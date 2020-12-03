<?php

namespace App\Http\Controllers;
use App\Role;
use App\User;
use App\Model\Membership;
use App\Model\Irc;
use App\Model\Reason;
use DB;
use URL;
use Illuminate\Support\Facades\Crypt;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

use Illuminate\Http\Request;

class IrcController extends CommonController
{
    public function __construct() {
        $this->Irc = new Irc;
		$this->middleware('auth');
    }
	public function index() {
		$irc = env("IRC",'Not set');
		$irc = $irc=='' ? 0 : $irc;
        echo 'IRC:'.$irc;
	}
	
	public function ircIndex(Request $request)
    {
		$get_roles = Auth::user()->roles;
		$user_role = $get_roles[0]->slug;

		if($user_role=='irc-confirmation' || $user_role=='irc-confirmation-officials'){
			$data['reason_view'] = Reason::where('status','=','1')->get();
			$encmemberid = $request->input('member');
			if(isset($encmemberid)){
				$member_id = Crypt::decrypt($encmemberid);
				$data['member_id'] = $member_id;
				$res = DB::table('membership as m')->select(DB::raw("if(count('m.new_ic') > 0  ,m.new_ic,m.old_ic) as nric"),'m.member_number','m.id as memberid','d.designation_name as membertype','p.person_title as persontitle','m.name as membername','cb.branch_name','c.company_name',DB::raw("DATE_FORMAT(m.dob,'%d/%b/%Y') as dob"),'m.gender',DB::raw("DATE_FORMAT(m.doj,'%d/%b/%Y') as doj"),DB::raw("(PERIOD_DIFF( DATE_FORMAT(CURDATE(), '%Y%m') , DATE_FORMAT(dob, '%Y%m') )) DIV 12 AS age"),'r.race_name','cb.address_one','cb.phone','cb.mobile','cb.union_branch_id')
							->leftjoin('designation as d','d.id','=','m.designation_id')
							->leftjoin('persontitle as p','p.id','=','m.member_title_id')
							->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
							->leftjoin('company as c','c.id','=','cb.company_id')
							->leftjoin('race as r','r.id','=','m.race_id')
							->where('m.id','=',$member_id)
							->first();
		
				$irc_count = DB::table('irc_confirmation as irc')
				->where('irc.resignedmemberno','=',$member_id)
				->count();
				if($irc_count==0){
					$data['irc_data'] = $res;
				}else{
					return redirect(app()->getLocale().'/irc_list')->with('message', 'Irc Already exits');
				}
			}else{
				$data['member_id'] = '';
			}
			//dd($data['member_id']);
			return view('irc.irc')->with('data',$data);
		}else{
			return redirect('/');
		}
		
	}
	
	public function ListIrcAccount() {
		
		return view('irc.users');
    }
	
	public function AddIrcAccount() {
		$data['union_view'] = []; 
		//DB::table('union_branch')->where('status','=','1')->get();
		$data['union_group'] = DB::table('union_groups')->get();
		return view('irc.add_user')->with('data',$data);
    }
	public function SaveUserAccount(Request $request) {
		$member_name = $request->input('name');
        $member_email = $request->input('email');
		$password = $request->input('password');
		$member_code = $request->input('member_code');
		$account_type = $request->input('account_type');
		$union_branch_id = $request->input('union_branch_id');
		if($account_type=='irc-confirmation' && $member_code==""){
			return redirect( app()->getLocale().'/add_irc_account')->with('error','Please pick a member'); 
		}
		if($account_type=='irc-branch-committee' && $union_branch_id==""){
			return redirect( app()->getLocale().'/add_irc_account')->with('error','Please select Branch'); 
		}
		
		$user_role = Role::where('slug', $account_type)->first();
		$request->validate([
            'name' => 'required',
                ], [
            'name.required' => 'Please enter User name',
		]);
		$member_user = new User();
		$member_user->name = $member_name;
		$member_user->email = $member_email;
		$member_user->password = bcrypt($password);
		
		$member_user->save();
		$member_user->roles()->attach($user_role);
		if($member_user){
			DB::table('irc_account')->insert(
				['MemberCode' => $member_code,'union_branch_id' => $union_branch_id, 'user_id' => $member_user->id,'account_type' => $account_type, 'created_by' => Auth::user()->id, 'created_at' => date('Y-m-d'), 'status' => 1]
			);
			return redirect( app()->getLocale().'/list_irc_account')->with('message','User account added successfully'); 
		}else{
			return redirect( app()->getLocale().'/list_irc_account')->with('error','Failed to add account'); 
		}
	}
	
	public function ajax_irc_users_list(Request $request){
		$columns = array(
            0 => 'u.name',
            1 => 'u.email',
            2 => 'i.MemberCode',
            3 => 'i.account_type',
            4 => 'id',
        );

		$totalData = DB::table('irc_account as i')
					 ->leftjoin('users as u', 'i.user_id', '=', 'u.id')
					 ->where('i.status', '=', 1)
					 ->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');

        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            if( $limit == -1){
				$users =  DB::table('irc_account as i')->select('u.id','u.name','u.email','i.account_type','m.member_number as MemberCode')
							->leftjoin('users as u', 'i.user_id', '=', 'u.id')
							->leftjoin('membership as m', 'm.id', '=', 'i.MemberCode')
							->where('i.status', '=', 1)
							->orderBy($order,$dir)
							->get()->toArray();
            }else{
				$users = DB::table('irc_account as i')->select('u.id','u.name','u.email','i.account_type','m.member_number as MemberCode')
						->leftjoin('users as u', 'i.user_id', '=', 'u.id')
						->leftjoin('membership as m', 'm.id', '=', 'i.MemberCode')
						->where('i.status', '=', 1)
						->offset($start)
						->limit($limit)
						->orderBy($order,$dir)
						->get()->toArray();
            }
        
        }
        else {
        $search = $request->input('search.value'); 
        if( $limit == -1){
            $users = DB::table('irc_account as i')->select('u.id','u.name','u.email','i.account_type','m.member_number as MemberCode')
						->leftjoin('users as u', 'i.user_id', '=', 'u.id')
						->leftjoin('membership as m', 'm.id', '=', 'i.MemberCode')
						->where('i.status', '=', 1)
						->where('u.id','LIKE',"%{$search}%")
                        ->orWhere('u.name', 'LIKE',"%{$search}%")
                        ->orWhere('u.email', 'LIKE',"%{$search}%")
                        ->orderBy($order,$dir)
                        ->get()->toArray();
        }else{
            $users =  DB::table('irc_account as i')->select('u.id','u.name','u.email','i.account_type','m.member_number as MemberCode')
					->leftjoin('users as u', 'i.user_id', '=', 'u.id')
					->leftjoin('membership as m', 'm.id', '=', 'i.MemberCode')
					->where('u.id','LIKE',"%{$search}%")
					->where('i.status', '=', 1)
					->orWhere('u.name', 'LIKE',"%{$search}%")
					->orWhere('u.email', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get()->toArray();
        }
        $totalFiltered = DB::table('irc_account as i')->select('u.id','u.name','u.email','i.account_type','m.member_number as MemberCode')
							->leftjoin('users as u', 'i.user_id', '=', 'u.id')
							->leftjoin('membership as m', 'm.id', '=', 'i.MemberCode')
							->where('i.status', '=', 1)
							->where('u.id','LIKE',"%{$search}%")
							->orWhere('u.name', 'LIKE',"%{$search}%")
							->orWhere('u.email', 'LIKE',"%{$search}%")
                   			 ->count();
        }
        $data = $this->CommonAjaxReturnold($users, 0, 'master.destroy', 0);
    
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );

