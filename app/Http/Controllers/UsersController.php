<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Support\Facades\Crypt;
use View;

use Illuminate\Http\Request;

class UsersController extends Controller
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
        $data = User::all();
        return view('users.users')->with('data',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.add_users');
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
            'email'=>'required',
            'password'=>'required',
            'confirm_password'=>'required',
        ],
        [
            'name.required'=>'Please enter User name',
            'email.required'=>'Please enter Valid Email',
            'password.required'=>'Please enter Password',
            'confirm_password.required'=>'Please enter Confirm Password',
        ]);
       
        $User = new User();
        $User->name = $request->name;
        $User->email = $request->email;
        $User->password = $request->password;
       
        if($User->password == $request->confirm_password)
        {
            $User->password = Crypt::encrypt($User->password);
            $User->save();
            $defdaultLang = app()->getLocale();
            return redirect($defdaultLang.'/users')->with('message','User Details Added Successfully!!');
        }
        else
        {
            $defdaultLang = app()->getLocale();
            return redirect($defdaultLang.'/add_users')->with('error','Paasword and Confirm password mismatch!!');
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
        
        $User = new User();
        $data['users_edit'] = User::find($id)->first();
       
        return view('users.edit_users')->with('data',$data);
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
            'name'=>'required',
            'email'=>'required',    
        ],
        [
            'name.required'=>'Please enter User name',
            'email.required'=>'Please enter Valid Email',
        ]);
       
        $User = new User();
        $User = User::find($id);
        $User->name = $request->name;
        $User->email = $request->email;
        $User->save();
        $defdaultLang = app()->getLocale();
        return redirect($defdaultLang.'/users')->with('message','User Details Updated Successfully!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $User = new User();
        $User = User::find($id);
        $User->delete();

        $defdaultLang = app()->getLocale();
        return redirect($defdaultLang.'/roles')->with('message','User Details Deleted Successfully!!');
    }
}
