<?php

namespace App\Http\Controllers\Admin;
use App\Models\Admin\Unit;
use App\Models\Admin\RawMaterial;
use App\Models\Admin\RawMaterialStockActivity;
use App\Models\Admin\SalesOrderProducts;
use App\Models\Admin\Customer;
use App\Models\Admin\Vendor;
use App\Models\Admin\Product;
use App\Models\Admin\PurchaseOrder;
use App\Models\Admin\PurchaseMaterialReceived;
use App\Models\Admin\PurchaseOrderMaterials;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Auth;
USE DB;
USE Redirect;

class InventoryMaterialController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:PurchaseMaterialReceived-index|PurchaseMaterialReceived-create|PurchaseMaterialReceived-show|PurchaseMaterialReceived-destroy', ['only' => ['index','show']]);
        $this->middleware('permission:PurchaseMaterialReceived-create', ['only' => ['create','store']]);
        $this->middleware('permission:PurchaseMaterialReceived-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:PurchaseMaterialReceived-destroy', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->can('PurchaseMaterialReceived-index')) {
            $PurchaseOrder = PurchaseOrder::join('vendors', 'vendors.id', '=', 'purchase_order.vendor_id')
            ->select('purchase_order.*','vendors.vendor_name')
            ->orderBy('purchase_order.id', 'DESC')
            ->get();
            return view('Admin.materialinventory.index',compact('PurchaseOrder'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(auth()->user()->can('PurchaseMaterialReceived-create')) {
            $PurchaseOrder= PurchaseOrder::findOrFail($request->id);
            $PurchaseOrderMaterial= PurchaseOrderMaterials::where('purchase_order_id', $request->id)->get();
            return view('Admin.materialinventory.create',compact('PurchaseOrder','PurchaseOrderMaterial'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(auth()->user()->can('PurchaseMaterialReceived-store')) {
            $pmrdata = $request->pmrdata??[];
            if(!empty($pmrdata)) {
                foreach($pmrdata as $k1=>$v1) {                
                    if(!array_key_exists('chk',$v1)){
                        // Check if checkbox is not checked than removed array
                        unset($pmrdata[$k1]);
                    } else if($v['received_quantity'] <= 0){
                        unset($pmrdata[$k1]);
                    } else {
                        // Remove checkbox from data
                        unset($pmrdata[$k1]['chk']);
                        // Set status and date to array
                        $pmrdata[$k1]['status'] = "Complete";
                        $pmrdata[$k1]['date'] = date('Y-m-d H:i:s');
                    }
                }
                if(empty($pmrdata)) {
                    return Redirect::back()->withErrors(['msg' => 'Please select checkbox with at least 1 quantity !']);
                } else {
            
                    MaterialRequirementActivity("Purchase-Order-Receive-Activity",$pmrdata);        
                    foreach($pmrdata as $k=>$v) {

                        // Recieved material create
                        $create = PurchaseMaterialReceived::create($pmrdata[$k]);
                        
                        // Update remained quantity in purchase order
                        $tmp1 = PurchaseOrderMaterials::where('id', $v['purchase_order_material_id'])->first();
                        $Q = $tmp1['remained_quantity'] - $v['received_quantity'];

                        $PurchaseOrderMaterials = PurchaseOrderMaterials::find($v['purchase_order_material_id']);
                        $RQ = 0;
                        if($Q>0) {
                            $RQ = $Q; 
                        } else {
                            $PurchaseOrderMaterials->status="Complete";
                        }
                        $PurchaseOrderMaterials->remained_quantity = $RQ;
                        $PurchaseOrderMaterials->save();

                        // Update stock in raw material
                        $rawCurrentQun= RawMaterial::where('id', $v['raw_material_id'])->pluck('quantity')->first();
                        $receivedQun = $v['received_quantity'];
                        $rawTotalQun = $rawCurrentQun + $receivedQun;
                        RawMaterial::where('id', $v['raw_material_id'])->update(['quantity' => $rawTotalQun]);

                        // Update stock in stock activity
                        $createA = RawMaterialStockActivity::create([
                            'raw_id'=> $v['raw_material_id'],
                            'issue_id'=> null,
                            'oldstock'=> $rawCurrentQun,
                            'stockOut'=> 0,
                            'stockIn'=> $receivedQun,
                            'newstock'=> $rawTotalQun,
                            'createdBy'=> Auth::user()->id,
                            'stock_date'=> date('Y-m-d'),
                        ]);

                    }            

                    // Check if all material is recieved than update purchase order status
                    $tmppurchasematerialdata = PurchaseOrderMaterials::selectRaw('SUM(CASE WHEN status = "Complete" THEN 1 ELSE 0 END) AS completeraw')
                    ->selectRaw('COUNT(id) AS totalraw')
                    ->where('purchase_order_id', $v['purchase_order_id'])
                    ->first();
                    $completeraw = $tmppurchasematerialdata['completeraw'];
                    $totalraw = $tmppurchasematerialdata['totalraw'];
                    if($completeraw == $totalraw) {
                        PurchaseOrder::where('id', $request->purchaseOrderId)->update(['status' => 'Complete']);
                    } else{
                        PurchaseOrder::where('id', $request->purchaseOrderId)->update(['status' => 'Partial Recieve']);
                    }
                    return redirect()->route('materialinventory.index')->with('msg','Raw Material Received successfully.');
                }
            }
            return Redirect::back()->withErrors(['msg' => 'Please select checkbox with at least 1 quantity !']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(auth()->user()->can('PurchaseMaterialReceived-show')) {
            $PurchaseMaterialReceived= PurchaseMaterialReceived::where('purchase_order_id',$id)->get();
            $PurchaseOrder= PurchaseOrder::findOrFail($id);
            return view('Admin.materialinventory.show',compact('PurchaseMaterialReceived','PurchaseOrder'));
        }
    }

}
