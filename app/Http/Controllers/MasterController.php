<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Helpers\CommonHelper;
use App\Model\Country;

class MasterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
        $this->Country = new Country;
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
}
