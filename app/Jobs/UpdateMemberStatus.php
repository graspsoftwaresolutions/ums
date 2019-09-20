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



class UpdateMemberStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $sub_company_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($company_auto_id)
    {
        $this->sub_company_id = $company_auto_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $company_auto_id = $this->sub_company_id;
        $company_id =  DB::table('mon_sub_company as sc')->where('id', '=',$company_auto_id)->pluck('CompanyCode')->first();
        $members =  DB::table('membership as m')
                    ->select('m.id')
                    ->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
                    ->leftjoin('company as c','c.id','=','cb.company_id')
                    ->where('cb.company_id', '=',$company_id)
                    ->get();
        foreach($members as $member){
            
        }
    }
}
