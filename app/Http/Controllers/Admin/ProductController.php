<?php

namespace App\Http\Controllers\Admin;
use App\Models\Admin\Unit;
use App\Models\Admin\SubCategory;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use App\Models\Admin\MaterialCategory;
use App\Models\Admin\RawMaterial;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Auth;
USE DB;

class ProductController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:Product-index|Product-create|Product-show|Product-destroy', ['only' => ['index','show']]);
        $this->middleware('permission:Product-create', ['only' => ['create','store']]);
        $this->middleware('permission:Product-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:Product-destroy', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->can('Product-index')) {
            $Product = Product::join('categorys', 'categorys.id', '=', 'product.category_id')
            ->join('subcategorys', 'subcategorys.id', '=', 'product.sub_category_id')
            ->select('product.*','categorys.name as catname','subcategorys.name as subcatname')
            ->get();
            return view('Admin.product.index',compact('Product'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->can('Product-create')) {
            $Category = Category::select("name", "id")->where('status','Active')->get();
            $MaterialCategory = MaterialCategory::select("name", "id")->where('status','Active')->get();
            return view('Admin.product.create',compact('Category','MaterialCategory'));
        }
    }
    public function getRawMaterialList(Request $request)
    {
        $RawMaterial = RawMaterial::select("name", "id")->where('status','Active')->where('category_id',$request->id)->get();
        return response()->json($RawMaterial); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(auth()->user()->can('Product-store')) {
            $request->validate([
                'name'=> 'required',
                'category_id'=> 'required',
                'sub_category_id'=> 'required',
                'sku'=> 'required',
                'status'=> 'required',
            ]);
            
            $tmpBOM = [];
            $rawids = $request->rawids;
            $rawunit = $request->rawunit;
            $rawqun = $request->rawqun;
            foreach($rawids as $k=>$v) {
                $tmpBOM[$v] = [
                    'id'=>$v,
                    'quantity'=>(isset($tmpBOM[$v])?$tmpBOM[$v]['quantity']+$rawqun[$k]:$rawqun[$k]),
                    'unitid'=>$rawunit[$k]
                ];
            }
            $data = [
                'name'=> $request->name,
                'category_id'=>$request->category_id,
                'sub_category_id'=>$request->sub_category_id,
                'sku'=>$request->sku,
                'description'=>$request->description,
                'status'=>$request->status,
                'product_BOM'=>json_encode($tmpBOM),
            ];
            $create = Product::create($data);
            return redirect()->route('product.index')->with('msg','Product created successfully.');
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
        if(auth()->user()->can('Product-show')) {
            $Product= Product::findOrFail($id);
            $Category = Category::findOrFail($Product->category_id);
            $SubCategory = SubCategory::findOrFail($Product->sub_category_id);
            return view('Admin.product.show',compact('Product','Category','SubCategory'));
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
        if(auth()->user()->can('Product-edit')) {
            $Product= Product::findOrFail($id);
            $Category = Category::select("name", "id")->where('status','Active')->get();
            $MaterialCategory = MaterialCategory::select("name", "id")->where('status','Active')->get();
            $Unit = Unit::select("unit_name", "id")->where('status','Active')->get();
            return view('Admin.product.create',compact('Product','Category','MaterialCategory','Unit'));
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
        if(auth()->user()->can('Product-update')) {
            $request->validate([
                'name'=> 'required',
                'category_id'=> 'required',
                'sub_category_id'=> 'required',
                'sku'=> 'required',
                'status'=> 'required',
            ]);

            $tmpBOM = [];
            $rawids = $request->rawids;
            $rawunit = $request->rawunit;
            $rawqun = $request->rawqun;
            foreach($rawids as $k=>$v) {
                $tmpBOM[$v] = [
                    'id'=>$v,
                    'quantity'=>(isset($tmpBOM[$v])?$tmpBOM[$v]['quantity']+$rawqun[$k]:$rawqun[$k]),
                    'unitid'=>$rawunit[$k]
                ];
            }

            $Product = Product::find($id);
            $Product->name=$request->name;
            $Product->category_id=$request->category_id;
            $Product->sub_category_id=$request->sub_category_id;
            $Product->sku=$request->sku;
            $Product->description=$request->description;
            $Product->status=$request->status;
            $Product->product_BOM=json_encode($tmpBOM);
            $Product->save();                  
            session()->flash('msg', 'Product Updated Successfuly.');
            return redirect()->route('product.index');
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
        if(auth()->user()->can('Product-destroy')) {
            $Product = Product::find($request->id)->delete();
            return response()->json(['msg'=>'Product deleted successfully.']);  
        }
    }

    public function Status(Request $request)
    {
        $id_array = $request->id;
        $flag= $request->value;
       
        $Roles = Product::whereIn('id',$id_array)->update(["status"=>$flag]);
       
        if($Roles == true)
        {
            return response()->json(['msg'=>1]);
        }
        else
        {
            return response()->json(['msg'=>0]);
        }
    }

    public function addRawMaterial(Request $request){
        // $data['rawmaterials'] = RawMaterial::where("id",$request->id)->get();
        // $data['UnitList'] = Unit::select("unit_name", "id")->where('status','Active')->get();
        $data['rawmaterials'] = RawMaterial::join('units', 'units.id', '=', 'raw_material.unit_id')
        ->select('raw_material.*','units.unit_name','units.id as unit_id')
        ->where("raw_material.id",$request->id)
        ->get();
        return response()->json($data);
    }
}
