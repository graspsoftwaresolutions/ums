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
use App\Model\MonSubCompanyAttach;
use App\Model\MonthlySubMemberMatch;
use App\Model\Membership;
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
use Log;
use Auth;



class SubscriptionController extends CommonController
{
	protected $limit;
    public function __construct() {
		$this->limit = 25;
        ini_set('memory_limit', -1);
        $this->middleware('auth');
        //$this->middleware('module:master');       
        $this->Company = new Company;
        $this->MonthlySubscription = new MonthlySubscription;
        $this->MonthlySubscriptionMember = new MonthlySubscriptionMember;
        $this->Status = new Status;
        $this->membermonthendstatus_table = "membermonthendstatus1";
        $this->ArrearEntry = new ArrearEntry;
        $bf_amount = Fee::where('fee_shortcode','=','BF')->pluck('fee_amount')->first();
        $ins_amount = Fee::where('fee_shortcode','=','INS')->pluck('fee_amount')->first();
        $this->bf_amount = $bf_amount=='' ? 0 : $bf_amount;
        $this->ins_amount = $ins_amount=='' ? 0 : $ins_amount;
    }
    //excel file download and upload it
    public function index() {
        $get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;
        $user_id = Auth::user()->id;
        $status_all = Status::where('status',1)->get();
        // foreach($status_all as $key => $value){
        //     //$status_id = $value->id;
        // }  
        // if($user_role=='union'){

        // }else if($user_role=='union-branch'){
        //     $union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id')->first();
        //     return $member_qry = DB::table('status as s')->join('membership as m','c.id','=','m.branch_id')
        //     return $member_qry = DB::table('company_branch as c')->join('membership as m','c.id','=','m.branch_id')
        //                     ->orderBy('m.id','DESC')
        //                     ->where([
        //                         ['c.union_branch_id','=',$union_branch_id]])->count();
        // }else if($user_role=='company'){

        // }else if($user_role=='company-branch'){

        // }

        
        
        $data['member_stat'] = $status_all;
        $data['approval_status'] = DB::table('mon_sub_match_table as mt')
                                    ->select('mt.id as id','mt.match_name as match_name')
                                    //->leftjoin('mon_sub_member_match as mm', 'mm.match_id' ,'=','mt.id')
                                    //->groupBy('mt.id')
                                    ->get();

        //isset($data['member_stat']) ? $data['member_stat'] : "";       
        return view('subscription.sub_fileupload.sub_listing')->with('data', $data);
    }   
    public function sub_company() {
        $userid = Auth::user()->id;
        $get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;
        $common_qry = DB::table('mon_sub_company as sc')->select('s.Date as date','c.company_name as company_name','sc.id as id')
        ->leftjoin('mon_sub as s', 's.id' ,'=','sc.MonthlySubscriptionId')
        ->leftjoin('company as c','c.id','=','sc.CompanyCode');
        if($user_role == 'union'){
            $company_list = $common_qry->get();
        }else if($user_role =='union-branch'){
            $unionbranchid = CommonHelper::getUnionBranchID($userid);
            $rawQuery = "SELECT s.Date as date,c.company_name as company_name,sc.id as id FROM `mon_sub_company` AS sc JOIN company_branch AS cb ON sc.CompanyCode = cb.company_id LEFT JOIN mon_sub AS s ON s.id = sc.MonthlySubscriptionId LEFT JOIN company AS c on c.id = sc.CompanyCode WHERE cb.union_branch_id = $unionbranchid GROUP BY sc.id";
            $company_list = DB::select( DB::raw($rawQuery));
        } 
        else if($user_role =='company'){
            $companyid = CommonHelper::getCompanyID($userid);
            $rawQuery = "SELECT s.Date as date,c.company_name as company_name,sc.id as id FROM `mon_sub_company` AS sc JOIN company_branch AS cb ON sc.CompanyCode = cb.company_id LEFT JOIN mon_sub AS s ON s.id = sc.MonthlySubscriptionId LEFT JOIN company AS c on c.id = sc.CompanyCode WHERE cb.company_id = $companyid GROUP BY sc.id";
            $company_list = DB::select( DB::raw($rawQuery));
        }
        else if($user_role =='company-branch'){
            $rawQuery = "SELECT s.Date as date,c.company_name as company_name,sc.id as id FROM `mon_sub_company` AS sc JOIN company_branch AS cb ON sc.CompanyCode = cb.company_id LEFT JOIN mon_sub AS s ON s.id = sc.MonthlySubscriptionId LEFT JOIN company AS c on c.id = sc.CompanyCode WHERE cb.user_id = $userid GROUP BY sc.id";
            $company_list = DB::select( DB::raw($rawQuery));
        } 
         
        $data['company_list'] = $company_list;
       
        
        return view('subscription.sub_company_list')->with('data', $data);
    }
	
	public function subscribeDownload(Request $request){
        $file_name = '';
        $fmmm_date = explode("/",$request->entry_date);  
        $file_name .= $fmmm_date[0];
        $file_name .= $fmmm_date[1];
        $file_name .= str_replace(' ', '-', CommonHelper::getComapnyName($request->sub_company)); 
        if($request->type!=1){
            $company_auto_id = '';
            if($request->type==2){
                $company_auto_id = $this->getCommonStatus($request->entry_date,$request->sub_company);
            }
            $request->request->add(['company_id' => $company_auto_id]);
            if($company_auto_id==''){
                $newtype =0;
            }else{
                $newtype =2;
            }
            //print_r($request->all());die;
            $s = new SubscriptionExport($newtype,$request->all());
            return Excel::download($s, $file_name.'.xlsx');
        }else{
            $rules = array(
                        'file' => 'required|mimes:xls,xlsx',
                    );
            $validator = Validator::make(Input::all(), $rules);
            if($validator->fails())
            {
                return back()->withErrors($validator);
            }
            else
            {
                if(Input::hasFile('file')){
                    $data['entry_date'] = $request->entry_date;
                    $data['sub_company'] = $request->sub_company;
                
                    $file = $request->file('file')->storeAs('subscription', $file_name.'.xlsx'  ,'local');
                    //$data = Excel::toArray(new SubscriptionImport, $file);
                    Excel::import(new SubscriptionImport($request->all()), $file);
                    //return back()->with('message', 'File Uploaded Successfully');
                    //echo 'upload success';
                    $company_auto_id = $this->getCommonStatus($request->entry_date, $request->sub_company);
                    if( $company_auto_id!=""){
                        $enc_id = Crypt::encrypt($company_auto_id);
                        return redirect(app()->getLocale().'/scan-subscription/'.$enc_id)->with('message', 'File Uploaded Successfully');
                    }else{
                        return redirect(app()->getLocale().'/home');
                    }
                    
                    //return $this->scanSubscriptions($request->entry_date,$request->sub_company);
                }
            }
        }
    }

