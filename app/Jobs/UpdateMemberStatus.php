<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use DB;
use Auth;
use Log;
use Facades\App\Repository\CacheMembers;
use Carbon\Carbon;
use App\Model\Membership;



class UpdateMemberStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $sub_company_id;
    protected $sub_id;
    protected $company_id;
    protected $subs_date;
   
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($company_auto_id)
    {
        $this->sub_company_id = $company_auto_id;
        $companydata =  DB::table('mon_sub_company as sc')->where('id', '=',$company_auto_id)->first();
        $company_id = $companydata->CompanyCode;
        $MonthlySubscriptionId = $companydata->MonthlySubscriptionId;
        $this->sub_id = $MonthlySubscriptionId;
        $this->company_id = $company_id;
        $subs_date =  DB::table('mon_sub as s')->where('id', '=',$MonthlySubscriptionId)->pluck('Date')->first();
        $this->subs_date = $subs_date;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $company_auto_id = $this->sub_company_id;
        $app_from_date = strtotime('2019-06-01');
        $file_upload_date = strtotime($this->subs_date);
        if($app_from_date<=$file_upload_date){
             //Log::useFiles(storage_path().'/logs/status-updates.log');
            Log::channel('customlog')->info('status updates started for company id: '.$company_auto_id);

            $company_id = $this->company_id;
            $members =  DB::table('membership as m')
                        ->select('m.id','m.status_id')
                        ->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
                        ->leftjoin('company as c','c.id','=','cb.company_id')
                        ->where('cb.company_id', '=',$company_id)
                        ->where('m.status_id', '!=',3)
                        ->where('m.status_id', '!=',4)
                        ->get();
            foreach($members as $member){
                $paydata =  DB::table('member_payments')->where('member_id', '=',$member->id)->first();
                $last_pay_date = $paydata->last_paid_date;
                $upload_date = $this->subs_date;
                if($last_pay_date!='' && $last_pay_date!='0000-00-00'){
                    $to = Carbon::createFromFormat('Y-m-d H:s:i', $last_pay_date.' 3:30:34');
                    $from = Carbon::createFromFormat('Y-m-d H:s:i', $upload_date.' 3:30:34');
                    $strlastpaid = strtotime($last_pay_date);
                    $diff_in_months = 0;
                    if($strlastpaid<$file_upload_date){
                        $diff_in_months = $to->diffInMonths($from);
                    }

                    $member_doj = CacheMembers::getDojbyMemberCode($member->id);
                    $to_one = Carbon::createFromFormat('Y-m-01 H:s:i', $member_doj.' 3:30:34');
                    $from_one = Carbon::createFromFormat('Y-m-d H:s:i', $upload_date.' 3:30:34');

                    $strdoj = strtotime($member_doj);
                   
                    
                    $diff_in_months_one = 0;
                    if($strdoj<$file_upload_date){
                        $diff_in_months_one = $to_one->diffInMonths($from_one);
                    }
                    
                    //if($member->id==25439){
                        Log::channel('customlog')->info('code-'.$member->id);
                        Log::channel('customlog')->info('member#:'.$member->id.'to month: '.$last_pay_date.'From month'.$upload_date);
                        Log::channel('customlog')->info('member#:'.$member->id.'doj: '.$member_doj.'From month'.$upload_date);
                    //}
                    Log::channel('customlog')->info('member#:'.$member->id.'month diff: '.$diff_in_months);

                    $membercount =  DB::table('mon_sub_member as sm')->where('MonthlySubscriptionCompanyId', '=',$company_auto_id)->where('MemberCode', '=',$member->id)->count();
                    if($diff_in_months_one>=3 && $diff_in_months>=4 && $diff_in_months<=12 && $membercount==0){
                        Log::channel('customlog')->info('status changed for memberid: '.$member->id.'&status=2&fromstatus='.$member->status_id);

                        $updata = ['status_id' => 2,'updated_at' => date('Y-m-d h:i:s'), 'updated_by' => 11];
                        $last_month = date('Y-m-01',strtotime($upload_date.' -1 Month'));
                        $statuss = DB::table('membermonthendstatus')->where('StatusMonth', '>=', $last_month)->where('MEMBER_CODE', $member->id)->where('TOTALMONTHSDUE','>=',4)->update(['STATUS_CODE'=>2]);

                        $savedata = Membership::where('id',$member->id)->where('status_id','!=',2)->update($updata);
                    }else if ($diff_in_months_one>=3 && $diff_in_months>=13 && $membercount==0){
                        Log::channel('customlog')->info('status changed for memberid: '.$member->id.'&status=3&fromstatus='.$member->status_id);
                        $updata = ['status_id' => 3,'updated_at' => date('Y-m-d h:i:s'), 'updated_by' => 11];
                        $savedata = Membership::where('id',$member->id)->where('status_id','!=',3)->update($updata);

                        $last_month = date('Y-m-01',strtotime($upload_date.' -1 Month'));
                        $statuss = DB::table('membermonthendstatus')->where('StatusMonth', '>=', $last_month)->where('MEMBER_CODE', $member->id)->where('TOTALMONTHSDUE','>=',13)->update(['STATUS_CODE'=>3]);

                    }
                }
            }
        }
       
    }
}
