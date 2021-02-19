<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Helpers\CommonHelper;

use App\Model\Membership;
use DB;
use View;
use Mail;
use URL;
use Response;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToArray;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Log;
use Auth;
use PDF;
use Artisan;
use Facades\App\Repository\CacheMembers;
use DateTime;
use App\Imports\SubsheetImport;
use App\Model\Status;


class EcoParkController extends Controller
{
	public function __construct() {
		$this->middleware('auth');
        ini_set('memory_limit', -1);
		ini_set('max_execution_time', 0);
        //ini_set('post_max_size', 126);
	}
	
    public function FileUpload() {
        $get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;
        $user_id = Auth::user()->id;
      
        $data['date'] = date('Y-m-01');

        //isset($data['member_stat']) ? $data['member_stat'] : "";       
        return view('eco_park.file_upload')->with('data', $data);
    }   
	
	public function EcoParkUpdate($lang, Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', '8000');
        ini_set('post_max_size', '64MB');
         $rules = array(
                    'file' => 'required|mimes:xls,xlsx',
                );
 
        $validator = Validator::make(Input::all(), $rules);
        if($validator->fails())
        {
           // dd($validator);
             //return 1;
            //return 1;
            return back()->withErrors($validator);
        }
        else
        {
             
        
            if(Input::hasFile('file')){

                $data['entry_date'] = $request->entry_date;
                $entry_date = $request->entry_date;
                $batch_type = $request->input('batch_type');

                $datearr = explode("/",$entry_date);  
                $monthname = $datearr[0];
                $year = $datearr[1];
                $full_date = date('Ymdhis',strtotime('01-'.$monthname.'-'.$year));

                $form_date = date('Y-m-d',strtotime('01-'.$monthname.'-'.$year));
                $form_datefull = date('Y-m-d',strtotime('01-'.$monthname.'-'.$year)).' '.date('h:i:s');
                $others = '';
                

                $file_name = 'ecopark_'.$full_date;
            
                $file = $request->file('file')->storeAs('ecopark', $file_name.'.xlsx'  ,'local');

                 $subsdata = (new SubsheetImport)->toArray('storage/app/ecopark/'.$file_name.'.xlsx');
                 $firstrow = $subsdata[0][2];
                 //dd($subsdata[0]);
                 //dd($firstrow);
               // return 2;
                if($firstrow[0]!='No.' || $firstrow[1]!='Privilege Card NO'){
                   // return 2;
                    return  redirect('en/ecopark/fileupload')->with('error', 'Wrong excel sheet');
                }

                $subscription_qry = DB::table('eco_park_type')->where('Date','=',$form_date)->where('type','=',$batch_type);
                $subscription_count = $subscription_qry->count();
                if($subscription_count>0){
                    $ecopark_month = $subscription_qry->get();
                    $date_auto_id = $ecopark_month[0]->id;
                }else{
                    $ecopark_month = [];
                    $ecopark_month['Date'] = $form_date;
                    $ecopark_month['type'] = $batch_type;
                    $ecopark_month['created_by'] = Auth::user()->id;
                    $ecopark_month['created_on'] = date('Y-m-d');
                    
                    $date_auto_id = DB::table('eco_park_type')->insertGetId($ecopark_month);
                }

                $firstsheet = $subsdata[0];
                $bulkedata = [];
                for ($i=3; $i < count($firstsheet); $i++) { 
                    if($firstsheet[$i][1]!=''){

                        // if($paiddate!=''){
                        //   $UNIX_DATE = ($paiddate - 25569) * 86400;
                        //   $paydate = gmdate("Y-m-d", $UNIX_DATE);
                        // }

                        $insertdata = [];
                        $insertdata['eco_park_type_id'] = $date_auto_id;
                        $insertdata['privilege_card_no'] = $firstsheet[$i][1];
                        $insertdata['reissue_pv_card_no'] = $firstsheet[$i][2];
                        $insertdata['full_name'] = $firstsheet[$i][3];
                        $insertdata['nric_old'] = $firstsheet[$i][4];
                        $insertdata['nric_new'] = $firstsheet[$i][5];
                        $insertdata['bank'] = $firstsheet[$i][6];
                        $insertdata['member_number'] = $firstsheet[$i][7];
                        $insertdata['telephone_no'] = $firstsheet[$i][8];
                        $insertdata['address'] = $firstsheet[$i][9];
                        $insertdata['updated_home_address'] = $firstsheet[$i][10];
                        $insertdata['original_fee'] = $firstsheet[$i][11];
                        $insertdata['payment_fee'] = $firstsheet[$i][12];
                        $insertdata['date_joined'] = $firstsheet[$i][13];
                        $insertdata['updated_bank_address'] = $firstsheet[$i][14];
                        $insertdata['remarks'] = $firstsheet[$i][15];
                        $insertdata['date_call'] = $firstsheet[$i][16];
                        $insertdata['date_updated_on'] = $firstsheet[$i][17];
                        $insertdata['card_dispatched'] = $firstsheet[$i][18];
                        $insertdata['card_issue_date'] = $firstsheet[$i][19];
                        $insertdata['card_status'] = $firstsheet[$i][20];
                        $insertdata['ack_of_card_received'] = $firstsheet[$i][21];
                        $insertdata['status'] = 0;
                        $insertdata['created_by'] = Auth::user()->id;
                        $insertdata['created_at'] = date('Y-m-d h:i:s');

                        $bulkedata[] = $insertdata;

                    }
                }
                $chunks = array_chunk($bulkedata, 2000);
                foreach ($chunks as $chunkdata) {
                   $savesal = DB::table('eco_park')->insert($chunkdata);
                }
                
                $enc_id = Crypt::encrypt($date_auto_id);
                //dd($bulkedata);
                return redirect($lang.'/latestecopark_process?auto_id='.$enc_id)->with('message','Eco park file uploaded successfully');

            }else{
                return redirect($lang.'/ecopark/fileupload')->with('message','please select file');
            }
            
        }
    }

