<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Http\Request;
use App\Model\Relation;
use DB;
use View;

class RelationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
        $this->Relation = new Relation;
    }
    public function index()
    {
        $data['relation_view'] = DB::table('relation')->where('status','=','1')->get();
        return view('relation.relation')->with('data',$data);
    }
    public function addRelation()
    {
        return view('relation.add-relation');
    }
    public function save(Request $request)
    {
        $request->validate([
            'relation_name'=>'required',
        ],
        [
            'relation_name.required'=>'please enter Relation name',
        ]);
        $relation['relation_name'] = $request->input('relation_name');
        $data_exists = DB::table('relation')->where([
            ['relation_name','=',$relation['relation_name']],
            ['status','=','1']
            ])->count();
        if($data_exists>0 && $data_exists!='' && $data_exists!='NULL')
        {
            return redirect('add-relation')->with('message','Relation Name Already Exists');
        }
        else
        {
            $id = $this->Relation->StoreRelation($relation);
            return redirect('relation')->with('message','Relation Name Added Succesfully');
        }
    }
    public function view($id)
    {
        $id = Crypt::decrypt($id);
        $data['relation_view'] = DB::table('relation')->where([
            ['status','=','1'],
            ['id','=',$id]
        ])->get();
        return view('relation.view_relation')->with('data',$data);
    }
    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $data['relation_view'] = DB::table('relation')->where([
            ['status','=','1'],
            ['id','=',$id]
        ])->get();
        return view('relation.edit_relation')->with('data',$data);
    }
    public function update(Request $request)
    {
        $id = $request->input('id');
        $request->validate([
            'relation_name'=>'required',
        ],
        [
            'relation_name.required'=>'please enter Relation name',
        ]);
        $relation['relation_name'] = $request->input('relation_name');
        $data_exists = DB::table('relation')->where([
            ['relation_name','=',$relation['relation_name']],
            ['status','=','1']
            ])->count();

        if($data_exists>0 && $data_exists!='' && $data_exists!='NULL')
        {
            return redirect()->back()->with('message','Relation Name Already Exists');
        }
        else
        {
            $id = DB::table('relation')->where('id','=',$id)->update($relation);
            return redirect('relation')->with('message','Relation Name Updated Succesfully');
        }
    }
    public function delete($id)
	{
		$data = DB::table('relation')->where('id','=',$id)->update(['status'=>'0']);
		return redirect('relation')->with('message','Relation Deleted Succesfully');
	}
}
