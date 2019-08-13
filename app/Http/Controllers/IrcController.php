<?php

namespace App\Http\Controllers;
use App\Role;
use App\User;
use App\Model\Membership;
use DB;
use URL;
use Illuminate\Support\Facades\Crypt;
use Auth;

use Illuminate\Http\Request;

class IrcController extends CommonController
{
    public function __construct() {
        
    }
	
	public function index() {
		$irc = env("IRC",'Not set');
		$irc = $irc=='' ? 0 : $irc;
        echo 'IRC:'.$irc;
	}
	
	public function ircIndex()
    {
		return view('IRC.irc');
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
				['MemberCode' => $member_code, 'user_id' => $member_user->id,'account_type' => $account_type, 'created_by' => Auth::user()->id, 'created_at' => date('Y-m-d')]
			);
			return redirect( app()->getLocale().'/list_irc_account')->with('message','User account added successfully'); 
		}else{
			return redirect( app()->getLocale().'/list_irc_account')->with('error','Failed to add account'); 
		}
	}
	
	public function ajax_irc_users_list(Request $request){
		$columns = array(
            0 => 'name',
            1 => 'email',
            2 => 'id',
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
				$users =  DB::table('irc_account as i')->select('u.id','u.name','u.email','i.account_type')
							->leftjoin('users as u', 'i.user_id', '=', 'u.id')
							->orderBy($order,$dir)
							->get()->toArray();
            }else{
				$users = DB::table('irc_account as i')->select('u.id','u.name','u.email','i.account_type')
						->leftjoin('users as u', 'i.user_id', '=', 'u.id')
						->offset($start)
						->limit($limit)
						->orderBy($order,$dir)
						->get()->toArray();
            }
        
        }
        else {
        $search = $request->input('search.value'); 
        if( $limit == -1){
            $users = DB::table('irc_account as i')->select('u.id','u.name','u.email','i.account_type')
						->leftjoin('users as u', 'i.user_id', '=', 'u.id')
						->where('u.id','LIKE',"%{$search}%")
                        ->orWhere('u.name', 'LIKE',"%{$search}%")
                        ->orWhere('u.email', 'LIKE',"%{$search}%")
                        ->orderBy($order,$dir)
                        ->get()->toArray();
        }else{
            $users =  DB::table('irc_account as i')->select('u.id','u.name','u.email','i.account_type')
					->leftjoin('users as u', 'i.user_id', '=', 'u.id')
					->where('u.id','LIKE',"%{$search}%")
					->orWhere('u.name', 'LIKE',"%{$search}%")
					->orWhere('u.email', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get()->toArray();
        }
        $totalFiltered = DB::table('irc_account as i')->select('u.id','u.name','u.email','i.account_type')
							->leftjoin('users as u', 'i.user_id', '=', 'u.id')
							->where('u.id','LIKE',"%{$search}%")
							->orWhere('u.name', 'LIKE',"%{$search}%")
							->orWhere('u.email', 'LIKE',"%{$search}%")
                   			 ->count();
        }
        $data = $this->CommonAjaxReturn($users, 0, 'master.destroy', 0);
    
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );

        echo json_encode($json_data); 
	}

	public function listIrc(Request $request){
		return view('irc.list_irc');
	}

	public function getIrcMembersList(Request $request)
	{
		DB::connection()->enableQueryLog();
		$searchkey = $request->input('searchkey');
        $search = $request->input('query');
		$res['suggestions'] = DB::table('irc_account as irc')->select(DB::raw('CONCAT(m.name, " - ", m.member_number) AS value'),'m.id as number','m.branch_id as branch_id','m.member_number')
							->leftjoin('membership as m','irc.MemberCode','=','m.id')
							->where('irc.account_type','=','	
							irc-confirmation')
							->where(function($query) use ($search){
                                $query->orWhere('m.id','LIKE',"%{$search}%")
                                    ->orWhere('m.member_number', 'LIKE',"%{$search}%")
                                    ->orWhere('m.name', 'LIKE',"%{$search}%");
                            })->limit(25)
							->get();   
		// $queries = DB::getQueryLog();
		// dd($queries);
         return response()->json($res);

	}
	
	public function ajax_irc_list(Request $request){
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
		if($statusfilter!=''){
			$totalqry = $totalqry->where('i.status','=',$statusfilter);
		}
		
		$commonselect = DB::table('irc_confirmation as i')
						->select(DB::raw('if(i.status=1,"Confirm","pending") as status_name'),'i.status','m.member_number as resignedmemberno','m.name as resignedmembername','i.resignedmembericno','i.resignedmemberbankname','i.resignedmemberbranchname','i.submitted_at as submitted_at','i.submitted_at as received','i.id')
						->leftjoin('membership as m', 'i.resignedmemberno', '=', 'm.id');
		
		if($statusfilter!=''){
			$commonselect = $commonselect->where('i.status','=',$statusfilter);
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
			$irclist =  $irclist->orderBy($order,$dir)
							->get()->toArray();     
        }
        else {
			$search = $searchfilter; 
			$irclist =  $commonselect->where(function($query) use ($search){
							$query->orWhere('i.id','LIKE',"%{$search}%")
								->orWhere('i.resignedmembername', 'LIKE',"%{$search}%")
								->orWhere('i.resignedmembericno', 'LIKE',"%{$search}%");
						});
			
			if( $limit != -1){
				$irclist =  $irclist->offset($start)
									->limit($limit);
			}
			
			$irclist =  $irclist->orderBy($order,$dir)
							->get()->toArray(); 
		
			$totalFiltered =$commonselect->where(function($query) use ($search){
									$query->orWhere('i.id','LIKE',"%{$search}%")
										->orWhere('i.resignedmembername', 'LIKE',"%{$search}%")
										->orWhere('i.resignedmembericno', 'LIKE',"%{$search}%");
								})->count();
        }
		
		$data = array();
        if(!empty($irclist))
        {
            foreach ($irclist as $irc)
            {
                $nestedData['status'] = $irc->status_name;
                $nestedData['resignedmemberno'] = $irc->resignedmemberno;
                $nestedData['resignedmembername'] = $irc->resignedmembername;
                $nestedData['resignedmembericno'] = $irc->resignedmembericno;
                $nestedData['resignedmemberbankname'] = $irc->resignedmemberbankname;
                $nestedData['resignedmemberbranchname'] = $irc->resignedmemberbranchname;
                $nestedData['received'] = $irc->received;
                $company_enc_id = Crypt::encrypt($irc->id);
                $editurl =  route('edit.irc', [app()->getLocale(),$company_enc_id]) ;
				//$editurl = URL::to('/')."/en/sub-company-members/".$company_enc_id;
                $nestedData['options'] = "<a style='float: left;' class='btn btn-small waves-effect waves-light cyan modal-trigger' href='".$editurl."'>Edit IRC</a>";
				$data[] = $nestedData;

			}
        }
        //$data = $this->CommonAjaxReturn($users, 0, 'master.destroy', 0);
    
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
		return $id;
	}
	
}