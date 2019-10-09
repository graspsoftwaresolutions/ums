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
        //Log::useFiles(storage_path().'/logs/status-updates.log');
        Log::channel('customlog')->info('status updates started for company id: '.$company_auto_id);

        $company_id = $this->company_id;
        $members =  DB::table('membership as m')
                    ->select('m.id')
                    ->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
                    ->leftjoin('company as c','c.id','=','cb.company_id')
                    ->where('cb.company_id', '=',$company_id)
                    ->get();
        foreach($members as $member){
            $paydata =  DB::table('member_payments')->where('member_id', '=',$member->id)->first();
            $last_pay_date = $paydata->last_paid_date;
            $upload_date = $this->subs_date;
            if($last_pay_date!='' && $last_pay_date!='0000-00-00'){
                $to = Carbon::createFromFormat('Y-m-d H:s:i', $last_pay_date.' 3:30:34');
                $from = Carbon::createFromFormat('Y-m-d H:s:i', $upload_date.' 3:30:34');
                $diff_in_months = $to->diffInMonths($from);

                $member_doj = CacheMembers::getDojbyMemberCode($member->id);
                $to_one = Carbon::createFromFormat('Y-m-d H:s:i', $member_doj.' 3:30:34');
                $from_one = Carbon::createFromFormat('Y-m-d H:s:i', $upload_date.' 3:30:34');
                
                $diff_in_months_one = $to_one->diffInMonths($from_one);
                if($member->id==25439){
                    Log::channel('customlog')->info('member#:'.$member->id.'to month: '.$to.'From month'.$from);
                    Log::channel('customlog')->info('member#:'.$member->id.'doj: '.$to_one.'From month'.$from);
                }
                Log::channel('customlog')->info('member#:'.$member->id.'month diff: '.$diff_in_months);
                if($diff_in_months_one>=3 && $diff_in_months>=3 && $diff_in_months<=11){
                    Log::channel('customlog')->info('status changed for memberid: '.$member->id.'&status=2');

                    $updata = ['status_id' => 2,'updated_at' => date('Y-m-d h:i:s'), 'updated_by' => 11];
                    $savedata = Membership::where('id',$member->id)->update($updata);
                }else if ($diff_in_months_one>=3 && $diff_in_months>=12){
                    Log::channel('customlog')->info('status changed for memberid: '.$member->id.'&status=3');
                    $updata = ['status_id' => 3,'updated_at' => date('Y-m-d h:i:s'), 'updated_by' => 11];
                    $savedata = Membership::where('id',$member->id)->update($updata);
                }
            }
        }
    }
}