    public function LatestEcoParkProcess(Request $request,$lang){
        $get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;
        $user_id = Auth::user()->id;
        $data = [];

        $nrd = DB::delete('DELETE c1 FROM eco_park c1 INNER JOIN eco_park c2 WHERE c1.id > c2.id AND c1.member_number = c2.member_number AND c1.privilege_card_no = c2.privilege_card_no AND c1.full_name = c2.full_name;');

        //$nrd = DB::delete('DELETE c1 FROM eco_park c1 INNER JOIN eco_park c2 WHERE c1.id > c2.id AND c1.privilege_card_no = c2.privilege_card_no AND c1.full_name = c2.full_name;');

        $encauto_id = $request->input('auto_id');

        $auto_id = Crypt::decrypt($encauto_id);
        //dd($auto_id);
       
        //$updatedate = date('Y-m-01',$date);
        $updatedate = DB::table('eco_park_type')
                            //->leftjoin('eco_park_type as t','ep.eco_park_type_id','=','t.id')
                            ->where('id','=',$auto_id)->pluck('Date')->first();
        $parktype = DB::table('eco_park_type')
                            //->leftjoin('eco_park_type as t','ep.eco_park_type_id','=','t.id')
                            ->where('id','=',$auto_id)->pluck('type')->first();

        $memberrowcount = DB::table('eco_park as ep')
                            ->leftjoin('eco_park_type as t','ep.eco_park_type_id','=','t.id')
                            ->where('t.Date','=',$updatedate)->where('ep.status','=',0)->count();
        if($parktype==1){
            $parktypename = 'Batch 1 Member';
        }else if($parktype==2){
            $parktypename = 'Batch 1 Non Member';
        }else if($parktype==3){
            $parktypename = 'Batch 2 Member';
        }else if($parktype==3){
            $parktypename = 'Batch 2 Non Member';
        }else{
            $parktypename = 'Others';
        }

        $data['row_count'] = $memberrowcount;
        $data['month_year'] = $updatedate;
        $data['parktype'] = $parktypename;
        $data['park_auto_id'] = $auto_id;
       // dd($memberrowcount);

        if($data['row_count']==0){
            return redirect($lang.'/ecopark/fileupload')->with('message','Finished scanning');
        }

        return view('eco_park.scan-ecopark')->with('data',$data);
    }

