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
use Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Log;
use Auth;



class SubscriptionController extends CommonController
{
    public function __construct() {
        ini_set('memory_limit', -1);
        $this->middleware('auth');
        //$this->middleware('module:master');       
        $this->Company = new Company;
        $this->MonthlySubscription = new MonthlySubscription;
        $this->MonthlySubscriptionMember = new MonthlySubscriptionMember;
        $this->Status = new Status;
        $this->membermonthendstatus_table = "membermonthendstatus1";
        $this->ArrearEntry = new ArrearEntry;
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
            foreach($status_all as $key => $value){
                $status_data['count'][$value->id] = CommonHelper::statusSubsMembersCompanyCount($value->id, $user_role, $user_id,$company_auto_id,$full_date);
                $status_data['amount'][$value->id] = round(CommonHelper::statusMembersCompanyAmount($value->id, $user_role, $user_id,$company_auto_id,$full_date), 0);
            }
            foreach($approval_status as $key => $value){
                $approval_data['count'][$value->id] = CommonHelper::statusSubsCompanyMatchCount($value->id, $user_role, $user_id,$company_auto_id,$full_date);
                $approval_data['approved'][$value->id] = CommonHelper::statusSubsCompanyMatchApprovalCount($value->id, $user_role, $user_id,$company_auto_id,1,$full_date);
                $approval_data['pending'][$value->id] = CommonHelper::statusSubsCompanyMatchApprovalCount($value->id, $user_role, $user_id,$company_auto_id,0,$full_date);
            }
            $data =['status' =>1, 'status_data' => $status_data, 'approval_data' => $approval_data, 'sundry_amount' => round(CommonHelper::statusSubsCompanyMatchAmount(2, $user_role, $user_id,$company_auto_id,$full_date), 0), 'sundry_count' => CommonHelper::statusSubsCompanyMatchCount(2, $user_role, $user_id,$company_auto_id,$full_date), 'company_auto_id' => $company_auto_id, 'month_year_number' => strtotime($full_date),'message'  => 'Data already uploaded for this company'];
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
                
                $up_sub_member =0;
                $match_count =  MonthlySubMemberMatch::where('mon_sub_member_id', '=',$subscription->id)->count();
                if($match_count>0){
                    $match_res =  MonthlySubMemberMatch::where('mon_sub_member_id', '=',$subscription->id)->get();
                    $matchid = $match_res[0]->id;
                    $subMemberMatch = MonthlySubMemberMatch::find($matchid);
                }else{
                    $subMemberMatch = new MonthlySubMemberMatch();
                }
                
                $subMemberMatch->mon_sub_member_id = $subscription->id;
                $subMemberMatch->created_by = Auth::user()->id;
                $subMemberMatch->created_on = date('Y-m-d');
               // DB::enableQueryLog();
                if($subscription_new_qry->count() > 0){
                   
                    $memberdata = $subscription_new_qry->select('status_id','id','branch_id','name')->get();
                    $up_sub_member =1;
                    $subMemberMatch->match_id = 1;
                   
                }else if($subscription_old_qry->count() > 0){
                    
                    $up_sub_member =1;
                    $memberdata = $subscription_old_qry->select('status_id','id','branch_id','name')->get();
                    $subMemberMatch->match_id = 8;
                }
               
                else{
                    $subMemberMatch->match_id = 2;
                }
               
                
                $upstatus=1;
                if($up_sub_member ==1){
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
                
                    if($company_code == $member_company_id){
                        $subMemberMatch->match_id = 9;
                    }
                    else if ( $company_code != $member_company_id){
                        $subMemberMatch->match_id = 4;
                    }
                    
                    if($memberdata[0]->name != $subscription->Name){
                        $subMemberMatch->match_id = 3;
                    }
                    
                    if($memberdata[0]->status_id ==3){
                        $subMemberMatch->match_id = 6;
                    }else if($memberdata[0]->status_id ==4){
                        $subMemberMatch->match_id = 7;
                    }
                }

                $subMemberMatch->save();
                
               
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

        $data['member_history'] = DB::table($this->membermonthendstatus_table.' as ms')
                                            ->where('ms.MEMBER_CODE','=',$id)
                                            ->OrderBy('ms.id','asc')
                                            ->get();
                                            
           
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
        if($member_status!=""){
			$cond ='';
			if(isset($company_id) && $company_id!=''){
				$cond =" AND m.MonthlySubscriptionCompanyId = '$company_id'";
			}
            $members_data = DB::select(DB::raw('select member.name as member_name, member.member_number as member_number,m.Amount as Amount, c.company_name as company_name, member.new_ic as ic,"0" as due,s.status_name as status_name, `member`.`id` as memberid, mm.mon_sub_member_id as sub_member_id, mm.id as match_auto_id, mm.approval_status as approval_status,mm.match_id as match_id from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub_member_match` as mm on m.id=mm.mon_sub_member_id left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` left join membership as member on `member`.`id` = `m`.`MemberCode` left join company_branch as cb on `cb`.`id` = `member`.`branch_id` left join company as c on `c`.`id` = `cb`.`company_id` left join status as s on `s`.`id` = `m`.`StatusId` where m.StatusId="'.$member_status.'" '.$cond.' AND `sm`.`Date`="'.$defaultdate.'"'));
            $data['member'] = $members_data;
            $data['status_type'] = 1;
            $data['status'] = $member_status;
        }
        if($approval_status!=""){
			$cond ='';
			if(isset($company_id) && $company_id!=''){
				$cond =" AND m.MonthlySubscriptionCompanyId = '$company_id'";
			}
           $members_data = DB::select(DB::raw('SELECT member.name as member_name, member.member_number as member_number,m.Amount as Amount, c.company_name as company_name, member.new_ic as ic,"0" as due,s.status_name as status_name, `member`.`id` as memberid, mm.mon_sub_member_id as sub_member_id, mm.id as match_auto_id, mm.approval_status as approval_status,mm.match_id as match_id FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` left join membership as member on `member`.`id` = `m`.`MemberCode` left join company_branch as cb on `cb`.`id` = `member`.`branch_id` left join company as c on `c`.`id` = `cb`.`company_id` left join status as s on `s`.`id` = `m`.`StatusId` WHERE mm.match_id="'.$approval_status.'" '.$cond.' AND `sm`.`Date`="'.$defaultdate.'"'));
           $data['member'] = $members_data;
           $data['status_type'] = 2;
           $data['status'] = $approval_status;
        }
		if($member_status==0 && $approval_status==""){
			$cond ='';
			if(isset($company_id) && $company_id!=''){
				$cond =" AND m.MonthlySubscriptionCompanyId = '$company_id'";
			}
           $members_data = DB::select(DB::raw('SELECT member.name as member_name, member.member_number as member_number,m.Amount as Amount, c.company_name as company_name, member.new_ic as ic,"0" as due,s.status_name as status_name, `member`.`id` as memberid, mm.mon_sub_member_id as sub_member_id, mm.id as match_auto_id, mm.approval_status as approval_status,mm.match_id as match_id FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` left join membership as member on `member`.`id` = `m`.`MemberCode` left join company_branch as cb on `cb`.`id` = `member`.`branch_id` left join company as c on `c`.`id` = `cb`.`company_id` left join status as s on `s`.`id` = `m`.`StatusId` WHERE mm.match_id="2" '.$cond.' AND `sm`.`Date`="'.$defaultdate.'"'));
           $data['member'] = $members_data;
           $data['status_type'] = 3;
           $data['status'] = 0;
        }
		return view('subscription.member_status')->with('data',$data);
    }
	
