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
	
	public function membersReport()
    {
        $data['data_limit']=$this->limit;
        $data['company_view'] = DB::table('company')->where('status','=','1')->get();
		$data['member_view'] = DB::table('company_branch as c')->select('c.id as cid','m.name','m.email','m.id as id','m.status_id as status_id','m.branch_id as branch_id', 'm.member_number','m.designation_id','d.id as designationid','d.designation_name','m.gender','com.company_name','m.doj','m.old_ic','m.new_ic','m.mobile','st.state_name','cit.id as cityid','cit.city_name','st.id as stateid','m.state_id','m.city_id','m.race_id','m.levy','m.levy_amount','m.tdf','m.tdf_amount','com.short_code as companycode','r.race_name','r.short_code as raceshortcode','s.font_color','c.branch_name as branch_name')
                ->join('membership as m','c.id','=','m.branch_id')
                ->leftjoin('company as com','com.id','=','c.company_id')
                ->leftjoin('status as s','s.id','=','m.status_id')
                ->leftjoin('designation as d','m.designation_id','=','d.id')
                ->leftjoin('state as st','st.id','=','m.state_id')
                ->leftjoin('city as cit','cit.id','=','m.city_id')
                ->leftjoin('race as r','r.id','=','m.race_id')
				->offset(0)
				->limit($data['data_limit'])
                ->get();
        return view('Reports.members')->with('data',$data);  
    }
	public function membersReportMore(Request $request){
		  $offset = $request->input('offset');
		  $members = DB::table('company_branch as c')->select('c.id as cid','m.name','m.email','m.id as id','m.status_id as status_id','m.branch_id as branch_id', 'm.member_number','m.designation_id','d.id as designationid','d.designation_name','m.gender','com.company_name','m.doj','m.old_ic','m.new_ic','m.mobile','st.state_name','cit.id as cityid','cit.city_name','st.id as stateid','m.state_id','m.city_id','m.race_id',DB::raw("ifnull(m.levy,'') as levy"),DB::raw("ifnull(m.levy_amount,'') as levy_amount"),'m.tdf','m.tdf_amount','com.short_code as companycode','r.race_name','r.short_code as raceshortcode','s.font_color','c.branch_name as branch_name')
                ->join('membership as m','c.id','=','m.branch_id')
                ->leftjoin('company as com','com.id','=','c.company_id')
                ->leftjoin('status as s','s.id','=','m.status_id')
                ->leftjoin('designation as d','m.designation_id','=','d.id')
                ->leftjoin('state as st','st.id','=','m.state_id')
                ->leftjoin('city as cit','cit.id','=','m.city_id')
                ->leftjoin('race as r','r.id','=','m.race_id')
				->offset($offset)
				->limit($this->limit)
                ->get();
		echo json_encode($members);
	}
}

