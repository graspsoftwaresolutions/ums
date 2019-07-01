<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Http\Request;
use App\Persontitle;
use DB;
use View;

class PersontitleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
        $this->Persontitle = new Persontitle;
    }
    public function index()
    {
        $data['title_view'] = DB::table('persontitle')->where('status','=','1')->get();
        return view('persontitle.persontitle')->with('data',$data);
    }
    public function addTitle()
    {
        return view('persontitle.add-persontitle');
    }
    public function save(Request $request)
    {
        $request->validate([
            'person_title'=>'required',
        ],
        [
            'person_title.required'=>'please enter person title name',
        ]);
        $person['person_title'] = $request->input('person_title');
        $data_exists = DB::table('persontitle')->where([
            ['person_title','=',$person['person_title']],
            ['status','=','1']
            ])->count();
        if($data_exists>0 && $data_exists!='' && $data_exists!='NULL')
        {
            return redirect('add-title')->with('message','Title Name Already Exists');
        }
        else
        {
            $id = $this->Persontitle->StorePersontitle($person);
            return redirect('persontitle')->with('message','Title Name Added Succesfully');
        }
    }
    public function view($id)
    {
        $id = Crypt::decrypt($id);
        $data['title_view'] = DB::table('persontitle')->where([
            ['status','=','1'],
            ['id','=',$id]
        ])->get();
        return view('persontitle.view_persontitle')->with('data',$data);
    }
    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $data['title_view'] = DB::table('persontitle')->where([
            ['status','=','1'],
            ['id','=',$id]
        ])->get();
        return view('persontitle.edit_persontitle')->with('data',$data);
    }
    public function update(Request $request)
    {
        $id = $request->input('id');
        $request->validate([
            'person_title'=>'required',
        ],
        [
            'person_title.required'=>'please enter Person Title name',
        ]);
        $person['person_title'] = $request->input('person_title');
        $data_exists = DB::table('persontitle')->where([
            ['person_title','=',$person['person_title']],
            ['status','=','1']
            ])->count();

        if($data_exists>0 && $data_exists!='' && $data_exists!='NULL')
        {
            return redirect()->back()->with('message','Tirle Name Already Exists');
        }
        else
        {
            $id = DB::table('persontitle')->where('id','=',$id)->update($person);
            return redirect('persontitle')->with('message','Title Name Updated Succesfully');
        }
    }
    public function delete($id)
	{
		$data = DB::table('persontitle')->where('id','=',$id)->update(['status'=>'0']);
		return redirect('persontitle')->with('message','Title Deleted Succesfully');
	}
}
