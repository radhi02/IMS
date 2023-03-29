<?php

namespace App\Http\Controllers\Admin\Module;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Auth;
class ModuleController extends Controller
{
    function Create()
    {
 
        return view('admin.Permissions.create');
    }

    function index($id)
    {
        $role_id=$id;
        $role = Role::find($role_id);
        $permisson =  Permission::all();
        $perant0 = array();
        $perant1 = array();
        foreach( $permisson as $key=>$val)
        { 
            if($val->moduleName != $id)
            {
            $perant[]=["id"=>$val->id,"Name"=>$val->moduleName];
            $id = $val->moduleName;
            }
        }
        $collection = new Collection();
        $collection =(isset($perant))?(object)$perant:$perant=[];
        $parent = $collection;   
        return view('admin.Permissions.index',compact('permisson','perant','role_id','role'));
    }

    function store(Request $request)
    {
        $ModuleName =$request->ModuleName;
        if($request->RouteType =="resource")
        {
            $route_array= array('index','create','store','show','edit','update','destroy');
        }
        else
        {
            $route_array= array($request->ModuleName);
        }
      
        $auth =Auth::user();

        $role_name = Role::select('id','name')->find($auth->Role);
        
        $get_con_name= $request->master;
        
        foreach($route_array as $key=>$value)
        {
            $permission = Permission::create(['name' => $get_con_name."-".$value, 'moduleName'=>$ModuleName]);
            if($role_name->name =='Admin'){
                $auth->assignRole($role_name->id);
            }    
        }
        session()->flash('msg', 'Module Created Updated Successfuly.');
        return redirect()->back();
    }


    function GivePermission(Request $request)
    {
     
        $permisson_req =  $request->child;
        $role_id=  $request->role;

        // $user= User::find($user_id);
        $role = Role::find($role_id);

         $modal = DB::table('role_has_permissions')->where('role_id',$role->id)->delete();

        if(!empty($permisson_req))
          {  
            foreach($permisson_req as $req)
            {
              
                $role->givePermissionTo($req);
            //   $permission(object)->assignRole($role);
            }
        
        }

     return redirect()->route('role.index');
    }
   
}
