<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\Country;
class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
           $State=State::where('deleted_at',null)->get();
           return view('admin.state.index',compact('State'));
      
    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Country= Country::where('status','Active')->where('deleted_at',null)->get();
       return view('admin.state.create',compact('Country'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $validation =   $request->validate([
            "CountryName"=>"required",
            "name"=>"required|unique:states",
            "status"=>"required",
        ],
        [ "CountryName.required"=>"Please Select Country",
        "name.required"=>"Please Enter State Name",
        "status.required"=>"Please Select Status",]
        );
        if($validation == true)
        {
            $State =State::create([
                "country_id"=>$request->CountryName,
                "name"=>$request->name,
                "status"=>$request->status,
            ]);

            session()->flash('msg', 'State Created Successfuly.');
            return redirect()->route('state.index');
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
  
        $state= State::find($id);
        
        return view('admin.state.show',compact('state'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Country= Country::where('status','Active')->where('deleted_at',null)->get();
        $state= State::find($id);
        
        return view('admin.state.create',compact('Country','state'));
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
        $validation =   $request->validate([
            "CountryName"=>"required",
            "name"=>"required|unique:states,name,".$id,
            "status"=>"required",
        ],
        [ "CountryName.required"=>"Please Select Country",
        "statename.required"=>"Please Enter State Name",
        "status.required"=>"Please Select Status",]
        );

        if($validation == true)
        {
            $State =State::find($id)->update([
                "CountryName"=>$request->CountryName,
                "name"=>$request->name,
                "status"=>$request->status,
            ]);
            session()->flash('msg', 'State Updated Successfuly.');
            return redirect()->route('state.index');
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
        $State = State::find($request->id)->delete();
        return response()->json(['msg'=>'State deleted successfully.']);
    }

    public function Status(Request $request)
    {
        $id_array = $request->id;
        $flag= $request->value;
        $State = State::whereIn('id',$id_array)->update(["status"=>$flag]);
        if($State == true)
        {
            return response()->json(['msg'=>1]);
        }
        else
        {
            return response()->json(['msg'=>0]);
        }
    }
}
