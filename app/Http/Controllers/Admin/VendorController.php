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
use Hash;
use Auth;
USE DB;
use App\Models\Admin\Company;

class VendorController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:Vendor-index|Vendor-create|Vendor-show|Vendor-destroy', ['only' => ['index','show']]);
        $this->middleware('permission:Vendor-create', ['only' => ['create','store']]);
        $this->middleware('permission:Vendor-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:Vendor-destroy', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->can('Vendor-index')) {
            $vendor = Vendor::all();
            return view('Admin.vendor.index',compact('vendor'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->can('Vendor-create')) {
            $Countries = DB::table('countries')->orderby('name','asc')->select('id','name')->get();
            return view('Admin.vendor.create',compact('Countries'));
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
        if(auth()->user()->can('Vendor-store')) {
            $get_perfectLast_id = vendorLastID();
            $request->validate([
                'vendor_name'=> 'required|min:3|max:20',
                'vendor_contactperson'=> 'required|min:3|max:20',
                'vendor_email'=> 'required|min:3|max:40',
                'vendor_phone'=> 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                'vendor_street'=> 'required|min:3|max:20',
                'vendor_city'=> 'required',
                'vendor_state'=> 'required',
                'vendor_country'=> 'required',
                'vendor_zipcode'=> 'required|min:6|max:6',
                'vendor_status'=>'required',
                'vendor_PAN' => 'nullable|regex:/^[A-Za-z]{5}[0-9]{4}[A-Z]{1}$/',
                'vendor_GST' => 'nullable|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/'
            ]);
            // dd($request->all());

            $create = Vendor::create([
                'vendor_name'=> $request->vendor_name,
                'vendor_code'=>$get_perfectLast_id,
                'vendor_contactperson'=> $request->vendor_contactperson,                            
                'vendor_email'=> $request->vendor_email,
                'vendor_phone'=>$request->vendor_phone,
                'Location'=>$request->Location,
                'vendor_street'=> $request->vendor_street,
                'city'=> $request->vendor_city,
                'state'=> $request->vendor_state,
                'country'=> $request->vendor_country,
                'vendor_zipcode'=> $request->vendor_zipcode,
                'vendor_GST'=> $request->vendor_GST,
                'vendor_PAN'=> $request->vendor_PAN,
                'vendor_type'=> $request->vendor_type,
                'vendor_MSME'=> $request->vendor_MSME,
                'vendor_MSME_number'=> $request->vendor_MSME_number,
                'vendor_status'=>$request->vendor_status,
            ]);
            return redirect()->route('vendor.index')->with('msg','Vendor created successfully.');
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
        if(auth()->user()->can('Vendor-show')) {
            $edit_vendor= Vendor::findOrFail($id);
            $roles = Role::all();
            $Countries = Countries::findOrFail($edit_vendor->country);
            $States = States::findOrFail($edit_vendor->state);
            $Cities = Cities::findOrFail($edit_vendor->city);
            return view('Admin.vendor.show',compact('edit_vendor','Countries','States','Cities'));
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
        if(auth()->user()->can('Vendor-edit')) {
            $edit_vendor= Vendor::find($id);
            $Countries = DB::table('countries')->orderby('name','asc')->select('id','name')->get();
            return view('Admin.vendor.create',compact('edit_vendor','Countries'));
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
        if(auth()->user()->can('Vendor-update')) {
            $request->validate([
                'vendor_name'=> 'required',
                'vendor_contactperson'=> 'required',
                'vendor_email'=> 'required',
                'vendor_phone'=> 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                'vendor_street'=> 'required',
                'vendor_city'=> 'required',
                'vendor_state'=> 'required',
                'vendor_country'=> 'required',
                'vendor_zipcode'=> 'required',
                'vendor_status'=>'required'
            ]);

            $Vendor = Vendor::find($id);
            $Vendor->vendor_name=$request->vendor_name;
            $Vendor->vendor_contactperson=$request->vendor_contactperson;
            $Vendor->vendor_email=$request->vendor_email;
            $Vendor->vendor_phone=$request->vendor_phone;
            $Vendor->Location=$request->Location;
            $Vendor->vendor_street=$request->vendor_street;
            $Vendor->city=$request->vendor_city;
            $Vendor->state=$request->vendor_state;
            $Vendor->country=$request->vendor_country;
            $Vendor->vendor_zipcode=$request->vendor_zipcode;
            $Vendor->vendor_GST=$request->vendor_GST;
            $Vendor->vendor_PAN=$request->vendor_PAN;
            $Vendor->vendor_type=$request->vendor_type;
            $Vendor->vendor_MSME=$request->vendor_MSME;
            $Vendor->vendor_MSME_number=$request->vendor_MSME_number;
            $Vendor->vendor_status=$request->vendor_status;
            $Vendor->save();                  

            return redirect()->route('vendor.index')->with('msg','Vendor Updated Successfuly.');
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
        if(auth()->user()->can('Vendor-destroy')) {
            $Vendor = Vendor::find($request->id)->delete();
            return response()->json(['msg'=>'Vendor deleted successfully.']);
        }
    }

    public function Status(Request $request)
    {
        $id_array = $request->id;
        $flag= $request->value;
       
        $Roles = Vendor::whereIn('id',$id_array)->update(["vendor_status"=>$flag]);
       
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
