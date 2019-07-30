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
        if($request->type==0){
            return Excel::download(new SubscriptionExport, $file_name.'.xlsx');
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
                    return back()->with('message', 'File Updated Successfully');;
                }
            }
        }
    }
  
}
