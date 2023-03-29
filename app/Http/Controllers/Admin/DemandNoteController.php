<?php

namespace App\Http\Controllers\Admin;
use App\Models\Admin\Unit;
use App\Models\Admin\SalesOrder;
use App\Models\Admin\RawMaterial;
use App\Models\Admin\SalesOrderProducts;
use App\Models\Admin\Demandnote;
use App\Models\Admin\Manufacture;
use App\Models\Admin\Product;
use App\Models\Admin\ProductQCActivity;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Auth;
USE DB;
use View;
use PDF;
use Redirect;

class DemandNoteController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:Demandnote-index|Demandnote-create|Demandnote-show|Demandnote-destroy', ['only' => ['index','show']]);
        $this->middleware('permission:Demandnote-create', ['only' => ['create','store']]);
        $this->middleware('permission:Demandnote-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:Demandnote-destroy', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->can('Demandnote-index')) {
            $DemandNoteList = Demandnote::join('manufacture_order', 'manufacture_order.id', '=', 'demandnote.mo_id')
            ->select('demandnote.*','manufacture_order.code as mocode')
            ->orderBy('demandnote.id', 'DESC')
            ->get();
            return view('Admin.demandnote.index',compact('DemandNoteList'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(auth()->user()->can('Demandnote-create')) {
            $ManufactureOrder= Manufacture::findOrFail($request->id);
            return view('Admin.demandnote.create',compact('ManufactureOrder'));
        }
    }

    public function recreate($id)
    {
        if(auth()->user()->can('Demandnote-create')) {
            $ProductQCData= ProductQCActivity::findOrFail($id);
            $ManufactureOrder= Manufacture::findOrFail($ProductQCData->mo_id);
            $product_id = $ProductQCData->product_id;
            $tmpPro = Product::select("product_BOM", "id")->where('id',$product_id)->first();
            $productBOM = json_decode($tmpPro->product_BOM,true);
            return view('Admin.demandnote.recreate',compact('ManufactureOrder','productBOM','ProductQCData'));
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
        if(auth()->user()->can('Demandnote-store')) {

            $dnot = $request->dnote;
            $txtValidateRaw = $request->txtValidateRaw;

            // $request->validate([
            //     'dnote.*.demandQun'=> 'required|numeric|min:1000',
            // ],[
            //     'dnote.*.demandQun.min'=> 'The '.$txtValidateRaw[2].' quantity must be at least 1000.!',
            // ]);

            $tmpBOM= Manufacture::where('id', $request->mId)->pluck('bom_detail')->first();
            foreach($dnot as $k=>$v){
                if(!array_key_exists('chk',$v)){
                    unset($dnot[$k]);
                } else if($v['demandQun'] <= 0){
                    unset($dnot[$k]);
                } else {
                    $dnot[$k]['RemainedQun'] = $dnot[$k]['demandQun'];
                }
                unset($dnot[$k]['chk']);
            }
            if(empty($dnot)) {
                return Redirect::back()->withErrors(['msg' => 'Please select checkbox with at least 1 quantity !']);
            } else {
                $validationRules = $validationMessage = [];
                foreach($dnot as $k=>$v){
                    $validationRules['dnote.'.$k.'.demandQun'] = 'required|numeric|min:1|max:'.$v['requiredQun'];
                    $validationMessage['dnote.'.$k.'.demandQun.max'] = 'The '.$txtValidateRaw[$k].' demand quantity must not be greater than '.$v['requiredQun'].' !';
                    $validationMessage['dnote.'.$k.'.demandQun.min'] = 'The '.$txtValidateRaw[$k].' demand quantity must be at least 1 !';
                }
                $request->validate($validationRules,$validationMessage);
            }

            if(!empty($dnot)) {
                MaterialRequirementActivity("Demand-Note-Activity",$dnot);        
                
                $create = Demandnote::create([
                    'code'=> demandNoteLastID(),
                    'mo_id'=> $request->mId,
                    'note'=> json_encode($dnot),
                    'date'=> date('Y-m-d'),
                ]);
                $insertedId = $create->id;

                $count = 0;
                if(!empty($tmpBOM)){
                    $tmpBOM = json_decode($tmpBOM,true);
                    foreach($tmpBOM as $k2=>$v2){
                        if(array_key_exists($k2,$dnot)){
                            $Q = $v2['remained_quantity'] - $dnot[$k2]['demandQun'];
                            if($Q>0){
                                $tmpBOM[$k2]['remained_quantity'] = $Q; 
                            } else {                        
                                $tmpBOM[$k2]['remained_quantity'] = 0;
                            }
                        }
                    }

                    foreach($tmpBOM as $k3=>$v3){
                        if($v3['remained_quantity'] > 0) $count++;
                    }

                    $Manufacture = Manufacture::find($request->mId);
                    if($count == 0) {
                        $Manufacture->demand_status="Completed";
                    } else {
                        $Manufacture->demand_status="In Progress";
                    }
                    $Manufacture->status="Open";
                    $Manufacture->bom_detail=json_encode($tmpBOM);
                    $Manufacture->save();
                }
            }
            return redirect()->route('manufacture.index')->with('msg','Demand Note created successfully.');
        }
    }

    public function restore(Request $request)
    {
        if(auth()->user()->can('Demandnote-store')) {

            $dnot = $request->dnote;
            $txtValidateRaw = $request->txtValidateRaw;
            if(!empty($dnot)) {
                foreach($dnot as $k=>$v){
                    if(!array_key_exists('chk',$v)){
                        unset($dnot[$k]);
                    } else if($v['demandQun'] <= 0){
                        unset($dnot[$k]);
                    } else {
                        $dnot[$k]['RemainedQun'] = $dnot[$k]['demandQun'];
                        $dnot[$k]['requiredQun'] = $dnot[$k]['demandQun'];
                    }
                    unset($dnot[$k]['chk']);
                }
            }

            if(!empty($dnot)) {
                $validationRules = $validationMessage = [];
                foreach($dnot as $k=>$v){
                    $validationRules['dnote.'.$k.'.demandQun'] = 'required|numeric|min:1|max:'.$v['requiredQun'];
                    $validationMessage['dnote.'.$k.'.demandQun.max'] = 'The '.$txtValidateRaw[$k].' demand quantity must not be greater than '.$v['requiredQun'].' !';
                    $validationMessage['dnote.'.$k.'.demandQun.min'] = 'The '.$txtValidateRaw[$k].' demand quantity must be at least 1 !';
                }
                $request->validate($validationRules,$validationMessage);
                
                $create = Demandnote::create([
                    'code'=> demandNoteLastID(),
                    'mo_id'=> $request->mId,
                    'note'=> json_encode($dnot),
                    'date'=> date('Y-m-d'),
                ]);
                MaterialRequirementActivity("Demand-Note-Activity",$dnot);        
                
                ProductQCActivity::where('id',$request->QCId)->update(['mo_status'=>'Indemand','quantity'=>0]);
                Manufacture::where('id',$request->mId)->update(['demand_status'=>'Completed']);
                return redirect()->route('rejectedmo.index')->with('msg','Demand Note created successfully.');
            }
            return redirect()->route('rejectedmo.index')->with('msg','Please select material to create Demand Note.');
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
        if(auth()->user()->can('Demandnote-show')) {
            $DemandNoteList = Demandnote::where('mo_id', $id)->get();
            return view('Admin.demandnote.show',compact('DemandNoteList'));
        }
    }

    public function showSingleDemandNote($id)
    {
        $DemandNoteList = Demandnote::findOrFail($id);
        return view('Admin.demandnote.showSingle',compact('DemandNoteList'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(auth()->user()->can('Demandnote-edit')) {
            $SalesOrder= SalesOrder::findOrFail($id);
            $Customer = Customer::select("customer_name", "id")->where('customer_status','Active')->get();
            $Product = Product::where('status','Active')->get();
            return view('Admin.salesorder.create',compact('SalesOrder','Customer','Product'));
        }
    }

    // Download Demand Note
    public function downloadDemandNote($id)
    {
        $DemandNoteList = Demandnote::where('id', $id)->first();
        // view()->share('DemandNoteList',$DemandNoteList);
        //  return view('Admin.demandnote.downDNote',compact('DemandNoteList'));  
        $pdf = PDF::loadView('Admin.demandnote.downDNote',compact('DemandNoteList'));
        return $pdf->download($DemandNoteList['code'].'.pdf');

    }

}
