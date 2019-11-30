<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Jobs\UpdateMonthendStatus;
use App\Model\State;
use Illuminate\Support\Facades\Crypt;
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
    public function insertMonthend($lang,Request $request)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', '1000');
        $entry_date = $request->input('entry_date');
        if($entry_date!=''){
            $datearr = explode("/",$entry_date);  
            $monthname = $datearr[0];
            $year = $datearr[1];
            $date = date('Y-m-d',strtotime('01-'.$monthname.'-'.$year));
            $cdate = date('Y-m-t',strtotime('01-'.$monthname.'-'.$year));
            $members = DB::table('membership')->select('id')->where('status_id','<=',2)->where('doj','<=',$cdate)->pluck('id');
            foreach( $members as $memberid){
                $monthrecordcount =  DB::table('membermonthendstatus as ms')->where('ms.MEMBER_CODE', '=' ,$memberid)
                ->where('ms.StatusMonth', '=' ,$date)
                ->count();
                if($monthrecordcount==0){
                   $sql = "INSERT INTO membermonthendstatus (StatusMonth,MEMBER_CODE,MEMBERTYPE_CODE, BANK_CODE,BRANCH_CODE,NUBE_BRANCH_CODE,SUBSCRIPTION_AMOUNT,BF_AMOUNT,
                    LASTPAYMENTDATE,TOTALSUBCRP_AMOUNT,TOTALBF_AMOUNT,TOTAL_MONTHS,TOTALMONTHSDUE,
                    TOTALMONTHSPAID,SUBSCRIPTIONDUE,BFDUE,ACCSUBSCRIPTION,ACCBF,ACCBENEFIT,
                    STATUS_CODE,ENTRY_DATE,ENTRY_TIME,INSURANCE_AMOUNT,TOTALINSURANCE_AMOUNT,
                    TOTALMONTHSCONTRIBUTION,INSURANCEDUE,ACCINSURANCE) SELECT '$date', m.id 
                    as memberid, m.designation_id, cb.company_id, m.branch_id, cb.union_branch_id,
                    mp.sub_monthly_amount,mp.bf_monthly_amount,mp.last_paid_date,'0','0','0',
                    (mp.totdue_months+1),mp.totpaid_months,(mp.duesub_amount+mp.sub_monthly_amount),
                    (mp.duebf_amount+mp.bf_monthly_amount),mp.accsub_amount,mp.accbf_amount,
                    mp.accbenefit_amount, m.status_id,'$date','00:00:00',mp.ins_monthly_amount, 
                   0, mp.totcontribution_months, 
                    (mp.dueins_amount+mp.ins_monthly_amount),mp.accins_amount from
                     membership as m LEFT JOIN company_branch AS cb ON cb.id = m.branch_id 
                     left join member_payments as mp on m.id=mp.member_id where m.status_id<=2 and m.id=$memberid";
                    
                    $status = DB::insert(DB::raw($sql));
                    
                }
            }
            return view('subscription.monthend_view')->with('success', 'Monthend record inserted Successfully for dues, you can upload it now');
        }else{
            return view('subscription.monthend_view')->with('error', 'please choose date');
        }
        // $date = '2019-09-01';
        // $members = DB::table('membership')->select('id')->where('status_id','<=',2)->pluck('id');
        // foreach( $members as $memberid){
        //     $monthrecordcount =  DB::table('membermonthendstatus as ms')->where('ms.MEMBER_CODE', '=' ,$memberid)
        //     ->where('ms.StatusMonth', '=' ,$date)
        //     ->count();
        //     if($monthrecordcount==0){
        //        $sql = "INSERT INTO membermonthendstatus (StatusMonth,MEMBER_CODE,MEMBERTYPE_CODE, BANK_CODE,BRANCH_CODE,NUBE_BRANCH_CODE,SUBSCRIPTION_AMOUNT,BF_AMOUNT,
        //         LASTPAYMENTDATE,TOTALSUBCRP_AMOUNT,TOTALBF_AMOUNT,TOTAL_MONTHS,TOTALMONTHSDUE,
        //         TOTALMONTHSPAID,SUBSCRIPTIONDUE,BFDUE,ACCSUBSCRIPTION,ACCBF,ACCBENEFIT,
        //         STATUS_CODE,ENTRY_DATE,ENTRY_TIME,INSURANCE_AMOUNT,TOTALINSURANCE_AMOUNT,
        //         TOTALMONTHSCONTRIBUTION,INSURANCEDUE,ACCINSURANCE) SELECT '$date', m.id 
        //         as memberid, m.designation_id, cb.company_id, m.branch_id, cb.union_branch_id,
        //         mp.sub_monthly_amount,mp.bf_monthly_amount,mp.last_paid_date,'0','0','0',
        //         (mp.totdue_months+1),mp.totpaid_months,(mp.duesub_amount+mp.sub_monthly_amount),
        //         (mp.duebf_amount+mp.bf_monthly_amount),mp.accsub_amount,mp.accbf_amount,
        //         mp.accbenefit_amount, m.status_id,'$date','00:00:00',mp.ins_monthly_amount, 
        //        0, mp.totcontribution_months, 
        //         (mp.dueins_amount+mp.ins_monthly_amount),mp.accins_amount from
        //          membership as m LEFT JOIN company_branch AS cb ON cb.id = m.branch_id 
        //          left join member_payments as mp on m.id=mp.member_id where m.status_id<=2 and m.id=$memberid";
                
        //         $status = DB::insert(DB::raw($sql));
                 
        //     }
        // }
        
        //return 'ok';
    }

    public function insertMonthendView(){
        return view('subscription.monthend_view');
    }

    public function ListMonthend(Request $request){
        $data['from_date'] = date('2019-01-01');
        $data['to_date'] = date('Y-m-d');
        $data['members_list'] = DB::table('membership as m')->where('m.doj','>=','2019-01-01')->orderBY('m.doj','asc')->get();
        return view('subscription.history_list')->with('data',$data);  
    }

    public function ajax_history_list(Request $request){
      
        $columns = array( 

            0 => 'name', 
            1 => 'member_number', 
            2 => 'doj',
            3 => 'id',
        );

        $totalData = DB::table('membership as m')->where('m.doj','>=','2019-01-01')
                    ->orderBY('m.doj','asc')
					->count();

        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            if( $limit == -1){
				
                $members = DB::table('membership as m')->select('m.id','m.name','m.member_number','m.status_id','m.doj')
                ->where('m.doj','>=','2019-01-01')
                //->join('state','country.id','=','state.country_id')
                ->orderBy($order,$dir)
                ->where('m.status','=','1')
				->get()->toArray();
            }else{
                $members = DB::table('membership as m')->select('m.id','m.name','m.member_number','m.status_id','m.doj')
                ->where('m.doj','>=','2019-01-01')
                //->join('state','country.id','=','state.country_id')
				->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->where('m.status','=','1')
                ->get()->toArray();
            }
        
        }
        else {
        $search = $request->input('search.value'); 
        if( $limit == -1){
			$members = DB::table('membership as m')->select('m.id','m.name','m.member_number','m.status_id','m.doj')
					//->join('state','country.id','=','state.country_id')
					//->where('state.id','LIKE',"%{$search}%")
                    ->orWhere('m.name', 'LIKE',"%{$search}%")
                    ->where('m.doj','>=','2019-01-01')
                    //->orWhere('state.state_name', 'LIKE',"%{$search}%")
                    ->where('m.status','=','1')
                    ->orderBy($order,$dir)
                    ->get()->toArray();
        }else{
            $members 	=  DB::table('country')->select('state.id','country.country_name','state.state_name','state.country_id','state.status')
						->join('state','country.id','=','state.country_id')
						->where('state.id','LIKE',"%{$search}%")
                        ->orWhere('country.country_name', 'LIKE',"%{$search}%")
                        ->orWhere('state.state_name', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->where('state.status','=','1')
                        ->orderBy($order,$dir)
                        ->get()->toArray();
        }
        $totalFiltered = DB::table('membership as m')
                  ->where('m.doj','>=','2019-01-01')
                  ->where('id','LIKE',"%{$search}%")
                    ->orWhere('name', 'LIKE',"%{$search}%")
                    
                    ->where('status','=','1')
                    ->count();
        }
        
        $table ="state";

        $data = array();
        if(!empty($members))
        {
            foreach ($members as $member)
            {
                $statusdate = date('Y-m-01',strtotime($member->doj));
                $monthcount =  DB::table('membermonthendstatus as ms')->where('ms.MEMBER_CODE', '=' ,$member->id)->where('ms.StatusMonth','=',$statusdate)->where('ms.TOTAL_MONTHS','=',0)->count();
                
                if($monthcount==1){
                   
                    $nestedData['id'] = $member->id;
                    $nestedData['name'] = $member->name;
                    $nestedData['member_number'] = $member->member_number;
                    $enc_id = Crypt::encrypt($member->id);
                    $nestedData['doj'] = date('d/M/Y',strtotime($member->doj));
                    //$editurl =  route('edit.irc', [app()->getLocale(),$enc_id]) ;
                    //$editurl = URL::to('/')."/en/sub-company-members/".$company_enc_id;
                    $edit = route('monthend.viewlists', [app()->getLocale(),$enc_id]);
                    
                    $actions ="<a class='waves-effect waves-light btn btn-sm' href='$edit'>Update History</a>";
                    $nestedData['options'] = $actions;
                    // if($irc->status_id!=4){
                    //     $nestedData['options'] = "";
                    // 	//$nestedData['options'] = "<a style='float: left;' class='btn btn-sm waves-effect waves-light cyan modal-trigger' href='".$editurl."'><i class='material-icons'>edit</i></a>";
                    // }else{
                    // 	$nestedData['options'] = "";
                    // }
                    $data[] = $nestedData;
                }else{
                    $totalFiltered--;
                    $totalData--;
                }
                
			}
        }
        dd($totalFiltered);
       
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );

        echo json_encode($json_data); 
    }

    public function ViewMemberHistory($lang,$encid)
    {
        $autoid = Crypt::decrypt($encid);
       // return $autoid;
       
        $data =  DB::table('membership as m')->select('m.id as memberid','c.id as companyid','cb.id as companybranchid','s.id as statusid','cb.branch_name','c.company_name','s.status_name','m.member_number','m.name','s.font_color','m.doj','m.salary')
        ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
        ->leftjoin('company as c','cb.company_id','=','c.id')
        ->leftjoin('status as s','m.status_id','=','s.id')
        ->where('m.id','=',$autoid)->first();

        return view('subscription.edit_history_rows')->with('data',$data);
    }

    public function ListMonthendFilter($lang,Request $request)
    {
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
       // date('Y-m-d',strtotime($to_date));
        $data['from_date'] = date('Y-m-d',strtotime($from_date));
        $data['to_date'] = date('Y-m-d',strtotime($to_date));
        $data['members_list'] = DB::table('membership as m')->where('m.doj','>=',$data['from_date'])->where('m.doj','<=',$data['to_date'])->orderBY('m.doj','asc')->get();
        return view('subscription.history_list')->with('data',$data);  
        //return $request->all();
    //     $autoid = Crypt::decrypt($encid);
    //    // return $autoid;
       
    //     $data =  DB::table('membership as m')->select('m.id as memberid','c.id as companyid','cb.id as companybranchid','s.id as statusid','cb.branch_name','c.company_name','s.status_name','m.member_number','m.name','s.font_color','m.doj','m.salary')
    //     ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
    //     ->leftjoin('company as c','cb.company_id','=','c.id')
    //     ->leftjoin('status as s','m.status_id','=','s.id')
    //     ->where('m.id','=',$autoid)->first();

    //     return view('subscription.edit_history_rows')->with('data',$data);
    }
}
