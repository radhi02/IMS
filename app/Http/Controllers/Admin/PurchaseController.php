<?php

namespace App\Http\Controllers\Admin;
use App\Models\Admin\Unit;
use App\Models\Admin\RawMaterial;
use App\Models\Admin\SalesOrderProducts;
use App\Models\Admin\Customer;
use App\Models\Admin\Vendor;
use App\Models\Admin\Product;
use App\Models\Admin\PurchaseOrder;
use App\Models\Admin\PurchaseMaterialReceived;
use App\Models\Admin\PurchaseOrderMaterials;
use App\Models\Admin\MaterialRequirement;
use App\Models\Admin\Company;
use App\Models\Banks;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Auth;
USE DB;
use PDF;

class PurchaseController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:PurchaseOrder-index|PurchaseOrder-create|PurchaseOrder-show|PurchaseOrder-destroy', ['only' => ['index','show']]);
        $this->middleware('permission:PurchaseOrder-create', ['only' => ['create','store']]);
        $this->middleware('permission:PurchaseOrder-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:PurchaseOrder-destroy', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->can('PurchaseOrder-index')) {
            $PurchaseOrder = PurchaseOrder::join('vendors', 'vendors.id', '=', 'purchase_order.vendor_id')
            ->select('purchase_order.*','vendors.vendor_name')
            ->orderBy('purchase_order.id', 'DESC')
            ->get();
            return view('Admin.purchase.index',compact('PurchaseOrder'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->can('PurchaseOrder-create')) {
            $Vendor = Vendor::select("vendor_name", "id")->where('vendor_status','Active')->get();
            $Rawmaterial = Rawmaterial::where('status','Active')->get();
            $companyState = Company::pluck('state')->first();
            return view('Admin.purchase.create',compact('Vendor','Rawmaterial','companyState'));
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
        if(auth()->user()->can('PurchaseOrder-store')) {
            $request->validate([
                'vendor_id'=> 'required',
                'status'=> 'required',
            ]);

            $purchasematerial = $request->purchasematerial;
            if(!empty($purchasematerial)) {            

                $data = [
                    'code'=>purchaseLastID(),
                    'vendor_id'=> $request->vendor_id,
                    'date'=>date('Y-m-d H:i:s'),
                    'description'=>$request->description,
                    'base_subtotal'=>$request->txtsubtotal,
                    'base_tax_amount'=>$request->txtordertax,
                    'base_grandtotal'=>$request->txtgrandtotal,
                    'status'=>"Pending",
                    'created_by'=>auth()->user()->id,
                ];
                
                $create = PurchaseOrder::create($data);
                $insertedId = $create->id;
                
                MaterialRequirementActivity("Purchase-Order-Activity",$purchasematerial);        

                foreach($purchasematerial as $pm1){
                    $pmdata = [
                        'purchase_order_id'=>$insertedId,
                        'raw_material_id'=> $pm1['rawId'],
                        'quantity'=>$pm1['rawqun'],
                        'remained_quantity'=>$pm1['rawqun'],
                        'tax_percentage'=>$pm1['rawtax'],
                        'base_price'=>$pm1['rawprice'],
                        'base_tax'=>$pm1['hiddentax'],
                        'base_subtotal'=>$pm1['hiddensubtotal'],
                        'base_total'=>$pm1['hiddentotal'],
                        'status'=>"Pending",
                    ];
                    
                    $create = PurchaseOrderMaterials::create($pmdata);
                }
                return redirect()->route('purchase.index')->with('msg','Purchase Order created successfully.');
            }
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
        if(auth()->user()->can('PurchaseOrder-show')) {
            $PurchaseOrder = PurchaseOrder::join('vendors', 'vendors.id', '=', 'purchase_order.vendor_id')
            ->join('countries', 'countries.id', '=', 'vendors.country')
            ->join('cities', 'cities.id', '=', 'vendors.city')
            ->join('states', 'states.id', '=', 'vendors.state')
            ->select('purchase_order.*','vendors.vendor_name','vendors.vendor_email','vendors.vendor_phone','vendors.vendor_street','vendors.vendor_zipcode','countries.name as countryname','cities.name as cityname','states.name as statename','states.id as stateid')
            ->where('purchase_order.id',$id)
            ->first();
            $companyState = Company::pluck('state')->first();
            $PurchaseOrderMaterials = PurchaseOrderMaterials::where('purchase_order_id',$id)->get();
            return view('Admin.purchase.show',compact('PurchaseOrder','PurchaseOrderMaterials','companyState'));
        }
    }
    
    public function fetchVendorAddress(Request $request) {
        $data['vendor'] = Vendor::join('countries', 'countries.id', '=', 'vendors.country')
        ->join('cities', 'cities.id', '=', 'vendors.city')
        ->join('states', 'states.id', '=', 'vendors.state')
        ->where("vendors.id",$request->id)
        ->select('vendors.*','countries.name as countryname','cities.name as cityname','states.name as statename')
        ->get();
        return response()->json($data);
    }
    
    public function fetchMaterialRequirement(Request $request) {
        $RequirementData = MaterialRequirement::where('raw_id', $request->id)->first();
        $quantity = RawMaterial::where('id', $request->id)->pluck('quantity')->first();
        if($RequirementData != null) {
            $resposneData = '<ul class="list-unstyled mb-0">
                <li> <span style="color: #7367F0;">Current Stock : </span> '.$quantity.'</li>
                <li> <span style="color: #7367F0;">Requirement : </span> '.$RequirementData->requirement.'</li>
                <li> <span style="color: #7367F0;">Open PO : </span> '.$RequirementData->pending_po.'</li>
                <li> <span style="color: #7367F0;">PO to be placed : </span> '.$RequirementData->new_po.'</li>
            </ul>';
        } else {
            $resposneData = '<ul class="list-unstyled mb-0">
                <li> <span style="color: #7367F0;">Current Stock : </span> '.$quantity.'</li>
                <li> <span style="color: #7367F0;">Requirement : </span> - </li>
                <li> <span style="color: #7367F0;">Open PO : </span> - </li>
                <li> <span style="color: #7367F0;">PO to be placed : </span> - </li>
            </ul>';
        }
        return response()->json($resposneData);
    }

    // Download Demand Note
    public function downloadPurchaseInvoice($id)
    {
        if(auth()->user()->can('PurchaseOrder-index')) {

            $InvoiceOrder = PurchaseOrder::join('vendors', 'vendors.id', '=', 'purchase_order.vendor_id')
            ->join('countries', 'countries.id', '=', 'vendors.country')
            ->join('cities', 'cities.id', '=', 'vendors.city')
            ->join('states', 'states.id', '=', 'vendors.state')
            ->select('purchase_order.*','vendors.vendor_name','vendors.vendor_email','vendors.vendor_phone','vendors.Location','vendors.vendor_street','vendors.vendor_zipcode','vendors.vendor_PAN','vendors.vendor_GST','countries.name as vendor_countryname','cities.name as vendor_cityname','states.name as vendor_statename','states.id as vendor_stateid')
            ->where('purchase_order.id',$id)
            ->first();

            $PurchaseOrderMaterials = PurchaseOrderMaterials::where('purchase_order_id',$id)->get();

            $CompanyDetails = Company::join('countries', 'countries.id', '=', 'companies.country')
            ->join('cities', 'cities.id', '=', 'companies.city')
            ->join('states', 'states.id', '=', 'companies.state')
            ->select('states', 'states.id', '=', 'companies.state')
            ->select('companies.*','countries.name as country','cities.name as city','states.name as state','states.id as stateid')
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
            $pdf = PDF::loadView('Admin/purchase/invoice',compact('InvoiceOrder','CompanyDetails','BankDetails','PurchaseOrderMaterials'))->setOptions(['defaultFont' => 'sans-serif']);;
            $pdf->getDOMpdf()->set_option('isPhpEnabled',true);
            return $pdf->stream($InvoiceOrder['code'].'.pdf');

            // return $pdf->stream($InvoiceOrder['code'].'.pdf');

            // return $pdf->download($InvoiceOrder['code'].'.pdf');
        }
    }
}
