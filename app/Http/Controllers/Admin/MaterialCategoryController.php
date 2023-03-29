<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Admin\MaterialCategory;
use Illuminate\Http\Request;

class MaterialCategoryController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:Manufacture-index|Manufacture-create|Manufacture-show|Manufacture-destroy', ['only' => ['index','show']]);
        $this->middleware('permission:Manufacture-create', ['only' => ['create','store']]);
        $this->middleware('permission:Manufacture-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:Manufacture-destroy', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->can('Manufacture-index')) {
            $MaterialCategory = Materialcategory::all();
            return view('Admin.materialcategory.index',compact('MaterialCategory'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->can('Manufacture-create')) {
            return view('Admin.materialcategory.create');
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
        if(auth()->user()->can('Manufacture-store')) {
            $request->validate([
                'name'=> 'required',
                
            ]);
            $create = Materialcategory::create([
                'name'=> $request->name,
                'description'=> $request->description,
                'status'=> $request->status,
            ]);
            return redirect()->route('materialcategory.index')->with('msg','Material Category created successfully.');
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
        if(auth()->user()->can('Manufacture-show')) {
            $MaterialCategory= Materialcategory::findOrFail($id);
            return view('Admin.materialcategory.show',compact('MaterialCategory'));
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
        if(auth()->user()->can('Manufacture-edit')) {
            $MaterialCategory= Materialcategory::findOrFail($id);
            return view('Admin.materialcategory.create',compact('MaterialCategory'));
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
        if(auth()->user()->can('Manufacture-update')) {
            $request->validate([
                'name'=> 'required',
            ]);
            $MaterialCategory = Materialcategory::find($id);
            $MaterialCategory->name=$request->name;
            $MaterialCategory->description=$request->description;
            $MaterialCategory->status=$request->status;
            $MaterialCategory->save();                  
            return redirect()->route('materialcategory.index')->with('msg','Material Category Updated successfully.');
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
        if(auth()->user()->can('Manufacture-destroy')) {
            $MaterialCategory = Materialcategory::find($request->id)->delete();
            return response()->json(['msg'=>'Material Category deleted successfully.']);  
        }
    }

    public function Status(Request $request)
    {
        $id_array = $request->id;
        $flag= $request->value;
       
        $Roles = Materialcategory::whereIn('id',$id_array)->update(["status"=>$flag]);
       
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
