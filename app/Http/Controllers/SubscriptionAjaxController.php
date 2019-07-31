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


class SubscriptionAjaxController extends CommonController
{
    public function __construct() {
        ini_set('memory_limit', '-1');
    }
    //Ajax Datatable Countries List //Users List 
    public function ajax_submember_list(Request $request){
		$companyid = $request->company_id;
        $columns = array( 
            0 => 'Name', 
            1 => 'membercode', 
            2 => 'nric', 
            3 => 'amount', 
            4 => 'statusId', 
            5 => 'id',
        );
		$commonqry = DB::table('mon_sub')->select('mon_sub.id','mon_sub.Date','mon_sub_company.MonthlySubscriptionId',
        'mon_sub_company.CompanyCode','company.company_name','company.id','mon_sub_member.Name','mon_sub_member.membercode','mon_sub_member.nric','mon_sub_member.amount','mon_sub_member.statusId','mon_sub_member.created_by')
        ->join('mon_sub_company', 'mon_sub.id' ,'=','mon_sub_company.MonthlySubscriptionId')
        ->join('company','company.id','=','mon_sub_company.CompanyCode')
        ->join('mon_sub_member','mon_sub_company.id','=','mon_sub_member.MonthlySubscriptionCompanyId')
        ->where('mon_sub_member.MonthlySubscriptionCompanyId','=',$companyid);
		
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
        
        $data = $this->CommonAjaxReturn($sub_mem, 0, '',0); 
      
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );

        echo json_encode($json_data); 
    }

  
}
