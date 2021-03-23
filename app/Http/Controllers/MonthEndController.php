<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Jobs\UpdateMonthendStatus;
use App\Model\State;
use Illuminate\Support\Facades\Crypt;
use Artisan;
use App\Helpers\CommonHelper;
use Auth;
use Facades\App\Repository\CacheMembers;
use DateTime;
use Log;

class MonthEndController extends Controller
{
    public function __construct() {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);
        $this->membermonthendstatus_table = "membermonthendstatus";
    }
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
        $data['due_month'] = 0;
        $data['company_id'] = '';
        $data['branch_id'] = '';
        $data['company_view'] = DB::table('company')->where('status','=','1')->get();
        
        $data['members_list'] = DB::table('membership as m')->select('id','name','doj','status_id','branch_id','member_number')->where('m.doj','>=','2019-01-01')->orderBY('m.doj','asc')->get();
        return view('subscription.history_list')->with('data',$data);  
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
        $due_month = $request->input('due_month');

        $company_id = $request->input('company_id');
        $branch_id = $request->input('branch_id');
       // date('Y-m-d',strtotime($to_date));
        $data['from_date'] = date('Y-m-d',strtotime($from_date));
        $data['to_date'] = date('Y-m-d',strtotime($to_date));
        $data['due_month'] = $due_month;
        $data['company_id'] = $company_id;
        $data['branch_id'] = $branch_id;
        $data['company_view'] = DB::table('company')->where('status','=','1')->get();

        if($branch_id!=''){
            $member_qry = DB::table('membership as m')->select('m.id','m.name','m.member_number','m.doj','m.status_id','m.branch_id');
            $member_qry = $member_qry->where('m.branch_id','=',$branch_id);
        }else{
            if($company_id!=""){
                $member_qry = DB::table('membership as m')->select('m.id','m.name','m.member_number','m.doj','m.status_id','m.branch_id')
            ->leftjoin('company_branch as cb','cb.id','=','m.branch_id');
            $member_qry = $member_qry->where('cb.company_id','=',$company_id);
            }else{
                $member_qry = DB::table('membership as m')->select('m.id','m.name','m.member_number','m.doj','m.status_id','m.branch_id')
                ->leftjoin('company_branch as cb','cb.id','=','m.branch_id');
            }
            
            
        }

        $data['members_list'] = $member_qry->where('m.doj','>=',$data['from_date'])->where('m.doj','<=',$data['to_date'])->orderBY('m.doj','asc')->get();
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

    public function ListMemberFilter($lang,Request $request)
    {
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $status_id = $request->input('status_id');
        $data['status_view'] = DB::table('status')->where('status','=','1')->get();
       // date('Y-m-d',strtotime($to_date));
        $data['from_date'] = date('Y-m-d',strtotime($from_date));
        $data['to_date'] = date('Y-m-d',strtotime($to_date));
        $data['status_id'] = $status_id;
        //$data['members_list'] = DB::table('membership as m')->where('m.doj','>=',$data['from_date'])->where('m.doj','<=',$data['to_date'])->orderBY('m.doj','asc')->get();
        return view('subscription.memberlist')->with('data',$data);  

    }

    public function ListMembers(Request $request){
        $data['from_date'] = date('1940-01-01');
        $data['to_date'] = date('Y-m-d');
        $data['status_id'] = '';
        $data['status_view'] = DB::table('status')->where('status','=','1')->get();
       // $data['members_list'] = DB::table('membership as m')->where('m.doj','>=','2019-01-01')->orderBY('m.doj','asc')->get();
        return view('subscription.memberlist')->with('data',$data);  
    }


    public function ajax_member_list(Request $request){
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $status_id = $request->input('status_id');
        $columns = array( 

            0 => 'name', 
            1 => 'member_number', 
            2 => 'doj',
            3 => 'branch_id',
            4 => 'branch_id',
            5 => 'status_id',
            6 => 'id',
        );

        $totalDataqry = DB::table('membership as m')
                    ->where('m.doj','>=',$from_date)
                    ->where('m.doj','<=',$to_date);

        if($status_id!=''){
            $totalDataqry = $totalDataqry->where('m.status_id','=',$status_id);
        }

        $totalData = $totalDataqry->orderBY('m.doj','asc')->count();


        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            if( $limit == -1){
                
                $membersqry = DB::table('membership as m')->select('m.id','m.name','m.member_number','m.status_id','m.doj','m.branch_id')
                ->where('m.doj','>=',$from_date)
                ->where('m.doj','<=',$to_date);
                if($status_id!=''){
                    $membersqry = $membersqry->where('m.status_id','=',$status_id);
                }
                //->join('state','country.id','=','state.country_id')
                $members = $membersqry->orderBy($order,$dir)
                ->where('m.status','=','1')
                ->get()->toArray();
            }else{
                $membersqry = DB::table('membership as m')->select('m.id','m.name','m.member_number','m.status_id','m.doj','m.branch_id')
                ->where('m.doj','>=',$from_date)
                ->where('m.doj','<=',$to_date);
                if($status_id!=''){
                    $membersqry = $membersqry->where('m.status_id','=',$status_id);
                }
                $members = $membersqry->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->where('m.status','=','1')
                ->get()->toArray();
            }
        
        }
        else {
        $search = $request->input('search.value'); 
        if( $limit == -1){
            $membersqry = DB::table('membership as m')->select('m.id','m.name','m.member_number','m.status_id','m.doj','m.branch_id')
                    ->orWhere('m.name', 'LIKE',"%{$search}%")
                    ->orWhere('m.member_number', 'LIKE',"%{$search}%")
                    ->where('m.doj','>=',$from_date)
                    ->where('m.doj','<=',$to_date);
                    if($status_id!=''){
                        $membersqry = $membersqry->where('m.status_id','=',$status_id);
                    }
                    $members = $membersqry->where('m.status','=','1')
                    ->orderBy($order,$dir)
                    ->get()->toArray();
        }else{
            $membersqry    =  DB::table('membership as m')->select('m.id','m.name','m.member_number','m.status_id','m.doj','m.branch_id')
                        ->orWhere('m.name', 'LIKE',"%{$search}%")
                        ->orWhere('m.member_number', 'LIKE',"%{$search}%")
                        ->where('m.doj','>=',$from_date)
                        ->where('m.doj','<=',$to_date);
                        if($status_id!=''){
                            $membersqry = $membersqry->where('m.status_id','=',$status_id);
                        }
                        $members = $membersqry->where('m.status','=','1')
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get()->toArray();
        }
        $totalFilteredqry = DB::table('membership as m')
                    ->where('m.doj','>=',$from_date)
                    ->where('m.doj','<=',$to_date);
                    if($status_id!=''){
                        $totalFilteredqry = $totalFilteredqry->where('m.status_id','=',$status_id);
                    }
                    $totalFiltered = $totalFilteredqry->where('id','LIKE',"%{$search}%")
                    ->orWhere('name', 'LIKE',"%{$search}%")
                    
                    ->where('status','=','1')
                    ->count();
        }
        
        $table ="membership";

        $data = array();
        if(!empty($members))
        {
            foreach ($members as $member)
            {
                $statusdate = date('Y-m-01',strtotime($member->doj));

                $branch_data = CommonHelper::getBranchCompany($member->branch_id);
                
                
                $nestedData['id'] = $member->id;
                $nestedData['name'] = $member->name;
                $nestedData['member_number'] = $member->member_number;
                $nestedData['company'] = $branch_data->company_name;
                $nestedData['branch'] = $branch_data->branch_name;
                $enc_id = Crypt::encrypt($member->id);
                $nestedData['doj'] = date('d/M/Y',strtotime($member->doj));
                $nestedData['status'] = CommonHelper::get_member_status_name($member->status_id);
                //$editurl =  route('edit.irc', [app()->getLocale(),$enc_id]) ;
                //$editurl = URL::to('/')."/en/sub-company-members/".$company_enc_id;
                $edit = route('monthend.viewlistsall', [app()->getLocale(),$enc_id]);
                $view = route('member.history', [app()->getLocale(),$enc_id]);
                $newhistory = route('monthend.addhistory', [app()->getLocale(),$enc_id]);
                
                $actions ="<a class='waves-effect waves-light btn btn-sm' href='$edit'>Update</a><a style='margin-left: 10px;' title='History'  class='waves-effect waves-light blue btn btn-sm' href='$view'>View</a>";

                $actions .="<a style='margin-left: 10px;' title='History'  class='waves-effect waves-light green btn btn-sm' href='$newhistory'>Add New</a>";
                
                $nestedData['options'] = $actions;
                // if($irc->status_id!=4){
                //     $nestedData['options'] = "";
                //  //$nestedData['options'] = "<a style='float: left;' class='btn btn-sm waves-effect waves-light cyan modal-trigger' href='".$editurl."'><i class='material-icons'>edit</i></a>";
                // }else{
                //  $nestedData['options'] = "";
                // }
                $data[] = $nestedData;
                
                
            }
        }
       // dd($totalFiltered);
       
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );

        echo json_encode($json_data); 
    }

    public function ViewAllMemberHistory($lang,$encid)
    {
        $autoid = Crypt::decrypt($encid);
       // return $autoid;
       
        $data =  DB::table('membership as m')->select('m.id as memberid','c.id as companyid','cb.id as companybranchid','s.id as statusid','cb.branch_name','c.company_name','s.status_name','m.member_number','m.name','s.font_color','m.doj','m.salary')
        ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
        ->leftjoin('company as c','cb.company_id','=','c.id')
        ->leftjoin('status as s','m.status_id','=','s.id')
        ->where('m.id','=',$autoid)->first();

        return view('subscription.edit_allhistory_rows')->with('data',$data);
    }

    public function ListMonthendDue(Request $request){
        // $data['from_date'] = date('2019-01-01');
        // $data['to_date'] = date('Y-m-d');
        $data['status_id'] = '';
        $data['due_months'] = '';
        $data['company_id'] = '';
        $data['branch_id'] = '';
        $data['status_view'] = DB::table('status')->where('status','=','1')->get();
        $data['company_view'] = DB::table('company')->where('status','=','1')->get();
        $data['members_list'] = [];
        return view('subscription.due_members_list')->with('data',$data);  
    }

    public function ListMonthendDueFilter($lang,Request $request)
    {
        ini_set('memory_limit', '-1');
		ini_set('max_execution_time', 10000); 
        // $from_date = $request->input('from_date');
        // $to_date = $request->input('to_date');
        $status_id = $request->input('status_id');
        $due_months = $request->input('due_months');
        $company_id = $request->input('company_id');
        $branch_id = $request->input('branch_id');
       // date('Y-m-d',strtotime($to_date));
        //$data['from_date'] = date('Y-m-d',strtotime($from_date));
        $data['company_view'] = DB::table('company')->where('status','=','1')->get();
        //$data['to_date'] = date('Y-m-d',strtotime($to_date)); 
        $data['status_id'] = $status_id;
        $data['company_id'] = $company_id;
        $data['branch_id'] = $branch_id;
        $data['due_months'] = $due_months;
        $data['status_view'] = DB::table('status')->where('status','=','1')->get();
       
      
        if($branch_id!=''){
            $member_qry = DB::table('membership as m')->select('m.id','m.name','m.member_number','m.doj','m.status_id','m.branch_id');
            $member_qry = $member_qry->where('m.branch_id','=',$branch_id);
        }else{
            $member_qry = DB::table('membership as m')->select('m.id','m.name','m.member_number','m.doj','m.status_id','m.branch_id')
            ->leftjoin('company_branch as cb','cb.id','=','m.branch_id');
            $member_qry = $member_qry->where('cb.company_id','=',$company_id);
            
        }
        
        if($status_id!=''){
            $member_qry = $member_qry->where('m.status_id','=',$status_id);
        }
        //dd($member_qry->count());
        $member_qry = $member_qry->orderBY('m.doj','asc')->get();
        $data['members_list'] = $member_qry;
        return view('subscription.due_members_list')->with('data',$data);  
     
    }

    public function memberallHistory($lang,$encid)
    {
        $autoid = Crypt::decrypt($encid);
       // return $autoid;
       
        $data =  DB::table('membership as m')->select('m.id as memberid','c.id as companyid','cb.id as companybranchid','s.id as statusid','cb.branch_name','c.company_name','s.status_name','m.member_number','m.name','s.font_color','m.doj','m.salary')
        ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
        ->leftjoin('company as c','cb.company_id','=','c.id')
        ->leftjoin('status as s','m.status_id','=','s.id')
        ->where('m.id','=',$autoid)->first();

        return view('subscription.add_allhistory_rows')->with('data',$data);
    }
    public function saveMonthendRows($lang,$ref,Request $request)
    {
        
        $entered_name = $request->input('entered_name');
        $ip_address = $request->ip();
       
        //return $request->all();
        $member_id = $request->input('member_id');
        $doj_date = $request->input('doj_date');
        $from_date = date('Y-m-d',strtotime($doj_date));
        $history_update_from = $from_date;
        $doj_subs = $request->input('doj_subs');
        $doj_bf = $request->input('doj_bf');
        $doj_ins = $request->input('doj_ins');
        $entrance_fee = $request->input('entrance_fee');
        $hq_fee = $request->input('hq_fee');
        $last_paid_date = Null;
        $memberdata =DB::table("membership")->select('branch_id','designation_id','status_id','name','member_number')->where('id','=',$member_id)->first();
        $branchdata = DB::table("company_branch")->where('id','=',$memberdata->branch_id)->first();
         

            $subscription_amount = $request->input('subscription_amount');
            $all_entry_date = $request->input('entry_date');
            
            $total_subscription_amount = $request->input('total_subscription_amount');
            $total_bf_amount = $request->input('total_bf_amount');
            $total_insurance_amount = $request->input('total_insurance_amount');
            $paycount = 0;
           
            if(isset($all_entry_date)){
                $historycount = count($all_entry_date);
               
                for ($i=0; $i < $historycount; $i++) { 
                    $month_auto_id = $request->input('month_auto_id')[$i];
                    $subs_amount = 0;
                    $bf_amount = 0;
                    $insurance_amount = 0;
                    $entry_date = $request->input('entry_date')[$i];
                    $entry_status_month = date('Y-m-d',strtotime($entry_date));
                    $total_months = $request->input('total_months')[$i];
                    if($entry_status_month!=''){
                        $subs_amount = $subs_amount=='' ? 0 : $subs_amount;
                        $bf_amount = $bf_amount=='' ? 0 : $bf_amount;
                        $insurance_amount = $insurance_amount=='' ? 0 : $insurance_amount;
                        
                        $total_months = $total_months=='' ? 0 : $total_months;
                        if($month_auto_id!=''){
                            // $monthend_data = [
                            //     'ENTRYMODE' => 'S',
                            //     'SUBSCRIPTION_AMOUNT' => $subs_amount,
                            //     'TOTALSUBCRP_AMOUNT' => $subs_amount,
                            //     'BF_AMOUNT' => $bf_amount,
                            //     'TOTALBF_AMOUNT' => $bf_amount,
                            //     'INSURANCE_AMOUNT' => $insurance_amount,
                            //     'TOTALINSURANCE_AMOUNT' => $insurance_amount,
                            //     'TOTAL_MONTHS' => $total_months,
                            // ];
                            // $upstatus = DB::table('membermonthendstatus')->where('MEMBER_CODE', '=', $member_id)->where('StatusMonth', '=', $entry_status_month)->where('Id', '=', $month_auto_id)->update($monthend_data);
                        }else{
                            $mont_count = DB::table($this->membermonthendstatus_table)->where('StatusMonth', '=', $entry_status_month)->where('arrear_status', '=', 0)->where('MEMBER_CODE', '=', $member_id)->count();

                            if($mont_count==0){
                               
                            $monthend_data = [
                                'StatusMonth' => $entry_status_month, 
                                'MEMBER_CODE' => $member_id,
                                'SUBSCRIPTION_AMOUNT' => $subs_amount,
                                'BF_AMOUNT' => $bf_amount,
                                'LASTPAYMENTDATE' => $entry_status_month,
                                'TOTALSUBCRP_AMOUNT' => $subs_amount,
                                'TOTALBF_AMOUNT' => $bf_amount,
                                'TOTAL_MONTHS' => $total_months,
                                'BANK_CODE' => $branchdata->company_id,
                                'NUBE_BRANCH_CODE' => $branchdata->union_branch_id,
                                'BRANCH_CODE' => $memberdata->branch_id,
                                'MEMBERTYPE_CODE' => $memberdata->designation_id,
                                'ENTRYMODE' => 'S',
                                'STATUS_CODE' => $memberdata->status_id,
                                'RESIGNED' => $memberdata->status_id==4 ? 1 : 0,
                                'ENTRY_DATE' => date('Y-m-d'),
                                'ENTRY_TIME' => date('h:i:s'),
                                'STRUCKOFF' => $memberdata->status_id==3 ? 1 : 0,
                                'INSURANCE_AMOUNT' => $insurance_amount,
                                'TOTALINSURANCE_AMOUNT' => $insurance_amount,
                            ];
                            DB::table($this->membermonthendstatus_table)->insert($monthend_data);
                            }
                            
                        }
                      
                        //return $db_arrear_date;
                        $paycount++;
    
                    }
                }
            }
            //dd('hi');

            if($history_update_from!=""){
                $is_old_record = DB::table($this->membermonthendstatus_table." as ms")
                ->select('ms.StatusMonth','ms.LASTPAYMENTDATE','ms.ACCBF','ms.ACCSUBSCRIPTION','ms.SUBSCRIPTION_AMOUNT','ms.BF_AMOUNT','ms.TOTALMONTHSPAID','ms.ACCINSURANCE','ms.TOTALMONTHSDUE','ms.SUBSCRIPTIONDUE','ms.TOTALMONTHSCONTRIBUTION','ms.INSURANCEDUE','ms.BFDUE','ms.INSURANCE_AMOUNT','ms.TOTAL_MONTHS')
                ->where('MEMBER_CODE', '=', $member_id)
                ->orderBY('StatusMonth','asc')
                ->limit(1)
                ->first();

                if($is_old_record->TOTALMONTHSDUE>1 || $is_old_record->TOTALMONTHSPAID>1){
                    $last_mont_record = $is_old_record;

                    $below_mont_records = DB::table($this->membermonthendstatus_table." as ms")
                    ->select('ms.StatusMonth','ms.Id','ms.SUBSCRIPTION_AMOUNT','ms.BF_AMOUNT','ms.INSURANCE_AMOUNT','ms.TOTAL_MONTHS','ms.arrear_status')
                    ->where('StatusMonth', '>', $is_old_record->StatusMonth)->where('MEMBER_CODE', '=', $member_id)
                    ->orderBY('StatusMonth','asc')
                    ->orderBY('arrear_status','asc')
                    ->get();
                }else{
                    $last_mont_record = DB::table($this->membermonthendstatus_table." as ms")
                    ->select('ms.StatusMonth','ms.LASTPAYMENTDATE','ms.ACCBF','ms.ACCSUBSCRIPTION','ms.SUBSCRIPTION_AMOUNT','ms.BF_AMOUNT','ms.TOTALMONTHSPAID','ms.ACCINSURANCE','ms.TOTALMONTHSDUE','ms.SUBSCRIPTIONDUE','ms.TOTALMONTHSCONTRIBUTION','ms.INSURANCEDUE','ms.BFDUE','ms.INSURANCE_AMOUNT','ms.TOTAL_MONTHS')
                    ->where('StatusMonth', '<', $history_update_from)->where('MEMBER_CODE', '=', $member_id)
                    ->orderBY('StatusMonth','desc')
                    ->limit(1)
                    ->first();

                    $below_mont_records = DB::table($this->membermonthendstatus_table." as ms")
                    ->select('ms.StatusMonth','ms.Id','ms.SUBSCRIPTION_AMOUNT','ms.BF_AMOUNT','ms.INSURANCE_AMOUNT','ms.TOTAL_MONTHS','ms.arrear_status')
                    ->where('StatusMonth', '>=', $history_update_from)->where('MEMBER_CODE', '=', $member_id)
                    ->orderBY('StatusMonth','asc')
                    ->orderBY('arrear_status','asc')
                    ->get();
                }

                

                $last_ACCINSURANCE = !empty($last_mont_record) ? $last_mont_record->ACCINSURANCE : 0;
                $last_ACCSUBSCRIPTION = !empty($last_mont_record) ? $last_mont_record->ACCSUBSCRIPTION : 0;
                $last_ACCBF = !empty($last_mont_record) ? $last_mont_record->ACCBF : 0;
                $last_SUBSCRIPTIONDUE = !empty($last_mont_record) ? $last_mont_record->SUBSCRIPTIONDUE : 0;
                $last_BFDUE = !empty($last_mont_record) ? $last_mont_record->BFDUE : 0;
                $last_INSURANCEDUE = !empty($last_mont_record) ? $last_mont_record->INSURANCEDUE : 0;
                $last_TOTALMONTHSDUE = !empty($last_mont_record) ? $last_mont_record->TOTALMONTHSDUE : 0;
                $last_TOTALMONTHSPAID = !empty($last_mont_record) ? $last_mont_record->TOTALMONTHSPAID : 0;
                $last_TOTALMONTHSCONTRIBUTION = !empty($last_mont_record) ? $last_mont_record->TOTALMONTHSCONTRIBUTION : 0;
                
                foreach($below_mont_records as $monthend){
                    $m_subs_amt = $monthend->SUBSCRIPTION_AMOUNT;
                    $m_bf_amt = $monthend->BF_AMOUNT;
                    $m_ins_amt = $monthend->INSURANCE_AMOUNT;
                    $m_total_months = $monthend->TOTAL_MONTHS;
                    $arrear_status = $monthend->arrear_status;

                    if($m_total_months>=1){
                        $new_ACCINSURANCE = $last_ACCINSURANCE+$m_ins_amt;
                        $new_ACCSUBSCRIPTION = $last_ACCSUBSCRIPTION+$m_subs_amt;
                        $new_ACCBF = $last_ACCBF+$m_bf_amt;
                        $new_SUBSCRIPTIONDUE = $last_SUBSCRIPTIONDUE;
                        $new_BFDUE = $last_BFDUE;
                        $new_INSURANCEDUE = $last_INSURANCEDUE;
                        if($m_total_months>=1){
                            if($arrear_status==1){
                                $new_TOTALMONTHSDUE = $last_TOTALMONTHSDUE-$m_total_months;
                            }else{
                                $new_TOTALMONTHSDUE = $m_total_months==1 ? $last_TOTALMONTHSDUE : ($last_TOTALMONTHSDUE+1)-$m_total_months;
                                //$new_TOTALMONTHSDUE = $m_total_months>1 ? ($last_TOTALMONTHSDUE+1)-$m_total_months : $last_TOTALMONTHSDUE-$m_total_months;
                            }
                        }else{
                            $new_TOTALMONTHSDUE = $last_TOTALMONTHSDUE;
                        }
                        
                        
                        $new_TOTALMONTHSPAID = $last_TOTALMONTHSPAID+$m_total_months;
                        $new_TOTALMONTHSCONTRIBUTION = $last_TOTALMONTHSCONTRIBUTION+$m_total_months;
                        $last_paid_date = $monthend->StatusMonth;
                    }else{
                        $new_ACCINSURANCE = $last_ACCINSURANCE;
                        $new_ACCSUBSCRIPTION = $last_ACCSUBSCRIPTION;
                        $new_ACCBF = $last_ACCBF;
                        $new_SUBSCRIPTIONDUE = $last_SUBSCRIPTIONDUE+$m_subs_amt;
                        $new_BFDUE = $last_BFDUE+$m_bf_amt;
                        $new_INSURANCEDUE = $last_INSURANCEDUE+$m_ins_amt;
                        $new_TOTALMONTHSDUE = $last_TOTALMONTHSDUE+1;
                        $new_TOTALMONTHSPAID = $last_TOTALMONTHSPAID;
                        $new_TOTALMONTHSCONTRIBUTION = $last_TOTALMONTHSCONTRIBUTION;

                        $last_paid_date = DB::table($this->membermonthendstatus_table." as ms")
                        ->select('ms.LASTPAYMENTDATE')
                        ->where('StatusMonth', '<', $monthend->StatusMonth)->where('MEMBER_CODE', '=', $member_id)
                        ->orderBY('StatusMonth','desc')
                        ->limit(1)
                        ->pluck('ms.LASTPAYMENTDATE')
                        ->first();
                    }
                
                    
                    $monthend_datas = [
                                'TOTALMONTHSDUE' => $new_TOTALMONTHSDUE,
                                'TOTALMONTHSPAID' => $new_TOTALMONTHSPAID,
                                'SUBSCRIPTIONDUE' => $new_SUBSCRIPTIONDUE,
                                'BFDUE' => $new_BFDUE,
                                'INSURANCEDUE' => $new_INSURANCEDUE,
                                'ACCSUBSCRIPTION' => $new_ACCSUBSCRIPTION,
                                'ACCBF' => $new_ACCBF,
                                'ACCINSURANCE' => $new_ACCINSURANCE,
                                'TOTALMONTHSCONTRIBUTION' => $new_TOTALMONTHSCONTRIBUTION,
                                'LASTPAYMENTDATE' => $last_paid_date,
                            ];
                    $m_upstatus = DB::table('membermonthendstatus')->where('Id', '=', $monthend->Id)->update($monthend_datas);

                    $last_ACCINSURANCE = $new_ACCINSURANCE;
                    $last_ACCSUBSCRIPTION = $new_ACCSUBSCRIPTION;
                    $last_ACCBF = $new_ACCBF;
                    $last_SUBSCRIPTIONDUE = $new_SUBSCRIPTIONDUE;
                    $last_BFDUE = $new_BFDUE;
                    $last_INSURANCEDUE = $new_INSURANCEDUE;
                    $last_TOTALMONTHSDUE = $new_TOTALMONTHSDUE;
                    $last_TOTALMONTHSPAID = $new_TOTALMONTHSPAID;
                    $last_TOTALMONTHSCONTRIBUTION = $new_TOTALMONTHSCONTRIBUTION;
                }

                $payment_data = [
                        'last_paid_date' => $last_paid_date,
                        'totpaid_months' => $last_TOTALMONTHSPAID,
                        'totcontribution_months' => $last_TOTALMONTHSCONTRIBUTION,
                        'totdue_months' => $last_TOTALMONTHSDUE,
                        'accbf_amount' => $last_ACCBF,
                        'accsub_amount' => $last_ACCSUBSCRIPTION,
                        'accins_amount' => $last_ACCINSURANCE,
                        'duebf_amount' =>  $last_BFDUE,
                        'dueins_amount' => $last_INSURANCEDUE,
                        'duesub_amount' =>  $last_SUBSCRIPTIONDUE,
                        'hq_fee' => $hq_fee,
                        'entrance_fee' => $entrance_fee,
                        'updated_by' => Auth::user()->id,
                    ];
                    DB::table('member_payments')->where('member_id', $member_id)->update($payment_data);
                // if($last_TOTALMONTHSDUE<=3){
                //     $m_status = 1;
                //     DB::table('membership')->where('id', $member_id)->update(['status_id' => $m_status]);
                // }else if($last_TOTALMONTHSDUE<=13){
                //     $m_status = 2;
                //     DB::table('membership')->where('id', $member_id)->update(['status_id' => $m_status]);
                // }
                
            }
       
        
        Log::channel('historychangelog')->info('-----------------------------');
        Log::channel('historychangelog')->info('member id= '.$member_id.'&member_number='.$memberdata->member_number.'&name='.$memberdata->name);
        Log::channel('historychangelog')->info('Updated by= '.$entered_name.'&auth id='.Auth::user()->id.'&ip='.$ip_address);
        Log::channel('historychangelog')->info('-----------------------------');
        //Log::channel('historychangelog')->info('Updated ip: '.$ip_address);
        
        return redirect($lang.'/clean-membership')->with('message','History Updated Successfully!!');

       
    }

    public function ListMonthendFollowUp(Request $request){
        // $data['from_date'] = date('2019-01-01');
        // $data['to_date'] = date('Y-m-d');
        $data['type'] = '';
        $data['company_id'] = '';
        $data['subs_month'] = date('Y-m-01');
        $data['branch_id'] = '';
        $data['status_view'] = DB::table('status')->where('status','=','1')->get();
        $data['company_view'] = DB::table('company')->where('status','=','1')->get();
        $data['members_list'] = [];
        $data['followup_type'] = '';
        return view('subscription.followup_members_list')->with('data',$data);  
    }

    public function ListMonthendFollowUpFilter($lang,Request $request)
    {
        ini_set('memory_limit', '-1');
		ini_set('max_execution_time', 10000); 
        $entry_date = $request->input('entry_date');
        $datearr = explode("/",$entry_date);  
        $monthname = $datearr[0];
        $year = $datearr[1];
        $full_date = date('Y-m-d',strtotime('01-'.$monthname.'-'.$year));
        $data['subs_month'] = date('Y-m-01',strtotime($full_date));
        // $to_date = $request->input('to_date');
       // $status_id = $request->input('status_id');
        $followup_type = $request->input('followup_type');
        $company_id = $request->input('company_id');
        $branch_id = $request->input('branch_id');
       // date('Y-m-d',strtotime($to_date));
        //$data['from_date'] = date('Y-m-d',strtotime($from_date));
        $data['company_view'] = DB::table('company')->where('status','=','1')->get();
        //$data['to_date'] = date('Y-m-d',strtotime($to_date)); 
        //$data['status_id'] = $status_id;
        $data['company_id'] = $company_id;
        $data['branch_id'] = $branch_id;
        $data['followup_type'] = $followup_type;
        //$data['subs_month'] = date('Y-m-01');
        //$data['status_view'] = DB::table('status')->where('status','=','1')->get();
       
      
        if($branch_id!=''){
            $member_qry = DB::table('membership as m')
            ->select('m.id','m.name','m.member_number','m.doj','m.status_id','m.branch_id');
            //->leftjoin('member_payments as mp','mp.member_id','=','m.id');
            $member_qry = $member_qry->where('m.branch_id','=',$branch_id);
        }else{
            $member_qry = DB::table('membership as m')->select('m.id','m.name','m.member_number','m.doj','m.status_id','m.branch_id')
            //->leftjoin('member_payments as mp','mp.member_id','=','m.id')
            ->leftjoin('company_branch as cb','cb.id','=','m.branch_id');
            $member_qry = $member_qry->where('cb.company_id','=',$company_id);
            
        }
        
        if($followup_type==1 || $followup_type==2){
            $member_qry = $member_qry->where('m.status_id','=',1);
        }
        if($followup_type==3 || $followup_type==4){
            $member_qry = $member_qry->where('m.status_id','=',2);
        }
        //dd($member_qry->count());
        $member_qry = $member_qry->orderBY('m.doj','asc')->get();
        $data['members_list'] = $member_qry;
        return view('subscription.followup_members_list')->with('data',$data);  
     
    }

    public function ajax_cleanmember_list(Request $request){
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $status_id = $request->input('status_id');
        $columns = array( 

            0 => 'name', 
            1 => 'member_number', 
            2 => 'doj',
            3 => 'branch_id',
            4 => 'branch_id',
            5 => 'status_id',
            6 => 'id',
        );

        $totalDataqry = DB::table('membership as m')
                    ->where('m.doj','>=',$from_date)
                    ->where('m.doj','<=',$to_date);

        if($status_id!=''){
            $totalDataqry = $totalDataqry->where('m.status_id','=',$status_id);
        }

        $totalData = $totalDataqry->orderBY('m.doj','asc')->count();


        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            if( $limit == -1){
                
                $membersqry = DB::table('membership as m')->select('m.id','m.name','m.member_number','m.status_id','m.doj','m.branch_id')
                ->where('m.doj','>=',$from_date)
                ->where('m.doj','<=',$to_date);
                if($status_id!=''){
                    $membersqry = $membersqry->where('m.status_id','=',$status_id);
                }
                //->join('state','country.id','=','state.country_id')
                $members = $membersqry->orderBy($order,$dir)
                ->where('m.status','=','1')
                ->get()->toArray();
            }else{
                $membersqry = DB::table('membership as m')->select('m.id','m.name','m.member_number','m.status_id','m.doj','m.branch_id')
                ->where('m.doj','>=',$from_date)
                ->where('m.doj','<=',$to_date);
                if($status_id!=''){
                    $membersqry = $membersqry->where('m.status_id','=',$status_id);
                }
                $members = $membersqry->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->where('m.status','=','1')
                ->get()->toArray();
            }
        
        }
        else {
        $search = $request->input('search.value'); 
        if( $limit == -1){
            $membersqry = DB::table('membership as m')->select('m.id','m.name','m.member_number','m.status_id','m.doj','m.branch_id')
                    ->orWhere('m.name', 'LIKE',"%{$search}%")
                    ->orWhere('m.member_number', 'LIKE',"%{$search}%")
                    ->where('m.doj','>=',$from_date)
                    ->where('m.doj','<=',$to_date);
                    if($status_id!=''){
                        $membersqry = $membersqry->where('m.status_id','=',$status_id);
                    }
                    $members = $membersqry->where('m.status','=','1')
                    ->orderBy($order,$dir)
                    ->get()->toArray();
        }else{
            $membersqry    =  DB::table('membership as m')->select('m.id','m.name','m.member_number','m.status_id','m.doj','m.branch_id')
                        ->orWhere('m.name', 'LIKE',"%{$search}%")
                        ->orWhere('m.member_number', 'LIKE',"%{$search}%")
                        ->where('m.doj','>=',$from_date)
                        ->where('m.doj','<=',$to_date);
                        if($status_id!=''){
                            $membersqry = $membersqry->where('m.status_id','=',$status_id);
                        }
                        $members = $membersqry->where('m.status','=','1')
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get()->toArray();
        }
        $totalFilteredqry = DB::table('membership as m')
                    ->where('m.doj','>=',$from_date)
                    ->where('m.doj','<=',$to_date);
                    if($status_id!=''){
                        $totalFilteredqry = $totalFilteredqry->where('m.status_id','=',$status_id);
                    }
                    $totalFiltered = $totalFilteredqry->where('id','LIKE',"%{$search}%")
                    ->orWhere('name', 'LIKE',"%{$search}%")
                    
                    ->where('status','=','1')
                    ->count();
        }
        
        $table ="membership";

        $data = array();
        if(!empty($members))
        {
            foreach ($members as $member)
            {
                $statusdate = date('Y-m-01',strtotime($member->doj));

                $branch_data = CommonHelper::getBranchCompany($member->branch_id);
                
                
                $nestedData['id'] = $member->id;
                $nestedData['name'] = $member->name;
                $nestedData['member_number'] = $member->member_number;
                $nestedData['company'] = $branch_data->company_name;
                $nestedData['branch'] = $branch_data->branch_name;
                $enc_id = Crypt::encrypt($member->id);
                $nestedData['doj'] = date('d/M/Y',strtotime($member->doj));
                $nestedData['status'] = CommonHelper::get_member_status_name($member->status_id);
                //$editurl =  route('edit.irc', [app()->getLocale(),$enc_id]) ;
                //$editurl = URL::to('/')."/en/sub-company-members/".$company_enc_id;
                $edit = route('member.viewlevy', [app()->getLocale(),$enc_id]);
                $view = route('member.history', [app()->getLocale(),$enc_id]);
                $newhistory = route('monthend.addhistory', [app()->getLocale(),$enc_id]);
                
                $actions ="<a class='waves-effect waves-light btn btn-sm' href='$edit'>Update Levy</a>";

                //$actions .="<a style='margin-left: 10px;' title='History'  class='waves-effect waves-light green btn btn-sm' href='$newhistory'>Add New</a>";
                
                $nestedData['options'] = $actions;
                // if($irc->status_id!=4){
                //     $nestedData['options'] = "";
                //  //$nestedData['options'] = "<a style='float: left;' class='btn btn-sm waves-effect waves-light cyan modal-trigger' href='".$editurl."'><i class='material-icons'>edit</i></a>";
                // }else{
                //  $nestedData['options'] = "";
                // }
                $data[] = $nestedData;
                
                
            }
        }
       // dd($totalFiltered);
       
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );

        echo json_encode($json_data); 
    }

    public function ListBeforeDojMonthend(Request $request){
        $data['from_date'] = date('2019-01-01');
        $data['to_date'] = date('Y-m-d');
        $data['due_month'] = 0;
        $data['company_id'] = '';
        $data['branch_id'] = '';
        $data['company_view'] = DB::table('company')->where('status','=','1')->get();
        
        $data['members_list'] = DB::table('membership as m')->select('id','name','doj','status_id','branch_id','member_number')->where('m.doj','>=','2019-01-01')->orderBY('m.doj','asc')->get();
        return view('subscription.doj_before_list')->with('data',$data);  
    }

    public function ListBeforeDojMonthendFilter($lang,Request $request)
    {
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');

        $company_id = $request->input('company_id');
        $branch_id = $request->input('branch_id');
       // date('Y-m-d',strtotime($to_date));
        $data['from_date'] = date('Y-m-d',strtotime($from_date));
        $data['to_date'] = date('Y-m-d',strtotime($to_date));
        //$data['due_month'] = $due_month;
        $data['company_id'] = $company_id;
        $data['branch_id'] = $branch_id;
        $data['company_view'] = DB::table('company')->where('status','=','1')->get();

        if($branch_id!=''){
            $member_qry = DB::table('membership as m')->select('m.id','m.name','m.member_number','m.doj','m.status_id','m.branch_id');
            $member_qry = $member_qry->where('m.branch_id','=',$branch_id);
        }else{
            if($company_id!=""){
                $member_qry = DB::table('membership as m')->select('m.id','m.name','m.member_number','m.doj','m.status_id','m.branch_id')
            ->leftjoin('company_branch as cb','cb.id','=','m.branch_id');
            $member_qry = $member_qry->where('cb.company_id','=',$company_id);
            }else{
                $member_qry = DB::table('membership as m')->select('m.id','m.name','m.member_number','m.doj','m.status_id','m.branch_id')
                ->leftjoin('company_branch as cb','cb.id','=','m.branch_id');
            }
            
            
        }

        $data['members_list'] = $member_qry->where('m.doj','>=',$data['from_date'])->where('m.doj','<=',$data['to_date'])->orderBY('m.doj','asc')->get();
        return view('subscription.doj_before_list')->with('data',$data);  
     
    }

    public function ViewBeforeDojHistory($lang,$id){
        $memberid = Crypt::decrypt($id);
    
        $data['member_details'] = DB::table('membership as m')->select('m.id as memberid','m.doj as doj','m.name as membername','m.id as MemberCode','m.new_ic as new_ic','m.old_ic as old_ic','d.designation_name as membertype','p.person_title as persontitle','cb.branch_name','c.company_name','m.doj','s.status_name','m.member_number','s.font_color')
                                            ->leftjoin('designation as d','d.id','=','m.designation_id')
                                            ->leftjoin('persontitle as p','p.id','=','m.member_title_id')
                                            ->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
                                            ->leftjoin('company as c','c.id','=','cb.company_id')
                                            ->leftjoin('race as r','r.id','=','m.race_id')
                                            ->leftjoin('status as s','s.id','=','m.status_id')
                                            //->leftjoin('mon_sub_company as sc','sm.MonthlySubscriptionCompanyId','=','sc.id')
                                            //->leftjoin('mon_sub as s','sc.MonthlySubscriptionId','=','s.id') 
                                            ->where('m.id','=',$memberid)
                                            ->first();
        $doj = date('Y-m-01',strtotime($data['member_details']->doj));
        $data['member_history'] = DB::table('membermonthendstatus as ms')->select('ms.id as id','ms.id as memberid','ms.StatusMonth',
                                         'ms.TOTALSUBCRP_AMOUNT as SUBSCRIPTION_AMOUNT','ms.TOTALBF_AMOUNT as BF_AMOUNT','ms.TOTALINSURANCE_AMOUNT as INSURANCE_AMOUNT','ms.TOTAL_MONTHS','ms.LASTPAYMENTDATE','ms.TOTALMONTHSPAID',DB::raw('IFNULL(ms.TOTALMONTHSDUE,0) as TOTALMONTHSDUE'),'ms.ACCSUBSCRIPTION','ms.ACCBF','ms.ACCINSURANCE','ms.arrear_status','ms.SUBSCRIPTIONDUE','ms.ENTRYMODE','ms.advance_amt','ms.advance_balamt','ms.advance_totalmonths','s.font_color') 
                ->leftjoin('status as s','s.id','=','ms.STATUS_CODE')
                ->where('ms.MEMBER_CODE', '=' ,$memberid)->where('ms.StatusMonth','<=',$doj)->OrderBy('ms.StatusMonth','asc')
                ->get();

        return view('subscription.doj_member_history')->with('data',$data);

    }

    public function DeleteBeforeDOJ($lang,Request $request){
        $memberid = $request->input('memberid');
        $memberdata = DB::table('membership as m')->select('branch_id','designation_id','status_id','doj')->where('m.id','=',$memberid)->first();
        $doj = $memberdata->doj;
        $dojmonth = date('Y-m-01',strtotime($doj));
        $delrow = DB::table('membermonthendstatus')->select('id') 
               ->where('MEMBER_CODE', '=' ,$memberid)->where('StatusMonth','<',$dojmonth)
               ->delete();

        $firstrow = DB::table('membermonthendstatus as ms')->select('ms.id as id','ms.id as memberid','ms.StatusMonth',
                                         'ms.TOTALSUBCRP_AMOUNT as SUBSCRIPTION_AMOUNT','ms.TOTALBF_AMOUNT as BF_AMOUNT','ms.TOTALINSURANCE_AMOUNT as INSURANCE_AMOUNT','ms.TOTAL_MONTHS','ms.LASTPAYMENTDATE','ms.TOTALMONTHSPAID',DB::raw('IFNULL(ms.TOTALMONTHSDUE,0) as TOTALMONTHSDUE'),'ms.ACCSUBSCRIPTION','ms.ACCBF','ms.ACCINSURANCE','ms.arrear_status','ms.SUBSCRIPTIONDUE','ms.ENTRYMODE','ms.advance_amt','ms.advance_balamt','ms.advance_totalmonths') 
                ->where('ms.MEMBER_CODE', '=' ,$memberid)->where('ms.StatusMonth','=',$dojmonth)
                ->first();
        if($firstrow==null){
            $branchdata = DB::table("company_branch")->where('id','=',$memberdata->branch_id)->first();
            $monthend_data = [
                                'StatusMonth' => $dojmonth, 
                                'MEMBER_CODE' => $memberid,
                                'SUBSCRIPTION_AMOUNT' => 0,
                                'BF_AMOUNT' => 0,
                                'LASTPAYMENTDATE' => '0000-00-00',
                                'TOTALSUBCRP_AMOUNT' => 0,
                                'TOTALBF_AMOUNT' => 0,
                                'TOTAL_MONTHS' => 0,
                                'TOTALMONTHSDUE' => 1,
                                'BANK_CODE' => $branchdata->company_id,
                                'NUBE_BRANCH_CODE' => $branchdata->union_branch_id,
                                'BRANCH_CODE' => $memberdata->branch_id,
                                'MEMBERTYPE_CODE' => $memberdata->designation_id,
                                'ENTRYMODE' => 'S',
                                'STATUS_CODE' => 1,
                                'RESIGNED' => 0,
                                'ENTRY_DATE' => date('Y-m-d'),
                                'ENTRY_TIME' => date('h:i:s'),
                                'STRUCKOFF' => 0,
                                'INSURANCE_AMOUNT' => 0,
                                'TOTALINSURANCE_AMOUNT' => 0,
                                'ACCSUBSCRIPTION' =>  0,
                                'ACCBF' => 0,
                                'ACCINSURANCE' =>  0,
                                'TOTALMONTHSPAID' =>  0,
                                'TOTALMONTHSCONTRIBUTION' =>  0,
                            ];
            DB::table($this->membermonthendstatus_table)->insert($monthend_data);

            // $firstrow = DB::table('membermonthendstatus as ms')->select('ms.id as id','ms.id as memberid','ms.StatusMonth',
            //                              'ms.TOTALSUBCRP_AMOUNT as SUBSCRIPTION_AMOUNT','ms.TOTALBF_AMOUNT as BF_AMOUNT','ms.TOTALINSURANCE_AMOUNT as INSURANCE_AMOUNT','ms.TOTAL_MONTHS','ms.LASTPAYMENTDATE','ms.TOTALMONTHSPAID',DB::raw('IFNULL(ms.TOTALMONTHSDUE,0) as TOTALMONTHSDUE'),'ms.ACCSUBSCRIPTION','ms.ACCBF','ms.ACCINSURANCE','ms.arrear_status','ms.SUBSCRIPTIONDUE','ms.ENTRYMODE','ms.advance_amt','ms.advance_balamt','ms.advance_totalmonths') 
            //     ->where('ms.MEMBER_CODE', '=' ,$memberid)->where('ms.StatusMonth','=',$dojmonth)
            //     ->first();
        }else{
           $monthend_datas = [
                                'LASTPAYMENTDATE' => $firstrow->TOTAL_MONTHS>0 ? $dojmonth : '0000-00-00',
                                'STATUS_CODE' => 1,
                                'ENTRY_DATE' => date('Y-m-d'),
                                'ENTRY_TIME' => date('h:i:s'),
                                'TOTALMONTHSDUE' => $firstrow->TOTAL_MONTHS==0 ? 1 : 0 ,
                                'TOTALMONTHSPAID' => $firstrow->TOTAL_MONTHS>0 ? 1 : 0,
                                'SUBSCRIPTIONDUE' => $firstrow->TOTAL_MONTHS==0 ? $firstrow->SUBSCRIPTION_AMOUNT : 0,
                                'BFDUE' => $firstrow->TOTAL_MONTHS==0 ? $firstrow->BF_AMOUNT : 0,
                                'INSURANCEDUE' => $firstrow->TOTAL_MONTHS==0 ? $firstrow->INSURANCE_AMOUNT : 0,
                                'ACCSUBSCRIPTION' => $firstrow->TOTAL_MONTHS>0 ? $firstrow->SUBSCRIPTION_AMOUNT : 0,
                                'ACCBF' => $firstrow->TOTAL_MONTHS>0 ? $firstrow->BF_AMOUNT : 0,
                                'ACCINSURANCE' => $firstrow->TOTAL_MONTHS>0 ? $firstrow->INSURANCE_AMOUNT : 0,
                                'TOTALMONTHSCONTRIBUTION' => $firstrow->TOTAL_MONTHS>0 ? 1 : 0,
                            ];

            $m_upstatus = DB::table('membermonthendstatus')->where('MEMBER_CODE', '=', $memberid)->where('StatusMonth','=',$dojmonth)->update($monthend_datas);
        }

        $last_mont_record = DB::table($this->membermonthendstatus_table." as ms")
            ->select('ms.StatusMonth','ms.LASTPAYMENTDATE','ms.ACCBF','ms.ACCSUBSCRIPTION','ms.SUBSCRIPTION_AMOUNT','ms.BF_AMOUNT','ms.TOTALMONTHSPAID','ms.ACCINSURANCE','ms.TOTALMONTHSDUE','ms.SUBSCRIPTIONDUE','ms.TOTALMONTHSCONTRIBUTION','ms.INSURANCEDUE','ms.BFDUE','ms.INSURANCE_AMOUNT','ms.TOTAL_MONTHS','ms.advance_amt','ms.advance_totalmonths','ms.advance_balamt','ms.ENTRYMODE')
            ->where('StatusMonth', '=', $dojmonth)->where('MEMBER_CODE', '=', $memberid)
            ->orderBY('StatusMonth','desc')
            ->limit(1)
            ->first();

        $below_mont_records = DB::table($this->membermonthendstatus_table." as ms")
            ->select('ms.StatusMonth','ms.Id','ms.SUBSCRIPTION_AMOUNT','ms.BF_AMOUNT','ms.INSURANCE_AMOUNT','ms.TOTAL_MONTHS','ms.advance_amt','ms.advance_totalmonths','ms.advance_balamt','ms.ENTRYMODE')
            ->where('StatusMonth', '>', $dojmonth)->where('MEMBER_CODE', '=', $memberid)
            ->orderBY('StatusMonth','asc')
            ->OrderBy('ms.arrear_status','asc')
            ->get();

        $last_ACCINSURANCE = !empty($last_mont_record) ? $last_mont_record->ACCINSURANCE : 0;
        $last_ACCSUBSCRIPTION = !empty($last_mont_record) ? $last_mont_record->ACCSUBSCRIPTION : 0;
        $last_ACCBF = !empty($last_mont_record) ? $last_mont_record->ACCBF : 0;
        $last_SUBSCRIPTIONDUE = !empty($last_mont_record) ? $last_mont_record->SUBSCRIPTIONDUE : 0;
        $last_BFDUE = !empty($last_mont_record) ? $last_mont_record->BFDUE : 0;
        $last_INSURANCEDUE = !empty($last_mont_record) ? $last_mont_record->INSURANCEDUE : 0;
        $last_TOTALMONTHSDUE = !empty($last_mont_record) ? $last_mont_record->TOTALMONTHSDUE : 0;
        $last_TOTALMONTHSPAID = !empty($last_mont_record) ? $last_mont_record->TOTALMONTHSPAID : 0;
        $last_TOTALMONTHSCONTRIBUTION = !empty($last_mont_record) ? $last_mont_record->TOTALMONTHSCONTRIBUTION : 0;
        $last_advance_amt = !empty($last_mont_record) ? $last_mont_record->advance_amt : 0;
        $last_advance_totalmonths = !empty($last_mont_record) ? $last_mont_record->advance_totalmonths : 0;
        $last_advance_balamt = !empty($last_mont_record) ? $last_mont_record->advance_balamt : 0;
        
        foreach($below_mont_records as $monthend){
            $m_subs_amt = $monthend->SUBSCRIPTION_AMOUNT;
            $m_bf_amt = $monthend->BF_AMOUNT;
            $m_ins_amt = $monthend->INSURANCE_AMOUNT;
            $m_total_months = $monthend->TOTAL_MONTHS;

            if($m_total_months==1){
                $new_ACCINSURANCE = $last_ACCINSURANCE+$m_ins_amt;
                $new_ACCSUBSCRIPTION = $last_ACCSUBSCRIPTION+$m_subs_amt;
                $new_ACCBF = $last_ACCBF+$m_bf_amt;
                $new_SUBSCRIPTIONDUE = $last_SUBSCRIPTIONDUE;
                $new_BFDUE = $last_BFDUE;
                $new_INSURANCEDUE = $last_INSURANCEDUE;
                $new_TOTALMONTHSDUE = $last_TOTALMONTHSDUE;
                $new_TOTALMONTHSPAID = $last_TOTALMONTHSPAID+1;
                $new_TOTALMONTHSCONTRIBUTION = $last_TOTALMONTHSCONTRIBUTION+1;
            }else{
                $new_ACCINSURANCE = $last_ACCINSURANCE;
                $new_ACCSUBSCRIPTION = $last_ACCSUBSCRIPTION;
                $new_ACCBF = $last_ACCBF;
                $new_SUBSCRIPTIONDUE = $last_SUBSCRIPTIONDUE+$m_subs_amt;
                $new_BFDUE = $last_BFDUE+$m_bf_amt;
                $new_INSURANCEDUE = $last_INSURANCEDUE+$m_ins_amt;
                $new_TOTALMONTHSDUE = $last_TOTALMONTHSDUE+1;
                $new_TOTALMONTHSPAID = $last_TOTALMONTHSPAID;
                $new_TOTALMONTHSCONTRIBUTION = $last_TOTALMONTHSCONTRIBUTION;
            }
            
            $monthend_datas = [
                        'TOTALMONTHSDUE' => $new_TOTALMONTHSDUE,
                        'TOTALMONTHSPAID' => $new_TOTALMONTHSPAID,
                        'SUBSCRIPTIONDUE' => $new_SUBSCRIPTIONDUE,
                        'BFDUE' => $new_BFDUE,
                        'INSURANCEDUE' => $new_INSURANCEDUE,
                        'ACCSUBSCRIPTION' => $new_ACCSUBSCRIPTION,
                        'ACCBF' => $new_ACCBF,
                        'ACCINSURANCE' => $new_ACCINSURANCE,
                        'TOTALMONTHSCONTRIBUTION' => $new_TOTALMONTHSCONTRIBUTION,
                        //'advance_amt' => $new_advance_amt,
                        //'advance_totalmonths' => $new_advance_totalmonths,
                        //'advance_balamt' => $new_advance_balamt,
                    ];
            $m_upstatus = DB::table('membermonthendstatus')->where('Id', '=', $monthend->Id)->update($monthend_datas);

            $last_ACCINSURANCE = $new_ACCINSURANCE;
            $last_ACCSUBSCRIPTION = $new_ACCSUBSCRIPTION;
            $last_ACCBF = $new_ACCBF;
            $last_SUBSCRIPTIONDUE = $new_SUBSCRIPTIONDUE;
            $last_BFDUE = $new_BFDUE;
            $last_INSURANCEDUE = $new_INSURANCEDUE;
            $last_TOTALMONTHSDUE = $new_TOTALMONTHSDUE;
            $last_TOTALMONTHSPAID = $new_TOTALMONTHSPAID;
            $last_TOTALMONTHSCONTRIBUTION = $new_TOTALMONTHSCONTRIBUTION;
           
        }

        $payment_data = [
                'totpaid_months' => $last_TOTALMONTHSPAID,
                'totcontribution_months' => $last_TOTALMONTHSCONTRIBUTION,
                'totdue_months' => $last_TOTALMONTHSDUE,
                'accbf_amount' => $last_ACCBF,
                'accsub_amount' => $last_ACCSUBSCRIPTION,
                'accins_amount' => $last_ACCINSURANCE,
                'duebf_amount' =>  $last_BFDUE,
                'dueins_amount' => $last_INSURANCEDUE,
                'duesub_amount' =>  $last_SUBSCRIPTIONDUE,
                'updated_by' => Auth::user()->id,
            ];
            DB::table('member_payments')->where('member_id', $memberid)->update($payment_data);
        if($last_TOTALMONTHSDUE<=3){
            $m_status = 1;
            DB::table('membership')->where('id', $memberid)->update(['status_id' => $m_status]);
        }else if($last_TOTALMONTHSDUE<=13){
            $m_status = 2;
            DB::table('membership')->where('id', $memberid)->update(['status_id' => $m_status]);
        }

        return redirect($lang.'/before-dojlist')->with('message','History deleted successfully');  
        

    }

    public function ListNegativeDue(Request $request){
      
        $data['due_month'] = 0;
        $data['status'] = DB::table('status')->where('status','=',1)->get();
        //dd($data['status'][3]);
        
        $data['members_list'] = DB::table('membermonthendstatus as ms')->select('StatusMonth','MEMBER_CODE','SUBSCRIPTION_AMOUNT','BF_AMOUNT','INSURANCE_AMOUNT','TOTAL_MONTHS','TOTALMONTHSDUE','TOTALMONTHSPAID')->where('ms.StatusMonth','=','2019-12-01')->where('ms.TOTALMONTHSDUE','<','0')->orderBY('ms.arrear_status','desc')->get();

        return view('subscription.negative_history_list')->with('data',$data);  
    }


}
