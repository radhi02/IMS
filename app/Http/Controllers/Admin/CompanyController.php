<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Company;
use Auth;
use DB;
class CompanyController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:Company-index|Company-create|Company-show|Company-destroy', ['only' => ['index','show']]);
        $this->middleware('permission:Company-create', ['only' => ['create','store']]);
        $this->middleware('permission:Company-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:Company-destroy', ['only' => ['destroy']]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(auth()->user()->can('Company-index')) {

            $img = $request->file('Logoimage');

            if (!empty($img) && $img->getClientOriginalName() != "") {
                $filename = $img->getClientOriginalName();
                $img->move(public_path('Admin/Company'), $filename);
            }

            $request->validate([
                "company_name"=>"required",
                "email"=>"required",
                "contact_no"=>"required",
                "reg_off_add"=>"required",
                "factory_add"=>"required",
                "edit_company_country"=>"required",
                "edit_company_state"=>"required",
                "edit_company_city"=>"required",
                "pincode"=>"required",
                "gst_no"=>'nullable|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/',
                "pan_no"=>'nullable|regex:/^[A-Za-z]{5}[0-9]{4}[A-Z]{1}$/',
            ],
            [
                "company_name.required"=>"Please enter Company  Name",
                "email.required"=>"Please enter Company Email",
                "contact_no.required"=>"Please enter Contact Number",
                "reg_off_add.required"=>"Please enter Registered Office Address",
                "factory_add.required"=>"Please enter Factory Address",
                "edit_company_country.required"=>"Please select Country",
                "edit_company_state.required"=>"Please select State",
                "edit_company_city.required"=>"Please select City",
                "pincode.required"=>"Please enter pincode",
                "gst_no.regex"=>"Please enter valid GST Number",
                "pan_no.regex"=>"Please enter valid PAN Number",
            ]);

            $data = [
                'company_code'=>"COMPANY001",
                'company_name'=>$request->company_name,
                'email'=>$request->email,
                'contact_no'=>$request->contact_no,
                'reg_off_add'=>nl2br($request->reg_off_add),
                'factory_add'=>nl2br($request->factory_add),
                'country'=>$request->edit_company_country,
                'state'=>$request->edit_company_state,
                'city'=>$request->edit_company_city,
                'pincode'=>$request->pincode,
                'gst_no'=>$request->gst_no,
                'pan_no'=>$request->pan_no,
                'website'=>$request->website,
                'otherdetails'=>$request->otherdetails,
                'logo'=>(isset($filename) ? $filename : null),
            ];

            $check = null;
            if($request->comp_id != '') {
                $check = Company::where('id',$request->comp_id)->first();
            }
            if($check == null){
                $data['created_by'] =Auth::user()->id;
                $Company = Company::create($data);
            } else {
                $data['updated_by'] =Auth::user()->id;
                $Company = Company::where('id', $request->comp_id)->update($data);
            }

            return redirect()->route('company.create')->with('msg','Company Details updated Successfuly.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->can('Company-create')) {
            $edit_company= Company::first();
            $Countries = DB::table('countries')->orderby('name','asc')->select('id','name')->get();
            return view('admin.company.create',compact('edit_company','Countries'));
        }
    }

    public function Status(Request $request)
    {
        $id_array = $request->id;
        $flag= $request->value;
         $Company = Company::whereIn('id',$id_array)->update(["status"=>$flag,"updated_by"=>Auth::user()->id]);
        if($Company == true)
        {
            return response()->json(['msg'=>1]);
        }
        else
        {
            return response()->json(['msg'=>0]);
        }
    }
}
