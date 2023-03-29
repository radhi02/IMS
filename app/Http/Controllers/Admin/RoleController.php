<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\Admin\RoleModel;
use Illuminate\Support\Facades\Input;
// use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;
use Session;
use Auth;
        
class RoleController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:Role-index|Role-create|Role-show|Role-destroy', ['only' => ['index','show']]);
         $this->middleware('permission:Role-create', ['only' => ['create','store']]);
         $this->middleware('permission:Role-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:Role-destroy', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->can('Role-index')) 
        {
            $roles = Role::all();
            return view("admin.role.index",compact('roles'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->can('Role-create')) 
        {
            return view("admin.role.create");
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
        if(auth()->user()->can('Role-store')) {
            $request->validate([
                'name' => 'required|unique:roles|max:255',
                'Description' => 'required',        
            ]);
            $auth =Auth::user()->comp_id;
            $role = Role::create(['name' =>$request->name,"status"=>$request->Status,"description"=>$request->Description]);
            return redirect()->route('role.index')->with('msg','Role Created Successfuly.');
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
        if(auth()->user()->can('Role-show')) 
        {
            $show_role =  Role::find($id);
            return view("admin.role.show",compact('show_role'));
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
        if(auth()->user()->can('Role-store')) 
        {
            $edit_role = Role::find($id);
            return view("admin.role.create",compact("edit_role"));
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
        if(auth()->user()->can('Role-update')) 
        {
            $role = Role::where('id',$id)->update(['name' =>$request->name,"status"=>$request->Status,"description"=>$request->Description]);
            return redirect()->route('role.index')->with('msg','Role Updated Successfuly.');
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
        if(auth()->user()->can('Role-destroy')) 
        {
            $Roles = Role::find($request->id)->delete();
            return response()->json(['msg'=>'Role deleted successfully.']);
        }
    }

    public function Status(Request $request)
    {      
        $id_array = $request->id;
        $flag= $request->value;
        $Roles = Role::whereIn('id',$id_array)->update(["status"=>$flag]);
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
