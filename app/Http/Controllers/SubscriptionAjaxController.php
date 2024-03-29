<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Helpers\CommonHelper;
use App\Model\Country;
use App\Model\State;
use App\Model\City;
use App\Model\Fee;
use App\User;
use App\Model\Relation;
use App\Model\Race;
use App\Model\Reason;
use App\Model\Persontitle;
use App\Model\UnionBranch;
use App\Model\AppForm;
use App\Model\CompanyBranch;
use App\Model\Designation;
use App\Model\Status;
use App\Model\FormType;
use App\Model\Company;
use App\Mail\UnionBranchMailable;
use App\Mail\CompanyBranchMailable;
use App\Model\MonthlySubscription;
use App\Model\MonthlySubscriptionCompany;
use App\Model\MonthlySubscriptionMember;
use App\Model\ArrearEntry;
use DB;
use View;
use Mail;
use App\Role;
use URL;
use Response;
use App\Exports\SubscriptionExport;
use App\Imports\SubscriptionImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToArray;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Auth;
use Artisan;
use App\Jobs\UpdateMemberStatus;
use Log;
use App\Imports\SubsheetImport;





class SubscriptionAjaxController extends CommonController
{
    protected $limit;
    public function __construct() {
        $this->limit = 25;
        ini_set('memory_limit', '-1');
        $this->membermonthendstatus_table = "membermonthendstatus";
        $bf_amount = Fee::where('fee_shortcode','=','BF')->pluck('fee_amount')->first();
        $ins_amount = Fee::where('fee_shortcode','=','INS')->pluck('fee_amount')->first();
        $this->bf_amount = $bf_amount=='' ? 3 : $bf_amount;
        $this->ins_amount = $ins_amount=='' ? 7 : $ins_amount;
    }
    //Ajax Datatable Countries List //Users List 
    public function ajax_submember_list(Request $request){
		$companyid = $request->company_id;
        $status = $request->status;
        $month = $request->month;

        $get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;
        
        $sl=0;
		$columns[$sl++] = 'mon_sub_member.Name';
		$columns[$sl++] = 'mon_sub_member.membercode';
		$columns[$sl++] = 'mon_sub_member.nric';
        $columns[$sl++] = 'mon_sub_member.amount';
        if($status!='all'){
          $columns[$sl++] = 'mon_sub_member.statusId';
        }
		$columns[$sl++] = 'mon_sub_member.id';
		$columns[$sl++] = 'mon_sub_member.id';
        // $columns = array( 
        //     0 => 'Name', 
        //     1 => 'membercode', 
        //     2 => 'nric', 
        //     3 => 'amount', 
        //     4 => 'statusId', 
        //     5 => 'id',
        // );
        
        $race_id = $request->input('race_id'); 
		$memberid = $request->input('memberid'); 
	 	$designation_id = $request->input('designation_id');
        

		$commonqry = DB::table('mon_sub')->select('mon_sub.id as mid','mon_sub_member.id as mmid','mon_sub.Date','mon_sub_company.MonthlySubscriptionId',
        'mon_sub_company.CompanyCode','company.company_name','company.id','mon_sub_member.Name','mon_sub_member.membercode','mon_sub_member.nric','mon_sub_member.amount','status.status_name as statusId','status.font_color','mon_sub_member.created_by','m.branch_id','m.member_number as member_number')
        ->join('mon_sub_company', 'mon_sub.id' ,'=','mon_sub_company.MonthlySubscriptionId')
        ->join('company','company.id','=','mon_sub_company.CompanyCode')
        ->join('mon_sub_member','mon_sub_company.id','=','mon_sub_member.MonthlySubscriptionCompanyId')
        ->leftjoin('status','mon_sub_member.StatusId','=','status.id')
        ->leftjoin('membership as m','m.id','=','mon_sub_member.MemberCode')
        ->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
        ->leftjoin('race as r','r.id','=','m.race_id')
        ->leftjoin('designation as d','d.id','=','m.designation_id')
        ->where('mon_sub.Date','!=',DB::raw('DATE_FORMAT(m.doj, "%Y-%m-01")'));

        if($race_id!="")
        {
             $commonqry = $commonqry->where('m.race_id','=',$race_id);
        }
        if($memberid != "")
        {
            $commonqry = $commonqry->where('m.id','=',$memberid);
        }
        if($designation_id != "")
        {
            $commonqry = $commonqry->where('m.designation_id','=',$designation_id);
        }

        //$commonqry->dump()->get();

        // $queries = DB::getQueryLog();
        // dd($queries);

        if($status!='all'){
            $commonqry = $commonqry->where('mon_sub_member.StatusId','=',$status); 
        }
        $commonqry = $commonqry->where('mon_sub_member.MonthlySubscriptionCompanyId','=',$companyid)
                                ->where('mon_sub.Date','=',$month);
        
        //$commonqry->dump()->get();
        $totalData = $commonqry->count();
        
        $totalFiltered = $totalData; 
        
       $limit = $request->input('length');
       $start = $request->input('start');
		  //var_dump($start);
		  //exit;
        $order = $columns[$request->input('order.0.column')];
     
        $dir = $request->input('order.0.dir');
        if(empty($request->input('search.value')))
        {            
            $sub_mem = $commonqry;
			if( $limit != -1){
				$sub_mem = $sub_mem->offset($start)
							->limit($limit);
			}
			$sub_mem = $sub_mem->orderBy($order,$dir)
			->get()->toArray();
        }
        else {
            $search = $request->input('search.value'); 
            
            $sub_mem = $commonqry->where(function($query) use ($search){
                            $query->orWhere('mon_sub_member.Name', 'LIKE',"%{$search}%")
                            ->orWhere('mon_sub_member.MemberCode', 'LIKE',"%{$search}%")
                            ->orWhere('mon_sub_member.NRIC', 'LIKE',"%{$search}%")
                            ->orWhere('m.member_number', 'LIKE',"%{$search}%")
                            ->orWhere('mon_sub_member.Amount', 'LIKE',"%{$search}%");
                        });  
          
		    if( $limit != -1){
			   $sub_mem = $sub_mem->offset($start)
						->limit($limit);
		    }
		    $sub_mem = $sub_mem->orderBy($order,$dir)
					  ->get()->toArray();
			
			
            $totalFiltered =  $commonqry->where(function($query) use ($search){
                                    $query->orWhere('mon_sub_member.Name', 'LIKE',"%{$search}%")
                                    ->orWhere('mon_sub_member.MemberCode', 'LIKE',"%{$search}%")
                                    ->orWhere('mon_sub_member.NRIC', 'LIKE',"%{$search}%")
                                    ->orWhere('m.member_number', 'LIKE',"%{$search}%")
                                    ->orWhere('mon_sub_member.Amount', 'LIKE',"%{$search}%");
                                })  
							   ->count();
        }
    //     var_dump($sub_mem);
    //    exit;
        $result = $sub_mem;

        $data = array();
        if(!empty($result))
        {
            foreach ($result as $resultdata)
            {
                $autoid = $resultdata->mmid;
                // foreach($resultdata as $newkey => $newvalue){
                //     if($newkey=='id'){
                //         $autoid = $newvalue;
                //     }else{
                //         $nestedData[$newkey] = $newvalue;
                //     }
                // }
                $nestedData['Name'] = $resultdata->Name;
                $nestedData['membercode'] = $resultdata->member_number;
                $nestedData['nric'] = $resultdata->nric;
                $nestedData['amount'] = $resultdata->amount;
                $font_color = $resultdata->font_color;
                $nestedData['font_color'] = $font_color;

                if($status=='all'){
                    $nestedData['statusId'] = $resultdata->statusId;
                    $nestedData['font_color'] = $font_color;
                }

                $memberid = $resultdata->membercode;
                $font_color = $resultdata->font_color;
                
                $enc_id = $memberid!='' ? Crypt::encrypt($memberid) : '';
                 $branchid = $resultdata->branch_id;
				
                $actions ='';
                $baseurl = URL::to('/');
                $member_transfer_link = $baseurl.'/'.app()->getLocale().'/member_transfer?member_id='.$enc_id.'&branch_id='.Crypt::encrypt($branchid);
                $histry = $memberid!='' ? route('member.history', [app()->getLocale(),$enc_id]) : '#';
                $member_delete_link = $baseurl.'/'.app()->getLocale().'/subscription_delete?sub_id='.$autoid;
                
                $actions .="<a style='float: left; margin-left: 10px;cursor:pointer;' title='Edit Subscription'  class='' onClick='return EditSubscription(".$autoid.")' ><i class='material-icons' style='color:#00bcd4'>edit</i></a>";
                $actions .="<a style='float: left; margin-left: 10px;' onclick='return ConfirmDeletion()' title='Delete Subscription'  class='' href='$member_delete_link'><i class='material-icons' style='color:red'>delete</i></a>";
                
                if($memberid!=''){
                    
                    $actions .="<a style='float: left; margin-left: 10px;' title='History'  class='' href='$histry'><i class='material-icons' style='color:#ff6f00;'>history</i></a>";

                    if($user_role=='union'){
                        $actions .="<a style='float: left; margin-left: 10px;' title='Member Transfer'  class='' href='$member_transfer_link'><i class='material-icons' style='color:#FFC107'>transfer_within_a_station</i></a>";
                    }
                    
                }  
                $nestedData['options'] = $actions;
                $data[] = $nestedData;

            }
        }
        
        //$data = $this->CommonAjaxReturn($sub_mem, 2, '',2); 
      
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );

