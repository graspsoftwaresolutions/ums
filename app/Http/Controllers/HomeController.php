<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\CommonHelper;
use Hash;
use App\Model\UnionBranch;
use App\Model\Membership;
use App\Model\Company;
use App\Model\CompanyBranch;
use App\Model\Branch;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		//$user = Auth::user();
        //dd($user->hasRole('developer'));
        $get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;
        $user_id = Auth::user()->id;
        //return $get_union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id');
        
        $data['union_branch_count'] = '';
        if($user_role=='union'){
            $union_branch_count = unionBranch::where('is_head',0)->count();
            $data['union_branch_count'] = $union_branch_count;

            $member_count = Membership::all()->count();
            $data['total_member_count'] = $member_count;
            
            $company_count = Company::all()->count();
            $data['total_company_count'] = $company_count;

            $company_branch_count = CompanyBranch::all()->count();
            $data['total_company_branch_count'] = $company_branch_count;

            $company_branch_count = CompanyBranch::all()->count();

            $total_active_members_count = Membership::where('status_id',2)->count();
            $data['total_active_members_count'] = $total_active_members_count;
            $total_new_members_count = Membership::where('status_id',1)->count();
            $data['total_new_members_count'] = $total_new_members_count;


        }else if($user_role=='union-branch'){
            $member_count = Membership::where('union_branch_id')->count();
            $data['total_member_count'] = $member_count;
        }
        
        return view('home')->with('data',$data);
    }
	public function redirectTo()
    {
        return app()->getLocale() . '/login';
    }
	
	public function showChangePasswordForm(){
        return view('change_password');
    }
	
	public function changePassword(Request $request){
        if (!(Hash::check($request->get('currentpassword'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error","Your current password does not matches with the password you provided. Please try again.");
        }
        if(strcmp($request->get('currentpassword'), $request->get('new-password')) == 0){
            //Current password and new password are same
            return redirect()->back()->with("error","New Password cannot be same as your current password. Please choose a different password.");
        }
        $validatedData = $request->validate([
            'currentpassword' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);
        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('password'));
        $user->save();
        return redirect()->back()->with("success","Password changed successfully !");
    }
}
