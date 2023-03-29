<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Admin\Unit;
use Illuminate\Http\Request;
use Hash;
use Auth;
USE DB;


class UnitController extends Controller
{
        
    function __construct()
    {
        $this->middleware('permission:Unit-index|Unit-create|Unit-show|Unit-destroy', ['only' => ['index','show']]);
        $this->middleware('permission:Unit-create', ['only' => ['create','store']]);
        $this->middleware('permission:Unit-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:Unit-destroy', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->can('Unit-index')) {
            $Unit = Unit::all();
            return view('Admin.unit.index',compact('Unit'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->can('Unit-index')) {
            return view('Admin.unit.create');
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
        if(auth()->user()->can('Unit-store')) {
            $request->validate([
                'unit_name'=> 'required',
            ]);
            $create = Unit::create([
                'unit_name'=> $request->unit_name,
                'description'=> $request->description,
                'status'=> $request->status,
            ]);
            return redirect()->route('unit.index')->with('msg','Unit created successfully.');
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
        if(auth()->user()->can('Unit-show')) {
            $Unit= Unit::findOrFail($id);
            return view('Admin.unit.show',compact('Unit'));
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
        if(auth()->user()->can('Unit-edit')) {
            $Unit= Unit::findOrFail($id);
            return view('Admin.unit.create',compact('Unit'));
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
        if(auth()->user()->can('Unit-update')) {
            $request->validate([
                'unit_name'=> 'required',
            ]);
            $Unit = Unit::find($id);
            $Unit->unit_name=$request->unit_name;
            $Unit->description=$request->description;
            $Unit->status=$request->status;
            $Unit->save();                  
            return redirect()->route('unit.index')->with('msg','Unit Updated Successfuly.');
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
        if(auth()->user()->can('Unit-destroy')) {
            $Unit = Unit::find($request->id)->delete();
            return response()->json(['msg'=>'Unit deleted successfully.']);  
        }
    }
    public function Status(Request $request)
    {
        $id_array = $request->id;
        $flag= $request->value;
       
        $Roles = Unit::whereIn('id',$id_array)->update(["status"=>$flag]);
       
        if($Roles == true)
        {
            return response()->json(['msg'=>1]);
        }
        else
        {
            return response()->json(['msg'=>0]);
        }
    }
}