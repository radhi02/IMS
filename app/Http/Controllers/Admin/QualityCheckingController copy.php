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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ManufactureOrder = Manufacture::join('sales_order', 'sales_order.id', '=', 'manufacture_order.sales_order_id')
        ->join('product', 'product.id', '=', 'manufacture_order.product_id')
        ->join('customers', 'customers.id', '=', 'sales_order.customer_id')
        ->select('sales_order.code as s_code','sales_order.id as s_id','sales_order.order_date as s_order_date','customers.customer_name as c_name','product.name as p_name','product.sku as p_sku','manufacture_order.*')
        ->where('manufacture_order.status','Finish')
        ->orwhere('manufacture_order.status','QCPending')
        ->get();
        return view('Admin.qualitychecking.index',compact('ManufactureOrder'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProductStock(Request $request)
    {
        $createA = ProductQCActivity::create([
            'product_id'=> $request->product_id,
            'mo_id'=> $request->mo_id,
            'quantity'=> $request->checkqun,
            'status'=>'Approve',
            'mo_status'=>'Pending',
            'reason'=> null
        ]);

        $ManufactureOrder = Manufacture::findOrFail($request->mo_id);
        $ManufactureOrder->approved_quantity += $request->checkqun;
        $ManufactureOrder->save();

        $QCData = ProductQCActivity::where("mo_id",$request->modal_mo_id)->select(DB::raw('SUM(quantity) AS quantity'))->get();
        $QCQuantity = $QCData->quantity;
        $Mdata= Manufacture::where('id', $request->mo_id)->first();
        $MOQuantity = $Mdata->quantity;
        $MOApproveQuantity = $Mdata->approved_quantity;
        $MOproduct_id = $Mdata->product_id;
        $MOid = $Mdata->id;
        $MOsid = $Mdata->sales_order_id;

        if($QCQuantity == $MOQuantity) {

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

            Manufacture::where('id',$MOid)->update(["status"=>"Instore"]);
        } else {
            Manufacture::where('id',$MOid)->update(["status"=>"QCPending"]);
        }        
        
        return redirect()->route('qualitychecking.index')->with('msg','You have approved product.');
    }

    public function store(Request $request)
    {
        $createA = ProductQCActivity::create([
            'product_id'=> $request->modal_product_id,
            'mo_id'=> $request->modal_mo_id,
            'quantity'=> $request->modal_quantity,
            'status'=>'Reject',
            'mo_status'=>'Pending',
            'reason'=> $request->modal_reason
        ]);

        $ManufactureOrder = Manufacture::findOrFail($request->modal_mo_id);
        $ManufactureOrder->rejected_quantity += $request->modal_quantity;
        $ManufactureOrder->save();

        $QCData = ProductQCActivity::where("mo_id",$request->modal_mo_id)->select(DB::raw('SUM(quantity) AS quantity'))->get();
        $QCQuantity = $QCData->quantity;
        $todayStockActivity['RawMaterial'] = DB::table('rawmaterial_stock_activity')
        ->groupBy('raw_id')
        ->whereDate('created_at', Carbon::today())
        ->get(['raw_id', DB::raw('SUM(stockIn) AS stockIn'), DB::raw('SUM(stockOut) AS stockOut')]);
  
        $Mdata = Manufacture::where('id', $request->modal_mo_id)->first();
        $MOQuantity = $Mdata->quantity;
        $MOApproveQuantity = $Mdata->approved_quantity;
        $MOproduct_id = $Mdata->product_id;
        $MOid = $Mdata->id;
        $MOsid = $Mdata->sales_order_id;


        if($QCQuantity == $MOQuantity) {

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
            Manufacture::where('id',$request->modal_mo_id)->update(["status"=>"Inprocess"]);
        } else {
            Manufacture::where('id',$request->modal_mo_id)->update(["status"=>"QCPending"]);
        }

        return redirect()->route('qualitychecking.index')->with('msg','Material is consumed for manufacturing order.');
    }

}
