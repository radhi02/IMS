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
use Illuminate\Support\Facades\Validator;
use Hash;
use Auth;
USE DB;
class CustomerController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:Customer-index|Customer-create|Customer-show|Customer-destroy', ['only' => ['index','show']]);
        $this->middleware('permission:Customer-create', ['only' => ['create','store']]);
        $this->middleware('permission:Customer-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:Customer-destroy', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->can('Customer-index')) {
            $customer = Customer::all();
            return view('Admin.customer.index',compact('customer'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->can('Customer-create')) {
            $Countries = DB::table('countries')->orderby('name','asc')->select('id','name')->get();
            return view('Admin.customer.create',compact('Countries'));
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
        if(auth()->user()->can('Customer-store')) {
            //customerLastID
            $get_perfectLast_id = customerLastID();
            $request->validate([
                'customer_name'=> 'required|max:20',
                'customer_contactperson'=> 'required|max:20',
                'customer_email'=> 'required|max:40',
                'customer_phone'=> 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20',
                'customer_street'=> 'required|min:5|max:20',
                'customer_city'=> 'required',
                'customer_state'=> 'required',
                'customer_country'=> 'required',
                'customer_zipcode'=> 'required|min:6|max:6',
                'customer_PAN'=> 'nullable|regex:/^[A-Za-z]{5}[0-9]{4}[A-Z]{1}$/',
                'customer_GST'=> 'nullable|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/',
                ],[
                'customer_PAN.regex'=> 'The customer PAN No. is invalid',
                'customer_GST.regex'=> 'The customer GST No. is invalid', ]
            );

            $create = Customer::create([
                'customer_name'=> $request->customer_name,
                'customer_code'=>$get_perfectLast_id,
                'customer_contactperson'=> $request->customer_contactperson,                            
                'customer_email'=> $request->customer_email,
                'customer_phone'=>$request->customer_phone,
                'Location'=>$request->Location,
                'customer_street'=> $request->customer_street,
                'city'=> $request->customer_city,
                'state'=> $request->customer_state,
                'country'=> $request->customer_country,
                'customer_zipcode'=> $request->customer_zipcode,
                'customer_GST'=> $request->customer_GST,
                'customer_PAN'=> $request->customer_PAN,
                'customer_status'=>$request->customer_status,
            ]);
            return redirect()->route('customer.index')->with('msg','Customer Created Successfully.');
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
        if(auth()->user()->can('Customer-show')) {
            $customer= Customer::findOrFail($id);
            $roles = Role::all();
            $Countries = Countries::findOrFail($customer->country);
            $States  = States::findOrFail($customer->state);
            $Cities = Cities::findOrFail($customer->city);
            return view('Admin.customer.show',compact('customer','Countries','States','Cities'));
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
        if(auth()->user()->can('Customer-edit')) {
            $customer= Customer::find($id);
            $Countries = DB::table('countries')->orderby('name','asc')->select('id','name')->get();
            $States  = States::find($customer->state);
            $Cities = Cities::find($customer->city);
            return view('Admin.customer.create',compact('customer','Countries','States','Cities'));
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
        if(auth()->user()->can('Customer-update')) {

            $request->validate([
                'customer_name'=> 'required|max:20',
                'customer_contactperson'=> 'required|max:20',
                'customer_email'=> 'required|max:40',
                'customer_phone'=> 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20',
                'customer_street'=> 'required|min:5|max:20',
                'customer_city'=> 'required',
                'customer_state'=> 'required',
                'customer_country'=> 'required',
                'customer_zipcode'=> 'required|min:6|max:6',
                'customer_PAN'=> 'nullable|regex:/^[A-Za-z]{5}[0-9]{4}[A-Z]{1}$/',
                'customer_GST'=> 'nullable|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/',
                ],[
                'customer_city.required'=> 'The customer city field is required',
                'customer_state.required'=> 'The customer state field is required',
                'customer_country.required'=> 'The customer country field is required',
                'customer_PAN.regex'=> 'The customer PAN No. is invalid',
                'customer_GST.regex'=> 'The customer GST No. is invalid', ]
            );
            $customer = Customer::find($id);
            $customer->customer_name=$request->customer_name;
            $customer->customer_contactperson=$request->customer_contactperson;
            $customer->customer_email=$request->customer_email;
            $customer->customer_phone=$request->customer_phone;
            $customer->Location=$request->Location;
            $customer->customer_street=$request->customer_street;
            $customer->city=$request->customer_city;
            $customer->state=$request->customer_state;
            $customer->country=$request->customer_country;
            $customer->customer_zipcode=$request->customer_zipcode;
            $customer->customer_GST=$request->customer_GST;
            $customer->customer_PAN=$request->customer_PAN;
            $customer->customer_status=$request->customer_status;
            $customer->save();                  

            return redirect()->route('customer.index')->with('msg','Customer Updated Successfuly.');
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
        if(auth()->user()->can('Customer-destroy')) {
            $customer = Customer::find($request->id)->delete();
            return response()->json(['msg'=>'Customer Deleted Successfully.']);
        }
    }
    public function Status(Request $request)
    {
        $id_array = $request->id;
        $flag= $request->value;
       
        $Roles = Customer::whereIn('id',$id_array)->update(["customer_status"=>$flag]);
       
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
