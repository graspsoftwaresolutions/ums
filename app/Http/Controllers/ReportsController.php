<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ReportsController extends Controller
{
	protected $limit;
	public function __construct()
    {
        ini_set('memory_limit', '-1');
        $this->limit = 25;       
    }
    public function newMemberIndex()
    {
        return view('reports.new_member');
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
                }else{
                    $members = $members->where(DB::raw('month(m.`doj`)'),'=',date('m'));
                    $members = $members->where(DB::raw('year(m.`doj`)'),'=',date('Y'));
                }
		$members = $members->offset(0)
				->limit($data['data_limit'])
                ->get();
        $data['member_view'] = $members;
        return view('Reports.members')->with('data',$data);  
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
}

