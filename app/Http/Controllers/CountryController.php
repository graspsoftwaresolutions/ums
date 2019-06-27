<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Http\Request;
use App\Model\Country;
use DB;
use View;

class CountryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
        $this->middleware('role:union'); 
        $this->Country = new Country;
    }
    public function index()
    {
        $data['country_view'] = DB::table('country')->where('status','=','1')->get();
        return view('country.country')->with('data',$data);
    }
    public function addCountry()
    {
        return view('country.add-country');
    }
    public function save(Request $request)
    {
        $request->validate([
            'country_name'=>'required',
        ],
        [
            'country_name.required'=>'please enter Country name',
        ]);
        $country['country_name'] = $request->input('country_name');
        $data_exists = DB::table('country')->where([
            ['country_name','=',$country['country_name']],
            ['status','=','1'],
            ])->count();
        if($data_exists>0 && $data_exists!='' && $data_exists!='NULL')
        {
            return redirect('add-country')->with('message','Country Name Already Exists');
        }
        else
        {
            $id = $this->Country->StoreCountry($country);
            return redirect('country')->with('message','Country Name Added Succesfully');
        }
    }
    public function view($id)
    {
        $id = Crypt::decrypt($id);
        $data['country_view'] = DB::table('country')->where('id','=',$id)->get(); 
        return view('country.view_country')->with('data',$data);
    }
    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $data['country_edit'] = DB::table('country')->where('id','=',$id)->get(); 
        return view('country.edit_country')->with('data',$data);
    }
    public function update(Request $request)
    {
        $id = $request->input('id');
        $country['country_name'] = $request->input('country_name');
		$id = DB::table('country')->where('id','=',$id)->update($country);
		return redirect('country')->with('message','Country Details Updated Succesfully');
    }
    public function delete($id)
	{
		$data = DB::table('country')->where('id','=',$id)->update(['status'=>'0']);
		return redirect('country')->with('country','Country Deleted Succesfully');
	} 
}
