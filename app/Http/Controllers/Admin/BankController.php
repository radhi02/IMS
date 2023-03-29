<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banks;
use Auth;
class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bank = Banks::where('deleted_at',null)->get();
        return view('admin.bank.index',compact('bank'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.bank.create');
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
            "BName"=>"required|unique:banks",
            "BIFSC"=>"required",
            "Branch"=>"required",
            "Baccount"=>"required",
            "status"=>"required"
        ],
        [
            "BName.required"=>"Please Ener Bank Name",
            "BIFSC.required"=>"Please Ener IFSC Code",
            "Branch.required"=>"Please Ener Branch Name",
            "Baccount.required"=>"Please Ener Account Number",
            "status.required"=>"Please Ener status",
        ]);

        $Banks = Banks::create([
            "BName"=>$request->BName,
            "BIFSC"=>$request->BIFSC,
            "BSWIFTCODE"=>$request->BSWIFTCODE,
            "Branch"=>$request->Branch,
            "Baccount"=>$request->Baccount,
            "BMICR"=>$request->BMICR,
            "status"=>$request->status,
            "created_by"=>Auth::user()->id,
            // "comp_id"=> Auth::user()->comp_id,
        ]);
        return redirect()->route('bank.index')->with('msg','Bank  Created Successfuly.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Banks= Banks::find($id);
        return view('admin.bank.show',compact('Banks'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $edit_bank= Banks::find($id);
         return view('admin.bank.create',compact('edit_bank'));
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
            'BName' => 'required|unique:banks,BName,'.$id,
            "BIFSC"=>"required",
            "Branch"=>"required",
            "Baccount"=>"required",
            "status"=>"required"
        ],
        [
            "BName.required"=>"Please Ener Bank Name",
            "BIFSC.required"=>"Please Ener IFSC Code",
            "Branch.required"=>"Please Ener Branch Name",
            "Baccount.required"=>"Please Ener Account Number",
            "status.required"=>"Please Ener status",
        ]);

        $Banks = Banks::where('id',$id)->update([
            "BName"=>$request->BName,
            "BIFSC"=>$request->BIFSC,
            "BSWIFTCODE"=>$request->BSWIFTCODE,
            "Branch"=>$request->Branch,
            "Baccount"=>$request->Baccount,
            "BMICR"=>$request->BMICR,
            "status"=>$request->status,
            "updated_by"=>Auth::user()->id,
            // "comp_id"=> Auth::user()->comp_id,
        ]);
        return redirect()->route('bank.index')->with('msg','Bank  Updated Successfuly.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $State = Banks::find($request->id)->delete();
        return response()->json(['msg'=>'Banks deleted successfully.']);
    }

    public function Status(Request $request)
    {
        $id_array = $request->id;
        $flag= $request->value;
         $Banks = Banks::whereIn('id',$id_array)->update(["status"=>$flag,"updated_by"=>Auth::user()->id]);
        if($Banks == true)
        {
            return response()->json(['msg'=>1]);
        }
        else
        {
            return response()->json(['msg'=>0]);
        }
    }
}