     public function scanEcoPark(Request $request,$lang){
        $get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;
        $user_id = Auth::user()->id;

        $limit = $request->input('limit');
        $park_auto_id = $request->input('park_auto_id');

        $encauto_id = Crypt::encrypt($park_auto_id);

        $updatedate = DB::table('eco_park_type')
                            //->leftjoin('eco_park_type as t','ep.eco_park_type_id','=','t.id')
                            ->where('id','=',$park_auto_id)->pluck('Date')->first();

        $ecopark_data = DB::table('eco_park as ep')->select('ep.*')
                            ->leftjoin('eco_park_type as t','ep.eco_park_type_id','=','t.id')
                            ->where('t.Date','=',$updatedate)->where('ep.status','=',0)->limit($limit)->get();
        //dd($ecopark_data);
        foreach($ecopark_data as $ecopark){
            $ecoparkid = $ecopark->id;
            $eco_park_type_id = $ecopark->eco_park_type_id;
            $name = $ecopark->full_name;
            $member_number = $ecopark->member_number;
            $nric_new = $ecopark->nric_new;
            $nric_old = $ecopark->nric_old;
            $original_fee = $ecopark->original_fee;
            $payment_fee = $ecopark->payment_fee;
            $status = $ecopark->status;

            $ecopark_number_qry =  DB::table('membership as m')->where(DB::raw("member_number"), '=',$member_number)->whereNotNull('member_number')->where(DB::raw("member_number"), '!=',0)->OrderBy('m.doj','desc')->limit(1)->select('status_id','id','branch_id','name','designation_id')->get();

            $memberdata = [];
            $memberverified = 0;
            if(count($ecopark_number_qry) > 0){
                $memberdata = $ecopark_number_qry;
                $memberverified = 1;
            }else{
                $ecopark_new_qry =  DB::table('membership as m')->where(DB::raw("TRIM(LEADING '0' FROM m.new_ic)"), '=',ltrim($nric_new, '0'))->whereNotNull('new_ic')->where(DB::raw("new_ic"), '!=',0)->OrderBy('m.doj','desc')->limit(1)->select('status_id','id','branch_id','name','designation_id')->get();
                if(count($ecopark_new_qry) > 0){
                     $memberdata = $ecopark_new_qry;
                     $memberverified = 2;
                }else{
                    $subscription_old_qry =  DB::table('membership as m')->where(DB::raw("TRIM(LEADING '0' FROM m.old_ic)"), '=',ltrim($nric_old, '0'))->whereNotNull('old_ic')->where(DB::raw("old_ic"), '!=',0)->OrderBy('m.doj','desc')->limit(1)->select('status_id','id','branch_id','name','designation_id')->get();
                    if(count($subscription_old_qry) > 0){
                        $memberdata = $subscription_old_qry;
                        $memberverified = 3;
                    }else{
                        $memberdata = [];
                        $memberverified = 0;
                    }
                }
            }

            if($memberverified>0){
                $member_code = $memberdata[0]->id;
                $company_branch_id = $memberdata[0]->branch_id;
                $status_id = $memberdata[0]->status_id;

                $bankid = DB::table('company_branch')
                            //->leftjoin('eco_park_type as t','ep.eco_park_type_id','=','t.id')
                            ->where('id','=',$company_branch_id)->pluck('company_id')->first();

                $updatedata = array('member_id' => $member_code ,'bank_id' => $bankid, 'status' => 1, 'status_id' => $status_id);

                $ecoaffects = DB::table('eco_park')
                ->where('id', $ecoparkid)
                ->update($updatedata); 

                if(!$ecoaffects){
                    $return_data = ['status' => 0 ,'message' => 'Invalid data'];
                }
            }else{
                $updatedata = array('status' => 1);

                $ecoaffects = DB::table('eco_park')
                ->where('id', $ecoparkid)
                ->update($updatedata); 

            }
           

        }
        $return_data = ['status' => 1 ,'message' => 'Eco Park updated successfully, Redirecting to upload page...',
                    'redirect_url' =>  URL::to('en/ecopark/summary?date='.strtotime($updatedate))];
        echo json_encode($return_data);
    }

    public function EcoParkList(Request $request,$lang){
        $data['parkdates'] = DB::table('eco_park_type')
                            ->groupBy('Date')->pluck('Date');
        return view('eco_park.date-list')->with('data',$data);
        //dd($parkdates);
    }

