<?php

namespace App\Http\Controllers\Admin;
use App\Models\User;
use App\Models\Admin\RoleModel;
use App\Models\Admin\Countries;
use App\Models\Admin\Cities;
use App\Models\Admin\States;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Vendor;
use App\Models\Admin\Customer;
use App\Models\Admin\Department;
use Hash;
use Auth;
USE DB;

class DepartmentController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:Department-index|Department-create|Department-show|Department-destroy', ['only' => ['index','show']]);
        $this->middleware('permission:Department-create', ['only' => ['create','store']]);
        $this->middleware('permission:Department-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:Department-destroy', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->can('Department-index')) {
            $Department = Department::all();
            return view('Admin.department.index',compact('Department'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->can('Department-create')) {
            return view('Admin.department.create');
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
        if(auth()->user()->can('Department-store')) {
            $get_perfectLast_id = departmentLastID();
            $request->validate([
                'department_name'=> 'required',
                'department_status'=> 'required',
            ]);
            $create = Department::create([
                'department_name'=> $request->department_name,
                'status'=> $request->department_status,
                'department_code'=>$get_perfectLast_id,
            ]);
            return redirect()->route('department.index')->with('msg','Department created successfully.');
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
        if(auth()->user()->can('Department-show')) {
            $Department= Department::findOrFail($id);
            return view('Admin.department.show',compact('Department'));
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
        if(auth()->user()->can('Department-edit')) {
            $Department= Department::findOrFail($id);
            return view('Admin.department.create',compact('Department'));
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
        if(auth()->user()->can('Department-update')) {
            $request->validate([
                'department_name'=> 'required',
                'department_status'=> 'required',
            ]);
            $Department = Department::find($id);
            $Department->department_name=$request->department_name;
            $Department->status=$request->department_status;
            $Department->save();                  
            session()->flash('msg', 'Department Updated Successfuly.');
            return redirect()->route('department.index');
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
        if(auth()->user()->can('Department-destroy')) {
            $Department = Department::find($request->id)->delete();
            return response()->json(['msg'=>'Department deleted successfully.']);  
        }
    }

    public function Status(Request $request)
    {
        $id_array = $request->id;
        $flag= $request->value;
       
        $Roles = Department::whereIn('id',$id_array)->update(["status"=>$flag]);
       
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

