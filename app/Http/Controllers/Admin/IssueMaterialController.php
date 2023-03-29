<?php

namespace App\Http\Controllers\Admin;
use App\Models\Admin\Unit;
use App\Models\Admin\SalesOrder;
use App\Models\Admin\RawMaterial;
use App\Models\Admin\SalesOrderProducts;
use App\Models\Admin\Demandnote;
use App\Models\Admin\Manufacture;
use App\Models\Admin\Issuematerial;
use App\Models\Admin\RawMaterialStockActivity;
use App\Models\Admin\Consumption;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Auth;
USE DB;
use View;
use PDF;
use Redirect;

class IssueMaterialController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:Issuematerial-index|Issuematerial-create|Issuematerial-show|Issuematerial-destroy', ['only' => ['index','show']]);
        $this->middleware('permission:Issuematerial-create', ['only' => ['create','store']]);
        $this->middleware('permission:Issuematerial-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:Issuematerial-destroy', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->can('Issuematerial-index')) {
            $IssueList = Issuematerial::join('demandnote', 'demandnote.id', '=', 'issuematerial.dn_id')
            ->join('manufacture_order', 'manufacture_order.id', '=', 'issuematerial.mo_id')
            ->select('issuematerial.*','manufacture_order.code as mocode','demandnote.code as dncode','demandnote.date as dndate')
            ->orderBy('issuematerial.id', 'DESC')
            ->get();
            return view('Admin.issuematerial.index',compact('IssueList'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(auth()->user()->can('Issuematerial-create')) {
            $DemandNote= Demandnote::findOrFail($request->id);
            return view('Admin.issuematerial.create',compact('DemandNote'));
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
        if(auth()->user()->can('Issuematerial-store')) {
            $inote = $request->inote??[];
            $txtValidateRaw = $request->txtValidateRaw??[];
            foreach($inote as $k=>$v){
                if(!array_key_exists('chk',$v)){
                    unset($inote[$k]);
                } else if($v['IssueQun'] <= 0){
                    unset($inote[$k]);
                }  else {
                    $inote[$k]['RemainedQun'] = $inote[$k]['IssueQun'];
                }
                unset($inote[$k]['chk']);
            }

            if(empty($inote)) {
                return Redirect::back()->withErrors(['msg' => 'Please select checkbox with at least 1 quantity !']);
            } else {
                $validationRules = $validationMessage = [];
                foreach($inote as $k=>$v){
                    $rawCurrentQun= RawMaterial::where('id', $v['RawId'])->pluck('quantity')->first();
                    $maxQ =  ($v['RemainedQun']>$rawCurrentQun) ? $rawCurrentQun : $v['RemainedQun'];
                    $validationRules['inote.'.$k.'.IssueQun'] = 'required|numeric|min:1|max:'.$maxQ;
                    $validationMessage['inote.'.$k.'.IssueQun.max'] = 'The '.$txtValidateRaw[$k].' issue quantity must not be greater than '.$maxQ.' !';
                    $validationMessage['inote.'.$k.'.IssueQun.min'] = 'The '.$txtValidateRaw[$k].' issue quantity must be at least 1 !';
                }
                $request->validate($validationRules,$validationMessage);
            }

            if(!empty($inote)) {

                MaterialRequirementActivity("Issue-Note-Activity",$inote);        
                $create = Issuematerial::create([
                    'code'=> IssueLastID(),
                    'dn_id'=> $request->dId,
                    'mo_id'=> $request->mId,
                    'status'=> 'Pending',
                    'materialnote'=> json_encode($inote),
                    'issue_date'=> date('Y-m-d'),
                ]);
                $insertedId = $create->id;

                foreach($inote as $i) {
                    $rawCurrentQun= RawMaterial::where('id', $i['RawId'])->pluck('quantity')->first();
                    $issueQun = $i['IssueQun'];
                    $rawTotalQun = $rawCurrentQun - $issueQun;
                    RawMaterial::where('id', $i['RawId'])->update(['quantity' => $rawTotalQun]);
                    $createA = RawMaterialStockActivity::create([
                        'raw_id'=> $i['RawId'],
                        'issue_id'=> $insertedId,
                        'oldstock'=> $rawCurrentQun,
                        'stockOut'=> $issueQun,
                        'stockIn'=> 0,
                        'newstock'=> $rawTotalQun,
                        'createdBy'=> Auth::user()->id,
                        'stock_date'=> date('Y-m-d'),
                    ]);
                }

                $tmpNote= Demandnote::where('id', $request->dId)->pluck('note')->first();
                $count = 0;
                if(!empty($tmpNote)){
                    $tmpNote = json_decode($tmpNote,true);
                    foreach($tmpNote as $k2=>$v2){
                        if(array_key_exists($k2,$inote)){
                            $Q = $v2['RemainedQun'] - $inote[$k2]['IssueQun'];
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
                    $DemandNote = Demandnote::find($request->dId);
                    if($count == 0){
                        $DemandNote->status="Complete";
                    }
                    $DemandNote->note=json_encode($tmpNote);
                    $DemandNote->save();
                }
                // Check if all demand note is completed than issue status in MO is completed 

                $Manufacture = Manufacture::where('id', $request->mId)->first();            
                if($Manufacture->demand_status == "Completed") {
                    $tmpDemandnotedata = Demandnote::selectRaw('SUM(CASE WHEN status = "Complete" THEN 1 ELSE 0 END) AS completeraw')
                    ->selectRaw('COUNT(id) AS totalraw')
                    ->where('mo_id', $request->mId)
                    ->first();
                    $completeraw = $tmpDemandnotedata['completeraw'];
                    $totalraw = $tmpDemandnotedata['totalraw'];
                    if($completeraw == $totalraw) {
                        $Manufacture = Manufacture::where('id',$request->mId)->update(["issue_status"=>"Completed"]);
                    } else {
                        $Manufacture = Manufacture::where('id',$request->mId)->update(["issue_status"=>"In Progress"]);
                    }
                } else {
                    $Manufacture = Manufacture::where('id',$request->mId)->update(["issue_status"=>"In Progress"]);
                }
            }
            return redirect()->route('demandnote.index')->with('msg','Material issued from store successfully.');
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
        if(auth()->user()->can('Issuematerial-show')) {
            $IssueList  = Issuematerial::findOrFail($id);
            return view('Admin.issuematerial.show',compact('IssueList'));
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
        if(auth()->user()->can('Issuematerial-edit')) {
            $SalesOrder= SalesOrder::findOrFail($id);
            $Customer = Customer::select("customer_name", "id")->where('customer_status','Active')->get();
            $Product = Product::where('status','Active')->get();
            return view('Admin.salesorder.create',compact('SalesOrder','Customer','Product'));
        }
    }

    // Download Issue Note
    public function downloadIssueNote($id)
    {
        if(auth()->user()->can('Issuematerial-show')) {
            $IssueNoteList = Issuematerial::where('id', $id)->first();
            // view()->share('DemandNoteList',$DemandNoteList);
            //  return view('Admin.demandnote.downDNote',compact('DemandNoteList'));  
            $pdf = PDF::loadView('Admin.issuematerial.downIssueNote',compact('IssueNoteList'));
            return $pdf->download($IssueNoteList['code'].'.pdf');
        }
    }

}