     public function EcoParkSummary(Request $request,$lang){
        
        $entry_date = $request->input('entry_date');
        if($entry_date!=''){
            $datearr = explode("/",$entry_date);  
            $monthname = $datearr[0];
            $year = $datearr[1];
            $parkdate = date('Y-m-01',strtotime('01-'.$monthname.'-'.$year));
        }else{
            $parkdateenc = $request->input('date');
            $parkdate = date('Y-m-01',$parkdateenc);
        }

        $parkdate = '2021-01-01';
        
        $data['parkdate'] = $parkdate;

        $status_all = Status::where('status',1)->get();
        
        $data['member_status'] = $status_all;

        $members_qry = DB::select(DB::raw('SELECT COUNT(e.id) AS count,ifnull(sum(e.payment_fee),0) as amount FROM `eco_park` AS `e` LEFT JOIN `eco_park_type` AS `t` ON `t`.`id` = `e`.`eco_park_type_id` WHERE t.Date="'.$parkdate.'"'));
        $data['members_sum'] = $members_qry[0];

        return view('eco_park.summary')->with('data',$data);
        //dd($parkdates);
    }
     public function EcoParkmembers(Request $request,$lang){
        $parkdateenc = $request->input('date');
        $parkdate = date('Y-m-01',$parkdateenc);
        $data['parkdate'] = $parkdate;
        $data['parkmembers'] = [];

        $data['parkautoid'] = DB::table('eco_park_type')
                            ->where('Date','=',$parkdate)->pluck('id')->first();

        return view('eco_park.members')->with('data',$data);
       // dd($parkdate);
    }

    public function statusCountView($lang, Request $request){
        $get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;
        $user_id = Auth::user()->id;
        
        $member_status = $request->input('member_status');
        $batch_type = $request->input('batch_type');
        $member_type = $request->input('member_type');
        $payment_type = $request->input('payment_type');
        $card_status = $request->input('card_status');
        $date = $request->input('date');
      
       
        $defaultdate = date('Y-m-01',$date);
        $data['data_limit'] = 2000;
        $filter_date = date('Y-m-01',$date);
        
        $data['member_status'] = Status::where('status',1)->get();
      
        //dd($company_str_List); 
        $data['filter_date'] = strtotime(date('Y-m-01',strtotime($defaultdate)));
        if($member_status!=""){
            $cond ='';

            if($member_status!="all"){
                 $cond =' and e.status_id='.$member_status;
            }else{
                $cond =' and e.status_id>=1';
            }
           
            $members_data = DB::select(DB::raw('SELECT e.*,t.type,t.Date FROM `eco_park` AS `e` LEFT JOIN `eco_park_type` AS `t` ON `t`.`id` = `e`.`eco_park_type_id` WHERE `t`.`Date`="'.$defaultdate.'" '.$cond.' LIMIT '.$data['data_limit']));

            $data['member'] = $members_data;
            $data['status_type'] = 1;
            $data['status'] = $member_status;
            $data['batch_type'] = '';
            $data['member_type'] = '';
            $data['payment_type'] = '';
            $data['card_status'] = '';
            $data['title_name'] = $member_status!="all" ? CommonHelper::get_member_status_name($member_status).' Members' : 'All Members';
        }

        if($batch_type!="" && $member_type!=''){
            //$cond ='';

            $cond =' and t.type='.$batch_type;


            if($member_type==0){
                $cond .= " and (e.member_id='' OR e.member_id is null)";
            }else{
                $cond .= " and e.member_id is not null";
            }
           
            $members_data = DB::select(DB::raw('SELECT e.*,t.type,t.Date FROM `eco_park` AS `e` LEFT JOIN `eco_park_type` AS `t` ON `t`.`id` = `e`.`eco_park_type_id` WHERE `t`.`Date`="'.$defaultdate.'" '.$cond.' LIMIT '.$data['data_limit']));

            $data['member'] = $members_data;
            $data['status_type'] = 2;
            $data['status'] = '';
            $data['batch_type'] = $batch_type;
            $data['member_type'] = $member_type;
            $data['payment_type'] = '';
            $data['card_status'] = '';

            if($batch_type==1){
                $parktypename = 'Batch 1 Member';
            }else if($batch_type==2){
                $parktypename = 'Batch 1 Non Member';
            }else if($batch_type==3){
                $parktypename = 'Batch 2 Member';
            }else if($batch_type==3){
                $parktypename = 'Batch 2 Non Member';
            }else{
                $parktypename = 'Others';
            }

            if($member_type==1){
                $membertypename = 'Members';
            }else{
                $membertypename = 'Non Members';
            }

            $data['title_name'] = $parktypename.'('.$membertypename.')';
        }   

        if($payment_type!=''){
           $cond = '';
           $payment_typename = '';
           if($payment_type=='low'){
                $cond .= " and e.payment_fee<1550 and e.payment_fee!=0";
                $payment_typename = 'Low Payment';
           }else if($payment_type=='zero'){
                $cond .= " and e.payment_fee=0";
                $payment_typename = 'Zero Payment';
           }else{
                $payment_typename = 'Full Payment';
           }

            if($member_type==0){
                $cond .= " and (e.member_id='' OR e.member_id is null)";
            }else{
                $cond .= " and e.member_id is not null";
            }

            $members_data = DB::select(DB::raw('SELECT e.*,t.type,t.Date FROM `eco_park` AS `e` LEFT JOIN `eco_park_type` AS `t` ON `t`.`id` = `e`.`eco_park_type_id` WHERE `t`.`Date`="'.$defaultdate.'" '.$cond.' LIMIT '.$data['data_limit']));

            $data['member'] = $members_data;
            $data['status_type'] = 3;
            $data['status'] = '';
            $data['batch_type'] = '';
            $data['member_type'] = $member_type;
            $data['payment_type'] = $payment_type;
            $data['card_status'] = '';

            if($member_type==1){
                $membertypename = 'Members';
            }else{
                $membertypename = 'Non Members';
            }

            $data['title_name'] = $payment_typename.'('.$membertypename.')';

        }       
        if($card_status==1){
            $cond = " and e.card_status='PC SEND OUT'";

            if($member_type==0){
                $cond .= " and (e.member_id='' OR e.member_id is null)";
            }else{
                $cond .= " and e.member_id is not null";
            }

            if($member_type==1){
                $membertypename = 'Paid Members have received card';
            }else{
                $membertypename = 'Paid Non Members have received card';
            }

            $members_data = DB::select(DB::raw('SELECT e.*,t.type,t.Date FROM `eco_park` AS `e` LEFT JOIN `eco_park_type` AS `t` ON `t`.`id` = `e`.`eco_park_type_id` WHERE `t`.`Date`="'.$defaultdate.'" '.$cond.' LIMIT '.$data['data_limit']));

            $data['member'] = $members_data;
            $data['status_type'] = 4;
            $data['status'] = '';
            $data['batch_type'] = '';
            $data['member_type'] = $member_type;
            $data['payment_type'] = '';
            $data['card_status'] = 1;

            $data['title_name'] = $membertypename;

        }  
        
        return view('eco_park.status_members')->with('data',$data);
    }

