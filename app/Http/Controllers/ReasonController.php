<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Http\Request;
use App\Model\Reason;
use DB;
use View;

class ReasonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
        $this->Reason = new Reason;
    }
    public function index()
    {
        $data['reason_view'] = DB::table('reason')->where('status','=','1')->get();
        return view('reason.reason')->with('data',$data);
    }
    public function addReason()
    {
        return view('reason.add-reason');
    }
    public function save(Request $request)
    {
        $request->validate([
            'reason_name'=>'required',
        ],
        [
            'reason_name.required'=>'please enter Reason name',
        ]);
        $reason['reason_name'] = $request->input('reason_name');
        $data_exists = DB::table('reason')->where([
            ['reason_name','=',$reason['reason_name']],
            ['status','=','1']
            ])->count();
        $defdaultLang = app()->getLocale();
        if($data_exists>0 && $data_exists!='' && $data_exists!='NULL')
        {
            return redirect($defdaultLang.'/add-reason')->with('message','Reason Name Already Exists');
        }
        else
        {
            $id = $this->Reason->StoreReason($reason);
            return redirect($defdaultLang.'/reason')->with('message','Reason Name Added Succesfully');
        }
    }
    public function view($id)
    {
        $id = Crypt::decrypt($id);
        $data['reason_view'] = DB::table('reason')->where([
            ['status','=','1'],
            ['id','=',$id]
        ])->get();
        return view('reason.view_reason')->with('data',$data);
    }
    public function edit($lang,$id)
    {
        $id = Crypt::decrypt($id);
        $data['reason_edit'] = DB::table('reason')->where([
            ['status','=','1'],
            ['id','=',$id]
        ])->get();
        return view('reason.edit_reason')->with('data',$data);
    }
    public function update(Request $request)
    {
        $id = $request->input('id');
        $request->validate([
            'reason_name'=>'required',
        ],
        [
            'reason_name.required'=>'please enter Reason name',
        ]);
        $reason['reason_name'] = $request->input('reason_name');
        $data_exists = DB::table('reason')->where([
            ['reason_name','=',$reason['reason_name']],
            ['status','=','1']
            ])->count();
        $defdaultLang = app()->getLocale();
        if($data_exists>0 && $data_exists!='' && $data_exists!='NULL')
        {
            return redirect()->back()->with('message','Reason Name Already Exists');
        }
        else
        {
            $id = DB::table('reason')->where('id','=',$id)->update($reason);
            return redirect($defdaultLang.'/reason')->with('message','Reason Name Updated Succesfully');
        }
    }
    public function delete($lang, $id)
	{
        $id = Crypt::decrypt($id);
        $data = DB::table('reason')->where('id','=',$id)->update(['status'=>'0']);
        $defdaultLang = app()->getLocale();
		return redirect($defdaultLang.'/reason')->with('message','Reason Deleted Succesfully');
	}
}
