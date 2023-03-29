<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\State;
use DataTables;
use App\Jobs\GetCityRecord;

class CityController extends Controller
{
    public function index(Request $request)
    {
        

        if($request->ajax()) {
        

            $data = City::with('state')->take(1);
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('checkbox', function($row1){ $btn1 = '<input type="checkbox" class="checkbox" name="ids[]"  value="'. $row1->id .'" data-id="'. $row1->id .'" />'; return $btn1; })
                    ->addColumn('action', function($row){

                          $btn = '<a href="'.route("city.show", $row->id) .'"class="btn btn-primary"><i class="fa fa-eye"></i></a>&nbsp;';

                           $btn = $btn.'<a href="'.route("city.edit", $row->id) .'" class="btn btn-success" ><i class="fa fa-edit"></i></a>';
                           $btn = $btn.' <button href="#" data-id="'.$row->id.'" data-cityname="'.$row->name.'" class="btn btn-danger Delete"><i class="fa fa-trash-o"></i></button>';

                           return $btn;
                    })
                    ->addColumn('status', function($row2){ if($row2->status=='Active') {$btn2 = '<span class="badge badge-success">'.$row2->status.'</span>';} else { $btn2 = '<span class="badge badge-danger">'.$row2->status.'</span>'; } return $btn2; })
                    ->rawColumns([ 'checkbox', 'action', 'status'])
                    ->make(true);
        }

        return view('admin.city.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $state= State::where('status','Active')->where('deleted_at',null)->get();
        // $City= City::where('status','Active')->where('deleted_at',null)->get();
       return view('admin.city.create',compact('state'));
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
            "StateName"=>"required",
            "name"=>"required|unique:cities",
            "status"=>"required",
        ],
        [ "StateName.required"=>"Please Select State",
        "name.required"=>"Please Enter State Name",
        "status.required"=>"Please Select Status",]
        );
        if($validation == true)
        {
            $State =City::create([
                "state_id"=>$request->StateName,
                "name"=>$request->name,
                "status"=>$request->status,
            ]);

            session()->flash('msg', 'City Created Successfuly.');
            return redirect()->route('city.index');
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
     
        $City= City::where('status','Active')->where('deleted_at',null)->find($id);
       return view('admin.city.show',compact('City'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $state= State::where('status','Active')->where('deleted_at',null)->get();
         $City= City::where('status','Active')->where('deleted_at',null)->where('id',$id)->first();
       return view('admin.city.create',compact('state','City'));
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
            "StateName"=>"required",
            "name"=>"required,id,".$id,
            "status"=>"required",
        ],
        [ "StateName.required"=>"Please Select State",
        "name.required"=>"Please Enter State Name",
        "status.required"=>"Please Select Status",]
        );
        if($validation == true)
        {
            $State =City::create([
                "state_id"=>$request->StateName,
                "name"=>$request->name,
                "status"=>$request->status,
            ]);

            session()->flash('msg', 'City Updated Successfuly.');
            return redirect()->route('city.index');
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
     
          $State = City::find($request->id)->delete();
        return response()->json(['msg'=>'City deleted successfully.']);
    }

    public function Status(Request $request)
    {
        $id_array = $request->id;
        $flag= $request->value;
        $State = City::whereIn('id',$id_array)->update(["status"=>$flag]);
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
