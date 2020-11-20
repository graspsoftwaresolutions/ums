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
use Artisan;
use App\Jobs\UpdateMemberStatus;

use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

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
		
		$user = Auth::user();
        //dd($user->hasRole('developer'));
		$get_roles = Auth::user()->roles;
		//dd($user);
		if(count($get_roles)==0){
			return view('errors.data-error')->with('data','User role does not configured');
		}	
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
        }else if($user_role=='staff-union-branch'){

            $union_group_id = DB::table('staff_union_account')->where('user_id',$user_id)->pluck('union_group_id')->first();
            
			$member_count = 0;
			if($union_group_id!=0){
				$union_branch_ids = DB::table('union_group_branches')->where('union_group_id',$union_group_id)->pluck('union_branch_id');

				$results = DB::table('membership as m')->select(DB::raw('count(*) as count'))
					->leftjoin('company_branch as cb', 'm.branch_id' ,'=','cb.id')
                    ->whereIn('cb.union_branch_id',$union_branch_ids)
                    ->first();
                // dd($results);
				
				//$rawQuery = "SELECT count(*) as count from company_branch as c inner join membership as m on c.id=m.branch_id where c.union_branch_id=$union_branch_id";
				//$results = DB::select( DB::raw($rawQuery));
				if(!empty($results)){
					$member_count = $results->count;
				}
				$company_branch_count = CompanyBranch::whereIn('union_branch_id',$union_branch_ids)->count();
			}else{
				$member_count = 0;
				$company_branch_count = 0;
			}
            $data['total_member_count'] = $member_count;
            $data['total_company_branch_count'] = $company_branch_count;
        }
        else if($user_role=='company'){
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
			$memberid = DB::table('irc_account as irc')->where('user_id','=',$user_id)
			->pluck('MemberCode')->first();  
			$unionbranchid = DB::table('membership as m')
				->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
				->where('m.id','=',$memberid)->pluck('cb.union_branch_id')->first();


			$union_no = $unionbranchid;

			$unionbranchname = DB::table('union_branch as ub')
			->where('ub.id','=',$unionbranchid)
			->pluck('union_branch')->first();  

			if($unionbranchname=='SEREMBAN' || $unionbranchname=='JB'){
				$unionbranchids = DB::table('union_branch as ub')
						->where(function($query) use ($union_no){
							$query->where('ub.union_branch', '=',"SEREMBAN")
								->orWhere('ub.union_branch', '=',"JB");
						})
					->pluck('ub.id')->toArray();  
			}else if($unionbranchname=='PENANG' || $unionbranchname=='KEDAH'){
				$unionbranchids = DB::table('union_branch as ub')
				->where(function($query) use ($union_no){
					$query->where('ub.union_branch', '=',"PENANG")
						->orWhere('ub.union_branch', '=',"KEDAH");
				})
				->pluck('ub.id')->toArray();  
			}else if($unionbranchname=='IPOH'){
				$unionbranchids = DB::table('union_branch as ub')
				->where('ub.union_branch','=','IPOH')
				->pluck('ub.id')->toArray();  
			}else if($unionbranchname=='KELANTAN'){
				$unionbranchids = DB::table('union_branch as ub')
				->where('ub.union_branch','=','KELANTAN')
				->pluck('ub.id')->toArray();  
			}else{
				//return $union_no;
				$unionbranchids = DB::table('union_branch as ub')
				->select('ub.id')
				->where(function($query) use ($union_no){
					$query->where('ub.union_branch', '=',"KL")
						->orWhere('ub.union_branch', '=',"KELANG")
						->orWhere('ub.union_branch', '=',"PAHANG");
				})
				->pluck('ub.id')->toArray();
				//->first();  
			}

			$c_head = DB::table('union_branch as ub')->select('ub.id')
						->where('ub.id','=',$unionbranchid)
						->where('ub.is_head','=',1)
						//->dump()
						->count();

			$total_ircpending_qry = DB::table('irc_confirmation as i')
								->leftjoin('membership as m','m.id','=','i.resignedmemberno') 
								->leftjoin('company_branch as cb','m.branch_id','=','cb.id')     
								->where('i.status','=',0)
								->where('i.irc_status','=',0);
							
			// if($c_head!=1){
			// 	$total_ircpending_qry = $total_ircpending_qry->where('cb.union_branch_id','=',$unionbranchid);
			// }
			$total_ircpending_qry = $total_ircpending_qry->whereIn('cb.union_branch_id',$unionbranchids);

			$total_ircpending_count = $total_ircpending_qry->count();
								
			$total_ircconfirm_qry = DB::table('irc_confirmation as i')
								->leftjoin('membership as m','m.id','=','i.resignedmemberno') 
								->leftjoin('company_branch as cb','m.branch_id','=','cb.id')    
								->where('i.status','=',1);

			$total_ircconfirm_qry = $total_ircconfirm_qry->whereIn('cb.union_branch_id',$unionbranchids);
			// if($c_head!=1){
			// 	$total_ircconfirm_qry = $total_ircconfirm_qry->where('cb.union_branch_id','=',$unionbranchid);
			// }
			$total_ircconfirm_count = $total_ircconfirm_qry->count();

			
			$total_ircapp_qry = DB::table('irc_confirmation as i')
									->leftjoin('membership as m','m.id','=','i.resignedmemberno') 
									->leftjoin('company_branch as cb','m.branch_id','=','cb.id')  
									
									->where('i.status','=',0)
									->where('i.irc_status','=',1);

			$total_ircapp_qry = $total_ircapp_qry->whereIn('cb.union_branch_id',$unionbranchids);
			// if($c_head!=1){
			// 	$total_ircapp_qry = $total_ircapp_qry->where('cb.union_branch_id','=',$unionbranchid);
			// }

			$total_ircapp_count = $total_ircapp_qry->count();

			$irc_count = $total_ircpending_count+$total_ircconfirm_count+$total_ircapp_count;

			
			$data['total_irc_count'] = $irc_count;
			$data['total_ircpending_count'] = $total_ircpending_count;
			$data['total_ircapproval_count'] = $total_ircapp_count;
			$data['total_ircconfirm_count'] = $total_ircconfirm_count;
		}else if($user_role=='irc-confirmation-officials'){

			$total_ircpending_qry = DB::table('irc_confirmation as i')
								->leftjoin('membership as m','m.id','=','i.resignedmemberno') 
								->leftjoin('company_branch as cb','m.branch_id','=','cb.id')     
								->where('i.status','=',0)
								->where('i.irc_status','=',0);
								
			//$total_ircpending_qry = $total_ircpending_qry->whereIn('cb.union_branch_id',$unionbranchids);

			$total_ircpending_count = $total_ircpending_qry->count();
								
			$total_ircconfirm_qry = DB::table('irc_confirmation as i')
								->leftjoin('membership as m','m.id','=','i.resignedmemberno') 
								->leftjoin('company_branch as cb','m.branch_id','=','cb.id')    
								->where('i.status','=',1);

			//$total_ircconfirm_qry = $total_ircconfirm_qry->whereIn('cb.union_branch_id',$unionbranchids);
			
			$total_ircconfirm_count = $total_ircconfirm_qry->count();

			
			$total_ircapp_qry = DB::table('irc_confirmation as i')
									->leftjoin('membership as m','m.id','=','i.resignedmemberno') 
									->leftjoin('company_branch as cb','m.branch_id','=','cb.id')  
									
									->where('i.status','=',0)
									->where('i.irc_status','=',1);

			//$total_ircapp_qry = $total_ircapp_qry->whereIn('cb.union_branch_id',$unionbranchids);
			
			$total_ircapp_count = $total_ircapp_qry->count();

			$irc_count = $total_ircpending_count+$total_ircconfirm_count+$total_ircapp_count;

			
			$data['total_irc_count'] = $irc_count;
			$data['total_ircpending_count'] = $total_ircpending_count;
			$data['total_ircapproval_count'] = $total_ircapp_count;
			$data['total_ircconfirm_count'] = $total_ircconfirm_count;
		}else if($user_role=='irc-branch-committee'){
			$unionbranchid = DB::table('irc_account as irc')->where('user_id','=',$user_id)
			->pluck('union_branch_id')->first();  
			$c_head = DB::table('union_branch as ub')->select('ub.id')
						->where('ub.id','=',$unionbranchid)
						->where('ub.is_head','=',1)
						//->dump()
						->count();

			$union_no = $unionbranchid;

			if($unionbranchid==1){
				$unionbranchids = DB::table('union_branch as ub')
						->where(function($query) use ($union_no){
							$query->where('ub.union_branch', '=',"SEREMBAN")
								->orWhere('ub.union_branch', '=',"JB");
						})
					->pluck('ub.id')->toArray();  
			}else if($unionbranchid==2){
				$unionbranchids = DB::table('union_branch as ub')
				->where(function($query) use ($union_no){
					$query->where('ub.union_branch', '=',"PENANG")
						->orWhere('ub.union_branch', '=',"KEDAH");
				})
				->pluck('ub.id')->toArray();  
			}else if($unionbranchid==3){
				$unionbranchids = DB::table('union_branch as ub')
				->where('ub.union_branch','=','IPOH')
				->pluck('ub.id')->toArray();  
			}else if($unionbranchid==4){
				$unionbranchids = DB::table('union_branch as ub')
				->where('ub.union_branch','=','KELANTAN')
				->pluck('ub.id')->toArray();  
			}else{
				//return $union_no;
				$unionbranchids = DB::table('union_branch as ub')
				->select('ub.id')
				->where(function($query) use ($union_no){
					$query->where('ub.union_branch', '=',"KL")
						->orWhere('ub.union_branch', '=',"KELANG")
						->orWhere('ub.union_branch', '=',"PAHANG");
				})
				->pluck('ub.id')->toArray();
				//->first();  
			}

			$total_ircpending_qry = DB::table('irc_confirmation as i')
								->leftjoin('membership as m','m.id','=','i.resignedmemberno') 
								->leftjoin('company_branch as cb','m.branch_id','=','cb.id')     
								->where('i.status','=',0)
								->where('i.irc_status','=',0);
								
			//if($c_head!=1){
				$total_ircpending_qry = $total_ircpending_qry->whereIn('cb.union_branch_id',$unionbranchids);
			//}
			$total_ircpending_count = $total_ircpending_qry->count();
								
			$total_ircconfirm_qry = DB::table('irc_confirmation as i')
								->leftjoin('membership as m','m.id','=','i.resignedmemberno') 
								->leftjoin('company_branch as cb','m.branch_id','=','cb.id')    
								->where('i.status','=',1);
			//if($c_head!=1){
				$total_ircconfirm_qry = $total_ircconfirm_qry->whereIn('cb.union_branch_id',$unionbranchids);
			//}
			$total_ircconfirm_count = $total_ircconfirm_qry->count();

			
			$total_ircapp_qry = DB::table('irc_confirmation as i')
									->leftjoin('membership as m','m.id','=','i.resignedmemberno') 
									->leftjoin('company_branch as cb','m.branch_id','=','cb.id')  
								
									->where('i.status','=',0)
									->where('i.irc_status','=',1);
			//if($c_head!=1){
				$total_ircapp_qry = $total_ircapp_qry->whereIn('cb.union_branch_id',$unionbranchids);
			//}

			$total_ircapp_count = $total_ircapp_qry->count();
			$irc_count = $total_ircpending_count+$total_ircconfirm_count+$total_ircapp_count;

			
			$data['total_irc_count'] = $irc_count;
			$data['total_ircpending_count'] = $total_ircpending_count;
			$data['total_ircapproval_count'] = $total_ircapp_count;
			$data['total_ircconfirm_count'] = $total_ircconfirm_count;
			
		}else if($user_role=='irc-branch-committee-officials'){
			// $unionbranchid = DB::table('irc_account as irc')->where('user_id','=',$user_id)
			// ->pluck('union_branch_id')->first();  
			// $c_head = DB::table('union_branch as ub')->select('ub.id')
			// 			->where('ub.id','=',$unionbranchid)
			// 			->where('ub.is_head','=',1)
			// 			//->dump()
			// 			->count();

			// $union_no = $unionbranchid;

			// if($unionbranchid==1){
			// 	$unionbranchids = DB::table('union_branch as ub')
			// 			->where(function($query) use ($union_no){
			// 				$query->where('ub.union_branch', '=',"SEREMBAN")
			// 					->orWhere('ub.union_branch', '=',"JB");
			// 			})
			// 		->pluck('ub.id')->toArray();  
			// }else if($unionbranchid==2){
			// 	$unionbranchids = DB::table('union_branch as ub')
			// 	->where(function($query) use ($union_no){
			// 		$query->where('ub.union_branch', '=',"PENANG")
			// 			->orWhere('ub.union_branch', '=',"KEDAH");
			// 	})
			// 	->pluck('ub.id')->toArray();  
			// }else if($unionbranchid==3){
			// 	$unionbranchids = DB::table('union_branch as ub')
			// 	->where('ub.union_branch','=','IPOH')
			// 	->pluck('ub.id')->toArray();  
			// }else if($unionbranchid==4){
			// 	$unionbranchids = DB::table('union_branch as ub')
			// 	->where('ub.union_branch','=','KELANTAN')
			// 	->pluck('ub.id')->toArray();  
			// }else{
			// 	//return $union_no;
			// 	$unionbranchids = DB::table('union_branch as ub')
			// 	->select('ub.id')
			// 	->where(function($query) use ($union_no){
			// 		$query->where('ub.union_branch', '=',"KL")
			// 			->orWhere('ub.union_branch', '=',"KELANG")
			// 			->orWhere('ub.union_branch', '=',"PAHANG");
			// 	})
			// 	->pluck('ub.id')->toArray();
			// 	//->first();  
			// }

			$total_ircpending_qry = DB::table('irc_confirmation as i')
								->leftjoin('membership as m','m.id','=','i.resignedmemberno') 
								->leftjoin('company_branch as cb','m.branch_id','=','cb.id')     
								->where('i.status','=',0)
								->where('i.irc_status','=',0);
								
			//if($c_head!=1){
				//$total_ircpending_qry = $total_ircpending_qry->whereIn('cb.union_branch_id',$unionbranchids);
			//}
			$total_ircpending_count = $total_ircpending_qry->count();
								
			$total_ircconfirm_qry = DB::table('irc_confirmation as i')
								->leftjoin('membership as m','m.id','=','i.resignedmemberno') 
								->leftjoin('company_branch as cb','m.branch_id','=','cb.id')    
								->where('i.status','=',1);
			//if($c_head!=1){
				//$total_ircconfirm_qry = $total_ircconfirm_qry->whereIn('cb.union_branch_id',$unionbranchids);
			//}
			$total_ircconfirm_count = $total_ircconfirm_qry->count();

			
			$total_ircapp_qry = DB::table('irc_confirmation as i')
									->leftjoin('membership as m','m.id','=','i.resignedmemberno') 
									->leftjoin('company_branch as cb','m.branch_id','=','cb.id')  
								
									->where('i.status','=',0)
									->where('i.irc_status','=',1);
			//if($c_head!=1){
				//$total_ircapp_qry = $total_ircapp_qry->whereIn('cb.union_branch_id',$unionbranchids);
			//}

			$total_ircapp_count = $total_ircapp_qry->count();
			$irc_count = $total_ircpending_count+$total_ircconfirm_count+$total_ircapp_count;

			
			$data['total_irc_count'] = $irc_count;
			$data['total_ircpending_count'] = $total_ircpending_count;
			$data['total_ircapproval_count'] = $total_ircapp_count;
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
	
	public function userList(){
		$data = [ 'branch_id' => 1, 'union_id' => 1];
		return Excel::download(new UsersExport($data), 'users.xlsx');
	}
	public function UpdateMemberStatus(Request $request){
        $company_auto_id = 403;
        //Artisan::call('queue:work --tries=1 --timeout=10000');
        UpdateMemberStatus::dispatch($company_auto_id);
        Artisan::call('queue:work --tries=1 --timeout=20000');
        echo 1;
    }
}