    public function ajax_ecoparkmember_list(Request $request){
        
        $status = $request->status;
        $month = $request->month;

        $get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;
        
        $sl=0;
        $columns[$sl++] = 'e.full_name';
        $columns[$sl++] = 'e.member_number';
        $columns[$sl++] = 'e.nric_new';
        $columns[$sl++] = 'e.payment_fee';
        if($status!='all'){
          $columns[$sl++] = 'e.status_id';
        }
        $columns[$sl++] = 'e.card_status';
        $columns[$sl++] = 'e.id';

        $commonqry = DB::table('eco_park as e')->select('e.id as eid','t.Date','t.type','e.member_id', 'e.full_name','e.privilege_card_no','e.nric_new','e.nric_old','e.member_number','e.bank','e.original_fee','s.status_name as status_name','e.status_id','s.font_color','e.payment_fee','e.date_joined','e.card_status')
       
        ->leftjoin('eco_park_type as t','e.eco_park_type_id','=','t.id')
        ->leftjoin('status as s','e.status_id','=','s.id')
        ->leftjoin('membership as m','m.id','=','e.member_id');
        //->leftjoin('company_branch as cb','cb.id','=','m.branch_id')

        //$commonqry->dump()->get();

        // $queries = DB::getQueryLog();
        // dd($queries);

        if($status!='all'){
            $commonqry = $commonqry->where('e.status_id','=',$status); 
        }
        $commonqry = $commonqry->where('t.Date','=',$month);
        
        //$commonqry->dump()->get();
        $totalData = $commonqry->count();
        
        $totalFiltered = $totalData; 
        
       $limit = $request->input('length');
       $start = $request->input('start');
          //var_dump($start);
          //exit;
        $order = $columns[$request->input('order.0.column')];
     
        $dir = $request->input('order.0.dir');
        if(empty($request->input('search.value')))
        {            
            $sub_mem = $commonqry;
            if( $limit != -1){
                $sub_mem = $sub_mem->offset($start)
                            ->limit($limit);
            }
            $sub_mem = $sub_mem->orderBy($order,$dir)
            ->get()->toArray();
        }
        else {
            $search = $request->input('search.value'); 
            
            $sub_mem = $commonqry->where(function($query) use ($search){
                            $query->orWhere('e.full_name', 'LIKE',"%{$search}%")
                            ->orWhere('e.member_number', 'LIKE',"%{$search}%")
                            ->orWhere('e.nric_new', 'LIKE',"%{$search}%")
                            ->orWhere('e.payment_fee', 'LIKE',"%{$search}%")
                            ->orWhere('s.status_name', 'LIKE',"%{$search}%");
                        });  
          
            if( $limit != -1){
               $sub_mem = $sub_mem->offset($start)
                        ->limit($limit);
            }
            $sub_mem = $sub_mem->orderBy($order,$dir)
                      ->get()->toArray();
            
            
            $totalFiltered =  $commonqry->where(function($query) use ($search){
                                     $query->orWhere('e.full_name', 'LIKE',"%{$search}%")
                                    ->orWhere('e.member_number', 'LIKE',"%{$search}%")
                                    ->orWhere('e.nric_new', 'LIKE',"%{$search}%")
                                    ->orWhere('e.payment_fee', 'LIKE',"%{$search}%")
                                    ->orWhere('e.card_status', 'LIKE',"%{$search}%")
                                    ->orWhere('s.status_name', 'LIKE',"%{$search}%");
                                })  
                               ->count();
        }
    //     var_dump($sub_mem);
    //    exit;
        $result = $sub_mem;

        $data = array();
        if(!empty($result))
        {
            foreach ($result as $resultdata)
            {
                $autoid = $resultdata->eid;
                // foreach($resultdata as $newkey => $newvalue){
                //     if($newkey=='id'){
                //         $autoid = $newvalue;
                //     }else{
                //         $nestedData[$newkey] = $newvalue;
                //     }
                // }
                $nestedData['name'] = $resultdata->full_name;
                $nestedData['member_number'] = $resultdata->member_number;
                $nestedData['nric_new'] = $resultdata->nric_new;
                $nestedData['payment_fee'] = $resultdata->payment_fee;
                $nestedData['card_status'] = $resultdata->card_status;
                $font_color = $resultdata->font_color;
                $nestedData['font_color'] = $font_color;

                if($status=='all'){
                    $nestedData['status_id'] = $resultdata->status_id;
                    $nestedData['status_name'] = $resultdata->status_name;
                    $nestedData['font_color'] = $font_color;
                }

                $memberid = $resultdata->member_id;
                $font_color = $resultdata->font_color;
                
                $enc_id = $memberid!='' ? Crypt::encrypt($memberid) : '';
               
                
                $actions ='';
                $baseurl = URL::to('/');
                
                $histry = $memberid!='' ? route('member.history', [app()->getLocale(),$enc_id]) : '#';
                $member_delete_link = $baseurl.'/'.app()->getLocale().'/subscription_delete?sub_id='.$autoid;
                
                $actions .="<a style='float: left; margin-left: 10px;cursor:pointer;' title='Edit Eco Park'  class='' ><i class='material-icons' style='color:#00bcd4'>edit</i></a>";
                //$actions .="<a style='float: left; margin-left: 10px;' onclick='return ConfirmDeletion()' title='Delete Subscription'  class='' href='$member_delete_link'><i class='material-icons' style='color:red'>delete</i></a>";
                
                if($memberid!=''){
                    
                    $actions .="<a style='float: left; margin-left: 10px;' title='History' target='_blank' class='' href='$histry'><i class='material-icons' style='color:#ff6f00;'>history</i></a>";
                    
                }  
                $nestedData['options'] = $actions;
                $data[] = $nestedData;

            }
        }
        
        //$data = $this->CommonAjaxReturn($sub_mem, 2, '',2); 
      
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );

        echo json_encode($json_data); 
    }

}
