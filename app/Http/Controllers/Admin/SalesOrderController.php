<?php

namespace App\Http\Controllers\Admin;
use App\Models\Admin\Unit;
use App\Models\User;
use App\Models\Admin\SubCategory;
use App\Models\Admin\Category;
use App\Models\Admin\SalesOrder;
use App\Models\Admin\MaterialCategory;
use App\Models\Admin\RawMaterial;
use App\Models\Admin\SalesOrderProducts;
use App\Models\Admin\Customer;
use App\Models\Admin\Product;
use Spatie\Permission\Models\Role;
use App\Models\Admin\Company;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Notification;
use App\Notifications\OffersNotification;
use Hash;
use Auth;
USE DB;
use View;
class SalesOrderController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:SalesOrder-index|SalesOrder-create|SalesOrder-show|SalesOrder-destroy', ['only' => ['index','show']]);
        $this->middleware('permission:SalesOrder-create', ['only' => ['create','store']]);
        $this->middleware('permission:SalesOrder-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:SalesOrder-destroy', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->can('SalesOrder-index')) {
            $SalesOrder = SalesOrder::join('customers', 'customers.id', '=', 'sales_order.customer_id')
            ->select('sales_order.*','customers.customer_name')
            ->orderBy('sales_order.id', 'DESC')
            ->get();
            return view('Admin.salesorder.index',compact('SalesOrder'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->can('SalesOrder-create')) {
            $Customer = Customer::select("customer_name", "id")->where('customer_status','Active')->get();          
            $Product = Product::where('status','Active')->whereNotNull('product_BOM')->get();
            $companyState = Company::pluck('state')->first();
            return view('Admin.salesorder.create',compact('Customer','Product','companyState'));
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
        if(auth()->user()->can('SalesOrder-store')) {

            $request->validate([
                'customer_id'=> 'required',
                'status'=> 'required',
            ]);
            
            $salesproduct = [];
            $rawqun = $request->rawqun??[];
            $rawprice = $request->rawprice??[];
            $rawamtwithtax = $request->rawamtwithtax??[];
            $rawamtwithouttax = $request->rawamtwithouttax??[];
            $rawtax = $request->rawtax??[];
            $rawtaxamount = $request->rawtaxamount??[];
            $rawdelivery = $request->rawdelivery??[];
            $rawids = $request->rawids??[];
            $orderUniqueCode = orderLastID();
            foreach($rawids as $k=>$v) {
                $salesproduct[] = [
                    'id'=>$k, 
                    'unique_id'=>unique_code(9),
                    'product_id'=>$v, 
                    'delivery_date'=>$rawdelivery[$k], 
                    'quantity'=>$rawqun[$k],
                    'invoice_remained_quantity'=>$rawqun[$k],
                    'base_price'=>$rawprice[$k],
                    'base_subtotal_withoutax'=>$rawamtwithouttax[$k],
                    'base_tax_per'=>$rawtax[$k],
                    'base_tax_amount'=>$rawtaxamount[$k],
                    'base_total'=>$rawamtwithtax[$k],
                    'status'=>'pending'
                ];
            }

            $data = [
                'customer_id'=> $request->customer_id,
                'code'=>$orderUniqueCode,
                'order_date'=>date('Y-m-d H:i:s'),
                'description'=>nl2br($request->description),
                'payment_terms'=>$request->payment_terms,
                'delivery_mode'=>$request->delivery_mode,
                'igst'=>$request->igst??0,
                'sgst'=>$request->sgst??0,
                'cgst'=>$request->cgst??0,
                'base_grandtotal'=>$request->txtgrandtotal,
                'base_subtotal'=>$request->txtsubtotal,
                'base_tax_amount'=>$request->txtordertax,
                'base_total_quantity'=>$request->txttotalquantity,
                'base_total_rate'=>$request->txttotalamount,
                'order_products'=>json_encode($salesproduct),
                'status'=>$request->status,
                'created_by'=>auth()->user()->id,
            ];

            $create = SalesOrder::create($data);

            $NotificationCODE = $orderUniqueCode;
            $NotificationMSG = 'added a new sales order';
            $NotificationURL = url()->current();
            SendCustomNotifications($NotificationMSG,$NotificationCODE,$NotificationURL);
            return redirect()->route('salesorder.index')->with('msg','SalesOrder created successfully.');
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
        if(auth()->user()->can('SalesOrder-show')) {
            $SalesOrder = SalesOrder::join('customers', 'customers.id', '=', 'sales_order.customer_id')
            ->join('countries', 'countries.id', '=', 'customers.country')
            ->join('cities', 'cities.id', '=', 'customers.city')
            ->join('states', 'states.id', '=', 'customers.state')
            ->select('sales_order.*','customers.customer_name','customers.customer_email','customers.customer_phone','customers.customer_street','customers.customer_zipcode','countries.name as countryname','cities.name as cityname','states.name as statename','customers.state as stateid')
            ->where('sales_order.id',$id)
            ->first();
            $companyState = Company::pluck('state')->first();
            return view('Admin.salesorder.show',compact('SalesOrder','companyState'));
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
        if(auth()->user()->can('SalesOrder-edit')) {
            $SalesOrder= SalesOrder::findOrFail($id);
            $Customer = Customer::select("customer_name", "id")->where('customer_status','Active')->get();
            $Product = Product::where('status','Active')->get();
            $companyState = Company::pluck('state')->first();
            return view('Admin.salesorder.create',compact('SalesOrder','Customer','Product','companyState'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(auth()->user()->can('SalesOrder-update')) {
            $request->validate([
                'customer_id'=> 'required',
                'status'=> 'required',
            ]);
            $salesproduct = [];
            $rawqun = $request->rawqun??[];
            $rawprice = $request->rawprice??[];
            $rawamtwithtax = $request->rawamtwithtax??[];
            $rawamtwithouttax = $request->rawamtwithouttax??[];
            $rawtax = $request->rawtax??[];
            $rawtaxamount = $request->rawtaxamount??[];
            $rawdelivery = $request->rawdelivery??[];
            $rawids = $request->rawids??[];

            foreach($rawids as $k=>$v) {
                $salesproduct[] = [
                    'id'=>$k, 
                    'unique_id'=>unique_code(9),
                    'product_id'=>$v, 
                    'delivery_date'=>$rawdelivery[$k], 
                    'quantity'=>$rawqun[$k],
                    'invoice_remained_quantity'=>$rawqun[$k],
                    'base_price'=>$rawprice[$k],
                    'base_subtotal_withoutax'=>$rawamtwithouttax[$k],
                    'base_tax_per'=>$rawtax[$k],
                    'base_tax_amount'=>$rawtaxamount[$k],
                    'base_total'=>$rawamtwithtax[$k],
                    'status'=>'pending'
                ];
            }

            $data = [
                'customer_id'=> $request->customer_id,
                'order_date'=>date('Y-m-d H:i:s'),
                'description'=>nl2br($request->description),
                'payment_terms'=>$request->payment_terms,
                'delivery_mode'=>$request->delivery_mode,
                'igst'=>$request->igst??0,
                'sgst'=>$request->sgst??0,
                'cgst'=>$request->cgst??0,
                'base_grandtotal'=>$request->txtgrandtotal,
                'base_subtotal'=>$request->txtsubtotal,
                'base_tax_amount'=>$request->txtordertax,
                'base_total_quantity'=>$request->txttotalquantity,
                'base_total_rate'=>$request->txttotalamount,
                'order_products'=>json_encode($salesproduct),
                'status'=>$request->status,
                'created_by'=>auth()->user()->id,
            ];

            SalesOrder::where('id',$id)->update($data);

            $NotificationCODE = SalesOrder::where('id',$id)->pluck('code')->first();
            $NotificationMSG = 'has updated sales order';
            $NotificationURL = url()->current();            
            SendCustomNotifications($NotificationMSG,$NotificationCODE,$NotificationURL);

            return redirect()->route('salesorder.index')->with('msg','SalesOrder Updated successfully.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(auth()->user()->can('SalesOrder-destroy')) {
            $NotificationCODE = SalesOrder::where('id',$request->id)->pluck('code')->first();
            $NotificationMSG = 'has deleted sales order';
            $NotificationURL = 'javascript:void(0)';            

            $SalesOrder = SalesOrder::find($request->id)->delete();

            SendCustomNotifications($NotificationMSG,$NotificationCODE,$NotificationURL);

            return response()->json(['msg'=>'SalesOrder deleted successfully.']);  
        }
    }
    public function Status(Request $request)
    {
        $id_array = $request->id;
        $flag= $request->value;
       
        $Roles = SalesOrder::whereIn('id',$id_array)->update(["status"=>$flag]);
       
        if($Roles == true)
        {
            return response()->json(['msg'=>1]);
        }
        else
        {
            return response()->json(['msg'=>0]);
        }
    }

    public function addProduct(Request $request) {
        $data['product'] = Product::where("id",$request->id)->get();
        return response()->json($data);
    }

    public function addToBOM(Request $request) {
        // dd($request->all());
        $pid = $request->pid;
        $qun = $request->qun;
        $divid = $request->divid;

        $tmpPro = Product::select("product_BOM", "id", "name")->where('id',$pid)->first();
        $productBOM = json_decode($tmpPro->product_BOM,true);
        $tmpBOM = [];

        $html = '<div class="table-responsive" id="'.$divid.'"  style="margin-bottom: 25px;">
                <div class="table-top"><h6> Product Name: '.$tmpPro->name.'</h6></div>
                 <table class="table datanew">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Name</th>
                            <th>INSTOCK QTY</th>
                            <th>REQUIRED QTY</th>
                            <th>Unit</th>
                        </tr>
                    </thead>
                    <tbody>';
        foreach($productBOM as $v2) {
            $rawm = RawMaterial::select('id','name','quantity')->where('id', $v2['id'])->get()->first();
            $unitname = Unit::where('id', $v2['unitid'])->pluck('unit_name')->first();
            $tmpRQ = $qun * $v2['quantity']; // Required Quantity
            $tmpAQ = $rawm->quantity;
            $tmpBQ = 0;
            if($tmpAQ < $tmpRQ) {
                $tmpBQ = $tmpRQ - $tmpAQ;
            }

            $tmpBOM[] = ['id'=>$v2['id'],'name'=>$rawm->name,'Buy_quantity'=>$tmpBQ,'Avbl_quantity'=>$tmpAQ,'Rqr_quantity'=>$tmpRQ,'unitid'=>$v2['unitid'],'unitname'=>$unitname];

            $html .= '<tr> <td>'.$v2['id'].'</td> <td>'.$rawm->name.'</td> <td>'.$tmpAQ.'</td>  <td>'.$tmpRQ.'</td><td>'.$unitname.'</td>';
        }
        $html .= '</tr> </tbody> </table> </div>';
        return response()->json(['data'=>$html]);
    }

    public function fetchCustomerAddress(Request $request) {
        $data['customer'] = Customer::join('countries', 'countries.id', '=', 'customers.country')
        ->join('cities', 'cities.id', '=', 'customers.city')
        ->join('states', 'states.id', '=', 'customers.state')
        ->where("customers.id",$request->id)
        ->select('customers.*','countries.name as countryname','cities.name as cityname','states.name as statename')
        ->get();
        return response()->json($data);
    }

    public function createJobCard($id) {
        $SalesOrder = SalesOrder::join('customers', 'customers.id', '=', 'sales_order.customer_id')
        ->join('countries', 'countries.id', '=', 'customers.country')
        ->join('cities', 'cities.id', '=', 'customers.state')
        ->join('states', 'states.id', '=', 'customers.city')
        ->select('sales_order.*','customers.customer_name','customers.customer_email','customers.customer_phone','customers.customer_street','customers.customer_zipcode','countries.name as countryname','cities.name as cityname','states.name as statename')
        ->where('sales_order.id',$id)
        ->first();
        return view('Admin.salesorder.jobcard',compact('SalesOrder'));
    }

}