	public function saveApproval($lang, Request $request){
        $match_auto_id = $request->input('match_auto_id');
        $match_id  = CommonHelper::get_member_match_id($match_auto_id);
        $member_approve = $request->input('member_approve');
        $approval_status=0;
		$bank_approve = $request->input('bank_approve');
		$struckoff_approve = $request->input('struckoff_approve');
		$resign_approve = $request->input('resign_approve');
		$nric_old_approve = $request->input('nric_old_approve');
		$nric_bank_approve = $request->input('nric_bank_approve');
		$previous_approve = $request->input('previous_approve');
		$previous_unpaid_approve = $request->input('previous_unpaid_approve');
		if($match_id==3){
            $approval_status= isset($member_approve) ? 1 : 0;
			DB::table('mon_sub_member_match')->where('id', '=', $match_auto_id)->where('match_id','=' ,3)->update(['approval_status' => $approval_status, 'description' => 'Mismatched Member Name', 'updated_by' => Auth::user()->id]);
        }
        if($match_id==4){
            $approval_status= isset($bank_approve) ? 1 : 0;
			DB::table('mon_sub_member_match')->where('id', '=', $match_auto_id)->where('match_id','=' ,4)->update(['approval_status' => $approval_status, 'description' => 'Mismatched Bank', 'updated_by' => Auth::user()->id]);
        }
		if($match_id==5){
            $approval_status= isset($previous_approve) ? 1 : 0;
			DB::table('mon_sub_member_match')->where('id', '=', $match_auto_id)->where('match_id','=' ,5)->update(['approval_status' => $approval_status, 'description' => 'Mismatched Bank', 'updated_by' => Auth::user()->id]);
        }
        if($match_id==6){
            $approval_status= isset($struckoff_approve) ? 1 : 0;
			DB::table('mon_sub_member_match')->where('id', '=', $match_auto_id)->where('match_id','=' ,6)->update(['approval_status' => $approval_status, 'description' => 'StruckOff Members', 'updated_by' => Auth::user()->id]);
		}
		if($match_id==7){
            $approval_status= isset($resign_approve) ? 1 : 0;
			DB::table('mon_sub_member_match')->where('id', '=', $match_auto_id)->where('match_id','=' ,7)->update(['approval_status' => $approval_status, 'description' => 'Resigned Members', 'updated_by' => Auth::user()->id]);
		}
		if($match_id==8){
            $approval_status= isset($nric_old_approve) ? 1 : 0;
			DB::table('mon_sub_member_match')->where('id', '=', $match_auto_id)->where('match_id','=' ,8)->update(['approval_status' => $approval_status, 'description' => 'NRIC Old Matched', 'updated_by' => Auth::user()->id]);
		}
		if($match_id==9){
            $approval_status= isset($nric_bank_approve) ? 1 : 0;
			DB::table('mon_sub_member_match')->where('id', '=', $match_auto_id)->where('match_id','=' ,9)->update(['approval_status' => $approval_status, 'description' => 'NRIC By Bank Matched', 'updated_by' => Auth::user()->id]);
		}
		if($match_id==10){
            $approval_status= isset($previous_unpaid_approve) ? 1 : 0;
			DB::table('mon_sub_member_match')->where('id', '=', $match_auto_id)->where('match_id','=' ,10)->update(['approval_status' => $approval_status, 'description' => 'Mismatched Bank', 'updated_by' => Auth::user()->id]);
        }
		$return_data = ['status' => 1, 'message' => 'Updated Succesfully', 'match_auto_id' => $match_auto_id, 'approval_status' => $approval_status];
		echo json_encode($return_data);
	}
    
    
}