    public function getSubscriptionStatus(Request $request){
        $entry_date = $request->entry_date;
        $sub_company = $request->sub_company;
        $get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;
        $user_id = Auth::user()->id;
        $data =[];

        $company_auto_id = '';
        $company_auto_id = $this->getCommonStatus($entry_date,$sub_company);
        $datearr = explode("/",$entry_date);  
        $monthname = $datearr[0];
        $year = $datearr[1];
        $full_date = date('Y-m-d',strtotime('01-'.$monthname.'-'.$year));
        
        if($company_auto_id!=''){
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
				$members_count = CommonHelper::statusSubsMembersCompanyCount($value->id, $user_role, $user_id,$company_auto_id,$full_date);
				$members_amount = round(CommonHelper::statusMembersCompanyAmount($value->id, $user_role, $user_id,$company_auto_id,$full_date), 0);
                $status_data['count'][$value->id] = $members_count;
                $status_data['amount'][$value->id] = $members_amount;
				$total_members_count += $members_count;
				$total_members_amount += $members_amount;
            }
            foreach($approval_status as $key => $value){
				$match_members_count = CommonHelper::statusSubsCompanyMatchCount($value->id, $user_role, $user_id,$company_auto_id,$full_date);
				$match_approval_members_count = CommonHelper::statusSubsCompanyMatchApprovalCount($value->id, $user_role, $user_id,$company_auto_id,1,$full_date);
				$match_pending_members_count = CommonHelper::statusSubsCompanyMatchApprovalCount($value->id, $user_role, $user_id,$company_auto_id,0,$full_date);
                $approval_data['count'][$value->id] = $match_members_count;
                $approval_data['approved'][$value->id] = $match_approval_members_count;
                $approval_data['pending'][$value->id] = $match_pending_members_count;
				$total_match_members_count += $match_members_count;
				$total_match_approval_members_count += $match_approval_members_count;
				$total_match_pending_members_count += $match_pending_members_count;
            }
			$sundry_count = CommonHelper::statusSubsCompanyMatchCount(2, $user_role, $user_id,$company_auto_id,$full_date);
			$sundry_amount = round(CommonHelper::statusSubsCompanyMatchAmount(2, $user_role, $user_id,$company_auto_id,$full_date), 0);
			$total_members_count += $sundry_count;
			$total_members_amount += $sundry_amount;
            $data =['status' =>1, 'status_data' => $status_data, 'approval_data' => $approval_data, 'sundry_amount' => $sundry_amount, 'sundry_count' => $sundry_count, 'total_members_amount' => $total_members_amount, 'total_members_count' => $total_members_count, 'total_match_members_count' => $total_match_members_count, 'total_match_approval_members_count' => $total_match_approval_members_count, 'total_match_pending_members_count' => $total_match_pending_members_count, 'company_auto_id' => $company_auto_id, 'month_year_number' => strtotime('01-'.$monthname.'-'.$year),'message'  => 'Data already uploaded for this company'];
        }else{
            $data =['status' =>0, 'status_data' => [], 'approval_data' => [] ,'message'  => 'No data found'];
        }

