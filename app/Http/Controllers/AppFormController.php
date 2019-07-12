<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Model\AppForm;
use App\Model\FormType;
use View;
use DB;

class AppFormController extends Controller
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
        $data = AppForm::all();
        return view('appform.appform')->with('data',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['form_type'] = FormType::all();
        return view('appform.add_appform')->with('data',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //print_r( $request->all());die;
        $request->validate([
            'formname'=>'required',
        ],
        [
            'formname.required'=>'Please enter Form name'
        ]);
       
        $AppForm = new AppForm();
        $AppForm->formname = $request->formname;
        $AppForm->formtype_id = $request->formtype;
        $AppForm->orderno = $request->orderno;
        $AppForm->route = $request->route;
        $AppForm->isactive = $request->isactive;
        $AppForm->isinsert = $request->isinsert;
        $AppForm->isupdate = $request->isupdate;
        $AppForm->isdelete = $request->isdelete;
        $AppForm->ismenu = $request->ismenu;
        $AppForm->description = $request->description;
        
        $AppForm->save();

        $defdaultLang = app()->getLocale();
        return redirect($defdaultLang.'/appform')->with('message','Form Details Added Successfully!!');
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
        $auto_id = Crypt::decrypt($id);
        $data['form_type'] = FormType::all();
        $AppForm = new AppForm();
        $data['appform_edit'] = AppForm::find($auto_id);
       
        return view('appform.edit_appform')->with('data',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$lang, $id)
    {
        $request->validate([
            'formname'=>'required',
        ],
        [
            'formname.required'=>'Please enter Form name'
        ]);
 
        $AppForm = new AppForm();
        $AppForm = AppForm::find($id);
        $AppForm->formname = $request->formname;
        $AppForm->formtype_id = $request->formtype;
        $AppForm->orderno = $request->orderno;
        $AppForm->route = $request->route;
        $AppForm->isactive = $request->isactive;
        $AppForm->isinsert = $request->isinsert;
        $AppForm->isupdate = $request->isupdate;
        $AppForm->isdelete = $request->isdelete;
        $AppForm->ismenu = $request->ismenu;
        $AppForm->description = $request->description;
        
        $AppForm->save();

        $defdaultLang = app()->getLocale();
        return redirect($defdaultLang.'/appform')->with('message','Form Details Updated Successfully!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($lang,$id)
    {
       // return "hiii"; 
       
        $AppForm = new AppForm();
        $AppForm = AppForm::find($id);
        $AppForm->delete();

        $defdaultLang = app()->getLocale();
        return redirect($defdaultLang.'/appform')->with('message','Form Details Deleted Successfully!!');
    }
}
