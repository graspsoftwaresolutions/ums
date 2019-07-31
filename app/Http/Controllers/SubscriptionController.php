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
        $status_all = Status::all();   
         $data['company_subscription_list'] = DB::table('mon_sub')->select('*')
                                            ->join('mon_sub_company', 'mon_sub.id' ,'=','mon_sub_company.MonthlySubscriptionId')
                                            ->join('company','company.id','=','mon_sub_company.CompanyCode')
                                            ->join('mon_sub_member','mon_sub_company.id','=','mon_sub_member.MonthlySubscriptionCompanyId')
                                            //->join('status','status.id','=','mon_sub_member.StatusId')
                                           // ->where('mon_sub_member.StatusId','=',NULL)->get();
                                           ->get();
        isset($data['company_subscription_list']) ? $data['company_subscription_list'] : "";      
       // dd($data['company_subscription_list']);
        $data['tot_count'] = $data['company_subscription_list']->count();
        $data['member_stat'] = $status_all;
        isset($data['member_stat']) ? $data['member_stat'] : "";   

        return view('subscription.sub_fileupload.sub_company')->with('data', $data);
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
            
            return Excel::download(new SubscriptionExport($newtype,$request->all()), $file_name.'.xlsx');
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
        $limit = 200;
        $company_auto_id = $request->company_auto_id;
        $start =  $request->start;
        $return_data = ['status' => 0 ,'message' => ''];
        if($company_auto_id!=""){
            $subscription_data = MonthlySubscriptionMember::select('id','NRIC as ICNO','NRIC as NRIC','Name','Amount')
                                                            ->where('MonthlySubscriptionCompanyId',$company_auto_id)
                                                            ->offset($start)
                                                            ->limit($limit)
                                                            ->get();
            $row_count = count($subscription_data);
            $count =0;
            foreach($subscription_data as $subscription){
                $nric = $subscription->NRIC;
                DB::enableQueryLog();
               
                $subscription_qry =  DB::table('membership as m')
                                    ->where(function($query) use ($nric){
                                        $query->orWhere('m.old_ic','=',$nric);
                                        $query->orWhere('m.new_ic', '=',$nric);
                                    });
                                   // $queries = DB::getQueryLog();
                                    //dd($queries);
                if($subscription_qry->count() > 0){
                    $memberdata = $subscription_qry->select('status_id','member_number')->get();
                    if(count($memberdata)>0){
                        $status_id = $memberdata[0]->status_id;
                        $member_code = $memberdata[0]->member_number;
                        $updata = ['MemberCode' => $member_code, 'StatusId' => $status_id];
                        $savedata = MonthlySubscriptionMember::where('MonthlySubscriptionCompanyId',$company_auto_id)
                        ->where('NRIC',$nric)->update($updata);
                        //die;
                    }
                }
                $count++;
            }
            $return_data = ['status' => 1 ,'message' => 'status and member code updated successfully, Redirecting to subscription details...','redirect_url' =>  URL::to('/'.app()->getLocale().'/subscription')];
        }else{
            $return_data = ['status' => 0 ,'message' => 'Invalid company id'];
        }
       echo json_encode($return_data);
    }

    public function submember($lang,$id)
    {
        return view('subscription.sub_member');
    }
    
    public function viewScanSubscriptions($lang,$id)
    {
        $company_auto_id = Crypt::decrypt($id);
        $data['company_auto_id'] = $company_auto_id;
        $memberrowcount = MonthlySubscriptionMember::where('MonthlySubscriptionCompanyId','=',$company_auto_id)->count();
        $data['row_count'] = $memberrowcount;
        return view('subscription.scan-subcription')->with('data',$data);
    }
}
