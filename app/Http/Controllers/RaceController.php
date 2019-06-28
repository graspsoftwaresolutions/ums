<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Http\Request;
use App\Model\Race;
use DB;
use View;

class RaceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
        $this->Race = new Race;
    }
    public function index()
    {
        $data['race_view'] = DB::table('race')->where('status','=','1')->get();
        return view('race.race')->with('data',$data);
    }
    public function addRace()
    {
        return view('race.add-race');
    }
    public function save(Request $request)
    {
        $request->validate([
            'race_name'=>'required',
        ],
        [
            'race_name.required'=>'please enter Race name',
        ]);
        $race['race_name'] = $request->input('race_name');
        $data_exists = DB::table('race')->where([
            ['race_name','=',$race['race_name']],
            ['status','=','1']
            ])->count();
        if($data_exists>0 && $data_exists!='' && $data_exists!='NULL')
        {
            return redirect('add-race')->with('message','Race Name Already Exists');
        }
        else
        {
            $id = $this->Race->StoreRace($race);
            return redirect('race')->with('message','Race Name Added Succesfully');
        }
    }
    public function view($id)
    {
        $id = Crypt::decrypt($id);
        $data['race_view'] = DB::table('race')->where([
            ['status','=','1'],
            ['id','=',$id]
        ])->get();
        return view('race.view_race')->with('data',$data);
    }
    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $data['race_edit'] = DB::table('race')->where([
            ['status','=','1'],
            ['id','=',$id]
        ])->get();
        return view('race.edit_race')->with('data',$data);
    }
    public function update(Request $request)
    {
        $id = $request->input('id');
        $request->validate([
            'race_name'=>'required',
        ],
        [
            'race_name.required'=>'please enter Race name',
        ]);
        $race['race_name'] = $request->input('race_name');
        $data_exists = DB::table('race')->where([
            ['race_name','=',$race['race_name']],
            ['status','=','1']
            ])->count();

        if($data_exists>0 && $data_exists!='' && $data_exists!='NULL')
        {
            return redirect()->back()->with('message','Race Name Already Exists');
        }
        else
        {
            $id = DB::table('race')->where('id','=',$id)->update($race);
            return redirect('race')->with('message','Race Name Updated Succesfully');
        }
    }
    public function delete($id)
	{
		$data = DB::table('race')->where('id','=',$id)->update(['status'=>'0']);
		return redirect('race')->with('message','Race Deleted Succesfully');
	}
}
