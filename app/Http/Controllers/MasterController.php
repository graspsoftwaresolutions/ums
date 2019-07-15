<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Helpers\CommonHelper;
use App\Model\Country;
use App\User;

class MasterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
        $this->Country = new Country;
        $this->User = new User;
    }
    public function countryList()
    {
        $data['country_view'] = Country::all();
        return view('master.country.country_list')->with('data',$data);
    }
    public function countrySave(Request $request)
    {
        $request->validate([
            'country_name'=>'required',
        ],
        [
            'country_name.required'=>'please enter Country name',
        ]);
        $data = $request->all();
        $data_exists = CommonHelper::getExistingCountry($request->country_name);
        $defdaultLang = app()->getLocale();
        if($data_exists>0)
        {
            return  redirect($defdaultLang.'/country')->with('error','Country Name Already Exists'); 
        }
        else{
            $saveCountry = $this->Country->saveCountrydata($data);
           
            if($saveCountry == true)
            {
                return  redirect($defdaultLang.'/country')->with('message','Country Name Added Succesfully');
            }
        }
    }
    public function countryEdit($lang,$id)
    {
        $id = Crypt::decrypt($id);
        $data = Country::find($id);
        return view('master.country.country_list')->with('data',$data);
    }
    //user Details 
    public function userSave(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required',
            // 'password'=>'required',
            // 'confirm_password'=>'required',
        ],
        [
            'name.required'=>'Please enter User name',
            'email.required'=>'Please enter Valid Email',
            // 'password.required'=>'Please enter Password',
            // 'confirm_password.required'=>'Please enter Confirm Password',
        ]);

        $data = $request->all();
        $data_exists = CommonHelper::getExistingUserEmail($request->email);
        $defdaultLang = app()->getLocale();
        if($data_exists>0)
        {
            return  redirect($defdaultLang.'/users')->with('error','User Email Already Exists'); 
        }
        else if(!empty($data)) {

           if(($request->password == $request->confirm_password))
           {
                $data['password'] = Crypt::encrypt($request->password);
                $saveUser = $this->User->saveUserdata($data);
            
                if($saveUser == true)
                {
                    return  redirect($defdaultLang.'/users')->with('message','User Added Succesfully');
                }
           }
           else {
             return  redirect($defdaultLang.'/users')->with('error','passwords are mismatch');
           }
         }
         else{
            $data->email = $request->email;
            $data->name = $request->name;
            $saveUser = $this->User->saveUserdata($data);
            if($saveUser == true)
            {
                return  redirect($defdaultLang.'/users')->with('message','User Updated Succesfully');
            }
         }
    }
}
