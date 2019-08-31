<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use DB;
use Auth;

class ReportsController extends Controller
{
	protected $limit;
	public function __construct()
    {
        ini_set('memory_limit', '-1');
        $this->limit = 25;
		$this->membermonthendstatus_table = "membermonthendstatus1";		
    }
    public function newMemberReport()
    {
        $data['data_limit']=$this->limit;
        $data['company_view'] = DB::table('company')->where('status','=','1')->get();
        $entry_fee = DB::table('fee')->where('fee_shortcode','=','ENT')->pluck('fee_amount')->first();
        $ins_fee = DB::table('fee')->where('fee_shortcode','=','INS')->pluck('fee_amount')->first();
        $total_fee = $entry_fee+$ins_fee;
        
        $members = DB::table('company_branch as c')->select('c.id as cid','m.name','m.email','m.id as id','m.status_id as status_id','m.branch_id as branch_id', 'm.member_number','m.designation_id','d.id as designationid','d.designation_name','m.gender','com.company_name','m.doj','m.old_ic','m.new_ic','m.mobile','st.state_name','cit.id as cityid','cit.city_name','st.id as stateid','m.state_id','m.city_id','m.race_id','m.levy','m.levy_amount','m.tdf','m.tdf_amount','com.short_code as companycode','r.race_name','r.short_code as raceshortcode','s.font_color','c.branch_name as branch_name',DB::raw("{$entry_fee} as entryfee"),DB::raw("{$ins_fee} as insfee"),DB::raw("ifnull(round(((m.salary*1)/100)-{$total_fee}),0) as subs"))
                    ->join('membership as m','c.id','=','m.branch_id')
                    ->leftjoin('company as com','com.id','=','c.company_id')
                    ->leftjoin('status as s','s.id','=','m.status_id')
                    ->leftjoin('designation as d','m.designation_id','=','d.id')
                    ->leftjoin('state as st','st.id','=','m.state_id')
                    ->leftjoin('city as cit','cit.id','=','m.city_id')
                    ->leftjoin('race as r','r.id','=','m.race_id');
                    
                    $members = $members->where(DB::raw('month(m.`doj`)'),'=',date('m'));
                    $members = $members->where(DB::raw('year(m.`doj`)'),'=',date('Y'));
                    $members = $members->offset(0)
                    ->limit($data['data_limit'])
                    ->get();
        $data['member_view'] = $members;
        return view('reports.new_member')->with('data',$data);  
    }
	
