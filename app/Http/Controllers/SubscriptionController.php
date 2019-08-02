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
use App\Model\Membership;
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
    }
    //excel file download and upload it
    public function index() {
        $status_all = Status::all();       
        $data['member_stat'] = $status_all;

        isset($data['member_stat']) ? $data['member_stat'] : "";       
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
            $Sub_typet = new SubscriptionExport($newtype,$request->all());
            return Excel::download($Sub_typet, $file_name.'.xlsx');
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
                        return redirect(app()->getLocale().'home');
                    }
                    
                    //return $this->scanSubscriptions($request->entry_date,$request->sub_company);
                }
            }
        }
    }

    public function getSubscriptionStatus(Request $request){
        $entry_date = $request->entry_date;
        $sub_company = $request->sub_company;
        $data =[];

        $company_auto_id = '';
        $company_auto_id = $this->getCommonStatus($entry_date,$sub_company);

        
        if($company_auto_id!=''){
            $data =['status' =>1 ,'message'  => 'Data already uploaded for this company'];
        }else{
            $data =['status' =>0 ,'message'  => 'No data found'];
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
        $limit = 200;
        $company_auto_id = $request->company_auto_id;
        $start =  $request->start;
        $return_data = ['status' => 0 ,'message' => ''];
        if($company_auto_id!=""){
           
            $subscription_data = MonthlySubscriptionMember::select('id','NRIC as ICNO','NRIC as NRIC','Name','Amount')
                                                            ->where('MonthlySubscriptionCompanyId',$company_auto_id)
                                                            ->where('update_status','=',0)
                                                            ->offset(0)
                                                            ->limit($limit)
                                                            ->get();
            //$queries = DB::getQueryLog();
            //Log::info($queries);
            //dd($queries);
            $row_count = count($subscription_data);
            $count =0;
            foreach($subscription_data as $subscription){
                $nric = $subscription->NRIC;
               
               
                $subscription_qry =  DB::table('membership as m')
                                    ->where(function($query) use ($nric){
                                        $query->orWhere('m.old_ic','=',$nric);
                                        $query->orWhere('m.new_ic', '=',$nric);
                                    });
                                   // $queries = DB::getQueryLog();
                                    //dd($queries);
                $upstatus=1;
                if($subscription_qry->count() > 0){
                    $memberdata = $subscription_qry->select('status_id','member_number')->get();
                    if(count($memberdata)>0){
                        $status_id = $memberdata[0]->status_id;
                        $member_code = $memberdata[0]->member_number;
                        $updata = ['MemberCode' => $member_code, 'StatusId' => $status_id, 'update_status' => 1];
                        //DB::enableQueryLog();
                        $savedata = MonthlySubscriptionMember::where('MonthlySubscriptionCompanyId',$company_auto_id)
                        ->where('NRIC',$nric)->update($updata);
                        $upstatus=0;
                        //die;
                    }
                }
                //DB::enableQueryLog();
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
       //  $year =2019;
       // $month =8;

       // return $id;
       //$data['member_subscription_details'] = DB::table('mon_sub_member as sm')->select('m.id as memberid','m.id as memberid','m.name as membername','m.member_number as MemberCode','sm.Amount','status.status_name')
      // var_dump($data['member_subscription_details']);
      //  exit;
       $data['member_subscription_details'] = DB::table('mon_sub_member as sm')->select('m.id as memberid','m.id as memberid','m.name as membername','m.member_number as MemberCode','sm.Amount','status.status_name')
                                            ->leftjoin('membership as m','m.member_number','=','sm.MemberCode') 
                                            ->leftjoin('mon_sub_company as sc','sm.MonthlySubscriptionCompanyId','=','sc.id')
                                            ->leftjoin('mon_sub as s','sc.MonthlySubscriptionId','=','s.id') 
                                            ->leftjoin('status as status','status.id','=','sm.StatusId')
                                           // ->where('s.Date','=',date('Y-m-01'))
                                           //  ->whereYear('s.Date', '=', $year)
                                            //->whereMonth('s.Date', '=', $month)
                                            ->where('m.id','=',$id)->get();

                                            DB::enableQueryLog();
        $data['member_subscription_list'] = DB::table('mon_sub_member as sm')->select('sm.Amount as Amount','s.Date as Date','status.status_name as status_name')
                                            ->leftjoin('mon_sub_company as sc', 'sc.id' ,'=','sm.MonthlySubscriptionCompanyId')
                                            ->leftjoin('mon_sub as s','s.id','=','sc.MonthlySubscriptionId')
                                            ->leftjoin('status as status','status.id','=','sm.StatusId')
                                            ->leftjoin('membership as m','m.member_number','=','sm.MemberCode')
                                            ->where('m.id','=',$id)
                                            ->groupBY('s.id')->get(); 
                                            //$queries = DB::getQueryLog();
                                           // dd($queries);
        // $data['member_subscription_list'] = DB::table('mon_sub as s')->select('sm.Amount','s')
        //                                     ->leftjoin('mon_sub_company', 'mon_sub.id' ,'=','mon_sub_company.MonthlySubscriptionId')
        //                                     ->leftjoin('mon_sub_member','mon_sub_company.id','=','mon_sub_member.MonthlySubscriptionCompanyId')
        //                                     // ->join('company','company.id','=','mon_sub_company.CompanyCode')
        //                                     // ->join('company_branch','company.id','=','company_branch.company_id')
        //                                     ->leftjoin('status','status.id','=','mon_sub_member.StatusId')
        //                                     ->leftjoin('membership','membership.member_number','=','mon_sub_member.MemberCode')
        //                                     ->where('membership.id','=',	
        //                                     $id)->get(); 
        //dd($data['member_subscription_list']);
           
        return view('subscription.sub_member')->with('data',$data);
          
    }

    public function memberfilter(Request $request)
    { 
        $member_code = $request->id;   
        $memberid = $request->memberid;
        $data['member_subscription_details'] = DB::table('mon_sub_member as sm')->select('m.id as memberid','m.name as membername','m.member_number as MemberCode','sm.Amount','status.status_name','s.Date')
            ->leftjoin('membership as m','m.member_number','=','sm.MemberCode') 
            ->leftjoin('mon_sub_company as sc','sm.MonthlySubscriptionCompanyId','=','sc.id')
            ->leftjoin('mon_sub as s','sc.MonthlySubscriptionId','=','s.id') 
            ->leftjoin('status as status','status.id','=','sm.StatusId')
            //->where('s.Date','=',date('Y-m-01'))
            ->where('m.id','=',$memberid)->get();

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
            $data['member_subscription_list'] = DB::table('mon_sub_member as sm')->select('sm.Amount as Amount','s.Date as Date','status.status_name as status_name')
                                ->leftjoin('mon_sub_company as sc', 'sc.id' ,'=','sm.MonthlySubscriptionCompanyId')
                                ->leftjoin('mon_sub as s','s.id','=','sc.MonthlySubscriptionId')
                                ->leftjoin('status as status','status.id','=','sm.StatusId')
                                ->leftjoin('membership as m','m.member_number','=','sm.MemberCode')
                                ->where('s.Date','>=', $from)
                                ->where('s.Date', '<=', $to)
                                ->where('sm.MemberCode','=',$member_code)->groupBY('s.id')->get();         
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
        $status_all = Status::all();   
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
       $data['member_stat'] = $status_all;
      // $data['member_stat'] = isset($data['member_stat']) ? $data['member_stat'] : [];   
	  // return  $data;
       return view('subscription.company_members')->with('data', $data);
    }
}
