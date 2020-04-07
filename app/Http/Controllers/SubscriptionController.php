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
use Facades\App\Repository\CacheMonthEnd;
use PDF;
use Artisan;
use Facades\App\Repository\CacheMembers;
use DateTime;



class SubscriptionController extends CommonController
{
	protected $limit;
    public function __construct() {
		$this->limit = 25;
        ini_set('memory_limit', -1);
		ini_set('max_execution_time', 0);
        $this->middleware('auth');
        //$this->middleware('module:master');       
        $this->Company = new Company;
        $this->MonthlySubscription = new MonthlySubscription;
        $this->MonthlySubscriptionMember = new MonthlySubscriptionMember;
        $this->Status = new Status;
        $this->membermonthendstatus_table = "membermonthendstatus";
        $this->ArrearEntry = new ArrearEntry;
        $bf_amount = Fee::where('fee_shortcode','=','BF')->pluck('fee_amount')->first();
        $ins_amount = Fee::where('fee_shortcode','=','INS')->pluck('fee_amount')->first();
        $this->bf_amount = $bf_amount=='' ? 3 : $bf_amount;
        $this->ins_amount = $ins_amount=='' ? 7 : $ins_amount;
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
       
        $data = [];
        //$data['company_list'] = $company_list;
       
        
        return view('subscription.sub_company_list')->with('data', $data);
    }
	
