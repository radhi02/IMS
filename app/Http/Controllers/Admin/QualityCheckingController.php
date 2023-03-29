<?php

namespace App\Http\Controllers\Admin;
use App\Models\Admin\RawMaterial;
use App\Models\Admin\Manufacture;
use App\Models\Admin\Issuematerial;
use App\Models\Admin\Consumption;
use App\Models\Admin\Product;
use App\Models\Admin\ProductStockActivity;
use App\Models\Admin\ProductQCActivity;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Auth;
USE DB;
use View;
use PDF;

class QualityCheckingController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ProductQCActivity-index|ProductQCActivity-create|ProductQCActivity-show|ProductQCActivity-destroy', ['only' => ['index','show']]);
        $this->middleware('permission:ProductQCActivity-create', ['only' => ['create','store']]);
        $this->middleware('permission:ProductQCActivity-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:ProductQCActivity-destroy', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->can('ProductQCActivity-index')) {
            $ManufactureOrder = Manufacture::join('sales_order', 'sales_order.id', '=', 'manufacture_order.sales_order_id')
            ->join('product', 'product.id', '=', 'manufacture_order.product_id')
            ->join('customers', 'customers.id', '=', 'sales_order.customer_id')
            ->select('sales_order.code as s_code','sales_order.id as s_id','sales_order.order_date as s_order_date','customers.customer_name as c_name','product.name as p_name','product.sku as p_sku','manufacture_order.*')
            ->where('manufacture_order.status','Finish')
            ->orwhere('manufacture_order.status','QCPending')
            ->orderBy('manufacture_order.id', 'DESC')
            ->get();
            return view('Admin.qualitychecking.index',compact('ManufactureOrder'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProductStock(Request $request)
    {        
        $QCData = ProductQCActivity::where([['mo_id','=',$request->mo_id],['product_id','=',$request->product_id],['status','=','Approve']])->first();
        
        $RejectedQCData = ProductQCActivity::where([['mo_id','=',$request->mo_id],['product_id','=',$request->product_id],['status','=','Reject']])->first();

        if(empty($QCData)) {
            $createA = ProductQCActivity::create([
                'product_id'=> $request->product_id,
                'mo_id'=> $request->mo_id,
                'quantity'=> $request->checkqun,
                'status'=>'Approve',
                'mo_status'=>'Pending',
                'reason'=> null
            ]);
        } else {
            $finalQ = $request->checkqun + $QCData->quantity;
            ProductQCActivity::where([['mo_id','=',$request->mo_id],['product_id','=',$request->product_id],['status','=','Approve']])->update(['quantity' => $finalQ]);
        }

        // if(!empty($RejectedQCData)){
        //     $approveQ = $RejectedQCData->quantity - $request->mo_id;
        //     ProductQCActivity::where([['mo_id','=',$request->mo_id],['product_id','=',$request->product_id],['status','=','Reject']])->update(['quantity' => $approveQ]);
        // }

        $QCData = ProductQCActivity::where([['mo_id','=',$request->mo_id],['product_id','=',$request->product_id]])->select(DB::raw('SUM(quantity) AS quantity'))->first();
        $QCQuantity = $QCData->quantity;

        $Mdata= Manufacture::where('id', $request->mo_id)->first();
        $MOQuantity = $Mdata->quantity;
        $MOproduct_id = $Mdata->product_id;
        $MOid = $Mdata->id;
        $MOsid = $Mdata->sales_order_id;
        
        if($QCQuantity == $MOQuantity) {

            // Get Approved quantity value
            $MOApproveQuantity= ProductQCActivity::where([['mo_id','=',$request->mo_id],['product_id','=',$request->product_id],['status','=','Approve']])->pluck('quantity')->first();

            // Update product stock
            $product = Product::findOrFail($MOproduct_id);
            $product->quantity += $MOApproveQuantity;
            $product->save();
    
            // Update product stock activity
            $createA = ProductStockActivity::create([
                'product_id'=> $MOproduct_id,
                'mo_id'=> $MOid,
                'so_id'=> $MOsid,
                'oldstock'=> $product->quantity,
                'stockOut'=> 0,
                'stockIn'=> $MOApproveQuantity,
                'newstock'=> $product->quantity + $MOApproveQuantity,
                'createdBy'=> Auth::user()->id,
                'stock_date'=> date('Y-m-d'),
            ]);

            // Get Rejected quantity value
            $MORejectedQuantity= ProductQCActivity::where([['mo_id','=',$request->mo_id],['product_id','=',$request->product_id],['status','=','Reject']])->pluck('quantity')->first();

            if($MORejectedQuantity != null) {
                Manufacture::where('id',$MOid)->update(["status"=>"Inprocess","demand_status"=>"Not Started","issue_status"=>"Not Started","consume_status"=>"Not Started",'check_quantity'=>$MORejectedQuantity,'rejected_quantity'=>0]);
                ProductQCActivity::where([['id','=',$MOid],['status','=','Reject']])->update(['mo_status'=>'Pending','quantity'=>0]);
            } else {
                Manufacture::where('id',$MOid)->update(["status"=>"Instore",'check_quantity'=>0,'rejected_quantity'=>0]);
                ProductQCActivity::where([['id','=',$MOid],['status','=','Reject']])->update(["quantity"=>0,"mo_status"=>"Instore"]);
            }
        } else {
            $TMPMO = Manufacture::findOrFail($MOid);
            $TMPMO->check_quantity = $TMPMO->check_quantity - $request->checkqun;
            $TMPMO->status = "QCPending";
            $TMPMO->save();
        }

        return redirect()->route('qualitychecking.index')->with('msg','You have approved product successfully.');
    }

    public function store(Request $request)
    {
        $QCData = ProductQCActivity::where([['mo_id','=',$request->modal_mo_id],['product_id','=',$request->modal_product_id],['status','=','Reject']])->first();

        $reasonArray = [];
        if(empty($QCData)) {
            $reasonArray[] = ['reason'=>$request->modal_reason,'created'=>date('Y-m-d H:i:s'),'quantity'=>$request->modal_quantity];
            $createA = ProductQCActivity::create([
                'product_id'=> $request->modal_product_id,
                'mo_id'=> $request->modal_mo_id,
                'quantity'=> $request->modal_quantity,
                'status'=>'Reject',
                'mo_status'=>'Pending',
                'reason'=> json_encode($reasonArray)
            ]);
        } else {
            $finalQ = $request->modal_quantity + $QCData->quantity;
            $reasonArray = json_decode($QCData->reason,true);
            $reasonArray[] = ['reason'=>$request->modal_reason,'created'=>date('Y-m-d H:i:s'),'quantity'=>$finalQ];
            ProductQCActivity::where([['mo_id','=',$request->modal_mo_id],['product_id','=',$request->modal_product_id],['status','=','Reject']])->update(['quantity' => $finalQ,'reason'=>$reasonArray]);
        }

        $QCData = ProductQCActivity::where([['mo_id','=',$request->modal_mo_id],['product_id','=',$request->modal_product_id]])->select(DB::raw('SUM(quantity) AS quantity'))->first();
        $QCQuantity = $QCData->quantity;
        
        $Mdata= Manufacture::where('id', $request->modal_mo_id)->first();
        $MOQuantity = $Mdata->quantity;
        $MOproduct_id = $Mdata->product_id;
        $MOid = $Mdata->id;
        $MOsid = $Mdata->sales_order_id;
        
        if($QCQuantity == $MOQuantity) {

            // Get Approved quantity value
            $MOApproveQuantity= ProductQCActivity::where([['mo_id','=',$request->modal_mo_id],['product_id','=',$request->modal_product_id],['status','=','Approve']])->pluck('quantity')->first();

            // Update product stock
            $product = Product::findOrFail($MOproduct_id);
            $product->quantity += $MOApproveQuantity;
            $product->save();
    
            // Update product stock activity
            $createA = ProductStockActivity::create([
                'product_id'=> $MOproduct_id,
                'mo_id'=> $MOid,
                'so_id'=> $MOsid,
                'oldstock'=> $product->quantity,
                'stockOut'=> 0,
                'stockIn'=> $MOApproveQuantity,
                'newstock'=> $product->quantity + $MOApproveQuantity,
                'createdBy'=> Auth::user()->id,
                'stock_date'=> date('Y-m-d'),
            ]);

            // Get Rejected quantity value
            $MORejectedQuantity= ProductQCActivity::where([['mo_id','=',$request->modal_mo_id],['product_id','=',$request->modal_product_id],['status','=','Reject']])->pluck('quantity')->first();

            if($MORejectedQuantity != null) {
                Manufacture::where('id',$MOid)->update(["status"=>"Inprocess","demand_status"=>"Not Started","issue_status"=>"Not Started","consume_status"=>"Not Started",'check_quantity'=>$MORejectedQuantity,'rejected_quantity'=>0]);
                ProductQCActivity::where([['mo_id','=',$request->modal_mo_id],["product_id","=",$request->modal_product_id],["status","=","Reject"]])->update(["quantity"=>0,"mo_status"=>"Pending"]);
            } else {
                Manufacture::where('id',$MOid)->update(["status"=>"Instore","check_quantity"=>0,"rejected_quantity"=>0]);
                ProductQCActivity::where([['mo_id','=',$request->modal_mo_id],["product_id","=",$request->modal_product_id],["status","=","Reject"]])->update(["quantity"=>0,"mo_status"=>"Instore"]);
            }
        } else {
            $TMPMO = Manufacture::findOrFail($MOid);
            $TMPMO->check_quantity = $TMPMO->check_quantity - $request->modal_quantity;
            $TMPMO->rejected_quantity = $TMPMO->rejected_quantity + $request->modal_quantity;
            $TMPMO->status = "QCPending";
            $TMPMO->save();
        }

        return redirect()->route('qualitychecking.index')->with('msg','You have rejected product successfully.');
    }

}
