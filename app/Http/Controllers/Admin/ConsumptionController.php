<?php

namespace App\Http\Controllers\Admin;
use App\Models\Admin\RawMaterial;
use App\Models\Admin\Manufacture;
use App\Models\Admin\Issuematerial;
use App\Models\Admin\Consumption;
use App\Models\Admin\Product;
use App\Models\Admin\ProductStockActivity;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Auth;
USE DB;
use View;
use PDF;
use Redirect;

class ConsumptionController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:Consumption-index|Consumption-create|Consumption-show|Consumption-destroy', ['only' => ['index','show']]);
        $this->middleware('permission:Consumption-create', ['only' => ['create','store']]);
        $this->middleware('permission:Consumption-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:Consumption-destroy', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->can('Consumption-index')) {
            $ManufactureOrder = Consumption::join('manufacture_order', 'manufacture_order.id', '=', 'consumption.mo_id')
            ->join('sales_order', 'sales_order.id', '=', 'manufacture_order.sales_order_id')
            ->join('product', 'product.id', '=', 'manufacture_order.product_id')
            ->select('consumption.*','manufacture_order.code as mocode','manufacture_order.id as mo_id','manufacture_order.delivery_date as delivery_date','manufacture_order.quantity as quantity','sales_order.code as s_code','product.name as p_name','product.sku as p_sku')
            ->orderBy('consumption.id', 'DESC')
            ->get();
            return view('Admin.consumption.index',compact('ManufactureOrder'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(auth()->user()->can('Consumption-create')) {
            $IssueMaterialData= Issuematerial::findOrFail($request->id);
            return view('Admin.consumption.create',compact('IssueMaterialData'));
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
        if(auth()->user()->can('Consumption-store')) {
            $inote = $request->inote;
            $txtValidateRaw = $request->txtValidateRaw??[];
            foreach($inote as $k=>$v){
                if(!array_key_exists('chk',$v)){
                    unset($inote[$k]);
                } else if($v['ConsumeQun'] <= 0){
                    unset($inote[$k]);
                }
                unset($inote[$k]['chk']);
            }

            if(empty($inote)) {
                return Redirect::back()->withErrors(['msg' => 'Please select checkbox with at least 1 quantity !']);
            } else {
                $validationRules = $validationMessage = [];
                foreach($inote as $k=>$v){
                    $validationRules['inote.'.$k.'.ConsumeQun'] = 'required|numeric|min:1|max:'.$v['requiredQun'];
                    $validationMessage['inote.'.$k.'.ConsumeQun.max'] = 'The '.$txtValidateRaw[$k].' consume quantity must not be greater than '.$v['requiredQun'].' !';
                    $validationMessage['inote.'.$k.'.ConsumeQun.min'] = 'The '.$txtValidateRaw[$k].' consume quantity must be at least 1 !';
                }
                $request->validate($validationRules,$validationMessage);
            }

            if(!empty($inote)) {

                MaterialRequirementActivity("Consume-Note-Activity",$inote);        
                $create = Consumption::create([
                    'code' => ConsumptionLastID(),
                    'issue_id' => $request->issueId,
                    'mo_id'=> $request->mId,
                    'consumptionnote'=> json_encode($inote),
                    'status'=> 'Complete',
                ]);

                $tmpNote= Issuematerial::where('id', $request->issueId)->pluck('materialnote')->first();
                $count = 0;
                if(!empty($tmpNote)){
                    $tmpNote = json_decode($tmpNote,true);
                    foreach($tmpNote as $k2=>$v2){
                        if(array_key_exists($k2,$inote)){
                            $Q = $v2['RemainedQun'] - $inote[$k2]['ConsumeQun'];
                            if($Q>0){
                                $tmpNote[$k2]['RemainedQun'] = $Q; 
                            } else {                        
                                $tmpNote[$k2]['RemainedQun'] = 0;
                            }
                        }
                    }
                    foreach($tmpNote as $k3=>$v3){
                        if($v3['RemainedQun'] > 0) $count++;
                    }
                    $IssueNote = Issuematerial::find($request->issueId);
                    if($count == 0){
                        $IssueNote->status="Complete";
                    }
                    $IssueNote->materialnote=json_encode($tmpNote);
                    $IssueNote->save();
                }

                // Check if all issue note is completed than consume status in MO is completed 
                $Manufacture = Manufacture::where('id', $request->mId)->first();            
                if($Manufacture->issue_status == "Completed") {
                    $tmpIssuematerialdata = Issuematerial::selectRaw('SUM(CASE WHEN status = "Complete" THEN 1 ELSE 0 END) AS completeraw')
                    ->selectRaw('COUNT(id) AS totalraw')
                    ->where('mo_id', $request->mId)
                    ->first();
                    $completeraw = $tmpIssuematerialdata['completeraw'];
                    $totalraw = $tmpIssuematerialdata['totalraw'];
                    if($completeraw == $totalraw) {
                        $Manufacture = Manufacture::where('id',$request->mId)->update(["consume_status"=>"Completed"]);
                    } else {
                        $Manufacture = Manufacture::where('id',$request->mId)->update(["consume_status"=>"In Progress"]);
                    }
                } else {
                    $Manufacture = Manufacture::where('id',$request->mId)->update(["consume_status"=>"In Progress"]);
                }

                $CompleteMo = Manufacture::where('id', $request->mId)->where('demand_status', "Completed")->where('issue_status', "Completed")->where('consume_status', "Completed")->count();
                if($CompleteMo == 1) Manufacture::where('id',$request->mId)->update(["status"=>"Finish"]);

            }

            return redirect()->route('consumption.index')->with('msg','Material is consumed for manufacturing order.');
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
        if(auth()->user()->can('Consumption-show')) {
            $ConsumptionList  = Consumption::findOrFail($id);
            return view('Admin.consumption.show',compact('ConsumptionList'));
        }
    }

    // Download Consumtpion Note
    public function downloadconsumptionNote($id)
    {
        $ConsumptionNoteList = Consumption::where('id', $id)->first();
        $pdf = PDF::loadView('Admin.consumption.downConsumptionNote',compact('ConsumptionNoteList'));
        return $pdf->download($ConsumptionNoteList['code'].'.pdf');
    }
    

}
