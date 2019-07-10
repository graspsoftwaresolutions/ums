<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Model\Company;
use App\Model\Branch;
use App\Helpers\CommonHelper;
use App\Mail\CompanyBranchMailable;
use DB;
use View;
use Mail;
use App\Role;
use App\User;
use URL;

class BranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
        $this->Branch = new Branch;
       
    }
    public function index()
    {
        $data = DB::table('company')->select('company.company_name','branch.branch_name','branch.id','branch.company_id','branch.status','branch.is_head','company.status')
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
        $defdaultLang = app()->getLocale();
        $request->validate([
            'company_id'=>'required',
            'union_branch_id'=>'required',
            'branch_name'=>'required',
            'address_one'=>'required',
            'country_id'=>'required',
            'state_id'=>'required',
            'city_id'=>'required',
            'postal_code'=>'required',
            'email'=>'required',
            'phone'=>'required',
            'mobile'=>'required',
        ],
        [
            'company_id.required'=>'please Choose Company name',
            'union_branch_id.required'=>'Please Choose union Banch',
            'branch_name.required'=>'please Enter branch name',
            'address_one.required'=>'please Enter address one name',
            'country_id.required'=>'please Enter country name',
            'state_id.required'=>'please Enter state name',
            'city_id.required'=>'please Enter city name',
            'postal_code.required'=>'please Enter postal code',
            'email.required'=>'please Enter email address',
            'phone.required'=>'please Enter phone number',
            'mobile.required'=>'please Enter mobile number',
        ]);
        $branch['company_id'] = $request->input('company_id');
        $branch['union_branch_id'] = $request->input('union_branch_id');
        $branch['branch_name'] = $request->input('branch_name');
        $branch['country_id'] = $request->input('country_id');
        $branch['state_id'] = $request->input('state_id');
        $branch['city_id'] = $request->input('city_id');
        $branch['postal_code'] = $request->input('postal_code');
        $branch['address_one'] = $request->input('address_one');
        $branch['address_two'] = $request->input('address_two');
        $branch['address_three'] = $request->input('address_three');
        $branch['phone'] = $request->input('phone');
        $branch['mobile'] = $request->input('mobile');
        $branch['email'] = $request->input('email');

        $is_head = $request->input('is_head');
        if(isset($is_head)){
            $branch['is_head'] = 1;
        }else{
            $branch['is_head'] = 0;
        }
       


        $company_head_role = Role::where('slug', 'company')->first();
        $company_branch_role = Role::where('slug', 'company-branch')->first();
        $randompass = CommonHelper::random_password(5,true);

        $data_exists_branchemail = DB::table('company_branch')->where([
            ['email','=',$branch['email']]
            ])->count();
        $data_exists_usersemail = DB::table('users')->where('email','=',$branch['email'])->count();
        $redirect_url = app()->getLocale().'/branch';
        if($data_exists_branchemail > 0 ||  $data_exists_usersemail > 0)
        {
            return redirect($defdaultLang.'/branch')->with('error','Email Already Exists');
        }
        else
        {
            $company_type =2;
           
            if($branch['is_head'] == 0)
            {
                $id = $this->Branch->StoreBranch($branch);
                $member_user = new User();
                $member_user->name = $request->input('branch_name');
                $member_user->email = $request->input('email');
                $member_user->password = bcrypt($randompass);
                $member_user->save();
                $member_user->roles()->attach($company_branch_role);
                $status =1;
            }else{
                $company_type = 1;
                $union_branch_id = $branch['union_branch_id'];
                $data = DB::table('company_branch')->where('is_head','=','1')->where('union_branch_id','=',$union_branch_id)->update(['is_head'=>'0']);
                $id = $this->Branch->StoreBranch($branch);
                //$rold_id_1 = DB::table('users_roles')->where('role_id','=','3')->where('union_branch_id','=',$branch['union_branch_id'])->update(['role_id'=>'4']);
                $rold_id_1 = DB::statement("UPDATE users_roles LEFT JOIN users ON users.id = users_roles.user_id SET users_roles.role_id = 4 WHERE users_roles.role_id = 3 AND users.union_branch_id = '$union_branch_id'");
                $member_user = new User();
                $member_user->name = $request->input('branch_name');
                $member_user->email = $request->input('email');
                $member_user->password = bcrypt($randompass);
                $member_user->save();
                $member_user->roles()->attach($company_head_role);
                $status =1;
            }

            $mail_data = array( 
                'name' => $request->input('branch_name'),
                'email' => $branch['email'],
                'password' => $randompass,
                'site_url' => URL::to("/"),
                'company_type' => $company_type,
            );
            $status = Mail::to($branch['email'])->send(new CompanyBranchMailable($mail_data));

            if( count(Mail::failures()) > 0 ) {
                return redirect($redirect_url)->with('message','Company Account created successfully, Failed to send mail');
            }else{
                return redirect($redirect_url)->with('message','Company Account created successfully, password sent to mail');
            }
        }

        // $data_exists = DB::table('company_branch')->where([
        //    ['branch_name','=', $branch['branch_name']],
        //    ['status','=','1'] 
        //     ])->count();
        // $defdaultLang = app()->getLocale();
        // if($data_exists>0 && $data_exists!='' && $data_exists!='NULL')
        // {
        //     return redirect($defdaultLang.'/branch')->with('message','Branch Name Already Exists');
        // }
        // else
        // {
        //     $id = $this->Branch->StoreBranch($branch);
        //     return redirect($defdaultLang.'/branch')->with('message','Branch Name Added Succesfully');
        // }
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
    public function edit($lang,$id)
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
    public function update($lang, Request $request)
    {
        $id = $request->input('id');
        $user_id = User::where('branch_id',$id)->pluck('id')[0];
        $branch['company_id'] = $request->input('company_id');
        $branch['union_branch_id'] = $request->input('union_branch_id');
        $branch['branch_name'] = $request->input('branch_name');
        $branch['country_id'] = $request->input('country_id');
        $branch['state_id'] = $request->input('state_id');
        $branch['city_id'] = $request->input('city_id');
        $branch['postal_code'] = $request->input('postal_code');
        $branch['address_one'] = $request->input('address_one');
        $branch['address_two'] = $request->input('address_two');
        $branch['address_three'] = $request->input('address_three');
        $branch['phone'] = $request->input('phone');
        $branch['mobile'] = $request->input('mobile');
        $branch['email'] = $request->input('email');
       
        $defdaultLang = app()->getLocale();

        $is_head = $request->input('is_head');
        if(isset($is_head)){
            $branch['is_head'] = 1;
        }else{
            $branch['is_head'] = 0;
        }
        $union_branch_id = $request->input('union_branch_id');

        $is_head_exists = DB::table('company_branch')->where([
            ['is_head','=','1'],
            ['union_branch_id','=', $request->input('union_branch_id')],
            ['status','=','1']
            ])->count();
        if($branch['is_head']==0){
            $upid = DB::table('company_branch')->where('id','=',$id)->update($branch);
            $rold_id_2 = DB::table('users_roles')->where('role_id','=','3')->where('user_id','=',$user_id)->update(['role_id'=>'4']);
        }else{
            $data = DB::table('company_branch')->where('is_head','=','1')->where('union_branch_id','=',$union_branch_id)->update(['is_head'=>'0']);
            $rold_id_1 = DB::statement("UPDATE users_roles LEFT JOIN users ON users.id = users_roles.user_id SET users_roles.role_id = 4 WHERE users_roles.role_id = 3 AND users.union_branch_id = '$union_branch_id'");
            $upid = DB::table('company_branch')->where('id','=',$id)->update($branch);
            $rold_id_2 = DB::table('users_roles')->where('role_id','=','4')->where('user_id','=',$user_id)->update(['role_id'=>'3']);
        }

		return redirect($defdaultLang.'/branch')->with('message','Branch Details Updated Succesfully');
    }
    public function delete($lang,$id)
	{
        $id = Crypt::decrypt($id);
        $data = DB::table('company_branch')->where('id','=',$id)->update(['status'=>'0']);
        $defdaultLang = app()->getLocale();
		return redirect($defdaultLang.'/branch')->with('branch','Branch Deleted Succesfully');
	} 
}
