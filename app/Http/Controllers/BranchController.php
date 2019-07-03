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
        $data['company_view'] = DB::table('company')->where('status','=','1')->get();
        $data['union_view'] = DB::table('union_branch')->where('status','=','1')->get();
        $data['country_view'] = DB::table('country')->select('id','country_name')->where('status','=','1')->get();
        return view('branch.add-branch')->with('data',$data);
    }
    public function save(Request $request)
    {
        $request->validate([
            'company_id'=>'required',
            'union_branch_id'=>'required',
            'branch_name'=>'required',
            'address_one'=>'required',
            'address_two'=>'required',
            'country_id'=>'required',
            'state_id'=>'required',
            'city_id'=>'required',
            'postal_code'=>'required',
            'email'=>'required',
            'phone'=>'required',
        ],
        [
            'company_id.required'=>'please Choose Company name',
            'union_branch_id.required'=>'Please Choose union Banch',
            'branch_name.required'=>'please Enter branch name',
            'address_one.required'=>'please Enter address one name',
            'address_two.required'=>'please Enter address two name',
            'country_id.required'=>'please Enter country name',
            'state_id.required'=>'please Enter state name',
            'city_id.required'=>'please Enter city name',
            'postal_code.required'=>'please Enter postal code',
            'email.required'=>'please Enter email address',
            'phone.required'=>'please Enter phone number',
        ]);
        $branch['company_id'] = $request->input('company_id');
        $branch['union_branch_id'] = $request->input('union_branch_id');
        $branch['branch_name'] = $request->input('branch_name');
        $branch['address_one'] = $request->input('address_one');
        $branch['address_two'] = $request->input('address_two');
        $branch['country_id'] = $request->input('country_id');
        $branch['state_id'] = $request->input('state_id');
        $branch['city_id'] = $request->input('city_id');
        $branch['postal_code'] = $request->input('postal_code');
        $branch['email'] = $request->input('email');
        $branch['phone'] = $request->input('phone');

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
    public function getStateList(Request $request)
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
    public function getCitiesList(Request $request){
      
        $id = $request->State_id;
        $res = DB::table('city')
        ->select('id','city_name')
        ->where([
            ['state_id','=',$id],
            ['status','=','1']
        ])->get();
       
        return response()->json($res);
    }
    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $data['branch_view'] = DB::table('company')->select('branch.*', 'company.company_name','branch.branch_name','branch.id','branch.company_id','branch.status','company.status','union_branch.union_branch','branch.union_branch_id')
                ->join('branch','company.id','=','branch.company_id')
                ->join('union_branch','branch.union_branch_id','=','union_branch.id')
                ->where([
                    ['branch.status','=','1'],
                    ['company.status','=','1'],
                    ['branch.id','=',$id]
                    ])->get();
        $company_id = $data['branch_view'][0]->company_id;
        $union_branch_id = $data['branch_view'][0]->union_branch_id;
        $data['company_view'] = DB::table('company')->where('status','=','1')->get();
        $data['union_view'] = DB::table('union_branch')->where('status','=','1')->get();
        $data['country_view'] = DB::table('country')->select('id','country_name')->where('status','=','1')->get();
        $data['state_view'] = DB::table('state')->select('id','state_name')->where('status','=','1')->get();
        $data['city_view'] = DB::table('city')->select('id','city_name')->where('status','=','1')->get();
        return view('branch.edit_branch')->with('data',$data);
    }
    public function update(Request $request)
    {
        $id = $request->input('id');
        $branch['company_id'] = $request->input('company_id');
        $branch['branch_name'] = $request->input('branch_name');
        $branch['union_branch_id'] = $request->input('union_branch_id');
        $branch['address_one'] = $request->input('address_one');
        $branch['address_two'] = $request->input('address_two');
        $branch['country_id'] = $request->input('country_id');
        $branch['state_id'] = $request->input('state_id');
        $branch['city_id'] = $request->input('city_id');
        $branch['postal_code'] = $request->input('postal_code');
        $branch['email'] = $request->input('email');
        $branch['phone'] = $request->input('phone');
		$id = DB::table('branch')->where('id','=',$id)->update($branch);
		return redirect('branch')->with('message','Branch Details Updated Succesfully');
    }
    public function delete($id)
	{
		$data = DB::table('branch')->where('id','=',$id)->update(['status'=>'0']);
		return redirect('branch')->with('branch','Branch Deleted Succesfully');
	} 
}
