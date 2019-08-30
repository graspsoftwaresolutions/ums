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
use App\Model\Status;
use App\Model\Irc;
use App\User;
use DB;

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
        ini_set('memory_limit', '-1');
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
            $union_branch_count = unionBranch::where('is_head','!=',1)->where('status',1)->count();
            $data['union_branch_count'] = $union_branch_count;
            
            //$member_count=0;
            //$member_count = Membership::where('status',1)->count();
            //$data['total_member_count'] = $member_count;

            $company_count = Company::where('status',1)->count();
            
            //$company_count = CompanyBranch::where('is_head',1)->where('status',1)->count();
            $data['total_company_count'] = $company_count;

            $company_branch_count = CompanyBranch::where('status',1)->count();
            $data['total_company_branch_count'] = $company_branch_count;

            $total_approved_members_count = Membership::where('is_request_approved',1)->count();
            $data['total_approved_members_count'] = $total_approved_members_count;
            $total_pending_members_count = Membership::where('is_request_approved',0)->count();
            $data['total_pending_members_count'] = $total_pending_members_count;

            $member_count = $total_approved_members_count + $total_pending_members_count;
            $data['total_member_count'] = $member_count;

          
           $status_active_members = Membership::where([ ['status_id',1],['status','=','1'] ])->count();
           $data['totla_active_member_count'] = $status_active_members;

           $status_defaulter_members = Membership::where([ ['status_id',2],['status','=','1'] ])->count();
           $data['totla_defaulter_member_count'] = $status_defaulter_members;

           $status_struckoff_members = Membership::where([ ['status_id',3],['status','=','1'] ])->count();
           $data['totla_struckoff_member_count'] = $status_struckoff_members;

           $status_resigned_members = Membership::where([ ['status_id',4],['status','=','1'] ])->count();
           $data['totla_resigned_member_count'] = $status_resigned_members;
          

        }else if($user_role=='union-branch'){
            $union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id');
            
			$member_count = 0;
			if(count($union_branch_id)>0){
				$union_branch_id = $union_branch_id[0];
				$rawQuery = "SELECT count(*) as count from company_branch as c inner join membership as m on c.id=m.branch_id where c.union_branch_id=$union_branch_id";
				$results = DB::select( DB::raw($rawQuery));
				if(!empty($results)){
					$member_count = $results[0]->count;
				}
				$company_branch_count = CompanyBranch::where('union_branch_id',$union_branch_id)->count();
			}else{
				$member_count = 0;
				$company_branch_count = 0;
			}
            $data['total_member_count'] = $member_count;
            $data['total_company_branch_count'] = $company_branch_count;
        }else if($user_role=='company'){
			$company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id');
			$company_branch_count = 0;
			$member_count = 0;
			if(count($company_id)>0){
				$companyid = $company_id[0];
				$company_branch_count = CompanyBranch::where('company_id',$company_id[0])->where('status',1)->count();
				$rawQuery = "SELECT count(*) as count from company_branch as c inner join membership as m on c.id=m.branch_id where company_id=$companyid";
				$results = DB::select( DB::raw($rawQuery));
				if(!empty($results)){
					$member_count = $results[0]->count;
				}
			}
			$data['total_company_branch_count'] = $company_branch_count;
			$data['total_member_count'] = $member_count;
		}else if($user_role=='company-branch'){
			$member_count = 0;
			$branch_id = CompanyBranch::where('user_id',$user_id)->pluck('id');
			if(count($branch_id)>0){
				$branchid = $branch_id[0];
				$rawQuery = "SELECT count(*) as count from company_branch as c inner join membership as m on c.id=m.branch_id where branch_id=$branchid";
				$results = DB::select( DB::raw($rawQuery));
				if(!empty($results)){
					$member_count = $results[0]->count;
				}
			}
			$data['total_member_count'] = $member_count;
		}else if($user_role=='irc-confirmation'){
			$total_ircpending_count = DB::table('irc_confirmation as i')
								->where('i.status','=',0)
								->where(function($query) use ($user_id){
									$query->orWhere('i.waspromoted','!=','1')
									  ->orWhere('i.beforepromotion','!=','1')
									  ->orWhere('i.attached','!=','1')
									  ->orWhere('i.herebyconfirm','!=','1')
									  ->orWhere('i.filledby','!=','1')
									  ->orWhere('i.nameofperson','!=','1')
									  ->orWhereNull('i.waspromoted')
									  ->orWhereNull('i.beforepromotion')
									  ->orWhereNull('i.attached')
									  ->orWhereNull('i.herebyconfirm')
									  ->orWhereNull('i.filledby')
									  ->orWhereNull('i.nameofperson');
									})->count();
								
			$total_ircconfirm_count = DB::table('irc_confirmation as i')->where('i.nameofperson','=','1')
									  ->where('i.waspromoted','=','1')
									  ->where('i.beforepromotion','=','1')
									  ->where('i.attached','=','1')
									  ->where('i.herebyconfirm','=','1')
									  ->where('i.filledby','=','1')
									  ->count();
			$irc_count = $total_ircpending_count+$total_ircconfirm_count;
			
			$data['total_irc_count'] = $irc_count;
			$data['total_ircpending_count'] = $total_ircpending_count;
			$data['total_ircconfirm_count'] = $total_ircconfirm_count;
		}else if($user_role=='irc-branch-committee'){
			$total_ircpending_count = DB::table('irc_confirmation as i')
									->where('i.nameofperson','=','1')
									->where('i.waspromoted','=','1')
									->where('i.beforepromotion','=','1')
									->where('i.attached','=','1')
									->where('i.herebyconfirm','=','1')
									->where('i.filledby','=','1')
									->where('i.status','=',0)->count();
			$total_ircconfirm_count = DB::table('irc_confirmation as i')->where('i.status','=',1)->count();
			$irc_count = $total_ircpending_count+$total_ircconfirm_count;
			
			$data['total_irc_count'] = $irc_count;
			$data['total_ircpending_count'] = $total_ircpending_count;
			$data['total_ircconfirm_count'] = $total_ircconfirm_count;
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
