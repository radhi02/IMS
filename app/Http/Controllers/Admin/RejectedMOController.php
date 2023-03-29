<?php

namespace App\Http\Controllers\Admin;
use App\Models\Admin\Unit;
use App\Models\Admin\SubCategory;
use App\Models\Admin\Category;
use App\Models\Admin\SalesOrder;
use App\Models\Admin\MaterialCategory;
use App\Models\Admin\RawMaterial;
use App\Models\Admin\SalesOrderProducts;
use App\Models\Admin\Customer;
use App\Models\Admin\Product;
use App\Models\Admin\Manufacture;
use App\Models\Admin\ProductQCActivity;
use App\Models\Admin\Issuematerial;
use App\Models\Admin\Consumption;
use App\Models\Admin\ProductStockActivity;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Auth;
USE DB;
use View;
class RejectedMOController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:Manufacture-index|Manufacture-create|Manufacture-show|Manufacture-destroy', ['only' => ['index','show']]);
        $this->middleware('permission:Manufacture-create', ['only' => ['create','store']]);
        $this->middleware('permission:Manufacture-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:Manufacture-destroy', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->can('Manufacture-index')) {
            $ManufactureOrder = Manufacture::join('product_qc_activity', 'product_qc_activity.mo_id', '=', 'manufacture_order.id')
            ->join('product', 'product.id', '=', 'product_qc_activity.product_id')
            ->select('manufacture_order.code as code','manufacture_order.id as moid','manufacture_order.sales_order_id as s_id','manufacture_order.delivery_date as delivery_date','manufacture_order.check_quantity as check_quantity','product_qc_activity.*','product.name as p_name','product.sku as p_sku')
            ->where('manufacture_order.status','Inprocess')
            ->where('product_qc_activity.status','Reject')
            ->orderBy('manufacture_order.id', 'DESC')
            ->get();
            return view('Admin.rejectedmo.index',compact('ManufactureOrder'));
        }
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function approveProduct(Request $request)
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
        //     $approveQ = $RejectedQCData->quantity - $request->checkqun;
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
            $TMPMO->save();
        }

        return redirect()->route('rejectedmo.index')->with('msg','You have approved product successfully.');
    }
}