        echo json_encode($json_data); 
    }

    public function ajax_pending_member_list(Request $request)
    {
        // echo "hii";
        // die;
        $companyid = $request->company_id;
        $sl=0;
        $columns = array( 
            $sl++ => 'Name', 
            $sl++ => 'membercode', 
            $sl++ => 'nric', 
            $sl++ => 'amount', 
            $sl++ => 'statusId', 
            $sl++ => 'mon_sub_member.id',
        );
        DB::enableQueryLog();
            $commonqry = DB::table('mon_sub')->select('mon_sub.id','mon_sub.Date','mon_sub_company.MonthlySubscriptionId',
            'mon_sub_company.CompanyCode','company.company_name','company.id','mon_sub_member.Name','mon_sub_member.membercode','mon_sub_member.nric','mon_sub_member.amount','mon_sub_member.statusId','mon_sub_member.created_by')
            ->join('mon_sub_company', 'mon_sub.id' ,'=','mon_sub_company.MonthlySubscriptionId')
            ->join('company','company.id','=','mon_sub_company.CompanyCode')
            ->join('mon_sub_member','mon_sub_company.id','=','mon_sub_member.MonthlySubscriptionCompanyId')
            ->where('mon_sub_member.MonthlySubscriptionCompanyId','=',$companyid)
            ->where('mon_sub_member.update_status','=','0');
        
        //  $queries = DB::getQueryLog();
        //  dd($queries);
        $totalData = $commonqry->count();
        
        $totalFiltered = $totalData; 
        
       $limit = $request->input('length');
       $start = $request->input('start');
		  //var_dump($start);
		  //exit;
        $order = $columns[$request->input('order.0.column')];
     
        $dir = $request->input('order.0.dir');
        if(empty($request->input('search.value')))
        {            
            $sub_mem = $commonqry;
			if( $limit != -1){
				$sub_mem = $sub_mem->offset($start)
							->limit($limit);
			}
			$sub_mem = $sub_mem->orderBy($order,$dir)
			->get()->toArray();
        }
        else {
			$search = $request->input('search.value'); 
			$sub_mem = $commonqry->where('mon_sub_member.id','LIKE',"%{$search}%")
					   ->orWhere('mon_sub_member.Name', 'LIKE',"%{$search}%")
					   ->orWhere('mon_sub_member.MemberCode', 'LIKE',"%{$search}%")
					   ->orWhere('mon_sub_member.NRIC', 'LIKE',"%{$search}%")
					   ->orWhere('mon_sub_member.Amount', 'LIKE',"%{$search}%");
		    if( $limit != -1){
			   $sub_mem = $sub_mem->offset($start)
						->limit($limit);
		    }
		    $sub_mem = $sub_mem->orderBy($order,$dir)
					  ->get()->toArray();
			
			
			$totalFiltered =  $commonqry->where('mon_sub_member.id','LIKE',"%{$search}%")
							    ->orWhere('mon_sub_member.Name', 'LIKE',"%{$search}%")
							   ->orWhere('mon_sub_member.MemberCode', 'LIKE',"%{$search}%")
							   ->orWhere('mon_sub_member.NRIC', 'LIKE',"%{$search}%")
							   ->orWhere('mon_sub_member.Amount', 'LIKE',"%{$search}%")
							   ->count();
        }
        //var_dump($sub_mem);
       // exit;
        
        $data = $this->CommonAjaxReturnold($sub_mem, 2, '',2); 
      
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );

        echo json_encode($json_data); 
    }

    public function ajax_sub_company_list(Request $request){
        $userid = Auth::user()->id;
        $get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;

        $columns = array( 
            0 => 's.Date', 
            1 => 'c.company_name',
            2 => 'sc.id',
            3 => 'sc.id',
            4 => 'sc.id',
        );
		
		
		if($user_role == 'union'){
            $common_qry = DB::table('mon_sub_company as sc')->select('s.Date as date','c.company_name as company_name','sc.id as id','sc.banktype')
                            ->leftjoin('mon_sub as s', 's.id' ,'=','sc.MonthlySubscriptionId')
                            ->leftjoin('company as c','c.id','=','sc.CompanyCode')
                            ->where('sc.banktype', '=' ,0);

            $common_qry_count = DB::table('mon_sub_company as sc')->select(DB::raw('count(*) as count'))
                            ->leftjoin('mon_sub as s', 's.id' ,'=','sc.MonthlySubscriptionId')
                            ->leftjoin('company as c','c.id','=','sc.CompanyCode')
                            ->where('sc.banktype', '=' ,0);

        }else if($user_role =='union-branch'){
            $unionbranchid = CommonHelper::getUnionBranchID($userid);
            $common_qry = DB::table('mon_sub_company as sc')->select('s.Date as date','c.company_name as company_name','sc.id as id','sc.banktype')
                            ->join('company_branch as cb', 'sc.CompanyCode' ,'=','cb.company_id')
                            ->leftjoin('mon_sub as s', 's.id' ,'=','sc.MonthlySubscriptionId')
                            ->leftjoin('company as c','c.id','=','sc.CompanyCode')
                            ->where('cb.union_branch_id', '=' ,$unionbranchid)
                            ->where('sc.banktype', '=' ,0)
                            ->GroupBY('sc.id');
            $common_qry_count = DB::table('mon_sub_company as sc')->select(DB::raw('count(*) as count'))
                    ->leftjoin('company_branch as cb', 'sc.CompanyCode' ,'=','cb.company_id')
                    ->leftjoin('mon_sub as s', 's.id' ,'=','sc.MonthlySubscriptionId')
                    ->leftjoin('company as c','c.id','=','sc.CompanyCode')
                    ->where('cb.union_branch_id', '=' ,$unionbranchid)
                    ->where('sc.banktype', '=' ,0)
                    ->GroupBY('sc.id');
        } 
        else if($user_role =='company'){
            $companyid = CommonHelper::getCompanyID($userid);
			$common_qry = DB::table('mon_sub_company as sc')->select('s.Date as date','c.company_name as company_name','sc.id as id','sc.banktype')
                            ->join('company_branch as cb', 'sc.CompanyCode' ,'=','cb.company_id')
                            ->leftjoin('mon_sub as s', 's.id' ,'=','sc.MonthlySubscriptionId')
                            ->leftjoin('company as c','c.id','=','sc.CompanyCode')
                            ->where('cb.company_id', '=' ,$companyid)
                            ->where('sc.banktype', '=' ,0)
                            ->GroupBY('sc.id');
            $common_qry_count = DB::table('mon_sub_company as sc')->select(DB::raw('count(*) as count'))
                            ->leftjoin('company as c','c.id','=','sc.CompanyCode')
                            ->where('sc.CompanyCode', '=' ,$companyid)->where('sc.banktype', '=' ,0);
        }
        else if($user_role =='company-branch'){
			$common_qry = DB::table('mon_sub_company as sc')->select('s.Date as date','c.company_name as company_name','sc.id as id','sc.banktype')
                            ->join('company_branch as cb', 'sc.CompanyCode' ,'=','cb.company_id')
                            ->leftjoin('mon_sub as s', 's.id' ,'=','sc.MonthlySubscriptionId')
                            ->leftjoin('company as c','c.id','=','sc.CompanyCode')
                            ->where('cb.user_id', '=' ,$userid)
                            ->where('sc.banktype', '=' ,0)
                            ->GroupBY('sc.id');
             $common_qry_count = DB::table('mon_sub_company as sc')->select(DB::raw('count(*) as count'))
                        ->leftjoin('company_branch as cb', 'sc.CompanyCode' ,'=','cb.company_id')
                        ->leftjoin('mon_sub as s', 's.id' ,'=','sc.MonthlySubscriptionId')
                        ->leftjoin('company as c','c.id','=','sc.CompanyCode')
                        ->where('cb.user_id', '=' ,$userid)
                        ->where('sc.banktype', '=' ,0)
                        ->GroupBY('sc.id');
        } 
        $totalData = $common_qry_count->count();

        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');


        if(empty($request->input('search.value')))
        {            
			$company_qry = $common_qry;
			if( $limit != -1){
				$company_qry = $company_qry->offset($start)->limit($limit);
			}
			$company_qry = $company_qry->orderBy($order,$dir)
							->get()->toArray();
        }
        else {
			//DB::enableQueryLog();
			$search = $request->input('search.value'); 
			$dateformat = '';  
			$yearformat = '';  
			$monthformat = '';  
			if(preg_match("^[a-z]{3}/[0-9]{4}^", $search)==true || preg_match("^[A-Z]{3}/[0-9]{4}^", $search)==true ){
				$fm_date = explode("/",$search);
				$dateformat = date('Y-m-01',strtotime('01-'.$fm_date[0].'-'.$fm_date[1]));
			}
			if(preg_match("^[a-z]{3}^", $search)==true || preg_match("^[A-Z]{3}^", $search)==true ){
				$fm_date = explode("/",$search);
				$monthformat = date('m',strtotime('01-'.$fm_date[0].'-2019'));
			}	
			if(preg_match("^[0-9]{4}^", $search)==true){
				$fm_date = explode("/",$search);
				$yearformat = date('Y',strtotime('01-08-'.$fm_date[0]));
            }	
            //return $dateformat;		
			
			$company_qry = $common_qry;
			if( $limit != -1){
				$company_qry = $company_qry->offset($start)->limit($limit);
			}
			 
			$company_qry =  $company_qry->where(function($query) use ($search,$dateformat,$monthformat,$yearformat){
                                $query->orWhere('sc.id','LIKE',"%{$search}%")
                                ->orWhere('c.company_name', 'LIKE',"%{$search}%");
							
                                //->orWhere(DB::raw('year(s.Date)'), '=',"%{$yearformat}%")
								if($dateformat!=''){
									$query->orWhere('s.Date', '=',"{$dateformat}");
								}else{
                                    if($monthformat!=''){
                                        $query->orWhere(DB::raw('month(s.`Date`)'), '=',"{$monthformat}");
                                    }
                                    if($yearformat!=''){
                                        $query->orWhere(DB::raw('year(s.`Date`)'), '=',"{$yearformat}");
                                    }
                                }
                            });
							 //$queries = DB::getQueryLog();
							//dd($queries);
			$company_qry = $company_qry->orderBy($order,$dir)->get()->toArray();
			
			$totalFiltered = $common_qry->count();
        }
        $data = array();
        if(!empty($company_qry))
        {
            foreach ($company_qry as $company)
            {
                //dd($company->banktype);
                $nestedData['month_year'] = date('M/Y',strtotime($company->date));
                $nestedData['company_name'] = $company->company_name;
                $date = date('M/Y',strtotime($company->date));
                $company_enc_id = Crypt::encrypt($company->id);
                $editurl =  route('subscription.members', [app()->getLocale(),$company_enc_id]) ;
                $summaryurl =  route('subscription.summary', [app()->getLocale(),$company_enc_id]) ;
                
                if($company->banktype==1){
                    $members_count = CommonHelper::subCompanyMembersActCount($company_enc_id, $user_role, $userid,$date);
                    $members_amt = CommonHelper::subCompanyMembersActAmount($company_enc_id, $user_role, $userid,$date);
                }else{
                    $members_count = CommonHelper::subCompanyMembersNotDojCount($company_enc_id, $user_role, $userid,$date);
                    $members_amt = CommonHelper::subCompanyMembersNotDojAmount($company_enc_id, $user_role, $userid,$date);

                    //$members_count = CommonHelper::subCompanyMembersCount($company_enc_id, $user_role, $userid,$date);
                   // $members_amt = CommonHelper::subCompanyMembersAmount($company_enc_id, $user_role, $userid,$date);
                }
                $members_amt = number_format($members_amt,2,".",",");
                
                $nestedData['company_name'] = $company->company_name;  
               // $nestedData['company_name'] = $company->company_name."&nbsp;&nbsp;&nbsp;".'<a href="'.$editurl.'">&nbsp; <span class="badge badge pill light-blue mr-10">'.$members_count.'</span></a>';  
               if($user_role =='company'){ 
                 $editurl= '#';
               }          
                $nestedData['count'] = '<a href="'.$editurl.'" class="new badge" title="View Members"><span class=" badge light-blue">'.$members_count.'</span></a> &nbsp; &nbsp;';

                // if($user_role =='company'){
                //    $nestedData['count'] .= '<a style="" target="_blank" href="'.$summaryurl.'" title="View Members" class="waves-effect waves-light blue btn btn-sm" href="">Summary</a>';
                // }

                $nestedData['amount'] = '<a href="'.$editurl.'" class="new badge" title="View Members"><span class=" badge pink">'.$members_amt.'</span></a>';
				$data[] = $nestedData;
			}
        }
        //$data = $this->CommonAjaxReturn($company_qry, 0, 'master.countrydestroy',0); 
       
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );

        echo json_encode($json_data); 
    }

    public function getDatewiseMember(Request $request){
        $json_data = ['data' => [], 'status' => 0];
        $get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;
        $user_id = Auth::user()->id;
        $entry_date = $request->input('entry_date');
        $fm_date = explode("/",$entry_date);
        $fm_date[1].'-'.$fm_date[0].'-'.'01';
        $dateformat = date('Y-m-01',strtotime($fm_date[1].'-'.$fm_date[0].'-'.'01'));
        if($entry_date!=""){
            $status_all = Status::where('status',1)->get();
            $status_data = [];
            $approval_status = DB::table('mon_sub_match_table as mt')
                                    ->select('mt.id as id','mt.match_name as match_name')
                                    ->get();
            $approval_data = [];
			$total_members_count = 0;
            $total_members_amount = 0;
			$total_match_members_count = 0;
			$total_match_approval_members_count = 0;
			$total_match_pending_members_count = 0;
            foreach($status_all as $key => $value){
                if($value->id>=3){
                    $members_count = CommonHelper::statusSubsMembersNotDojActCount($value->id, $user_role, $user_id, $dateformat);
				    $members_amount = CommonHelper::statusMembersNotDojActAmount($value->id, $user_role, $user_id, $dateformat);
                }else{
                    $members_count = CommonHelper::statusSubsMembersNotDojCount($value->id, $user_role, $user_id, $dateformat);
				    $members_amount = CommonHelper::statusMembersNotDojAmount($value->id, $user_role, $user_id, $dateformat);
                }
				
                $status_data['count'][$value->id] = $members_count;
                $status_data['amount'][$value->id] = number_format($members_amount,2,".",",");
				$total_members_count += $members_count;
				$total_members_amount += $members_amount;
            }
            foreach($approval_status as $key => $value){
               
                if($value->id==2){
                    $match_members_count = CommonHelper::statusSubsMatchCount($value->id, $user_role, $user_id, $dateformat);
                    $match_approval_members_count = CommonHelper::statusSubsMatchApprovalCount($value->id, $user_role, $user_id,1, $dateformat);
			    	$match_pending_members_count = CommonHelper::statusSubsMatchApprovalCount($value->id, $user_role, $user_id,0, $dateformat);
                }else{
                    if(($value->id == 6 || $value->id == 7)){
                        $match_members_count = CommonHelper::statusSubsMatchNotDojActCount($value->id, $user_role, $user_id, $dateformat);
                        $match_approval_members_count = CommonHelper::statusSubsMatchNotApprovalActCount($value->id, $user_role, $user_id,1, $dateformat);
                        $match_pending_members_count = CommonHelper::statusSubsMatchNotApprovalActCount($value->id, $user_role, $user_id,0, $dateformat);
                    }
                    else{
                        $match_members_count = CommonHelper::statusSubsMatchNotDojCount($value->id, $user_role, $user_id, $dateformat);
                        $match_approval_members_count = CommonHelper::statusSubsMatchNotApprovalCount($value->id, $user_role, $user_id,1, $dateformat);
                        $match_pending_members_count = CommonHelper::statusSubsMatchNotApprovalCount($value->id, $user_role, $user_id,0, $dateformat);
                    }
                    
                }
				
                $approval_data['count'][$value->id] = $match_members_count;
                $approval_data['approved'][$value->id] = $match_approval_members_count;
                $approval_data['pending'][$value->id] = $match_pending_members_count;
				$total_match_members_count += $match_members_count;
				$total_match_approval_members_count += $match_approval_members_count;
				$total_match_pending_members_count += $match_pending_members_count;
            }
			$sundry_count = CommonHelper::statusSubsMatchCount(2, $user_role, $user_id,$dateformat);
			$sundry_amount = round(CommonHelper::statusSubsMatchAmount(2, $user_role, $user_id,$dateformat), 0);

            $member_additional_count = CommonHelper::additionalSubsMembersNotDojCount(strtotime($dateformat)); 
            $member_additional_amount = CommonHelper::additionalMembersNotDojAmount(strtotime($dateformat)); 

            $member_arrear_count = CommonHelper::arrearMembersCount(strtotime($dateformat)); 
            $member_arrear_amount = CommonHelper::arrearMembersAmount(strtotime($dateformat)); 


			$total_members_count += $sundry_count;
			$total_members_amount += $sundry_amount;
            $json_data = ['status_data' => $status_data, 'approval_data' => $approval_data, 'month_year_number' => strtotime($dateformat) , 'sundry_amount' => number_format($sundry_amount,2,".",","), 'sundry_count' => $sundry_count, 'total_members_amount' => number_format($total_members_amount,2,".",","), 'total_members_count' => $total_members_count, 'total_match_members_count' => $total_match_members_count, 'total_match_approval_members_count' => $total_match_approval_members_count, 'total_match_pending_members_count' => $total_match_pending_members_count, 'member_additional_count' => $member_additional_count, 'member_additional_amount' => number_format($member_additional_amount,2,".",","), 'member_arrear_count' => $member_arrear_count, 'member_arrear_amount' => $member_arrear_amount, 'status' => 1];
        }
        echo json_encode($json_data); 
    }
	
	public function ajax_member_history(Request $request)
    {
        $member_code = $request->member_code;
        $sl=0;
        $columns = array( 
            $sl++ => 'ms.StatusMonth', 
            $sl++ => 'ms.SUBSCRIPTION_AMOUNT', 
            $sl++ => 'ms.BF_AMOUNT', 
            $sl++ => 'ms.INSURANCE_AMOUNT', 
            $sl++ => 'ms.TOTAL_MONTHS', 
            $sl++ => 'ms.LASTPAYMENTDATE', 
            $sl++ => 'ms.TOTALMONTHSPAID',
            $sl++ => 'ms.SUBSCRIPTIONDUE',
            $sl++ => 'ms.TOTALMONTHSPAID',
            $sl++ => 'ms.ACCSUBSCRIPTION',
            $sl++ => 'ms.ACCBF',
            $sl++ => 'ms.ACCINSURANCE',
        );
		
		$commonqry = DB::table($this->membermonthendstatus_table.' as ms')->select('ms.id as id','ms.id as memberid','ms.StatusMonth',
		'ms.SUBSCRIPTION_AMOUNT','ms.BF_AMOUNT','ms.INSURANCE_AMOUNT','ms.TOTAL_MONTHS','ms.LASTPAYMENTDATE','ms.TOTALMONTHSPAID','ms.SUBSCRIPTIONDUE','ms.ACCSUBSCRIPTION','ms.ACCBF','ms.ACCINSURANCE','s.font_color','m.name','m.member_number as member_number')
		->leftjoin('membership as m', 'm.id' ,'=','ms.MEMBER_CODE')
		->leftjoin('status as s','s.id','=','ms.STATUS_CODE')
		->where('m.id','=',$member_code);
        
        $totalData = $commonqry->count();
        
        $totalFiltered = $totalData; 
        
        $limit = $request->input('length');
        $start = $request->input('start');
		
        $order = $columns[$request->input('order.0.column')];
     
        $dir = $request->input('order.0.dir');
        if(empty($request->input('search.value')))
        {            
            $sub_mem = $commonqry;
			if( $limit != -1){
				$sub_mem = $sub_mem->offset($start)
							->limit($limit);
			}
			$sub_mem = $sub_mem->orderBy($order,$dir)
			->get()->toArray();
        }
        else {
			$search = $request->input('search.value'); 
			$sub_mem = $commonqry->where('m.id','LIKE',"%{$search}%")
					   ->orWhere('m.member_number', 'LIKE',"%{$search}%");
		    if( $limit != -1){
			   $sub_mem = $sub_mem->offset($start)
						->limit($limit);
		    }
		    $sub_mem = $sub_mem->orderBy($order,$dir)
					  ->get()->toArray();
			
			
			$totalFiltered =  $commonqry->where('m.id','LIKE',"%{$search}%")
							    ->orWhere('m.member_number', 'LIKE',"%{$search}%")
							   ->count();
        }
      
        $data = array();
        if(!empty($sub_mem))
        {
            foreach ($sub_mem as $history)
            {
                $nestedData['StatusMonth'] = date('M/ Y',strtotime($history->StatusMonth));
                $nestedData['SUBSCRIPTION_AMOUNT'] = $history->SUBSCRIPTION_AMOUNT;
                $nestedData['BF_AMOUNT'] = $history->BF_AMOUNT;
                $nestedData['INSURANCE_AMOUNT'] = $history->INSURANCE_AMOUNT;
                $nestedData['TOTALMONTHSCONTRIBUTION'] = $history->TOTAL_MONTHS;
                $nestedData['LASTPAYMENTDATE'] = date('M/ Y',strtotime($history->LASTPAYMENTDATE));
                $nestedData['TOTALMONTHSPAID'] = $history->TOTALMONTHSPAID;
                $nestedData['SUBSCRIPTIONDUE'] = $history->SUBSCRIPTIONDUE;
                $nestedData['Total'] = $history->SUBSCRIPTIONDUE+$history->TOTALMONTHSPAID;
                $nestedData['ACCSUBSCRIPTION'] = $history->ACCSUBSCRIPTION;
                $nestedData['ACCBF'] = $history->ACCBF;
                $nestedData['ACCINSURANCE'] = $history->ACCINSURANCE;
                $nestedData['font_color'] = $history->font_color;
               
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

	public function ajax_arrear_list(Request $request,$lang)
    {
        $userid = Auth::user()->id;
        $get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;
       // echo "hii"; die;
        $sl=0;
        $columns = array( 
            $sl++ => 'ar.nric', 
            $sl++ => 'ar.membercode', 
            $sl++ => 'm.membername',
            $sl++ => 'ar.company_id', 
            $sl++ => 'ar.branch_id',
            $sl++ => 'ar.arrear_date',
            $sl++ => 'ar.arrear_amount',
            $sl++ => 'ar.no_of_months',
            $sl++ => 'ar.status_id',
        );
        
       // DB::enableQueryLog();
		$commonqry = DB::table('arrear_entry as ar')->select('ar.no_of_months','ar.id as arrearid','ar.nric','ar.arrear_date','ar.arrear_amount','cb.branch_name','c.company_name','s.status_name','m.member_number','m.name as membername','s.font_color','ar.membercode')
                    ->leftjoin('membership as m','ar.membercode','=','m.id')
                    ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
                    ->leftjoin('company as c','cb.company_id','=','c.id')
                    ->leftjoin('status as s','m.status_id','=','s.id')
                    ->orderBy('ar.id','DESC');
        if($user_role == 'union'){
            $commonqry = $commonqry;
        }else if($user_role =='union-branch'){
            $unionbranchid = CommonHelper::getUnionBranchID($userid);
            $commonqry = $commonqry->where('cb.union_branch_id', '=' ,$unionbranchid);
        }else if($user_role =='company'){
            $companyid = CommonHelper::getCompanyID($userid);
            $commonqry = $commonqry->where('cb.company_id', '=' ,$companyid);
        }else if($user_role =='company-branch'){
            $branchid = CommonHelper::getCompanyBranchID($userid);
            $commonqry = $commonqry->where('cb.id', '=' ,$branchid);
        }
        //  $queries = DB::getQueryLog();
		// 					dd($queries);
        $totalData = $commonqry->count();
        
        $totalFiltered = $totalData; 
        
        $limit = $request->input('length');
        $start = $request->input('start');
		
        $order = $columns[$request->input('order.0.column')];
     
        $dir = $request->input('order.0.dir');
        if(empty($request->input('search.value')))
        {            
            $sub_mem = $commonqry;
			if( $limit != -1){
				$sub_mem = $sub_mem->offset($start)
							->limit($limit);
			}
			$sub_mem = $sub_mem->orderBy($order,$dir)
			->get()->toArray();
        }
        else {
            
            $search = $request->input('search.value'); 
            $sub_mem =  $commonqry->where(function($query) use ($search){
                $query->where('m.id', 'LIKE',"%{$search}%")
                      ->orWhere('ar.nric', 'LIKE',"%{$search}%")
                       ->orWhere('ar.arrear_amount', 'LIKE',"%{$search}%")
                       ->orWhere('cb.branch_name', 'LIKE',"%{$search}%")
                       ->orWhere('c.company_name', 'LIKE',"%{$search}%")
                       ->orWhere('s.status_name', 'LIKE',"%{$search}%")
                       ->orWhere('m.member_number', 'LIKE',"%{$search}%")
                       ->orWhere('ar.arrear_date', 'LIKE',"%{$search}%")
                       ->orWhere('m.name', 'LIKE',"%{$search}%")
                       ->orWhere('ar.no_of_months', 'LIKE',"%{$search}%");
            });          
		    if( $limit != -1){
			   $sub_mem = $sub_mem->offset($start)
						->limit($limit);
		    }
		    $sub_mem = $sub_mem->orderBy($order,$dir)
					  ->get()->toArray();
			
            $totalFiltered =  $commonqry->where(function($query) use ($search){
                        $query->where('m.id', 'LIKE',"%{$search}%")
                              ->orWhere('ar.nric', 'LIKE',"%{$search}%")
                               ->orWhere('ar.arrear_amount', 'LIKE',"%{$search}%")
                               ->orWhere('cb.branch_name', 'LIKE',"%{$search}%")
                               ->orWhere('c.company_name', 'LIKE',"%{$search}%")
                               ->orWhere('s.status_name', 'LIKE',"%{$search}%")
                               ->orWhere('m.member_number', 'LIKE',"%{$search}%")
                               ->orWhere('ar.arrear_date', 'LIKE',"%{$search}%")
                               ->orWhere('m.name', 'LIKE',"%{$search}%")
                               ->orWhere('ar.no_of_months', 'LIKE',"%{$search}%");
                    })->count();
        }
      
        $data = array();
        if(!empty($sub_mem))
        {
            foreach ($sub_mem as $arrear)
            {
                $nestedData['nric'] = $arrear->nric;
                $nestedData['membercode'] = $arrear->member_number;
                $nestedData['membername'] = $arrear->membername;
                $nestedData['company_id'] = $arrear->company_name;
                $nestedData['branch_id'] = $arrear->branch_name;
                $nestedData['arrear_date'] = date('d/M/ Y',strtotime($arrear->arrear_date));
                $nestedData['arrear_amount'] = $arrear->arrear_amount;
                $nestedData['no_of_months'] = $arrear->no_of_months;
                $nestedData['status_id'] = $arrear->status_name;
                $font_color = $arrear->font_color;
                $nestedData['font_color'] = $font_color;

                $enc_id = Crypt::encrypt($arrear->arrearid);
                $delete =  route('subscription.arrearentrydelete', [app()->getLocale(),$enc_id]) ;

                $histry = route('member.history', [app()->getLocale(),Crypt::encrypt($arrear->membercode)]) ;
                               
                $edit = route('subscription.editbulkarrearrecords', [app()->getLocale(),$enc_id]);
                //$edit = route('subscription.editarrearrecords', [app()->getLocale(),$enc_id]);
                
                $actions ="<a style='float: left;' id='$edit' title='Edit' class='modal-trigger hide' href='$edit'><i class='material-icons' style='color:#2196f3'>edit</i></a>";

                $actions .="<a style='float: left;' id='$edit' title='Edit' class='modal-trigger' href='$edit'><i class='material-icons' style='color:#2196f3'>edit</i></a>";

                $actions .="<a style='float: left; margin-left: 10px;' title='History'  class='' href='$histry'><i class='material-icons' style='color:#ff6f00;'>history</i></a>";
                
                // $actions .="<a><form style='display:inline-block;' action='$delete' method='POST'>".method_field('DELETE').csrf_field();
                // $actions .="<button  type='submit' class='' style='background:none;border:none;'  onclick='return ConfirmDeletion()'><i class='material-icons' style='color:red;'>delete</i></button> </form>";
                $nestedData['options'] = $actions;
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
    public function SubscriptionMemberDetails(Request $request){
       $sub_member_auto_id = $request->input('sub_member_auto_id');
	   $up_member_data = DB::table('mon_sub_member')->where('id','=',$sub_member_auto_id)->first();
	   $match_data = DB::table('mon_sub_member_match')->where('mon_sub_member_id','=',$sub_member_auto_id)->get();
	   $member_id = DB::table('mon_sub_member')->where('id','=',$sub_member_auto_id)->pluck('MemberCode')->first();
	   $data['up_member_data'] = $up_member_data;
	   //$data['up_nric'] = $up_member_data->NRIC;
	   $data['registered_member_name'] = CommonHelper::getmemberName($member_id);
	   $data['registered_member_number'] = CommonHelper::getmembercode_byid($member_id);
	   $data['status'] = 1;
	   $baseurl = URL::to('/');
	   foreach($match_data as $mkey => $member){
		    $match_id = $member->match_id;
		    foreach($member as $newkey => $newvalue){
                $data['match'][$mkey][$newkey] = $newvalue;
            }
		    if($match_id==3){
				$data['match'][$mkey]['uploaded_member_name'] = DB::table('mon_sub_member')->where('id','=',$sub_member_auto_id)->pluck('Name')->first();
		    }
		    if($match_id==4){
				$company_name = CommonHelper::getCompanyIDbyMemberID($member_id);
				$sub_company_name = CommonHelper::getCompanyIDbySubMemberID($sub_member_auto_id);
				$sub_branch_id = CommonHelper::getmemberBranchid($member_id);
				$member_transfer_link = $baseurl.'/'.app()->getLocale().'/member_transfer?member_id='.Crypt::encrypt($member_id).'&branch_id='.Crypt::encrypt($sub_branch_id);
				$data['match'][$mkey]['registered_bank_name'] = $company_name;
				$data['match'][$mkey]['registered_bank_id'] = CommonHelper::getCompanyAutoIDbyMemberID($member_id);
				$data['match'][$mkey]['uploaded_bank_id'] = CommonHelper::getCompanyAutoIDbySubMemberID($sub_member_auto_id);
				$data['match'][$mkey]['uploaded_bank_name'] = $sub_company_name;
				$data['match'][$mkey]['transfer_link'] = $member_transfer_link;
			}
			if($match_id==5){
				$cur_date = DB::table("mon_sub_company as mc")->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')->where('mc.id','=',$up_member_data->MonthlySubscriptionCompanyId)->pluck('Date')->first();
				$last_month = date('Y-m-01',strtotime($cur_date.' -1 Month'));
				$old_subscription_amount = DB::table("mon_sub_member as mm")
								->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
								->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
								->where('mm.MemberCode','=',$member_id)
								->where('ms.Date','=',$last_month)
								->orderBY('mm.MonthlySubscriptionCompanyId','desc')
								->pluck('Amount')
								->first();
				$data['match'][$mkey]['old_payment'] = $old_subscription_amount;
			}
			$data['match'][$mkey]['updated_user'] = CommonHelper::getUserName($member->updated_by);
			$data['match'][$mkey]['created_user'] = CommonHelper::getUserName($member->created_by);
	   }
       echo json_encode($data);  
    }
    public function getMoreSubscription(Request $request){
        $get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;
        $user_id = Auth::user()->id;
        if($user_role=='union'){
			$company_ids = DB::table('company as c')
							->pluck('c.id');
		}else if($user_role=='union-branch'){
			$union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id')->first();
			$company_ids = DB::table('company_branch as cb')
							->leftjoin('company as c','cb.company_id','=','c.id')
							->leftjoin('union_branch as u','cb.union_branch_id','=','u.id')
							->where('cb.union_branch_id', '=',$union_branch_id)
							->groupBY('c.id')
							->pluck('c.id');
		}else if($user_role=='company'){
			$user_company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id')->first();
			$company_ids = DB::table('company_branch as cb')
							->leftjoin('company as c','cb.company_id','=','c.id')
							->leftjoin('union_branch as u','cb.union_branch_id','=','u.id')
							->where('cb.company_id', '=',$user_company_id)
							->groupBY('c.id')
							->pluck('c.id');
		}else if($user_role=='company-branch'){
			$user_company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id')->first();
			$company_ids = DB::table('company_branch as cb')
							->leftjoin('company as c','cb.company_id','=','c.id')
							->leftjoin('union_branch as u','cb.union_branch_id','=','u.id')
							->where('cb.company_id', '=',$user_company_id)
							->groupBY('c.id')
							->pluck('c.id');
		}else{
			$company_ids = [];
        }
        
        $offset = $request->input('offset');
        $filter_date = $request->input('filter_date');
        $member_status = $request->input('member_status');
        $approval_status = $request->input('approval_status');
        $company_id = $request->input('company_id');
        $member_auto_id = $request->input('member_auto_id');
        $status_type = $request->input('status_type');
        $defaultdate = date('Y-m-01',$filter_date);
		
        $data['data_limit'] = $this->limit;
		$members_data = [];
		if($filter_date!=""){
			
			$members_qry =  DB::table('mon_sub_member as m')->select(DB::raw('member.name as member_name, ifnull(member.member_number,"") as member_number,m.Amount as Amount, c.company_name as company_name, member.new_ic as ic,"0" as due,ifnull(s.status_name,"") as status_name, `member`.`id` as memberid, m.id as sub_member_id,m.Name as up_member_name,m.NRIC as up_nric,m.approval_status as approval_status'))
							->leftjoin('mon_sub_company as sc','m.MonthlySubscriptionCompanyId','=','sc.id')
							->leftjoin('mon_sub_member_match as mm','mm.mon_sub_member_id','=','m.id')
							->leftjoin('mon_sub as sm','sc.MonthlySubscriptionId','=','sm.id')
							->leftjoin('membership as member','m.MemberCode','=','member.id')
							->leftjoin('company as c','sc.CompanyCode','=','c.id')
                            ->leftjoin('status as s','m.StatusId','=','s.id');
                            //->leftjoin('member_payments as mp','mp.member_id','=','member.id')
							//->leftjoin('mon_sub_match_table as match','mm.match_id','=','match.id');
			if(isset($company_id) && $company_id!=''){
				$members_qry = $members_qry->where('m.MonthlySubscriptionCompanyId','=',$company_id);
			}
			
			if($member_status!='all' && $member_status!=0){
				$members_qry = $members_qry->where('m.StatusId','=',$member_status);
			}
			if($approval_status!='all'){
				$members_qry = $members_qry->where('mm.match_id','=',$approval_status);
			}
			if($member_status=='0'){
				$members_qry = $members_qry->where('mm.match_id','=',2);
			}
			
			if($member_auto_id!=0 && $member_auto_id!=""){
				$members_qry = $members_qry->where('m.id','=',$member_auto_id);
            }
            $members_qry = $members_qry->whereIn('c.id', $company_ids);
			$members_qry = $members_qry->where('sm.Date','=',$defaultdate);
			$members_data = $members_qry->offset($offset)
              ->limit($data['data_limit'])
			  ->groupBy('m.id')
			  //->dump()
              ->get();
            
		}
		$data['status'] = 0;
        if($member_status!=""){
            $data['status'] = $member_status;
        }
        if($approval_status!=""){
           $data['status'] = $approval_status;
        }
		if($member_status==0 && $approval_status==""){
           $data['status'] = 0;
        }
		$data['status_type'] = $status_type;
        //dd($members_data);
        foreach($members_data as $mkey => $member){
            foreach($member as $newkey => $newvalue){
                $data['member'][$mkey][$newkey] = $newvalue;
            }
            $enc_member = Crypt::encrypt($member->memberid);
            $data['member'][$mkey]['enc_member'] = $enc_member;
            $data['member'][$mkey]['approval_status'] = $member->approval_status;
           // $data['member'][$mkey]['approval_status'] = CommonHelper::get_overall_approval_status($member->sub_member_id);
            $data['member'][$mkey]['due_months'] = CommonHelper::get_duemonths_monthend($member->memberid,$filter_date);
        }
        
        echo json_encode($data); 
    }

    public function getAutomemberslist(Request $request){
        $company_id = $request->input('company_id');
        $filter_date = $request->input('filter_date');
        $member_status = $request->input('member_status');
        $approval_status = $request->input('approval_status');
        $search = $request->input('query');
       
        $member_query = DB::table('mon_sub_member as sm')->select(DB::raw('CONCAT(sm.Name, " - ", sm.NRIC) AS value'),'sm.NRIC as number','m.id as member_code','sm.id as sub_member_id')      
                            //->leftjoin('mon_sub_member as sm','sm.id','=','mm.mon_sub_member_id')
                            ->leftjoin('mon_sub_company as sc','sc.id','=','sm.MonthlySubscriptionCompanyId')
                            ->leftjoin('mon_sub as ms','ms.id','=','sc.MonthlySubscriptionId')
                            ->leftjoin('membership as m','m.id','=','sm.MemberCode')
                            ->where('ms.Date','=',date('Y-m-01',$filter_date));
        if($company_id!=""){
            $member_query = $member_query->where('sm.MonthlySubscriptionCompanyId','=',$company_id);
        }
        if($member_status!=""){
            //$member_query = $member_query->where('sm.StatusId','=',$member_status);
        }
        if($approval_status!=""){
            //$member_query = $member_query->where('mm.match_id','=',$approval_status);
        }
        $member_query = $member_query->where(function($query) use ($search){
                                $query->orWhere('m.id','LIKE',"%{$search}%")
                                    ->orWhere('m.member_number', 'LIKE',"%{$search}%")
                                    ->orWhere('sm.NRIC', 'LIKE',"%{$search}%")
                                    ->orWhere('sm.Name', 'LIKE',"%{$search}%");
                            })->limit(25)
                            ->get(); 
         $res['suggestions'] =  $member_query;      
        //$queries = DB::getQueryLog();
                            //  dd($queries);
         return response()->json($res);
    }

    public function membershistoryMore($lang,Request $request)
    {
        $get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;
        $user_id = Auth::user()->id;
        
        $offset = $request->input('offset');
        $memberid = $request->input('member_id');
        $load_type = $request->input('load_type');
		
        $data['data_limit']=$this->limit;

        $data['member_history'] = DB::table($this->membermonthendstatus_table.' as ms')->select('ms.id as id','ms.id as memberid',DB::raw("DATE_FORMAT(`ms`.`StatusMonth`, '%b /%Y') as StatusMonth"),
											'ms.TOTALSUBCRP_AMOUNT as SUBSCRIPTION_AMOUNT','ms.TOTALBF_AMOUNT as BF_AMOUNT','ms.TOTALINSURANCE_AMOUNT as INSURANCE_AMOUNT','ms.TOTAL_MONTHS',DB::raw("DATE_FORMAT(`ms`.`LASTPAYMENTDATE`, '%b /%Y') as LASTPAYMENTDATE"),'ms.TOTALMONTHSPAID','ms.TOTALMONTHSDUE as TOTALMONTHSDUE','ms.ACCSUBSCRIPTION','ms.ACCBF','ms.ACCINSURANCE','s.font_color','m.name','m.member_number as member_number',DB::raw("ifnull(round(ms.TOTALMONTHSDUE+ms.TOTALMONTHSPAID),0) as total"),'ms.arrear_status','ms.SUBSCRIPTIONDUE')
											->leftjoin('membership as m', 'm.id' ,'=','ms.MEMBER_CODE')
											->leftjoin('status as s','s.id','=','ms.STATUS_CODE')
											->where('ms.MEMBER_CODE','=',$memberid)
											->offset($offset)
                                            ->limit($this->limit)
                                            ->OrderBy('ms.StatusMonth','asc')
                                            ->OrderBy('ms.arrear_status','asc')
                                            ->get();
		
		// $company_list =  $company_view->get();
		// foreach($company_list as $ckey => $company){
        //     foreach($company as $newkey => $newvalue){
        //         $data['company_view'][$ckey][$newkey] = $newvalue;
        //     }
			
        //     $data['company_view'][$ckey]['total_members'] = $total_members;
        //     $data['company_view'][$ckey]['active_amt'] =  number_format($active_amt,2, '.', ',');
        //     $data['company_view'][$ckey]['default_amt'] =  number_format($default_amt,2, '.', ',');
        //     $data['company_view'][$ckey]['struckoff_amt'] =  number_format($struckoff_amt,2, '.', ',');
        //     $data['company_view'][$ckey]['resign_amt'] =  number_format($resign_amt,2, '.', ',');
        //     $data['company_view'][$ckey]['sundry_amt'] =  number_format($sundry_amt,2, '.', ',');
        //     $data['company_view'][$ckey]['total_amount'] =  number_format(($active_amt+$default_amt+$struckoff_amt+$resign_amt+$sundry_amt), 2, '.', ',');
		// 	$data['company_view'][$ckey]['enc_id'] = Crypt::encrypt($company->id);
        // }
    
        echo json_encode($data);
    }

    public function UpdateMemberStatus($lang,Request $request){
        $company_auto_id = $request->company_auto_id;
        //Artisan::call('queue:work --tries=1 --timeout=10000');
        UpdateMemberStatus::dispatch($company_auto_id);
        Artisan::call('queue:work --tries=1 --timeout=20000');
        echo 1;
    }

    public function ApproveSubscriptionAll($lang,Request $request){
        ini_set('memory_limit', -1);
		ini_set('max_execution_time', 0);
        $approval_status = $request->input('approval_status');
        $companyid = $request->input('companyid');
        $approval_date = $request->input('approval_date');
        $bulknameverify = $request->input('bulknameverify');

        $approvaldate = date('Y-m-01',$approval_date);

        $members_qry =  DB::table('mon_sub_member as m')->select('m.id','m.MemberCode','m.Name','m.MonthlySubscriptionCompanyId','m.Amount')
        ->leftjoin('mon_sub_member_match as mm','mm.mon_sub_member_id','=','m.id')
        ->leftjoin('mon_sub_company as sc','m.MonthlySubscriptionCompanyId','=','sc.id')
        ->leftjoin('mon_sub as sm','sc.MonthlySubscriptionId','=','sm.id')
        ->where(function($query) use ($approval_date){
            $query->orWhere('m.approval_status','=','0')
              ->orWhereNull('m.approval_status');
            })
        //->where('m.approval_status','!=',1)
        ->where('mm.match_id','=',$approval_status)
        ->where('sm.Date','=',$approvaldate);
        if($companyid!=null){
            $members_qry =  $members_qry->where('m.MonthlySubscriptionCompanyId','=',$companyid);
        }
        $members_qry = $members_qry->get();
       // Log::channel('approvallog')->info('data: '.json_encode($members_qry));
       // dd($members_qry);
        foreach($members_qry as $members){
            $submemberid = $members->id;
            $memberid = $members->MemberCode;
            $uploaded_member_name = $members->Name;
            if($approval_status==3){
                //DB::enableQueryLog();
				//if($approval_status==1){
					if($bulknameverify==1){
						DB::table('mon_sub_member')->where('id', '=', $submemberid)->update(['Name' =>  CommonHelper::getmemberName($memberid)]);
					}else{
						
						DB::table('membership')->where('id', '=', $memberid)->update(['name' => $uploaded_member_name]);
					}
                //}
                //$queries = DB::getQueryLog();
                //dd($queries);
				DB::table('mon_sub_member_match')->where('mon_sub_member_id', '=', $submemberid)->where('match_id','=' ,3)->update(['approval_status' => 1, 'description' => 'Mismatched Member Name', 'updated_by' => Auth::user()->id]);
            }else{
                DB::table('mon_sub_member_match')->where('mon_sub_member_id', '=', $submemberid)->where('match_id','=' ,$approval_status)->update(['approval_status' => 1, 'updated_by' => Auth::user()->id]);
            }
            $match_count = DB::table('mon_sub_member_match')->where('mon_sub_member_id','=',$submemberid)->count();
           
            $app_match_count = DB::table('mon_sub_member_match')->where('mon_sub_member_id','=',$submemberid)->where('approval_status','=',1)->count();
            
            if($match_count==$app_match_count){
                $updata = ['update_status' => 1,'approval_status' => 1];
                $savedata = MonthlySubscriptionMember::where('id',$submemberid)->update($updata);
               // DB::table('mon_sub_member')->where('id', '=', $submemberid)->update(['approval_status' => 1, 'updated_by' => Auth::user()->id]);
                $sub_member =DB::table("mon_sub_member")->where('id','=',$submemberid)->first();
                $sub_company_id = $members->MonthlySubscriptionCompanyId;
                $member_id = $members->MemberCode;
                $sub_amount = $members->Amount;
                $cur_date = DB::table("mon_sub_company as mc")->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')->where('mc.id','=',$sub_company_id)->pluck('Date')->first();
                $last_month = date('Y-m-01',strtotime($cur_date.' -1 Month'));
               
               
                $memberdata =DB::table("membership")->select('branch_id','designation_id','status_id')->where('id','=',$member_id)->first();
                //$member_doj = $memberdata->doj;
                // $total_subs_obj = DB::table('mon_sub_member')->select(DB::raw('IFNULL(sum("Amount"),0) as amount'))
                //             ->where('MemberCode', '=', $member_id)
                //             ->first();
                // $member_doj = $memberdata->doj;
                // $total_subs = $total_subs_obj->amount;
                
                // $total_count = DB::table('mon_sub_member')
                // ->where('MemberCode', '=', $member_id)
                // ->count();
                
                // $old_subscription_count = DB::table("mon_sub_member as mm")
                //                 ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
                //                 ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                //                 ->where('mm.MemberCode','=',$member_id)
                //                 ->where('ms.Date','=',$last_month)
                //                 ->orderBY('mm.MonthlySubscriptionCompanyId','desc')
                //                 ->count();
                
                // $paid_bf = $total_subs-($total_count*$this->bf_amount);
                
                    
                // $to = Carbon::createFromFormat('Y-m-d H:s:i', $member_doj.' 3:30:34');
                // $from = Carbon::createFromFormat('Y-m-d H:s:i', $cur_date.' 9:30:34');
                // $diff_in_months = $to->diffInMonths($from);
                
                // $bf_due = ($diff_in_months-$total_count)*$this->bf_amount;
                // $ins_due = ($diff_in_months-$total_count)*$this->ins_amount;
                // $total_subs_to_paid = $diff_in_months==0 ? $sub_amount : ($diff_in_months*$sub_amount);
                // $total_pending = $total_subs_to_paid - $total_subs;
                //dd($member_code);
                $branchdata = DB::table("company_branch")->where('id','=',$memberdata->branch_id)->first();
                // $last_subscription_res = DB::table($this->membermonthendstatus_table." as ms")->select('ms.LASTPAYMENTDATE','ms.ACCINSURANCE','ms.ACCBF','ms.ACCSUBSCRIPTION','ms.SUBSCRIPTION_AMOUNT','ms.BF_AMOUNT','ms.TOTALMONTHSPAID','ms.ACCINSURANCE','ms.TOTALMONTHSDUE','ms.SUBSCRIPTIONDUE','ms.TOTALMONTHSCONTRIBUTION')
                // ->where('ms.MEMBER_CODE','=',$member_id)
                // ->where('ms.StatusMonth','<',$cur_date)
                // ->orderBY('ms.StatusMonth','desc')
                // ->first();

                $last_subscription_res = DB::table("member_payments as ms")->select('ms.last_paid_date as LASTPAYMENTDATE','ms.accins_amount as ACCINSURANCE','ms.accbf_amount as ACCBF','ms.accsub_amount as ACCSUBSCRIPTION','ms.sub_monthly_amount as SUBSCRIPTION_AMOUNT','ms.bf_monthly_amount as BF_AMOUNT','ms.totpaid_months as TOTALMONTHSPAID','ms.totdue_months as TOTALMONTHSDUE','ms.totcontribution_months as TOTALMONTHSCONTRIBUTION','ms.duesub_amount as SUBSCRIPTIONDUE','ms.dueins_amount as INSURANCEDUE','ms.duebf_amount as BFDUE')
                ->where('ms.member_id','=',$member_id)
                ->first();

                $m_subs_amt = number_format($sub_amount-($this->bf_amount+$this->ins_amount),2);
            
                $mont_count = DB::table($this->membermonthendstatus_table)->where('StatusMonth', '=', $cur_date)->where('MEMBER_CODE', '=', $member_id)->count();
                //dd($mont_count);
                
               // Log::channel('approvallog')->info('approval log: '.$member_id.'&date:'.$cur_date.'&count:'.$mont_count);
               $last_paid_date = !empty($last_subscription_res) ? $last_subscription_res->LASTPAYMENTDATE : '';

               if(strtotime($last_paid_date)<strtotime($cur_date)){
                    $monthend_data = [
                        'StatusMonth' => $cur_date, 
                        'MEMBER_CODE' => $member_id,
                        'SUBSCRIPTION_AMOUNT' => $m_subs_amt,
                        'BF_AMOUNT' => $this->bf_amount,
                        'LASTPAYMENTDATE' => $cur_date,
                        'TOTALSUBCRP_AMOUNT' => $m_subs_amt,
                        'TOTALBF_AMOUNT' => $this->bf_amount,
                        'TOTAL_MONTHS' => 1,
                        'BANK_CODE' => $branchdata->company_id,
                        'NUBE_BRANCH_CODE' => $branchdata->union_branch_id,
                        'BRANCH_CODE' => $memberdata->branch_id,
                        'MEMBERTYPE_CODE' => $memberdata->designation_id,
                        'ENTRYMODE' => 'S',
                        //'DEFAULTINGMONTHS' => 0,
                        'TOTALMONTHSDUE' => !empty($last_subscription_res) ? $last_subscription_res->TOTALMONTHSDUE : 0,
                        'TOTALMONTHSPAID' => !empty($last_subscription_res) ? $last_subscription_res->TOTALMONTHSPAID+1 : 1,
                        'SUBSCRIPTIONDUE' => !empty($last_subscription_res) ? $last_subscription_res->SUBSCRIPTIONDUE-$m_subs_amt : 0,
                        'BFDUE' => !empty($last_subscription_res) ? $last_subscription_res->BFDUE-$this->bf_amount : 0,
                        'ACCSUBSCRIPTION' => !empty($last_subscription_res) ? $last_subscription_res->ACCSUBSCRIPTION+$m_subs_amt : $m_subs_amt,
                        'ACCBF' => !empty($last_subscription_res) ? $last_subscription_res->ACCBF+$this->bf_amount : $this->bf_amount,
                        'ACCINSURANCE' => !empty($last_subscription_res) ? $last_subscription_res->ACCINSURANCE+$this->ins_amount : $this->ins_amount,
                        //'ACCBENEFIT' => 0,
                        //'CURRENT_YDTBF' => 0,
                        //'CURRENT_YDTSUBSCRIPTION' => 0,
                        'STATUS_CODE' => $memberdata->status_id,
                        'RESIGNED' => $memberdata->status_id==4 ? 1 : 0,
                        'ENTRY_DATE' => date('Y-m-d'),
                        'ENTRY_TIME' => date('h:i:s'),
                        'STRUCKOFF' => $memberdata->status_id==3 ? 1 : 0,
                        'INSURANCE_AMOUNT' => $this->ins_amount,
                        'TOTALINSURANCE_AMOUNT' => $this->ins_amount,
                        'TOTALMONTHSCONTRIBUTION' => !empty($last_subscription_res) ? $last_subscription_res->TOTALMONTHSCONTRIBUTION+1 : 1,
                        'INSURANCEDUE' => !empty($last_subscription_res) ? $last_subscription_res->INSURANCEDUE-$this->ins_amount : 0,
                        //'CURRENT_YDTINSURANCE' => 0,
                    ];


                    //DB::connection()->enableQueryLog();
                    $recent_paid_date = DB::table("member_payments as ms")
                    ->where('ms.member_id','=',$member_id)
                    ->pluck('ms.last_paid_date')
                    ->first();
                    if($mont_count>0 || $recent_paid_date==$cur_date){
                        Log::channel('approvallog')->info('up approval log: '.$member_id.'&date:'.$cur_date.'&count:'.$mont_count);
                        $statuss = DB::table($this->membermonthendstatus_table)->where('StatusMonth', $cur_date)->where('MEMBER_CODE', $member_id)->update($monthend_data);
                        //$queries = DB::getQueryLog();
                        // dd($statuss);
                    }else{
                        Log::channel('approvallog')->info('insert approval log: '.$member_id.'&date:'.$cur_date.'&count:'.$mont_count);
                        DB::table($this->membermonthendstatus_table)->insert($monthend_data);
                    }
                    // $payment_data = [
                    //     'last_paid_date' => $cur_date,
                    //     'totpaid_months' => !empty($last_subscription_res) ? $last_subscription_res->TOTALMONTHSPAID+1 : 1,
                    //     'totcontribution_months' => !empty($last_subscription_res) ? $last_subscription_res->TOTALMONTHSCONTRIBUTION+1 : 1,
                    //     'accbf_amount' => !empty($last_subscription_res) ? $last_subscription_res->ACCBF+$this->bf_amount : $this->bf_amount,
                    //     'accsub_amount' => !empty($last_subscription_res) ? $last_subscription_res->ACCSUBSCRIPTION+$m_subs_amt : $m_subs_amt,
                    //     'accins_amount' => !empty($last_subscription_res) ? $last_subscription_res->ACCINSURANCE+$this->ins_amount : $this->ins_amount,
                    //     'updated_by' => Auth::user()->id,
                    // ];
                    // DB::table('member_payments')->where('member_id', $member_id)->update($payment_data);
               }
               
            }
        }
        return '1';
    }

    public function ajax_subscription_company_list(Request $request){
        $userid = Auth::user()->id;
        $get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;

        $columns = array( 
            0 => 's.Date', 
            1 => 'c.company_name',
            2 => 'sc.id',
            3 => 'sc.id',
            4 => 'sc.id',
        );
		
		
		if($user_role == 'union'){
            $common_qry = DB::table('mon_sub_company as sc')->select('s.Date as date','c.company_name as company_name','sc.id as id','sc.banktype')
                            ->leftjoin('mon_sub as s', 's.id' ,'=','sc.MonthlySubscriptionId')
                            ->leftjoin('company as c','c.id','=','sc.CompanyCode')
                            ->where('sc.banktype', '=' ,1);

            $common_qry_count = DB::table('mon_sub_company as sc')->select(DB::raw('count(*) as count'))
                            ->leftjoin('mon_sub as s', 's.id' ,'=','sc.MonthlySubscriptionId')
                            ->leftjoin('company as c','c.id','=','sc.CompanyCode')
                            ->where('sc.banktype', '=' ,1);

        }else if($user_role =='union-branch'){
            $unionbranchid = CommonHelper::getUnionBranchID($userid);
            $common_qry = DB::table('mon_sub_company as sc')->select('s.Date as date','c.company_name as company_name','sc.id as id','sc.banktype')
                            ->join('company_branch as cb', 'sc.CompanyCode' ,'=','cb.company_id')
                            ->leftjoin('mon_sub as s', 's.id' ,'=','sc.MonthlySubscriptionId')
                            ->leftjoin('company as c','c.id','=','sc.CompanyCode')
                            ->where('cb.union_branch_id', '=' ,$unionbranchid)
                            ->where('sc.banktype', '=' ,1)
                            ->GroupBY('sc.id');
            $common_qry_count = DB::table('mon_sub_company as sc')->select(DB::raw('count(*) as count'))
                    ->leftjoin('company_branch as cb', 'sc.CompanyCode' ,'=','cb.company_id')
                    ->leftjoin('mon_sub as s', 's.id' ,'=','sc.MonthlySubscriptionId')
                    ->leftjoin('company as c','c.id','=','sc.CompanyCode')
                    ->where('cb.union_branch_id', '=' ,$unionbranchid)
                    ->where('sc.banktype', '=' ,1)
                    ->GroupBY('sc.id');
        } 
        else if($user_role =='company'){
            $companyid = CommonHelper::getCompanyID($userid);
			$common_qry = DB::table('mon_sub_company as sc')->select('s.Date as date','c.company_name as company_name','sc.id as id','sc.banktype')
                            ->join('company_branch as cb', 'sc.CompanyCode' ,'=','cb.company_id')
                            ->leftjoin('mon_sub as s', 's.id' ,'=','sc.MonthlySubscriptionId')
                            ->leftjoin('company as c','c.id','=','sc.CompanyCode')
                            ->where('cb.company_id', '=' ,$companyid)
                            ->where('sc.banktype', '=' ,1)
                            ->GroupBY('sc.id');
            $common_qry_count = DB::table('mon_sub_company as sc')->select(DB::raw('count(*) as count'))
                            ->leftjoin('company as c','c.id','=','sc.CompanyCode')
                            ->where('sc.CompanyCode', '=' ,$companyid)->where('sc.banktype', '=' ,1);
        }
        else if($user_role =='company-branch'){
			$common_qry = DB::table('mon_sub_company as sc')->select('s.Date as date','c.company_name as company_name','sc.id as id','sc.banktype')
                            ->join('company_branch as cb', 'sc.CompanyCode' ,'=','cb.company_id')
                            ->leftjoin('mon_sub as s', 's.id' ,'=','sc.MonthlySubscriptionId')
                            ->leftjoin('company as c','c.id','=','sc.CompanyCode')
                            ->where('cb.user_id', '=' ,$userid)
                            ->where('sc.banktype', '=' ,1)
                            ->GroupBY('sc.id');
             $common_qry_count = DB::table('mon_sub_company as sc')->select(DB::raw('count(*) as count'))
                        ->leftjoin('company_branch as cb', 'sc.CompanyCode' ,'=','cb.company_id')
                        ->leftjoin('mon_sub as s', 's.id' ,'=','sc.MonthlySubscriptionId')
                        ->leftjoin('company as c','c.id','=','sc.CompanyCode')
                        ->where('cb.user_id', '=' ,$userid)
                        ->where('sc.banktype', '=' ,1)
                        ->GroupBY('sc.id');
        } 
        $totalData = $common_qry_count->count();

        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');


        if(empty($request->input('search.value')))
        {            
			$company_qry = $common_qry;
			if( $limit != -1){
				$company_qry = $company_qry->offset($start)->limit($limit);
			}
			$company_qry = $company_qry->orderBy($order,$dir)
							->get()->toArray();
        }
        else {
			//DB::enableQueryLog();
			$search = $request->input('search.value'); 
			$dateformat = '';  
			$yearformat = '';  
			$monthformat = '';  
			if(preg_match("^[a-z]{3}/[0-9]{4}^", $search)==true || preg_match("^[A-Z]{3}/[0-9]{4}^", $search)==true ){
				$fm_date = explode("/",$search);
				$dateformat = date('Y-m-01',strtotime('01-'.$fm_date[0].'-'.$fm_date[1]));
			}
			if(preg_match("^[a-z]{3}^", $search)==true || preg_match("^[A-Z]{3}^", $search)==true ){
				$fm_date = explode("/",$search);
				$monthformat = date('m',strtotime('01-'.$fm_date[0].'-2019'));
			}	
			if(preg_match("^[0-9]{4}^", $search)==true){
				$fm_date = explode("/",$search);
				$yearformat = date('Y',strtotime('01-08-'.$fm_date[0]));
            }	
            //return $dateformat;		
			
			$company_qry = $common_qry;
			if( $limit != -1){
				$company_qry = $company_qry->offset($start)->limit($limit);
			}
			 
			$company_qry =  $company_qry->where(function($query) use ($search,$dateformat,$monthformat,$yearformat){
                                $query->orWhere('sc.id','LIKE',"%{$search}%")
                                ->orWhere('c.company_name', 'LIKE',"%{$search}%");
							
                                //->orWhere(DB::raw('year(s.Date)'), '=',"%{$yearformat}%")
								if($dateformat!=''){
									$query->orWhere('s.Date', '=',"{$dateformat}");
								}else{
                                    if($monthformat!=''){
                                        $query->orWhere(DB::raw('month(s.`Date`)'), '=',"{$monthformat}");
                                    }
                                    if($yearformat!=''){
                                        $query->orWhere(DB::raw('year(s.`Date`)'), '=',"{$yearformat}");
                                    }
                                }
                            });
							 //$queries = DB::getQueryLog();
							//dd($queries);
			$company_qry = $company_qry->orderBy($order,$dir)->get()->toArray();
			
			$totalFiltered = $common_qry->count();
        }
        $data = array();
        if(!empty($company_qry))
        {
            foreach ($company_qry as $company)
            {
                //dd($company->banktype);
                $nestedData['month_year'] = date('M/Y',strtotime($company->date));
                $nestedData['company_name'] = $company->company_name;
                $date = date('M/Y',strtotime($company->date));
                $company_enc_id = Crypt::encrypt($company->id);
                $editurl =  route('subscription.members', [app()->getLocale(),$company_enc_id]) ;
                $summaryurl =  route('subscription.summary', [app()->getLocale(),$company_enc_id]) ;
                
                if($company->banktype==1){
                    $members_count = CommonHelper::subCompanyMembersNotDOJActCount($company_enc_id, $user_role, $userid,$date);
                    $members_amt = CommonHelper::subCompanyMembersNotDOJActAmount($company_enc_id, $user_role, $userid,$date);

                    $cond =" AND m.MonthlySubscriptionCompanyId = '$company->id'";

                   

                    $members_amtone = DB::select(DB::raw('SELECT sum(Amount) as Amount FROM mon_sub_member_match as mm LEFT JOIN `mon_sub_member` AS `m` ON mm.mon_sub_member_id=m.id left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` left join membership as member on `member`.`id` = `m`.`MemberCode`  left join status as s on `s`.`id` = `m`.`StatusId`  where 1=1 '.$cond.' AND mm.match_id =4 '));
                    $members_countone = [];
                    $misbankamt = 0;
                    if(!empty($members_amtone)){
                        $misbankamt = $members_amtone[0]->Amount;

                        $members_countone = DB::select(DB::raw('SELECT Amount as value FROM mon_sub_member_match as mm LEFT JOIN `mon_sub_member` AS `m` ON mm.mon_sub_member_id=m.id left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` left join membership as member on `member`.`id` = `m`.`MemberCode`  left join status as s on `s`.`id` = `m`.`StatusId`  where 1=1 '.$cond.' AND mm.match_id =4 '));
                    }
                    $members_amt = $members_amt-$misbankamt;
                    $members_count = $members_count-count($members_countone);
                }else{
                    $members_count = CommonHelper::subCompanyMembersNotDojCount($company_enc_id, $user_role, $userid,$date);
                    $members_amt = CommonHelper::subCompanyMembersNotDojAmount($company_enc_id, $user_role, $userid,$date);
                }
                $members_amt = number_format($members_amt,2,".",",");
                
                $nestedData['company_name'] = $company->company_name;  
               // $nestedData['company_name'] = $company->company_name."&nbsp;&nbsp;&nbsp;".'<a href="'.$editurl.'">&nbsp; <span class="badge badge pill light-blue mr-10">'.$members_count.'</span></a>';       
               if($user_role =='company'){
                   $editurl = '#';
               }     
                $nestedData['count'] = '<a href="'.$editurl.'" class="new badge" title="View Members"><span class=" badge light-blue">'.$members_count.'</span></a> &nbsp; &nbsp;';

                //if($user_role =='company'){
                   $nestedData['count'] .= '<a style="" target="_blank" href="'.$summaryurl.'" title="View Members" class="waves-effect waves-light blue btn btn-sm" href="">Summary</a>';
                //}

                $nestedData['amount'] = '<a href="'.$editurl.'" class="new badge" title="View Members"><span class=" badge pink">'.$members_amt.'</span></a>';
				$data[] = $nestedData;
			}
        }
        //$data = $this->CommonAjaxReturn($company_qry, 0, 'master.countrydestroy',0); 
       
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );

        echo json_encode($json_data); 
    }
    public function unmatchedMemberDetails(Request $request){
        $sub_member_auto_id = $request->input('sub_member_auto_id');
        $up_member_data = DB::table('mon_sub_member')->where('id','=',$sub_member_auto_id)->first();
        $match_data = DB::table('mon_sub_member_match')->where('mon_sub_member_id','=',$sub_member_auto_id)->get();
        $member_id = DB::table('mon_sub_member')->where('id','=',$sub_member_auto_id)->pluck('MemberCode')->first();
        $data['up_member_data'] = $up_member_data;
        $unmatchdata = DB::table('mon_sub_remarks')->where('mon_sub_member_id','=',$sub_member_auto_id)
        ->where('type','=',0)
        ->where('approval_status','=',1)
        ->first();
        $data['registered_member_name'] = CommonHelper::getmemberName($member_id);
	    $data['registered_member_number'] = CommonHelper::getmembercode_byid($member_id);
        $data['unmatchdata'] = $unmatchdata;
        //$data['up_nric'] = $up_member_data->NRIC;
       // $data['registered_member_name'] = CommonHelper::getmemberName($member_id);
       // $data['registered_member_number'] = CommonHelper::getmembercode_byid($member_id);
        $data['status'] = 1;
        $baseurl = URL::to('/');
        
        echo json_encode($data);  
     }
     public function VariationMemberDetails(Request $request){
        $sub_member_auto_id = $request->input('sub_member_auto_id');
        $up_member_data = DB::table('mon_sub_member')->where('id','=',$sub_member_auto_id)->first();
        $match_data = DB::table('mon_sub_member_match')->where('mon_sub_member_id','=',$sub_member_auto_id)->get();
        $member_id = DB::table('mon_sub_member')->where('id','=',$sub_member_auto_id)->pluck('MemberCode')->first();
        $data['up_member_data'] = $up_member_data;
        $unmatchdata = DB::table('mon_sub_remarks')->where('mon_sub_member_id','=',$sub_member_auto_id)
        ->where('type','=',1)
        ->where('approval_status','=',1)
        ->first();
        $data['registered_member_name'] = CommonHelper::getmemberName($member_id);
	    $data['registered_member_number'] = CommonHelper::getmembercode_byid($member_id);
        $data['unmatchdata'] = $unmatchdata;
        //$data['up_nric'] = $up_member_data->NRIC;
       // $data['registered_member_name'] = CommonHelper::getmemberName($member_id);
       // $data['registered_member_number'] = CommonHelper::getmembercode_byid($member_id);
        $data['status'] = 1;
        $baseurl = URL::to('/');
        
        echo json_encode($data);  
     }

     public function unpaidMemberDetails(Request $request){
        $sub_member_auto_id = $request->input('sub_member_auto_id');
        $date = $request->input('date');
        $up_member_data = DB::table('mon_sub_member')->where('id','=',$sub_member_auto_id)->first();
        $match_data = DB::table('mon_sub_member_match')->where('mon_sub_member_id','=',$sub_member_auto_id)->get();
        $member_id = DB::table('mon_sub_member')->where('id','=',$sub_member_auto_id)->pluck('MemberCode')->first();
        $data['up_member_data'] = $up_member_data;
        $unmatchdata = DB::table('mon_sub_remarks')->where('mon_sub_member_id','=',$sub_member_auto_id)
        ->where('date','=',$date)
        ->where('type','=',1)
        ->where('approval_status','=',1)
        ->first();
        $data['registered_member_name'] = CommonHelper::getmemberName($member_id);
	    $data['registered_member_number'] = CommonHelper::getmembercode_byid($member_id);
        $data['unmatchdata'] = $unmatchdata;
        //$data['up_nric'] = $up_member_data->NRIC;
       // $data['registered_member_name'] = CommonHelper::getmemberName($member_id);
       // $data['registered_member_number'] = CommonHelper::getmembercode_byid($member_id);
        $data['status'] = 1;
        $baseurl = URL::to('/');
        
        echo json_encode($data);  
     }

     public function ajax_advance_list(Request $request,$lang)
    {
        $userid = Auth::user()->id;
        $get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;
       // echo "hii"; die;
        $sl=0;
        $columns = array( 
            $sl++ => 'ap.member_id', 
            $sl++ => 'm.name',
            $sl++ => 'cb.company_id', 
            $sl++ => 'm.branch_id',
            $sl++ => 'ap.from_date',
            //$sl++ => 'ap.to_date',
            $sl++ => 'ap.advance_amount',
            $sl++ => 'ap.no_of_months',
            $sl++ => 'm.status_id',
        );
        
       // DB::enableQueryLog();
		$commonqry = DB::table('advance_payments as ap')->select('ap.no_of_months','ap.id as advanceid','ap.from_date','ap.to_date','ap.advance_amount','cb.branch_name','c.company_name','s.status_name','m.member_number','m.name as membername','s.font_color','ap.member_id','ap.paid_amount','ap.balance_amount')
                    ->leftjoin('membership as m','ap.member_id','=','m.id')
                    ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
                    ->leftjoin('company as c','cb.company_id','=','c.id')
                    ->leftjoin('status as s','m.status_id','=','s.id')
                    ->where('ap.no_of_months', '!=' ,0)
                    ->orderBy('ap.id','DESC');
        if($user_role == 'union'){
            $commonqry = $commonqry;
        }else if($user_role =='union-branch'){
            $unionbranchid = CommonHelper::getUnionBranchID($userid);
            $commonqry = $commonqry->where('cb.union_branch_id', '=' ,$unionbranchid);
        }else if($user_role =='company'){
            $companyid = CommonHelper::getCompanyID($userid);
            $commonqry = $commonqry->where('cb.company_id', '=' ,$companyid);
        }else if($user_role =='company-branch'){
            $branchid = CommonHelper::getCompanyBranchID($userid);
            $commonqry = $commonqry->where('cb.id', '=' ,$branchid);
        }
        //  $queries = DB::getQueryLog();
		// 					dd($queries);
        $totalData = $commonqry->count();
        
        $totalFiltered = $totalData; 
        
        $limit = $request->input('length');
        $start = $request->input('start');
		
        $order = $columns[$request->input('order.0.column')];
     
        $dir = $request->input('order.0.dir');
        if(empty($request->input('search.value')))
        {            
            $sub_mem = $commonqry;
			if( $limit != -1){
				$sub_mem = $sub_mem->offset($start)
							->limit($limit);
			}
			$sub_mem = $sub_mem->orderBy($order,$dir)
			->get()->toArray();
        }
        else {
            
            $search = $request->input('search.value'); 
            $sub_mem =  $commonqry->where(function($query) use ($search){
                $query->where('m.id', 'LIKE',"%{$search}%")
                      ->orWhere('m.new_ic', 'LIKE',"%{$search}%")
                       ->orWhere('ap.advance_amount', 'LIKE',"%{$search}%")
                       ->orWhere('cb.branch_name', 'LIKE',"%{$search}%")
                       ->orWhere('c.company_name', 'LIKE',"%{$search}%")
                       ->orWhere('s.status_name', 'LIKE',"%{$search}%")
                       ->orWhere('m.member_number', 'LIKE',"%{$search}%")
                       ->orWhere('ap.from_date', 'LIKE',"%{$search}%")
                       ->orWhere('m.name', 'LIKE',"%{$search}%")
                       ->orWhere('ap.no_of_months', 'LIKE',"%{$search}%");
            });          
		    if( $limit != -1){
			   $sub_mem = $sub_mem->offset($start)
						->limit($limit);
		    }
		    $sub_mem = $sub_mem->orderBy($order,$dir)
					  ->get()->toArray();
			
            $totalFiltered =  $commonqry->where(function($query) use ($search){
                        $query->where('m.id', 'LIKE',"%{$search}%")
                        ->orWhere('m.new_ic', 'LIKE',"%{$search}%")
                        ->orWhere('ap.advance_amount', 'LIKE',"%{$search}%")
                        ->orWhere('cb.branch_name', 'LIKE',"%{$search}%")
                        ->orWhere('c.company_name', 'LIKE',"%{$search}%")
                        ->orWhere('s.status_name', 'LIKE',"%{$search}%")
                        ->orWhere('m.member_number', 'LIKE',"%{$search}%")
                        ->orWhere('ap.from_date', 'LIKE',"%{$search}%")
                        ->orWhere('m.name', 'LIKE',"%{$search}%")
                        ->orWhere('ap.no_of_months', 'LIKE',"%{$search}%");
                    })->count();
        }
      
        $data = array();
        if(!empty($sub_mem))
        {
            foreach ($sub_mem as $arrear)
            {
                $nestedData['membercode'] = $arrear->member_number;
                $nestedData['membername'] = $arrear->membername;
                $nestedData['company_id'] = $arrear->company_name;
                $nestedData['branch_id'] = $arrear->branch_name;
                $nestedData['from_date'] = date('d/M/ Y',strtotime($arrear->from_date));
                //$nestedData['to_date'] = date('d/M/ Y',strtotime($arrear->to_date));
                $nestedData['advance_amount'] = $arrear->advance_amount;
                $nestedData['no_of_months'] = $arrear->no_of_months;
                $nestedData['status_id'] = $arrear->status_name;
                $font_color = $arrear->font_color;
                $nestedData['font_color'] = $font_color;
                $nestedData['paid_amount'] = $arrear->paid_amount;
                $nestedData['balance_amount'] = $arrear->balance_amount;

                $enc_id = Crypt::encrypt($arrear->advanceid);
                $delete =  route('subscription.arrearentrydelete', [app()->getLocale(),$enc_id]) ;

                $histry = route('member.history', [app()->getLocale(),Crypt::encrypt($arrear->member_id)]) ;
                               
               // $edit = route('subscription.editbulkarrearrecords', [app()->getLocale(),$enc_id]);
                //$edit = route('subscription.editarrearrecords', [app()->getLocale(),$enc_id]);

                $edit = route('subscription.editadvance', [app()->getLocale(),$enc_id]);
                //$edit = '';
                
                // $actions ="<a style='float: left;' id='$edit' title='Edit' class='modal-trigger hide' href='$edit'><i class='material-icons' style='color:#2196f3'>edit</i></a>";
                $actions = '';

                if($arrear->paid_amount==0){
                    $actions .="<a style='float: left;' id='$edit' title='Edit' class='modal-trigger' href='$edit'><i class='material-icons' style='color:#2196f3'>edit</i></a>";
                }

                $actions .="<a style='float: left; margin-left: 10px;' id='$edit' title='Pay' onclick='return PaySubscription($arrear->advanceid)' class='modal-trigger' href='#'><i class='material-icons' style='color:#ff4081'>payment</i></a>";

                $actions .="<a style='float: left; margin-left: 10px;' title='History'  class='' href='$histry'><i class='material-icons' style='color:#ff6f00;'>history</i></a>";
                
                // $actions .="<a><form style='display:inline-block;' action='$delete' method='POST'>".method_field('DELETE').csrf_field();
                // $actions .="<button  type='submit' class='' style='background:none;border:none;'  onclick='return ConfirmDeletion()'><i class='material-icons' style='color:red;'>delete</i></button> </form>";
                $nestedData['options'] = $actions;
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

    public function AdvanceDetails($lang,Request $request)
    {
        $advanceid = $request->input('advanceid');

        $advanceres = DB::table('advance_payments as ap')
                        ->select('ap.id as advanceid','ap.member_id','ap.advance_date','ap.from_date','ap.to_date','ap.no_of_months','ap.advance_amount','m.name','m.member_number','ap.balance_amount')
                        ->leftjoin('membership as m','ap.member_id','=','m.id')
                        ->where('ap.id','=',$advanceid)
                        ->first();

        echo json_encode($advanceres);
    }

    public function LatestSubsSalaryUpdate($lang, Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', '8000');
         $rules = array(
                    'file' => 'required|mimes:xls,xlsx',
                );
        $validator = Validator::make(Input::all(), $rules);
        if($validator->fails())
        {
            //return 1;
            return back()->withErrors($validator);
        }
        else
        {
            $inctype = $request->input('types');
            $salcount = 1; 
            if($inctype!=null){
                if(Input::hasFile('file')){
                    $data['entry_date'] = $request->entry_date;
                    $entry_date = $request->entry_date;

                    $datearr = explode("/",$entry_date);  
                    $monthname = $datearr[0];
                    $year = $datearr[1];
                    $full_date = date('Ymdhis',strtotime('01-'.$monthname.'-'.$year));

                    $form_date = date('Y-m-d',strtotime('01-'.$monthname.'-'.$year));
                    $form_datefull = date('Y-m-d',strtotime('01-'.$monthname.'-'.$year)).' '.date('h:i:s');
                    $others = '';
                    

                    $file_name = 'salary_'.$full_date;
                   // $data['sub_company'] = $request->sub_company;
                
                    $file = $request->file('file')->storeAs('salary', $file_name.'.xlsx'  ,'local');

                     $subsdata = (new SubsheetImport)->toArray('storage/app/salary/'.$file_name.'.xlsx');
                     $firstrow = $subsdata[0][0];
                    
                    if($firstrow[0]!='Sno' || $firstrow[1]!='MemberID' || $firstrow[2]!='Name' || $firstrow[3]!='Variation Amount'){
                        return  redirect('en/subscription')->with('error', 'Wrong excel sheet');
                    }
                    $firstsheet = $subsdata[0];
                    $bulkedata = [];
                    for ($i=1; $i < count($firstsheet); $i++) { 
                        if($firstsheet[$i][1]!=''){
                            $memberno = $firstsheet[$i][1];
                            $membername = $firstsheet[$i][2];
                            $memberic = '';
                            $varianceamt = $firstsheet[$i][3];

                            $insertdata = [];
                            $insertdata['member_number'] = $memberno;
                            $insertdata['date'] = $form_datefull;
                            $insertdata['increment_type_id'] = $inctype;
                            $insertdata['name'] = $membername;
                            $insertdata['amount'] = $varianceamt;
                            $insertdata['status'] = 0;
                            $insertdata['created_by'] = Auth::user()->id;
                            $insertdata['created_at'] = date('Y-m-d h:i:s');

                            $bulkedata[] = $insertdata;

                            //$savesal = DB::table('salary_updation_temp')->insert($insertdata);
                        }
                    }
                    $savesal = DB::table('salary_updation_temp')->insert($bulkedata);
                    //dd($bulkedata);
                    return redirect($lang.'/latestsalary_process?date='.strtotime($form_datefull).'&inctype='.$inctype)->with('message','Salary file uploaded successfully');

                }else{
                    return redirect($lang.'/latestsalary_upload')->with('message','please select file');
                }
            }else{
                return redirect($lang.'/latestsalary_upload')->with('message','please select increment type');
            }
        }
    }

    public function LatestSubsSalaryProcess(Request $request,$lang){
        $get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;
        $user_id = Auth::user()->id;
        $data = [];
        
        $date = $request->input('date');
        $inctype = $request->input('inctype');
        $updatedate = date('Y-m-01',$date);

        $memberrowcount = DB::table('salary_updation_temp')->where('date','=',$updatedate)->where('increment_type_id','=',$inctype)->count();

        $data['row_count'] = $memberrowcount;
        $data['month_year'] = $updatedate;
        $data['inctype'] = $inctype;
        $data['monthstring'] = $date;

        if($data['row_count']==0){
            return redirect($lang.'/latestsalary_upload')->with('message','Finished scanning');
        }

        return view('subscription.scan-salary')->with('data',$data);
    }

    public function scanSalary(Request $request,$lang){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', '3000');

        $date = $request->input('monthstring');
        $inctype = $request->input('inctype');
        $updatedate = date('Y-m-01',$date);
        $limit = $request->input('limit');

        $form_date = date('Y-m-d',$date);
        $form_datefull = date('Y-m-d',$date).' '.date('h:i:s');
        $others = '';

        $return_data = ['status' => 0 ,'message' => ''];
        if($updatedate!=""){
            $salary_data = DB::table('salary_updation_temp')->where('date','=',$updatedate)->where('increment_type_id','=',$inctype)->limit($limit)->get();
            foreach($salary_data as $salary){
                $salaryid = $salary->id;
                $memberno = $salary->member_number;
                $membername = $salary->name;
                $memberic = '';
                $varianceamt = $salary->amount;

                $memberid = DB::table('membership as m')->select('m.id')->where('m.member_number', '=', $memberno)->pluck('m.id')->first();
                $salary = DB::table('membership as m')->select('m.salary')->where('m.member_number', '=', $memberno)->pluck('m.salary')->first();

                $updated_salary = CommonHelper::getIncrementValue($memberid,$form_date,$form_date);
                
                $addonsalary = 0;
               
                
                if(!empty($updated_salary)){
                    //dd($updated_salary);
                    $newbasicsal = $salary;
                    foreach($updated_salary as $key => $upsalary){

                        if($upsalary->date==$form_date){
                            if($upsalary->increment_type_id==4){
                                $addonsalary -= $upsalary->additional_amt;
                            }else{
                                $addonsalary += $upsalary->additional_amt;
                            }
                        
                            if($key==0){
                                $newbasicsal = $upsalary->basic_salary;
                            }
                            
                        }else{
                            if($upsalary->increment_type_id==1 || $upsalary->increment_type_id==4){
                                if($upsalary->increment_type_id==4){
                                    $addonsalary -= $upsalary->additional_amt;
                                }else{
                                    $addonsalary += $upsalary->additional_amt;
                                }

                                if($key==0){
                                    $newbasicsal = $upsalary->basic_salary;
                                }
                               
                            }
                        }
                        
                    }
                    $newsalary = $newbasicsal+$addonsalary;

                    $total_subs = ($newsalary*1)/100;
                    $payable_subs = $total_subs;

                    //$payable_subs = number_format($total_subs,2,".","");

                }else{
                    $total_subs = ($salary*1)/100;
                    $payable_subs = $total_subs;
                }

                $subsamt = $payable_subs+$varianceamt;
                
                if($subsamt!=''){
                    $subssal = $subsamt*100;
                    if($inctype!=''){
                        $lastupdate = DB::table('salary_updations as s')->where('s.member_id','=',$memberid)
                       // ->where('s.date','<',$form_date)
                        ->where(DB::raw('DATE_FORMAT(s.date, "%Y-%m-%d")'),'<',$form_date)
                        ->orderBy('s.date','desc')
                        ->pluck(DB::raw('DATE_FORMAT(s.date, "%Y-%m-%d") as date'))->first();
                        

                        $lastsalary = DB::table('salary_updations as s')->where('s.member_id','=',$memberid)
                                            ->where(DB::raw('DATE_FORMAT(s.date, "%Y-%m-%d")'),'<',$form_date)
                                            ->orderBy('s.date','desc')
                                            ->pluck('s.basic_salary')->first();
                        
                        $basicsalry = DB::table('salary_updations as s')
                                            ->select("s.additional_amt as additions","s.increment_type_id")
                                            ->where('s.member_id','=',$memberid)
                                            ->where(DB::raw('DATE_FORMAT(s.date, "%Y-%m-%d")'),'=',$lastupdate)
                                            ->where(function($query) use ($lastupdate){
                                                $query->Where('s.increment_type_id','=',1)
                                                    ->orWhere('s.increment_type_id','=',4);
                                            })->get();

                        $companyid = DB::table('membership as m')
                                            ->select('c.company_id')
                                            ->leftjoin('company_branch as c','c.id','=','m.branch_id')
                                            ->where('m.id', '=', $memberid)->pluck('c.company_id')->first();
                        if($lastupdate!=''){
                            $lastsalary = $lastsalary=='' ? 0 : $lastsalary;
                            if(count($basicsalry)>0){
                                //return $basicsalry;
                                if($basicsalry[0]->increment_type_id==1){
                                    $salary = $lastsalary+$basicsalry[0]->additions;
                                }else{
                                    $salary = $lastsalary-$basicsalry[0]->additions;
                                }
                            }else{
                                $salary = DB::table('membership as m')->select('m.salary')->where('m.id', '=', $memberid)->pluck('m.salary')->first();
                            }
                          
                            //return $salary;
                        }else{
                            $salary = DB::table('membership as m')->select('m.salary')->where('m.id', '=', $memberid)->pluck('m.salary')->first();

                        }
                    }

                    $newincsalary = $salary;

                    if($inctype<=3){

                        $additional_amt = $subssal-$salary;

                        if($additional_amt>0){
                          
                            $newsalary = $subssal;

                            $salcount = DB::table('salary_updations as s')->where('member_id','=',$memberid)
                            ->where(DB::raw('DATE_FORMAT(s.date, "%Y-%m-%d")'),'=',$form_date)
                            ->where('increment_type_id','=',$inctype)
                            ->count();

                            if($salcount==0){
                                $insertdata = [];
                                $insertdata['member_id'] = $memberid;
                                $insertdata['date'] = $form_datefull;
                                $insertdata['company_id'] = $companyid;
                                $insertdata['increment_type_id'] = $inctype;
                                $insertdata['amount_type'] = 1;
                                $insertdata['basic_salary'] = $salary;
                                $insertdata['value'] = $additional_amt;
                                $insertdata['updated_salary'] = $newsalary;
                                $insertdata['additional_amt'] = $additional_amt;
                                $insertdata['summary'] = $others;
                                $insertdata['created_by'] = Auth::user()->id;
                                $insertdata['created_at'] = date('Y-m-d h:i:s');

                                $savesal = DB::table('salary_updations')->insert($insertdata);

                                if($inctype==1){
                                    $newincsalary = $newsalary;
                                }
                                
                            }
                        }

                    }else if($inctype==4){
                       
                        $dec_amt = $salary-$subssal;
                        
                      
                        if($dec_amt>0){
                            
                            
                            $newsalary = $subssal;

                            $newincsalary = $newsalary;

                            

                            $salcount = DB::table('salary_updations as s')->where('member_id','=',$memberid)
                            ->where(DB::raw('DATE_FORMAT(s.date, "%Y-%m-%d")'),'=',$form_date)
                            ->where('increment_type_id','=',$inctype)
                            ->count();

                           // return $salcount;

                            if($salcount==0){
                                $insertdata = [];
                                $insertdata['member_id'] = $memberid;
                                $insertdata['date'] = $form_datefull;
                                $insertdata['company_id'] = $companyid;
                                $insertdata['increment_type_id'] = $inctype;
                                $insertdata['amount_type'] = 1;
                                $insertdata['basic_salary'] = $salary;
                                $insertdata['value'] = $dec_amt;
                                $insertdata['updated_salary'] = $newsalary;
                                $insertdata['additional_amt'] = $dec_amt;
                                $insertdata['summary'] = $others;
                                $insertdata['created_by'] = Auth::user()->id;
                                $insertdata['created_at'] = date('Y-m-d h:i:s');

                                $savesal = DB::table('salary_updations')->insert($insertdata);
                                //dd($savesal);
                                
                            }
                        }

                    }else{
                        $additional_amt = $subssal-$salary;
                        //if($additional_amt>0){
                          
                            $newsalary = $subssal;

                            $salcount = DB::table('salary_updations as s')->where('member_id','=',$memberid)
                            ->where(DB::raw('DATE_FORMAT(s.date, "%Y-%m-%d")'),'=',$form_date)
                            ->where('increment_type_id','=',$inctype)
                            ->count();

                            if($salcount==0){
                                $insertdata = [];
                                $insertdata['member_id'] = $memberid;
                                $insertdata['date'] = $form_datefull;
                                $insertdata['company_id'] = $companyid;
                                $insertdata['increment_type_id'] = $inctype;
                                $insertdata['amount_type'] = 1;
                                $insertdata['basic_salary'] = $salary;
                                $insertdata['value'] = $additional_amt;
                                $insertdata['updated_salary'] = $newsalary;
                                $insertdata['additional_amt'] = $additional_amt;
                                $insertdata['summary'] = $others;
                                $insertdata['created_by'] = Auth::user()->id;
                                $insertdata['created_at'] = date('Y-m-d h:i:s');

                                $savesal = DB::table('salary_updations')->insert($insertdata);
                                
                            }
                        //}
                    }
                    if($salcount==0){
                        if($newincsalary==$newsalary){
                            DB::table('membership')->where('id','=',$memberid)->update(['current_salary' => $newincsalary, 'last_update' => $form_datefull]);
                        }else{
                            DB::table('membership')->where('id','=',$memberid)->update(['current_salary' => $newincsalary]);
                        }
                    }
                }
                DB::table('salary_updation_temp')->where('id','=',$salaryid)->delete();
            }

            $return_data = ['status' => 1 ,'message' => 'salary updated successfully, Redirecting to upload page...',
            'redirect_url' =>  URL::to('/'.app()->getLocale().'/latestsalary_upload/')];

        }else{
            $return_data = ['status' => 0 ,'message' => 'Invalid data'];
        }
        echo json_encode($return_data);
    }
}
