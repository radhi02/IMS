<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin\Vendor;
use App\Models\Admin\Category;
use App\Models\Admin\Consumption;
use App\Models\Admin\Customer;
use App\Models\Admin\Demandnote;
use App\Models\Admin\Department;
use App\Models\Admin\Issuematerial;
use App\Models\Admin\Manufacture;
use App\Models\Admin\MaterialCategory;
use App\Models\Admin\Product;
use App\Models\Admin\ProductStockActivity;
use App\Models\Admin\PurchaseMaterialReceived;
use App\Models\Admin\PurchaseOrder;
use App\Models\Admin\PurchaseOrderMaterials;
use App\Models\Admin\RawMaterial;
use App\Models\Admin\RawMaterialStockActivity;
use App\Models\Admin\RoleModel;
use App\Models\Admin\SalesOrder;
use App\Models\Admin\SalesOrderProducts;
use App\Models\Admin\States;
use App\Models\Admin\SubCategory;
use App\Models\Admin\Unit;
use App\Models\Admin\InvoiceOrder;
use App\Models\Admin\InvoicePayment;
use App\Models\User;
use App\Models\Admin\MaterialRequirement;
use Carbon\Carbon;
use App\Notifications\OffersNotification;

use DB;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
      // $notifications = auth()->user()->unreadNotifications;
      $counter = $cashcounter = $todayStockActivity = $MaterialRequiement = [];

      $counter['vendor'] = Vendor::where("vendor_status","Active")->count(); 
      $counter['customer'] = Customer::where("customer_status","Active")->count(); 
      $counter['salesorder'] = SalesOrder::count(); 
      $counter['purchaseorder'] = PurchaseOrder::count(); 

      // Total Sale Amount
      $toalSaleAmt = SalesOrder::selectRaw('ifnull(SUM(base_grandtotal),0) AS total_sale_amt')->first();
      $dueSaleAmt = InvoiceOrder::selectRaw('ifnull(SUM(due_amount),0) AS sale_due_amt')->first();
      $recievedSaleAmt = InvoicePayment::selectRaw('ifnull(SUM(received_amount),0) AS sale_received_amt')->first();
      $PurchaseAmt = PurchaseOrder::selectRaw('ifnull(SUM(base_grandtotal),0) AS total_purchase_amt')->first();

      $cashcounter = [
         'total_sale_amt' => $toalSaleAmt['total_sale_amt'],
         'sale_due_amt' => $dueSaleAmt['sale_due_amt'],
         'sale_received_amt' => $recievedSaleAmt['sale_received_amt'],
         'total_purchase_amt' => $PurchaseAmt['total_purchase_amt']
      ];

      $todayStockActivity['RawMaterial'] = DB::table('rawmaterial_stock_activity')
      ->groupBy('raw_id')
      ->whereDate('created_at', Carbon::today())
      ->get(['raw_id', DB::raw('ifnull(SUM(stockIn),0) AS stockIn'), DB::raw('ifnull(SUM(stockOut),0) AS stockOut')]);

      $todayStockActivity['Product'] = DB::table('product_stock_activity')
      ->groupBy('product_id')
      ->whereDate('created_at', Carbon::today())
      ->get(['product_id', DB::raw('ifnull(SUM(stockIn),0) AS stockIn'), DB::raw('ifnull(SUM(stockOut),0) AS stockOut')]);

      // Raw Material Requirement Table
      $MaterialRequiement = MaterialRequirement::leftjoin('raw_material', 'raw_material.id', '=', 'material_requirement.raw_id')->select('material_requirement.*','raw_material.quantity as Instock')->get();

      // Raw Material Requiement Chart Data
      $RMRC = RawMaterial::leftjoin('material_requirement', 'material_requirement.id', '=', 'raw_material.id')
      ->select( DB::raw("ifnull(SUM(raw_material.quantity),0) as total_quantity"), DB::raw("ifnull(SUM(material_requirement.requirement),0) as total_requirement"), DB::raw("ifnull(SUM(material_requirement.pending_po),0) as total_pending_po"), DB::raw("ifnull(SUM(material_requirement.new_po),0) as total_new_po")) ->first();
      $RMRC['Key'] = "'In Stock', 'Mfg Requirement', 'Open PO', 'Need to Buy'";
      $RMRC['Value'] = $RMRC['total_quantity'].','.$RMRC['total_requirement'].','.$RMRC['total_pending_po'].','.$RMRC['total_new_po'];

      // Manufacturing Order Data
      $ManufactureData = Manufacture::selectRaw('ifnull(SUM(CASE WHEN status = "Pending" THEN 1 ELSE 0 END),0) AS Pending')
      ->selectRaw('ifnull(SUM(CASE WHEN status = "Open" THEN 1 ELSE 0 END),0) AS Open')
      ->selectRaw('ifnull(SUM(CASE WHEN status = "Finish" THEN 1 ELSE 0 END),0) AS Finish')
      ->selectRaw('ifnull(SUM(CASE WHEN (status = "QCPending" OR status = "Inprocess") THEN 1 ELSE 0  END),0) AS QCPending')
      ->selectRaw('ifnull(SUM(CASE WHEN status = "Instore" THEN 1 ELSE 0 END),0) AS Instore')
      ->first();
      $MODATA['Key'] = "'Pending', 'Open', 'Mfg Complete', 'QC Pending', 'Instore'";
      $MODATA['Value'] = $ManufactureData['Pending'].','.$ManufactureData['Open'].','.$ManufactureData['Finish'].','.$ManufactureData['QCPending'].','.$ManufactureData['Instore'];

      // PurchaseOrderMaterials chart
      $POQuantity = [];
      $PurchaseOrderData = PurchaseOrder::selectRaw('ifnull(SUM(CASE WHEN status = "Pending" THEN 1 ELSE 0 END),0) AS POPending')
      ->selectRaw('ifnull(SUM(CASE WHEN status = "Partial Recieve" THEN 1 ELSE 0 END),0) AS POPartialRecieve')
      ->selectRaw('ifnull(SUM(CASE WHEN status = "Complete" THEN 1 ELSE 0 END),0) AS POComplete')
      ->first();

      $POQuantity['Key'] = "'Pending', 'Partial Recieve', 'Complete'";
      $POQuantity['Value'] = $PurchaseOrderData->POPending.','.$PurchaseOrderData->POPartialRecieve.','.$PurchaseOrderData->POComplete;

      // $PurchaseOrderMaterials = PurchaseOrderMaterials::first([DB::raw('SUM(quantity) AS quantity'), DB::raw('SUM(remained_quantity) AS remained_quantity')]);
      // $openPOQuantity = $PurchaseOrderMaterials->quantity - $PurchaseOrderMaterials->remained_quantity;
      // $closePOQuantity = $PurchaseOrderMaterials->remained_quantity;
      // $POQuantity['Key'] = "'Open Purchase Order', 'Close Purchase Order'";
      // $POQuantity['Value'] = $openPOQuantity.','.$closePOQuantity;

      // Invoice Order chart
      $IOQuantity = [];
      $InvoiceOrderData = InvoiceOrder::selectRaw('ifnull(SUM(CASE WHEN status = "Open" THEN 1 ELSE 0 END),0) AS IOOpen')
      ->selectRaw('ifnull(SUM(CASE WHEN status = "Partial Paid" THEN 1 ELSE 0 END),0) AS IOPartialPaid')
      ->selectRaw('ifnull(SUM(CASE WHEN status = "Close" THEN 1 ELSE 0 END),0) AS IOClose')
      ->first();
      // $InvoiceOrderData = InvoiceOrder::selectRaw('SUM(CASE WHEN status = "Open" THEN base_total_quantity ELSE 0 END) AS IOOpen')
      // ->selectRaw('SUM(CASE WHEN status = "Partial Paid" THEN base_total_quantity ELSE 0 END) AS IOPartialPaid')
      // ->selectRaw('SUM(CASE WHEN status = "Close" THEN base_total_quantity ELSE 0 END) AS IOClose')
      // ->first();

      $IOQuantity['Key'] = "'Open','Partial Paid', 'Close'";
      $IOQuantity['Value'] = $InvoiceOrderData->IOOpen.','.$InvoiceOrderData->IOPartialPaid.','.$InvoiceOrderData->IOClose;

      return view('home',compact('counter','cashcounter','todayStockActivity','MaterialRequiement','RMRC','POQuantity','IOQuantity','MODATA'));
    }

   public function markNotification(Request $request)
   {      
      auth()->user()
      ->unreadNotifications
      ->when($request->input('id'), function ($query) use ($request) {
         return $query->where('id', $request->input('id'));
      })
      ->markAsRead();
      
      $count = DB::table('notifications')->where("notifiable_id",auth()->user()->id)->where("read_at",null)->count(); 
      return response()->json(['count'=>$count]);
   }

   public function fetchNotifications() {
      $notifications = auth()->user()->unreadNotifications;
      $count = DB::table('notifications')->where("notifiable_id",auth()->user()->id)->where("read_at",null)->count(); 
      $html = view('admin.layout.notificationList',compact('notifications'))->render();
      return response()->json(array('html'=>$html,'count'=>$count));
   }

   public function showNotifications() {
      $notifications = DB::table('notifications')->where("notifiable_id",auth()->user()->id)->get();
      return view('admin.notifications.show',compact('notifications'));
   }

    function Countries(Request $request)
    {
        $search = $request->search;

        if($search == ''){
           $countryList = DB::table('countries')->orderby('name','asc')->select('id','name')->get();
        }else{
           $countryList = DB::table('countries')->orderby('name','asc')->select('id','name')->where('name', 'like', '%' .$search . '%')->get();
        }
  
        $response = array();
        foreach($countryList as $country){
           $response[] = array(
                "id"=>$country->id,
                "text"=>$country->name
           );
        }
        return response()->json($response); 
  
    }

    function get_state(Request $request)
    {
        $search = $request->search;
        $country = $request->country;

        if($search == ''){
           $stateList = DB::table('states')->orderby('name','asc')->select('id','name')->where('country_id',$country)->get();
        }else{
           $stateList = DB::table('states')->orderby('name','asc')->select('id','name')->where('name', 'like', '%' .$search . '%')->where('country_id',$country)->get();
        }
  
        $response = array();
        foreach($stateList as $state){
           $response[] = array(
                "id"=>$state->id,
                "text"=>$state->name
           );
        }
        return response()->json($response); 
  
    }

    function get_city(Request $request)
    {
        $search = $request->search;
        $state = $request->state;

        if($search == ''){
           $cityList = DB::table('cities')->orderby('name','asc')->select('id','name')->where('state_id',$state)->get();
        }else{
           $cityList = DB::table('cities')->orderby('name','asc')->select('id','name')->where('name', 'like', '%' .$search . '%')->where('state_id',$state)->get();
        }
  
        $response = array();
        foreach($cityList as $city){
           $response[] = array(
                "id"=>$city->id,
                "text"=>$city->name
           );
        }
        return response()->json($response); 
    }

}
