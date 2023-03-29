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
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Auth;
USE DB;
use View;
class ManufactureController extends Controller
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
            $ManufactureOrder = Manufacture::join('sales_order', 'sales_order.id', '=', 'manufacture_order.sales_order_id')
            ->join('product', 'product.id', '=', 'manufacture_order.product_id')
            ->join('customers', 'customers.id', '=', 'sales_order.customer_id')
            ->select('sales_order.code as s_code','sales_order.id as s_id','sales_order.order_date as s_order_date','customers.customer_name as c_name','product.name as p_name','product.sku as p_sku','manufacture_order.*')
            ->orderBy('manufacture_order.id', 'DESC')
            ->get();
            return view('Admin.manufacture.index',compact('ManufactureOrder'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(auth()->user()->can('Manufacture-create')) {
            $SalesOrder = SalesOrder::join('customers', 'customers.id', '=', 'sales_order.customer_id')
            ->join('countries', 'countries.id', '=', 'customers.country')
            ->join('cities', 'cities.id', '=', 'customers.city')
            ->join('states', 'states.id', '=', 'customers.state')
            ->select('sales_order.*','customers.customer_name','customers.customer_email','customers.customer_phone','customers.customer_street','customers.customer_zipcode','countries.name as countryname','cities.name as cityname','states.name as statename')
            ->where('sales_order.id',$request->id)
            ->first();
            if($SalesOrder == null || empty($SalesOrder) ) {
                return view('Admin.layout.notfound');
            }
            return view('Admin.manufacture.create',compact('SalesOrder'));
        }   
    }

    public function makeListing(Request $request){
        $ManufactureOrder['order'] = Manufacture::where('product_id',$request->pid)->where('uniqueid',$request->unique)->get();
        $ManufactureOrder['product'] = Product::where('id', $request->pid)->pluck('name')->first();
        return response()->json($ManufactureOrder);  
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(auth()->user()->can('Manufacture-store')) {
            $request->validate([
                'rawqun'=> 'required',
                'rawdelivery'=> 'required',
                'sales_order_id'=> 'required',
                'product_id'=> 'required',
            ]);
            
            $salesproduct = [];
            $rawqun = $request->rawqun??[];
            $rawdelivery = $request->rawdelivery??[];
            $rawids = $request->rawids??[];
            foreach($rawqun as $k=>$v) {
                $tmpPro = Product::select("product_BOM", "id")->where('id',$request->product_id)->first();
                $productBOM = json_decode($tmpPro->product_BOM,true);
                $finalBOM = [];
                foreach($productBOM as $v2) {
                    $tmpRQ = $rawqun[$k] * $v2['quantity']; // Required Quantity
                    $tmpSum = (isset($finalBOM[$v2['id']]['quantity']))?$finalBOM[$v2['id']]['quantity']:0;
                    $finalBOM[$v2['id']] = [
                        'id'=>$v2['id'],
                        'quantity'=> $tmpSum + $tmpRQ,
                        'remained_quantity'=> $tmpSum + $tmpRQ,
                        'unitid'=>$v2['unitid']
                    ];
                }

                if($rawids[$k] != null) {
                    $update = Manufacture::where('id',$rawids[$k])->update([
                        'delivery_date'=>$rawdelivery[$k], 
                        'quantity'=>$rawqun[$k],
                        'bom_detail'=>json_encode($finalBOM)
                    ]);
                } else {
                    $last = DB::table('manufacture_order')->where('sales_order_id',$request->sales_order_id)->get()->count();
                    $get_perfectLast_id = $last + 1;
                    $get_perfectLast_id = str_pad($get_perfectLast_id, 2, '0', STR_PAD_LEFT);
                    
                    $create = Manufacture::create([
                        'code'=>saleForManuLastID($request->sales_order_id).'/'.$get_perfectLast_id, 
                        'uniqueid'=>$request->uniqueid, 
                        'sales_order_id'=>$request->sales_order_id, 
                        'product_id'=>$request->product_id, 
                        'delivery_date'=>$rawdelivery[$k], 
                        'quantity'=>$rawqun[$k],
                        'check_quantity'=>$rawqun[$k],
                        'bom_detail'=>json_encode($finalBOM),
                        'demand_status'=>'Not Started',
                        'issue_status'=>'Not Started',
                        'consume_status'=>'Not Started'
                    ]);
                }
            }
            return redirect()->route('manufacture.index')->with('msg','Product is added for manufacturing.');
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
        if(auth()->user()->can('Manufacture-show')) {
            $ManufactureOrder= Manufacture::findOrFail($id);
            return view('Admin.manufacture.show',compact('ManufactureOrder'));
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(auth()->user()->can('Manufacture-edit')) {
            $SalesOrder= SalesOrder::findOrFail($id);
            $Customer = Customer::select("customer_name", "id")->where('customer_status','Active')->get();
            $Product = Product::where('status','Active')->get();
            return view('Admin.manufacture.create',compact('SalesOrder','Customer','Product'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(auth()->user()->can('Manufacture-destroy')) {
            $ManufactureOrder = Manufacture::find($id)->delete();
            return response()->json(['msg'=>'Order deleted successfully.']);  
        }
    }
}
