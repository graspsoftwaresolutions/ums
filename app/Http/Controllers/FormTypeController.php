<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Crypt;
use App\Model\FormType;


use Illuminate\Http\Request;

class FormTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = FormType::all();
        return view('formType.formtype')->with('data',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('formType.add_formtype');
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
            'formname'=>'required',
        ],
        [
            'formname.required'=>'Please enter Form name',
        ]);
        $FormType = new FormType();
        $FormType->formname = $request->formname;
        $FormType->orderno = $request->orderno;
        $FormType->save();

        $defdaultLang = app()->getLocale();
        return redirect($defdaultLang.'/formtype')->with('message','FormType Details Added Successfully!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($lang,$id)
    {
        $id = Crypt::decrypt($id);
        
        $FormType = new FormType();
        $data['form_edit'] = FormType::find($id);
       // return $data; 
        return view('formType.edit_formtype')->with('data',$data);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$lang,$id)
    {
        $request->validate([
            'formname'=>'required',
        ],
        [
            'formname.required'=>'Please enter Form name',
        ]);
       
        $FormType = new FormType();
        $FormType = FormType::find($id);
        $FormType->formname = $request->formname;
        $FormType->orderno = $request->orderno;
        $FormType->status = $request->status;
        $FormType->save();

        $defdaultLang = app()->getLocale();
        return redirect($defdaultLang.'/formtype')->with('message','FormType Details Added Successfully!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($lang,$id)
    {
        $FormType = new FormType();
        $FormType = FormType::find($id);
        $Role->delete();

        $defdaultLang = app()->getLocale();
        return redirect($defdaultLang.'/formtype')->with('message','FormType Details Deleted Successfully!!');
    }
}
