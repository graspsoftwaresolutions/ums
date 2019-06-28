<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Status;
use Illuminate\Support\Facades\Crypt;
use DB;

class StatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
        $this->middleware('role:union');
        $this->Status = new Status;
    }
    public function index()
    {
        $data['status_view'] = DB::table('status')->where('status','=','1')->get();
        return view('status.status')->with('data',$data);
    }
    public function addStatus()
    {
        $data = DB::table('status')->where('status','=','1')->get();
        return view('status.add_status',compact('data',$data));
    }
    public function save(Request $request)
    {
        $request->validate([
            'status_name'=>'required',
        ],
        [
            'status_name.required'=>'please enter Status name',
        ]);
        $status['status_name'] = $request->input('status_name');
        $data_exists = DB::table('status')->where([
            ['status_name','=',$status['status_name']],
            ['status','=','1'],
            ])->count();
        if($data_exists>0 && $data_exists!='' && $data_exists!='NULL')
        {
            return redirect('add_status')->with('message','Status Name Already Exists');
        }
        else
        {
            $id = $this->Status->StoreStatus($status);
            return redirect('status')->with('message','Status Name Added Succesfully');
        }
    }
    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $data['status_edit'] = DB::table('status')->where('id','=',$id)->get(); 
        return view('status.edit_status')->with('data',$data);
    }
    public function update(Request $request)
    {
        $id = $request->input('id');
        $status['status_name'] = $request->input('status_name');
		$id = DB::table('status')->where('id','=',$id)->update($status);
		return redirect('status')->with('message','Status Details Updated Succesfully');
    }
    public function delete($id)
	{
		$data = DB::table('status')->where('id','=',$id)->update(['status'=>'0']);
		return redirect('status')->with('message','Status Deleted Succesfully');
	} 
}
