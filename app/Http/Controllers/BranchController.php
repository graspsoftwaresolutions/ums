<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Model\Company;
use App\Model\Branch;
use DB;
use View;

class BranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
        $this->middleware('role:union');
        $this->Branch = new Branch;
       
    }
    public function index()
    {
        $data = DB::table('company')->select('company.company_name','branch.branch_name','branch.id','branch.company_id','branch.status','company.status')
                ->join('branch','company.id','=','branch.company_id')
                ->orderBy('company.id','ASC')
                ->where([
                    ['branch.status','=','1'],
                    ['company.status','=','1']
                    ])->get();
        return view('branch.branch',compact('data',$data));
    }
    public function addBranch()
    {
        $data = DB::table('company')->where('status','=','1')->get();
        return view('branch.add-branch',compact('data',$data));
    }
    public function save(Request $request)
    {
        $request->validate([
            'company_id'=>'required',
            'branch_name'=>'required'
        ],
        [
            'company_id.required'=>'please Choose Company name',
            'branch_name.required'=>'please Enter branch name',
        ]);
        $branch['company_id'] = $request->input('company_id');
        $branch['branch_name'] = $request->input('branch_name');
        $data_exists = DB::table('branch')->where([
           ['branch_name','=', $branch['branch_name']],
           ['status','=','1'] 
            ])->count();
        if($data_exists>0 && $data_exists!='' && $data_exists!='NULL')
        {
            return redirect('branch')->with('message','Branch Name Already Exists');
        }
        else
        {
            $id = $this->Branch->StoreBranch($branch);
            return redirect('branch')->with('message','Branch Name Added Succesfully');
        }
    }
    public function view($id)
    {
        $id = Crypt::decrypt($id);
        $data = DB::table('company')->select('company.company_name','branch.branch_name','branch.id','branch.company_id','branch.status','company.status')
                ->join('branch','company.id','=','branch.company_id')
                ->where([
                    ['branch.status','=','1'],
                    ['company.status','=','1'],
                    ['branch.id','=',$id]
                    ])->get();
        return view('branch.view_branch',compact('data',$data));
    }
    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $data['branch_view'] = DB::table('company')->select('company.company_name','branch.branch_name','branch.id','branch.company_id','branch.status','company.status')
                ->join('branch','company.id','=','branch.company_id')
                ->where([
                    ['branch.status','=','1'],
                    ['company.status','=','1'],
                    ['branch.id','=',$id]
                    ])->get();
        $company_id = $data['branch_view'][0]->company_id;
        $data['company_view'] = DB::table('company')->where('status','=','1')->get();
        return view('branch.edit_branch')->with('data',$data);
    }
    public function update(Request $request)
    {
        $id = $request->input('id');
        $branch['company_id'] = $request->input('company_id');
        $branch['branch_name'] = $request->input('branch_name');
		$id = DB::table('branch')->where('id','=',$id)->update($branch);
		return redirect('branch')->with('message','Branch Details Updated Succesfully');
    }
    public function delete($id)
	{
		$data = DB::table('branch')->where('id','=',$id)->update(['status'=>'0']);
		return redirect('branch')->with('branch','Branch Deleted Succesfully');
	} 
}
