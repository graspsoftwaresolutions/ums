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
	
	public function ircIndex()
    {
		$data['reason_view'] = Reason::where('status','=','1')->get();
		return view('irc.irc')->with('data',$data);
	}
	
	public function ListIrcAccount() {
		
		return view('irc.users');
    }
	
	public function AddIrcAccount() {
		$data['union_view'] = DB::table('union_branch')->where('status','=','1')->get();
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
				['MemberCode' => $member_code,'union_branch_id' => $union_branch_id, 'user_id' => $member_user->id,'account_type' => $account_type, 'created_by' => Auth::user()->id, 'created_at' => date('Y-m-d')]
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
							->orderBy($order,$dir)
							->get()->toArray();
            }else{
				$users = DB::table('irc_account as i')->select('u.id','u.name','u.email','i.account_type','m.member_number as MemberCode')
						->leftjoin('users as u', 'i.user_id', '=', 'u.id')
						->leftjoin('membership as m', 'm.id', '=', 'i.MemberCode')
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
		$membercode = DB::table('irc_account as irc')->where('user_id','=',$userid)->pluck('MemberCode')->first();
		$c_head = DB::table('membership as m')->select('m.id')
							->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
							->leftjoin('union_branch as u','cb.union_branch_id','=','u.id')
							->where('m.id','=',$membercode)
							->where('u.is_head','=',1)
							//->dump()
							->count();
		//dd(2);
		$searchkey = $request->input('searchkey');
		$search = $request->input('query');
		$union_branch_id = $request->input('union_branch_id');
		$ircsuggestions = DB::table('irc_account as irc')->select(DB::raw('CONCAT(m.name, " - ", m.member_number) AS value'),'m.id as number','m.branch_id as branch_id','m.member_number','irc.MemberCode')
							->leftjoin('membership as m','irc.MemberCode','=','m.id')
							->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
							->where('irc.account_type','=','irc-confirmation')
							->where(function($query) use ($search){
                                $query->orWhere('m.member_number', '=',"{$search}")
									->orWhere('m.name', 'LIKE',"%{$search}%")
									->orWhere('m.new_ic', '=',"{$search}")
									->orWhere('m.old_ic', '=',"{$search}")
									->orWhere('irc.MemberCode', '=',"{$search}");
							})
							->where('m.status_id','!=',4);
		if($c_head!=1){
			$ircsuggestions = $ircsuggestions->where('cb.union_branch_id','=',$union_branch_id);
		}					
	
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
					 ->leftjoin('membership as m', 'i.resignedmemberno', '=', 'm.id');
		if($user_role=='irc-branch-committee'){
			if($statusfilter!=''){
				if($statusfilter==0){
					  $totalqry = $totalqry->where('i.nameofperson','=','1')
					  ->where('i.waspromoted','=','1')
					  ->where('i.beforepromotion','=','1')
					  ->where('i.attached','=','1')
					  ->where('i.herebyconfirm','=','1')
					  ->where('i.filledby','=','1')
					  ->where('i.status','=',$statusfilter);
				}else{
					$totalqry = $totalqry->where('i.status','=',$statusfilter);
				}
			}else{
				 $totalqry = $totalqry->where('i.nameofperson','=','1')
					  ->where('i.waspromoted','=','1')
					  ->where('i.beforepromotion','=','1')
					  ->where('i.attached','=','1')
					  ->where('i.herebyconfirm','=','1')
					  ->where('i.filledby','=','1');
			}
		}else{
			if($statusfilter!=''){
				if($statusfilter==0){
					   $totalqry = $totalqry->where('i.status','=',0)
							->where(function($query) use ($statusfilter){
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
								});
				}else{
					 $totalqry = $totalqry->where('i.nameofperson','=','1')
					  ->where('i.waspromoted','=','1')
					  ->where('i.beforepromotion','=','1')
					  ->where('i.attached','=','1')
					  ->where('i.herebyconfirm','=','1')
					  ->where('i.filledby','=','1');
					  //->where('i.status','=','0');
				}
			}else{
				 //$totalqry = $totalqry->where('i.status','=','0');
			}
		}
		
		
		$commonselect = DB::table('irc_confirmation as i')
						->select(DB::raw('if(i.status=1,"Confirm","pending") as status_name'),'i.status','m.member_number as resignedmemberno','m.name as resignedmembername','i.resignedmembericno','i.resignedmemberbankname','i.resignedmemberbranchname','i.submitted_at as submitted_at','i.submitted_at as received','i.id','m.status_id as status_id')
						->leftjoin('membership as m', 'i.resignedmemberno', '=', 'm.id');
		if($user_role=='irc-branch-committee'){
			if($statusfilter!=''){
				if($statusfilter==0){
					  $commonselect = $commonselect->where('i.nameofperson','=','1')
					  ->where('i.waspromoted','=','1')
					  ->where('i.beforepromotion','=','1')
					  ->where('i.attached','=','1')
					  ->where('i.herebyconfirm','=','1')
					  ->where('i.filledby','=','1')
					  ->where('i.status','=',$statusfilter);
				}else{
					$commonselect = $commonselect->where('i.status','=',$statusfilter);
				}
			}else{
				 $commonselect = $commonselect->where('i.nameofperson','=','1')
					  ->where('i.waspromoted','=','1')
					  ->where('i.beforepromotion','=','1')
					  ->where('i.attached','=','1')
					  ->where('i.herebyconfirm','=','1')
					  ->where('i.filledby','=','1');
			}
		}else{
			if($statusfilter!=''){
				if($statusfilter==0){
					  $commonselect = $commonselect->where('i.status','=',0)
								->where(function($query) use ($statusfilter){
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
									});
					
				}else{
					 $commonselect = $commonselect->where('i.nameofperson','=','1')
					  ->where('i.waspromoted','=','1')
					  ->where('i.beforepromotion','=','1')
					  ->where('i.attached','=','1')
					  ->where('i.herebyconfirm','=','1')
					  ->where('i.filledby','=','1');
					  //->where('i.status','=','0');
				}
			}else{
				 //$commonselect = $commonselect->where('i.status','=','0');
			}
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
				if($user_role!='irc-branch-committee'){
					$check_count = DB::table('irc_confirmation as irc')
							  ->where('irc.nameofperson','=','1')
							  ->where('irc.waspromoted','=','1')
							  ->where('irc.beforepromotion','=','1')
							  ->where('irc.attached','=','1')
							  ->where('irc.herebyconfirm','=','1')
							  ->where('irc.filledby','=','1')
							  //->where('irc.status','=','0')
							  ->where('irc.id','=',$irc->id)
							  ->count();
					if($check_count>0){
						$nestedData['status'] = 'Confirm';
					}else{
						$nestedData['status'] = 'Pending';
					}
					
				}else{
					$nestedData['status'] = $irc->status_name;
				}
                $nestedData['resignedmemberno'] = $irc->resignedmemberno;
                $nestedData['resignedmembername'] = $irc->resignedmembername;
                $nestedData['resignedmembericno'] = $irc->resignedmembericno;
                $nestedData['resignedmemberbankname'] = $irc->resignedmemberbankname;
                $nestedData['resignedmemberbranchname'] = $irc->resignedmemberbranchname;
                $nestedData['received'] = $irc->received;
                $company_enc_id = Crypt::encrypt($irc->id);
                $editurl =  route('edit.irc', [app()->getLocale(),$company_enc_id]) ;
				//$editurl = URL::to('/')."/en/sub-company-members/".$company_enc_id;
				if($irc->status_id!=4){
					$nestedData['options'] = "<a style='float: left;' class='btn btn-small waves-effect waves-light cyan modal-trigger' href='".$editurl."'>Edit IRC</a>";
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
									'irc.waspromoted','irc.beforepromotion','irc.attached','irc.herebyconfirm','irc.filledby','irc.nameforfilledby','irc.remarks','irc.status',DB::raw("DATE_FORMAT(irc.submitted_at,'%d/%b/%Y') as submitted_at"),DB::raw("DATE_FORMAT(irc.gradewef,'%d/%b/%Y') as gradewef"),
									'm.member_number','d.designation_name','p.person_title',DB::raw("DATE_FORMAT(m.dob,'%d/%b/%Y') as dob"),DB::raw("(PERIOD_DIFF( DATE_FORMAT(CURDATE(), '%Y%m') , DATE_FORMAT(m.dob, '%Y%m') )) DIV 12 AS age"),'m.gender',DB::raw("DATE_FORMAT(m.doj,'%d/%b/%Y') as doj"),'r.race_name','irc.ircmembershipno','reas.id as reasonid','irc.branchcommitteeverification1','irc.branchcommitteeverification2','irc.branchcommitteeName','irc.branchcommitteeZone',DB::raw("DATE_FORMAT(irc.branchcommitteedate,'%d/%b/%Y') as branchcommitteedate"))
									->leftjoin('membership as m','irc.resignedmemberno','=','m.id')
									->leftjoin('designation as d','m.designation_id','=','d.id')
									->leftjoin('persontitle as p','m.member_title_id','=','p.id')
									->leftjoin('race as r','m.race_id','=','r.id')
									->leftjoin('reason as reas','irc.resignedreason','=','reas.id')
									->where('irc.id','=',$id)
									->first();
		
		$data['reason_view'] = Reason::where('status','=','1')->get();


		return view('irc.edit_irc')->with('data',$data);
	}

	public function saveIrc(Request $request)
	{
		$data = $request->all();
		$resigned_member = $request->input('resignedmemberno');
		$ircmembershipno = $request->input('ircmembershipno');
		
		if($data['gradewef'])
		{
			$fmmm_date = explode("/",$data['gradewef']);           							
			$gradewef = $fmmm_date[2]."-".$fmmm_date[1]."-".$fmmm_date[0];
			$grade = date('Y-m-d', strtotime($gradewef));
			$data['gradewef'] =  $grade;
		}

		if($data['submitted_at'])
		{
			$fmmm_date = explode("/",$data['submitted_at']);           							
			$submittedat = $fmmm_date[2]."-".$fmmm_date[1]."-".$fmmm_date[0];
			$submit = date('Y-m-d', strtotime($submittedat));
			$data['submitted_at'] =  $submit;
		}
		
		$data['status'] = 0;
		if(!empty(Auth::user())){
		
			$userid = Auth::user()->id;
			$get_roles = Auth::user()->roles;
			$user_role = $get_roles[0]->slug;
		}

		$defdaultLang = app()->getLocale();
		
		if(!empty($request->id))
		{
			// echo "<pre>"; 
			// print_r($request->all());
			// exit;

			if($user_role=='irc-confirmation')
			{		
				$saveIrc = $this->Irc->saveIrcdata($data);
				
			}
			else if($user_role=='irc-branch-committee')
			{
				if($data['branchcommitteedate'])
				{
					$fmmm_date = explode("/",$data['branchcommitteedate']);           							
					$branch = $fmmm_date[2]."-".$fmmm_date[1]."-".$fmmm_date[0];
					$branchdate = date('Y-m-d', strtotime($branch));
					$data['branchcommitteedate'] =  $branchdate;
				}
				$saveIrc = $this->Irc->saveIrcdata($data);

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
			if($ircmembershipno=='' || $resigned_member==''){
				return redirect(app()->getLocale().'/irc_list')->with('error', 'Please choose member');
			}
			$saveIrc = $this->Irc->saveIrcdata($data);
		}
		if ($saveIrc == true) {
			if(!empty($request->id))
			{
				return redirect(app()->getLocale().'/irc_list')->with('message', 'Irc Updated Succesfully');
			}
			else
			{
				return redirect(app()->getLocale().'/irc_list')->with('message', 'IRC Name Added Succesfully');
			}
		}
	}
	//getMembersList
	public function getMembersList(Request $request)
	{
	    $searchkey = $request->input('searchkey');
        $search = $request->input('query');
        $res['suggestions'] = DB::table('membership as m')->select(DB::raw('CONCAT(m.name, " - ", m.member_number) AS value'),'m.id as number','m.branch_id as branch_id','m.member_number','m.gender')      
                            ->where(function($query) use ($search){
                                $query->orWhere('m.member_number', '=',"{$search}")
                                    ->orWhere('m.name', 'LIKE',"%{$search}%")
									->orWhere('m.old_ic', '=',"{$search}")
									->orWhere('m.new_ic', '=',"{$search}");
                            })->limit(25)
                            ->where('status_id','!=',4)  
                            ->get();   
         return response()->json($res);
	}

	 public function getAutomemberslist(Request $request){
        $searchkey = $request->input('serachkey');
        $search = $request->input('query');
        //DB::enableQueryLog();
        $res['suggestions'] = DB::table('membership as m')->select(DB::raw('CONCAT(m.name, " - ", m.member_number) AS value'),'m.id as number','m.branch_id as branch_id','m.member_number as member_code')      
                            ->where(function($query) use ($search){
                                $query->orWhere('m.id','LIKE',"%{$search}%")
                                    ->orWhere('m.member_number', 'LIKE',"%{$search}%")
                                    ->orWhere('m.name', 'LIKE',"%{$search}%");
							})
							->where('m.status_id','!=',4)
							->limit(25)
                            ->get();        
        //$queries = DB::getQueryLog();
                            //  dd($queries);
         return response()->json($res);
    }
}
