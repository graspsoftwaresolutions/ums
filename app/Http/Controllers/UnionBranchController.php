<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Http\Request;
use App\Model\UnionBranch;
use DB;
use View;

class UnionBranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
        $this->UnionBranch = new UnionBranch;
    }
    public function index()
    {
        $data['union_view'] = DB::table('union_branch')->where('status','=','1')->get();
        return view('unionbranch.unionbranch')->with('data',$data);
    }
    public function addUnionBranch()
    {
        return view('unionbranch.add_unionbranch');
    }
    public function save(Request $request)
    {
        $request->validate([
            'branch_name'=>'required',
        ],
        [
            'branch_name.required'=>'please enter Branch name',
        ]);
        $union['union_branch'] = $request->input('branch_name');
        
        $union['is_head'] = $request->input('is_head');
        
        //Data Exists
        $data_exists = DB::table('union_branch')->where([
            ['union_branch','=', $union['union_branch']],
            ['status','=','1'] 
             ])->count();
         if($data_exists>0 && $data_exists!='' && $data_exists!='NULL')
         {
             return redirect('add-unionbranch')->with('message','Union Branch Name Already Exists');
         }
         else
         {
            if($union['is_head'] == '')
            {
                $union['is_head'] = '0';
                $id = $this->UnionBranch->StoreUnionBranch($union);
                return redirect('unionbranch')->with('message','Union Branch Name Added Succesfully');
            }
            else{
                $union['is_head'] = '1';
                $is_head_exists = DB::table('union_branch')->where([
                    ['is_head','=','1'],
                    ['status','=','1']
                    ])->count();
                if($is_head_exists > 0 && !empty($union['is_head']))
                {
                    $data = DB::table('union_branch')->where('is_head','=','1')->update(['is_head'=>'0']);
                    $id = $this->UnionBranch->StoreUnionBranch($union);
                    return redirect('unionbranch')->with('message','Union Branch Name Added Succesfully');
                }
                else{
                    
                    return redirect('unionbranch')->with('message','Error');
                }
            }
         }
    }
    public function view($id)
    {
        $id = Crypt::decrypt($id);
        $data['union_branch'] = DB::table('union_branch')->where([
            ['status','=','1'],
            ['id','=',$id]
        ])->get();
        return view('unionbranch.view_unionbranch')->with('data',$data);
    }
    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $data['union_branch'] = DB::table('union_branch')->where([
            ['status','=','1'],
            ['id','=',$id]
        ])->get();
        return view('unionbranch.edit_unionbranch')->with('data',$data);
    }
    public function update(Request $request)
    {
        $id = $request->input('id');
        $request->validate([
            'branch_name'=>'required',
        ],
        [
            'branch_name.required'=>'please enter Branch name',
        ]);
        $union['union_branch'] = $request->input('branch_name');
        $union['is_head'] = $request->input('is_head');
        
         //Data Exists
         $data_exists = DB::table('union_branch')->where([
            ['union_branch','=', $union['union_branch']],
            ['status','=','1'] 
             ])->count();
         
            if($union['is_head'] == '')
            {
                $union['is_head'] = '0';
                $id = DB::table('union_branch')->where('id','=',$id)->update($union);
                return redirect('unionbranch')->with('message','Union Branch Name Updated Succesfully');
            }
            else{

                $is_head_exists = DB::table('union_branch')->where([
                    ['is_head','=','1'],
                    ['status','=','1']
                    ])->count(); 
                if($is_head_exists > 0 && !empty($union['is_head']))
                {
                    $data = DB::table('union_branch')->where('is_head','=','1')->update(['is_head'=>'0']);
                    $id = DB::table('union_branch')->where('id','=',$id)->update($union);
                    return redirect('unionbranch')->with('message','Union Branch Name Updated Succesfully');
                }
                else{
                    $id = DB::table('union_branch')->where('id','=',$id)->update($union);
                    return redirect('unionbranch')->with('message','Union Branch Name Updated Succesfully');
                }
            }
            
    }
    public function delete($id)
	{
		$data = DB::table('union_branch')->where('id','=',$id)->update(['status'=>'0']);
		return redirect('unionbranch')->with('message','Union Branch Deleted Succesfully');
	}
}
