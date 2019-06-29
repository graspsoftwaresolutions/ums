<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Http\Request;
use App\Model\Designation;
use DB;
use View;

class DesignationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
        $this->middleware('role:union');
        $this->Designation = new Designation;
    }
    public function index()
    {
        $data['designation_view'] = DB::table('designation')->where('status','=','1')->get(); 
        return view('designation.designation')->with('data',$data);
    }
    public function addDesignation()
    {
        return view('designation.add-designation');
    }
    public function save(Request $request)
    {
        $request->validate([
            'designation_name'=>'required',
        ],
        [
            'designation_name.required'=>'please enter Designation name',
        ]);
        $designation['designation_name'] = $request->input('designation_name');
        $data_exists = DB::table('designation')->where([
            ['designation_name','=',$designation['designation_name']],
            ['status','=','1']
            ])->count();
        if($data_exists>0 && $data_exists!='' && $data_exists!='NULL')
        {
            return redirect('add-designation')->with('message','Designation Name Already Exists');
        }
        else
        {
            $id = $this->Designation->StoreDesignation($designation);
            return redirect('designation')->with('message','Designation Name Added Succesfully');
        }
    }
    public function view($id)
    {
        $id = Crypt::decrypt($id);
        $data['designation_view'] = DB::table('designation')->where([
            ['status','=','1'],
            ['id','=',$id]
            ])->get();
        return view('designation.view_designation')->with('data',$data);
    }
    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $data['designation_view'] = DB::table('designation')->where([
            ['status','=','1'],
            ['id','=',$id]
            ])->get();
        return view('designation.edit_designation')->with('data',$data);
    }
    public function update(Request $request)
    {
        $id = $request->input('id');
        $request->validate([
            'designation_name'=>'required',
        ],
        [
            'designation_name.required'=>'please enter Designation name',
        ]);
        $designation['designation_name'] = $request->input('designation_name');
        $data_exists = DB::table('designation')->where([
            ['designation_name','=',$designation['designation_name']],
            ['status','=','1']
            ])->count();

        if($data_exists>0 && $data_exists!='' && $data_exists!='NULL')
        {
            return redirect()->back()->with('message','Designation Name Already Exists');
        }
        else
        {
            $id = DB::table('designation')->where('id','=',$id)->update($designation);
            return redirect('designation')->with('message','Designation Name Updated Succesfully');
        }
    }
    public function delete($id)
	{
		$data = DB::table('designation')->where('id','=',$id)->update(['status'=>'0']);
		return redirect('designation')->with('message','Designation Deleted Succesfully');
	}
}
