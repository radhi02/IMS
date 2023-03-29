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
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Auth;
USE DB;
use View;
use PDF;

class StockMaterialController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:RawMaterial-index|RawMaterial-create|RawMaterial-show|RawMaterial-destroy', ['only' => ['index','show']]);
        $this->middleware('permission:RawMaterial-create', ['only' => ['create','store']]);
        $this->middleware('permission:RawMaterial-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:RawMaterial-destroy', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->can('RawMaterial-index')) {
            $RawMaterial = RawMaterial::join('units as u1', 'u1.id', '=', 'raw_material.unit_id')
            ->select('raw_material.*','u1.unit_name as uname1')
            ->get();
            return view('Admin.stockmaterial.index',compact('RawMaterial'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $DemandNote= Demandnote::findOrFail($request->id);
        return view('Admin.issuematerial.create',compact('DemandNote'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rawCurrentQun= RawMaterial::where('id', $request->mId)->pluck('quantity')->first();
        $addQun = $request->quantity;
        $rawTotalQun = $rawCurrentQun + $addQun;
        RawMaterial::where('id', $request->mId)->update(['quantity' => $rawTotalQun]);
        $createA = RawMaterialStockActivity::create([
            'raw_id'=> $request->mId,
            'issue_id'=> null,
            'oldstock'=> $rawCurrentQun,
            'stockOut'=> 0,
            'stockIn'=> $addQun,
            'newstock'=> $rawTotalQun,
            'createdBy'=> Auth::user()->id,
            'stock_date'=> date('Y-m-d'),
        ]);
        return redirect()->route('stockmaterial.index')->with('msg','Raw Material stock added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $IssueList  = Issuematerial::findOrFail($id);
        return view('Admin.issuematerial.show',compact('IssueList'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $SalesOrder= SalesOrder::findOrFail($id);
        $Customer = Customer::select("customer_name", "id")->where('customer_status','Active')->get();
        $Product = Product::where('status','Active')->get();
        return view('Admin.salesorder.create',compact('SalesOrder','Customer','Product'));
    }

    // Download Demand Note
    public function downloadIssueNote($id)
    {
        $IssueNoteList = Issuematerial::where('id', $id)->first();
        // view()->share('DemandNoteList',$DemandNoteList);
        //  return view('Admin.demandnote.downDNote',compact('DemandNoteList'));  
        $pdf = PDF::loadView('Admin.issuematerial.downIssueNote',compact('IssueNoteList'));
        return $pdf->download($IssueNoteList['code'].'.pdf');

    }

}