	public function membersReport(Request $request, $lang, $status_id)
    {
        $data['data_limit']=$this->limit;
        $data['status_id']=$status_id;
        $data['company_view'] = DB::table('company')->where('status','=','1')->get();
       
        $members = DB::table('company_branch as c')->select('c.id as cid','m.name','m.email','m.id as id','m.status_id as status_id','m.branch_id as branch_id', 'm.member_number','m.designation_id','d.id as designationid','d.designation_name','m.gender','com.company_name','m.doj','m.old_ic','m.new_ic','m.mobile','st.state_name','cit.id as cityid','cit.city_name','st.id as stateid','m.state_id','m.city_id','m.race_id','m.levy','m.levy_amount','m.tdf','m.tdf_amount','com.short_code as companycode','r.race_name','r.short_code as raceshortcode','s.font_color','c.branch_name as branch_name')
                ->join('membership as m','c.id','=','m.branch_id')
                ->leftjoin('company as com','com.id','=','c.company_id')
                ->leftjoin('status as s','s.id','=','m.status_id')
                ->leftjoin('designation as d','m.designation_id','=','d.id')
                ->leftjoin('state as st','st.id','=','m.state_id')
                ->leftjoin('city as cit','cit.id','=','m.city_id')
                ->leftjoin('race as r','r.id','=','m.race_id');
                if($status_id!="" && $status_id!=0){
                    $members = $members->where('m.status_id','=',$status_id);
                }
                $members = $members->where(DB::raw('month(m.`doj`)'),'=',date('m'));
                $members = $members->where(DB::raw('year(m.`doj`)'),'=',date('Y'));
		        $members = $members->offset(0)
				->limit($data['data_limit'])
                ->get();
        $data['member_view'] = $members;
        return view('reports.members')->with('data',$data);  
    }
	public function membersReportMore(Request $request){
        $offset = $request->input('offset');
        $month_year = $request->input('month_year');
        $company_id = $request->input('company_id');
        $branch_id = $request->input('branch_id');
        $member_auto_id = $request->input('member_auto_id');
        $status_id = $request->input('status_id');
        $monthno = '';
        $yearno = '';
        if($month_year!=""){
          $fmmm_date = explode("/",$month_year);
          $monthno = date('m',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
          $yearno = date('Y',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
        }
          $members = DB::table('company_branch as c')->select('c.id as cid','m.name','m.email','m.id as id','m.status_id as status_id','m.branch_id as branch_id', 'm.member_number','m.designation_id','d.id as designationid','d.designation_name','m.gender','com.company_name','m.doj','m.old_ic','m.new_ic','m.mobile','st.state_name','cit.id as cityid','cit.city_name','st.id as stateid','m.state_id','m.city_id','m.race_id',DB::raw("ifnull(m.levy,'') as levy"),DB::raw("ifnull(m.levy_amount,'') as levy_amount"),'m.tdf','m.tdf_amount','com.short_code as companycode','r.race_name','r.short_code as raceshortcode','s.font_color','c.branch_name as branch_name')
              ->join('membership as m','c.id','=','m.branch_id')
              ->leftjoin('company as com','com.id','=','c.company_id')
              ->leftjoin('status as s','s.id','=','m.status_id')
              ->leftjoin('designation as d','m.designation_id','=','d.id')
              ->leftjoin('state as st','st.id','=','m.state_id')
              ->leftjoin('city as cit','cit.id','=','m.city_id')
              ->leftjoin('race as r','r.id','=','m.race_id');
              if($monthno!="" && $yearno!=""){
                  $members = $members->where(DB::raw('month(m.`doj`)'),'=',$monthno);
                  $members = $members->where(DB::raw('year(m.`doj`)'),'=',$yearno);
              }
              if($branch_id!=""){
                  $members = $members->where('m.branch_id','=',$branch_id);
              }else{
                  if($company_id!=""){
                      $members = $members->where('c.company_id','=',$company_id);
                  }
              }
              if($member_auto_id!=""){
                  $members = $members->where('m.id','=',$member_auto_id);
              }
              if($status_id!="" && $status_id!=0){
                  $members = $members->where('m.status_id','=',$status_id);
              }
              
          $members = $members->offset($offset)
              ->limit($this->limit)
              //->dump()
              ->get();
        echo json_encode($members);
		 
    }
    public function getAutomemberslist(Request $request){
        $searchkey = $request->input('serachkey');
        $company_id = $request->input('company_id');
        $branch_id = $request->input('branch_id');
        $search = $request->input('query');
        //DB::enableQueryLog();
        $member_query = DB::table('membership as m')->select(DB::raw('CONCAT(m.name, " - ", m.member_number) AS value'),'m.id as number','m.branch_id as branch_id','m.member_number as member_code')      
                            ->leftjoin('company_branch as c','c.id','=','m.branch_id');
        if($company_id!=""){
            $member_query = $member_query->where('c.company_id','=',$company_id);
        }
        if($branch_id!=""){
            $member_query = $member_query->where('m.branch_id','=',$branch_id);
        }
        $member_query = $member_query->where(function($query) use ($search){
                                $query->orWhere('m.id','LIKE',"%{$search}%")
                                    ->orWhere('m.member_number', 'LIKE',"%{$search}%")
                                    ->orWhere('m.name', 'LIKE',"%{$search}%");
                            })->limit(25)
                            ->get(); 
         $res['suggestions'] =  $member_query;      
        //$queries = DB::getQueryLog();
                            //  dd($queries);
         return response()->json($res);
    }

    public function membersNewReportMore(Request $request){
        $offset = $request->input('offset');
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $company_id = $request->input('company_id');
        $branch_id = $request->input('branch_id');
        $member_auto_id = $request->input('member_auto_id');
        $join_type = $request->input('join_type');
        $fromdate = CommonHelper::ConvertdatetoDBFormat($from_date);
        $todate = CommonHelper::ConvertdatetoDBFormat($to_date);
        $entry_fee = DB::table('fee')->where('fee_shortcode','=','ENT')->pluck('fee_amount')->first();
        $ins_fee = DB::table('fee')->where('fee_shortcode','=','INS')->pluck('fee_amount')->first();
        $total_fee = $entry_fee+$ins_fee;
          $members = DB::table('company_branch as c')->select('c.id as cid','m.name','m.email','m.id as id','m.status_id as status_id','m.branch_id as branch_id', 'm.member_number','m.designation_id','d.id as designationid','d.designation_name','m.gender','com.company_name','m.doj','m.old_ic','m.new_ic','m.mobile','st.state_name','cit.id as cityid','cit.city_name','st.id as stateid','m.state_id','m.city_id','m.race_id',DB::raw("ifnull(m.levy,'') as levy"),DB::raw("ifnull(m.levy_amount,'') as levy_amount"),'m.tdf','m.tdf_amount','com.short_code as companycode','r.race_name','r.short_code as raceshortcode','s.font_color','c.branch_name as branch_name',DB::raw("{$entry_fee} as entryfee"),DB::raw("{$ins_fee} as insfee"),DB::raw("ifnull(round(((m.salary*1)/100)-{$total_fee}),0) as subs"))
              ->join('membership as m','c.id','=','m.branch_id')
              ->leftjoin('company as com','com.id','=','c.company_id')
              ->leftjoin('status as s','s.id','=','m.status_id')
              ->leftjoin('designation as d','m.designation_id','=','d.id')
              ->leftjoin('state as st','st.id','=','m.state_id')
              ->leftjoin('city as cit','cit.id','=','m.city_id')
              ->leftjoin('race as r','r.id','=','m.race_id');
              if($fromdate!="" && $todate!=""){
                  $members = $members->where(DB::raw('date(m.`doj`)'),'>=',$fromdate);
                  $members = $members->where(DB::raw('date(m.`doj`)'),'<=',$todate);
              }
              if($branch_id!=""){
                  $members = $members->where('m.branch_id','=',$branch_id);
              }else{
                  if($company_id!=""){
                      $members = $members->where('c.company_id','=',$company_id);
                  }
              }
              if($join_type==2){
                $members = $members->where('m.old_member_number','!=',NULL);
              }
              if($join_type==1){
                $members = $members->where('m.old_member_number','=',NULL);
              }
              if($member_auto_id!=""){
                  $members = $members->where('m.id','=',$member_auto_id);
              }
              
          $members = $members->offset($offset)
              ->limit($this->limit)
              //->dump()
              ->get();
        echo json_encode($members);
        
    }
    public function resignMemberReport()
    {
        $data['data_limit']=$this->limit;
        $data['company_view'] = DB::table('company')->where('status','=','1')->get();
       
        
        $members = DB::table('resignation as rs')->select('c.id as cid','m.name','m.email','m.id as id','m.status_id as status_id','m.branch_id as branch_id', 'm.member_number','m.designation_id','d.id as designationid','d.designation_name','m.gender','com.company_name','m.doj','m.old_ic','m.new_ic','m.mobile','st.state_name','cit.id as cityid','cit.city_name','st.id as stateid','m.state_id','m.city_id','m.race_id','m.levy','m.levy_amount','m.tdf','m.tdf_amount','com.short_code as companycode','r.race_name','r.short_code as raceshortcode','s.font_color','c.branch_name as branch_name','rs.accbenefit as contribution',DB::raw("ifnull(rs.`accbf`+rs.insuranceamount,0) AS benifit"),DB::raw("ifnull(rs.`accbf`+rs.`insuranceamount`+rs.`accbenefit`,0) AS total"),'rs.resignation_date')
                    ->leftjoin('membership as m','m.id','=','rs.member_code')
                    ->leftjoin('company_branch as c','c.id','=','m.branch_id')
                    ->leftjoin('company as com','com.id','=','c.company_id')
                    ->leftjoin('status as s','s.id','=','m.status_id')
                    ->leftjoin('designation as d','m.designation_id','=','d.id')
                    ->leftjoin('state as st','st.id','=','m.state_id')
                    ->leftjoin('city as cit','cit.id','=','m.city_id')
                    ->leftjoin('race as r','r.id','=','m.race_id');

                    $members = $members->where(DB::raw('date(rs.`voucher_date`)'),'>=',date('Y-m-01'));
                    $members = $members->where(DB::raw('date(rs.`voucher_date`)'),'<=',date('Y-m-t'));
                  
                    $members = $members->offset(0)
                    ->limit($data['data_limit'])
                    ->get();
        $data['member_view'] = $members;
        return view('reports.resign_member')->with('data',$data);  
    }
    public function membersResignReportMore(Request $request){
        $offset = $request->input('offset');
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $company_id = $request->input('company_id');
        $branch_id = $request->input('branch_id');
        $member_auto_id = $request->input('member_auto_id');
        $date_type = $request->input('date_type');
        $fromdate = CommonHelper::ConvertdatetoDBFormat($from_date);
        $todate = CommonHelper::ConvertdatetoDBFormat($to_date);
        
        $members = DB::table('resignation as rs')->select('c.id as cid','m.name','m.email','m.id as id','m.status_id as status_id','m.branch_id as branch_id', 'm.member_number','m.designation_id','d.id as designationid','d.designation_name','m.gender','com.company_name','m.doj','m.old_ic','m.new_ic','m.mobile','st.state_name','cit.id as cityid','cit.city_name','st.id as stateid','m.state_id','m.city_id','m.race_id','m.levy','m.levy_amount','m.tdf','m.tdf_amount','com.short_code as companycode','r.race_name','r.short_code as raceshortcode','s.font_color','c.branch_name as branch_name','rs.accbenefit as contribution',DB::raw("ifnull(rs.`accbf`+rs.insuranceamount,0) AS benifit"),DB::raw("ifnull(rs.`accbf`+rs.`insuranceamount`+rs.`accbenefit`,0) AS total"),'rs.resignation_date')
                ->leftjoin('membership as m','m.id','=','rs.member_code')
                ->leftjoin('company_branch as c','c.id','=','m.branch_id')
                ->leftjoin('company as com','com.id','=','c.company_id')
                ->leftjoin('status as s','s.id','=','m.status_id')
                ->leftjoin('designation as d','m.designation_id','=','d.id')
                ->leftjoin('state as st','st.id','=','m.state_id')
                ->leftjoin('city as cit','cit.id','=','m.city_id')
                ->leftjoin('race as r','r.id','=','m.race_id');
               if($fromdate!="" && $todate!="" && $date_type==1){
                  $members = $members->where(DB::raw('date(rs.`resignation_date`)'),'>=',$fromdate);
                  $members = $members->where(DB::raw('date(rs.`resignation_date`)'),'<=',$todate);
               }
               if($fromdate!="" && $todate!="" && $date_type==2){
                    $members = $members->where(DB::raw('date(rs.`voucher_date`)'),'>=',$fromdate);
                    $members = $members->where(DB::raw('date(rs.`voucher_date`)'),'<=',$todate);
               }
              if($branch_id!=""){
                  $members = $members->where('m.branch_id','=',$branch_id);
              }else{
                  if($company_id!=""){
                      $members = $members->where('c.company_id','=',$company_id);
                  }
              }
             
              
          $members = $members->offset($offset)
              ->limit($this->limit)
              //->dump()
              ->get();
        echo json_encode($members);
        
    }

    public function takafulReport()
    {
        $data['data_limit']=$this->limit;
        $data['company_view'] = DB::table('company')->where('status','=','1')->get();
       
        $members = DB::table($this->membermonthendstatus_table.' as ms')
					->select('c.id as cid','m.name','m.email','m.id as id','m.status_id as status_id','m.branch_id as branch_id', 'm.member_number','m.designation_id','d.id as designationid','d.designation_name','m.gender','com.company_name','m.doj','m.old_ic','m.new_ic','m.mobile','st.state_name','cit.id as cityid','cit.city_name','st.id as stateid','m.state_id','m.city_id','m.race_id','m.levy','m.levy_amount','m.tdf','m.tdf_amount','com.short_code as companycode','r.race_name','r.short_code as raceshortcode','s.font_color','c.branch_name as branch_name','ms.SUBSCRIPTION_AMOUNT','ms.BF_AMOUNT',DB::raw("ifnull(ms.`SUBSCRIPTION_AMOUNT`+ms.`BF_AMOUNT`,0) AS total"))
					->leftjoin('membership as m','m.id','=','ms.MEMBER_CODE')
                    ->leftjoin('company_branch as c','c.id','=','m.branch_id')
                    ->leftjoin('company as com','com.id','=','c.company_id')
                    ->leftjoin('status as s','s.id','=','m.status_id')
                    ->leftjoin('designation as d','m.designation_id','=','d.id')
                    ->leftjoin('state as st','st.id','=','m.state_id')
                    ->leftjoin('city as cit','cit.id','=','m.city_id')
                    ->leftjoin('race as r','r.id','=','m.race_id');
      
        $members = $members->where(DB::raw('month(ms.`StatusMonth`)'),'=',date('m'));
        $members = $members->where(DB::raw('year(ms.`StatusMonth`)'),'=',date('Y'));
                  
		$members = $members->offset(0)
		->limit($data['data_limit'])
		->get();
        $data['member_view'] = $members;
        return view('reports.takaful')->with('data',$data);  
    }
	public function takafulReportMore(Request $request){
        $offset = $request->input('offset');
        $month_year = $request->input('month_year');
        $company_id = $request->input('company_id');
        $branch_id = $request->input('branch_id');
        $member_auto_id = $request->input('member_auto_id');
        $monthno = '';
        $yearno = '';
        if($month_year!=""){
          $fmmm_date = explode("/",$month_year);
          $monthno = date('m',strtotime('01-'.$fmmm_date[0].'-'.$fmmm_date[1]));
          $yearno = date('Y',strtotime('01-'.$fmmm_date[0].'-'.$fmmm_date[1]));
        }
			$members = DB::table($this->membermonthendstatus_table.' as ms')
					->select('c.id as cid','m.name','m.email','m.id as id','m.status_id as status_id','m.branch_id as branch_id', 'm.member_number','m.designation_id','d.id as designationid','d.designation_name','m.gender','com.company_name','m.doj','m.old_ic','m.new_ic','m.mobile','st.state_name','cit.id as cityid','cit.city_name','st.id as stateid','m.state_id','m.city_id','m.race_id','m.levy','m.levy_amount','m.tdf','m.tdf_amount','com.short_code as companycode','r.race_name','r.short_code as raceshortcode','s.font_color','c.branch_name as branch_name','ms.SUBSCRIPTION_AMOUNT','ms.BF_AMOUNT',DB::raw("ifnull(ms.`SUBSCRIPTION_AMOUNT`+ms.`BF_AMOUNT`,0) AS total"))
					->leftjoin('membership as m','m.id','=','ms.MEMBER_CODE')
                    ->leftjoin('company_branch as c','c.id','=','m.branch_id')
                    ->leftjoin('company as com','com.id','=','c.company_id')
                    ->leftjoin('status as s','s.id','=','m.status_id')
                    ->leftjoin('designation as d','m.designation_id','=','d.id')
                    ->leftjoin('state as st','st.id','=','m.state_id')
                    ->leftjoin('city as cit','cit.id','=','m.city_id')
                    ->leftjoin('race as r','r.id','=','m.race_id');
              if($monthno!="" && $yearno!=""){
                  $members = $members->where(DB::raw('month(ms.`StatusMonth`)'),'=',$monthno);
                  $members = $members->where(DB::raw('year(ms.`StatusMonth`)'),'=',$yearno);
              }
              if($branch_id!=""){
                  $members = $members->where('m.branch_id','=',$branch_id);
              }else{
                  if($company_id!=""){
                      $members = $members->where('c.company_id','=',$company_id);
                  }
              }
              if($member_auto_id!=""){
                  $members = $members->where('m.id','=',$member_auto_id);
              }
              
          $members = $members->offset($offset)
              ->limit($this->limit)
              //->dump()
              ->get();
        echo json_encode($members);

    }
	 public function halfshareReport(Request $request, $lang)
    {
       $half_s = DB::table('membermonthendstatus1 as mend')->select(DB::raw('sum(mend.totalbf_amount) as bfamount'),
       DB::raw('sum(mend.totalinsurance_amount) as insamt'), DB::raw('sum(mend.totalsubcrp_amount) as subamt'),
       'mend.branch_code','mend.statusmonth','cb.union_branch_id','ub.union_branch')
                ->leftjoin('company_branch as cb','cb.id','=','mend.branch_code') 
                ->leftjoin('union_branch as ub','ub.id','=','cb.union_branch_id')  
                ->where(DB::raw('month(mend.statusmonth)'),'=',date('m'))  
                ->where(DB::raw('year(mend.statusmonth)'),'=',date('Y'))
                ->groupBy('cb.union_branch_id')
                ->get();              
       $data['half_share'] = $half_s;
       $data['date'] = date('M/Y');
        return view('reports.halfshare')->with('data',$data);  
	}
	
	public function VariationReport(Request $request, $lang)
    {
		$data['data_limit']=$this->limit;
		$data['company_list'] = DB::table('company')->where('status','=','1')->get();
		$data['company_view'] = DB::table('mon_sub_company as mc')->select('c.id as cid','mc.id as id','c.company_name as company_name')
                                ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                                ->leftjoin('company as c','mc.CompanyCode','=','c.id')
                                ->where('ms.Date', '=', date('Y-m-01'))->get();
        return view('reports.variation')->with('data',$data);  
	}
	
	public function VariationFiltereport(Request $request, $lang)
    {
		$month_year = $request->input('month_year');
		$company_id = $request->input('company_id');
		$monthno = '';
        $yearno = '';
        if($month_year!=""){
          $fmmm_date = explode("/",$month_year);
          $monthno = date('m',strtotime('01-'.$fmmm_date[0].'-'.$fmmm_date[1]));
          $yearno = date('Y',strtotime('01-'.$fmmm_date[0].'-'.$fmmm_date[1]));
        }
		$data['data_limit']=$this->limit;
		$company_view = DB::table('mon_sub_company as mc')->select('c.id as cid','mc.id as id','c.company_name as company_name')
                                ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                                ->leftjoin('company as c','mc.CompanyCode','=','c.id');
		if($monthno!="" && $yearno!=""){
			$company_view = $company_view->where(DB::raw('month(ms.`Date`)'),'=',$monthno);
			$company_view = $company_view->where(DB::raw('year(ms.`Date`)'),'=',$yearno);
		}
		if($company_id!=""){
			$company_view = $company_view->where('mc.CompanyCode','=',$company_id);
		}
		$company_list =  $company_view->get();
		foreach($company_list as $ckey => $company){
            foreach($company as $newkey => $newvalue){
                $data['company_view'][$ckey][$newkey] = $newvalue;
            }
			$current_count = CommonHelper::getMonthlyPaidCount($company->cid,date('Y-m-01',strtotime('01-'.$monthno.'-'.$yearno)));
			$last_month_count = CommonHelper::getMonthlyPaidCount($company->cid,date('Y-m-01',strtotime('01-'.$monthno.'-'.$yearno.' -1 Month')));
            $data['company_view'][$ckey]['current_count'] = $current_count;
            $data['company_view'][$ckey]['last_count'] = $last_month_count;
            $data['company_view'][$ckey]['difference'] = abs($current_count-$last_month_count);
            $data['company_view'][$ckey]['diif_color'] = $current_count-$last_month_count>0 ? 'green' : 'red';
            $data['company_view'][$ckey]['unpaid'] = 0;
            $data['company_view'][$ckey]['paid'] = $current_count;
        }
		echo json_encode($data);
		
	}
	public function SubscriptionReport(Request $request, $lang)
    {
		$data['data_limit']=$this->limit;
		$data['company_list'] = DB::table('company')->where('status','=','1')->get();
		$data['company_view'] = DB::table('mon_sub_company as mc')->select('c.id as cid','mc.id as id','c.company_name as company_name')
                                ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                                ->leftjoin('company as c','mc.CompanyCode','=','c.id')
                                ->where('ms.Date', '=', date('Y-m-01'))->get();
        return view('reports.subscription')->with('data',$data);  
	}
	public function SubscriptionFiltereport(Request $request, $lang){
		$get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;
        $user_id = Auth::user()->id;
		
		$month_year = $request->input('month_year');
		$company_id = $request->input('company_id');
		$monthno = '';
        $yearno = '';
        if($month_year!=""){
          $fmmm_date = explode("/",$month_year);
          $monthno = date('m',strtotime('01-'.$fmmm_date[0].'-'.$fmmm_date[1]));
          $yearno = date('Y',strtotime('01-'.$fmmm_date[0].'-'.$fmmm_date[1]));
        }
		$data['data_limit']=$this->limit;
		$company_view = DB::table('mon_sub_company as mc')->select('c.id as cid','mc.id as id','c.company_name as company_name')
                                ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                                ->leftjoin('company as c','mc.CompanyCode','=','c.id');
		if($monthno!="" && $yearno!=""){
			$company_view = $company_view->where(DB::raw('month(ms.`Date`)'),'=',$monthno);
			$company_view = $company_view->where(DB::raw('year(ms.`Date`)'),'=',$yearno);
		}
		if($company_id!=""){
			$company_view = $company_view->where('mc.CompanyCode','=',$company_id);
		}
		$company_list =  $company_view->get();
		$dateformat = date('Y-m-01',strtotime('01-'.$monthno.'-'.$yearno));
		foreach($company_list as $ckey => $company){
            foreach($company as $newkey => $newvalue){
                $data['company_view'][$ckey][$newkey] = $newvalue;
            }
			
			$active_amt = CommonHelper::statusMembersCompanyAmount(1, $user_role, $user_id,$company->id, $dateformat);
			$default_amt = CommonHelper::statusMembersCompanyAmount(2, $user_role, $user_id,$company->id, $dateformat);
			$struckoff_amt = CommonHelper::statusMembersCompanyAmount(3, $user_role, $user_id,$company->id, $dateformat);
			$resign_amt = CommonHelper::statusMembersCompanyAmount(4, $user_role, $user_id,$company->id, $dateformat);
			$sundry_amt = CommonHelper::statusSubsCompanyMatchAmount(2, $user_role, $user_id,$company->id, $dateformat);
			$total_members = CommonHelper::statusSubsMembersCompanyTotalCount($user_role, $user_id,$company->id,$dateformat);
			
            $data['company_view'][$ckey]['total_members'] = $total_members;
            $data['company_view'][$ckey]['active_amt'] =  number_format($active_amt,2, '.', ',');
            $data['company_view'][$ckey]['default_amt'] =  number_format($default_amt,2, '.', ',');
            $data['company_view'][$ckey]['struckoff_amt'] =  number_format($struckoff_amt,2, '.', ',');
            $data['company_view'][$ckey]['resign_amt'] =  number_format($resign_amt,2, '.', ',');
            $data['company_view'][$ckey]['sundry_amt'] =  number_format($sundry_amt,2, '.', ',');
            $data['company_view'][$ckey]['total_amount'] =  number_format(($active_amt+$default_amt+$struckoff_amt+$resign_amt+$sundry_amt), 2, '.', ',');
        }
		echo json_encode($data);
	}
	
	public function filterHalfShareReport(Request $request, $lang){
		$month_year = $request->input('month_year');
		$monthno = '';
        $yearno = '';
        if($month_year!=""){
			$fmmm_date = explode("/",$month_year);
			$monthno = date('m',strtotime('01-'.$fmmm_date[0].'-'.$fmmm_date[1]));
			$yearno = date('Y',strtotime('01-'.$fmmm_date[0].'-'.$fmmm_date[1]));
			$data['date'] = $month_year;
        }else{
			$monthno = date('m');
			$yearno = date('Y');
			$data['date'] = date('M/Y');
		}
		$half_s = DB::table('membermonthendstatus1 as mend')->select(DB::raw('sum(mend.totalbf_amount) as bfamount'),
		DB::raw('sum(mend.totalinsurance_amount) as insamt'), DB::raw('sum(mend.totalsubcrp_amount) as subamt'),
       'mend.branch_code','mend.statusmonth','cb.union_branch_id','ub.union_branch')
                ->leftjoin('company_branch as cb','cb.id','=','mend.branch_code') 
                ->leftjoin('union_branch as ub','ub.id','=','cb.union_branch_id')  
                ->where(DB::raw('month(mend.statusmonth)'),'=',$monthno)  
                ->where(DB::raw('year(mend.statusmonth)'),'=',$yearno)
                ->groupBy('cb.union_branch_id')
                ->get();              
		$data['half_share'] = $half_s;
        return view('reports.halfshare')->with('data',$data); 
	}
}

