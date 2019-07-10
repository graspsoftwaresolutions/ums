<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Role;

class RolesController extends Controller
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
        $data = Role::all();
        return view('roles.roles')->with('data',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('roles.add_roles');
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
            'name'=>'required',
            'slug' => 'required'
        ],
        [
            'name.required'=>'Please enter Role name',
            'slug.required'=>'Please enter Slug name'
        ]);
       
        $Role = new Role();
        $Role->name = $request->name;
        $Role->slug = $request->slug;
        $Role->save();

        $defdaultLang = app()->getLocale();
        return redirect($defdaultLang.'/roles')->with('message','Role Details Added Successfully!!');
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
        
        $Role = new Role();
        $data['roles_edit'] = Role::find($id)->first();
       
        return view('roles.edit_roles')->with('data',$data);
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
            'name'=>'required',
            'slug' => 'required'
        ],
        [
            'name.required'=>'Please enter Role name',
            'slug.required'=>'Please enter Slug name'
        ]);
       
        $Role = new Role();
        $Role = Role::find($id);
        $Role->name = $request->name;
        $Role->slug = $request->slug;
        $Role->save();

        $defdaultLang = app()->getLocale();
        return redirect($defdaultLang.'/roles')->with('message','Role Details Updated Successfully!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($lang,$id)
    {
        $Role = new Role();
        $Role = Role::find($id);
        $Role->delete();

        $defdaultLang = app()->getLocale();
        return redirect($defdaultLang.'/roles')->with('message','Role Details Deleted Successfully!!');
    }
}
