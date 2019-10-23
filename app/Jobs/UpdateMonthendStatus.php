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
use Carbon\Carbon;
use App\Model\Membership;

class UpdateMonthendStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $status_month;
    protected $member_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($membercode,$status_month)
    {
        $this->member_id = $membercode;
        $this->status_month = $status_month;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $member_id = $this->member_id;
        $status_month = $this->status_month;
        $monthrecord =  DB::table('membermonthendstatus as ms')->select('Id','StatusMonth','MEMBER_CODE as memberid','TOTALSUBCRP_AMOUNT','TOTALBF_AMOUNT','TOTALINSURANCE_AMOUNT')->where('ms.StatusMonth', '>' ,$status_month)
        ->where('ms.MEMBER_CODE', '=' ,$member_id)->get();

        $lastmonthrecord =  DB::table('membermonthendstatus as ms')->select('Id','StatusMonth','MEMBER_CODE as memberid','TOTALSUBCRP_AMOUNT','TOTALBF_AMOUNT','TOTALINSURANCE_AMOUNT','ACCBF','ACCSUBSCRIPTION','ACCINSURANCE','CURRENT_YDTBF','CURRENT_YDTSUBSCRIPTION','CURRENT_YDTINSURANCE')
            ->where('ms.StatusMonth', '=' ,$status_month)
            ->where('ms.MEMBER_CODE', '=' ,$member_id)->first();
        
        $ACCSUBSCRIPTION = $lastmonthrecord->ACCSUBSCRIPTION;
        $ACCBF = $lastmonthrecord->ACCBF;
        $ACCINSURANCE = $lastmonthrecord->ACCINSURANCE;
        $CURRENT_YDTBF = $lastmonthrecord->CURRENT_YDTBF;
        $CURRENT_YDTSUBSCRIPTION = $lastmonthrecord->CURRENT_YDTSUBSCRIPTION;
        $CURRENT_YDTINSURANCE = $lastmonthrecord->CURRENT_YDTINSURANCE;

        Log::channel('monthendlog')->info('monthendstatus update member id: '.$member_id.'&status_month='.$status_month);
        foreach($monthrecord as $month){
            Log::channel('monthendlog')->info('monthendstatus update member id: '.$member_id.'&updating on status_month='.$month->StatusMonth);

            $newACCBF=$ACCBF+$month->TOTALBF_AMOUNT;
            $newACCSUBSCRIPTION=$ACCSUBSCRIPTION+$month->TOTALSUBCRP_AMOUNT;
            $newCURRENT_YDTBF=$CURRENT_YDTBF+$month->TOTALBF_AMOUNT;
            $newCURRENT_YDTSUBSCRIPTION=$CURRENT_YDTSUBSCRIPTION+$month->TOTALSUBCRP_AMOUNT;
            $newACCINSURANCE=$ACCINSURANCE+$month->TOTALINSURANCE_AMOUNT;
            $newCURRENT_YDTINSURANCE=$CURRENT_YDTINSURANCE+$month->TOTALINSURANCE_AMOUNT;

            if($month->TOTALSUBCRP_AMOUNT!=0){
                $monthend_data = [ 
                    'SUBSCRIPTION_AMOUNT' => $month->TOTALSUBCRP_AMOUNT,
                    'BF_AMOUNT' => $month->TOTALBF_AMOUNT,
                    'INSURANCE_AMOUNT' => $month->TOTALINSURANCE_AMOUNT,
                    'TOTALSUBCRP_AMOUNT' => $month->TOTALSUBCRP_AMOUNT,
                    'TOTALBF_AMOUNT' => $month->TOTALBF_AMOUNT,
                    'TOTALINSURANCE_AMOUNT' => $month->TOTALINSURANCE_AMOUNT, 
                    'ACCBF' => $newACCBF,
                    'ACCSUBSCRIPTION' => $newACCSUBSCRIPTION,
                    'ACCINSURANCE' => $newACCINSURANCE, 
                    'CURRENT_YDTBF' => $newCURRENT_YDTBF,
                    'CURRENT_YDTINSURANCE' => $newCURRENT_YDTSUBSCRIPTION,
                    'CURRENT_YDTSUBSCRIPTION' => $newCURRENT_YDTINSURANCE,
                 ];
                 $status = DB::table('membermonthendstatus')->where('Id', $month->Id)->update($monthend_data);
                 $ACCBF=$newACCBF;
                 $ACCSUBSCRIPTION=$newACCSUBSCRIPTION;
                 $ACCINSURANCE=$newACCINSURANCE;
                 $CURRENT_YDTBF=$newCURRENT_YDTBF;
                 $CURRENT_YDTSUBSCRIPTION = $newCURRENT_YDTSUBSCRIPTION;
                 $CURRENT_YDTINSURANCE = $newCURRENT_YDTINSURANCE;
            }else{
                $monthend_data = [ 
                    'SUBSCRIPTION_AMOUNT' => $month->TOTALSUBCRP_AMOUNT,
                    'BF_AMOUNT' => $month->TOTALBF_AMOUNT,
                    'INSURANCE_AMOUNT' => $month->TOTALINSURANCE_AMOUNT,
                    'TOTALSUBCRP_AMOUNT' => $month->TOTALSUBCRP_AMOUNT,
                    'TOTALBF_AMOUNT' => $month->TOTALBF_AMOUNT,
                    'TOTALINSURANCE_AMOUNT' => $month->TOTALINSURANCE_AMOUNT, 
                    'ACCBF' => $ACCBF,
                    'ACCSUBSCRIPTION' => $ACCSUBSCRIPTION,
                    'ACCINSURANCE' => $ACCINSURANCE, 
                    'CURRENT_YDTBF' => $CURRENT_YDTBF,
                    'CURRENT_YDTINSURANCE' => $CURRENT_YDTINSURANCE,
                    'CURRENT_YDTSUBSCRIPTION' => $CURRENT_YDTSUBSCRIPTION,
                 ];
                 $status = DB::table('membermonthendstatus')->where('Id', $month->Id)->update($monthend_data);
            }
            
        }
    }
}
