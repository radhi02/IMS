<?php

namespace App\Http\Controllers\Admin;
use App\Models\Admin\Unit;
use App\Models\Admin\RawMaterial;
use App\Models\Admin\MaterialCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Auth;
USE DB;

class RawMaterialController extends Controller
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
            $RawMaterial = RawMaterial::join('material_category', 'material_category.id', '=', 'raw_material.material_category_id')
            ->join('units as u1', 'u1.id', '=', 'raw_material.unit_id')
            ->select('raw_material.*','material_category.name as catname','u1.unit_name as uname1')
            ->get();
            // $RawMaterial = RawMaterial::all();
            return view('Admin.rawmaterial.index',compact('RawMaterial'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->can('RawMaterial-create')) {
            $Unit = Unit::select("unit_name", "id")->where('status','Active')->get();
            $Category = MaterialCategory::select("name", "id")->where('status','Active')->get();
            return view('Admin.rawmaterial.create',compact('Unit','Category'));
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
        if(auth()->user()->can('RawMaterial-store')) {
            $request->validate([
                'name'=> 'required',
                'material_category_id'=> 'required',
                'unit_id'=> 'required',
                'location'=> 'required',
                'quantity'=> 'required',
                'status'=> 'required',
                'code'=> 'required',
                'HSN_CODE'=> 'required',
                // 'GST'=> 'required',
            ]);
            $data = [
                'name'=> $request->name,
                'material_category_id'=>$request->material_category_id,
                'unit_id'=>$request->unit_id,
                'quantity'=>$request->quantity,
                'description'=>$request->description,
                'status'=>$request->status,
                'code'=>$request->code,
                'HSN_CODE'=>$request->HSN_CODE,
                'GST'=>0,
            ];
            if(isset($request->location)) {
                $data['location'] = json_encode($request->location);
            }
            $create = RawMaterial::create($data);
            return redirect()->route('rawmaterial.index')->with('msg','Raw Material created successfully.');
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
        if(auth()->user()->can('RawMaterial-show')) {
            $RawMaterial= RawMaterial::findOrFail($id);
            $Category = MaterialCategory::findOrFail($RawMaterial->material_category_id);
            $Unit = Unit::findOrFail($RawMaterial->unit_id);
            return view('Admin.rawmaterial.show',compact('RawMaterial','Unit','Category'));
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
        if(auth()->user()->can('RawMaterial-edit')) {
            $RawMaterial= RawMaterial::findOrFail($id);
            $Category = MaterialCategory::select("name", "id")->where('status','Active')->get();
            $Unit = Unit::select("unit_name", "id")->where('status','Active')->get();
            return view('Admin.rawmaterial.create',compact('RawMaterial','Unit','Category'));
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
        if(auth()->user()->can('RawMaterial-update')) {
            $request->validate([
                'name'=> 'required',
                'material_category_id'=> 'required',
                'unit_id'=> 'required',
                'location'=> 'required',
                'quantity'=> 'required',
                'status'=> 'required',
                'code'=> 'required',
                'HSN_CODE'=> 'required',
                // 'GST'=> 'required',
            ]);
            $RawMaterial = RawMaterial::find($id);
            $RawMaterial->name=$request->name;
            $RawMaterial->material_category_id=$request->material_category_id;
            $RawMaterial->unit_id=$request->unit_id;
            $RawMaterial->location = null;
            if(isset($request->location)) {
                $RawMaterial->location = json_encode($request->location);
            }
            $RawMaterial->quantity=$request->quantity;
            $RawMaterial->status=$request->status;
            $RawMaterial->code=$request->code;
            $RawMaterial->HSN_CODE=$request->HSN_CODE;
            $RawMaterial->save();                  
            session()->flash('msg', 'Raw Material Updated Successfuly.');
            return redirect()->route('rawmaterial.index');
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
        if(auth()->user()->can('RawMaterial-destroy')) {
            $RawMaterial = RawMaterial::find($request->id)->delete();
            return response()->json(['msg'=>'Raw Material deleted successfully.']);  
        }
    }
    
    public function Status(Request $request)
    {
        $id_array = $request->id;
        $flag= $request->value;
       
        $Roles = RawMaterial::whereIn('id',$id_array)->update(["status"=>$flag]);
       
        if($Roles == true)
        {
            return response()->json(['msg'=>1]);
        }
        else
        {
            return response()->json(['msg'=>0]);
        }
    }
   
    public function fetchRawMaterial(Request $request)
    {
        $data['rawmaterials'] = RawMaterial::whereIn("material_category_id",$request->id)->get(["name", "id"]);
        return response()->json($data);
    }
}
