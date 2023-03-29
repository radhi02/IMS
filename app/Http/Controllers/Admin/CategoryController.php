<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:Category-index|Category-create|Category-show|Category-destroy', ['only' => ['index','show']]);
        $this->middleware('permission:Category-create', ['only' => ['create','store']]);
        $this->middleware('permission:Category-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:Category-destroy', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->can('Category-index')) {
            $Category = Category::all();
            return view('Admin.category.index',compact('Category'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->can('Category-create')) {
            return view('Admin.category.create');
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
        if(auth()->user()->can('Category-store')) {
            $request->validate([
                'name'=> 'required',
                
            ]);
            $create = Category::create([
                'name'=> $request->name,
                'description'=> $request->description,
                'status'=> $request->status,
            ]);

            $NotificationCODE = $request->name;
            $NotificationMSG = 'has created new category';
            $NotificationURL = url()->current();            
            SendCustomNotifications($NotificationMSG,$NotificationCODE,$NotificationURL);

            return redirect()->route('category.index')->with('msg','Category created successfully.');
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
        if(auth()->user()->can('Category-show')) {
            $Category= Category::findOrFail($id);
            return view('Admin.category.show',compact('Category'));
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
        if(auth()->user()->can('Category-edit')) {
            $Category= Category::findOrFail($id);
            return view('Admin.category.create',compact('Category'));
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
        if(auth()->user()->can('Category-update')) {
            $request->validate([
                'name'=> 'required',
            ]);
            $Category = Category::find($id);
            $Category->name=$request->name;
            $Category->description=$request->description;
            $Category->status=$request->status;
            $Category->save();                  

            $NotificationCODE = $request->name;
            $NotificationMSG = 'has updated category';
            $NotificationURL = url()->current();            
            SendCustomNotifications($NotificationMSG,$NotificationCODE,$NotificationURL);

            return redirect()->route('category.index')->with('msg','Category Updated successfully.');
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
        if(auth()->user()->can('Category-destroy')) {
            $Category = Category::find($request->id)->delete();
            return response()->json(['msg'=>'Category deleted successfully.']);  
        }
    }

    public function Status(Request $request)
    {
        $id_array = $request->id;
        $flag= $request->value;
       
        $Roles = Category::whereIn('id',$id_array)->update(["status"=>$flag]);
       
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