	public function subscribeDownload(Request $request){
       // return $request->all();
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
                //return 1;
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

            $banktype = DB::table('mon_sub_company as mc')
                                    ->select('mc.banktype')
                                    ->where('id','=',$company_auto_id)
                                    ->pluck('mc.banktype')
                                    ->first();
            $approval_data = [];
            $total_members_count = 0;
            $total_members_amount = 0;
			$total_match_members_count = 0;
			$total_match_approval_members_count = 0;
			$total_match_pending_members_count = 0;
            foreach($status_all as $key => $value){
                if($banktype==1 && $value->id>=3){
                    $members_count = 0;
                    $members_amount = 0;
                }else{
                    $members_count = CommonHelper::statusSubsMembersNotDOJCompanyCount($value->id, $user_role, $user_id,$company_auto_id,$full_date);
                    $members_amount = CommonHelper::statusMembersNotDojCompanyAmount($value->id, $user_role, $user_id,$company_auto_id,$full_date);
                }
				
                
                $status_data['count'][$value->id] = $members_count;
                $status_data['amount'][$value->id] = number_format($members_amount,2,".",",");
				$total_members_count += $members_count;
				$total_members_amount += $members_amount;
            }
            foreach($approval_status as $key => $value){
                if($value->id==2){
                    $match_members_count = CommonHelper::statusSubsCompanyMatchCount($value->id, $user_role, $user_id,$company_auto_id,$full_date);
                    $match_approval_members_count = CommonHelper::statusSubsCompanyMatchApprovalCount($value->id, $user_role, $user_id,$company_auto_id,1,$full_date);
                    $match_pending_members_count = CommonHelper::statusSubsCompanyMatchApprovalCount($value->id, $user_role, $user_id,$company_auto_id,0,$full_date);
                }else{
                    if($banktype==1 && ($value->id==6 || $value->id==7)){
                        $match_members_count = 0;
                        $match_approval_members_count = 0;
                        $match_pending_members_count = 0;
                    }else{
                        $match_members_count = CommonHelper::statusSubsCompanyMatchNotDojCount($value->id, $user_role, $user_id,$company_auto_id,$full_date);
                        $match_approval_members_count = CommonHelper::statusSubsCompanyMatchApprovalNotCount($value->id, $user_role, $user_id,$company_auto_id,1,$full_date);
                        $match_pending_members_count = CommonHelper::statusSubsCompanyMatchApprovalNotCount($value->id, $user_role, $user_id,$company_auto_id,0,$full_date);
                    }
                }
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

            if($banktype==1){
                $resstatus=2;
                $message = 'Data already uploaded for this bank by bank';
            }else{
                $resstatus=1;
                $message = 'Data already uploaded for this bank';
            }
            $data =['status' => $resstatus, 'status_data' => $status_data, 'approval_data' => $approval_data, 'sundry_amount' => number_format($sundry_amount,2,".",","), 'sundry_count' => $sundry_count, 'total_members_amount' => number_format($total_members_amount,2,".",","), 'total_members_count' => $total_members_count, 'total_match_members_count' => $total_match_members_count, 'total_match_approval_members_count' => $total_match_approval_members_count, 'total_match_pending_members_count' => $total_match_pending_members_count, 'company_auto_id' => $company_auto_id, 'month_year_number' => strtotime('01-'.$monthname.'-'.$year),'message'  => $message];
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
		ini_set('max_execution_time', '1000');
        $limit = 100;
        $company_auto_id = $request->company_auto_id;
        $start =  $request->start;
		$return_data = ['status' => 0 ,'message' => ''];
		if($start==0){
			Artisan::call('cache:clear');
		}
		
		/* $members = CacheMembers::all('id');
		$arr_old_ic=[];
		$arr_new_ic=[];
		$arr_employee_ic=[];
		foreach($members as $member){
			$arr_old_ic[]=$member->old_ic;
			$arr_new_ic[]=$member->new_ic;
			$arr_employee_ic[]=$member->employee_id;
		} */

        if($company_auto_id!=""){
            $banktype = DB::table('mon_sub_company as mc')
            ->select('mc.banktype')
            ->where('id','=',$company_auto_id)
            ->pluck('mc.banktype')
            ->first();

            $subscription_data = MonthlySubscriptionMember::select('id','NRIC as ICNO','NRIC as NRIC','Name','Amount','MonthlySubscriptionCompanyId')
                                                            ->where('MonthlySubscriptionCompanyId',$company_auto_id)
                                                            ->where('update_status','!=',1)
                                                            ->offset(0)
                                                            ->limit($limit)
                                                            ->get();
            
            $row_count = count($subscription_data);
            $count =0;
			
            foreach($subscription_data as $subscription){
				$nric = $subscription->NRIC;
				$approval_status=0;
				$memberdata = [];
			   
			    $subscription_new_qry =  DB::table('membership as m')->where(DB::raw("TRIM(LEADING '0' FROM m.new_ic)"), '=',ltrim($nric, '0'))->OrderBy('m.doj','desc')->limit(1)->select('status_id','id','branch_id','name','designation_id')->get();
			
				
				/* $new_nric_exists=0;
                if (in_array($nric, $arr_new_ic))
                {
                    $subscription_new_qry =  DB::table('membership as m')->where('m.new_ic', '=',$nric)->OrderBy('m.doj','desc')->limit(1);
                    $new_nric_exists=1;
                }
                $old_nric_exists=0;
                if (in_array($nric, $arr_old_ic))
                {
                    $subscription_old_qry =  DB::table('membership as m')->where('m.old_ic', '=',$nric)->OrderBy('m.doj','desc')->limit(1);
                    $old_nric_exists=1;
                }
                $empid_nric_exists=0;
                if (in_array($nric, $arr_employee_ic))
                {
                    $subscription_empid_qry =  DB::table('membership as m')->where('m.employee_id', '=',$nric)->OrderBy('m.doj','desc')->limit(1);
                    $empid_nric_exists=1;
                } */
                
                $up_sub_member =0;
                $match_res =  MonthlySubMemberMatch::where('mon_sub_member_id', '=',$subscription->id)
									->where(function($query) {
										  $query->where('match_id', 1)
											->orWhere('match_id', 8)
											->orWhere('match_id', 9)
											->orWhere('match_id', 2);
									  })->get();
                if(count($match_res)>0){
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
				if(count($subscription_new_qry) > 0){
					$memberdata = $subscription_new_qry;
					$up_sub_member =1;
                    $subMemberMatch->match_id = 1;
                    $subMemberMatch->approval_status = 1;
					$nric_matched = 1;
				}else{
					$subscription_old_qry =  DB::table('membership as m')->where(DB::raw("TRIM(LEADING '0' FROM m.old_ic)"), '=',ltrim($nric, '0'))->OrderBy('m.doj','desc')->limit(1)->select('status_id','id','branch_id','name','designation_id')->get();
					if(count($subscription_old_qry) > 0){
						$up_sub_member =1;
						$memberdata = $subscription_old_qry;
						$subMemberMatch->match_id = 8;
						//$nric_matched = 1;
					}else{
						$subscription_empid_qry =  DB::table('membership as m')->where(DB::raw("TRIM(LEADING '0' FROM m.employee_id)"), '=',ltrim($nric, '0'))->OrderBy('m.doj','desc')->limit(1)->select('status_id','id','branch_id','name','designation_id')->get();
						if(count($subscription_empid_qry) > 0){
                    
							$up_sub_member =1;
							$memberdata = $subscription_empid_qry;
							$subMemberMatch->match_id = 9;
						} else{
							$subMemberMatch->match_id = 2;
						}
					}
				}
                if(count($memberdata)>0){
                    if($memberdata[0]->status_id<=2){
                        $subMemberMatch->save();
                    }
                }else{
                    $subMemberMatch->save();
                }
               
              
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
                        //$upstatus=0;
                    }
					
					$company_code = CacheMembers::getcompanyidOfsubscribeCompanyid($subscription->MonthlySubscriptionCompanyId);
					
                    //$company_code = CommonHelper::getcompanyidOfsubscribeCompanyid($subscription->MonthlySubscriptionCompanyId);
                    //$member_company_id = CacheMembers::getcompanyidbyBranchid($memberdata[0]->branch_id);
                    $branch_data = CacheMembers::getbranchbyBranchid($memberdata[0]->branch_id);
					
					$match_company_status = $this->recursiveCompany($company_code,$branch_data->company_id);
                
                    if(!$match_company_status){
						$subMemberMatch_one = MonthlySubMemberMatch::where('match_id','=',4)->where('mon_sub_member_id','=',$subscription->id)->first();
						
						if(empty($subMemberMatch_one)){
							$subMemberMatch_one = new MonthlySubMemberMatch();
						}
                        $subMemberMatch_one->match_id = 4;
						$subMemberMatch_one->mon_sub_member_id = $subscription->id;
						$subMemberMatch_one->created_by = Auth::user()->id;
                        $subMemberMatch_one->created_on = date('Y-m-d');
                        
                        if(count($memberdata)>0){
                            if($memberdata[0]->status_id<=2){
                                $subMemberMatch_one->save();
                            }
                        }else{
                            $subMemberMatch_one->save();
                        }

						//$subMemberMatch_one->save();
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
                        
                        if(count($memberdata)>0){
                            if($memberdata[0]->status_id<=2){
                                $subMemberMatch_two->save();
                            }
                        }else{
                            $subMemberMatch_two->save();
                        }

						//$subMemberMatch_two->save();
						$insert_month_end = 0;
                    }
					
					$cur_date = CacheMembers::getDatebycompanyid($subscription->MonthlySubscriptionCompanyId);
					$last_month = date('Y-m-01',strtotime($cur_date.' -1 Month'));

                    $old_subscription_res = DB::table("mon_sub_member as mm")->select('mm.Amount')
							->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
							->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
							->where('mm.MemberCode','=',$member_code)
							->where('ms.Date','=',$last_month)
                            ->orderBY('mm.MonthlySubscriptionCompanyId','desc')
                            ->get();
							
					$member_doj = CacheMembers::getDojbyMemberCode($member_code);
					//$member_doj = DB::table("membership as m")->where('m.id','=',$member_code)->pluck('doj')->first();
					$member_month_yr = date('m-Y',strtotime($member_doj));
					$cur_month_yr = date('m-Y',strtotime($cur_date));
					//dd($member_month_yr);
					//dd($old_subscription_count);
					if($member_month_yr!=$cur_month_yr){
						if(count($old_subscription_res)>0){
							$old_subscription_amount = $old_subscription_res[0]->Amount;
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
                                
                                if(count($memberdata)>0){
                                    if($memberdata[0]->status_id<=2){
                                        $subMemberMatch_three->save();
                                    }
                                }else{
                                    $subMemberMatch_three->save();
                                }

								//$subMemberMatch_three->save();
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
                            
                            if(count($memberdata)>0){
                                if($memberdata[0]->status_id<=2){
                                    $subMemberMatch_four->save();
                                }
                            }else{
                                $subMemberMatch_four->save();
                            }

							//$subMemberMatch_four->save();
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
						$approval_status=1;
						
                        $mont_count = CacheMembers::getMonthEndMemberStatus($cur_date, $member_code);

                        $last_subscription_res = DB::table("member_payments as ms")->select('ms.last_paid_date as LASTPAYMENTDATE','ms.accins_amount as ACCINSURANCE','ms.accbf_amount as ACCBF','ms.accsub_amount as ACCSUBSCRIPTION','ms.sub_monthly_amount as SUBSCRIPTION_AMOUNT','ms.bf_monthly_amount as BF_AMOUNT','ms.totpaid_months as TOTALMONTHSPAID','ms.totdue_months as TOTALMONTHSDUE','ms.totcontribution_months as TOTALMONTHSCONTRIBUTION','ms.duesub_amount as SUBSCRIPTIONDUE','ms.dueins_amount as INSURANCEDUE')
                            ->where('ms.member_id','=',$member_code)
                            ->first();

                        $m_subs_amt = number_format($subscription->Amount-($this->bf_amount+$this->ins_amount),2);

                        if(!empty($last_subscription_res)){
                            // if($last_subscription_res->LASTPAYMENTDATE==$cur_date){
                            //     $new_total_months_paid = $last_subscription_res->TOTALMONTHSPAID;
                            //     $new_acc_subscription = $last_subscription_res->ACCSUBSCRIPTION;
                            //     $new_acc_bf = $last_subscription_res->ACCBF;
                            //     $new_acc_insurance = $last_subscription_res->ACCINSURANCE;
                            //     $new_contribution = $last_subscription_res->TOTALMONTHSCONTRIBUTION;
                            // }else{
                            //     $new_total_months_paid = $last_subscription_res->TOTALMONTHSPAID+1;
                            //     $new_acc_subscription = $last_subscription_res->ACCSUBSCRIPTION+$m_subs_amt;
                            //     $new_acc_bf = $last_subscription_res->ACCBF+$this->bf_amount;
                            //     $new_acc_insurance = $last_subscription_res->ACCINSURANCE+$this->ins_amount;
                            //     $new_contribution = $last_subscription_res->TOTALMONTHSCONTRIBUTION+1;
                            // }
                            $new_total_months_paid = $last_subscription_res->TOTALMONTHSPAID+1;
                            $new_acc_subscription = $last_subscription_res->ACCSUBSCRIPTION+$m_subs_amt;
                            $new_acc_bf = $last_subscription_res->ACCBF+$this->bf_amount;
                            $new_acc_insurance = $last_subscription_res->ACCINSURANCE+$this->ins_amount;
                            $new_contribution = $last_subscription_res->TOTALMONTHSCONTRIBUTION+1;
                            
                        }else{
                            $new_total_months_paid = 1;
                            $new_acc_subscription = $m_subs_amt;
                            $new_acc_bf = $this->bf_amount;
                            $new_acc_insurance = $this->ins_amount;
                            $new_contribution = 1;
                        }
						
						$monthend_data = [
												'StatusMonth' => $cur_date, 
												'MEMBER_CODE' => $member_code,
												'SUBSCRIPTION_AMOUNT' => $m_subs_amt,
												'BF_AMOUNT' => $this->bf_amount,
												'LASTPAYMENTDATE' => $cur_date,
												'TOTALSUBCRP_AMOUNT' => $m_subs_amt,
												'TOTALBF_AMOUNT' => $this->bf_amount,
												'TOTAL_MONTHS' => 1,
												'BANK_CODE' => $branch_data->company_id,
												'NUBE_BRANCH_CODE' => $branch_data->union_branch_id,
												'BRANCH_CODE' => $memberdata[0]->branch_id,
												'MEMBERTYPE_CODE' => $memberdata[0]->designation_id,
												'ENTRYMODE' => 'S',
												//'DEFAULTINGMONTHS' => 0,
												'TOTALMONTHSDUE' => !empty($last_subscription_res) ? $last_subscription_res->TOTALMONTHSDUE : 0,
												'TOTALMONTHSPAID' => $new_total_months_paid,
                                                'SUBSCRIPTIONDUE' => !empty($last_subscription_res) ? $last_subscription_res->SUBSCRIPTIONDUE : 0,
												'BFDUE' => 0,
												'ACCSUBSCRIPTION' => $new_acc_subscription,
												'ACCBF' => $new_acc_bf,
                                                'ACCINSURANCE' => $new_acc_insurance,
												//'ACCBENEFIT' => 0,
												//'CURRENT_YDTBF' => 0,
												//'CURRENT_YDTSUBSCRIPTION' => 0,
												'STATUS_CODE' => $memberdata[0]->status_id,
												'RESIGNED' => $memberdata[0]->status_id==4 ? 1 : 0,
												'ENTRY_DATE' => date('Y-m-d'),
												'ENTRY_TIME' => date('h:i:s'),
												'STRUCKOFF' => $memberdata[0]->status_id==3 ? 1 : 0,
												'INSURANCE_AMOUNT' => $this->ins_amount,
												'TOTALINSURANCE_AMOUNT' => $this->ins_amount,
												'TOTALMONTHSCONTRIBUTION' => $new_contribution,
												'INSURANCEDUE' => !empty($last_subscription_res) ? $last_subscription_res->INSURANCEDUE : 0,
												//'CURRENT_YDTINSURANCE' => 0,
											];
						if($mont_count>0){
							DB::table($this->membermonthendstatus_table)->where('StatusMonth', $cur_date)->where('MEMBER_CODE', $member_code)->update($monthend_data);
						}else{
							DB::table($this->membermonthendstatus_table)->insert($monthend_data);
						}
						// $payment_data = [
						// 	'last_paid_date' => $cur_date,
						// 	'totpaid_months' => $new_total_months_paid,
                        //     'totcontribution_months' => $new_contribution,
                        //     'accbf_amount' => $new_acc_bf,
                        //     'accsub_amount' => $new_acc_subscription,
                        //     'accins_amount' => $new_acc_insurance,
						// 	'updated_by' => Auth::user()->id,
						// ];
						// DB::table('member_payments')->where('member_id', $member_code)->update($payment_data);
						
					}
                }

               
                if( $upstatus==1){
                    $updata = ['update_status' => 1,'approval_status' => $approval_status];
                    $savedata = MonthlySubscriptionMember::where('id',$subscription->id)->update($updata);
                }
                
                $count++;
            }
            //Log::info($start.'count-'.$count);
            //Log::Error($start.'rowcount-'.$row_count);
            $enc_id = Crypt::encrypt($company_auto_id);
            if($banktype==1){
                $return_data = ['status' => 1 ,'message' => 'status and member code updated successfully, Redirecting to subscription details...','redirect_url' =>  URL::to('/'.app()->getLocale().'/sub-company-summary/'.$enc_id)];
            }else{
                $return_data = ['status' => 1 ,'message' => 'status and member code updated successfully, Redirecting to subscription details...','redirect_url' =>  URL::to('/'.app()->getLocale().'/sub-company-members/'.$enc_id)];
            }
          
        }else{
            $return_data = ['status' => 0 ,'message' => 'Invalid company id'];
		}
		//Artisan::call('cache:clear');
       	echo json_encode($return_data);
    }
	
	public function recursiveCompany($company_code,$member_company_id){
		//Print out the number.
		//If the number is less or equal to 50.
		if($company_code == $member_company_id){
			//Call the function again. Increment number by one.
			return true;
		}else{
			$company_data = CacheMembers::getHeadcompanyidbyid($member_company_id);
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
		$company_data = DB::table('mon_sub_company as mc')->select('mc.CompanyCode','c.company_name','ms.Date')
						->leftjoin('mon_sub as ms', 'ms.id' ,'=','mc.MonthlySubscriptionId')
						->leftjoin('company as c', 'c.id' ,'=','mc.CompanyCode')
						->where('mc.id','=',$company_auto_id)->first();
		$data['company_auto_id'] = $company_auto_id;
		$data['company_name'] = $company_data->company_name;
		$data['month_year'] = date('M/Y',strtotime($company_data->Date));
        $memberrowcount = MonthlySubscriptionMember::where('MonthlySubscriptionCompanyId','=',$company_auto_id)->where('update_status','=',0)->count();
        $data['row_count'] = $memberrowcount;
        return view('subscription.scan-subcription')->with('data',$data);
    }

    public function companyMembers($lang,$id){
		Artisan::call('cache:clear');
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

        //$id = Crypt::decrypt($id);
    
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
											'ms.TOTALSUBCRP_AMOUNT as SUBSCRIPTION_AMOUNT','ms.TOTALBF_AMOUNT as BF_AMOUNT','ms.TOTALINSURANCE_AMOUNT as INSURANCE_AMOUNT','ms.TOTAL_MONTHS','ms.LASTPAYMENTDATE','ms.TOTALMONTHSPAID',DB::raw('IFNULL(ms.TOTALMONTHSDUE,0) as TOTALMONTHSDUE'),'ms.ACCSUBSCRIPTION','ms.ACCBF','ms.ACCINSURANCE','s.font_color','m.name','m.member_number as member_number','ms.arrear_status','ms.SUBSCRIPTIONDUE')
											->leftjoin('membership as m', 'm.id' ,'=','ms.MEMBER_CODE')
											->leftjoin('status as s','s.id','=','ms.STATUS_CODE')
											->where('ms.MEMBER_CODE','=',$id)
											->offset(0)
                                            ->limit($this->limit)
                                            ->OrderBy('ms.StatusMonth','asc')
                                            ->OrderBy('ms.arrear_status','asc')
                                            ->get();
		//dd($data['current_member_history']);
		if($old_member_id!=""){
			$data['previous_member_history'] = DB::table($this->membermonthendstatus_table.' as ms')->select('ms.id as id','ms.id as memberid','ms.StatusMonth',
			'ms.TOTALSUBCRP_AMOUNT as SUBSCRIPTION_AMOUNT','ms.TOTALBF_AMOUNT as BF_AMOUNT','ms.TOTALINSURANCE_AMOUNT as INSURANCE_AMOUNT','ms.TOTAL_MONTHS','ms.LASTPAYMENTDATE','ms.TOTALMONTHSPAID','ms.TOTALMONTHSDUE as TOTALMONTHSDUE','ms.ACCSUBSCRIPTION','ms.ACCBF','ms.ACCINSURANCE','s.font_color','m.name','m.member_number as member_number','ms.arrear_status','ms.SUBSCRIPTIONDUE')
											->leftjoin('membership as m', 'm.id' ,'=','ms.MEMBER_CODE')
											->leftjoin('status as s','s.id','=','ms.STATUS_CODE')
											->where('ms.MEMBER_CODE','=',$old_member_id)
											->offset(0)
                                            ->limit($this->limit)
                                            ->OrderBy('ms.StatusMonth','asc')
                                            ->OrderBy('ms.arrear_status','asc')
                                            ->get();
		}else{
			$data['previous_member_history'] = [];
		}
                                            
        $data['data_limit'] = $this->limit;
        $data['old_member_id'] = $old_member_id;
        $data['member_id'] = $id;
        return view('subscription.member_history')->with('data',$data);
      
            // $data['member_subscription_details'] = DB::table('mon_sub_member as sm')->select('m.id as memberid','m.doj as doj','m.name as membername','m.id as MemberCode','sm.Amount','status.status_name','s.Date')
            // ->leftjoin('membership as m','m.id','=','sm.MemberCode') 
            // ->leftjoin('mon_sub_company as sc','sm.MonthlySubscriptionCompanyId','=','sc.id')
            // ->leftjoin('mon_sub as s','sc.MonthlySubscriptionId','=','s.id') 
            // ->leftjoin('status as status','status.id','=','sm.StatusId')
            // //->where('s.Date','=',date('Y-m-01'))
            // ->orderBY('s.Date','desc')
            // ->where('m.id','=',$id)
            // ->get();

            // if(count($data['member_subscription_details']) == 0)
            // {
            //      return view('subscription.subscription_payment')->with('message','No transaction Done');
            // }
 
            // DB::enableQueryLog();
            // $data['member_subscription_list'] = DB::table('mon_sub_member as sm')->select('sm.Amount as Amount','s.Date as Date','status.status_name as status_name')
            //         ->leftjoin('mon_sub_company as sc', 'sc.id' ,'=','sm.MonthlySubscriptionCompanyId')
            //         ->leftjoin('mon_sub as s','s.id','=','sc.MonthlySubscriptionId')
            //         ->leftjoin('status as status','status.id','=','sm.StatusId')
            //         ->leftjoin('membership as m','m.id','=','sm.MemberCode')
            //         ->where('m.id','=',$id)
            //         ->get();  
            // return view('subscription.subscription_paymenthistory')->with('data',$data);
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
											'ms.TOTALSUBCRP_AMOUNT as SUBSCRIPTION_AMOUNT','ms.TOTALBF_AMOUNT as BF_AMOUNT','ms.TOTALINSURANCE_AMOUNT as INSURANCE_AMOUNT','ms.TOTAL_MONTHS','ms.LASTPAYMENTDATE','ms.TOTALMONTHSPAID',DB::raw('IFNULL(ms.TOTALMONTHSDUE,0) as TOTALMONTHSDUE'),'ms.ACCSUBSCRIPTION','ms.ACCBF','ms.ACCINSURANCE','s.font_color','m.name','m.member_number as member_number','ms.arrear_status','ms.SUBSCRIPTIONDUE')
											->leftjoin('membership as m', 'm.id' ,'=','ms.MEMBER_CODE')
											->leftjoin('status as s','s.id','=','ms.STATUS_CODE')
											->where('ms.MEMBER_CODE','=',$id)
											->offset(0)
                                            ->limit($this->limit)
                                            ->OrderBy('ms.StatusMonth','asc')
                                            ->OrderBy('ms.arrear_status','asc')
                                            ->get();
		
		if($old_member_id!=""){
			$data['previous_member_history'] = DB::table($this->membermonthendstatus_table.' as ms')->select('ms.id as id','ms.id as memberid','ms.StatusMonth',
			'ms.TOTALSUBCRP_AMOUNT as SUBSCRIPTION_AMOUNT','ms.TOTALBF_AMOUNT as BF_AMOUNT','ms.TOTALINSURANCE_AMOUNT as INSURANCE_AMOUNT','ms.TOTAL_MONTHS','ms.LASTPAYMENTDATE','ms.TOTALMONTHSPAID','ms.TOTALMONTHSDUE as TOTALMONTHSDUE','ms.ACCSUBSCRIPTION','ms.ACCBF','ms.ACCINSURANCE','s.font_color','m.name','m.member_number as member_number','ms.arrear_status','ms.SUBSCRIPTIONDUE')
											->leftjoin('membership as m', 'm.id' ,'=','ms.MEMBER_CODE')
											->leftjoin('status as s','s.id','=','ms.STATUS_CODE')
											->where('ms.MEMBER_CODE','=',$old_member_id)
											->offset(0)
                                            ->limit($this->limit)
                                            ->OrderBy('ms.StatusMonth','asc')
                                            ->OrderBy('ms.arrear_status','asc')
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
        
        return view('subscription.arrear_entry');
    }
    public function arrearentryAdd()
    {
		$data['fee_view'] = Fee::all();
        return view('subscription.add_arrearentry')->with('data',$data);
    }
    public function getNricMemberlist(Request $request)
    {
        $searchkey = $request->input('searchkey');
        $search = $request->input('query');
        $res['suggestions'] = DB::table('membership as m')->select(DB::raw('CONCAT(m.name, " - ", m.member_number, "-" , m.new_ic) AS value'),'m.id as number','m.branch_id as branch_id','m.member_number',DB::raw("if(m.new_ic > 0  ,m.new_ic,m.old_ic) as nric"))

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
        $autoid = $res->memberid;

        $resmonthend = DB::table('membermonthendstatus')->select('SUBSCRIPTIONDUE','BFDUE','INSURANCEDUE','TOTALMONTHSDUE')
                    ->where('MEMBER_CODE','=',$autoid)
                    ->OrderBy('StatusMonth','desc')
                    ->limit(1)->first();
                    //->limit(1)->dump()->get();
        if(!empty($resmonthend)){
            $duemonths = $resmonthend->TOTALMONTHSDUE;
            $subsdue = $resmonthend->SUBSCRIPTIONDUE;
            $subsbf = $resmonthend->BFDUE;
            $subsins = $resmonthend->INSURANCEDUE;
        }else{
            $duemonths = 0;
            $subsdue = 0;
            $subsbf = 0;
            $subsins = 0;
        }
       // dd($resmonthend);
        $res->duemonths = $duemonths;
        $res->subscriptiondue = $subsdue;
        $res->bfdue = $subsbf;
        $res->insurancedue = $subsins;
        $res->totaldues = $subsins+$subsdue+$subsbf;
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
        $request->request->add(['created_by' => Auth::user()->id]);
        $request->request->add(['updated_by' => Auth::user()->id]);
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
                return redirect($defdaultLang.'/sub-arrearentry')->with('message','Entry Updated Succesfully');
            }
            else
            {
                $arrearid = $saveArrearEntry->id;
                $enc_arrearid = Crypt::encrypt($arrearid);
                return redirect($defdaultLang.'/sub-arrearupdate/'.$enc_arrearid)->with('message','Entry Added Succesfully');
            }
        }
    }
    public function arrearentryEdit($lang,$id)
    {
        $id = Crypt::decrypt($id);
       
         $data =  DB::table('arrear_entry as ar')->select('ar.no_of_months','m.id as memberid','c.id as companyid','cb.id as companybranchid','s.id as statusid','ar.id as arrearid','ar.nric',DB::raw("DATE_FORMAT(ar.arrear_date,'%d/%m/%Y') as arrear_date"),'ar.arrear_amount','cb.branch_name','c.company_name','s.status_name','m.member_number','m.name as membername','s.font_color')
        ->leftjoin('membership as m','ar.membercode','=','m.id')
        ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
        ->leftjoin('company as c','cb.company_id','=','c.id')
        ->leftjoin('status as s','m.status_id','=','s.id')
        ->where('ar.id','=',$id)->first();

        return view('subscription.edit_arrearentry')->with('data',$data);
    }

    public function UpdateArrear($lang,$id)
    {
        $id = Crypt::decrypt($id);
       
         $data =  DB::table('arrear_entry as ar')->select('ar.no_of_months','m.id as memberid','c.id as companyid','cb.id as companybranchid','s.id as statusid','ar.id as arrearid','ar.nric','ar.arrear_date','ar.arrear_amount','cb.branch_name','c.company_name','s.status_name','m.member_number','m.name as membername','s.font_color')
        ->leftjoin('membership as m','ar.membercode','=','m.id')
        ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
        ->leftjoin('company as c','cb.company_id','=','c.id')
        ->leftjoin('status as s','m.status_id','=','s.id')
        ->where('ar.id','=',$id)->first();

        return view('subscription.view_arrearentry')->with('data',$data);
    }

    public function saveArrearRows($lang,Request $request)
    {
        //return $request->all();
        $arrear_id = $request->input('arrear_id');
        $member_id = $request->input('member_id');
        $subscription_amount = $request->input('subscription_amount');
        $arrear_date = $request->input('arrear_date');
        $db_arrear_date = date('Y-m-d',strtotime($arrear_date));
        $arrearcount = count($subscription_amount);
        $total_subscription_amount = $request->input('total_subscription_amount');
        $total_bf_amount = $request->input('total_bf_amount');
        $total_insurance_amount = $request->input('total_insurance_amount');
        $arrear_status_date = date('Y-m-01',strtotime($arrear_date));
        $paycount = 0;
        $history_update_from = '';
        $memberdata =DB::table("membership")->select('branch_id','designation_id','status_id')->where('id','=',$member_id)->first();
         $branchdata = DB::table("company_branch")->where('id','=',$memberdata->branch_id)->first();
        for ($i=0; $i < $arrearcount; $i++) { 
            $subs_amount = $request->input('subscription_amount')[$i];
            $bf_amount = $request->input('bf_amount')[$i];
            $insurance_amount = $request->input('insurance_amount')[$i];
            $entry_date = $request->input('entry_date')[$i];
            $entry_status_month = date('Y-m-d',strtotime($entry_date));
            if((($subs_amount!='' && $subs_amount!=0) || ($bf_amount!='' && $bf_amount!=0) || ($insurance_amount!='' && $insurance_amount!=0)) && $entry_status_month!=''){
                $subs_amount = $subs_amount=='' ? 0 : $subs_amount;
                $bf_amount = $bf_amount=='' ? 0 : $bf_amount;
                $insurance_amount = $insurance_amount=='' ? 0 : $insurance_amount;
                $entry_status_month;
                if($paycount==0){
                    $history_update_from = $entry_status_month;
                }
                $monthend_data = [
                                'arrear_status' => 1,
                                'arrear_date' => $db_arrear_date,
                                'ENTRYMODE' => 'A',
                                'SUBSCRIPTION_AMOUNT' => $subs_amount,
                                'TOTALSUBCRP_AMOUNT' => $subs_amount,
                                'BF_AMOUNT' => $bf_amount,
                                'TOTALBF_AMOUNT' => $bf_amount,
                                'INSURANCE_AMOUNT' => $insurance_amount,
                                'TOTALINSURANCE_AMOUNT' => $insurance_amount,
                                'TOTAL_MONTHS' => 1,
                             ];
                $mont_count = DB::table($this->membermonthendstatus_table)->where('StatusMonth', '=', $entry_status_month)->where('MEMBER_CODE', '=', $member_id)->count();
                if($mont_count==1){
                    $upstatus = DB::table('membermonthendstatus')->where('MEMBER_CODE', '=', $member_id)->where('StatusMonth', '=', $entry_status_month)->update($monthend_data);
                }else{
                    $memberdata =DB::table("membership")->select('branch_id','designation_id','status_id')->where('id','=',$member_id)->first();
                        $branchdata = DB::table("company_branch")->where('id','=',$memberdata->branch_id)->first();
                        $last_subscription_res = DB::table("member_payments as ms")->select('ms.last_paid_date as LASTPAYMENTDATE','ms.accins_amount as ACCINSURANCE','ms.accbf_amount as ACCBF','ms.accsub_amount as ACCSUBSCRIPTION','ms.sub_monthly_amount as SUBSCRIPTION_AMOUNT','ms.bf_monthly_amount as BF_AMOUNT','ms.totpaid_months as TOTALMONTHSPAID','ms.totdue_months as TOTALMONTHSDUE','ms.totcontribution_months as TOTALMONTHSCONTRIBUTION','ms.duesub_amount as SUBSCRIPTIONDUE','ms.dueins_amount as INSURANCEDUE','ms.duebf_amount as BFDUE')
                            ->where('ms.member_id','=',$member_id)
                            ->first();
                            //print_r($last_subscription_res);
                            //die;
                        $monthend_data = [
                            'StatusMonth' => $entry_status_month, 
                            'MEMBER_CODE' => $member_id,
                            'SUBSCRIPTION_AMOUNT' => $subs_amount,
                            'BF_AMOUNT' => $bf_amount,
                            'LASTPAYMENTDATE' => $entry_status_month,
                            'TOTALSUBCRP_AMOUNT' => $subs_amount,
                            'TOTALBF_AMOUNT' => $bf_amount,
                            'TOTAL_MONTHS' => 1,
                            'BANK_CODE' => $branchdata->company_id,
                            'NUBE_BRANCH_CODE' => $branchdata->union_branch_id,
                            'BRANCH_CODE' => $memberdata->branch_id,
                            'MEMBERTYPE_CODE' => $memberdata->designation_id,
                            'ENTRYMODE' => 'A',
                            //'DEFAULTINGMONTHS' => 0,
                            'TOTALMONTHSDUE' => !empty($last_subscription_res) ? $last_subscription_res->TOTALMONTHSDUE-$paycount : 0,
                            'TOTALMONTHSPAID' => !empty($last_subscription_res) ? $last_subscription_res->TOTALMONTHSPAID+$paycount : 1,
                            'SUBSCRIPTIONDUE' => !empty($last_subscription_res) ? $last_subscription_res->SUBSCRIPTIONDUE-$subs_amount : 0,
                            'BFDUE' => !empty($last_subscription_res) ? $last_subscription_res->BFDUE-$bf_amount : 0,
                            'ACCSUBSCRIPTION' => !empty($last_subscription_res) ? $last_subscription_res->ACCSUBSCRIPTION+$subs_amount : $subs_amount,
                            'ACCBF' => !empty($last_subscription_res) ? $last_subscription_res->ACCBF+$bf_amount : $bf_amount,
                            'ACCINSURANCE' => !empty($last_subscription_res) ? $last_subscription_res->ACCINSURANCE+$insurance_amount : $insurance_amount,
                            //'ACCBENEFIT' => 0,
                            //'CURRENT_YDTBF' => 0,
                            //'CURRENT_YDTSUBSCRIPTION' => 0,
                            'STATUS_CODE' => $memberdata->status_id,
                            'RESIGNED' => $memberdata->status_id==4 ? 1 : 0,
                            'ENTRY_DATE' => date('Y-m-d'),
                            'ENTRY_TIME' => date('h:i:s'),
                            'STRUCKOFF' => $memberdata->status_id==3 ? 1 : 0,
                            'INSURANCE_AMOUNT' => $insurance_amount,
                            'TOTALINSURANCE_AMOUNT' => $insurance_amount,
                            'TOTALMONTHSCONTRIBUTION' => !empty($last_subscription_res) ? $last_subscription_res->TOTALMONTHSCONTRIBUTION+1 : 1,
                            'INSURANCEDUE' => !empty($last_subscription_res) ? $last_subscription_res->INSURANCEDUE-$insurance_amount : 0,
                            'arrear_status' => 1,
                            'arrear_date' => $db_arrear_date,
                            // 'TOTALMONTHSDUE' => DB::raw('TOTALMONTHSDUE-'.$paycount),
                            //'TOTALMONTHSPAID' => DB::raw('TOTALMONTHSPAID+'.$paycount),
                            //'SUBSCRIPTIONDUE' => DB::raw('SUBSCRIPTIONDUE-'.$total_subscription_amount),
                            //'BFDUE' => DB::raw('BFDUE-'.$total_bf_amount),
                            //'INSURANCEDUE' => DB::raw('INSURANCEDUE-'.$total_insurance_amount),
                            //'ACCSUBSCRIPTION' => DB::raw('ACCSUBSCRIPTION+'.$total_subscription_amount),
                            //'ACCBF' => DB::raw('ACCBF+'.$total_bf_amount),
                            //'ACCINSURANCE' => DB::raw('ACCINSURANCE+'.$total_insurance_amount),
                            //'ENTRYMODE' => 'A',
                        ];
                        DB::table($this->membermonthendstatus_table)->insert($monthend_data);
                        //Log::debug(json_encode($monthend_data));
                }
                //return $db_arrear_date;
                $paycount++;

            }
        }
        if($history_update_from!=""){
            $last_mont_record = DB::table($this->membermonthendstatus_table." as ms")
            ->select('ms.StatusMonth','ms.LASTPAYMENTDATE','ms.ACCBF','ms.ACCSUBSCRIPTION','ms.SUBSCRIPTION_AMOUNT','ms.BF_AMOUNT','ms.TOTALMONTHSPAID','ms.ACCINSURANCE','ms.TOTALMONTHSDUE','ms.SUBSCRIPTIONDUE','ms.TOTALMONTHSCONTRIBUTION','ms.INSURANCEDUE','ms.BFDUE','ms.INSURANCE_AMOUNT','ms.TOTAL_MONTHS')
            ->where('StatusMonth', '<', $history_update_from)->where('MEMBER_CODE', '=', $member_id)
            ->orderBY('StatusMonth','desc')
            ->limit(1)
            ->first();

            $below_mont_records = DB::table($this->membermonthendstatus_table." as ms")
            ->select('ms.StatusMonth','ms.Id','ms.SUBSCRIPTION_AMOUNT','ms.BF_AMOUNT','ms.INSURANCE_AMOUNT','ms.TOTAL_MONTHS')
            ->where('StatusMonth', '>=', $history_update_from)->where('MEMBER_CODE', '=', $member_id)
            ->orderBY('StatusMonth','asc')
            ->get();

            $last_ACCINSURANCE = !empty($last_mont_record) ? $last_mont_record->ACCINSURANCE : 0;
            $last_ACCSUBSCRIPTION = !empty($last_mont_record) ? $last_mont_record->ACCSUBSCRIPTION : 0;
            $last_ACCBF = !empty($last_mont_record) ? $last_mont_record->ACCBF : 0;
            $last_SUBSCRIPTIONDUE = !empty($last_mont_record) ? $last_mont_record->SUBSCRIPTIONDUE : 0;
            $last_BFDUE = !empty($last_mont_record) ? $last_mont_record->BFDUE : 0;
            $last_INSURANCEDUE = !empty($last_mont_record) ? $last_mont_record->INSURANCEDUE : 0;
            $last_TOTALMONTHSDUE = !empty($last_mont_record) ? $last_mont_record->TOTALMONTHSDUE : 0;
            $last_TOTALMONTHSPAID = !empty($last_mont_record) ? $last_mont_record->TOTALMONTHSPAID : 0;
            $last_TOTALMONTHSCONTRIBUTION = !empty($last_mont_record) ? $last_mont_record->TOTALMONTHSCONTRIBUTION : 0;
            
            foreach($below_mont_records as $monthend){
                $m_subs_amt = $monthend->SUBSCRIPTION_AMOUNT;
                $m_bf_amt = $monthend->BF_AMOUNT;
                $m_ins_amt = $monthend->INSURANCE_AMOUNT;
                $m_total_months = $monthend->TOTAL_MONTHS;

                if($m_total_months==1){
                    $new_ACCINSURANCE = $last_ACCINSURANCE+$m_ins_amt;
                    $new_ACCSUBSCRIPTION = $last_ACCSUBSCRIPTION+$m_subs_amt;
                    $new_ACCBF = $last_ACCBF+$m_bf_amt;
                    $new_SUBSCRIPTIONDUE = $last_SUBSCRIPTIONDUE;
                    $new_BFDUE = $last_BFDUE;
                    $new_INSURANCEDUE = $last_INSURANCEDUE;
                    $new_TOTALMONTHSDUE = $last_TOTALMONTHSDUE;
                    $new_TOTALMONTHSPAID = $last_TOTALMONTHSPAID+1;
                    $new_TOTALMONTHSCONTRIBUTION = $last_TOTALMONTHSCONTRIBUTION+1;
                }else{
                    $new_ACCINSURANCE = $last_ACCINSURANCE;
                    $new_ACCSUBSCRIPTION = $last_ACCSUBSCRIPTION;
                    $new_ACCBF = $last_ACCBF;
                    $new_SUBSCRIPTIONDUE = $last_SUBSCRIPTIONDUE+$m_subs_amt;
                    $new_BFDUE = $last_BFDUE+$m_bf_amt;
                    $new_INSURANCEDUE = $last_INSURANCEDUE+$m_ins_amt;
                    $new_TOTALMONTHSDUE = $last_TOTALMONTHSDUE+1;
                    $new_TOTALMONTHSPAID = $last_TOTALMONTHSPAID;
                    $new_TOTALMONTHSCONTRIBUTION = $last_TOTALMONTHSCONTRIBUTION;
                }
              
                
                $monthend_datas = [
                            'TOTALMONTHSDUE' => $new_TOTALMONTHSDUE,
                            'TOTALMONTHSPAID' => $new_TOTALMONTHSPAID,
                            'SUBSCRIPTIONDUE' => $new_SUBSCRIPTIONDUE,
                            'BFDUE' => $new_BFDUE,
                            'INSURANCEDUE' => $new_INSURANCEDUE,
                            'ACCSUBSCRIPTION' => $new_ACCSUBSCRIPTION,
                            'ACCBF' => $new_ACCBF,
                            'ACCINSURANCE' => $new_ACCINSURANCE,
                            'TOTALMONTHSCONTRIBUTION' => $new_TOTALMONTHSCONTRIBUTION,
                        ];
                $m_upstatus = DB::table('membermonthendstatus')->where('Id', '=', $monthend->Id)->update($monthend_datas);

                $last_ACCINSURANCE = $new_ACCINSURANCE;
                $last_ACCSUBSCRIPTION = $new_ACCSUBSCRIPTION;
                $last_ACCBF = $new_ACCBF;
                $last_SUBSCRIPTIONDUE = $new_SUBSCRIPTIONDUE;
                $last_BFDUE = $new_BFDUE;
                $last_INSURANCEDUE = $new_INSURANCEDUE;
                $last_TOTALMONTHSDUE = $new_TOTALMONTHSDUE;
                $last_TOTALMONTHSPAID = $new_TOTALMONTHSPAID;
                $last_TOTALMONTHSCONTRIBUTION = $new_TOTALMONTHSCONTRIBUTION;
            }

            $payment_data = [
                    //'last_paid_date' => $db_arrear_date,
                    'totpaid_months' => $last_TOTALMONTHSPAID,
                    'totcontribution_months' => $last_TOTALMONTHSCONTRIBUTION,
                    'totdue_months' => $last_TOTALMONTHSDUE,
                    'accbf_amount' => $last_ACCBF,
                    'accsub_amount' => $last_ACCSUBSCRIPTION,
                    'accins_amount' => $last_ACCINSURANCE,
                    'duebf_amount' =>  $last_BFDUE,
                    'dueins_amount' => $last_INSURANCEDUE,
                    'duesub_amount' =>  $last_SUBSCRIPTIONDUE,
                    'updated_by' => Auth::user()->id,
                ];
                DB::table('member_payments')->where('member_id', $member_id)->update($payment_data);
            if($last_TOTALMONTHSDUE<=3){
                $m_status = 1;
                DB::table('membership')->where('id', $member_id)->update(['status_id' => $m_status]);
            }else if($last_TOTALMONTHSDUE<=13){
                $m_status = 2;
                DB::table('membership')->where('id', $member_id)->update(['status_id' => $m_status]);
            }
            if($arrear_id!=''){
                $arrear_data = [
                    'updated_at' => date('Y-m-d h:i:s'),
                    'updated_by' => Auth::user()->id,
                ];
                $arrear_upstatus = DB::table('arrear_entry')->where('id', '=', $arrear_id)->update($arrear_data);
            }
           
            
        }

        // $mont_count = DB::table($this->membermonthendstatus_table)->where('StatusMonth', '=', $db_arrear_date)->where('MEMBER_CODE', '=', $member_id)->count();
        // //DB::enableQuerylog();
        // if($mont_count==1){
        //    // DB::enableQuerylog();
        //     // $monthend_data = [
        //     //     'TOTALMONTHSDUE' => DB::raw('TOTALMONTHSDUE-'.$paycount),
        //     //     'TOTALMONTHSPAID' => DB::raw('TOTALMONTHSPAID+'.$paycount),
        //     //     'SUBSCRIPTIONDUE' => DB::raw('SUBSCRIPTIONDUE-'.$total_subscription_amount),
        //     //     'BFDUE' => DB::raw('BFDUE-'.$total_bf_amount),
        //     //     'INSURANCEDUE' => DB::raw('INSURANCEDUE-'.$total_insurance_amount),
        //     //     'ACCSUBSCRIPTION' => DB::raw('ACCSUBSCRIPTION+'.$total_subscription_amount),
        //     //     'ACCBF' => DB::raw('ACCBF+'.$total_bf_amount),
        //     //     'ACCINSURANCE' => DB::raw('ACCINSURANCE+'.$total_insurance_amount),
        //     //     'ENTRYMODE' => 'A',
        //     // ];
        //     // // return $monthend_data;
        //     // $statuss = DB::table($this->membermonthendstatus_table)->where('StatusMonth', $db_arrear_date)->where('MEMBER_CODE', $member_id)->update($monthend_data);

        //     $last_subscription_res = DB::table("member_payments as ms")->select('ms.last_paid_date as LASTPAYMENTDATE','ms.accins_amount as ACCINSURANCE','ms.accbf_amount as ACCBF','ms.accsub_amount as ACCSUBSCRIPTION','ms.sub_monthly_amount as SUBSCRIPTION_AMOUNT','ms.bf_monthly_amount as BF_AMOUNT','ms.totpaid_months as TOTALMONTHSPAID','ms.totdue_months as TOTALMONTHSDUE','ms.totcontribution_months as TOTALMONTHSCONTRIBUTION','ms.duesub_amount as SUBSCRIPTIONDUE','ms.dueins_amount as INSURANCEDUE','ms.duebf_amount as BFDUE')
        //     ->where('ms.member_id','=',$member_id)
        //     ->first();
        //     //print_r($last_subscription_res);
        //     //die;
        //     //dd(DB::getQuerylog());
        // }else{
        //     $memberdata =DB::table("membership")->select('branch_id','designation_id','status_id')->where('id','=',$member_id)->first();
        //     $branchdata = DB::table("company_branch")->where('id','=',$memberdata->branch_id)->first();
        //     $last_subscription_res = DB::table("member_payments as ms")->select('ms.last_paid_date as LASTPAYMENTDATE','ms.accins_amount as ACCINSURANCE','ms.accbf_amount as ACCBF','ms.accsub_amount as ACCSUBSCRIPTION','ms.sub_monthly_amount as SUBSCRIPTION_AMOUNT','ms.bf_monthly_amount as BF_AMOUNT','ms.totpaid_months as TOTALMONTHSPAID','ms.totdue_months as TOTALMONTHSDUE','ms.totcontribution_months as TOTALMONTHSCONTRIBUTION','ms.duesub_amount as SUBSCRIPTIONDUE','ms.dueins_amount as INSURANCEDUE','ms.duebf_amount as BFDUE')
        //         ->where('ms.member_id','=',$member_id)
        //         ->first();
        //         //print_r($last_subscription_res);
        //         //die;
        //     $monthend_data = [
        //         'StatusMonth' => $db_arrear_date, 
        //         'MEMBER_CODE' => $member_id,
        //         'SUBSCRIPTION_AMOUNT' => !empty($last_subscription_res) ? $last_subscription_res->SUBSCRIPTION_AMOUNT : 0,
        //         'BF_AMOUNT' => $this->bf_amount,
        //         'LASTPAYMENTDATE' => $db_arrear_date,
        //         'TOTALSUBCRP_AMOUNT' => !empty($last_subscription_res) ? $last_subscription_res->SUBSCRIPTION_AMOUNT : 0,
        //         'TOTALBF_AMOUNT' => $this->bf_amount,
        //         'TOTAL_MONTHS' => 0,
        //         'BANK_CODE' => $branchdata->company_id,
        //         'NUBE_BRANCH_CODE' => $branchdata->union_branch_id,
        //         'BRANCH_CODE' => $memberdata->branch_id,
        //         'MEMBERTYPE_CODE' => $memberdata->designation_id,
        //         'ENTRYMODE' => 'A',
        //         //'DEFAULTINGMONTHS' => 0,
        //         'TOTALMONTHSDUE' => !empty($last_subscription_res) ? $last_subscription_res->TOTALMONTHSDUE-$paycount : 0,
        //         'TOTALMONTHSPAID' => !empty($last_subscription_res) ? $last_subscription_res->TOTALMONTHSPAID+$paycount : 1,
        //         'SUBSCRIPTIONDUE' => !empty($last_subscription_res) ? $last_subscription_res->SUBSCRIPTIONDUE-$total_subscription_amount : 0,
        //         'BFDUE' => !empty($last_subscription_res) ? $last_subscription_res->BFDUE-$total_bf_amount : 0,
        //         'ACCSUBSCRIPTION' => !empty($last_subscription_res) ? $last_subscription_res->ACCSUBSCRIPTION+$total_subscription_amount : $total_subscription_amount,
        //         'ACCBF' => !empty($last_subscription_res) ? $last_subscription_res->ACCBF+$total_bf_amount : $total_bf_amount,
        //         'ACCINSURANCE' => !empty($last_subscription_res) ? $last_subscription_res->ACCINSURANCE+$total_insurance_amount : $total_insurance_amount,
        //         //'ACCBENEFIT' => 0,
        //         //'CURRENT_YDTBF' => 0,
        //         //'CURRENT_YDTSUBSCRIPTION' => 0,
        //         'STATUS_CODE' => $memberdata->status_id,
        //         'RESIGNED' => $memberdata->status_id==4 ? 1 : 0,
        //         'ENTRY_DATE' => date('Y-m-d'),
        //         'ENTRY_TIME' => date('h:i:s'),
        //         'STRUCKOFF' => $memberdata->status_id==3 ? 1 : 0,
        //         'INSURANCE_AMOUNT' => $this->ins_amount,
        //         'TOTALINSURANCE_AMOUNT' => $this->ins_amount,
        //         'TOTALMONTHSCONTRIBUTION' => !empty($last_subscription_res) ? $last_subscription_res->TOTALMONTHSCONTRIBUTION+1 : 1,
        //         'INSURANCEDUE' => !empty($last_subscription_res) ? $last_subscription_res->INSURANCEDUE-$total_insurance_amount : 0,
        //         // 'TOTALMONTHSDUE' => DB::raw('TOTALMONTHSDUE-'.$paycount),
        //         //'TOTALMONTHSPAID' => DB::raw('TOTALMONTHSPAID+'.$paycount),
        //         //'SUBSCRIPTIONDUE' => DB::raw('SUBSCRIPTIONDUE-'.$total_subscription_amount),
        //         //'BFDUE' => DB::raw('BFDUE-'.$total_bf_amount),
        //         //'INSURANCEDUE' => DB::raw('INSURANCEDUE-'.$total_insurance_amount),
        //         //'ACCSUBSCRIPTION' => DB::raw('ACCSUBSCRIPTION+'.$total_subscription_amount),
        //         //'ACCBF' => DB::raw('ACCBF+'.$total_bf_amount),
        //         //'ACCINSURANCE' => DB::raw('ACCINSURANCE+'.$total_insurance_amount),
        //         //'ENTRYMODE' => 'A',
        //     ];
        //     DB::table($this->membermonthendstatus_table)->insert($monthend_data);
        // }
        
        // $payment_data = [
        //     //'last_paid_date' => $db_arrear_date,
        //     'totpaid_months' => !empty($last_subscription_res) ? $last_subscription_res->TOTALMONTHSPAID+$paycount : $paycount,
        //     'totcontribution_months' => !empty($last_subscription_res) ? $last_subscription_res->TOTALMONTHSCONTRIBUTION+$paycount : $paycount,
        //     'totdue_months' => !empty($last_subscription_res) ? $last_subscription_res->TOTALMONTHSDUE-$paycount : $paycount,
        //     'accbf_amount' => !empty($last_subscription_res) ? $last_subscription_res->ACCBF+$total_bf_amount : $total_bf_amount,
        //     'accsub_amount' => !empty($last_subscription_res) ? $last_subscription_res->ACCSUBSCRIPTION+$total_subscription_amount : $total_subscription_amount,
        //     'accins_amount' => !empty($last_subscription_res) ? $last_subscription_res->ACCINSURANCE+$total_insurance_amount : $total_insurance_amount,
        //     'duebf_amount' =>  DB::raw('duebf_amount-'.$total_bf_amount),
        //     'dueins_amount' =>  DB::raw('dueins_amount-'.$total_insurance_amount),
        //     'duesub_amount' =>  DB::raw('duesub_amount-'.$total_subscription_amount),
        //     'updated_by' => Auth::user()->id,
        // ];
        // DB::table('member_payments')->where('member_id', $member_id)->update($payment_data);
        //dd(DB::getQuerylog());
        //return $paycount;

        return redirect($lang.'/sub-arrearentry')->with('message','Arrear Entry Updated Successfully!!');

       
    }
    public function arrearentrydestroy($lang,$id)
    {
        $id = Crypt::decrypt($id);
        $ArrearEntry = ArrearEntry::find($id);
        $ArrearEntry->delete();
        return redirect($lang.'/sub-arrearentry')->with('message','Arrear Entry Details Deleted Successfully!!');
    }
	
	public function statusCountView($lang, Request $request){
		$get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;
        $user_id = Auth::user()->id;
		
        $member_status = $request->input('member_status');
        $approval_status = $request->input('approval_status');
        $date = $request->input('date');
        $company_id = $request->input('company_id');
       
		$defaultdate = date('Y-m-01',$date);
		$data['data_limit'] = $this->limit;
		$data['company_id'] = $company_id;
		$filter_date = date('Y-m-01',$date);
		
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
        $data['company_view'] = DB::table('mon_sub_company as mc')->select('c.id as cid','mc.id as id','c.company_name as company_name')
                                ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                                ->leftjoin('company as c','mc.CompanyCode','=','c.id')
								->whereIn('c.id', $company_ids)
                                ->where('ms.Date', '=', date('Y-m-01',$date))->get();
		$data['member_status'] = Status::where('status',1)->get();
		$data['approval_status'] = DB::table('mon_sub_match_table as mt')
                                    ->select('mt.id as id','mt.match_name as match_name')
                                    ->get();
		$company_str_List ='';
		$slno=0;
		foreach($company_ids as $cids){
			if($slno!=0){
				$company_str_List .=',';
			}
			$company_str_List .="'".$cids."'";
			$slno++;
		}
		//dd($company_str_List); 
		$data['filter_date'] = strtotime(date('Y-m-01',strtotime($defaultdate)));
        if($member_status!=""){
			$cond ='';
			if(isset($company_id) && $company_id!=''){
				$cond =" AND m.MonthlySubscriptionCompanyId = '$company_id'";
            }
            if($member_status==3 ||$member_status==4){
                $cond .=" AND sc.banktype <> 1";
            }
			$members_data = DB::select(DB::raw('select member.name as member_name, member.member_number as member_number,m.Amount as Amount, c.company_name as company_name, member.new_ic as ic,"0" as due,s.status_name as status_name, `member`.`id` as memberid, m.id as sub_member_id,m.Name as up_member_name,m.NRIC as up_nric,m.approval_status from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` left join membership as member on `member`.`id` = `m`.`MemberCode` left join company as c on `c`.`id` = `sc`.`CompanyCode` left join status as s on `s`.`id` = `m`.`StatusId`  where m.StatusId="'.$member_status.'" '.$cond.' AND `sm`.`Date`="'.$defaultdate.'" AND `c`.`id` IN ('.$company_str_List.') LIMIT '.$data['data_limit']));
            $data['member'] = $members_data;
            $data['status_type'] = 1;
            $data['status'] = $member_status;
            $data['pending_approval_count'] = 0;
        }
        if($approval_status!=""){
			$cond ='';
			if(isset($company_id) && $company_id!=''){
				$cond =" AND m.MonthlySubscriptionCompanyId = '$company_id'";
			}
			
           $members_data = DB::select(DB::raw('SELECT member.name as member_name, member.member_number as member_number,m.Amount as Amount, c.company_name as company_name, member.new_ic as ic,"0" as due,s.status_name as status_name, `member`.`id` as memberid, mm.mon_sub_member_id as sub_member_id,m.Name as up_member_name,m.NRIC as up_nric,m.approval_status as approval_status FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as sc on sc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` left join membership as member on `member`.`id` = `m`.`MemberCode`  left join company as c on `c`.`id` = `sc`.`CompanyCode` left join status as s on `s`.`id` = `m`.`StatusId`  WHERE mm.match_id="'.$approval_status.'" '.$cond.' AND `sm`.`Date`="'.$defaultdate.'" AND `c`.`id` IN ('.$company_str_List.') LIMIT '.$data['data_limit']));

           $total_members_data = DB::select(DB::raw('SELECT member.id FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as sc on sc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` left join membership as member on `member`.`id` = `m`.`MemberCode`  left join company as c on `c`.`id` = `sc`.`CompanyCode` left join status as s on `s`.`id` = `m`.`StatusId`  WHERE mm.match_id="'.$approval_status.'" '.$cond.' AND `sm`.`Date`="'.$defaultdate.'" AND `c`.`id` IN ('.$company_str_List.') AND ( m.approval_status = 0 OR m.approval_status  IS NULL )'));
           

           $count_members_data =count($total_members_data);
		  
		   
           $data['member'] = $members_data;
           $data['status_type'] = 2;
           $data['status'] = $approval_status;
           $data['pending_approval_count'] = $count_members_data;
		   
        }
		if($member_status==0 && $approval_status==""){
			$cond ='';
			if(isset($company_id) && $company_id!=''){
				$cond =" AND m.MonthlySubscriptionCompanyId = '$company_id'";
			}
           $members_data = DB::select(DB::raw('SELECT member.name as member_name, member.member_number as member_number,m.Amount as Amount, c.company_name as company_name, member.new_ic as ic,"0" as due,s.status_name as status_name, `member`.`id` as memberid, mm.mon_sub_member_id as sub_member_id,m.Name as up_member_name,m.NRIC as up_nric,m.approval_status as approval_status FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as sc on sc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` left join membership as member on `member`.`id` = `m`.`MemberCode` left join company as c on `c`.`id` = `sc`.`CompanyCode` left join status as s on `s`.`id` = `m`.`StatusId`  WHERE mm.match_id="2" '.$cond.' AND `sm`.`Date`="'.$defaultdate.'" AND `c`.`id` IN ('.$company_str_List.') LIMIT '.$data['data_limit']));
		   
           $data['member'] = $members_data;
           $data['status_type'] = 3;
           $data['status'] = 0;
           $data['pending_approval_count'] = 0;
        }
		if($member_status=='all' || $approval_status=="all"){
			$cond ='1=1';
			if(isset($company_id) && $company_id!=''){
				$cond =" m.MonthlySubscriptionCompanyId = '$company_id'";
			}
			$members_data = DB::select(DB::raw('select member.name as member_name, member.member_number as member_number,m.Amount as Amount, c.company_name as company_name, member.new_ic as ic,"0" as due,s.status_name as status_name, `member`.`id` as memberid, m.id as sub_member_id,m.Name as up_member_name,m.NRIC as up_nric,m.approval_status as approval_status from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` left join membership as member on `member`.`id` = `m`.`MemberCode` left join company as c on `c`.`id` = `sc`.`CompanyCode` left join status as s on `s`.`id` = `m`.`StatusId`  where '.$cond.' AND `sm`.`Date`="'.$defaultdate.'" AND `c`.`id` IN ('.$company_str_List.') LIMIT '.$data['data_limit']));
           $data['member'] = $members_data;
           $data['status_type'] = 4;
           $data['status'] = 0;
           $data['pending_approval_count'] = 0;
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
                $ic_match_count = 0 ;
				$member_match = $match_id;
				$nric_not_approve = $request->input('nric_not_approve');
                $member_auto_id = $request->input('member_search_auto_id');
                $sub_member_res =DB::table("mon_sub_member")->where('id','=',$sub_member_id)->first();
                $approval_status= isset($nric_not_approve) ? 1 : 0;
                $member_id = $member_auto_id;
				if($approval_status==1){
					if($member_auto_id!=""){
                        $nric_no = $sub_member_res->NRIC;
                        $ic_match_count = DB::table('membership as m')->where('id','=',$member_auto_id)                  ->where(function($query) use ($nric_no){
                                            $query->orWhere('m.new_ic', 'LIKE',"%{$nric_no}%")
                                                ->orWhere('m.old_ic', 'LIKE',"%{$nric_no}%")
                                                ->orWhere('m.employee_id', 'LIKE',"%{$nric_no}%");
                                        })->count();     
                       // return $ic_match_count;
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
              //  return $ic_match_count;
                //DB::connection()->enableQueryLog();
				if($ic_match_count==1){
                    //DB::table('mon_sub_member_match')->where('id', '=', $match->id)->delete();
                    DB::table('mon_sub_member_match')->where('id', '=', $match->id)->where('match_id','=' ,$match_id)->update(['approval_status' => 1, 'description' => 'NRIC Matched', 'updated_by' => Auth::user()->id, 'match_id' => 1]);
                }else{
                    DB::table('mon_sub_member_match')->where('id', '=', $match->id)->where('match_id','=' ,$match_id)->update(['approval_status' => $approval_status, 'description' => 'NRIC Not Matched', 'updated_by' => Auth::user()->id]);
                }
               // $queries = DB::getQueryLog();
                //dd($queries);
				
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
                        $bankverify = $request->input('bankverify');
                        $bankverify= isset($bankverify) ? 1 : 0;
                        $approval_masg= "Bank can't verify, both banks are differenet";
                        $approval_status= 0;

                        $subs_data = DB::table("mon_sub_member as mm")->select('ms.Date','ms.id as month_auto_id')
							->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
							->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
							->where('mm.id','=',$sub_member_id)
                            ->first();
                        $subs_date = $subs_data->Date;
                        $month_auto_id = $subs_data->month_auto_id;
                        if($bankverify==1){
                            $sub_bank_id = DB::table("mon_sub_company as mc")->select('mc.id')
							->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                            ->where('ms.Date','=',$subs_date)
                            ->where('mc.CompanyCode','=',$registered_bank_id)
                            ->pluck('mc.id')->first();

                            if($sub_bank_id==''){
                                $subscription_company = new MonthlySubscriptionCompany();
                                $subscription_company->MonthlySubscriptionId = $month_auto_id;
                                $subscription_company->CompanyCode = $registered_bank_id;
                                $subscription_company->created_by = Auth::user()->id;
                                $subscription_company->created_on = date('Y-m-d');
                                $subscription_company->save();
                                $sub_bank_id = $subscription_company->id;
                            }

                            DB::table('mon_sub_member')->where('id', '=', $sub_member_id)->update(['MonthlySubscriptionCompanyId' => $sub_bank_id, 'updated_by' => Auth::user()->id]);

                            $approval_masg= "Bank Changed Succesfully";
                            $approval_status= 1;
                        }else{
                            $approval_masg= "Bank can't verify, both banks are differenet";
                            $approval_status= 0;
                        }
						
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
        //echo $total_approval_status;
		if($total_approval_status==1){
			$sub_member =DB::table("mon_sub_member")->where('id','=',$sub_member_id)->first();
			$sub_company_id = $sub_member->MonthlySubscriptionCompanyId;
			$member_id = $sub_member->MemberCode;
			$cur_date = DB::table("mon_sub_company as mc")->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')->where('mc.id','=',$sub_company_id)->pluck('Date')->first();
			//$last_month = date('Y-m-01',strtotime($cur_date.' -1 Month'));
			$mont_count = DB::table($this->membermonthendstatus_table)->where('StatusMonth', '=', $cur_date)->where('MEMBER_CODE', '=', $member_id)->count();
            $memberdata =DB::table("membership")->where('id','=',$member_id)->first();
            //$member_doj = $memberdata->doj;
			// $total_subs_obj = DB::table('mon_sub_member')->select(DB::raw('IFNULL(sum("Amount"),0) as amount'))
			// 			->where('MemberCode', '=', $member_id)
			// 			->first();
			
			// $total_subs = $total_subs_obj->amount;
			
			// $total_count = DB::table('mon_sub_member')
			// ->where('MemberCode', '=', $member_id)
			// ->count();
			
			//  $old_subscription_count = DB::table("mon_sub_member as mm")
			// 				->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
			// 				->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
			// 				->where('mm.MemberCode','=',$member_id)
			// 				->where('ms.Date','=',$last_month)
            //                 ->orderBY('mm.MonthlySubscriptionCompanyId','desc')
            //                 ->count();
			
			// $paid_bf = $total_subs-($total_count*$this->bf_amount);
			
				
			// $to = Carbon::createFromFormat('Y-m-d H:s:i', $member_doj.' 3:30:34');
			// $from = Carbon::createFromFormat('Y-m-d H:s:i', $cur_date.' 9:30:34');
			// $diff_in_months = $to->diffInMonths($from);
			
			// $bf_due = ($diff_in_months-$total_count)*$this->bf_amount;
			// $ins_due = ($diff_in_months-$total_count)*$this->ins_amount;
			// $total_subs_to_paid = $diff_in_months==0 ? $sub_member->Amount : ($diff_in_months*$sub_member->Amount);
			// $total_pending = $total_subs_to_paid - $total_subs;
			//dd($member_code);
			$branchdata = DB::table("company_branch")->where('id','=',$memberdata->branch_id)->first();
			// $last_subscription_res = DB::table($this->membermonthendstatus_table." as ms")->select('ms.LASTPAYMENTDATE','ms.ACCINSURANCE','ms.ACCBF','ms.ACCSUBSCRIPTION','ms.SUBSCRIPTION_AMOUNT','ms.BF_AMOUNT','ms.TOTALMONTHSPAID','ms.ACCINSURANCE','ms.TOTALMONTHSDUE','ms.SUBSCRIPTIONDUE','ms.TOTALMONTHSCONTRIBUTION')
			// ->where('ms.MEMBER_CODE','=',$member_id)
            // ->where('ms.StatusMonth','<',$cur_date)
			// ->orderBY('ms.StatusMonth','desc')
            // ->first();
            
            $last_subscription_res = DB::table("member_payments as ms")->select('ms.last_paid_date as LASTPAYMENTDATE','ms.accins_amount as ACCINSURANCE','ms.accbf_amount as ACCBF','ms.accsub_amount as ACCSUBSCRIPTION','ms.sub_monthly_amount as SUBSCRIPTION_AMOUNT','ms.bf_monthly_amount as BF_AMOUNT','ms.totpaid_months as TOTALMONTHSPAID','ms.totdue_months as TOTALMONTHSDUE','ms.totcontribution_months as TOTALMONTHSCONTRIBUTION','ms.duesub_amount as SUBSCRIPTIONDUE','ms.dueins_amount as INSURANCEDUE')
                            ->where('ms.member_id','=',$member_id)
                            ->first();
			$m_subs_amt = number_format($sub_member->Amount-($this->bf_amount+$this->ins_amount),2);
		
			
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
                                'SUBSCRIPTIONDUE' => !empty($last_subscription_res) ? $last_subscription_res->SUBSCRIPTIONDUE : 0,
                                'BFDUE' => 0,
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
                                'INSURANCEDUE' => !empty($last_subscription_res) ? $last_subscription_res->INSURANCEDUE : 0,
                                //'CURRENT_YDTINSURANCE' => 0,
                            ];
			
			$updata = ['update_status' => 1,'approval_status' => $approval_status];
            $savedata = MonthlySubscriptionMember::where('id',$sub_member_id)->update($updata);
            //DB::connection()->enableQueryLog();

			if($mont_count>0){
               
                $statuss = DB::table($this->membermonthendstatus_table)->where('StatusMonth', $cur_date)->where('MEMBER_CODE', $member_id)->update($monthend_data);
                //$queries = DB::getQueryLog();
               // dd($statuss);
			}else{
				DB::table($this->membermonthendstatus_table)->insert($monthend_data);
			}
			// $payment_data = [
			// 	'last_paid_date' => $cur_date,
            //     'totpaid_months' => !empty($last_subscription_res) ? $last_subscription_res->TOTALMONTHSPAID+1 : 1,
            //     'totcontribution_months' => !empty($last_subscription_res) ? $last_subscription_res->TOTALMONTHSCONTRIBUTION+1 : 1,
            //     'accbf_amount' => !empty($last_subscription_res) ? $last_subscription_res->ACCBF+$this->bf_amount : $this->bf_amount,
            //     'accsub_amount' => !empty($last_subscription_res) ? $last_subscription_res->ACCSUBSCRIPTION+$m_subs_amt : $m_subs_amt,
            //     'accins_amount' => !empty($last_subscription_res) ? $last_subscription_res->ACCINSURANCE+$this->ins_amount : $this->ins_amount,
			// 	'updated_by' => Auth::user()->id,
			// ];
			// DB::table('member_payments')->where('member_id', $member_id)->update($payment_data);
		}
        Artisan::call('cache:clear');
		$return_data = ['status' => 1, 'message' => $approval_masg, 'sub_member_auto_id' => $sub_member_id, 'member_number' => $member_id, 'member_status' => $member_status, 'approval_status' => $total_approval_status, 'member_match' => $member_match];
		echo json_encode($return_data);
	}
	
	public function variation(){
		$data['month_year'] = date('M/Y');
		$data['month_year_full'] = date('Y-m-01');
		$data['last_month_year']= date("Y-m-01", strtotime("first day of previous month"));
		
        $data['groupby'] = 1;
        $data['variationtype']='';
        $data['DisplaySubscription'] = false;
        $data['types'] = '';
		//$data['sixmonthvariation'] = false;
		//$data['company_list'] = DB::table('company')->where('status','=','1')->get();
		// $company_view = DB::table("mon_sub_member as mm")->select('cb.union_branch_id as union_branchid','u.union_branch as union_branch_name')
        //                         ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
		// 						->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
		// 						->leftjoin('membership as m','m.id','=','mm.MemberCode')
		// 						->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
		// 						->leftjoin('company as c','cb.company_id','=','c.id')
        //                         ->leftjoin('union_branch as u','cb.union_branch_id','=','u.id')
        //                         ->where('ms.Date', '=', $data['month_year_full'])
        //                         ->where('mm.update_status', '=', 1)
        //                         ->where('mm.MemberCode', '!=', Null)
		// 						->groupBY('cb.union_branch_id')
		// 						->get();
		$data['union_branch_view'] = CacheMonthEnd::getUnionBranchByDate($data['month_year_full']);
		
		$data['company_view']=[];
		$data['branch_view']=[];
	/* 	$data['company_view'] = DB::table("membermonthendstatus as mm")->select('mm.BANK_CODE as company_id','c.company_name as company_name')
                                ->leftjoin('company as c','mm.BANK_CODE','=','c.id')
                                ->where('mm.StatusMonth', '=', date('Y-m-01'))
								->groupBY('mm.BANK_CODE')
								->get(); */
		//return $data['company_view'];
		return view('subscription.variation')->with('data', $data);
	}
	
	public function variationFilter($lang, Request $request){
		//return $request->all();
		$entry_date = $request->input('entry_date');
		$groupby = $request->input('groupby');
        $display_subs = $request->input('display_subs');
        $variationtype = $request->input('variationtype');
        $types = $request->input('types');
		//$sixmonthvariation = $request->input('sixmonth-variation');
		$fm_date = explode("/",$entry_date);
        $fm_date[1].'-'.$fm_date[0].'-'.'01';
        $datestring = strtotime($fm_date[1].'-'.$fm_date[0].'-'.'01');
		$data['month_year'] = date('M/Y',$datestring);
		$data['month_year_full'] = date('Y-m-01',$datestring);
		$data['last_month_year']= date('Y-m-01',strtotime($fm_date[1].'-'.$fm_date[0].'-'.'01 -1 Month'));
		
		$data['groupby'] = $groupby;
        $data['DisplaySubscription'] = $display_subs;
        $data['types'] = $types;
       // $data['sixmonthvariation'] = $sixmonthvariation;
        $data['variationtype']=$variationtype;
		if($groupby==1){
			// $company_view = DB::table("mon_sub_member as mm")->select('cb.union_branch_id as union_branchid','u.union_branch as union_branch_name')
            //                     ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
			// 					->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
			// 					->leftjoin('membership as m','m.id','=','mm.MemberCode')
			// 					->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
			// 					->leftjoin('company as c','cb.company_id','=','c.id')
            //                     ->leftjoin('union_branch as u','cb.union_branch_id','=','u.id')
            //                     ->where('ms.Date', '=', $data['month_year_full'])
			// 					->where('mm.update_status', '=', 1)
			// 					->where('mm.MemberCode', '!=', Null)
			// 					->groupBY('cb.union_branch_id')
			// 					->get();
			$data['union_branch_view'] = CacheMonthEnd::getUnionBranchByDate($data['month_year_full']);
			$data['company_view']=[];
			$data['branch_view']=[];
			$data['head_company_view']=[];
		}
		elseif($groupby==2){
			$head_company_view = DB::table('company')->select('company_name','id','short_code as companycode')->orderBy('company_name','asc')->where('status','=','1')
			->where(function ($query) {
				$query->where('head_of_company', '=', '')
					->orWhere('head_of_company', '=', 0)
						->orWhereNull('head_of_company');
			})->get();
				//dd($head_company_view);
			foreach($head_company_view as $mkey => $company){
				$companyid = $company->id;
				//$company_str_List ="'".$companyid."'";
				$company_ids = DB::table('company')->where('head_of_company','=',$companyid)->orderBy('company_name','asc')->pluck('id')->toArray();
				$res_company = array_merge($company_ids, [$companyid]); 


				foreach($company as $newkey => $newvalue){
					$data['head_company_view'][$mkey][$newkey] = $newvalue;
				}
				$data['head_company_view'][$mkey]['company_list'] = $res_company;
				//$company_str_List ='';

			}
			//$data['company_view'] = CacheMonthEnd::getCompaniesByDate($data['month_year_full']);
			$data['union_branch_view']=[];
			$data['branch_view']=[];
		}
		else{
			$data['branch_view'] = CacheMonthEnd::getBranchByDate($data['month_year_full']);
			$data['union_branch_view']=[];
			$data['company_view']=[];
			$data['head_company_view']=[];
		}
		
		//$data['company_list'] = DB::table('company')->where('status','=','1')->get();
		/* $data['company_view'] = DB::table("membermonthendstatus as mm")->select('mm.BANK_CODE as company_id','c.company_name as company_name')
                                ->leftjoin('company as c','mm.BANK_CODE','=','c.id')
                                ->where('mm.StatusMonth', '=', date('Y-m-01',$datestring))
								->groupBY('mm.BANK_CODE')
								->get(); */
		//return $data['company_view'];
		return view('subscription.variation')->with('data', $data);
	}
	
	public function variationAll($lang, Request $request){
        ini_set('memory_limit', -1);
		ini_set('max_execution_time', 10000);
		//return strtotime('now');
		$datestring = $request->input('date');
		$groupby = $request->input('groupby');
		$display_subs = $request->input('display_subs');
        $variation = $request->input('variation');
        
        $data['sub_company'] = $request->input('sub_company');
        $data['unionbranch_id'] = $request->input('unionbranch_id');
        
		//$datestring = strtotime('2019-04-01');
		//return date('Y-m-01',strtotime($datestring));
		$data['month_year'] = date('M/Y',$datestring);
		$data['month_year_full'] = date('Y-m-01',$datestring);
		$data['groupby'] = $groupby;
		$data['DisplaySubscription'] = $display_subs;
		$data['print'] = $request->input('print');
        $data['variation'] = $request->input('variation');
        $data['inctype'] = $request->input('inctype');
       // $last_month = date('Y-m-01',strtotime($cur_date.' -1 Month'));
        $data['last_month_year']= date("Y-m-01", strtotime($data['month_year_full']." -1 Month"));
		if($groupby==1){
            if($data['unionbranch_id']!=''){
                $company_view = DB::table("mon_sub_member as mm")->select('cb.union_branch_id as union_branchid','u.union_branch as union_branch_name')
                                ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
                                ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                                ->leftjoin('membership as m','m.id','=','mm.MemberCode')
                                ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
                                ->leftjoin('company as c','cb.company_id','=','c.id')
                                ->leftjoin('union_branch as u','cb.union_branch_id','=','u.id')
                                ->where('ms.Date', '=', $data['month_year_full'])
								->where('mm.update_status', '=', 1)
                                ->where('mm.MemberCode', '!=', Null)
                                ->where('cb.union_branch_id', '=', $data['unionbranch_id']);
                $data['union_branch_view'] = $company_view->groupBY('cb.union_branch_id')
								->get();
            }else{
                $data['union_branch_view'] = CacheMonthEnd::getUnionBranchByDate($data['month_year_full']);
            }
			//$data['union_branch_view'] = CacheMonthEnd::getUnionBranchByDate($data['month_year_full']);
			$data['company_view']=[];
			$data['branch_view']=[];
		}
		elseif($groupby==2){
            if($data['sub_company']!=''){
                $company_view = DB::table("mon_sub_member as mm")->select('mc.CompanyCode as company_id','c.company_name as company_name')
                                ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
								->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
								->leftjoin('membership as m','m.id','=','mm.MemberCode')
								->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
								->leftjoin('company as c','mc.CompanyCode','=','c.id')
                                //->leftjoin('union_branch as u','cb.union_branch_id','=','u.id')
                                ->where('ms.Date', '=', $data['month_year_full'])
								->where('mm.update_status', '=', 1)
                                ->where('mm.MemberCode', '!=', Null)
                                ->where('mc.CompanyCode', '=', $data['sub_company']);
                $data['company_view'] = $company_view->groupBY('mc.CompanyCode')
								->get();
            }else{
                $data['company_view'] = CacheMonthEnd::getCompaniesByDate($data['month_year_full']);
            }
			//$data['company_view'] = CacheMonthEnd::getCompaniesByDate($data['month_year_full']);
			$data['union_branch_view']=[];
            $data['branch_view']=[];
            $head_company_view = DB::table('company')->select('company_name','id','short_code as companycode')->orderBy('company_name','asc')->where('status','=','1')
			->where(function ($query) {
				$query->where('head_of_company', '=', '')
					->orWhere('head_of_company', '=', 0)
						->orWhereNull('head_of_company');
			})->get();
				//dd($head_company_view);
			foreach($head_company_view as $mkey => $company){
				$companyid = $company->id;
				//$company_str_List ="'".$companyid."'";
				$company_ids = DB::table('company')->where('head_of_company','=',$companyid)->orderBy('company_name','asc')->pluck('id')->toArray();
				$res_company = array_merge($company_ids, [$companyid]); 


				foreach($company as $newkey => $newvalue){
					$data['head_company_view'][$mkey][$newkey] = $newvalue;
				}
				$data['head_company_view'][$mkey]['company_list'] = $res_company;
				//$company_str_List ='';

			}
		}
		else{
            if($data['sub_company']!=''){
                $company_view = DB::table("mon_sub_member as mm")->select('m.branch_id as branch_id','cb.branch_name as branch_name','c.company_name as company_name')
                                ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
                                ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                                ->leftjoin('membership as m','m.id','=','mm.MemberCode')
                                ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
                                ->leftjoin('company as c','cb.company_id','=','c.id')
                                //->leftjoin('union_branch as u','cb.union_branch_id','=','u.id')
                                ->where('ms.Date', '=', $data['month_year_full'])
								->where('mm.update_status', '=', 1)
                                ->where('mm.MemberCode', '!=', Null)
                                ->where('mc.CompanyCode', '=', $data['sub_company']);
                $data['branch_view'] = $company_view->groupBY('m.branch_id')
								->get();
            }else{
                 $data['branch_view'] = CacheMonthEnd::getBranchByDate($data['month_year_full']);
            }
			//$data['branch_view'] = CacheMonthEnd::getBranchByDate($data['month_year_full']);
			//dd($data['branch_view']);
			$data['union_branch_view']=[];
			$data['company_view']=[];
		}
		//$data['company_list'] = DB::table('company')->where('status','=','1')->get();
	/* 	$data['company_view'] = DB::table("membermonthendstatus as mm")->select('mm.BANK_CODE as company_id','c.company_name as company_name')
                                ->leftjoin('company as c','mm.BANK_CODE','=','c.id')
                                ->where('mm.StatusMonth', '=', date('Y-m-01',$datestring))
								->where('mm.BANK_CODE', '=', 2)
								->orWhere('mm.BANK_CODE', '=', 3)
								->groupBY('mm.BANK_CODE')
								->get(); */
		if($data['print']==1){
            if($data['variation']!=''){
                return view('subscription.variation_all')->with('data', $data);
            }else{
                return view('subscription.variation_difference')->with('data', $data);
            }
			
		}else{
			$new['data'] = $data;
			$new['data']['print'] = '0';
			//$data['print'] = 0;
			//PDF::loadHTML(view('subscription.variation_all')->with('data', $data))->setPaper('a4', 'landscape')->setWarnings(false)->save('myfile.pdf');
			/* $pdf = App::make('dompdf.wrapper');
			$pdf->loadHTML(view('subscription.variation_all')->with('data', $data));
			return $pdf->stream(); */
			$pdf = PDF::loadView('subscription.variation_all', $new);  
			return $pdf->download('subscription-variation.pdf'); 
			//return view('subscription.variation_all')->with($new);
			//dd($pdf);
		}
	}
	
	public function DeleteSubscription($lang, Request $request)
	{
		$sub_member_id = $request->input('sub_id');
		$member_data = DB::table("mon_sub_member as mm")->select('mm.MemberCode as member_id','ms.Date as date','mm.MonthlySubscriptionCompanyId as MonthlySubscriptionCompanyId')
                    ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
                    ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                    ->where('mm.id', '=', $sub_member_id)
                    ->first();
        $historydel = DB::table('membermonthendstatus')
                            ->where('StatusMonth','=',$member_data->date)
                            ->where('MEMBER_CODE','=',$member_data->member_id)
                            ->delete();
        if($historydel!=0){
            $historylast = DB::table('membermonthendstatus')
                            ->select('LASTPAYMENTDATE','SUBSCRIPTIONDUE','TOTALMONTHSPAID','TOTALMONTHSCONTRIBUTION','ACCBF','ACCSUBSCRIPTION','ACCINSURANCE')
                            ->where('MEMBER_CODE','=',$member_data->member_id)
                            ->orderBy('StatusMonth','DESC')
                            ->first();
            if($historylast!=Null){
               $LASTPAYMENTDATE = $historylast->LASTPAYMENTDATE;
               $SUBSCRIPTIONDUE = $historylast->SUBSCRIPTIONDUE;
               $payment_data = [
                    'last_paid_date' => $LASTPAYMENTDATE,
                    'totpaid_months' => $historylast->TOTALMONTHSPAID,
                    'totcontribution_months' => $historylast->TOTALMONTHSCONTRIBUTION,
                    'accbf_amount' => $historylast->ACCBF,
                    'accsub_amount' => $historylast->ACCSUBSCRIPTION,
                    'accins_amount' => $historylast->ACCINSURANCE,
                    'updated_by' => Auth::user()->id,
                ];
                DB::table('member_payments')->where('member_id', $member_data->member_id)->update($payment_data);
            }else{
                $payment_data = [
                    'last_paid_date' => Null,
                    'due_amount' => 0,
                    'updated_by' => Auth::user()->id,
                ];
                DB::table('member_payments')->where('member_id', $member_data->member_id)->update($payment_data);
            }
        }
        $matchel = DB::table('mon_sub_member_match')
                            ->where('mon_sub_member_id','=',$sub_member_id)
                            ->delete();
        $submemberdel = DB::table('mon_sub_member')
                            ->where('id','=',$sub_member_id)
                            ->delete();
        $enc_id = Crypt::encrypt($member_data->MonthlySubscriptionCompanyId); 
        if($submemberdel){
            
            return redirect(URL::to('/'.app()->getLocale().'/sub-company-members/'.$enc_id))->with('message', 'Subscription deleted Successfully');
        }else{
             return redirect(URL::to('/'.app()->getLocale().'/sub-company-members/'.$enc_id))->with('error', 'Failed to delete');
        }
	}


    public function saveSubscription($lang, Request $request){
        //return $request->all();
        $sub_member_id = $request->input('sub_member_auto_id');
        $sub_member_name = $request->input('sub_member_name');
        $sub_member_nric = $request->input('sub_member_nric');
        $sub_member_amount = $request->input('sub_member_amount');
        $m_subs_amt = number_format($sub_member_amount-($this->bf_amount+$this->ins_amount),2);
        if($sub_member_id!=''){
            $active_member_data = DB::table("mon_sub_member as mm")->select('mm.MemberCode as member_id','mm.Amount')
                    ->where('mm.id', '=', $sub_member_id)
                    ->whereNotNull('mm.MemberCode')
                    ->where('mm.update_status', '=', 1)
                    ->get();
            if(count($active_member_data)>0){
                $member_data = DB::table("mon_sub_member as mm")->select('mm.MemberCode as member_id','ms.Date as date','mm.MonthlySubscriptionCompanyId as MonthlySubscriptionCompanyId','mm.Amount')
                    ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
                    ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                    ->where('mm.id', '=', $sub_member_id)
                    ->first();
            }
            $matchel = DB::table('mon_sub_member_match')
                            ->where('mon_sub_member_id','=',$sub_member_id)
                            ->delete();

            $updata = ['MemberCode' => Null, 'StatusId' => Null, 'update_status' => 0, 'NRIC' => $sub_member_nric, 'Name' => $sub_member_name, 'Amount' => $sub_member_amount];
                        //DB::enableQueryLog();
            $savedata = MonthlySubscriptionMember::where('id',$sub_member_id)->update($updata);

            $sub_data = DB::table("mon_sub_member as mm")->select('mm.MonthlySubscriptionCompanyId')
                    ->where('mm.id', '=', $sub_member_id)
                    ->first();

            if(!empty($member_data)){
                $last_subscription_res = DB::table("member_payments as ms")->select('ms.last_paid_date as LASTPAYMENTDATE','ms.accins_amount as ACCINSURANCE','ms.accbf_amount as ACCBF','ms.accsub_amount as ACCSUBSCRIPTION','ms.sub_monthly_amount as SUBSCRIPTION_AMOUNT','ms.bf_monthly_amount as BF_AMOUNT','ms.totpaid_months as TOTALMONTHSPAID','ms.totdue_months as TOTALMONTHSDUE','ms.totcontribution_months as TOTALMONTHSCONTRIBUTION','ms.duesub_amount as SUBSCRIPTIONDUE','ms.dueins_amount as INSURANCEDUE')
                            ->where('ms.member_id','=',$member_data->member_id)
                            ->first();
                $last_sub_amt = number_format($member_data->Amount-($this->bf_amount+$this->ins_amount),2);
                if($last_sub_amt!=$m_subs_amt){
                    $payment_data = [
                        'sub_monthly_amount' => $m_subs_amt,
                        'totpaid_months' => $last_subscription_res->TOTALMONTHSPAID-1,
                        'totcontribution_months' => $last_subscription_res->TOTALMONTHSCONTRIBUTION-1,
                        'accbf_amount' => $last_subscription_res->ACCBF-$this->bf_amount,
                        'accsub_amount' => $last_subscription_res->ACCSUBSCRIPTION-$last_sub_amt,
                        'accins_amount' => $last_subscription_res->ACCINSURANCE-$this->ins_amount,
                        'updated_by' => Auth::user()->id,
                    ];
                    DB::table('member_payments')->where('member_id', $member_data->member_id)->update($payment_data);
                }

            }
            

            $enc_id = Crypt::encrypt($sub_data->MonthlySubscriptionCompanyId); 
            return redirect(URL::to('/'.app()->getLocale().'/scan-subscription/'.$enc_id))->with('message', 'Subscription updated Successfully');
        }
    }

    public function arrearRecordEdit($lang,$id)
    {
        $id = Crypt::decrypt($id);
       
         $data =  DB::table('arrear_entry as ar')->select('ar.no_of_months','m.id as memberid','c.id as companyid','cb.id as companybranchid','s.id as statusid','ar.id as arrearid','ar.nric','ar.arrear_date','ar.arrear_amount','cb.branch_name','c.company_name','s.status_name','m.member_number','m.name as membername','s.font_color')
        ->leftjoin('membership as m','ar.membercode','=','m.id')
        ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
        ->leftjoin('company as c','cb.company_id','=','c.id')
        ->leftjoin('status as s','m.status_id','=','s.id')
        ->where('ar.id','=',$id)->first();

        return view('subscription.edit_arrear_rows')->with('data',$data);
    }

    public function saveMonthendRows($lang,$ref,Request $request)
    {
        
        $entered_name = $request->input('entered_name');
        $ip_address = $request->ip();
       
        //return $request->all();
        $member_id = $request->input('member_id');
        $doj_date = $request->input('doj_date');
        $from_date = date('Y-m-d',strtotime($doj_date));
        $history_update_from = $from_date;
        $doj_subs = $request->input('doj_subs');
        $doj_bf = $request->input('doj_bf');
        $doj_ins = $request->input('doj_ins');
        $entrance_fee = $request->input('entrance_fee');
        $hq_fee = $request->input('hq_fee');
        $last_paid_date = Null;
        $memberdata =DB::table("membership")->select('branch_id','designation_id','status_id','name','member_number')->where('id','=',$member_id)->first();
        $branchdata = DB::table("company_branch")->where('id','=',$memberdata->branch_id)->first();
        if($doj_subs>0 || $doj_bf>0 || $doj_ins>0){
            $last_paid_date = $from_date;
            $doj_exists =  DB::table('membermonthendstatus')->where('MEMBER_CODE', '=', $member_id)->where('StatusMonth', '=', $from_date)->count();
            if($doj_exists!=0){
                $monthend_data = [
                    'ENTRYMODE' => 'S',
                    'SUBSCRIPTION_AMOUNT' => $doj_subs,
                    'TOTALSUBCRP_AMOUNT' => $doj_subs,
                    'BF_AMOUNT' => $doj_bf,
                    'TOTALBF_AMOUNT' => $doj_bf,
                    'INSURANCE_AMOUNT' => $doj_ins,
                    'TOTALINSURANCE_AMOUNT' => $doj_ins,
                    'TOTAL_MONTHS' => 1,
                    'TOTALMONTHSPAID' => 1,
                    'ACCSUBSCRIPTION' => $doj_subs,
                    'ACCBF' => $doj_bf,
                    'ACCINSURANCE' => $doj_ins,
                ];
                $upstatus = DB::table('membermonthendstatus')->where('MEMBER_CODE', '=', $member_id)->where('StatusMonth', '=', $from_date)->update($monthend_data);
            }else{
                
                $last_subscription_res = DB::table("member_payments as ms")->select('ms.last_paid_date as LASTPAYMENTDATE','ms.accins_amount as ACCINSURANCE','ms.accbf_amount as ACCBF','ms.accsub_amount as ACCSUBSCRIPTION','ms.sub_monthly_amount as SUBSCRIPTION_AMOUNT','ms.bf_monthly_amount as BF_AMOUNT','ms.totpaid_months as TOTALMONTHSPAID','ms.totdue_months as TOTALMONTHSDUE','ms.totcontribution_months as TOTALMONTHSCONTRIBUTION','ms.duesub_amount as SUBSCRIPTIONDUE','ms.dueins_amount as INSURANCEDUE','ms.duebf_amount as BFDUE')
                    ->where('ms.member_id','=',$member_id)
                    ->first();
                    //print_r($last_subscription_res);
                    //die;
                $monthend_data = [
                    'StatusMonth' => $from_date, 
                    'MEMBER_CODE' => $member_id,
                    'SUBSCRIPTION_AMOUNT' => $doj_subs,
                    'BF_AMOUNT' => $doj_bf,
                    'LASTPAYMENTDATE' => $from_date,
                    'TOTALSUBCRP_AMOUNT' => $doj_subs,
                    'TOTALBF_AMOUNT' => $doj_bf,
                    'TOTAL_MONTHS' => 1,
                    'BANK_CODE' => $branchdata->company_id,
                    'NUBE_BRANCH_CODE' => $branchdata->union_branch_id,
                    'BRANCH_CODE' => $memberdata->branch_id,
                    'MEMBERTYPE_CODE' => $memberdata->designation_id,
                    'ENTRYMODE' => 'S',
                    //'DEFAULTINGMONTHS' => 0,
                    'TOTALMONTHSDUE' => 0,
                    'TOTALMONTHSPAID' => 1,
                    'SUBSCRIPTIONDUE' => 0,
                    'BFDUE' => 0,
                    'ACCSUBSCRIPTION' => $doj_subs,
                    'ACCBF' => $doj_bf,
                    'ACCINSURANCE' => $doj_ins,
                   
                    'STATUS_CODE' => $memberdata->status_id,
                    'RESIGNED' => $memberdata->status_id==4 ? 1 : 0,
                    'ENTRY_DATE' => date('Y-m-d'),
                    'ENTRY_TIME' => date('h:i:s'),
                    'STRUCKOFF' => $memberdata->status_id==3 ? 1 : 0,
                    'INSURANCE_AMOUNT' => $doj_ins,
                    'TOTALINSURANCE_AMOUNT' => $doj_ins,
                    'TOTALMONTHSCONTRIBUTION' => 1,
                    'INSURANCEDUE' => 0,
                   
                ];
                DB::table($this->membermonthendstatus_table)->insert($monthend_data);
            }
        }
        
         

            $subscription_amount = $request->input('subscription_amount');
            
            $total_subscription_amount = $request->input('total_subscription_amount');
            $total_bf_amount = $request->input('total_bf_amount');
            $total_insurance_amount = $request->input('total_insurance_amount');
            $paycount = 0;
           
            if(isset($subscription_amount)){
                $historycount = count($subscription_amount);
               
                for ($i=0; $i < $historycount; $i++) { 
                    $month_auto_id = $request->input('month_auto_id')[$i];
                    $subs_amount = $request->input('subscription_amount')[$i];
                    $bf_amount = $request->input('bf_amount')[$i];
                    $insurance_amount = $request->input('insurance_amount')[$i];
                    $entry_date = $request->input('entry_date')[$i];
                    $entry_status_month = date('Y-m-d',strtotime($entry_date));
                    $total_months = $request->input('total_months')[$i];
                    if((($subs_amount!='' && $subs_amount!=0) || ($bf_amount!='' && $bf_amount!=0) || ($insurance_amount!='' && $insurance_amount!=0)) && $entry_status_month!=''){
                        $subs_amount = $subs_amount=='' ? 0 : $subs_amount;
                        $bf_amount = $bf_amount=='' ? 0 : $bf_amount;
                        $insurance_amount = $insurance_amount=='' ? 0 : $insurance_amount;
                        // if($entry_status_month=='2015-12-01'){
                        //     die($insurance_amount);
                        // }
                        //$entry_status_month;
                        // if($paycount==0){
                        //     $history_update_from = $entry_status_month;
                        // }
                        $total_months = $total_months==0 ? 1 : $total_months;
                        if($month_auto_id!=''){
                            $monthend_data = [
                                'ENTRYMODE' => 'S',
                                'SUBSCRIPTION_AMOUNT' => $subs_amount,
                                'TOTALSUBCRP_AMOUNT' => $subs_amount,
                                'BF_AMOUNT' => $bf_amount,
                                'TOTALBF_AMOUNT' => $bf_amount,
                                'INSURANCE_AMOUNT' => $insurance_amount,
                                'TOTALINSURANCE_AMOUNT' => $insurance_amount,
                                'TOTAL_MONTHS' => $total_months,
                            ];
                            $upstatus = DB::table('membermonthendstatus')->where('MEMBER_CODE', '=', $member_id)->where('StatusMonth', '=', $entry_status_month)->where('Id', '=', $month_auto_id)->update($monthend_data);
                        }else{
                            $mont_count = DB::table($this->membermonthendstatus_table)->where('StatusMonth', '=', $entry_status_month)->where('MEMBER_CODE', '=', $member_id)->count();

                            if($mont_count==0){
                                // $last_subscription_res = DB::table("member_payments as ms")->select('ms.last_paid_date as LASTPAYMENTDATE','ms.accins_amount as ACCINSURANCE','ms.accbf_amount as ACCBF','ms.accsub_amount as ACCSUBSCRIPTION','ms.sub_monthly_amount as SUBSCRIPTION_AMOUNT','ms.bf_monthly_amount as BF_AMOUNT','ms.totpaid_months as TOTALMONTHSPAID','ms.totdue_months as TOTALMONTHSDUE','ms.totcontribution_months as TOTALMONTHSCONTRIBUTION','ms.duesub_amount as SUBSCRIPTIONDUE','ms.dueins_amount as INSURANCEDUE','ms.duebf_amount as BFDUE')
                                // ->where('ms.member_id','=',$member_id)
                                // ->first();
                                //print_r($last_subscription_res);
                                //die;
                            $monthend_data = [
                                'StatusMonth' => $entry_status_month, 
                                'MEMBER_CODE' => $member_id,
                                'SUBSCRIPTION_AMOUNT' => $subs_amount,
                                'BF_AMOUNT' => $bf_amount,
                                'LASTPAYMENTDATE' => $entry_status_month,
                                'TOTALSUBCRP_AMOUNT' => $subs_amount,
                                'TOTALBF_AMOUNT' => $bf_amount,
                                'TOTAL_MONTHS' => $total_months,
                                'BANK_CODE' => $branchdata->company_id,
                                'NUBE_BRANCH_CODE' => $branchdata->union_branch_id,
                                'BRANCH_CODE' => $memberdata->branch_id,
                                'MEMBERTYPE_CODE' => $memberdata->designation_id,
                                'ENTRYMODE' => 'S',
                                'STATUS_CODE' => $memberdata->status_id,
                                'RESIGNED' => $memberdata->status_id==4 ? 1 : 0,
                                'ENTRY_DATE' => date('Y-m-d'),
                                'ENTRY_TIME' => date('h:i:s'),
                                'STRUCKOFF' => $memberdata->status_id==3 ? 1 : 0,
                                'INSURANCE_AMOUNT' => $insurance_amount,
                                'TOTALINSURANCE_AMOUNT' => $insurance_amount,
                            ];
                            DB::table($this->membermonthendstatus_table)->insert($monthend_data);
                            }
                            
                        }
                      
                        //return $db_arrear_date;
                        $paycount++;
    
                    }
                }
            }
            //dd('hi');
            

            if($history_update_from!=""){
                $is_old_record = DB::table($this->membermonthendstatus_table." as ms")
                ->select('ms.StatusMonth','ms.LASTPAYMENTDATE','ms.ACCBF','ms.ACCSUBSCRIPTION','ms.SUBSCRIPTION_AMOUNT','ms.BF_AMOUNT','ms.TOTALMONTHSPAID','ms.ACCINSURANCE','ms.TOTALMONTHSDUE','ms.SUBSCRIPTIONDUE','ms.TOTALMONTHSCONTRIBUTION','ms.INSURANCEDUE','ms.BFDUE','ms.INSURANCE_AMOUNT','ms.TOTAL_MONTHS')
                ->where('MEMBER_CODE', '=', $member_id)
                ->orderBY('StatusMonth','asc')
                ->limit(1)
                ->first();

                if($is_old_record->TOTALMONTHSDUE>1 || $is_old_record->TOTALMONTHSPAID>1){
                    $last_mont_record = $is_old_record;

                    $below_mont_records = DB::table($this->membermonthendstatus_table." as ms")
                    ->select('ms.StatusMonth','ms.Id','ms.SUBSCRIPTION_AMOUNT','ms.BF_AMOUNT','ms.INSURANCE_AMOUNT','ms.TOTAL_MONTHS','ms.arrear_status')
                    ->where('StatusMonth', '>', $is_old_record->StatusMonth)->where('MEMBER_CODE', '=', $member_id)
                    ->orderBY('StatusMonth','asc')
                    ->orderBY('arrear_status','asc')
                    ->get();
                }else{
                    $last_mont_record = DB::table($this->membermonthendstatus_table." as ms")
                    ->select('ms.StatusMonth','ms.LASTPAYMENTDATE','ms.ACCBF','ms.ACCSUBSCRIPTION','ms.SUBSCRIPTION_AMOUNT','ms.BF_AMOUNT','ms.TOTALMONTHSPAID','ms.ACCINSURANCE','ms.TOTALMONTHSDUE','ms.SUBSCRIPTIONDUE','ms.TOTALMONTHSCONTRIBUTION','ms.INSURANCEDUE','ms.BFDUE','ms.INSURANCE_AMOUNT','ms.TOTAL_MONTHS')
                    ->where('StatusMonth', '<', $history_update_from)->where('MEMBER_CODE', '=', $member_id)
                    ->orderBY('StatusMonth','desc')
                    ->limit(1)
                    ->first();

                    $below_mont_records = DB::table($this->membermonthendstatus_table." as ms")
                    ->select('ms.StatusMonth','ms.Id','ms.SUBSCRIPTION_AMOUNT','ms.BF_AMOUNT','ms.INSURANCE_AMOUNT','ms.TOTAL_MONTHS','ms.arrear_status')
                    ->where('StatusMonth', '>=', $history_update_from)->where('MEMBER_CODE', '=', $member_id)
                    ->orderBY('StatusMonth','asc')
                    ->orderBY('arrear_status','asc')
                    ->get();
                }

                

                $last_ACCINSURANCE = !empty($last_mont_record) ? $last_mont_record->ACCINSURANCE : 0;
                $last_ACCSUBSCRIPTION = !empty($last_mont_record) ? $last_mont_record->ACCSUBSCRIPTION : 0;
                $last_ACCBF = !empty($last_mont_record) ? $last_mont_record->ACCBF : 0;
                $last_SUBSCRIPTIONDUE = !empty($last_mont_record) ? $last_mont_record->SUBSCRIPTIONDUE : 0;
                $last_BFDUE = !empty($last_mont_record) ? $last_mont_record->BFDUE : 0;
                $last_INSURANCEDUE = !empty($last_mont_record) ? $last_mont_record->INSURANCEDUE : 0;
                $last_TOTALMONTHSDUE = !empty($last_mont_record) ? $last_mont_record->TOTALMONTHSDUE : 0;
                $last_TOTALMONTHSPAID = !empty($last_mont_record) ? $last_mont_record->TOTALMONTHSPAID : 0;
                $last_TOTALMONTHSCONTRIBUTION = !empty($last_mont_record) ? $last_mont_record->TOTALMONTHSCONTRIBUTION : 0;
                
                foreach($below_mont_records as $monthend){
                    $m_subs_amt = $monthend->SUBSCRIPTION_AMOUNT;
                    $m_bf_amt = $monthend->BF_AMOUNT;
                    $m_ins_amt = $monthend->INSURANCE_AMOUNT;
                    $m_total_months = $monthend->TOTAL_MONTHS;
                    $arrear_status = $monthend->arrear_status;

                    if($m_total_months>=1){
                        $new_ACCINSURANCE = $last_ACCINSURANCE+$m_ins_amt;
                        $new_ACCSUBSCRIPTION = $last_ACCSUBSCRIPTION+$m_subs_amt;
                        $new_ACCBF = $last_ACCBF+$m_bf_amt;
                        $new_SUBSCRIPTIONDUE = $last_SUBSCRIPTIONDUE;
                        $new_BFDUE = $last_BFDUE;
                        $new_INSURANCEDUE = $last_INSURANCEDUE;
                        if($m_total_months>=1){
                            if($arrear_status==1){
                                $new_TOTALMONTHSDUE = $last_TOTALMONTHSDUE-$m_total_months;
                            }else{
                                $new_TOTALMONTHSDUE = $m_total_months==1 ? $last_TOTALMONTHSDUE : ($last_TOTALMONTHSDUE+1)-$m_total_months;
                                //$new_TOTALMONTHSDUE = $m_total_months>1 ? ($last_TOTALMONTHSDUE+1)-$m_total_months : $last_TOTALMONTHSDUE-$m_total_months;
                            }
                        }else{
                            $new_TOTALMONTHSDUE = $last_TOTALMONTHSDUE;
                        }
                        
                        
                        $new_TOTALMONTHSPAID = $last_TOTALMONTHSPAID+$m_total_months;
                        $new_TOTALMONTHSCONTRIBUTION = $last_TOTALMONTHSCONTRIBUTION+$m_total_months;
                        $last_paid_date = $monthend->StatusMonth;
                    }else{
                        $new_ACCINSURANCE = $last_ACCINSURANCE;
                        $new_ACCSUBSCRIPTION = $last_ACCSUBSCRIPTION;
                        $new_ACCBF = $last_ACCBF;
                        $new_SUBSCRIPTIONDUE = $last_SUBSCRIPTIONDUE+$m_subs_amt;
                        $new_BFDUE = $last_BFDUE+$m_bf_amt;
                        $new_INSURANCEDUE = $last_INSURANCEDUE+$m_ins_amt;
                        $new_TOTALMONTHSDUE = $last_TOTALMONTHSDUE+1;
                        $new_TOTALMONTHSPAID = $last_TOTALMONTHSPAID;
                        $new_TOTALMONTHSCONTRIBUTION = $last_TOTALMONTHSCONTRIBUTION;
                        $last_paid_date = DB::table($this->membermonthendstatus_table." as ms")
                        ->select('ms.LASTPAYMENTDATE')
                        ->where('StatusMonth', '<', $monthend->StatusMonth)->where('MEMBER_CODE', '=', $member_id)
                        ->orderBY('StatusMonth','desc')
                        ->limit(1)
                        ->pluck('ms.LASTPAYMENTDATE')
                        ->first();
                    }
                
                    
                    $monthend_datas = [
                                'TOTALMONTHSDUE' => $new_TOTALMONTHSDUE,
                                'TOTALMONTHSPAID' => $new_TOTALMONTHSPAID,
                                'SUBSCRIPTIONDUE' => $new_SUBSCRIPTIONDUE,
                                'BFDUE' => $new_BFDUE,
                                'INSURANCEDUE' => $new_INSURANCEDUE,
                                'ACCSUBSCRIPTION' => $new_ACCSUBSCRIPTION,
                                'ACCBF' => $new_ACCBF,
                                'ACCINSURANCE' => $new_ACCINSURANCE,
                                'TOTALMONTHSCONTRIBUTION' => $new_TOTALMONTHSCONTRIBUTION,
                                'LASTPAYMENTDATE' => $last_paid_date,
                            ];
                    $m_upstatus = DB::table('membermonthendstatus')->where('Id', '=', $monthend->Id)->update($monthend_datas);

                    $last_ACCINSURANCE = $new_ACCINSURANCE;
                    $last_ACCSUBSCRIPTION = $new_ACCSUBSCRIPTION;
                    $last_ACCBF = $new_ACCBF;
                    $last_SUBSCRIPTIONDUE = $new_SUBSCRIPTIONDUE;
                    $last_BFDUE = $new_BFDUE;
                    $last_INSURANCEDUE = $new_INSURANCEDUE;
                    $last_TOTALMONTHSDUE = $new_TOTALMONTHSDUE;
                    $last_TOTALMONTHSPAID = $new_TOTALMONTHSPAID;
                    $last_TOTALMONTHSCONTRIBUTION = $new_TOTALMONTHSCONTRIBUTION;
                }

                $payment_data = [
                        'last_paid_date' => $last_paid_date,
                        'totpaid_months' => $last_TOTALMONTHSPAID,
                        'totcontribution_months' => $last_TOTALMONTHSCONTRIBUTION,
                        'totdue_months' => $last_TOTALMONTHSDUE,
                        'accbf_amount' => $last_ACCBF,
                        'accsub_amount' => $last_ACCSUBSCRIPTION,
                        'accins_amount' => $last_ACCINSURANCE,
                        'duebf_amount' =>  $last_BFDUE,
                        'dueins_amount' => $last_INSURANCEDUE,
                        'duesub_amount' =>  $last_SUBSCRIPTIONDUE,
                        'hq_fee' => $hq_fee,
                        'entrance_fee' => $entrance_fee,
                        'updated_by' => Auth::user()->id,
                    ];
                    DB::table('member_payments')->where('member_id', $member_id)->update($payment_data);
                if($last_TOTALMONTHSDUE<=3){
                    $m_status = 1;
                    DB::table('membership')->where('id', $member_id)->update(['status_id' => $m_status]);
                }else if($last_TOTALMONTHSDUE<=13){
                    $m_status = 2;
                    DB::table('membership')->where('id', $member_id)->update(['status_id' => $m_status]);
                }
                
            }
       
        
        Log::channel('historychangelog')->info('-----------------------------');
        Log::channel('historychangelog')->info('member id= '.$member_id.'&member_number='.$memberdata->member_number.'&name='.$memberdata->name);
        Log::channel('historychangelog')->info('Updated by= '.$entered_name.'&auth id='.Auth::user()->id.'&ip='.$ip_address);
        Log::channel('historychangelog')->info('-----------------------------');
        //Log::channel('historychangelog')->info('Updated ip: '.$ip_address);
        
        if($ref==1){
            return redirect($lang.'/history-list')->with('message','History Updated Successfully!!');
        }else{
            return redirect($lang.'/clean-membership')->with('message','History Updated Successfully!!');
        }
        

       
    }

    public function subMemberStatement()
    {
        $user_id = Auth::user()->id;
        $member_id = Membership::where('user_id','=',$user_id)->first();
        $id = $member_id->id;

                                            
        $data['to_date'] = date('Y-m-01');
        $data['member_id'] = $id;
        return view('subscription.statement')->with('data',$data);
      
    }


    public function arrearBulkEntryAdd(){
        $data['fee_view'] = Fee::all();
        return view('subscription.add_bulkarrearentry')->with('data',$data);
    }

    public function arrearBulkEntrySave(Request $request)
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
        $request->request->add(['created_by' => Auth::user()->id]);
        $request->request->add(['updated_by' => Auth::user()->id]);
        $request->request->add(['type' => 1]);
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
                return redirect($defdaultLang.'/sub-bulkarrearentry')->with('message','Entry Updated Succesfully');
            }
            else
            {
                $arrearid = $saveArrearEntry->id;
                $enc_arrearid = Crypt::encrypt($arrearid);
                return redirect($defdaultLang.'/sub-bulkarrearupdate/'.$enc_arrearid)->with('message','Entry Added Succesfully');
            }
        }
    }

    public function ViewBulkArrear($lang,$encid){
        $id = Crypt::decrypt($encid);
       
        $data =  DB::table('arrear_entry as ar')->select('ar.no_of_months','m.id as memberid','c.id as companyid','cb.id as companybranchid','s.id as statusid','ar.id as arrearid','ar.nric','ar.arrear_date','ar.arrear_amount','cb.branch_name','c.company_name','s.status_name','m.member_number','m.name as membername','s.font_color','ar.no_of_rows')
       ->leftjoin('membership as m','ar.membercode','=','m.id')
       ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
       ->leftjoin('company as c','cb.company_id','=','c.id')
       ->leftjoin('status as s','m.status_id','=','s.id')
       ->where('ar.id','=',$id)->first();

       return view('subscription.view_bulkarrearentry')->with('data',$data);
    }

    public function saveArrearBulkRows($lang,Request $request)
    {
        //return $request->all();
        $arrear_id = $request->input('arrear_id');
        $member_id = $request->input('member_id');
        $subscription_amount = $request->input('subscription_amount');
        $arrear_date = $request->input('arrear_date');
        $db_arrear_date = date('Y-m-d',strtotime($arrear_date));
        $arrearcount = count($subscription_amount);
        $total_subscription_amount = $request->input('total_subscription_amount');
        $total_bf_amount = $request->input('total_bf_amount');
        $total_insurance_amount = $request->input('total_insurance_amount');
        $arrear_status_date = date('Y-m-01',strtotime($arrear_date));
        $paycount = 0;
        $history_update_from = '';
        $memberdata =DB::table("membership")->select('branch_id','designation_id','status_id')->where('id','=',$member_id)->first();
         $branchdata = DB::table("company_branch")->where('id','=',$memberdata->branch_id)->first();

         $tot_subs=0;
         $tot_bf=0;
         $tot_ins=0;
         $total_paids = 0;
         DB::table('arrear_entry_records')->where('arrear_id','=',$arrear_id)->delete();

        for ($i=0; $i < $arrearcount; $i++) { 
            $subs_amount = $request->input('subscription_amount')[$i];
            $bf_amount = $request->input('bf_amount')[$i];
            $insurance_amount = $request->input('insurance_amount')[$i];
            $from_entry_date = $request->input('from_entry_date')[$i];
            $to_entry_date = $request->input('to_entry_date')[$i];
            $from_entry_month = date('Y-m-d',strtotime($from_entry_date));
            $to_entry_month = date('Y-m-d',strtotime($to_entry_date));

          
            if((($subs_amount!='' && $subs_amount!=0) || ($bf_amount!='' && $bf_amount!=0) || ($insurance_amount!='' && $insurance_amount!=0)) && $from_entry_month!='' && $to_entry_month!=''){

                

                

                $start = new DateTime($from_entry_month);
                $end   = new DateTime($to_entry_month);
                $diff  = $start->diff($end);
                $total_paids += $diff->format('%y') * 12 + $diff->format('%m') + 1;

                $subs_amount = $subs_amount=='' ? 0 : $subs_amount;
                $bf_amount = $bf_amount=='' ? 0 : $bf_amount;
                $insurance_amount = $insurance_amount=='' ? 0 : $insurance_amount;
                
                $tot_subs+=$subs_amount;
                $tot_bf+=$bf_amount;
                $tot_ins+=$insurance_amount;

                DB::table('arrear_entry_records')->insert(
                    ['arrear_id' => $arrear_id, 'from_date' => $from_entry_month, 'to_date' => $to_entry_month, 'subscription_amount' => $subs_amount, 'bf_amount' => $bf_amount, 'insurance_amount' => $insurance_amount]
                );
               
                //return $db_arrear_date;
                $paycount++;

            }
        }


        $monthend_data = [
            'arrear_status' => 1,
            'arrear_date' => $db_arrear_date,
            'ENTRYMODE' => 'A',
            'SUBSCRIPTION_AMOUNT' => $tot_subs,
            'TOTALSUBCRP_AMOUNT' => $tot_subs,
            'BF_AMOUNT' => $tot_bf,
            'TOTALBF_AMOUNT' => $tot_bf,
            'INSURANCE_AMOUNT' => $tot_ins,
            'TOTALINSURANCE_AMOUNT' => $tot_ins,
            'TOTAL_MONTHS' => $total_paids,
         ];
         //return $monthend_data;
        $mont_count = DB::table($this->membermonthendstatus_table)->where('StatusMonth', '=', $arrear_status_date)->where('MEMBER_CODE', '=', $member_id)->where('arrear_status', '=', 1)->count();
        
        if($mont_count==1){
          $upstatus = DB::table('membermonthendstatus')->where('MEMBER_CODE', '=', $member_id)->where('StatusMonth', '=', $arrear_status_date)->where('arrear_status', '=', 1)->update($monthend_data);
        }else{
            $memberdata =DB::table("membership")->select('branch_id','designation_id','status_id')->where('id','=',$member_id)->first();
            $mont_status = DB::table($this->membermonthendstatus_table)->where('StatusMonth', '=', $arrear_status_date)->where('MEMBER_CODE', '=', $member_id)->where('arrear_status', '!=', 1)->pluck('STATUS_CODE')->first();
            //return $mont_status;
            $branchdata = DB::table("company_branch")->where('id','=',$memberdata->branch_id)->first();
            $last_subscription_res = DB::table("member_payments as ms")->select('ms.last_paid_date as LASTPAYMENTDATE','ms.accins_amount as ACCINSURANCE','ms.accbf_amount as ACCBF','ms.accsub_amount as ACCSUBSCRIPTION','ms.sub_monthly_amount as SUBSCRIPTION_AMOUNT','ms.bf_monthly_amount as BF_AMOUNT','ms.totpaid_months as TOTALMONTHSPAID','ms.totdue_months as TOTALMONTHSDUE','ms.totcontribution_months as TOTALMONTHSCONTRIBUTION','ms.duesub_amount as SUBSCRIPTIONDUE','ms.dueins_amount as INSURANCEDUE','ms.duebf_amount as BFDUE')
                ->where('ms.member_id','=',$member_id)
                ->first();
            
            $paymember_status = $mont_status=='' ? $memberdata->status_id : $mont_status;
                //print_r($last_subscription_res);
                //die;
            $monthend_data = [
                'StatusMonth' => $arrear_status_date, 
                'MEMBER_CODE' => $member_id,
                'SUBSCRIPTION_AMOUNT' => $tot_subs,
                'BF_AMOUNT' => $tot_bf,
                'LASTPAYMENTDATE' => $arrear_status_date,
                'TOTALSUBCRP_AMOUNT' => $tot_subs,
                'TOTALBF_AMOUNT' => $tot_bf,
                'TOTAL_MONTHS' => $total_paids,
                'BANK_CODE' => $branchdata->company_id,
                'NUBE_BRANCH_CODE' => $branchdata->union_branch_id,
                'BRANCH_CODE' => $memberdata->branch_id,
                'MEMBERTYPE_CODE' => $memberdata->designation_id,
                'ENTRYMODE' => 'A',
                //'DEFAULTINGMONTHS' => 0,
                'TOTALMONTHSDUE' => !empty($last_subscription_res) ? $last_subscription_res->TOTALMONTHSDUE-$paycount : 0,
                'TOTALMONTHSPAID' => !empty($last_subscription_res) ? $last_subscription_res->TOTALMONTHSPAID+$paycount : 1,
                'SUBSCRIPTIONDUE' => !empty($last_subscription_res) ? $last_subscription_res->SUBSCRIPTIONDUE-$tot_subs : 0,
                'BFDUE' => !empty($last_subscription_res) ? $last_subscription_res->BFDUE-$tot_bf : 0,
                'ACCSUBSCRIPTION' => !empty($last_subscription_res) ? $last_subscription_res->ACCSUBSCRIPTION+$tot_subs : $tot_subs,
                'ACCBF' => !empty($last_subscription_res) ? $last_subscription_res->ACCBF+$tot_bf : $tot_bf,
                'ACCINSURANCE' => !empty($last_subscription_res) ? $last_subscription_res->ACCINSURANCE+$tot_ins : $tot_ins,
              
                'STATUS_CODE' => $paymember_status,
                'RESIGNED' => $paymember_status==4 ? 1 : 0,
                'ENTRY_DATE' => date('Y-m-d'),
                'ENTRY_TIME' => date('h:i:s'),
                'STRUCKOFF' => $paymember_status==3 ? 1 : 0,
                'INSURANCE_AMOUNT' => $tot_ins,
                'TOTALINSURANCE_AMOUNT' => $tot_ins,
                'TOTALMONTHSCONTRIBUTION' => !empty($last_subscription_res) ? $last_subscription_res->TOTALMONTHSCONTRIBUTION+1 : 1,
                'INSURANCEDUE' => !empty($last_subscription_res) ? $last_subscription_res->INSURANCEDUE-$tot_ins : 0,
                'arrear_status' => 1,
                'arrear_date' => $db_arrear_date,
                
            ];
            DB::table($this->membermonthendstatus_table)->insert($monthend_data);
            Log::debug(json_encode($monthend_data));
        }
        if($arrear_status_date!=""){
            $last_mont_record = DB::table($this->membermonthendstatus_table." as ms")
            ->select('ms.StatusMonth','ms.LASTPAYMENTDATE','ms.ACCBF','ms.ACCSUBSCRIPTION','ms.SUBSCRIPTION_AMOUNT','ms.BF_AMOUNT','ms.TOTALMONTHSPAID','ms.ACCINSURANCE','ms.TOTALMONTHSDUE','ms.SUBSCRIPTIONDUE','ms.TOTALMONTHSCONTRIBUTION','ms.INSURANCEDUE','ms.BFDUE','ms.INSURANCE_AMOUNT','ms.TOTAL_MONTHS')
            ->where('StatusMonth', '<=', $arrear_status_date)->where('MEMBER_CODE', '=', $member_id)
            ->where('arrear_status','!=',1)
            ->orderBY('StatusMonth','desc')
            ->limit(1)
            ->first();

            $below_mont_records = DB::table($this->membermonthendstatus_table." as ms")
            ->select('ms.StatusMonth','ms.Id','ms.SUBSCRIPTION_AMOUNT','ms.BF_AMOUNT','ms.INSURANCE_AMOUNT','ms.TOTAL_MONTHS','ms.arrear_status')
            ->where('StatusMonth', '>=', $arrear_status_date)->where('MEMBER_CODE', '=', $member_id)
            //->where('arrear_status','=',1)
            ->orderBY('StatusMonth','asc')
            ->orderBY('arrear_status','asc')
            ->get();

            $last_ACCINSURANCE = !empty($last_mont_record) ? $last_mont_record->ACCINSURANCE : 0;
            $last_ACCSUBSCRIPTION = !empty($last_mont_record) ? $last_mont_record->ACCSUBSCRIPTION : 0;
            $last_ACCBF = !empty($last_mont_record) ? $last_mont_record->ACCBF : 0;
            $last_SUBSCRIPTIONDUE = !empty($last_mont_record) ? $last_mont_record->SUBSCRIPTIONDUE : 0;
            $last_BFDUE = !empty($last_mont_record) ? $last_mont_record->BFDUE : 0;
            $last_INSURANCEDUE = !empty($last_mont_record) ? $last_mont_record->INSURANCEDUE : 0;
            $last_TOTALMONTHSDUE = !empty($last_mont_record) ? $last_mont_record->TOTALMONTHSDUE : 0;
            $last_TOTALMONTHSPAID = !empty($last_mont_record) ? $last_mont_record->TOTALMONTHSPAID : 0;
            $last_TOTALMONTHSCONTRIBUTION = !empty($last_mont_record) ? $last_mont_record->TOTALMONTHSCONTRIBUTION : 0;
            
            foreach($below_mont_records as $monthend){
                $m_subs_amt = $monthend->SUBSCRIPTION_AMOUNT;
                $m_bf_amt = $monthend->BF_AMOUNT;
                $m_ins_amt = $monthend->INSURANCE_AMOUNT;
                $m_total_months = $monthend->TOTAL_MONTHS;

                if($monthend->StatusMonth==$arrear_status_date && $monthend->arrear_status!=1){
                }else{
                if($m_total_months>0){
                    $newdues=$last_TOTALMONTHSDUE-$m_total_months;
                    if($last_TOTALMONTHSDUE-$m_total_months<0){
                        $newdues=0;
                    }
                    // if($monthend->StatusMonth==$arrear_status_date && $monthend->arrear_status!=1){
                    //     $newdues=$last_TOTALMONTHSDUE;
                    // }
                    $new_ACCINSURANCE = $last_ACCINSURANCE+$m_ins_amt;
                    $new_ACCSUBSCRIPTION = $last_ACCSUBSCRIPTION+$m_subs_amt;
                    $new_ACCBF = $last_ACCBF+$m_bf_amt;
                    $new_SUBSCRIPTIONDUE = $last_SUBSCRIPTIONDUE;
                    $new_BFDUE = $last_BFDUE;
                    $new_INSURANCEDUE = $last_INSURANCEDUE;
                    $new_TOTALMONTHSDUE = $newdues;
                    $new_TOTALMONTHSPAID = $last_TOTALMONTHSPAID+$m_total_months;
                    $new_TOTALMONTHSCONTRIBUTION = $last_TOTALMONTHSCONTRIBUTION+$m_total_months;
                }else{
                    
                    $new_ACCINSURANCE = $last_ACCINSURANCE;
                    $new_ACCSUBSCRIPTION = $last_ACCSUBSCRIPTION;
                    $new_ACCBF = $last_ACCBF;
                    $new_SUBSCRIPTIONDUE = $last_SUBSCRIPTIONDUE+$m_subs_amt;
                    $new_BFDUE = $last_BFDUE+$m_bf_amt;
                    $new_INSURANCEDUE = $last_INSURANCEDUE+$m_ins_amt;
                    $new_TOTALMONTHSDUE = $last_TOTALMONTHSDUE+1;
                    $new_TOTALMONTHSPAID = $last_TOTALMONTHSPAID;
                    $new_TOTALMONTHSCONTRIBUTION = $last_TOTALMONTHSCONTRIBUTION;
                }
              
                
                $monthend_datas = [
                            'TOTALMONTHSDUE' => $new_TOTALMONTHSDUE,
                            'TOTALMONTHSPAID' => $new_TOTALMONTHSPAID,
                            'SUBSCRIPTIONDUE' => $new_SUBSCRIPTIONDUE,
                            'BFDUE' => $new_BFDUE,
                            'INSURANCEDUE' => $new_INSURANCEDUE,
                            'ACCSUBSCRIPTION' => $new_ACCSUBSCRIPTION,
                            'ACCBF' => $new_ACCBF,
                            'ACCINSURANCE' => $new_ACCINSURANCE,
                            'TOTALMONTHSCONTRIBUTION' => $new_TOTALMONTHSCONTRIBUTION,
                        ];
                $m_upstatus = DB::table('membermonthendstatus')->where('Id', '=', $monthend->Id)->update($monthend_datas);

                $last_ACCINSURANCE = $new_ACCINSURANCE;
                $last_ACCSUBSCRIPTION = $new_ACCSUBSCRIPTION;
                $last_ACCBF = $new_ACCBF;
                $last_SUBSCRIPTIONDUE = $new_SUBSCRIPTIONDUE;
                $last_BFDUE = $new_BFDUE;
                $last_INSURANCEDUE = $new_INSURANCEDUE;
                $last_TOTALMONTHSDUE = $new_TOTALMONTHSDUE;
                $last_TOTALMONTHSPAID = $new_TOTALMONTHSPAID;
                $last_TOTALMONTHSCONTRIBUTION = $new_TOTALMONTHSCONTRIBUTION;
                }

            }

            $payment_data = [
                    //'last_paid_date' => $db_arrear_date,
                    'totpaid_months' => $last_TOTALMONTHSPAID,
                    'totcontribution_months' => $last_TOTALMONTHSCONTRIBUTION,
                    'totdue_months' => $last_TOTALMONTHSDUE,
                    'accbf_amount' => $last_ACCBF,
                    'accsub_amount' => $last_ACCSUBSCRIPTION,
                    'accins_amount' => $last_ACCINSURANCE,
                    'duebf_amount' =>  $last_BFDUE,
                    'dueins_amount' => $last_INSURANCEDUE,
                    'duesub_amount' =>  $last_SUBSCRIPTIONDUE,
                    'updated_by' => Auth::user()->id,
                ];
                DB::table('member_payments')->where('member_id', $member_id)->update($payment_data);
            if($last_TOTALMONTHSDUE<=3){
                $m_status = 1;
                DB::table('membership')->where('id', $member_id)->update(['status_id' => $m_status]);
                
                $monthend_datasone = [
                    'STATUS_CODE' => $m_status,
                    'RESIGNED' => $m_status==4 ? 1 : 0,
                    'STRUCKOFF' => $m_status==3 ? 1 : 0,
                ];
                $m_upstatusone = DB::table('membermonthendstatus')->where('MEMBER_CODE', '=', $member_id)->where('StatusMonth', '>=', $arrear_status_date)->update($monthend_datasone);
            }else if($last_TOTALMONTHSDUE<=13){
                $m_status = 2;
                DB::table('membership')->where('id', $member_id)->update(['status_id' => $m_status]);

                $monthend_datasone = [
                    'STATUS_CODE' => $m_status,
                    'RESIGNED' => $m_status==4 ? 1 : 0,
                    'STRUCKOFF' => $m_status==3 ? 1 : 0,
                ];
                $m_upstatusone = DB::table('membermonthendstatus')->where('MEMBER_CODE', '=', $member_id)->where('StatusMonth', '>=', $arrear_status_date)->update($monthend_datasone);
            }
            if($arrear_id!=''){
                $arrear_data = [
                    'updated_at' => date('Y-m-d h:i:s'),
                    'updated_by' => Auth::user()->id,
                ];
                $arrear_upstatus = DB::table('arrear_entry')->where('id', '=', $arrear_id)->update($arrear_data);
            }
           
            
        }


        return redirect($lang.'/sub-arrearentry')->with('message','Arrear Entry Updated Successfully!!');

       
    }


    public function arrearRecordBulkEdit($lang,$encid)
    {
        $id = Crypt::decrypt($encid);
       
         $data['data'] =  DB::table('arrear_entry as ar')->select('ar.no_of_months','m.id as memberid','c.id as companyid','cb.id as companybranchid','s.id as statusid','ar.id as arrearid','ar.nric','ar.arrear_date','ar.arrear_amount','cb.branch_name','c.company_name','s.status_name','m.member_number','m.name as membername','s.font_color')
        ->leftjoin('membership as m','ar.membercode','=','m.id')
        ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
        ->leftjoin('company as c','cb.company_id','=','c.id')
        ->leftjoin('status as s','m.status_id','=','s.id')
        ->where('ar.id','=',$id)->first();

        $data['records'] = DB::table('arrear_entry_records')->where('arrear_id','=',$id)->get();

        return view('subscription.edit_bulk_arrear_rows')->with('data',$data);
    }

    public function VarianceMembersList($lang, Request $request){
		$get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;
        $user_id = Auth::user()->id;
        $data['type'] = $request->input('type');
        $data['date'] = date('Y-m-d',$request->input('date'));
        $data['union_branchid'] = $request->input('union_branchid');
        $data['company_id'] = $request->input('company_id');
        $data['branch_id'] = $request->input('branch_id');
        return view('subscription.variance_members')->with('data',$data);
    }

    public function InvalidSubscription() {
        $get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;
        $user_id = Auth::user()->id;
        //$status_all = Status::where('status',1)->get();
      
        
        $data['date'] = date('Y-m-01');
        $company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id')->first();
        $company_name = CommonHelper::getCompanyName($company_id);

        $company_ids = DB::table('company_branch as cb')
							->leftjoin('company as c','cb.company_id','=','c.id')
							->leftjoin('union_branch as u','cb.union_branch_id','=','u.id')
							->where('cb.company_id', '=',$company_id)
							->groupBY('c.id')
                            ->pluck('c.id');
                            
        $company_str_List ='';
        $slno=0;
        foreach($company_ids as $cids){
            if($slno!=0){
                $company_str_List .=',';
            }
            $company_str_List .="'".$cids."'";
            $slno++;
        }

        $cond ='';
        if(isset($company_id) && $company_id!=''){
            $cond =" AND m.MonthlySubscriptionCompanyId = '$company_id'";
        }
        $struckoff_members_data = DB::select(DB::raw('select member.name as member_name, member.member_number as member_number,m.Amount as Amount, c.company_name as company_name, member.new_ic as ic,"0" as due,s.status_name as status_name, `member`.`id` as memberid, m.id as sub_member_id,m.Name as up_member_name,m.NRIC as up_nric,m.approval_status from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` left join membership as member on `member`.`id` = `m`.`MemberCode` left join company as c on `c`.`id` = `sc`.`CompanyCode` left join status as s on `s`.`id` = `m`.`StatusId`  where m.StatusId="3" '.$cond.' AND `sm`.`Date`="'.$data['date'].'" AND `c`.`id` IN ('.$company_str_List.') '));
        $data['struckoff_members'] = $struckoff_members_data;

        $resigned_members_data = DB::select(DB::raw('select member.name as member_name, member.member_number as member_number,m.Amount as Amount, c.company_name as company_name, member.new_ic as ic,"0" as due,s.status_name as status_name, `member`.`id` as memberid, m.id as sub_member_id,m.Name as up_member_name,m.NRIC as up_nric,m.approval_status from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` left join membership as member on `member`.`id` = `m`.`MemberCode` left join company as c on `c`.`id` = `sc`.`CompanyCode` left join status as s on `s`.`id` = `m`.`StatusId`  where m.StatusId="3" '.$cond.' AND `sm`.`Date`="'.$data['date'].'" AND `c`.`id` IN ('.$company_str_List.') '));
        $data['resigned_members'] = $resigned_members_data;

        $data['company_name'] = $company_name; 
        $data['company_id'] = $company_id; 

        
        return view('subscription.invalid_subscription')->with('data', $data);
    } 
    
    public function ViewStruckoff($lang, Request $request){
        $get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;
        $user_id = Auth::user()->id;

        $entry_date = $request->input('entry_date_one');  
        $datearr = explode("/",$entry_date);  
        $monthname = $datearr[0];
        $year = $datearr[1];
        $full_date = date('Y-m-d',strtotime('01-'.$monthname.'-'.$year));
        $data['date'] = date('Y-m-01',strtotime($full_date));
        $company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id')->first();
        $company_name = CommonHelper::getCompanyName($company_id);


        $company_ids = DB::table('company_branch as cb')
							->leftjoin('company as c','cb.company_id','=','c.id')
							->leftjoin('union_branch as u','cb.union_branch_id','=','u.id')
							->where('cb.company_id', '=',$company_id)
							->groupBY('c.id')
                            ->pluck('c.id');
                            
        $company_str_List ='';
        $slno=0;
        foreach($company_ids as $cids){
            if($slno!=0){
                $company_str_List .=',';
            }
            $company_str_List .="'".$cids."'";
            $slno++;
        }

        $cond ='';
        // if(isset($company_id) && $company_id!=''){
        //     $cond =" AND m.MonthlySubscriptionCompanyId = '$company_id'";
        // }
        $struckoff_members_data = DB::select(DB::raw('select member.name as member_name, member.member_number as member_number,m.Amount as Amount, c.company_name as company_name, member.new_ic as ic,"0" as due,s.status_name as status_name, `member`.`id` as memberid, m.id as sub_member_id,m.Name as up_member_name,m.NRIC as up_nric,m.approval_status from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` left join membership as member on `member`.`id` = `m`.`MemberCode` left join company as c on `c`.`id` = `sc`.`CompanyCode` left join status as s on `s`.`id` = `m`.`StatusId`  where m.StatusId="3" '.$cond.' AND `sm`.`Date`="'.$data['date'].'" AND `c`.`id` IN ('.$company_str_List.') '));
        $data['struckoff_members'] = $struckoff_members_data;

        $resigned_members_data = DB::select(DB::raw('select member.name as member_name, member.member_number as member_number,m.Amount as Amount, c.company_name as company_name, member.new_ic as ic,"0" as due,s.status_name as status_name, `member`.`id` as memberid, m.id as sub_member_id,m.Name as up_member_name,m.NRIC as up_nric,m.approval_status from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` left join membership as member on `member`.`id` = `m`.`MemberCode` left join company as c on `c`.`id` = `sc`.`CompanyCode` left join status as s on `s`.`id` = `m`.`StatusId`  where m.StatusId="4" '.$cond.' AND `sm`.`Date`="'.$data['date'].'" AND `c`.`id` IN ('.$company_str_List.') '));
        $data['resigned_members'] = $resigned_members_data;

        $data['company_name'] = $company_name; 
        $data['company_id'] = $company_id; 

        //return $data['struckoff_members'] ;
       
        return view('subscription.invalid_subscription')->with('data', $data);
    }

    public function ViewFollowup($lang, Request $request){

    }

    public function companyMembersSummary($lang,$id)
    {
        $company_auto_id = Crypt::decrypt($id);
        $data['company_auto_id'] = $company_auto_id;
        $status_all = Status::where('status','=',1)->get();  

       

        $get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;
        $user_id = Auth::user()->id;

        //if($user_role=='company'){
            $cond ='';
            if(isset($company_auto_id) && $company_auto_id!=''){
                $cond =" AND m.MonthlySubscriptionCompanyId = '$company_auto_id'";
            }

            $members_data = DB::select(DB::raw('SELECT member.name AS member_name, member.member_number AS member_number, m.Amount AS Amount, member.new_ic AS ic, "0" AS due, s.status_name AS status_name, `member`.`id` AS memberid, m.id AS sub_member_id, m.Name AS up_member_name, m.NRIC AS up_nric, m.approval_status FROM mon_sub_member_match as mm LEFT JOIN `mon_sub_member` AS `m` ON mm.mon_sub_member_id=m.id left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` left join membership as member on `member`.`id` = `m`.`MemberCode`  left join status as s on `s`.`id` = `m`.`StatusId`  where 1=1 '.$cond.' AND mm.match_id in (2,4,6,7)'));

            $data['subsdata'] = DB::table('mon_sub_company as sc')->select('s.Date','c.short_code','c.company_name','c.id as company_id')
            ->leftjoin('mon_sub as s', 's.id' ,'=','sc.MonthlySubscriptionId')
            ->leftjoin('company as c','c.id','=','sc.CompanyCode')
            ->where('sc.id','=',$company_auto_id)
           // ->where('mon_sub_company.id','=',$company_auto_id)
            //->pluck('s.Date')
            ->first();

            // //$data['matched_count'] = DB::table('mon_sub_member as mm')->select('*')
            // ->leftjoin('mon_sub_company as sc', 'sc.id' ,'=','mm.MonthlySubscriptionCompanyId')
            // //->leftjoin('mon_sub as s', 's.id' ,'=','sc.MonthlySubscriptionId')
            // ->leftjoin('company as c','c.id','=','sc.CompanyCode')
            // ->where('sc.id','=',$company_auto_id)
            // ->where('mm.StatusId','<=',2)
            // ->count();

            $data['company_subscription_list'] = DB::table('mon_sub_member as mm')->select('*')
            ->leftjoin('mon_sub_company as sc', 'sc.id' ,'=','mm.MonthlySubscriptionCompanyId')
            //->leftjoin('mon_sub as s', 's.id' ,'=','sc.MonthlySubscriptionId')
            ->leftjoin('company as c','c.id','=','sc.CompanyCode')
            ->where('sc.id','=',$company_auto_id)
            //->where('mm.STatusId','<=',2)
            ->count();

            $data['matched_count'] = $data['company_subscription_list']-count($members_data);

            $data['doj_count'] = DB::table('mon_sub_member as mm')->select('*')
            ->leftjoin('mon_sub_company as sc', 'sc.id' ,'=','mm.MonthlySubscriptionCompanyId')
            ->leftjoin('mon_sub as s', 's.id' ,'=','sc.MonthlySubscriptionId')
            ->leftjoin('company as c','c.id','=','sc.CompanyCode')
            ->leftjoin('membership as m','m.id','=','mm.MemberCode')
            ->where('sc.id','=',$company_auto_id)
            ->where('s.Date','=',DB::raw('DATE_FORMAT(m.doj, "%Y-%m-01")'))
            ->count();

            $members_amt = DB::select(DB::raw('SELECT sum(Amount) as Amount FROM mon_sub_member_match as mm LEFT JOIN `mon_sub_member` AS `m` ON mm.mon_sub_member_id=m.id left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` left join membership as member on `member`.`id` = `m`.`MemberCode`  left join status as s on `s`.`id` = `m`.`StatusId`  where 1=1 '.$cond.' AND mm.match_id =4 '));

            $misbankamt = 0;
            if(!empty($members_amt)){
                $misbankamt = $members_amt[0]->Amount;
            }

            $matchedamt = DB::table('mon_sub_member as mm')->select(DB::raw('sum(Amount) as Amount'))
            ->leftjoin('mon_sub_company as sc', 'sc.id' ,'=','mm.MonthlySubscriptionCompanyId')
            ->leftjoin('mon_sub as s', 's.id' ,'=','sc.MonthlySubscriptionId')
            ->leftjoin('company as c','c.id','=','sc.CompanyCode')
            ->leftjoin('membership as m','m.id','=','mm.MemberCode')
            ->where('sc.id','=',$company_auto_id)
            ->where('mm.StatusId','<=',2)
            ->where('s.Date','<>',DB::raw('DATE_FORMAT(m.doj, "%Y-%m-01")'))
            ->pluck('Amount')
            ->first();

            $data['matched_amount'] = $matchedamt-$misbankamt;
            //return $data['matched_amount'] ;

            

            //dd($members_amt);

            

            if($user_role=='company'){
                $company_id = DB::table('company_branch as cb')->select('cb.company_id')
                ->leftjoin('company as c', 'c.id' ,'=','cb.company_id')
                ->where('cb.user_id','=',$user_id)
                ->pluck('cb.company_id')
                ->first();
            }else{
                $company_id = $data['subsdata']->company_id;
            }
            $data['companyid'] = $company_id;
           

            $data['members_count'] = DB::table('membership as m')
            ->leftjoin('company_branch as cb', 'cb.id' ,'=','m.branch_id')
            ->leftjoin('company as c', 'c.id' ,'=','cb.company_id')
            ->where('status_id','<=',2)->where('c.id','=',$company_id)->count();
            return view('subscription.company_members_summary')->with('data', $data);
        // }else{
        //     return 'Invalid access';
        // }

       
    }

    public function SummarystatusCountView($lang, Request $request){
		$get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;
        $user_id = Auth::user()->id;
		
        $status = $request->input('status');
        $date = $request->input('date');
        $company_id = $request->input('company_id');
       
		$defaultdate = date('Y-m-01',$date);
		$data['company_id'] = $company_id;
        $filter_date = date('Y-m-01',$date);
        $data['status'] = $status;
        $data['filter_date'] = strtotime(date('Y-m-01',strtotime($defaultdate)));

        //if($user_role=='company'){
            // $user_company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id')->first();
			// $company_ids = DB::table('company_branch as cb')
			// 				->leftjoin('company as c','cb.company_id','=','c.id')
			// 				->leftjoin('union_branch as u','cb.union_branch_id','=','u.id')
			// 				->where('cb.company_id', '=',$user_company_id)
			// 				->groupBY('c.id')
            //                 ->pluck('c.id');

            // $company_str_List ='';
            // $slno=0;
            // foreach($company_ids as $cids){
            //     if($slno!=0){
            //         $company_str_List .=',';
            //     }
            //     $company_str_List .="'".$cids."'";
            //     $slno++;
            // }

            if($status==1){
                $cond ='';
                if(isset($company_id) && $company_id!=''){
                    $cond =" AND m.MonthlySubscriptionCompanyId = '$company_id'";
                }
               
                $members_data = DB::select(DB::raw('select member.name as member_name, member.member_number as member_number,m.Amount as Amount, member.new_ic as ic,"0" as due,s.status_name as status_name, `member`.`id` as memberid, m.id as sub_member_id,m.Name as up_member_name,m.NRIC as up_nric,m.approval_status from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` left join membership as member on `member`.`id` = `m`.`MemberCode` left join status as s on `s`.`id` = `m`.`StatusId` where m.StatusId<="2" '.$cond));
                $data['member'] = $members_data;
              //  dd($members_data);
                
            }else{
                $cond ='';
                if(isset($company_id) && $company_id!=''){
                    $cond =" AND m.MonthlySubscriptionCompanyId = '$company_id'";
                }
               
                $members_data = DB::select(DB::raw('SELECT member.name AS member_name, member.member_number AS member_number, m.Amount AS Amount, member.new_ic AS ic, "0" AS due, s.status_name AS status_name, `member`.`id` AS memberid, m.id AS sub_member_id, m.Name AS up_member_name, m.NRIC AS up_nric, m.approval_status FROM mon_sub_member_match as mm LEFT JOIN `mon_sub_member` AS `m` ON mm.mon_sub_member_id=m.id left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` left join membership as member on `member`.`id` = `m`.`MemberCode`  left join status as s on `s`.`id` = `m`.`StatusId`  where 1=1 '.$cond.' AND mm.match_id in (2,4,6,7)'));
                // dd('select member.name as member_name, member.member_number as member_number,m.Amount as Amount, member.new_ic as ic,"0" as due,s.status_name as status_name, `member`.`id` as memberid, m.id as sub_member_id,m.Name as up_member_name,m.NRIC as up_nric,m.approval_status from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` left join membership as member on `member`.`id` = `m`.`MemberCode`  left join status as s on `s`.`id` = `m`.`StatusId`  where 1=1 '.$cond.' AND (m.StatusId>2 OR m.MemberCode is Null)');
                $data['member'] = $members_data;
            }
           
            $total_amt = 0;
            foreach($members_data as $member){
                $total_amt += $member->Amount;
            }
            $data['total_amt'] = $total_amt;

            return view('subscription.summary_status')->with('data',$data);
       // }


    }

    public function saveMismatched($lang, Request $request){
        //return 1;
        $sub_member_id = $request->input('sub_member_id');
        $description = $request->input('description');
        $reasonid = $request->input('reasonid');
		$match_data = DB::table('mon_sub_member_match')->where('mon_sub_member_id','=',$sub_member_id)->get();
		$total_match_count = 0;
		$member_code = '';
		$member_status = '';
		$member_match = '';
        $approval_masg = 'Updated Succesfully';

        $mismatchedcount = DB::table('mon_sub_remarks')->where('mon_sub_member_id','=',$sub_member_id)->where('type','=',0)->count();
        //return $mismatchedcount;

        $insertdata = [];
        $insertdata['mon_sub_member_id'] = $sub_member_id;
        $insertdata['type'] = 0;
        $insertdata['approval_status'] = 1;
        $insertdata['reason'] = '';
        $insertdata['remarks'] = $description;
        if($mismatchedcount==0){
            $saveunmatch = DB::table('mon_sub_remarks')->insert($insertdata);
        }else{
            $saveunmatch = DB::table('mon_sub_remarks')
            ->where('mon_sub_member_id', $sub_member_id)
            ->update($insertdata);
        }
        
        $member_id = DB::table('mon_sub_member')->where('id','=',$sub_member_id)->pluck('MemberCode')->first();
	
        Artisan::call('cache:clear');
		$return_data = ['status' => 1, 'message' => $approval_masg, 'sub_member_auto_id' => $sub_member_id, 'member_number' => $member_id, 'member_status' => $member_status, 'approval_status' => 1, 'member_match' => $member_match];
		echo json_encode($return_data);
    }
    
    public function sub_company_list() {
        $userid = Auth::user()->id;
        $get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;
      
        $data = [];
     
         
        //$data['company_list'] = $company_list;
       
        
        return view('subscription.subscription_company_list')->with('data', $data);
    }

    public function varianceList($lang, Request $request){
		$data['to_month_year'] = date('M/Y');
		$data['from_year_full'] = date("Y-m-01", strtotime("first day of previous month"));
        $data['to_year_full']= date('Y-m-01');

        $userid = Auth::user()->id;
		$companyid = CommonHelper::getCompanyID($userid);
        
        $subcompanyid = DB::table('mon_sub_company as sc')
                        ->leftjoin('mon_sub as ms','ms.id','=','sc.MonthlySubscriptionId')
                        ->where('sc.CompanyCode','=',$companyid)
                        ->where('ms.Date','=',$data['to_year_full'])
                        ->pluck('sc.id')
                        ->first();
        
        $cond ='';
        if(isset($subcompanyid) && $subcompanyid!=''){
            $cond =" AND m.MonthlySubscriptionCompanyId = '$subcompanyid'";
        }
       
       // $members_data = DB::select(DB::raw('select member.name as member_name, member.member_number as member_number,m.Amount as Amount, member.new_ic as ic,"0" as due,s.status_name as status_name, `member`.`id` as memberid, m.id as sub_member_id,m.Name as up_member_name,m.NRIC as up_nric,m.approval_status from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` left join membership as member on `member`.`id` = `m`.`MemberCode`  left join status as s on `s`.`id` = `m`.`StatusId`  where 1=1 '.$cond.' AND (m.StatusId>"2" OR m.MemberCode is null)'));

        $members_data = DB::select(DB::raw('SELECT member.name AS member_name, member.member_number AS member_number, m.Amount AS Amount, member.new_ic AS ic, "0" AS due, s.status_name AS status_name, `member`.`id` AS memberid, m.id AS sub_member_id, m.Name AS up_member_name, m.NRIC AS up_nric, m.approval_status FROM mon_sub_member_match as mm LEFT JOIN `mon_sub_member` AS `m` ON mm.mon_sub_member_id=m.id left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` left join membership as member on `member`.`id` = `m`.`MemberCode`  left join status as s on `s`.`id` = `m`.`StatusId`  where 1=1 '.$cond.' AND mm.match_id in (2,4,6,7)'));
		
        $data['company_view']=[];
        $data['diff_in_months']=1;
        $data['branch_view']=[];
        $data['submembers'] = $members_data;
        $data['companyid'] = $companyid;
	
		return view('subscription.variation_bank_members')->with('data', $data);
    }
    
    public function varianceFilter($lang, Request $request){
        //return $request->all();
        $from_date_str = $request->input('from_date');
        $to_date_str = $request->input('to_date');
        $companyid = $request->input('companyid');

        $to_date = explode("/",$to_date_str);
        $from_date = explode("/",$from_date_str);
      
       // $fm_date[1].'-'.$fm_date[0].'-'.'01';
        $todatestring = strtotime($to_date[1].'-'.$to_date[0].'-'.'01');
        
        $fromdatestring = strtotime($from_date[1].'-'.$from_date[0].'-'.'01');

        $to = Carbon::createFromFormat('Y-m-d H:s:i', date('Y-m-01',$todatestring).' '.date('H:i:s'));
        $from = Carbon::createFromFormat('Y-m-d H:s:i',  date('Y-m-01',$fromdatestring).' '.date('H:i:s'));
        //$diff_in_months = $to->diffInMonths($from);
        $diff_in_months = 1;
        

       // $from_date = explode("/",$from_date_str);
		$data['to_month_year'] = $to_date_str;
		$data['from_year_full'] = date('Y-m-01',$fromdatestring);
        $data['to_year_full']= date('Y-m-01',$todatestring);
        //return 1;

        // $submembers = DB::table('mon_sub_member as mm')
        // ->select('mm.MemberCode as member_id','mm.Amount','mm.NRIC','mm.Name','mm.id as submemberid')
        // ->leftjoin('mon_sub_company as sc','sc.id','=','mm.MonthlySubscriptionCompanyId')
        // ->leftjoin('mon_sub as ms','ms.id','=','sc.MonthlySubscriptionId')
        // ->leftjoin('company as c','c.id','=','sc.CompanyCode')
        //             ->where('sc.CompanyCode','=',$companyid)
        //             ->where('ms.Date','=', $data['to_year_full'])
        //             ->get();
        //dd($submembers);

        $subcompanyid = DB::table('mon_sub_company as sc')
                        ->leftjoin('mon_sub as ms','ms.id','=','sc.MonthlySubscriptionId')
                        ->where('sc.CompanyCode','=',$companyid)
                        ->where('ms.Date','=',$data['to_year_full'])
                        ->pluck('sc.id')
                        ->first();
        
        $cond ='';
        if(isset($subcompanyid) && $subcompanyid!=''){
            $cond =" AND m.MonthlySubscriptionCompanyId = '$subcompanyid'";
        }
       
        $members_data = DB::select(DB::raw('SELECT member.name AS member_name, member.member_number AS member_number, m.Amount AS Amount, member.new_ic AS ic, "0" AS due, s.status_name AS status_name, `member`.`id` AS memberid, m.id AS sub_member_id, m.Name AS up_member_name, m.NRIC AS up_nric, m.approval_status FROM mon_sub_member_match as mm LEFT JOIN `mon_sub_member` AS `m` ON mm.mon_sub_member_id=m.id left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` left join membership as member on `member`.`id` = `m`.`MemberCode`  left join status as s on `s`.`id` = `m`.`StatusId`  where 1=1 '.$cond.' AND mm.match_id in (2,4,6,7)'));

        $data['submembers'] = $members_data;
		
        $data['company_view']=[];
        $data['diff_in_months']=$diff_in_months;
        $data['companyid'] = $companyid;
      //  $data['submembers']=[];
        $data['branch_view']=[];
        
        //return $data;
	
		return view('subscription.variation_bank_members')->with('data', $data);
    }


    public function saveVariance($lang, Request $request){
        //return 1;
        $sub_member_id = $request->input('vsub_member_id');
        $description = $request->input('vdescription');
        $reasonid = $request->input('vreasonid');
        
		
		$member_status = '';
		
        $approval_masg = 'Updated Succesfully';

        $mismatchedcount = DB::table('mon_sub_remarks')->where('mon_sub_member_id','=',$sub_member_id)->where('type','=',1)->count();
        //return $mismatchedcount;

        $insertdata = [];
        $insertdata['mon_sub_member_id'] = $sub_member_id;
        $insertdata['type'] = 1;
        $insertdata['approval_status'] = 1;
        $insertdata['reason'] = $reasonid;
        $insertdata['remarks'] = $description;
       
        if($mismatchedcount==0){
            $saveunmatch = DB::table('mon_sub_remarks')->insert($insertdata);
        }else{
            $saveunmatch = DB::table('mon_sub_remarks')
            ->where('mon_sub_member_id', $sub_member_id)
            ->update($insertdata);
        }
        
        $member_id = DB::table('mon_sub_member')->where('id','=',$sub_member_id)->pluck('MemberCode')->first();
	
        Artisan::call('cache:clear');
		$return_data = ['status' => 1, 'message' => $approval_masg, 'sub_member_auto_id' => $sub_member_id, 'member_number' => $member_id, 'member_status' => $member_status, 'approval_status' => 1];
		echo json_encode($return_data);
    }

    public function Discripancy(){
		$data['month_year'] = date('M/Y');
		$data['month_year_full'] = date('Y-m-01');
		$data['last_month_year']= date("Y-m-01", strtotime("first day of previous month"));
		
        $data['groupby'] = 2;
        $data['variationtype']='4';
        $data['DisplaySubscription'] = false;
        $data['types'] = '';
        $data['inctypes'] = DB::table('increment_types')->get();
		
		$data['union_branch_view'] = CacheMonthEnd::getUnionBranchByDate($data['month_year_full']);
		
		$data['company_view']=[];
        $data['branch_view']=[];
        $data['sub_company'] = '';
        $data['unionbranch_id'] = '';
	
		return view('subscription.discrepancy')->with('data', $data);
    }
    
    public function DiscripancyFilter($lang, Request $request){
		//return $request->all();
		$entry_date = $request->input('entry_date');
		$groupby = $request->input('groupby');
        $display_subs = $request->input('display_subs');
        $variationtype = $request->input('variationtype');
        $types = $request->input('types');
		//$sixmonthvariation = $request->input('sixmonth-variation');
		$fm_date = explode("/",$entry_date);
        $fm_date[1].'-'.$fm_date[0].'-'.'01';
        $datestring = strtotime($fm_date[1].'-'.$fm_date[0].'-'.'01');
		$data['month_year'] = date('M/Y',$datestring);
		$data['month_year_full'] = date('Y-m-01',$datestring);
        $data['last_month_year']= date('Y-m-01',strtotime($fm_date[1].'-'.$fm_date[0].'-'.'01 -1 Month'));
        
        $data['sub_company'] = $request->input('sub_company');
        $data['unionbranch_id'] = $request->input('unionbranch_id');
		
		$data['groupby'] = $groupby;
        $data['DisplaySubscription'] = $display_subs;
        $data['types'] = $types;
        $data['inctypes'] = DB::table('increment_types')->get();
       // $data['sixmonthvariation'] = $sixmonthvariation;
        $data['variationtype']=$variationtype;
		if($groupby==1){
            if($data['unionbranch_id']!=''){
                $company_view = DB::table("mon_sub_member as mm")->select('cb.union_branch_id as union_branchid','u.union_branch as union_branch_name')
                                ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
                                ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                                ->leftjoin('membership as m','m.id','=','mm.MemberCode')
                                ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
                                ->leftjoin('company as c','cb.company_id','=','c.id')
                                ->leftjoin('union_branch as u','cb.union_branch_id','=','u.id')
                                ->where('ms.Date', '=', $data['month_year_full'])
								->where('mm.update_status', '=', 1)
                                ->where('mm.MemberCode', '!=', Null)
                                ->where('cb.union_branch_id', '=', $data['unionbranch_id']);
                $data['union_branch_view'] = $company_view->groupBY('cb.union_branch_id')
								->get();
            }else{
                $data['union_branch_view'] = CacheMonthEnd::getUnionBranchByDate($data['month_year_full']);
            }
			
			$data['company_view']=[];
			$data['branch_view']=[];
			$data['head_company_view']=[];
		}
		elseif($groupby==2){
			$head_company_view = DB::table('company')->select('company_name','id','short_code as companycode')->orderBy('company_name','asc')->where('status','=','1')
			->where(function ($query) {
				$query->where('head_of_company', '=', '')
					->orWhere('head_of_company', '=', 0)
						->orWhereNull('head_of_company');
			})->get();
				//dd($head_company_view);
			foreach($head_company_view as $mkey => $company){
				$companyid = $company->id;
				//$company_str_List ="'".$companyid."'";
				$company_ids = DB::table('company')->where('head_of_company','=',$companyid)->orderBy('company_name','asc')->pluck('id')->toArray();
				$res_company = array_merge($company_ids, [$companyid]); 


				foreach($company as $newkey => $newvalue){
					$data['head_company_view'][$mkey][$newkey] = $newvalue;
				}
				$data['head_company_view'][$mkey]['company_list'] = $res_company;
				//$company_str_List ='';

            }
            if($data['sub_company']!=''){
                $company_view = DB::table("mon_sub_member as mm")->select('mc.CompanyCode as company_id','c.company_name as company_name')
                                ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
								->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
								->leftjoin('membership as m','m.id','=','mm.MemberCode')
								->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
								->leftjoin('company as c','mc.CompanyCode','=','c.id')
                                //->leftjoin('union_branch as u','cb.union_branch_id','=','u.id')
                                ->where('ms.Date', '=', $data['month_year_full'])
								->where('mm.update_status', '=', 1)
                                ->where('mm.MemberCode', '!=', Null)
                                ->where('mc.CompanyCode', '=', $data['sub_company']);
                $data['company_view'] = $company_view->groupBY('mc.CompanyCode')
								->get();
            }else{
                $data['company_view'] = CacheMonthEnd::getCompaniesByDate($data['month_year_full']);
            }
			
			$data['union_branch_view']=[];
			$data['branch_view']=[];
		}
		else{
            if($data['sub_company']!=''){
                $company_view = DB::table("mon_sub_member as mm")->select('m.branch_id as branch_id','cb.branch_name as branch_name','c.company_name as company_name')
                                ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
                                ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                                ->leftjoin('membership as m','m.id','=','mm.MemberCode')
                                ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
                                ->leftjoin('company as c','cb.company_id','=','c.id')
                                //->leftjoin('union_branch as u','cb.union_branch_id','=','u.id')
                                ->where('ms.Date', '=', $data['month_year_full'])
								->where('mm.update_status', '=', 1)
                                ->where('mm.MemberCode', '!=', Null)
                                ->where('mc.CompanyCode', '=', $data['sub_company']);
                $data['branch_view'] = $company_view->groupBY('m.branch_id')
								->get();
            }else{
                 $data['branch_view'] = CacheMonthEnd::getBranchByDate($data['month_year_full']);
            }
			$data['union_branch_view']=[];
			$data['company_view']=[];
			$data['head_company_view']=[];
		}
		
		return view('subscription.discrepancy')->with('data', $data);
	}
    
    public function DiscripancyUpdate(Request $request, $lang)
    {
        $groupby = $request->input('disgroupby');
        $entry_date = $request->input('disentry_date');

        $datearr = explode("/",$entry_date);
        $monthname = $datearr[0];
        $year = $datearr[1];
        $form_date = date('Y-m-d',strtotime('01-'.$monthname.'-'.$year));
        $form_datefull = date('Y-m-d',strtotime('01-'.$monthname.'-'.$year)).' '.date('h:i:s');

        $companyid = '';
        $unionid = '';
        $branchid = '';

        $disrefid = $request->input('disrefid');

        if(isset($disrefid)){
            foreach ($disrefid as $key => $value) {
                $refid = $value;
                $inctype = $request->input('inctype_'.$refid);
                $others = $request->input('others_'.$refid);
                if($inctype!=null){
                   $memberids = $request->input('memberids_'.$refid);
                   if(isset($memberids)){
                        for($i=0; $i<count($memberids);$i++){
                            $memberid = $request->input('memberids_'.$refid)[$i];
                            $subsamt = $request->input('thissubs_'.$memberid);
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
                                    if($basicsalry[0]->increment_type_id==1){
                                        $salary = $lastsalary+$basicsalry[0]->additions;
                                    }else{
                                        $salary = $lastsalary-$basicsalry[0]->additions;
                                    }
                                    
                                }else{
                                    $salary = DB::table('membership as m')->select('m.salary')->where('m.id', '=', $memberid)->pluck('m.salary')->first();
                                }
                            }else{
                                return redirect($lang.'/subscription_discrepancy')->with('message','Salary Updations Added successfully!!');
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

                                        $savesal = DB::table('salary_updations')->insert($insertdata);
                                        
                                    }
                                //}
                            }
                            if($newincsalary==$newsalary){
                                DB::table('membership')->where('id','=',$memberid)->update(['current_salary' => $newincsalary, 'last_update' => $form_datefull]);
                            }else{
                                DB::table('membership')->where('id','=',$memberid)->update(['current_salary' => $newincsalary]);
                            }
                            

                            // $salcount = DB::table('salary_updations')->where('member_id','=',$memberid)
                            //         ->where('date','=',$form_date)
                            //         ->where('increment_type_id','=',$inctype)
                            //         ->count();          
                            
                        }
                   }
                }

            }
        }
        echo json_encode(['status' => 1, 'message' => 'Salary Updations Added successfully!!']);
        //return redirect($lang.'/subscription_discrepancy')->with('message','Salary Updations Added successfully!!');
    }

    public function variationMembersAll($lang, Request $request){
        $datestring = $request->input('date');
		$companyid = $request->input('companyid');
	
        $data['companyid'] = $companyid;
        
		//$datestring = strtotime('2019-04-01');
		//return date('Y-m-01',strtotime($datestring));
		$data['month_year'] = date('M/Y',$datestring);
        $data['month_year_full'] = date('Y-m-01',$datestring);

        $subcompanyid = DB::table('mon_sub_company as sc')
                        ->leftjoin('mon_sub as ms','ms.id','=','sc.MonthlySubscriptionId')
                        ->where('sc.CompanyCode','=',$companyid)
                        ->where('ms.Date','=',$data['month_year_full'])
                        ->pluck('sc.id')
                        ->first();
        
        $cond ='';
        if(isset($subcompanyid) && $subcompanyid!=''){
            $cond =" AND m.MonthlySubscriptionCompanyId = '$subcompanyid'";
        }
       
       // $members_data = DB::select(DB::raw('select member.name as member_name, member.member_number as member_number,m.Amount as Amount, member.new_ic as ic,"0" as due,s.status_name as status_name, `member`.`id` as memberid, m.id as sub_member_id,m.Name as up_member_name,m.NRIC as up_nric,m.approval_status from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` left join membership as member on `member`.`id` = `m`.`MemberCode`  left join status as s on `s`.`id` = `m`.`StatusId`  where 1=1 '.$cond.' AND (m.StatusId>"2" OR m.MemberCode is null)'));


        $members_data = DB::select(DB::raw('SELECT member.name AS member_name, member.member_number AS member_number, m.Amount AS Amount, member.new_ic AS ic, "0" AS due, s.status_name AS status_name, `member`.`id` AS memberid, m.id AS sub_member_id, m.Name AS up_member_name, m.NRIC AS up_nric, m.approval_status FROM mon_sub_member_match as mm LEFT JOIN `mon_sub_member` AS `m` ON mm.mon_sub_member_id=m.id left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` left join membership as member on `member`.`id` = `m`.`MemberCode`  left join status as s on `s`.`id` = `m`.`StatusId`  where 1=1 '.$cond.' AND mm.match_id in (2,4,6,7)'));

        $data['subsdata'] = DB::table('mon_sub_company as sc')->select('s.Date','c.short_code','c.company_name','c.id as company_id')
        ->leftjoin('mon_sub as s', 's.id' ,'=','sc.MonthlySubscriptionId')
        ->leftjoin('company as c','c.id','=','sc.CompanyCode')
        ->where('sc.id','=',$subcompanyid)
        ->first();

        $data['company_subscription_list'] = DB::table('mon_sub_member as mm')->select('*')
        ->leftjoin('mon_sub_company as sc', 'sc.id' ,'=','mm.MonthlySubscriptionCompanyId')
        //->leftjoin('mon_sub as s', 's.id' ,'=','sc.MonthlySubscriptionId')
        ->leftjoin('company as c','c.id','=','sc.CompanyCode')
        ->where('sc.id','=',$subcompanyid)
        //->where('mm.STatusId','<=',2)
        ->count();

        $data['matched_count'] = $data['company_subscription_list']-count($members_data);

        $data['doj_count'] = DB::table('mon_sub_member as mm')->select('*')
        ->leftjoin('mon_sub_company as sc', 'sc.id' ,'=','mm.MonthlySubscriptionCompanyId')
        ->leftjoin('mon_sub as s', 's.id' ,'=','sc.MonthlySubscriptionId')
        ->leftjoin('company as c','c.id','=','sc.CompanyCode')
        ->leftjoin('membership as m','m.id','=','mm.MemberCode')
        ->where('sc.id','=',$subcompanyid)
        ->where('s.Date','=',DB::raw('DATE_FORMAT(m.doj, "%Y-%m-01")'))
        ->count();

        $members_amt = DB::select(DB::raw('SELECT sum(Amount) as Amount FROM mon_sub_member_match as mm LEFT JOIN `mon_sub_member` AS `m` ON mm.mon_sub_member_id=m.id left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` left join membership as member on `member`.`id` = `m`.`MemberCode`  left join status as s on `s`.`id` = `m`.`StatusId`  where 1=1 '.$cond.' AND mm.match_id =4 '));

        $misbankamt = 0;
        if(!empty($members_amt)){
            $misbankamt = $members_amt[0]->Amount;
        }

        $matchedamt = DB::table('mon_sub_member as mm')->select(DB::raw('sum(Amount) as Amount'))
        ->leftjoin('mon_sub_company as sc', 'sc.id' ,'=','mm.MonthlySubscriptionCompanyId')
        ->leftjoin('mon_sub as s', 's.id' ,'=','sc.MonthlySubscriptionId')
        ->leftjoin('company as c','c.id','=','sc.CompanyCode')
        ->leftjoin('membership as m','m.id','=','mm.MemberCode')
        ->where('sc.id','=',$subcompanyid)
        ->where('mm.StatusId','<=',2)
        ->where('s.Date','<>',DB::raw('DATE_FORMAT(m.doj, "%Y-%m-01")'))
        ->pluck('Amount')
        ->first();

        $data['matched_amount'] = $matchedamt-$misbankamt;

        //return $data['matched_amount'] ;

        


        $data['members_count'] = DB::table('membership as m')
        ->leftjoin('company_branch as cb', 'cb.id' ,'=','m.branch_id')
        ->leftjoin('company as c', 'c.id' ,'=','cb.company_id')
        ->where('status_id','<=',2)->where('c.id','=',$companyid)->count();
		
        $data['submembers'] = $members_data;
        
        return view('subscription.variation_bank_members_print')->with('data', $data);
    }
    
}
