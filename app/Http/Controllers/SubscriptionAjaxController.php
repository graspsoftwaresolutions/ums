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
use Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Auth;


class SubscriptionAjaxController extends CommonController
{
    public function __construct() {
        ini_set('memory_limit', '-1');
        $this->membermonthendstatus_table = "membermonthendstatus1";
    }
    //Ajax Datatable Countries List //Users List 
    public function ajax_submember_list(Request $request){
		$companyid = $request->company_id;
        $status = $request->status;
        $sl=0;
		$columns[$sl++] = 'Name';
		$columns[$sl++] = 'membercode';
		$columns[$sl++] = 'nric';
        $columns[$sl++] = 'amount';
        if($status!='all'){
          $columns[$sl++] = 'statusId';
        }
		$columns[$sl++] = 'id';
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
        

		$commonqry = DB::table('mon_sub')->select('mon_sub.id','mon_sub.Date','mon_sub_company.MonthlySubscriptionId',
        'mon_sub_company.CompanyCode','company.company_name','company.id','mon_sub_member.Name','mon_sub_member.membercode','mon_sub_member.nric','mon_sub_member.amount','status.status_name as statusId','status.font_color','mon_sub_member.created_by','m.branch_id','m.member_number as member_number')
        ->join('mon_sub_company', 'mon_sub.id' ,'=','mon_sub_company.MonthlySubscriptionId')
        ->join('company','company.id','=','mon_sub_company.CompanyCode')
        ->join('mon_sub_member','mon_sub_company.id','=','mon_sub_member.MonthlySubscriptionCompanyId')
        ->leftjoin('status','mon_sub_member.StatusId','=','status.id')
        ->leftjoin('membership as m','m.id','=','mon_sub_member.MemberCode')
        ->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
        ->leftjoin('race as r','r.id','=','m.race_id')
        ->leftjoin('designation as d','d.id','=','m.designation_id');

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
        $commonqry = $commonqry->where('mon_sub_member.MonthlySubscriptionCompanyId','=',$companyid);
        
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
            $sub_mem = $commonqry->where('mon_sub_member.id','LIKE',"%{$search}%")
                        ->orWhere('m.id', 'LIKE',"%{$search}%")
					   ->orWhere('mon_sub_member.Name', 'LIKE',"%{$search}%")
					   ->orWhere('mon_sub_member.MemberCode', 'LIKE',"%{$search}%")
					   ->orWhere('mon_sub_member.NRIC', 'LIKE',"%{$search}%")
					   ->orWhere('m.member_number', 'LIKE',"%{$search}%")
					   ->orWhere('mon_sub_member.Amount', 'LIKE',"%{$search}%");
		    if( $limit != -1){
			   $sub_mem = $sub_mem->offset($start)
						->limit($limit);
		    }
		    $sub_mem = $sub_mem->orderBy($order,$dir)
					  ->get()->toArray();
			
			
            $totalFiltered =  $commonqry->where('mon_sub_member.id','LIKE',"%{$search}%")
                             ->orWhere('m.id', 'LIKE',"%{$search}%")
                            ->orWhere('mon_sub_member.Name', 'LIKE',"%{$search}%")
							    ->orWhere('mon_sub_member.Name', 'LIKE',"%{$search}%")
							   ->orWhere('mon_sub_member.MemberCode', 'LIKE',"%{$search}%")
                               ->orWhere('mon_sub_member.NRIC', 'LIKE',"%{$search}%")
                               ->orWhere('m.member_number', 'LIKE',"%{$search}%")
							   ->orWhere('mon_sub_member.Amount', 'LIKE',"%{$search}%")
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
                $autoid = $resultdata->id;
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
                $histry = $memberid!='' ? route('subscription.submember', [app()->getLocale(),$enc_id]) : '#';
                if($memberid!=''){
                    $actions .="<a style='float: left; margin-left: 10px;' title='History'  class='' href='$histry'><i class='material-icons' style='color:#FF69B4;'>history</i></a>";
                    $actions .="<a style='float: left; margin-left: 10px;' title='Member Transcation'  class='' href='$member_transfer_link'><i class='material-icons' style='color:#FFC107'>transfer_within_a_station</i></a>";
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

        $columns = array( 
            0 => 'Name', 
            1 => 'membercode', 
            2 => 'nric', 
            3 => 'amount', 
            4 => 'statusId', 
            5 => 'id',
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
        
        $data = $this->CommonAjaxReturn($sub_mem, 2, '',2); 
      
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
        );
		
		
		if($user_role == 'union'){
            $common_qry = DB::table('mon_sub_company as sc')->select('s.Date as date','c.company_name as company_name','sc.id as id')
                            ->leftjoin('mon_sub as s', 's.id' ,'=','sc.MonthlySubscriptionId')
                            ->leftjoin('company as c','c.id','=','sc.CompanyCode');
        }else if($user_role =='union-branch'){
            $unionbranchid = CommonHelper::getUnionBranchID($userid);
            $common_qry = DB::table('mon_sub_company as sc')->select('s.Date as date','c.company_name as company_name','sc.id as id')
                            ->join('company_branch as cb', 'sc.CompanyCode' ,'=','cb.company_id')
                            ->leftjoin('mon_sub as s', 's.id' ,'=','sc.MonthlySubscriptionId')
                            ->leftjoin('company as c','c.id','=','sc.CompanyCode')
							->where('cb.union_branch_id', '=' ,$unionbranchid)
							->GroupBY('sc.id');
        } 
        else if($user_role =='company'){
            $companyid = CommonHelper::getCompanyID($userid);
			$common_qry = DB::table('mon_sub_company as sc')->select('s.Date as date','c.company_name as company_name','sc.id as id')
                            ->join('company_branch as cb', 'sc.CompanyCode' ,'=','cb.company_id')
                            ->leftjoin('mon_sub as s', 's.id' ,'=','sc.MonthlySubscriptionId')
                            ->leftjoin('company as c','c.id','=','sc.CompanyCode')
							->where('cb.company_id', '=' ,$companyid)
							->GroupBY('sc.id');
        }
        else if($user_role =='company-branch'){
			$common_qry = DB::table('mon_sub_company as sc')->select('s.Date as date','c.company_name as company_name','sc.id as id')
                            ->join('company_branch as cb', 'sc.CompanyCode' ,'=','cb.company_id')
                            ->leftjoin('mon_sub as s', 's.id' ,'=','sc.MonthlySubscriptionId')
                            ->leftjoin('company as c','c.id','=','sc.CompanyCode')
							->where('cb.user_id', '=' ,$userid)
							->GroupBY('sc.id');
        } 
        $totalData = $common_qry->count();

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
			
			$company_qry = $common_qry;
			if( $limit != -1){
				$company_qry = $company_qry->offset($start)->limit($limit);
			}
			 
			$company_qry =  $company_qry->where(function($query) use ($search,$dateformat,$monthformat,$yearformat){
                                $query->orWhere('sc.id','LIKE',"%{$search}%")
                                ->orWhere('c.company_name', 'LIKE',"%{$search}%");
								if($monthformat!=''){
									$query->orWhere(DB::raw('month(s.`Date`)'), '=',"{$monthformat}");
								}
								if($yearformat!=''){
									$query->orWhere(DB::raw('year(s.`Date`)'), '=',"{$yearformat}");
								}
                                //->orWhere(DB::raw('year(s.Date)'), '=',"%{$yearformat}%")
								if($dateformat!=''){
									$query->orWhere('s.Date', 'LIKE',"%{$dateformat}%");
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
                $nestedData['month_year'] = date('M/Y',strtotime($company->date));
                $nestedData['company_name'] = $company->company_name;
                $company_enc_id = Crypt::encrypt($company->id);
                $editurl =  route('subscription.members', [app()->getLocale(),$company_enc_id]) ;
				//$editurl = URL::to('/')."/en/sub-company-members/".$company_enc_id;
                $nestedData['options'] = "<a style='float: left;' class='btn btn-small waves-effect waves-light cyan modal-trigger' href='".$editurl."'>View Members</a>";
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
        $dateformat = date('Y-m-01',strtotime('01-'.$fm_date[0].'-'.$fm_date[1]));
        if($entry_date!=""){
            $status_all = Status::where('status',1)->get();
            $status_data = [];
            $approval_status = DB::table('mon_sub_match_table as mt')
                                    ->select('mt.id as id','mt.match_name as match_name')
                                    ->get();
            $approval_data = [];
            foreach($status_all as $key => $value){
                $status_data['count'][$value->id] = CommonHelper::statusSubsMembersCount($value->id, $user_role, $user_id, $dateformat);
                $status_data['amount'][$value->id] = round(CommonHelper::statusMembersAmount($value->id, $user_role, $user_id, $dateformat), 0);
            }
            foreach($approval_status as $key => $value){
                $approval_data['count'][$value->id] = CommonHelper::statusSubsMatchCount($value->id, $user_role, $user_id, $dateformat);
            }
            $json_data = ['status_data' => $status_data, 'approval_data' => $approval_data, 'month_year_number' => strtotime($dateformat) , 'status' => 1];
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
            $sl++ => 'ar.status_id',
        );
        
       // DB::enableQueryLog();
		$commonqry = DB::table('arrear_entry as ar')->select('ar.id as arrearid','ar.nric','ar.arrear_date','ar.arrear_amount','cb.branch_name','c.company_name','s.status_name','m.member_number','m.name as membername','s.font_color')
                    ->leftjoin('membership as m','ar.membercode','=','m.id')
                    ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
                    ->leftjoin('company as c','cb.company_id','=','c.id')
                    ->leftjoin('status as s','m.status_id','=','s.id')
                    ->orderBy('ar.id','DESC');
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
			$sub_mem = $commonqry->where('m.id','LIKE',"%{$search}%")
                       ->orWhere('ar.nric', 'LIKE',"%{$search}%")
                       ->orWhere('ar.arrear_amount', 'LIKE',"%{$search}%")
                       ->orWhere('cb.branch_name', 'LIKE',"%{$search}%")
                       ->orWhere('c.company_name', 'LIKE',"%{$search}%")
                       ->orWhere('s.status_name', 'LIKE',"%{$search}%")
                       ->orWhere('m.member_number', 'LIKE',"%{$search}%")
                       ->orWhere('ar.arrear_date', 'LIKE',"%{$search}%")
                       ->orWhere('m.name', 'LIKE',"%{$search}%");
                       
		    if( $limit != -1){
			   $sub_mem = $sub_mem->offset($start)
						->limit($limit);
		    }
		    $sub_mem = $sub_mem->orderBy($order,$dir)
					  ->get()->toArray();
			
			
			$totalFiltered =  $commonqry->where('m.id','LIKE',"%{$search}%")
                                ->orWhere('ar.nric', 'LIKE',"%{$search}%")
                                ->orWhere('ar.arrear_amount', 'LIKE',"%{$search}%")
                                ->orWhere('cb.branch_name', 'LIKE',"%{$search}%")
                                ->orWhere('c.company_name', 'LIKE',"%{$search}%")
                                ->orWhere('s.status_name', 'LIKE',"%{$search}%")
                                ->orWhere('m.member_number', 'LIKE',"%{$search}%")
                                ->orWhere('ar.arrear_date', 'LIKE',"%{$search}%")
                                ->orWhere('m.name', 'LIKE',"%{$search}%")
							   ->count();
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
                $nestedData['status_id'] = $arrear->status_name;
                $font_color = $arrear->font_color;
                $nestedData['font_color'] = $font_color;

                $enc_id = Crypt::encrypt($arrear->arrearid);
                $delete =  route('subscription.arrearentrydelete', [app()->getLocale(),$enc_id]) ;
                               
                $edit = route('subscription.editarreatentry', [app()->getLocale(),$enc_id]);
                $actions ="<a style='float: left;' id='$edit' title='Edit' class='modal-trigger' href='$edit'><i class='material-icons' style='color:#2196f3'>edit</i></a>";
                $actions .="<a><form style='display:inline-block;' action='$delete' method='POST'>".method_field('DELETE').csrf_field();
                $actions .="<button  type='submit' class='' style='background:none;border:none;'  onclick='return ConfirmDeletion()'><i class='material-icons' style='color:red;'>delete</i></button> </form>";
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
}