        return $data;
    }

    public function getCommonStatus($entry_date,$sub_company){
        $datearr = explode("/",$entry_date);  
        $monthname = $datearr[0];
        $year = $datearr[1];
        $form_date = date('Y-m-d',strtotime('01-'.$monthname.'-'.$year));
        $company_auto_id = '';
        $month_auto_id = '';

        $subscription_qry = MonthlySubscription::where('Date','=',$form_date);
        $subscription_count = $subscription_qry->count();
        if($subscription_count>0){
            $subscription_month = $subscription_qry->get();
            $month_auto_id = $subscription_month[0]->id;

            $subscription_company_qry = MonthlySubscriptionCompany::where('MonthlySubscriptionId','=',$month_auto_id)->where('CompanyCode',$sub_company);
            $subscription_company_count = $subscription_company_qry->count();
            if($subscription_company_count>0){
                $subscription_company =$subscription_company_qry->get();
                $company_auto_id = $subscription_company[0]->id;
            }
        }
        return $company_auto_id;
    }

    public function scanSubscriptions(Request $request){
        ini_set('memory_limit', -1);
		ini_set('max_execution_time', '300');
        $limit = 100;
        $company_auto_id = $request->company_auto_id;
        $start =  $request->start;
        $return_data = ['status' => 0 ,'message' => ''];
        if($company_auto_id!=""){
           
            $subscription_data = MonthlySubscriptionMember::select('id','NRIC as ICNO','NRIC as NRIC','Name','Amount','MonthlySubscriptionCompanyId')
                                                            ->where('MonthlySubscriptionCompanyId',$company_auto_id)
                                                            ->where('update_status','=',0)
                                                            ->offset(0)
                                                            ->limit($limit)
                                                            ->get();
            
            $row_count = count($subscription_data);
            $count =0;
			
            foreach($subscription_data as $subscription){
                $nric = $subscription->NRIC;
               
                $subscription_new_qry =  DB::table('membership as m')->where('m.new_ic', '=',$nric);
                
                $subscription_old_qry =  DB::table('membership as m')->where('m.old_ic', '=',$nric);
				
                $subscription_empid_qry =  DB::table('membership as m')->where('m.employee_id', '=',$nric);
                
                $up_sub_member =0;
                $match_count =  MonthlySubMemberMatch::where('mon_sub_member_id', '=',$subscription->id)
									->where(function($query) {
										  $query->where('match_id', 1)
											->orWhere('match_id', 8)
											->orWhere('match_id', 9)
											->orWhere('match_id', 2);
									  })->count();
                if($match_count>0){
                    $match_res =  MonthlySubMemberMatch::where('mon_sub_member_id', '=',$subscription->id)
										->where(function($query) {
										  $query->where('match_id', 1)
											->orWhere('match_id', 8)
											->orWhere('match_id', 9)
											->orWhere('match_id', 2);
									    })->get();
                    $matchid = $match_res[0]->id;
                    $subMemberMatch = MonthlySubMemberMatch::find($matchid);
                }else{
                    $subMemberMatch = new MonthlySubMemberMatch();
                }
                
                $subMemberMatch->mon_sub_member_id = $subscription->id;
                $subMemberMatch->created_by = Auth::user()->id;
                $subMemberMatch->created_on = date('Y-m-d');
               // DB::enableQueryLog();
				$insert_month_end = 0;
				$nric_matched = 0;
                if($subscription_new_qry->count() > 0){
                   
                    $memberdata = $subscription_new_qry->select('status_id','id','branch_id','name')->get();
                    $up_sub_member =1;
                    $subMemberMatch->match_id = 1;
					$nric_matched = 1;
                   
                }else if($subscription_old_qry->count() > 0){
                    
                    $up_sub_member =1;
                    $memberdata = $subscription_old_qry->select('status_id','id','branch_id','name')->get();
                    $subMemberMatch->match_id = 8;
					//$nric_matched = 1;
                }
				else if($subscription_empid_qry->count() > 0){
                    
                    $up_sub_member =1;
                    $memberdata = $subscription_empid_qry->select('status_id','id','branch_id','name')->get();
                    $subMemberMatch->match_id = 9;
                }
               
                else{
                    $subMemberMatch->match_id = 2;
                }
                $subMemberMatch->save();
              
                $upstatus=1;
                if($up_sub_member ==1){
					$insert_month_end = 1;
                    if(count($memberdata)>0){
                        $status_id = $memberdata[0]->status_id;
                        $member_code = $memberdata[0]->id;
                        $updata = ['MemberCode' => $member_code, 'StatusId' => $status_id, 'update_status' => 1];
                        //DB::enableQueryLog();
                        $savedata = MonthlySubscriptionMember::where('MonthlySubscriptionCompanyId',$company_auto_id)
                        ->where('NRIC',$nric)->update($updata);
                        $upstatus=0;
                    }
					
                    $company_code = CommonHelper::getcompanyidOfsubscribeCompanyid($subscription->MonthlySubscriptionCompanyId);
                    $member_company_id = CommonHelper::getcompanyidbyBranchid($memberdata[0]->branch_id);
					
					$match_company_status = $this->recursiveCompany($company_code,$member_company_id);
                
                    if(!$match_company_status){
						$subMemberMatch_one = MonthlySubMemberMatch::where('match_id','=',4)->where('mon_sub_member_id','=',$subscription->id)->first();
						
						if(empty($subMemberMatch_one)){
							$subMemberMatch_one = new MonthlySubMemberMatch();
						}
                        $subMemberMatch_one->match_id = 4;
						$subMemberMatch_one->mon_sub_member_id = $subscription->id;
						$subMemberMatch_one->created_by = Auth::user()->id;
						$subMemberMatch_one->created_on = date('Y-m-d');
						$subMemberMatch_one->save();
						$insert_month_end = 0;
                    }
					
                                       
                    if(strtoupper(trim($memberdata[0]->name)) != strtoupper($subscription->Name)){
						$subMemberMatch_two = MonthlySubMemberMatch::where('match_id','=',3)->where('mon_sub_member_id','=',$subscription->id)->first();
						if(empty($subMemberMatch_two)){
							$subMemberMatch_two = new MonthlySubMemberMatch();
						}
                        $subMemberMatch_two->match_id = 3;
						$subMemberMatch_two->mon_sub_member_id = $subscription->id;
						$subMemberMatch_two->created_by = Auth::user()->id;
						$subMemberMatch_two->created_on = date('Y-m-d');
						$subMemberMatch_two->save();
						$insert_month_end = 0;
                    }
					
					$cur_date = DB::table("mon_sub_company as mc")->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')->where('mc.id','=',$subscription->MonthlySubscriptionCompanyId)->pluck('Date')->first();
					$last_month = date('Y-m-01',strtotime($cur_date.' -1 Month'));

                    $old_subscription_count = DB::table("mon_sub_member as mm")
							->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
							->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
							->where('mm.MemberCode','=',$member_code)
							->where('ms.Date','=',$last_month)
                            ->orderBY('mm.MonthlySubscriptionCompanyId','desc')
                            ->count();
							
					$member_doj = DB::table("membership as m")->where('m.id','=',$member_code)->pluck('doj')->first();
					$member_month_yr = date('m-Y',strtotime($member_doj));
					$cur_month_yr = date('m-Y',strtotime($cur_date));
					//dd($member_month_yr);
					//dd($old_subscription_count);
					if($member_month_yr!=$cur_month_yr){
						if($old_subscription_count>0){
							$old_subscription_amount = DB::table("mon_sub_member as mm")
								->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
								->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
								->where('mm.MemberCode','=',$member_code)
								->where('ms.Date','=',$last_month)
								->orderBY('mm.MonthlySubscriptionCompanyId','desc')
								->pluck('Amount')
								->first();
							//dd($old_subscription_amount);
							if($old_subscription_amount!=$subscription->Amount){
								$subMemberMatch_three = MonthlySubMemberMatch::where('match_id','=',5)->where('mon_sub_member_id','=',$subscription->id)->first();
								if(empty($subMemberMatch_three)){
									$subMemberMatch_three = new MonthlySubMemberMatch();
								}
								$subMemberMatch_three->match_id = 5;
								$subMemberMatch_three->mon_sub_member_id = $subscription->id;
								$subMemberMatch_three->created_by = Auth::user()->id;
								$subMemberMatch_three->created_on = date('Y-m-d');
								$subMemberMatch_three->save();
								$insert_month_end = 0;
							}
						}else{
							$subMemberMatch_four = MonthlySubMemberMatch::where('match_id','=',10)->where('mon_sub_member_id','=',$subscription->id)->first();
							if(empty($subMemberMatch_four)){
								$subMemberMatch_four = new MonthlySubMemberMatch();
							}
							
							$subMemberMatch_four->match_id = 10;
							$subMemberMatch_four->mon_sub_member_id = $subscription->id;
							$subMemberMatch_four->created_by = Auth::user()->id;
							$subMemberMatch_four->created_on = date('Y-m-d');
							$subMemberMatch_four->save();
							$insert_month_end = 0;
						}
					}
			   
                    
                    if($memberdata[0]->status_id ==3){
						$subMemberMatch_five = MonthlySubMemberMatch::where('match_id','=',6)->where('mon_sub_member_id','=',$subscription->id)->first();
						if(empty($subMemberMatch_five)){
							$subMemberMatch_five = new MonthlySubMemberMatch();
						}
                        $subMemberMatch_five->match_id = 6;
						$subMemberMatch_five->mon_sub_member_id = $subscription->id;
						$subMemberMatch_five->created_by = Auth::user()->id;
						$subMemberMatch_five->created_on = date('Y-m-d');
						$subMemberMatch_five->save();
						$insert_month_end = 0;
                    }else if($memberdata[0]->status_id ==4){
						$subMemberMatch_six = MonthlySubMemberMatch::where('match_id','=',7)->where('mon_sub_member_id','=',$subscription->id)->first();
						if(empty($subMemberMatch_six)){
							$subMemberMatch_six = new MonthlySubMemberMatch();
						}
                        $subMemberMatch_six->match_id = 7;
						$subMemberMatch_six->mon_sub_member_id = $subscription->id;
						$subMemberMatch_six->created_by = Auth::user()->id;
						$subMemberMatch_six->created_on = date('Y-m-d');
						$subMemberMatch_six->save();
						$insert_month_end = 0;
                    }
					
					if($insert_month_end==1 && $nric_matched==1){
						
						$total_subs_obj = DB::table('mon_sub_member')->select(DB::raw('IFNULL(sum("Amount"),0) as amount'))
						->where('MemberCode', '=', $member_code)
						->first();
						$total_subs = $total_subs_obj->amount;
						
						$total_count = DB::table('mon_sub_member')
						->where('MemberCode', '=', $member_code)
						->count();
						
						$paid_bf = $total_subs-($total_count*$this->bf_amount);
						
							
						$to = Carbon::createFromFormat('Y-m-d H:s:i', $member_doj.' 3:30:34');
						$from = Carbon::createFromFormat('Y-m-d H:s:i', $cur_date.' 9:30:34');
						$diff_in_months = $to->diffInMonths($from);
						
						$bf_due = ($diff_in_months-$total_count)*$this->bf_amount;
						$ins_due = ($diff_in_months-$total_count)*$this->ins_amount;
						$total_subs_to_paid = $diff_in_months==0 ? $subscription->Amount : ($diff_in_months*$subscription->Amount);
						$total_pending = $total_subs_to_paid - $total_subs;
						
						$mont_count = DB::table($this->membermonthendstatus_table)->where('StatusMonth', '=', $cur_date)->where('MEMBER_CODE', '=', $member_code)->count();
						//dd($member_code);
						$monthend_data = [
												'StatusMonth' => $cur_date, 
												'MEMBER_CODE' => $member_code,
												'SUBSCRIPTION_AMOUNT' => $subscription->Amount,
												'BF_AMOUNT' => $this->bf_amount,
												'LASTPAYMENTDATE' => $old_subscription_count>0 ? $last_month : NULL,
												'TOTALSUBCRP_AMOUNT' => $total_subs,
												'TOTALBF_AMOUNT' => $total_count*$this->bf_amount,
												'TOTAL_MONTHS' => $diff_in_months,
												//'ENTRYMODE' => 0,
												//'DEFAULTINGMONTHS' => 0,
												'TOTALMONTHSDUE' => $diff_in_months==0 ? 0 : $diff_in_months-$total_count,
												'TOTALMONTHSPAID' => $total_count,
												'SUBSCRIPTIONDUE' => $total_pending,
												'BFDUE' => $bf_due,
												'ACCSUBSCRIPTION' => $subscription->Amount,
												'ACCBF' => $this->bf_amount,
												//'ACCBENEFIT' => 0,
												//'CURRENT_YDTBF' => 0,
												//'CURRENT_YDTSUBSCRIPTION' => 0,
												'STATUS_CODE' => $memberdata[0]->status_id,
												'RESIGNED' => $memberdata[0]->status_id==4 ? 1 : 0,
												'ENTRY_DATE' => date('Y-m-d'),
												'ENTRY_TIME' => date('h:i:s'),
												'STRUCKOFF' => $memberdata[0]->status_id==3 ? 1 : 0,
												'INSURANCE_AMOUNT' => $this->ins_amount,
												'TOTALINSURANCE_AMOUNT' => $diff_in_months==0 ? $this->ins_amount : ($diff_in_months*$this->ins_amount),
												'TOTALMONTHSCONTRIBUTION' => $total_count,
												'INSURANCEDUE' => $ins_due,
												'ACCINSURANCE' => $this->ins_amount,
												//'CURRENT_YDTINSURANCE' => 0,
											];
						if($mont_count>0){
							DB::table($this->membermonthendstatus_table)->where('StatusMonth', $cur_date)->where('MEMBER_CODE', $member_code)->update($monthend_data);
						}else{
							DB::table($this->membermonthendstatus_table)->insert($monthend_data);
						}
						
					}
                }

               
                if( $upstatus==1){
                    $updata = ['update_status' => 1];
                    $savedata = MonthlySubscriptionMember::where('id',$subscription->id)->update($updata);
                }
                
                $count++;
            }
            //Log::info($start.'count-'.$count);
            //Log::Error($start.'rowcount-'.$row_count);
            $enc_id = Crypt::encrypt($company_auto_id);
            $return_data = ['status' => 1 ,'message' => 'status and member code updated successfully, Redirecting to subscription details...','redirect_url' =>  URL::to('/'.app()->getLocale().'/sub-company-members/'.$enc_id)];
        }else{
            $return_data = ['status' => 0 ,'message' => 'Invalid company id'];
        }
       echo json_encode($return_data);
    }
	
	public function recursiveCompany($company_code,$member_company_id){
		//Print out the number.
		//If the number is less or equal to 50.
		if($company_code == $member_company_id){
			//Call the function again. Increment number by one.
			return true;
		}else{
			$company_data = Company::find($member_company_id);
			if($company_code == $company_data->head_of_company){
				return true;
			}else{
				return false;
			}
		}
	}

    public function submember($lang,$id)
    {
        $id = Crypt::decrypt($id);
      //return  $font_color = $fontcolor;
        
       //  $year =2019;
       // $month =8;

       // return $id;
       
       $data['member_subscription_details'] = DB::table('mon_sub_member as sm')->select('m.id as memberid','m.doj as doj','m.name as membername','m.id as MemberCode','sm.Amount','status.status_name','s.Date','status.font_color','m.member_number')
                                            ->leftjoin('membership as m','m.id','=','sm.MemberCode') 
                                            ->leftjoin('mon_sub_company as sc','sm.MonthlySubscriptionCompanyId','=','sc.id')
                                            ->leftjoin('mon_sub as s','sc.MonthlySubscriptionId','=','s.id') 
                                            ->leftjoin('status as status','status.id','=','sm.StatusId')
                                            //->where('s.Date','=',date('Y-m-01'))
                                            ->orderBY('s.Date','desc')
                                            ->where('m.id','=',$id)
                                            ->first();

        DB::enableQueryLog();
        $data['member_subscription_list'] = DB::table('mon_sub_member as sm')->select('sm.Amount as Amount','s.Date as Date','status.status_name as status_name','status.font_color','m.member_number')
                                            ->leftjoin('mon_sub_company as sc', 'sc.id' ,'=','sm.MonthlySubscriptionCompanyId')
                                            ->leftjoin('mon_sub as s','s.id','=','sc.MonthlySubscriptionId')
                                            ->leftjoin('status as status','status.id','=','sm.StatusId')
                                            ->leftjoin('membership as m','m.id','=','sm.MemberCode')
                                            ->where('m.id','=',$id)
                                            //->groupBY('s.id')
                                            ->get(); 
                                            
           
        return view('subscription.sub_member')->with('data',$data);
          
    }

    public function memberfilter(Request $request)
    { 
        $member_code = $request->id;   
        $memberid = $request->memberid;

        $data['member_subscription_details'] = DB::table('mon_sub_member as sm')->select('m.id as memberid','m.doj as doj','m.name as membername','m.id as MemberCode','sm.Amount','status.status_name','status.font_color','s.Date','m.member_number')
            ->leftjoin('membership as m','m.id','=','sm.MemberCode') 
            ->leftjoin('mon_sub_company as sc','sm.MonthlySubscriptionCompanyId','=','sc.id')
            ->leftjoin('mon_sub as s','sc.MonthlySubscriptionId','=','s.id') 
            ->leftjoin('status as status','status.id','=','sm.StatusId')
            ->orderBY('s.Date','desc')
            ->where('m.id','=',$memberid)->first();

        //return $memberid;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $data['member_subscription_list']=$data['member_subscription_details'];
        if($from_date!=""  && $to_date!=""){
           // var_dump("scvgdffd");
           // exit;
            $fmmm_date = explode("/",$from_date);
            $fmdate = $fmmm_date[2]."-".$fmmm_date[1]."-".$fmmm_date[0];
            $from = date('Y-m-d', strtotime($fmdate));

            $fmmm_date = explode("/",$to_date);
            $todate = $fmmm_date[2]."-".$fmmm_date[1]."-".$fmmm_date[0];
            $to = date('Y-m-d', strtotime($todate));

            
            DB::enableQueryLog();
            $data['member_subscription_list'] = DB::table('mon_sub_member as sm')->select('sm.Amount as Amount','s.Date as Date','status.status_name as status_name','m.member_number')
                                ->leftjoin('mon_sub_company as sc', 'sc.id' ,'=','sm.MonthlySubscriptionCompanyId')
                                ->leftjoin('mon_sub as s','s.id','=','sc.MonthlySubscriptionId')
                                ->leftjoin('status as status','status.id','=','sm.StatusId')
                                ->leftjoin('membership as m','m.id','=','sm.MemberCode')
                                ->where('s.Date','>=', $from)
                                ->where('s.Date', '<=', $to)
                                ->where('sm.MemberCode','=',$memberid)
                                //->groupBY('s.id')
                                ->get();         
        }else{
            $data['member_subscription_list'] = $data['member_subscription_details'];
        }
        //var_dump($data['member_subscription_list']);
       // exit;
        return view('subscription.sub_member')->with('data',$data);
      
    }
    
    public function viewScanSubscriptions($lang,$id)
    {
        $company_auto_id = Crypt::decrypt($id);
        $data['company_auto_id'] = $company_auto_id;
        $memberrowcount = MonthlySubscriptionMember::where('MonthlySubscriptionCompanyId','=',$company_auto_id)->where('update_status','=',0)->count();
        $data['row_count'] = $memberrowcount;
        return view('subscription.scan-subcription')->with('data',$data);
    }

    public function companyMembers($lang,$id){
        $company_auto_id = Crypt::decrypt($id);
        $data['company_auto_id'] = $company_auto_id;
        $status_all = Status::where('status','=',1)->get();   
		DB::enableQueryLog();
      /*  $data['company_subscription_list'] = DB::table('mon_sub_member')->select('*')
                                         ->leftjoin('mon_sub_company', 'mon_sub_company.id' ,'=','mon_sub_member.MonthlySubscriptionCompanyId')
                                       ->leftjoin('mon_sub','mon_sub.id','=','mon_sub_company.MonthlySubscriptionId')
                                        ->where('mon_sub_company.id','=',$company_auto_id)
                                         ->get();
										 $queries = DB::getQueryLog();
                                    dd($queries); */
        $data['company_subscription_list'] = DB::table('mon_sub')->select('*')
                                           ->join('mon_sub_company', 'mon_sub.id' ,'=','mon_sub_company.MonthlySubscriptionId')
                                           ->join('company','company.id','=','mon_sub_company.CompanyCode')
                                           ->where('mon_sub_company.id','=',$company_auto_id)
                                           ->get();
										   
       $data['company_subscription_list'] = isset($data['company_subscription_list']) ? $data['company_subscription_list'] : [];      
      // dd($data['company_subscription_list']);
       $data['tot_count'] = MonthlySubscriptionMember::where('MonthlySubscriptionCompanyId','=',$company_auto_id)->count();
       $data['non_updated_rows'] = MonthlySubscriptionMember::where('MonthlySubscriptionCompanyId','=',$company_auto_id)->where('update_status','=',0)->count();
       $data['designation_view'] = Designation::where('status','=','1')->get();  
       $data['race_view'] = Race::where('status','=','1')->get();
       $data['member_stat'] = $status_all;
       $data['approval_status'] = DB::table('mon_sub_match_table as mt')
                                    ->select('mt.id as id','mt.match_name as match_name')
                                    ->get();
      // $data['member_stat'] = isset($data['member_stat']) ? $data['member_stat'] : [];   
	  // return  $data;
       return view('subscription.company_members')->with('data', $data);
    }

    public function pendingMembers($lang,$id)
    {
        $company_auto_id = Crypt::decrypt($id);
        $data['company_auto_id'] = $company_auto_id;


            $data['pending_members_list'] = DB::table('mon_sub_member as msm')->select('*')
                                           ->join('mon_sub_company as msc', 'msm.MonthlySubscriptionCompanyId' ,'=','msc.id')
                                           ->join('company','company.id','=','msc.CompanyCode')
                                           ->where('msc.id','=',$company_auto_id)
                                           ->where('msm.update_status','=','0')
                                           ->get();

        
        return view('subscription.pending_members')->with('data', $data);
    }

    public function downloadSubscription($lang){
        $s = new SubscriptionExport(0,[]);
        return Excel::download($s, 'subscription.xlsx');
    }
    //Subcription Payment 
    public function subPayment()
    {
        return view('subscription.subscription_payment');
    }
    public function subPaymentHistory()
    {
        $user_id = Auth::user()->id;
        $member_id = Membership::where('user_id','=',$user_id)->first();
        $id = $member_id->id;
      
            $data['member_subscription_details'] = DB::table('mon_sub_member as sm')->select('m.id as memberid','m.doj as doj','m.name as membername','m.id as MemberCode','sm.Amount','status.status_name','s.Date')
            ->leftjoin('membership as m','m.id','=','sm.MemberCode') 
            ->leftjoin('mon_sub_company as sc','sm.MonthlySubscriptionCompanyId','=','sc.id')
            ->leftjoin('mon_sub as s','sc.MonthlySubscriptionId','=','s.id') 
            ->leftjoin('status as status','status.id','=','sm.StatusId')
            //->where('s.Date','=',date('Y-m-01'))
            ->orderBY('s.Date','desc')
            ->where('m.id','=',$id)
            ->get();

            if(count($data['member_subscription_details']) == 0)
            {
                 return view('subscription.subscription_payment')->with('message','No transaction Done');
            }
 
            DB::enableQueryLog();
            $data['member_subscription_list'] = DB::table('mon_sub_member as sm')->select('sm.Amount as Amount','s.Date as Date','status.status_name as status_name')
                    ->leftjoin('mon_sub_company as sc', 'sc.id' ,'=','sm.MonthlySubscriptionCompanyId')
                    ->leftjoin('mon_sub as s','s.id','=','sc.MonthlySubscriptionId')
                    ->leftjoin('status as status','status.id','=','sm.StatusId')
                    ->leftjoin('membership as m','m.id','=','sm.MemberCode')
                    ->where('m.id','=',$id)
                    ->get();  
            return view('subscription.subscription_paymenthistory')->with('data',$data);
    }
	
	public function memberHistory($lang,$id){
		$id = Crypt::decrypt($id);
    
        $data['member_details'] = DB::table('membership as m')->select('m.id as memberid','m.doj as doj','m.name as membername','m.id as MemberCode','m.new_ic as new_ic','m.old_ic as old_ic','d.designation_name as membertype','p.person_title as persontitle','cb.branch_name','c.company_name','m.doj','s.status_name','m.member_number','s.font_color')
											->leftjoin('designation as d','d.id','=','m.designation_id')
											->leftjoin('persontitle as p','p.id','=','m.member_title_id')
											->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
											->leftjoin('company as c','c.id','=','cb.company_id')
											->leftjoin('race as r','r.id','=','m.race_id')
											->leftjoin('status as s','s.id','=','m.status_id')
                                            //->leftjoin('mon_sub_company as sc','sm.MonthlySubscriptionCompanyId','=','sc.id')
                                            //->leftjoin('mon_sub as s','sc.MonthlySubscriptionId','=','s.id') 
                                            ->where('m.id','=',$id)
                                            ->first();

        $data['last_month_record'] = DB::table($this->membermonthendstatus_table.' as ms')
                                            ->where('ms.MEMBER_CODE','=',$id)
                                            ->OrderBy('ms.StatusMonth','desc')
                                            ->first();
		$old_member_id = Membership::where('id','=',$id)->pluck('old_member_number')->first();
		
		$data['current_member_history'] = DB::table($this->membermonthendstatus_table.' as ms')->select('ms.id as id','ms.id as memberid','ms.StatusMonth',
											'ms.SUBSCRIPTION_AMOUNT','ms.BF_AMOUNT','ms.INSURANCE_AMOUNT','ms.TOTAL_MONTHS','ms.LASTPAYMENTDATE','ms.TOTALMONTHSPAID','ms.SUBSCRIPTIONDUE','ms.ACCSUBSCRIPTION','ms.ACCBF','ms.ACCINSURANCE','s.font_color','m.name','m.member_number as member_number')
											->leftjoin('membership as m', 'm.id' ,'=','ms.MEMBER_CODE')
											->leftjoin('status as s','s.id','=','ms.STATUS_CODE')
											->where('ms.MEMBER_CODE','=',$id)
											->offset(0)
											->limit($this->limit)
                                            ->get();
		
		if($old_member_id!=""){
			$data['previous_member_history'] = DB::table($this->membermonthendstatus_table.' as ms')->select('ms.id as id','ms.id as memberid','ms.StatusMonth',
											'ms.SUBSCRIPTION_AMOUNT','ms.BF_AMOUNT','ms.INSURANCE_AMOUNT','ms.TOTAL_MONTHS','ms.LASTPAYMENTDATE','ms.TOTALMONTHSPAID','ms.SUBSCRIPTIONDUE','ms.ACCSUBSCRIPTION','ms.ACCBF','ms.ACCINSURANCE','s.font_color','m.name','m.member_number as member_number')
											->leftjoin('membership as m', 'm.id' ,'=','ms.MEMBER_CODE')
											->leftjoin('status as s','s.id','=','ms.STATUS_CODE')
											->where('ms.MEMBER_CODE','=',$old_member_id)
											->offset(0)
											->limit($this->limit)
                                            ->get();
		}else{
			$data['previous_member_history'] = [];
		}
                                            
        $data['data_limit'] = $this->limit;
        $data['old_member_id'] = $old_member_id;
        $data['member_id'] = $id;
        return view('subscription.member_history')->with('data',$data);
    }
    //Arrear 
    public function arrearentryIndex()
    {
        //echo "hiii"; die;
        return view('subscription.arrear_entry');
    }
    public function arrearentryAdd()
    {
        return view('subscription.add_arrearentry');
    }
    public function getNricMemberlist(Request $request)
    {
        $searchkey = $request->input('searchkey');
        $search = $request->input('query');
        $res['suggestions'] = DB::table('membership as m')->select(DB::raw('CONCAT(m.name, " - ", m.member_number, "-" , m.new_ic) AS value'),'m.id as number','m.branch_id as branch_id','m.member_number')

                            ->where(function($query) use ($search){
                                $query->orWhere('m.id','LIKE',"%{$search}%")
                                    ->orWhere('m.old_ic', 'LIKE',"%{$search}%")
                                    ->orWhere('m.new_ic', 'LIKE',"%{$search}%")
                                    ->orWhere('m.member_number', 'LIKE',"%{$search}%")
                                    ->orWhere('m.name', 'LIKE',"%{$search}%");
                                 })->limit(25)
                            ->get();   
         return response()->json($res);
    }
    public function getMembersListValues(Request $request)
	{
		DB::connection()->enableQueryLog();
		$member_id = $request->member_id;
		
		$res = DB::table('membership as m')->select(DB::raw("if(m.new_ic > 0  ,m.new_ic,m.old_ic) as nric"),'m.member_number','m.id as memberid','m.name as membername','cb.branch_name','c.company_name','s.status_name','s.id as statusid','cb.id as companybranchid','c.id as companyid')
							->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
							->leftjoin('company as c','c.id','=','cb.company_id')
                            ->leftjoin('status as s','s.id','=','m.status_id')
							->where('m.member_number','=',$member_id)
							->first();
			// $queries = DB::getQueryLog();
			// dd($queries);
			return response()->json($res);
    }
    public function arrearentrySave(Request $request)
    {
        $request->validate([
            'nric'=>'required',
            'arrear_date'=>'required',
            'arrear_amount'=>'required',
        ],
        [
            'nric.required'=>'please enter NRIC',
            'arrear_date.required'=>'please choose date',
            'arrear_amount.required'=>'please enter Amount',
        ]);
        $data = $request->all();  
        // echo "<pre>";
        // print_r($data); die;
         
        $data['arrear_date'] = CommonHelper::convert_date_database($request->arrear_date);
        $defdaultLang = app()->getLocale();

        $saveArrearEntry = $this->ArrearEntry->saveArreardata($data);
        
        if($saveArrearEntry == true)
        {
            if(!empty($request->id))
            {
                return  redirect($defdaultLang.'/subscription.arrearentry')->with('message','Entry Updated Succesfully');
            }
            else
            {
                return  redirect($defdaultLang.'/subscription.arrearentry')->with('message','Entry Added Succesfully');
            }
        }
        }
    public function arrearentryEdit($lang,$id)
    {
        $id = Crypt::decrypt($id);
       
         $data =  DB::table('arrear_entry as ar')->select('m.id as memberid','c.id as companyid','cb.id as companybranchid','s.id as statusid','ar.id as arrearid','ar.nric',DB::raw("DATE_FORMAT(ar.arrear_date,'%d/%b/%Y') as arrear_date"),'ar.arrear_amount','cb.branch_name','c.company_name','s.status_name','m.member_number','m.name as membername','s.font_color')
        ->leftjoin('membership as m','ar.membercode','=','m.id')
        ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
        ->leftjoin('company as c','cb.company_id','=','c.id')
        ->leftjoin('status as s','m.status_id','=','s.id')
        ->where('ar.id','=',$id)->first();

        return view('subscription.edit_arrearentry')->with('data',$data);
    }
    public function arrearentrydestroy($lang,$id)
    {
        $id = Crypt::decrypt($id);
        $ArrearEntry = ArrearEntry::find($id);
        $ArrearEntry->delete();
        return redirect($lang.'/subscription.arrearentry')->with('message','Arrear Entry Details Deleted Successfully!!');
    }
	
	public function statusCountView($lang, Request $request){
        $member_status = $request->input('member_status');
        $approval_status = $request->input('approval_status');
        $date = $request->input('date');
        $company_id = $request->input('company_id');
        $get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;
        $user_id = Auth::user()->id;
		$defaultdate = date('Y-m-01',$date);
		$data['data_limit'] = $this->limit;
		$data['company_id'] = $company_id;
        $data['company_view'] = DB::table('mon_sub_company as mc')->select('c.id as cid','mc.id as id','c.company_name as company_name')
                                ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                                ->leftjoin('company as c','mc.CompanyCode','=','c.id')
                                ->where('ms.Date', '=', date('Y-m-01',$date))->get();
		$data['member_status'] = Status::where('status',1)->get();
		$data['approval_status'] = DB::table('mon_sub_match_table as mt')
                                    ->select('mt.id as id','mt.match_name as match_name')
                                    ->get();

		$data['filter_date'] = strtotime(date('Y-m-01',strtotime($defaultdate)));
        if($member_status!=""){
			$cond ='';
			if(isset($company_id) && $company_id!=''){
				$cond =" AND m.MonthlySubscriptionCompanyId = '$company_id'";
			}
			$members_data = DB::select(DB::raw('select member.name as member_name, member.member_number as member_number,m.Amount as Amount, c.company_name as company_name, member.new_ic as ic,"0" as due,s.status_name as status_name, `member`.`id` as memberid, m.id as sub_member_id,m.Name as up_member_name,m.NRIC as up_nric from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` left join membership as member on `member`.`id` = `m`.`MemberCode` left join company as c on `c`.`id` = `sc`.`CompanyCode` left join status as s on `s`.`id` = `m`.`StatusId` where m.StatusId="'.$member_status.'" '.$cond.' AND `sm`.`Date`="'.$defaultdate.'" LIMIT '.$data['data_limit']));
            $data['member'] = $members_data;
            $data['status_type'] = 1;
            $data['status'] = $member_status;
        }
        if($approval_status!=""){
			$cond ='';
			if(isset($company_id) && $company_id!=''){
				$cond =" AND m.MonthlySubscriptionCompanyId = '$company_id'";
			}
           $members_data = DB::select(DB::raw('SELECT member.name as member_name, member.member_number as member_number,m.Amount as Amount, c.company_name as company_name, member.new_ic as ic,"0" as due,s.status_name as status_name, `member`.`id` as memberid, mm.mon_sub_member_id as sub_member_id,m.Name as up_member_name,m.NRIC as up_nric FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as sc on sc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` left join membership as member on `member`.`id` = `m`.`MemberCode`  left join company as c on `c`.`id` = `sc`.`CompanyCode` left join status as s on `s`.`id` = `m`.`StatusId` WHERE mm.match_id="'.$approval_status.'" '.$cond.' AND `sm`.`Date`="'.$defaultdate.'" LIMIT '.$data['data_limit']));
           $data['member'] = $members_data;
           $data['status_type'] = 2;
           $data['status'] = $approval_status;
        }
		if($member_status==0 && $approval_status==""){
			$cond ='';
			if(isset($company_id) && $company_id!=''){
				$cond =" AND m.MonthlySubscriptionCompanyId = '$company_id'";
			}
           $members_data = DB::select(DB::raw('SELECT member.name as member_name, member.member_number as member_number,m.Amount as Amount, c.company_name as company_name, member.new_ic as ic,"0" as due,s.status_name as status_name, `member`.`id` as memberid, mm.mon_sub_member_id as sub_member_id,m.Name as up_member_name,m.NRIC as up_nric FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as sc on sc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` left join membership as member on `member`.`id` = `m`.`MemberCode` left join company as c on `c`.`id` = `sc`.`CompanyCode` left join status as s on `s`.`id` = `m`.`StatusId` WHERE mm.match_id="2" '.$cond.' AND `sm`.`Date`="'.$defaultdate.'" LIMIT '.$data['data_limit']));
           $data['member'] = $members_data;
           $data['status_type'] = 3;
           $data['status'] = 0;
        }
		if($member_status=='all' || $approval_status=="all"){
			$cond ='1=1';
			if(isset($company_id) && $company_id!=''){
				$cond =" m.MonthlySubscriptionCompanyId = '$company_id'";
			}
			$members_data = DB::select(DB::raw('select member.name as member_name, member.member_number as member_number,m.Amount as Amount, c.company_name as company_name, member.new_ic as ic,"0" as due,s.status_name as status_name, `member`.`id` as memberid, m.id as sub_member_id,m.Name as up_member_name,m.NRIC as up_nric from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` left join membership as member on `member`.`id` = `m`.`MemberCode` left join company as c on `c`.`id` = `sc`.`CompanyCode` left join status as s on `s`.`id` = `m`.`StatusId` where '.$cond.' AND `sm`.`Date`="'.$defaultdate.'" LIMIT '.$data['data_limit']));
           $data['member'] = $members_data;
           $data['status_type'] = 4;
           $data['status'] = 0;
		}
		return view('subscription.member_status')->with('data',$data);
    }
	
	public function saveApproval($lang, Request $request){
        $sub_member_id = $request->input('sub_member_id');
		$match_data = DB::table('mon_sub_member_match')->where('mon_sub_member_id','=',$sub_member_id)->get();
		$total_match_count = 0;
		$member_code = '';
		$member_status = '';
		$member_match = '';
		$approval_masg = 'Updated Succesfully';
		foreach($match_data as $mkey => $match){
			$match_id = $match->match_id;
			$approval_status=0;
			if($match_id==1){
				$nric_approve = $request->input('nric_approve');
				$approval_status= isset($nric_approve) ? 1 : 0;
				DB::table('mon_sub_member_match')->where('id', '=', $match->id)->where('match_id','=' ,$match_id)->update(['approval_status' => $approval_status, 'description' => 'NRIC Matched', 'updated_by' => Auth::user()->id]);
			}
			if($match_id==2){
				$member_match = $match_id;
				$nric_not_approve = $request->input('nric_not_approve');
				$member_auto_id = $request->input('member_search_auto_id');
				$approval_status= isset($nric_not_approve) ? 1 : 0;
				if($approval_status==1){
					if($member_auto_id!=""){
						$memberdata = DB::table("membership")->where('id','=',$member_auto_id)->first();
						$sub_member =DB::table("mon_sub_member")->where('id','=',$sub_member_id)->update(['MemberCode' => $member_auto_id, 'StatusId' => $memberdata->status_id]);
						$member_code = $memberdata->member_number;
						$member_status = CommonHelper::getStatusName($memberdata->status_id);
					}else{
						$approval_masg= "Nric can't verify, member should not found";
						$approval_status= 0;
					}
				}else{
					$approval_status= 0;
					//$approval_masg= 'Update';
				}
				
				DB::table('mon_sub_member_match')->where('id', '=', $match->id)->where('match_id','=' ,$match_id)->update(['approval_status' => $approval_status, 'description' => 'NRIC Not Matched', 'updated_by' => Auth::user()->id]);
			}
			if($match_id==3){
				$member_approve = $request->input('member_approve');
				$approval_status= isset($member_approve) ? 1 : 0;
				$nameverify = $request->input('nameverify');
				if($approval_status==1){
					$member_id = DB::table('mon_sub_member')->where('id','=',$sub_member_id)->pluck('MemberCode')->first();
					if($nameverify==1){
						DB::table('mon_sub_member')->where('id', '=', $sub_member_id)->update(['Name' =>  CommonHelper::getmemberName($member_id)]);
					}else{
						$uploaded_member_name = DB::table('mon_sub_member')->where('id','=',$sub_member_id)->pluck('Name')->first();
						DB::table('membership')->where('id', '=', $member_id)->update(['name' => $uploaded_member_name]);
					}
				}
				DB::table('mon_sub_member_match')->where('id', '=', $match->id)->where('match_id','=' ,$match_id)->update(['approval_status' => $approval_status, 'description' => 'Mismatched Member Name', 'updated_by' => Auth::user()->id]);
			}
			if($match_id==4){
				$bank_approve = $request->input('bank_approve');
				$approval_status= isset($bank_approve) ? 1 : 0;
				if($approval_status==1){
					$registered_bank_id = $request->input('registered_bank_id');
					$uploaded_bank_id = $request->input('uploaded_bank_id');
					if($registered_bank_id==$uploaded_bank_id){
						$approval_status= 1;
					}else{
						$approval_masg= "Bank can't verify, both banks are differenet";
						$approval_status= 0;
					}
				}else{
					//$approval_masg= 'Please check anything to update';
				}
				DB::table('mon_sub_member_match')->where('id', '=', $match->id)->where('match_id','=' ,$match_id)->update(['approval_status' => $approval_status, 'description' => 'Mismatched Bank', 'updated_by' => Auth::user()->id]);
			}
			if($match_id==5){
				$previous_approve = $request->input('previous_approve');
				$approval_status= isset($previous_approve) ? 1 : 0;
				DB::table('mon_sub_member_match')->where('id', '=', $match->id)->where('match_id','=' ,$match_id)->update(['approval_status' => $approval_status, 'description' => 'Mismatched Previous Subscription', 'updated_by' => Auth::user()->id]);
			}
			if($match_id==6){
				$struckoff_approve = $request->input('struckoff_approve');
				$approval_status= isset($struckoff_approve) ? 1 : 0;
				DB::table('mon_sub_member_match')->where('id', '=', $match->id)->where('match_id','=' ,$match_id)->update(['approval_status' => $approval_status, 'description' => 'StruckOff Members', 'updated_by' => Auth::user()->id]);
			}
			if($match_id==7){
				$resign_approve = $request->input('resign_approve');
				$approval_status= isset($resign_approve) ? 1 : 0;
				DB::table('mon_sub_member_match')->where('id', '=', $match->id)->where('match_id','=' ,$match_id)->update(['approval_status' => $approval_status, 'description' => 'Resigned Members', 'updated_by' => Auth::user()->id]);
			}
			if($match_id==8){
				$nric_old_approve = $request->input('nric_old_approve');
				$approval_status= isset($nric_old_approve) ? 1 : 0;
				DB::table('mon_sub_member_match')->where('id', '=', $match->id)->where('match_id','=' ,$match_id)->update(['approval_status' => $approval_status, 'description' => 'NRIC Old Matched', 'updated_by' => Auth::user()->id]);
			}
			if($match_id==9){
				$nric_bank_approve = $request->input('nric_bank_approve');
				$approval_status= isset($nric_bank_approve) ? 1 : 0;
				DB::table('mon_sub_member_match')->where('id', '=', $match->id)->where('match_id','=' ,$match_id)->update(['approval_status' => $approval_status, 'description' => 'Employee ID Matched', 'updated_by' => Auth::user()->id]);
			}
			if($match_id==10){
				$previous_unpaid_approve = $request->input('previous_unpaid_approve');
				$approval_status= isset($previous_unpaid_approve) ? 1 : 0;
				DB::table('mon_sub_member_match')->where('id', '=', $match->id)->where('match_id','=' ,$match_id)->update(['approval_status' => $approval_status, 'description' => 'Previous Subscription Unpaid', 'updated_by' => Auth::user()->id]);
			}
			$total_match_count++;
		}
		$approved_match_count = DB::table('mon_sub_member_match')->where('mon_sub_member_id','=',$sub_member_id)->where('approval_status','=',1)->count();
		$total_approval_status = $total_match_count==$approved_match_count ? '1' : '0';
		if($total_approval_status==1){
			$sub_member =DB::table("mon_sub_member")->where('id','=',$sub_member_id)->first();
			$sub_company_id = $sub_member->MonthlySubscriptionCompanyId;
			$member_id = $sub_member->MemberCode;
			$cur_date = DB::table("mon_sub_company as mc")->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')->where('mc.id','=',$sub_company_id)->pluck('Date')->first();
			$last_month = date('Y-m-01',strtotime($cur_date.' -1 Month'));
			$mont_count = DB::table($this->membermonthendstatus_table)->where('StatusMonth', '=', $cur_date)->where('MEMBER_CODE', '=', $member_id)->count();
			$memberdata =DB::table("membership")->where('id','=',$member_id)->first();
			$total_subs_obj = DB::table('mon_sub_member')->select(DB::raw('IFNULL(sum("Amount"),0) as amount'))
						->where('MemberCode', '=', $member_id)
						->first();
			$member_doj = $memberdata->doj;
			$total_subs = $total_subs_obj->amount;
			
			$total_count = DB::table('mon_sub_member')
			->where('MemberCode', '=', $member_id)
			->count();
			
			 $old_subscription_count = DB::table("mon_sub_member as mm")
							->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
							->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
							->where('mm.MemberCode','=',$member_id)
							->where('ms.Date','=',$last_month)
                            ->orderBY('mm.MonthlySubscriptionCompanyId','desc')
                            ->count();
			
			$paid_bf = $total_subs-($total_count*$this->bf_amount);
			
				
			$to = Carbon::createFromFormat('Y-m-d H:s:i', $member_doj.' 3:30:34');
			$from = Carbon::createFromFormat('Y-m-d H:s:i', $cur_date.' 9:30:34');
			$diff_in_months = $to->diffInMonths($from);
			
			$bf_due = ($diff_in_months-$total_count)*$this->bf_amount;
			$ins_due = ($diff_in_months-$total_count)*$this->ins_amount;
			$total_subs_to_paid = $diff_in_months==0 ? $sub_member->Amount : ($diff_in_months*$sub_member->Amount);
			$total_pending = $total_subs_to_paid - $total_subs;
			//dd($member_code);
			$monthend_data = [
									'StatusMonth' => $cur_date, 
									'MEMBER_CODE' => $member_id,
									'SUBSCRIPTION_AMOUNT' => $sub_member->Amount,
									'BF_AMOUNT' => $this->bf_amount,
									'LASTPAYMENTDATE' => $old_subscription_count>0 ? $last_month : NULL,
									'TOTALSUBCRP_AMOUNT' => $total_subs,
									'TOTALBF_AMOUNT' => $total_count*$this->bf_amount,
									'TOTAL_MONTHS' => $diff_in_months,
									//'ENTRYMODE' => 0,
									//'DEFAULTINGMONTHS' => 0,
									'TOTALMONTHSDUE' => $diff_in_months==0 ? 0 : $diff_in_months-$total_count,
									'TOTALMONTHSPAID' => $total_count,
									'SUBSCRIPTIONDUE' => $total_pending,
									'BFDUE' => $bf_due,
									'ACCSUBSCRIPTION' => $sub_member->Amount,
									'ACCBF' => $this->bf_amount,
									//'ACCBENEFIT' => 0,
									//'CURRENT_YDTBF' => 0,
									//'CURRENT_YDTSUBSCRIPTION' => 0,
									'STATUS_CODE' => $memberdata->status_id,
									'RESIGNED' => $memberdata->status_id==4 ? 1 : 0,
									'ENTRY_DATE' => date('Y-m-d'),
									'ENTRY_TIME' => date('h:i:s'),
									'STRUCKOFF' => $memberdata->status_id==3 ? 1 : 0,
									'INSURANCE_AMOUNT' => $this->ins_amount,
									'TOTALINSURANCE_AMOUNT' => $diff_in_months==0 ? $this->ins_amount : ($diff_in_months*$this->ins_amount),
									'TOTALMONTHSCONTRIBUTION' => $total_count,
									'INSURANCEDUE' => $ins_due,
									'ACCINSURANCE' => $this->ins_amount,
									//'CURRENT_YDTINSURANCE' => 0,
								];
			if($mont_count>0){
				DB::table($this->membermonthendstatus_table)->where('StatusMonth', $cur_date)->where('MEMBER_CODE', $member_code)->update($monthend_data);
			}else{
				DB::table($this->membermonthendstatus_table)->insert($monthend_data);
			}
		}
        
		$return_data = ['status' => 1, 'message' => $approval_masg, 'sub_member_auto_id' => $sub_member_id, 'member_number' => $member_code, 'member_status' => $member_status, 'approval_status' => $total_approval_status, 'member_match' => $member_match];
		echo json_encode($return_data);
	}
    
    
}
