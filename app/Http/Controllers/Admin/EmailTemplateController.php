<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\EmailTemplate;
use Illuminate\Http\Request;
use Auth;
class EmailTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $email = EmailTemplate::where('deleted_at',NULL)->get();
        return view('admin.email_template.index', compact('email'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin\EmailTemplate  $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('admin.email_template.show', ['email' => EmailTemplate::findOrFail($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin\EmailTemplate  $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('admin.email_template.create', ['email' => EmailTemplate::findOrFail($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\EmailTemplate  $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // echo '<pre>'; print_r($request->all()); exit;
        $request->validate([
            'content' => 'required',
            'subject' => 'required',
            //'status' => 'required',
        ]);
        
        EmailTemplate::where('id', $id)->update([
            'subject' => $request->subject,
            'content' => $request->content
        ]);

        return redirect()->route('email_template.index')
                        ->with('msg','Email Template updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin\EmailTemplate  $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $email = EmailTemplate::find($request->id)->delete();

        return response()->json(['msg'=>'Email Template deleted successfully.']);
    }

    /**
     * Change the status of the specified resource from storage.
     *
     * @param  \App\Models\admin\EmailTemplate  $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function Status(Request $request)
    {
        $id_array = $request->id;
        $flag = $request->value;
        $email = EmailTemplate::whereIn('id',$id_array)->update(["status"=>$flag]);
        if($email == true)
        {
            return response()->json(['msg'=>1]);
        }
        else
        {
            return response()->json(['msg'=>0]);
        }
    }
}
