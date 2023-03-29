<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Admin\Lead;
use App\Models\Admin\Customer;
use App\Models\Admin\SubCategory;
use App\Models\Admin\Category;
use Illuminate\Http\Request;
use View;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Lead = Lead::all();
        return view('Admin.lead.index',compact('Lead'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Customer = Customer::select("customer_name", "id")->where('customer_status','Active')->get();
        $Category = Category::select("name", "id")->where('status','Active')->get();
        $SubCategory = [];
        return view('Admin.lead.create',compact('Customer','Category','SubCategory'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'comment'=> 'required',
            'customer_id'=> 'required',
            'category'=> 'required',
            'subcategory'=> 'required',
            'status'=> 'required',
        ]);
        
        $create = Lead::create([
            'comment'=> $request->comment,
            'status'=> $request->status,
            'cat_ids'=> implode(',',$request->category),
            'subcat_ids'=> implode(',',$request->subcategory),
            'customer_id'=> $request->customer_id,
       ]);
       return redirect()->route('lead.index')->with('msg','Lead created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Lead= Lead::findOrFail($id);
        $CategoryDetails = SubCategory::leftjoin('categorys as C', 'C.id', '=', 'subcategorys.category_id')->whereIn('subcategorys.id',explode(',',$Lead['subcat_ids']))->get(['subcategorys.id as SId','subcategorys.name as SName','subcategorys.description as SDes','C.id as CId','C.name as CName','C.description as CDes']);
        return view('Admin.lead.show',compact('Lead','CategoryDetails'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Lead= Lead::findOrFail($id);
        $Category = Category::select("name", "id")->where('status','Active')->get();
        $SubCategory = SubCategory::select("name", "id")->where('status','Active')->whereIn('category_id',explode(',',$Lead['cat_ids']))->get();
        $Customer = Customer::select("customer_name", "id")->where('customer_status','Active')->get();
        return view('Admin.lead.create',compact('Lead','Customer','Category','SubCategory'));
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
        $request->validate([
            'comment'=> 'required',
            'customer_id'=> 'required',
            'category'=> 'required',
            'subcategory'=> 'required',
            'status'=> 'required',
        ]);
        $Lead = Lead::find($id);
        $Lead->comment=$request->comment;
        $Lead->customer_id=$request->customer_id;
        $Lead->status=$request->status;
        $Lead->cat_ids=implode(',',$request->category);
        $Lead->subcat_ids=implode(',',$request->subcategory);
        $Lead->save();                  
        session()->flash('msg', 'Lead Updated Successfuly.');
        return redirect()->route('lead.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $Lead = Lead::find($request->id)->delete();
        return response()->json(['msg'=>'Lead deleted successfully.']);  
    }

    public function Status(Request $request)
    {
        $id_array = $request->id;
        $flag= $request->value;
       
        $Roles = Lead::whereIn('id',$id_array)->update(["status"=>$flag]);
       
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
