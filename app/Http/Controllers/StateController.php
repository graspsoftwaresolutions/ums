<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Model\Country;
use App\Model\State;
use DB;
use View;

class StateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
        $this->middleware('role:union');
        $this->State = new State;
    }
    public function index()
    {
        $data = DB::table('country')->select('country.country_name','state.state_name','state.id','state.country_id','state.status')
                ->join('state','country.id','=','state.country_id')
                ->orderBy('country.id','ASC')
                ->where('state.status','=','1')->get();
        return view('state.state',compact('data',$data));
    }
    public function addState()
    {
        $data = DB::table('country')->where('status','=','1')->get();
        return view('state.add_state',compact('data',$data));
    }
    public function save(Request $request)
    {
        $request->validate([
            'country_id'=>'required',
            'state_name'=>'required'
        ],
        [
            'country_id.required'=>'please Choose Country name',
            'state_name.required'=>'please Enter State name',
        ]);
        $state['country_id'] = $request->input('country_id');
        $state['state_name'] = $request->input('state_name');
        $data_exists = DB::table('state')->where([
           ['state_name','=', $state['state_name']],
           ['status','=','1'] 
            ])->count();
        if($data_exists>0 && $data_exists!='' && $data_exists!='NULL')
        {
            $defaultLanguage = app()->getLocale();
            return redirect($defaultLanguage.'/state')->with('message','State Name Already Exists');
        }
        else
        {
            $id = $this->State->StoreState($state);
            $defaultLanguage = app()->getLocale();
            return redirect($defaultLanguage.'/state')->with('message','State Name Added Succesfully');
        }
    }
    public function edit($lang,$id)
    {
        $id = Crypt::decrypt($id);
        $data['state_view'] = DB::table('country')->select('country.country_name','state.state_name','state.id','state.country_id','state.status','state.id')
                ->join('state','country.id','=','state.country_id')
                ->where('state.id','=',$id)->get();
        $country_id = $data['state_view'][0]->country_id;    
        $data['country_view'] = DB::table('country')->where('status','=','1')->get();    
        return view('state.edit_state',compact('data',$data));
    }
    public function update(Request $request)
    {
        $id = $request->input('id');
        $state['country_id'] = $request->input('country_id');
        $state['state_name'] = $request->input('state_name');
        $id = DB::table('state')->where('id','=',$id)->update($state);
        $defaultLanguage = app()->getLocale();
		return redirect($defaultLanguage.'/state')->with('message','State Details Updated Succesfully');
    }
    public function delete($lang,$id)
	{
        $id = Crypt::decrypt($id);
        $data = DB::table('state')->where('id','=',$id)->update(['status'=>'0']);
        $defaultLanguage = app()->getLocale();
		return redirect($defaultLanguage.'/state')->with('state','State Deleted Succesfully');
	}
}
