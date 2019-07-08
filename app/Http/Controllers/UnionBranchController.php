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
        $data['country_view'] = DB::table('country')->select('id','country_name')->where('status','=','1')->get();
        return view('unionbranch.add_unionbranch')->with('data',$data);
    }
    public function save(Request $request)
    {
        $request->validate([
            'branch_name'=>'required',
            'phone'=>'required',
            'email'=>'required',
            'country_id'=>'required',
            'state_id'=>'required',
            'city_id'=>'required',
            'postal_code'=>'required',
            'address_one'=>'required',
            'logo' => 'max:2000',
        ],
        [
            'branch_name.required'=>'Please Enter Branch Name',
            'phone.required'=>'Please Enter Mobile Number',
            'email.required'=>'Please Enter Email Address',
            'country_id.required'=>'Please choose  your Country',
            'state_id.required'=>'Please choose  your State',
            'city_id.required'=>'Please choose  your city',
            'postal_code.required'=>'Please Enter postal code',
            'address_one.required'=>'Please Enter your Address',
            'logo' => 'Maximum file size is 2MB',
        ]);
        $union['union_branch'] = $request->input('branch_name');
        $union['phone'] = $request->input('phone');
        $union['mobile'] = $request->input('mobile');
        $union['email'] = $request->input('email');
        $union['postal_code'] = $request->input('postal_code');
        $union['country_id'] = $request->input('country_id');
        $union['state_id'] = $request->input('state_id');
        $union['city_id'] = $request->input('city_id');
        $union['address_one'] = $request->input('address_one');
        $union['address_two'] = $request->input('address_two');
        $union['address_three'] = $request->input('address_three');
        $files = $request->file('logo');
        
		if(!empty($files))
		{
			$image_name = time().'.'.$files->getClientOriginalExtension();
			$files->move('public/images',$image_name);
			$union['logo'] = $image_name;
        }
        
        $union['is_head'] = $request->input('is_head');
        $defaultLanguage = app()->getLocale();
        
        //Data Exists
        $data_exists = DB::table('union_branch')->where([
            ['union_branch','=', $union['union_branch']],
            ['status','=','1'] 
             ])->count();
         if($data_exists>0 && $data_exists!='' && $data_exists!='NULL')
         {
             return redirect($defaultLanguage.'/add-unionbranch')->with('message','Union Branch Name Already Exists');
         }
         else
         {
            if($union['is_head'] == '')
            {
                $union['is_head'] = '0';
                $id = $this->UnionBranch->StoreUnionBranch($union);
                return redirect($defaultLanguage.'/unionbranch')->with('message','Union Branch Name Added Succesfully');
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
                    return redirect($defaultLanguage.'/unionbranch')->with('message','Union Branch Name Added Succesfully');
                }
                else{
                    $id = $this->UnionBranch->StoreUnionBranch($union);
                    return redirect($defaultLanguage.'/unionbranch')->with('message','Union Branch Name Added Succesfully');
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
    public function edit($lang,$id)
    {
        DB::connection()->enableQueryLog();
        $id = Crypt::decrypt($id);
        $data['union_branch'] = DB::table('union_branch')->select('union_branch.id as branchid','union_branch.id','union_branch.union_branch','union_branch.is_head','union_branch.country_id','union_branch.state_id','union_branch.city_id','union_branch.postal_code','union_branch.address_one','union_branch.address_two','union_branch.phone','union_branch.email','union_branch.is_head',
                                            'union_branch.status','union_branch.address_three','union_branch.mobile','union_branch.logo','country.id','country.country_name','country.status','state.id','state.state_name','state.status','city.id','city.city_name','city.status')
                                ->leftjoin('country','union_branch.country_id','=','country.id')
                                ->leftjoin('state','union_branch.state_id','=','state.id')
                                ->leftjoin('city','union_branch.city_id','=','city.id')
                                ->where([
                                        ['union_branch.status','=','1'],
                                        ['union_branch.id','=',$id]
                                    ])->get();
        
        $country_id = $data['union_branch'][0]->country_id;
        $state_id = $data['union_branch'][0]->state_id;
        $city_id = $data['union_branch'][0]->city_id;
        
        $queries = DB::getQueryLog();
        $defaultLanguage = app()->getLocale();
        //dd($queries);
        //return $data['union_branch'];
        $data['state_view'] = DB::table('state')->select('id','state_name')->where('status','=','1')->where('country_id','=',$country_id)->get();
        $data['city_view'] = DB::table('city')->select('id','city_name')->where('status','=','1')->where('state_id','=',$state_id)->get();
        $data['country_view'] = DB::table('country')->select('id','country_name')->where('status','=','1')->get();
        return view('unionbranch.edit_unionbranch')->with('data',$data);
    }
    public function update(Request $request)
    {
        $auto_id = $request->input('id');
        $request->validate([
            'branch_name'=>'required',
            'phone'=>'required',
            'email'=>'required',
            'country_id'=>'required',
            'state_id'=>'required',
            'city_id'=>'required',
            'postal_code'=>'required',
            'address_one'=>'required',
        ],
        [
            'branch_name.required'=>'please enter Branch name',
            'phone.required'=>'Please Enter Mobile Number',
            'email.required'=>'Please Enter Email Address',
            'country_id.required'=>'Please choose  your Country',
            'state_id.required'=>'Please choose  your State',
            'city_id.required'=>'Please choose  your city',
            'postal_code.required'=>'Please Enter postal code',
            'address_one.required'=>'Please Enter your Address',
        ]);
        $union['union_branch'] = $request->input('branch_name');
        $union['phone'] = $request->input('phone');
        $union['email'] = $request->input('email');
        $union['mobile'] = $request->input('mobile');
        $union['postal_code'] = $request->input('postal_code');
        $union['country_id'] = $request->input('country_id');
        $union['state_id'] = $request->input('state_id');
        $union['city_id'] = $request->input('city_id');
        $union['address_one'] = $request->input('address_one');
        $union['is_head'] = $request->input('is_head');
        $union['address_two'] = $request->input('address_two');
        $union['address_three'] = $request->input('address_three');
        $files = $request->file('logo');
		if(!empty($files))
		{
			$image_name = time().'.'.$files->getClientOriginalExtension();
			$files->move('public/images',$image_name);
			$union['logo'] = $image_name;
        }
        $defaultLanguage = app()->getLocale();
        
         //Data Exists
         $data_exists = DB::table('union_branch')->where([
            ['union_branch','=', $union['union_branch']],
            ['status','=','1'] 
             ])->count();
         
            if($union['is_head'] == '')
            {
                DB::connection()->enableQueryLog();
                $union['is_head'] = '0';
                $id = DB::table('union_branch')->where('id','=',$auto_id)->update($union);
                

                return redirect($defaultLanguage.'/unionbranch')->with('message','Union Branch Name Updated Succesfully');
            }
            else{

                $is_head_exists = DB::table('union_branch')->where([
                    ['is_head','=','1'],
                    ['status','=','1']
                    ])->count(); 
                if($is_head_exists > 0 && !empty($union['is_head']))
                {
                    $data = DB::table('union_branch')->where('is_head','=','1')->update(['is_head'=>'0']);
                    $id = DB::table('union_branch')->where('id','=',$auto_id)->update($union);
                    return redirect($defaultLanguage.'/unionbranch')->with('message','Union Branch Name Updated Succesfully');
                }
                else{
                    $id = DB::table('union_branch')->where('id','=',$auto_id)->update($union);
                    return redirect($defaultLanguage.'/unionbranch')->with('message','Union Branch Name Updated Succesfully');
                }
            }
    }
    public function delete($lang,$id)
	{
        $id = Crypt::decrypt($id);
		$data = DB::table('union_branch')->where('id','=',$id)->update(['status'=>'0']);
		return redirect($lang.'/unionbranch')->with('message','Union Branch Deleted Succesfully');
	}
}
