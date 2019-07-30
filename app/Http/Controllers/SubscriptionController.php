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



class SubscriptionController extends Controller
{
    public function __construct() {
        ini_set('memory_limit', '-1');
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
                                            ->where('mon_sub_member.StatusId','=','1')->get();

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
                    $file = $request->file('file')->storeAs('subscription', $file_name.'.xlsx'  ,'local');
                    //$data = Excel::toArray(new SubscriptionImport, $file);
                    Excel::import(new SubscriptionImport($request->all()), $file);
                    return back()->with('message', 'File Uploaded Successfully');;
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
    public function submember($lang,$id)
    {
        return view('subscription.sub_member');
    }
}