        echo json_encode($json_data); 
	}

	public function listIrc(Request $request){
		$data['irc_status'] = '';
        if(!empty($request->all())){
            $data['irc_status'] = $request->input('status');
        }
		return view('irc.list_irc')->with('data',$data);
	}

	public function getIrcMembersList(Request $request)
	{
		$userid = Auth::user()->id;
		$get_roles = Auth::user()->roles;
		$user_role = $get_roles[0]->slug;
		//$membercode = DB::table('irc_account as irc')->where('user_id','=',$userid)->pluck('MemberCode')->first();
		// $c_head = DB::table('membership as m')->select('m.id')
		// 					->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
		// 					->leftjoin('union_branch as u','cb.union_branch_id','=','u.id')
		// 					->where('m.id','=',$membercode)
		// 					->where('u.is_head','=',1)
		// 					//->dump()
		// 					->count();
		//dd(2);
		$searchkey = $request->input('searchkey');
		$search = $request->input('query');
		$unionbranchid = $request->input('union_branch_id');

		$unionbranchname = DB::table('union_branch as ub')
			->where('ub.id','=',$unionbranchid)
			->pluck('union_branch')->first();  
	
			//return $unionbranchname;
	
		$union_no = $unionbranchid;
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
		}else if($unionbranchname=='IPOH' || $unionbranchname=='PERAK'){
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

		$ircsuggestions = DB::table('irc_account as irc')->select(DB::raw('CONCAT(m.name, " - ", m.member_number) AS value'),'m.id as number','m.branch_id as branch_id','m.member_number','irc.MemberCode')
							->leftjoin('membership as m','irc.MemberCode','=','m.id')
							->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
							->where('irc.account_type','=','irc-confirmation')
							->where(function($query) use ($search){
                                $query->orWhere('m.member_number', 'LIKE',"{$search}%")
									->orWhere('m.name', 'LIKE',"{$search}%")
									->orWhere('m.new_ic', 'LIKE',"{$search}%")
									->orWhere('m.old_ic', 'LIKE',"{$search}%");
							})
							->where('m.status_id','!=',4);
		//if($c_head!=1){
			//$ircsuggestions = $ircsuggestions->whereIn('cb.union_branch_id',$unionbranchids);
		//}					
	
		$res['suggestions'] = $ircsuggestions->limit(25)
								->get(); 
         return response()->json($res);
	}
	public function getIrcMembersListValues(Request $request)
	{
		$member_id = $request->member_id;
		$res = DB::table('irc_account as irc')->select('m.id as mid','m.name as membername','c.company_name as bankname','cb.address_one','cb.phone','cb.mobile')
				->leftjoin('membership as m','irc.MemberCode','=','m.id')
				->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
				->leftjoin('company as c','cb.company_id','=','c.id')
				->where('irc.account_type','=','irc-confirmation')
				->where('m.member_number','=',$member_id)
				->first();
		//dd($res);
		return response()->json($res);
	}
	
	public function ajax_irc_list(Request $request){
		$get_roles = Auth::user()->roles;
		$user_role = $get_roles[0]->slug;
		$user_id = Auth::user()->id;
		$searchfilter = $request->input('searchfilter');
		$statusfilter = $request->input('statusfilter');
		$columns = array(
            0 => 'i.id',
            1 => 'i.resignedmemberno',
            2 => 'i.resignedmembername',
            3 => 'i.resignedmembericno',
            4 => 'i.resignedmemberbankname',
            5 => 'i.resignedmemberbranchname',
            6 => 'i.submitted_at',
            7 => 'i.submitted_at',
            8 => 'i.id',
        );

		$totalqry = DB::table('irc_confirmation as i')
					 ->leftjoin('membership as m', 'i.resignedmemberno', '=', 'm.id')
					 ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')  ;
		if($user_role=='irc-branch-committee' || $user_role=='irc-branch-committee-officials'){
			$unionbranchid = DB::table('irc_account as irc')->where('user_id','=',$user_id)
			->pluck('union_branch_id')->first();  

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

			$c_head = DB::table('union_branch as ub')->select('ub.id')
						->where('ub.id','=',$unionbranchid)
						->where('ub.is_head','=',1)
						//->dump()
						->count();
						//dd($c_head);
			
			if($statusfilter!=''){
				if($statusfilter==0){
					  $totalqry = $totalqry->where('i.irc_status','=',1)
					  ->where('i.status','=',$statusfilter);
				}else{
					$totalqry = $totalqry->where('i.status','=',$statusfilter)->where('i.irc_status','=',1);
				}
			}else{
				 $totalqry = $totalqry->where('i.irc_status','=',1);
			}
			if($user_role=='irc-confirmation' || $user_role=='irc-branch-committee'){
				$totalqry = $totalqry->whereIn('cb.union_branch_id',$unionbranchids);
			}
			// if($c_head!=1){
			// 	$totalqry = $totalqry->where('cb.union_branch_id','=',$unionbranchid);
			// }
		}else{
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
			}else if($unionbranchname=='IPOH' || $unionbranchname=='PERAK'){
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
			if($statusfilter!=''){
				if($statusfilter==0){
					   $totalqry = $totalqry->where('i.status','=',0)
							->where('i.irc_status','=',0);
							// ->where(function($query) use ($statusfilter){
							// 	$query->orWhere('i.waspromoted','!=','1')
							// 	  ->orWhere('i.beforepromotion','!=','1')
							// 	  ->orWhere('i.attached','!=','1')
							// 	  ->orWhere('i.herebyconfirm','!=','1')
							// 	  ->orWhere('i.filledby','!=','1')
							// 	  ->orWhere('i.nameofperson','!=','1')
							// 	  ->orWhereNull('i.waspromoted')
							// 	  ->orWhereNull('i.beforepromotion')
							// 	  ->orWhereNull('i.attached')
							// 	  ->orWhereNull('i.herebyconfirm')
							// 	  ->orWhereNull('i.filledby')
							// 	  ->orWhereNull('i.nameofperson');
							// 	});
						// if($c_head!=1){
						// 	$totalqry = $totalqry->where('cb.union_branch_id','=',$unionbranchid);
						// }
				}else{
					$totalqry = $totalqry->where('i.irc_status','=',1);
					  //->where('i.status','=','0');
					// if($c_head!=1){
					// 	$totalqry = $totalqry->where('cb.union_branch_id','=',$unionbranchid);
					// }
				}
			}else{
				// if($c_head!=1){
				// 	$totalqry = $totalqry->where('cb.union_branch_id','=',$unionbranchid);
				// }
				 //$totalqry = $totalqry->where('i.status','=','0');
			}
			if($user_role=='irc-confirmation' || $user_role=='irc-branch-committee'){
				$totalqry = $totalqry->whereIn('cb.union_branch_id',$unionbranchids);
			}
		}
		
		$commonselect = DB::table('irc_confirmation as i')
						->select(DB::raw('if(i.status=1,"Confirm","pending") as status_name'),'i.status','m.member_number as resignedmemberno','m.name as resignedmembername','i.resignedmembericno','i.resignedmemberbankname','i.resignedmemberbranchname','i.submitted_at as submitted_at','i.submitted_at as received','i.id','m.status_id as status_id','m.new_ic','m.old_ic','m.employee_id','i.irc_status')
						->leftjoin('membership as m', 'i.resignedmemberno', '=', 'm.id')
						->leftjoin('company_branch as cb','m.branch_id','=','cb.id');
		if($user_role=='irc-branch-committee' || $user_role=='irc-branch-committee-officials'){
			if($statusfilter!=''){
				if($statusfilter==0){
					  $commonselect = $commonselect->where('i.irc_status','=',1)
					  ->where('i.status','=',$statusfilter);
				}else{
					$commonselect = $commonselect->where('i.status','=',$statusfilter)->where('i.irc_status','=',1);
				}
			}else{
				 $commonselect = $commonselect->where('i.irc_status','=',1);
			}
			// if($c_head!=1){
			// 	$commonselect = $commonselect->where('cb.union_branch_id','=',$unionbranchid);
			// }
		}else{
			if($statusfilter!=''){
				if($statusfilter==0){
					  $commonselect = $commonselect->where('i.status','=',0)
								  ->where('i.irc_status','=',0);
								// ->where(function($query) use ($statusfilter){
								// 	$query->orWhere('i.waspromoted','!=','1')
								// 	  ->orWhere('i.beforepromotion','!=','1')
								// 	  ->orWhere('i.attached','!=','1')
								// 	  ->orWhere('i.herebyconfirm','!=','1')
								// 	  ->orWhere('i.filledby','!=','1')
								// 	  ->orWhere('i.nameofperson','!=','1')
								// 	  ->orWhereNull('i.waspromoted')
								// 	  ->orWhereNull('i.beforepromotion')
								// 	  ->orWhereNull('i.attached')
								// 	  ->orWhereNull('i.herebyconfirm')
								// 	  ->orWhereNull('i.filledby')
								// 	  ->orWhereNull('i.nameofperson');
								// 	});
					
				}else{
					 $commonselect = $commonselect->where('i.irc_status','=',1);
					  //->where('i.status','=','0');
				}
			}else{
				 //$commonselect = $commonselect->where('i.status','=','0');
			}
			// if($c_head!=1){
			// 	$commonselect = $commonselect->where('cb.union_branch_id','=',$unionbranchid);
			// }
		}
		if($user_role=='irc-confirmation' || $user_role=='irc-branch-committee'){
			$commonselect = $commonselect->whereIn('cb.union_branch_id',$unionbranchids);
		}
		
		$totalData = $totalqry->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');

        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($searchfilter))
        {       
			$irclist =  $commonselect;
			if( $limit != -1){
				$irclist =  $irclist->offset($start)
									->limit($limit);
			}
			if($order == 'i.id'){
				$irclist =  $irclist->orderBy($order,'desc')
							->get()->toArray();     
			}else{
				$irclist =  $irclist->orderBy($order,$dir)
							->get()->toArray();     
			}
			
        }
        else {
			$search = $searchfilter; 
			$irclist =  $commonselect->where(function($query) use ($search){
							$query->orWhere('i.id','LIKE',"%{$search}%")
								->orWhere('i.resignedmembername', 'LIKE',"%{$search}%")
								->orWhere('m.member_number', 'LIKE',"%{$search}%")
								->orWhere('i.resignedmembericno', 'LIKE',"%{$search}%");
						});
			
			if( $limit != -1){
				$irclist =  $irclist->offset($start)
									->limit($limit);
			}
			//$irc->resignedmemberno
			$irclist =  $irclist->orderBy($order,$dir)
							->get()->toArray(); 
		
			$totalFiltered =$commonselect->where(function($query) use ($search){
									$query->orWhere('i.id','LIKE',"%{$search}%")
										->orWhere('i.resignedmembername', 'LIKE',"%{$search}%")
										->orWhere('m.member_number', 'LIKE',"%{$search}%")
										->orWhere('i.resignedmembericno', 'LIKE',"%{$search}%");
								})->count();
        }
		$data = array();
        if(!empty($irclist))
        {
            foreach ($irclist as $irc)
            {
				if($user_role!='irc-branch-committee' && $user_role!='irc-branch-committee-officials'){
					$check_count = DB::table('irc_confirmation as irc')
								->where('irc.irc_status','=',1)
							  //->where('irc.status','=','0')
							  ->where('irc.id','=',$irc->id)
							  ->count();
					if($check_count>0){
						$nestedData['status'] = 'Confirm';
					}else{
						$nestedData['status'] = 'Pending';
					}
					//dd($check_count);
				}else{
					$nestedData['status'] = $irc->status_name;
				}

				if($irc->new_ic!=''){
					$icno = $irc->new_ic;
				}else if($irc->old_ic!=''){
					$icno = $irc->old_ic;
				}else{
					$icno = $irc->employee_id;
				}
				
                $nestedData['resignedmemberno'] = $irc->resignedmemberno;
                $nestedData['resignedmembername'] = $irc->resignedmembername;
                $nestedData['resignedmembericno'] = $icno;
                $nestedData['resignedmemberbankname'] = $irc->resignedmemberbankname;
                $nestedData['resignedmemberbranchname'] = $irc->resignedmemberbranchname;
                $nestedData['received'] = $irc->received=='0000-00-00' ? '' : $irc->received;
                $company_enc_id = Crypt::encrypt($irc->id);
                $editurl =  route('edit.irc', [app()->getLocale(),$company_enc_id]) ;
                $nestedData['options'] = "";
				//$editurl = URL::to('/')."/en/sub-company-members/".$company_enc_id;
				if($irc->status_id!=4){
					if($user_role=='irc-confirmation' || $user_role=='irc-confirmation-officials'){
						if($irc->irc_status!=1){
							$nestedData['options'] = "<a style='float: left;' class='btn btn-sm waves-effect waves-light cyan modal-trigger' href='".$editurl."'><i class='material-icons'>edit</i></a>";
						}else{
							$nestedData['options'] = "<a style='float: left;' disabled class='btn btn-sm waves-effect waves-light cyan modal-trigger' ><i class='material-icons'>edit</i></a>";
						}
					}
					if($user_role=='irc-branch-committee' || $user_role=='irc-branch-committee-officials'){
						if($irc->status!=1){
							$nestedData['options'] = "<a style='float: left;' class='btn btn-sm waves-effect waves-light cyan modal-trigger' href='".$editurl."'><i class='material-icons'>edit</i></a>";
						}else{
							$nestedData['options'] = "<a style='float: left;' disabled class='btn btn-sm waves-effect waves-light cyan modal-trigger' ><i class='material-icons'>edit</i></a>";
						}
					}
					
				}else{
					$nestedData['options'] = "";
				}
				$data[] = $nestedData;
			}
        }
    
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );

        echo json_encode($json_data); 
	}
	public function editIrc(Request $request,$lang,$enc_id){
		$id = Crypt::decrypt($enc_id);
		
		$data['resignedmember'] = DB::table('irc_confirmation as irc')->select('irc.id as ircid','irc.resignedmemberno','irc.resignedmembername','irc.resignedmembericno','irc.resignedmemberbankname','irc.resignedmemberbranchname','irc.ircname','irc.ircposition','irc.ircbank','irc.ircbankaddress','irc.irctelephoneno','irc.ircmobileno','irc.ircfaxno','irc.gradewef','irc.nameofperson',
									'irc.waspromoted','irc.beforepromotion','irc.attached','irc.herebyconfirm','irc.filledby','irc.nameforfilledby','irc.remarks','irc.status',DB::raw("DATE_FORMAT(irc.submitted_at,'%d/%m/%Y') as submitted_at"),DB::raw("DATE_FORMAT(irc.gradewef,'%d/%m/%Y') as gradewef"),
									'm.member_number','d.designation_name','p.person_title',DB::raw("DATE_FORMAT(m.dob,'%d/%m/%Y') as dob"),DB::raw("(PERIOD_DIFF( DATE_FORMAT(CURDATE(), '%Y%m') , DATE_FORMAT(m.dob, '%Y%m') )) DIV 12 AS age"),'m.gender',DB::raw("DATE_FORMAT(m.doj,'%d/%m/%Y') as doj"),'r.race_name','irc.ircmembershipno','reas.id as reasonid','irc.branchcommitteeverification1','irc.branchcommitteeverification2','irc.branchcommitteeName','irc.branchcommitteeZone',DB::raw("DATE_FORMAT(irc.branchcommitteedate,'%d/%m/%Y') as branchcommitteedate"),'irc.comments','cb.union_branch_id','irc.posfilledbytype','irc.posfilledmemberid','irc.messengertype','irc.attachment_file','irc.attachfourtype','irc.committiecode','irc.replacestafftype','irc.samebranchtype','irc.expelledtypefive','irc.stoppedtypefive','irc.resigntypefour')
									->leftjoin('membership as m','irc.resignedmemberno','=','m.id')
									->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
									->leftjoin('designation as d','m.designation_id','=','d.id')
									->leftjoin('persontitle as p','m.member_title_id','=','p.id')
									->leftjoin('race as r','m.race_id','=','r.id')
									->leftjoin('reason as reas','irc.resignedreason','=','reas.id')
									->where('irc.id','=',$id)
									->first();
		
		$data['irc_details'] = DB::table('irc_confirmation as irc')->select('*')
									->where('irc.id','=',$id)
									->first();
		
		$data['reason_view'] = Reason::where('status','=','1')->get();


		return view('irc.edit_irc')->with('data',$data);
	}

	public function saveIrc(Request $request)
	{
		//return $request->all();
		$data = $request->all();
		$resigned_member = $request->input('resignedmemberno');
		$ircmembershipno = $request->input('ircmembershipno');
		$section_type = $request->input('section_type');

		$gradewef = '';
		$messengerbox = 0;
		$jobtakenbox = 0;
		$jobtakenby = '';
		$posfilledbybox = 0;
		$posfilledby = '';
		$replacestaffbox = 0;
		$replacestaff = '';
		$appcontactbox = 0;
		$appcontact = '';
		$appoffice = '';
		$appmobile = '';
		$appfax = '';
		$appemail = '';
		$samebranchbox = 0;

		$attachedbox = 0;
		$attached = '';

		$posfilledbytype = '';
		$posfilledmemberid = '';
		$messengertype = '';
		$attachment_file = '';
		$replacestafftype = '';

		$insertdata = ['resignedmemberno' => $data['resignedmemberno'],'resignedmembername' => $data['resignedmembername'],'resignedmembericno' => $data['resignedmembericno'],'resignedmemberbankname' => $data['resignedmemberbankname'],'resignedmemberbranchname' => $data['resignedmemberbranchname'],'resignedreason' => $data['resignedreason'],'ircmembershipno' => $data['ircmembershipno'],'ircname' => $data['ircname'],'ircposition' => $data['ircposition'],
		'ircbank' => $data['ircbank'],'ircbankaddress' => $data['ircbankaddress'],'irctelephoneno' => $data['irctelephoneno'],'ircmobileno' => $data['ircmobileno'],'ircfaxno' => $data['ircfaxno']];
		$ircstatus = 0;

		if($section_type==1){
			$personnameboxone = $request->input('personnameboxone');
			$retiredboxone = $request->input('retiredboxone');
			$messengerboxone = $request->input('messengerboxone');
			$attachedboxone = $request->input('attachedboxone');
			$jobtakenboxone = $request->input('jobtakenboxone');
			$posfilledbyboxone = $request->input('posfilledbyboxone');
			$replacestaffboxone = $request->input('replacestaffboxone');
			$appcontactboxone = $request->input('appcontactboxone');

			if(isset($messengerboxone)){
				$messengerbox = 1;
			}
			if(isset($jobtakenboxone)){
				$jobtakenbox = 1;
			}
			if(isset($posfilledbyboxone)){
				$posfilledbybox = 1;
			}
			if(isset($replacestaffboxone)){
				$replacestaffbox = 1;
			}
			if(isset($appcontactboxone)){
				$appcontactbox = 1;
			}
			if(isset($attachedboxone)){
				$attachedbox = 1;
			}

			$person_nameone = $request->input('person_nameone');
			$gradewef = $request->input('gradewefone');
			$attached = $request->input('attachedone');
			$jobtakenby = $request->input('jobtakenbyone');
			$posfilledby = $request->input('posfilledbyone');
			$replacestaff = $request->input('replacestaffone');
			$appcontact = $request->input('appcontactone');
			$appoffice = $request->input('appofficeone');
			$appmobile = $request->input('apphpone');
			$appfax = $request->input('appfaxone');
			$appemail = $request->input('appemailone');

			$posfilledbytype = $request->input('posfilledbytypeone');
			$posfilledmemberid = $request->input('posfilledbymemberidone');
			$messengertype = $request->input('messengerone');
			$replacestafftype = $request->input('replacestafftypeone');
			$attachment_file = 'attachmentone';

			$insertdata['nameofperson'] = isset($personnameboxone) ? 1 : 0;
			$insertdata['retiredbox'] = isset($retiredboxone) ? 1 : 0;

			$insertdata['posfilledbytype'] = $posfilledbytype;
			$insertdata['posfilledmemberid'] = $posfilledmemberid;
			$insertdata['messengertype'] = $messengertype;
			$insertdata['replacestafftype'] = $replacestafftype;

			if($messengerbox == 1 && $jobtakenbox == 1 && $posfilledbybox == 1 && $replacestaffbox == 1 && $appcontactbox == 1 && $attachedbox == 1 && $insertdata['nameofperson'] == 1 && $insertdata['retiredbox'] == 1){
				$ircstatus = 1;
			}
			
		}else if($section_type==2){
			$memberdemisedboxtwo = $request->input('memberdemisedboxtwo');
			$nameofpersonboxtwo = $request->input('nameofpersonboxtwo');
			$relationshipboxtwo = $request->input('relationshipboxtwo');
			$applicantboxtwo = $request->input('applicantboxtwo');
			$jobtakenboxtwo = $request->input('jobtakenboxtwo');
			$posfilledbyboxtwo = $request->input('posfilledbyboxtwo');
			$replacestaffboxtwo = $request->input('replacestaffboxtwo');
			$appcontactboxtwo = $request->input('appcontactboxtwo');

			if(isset($jobtakenboxtwo)){
				$jobtakenbox = 1;
			}
			if(isset($posfilledbyboxtwo)){
				$posfilledbybox = 1;
			}
			if(isset($replacestaffboxtwo)){
				$replacestaffbox = 1;
			}
			if(isset($appcontactboxtwo)){
				$appcontactbox = 1;
			}

			$memberdemisedtwo = $request->input('memberdemisedtwo');
			$nameofpersontwo = $request->input('nameofpersontwo');
			$relationshiptwo = $request->input('relationshiptwo');
			$jobtakenby = $request->input('jobtakenbytwo');
			$posfilledby = $request->input('posfilledbytwo');
			$replacestaff = $request->input('replacestafftwo');
			$appcontact = $request->input('appcontacttwo');
			$appoffice = $request->input('appofficetwo');
			$appmobile = $request->input('appmobiletwo');
			$appfax = $request->input('appfaxtwo');
			$appemail = $request->input('appemailtwo');
			$applicanttwo = $request->input('applicanttwo');

			$posfilledbytype = $request->input('posfilledbytypetwo');
			$posfilledmemberid = $request->input('posfilledbymemberidtwo');
			$replacestafftype = $request->input('replacestafftypetwo');

			$insertdata['demised_onboxtwo'] = isset($memberdemisedboxtwo) ? 1 : 0;
			$insertdata['demised_ontwo'] = $memberdemisedtwo;
			$insertdata['member_nameboxtwo'] = isset($memberdemisedboxtwo) ? 1 : 0;
			$insertdata['member_nametwo'] = $nameofpersontwo;
			$insertdata['relationshipboxtwo'] = isset($relationshipboxtwo) ? 1 : 0;
			$insertdata['relationshiptwo'] = $relationshiptwo;
			$insertdata['applicantboxtwo'] = isset($applicantboxtwo) ? 1 : 0;
			$insertdata['nameofperson'] = isset($nameofpersonboxtwo) ? 1 : 0;
			$insertdata['applicanttwo'] = $applicanttwo;

			$insertdata['posfilledbytype'] = $posfilledbytype;
			$insertdata['posfilledmemberid'] = $posfilledmemberid;
			$insertdata['replacestafftype'] = $replacestafftype;

			if($jobtakenbox == 1 && $posfilledbybox == 1 && $replacestaffbox == 1 && $appcontactbox == 1 && $insertdata['demised_onboxtwo'] == 1 && $insertdata['member_nameboxtwo'] == 1 && $insertdata['relationshipboxtwo'] == 1 && $insertdata['applicantboxtwo'] == 1 && $insertdata['nameofperson'] == 1){
				$ircstatus = 1;
			}

		}else if($section_type==3){
			$nameofpersonboxthree = $request->input('nameofpersonboxthree');
			$messengerboxthree = $request->input('messengerboxthree');
			$promotedboxthree = $request->input('promotedboxthree');
			$attachedboxthree = $request->input('attachedboxthree');
			$transfertoplaceboxthree = $request->input('transfertoplaceboxthree');
			$samebranchboxthree = $request->input('samebranchboxthree');
			$jobtakenboxthree = $request->input('jobtakenboxthree');
			$posfilledbyboxthree = $request->input('posfilledbyboxthree');
			$replacestaffboxthree = $request->input('replacestaffboxthree');
			$appcontactboxthree = $request->input('appcontactboxthree');
			$samebranchothers = $request->input('samebranchothers');

			if(isset($messengerboxthree)){
				$messengerbox = 1;
			}
			if(isset($jobtakenboxthree)){
				$jobtakenbox = 1;
			}
			if(isset($posfilledbyboxthree)){
				$posfilledbybox = 1;
			}
			if(isset($replacestaffboxthree)){
				$replacestaffbox = 1;
			}
			if(isset($appcontactboxthree)){
				$appcontactbox = 1;
			}
			if(isset($samebranchboxthree)){
				$samebranchbox = 1;
			}
			if(isset($attachedboxthree)){
				$attachedbox = 1;
			}

			$person_namethree = $request->input('person_namethree');
			$promotedthree = $request->input('promotedthree');
			$gradewef = $request->input('gradewefthree');
			$attached = $request->input('attachedthree');
			$transfertoplacethree = $request->input('transfertoplacethree');
			$jobtakenby = $request->input('jobtakenbythree');
			$posfilledby = $request->input('posfilledbythree');
			$replacestaff = $request->input('replacestaffthree');
			$appcontact = $request->input('appcontactthree');
			$appoffice = $request->input('appofficethree');
			$appmobile = $request->input('apphpthree');
			$appfax = $request->input('appfaxthree');
			$appemail = $request->input('appemailthree');

			$posfilledbytype = $request->input('posfilledbytypethree');
			$posfilledmemberid = $request->input('posfilledbymemberidthree');
			$messengertype = $request->input('messengerthree');
			$replacestafftype = $request->input('replacestafftypethree');
			$attachment_file = 'attachmentthree';

			$samebranchtype = $request->input('samebranchtype');

			$insertdata['promotedboxthree'] = isset($promotedboxthree) ? 1 : 0;
			$insertdata['promotedto'] = $promotedthree;
			$insertdata['transfertoplaceboxthree'] = isset($transfertoplaceboxthree) ? 1 : 0;
			$insertdata['transfertoplacethree'] = $transfertoplacethree;
			$insertdata['nameofperson'] = isset($nameofpersonboxthree) ? 1 : 0;

			$insertdata['posfilledbytype'] = $posfilledbytype;
			$insertdata['posfilledmemberid'] = $posfilledmemberid;
			$insertdata['messengertype'] = $messengertype;
			$insertdata['replacestafftype'] = $replacestafftype;
			$insertdata['samebranchtype'] = $samebranchtype;
			$insertdata['samebranchothers'] = $samebranchothers;

			if($messengerbox == 1 && $jobtakenbox == 1 && $posfilledbybox == 1 && $replacestaffbox == 1 && $appcontactbox == 1 && $samebranchbox == 1 && $attachedbox == 1 && $insertdata['promotedboxthree'] == 1 && $insertdata['transfertoplaceboxthree'] == 1 && $insertdata['nameofperson'] == 1){
				$ircstatus = 1;
			}

		}else if($section_type==4){
			$personnameboxfour = $request->input('personnameboxfour');
			$resignedonboxfour = $request->input('resignedonboxfour');
			$messengerboxfour = $request->input('messengerboxfour');
			$attachedboxfour = $request->input('attachedboxfour');
			$jobtakenboxfour = $request->input('jobtakenboxfour');
			$posfilledbyboxfour = $request->input('posfilledbyboxfour');
			$replacestaffboxfour = $request->input('replacestaffboxfour');
			$appcontactboxfour = $request->input('appcontactboxfour');

			if(isset($messengerboxfour)){
				$messengerbox = 1;
			}
			if(isset($jobtakenboxfour)){
				$jobtakenbox = 1;
			}
			if(isset($posfilledbyboxfour)){
				$posfilledbybox = 1;
			}
			if(isset($replacestaffboxfour)){
				$replacestaffbox = 1;
			}
			if(isset($appcontactboxfour)){
				$appcontactbox = 1;
			}
			if(isset($attachedboxfour)){
				$attachedbox = 1;
			}

			$person_namefour = $request->input('person_namefour');
			$gradewef = $request->input('gradeweffour');
			$attached = $request->input('attachedfour');
			$jobtakenby = $request->input('jobtakenbyfour');
			$posfilledby = $request->input('posfilledbyfour');
			$replacestaff = $request->input('replacestafffour');
			$appcontact = $request->input('appcontactfour');
			$appoffice = $request->input('appofficefour');
			$appmobile = $request->input('apphpfour');
			$appfax = $request->input('appfaxfour');
			$appemail = $request->input('appemailfour');

			$posfilledbytype = $request->input('posfilledbytypefour');
			$posfilledmemberid = $request->input('posfilledbymemberidfour');
			$messengertype = $request->input('messengerfour');
			$replacestafftype = $request->input('replacestafftypefour');
			$attachment_file = 'attachmentfour';
			$attachfourtype = $request->input('attachfourtype');
			$resigntypefour = $request->input('resigntypefour');

			$insertdata['resignedonboxfour'] = isset($resignedonboxfour) ? 1 : 0;
			$insertdata['nameofperson'] = isset($personnameboxfour) ? 1 : 0;

			$insertdata['posfilledbytype'] = $posfilledbytype;
			$insertdata['posfilledmemberid'] = $posfilledmemberid;
			$insertdata['messengertype'] = $messengertype;
			$insertdata['replacestafftype'] = $replacestafftype;
			$insertdata['attachfourtype'] = $attachfourtype;
			$insertdata['resigntypefour'] = $resigntypefour;

			if($messengerbox == 1 && $jobtakenbox == 1 && $posfilledbybox == 1 && $replacestaffbox == 1 && $appcontactbox == 1 && $attachedbox == 1 && $insertdata['resignedonboxfour'] == 1 && $insertdata['nameofperson'] == 1 ){
				$ircstatus = 1;
			}
		
		}else if($section_type==5){
			$expelledboxfive = $request->input('expelledboxfive');
			$samejobboxfive = $request->input('samejobboxfive');
			$samebranchbox = $request->input('samebranchboxfive');
			$memberstoppedboxfive = $request->input('memberstoppedboxfive');
			if(isset($samebranchbox)){
				$samebranchbox = 1;
			}

			$gradewef = $request->input('gradeweffive');

			$expelledtypefive = $request->input('expelledtypefive');
			$stoppedtypefive = $request->input('stoppedtypefive');
			$samebranchtype = $request->input('samebranchtypefive');

			$insertdata['samebranchtype'] = $samebranchtype;
			$insertdata['expelledtypefive'] = $expelledtypefive;
			$insertdata['stoppedtypefive'] = $stoppedtypefive;

			//$committiename = $request->input('committiename');
			//$attachedfour = $request->input('committieverifyname');
			$insertdata['expelledboxfive'] = isset($expelledboxfive) ? 1 : 0;
			$insertdata['samejobboxfive'] = isset($samejobboxfive) ? 1 : 0;
			$insertdata['memberstoppedboxfive'] = isset($memberstoppedboxfive) ? 1 : 0;

			if($samebranchbox == 1 && $insertdata['expelledboxfive'] == 1 && $insertdata['samejobboxfive'] == 1 && $insertdata['memberstoppedboxfive'] == 1 ){
				$ircstatus = 1;
			}
		}else{

		}
		$dobgradewef =  '';
		if($gradewef!='')
		{
			$fmmm_date = explode("/",$gradewef);           							
			$gradewef = $fmmm_date[2]."-".$fmmm_date[1]."-".$fmmm_date[0];
			$grade = date('Y-m-d', strtotime($gradewef));
			$dobgradewef =  $grade;
		}
		$insertdata['attachedbox'] = $attachedbox;
		$insertdata['gradewef'] = $dobgradewef;
		$insertdata['messengerbox'] = $messengerbox;
		$insertdata['jobtakenbox'] = $jobtakenbox;
		$insertdata['jobtakenby'] = $jobtakenby;
		$insertdata['posfilledbybox'] = $posfilledbybox;
		$insertdata['posfilledby'] = $posfilledby;
		$insertdata['replacestaffbox'] = $replacestaffbox;
		$insertdata['replacestaff'] = $replacestaff;
		$insertdata['appcontactbox'] = $appcontactbox;
		$insertdata['appcontact'] = $appcontact;
		$insertdata['appoffice'] = $appoffice;
		$insertdata['appmobile'] = $appmobile;
		$insertdata['appfax'] = $appfax;
		$insertdata['appemail'] = $appemail;
		$insertdata['samebranchbox'] = $samebranchbox;

		$insertdata['attachedbox'] = $attachedbox;
		$insertdata['attached_desc'] = $attached;
		$insertdata['irc_status'] = $ircstatus;

		if($attachment_file!=''){
			if(Input::hasFile($attachment_file)){
				$filenameWithExt = $request->file($attachment_file)->getClientOriginalExtension();
				$inputfilenames = strtotime(date('Ymdhis')).'.'.$filenameWithExt;
				$file = $request->file($attachment_file)->storeAs('irc', $inputfilenames ,'local');
				$insertdata['attachment_file'] = $inputfilenames;
				
			}
		}

		if(Input::hasFile('formupload')){
			$filenameWithExt = $request->file('formupload')->getClientOriginalExtension();
			$inputfilenames = $data['resignedmemberno'].'_'.strtotime(date('Ymdhis')).'.'.$filenameWithExt;
			$file = $request->file('formupload')->storeAs('irc', $inputfilenames ,'local');
			$insertdata['attachment_fullform'] = $inputfilenames;
			
		}
		
		$submitted_at = $request->input('submitted_at');
		$created_at = date('Y-m-d h:i:s');
		$insertdata['created_at'] = $created_at;
		if($submitted_at!='')
		{
			$fmmm_date = explode("/",$submitted_at);           							
			$submittedat = $fmmm_date[2]."-".$fmmm_date[1]."-".$fmmm_date[0];
			$submit = date('Y-m-d', strtotime($submittedat));
			//$data['submitted_at'] =  $submit;
			$insertdata['submitted_at'] = $submit;
		}
		
		$insertdata['status'] = 0;
		if(!empty(Auth::user())){
		
			$userid = Auth::user()->id;
			$get_roles = Auth::user()->roles;
			$user_role = $get_roles[0]->slug;
		}

		$defdaultLang = app()->getLocale();
		
		if(!empty($request->input('ircid')))
		{
			//  echo "<pre>"; 
			//  print_r($request->all());
			//  die;

			if($user_role=='irc-confirmation' || $user_role=='irc-confirmation-officials')
			{		
				$saveIrc = DB::table('irc_confirmation')
                ->where('id', $request->input('ircid'))
				->update($insertdata);
				
				//return $saveIrc;
				//$saveIrc = $this->Irc->saveIrcdata($data);
				
			}
			else if($user_role=='irc-branch-committee' || $user_role=='irc-branch-committee-officials')
			{
				$updatedata = [];
				$committieverificationboxone = $request->input('committieverificationboxone');
				$committieverificationboxtwo = $request->input('committieverificationboxtwo');
				$committieverificationboxthree = $request->input('committieverificationboxthree');
				$committiecode  = $request->input('committiecode');

				$committieremark = $request->input('committieremark');
				$branchcommitteeName = $request->input('branchcommitteeName');
				$branchcommitteeZone = $request->input('branchcommitteeZone');
				$branchcommitteedate = $request->input('branchcommitteedate');

				$updatedata['committieverificationboxone'] = isset($committieverificationboxone) ? 1 : 0;
				$updatedata['committieverificationboxtwo'] = isset($committieverificationboxtwo) ? 1 : 0;
				$updatedata['committieverificationboxthree'] = isset($committieverificationboxthree) ? 1 : 0;
				$updatedata['committieremark'] = $committieremark;
				$updatedata['committiename'] = $request->input('committiename');
				$updatedata['committieverifyname'] = $request->input('committieverifyname');
				$updatedata['committie_official_name'] = $request->input('committie_official_name');
				$updatedata['branchcommitteeName'] = $branchcommitteeName;
				$updatedata['branchcommitteeZone'] = $branchcommitteeZone;
				$updatedata['committiecode'] = $committiecode;
				
				if($branchcommitteedate!='')
				{
					$fmmm_date = explode("/",$branchcommitteedate);           							
					$branch = $fmmm_date[2]."-".$fmmm_date[1]."-".$fmmm_date[0];
					$branchdate = date('Y-m-d', strtotime($branch));
					$updatedata['branchcommitteedate'] =  $branchdate;
				}
				if($updatedata['committieverificationboxone']==1 && $updatedata['committieverificationboxtwo']==1 && $updatedata['committieverificationboxthree']==1){
					$updatedata['status'] = 1;
				}
				
				$saveIrc = DB::table('irc_confirmation')
                ->where('id', $request->input('ircid'))
				->update($updatedata);
				//return $request->input('ircid');
				//$saveIrc = $this->Irc->saveIrcdata($data);

			}
			$check_edit = DB::table('irc_confirmation as irc')
						  ->where('irc.nameofperson','=','1')
						  ->where('irc.waspromoted','=','1')
						  ->where('irc.beforepromotion','=','1')
						  ->where('irc.attached','=','1')
						  ->where('irc.herebyconfirm','=','1')
						  ->where('irc.filledby','=','1')
						  ->where('irc.branchcommitteeverification1','=','1')
						  ->where('irc.branchcommitteeverification2','=','1')
						  ->where('irc.id','=',$request->id)
						  ->update(['status'=>'1']);
		}
		else{
			if($user_role=='irc-confirmation'){
				if($ircmembershipno=='' || $resigned_member==''){
					return redirect(app()->getLocale().'/irc_list')->with('error', 'Please choose member');
				}
			}else{
				if($resigned_member==''){
					return redirect(app()->getLocale().'/irc_list')->with('error', 'Please choose member');
				}
			}
			
			$check_irc = DB::table('irc_confirmation as irc')
						->where('irc.resignedmemberno','=',$resigned_member)
						->count();
			//echo "<pre>"; 
			//print_r($request->all());
			//die;
			if($check_irc==0){
				$saveIrc = DB::table('irc_confirmation')->insert($insertdata);
				//$saveIrc = $this->Irc->saveIrcdata($data);
			}else{
				return redirect(app()->getLocale().'/irc_list')->with('error', 'Already data exists for this member');
			}
			
		}
		if ($saveIrc == true) {
			if(!empty($request->ircid))
			{
				return redirect(app()->getLocale().'/irc_list')->with('message', 'Irc Updated Succesfully');
			}
			else
			{
				return redirect(app()->getLocale().'/irc_list')->with('message', 'IRC Name Added Succesfully');
			}
		}else{
			return redirect(app()->getLocale().'/irc_list')->with('message', 'Irc Updated Succesfully');
		}
	}
	//getMembersList
	public function getMembersList(Request $request)
	{
		$userid = Auth::user()->id;
		$get_roles = Auth::user()->roles;
		$user_role = $get_roles[0]->slug;
		if($user_role == 'irc-confirmation-officials'){
			$searchkey = $request->input('searchkey');
			$search = $request->input('query');
			$ircsuggestions = DB::table('membership as m')->select(DB::raw('CONCAT(m.name, " - ", m.member_number) AS value'),'m.id as number','m.branch_id as branch_id','m.member_number','m.gender') ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')     
								->where(function($query) use ($search){
									$query->orWhere('m.member_number', 'LIKE',"{$search}%")
										->orWhere('m.name', 'LIKE',"{$search}%")
										->orWhere('m.old_ic', 'LIKE',"{$search}%")
										->orWhere('m.new_ic', 'LIKE',"{$search}%");
								})->limit(25)
								//->where('m.id','!=',$memberid)
								->where('status_id','!=',4);   
			// if($c_head!=1){
			// 	$ircsuggestions = $ircsuggestions->where('cb.union_branch_id','=',$unionbranchid);
			// }else{
			//$ircsuggestions = $ircsuggestions->whereIn('cb.union_branch_id',$unionbranchids);
			//}
			$res['suggestions'] = $ircsuggestions->get();
		}else{
			if($user_role == 'irc-confirmation'){
				$memberid = DB::table('irc_account as irc')->where('user_id','=',$userid)
				->pluck('MemberCode')->first();  
				$unionbranchid = DB::table('membership as m')
					->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
					->where('m.id','=',$memberid)->pluck('cb.union_branch_id')->first();  
			}else{
				$unionbranchid = DB::table('irc_account as irc')->where('user_id','=',$userid)
				->pluck('union_branch_id')->first();  
			}
	
			$unionbranchname = DB::table('union_branch as ub')
			->where('ub.id','=',$unionbranchid)
			->pluck('union_branch')->first();  
			//return $unionbranchname;
	
			$union_no = $unionbranchid;
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
			}else if($unionbranchname=='IPOH' || $unionbranchname=='PERAK'){
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
			//return $unionbranchids;
	
			$c_head = DB::table('union_branch as ub')->select('ub.id')
								->where('ub.id','=',$unionbranchid)
								->where('ub.is_head','=',1)
								//->dump()
								->count();
	
			$searchkey = $request->input('searchkey');
			$search = $request->input('query');
			$ircsuggestions = DB::table('membership as m')->select(DB::raw('CONCAT(m.name, " - ", m.member_number) AS value'),'m.id as number','m.branch_id as branch_id','m.member_number','m.gender') ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')     
								->where(function($query) use ($search){
									$query->orWhere('m.member_number', 'LIKE',"{$search}%")
										->orWhere('m.name', 'LIKE',"{$search}%")
										->orWhere('m.old_ic', 'LIKE',"{$search}%")
										->orWhere('m.new_ic', 'LIKE',"{$search}%");
								})->limit(25)
								->where('m.id','!=',$memberid)
								->where('status_id','!=',4);   
			// if($c_head!=1){
			// 	$ircsuggestions = $ircsuggestions->where('cb.union_branch_id','=',$unionbranchid);
			// }else{
			$ircsuggestions = $ircsuggestions->whereIn('cb.union_branch_id',$unionbranchids);
			$ircsuggestions = $ircsuggestions->where('m.send_irc_request',1);
			//}
			$res['suggestions'] = $ircsuggestions->get();
		}
		
         return response()->json($res);
	}

	 public function getAutomemberslist(Request $request){
        $searchkey = $request->input('serachkey');
        $search = $request->input('query');
        //DB::enableQueryLog();
        $res['suggestions'] = DB::table('membership as m')->select(DB::raw('CONCAT(m.name, " - ", m.member_number) AS value'),'m.id as number','m.branch_id as branch_id','m.member_number as member_code')      
                            ->where(function($query) use ($search){
                                $query->orWhere('m.id','LIKE',"{$search}%")
                                    ->orWhere('m.member_number', 'LIKE',"{$search}%")
                                    ->orWhere('m.name', 'LIKE',"{$search}%");
							})
							->where('m.status_id','!=',4)
							->limit(25)
                            ->get();        
        //$queries = DB::getQueryLog();
                            //  dd($queries);
         return response()->json($res);
	}
	
	public function userDetail(Request $request)
    {
        $id = $request->id;
       // $User = new User();
		$data = User::find($id);

		$irc_account = DB::table('irc_account')
		->where('user_id','=',$data->id)
		->first();  

		if($irc_account->account_type=='irc-confirmation'){
			$unionbranchid = '';
			$memberid = $irc_account->MemberCode;

			$membership = DB::table('membership')
			->where('id','=',$memberid)
			->first();  
			$unionbranch = '';

		}else{
			$memberid = '';
			$membership = [];
			$unionbranchid = $irc_account->union_branch_id;

			if($unionbranchid==1){
				$unionbranch = 'SMJ';
			}else if($unionbranchid==2){
				$unionbranch = 'PKP';
			}else if($unionbranchid==3){
				$unionbranch = 'PERAK';
			}else if($unionbranchid==4){
				$unionbranch = 'KELANTAN TERENGGANU';
			}else{
				$unionbranch = 'KLSP';
			}
			// $unionbranch = DB::table('union_branch')
			// ->where('id','=',$unionbranchid)
			// ->first();  

		}
		
		$userdata['name'] = $data->name;
		$userdata['email'] = $data->email;
		$userdata['id'] = $data->id;
		$userdata['irc_account'] = $irc_account;
		$userdata['irc_type'] = $irc_account->account_type;
		$userdata['membership'] = $membership;
		$userdata['unionbranch'] = $unionbranch;

        return $userdata;
	}
	
	public function getMembersListValues(Request $request)
	{
		$member_id = $request->member_id;
		
		//DB::connection()->enableQueryLog();
		
		$res = DB::table('membership as m')->select(DB::raw("if(count('m.new_ic') > 0  ,m.new_ic,m.old_ic) as nric"),'m.member_number','m.id as memberid','d.designation_name as membertype','p.person_title as persontitle','m.name as membername','cb.branch_name','c.company_name',DB::raw("DATE_FORMAT(m.dob,'%d/%b/%Y') as dob"),'m.gender',DB::raw("DATE_FORMAT(m.doj,'%d/%b/%Y') as doj"),DB::raw("(PERIOD_DIFF( DATE_FORMAT(CURDATE(), '%Y%m') , DATE_FORMAT(dob, '%Y%m') )) DIV 12 AS age"),'r.race_name','cb.address_one','cb.phone','cb.mobile','cb.union_branch_id')
							->leftjoin('designation as d','d.id','=','m.designation_id')
							->leftjoin('persontitle as p','p.id','=','m.member_title_id')
							->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
							->leftjoin('company as c','c.id','=','cb.company_id')
							->leftjoin('race as r','r.id','=','m.race_id')
							->where('m.member_number','=',$member_id)
							->first();
		
		$irc_count = DB::table('irc_confirmation as irc')
		->where('irc.resignedmemberno','=',$res->memberid)
		->count();
		

		if($irc_count==0){
			return response()->json($res);
		}
		return 1;

		// $queries = DB::getQueryLog();
		// dd($queries);
		
	}

	public function IrcWaiters(Request $request,$lang){
		return view('irc.waiters_list');
	}

	public function ajax_irc_waiters_list(Request $request){
		$user_id = Auth::user()->id;
		$get_roles = Auth::user()->roles;
		$user_role = $get_roles[0]->slug;

		$columns = array(
            0 => 'm.member_number',
            1 => 'm.name',
            2 => 'm.new_ic',
            3 => 'c.company_name',
            4 => 'cb.branch_name',
            5 => 'm.id',
        );

		$unionbranchids = [];
        if($user_role=='irc-confirmation'){
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
			}else if($unionbranchname=='IPOH' || $unionbranchname=='PERAK'){
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
        }

		$totalDataqry = DB::table('membership as m')
					 ->leftjoin('company_branch as cb', 'm.branch_id', '=', 'cb.id')
					 ->leftjoin('irc_confirmation as irc', 'm.id', '=', 'irc.resignedmemberno');
			if($user_role=='irc-confirmation'){
				$totalDataqry = $totalDataqry->whereIn('cb.union_branch_id',$unionbranchids);
			}
			$totalData = $totalDataqry->where('m.send_irc_request','=',1)->whereNull('irc.resignedmemberno')
					 ->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');

        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            if( $limit == -1){
				$usersqry =  DB::table('membership as m')->select('m.id','m.name','m.member_number','c.company_name','cb.branch_name','m.new_ic as icno')
							->leftjoin('company_branch as cb', 'm.branch_id', '=', 'cb.id')
							->leftjoin('company as c', 'c.id', '=', 'cb.company_id')
							->leftjoin('irc_confirmation as irc', 'm.id', '=', 'irc.resignedmemberno');
				if($user_role=='irc-confirmation'){
					$usersqry = $usersqry->whereIn('cb.union_branch_id',$unionbranchids);
				}
				$users = $usersqry->where('m.send_irc_request','=',1)->whereNull('irc.resignedmemberno')
							->orderBy($order,$dir)
							->get()->toArray();
            }else{
				$usersqry =  DB::table('membership as m')->select('m.id','m.name','m.member_number','c.company_name','cb.branch_name','m.new_ic as icno')
							->leftjoin('company_branch as cb', 'm.branch_id', '=', 'cb.id')
							->leftjoin('company as c', 'c.id', '=', 'cb.company_id')
							->leftjoin('irc_confirmation as irc', 'm.id', '=', 'irc.resignedmemberno');
				if($user_role=='irc-confirmation'){
					$usersqry = $usersqry->whereIn('cb.union_branch_id',$unionbranchids);
				}
				$users = $usersqry->where('m.send_irc_request','=',1)->whereNull('irc.resignedmemberno')
							->offset($start)
							->limit($limit)
							->orderBy($order,$dir)
							->get()->toArray();
            }
        
        }
        else {
        $search = $request->input('search.value'); 
        if( $limit == -1){
            $usersqry =  DB::table('membership as m')->select('m.id','m.name','m.member_number','c.company_name','cb.branch_name','m.new_ic as icno')
							->leftjoin('company_branch as cb', 'm.branch_id', '=', 'cb.id')
							->leftjoin('company as c', 'c.id', '=', 'cb.company_id')
							->leftjoin('irc_confirmation as irc', 'm.id', '=', 'irc.resignedmemberno');
				if($user_role=='irc-confirmation'){
					$usersqry = $usersqry->whereIn('cb.union_branch_id',$unionbranchids);
				}
				$users = $usersqry->where('m.send_irc_request','=',1)->whereNull('irc.resignedmemberno')
							->where('m.id','LIKE',"%{$search}%")
	                        ->orWhere('m.name', 'LIKE',"%{$search}%")
	                        ->orWhere('m.email', 'LIKE',"%{$search}%")
	                        ->orderBy($order,$dir)
	                        ->get()->toArray();
        }else{
            $usersqry =  DB::table('membership as m')->select('m.id','m.name','m.member_number','c.company_name','cb.branch_name','m.new_ic as icno')
							->leftjoin('company_branch as cb', 'm.branch_id', '=', 'cb.id')
							->leftjoin('company as c', 'c.id', '=', 'cb.company_id')
							->leftjoin('irc_confirmation as irc', 'm.id', '=', 'irc.resignedmemberno');
				if($user_role=='irc-confirmation'){
					$usersqry = $usersqry->whereIn('cb.union_branch_id',$unionbranchids);
				}
				$users = $usersqry->where('m.send_irc_request','=',1)->whereNull('irc.resignedmemberno')
							->where('m.id','LIKE',"%{$search}%")
	                        ->orWhere('m.name', 'LIKE',"%{$search}%")
	                        ->orWhere('m.email', 'LIKE',"%{$search}%")
	                        ->offset($start)
	                        ->limit($limit)
	                        ->orderBy($order,$dir)
	                        ->get()->toArray();
        }
        	$totalFilterqry =  DB::table('membership as m')->select('m.id','m.name','m.member_number','c.company_name','cb.branch_name','m.new_ic as icno')
							->leftjoin('company_branch as cb', 'm.branch_id', '=', 'cb.id')
							->leftjoin('company as c', 'c.id', '=', 'cb.company_id')
							->leftjoin('irc_confirmation as irc', 'm.id', '=', 'irc.resignedmemberno');
				if($user_role=='irc-confirmation'){
					$totalFilterqry = $totalFilterqry->whereIn('cb.union_branch_id',$unionbranchids);
				}
				$totalFiltered = $totalFilterqry->where('m.send_irc_request','=',1)->whereNull('irc.resignedmemberno')->where('m.id','LIKE',"%{$search}%")
	                        ->orWhere('m.name', 'LIKE',"%{$search}%")
	                        ->orWhere('m.email', 'LIKE',"%{$search}%")
                   			 ->count();
        }
        $data = array();
        if(!empty($users))
        {
            foreach ($users as $irc)
            {
				
                $nestedData['member_number'] = $irc->member_number;
                $nestedData['name'] = $irc->name;
                $nestedData['icno'] = $irc->icno;
                $nestedData['company_name'] = $irc->company_name;
                $nestedData['branch_name'] = $irc->branch_name;

                $member_enc_id = Crypt::encrypt($irc->id);
                $baseurl = URL::to('/');
				$membershiplink =  $baseurl.'/'.app()->getLocale().'/irc_irc?member='.$member_enc_id;                
                
				$nestedData['options'] = "<a style='float: left;' target='_blank' class='btn btn-sm waves-effect waves-light cyan modal-trigger' href='".$membershiplink."'>Create IRC</a>";

				$data[] = $nestedData;
			}
        }
        //$data = $this->CommonAjaxReturnold($users, 0, 'master.destroy', 0);
    
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );

        echo json_encode($json_data); 
	}

	public function TestIRC(){
		return view('emails.irc');
	}
}
