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
use App\Models\Admin\Company;
use App\Models\Admin\Product;
use App\Models\Admin\ProductStockActivity;
use App\Models\Admin\InvoiceOrder;
use App\Models\Admin\InvoicePayment;
use App\Models\Banks;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Auth;
USE DB;
use View;
use PDF;
class InvoiceController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:InvoiceOrder-index|InvoiceOrder-create|InvoiceOrder-show|InvoiceOrder-destroy', ['only' => ['index','show']]);
        $this->middleware('permission:InvoiceOrder-create', ['only' => ['create','store']]);
        $this->middleware('permission:InvoiceOrder-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:InvoiceOrder-destroy', ['only' => ['destroy']]);
        $this->middleware('permission:InvoicePayment-index|InvoicePayment-create|InvoicePayment-show|InvoicePayment-destroy', ['only' => ['index','show']]);
        $this->middleware('permission:InvoicePayment-create', ['only' => ['create','store']]);
        $this->middleware('permission:InvoicePayment-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:InvoicePayment-destroy', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->can('InvoiceOrder-index')) {
            $Invoicedata = InvoiceOrder::join('sales_order', 'sales_order.id', '=', 'invoice_order.so_id')
            ->join('customers', 'customers.id', '=', 'invoice_order.customer_id')
            ->select('invoice_order.*','customers.customer_name as customer_name','sales_order.code as scode')
            ->orderBy('invoice_order.id', 'DESC')
            ->get();
            return view('Admin.invoice.index',compact('Invoicedata'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(auth()->user()->can('InvoiceOrder-create')) {
            $Customer = Customer::select("customer_name", "id")->where('customer_status','Active')->get();
            return view('Admin.invoice.create',compact('Customer'));
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
        if(auth()->user()->can('InvoiceOrder-show')) {
            $Paymentdata = InvoicePayment::leftjoin('users', 'users.id', '=', 'invoice_payment.created_by')->select('invoice_payment.*','users.first_name','users.last_name')->where('invoice_payment.invoice_id',$id)->get();
            $InvoiceData = InvoiceOrder::where('id',$id)->first();
            return view('Admin.invoice.show',compact('Paymentdata','InvoiceData'));
        }
    }

    public function fetchSalesOrderList(Request $request)
    {
        $data['orderlist'] = SalesOrder::where("customer_id",$request->id)->get(["code", "id"]);
        return response()->json($data);
    }

    public function fetchSalesOrderData(Request $request)
    {
        $SalesOrder = SalesOrder::join('customers', 'customers.id', '=', 'sales_order.customer_id')
        ->join('countries', 'countries.id', '=', 'customers.country')
        ->join('cities', 'cities.id', '=', 'customers.city')
        ->join('states', 'states.id', '=', 'customers.state')
        ->select('sales_order.*','customers.customer_name','customers.customer_email','customers.customer_phone','customers.customer_street','customers.customer_zipcode','countries.name as countryname','cities.name as cityname','states.name as statename','customers.state as stateid')
        ->where('sales_order.id',$request->id)
        ->first();
        $companyState = Company::pluck('state')->first();
        return View::make("Admin.invoice.loadsalesorder")->with(["SalesOrder"=>$SalesOrder,"companyState"=>$companyState])->render();
    } 

    public function fetchProductQun(Request $request){
        $AvailableQ = DB::table('product')->where('id',$request->pid)->pluck('quantity')->first();
        $RemainedQ = $request->qun;
        $orderedQ = $request->orderq;
        $maxQ = ($RemainedQ>$AvailableQ)?$AvailableQ:$RemainedQ;
        if($RemainedQ > $orderedQ) {
            return response()->json(['msg'=>2,'msgtext'=>" Insufficient Quantity ! Ordered Quantity is ".$orderedQ]);
        }
        if($RemainedQ > $AvailableQ) {
            return response()->json(['msg'=>2,'msgtext'=>" Insufficient Quantity !  Instock Quantity is ".$AvailableQ]); 
        } 
        return response()->json(['msg'=>1]); //true
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        
        if(auth()->user()->can('InvoiceOrder-store')) {
            $salesproduct = [];
            $invoicedata = $request->invoicedata??[];
            $ProQunArray = [];

            if($invoicedata != null) {
                foreach($invoicedata as $k=>$v){
                    if(!array_key_exists('chk',$v)){
                        unset($invoicedata[$k]);
                    } else {
                        $ProQunArray[$v['product_id']] = $v['quantity'];
                    }
                    unset($invoicedata[$k]['chk']);
                }
                $order_products = array_values($invoicedata);

                if(!empty($order_products)){
                    
                    $SalesOrder = DB::table('sales_order')->where('id',$request->sales_order_id)->first();
                    $SOP = json_decode($SalesOrder->order_products,true);

                    foreach($SOP as $opkey => $opval){
                        if(array_key_exists($opval['product_id'],$ProQunArray)) {
                            $SOP[$opkey]['invoice_remained_quantity'] = $SOP[$opkey]['invoice_remained_quantity'] - $ProQunArray[$opval['product_id']];
                        }
                    }
                    foreach($ProQunArray as $k1=>$v1){
                        // Update product stock
                        $product = Product::findOrFail($k1);
                        if($product != null) {
                            $product->quantity = $product->quantity - $v1;
                            $product->save();
                            
                            // Update product stock activity
                            $createA = ProductStockActivity::create([
                                'product_id'=> $k1,
                                'mo_id'=> null,
                                'so_id'=> $request->sales_order_id,
                                'oldstock'=> $product->quantity,
                                'stockOut'=> $v1,
                                'stockIn'=> 0,
                                'newstock'=> $product->quantity - $v1,
                                'createdBy'=> Auth::user()->id,
                                'stock_date'=> date('Y-m-d'),
                            ]);
                        }
                    }

                    SalesOrder::where('id',$request->sales_order_id)->update(['order_products'=> json_encode($SOP)]);
                    $data = [
                        'so_id'=> $request->sales_order_id,
                        'customer_id'=> $request->customer_id,
                        'code'=> InvoiceLastID(),
                        'order_products'=> json_encode($order_products),
                        'igst'=>$request->igst??0,
                        'sgst'=>$request->sgst??0,
                        'cgst'=>$request->cgst??0,
                        'base_grandtotal'=>$request->txtgrandtotal,
                        'base_subtotal'=>$request->txtsubtotal,
                        'base_tax_amount'=>$request->txtordertax,
                        'base_total_quantity'=>$request->txttotalquantity,
                        'base_total_rate'=>$request->txttotalamount,
                        'due_amount'=>$request->txtgrandtotal,
                        'receivable_amount'=>$request->txtgrandtotal,
                        'due_date'=> date('Y-m-d H:i:s'),
                        'status'=>'Open',
                        'created_by'=>auth()->user()->id,
                    ];
                    $create = InvoiceOrder::create($data);
                }
            }
            return redirect()->route('invoice.index')->with('msg','Invoice created successfully.');
        }
    }

    
    // Download Demand Note
    public function downloadInvoice($id)
    {
        if(auth()->user()->can('InvoiceOrder-index')) {

            $InvoiceOrder = InvoiceOrder::join('customers', 'customers.id', '=', 'invoice_order.customer_id')
            ->join('countries', 'countries.id', '=', 'customers.country')
            ->join('cities', 'cities.id', '=', 'customers.city')
            ->join('states', 'states.id', '=', 'customers.state')
            ->join('sales_order', 'sales_order.id', '=', 'invoice_order.so_id')
            ->select('invoice_order.*','customers.customer_name','customers.customer_email','customers.customer_phone','customers.customer_street','customers.customer_zipcode','customers.customer_GST','customers.customer_PAN','countries.name as countryname','countries.name as countryname','cities.name as cityname','states.name as statename','states.name as stateid','sales_order.description as paymentterms')
            ->where('invoice_order.id',$id)
            ->first();

            $CompanyDetails = Company::join('countries', 'countries.id', '=', 'companies.country')
            ->join('cities', 'cities.id', '=', 'companies.city')
            ->join('states', 'states.id', '=', 'companies.state')
            ->select('states', 'states.id', '=', 'companies.state')
            ->select('companies.*','countries.name as country','cities.name as city','states.name as state')
            ->first();

            $BankDetails = Banks::where('status', 'Active')->first();
            $data = array(
                'InvoiceOrder' => $InvoiceOrder,
                'CompanyDetails' => $CompanyDetails,
                'BankDetails' => $BankDetails,
            );

            // To check pdf on page
            // view()->share('InvoiceOrder',$InvoiceOrder);
            // return view('Admin.invoice.download',compact('InvoiceOrder','CompanyDetails','BankDetails'));  

            // TO download pdf
            $pdf = PDF::loadView('Admin/invoice/download_s',compact('InvoiceOrder','CompanyDetails','BankDetails'))->setOptions(['defaultFont' => 'sans-serif']);;
            $pdf->getDOMpdf()->set_option('isPhpEnabled',true);
            return $pdf->stream($InvoiceOrder['code'].'.pdf');
        }
        // return $pdf->stream($InvoiceOrder['code'].'.pdf');

        // return $pdf->download($InvoiceOrder['code'].'.pdf');
    }
    
    public function createInvoicePayment($id){
        if(auth()->user()->can('InvoicePayment-create')) {
            $Paymentdata = InvoicePayment::leftjoin('users', 'users.id', '=', 'invoice_payment.created_by')
            ->leftjoin('banks', 'banks.id', '=', 'invoice_payment.company_bank_id')
            ->select('invoice_payment.*','users.first_name','users.last_name','banks.BName')
            ->where('invoice_payment.invoice_id',$id)->get();

            $InvoiceData = InvoiceOrder::leftjoin('sales_order', 'sales_order.id', '=', 'invoice_order.so_id')
            ->leftjoin('customers', 'customers.id', '=', 'sales_order.customer_id')
            ->leftjoin('countries', 'countries.id', '=', 'customers.country')
            ->leftjoin('cities', 'cities.id', '=', 'customers.city')
            ->leftjoin('states', 'states.id', '=', 'customers.state')
            ->select('invoice_order.*','customers.customer_name','customers.customer_email','customers.customer_phone','customers.customer_street','customers.customer_zipcode','countries.name as countryname','cities.name as cityname','states.name as statename','states.name as stateid','sales_order.order_date as sales_order_date','sales_order.code as scode')
            ->where('invoice_order.id',$id)->first();
            
            $bank = Banks::where('deleted_at',null)->get();
            return view('Admin.invoice.getPayment',compact('InvoiceData','Paymentdata','bank'));
        }
    }

    public function storeInvoicePayment(Request $request){
        if(auth()->user()->can('InvoicePayment-store')) {
            $dueAmt = InvoiceOrder::where('id',$request->invoice_id)->pluck('due_amount')->first();
            $request->validate([
                'invoice_id' => 'required',
                'company_bank_id' => 'required',
                'received_amount'=> 'required|numeric|max:'.$dueAmt,
                'bank_details'=> 'required',
                'reference_no'=> 'required'
            ]);
            $received_amount = number_format($request->received_amount, 2);

            if($dueAmt != null) {
                if($dueAmt == $received_amount) {
                    $status = "Close";
                } else if($received_amount < $dueAmt) {
                    $status = "Partial Paid";
                }
                
                $InvoiceOrder = InvoiceOrder::findOrFail($request->invoice_id);
                if($dueAmt != null) {
                    $InvoiceOrder->due_amount = $InvoiceOrder->due_amount - $received_amount;
                    $InvoiceOrder->status = $status;
                    $InvoiceOrder->save();
                }
                
                $createA = InvoicePayment::create([
                    'invoice_id'=> $request->invoice_id,
                    'company_bank_id'=> $request->company_bank_id,
                    'received_amount'=> $received_amount,
                    'bank_details'=> $request->bank_details,
                    'reference_no'=> $request->reference_no,
                    'created_by'=> auth()->user()->id,
                ]);
            }
            return redirect()->route('invoice.index')->with('msg','Payment Recieved successfully.');
        }
    }
}
