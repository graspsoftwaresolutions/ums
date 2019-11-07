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

class InsertMonthendMonthly implements ShouldQueue
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
        $cur_date = $this->subs_date;
        if($app_from_date<=$file_upload_date){
            Log::channel('monthendstartlog')->info('status updates started for company id: '.$company_auto_id.'&date'.$cur_date);
             $members =  DB::table('membership as m')
                        ->select('m.id','m.status_id')
                        ->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
                        ->leftjoin('company as c','c.id','=','cb.company_id')
                        ->where('cb.company_id', '=',$company_id)
                        ->where('m.status_id', '!=',3)
                        ->where('m.status_id', '!=',4)
                        ->where('m.doj', '<=',$cur_date)
                        ->get();
            foreach($members as $member){
                $membercount = DB::table('membermonthendstatus')->where('StatusMonth', '=', $cur_date)->where('MEMBER_CODE', '=', $member->id)->count();
                if($membercount==0){
                    $memberid = $member->id; 
                    $sql = "INSERT INTO membermonthendstatus (StatusMonth,MEMBER_CODE,MEMBERTYPE_CODE, BANK_CODE,BRANCH_CODE,NUBE_BRANCH_CODE,SUBSCRIPTION_AMOUNT,BF_AMOUNT,
                    LASTPAYMENTDATE,TOTALSUBCRP_AMOUNT,TOTALBF_AMOUNT,TOTAL_MONTHS,TOTALMONTHSDUE,
                    TOTALMONTHSPAID,SUBSCRIPTIONDUE,BFDUE,ACCSUBSCRIPTION,ACCBF,ACCBENEFIT,
                    STATUS_CODE,ENTRY_DATE,ENTRY_TIME,INSURANCE_AMOUNT,TOTALINSURANCE_AMOUNT,
                    TOTALMONTHSCONTRIBUTION,INSURANCEDUE,ACCINSURANCE) SELECT '$cur_date', m.id 
                    as memberid, m.designation_id, cb.company_id, m.branch_id, cb.union_branch_id,
                    mp.sub_monthly_amount,mp.bf_monthly_amount,mp.last_paid_date,'0','0','0',
                    (mp.totdue_months+1),mp.totpaid_months,(mp.duesub_amount+mp.sub_monthly_amount),
                    (mp.duebf_amount+mp.bf_monthly_amount),mp.accsub_amount,mp.accbf_amount,
                    mp.accbenefit_amount, m.status_id,'$cur_date','00:00:00',mp.ins_monthly_amount, 
                   0, mp.totcontribution_months, 
                    (mp.dueins_amount+mp.ins_monthly_amount),mp.accins_amount from
                     membership as m LEFT JOIN company_branch AS cb ON cb.id = m.branch_id 
                     left join member_payments as mp on m.id=mp.member_id where m.status_id<=2 and m.id=$memberid";
                    
                    $status = DB::insert(DB::raw($sql));
                    Log::channel('monthendstartlog')->info('inserted member id: '.$memberid);
                }
            }
        }
    }
}
