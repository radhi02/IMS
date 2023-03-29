<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Godown;
use App\Models\Banks;
use App\Models\Admin\Company;
use DB;
class GodownController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Godown = Godown::where('deleted_at',NULL)->get();
        return view('admin.godown.index', compact('Godown'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Company = Company::where('status','Active')->where('deleted_at',NULL)->get();
        $Banks =  Banks::where('status','Active')->get();
        return view('admin.godown.create', compact('Company','Banks'));
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
            'company_id'=>'required',
            'godown_code' => 'required|unique:godown|max:255',
            'godown_name' => 'required|unique:godown|max:255',
            'Swift_Code' => 'required|unique:godown|max:255',
            'reg_off_add'=> 'required',
            'state'=> 'required',
            'city'=> 'required',
            // 'cin_no'=> 'required',
            // 'gst_no'=> 'required',
            'gst_no' => 'required|unique:godown|max:255',
            'pan_no'=> 'required',
            'email'=> 'required',
            'contact_no'=> 'required',
            'bank_name'=> 'required',
            'bank_acc_no'=> 'required',
            'ifsc'=> 'required',
            'status'=> 'required',
        ]);
        
        $datas=[];
        if(isset($request->otherdetails))
        {
            foreach($request->otherdetails as $key=>$details)
            { 
                    $datas[]=implode("=||=", $details);
            }
        }
        
    
        // $final_details = json_decode(implode(",",$datas));
        // dd();
    //  $OtherDetails = (array)$datas;

    Godown::create(
        [
            'comp_id'=>$request->company_id,
            'godown_code' => $request->godown_code,
            'godown_name' => $request->godown_name,
            'reg_off_add'=> $request->reg_off_add,
            'factory_add'=> $request->godown_add,
            'state'=> $request->godown_state,
            'city'=> $request->godown_city,
            'pincode'=> $request->pincode,
            'cin_no'=> $request->cin_no,
            'gst_no'=> $request->gst_no,
            'pan_no'=> $request->pan_no,
            'email'=> $request->email,
            'website'=> $request->website,
            'contact_no'=> $request->contact_no,
            'bank_name'=> $request->bank_name,
            'bank_acc_no'=> $request->bank_acc_no,
            'ifsc'=> $request->ifsc,
            'Swift_Code'=>$request->Swift_Code,
            'otherdetails'=> implode("=||=",$datas),
            'status' => $request->status,
        ]);

        session()->flash('msg', 'Godown  Created Successfuly.');
        return redirect()->route('godown.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $godown = Godown::find($id);
        return view('admin.godown.show', compact('godown'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Company = Company::where('status','Active')->where('deleted_at',NULL)->get();
        $godown = Godown::find($id);
         $Banks =  Banks::where('status','Active')->get();
        return view('admin.godown.create', compact('godown','Company','Banks'));
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
     

        $datas=[];
        
       
        if(isset($request->otherdetails))
        {
            foreach($request->otherdetails as $key=>$details){
                $datas[]=implode("=||=", $details);
        }
        }
    // //     $final_details = json_encode(implode("=||=",$datas));
        $request->validate([
            'company_id'=>'required',
            'godown_code' => 'required|unique:godown,godown_code,'.$id,
            'godown_name' => 'required|unique:godown,godown_name,'.$id,
            'reg_off_add'=> 'required',
            'state'=> 'required',
            'city'=> 'required',
            // 'cin_no'=> 'required',
            // 'gst_no'=> 'required',
            'gst_no' => 'required|unique:godown,gst_no,'.$id,
          
            
            'pan_no'=> 'required',
            'email'=> 'required',
            'contact_no'=> 'required',
            'bank_name'=> 'required',
            'bank_acc_no'=> 'required',
            'ifsc'=> 'required',
            'status'=> 'required',
        ]);
    //   dd($datas, $id);

    

        Godown::where('id',$id)->update(
        [
            'comp_id'=>$request->company_id,
            'godown_code' => $request->godown_code,
            'godown_name' => $request->godown_name,
            'reg_off_add'=> $request->reg_off_add,
            'factory_add'=> $request->godown_add,
            'state'=> $request->godown_state,
            'city'=> $request->godown_city,
            'pincode'=> $request->pincode,
            'cin_no'=> $request->cin_no,
            'gst_no'=> $request->gst_no,
            'pan_no'=> $request->pan_no,
            'email'=> $request->email,
            'website'=> $request->website,
            'contact_no'=> $request->contact_no,
            'bank_name'=> $request->bank_name,
            'bank_acc_no'=> $request->bank_acc_no,
            'ifsc'=> $request->ifsc,
            'Swift_Code'=>$request->Swift_Code,
            'otherdetails'=> implode("=||=",$datas),
            'status' => $request->status,
        ]);

        session()->flash('msg', 'Godown Updated Successfuly.');
        return redirect()->route('godown.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        
        $company = Godown::find($request->id)->delete();

        return response()->json(['msg'=>'Company deleted successfully.']);
    }

    /**
     * Change the status of the specified resource from storage.
     *
     * @param  \App\Models\Admin\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function Status(Request $request)
    {
        $id_array = $request->id;
        $flag = $request->value;
        $company = Godown::whereIn('id',$id_array)->update(["status"=>$flag]);
        if($company == true)
        {
            return response()->json(['msg'=>1]);
        }
        else
        {
            return response()->json(['msg'=>0]);
        }
    }
    public function DestroyOther(Request $request)
    {
        $name =   $request->datas;
         $id   =    $request->id;
         $string="";
      


         $datas =  DB::table('godown')->select('otherdetails')->where('id','=',$id)->where('deleted_at','=',null)->first();
    
         $array_other_details =  explode("=||=", $datas->otherdetails);

    
           $key = array_search($name, $array_other_details);
    

            if(isset($key))
            {   unset($array_other_details[$key]);
              
             
               
                $other =implode("=||=",$array_other_details);

          
               $data = Godown::where('id',$id)->update(['otherdetails'=>$other ]);
            
                return response()->json(['msg'=>1]);
           
            }                return response()->json(['msg'=>0]);

          
        }
}
