<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Http\Request;
use App\Model\City;
use App\Model\State;
use App\Model\Country;
use DB;
use View;

class CityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
        $this->middleware('role:union');
        $this->City = new City;
       
    }
    public function index()
    {
        $data = DB::table('country')->select('country.country_name','state.state_name','city.id','state.country_id','city.status','city.city_name')
                ->join('state','country.id','=','state.country_id')
                ->join('city','city.state_id','=','state.id')
                ->orderBy('country.id','ASC')
                ->where('city.status','=','1')->get();
        return view('city.city',compact('data',$data));
    }
    public function addCity()
    {
        $countries['country_view'] = DB::table('country')->select('id','country_name')->where('status','=','1')->get();
        return view('city.add-city')->with('data',$countries);
        return view('city.add-city',compact('data',$data));
    }
    public function getStateorderList(Request $request)
    {
        $id = $request->country_id;
        $res = DB::table('state')
                ->select('id','state_name')
                ->where([
                    ['country_id','=',$id],
                    ['status','=','1']
                ])->get();
                return response()->json($res);
    }
    public function save(Request $request)
    {
        $request->validate([
            
            'state_id'=>'required',
            'city_name'=>'required'
        ],
        [
           
            'state_id.required'=>'please Enter State name',
            'city_name.required'=>'please Enter City name',
        ]);
       
        $city['state_id'] = $request->input('state_id');
        $city['city_name'] = $request->input('city_name');
        $data_exists = DB::table('country')
                    ->join('state','country.id','=','state.country_id')
                    ->join('city','city.state_id','=','state.id')
                    ->where([
                        ['city.city_name','=',$city['city_name']],
                        ['city.status','=','1'],
                        ])->count();

        if($data_exists>0 && $data_exists!='' && $data_exists!='NULL')
        {
            return redirect('city')->with('message','City Name Already Exists');
        }
        else
        {
            $id = $this->City->StoreCity($city);
            return redirect('city')->with('message','City Name Added Succesfully');
        }
    }
    public function view($id)
    {
        $id = Crypt::decrypt($id);
        $data['city_view'] =  DB::table('country')
        ->join('state','country.id','=','state.country_id')
        ->join('city','city.state_id','=','state.id')
        ->where([
            ['city.id','=',$id],
            ['city.status','=','1']
        ])->get();
        return view('city.view_city')->with('data',$data);
    }
    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $data['city_view'] =  DB::table('country')
        ->join('state','country.id','=','state.country_id')
        ->join('city','city.state_id','=','state.id')
        ->where([
            ['city.id','=',$id],
            ['city.status','=','1'],
            ['state.status','=','1'],
            ['country.status','=','1']
        ])->get();
        $country_id = $data['city_view'][0]->country_id;
        $state_id = $data['city_view'][0]->state_id;
        $data['country_view'] = DB::table('country')->where('status','=','1')->get();
        $data['state_view'] = DB::table('state')->where('status','=','1')->get();
        return view('city.edit_city')->with('data',$data);
    }
    public function update(Request $request)
    {
        $id = $request->input('id');
        $city['city_name'] = $request->input('city_name');
		$id = DB::table('city')->where('id','=',$id)->update($city);
		return redirect('city')->with('message','City Details Updated Succesfully');
    }
    public function delete($id)
	{
		$data = DB::table('city')->where('id','=',$id)->update(['status'=>'0']);
		return redirect('city')->with('message','City Details Deleted Succesfully');
	} 
}
