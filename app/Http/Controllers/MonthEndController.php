<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Jobs\UpdateMonthendStatus;
use Artisan;

class MonthEndController extends Controller
{
    public function index(){
        $data = [];
    	return view('monthend_update')->with('data',$data); 
    }

    public function getMonthendInfo($lang,Request $request)
    {
        $month_year = $request->input('month_year');
        $member_auto_id = $request->input('member_auto_id');
        $statusMonth = date('Y-m-01');
        if($month_year!=""){
           $fmmm_date = explode("/",$month_year);
           $statusMonth = date('Y-m-01',strtotime('01-'.$fmmm_date[0].'-'.$fmmm_date[1]));
        }
        $monthrecord =  DB::table('membermonthendstatus as ms')->where('ms.MEMBER_CODE', '=' ,$member_auto_id)
        ->where('ms.StatusMonth', '=' ,$statusMonth)
        ->first();
        $data = ['status' => 1, 'data' => $monthrecord];
        return $data;
    }

    public function SaveMonthEnd($lang,Request $request){
       // return $request->all();
        $subscription_amount = $request->input('subscription_amount');
        $bf_amount = $request->input('bf_amount');
        $insurance_amount = $request->input('insurance_amount');
        $month_auto_id = $request->input('month_auto_id');
        $monthrecord =  DB::table('membermonthendstatus as ms')->where('ms.Id', '=' ,$month_auto_id)->first();
        $current_TOTALSUBCRP_AMOUNT = $monthrecord->TOTALSUBCRP_AMOUNT;
        $current_TOTALBF_AMOUNT = $monthrecord->TOTALBF_AMOUNT;
        $current_TOTALINSURANCE_AMOUNT = $monthrecord->TOTALINSURANCE_AMOUNT;
        if($current_TOTALSUBCRP_AMOUNT!=$subscription_amount || $current_TOTALBF_AMOUNT!=$bf_amount || $current_TOTALINSURANCE_AMOUNT!=$insurance_amount){
            $newACCBF=($monthrecord->ACCBF-$current_TOTALBF_AMOUNT)+$bf_amount;
            $newACCSUBSCRIPTION=($monthrecord->ACCSUBSCRIPTION-$current_TOTALSUBCRP_AMOUNT)+$subscription_amount;
            $newCURRENT_YDTBF=($monthrecord->CURRENT_YDTBF-$current_TOTALBF_AMOUNT)+$bf_amount;
            $newCURRENT_YDTSUBSCRIPTION=($monthrecord->CURRENT_YDTSUBSCRIPTION-$current_TOTALSUBCRP_AMOUNT)+$subscription_amount;
            $newACCINSURANCE=($monthrecord->ACCINSURANCE-$current_TOTALINSURANCE_AMOUNT)+$insurance_amount;
            $newCURRENT_YDTINSURANCE=($monthrecord->CURRENT_YDTINSURANCE-$current_TOTALINSURANCE_AMOUNT)+$insurance_amount;

            $monthend_data = [ 
                        'SUBSCRIPTION_AMOUNT' => $subscription_amount,
                        'BF_AMOUNT' => $bf_amount,
                        'INSURANCE_AMOUNT' => $insurance_amount,
                        'TOTALSUBCRP_AMOUNT' => $subscription_amount,
                        'TOTALBF_AMOUNT' => $bf_amount,
                        'TOTALINSURANCE_AMOUNT' => $insurance_amount, 
                        'ACCBF' => $newACCBF,
                        'ACCSUBSCRIPTION' => $newACCSUBSCRIPTION,
                        'ACCINSURANCE' => $newACCINSURANCE, 
                        'CURRENT_YDTBF' => $newCURRENT_YDTBF,
                        'CURRENT_YDTINSURANCE' => $newCURRENT_YDTSUBSCRIPTION,
                        'CURRENT_YDTSUBSCRIPTION' => $newCURRENT_YDTINSURANCE,
                     ];
            $status = DB::table('membermonthendstatus')->where('Id', $month_auto_id)->update($monthend_data);
            if($status==1){
                $membercode = $monthrecord->MEMBER_CODE;
                $status_month = $monthrecord->StatusMonth;
                UpdateMonthendStatus::dispatch($membercode,$status_month);
                Artisan::call('queue:work --tries=1 --timeout=10000');
                return 'current month updated , other months will start updates';
            }
        }else{
            echo 'no changes in record';
        }
    }
}
