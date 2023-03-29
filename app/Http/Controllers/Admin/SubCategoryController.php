<?php

namespace App\Http\Controllers\Admin;
use App\Models\Admin\SubCategory;
use App\Models\Admin\Category;
use App\Models\Admin\Unit;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class SubCategoryController extends Controller
{
    
    function __construct()
    {
        $this->middleware('permission:SubCategory-index|SubCategory-create|SubCategory-show|SubCategory-destroy', ['only' => ['index','show']]);
        $this->middleware('permission:SubCategory-create', ['only' => ['create','store']]);
        $this->middleware('permission:SubCategory-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:SubCategory-destroy', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->can('SubCategory-index')) {
            $SubCategory = DB::table('subcategorys')
            ->select('subcategorys.*','categorys.name as cname')
            ->join('categorys', 'categorys.id', '=', 'subcategorys.category_id')
            ->get();
            return view('Admin.subcategory.index',compact('SubCategory'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->can('SubCategory-create')) {
            $Category = Category::select("name", "id")->where('status','Active')->get();
            return view('Admin.subcategory.create',compact('Category'));
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
        if(auth()->user()->can('SubCategory-store')) {
            $request->validate([
                'name'=> 'required',
                
            ]);
            $create = SubCategory::create([
                'name'=> $request->name,
                'category_id'=>$request->category_id,
                'description'=>$request->description,
                'status'=>$request->status,            
            ]);
            return redirect()->route('subcategory.index')->with('msg','Sub Category created successfully.');
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
        if(auth()->user()->can('SubCategory-show')) {
            $SubCategory= SubCategory::findOrFail($id);
            $Category = Category::findOrFail($SubCategory->category_id);
            return view('Admin.subcategory.show',compact('SubCategory','Category'));
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
        if(auth()->user()->can('SubCategory-edit')) {
            $SubCategory= SubCategory::findOrFail($id);
            $Category = Category::select("name", "id")->where('status','Active')->get();
            return view('Admin.subcategory.create',compact('SubCategory','Category'));
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
        if(auth()->user()->can('SubCategory-update')) {
            $request->validate([
                'name'=> 'required',
            ]);
            $SubCategory = SubCategory::find($id);
            $SubCategory->name=$request->name;
            $SubCategory->category_id=$request->category_id;
            $SubCategory->description=$request->description;
            $SubCategory->status=$request->status;
            $SubCategory->save();                  
            return redirect()->route('subcategory.index')->with('msg','Sub Category Updated successfully.');
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
        if(auth()->user()->can('SubCategory-destroy')) {
            $SubCategory = SubCategory::find($request->id)->delete();
            return response()->json(['msg'=>'SubCategory deleted successfully.']);  
        }
    }

    public function Status(Request $request)
    {
        $id_array = $request->id;
        $flag= $request->value;
       
        $Roles = SubCategory::whereIn('id',$id_array)->update(["status"=>$flag]);
       
        if($Roles == true)
        {
            return response()->json(['msg'=>1]);
        }
        else
        {
            return response()->json(['msg'=>0]);
        }
    }
    
    public function fetchSubCategory(Request $request)
    {
        $data['subcategories'] = SubCategory::where("category_id",$request->id)->get(["name", "id"]);
        return response()->json($data);
    }

}
