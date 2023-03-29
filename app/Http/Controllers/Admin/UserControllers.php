<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Cities;
use App\Models\Admin\Countries;
use App\Models\Admin\Department;
use App\Models\Admin\States;
use App\Models\User;
use DB;
use Hash;
use Password;
use File;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserControllers extends Controller
{
    function __construct()
    {
        $this->middleware('permission:User-index|User-create|User-show|User-destroy', ['only' => ['index','show']]);
        $this->middleware('permission:User-create', ['only' => ['create','store']]);
        $this->middleware('permission:User-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:User-destroy', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->can('User-index')) {
            $users = User::where('deleted_at', null)->get();
            return view("admin.Users.index", compact("users"));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->can('User-create')) {
            $roles = Role::all();
            $departments = Department::where('status', 'Active')->get();
            $States = "";
            $Cities = "";
            $Countries = DB::table('countries')->orderby('name','asc')->select('id','name')->get();

            $Company = [];

            return view('admin.Users.create', compact('roles', 'departments', 'Countries', 'States', 'Cities', 'Company'));
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
        if(auth()->user()->can('User-store')) {
            $get_perfectLast_id = userLastID();
            $img = $request->file('image');

            if (!empty($img) && $img->getClientOriginalName() != "") {
                $filename = $img->getClientOriginalName();
                $img->move(public_path('Admin/Users'), $filename);
            }

            $request->validate([
                'firstName' => 'required|min:3|max:12',
                'lastName' => 'required|min:3|max:12',
                'email' => 'required|email|unique:users',
                // 'password' => 'required|min:8|max:12|regex:/^([0-9\s\-\+\(\)]*)$/', 
                'UserType' => 'required',
                'Phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                'address' => 'required',
                'user_country' => 'required',
                'user_state' => 'required',
                'user_city' => 'required',
                'pincode' => 'required',
                'peraddress' => 'required',
                'Per_Country' => 'required',
                'Per_State' => 'required',
                'Per_city' => 'required',
                'perpincode' => 'required',
                'Status' => 'required',
            ]);

            $UserData = [
                'comp_id' => 1,
                'first_name' => $request->firstName,
                'last_name' => $request->lastName,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_status' => $request->Status,
                'Role' => $request->UserType,
                'Image' => (isset($filename) ? $filename : null),
                'Phone' => $request->Phone,
                'Country' => $request->user_country,
                'State' => $request->user_state,
                'city' => $request->user_city,
                'pincode' => $request->pincode,
                'Address' => $request->address,
                'doj' => $request->doj,
                'Per_Country' => $request->Per_Country,
                'Per_State' => $request->Per_State,
                'Per_city' => $request->Per_city,
                'Per_pincode' => $request->perpincode,
                'Per_Address' => $request->peraddress,
                'department_id' => $request->department_id,
                'gender' => $request->gender,
                'marital_status' => $request->marital_status,
                'bank_name' => $request->bank_name,
                'bank_swiftcode' => $request->bank_swiftcode,
                'bank_branch' => $request->bank_branch,
                'acc_number' => $request->acc_number,
                'acc_name' => $request->acc_name,
                'acc_ifsccode' => $request->acc_ifsccode,
                'blood_group' => $request->blood_group,
                'user_PAN' => $request->user_PAN,
                'user_ADHAR' => $request->user_ADHAR,
                'emp_code'=>$get_perfectLast_id,
            ];

            $users = User::create($UserData);
            $users->assignRole($request->UserType);
            return redirect()->route('user.index')->with('msg','User Created Successfuly.');
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
        if(auth()->user()->can('User-show')) {
            $edit_users = User::findOrFail($id);
            $roles = Role::all();
            $Countries = Countries::findOrFail($edit_users->Country);
            $States = States::findOrFail($edit_users->State);
            $Cities = Cities::findOrFail($edit_users->city);
            return view('admin.Users.show', compact('edit_users', 'Countries', 'States', 'Cities'));
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
        if(auth()->user()->can('User-edit')) {
            $roles = Role::all();
            $edit_users = User::find($id);
            $departments = Department::all();
            $Countries = DB::table('countries')->orderby('name','asc')->select('id','name')->get();
            $Company = [];
            return view('admin.Users.create', compact('edit_users', 'roles', 'Countries', 'Company','departments'));
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
        if(auth()->user()->can('User-update')) {
            $img = $request->file('image');

            if (!empty($img) && $img->getClientOriginalName() != "") {
                $filename = time()."_".$img->getClientOriginalName();
                $img->move(public_path('Admin/Users'), $filename);
            }
            $request->validate([
                'firstName' => 'required',
                'lastName' => 'required',
                'email' => 'unique:users,email,' . $id,
                'UserType' => 'required',
                'Phone' => 'required',
                'address' => 'required',
                'user_country' => 'required',
                'user_state' => 'required',
                'user_city' => 'required',
                'pincode' => 'required',
                'peraddress' => 'required',
                'Per_Country' => 'required',
                'Per_State' => 'required',
                'Per_city' => 'required',
                'perpincode' => 'required',
                'Status' => 'required',
            ]);
            $User = User::find($id);
            $User->comp_id = 1;
            $User->first_name = $request->firstName;
            $User->last_name = $request->lastName;
            $User->email = $request->email;
            $User->user_status = $request->Status;
            $User->Role = $request->UserType;
            $User->Phone = $request->Phone;
            $User->Country = $request->user_country;
            $User->State = $request->user_state;
            $User->city = $request->user_city;
            $User->pincode = $request->pincode;
            $User->Address = $request->address;
            $User->doj = $request->doj;
            $User->Per_Country = $request->Per_Country;
            $User->Per_State = $request->Per_State;
            $User->Per_city = $request->Per_city;
            $User->Per_pincode = $request->perpincode;
            $User->Per_Address = $request->peraddress;
            $User->department_id = $request->department_id;
            $User->gender = $request->gender;
            $User->marital_status = $request->marital_status;
            $User->bank_name = $request->bank_name;
            $User->bank_swiftcode = $request->bank_swiftcode;
            $User->bank_branch = $request->bank_branch;
            $User->acc_number = $request->acc_number;
            $User->acc_name = $request->acc_name;
            $User->acc_ifsccode = $request->acc_ifsccode;
            $User->blood_group = $request->blood_group;
            $User->user_PAN = $request->user_PAN;
            $User->user_ADHAR = $request->user_ADHAR;
            if (isset($filename)) {$User->Image = $filename;}
            $User->save();
            DB::table('model_has_roles')->where('model_id', $id)->delete();
            $User->assignRole($request->UserType);
            return redirect()->route('user.index')->with('msg','User Updated Successfuly.');
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
        if(auth()->user()->can('User-destroy')) {
            $User = User::find($request->id)->delete();
            return response()->json(['msg' => 'User deleted successfully.']);
        }
    }

    public function deleteUserImage(Request $request)
    {
        $image_path = public_path('Admin/Users/').$request->imgname;
        if(File::exists($image_path)) {
            File::delete($image_path);
        }
        $User = User::find($request->userid);
        $User->Image = null;
        $User->save();
        return response()->json(['msg' => 'User Image deleted successfully.']);
    }

    public function Status(Request $request)
    {
        $id_array = $request->id;
        $flag = $request->value;
        $Roles = User::whereIn('id', $id_array)->update(["user_status" => $flag]);
        if ($Roles == true) {
            return response()->json(['msg' => 1]);
        } else {
            return response()->json(['msg' => 0]);
        }
    }
}
