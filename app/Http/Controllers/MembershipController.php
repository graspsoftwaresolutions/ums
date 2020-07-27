<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Http\Request;
use App\Model\Membership;
use App\Model\Country;
use App\Model\State;
use App\Model\City;
use App\Model\Company;
use DB;
use View;
use Mail;
use App\Role;
use App\User;
use App\Model\MemberNominees;
use App\Model\MemberGuardian;
use App\Model\MemberFee;
use App\Model\CompanyBranch;
use App\Model\UnionBranch;
use App\Model\Resignation;
use App\Model\Reason;
use App\Helpers\CommonHelper;
use App\Mail\SendMemberMailable;
use URL;
use Auth;
use Carbon\Carbon;
use PDF;


class MembershipController extends Controller
{
    public function __construct()
    {
        ini_set('memory_limit', '-1');
        $this->middleware('auth'); 
		//$this->middleware('role:union|union-branch|company|company-branch');
        $this->Membership = new Membership;
        $this->MemberGuardian = new MemberGuardian;       
    }
    public function index(Request $request)
    {
        $data['country_view'] = DB::table('country')->select('id','country_name')->where('status','=','1')->get();
        $data['company_view'] = DB::table('company')->where('status','=','1')->get();
        $data['companybranch_view'] = DB::table('company_branch')->where('status','=','1')->get();
        $data['race_view'] = DB::table('race')->where('status','=','1')->get();
        $data['status_view'] = DB::table('status')->where('status','=','1')->get();
        $data['state_view'] = DB::table('state')->where('status','=','1')->get();
        $data['city_view'] = DB::table('city')->where('status','=','1')->get();
        $data['unionbranch_view'] = DB::table('union_branch')->where('status','=','1')->get();
        $data['member_status'] = 'all';
        if(!empty($request->all())){
            $data['member_status'] = $request->input('status');
        }
        $data['member_type'] = 1;

        return view('membership.membership')->with('data',$data); 
    }
	
    public function addMember()
    {
         $data['country_view'] = DB::table('country')->select('id','country_name')->where('status','=','1')->get();
         $data['title_view'] = DB::table('persontitle')->where('status','=','1')->get();
         $data['company_view'] = DB::table('company')->where('status','=','1')->get();
         $data['designation_view'] = DB::table('designation')->where('status','=','1')->get();
         $data['race_view'] = DB::table('race')->where('status','=','1')->get();
         $data['status_view'] = DB::table('status')->where('status','=','1')->get();
         $data['fee_list'] = DB::table('fee')->where('status','=','1')->get();
         $data['relationship_view'] = DB::table('relation')->where('status','=','1')->get();
         $data['user_type'] = 1;
         
        //return $data['title_view'];
        return view('membership.add_membership')->with('data',$data);  
        
    }
    
    
    public function edit(Request $request, $lang,$id)
    {
		$irc_status = 0;
		$resign_status = 0;
        if(!empty($request->all())){
			if($request->input('status')==1){
				 $irc_status = 1;
			}
			if($request->input('status')==2){
				 $resign_status = 1;
			}
	    }
        $id = Crypt::decrypt($id);
        //print_r($id) ;
         DB::connection()->enableQueryLog();
         $data['member_view'] = DB::table('membership')->select('membership.id as mid','membership.member_title_id','membership.member_number','membership.name','membership.gender','membership.designation_id','membership.email','membership.mobile',
                                        'membership.country_id','membership.state_id','membership.city_id','membership.address_one','membership.address_two','membership.address_three','membership.race_id','membership.old_ic','membership.new_ic',
                                        'membership.dob','membership.doj','membership.doe','membership.postal_code','membership.salary','membership.status_id','branch_id','membership.password','membership.user_type','membership.status','country.id','country.country_name','country.status','state.id','state.state_name','state.status',
                                        'city.id','city.city_name','city.status','company_branch.id','company_branch.branch_name','company_branch.status','designation.id','designation.designation_name','designation.status','race.id','race.race_name','race.status','persontitle.id','persontitle.person_title','persontitle.status','membership.old_member_number','membership.employee_id','membership.is_request_approved',
                                        'membership.levy','membership.levy_amount','membership.tdf','membership.tdf_amount','membership.current_salary','membership.last_update','membership.approval_status','membership.approval_reason','membership.designation_new_id','membership.designation_others','membership.approved_by')
                                ->leftjoin('country','membership.country_id','=','country.id')
                                ->leftjoin('state','membership.state_id','=','state.id')
                                ->leftjoin('city','membership.city_id','=','city.id')
                                ->leftjoin('company_branch','membership.branch_id','=','company_branch.id')
                                ->leftjoin('persontitle','membership.member_title_id','=','persontitle.id')
                                ->leftjoin('race','membership.race_id','=','race.id')
                                ->leftjoin('designation','membership.designation_id','=','designation.id')
                                ->where([
                                   ['membership.id','=',$id]
                                ])->get();

                            //     $queries = DB::getQueryLog();
                            //   dd($queries);
                             
        $country_id = $data['member_view'][0]->country_id;
      
        $state_id = $data['member_view'][0]->state_id;
        $city_id = $data['member_view'][0]->city_id;
      
        $company_id = CommonHelper::get_branch_company_id($data['member_view'][0]->branch_id);
        //$company_id = $data['member_view'][0]->company_id;
        $data['status_view'] = DB::table('status')->where('status','=','1')->get();
        $data['company_view'] = DB::table('company')->select('id','company_name')->where('status','=','1')->get();
        $data['state_view'] = DB::table('state')->select('id','state_name')->where('status','=','1')->where('country_id','=',$country_id)->get();
        $data['city_view'] = DB::table('city')->select('id','city_name')->where('status','=','1')->where('state_id','=',$state_id)->get();
        $data['country_view'] = DB::table('country')->select('id','country_name')->where('status','=','1')->get();
        $data['branch_view'] = DB::table('company_branch')->where('status','=','1')->where('company_id', $company_id)->get();
        $data['title_view'] = DB::table('persontitle')->where('status','=','1')->get();
        $data['designation_view'] = DB::table('designation')->where('status','=','1')->get();
        $data['race_view'] = DB::table('race')->where('status','=','1')->get();
        $data['relationship_view'] = DB::table('relation')->where('status','=','1')->get();
        $data['nominee_view'] = DB::table('member_nominees')->where('status','=','1')->where('member_id','=',$id)->get();
        $data['gardian_view'] = DB::table('member_guardian')->where('status','=','1')->where('member_id','=',$id)->get();
       
        $data['fee_list'] = DB::table('fee')->where('status','=','1')->get();
        $data['irc_status'] = $irc_status;
        $data['resign_status'] = $resign_status;
        $data['view_status'] = 0;
        
        $data['fee_view'] = DB::table('member_fee')->where('status','=','1')->where('member_id','=',$id)->get();
      // return  $data; 
        // $data['user_type'] = 1;
        // return view('membership.add_membership')->with('data',$data);  
		//dd($data);
        return view('membership.edit_membership')->with('data',$data); 
   
    }
    
    public function delete($id)
	{
		$data = DB::table('membership')->where('id','=',$id)->update(['status'=>'0']);
		return redirect('membership')->with('message','Member Deleted Succesfully');
    }
    

    public function new_members(Request $request, $lang){
        $type = $request->input('type');
        $data['country_view'] = DB::table('country')->select('id','country_name')->where('status','=','1')->get();
        $data['company_view'] = DB::table('company')->where('status','=','1')->get();
        $data['companybranch_view'] = DB::table('company_branch')->where('status','=','1')->get();
        $data['race_view'] = DB::table('race')->where('status','=','1')->get();
        $data['status_view'] = DB::table('status')->where('status','=','1')->get();
        $data['state_view'] = DB::table('state')->where('status','=','1')->get();
        $data['city_view'] = DB::table('city')->where('status','=','1')->get();
        $data['unionbranch_view'] = DB::table('union_branch')->where('status','=','1')->get();
        $data['member_status'] = 'all';
        if(!empty($request->all())){
            $data['member_status'] = $request->input('status');
        }
        if($type==''){
            $data['type'] = 0;
        }else{
            $data['type'] = $type;
        }
        $data['member_type'] = 0;
        //return 'pending';
        return view('membership.pending_membership')->with('data',$data); 
    }

    public function getNomineeData(Request $request){
       
        $nominee_id = $request->nominee_id;
        $res = DB::table('member_nominees')->where([
            ['id','=',$nominee_id]
        ])->get();
        $result_data = $res[0];
        $result_data->dob = date('d/M/Y',strtotime($result_data->dob));
      
        return response()->json($result_data);
    }

    public function updateNominee(Request $request){
        $returndata = array('status' => 0, 'message' => '', 'data' => '');
        $nominee = MemberNominees::find($request->edit_nominee_id);
        $nominee->address_one = $request->edit_nominee_address_one;
        $nominee->address_two = $request->edit_nominee_address_two;
        $nominee->address_three = $request->edit_nominee_address_three;
        $nominee->city_id = $request->edit_nominee_city_id;
        $nominee->country_id = $request->edit_nominee_country_id;

        $fmmm_date = explode("/",$request->edit_nominee_dob);           							
        $dob1 = $fmmm_date[2]."-".$fmmm_date[1]."-".$fmmm_date[0];
        $dob = date('Y-m-d', strtotime($dob1));
        $nominee->dob =  $dob;

        //$nominee->dob = $request->edit_nominee_dob;
        $nominee->gender = $request->edit_sex;
        $nominee->mobile = $request->edit_nominee_mobile;
        $nominee->nominee_name = $request->edit_nominee_name;
        $nominee->nric_n = $request->edit_nric_n;
        $nominee->nric_o = $request->edit_nric_o;
        $nominee->phone = $request->edit_nominee_phone;
        $nominee->postal_code = $request->edit_nominee_postal_code;
        $nominee->relation_id = $request->edit_relationship;
        $nominee->save();

        $years =  Carbon::parse($nominee->dob)->age;

        if($nominee){
            $returndata = array('status' => 1, 'message' => 'Nominee updated successfully', 'data' => array('age'=> $years,'relationship'=> CommonHelper::get_relationship_name($nominee->relation_id),
            'name' =>$nominee->nominee_name, 'gender' => $nominee->gender, 'nric_n' => $nominee->nric_n, 'nric_o' => $nominee->nric_o, 'nominee_id' =>$nominee->id));
         }else{
            $returndata = array('status' => 0, 'message' => 'Failed to add', 'data' => '');
        }
       echo json_encode($returndata);

    }
    public function deleteNominee(Request $request){
        $delete = MemberNominees::find($request->nominee_id)->delete();
        $returndata = array('status' => 0, 'message' => '', 'data' => '');
        if($delete){
            $returndata = array('status' => 1, 'message' => 'Nominee data deleted successfully', 'data' => '');
        }else{
            $returndata = array('status' => 0, 'message' => 'Failed to delete', 'data' => '');
            
        }
        echo json_encode($returndata);
    }

    public function deleteFee(Request $request){
        $delete = MemberFee::find($request->fee_id)->delete();
        $returndata = array('status' => 0, 'message' => '', 'data' => '');
        if($delete){
            $returndata = array('status' => 1, 'message' => 'Fee data deleted successfully', 'data' => '');
        }else{
            $returndata = array('status' => 0, 'message' => 'Failed to delete', 'data' => '');
            
        }
        echo json_encode($returndata);
    }

    //Company Details End
    public function AjaxmembersList(Request $request,$lang, $type){
        $member_status = $request->input('status');
        $sl=0;
        $columns[$sl++] = 'm.id';
        $columns[$sl++] = 'm.member_number';
        $columns[$sl++] = 'm.name';
        $columns[$sl++] = 'm.designation_id';
        $columns[$sl++] = 'm.gender'; 
        $columns[$sl++] = 'com.short_code';
        $columns[$sl++] = 'c.branch_name';
        $columns[$sl++] = 'm.levy';
        $columns[$sl++] = 'm.levy_amount';
        $columns[$sl++] = 'm.tdf';
        $columns[$sl++] = 'm.tdf_amount';
        $columns[$sl++] = 'm.doj';
        $columns[$sl++] = 'm.city_id';
        $columns[$sl++] = 'm.state_id';
        $columns[$sl++] = 'm.old_ic';
        $columns[$sl++] = 'm.new_ic';
		$columns[$sl++] = 'm.mobile';
		$columns[$sl++] = 'm.email';
        
        $columns[$sl++] = 'm.race_id';
		//if($type==1){
			$columns[$sl++] = 'm.status_id';
		//}
        
		if($type==1){
			$approved_cond = 1;
		}else{
			$approved_cond = 0;
		}
		
		$get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;
		$user_id = Auth::user()->id; 
		$member_qry = '';
		
		$unionbranch_id = $request->input('unionbranch_id'); 
		$company_id = $request->input('company_id'); 
		$branch_id = $request->input('branch_id'); 
		$gender = $request->input('gender'); 
		$race_id = $request->input('race_id'); 
		$status_id = $request->input('status_id'); 
		$country_id = $request->input('country_id'); 
		$state_id = $request->input('state_id');
		$city_id = $request->input('city_id'); 
		
		
		if($user_role=='union' || $user_role=='data-entry'){
            //DB::enableQueryLog();
				
			$member_qry = DB::table('membership as m')->select(DB::raw("IFNULL(m.levy, '---') AS levy"),'m.member_number','m.id as id','m.name','m.gender','m.designation_id','m.email','m.branch_id','m.status_id','m.doj','c.branch_name','c.id as companybranchid','com.id as companyid','com.company_name' ,'d.designation_name','m.old_ic','m.new_ic','m.mobile',DB::raw("IFNULL(st.state_name, '---') AS state_name"),'cit.id as cityid',DB::raw("IFNULL(cit.city_name, '---') AS city_name"),'st.id as stateid','m.state_id','m.city_id','m.race_id',DB::raw("IFNULL(m.levy_amount, '---') AS levy_amount"),DB::raw("IFNULL(m.tdf, '---') AS tdf"),DB::raw("IFNULL(m.tdf_amount, '---') AS tdf_amount"),'com.short_code','r.race_name','r.short_code as raceshortcode','s.status_name','s.font_color','con.country_name')
						 ->leftjoin('designation as d','m.designation_id','=','d.id')
						 ->leftjoin('company_branch as c','m.branch_id','=','c.id')
						 ->leftjoin('company as com','com.id','=','c.company_id')
						 ->leftjoin('union_branch as ub','c.union_branch_id','=','ub.id')
						 ->leftjoin('status as s','s.id','=','m.status_id')
						 ->leftjoin('country as con','con.id','=','m.country_id')
						 ->leftjoin('state as st','st.id','=','c.state_id')
						 ->leftjoin('city as cit','cit.id','=','c.city_id')
						 ->leftjoin('race as r','r.id','=','m.race_id')
                         ->where('m.is_request_approved','=',$approved_cond)
                         ->orderBy('m.id','DESC');
			if($branch_id!=""){
				  $member_qry = $member_qry->where('m.branch_id','=',$branch_id);
			  }elseif($company_id!= ''){
				   $member_qry = $member_qry->where('c.company_id','=',$company_id);
			  }
			  if($unionbranch_id!= ''){
				  $member_qry = $member_qry->where('c.union_branch_id','=',$unionbranch_id);
			  }
			 if($gender!="")
			 {
			  	$member_qry = $member_qry->where('m.gender','=',$gender);
             }
			 if($race_id != "")
			 {
				 $member_qry = $member_qry->where('m.race_id','=',$race_id);
			 }
			 if($status_id!=0 && $status_id != "")
			 {
				 $member_qry = $member_qry->where('m.status_id','=',$status_id);
			 }
			 if($country_id != "")
			 {
				 $member_qry = $member_qry->where('c.country_id','=',$country_id);
			 }
			 if($state_id != "")
			 {
				 $member_qry = $member_qry->where('c.state_id','=',$state_id);
			 }
			 if($city_id != "")
			 {
				 $member_qry = $member_qry->where('c.city_id','=',$city_id);
			 }
			  
			if($member_status !='all'){
				$member_qry = $member_qry->where('m.status_id','=',$member_status);
			}
			//$member_qry->dump()->get();			 
			// $queries = DB::getQueryLog();
			// dd($queries);         
         
                        
		}else if($user_role=='union-branch'){
           
            $union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id');
            $union_branch_id_val = '';
			if(count($union_branch_id)>0){
                $union_branch_id_val = $union_branch_id[0];
                $member_qry = DB::table('company_branch as c')->select(DB::raw("IFNULL(m.levy, '---') AS levy"),'c.id as cid','m.name','m.email','m.id as id','m.status_id as status_id','m.branch_id as branch_id',
                'm.member_number','m.designation_id','d.id as designationid','d.designation_name','m.gender','com.company_name','m.doj','m.old_ic','m.new_ic','m.mobile',DB::raw("IFNULL(st.state_name, '---') AS state_name"),'cit.id as cityid',DB::raw("IFNULL(cit.city_name, '---') AS city_name"),'st.id as stateid','m.state_id','m.city_id','m.race_id',DB::raw("IFNULL(m.levy_amount, '---') AS levy_amount"),DB::raw("IFNULL(m.tdf, '---') AS tdf"),DB::raw("IFNULL(m.tdf_amount, '---') AS tdf_amount"),'com.short_code','r.race_name','r.short_code as raceshortcode','s.font_color')
                ->join('membership as m','c.id','=','m.branch_id')
                ->leftjoin('company as com','com.id','=','c.company_id')
                ->leftjoin('union_branch as ub','c.union_branch_id','=','ub.id')
                ->leftjoin('status as s','s.id','=','m.status_id')
                ->leftjoin('designation as d','m.designation_id','=','d.id')
                ->leftjoin('country as con','con.id','=','m.country_id')
                ->leftjoin('state as st','st.id','=','c.state_id')
                ->leftjoin('city as cit','cit.id','=','c.city_id')
                ->leftjoin('race as r','r.id','=','m.race_id')
                ->orderBy('m.id','DESC')
                ->where([
                    ['c.union_branch_id','=',$union_branch_id_val],
                    ['m.is_request_approved','=',$approved_cond]
                    ]);
                	if($branch_id!=""){
                        $member_qry = $member_qry->where('m.branch_id','=',$branch_id);
                    }elseif($company_id!= ''){
                         $member_qry = $member_qry->where('c.company_id','=',$company_id);
                    }
                    if($unionbranch_id!= ''){
                        $member_qry = $member_qry->where('c.union_branch_id','=',$unionbranch_id);
                    }
                   if($gender!="")
                   {
                        $member_qry = $member_qry->where('m.gender','=',$gender);
                    }
                   if($race_id != "")
                   {
                       $member_qry = $member_qry->where('m.race_id','=',$race_id);
                   }
                   if($status_id!=0 && $status_id != "")
                   {
                       $member_qry = $member_qry->where('m.status_id','=',$status_id);
                   }
                   if($country_id != "")
                   {
                       $member_qry = $member_qry->where('m.country_id','=',$country_id);
                   }
                   if($state_id != "")
                   {
                       $member_qry = $member_qry->where('c.state_id','=',$state_id);
                   }
                   if($city_id != "")
                   {
                       $member_qry = $member_qry->where('c.city_id','=',$city_id);
                   }
                if($member_status!='all'){
                    $member_qry = $member_qry->where('m.status_id','=',$member_status);
                }
			}
		}else if($user_role=='company'){
			$company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id');
			if(count($company_id)>0){
				$companyid = $company_id[0];
                $member_qry = DB::table('company_branch as c')->select(DB::raw("IFNULL(m.levy, '---') AS levy"),'c.id as cid','m.name','m.email','m.id','m.mobile','m.status_id as status_id','m.branch_id as branch_id',
                              'm.member_number','m.designation_id','d.designation_name','m.gender','com.company_name','m.doj','m.old_ic','m.new_ic','m.mobile',DB::raw("IFNULL(st.state_name, '---') AS state_name"),'cit.id as cityid',DB::raw("IFNULL(cit.city_name, '---') AS city_name"),'st.id as stateid','m.state_id','m.city_id','m.race_id',DB::raw("IFNULL(m.levy_amount, '---') AS levy_amount"),DB::raw("IFNULL(m.tdf, '---') AS tdf"),DB::raw("IFNULL(m.tdf_amount, '---') AS tdf_amount"),'com.short_code','r.race_name as raceshortcode','s.font_color')
                ->join('membership as m','c.id','=','m.branch_id')
                ->leftjoin('designation as d','m.designation_id','=','d.id')
                ->leftjoin('company as com','com.id','=','c.company_id')
                ->leftjoin('union_branch as ub','c.union_branch_id','=','ub.id')
                ->leftjoin('status as s','s.id','=','m.status_id')
                ->leftjoin('country as con','con.id','=','m.country_id')
                ->leftjoin('state as st','st.id','=','c.state_id')
                ->leftjoin('city as cit','cit.id','=','c.city_id')
                ->leftjoin('race as r','r.id','=','m.race_id')
                ->orderBy('m.id','DESC')
                ->where([
                    ['c.company_id','=',$companyid],
                    ['m.is_request_approved','=',$approved_cond]
                    ]);
                    if($branch_id!=""){
                        $member_qry = $member_qry->where('m.branch_id','=',$branch_id);
                    }elseif($company_id!= ''){
                         $member_qry = $member_qry->where('c.company_id','=',$company_id);
                    }
                    if($unionbranch_id!= ''){
                        $member_qry = $member_qry->where('c.union_branch_id','=',$unionbranch_id);
                    }
                   if($gender!="")
                   {
                        $member_qry = $member_qry->where('m.gender','=',$gender);
                    }
                   if($race_id != "")
                   {
                       $member_qry = $member_qry->where('m.race_id','=',$race_id);
                   }
                   if($status_id!=0 && $status_id != "")
                   {
                       $member_qry = $member_qry->where('m.status_id','=',$status_id);
                   }
                   if($country_id != "")
                   {
                       $member_qry = $member_qry->where('c.country_id','=',$country_id);
                   }
                   if($state_id != "")
                   {
                       $member_qry = $member_qry->where('c.state_id','=',$state_id);
                   }
                   if($city_id != "")
                   {
                       $member_qry = $member_qry->where('c.city_id','=',$city_id);
                   }
                if($member_status!='all'){
                    $member_qry = $member_qry->where('m.status_id','=',$member_status);
                }
			}
		}else if($user_role=='company-branch'){
			$branch_id = CompanyBranch::where('user_id',$user_id)->pluck('id');
			if(count($branch_id)>0){
				$branchid = $branch_id[0];
                $member_qry = DB::table('company_branch as c')->select(DB::raw("IFNULL(m.levy, '---') AS levy"),'c.id as cid','m.name','m.email','m.id','m.mobile','m.status_id as status_id','m.branch_id as branch_id',
                              'm.member_number','m.designation_id','d.designation_name','m.gender','com.company_name','m.doj','m.old_ic','m.new_ic','m.mobile','m.state_id','m.city_id','m.race_id','m.mobile',DB::raw("IFNULL(st.state_name, '---') AS state_name"),'cit.id as cityid',DB::raw("IFNULL(cit.city_name, '---') AS city_name"),'st.id as stateid','m.state_id','m.city_id','m.race_id',DB::raw("IFNULL(m.levy_amount, '---') AS levy_amount"),DB::raw("IFNULL(m.tdf, '---') AS tdf"),DB::raw("IFNULL(m.tdf_amount, '---') AS tdf_amount"),'com.short_code','r.race_name','r.short_code')
                ->join('membership as m','c.id','=','m.branch_id')
                ->leftjoin('designation as d','m.designation_id','=','d.id')
                ->leftjoin('company as com','com.id','=','c.company_id')
                ->leftjoin('union_branch as ub','c.union_branch_id','=','ub.id')
                ->leftjoin('status as s','s.id','=','m.status_id')
                ->leftjoin('country as con','con.id','=','m.country_id')
                ->leftjoin('state as st','st.id','=','c.state_id')
                ->leftjoin('city as cit','cit.id','=','c.city_id')
                ->leftjoin('race as r','r.id','=','m.race_id')
                ->orderBy('m.id','DESC')
                ->where([
                    ['m.branch_id','=',$branchid],
                    ['m.is_request_approved','=',$approved_cond]
                    ]);
                    if($branch_id!=""){
                        $member_qry = $member_qry->where('m.branch_id','=',$branch_id);
                    }elseif($company_id!= ''){
                         $member_qry = $member_qry->where('c.company_id','=',$company_id);
                    }
                    if($unionbranch_id!= ''){
                        $member_qry = $member_qry->where('c.union_branch_id','=',$unionbranch_id);
                    }
                   if($gender!="")
                   {
                        $member_qry = $member_qry->where('m.gender','=',$gender);
                    }
                   if($race_id != "")
                   {
                       $member_qry = $member_qry->where('m.race_id','=',$race_id);
                   }
                   if($status_id!=0 && $status_id != "")
                   {
                       $member_qry = $member_qry->where('m.status_id','=',$status_id);
                   }
                   if($country_id != "")
                   {
                       $member_qry = $member_qry->where('c.country_id','=',$country_id);
                   }
                   if($state_id != "")
                   {
                       $member_qry = $member_qry->where('c.state_id','=',$state_id);
                   }
                   if($city_id != "")
                   {
                       $member_qry = $member_qry->where('c.city_id','=',$city_id);
                   }
                if($member_status!='all'){
                    $member_qry = $member_qry->where('m.status_id','=',$member_status);
                }
			}
        }
		$totalData = 0;
		if($member_qry!=""){
            $totalData = $member_qry->count();
           
		}
								
        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            //DB::enableQueryLog();
				$compQuery = DB::table('company_branch as c')
				->select(DB::raw("IFNULL(m.levy, '---') AS levy"),DB::raw("IFNULL(c.branch_name, '---') AS branch_name"),DB::raw("IFNULL(d.designation_name, '---') AS designation_name"),'c.id as cid','m.name','m.email','m.id as id','m.status_id as status_id','m.branch_id as branch_id','s.status_name as status_name','m.member_number','m.designation_id',DB::raw("IFNULL(m.gender, '---') AS gender"),DB::raw("IFNULL(com.company_name, '---') AS company_name"),DB::raw("IFNULL(m.doj, '---') AS doj"),DB::raw("IFNULL(m.old_ic, '---') AS old_ic"),DB::raw("IFNULL(m.new_ic, '---') AS new_ic"),DB::raw("IFNULL(st.state_name, '---') AS state_name"),'cit.id as cityid',DB::raw("IFNULL(cit.city_name, '---') AS city_name"),'st.id as stateid','m.state_id','m.city_id','m.race_id',DB::raw("IFNULL(m.levy_amount, '---') AS levy_amount"),DB::raw("IFNULL(m.tdf, '---') AS tdf"),DB::raw("IFNULL(m.tdf_amount, '---') AS tdf_amount"),DB::raw("IFNULL(com.short_code, '---') AS short_code"),DB::raw("IFNULL(m.mobile, '---') AS mobile"),DB::raw("IFNULL(r.race_name, '---') AS race_name"),DB::raw("IFNULL(r.short_code, '---') AS raceshortcode"),'s.status_name','s.font_color')
                ->join('membership as m','c.id','=','m.branch_id')
                ->leftjoin('designation as d','m.designation_id','=','d.id')
                ->leftjoin('company as com','com.id','=','c.company_id')
                ->leftjoin('status as s','s.id','=','m.status_id')
                ->leftjoin('state as st','st.id','=','c.state_id')
                ->leftjoin('city as cit','cit.id','=','c.city_id')
                ->leftjoin('race as r','r.id','=','m.race_id')
                ->where('m.is_request_approved','=',$approved_cond);
                if($member_status!='all'){
                    $compQuery = $compQuery->where('m.status_id','=',$member_status);
                }
				if($user_role=='union-branch'){
					$compQuery =  $compQuery->where([
                    ['c.union_branch_id','=',$union_branch_id_val]
                    ]);
				}
				if($user_role=='company'){
					$compQuery =  $compQuery->where([
                    ['c.company_id','=',$companyid]
                    ]);
				}
				if($user_role=='company-branch'){
					$compQuery =  $compQuery->where([
                    ['m.branch_id','=',$branchid]
                    ]);
                }
                if($branch_id!=""){
                    $compQuery = $compQuery->where('m.branch_id','=',$branch_id);
                }elseif($company_id!= ''){
                     $compQuery = $compQuery->where('c.company_id','=',$company_id);
                }
                if($unionbranch_id!= ''){
                    $compQuery = $compQuery->where('c.union_branch_id','=',$unionbranch_id);
                }
               // $compQuery->dump()->get();
               if($gender!="")
               {
                    $compQuery = $compQuery->where('m.gender','=',$gender);
              }
               if($race_id != "")
               {
                   $compQuery = $compQuery->where('m.race_id','=',$race_id);
               }
               if($status_id!=0 && $status_id != "")
               {
                   $compQuery = $compQuery->where('m.status_id','=',$status_id);
               }
               if($country_id != "")
               {
                   $compQuery = $compQuery->where('c.country_id','=',$country_id);
               }
               if($state_id != "")
               {
                   $compQuery = $compQuery->where('c.state_id','=',$state_id);
               }
               if($city_id != "")
               {
                   $compQuery = $compQuery->where('c.city_id','=',$city_id);
               }
                
              if($member_status !='all'){
                  $compQuery = $compQuery->where('m.status_id','=',$member_status);
              }
                
			if( $limit != -1){
				$compQuery = $compQuery->offset($start)
				->limit($limit);
            }
           /*  if($order =='m.member_number'){
                $memberslist = $compQuery->orderBy('m.id','desc')
			        ->get()->toArray(); 
            }else{
                $memberslist = $compQuery->orderBy($order,$dir)
                ->get()->toArray(); 
            } */
			$memberslist = $compQuery->orderBy($order,$dir)
                ->get()->toArray(); 
            
        }
        else {
           // DB::enableQueryLog();
            $search = $request->input('search.value'); 
        
			$compQuery = DB::table('company_branch as c')
							->select(DB::raw("IFNULL(m.levy, '---') AS levy"),DB::raw("IFNULL(c.branch_name, '---') AS branch_name"),DB::raw("IFNULL(d.designation_name, '---') AS designation_name"),'c.id as cid','m.name','m.email','m.id as id','m.status_id as status_id','m.branch_id as branch_id','s.status_name as status_name','m.member_number','m.designation_id',DB::raw("IFNULL(m.gender, '---') AS gender"),DB::raw("IFNULL(com.company_name, '---') AS company_name"),DB::raw("IFNULL(m.doj, '---') AS doj"),DB::raw("IFNULL(m.old_ic, '---') AS old_ic"),DB::raw("IFNULL(m.new_ic, '---') AS new_ic"),DB::raw("IFNULL(st.state_name, '---') AS state_name"),'cit.id as cityid',DB::raw("IFNULL(cit.city_name, '---') AS city_name"),'st.id as stateid','m.state_id','m.city_id','m.race_id',DB::raw("IFNULL(m.levy_amount, '---') AS levy_amount"),DB::raw("IFNULL(m.tdf, '---') AS tdf"),DB::raw("IFNULL(m.tdf_amount, '---') AS tdf_amount"),DB::raw("IFNULL(com.short_code, '---') AS short_code"),DB::raw("IFNULL(m.mobile, '---') AS mobile"),DB::raw("IFNULL(r.race_name, '---') AS race_name"),DB::raw("IFNULL(r.short_code, '---') AS raceshortcode"),'s.status_name','s.font_color')
                            ->join('membership as m','c.id','=','m.branch_id')
                            ->leftjoin('designation as d','m.designation_id','=','d.id')
                            ->leftjoin('company as com','com.id','=','c.company_id')
                            ->leftjoin('status as s','s.id','=','m.status_id')
                            ->leftjoin('state as st','st.id','=','c.state_id')
                            ->leftjoin('city as cit','cit.id','=','c.city_id')
                            ->leftjoin('race as r','r.id','=','m.race_id')
                            ->where('m.is_request_approved','=',$approved_cond);
                            if($member_status!='all'){
                                $compQuery = $compQuery->where('m.status_id','=',$member_status);
                            }
							if($user_role=='union-branch'){
								$compQuery =  $compQuery->where([
								['c.union_branch_id','=',$union_branch_id]
								]);
							}
							if($user_role=='company'){
								$compQuery =  $compQuery->where([
								['c.company_id','=',$companyid]
								]);
							}
							if($user_role=='company-branch'){
								$compQuery =  $compQuery->where([
								['m.branch_id','=',$branchid]
								]);
							}
                            $compQuery =  $compQuery->where(function($query) use ($search){
                                $query->orWhere('com.company_name', 'LIKE',"%{$search}%")
                                ->orWhere('m.member_number', '=',"{$search}")
                                ->orWhere('d.designation_name', 'LIKE',"%{$search}%")
                                ->orWhere('m.gender', 'LIKE',"%{$search}%")
                                ->orWhere('m.doj', 'LIKE',"%{$search}%")
                                ->orWhere('m.name', 'LIKE',"%{$search}%")
                                ->orWhere(DB::raw("TRIM(LEADING '0' FROM m.old_ic)"), 'LIKE',"{$search}")
                                ->orWhere(DB::raw("TRIM(LEADING '0' FROM m.new_ic)"), 'LIKE',"{$search}")
                                ->orWhere(DB::raw("TRIM(LEADING '0' FROM m.employee_id)"), 'LIKE',"{$search}")
								->orWhere('m.old_ic', 'LIKE',"{$search}")
								->orWhere('m.new_ic', 'LIKE',"{$search}")
								->orWhere('m.employee_id', 'LIKE',"{$search}")
                                ->orWhere(DB::raw("TRIM(LEADING '0' FROM m.new_ic)"), 'LIKE',"{$search}")
                                ->orWhere(DB::raw("TRIM(LEADING '0' FROM m.employee_id)"), 'LIKE',"{$search}")
                                ->orWhere('com.short_code', 'LIKE',"%{$search}%")
                                ->orWhere('st.state_name', 'LIKE',"%{$search}%")
                                ->orWhere('cit.city_name', 'LIKE',"%{$search}%")
                                ->orWhere('m.levy', 'LIKE',"%{$search}%")
                                ->orWhere('m.levy_amount', 'LIKE',"%{$search}%")
                                ->orWhere('m.tdf', 'LIKE',"%{$search}%")
                                ->orWhere('m.tdf_amount', 'LIKE',"%{$search}%")
                               // ->orWhere('m.email', 'LIKE',"%{$search}%")
                                ->orWhere('m.mobile', 'LIKE',"{$search}")
                                ->orWhere('r.short_code', 'LIKE',"%{$search}%");
                                //->orWhere('c.branch_name', 'LIKE',"%{$search}%")
                                //->orWhere('s.status_name', 'LIKE',"%{$search}%");
                            });
			if( $limit != -1){
				$compQuery = $compQuery->offset($start)
				->limit($limit);
			}
			$memberslist = $compQuery
			->orderBy($order,$dir)
			->get()->toArray();

             $totalFiltered = $compQuery->count();

           
          
    }
	$data = array();
        if(!empty($memberslist))
        {
            foreach ($memberslist as $member)
            {
                $nestedData['member_number'] = $member->member_number;
                $nestedData['name'] = $member->name;
                $designation = $member->designation_name[0];
                $nestedData['designation_id'] = $designation;
                $gender = $member->gender[0];
                $nestedData['gender'] = $gender;
                $nestedData['short_code'] = $member->short_code;
                $nestedData['branch_name'] = $member->branch_name;
            
                $nestedData['levy'] = $member->levy;
                $nestedData['levy_amount'] = $member->levy_amount;
                $nestedData['tdf'] = $member->tdf;
                $nestedData['tdf_amount'] = $member->tdf_amount;
                $nestedData['doj'] = $member->doj;
                $nestedData['city_id'] = $member->city_name;
                $nestedData['state_id'] = $member->state_name;
                $nestedData['old_ic'] = $member->old_ic; 
                $nestedData['new_ic'] = $member->new_ic;
                $nestedData['mobile'] = $member->mobile;
                $nestedData['race_id'] = $member->raceshortcode;
                $nestedData['status'] = $member->status_name;
                $font_color = $member->font_color;
                $nestedData['font_color'] = $font_color;
                
                $enc_id = Crypt::encrypt($member->id);
                $delete = "";
                               
                
                $view = route('master.viewmembership', [app()->getLocale(),$enc_id]);
                $histry = route('member.history', [app()->getLocale(),$enc_id]);
                
                if($user_role=='union-branch'){
                    $edit = route('union.editmembership', [app()->getLocale(),$enc_id]);
                    $actions ="<a style='' id='$edit' onClick='showeditForm();' title='Edit' class='btn-sm waves-effect waves-light cyan modal-trigger' href='$edit'><i class='material-icons'>edit</i></a>";
                }else{
                    $edit = route('master.editmembership', [app()->getLocale(),$enc_id]);
                    $actions ="<a style='' id='$edit' onClick='showeditForm();' title='Edit' class='btn-sm waves-effect waves-light cyan modal-trigger' href='$edit'><i class='material-icons'>edit</i></a>";
                }

                if($user_role=='union'){
                    $actions .="<a style='margin-left: 10px;' title='View' class='btn-sm waves-effect waves-light purple modal-trigger' href='$view'><i class='material-icons'>remove_red_eye</i></a>";
                }
                
               // $actions ="<a style='float: left;' id='$edit' onClick='showeditForm();' title='Edit' class='modal-trigger' href='$edit'><i class='material-icons' style='color:#2196f3'>edit</i></a>";

                //DB::enableQueryLog();
                $history_list = DB::table('mon_sub_member')
                                    ->where('MemberCode','=',$member->id)->get();
									
				$ircstatus = CommonHelper::get_irc_confirmation_status($member->id);
				$irc_env = CommonHelper::getIRCVariable();
              
                           
                if(count($history_list)!=0)
                {
                   // $actions .="<a style='margin-left: 10px;' title='History'  class='' href='$histry'><i class='material-icons' style='color:#FF69B4;'>history</i></a>";
                }
                if($user_role=='union' || $user_role=='data-entry'){
                    $actions .="<a style='margin-left: 10px;' title='Payment History'  class='btn-sm waves-effect waves-light amber darken-4' href='$histry'><i class='material-icons'>history</i></a>";
                }
				
                $baseurl = URL::to('/');
               
                $member_transfer_link = $baseurl.'/'.app()->getLocale().'/member_transfer?member_id='.Crypt::encrypt($member->id).'&branch_id='.Crypt::encrypt($member->branch_id);

                $doj_url = date('d/m/Y',strtotime($member->doj));
                $member_card_link = $baseurl.'/'.app()->getLocale().'/get-new-members-print??offset=0&from_date='.$doj_url.'&to_date='.$doj_url.'&company_id=&branch_id=&member_auto_id='.$member->id.'&join_type=&unionbranch_id=&from_member_no=&to_member_no=';

                if($user_role=='union'){
                   $actions .="<a style='margin-left: 10px;' title='Member Transfer'  class='btn-sm waves-effect waves-light yellow darken-3' href='$member_transfer_link'><i class='material-icons' >transfer_within_a_station</i></a>";
                }

				if($ircstatus==1 && $irc_env){
                    $editmemberirc_link = $baseurl.'/'.app()->getLocale().'/membership-edit/'.$enc_id.'?status=1';
                    if($user_role=='union'){
                        $actions .= "<a style='margin-left: 10px;' title='IRC Details'  class='btn-sm waves-effect waves-light purple' href='$editmemberirc_link'><i class='material-icons' >confirmation_number</i></a>";
                    }
				}
				if($irc_env==false){
                    if($user_role=='union'){
					    $editmemberirc_link = $baseurl.'/'.app()->getLocale().'/membership-edit/'.$enc_id.'?status=2';
                        $actions .= "<a style='margin-left: 10px;' title='Resign Now'  class='btn-sm waves-effect waves-light red' href='$editmemberirc_link'><i class='material-icons' >block</i></a>";
                    }
                }
                if($user_role=='union'){
                    $actions .= "<a style='margin-left: 10px;' title='Card print'  class='btn-sm waves-effect waves-light blue' target='_blank' href='$member_card_link'><i class='material-icons' >card_membership</i></a>";
                }
                
               
                //$data = $this->CommonAjaxReturn($city, 0, 'master.citydestroy', 0);
                $nestedData['options'] = $actions;
                $data[] = $nestedData;

            }
        }
         $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );

        echo json_encode($json_data); 
    } 
	
	public function memberTransfer(Request $request){
        $request_data = $request->all();
        if(!empty($request_data)){
            $enc_member_id = $request->input('member_id');
            $enc_branch_id = $request->input('branch_id');
            $data['member_id'] = Crypt::decrypt($enc_member_id);
            $data['branch_id'] = Crypt::decrypt($enc_branch_id);
            $data['member_data'] = Membership::find($data['member_id']);
            $branch_info = CompanyBranch::find($data['branch_id']);
            $branchdata = [];
            if(!empty($branch_info)){
                $branchdata = $branch_info;
                $companyid = CommonHelper::getcompanyidbyBranchid($branch_info->id);
                $branchdata['country_name'] = CommonHelper::getCountryName($branch_info->country_id);
                $branchdata['state_name'] =  CommonHelper::getstateName($branch_info->state_id);
                $branchdata['city_name'] =  CommonHelper::getcityName($branch_info->city_id);
                $branchdata['company_name'] =  CommonHelper::getCompanyName($companyid);
            }
            $data['current_branch_data'] = $branchdata;
        }
      
        $data['country_view'] = DB::table('country')->select('id','country_name')->where('status','=','1')->get();
        $data['company_view'] = DB::table('company')->where('status','=','1')->get();
		return view('membership.member_transfer')->with('data',$data); 
    }
    
    public function getAutomemberslist(Request $request){
        $searchkey = $request->input('serachkey');
        $search = $request->input('query');
        //DB::enableQueryLog();
        $res['suggestions'] = DB::table('membership as m')->select(DB::raw('CONCAT(m.name, " - ", m.member_number) AS value'),'m.id as number','m.branch_id as branch_id','m.member_number as member_code')      
                            ->where(function($query) use ($search){
                                $query->orWhere('m.id','LIKE',"%{$search}%")
                                    ->orWhere('m.member_number', 'LIKE',"%{$search}%")
                                    ->orWhere('m.new_ic', 'LIKE',"%{$search}%")
                                    ->orWhere('m.old_ic', 'LIKE',"%{$search}%")
                                    ->orWhere('m.employee_id', 'LIKE',"%{$search}%")
                                    ->orWhere('m.name', 'LIKE',"%{$search}%");
                            })->limit(25)
                            ->get();        
        //$queries = DB::getQueryLog();
                            //  dd($queries);
         return response()->json($res);
    }

    public function getBranchDetails(Request $request){
        $branchid = $request->branchid;
        $branch_info = CompanyBranch::find($branchid);
        $return_data = ['status' => 1,'data' => []];
        if(!empty($branch_info)){
            $data = $branch_info;
            $companyid = CommonHelper::getcompanyidbyBranchid($branch_info->id);
            $data['country_name'] = CommonHelper::getCountryName($branch_info->country_id);
            $data['state_name'] =  CommonHelper::getstateName($branch_info->state_id);
            $data['city_name'] =  CommonHelper::getcityName($branch_info->city_id);
            $data['company_name'] =  CommonHelper::getCompanyName($companyid);
            //$data['branch_name'] =  $branch_info->branch_name;
            $return_data = ['status' => 1,'data' => $data];
        }
        echo json_encode($return_data);
    }

    public function ChangeMemberBranch(Request $request){
       $member_id = $request->input('transfer_member_code');
       $old_branch_id = $request->input('transfer_member_branch_id');
       $new_branch_id = $request->input('new_branch');
       $transfer_date = $request->input('transfer_date');
       if($transfer_date!=""){
            $fmmm_date = explode("/",$transfer_date);
            $fmdate = $fmmm_date[2]."-".$fmmm_date[1]."-".$fmmm_date[0];
            $transfer_date = date('Y-m-d', strtotime($fmdate));
       }else{
            $transfer_date = date('Y-m-d');
       }
      
      //return $request->all();
      // DB::enableQueryLog();
       if($old_branch_id!= $new_branch_id){
            $member_data = Membership::where('id', '=', $member_id)->where('branch_id', '=', $old_branch_id)->update(array('branch_id' => $new_branch_id));
           
            if($member_data){
                DB::table('member_transfer_history')->insert(
                    ['MemberCode' => $member_id, 'old_branch_id' => $old_branch_id, 'new_branch_id' => $new_branch_id, 'transfer_date' => $transfer_date, 'created_by' => Auth::user()->id, 'created_at' => date('Y-m-d')]
                );
                return redirect(app()->getLocale().'/transfer_history')->with('message','Member Transfered Succesfully');
            }else{
                return redirect(app()->getLocale().'/transfer_history')->with('error','Failed to transfer');
            }
        }else{
            return redirect(app()->getLocale().'/transfer_history')->with('error','Old branch and new branch should not same');
        }
    }


    public function memberTransferHistory(){
        return view('membership.member_transfer_history');
    }
	
	public function ajax_transfer_list(Request $request){
		$get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;
		$user_id = Auth::user()->id; 
        
        $datefilter = $request->input('datefilter');
        $memberid = $request->input('memberid');
        $dateformat = '';  
        $yearformat = '';  
        $monthformat = '';  
		 
        if(preg_match("^[a-z]{3}/[0-9]{4}^", $datefilter)==true || preg_match("^[A-Z]{3}/[0-9]{4}^", strtoupper($datefilter))==true ){
            $fm_date = explode("/",$datefilter);
            $dateformat = date('Y-m',strtotime('01-'.$fm_date[0].'-'.$fm_date[1]));
        }
        if(preg_match("^[a-z]{3}^", $datefilter)==true || preg_match("^[A-Z]{3}^", $datefilter)==true ){
            $fm_date = explode("/",$datefilter);
            $monthformat = date('m',strtotime('01-'.$fm_date[0].'-2019'));
        }	
        if(preg_match("^[0-9]{4}^", $datefilter)==true){
            $fm_date = explode("/",$datefilter);
            $yearformat = date('Y',strtotime('01-08-'.$fm_date[0]));
        }
        $sl=0;
		 $columns = array( 
            $sl++ => 'h.MemberCode', 
            $sl++ => 'm.member_number', 
            $sl++ => 'h.old_branch_id',
            $sl++ => 'h.new_branch_id',
            $sl++ => 'h.transfer_date',
            $sl++ => 'h.id',
        );
		$commonselect = DB::table('member_transfer_history as h')->select('m.name','h.old_branch_id','h.new_branch_id','h.transfer_date','h.id','h.MemberCode','m.member_number');
		$commoncount = DB::table('member_transfer_history as h');
		if($user_role=='union'){
			$commonselectqry = $commonselect->leftjoin('membership as m','m.id','=','h.MemberCode')->where(DB::raw('DATE_FORMAT(h.`transfer_date`,"%Y-%m")'), '=',"{$dateformat}");
			$commoncountqry = $commoncount->leftjoin('membership as m','m.id','=','h.MemberCode')->where(DB::raw('DATE_FORMAT(h.`transfer_date`,"%Y-%m")'), '=',"{$dateformat}");
		}else if($user_role=='union-branch'){
			$union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id');
            $union_branch_id_val = '';
			if(count($union_branch_id)>0){
				$union_branch_id_val = $union_branch_id[0];
				$commonselectqry = $commonselect->leftjoin('membership as m','m.id','=','h.MemberCode')
									->join('company_branch as c','c.id','=','m.branch_id')
									->where(DB::raw('DATE_FORMAT(h.`transfer_date`,"%Y-%m")'), '=',"{$dateformat}")
									->where([
										['c.union_branch_id','=',$union_branch_id_val]
										]);
				$commoncountqry = $commoncount->leftjoin('membership as m','m.id','=','h.MemberCode')
									->join('company_branch as c','c.id','=','m.branch_id')
									->where(DB::raw('DATE_FORMAT(h.`transfer_date`,"%Y-%m")'), '=',"{$dateformat}")
									->where([
										['c.union_branch_id','=',$union_branch_id_val]
										]);
               
			}
		}else if($user_role=='company'){
			$company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id');
			if(count($company_id)>0){
				$companyid = $company_id[0];
				$commonselectqry = $commonselect->leftjoin('membership as m','m.id','=','h.MemberCode')
									->join('company_branch as c','c.id','=','m.branch_id')
									->where(DB::raw('DATE_FORMAT(h.`transfer_date`,"%Y-%m")'), '=',"{$dateformat}")
									->where([
										['c.company_id','=',$companyid]
										]);
				$commoncountqry = $commoncount->leftjoin('membership as m','m.id','=','h.MemberCode')
									->join('company_branch as c','c.id','=','m.branch_id')
									->where(DB::raw('DATE_FORMAT(h.`transfer_date`,"%Y-%m")'), '=',"{$dateformat}")
									->where([
										['c.company_id','=',$companyid]
										]);
			}
		}else if($user_role=='company-branch'){
			$branch_id = CompanyBranch::where('user_id',$user_id)->pluck('id');
			if(count($branch_id)>0){
				$branchid = $branch_id[0];
                $commonselectqry = $commonselect->leftjoin('membership as m','m.id','=','h.MemberCode')
									->join('company_branch as c','c.id','=','m.branch_id')
									->where(DB::raw('DATE_FORMAT(h.`transfer_date`,"%Y-%m")'), '=',"{$dateformat}")
									->where([
										['m.branch_id','=',$branchid]
										]);
				$commoncountqry = $commoncount->leftjoin('membership as m','m.id','=','h.MemberCode')
									->join('company_branch as c','c.id','=','m.branch_id')
									->where(DB::raw('DATE_FORMAT(h.`transfer_date`,"%Y-%m")'), '=',"{$dateformat}")
									->where([
										['m.branch_id','=',$branchid]
										]);
			}
        }
		$totalData = $commoncountqry->count();

        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        

        if(empty($request->input('search.value')))
        {            
            if( $limit == -1){
                $historylist = $commonselectqry;
                if($memberid!=""){
                    $historylist = $commonselectqry->where('m.id','=',$memberid);
                }
                $historylist = $commonselectqry->get();
            }else{
                $historylist = $commonselectqry;
                if($memberid!=""){
                    $historylist = $commonselectqry->where('m.id','=',$memberid);
                }
                $historylist = $historylist->offset($start)
                                            ->limit($limit)
                                            ->orderBy($order,$dir)
                                            ->get();
            }
        
        }
        else {
			$search = $request->input('search.value'); 
		  
				
			$historylist =  $commonselectqry->leftjoin('company_branch as cb','cb.id','=','h.old_branch_id')
									->leftjoin('company_branch as cbone','cbone.id','=','h.new_branch_id');
								   
                                //->orWhere(DB::raw('year(s.Date)'), '=',"%{$yearformat}%")
                                $historylist = $commonselectqry;
								if($memberid!=""){
                                    $historylist = $historylist->where('m.id','=',$memberid);
                                }
								
								if( $limit != -1){
									$historylist = $historylist->offset($start)->limit($limit);
								}
								$historylist = $historylist->orderBy($order,$dir)
									->get();

			$historycount = $commoncountqry->leftjoin('company_branch as cb','cb.id','=','h.old_branch_id')
								 ->leftjoin('company_branch as cbone','cbone.id','=','h.new_branch_id');
                                
                                if($memberid!=""){
                                    $historycount = $historycount->where('m.id','=',$memberid);
                                }
								//->orWhere(DB::raw('year(s.Date)'), '=',"%{$yearformat}%")
							   
								$totalFiltered = $historycount->count();
        }
        
        $data = array();
        if(!empty($historylist))
        {
            foreach ($historylist as $company)
            {
				
                //$nestedData['month_year'] = date('M/Y',strtotime($company->name));
                $nestedData['member_name'] = $company->name;
                $nestedData['member_number'] = $company->member_number;
                $nestedData['frombank'] = CommonHelper::getBranchName($company->old_branch_id);
                $nestedData['tobank'] = CommonHelper::getBranchName($company->new_branch_id);
                $nestedData['transfer_date'] = $company->transfer_date!="0000-00-00" ? date('d/M/Y', strtotime($company->transfer_date)) : '';
                $company_enc_id = Crypt::encrypt($company->id);
                $editurl =  route('subscription.members', [app()->getLocale(),$company_enc_id]) ;
                $is_last_transfer = CommonHelper::ChecklastTranfer($company->id,$company->MemberCode);
                if($is_last_transfer==1){
                    $editurl = URL::to('/')."/en/sub-company-members/".$company_enc_id;
                    $baseurl = URL::to('/');
                    $member_transfer_link = $baseurl.'/'.app()->getLocale().'/edit_member_transfer?history_id='.Crypt::encrypt($company->id);
                    $actions ="<a style='float: left; margin-left: 10px;' title='Edit transfer'  class='btn-floating waves-effect waves-light amber darken-4' href='$member_transfer_link'><i class='material-icons'>transfer_within_a_station</i>Transfer</a>";
                }else{
                    $actions ='';
                }
               
				//$editurl = URL::to('/')."/en/sub-company-members/".$company_enc_id;
                $nestedData['options'] = $actions;
				$data[] = $nestedData;

			}
        }
       
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );

        echo json_encode($json_data); 
    }
    
    public function editmemberTransfer(Request $request){
        $request_data = $request->all();
        if(!empty($request_data)){
            $enc_history_id = $request->input('history_id');
            $history_id = Crypt::decrypt($enc_history_id);
            $historydata = DB::table('member_transfer_history')->where('id','=',$history_id)->first();
            $data['member_id'] = $historydata->MemberCode;
            $data['old_branch_id'] = $historydata->old_branch_id;
            $data['to_branch_id'] = $historydata->new_branch_id;
           
            $data['historydata'] = $historydata;
            $data['member_data'] = Membership::find($data['member_id']);
            $branch_info = CompanyBranch::find($data['old_branch_id']);
            $branchdata = [];
            if(!empty($branch_info)){
                $branchdata = $branch_info;
                $companyid = CommonHelper::getcompanyidbyBranchid($branch_info->id);
                $branchdata['country_name'] = CommonHelper::getCountryName($branch_info->country_id);
                $branchdata['state_name'] =  CommonHelper::getstateName($branch_info->state_id);
                $branchdata['city_name'] =  CommonHelper::getcityName($branch_info->city_id);
                $branchdata['company_name'] =  CommonHelper::getCompanyName($companyid);
            }
            $data['current_branch_data'] = $branchdata;
        }
      
        $data['country_view'] = DB::table('country')->select('id','country_name')->where('status','=','1')->get();
        $data['company_view'] = DB::table('company')->where('status','=','1')->get();
        $to_branch_info = CompanyBranch::find($data['to_branch_id']);
        $tobranchdata = [];
        if(!empty($to_branch_info)){
            $tobranchdata = $to_branch_info;
            $data['to_company_id'] = CommonHelper::getcompanyidbyBranchid($data['to_branch_id']);
            $tobranchdata['country_name'] = CommonHelper::getCountryName($to_branch_info->country_id);
            $tobranchdata['state_name'] =  CommonHelper::getstateName($to_branch_info->state_id);
            $tobranchdata['city_name'] =  CommonHelper::getcityName($to_branch_info->city_id);
        }
        $data['to_branch_data'] = $tobranchdata;
        $data['to_branch_view'] = DB::table('company_branch')->where('company_id','=',$data['to_company_id'])->get();
		return view('membership.edit_member_transfer')->with('data',$data); 
    }

    public function updatememberTransfer(Request $request){
        $history_id = $request->input('history_id');
        $member_id = $request->input('transfer_member_code');
        $old_branch_id = $request->input('transfer_member_branch_id');
        $new_branch_id = $request->input('new_branch');
        $transfer_date = $request->input('transfer_date');
        if($transfer_date!=""){
                $fmmm_date = explode("/",$transfer_date);
                $fmdate = $fmmm_date[2]."-".$fmmm_date[1]."-".$fmmm_date[0];
                $transfer_date = date('Y-m-d', strtotime($fmdate));
        }else{
                $transfer_date = date('Y-m-d');
        }
        $historydata = DB::table('member_transfer_history')->where([
            ['id','=',$history_id],
            ['MemberCode','=',$member_id],
            ['old_branch_id','=',$old_branch_id],
            ['new_branch_id','=',$new_branch_id]
            ])->first();
        if(!empty($historydata)){
            $historyresult = DB::table('member_transfer_history')->where('id','=',$historydata->id)->update(['transfer_date' => $transfer_date]);
            return redirect(app()->getLocale().'/transfer_history')->with('message','Member Transfered Succesfully');
        }else{
            $historydata = DB::table('member_transfer_history')->where('id','=',$history_id)->first();
            if($old_branch_id!= $new_branch_id){
                $member_data = Membership::where('id', '=', $member_id)->update(array('branch_id' => $new_branch_id));
               
                if($member_data){
                    $historyresult = DB::table('member_transfer_history')
                                    ->where('id','=',$historydata->id)
                                    ->update(['MemberCode' => $member_id, 'old_branch_id' => $old_branch_id, 'new_branch_id' => $new_branch_id, 'transfer_date' => $transfer_date, 'updated_by' => Auth::user()->id, 'updated_at' => date('Y-m-d')]);
                    return redirect(app()->getLocale().'/transfer_history')->with('message','Member Transfered Succesfully');
                }else{
                    return redirect(app()->getLocale().'/transfer_history')->with('error','Failed to transfer');
                }
            }else{
                return redirect(app()->getLocale().'/transfer_history')->with('error','Old branch and new branch should not same');
            }
        }
       
       //return $request->all();
       // DB::enableQueryLog();
        
       
       
     }

     public function deletememberTransfer($lang,$enchistoryid){
        $history_id = Crypt::decrypt($enchistoryid);
        $historydata = DB::table('member_transfer_history')->where('id','=',$history_id)->first();
        $memberid = $historydata->MemberCode;
        $old_branch_id = $historydata->old_branch_id;
        $new_branch_id = $historydata->new_branch_id;
        $member_data = Membership::where('id', '=', $memberid)->where('branch_id', '=', $new_branch_id)->update(array('branch_id' => $old_branch_id));
        if($member_data){
            $historyresult = DB::table('member_transfer_history')
                            ->where('id','=',$history_id)
                            ->delete();
            return redirect(app()->getLocale().'/transfer_history')->with('message','Transfer deleted Succesfully');
        }else{
            return redirect(app()->getLocale().'/transfer_history')->with('error','Failed to delete');
        }
     }
	 
	public function getRelativename(Request $request){
		$member_id  = $request->input('member_id');
        $resign_claimer  = $request->input('resign_claimer');
        $getselfid = DB::table('relation')->where('relation_name','=','SELF')->pluck('id')->first();
        if($getselfid != $resign_claimer){
            $relativename = MemberNominees::where('relation_id','=',$resign_claimer)->where('member_id','=',$member_id)->pluck('nominee_name')->first();
        }else{
            $relativename = Membership::where('id', '=', $member_id)->pluck('name')->first();
        }
       echo json_encode($relativename);
	}

    public function resignPDF($lang,$encid){
        $memberid = Crypt::decrypt($encid);
        $member_data = Membership::find($memberid);
        $resign_data = Resignation::where('member_code','=',$memberid)->first();
        $data = [
                    'member_data' =>  $member_data,
                    'resign_data' =>  $resign_data,
                ];
        return view('membership.resign-status', $data)->with('message','member resigned successfully');
    }
    public function genresignPDF($lang,$encid){
        $memberid = Crypt::decrypt($encid);
        $member_data = Membership::find($memberid);
        $resign_data = Resignation::where('member_code','=',$memberid)->first();
        $data = [
                    'member_data' =>  $member_data,
                    'resign_data' =>  $resign_data,
                ];
        $pdf = PDF::loadView('membership.pdf_resign', $data);  
        return $pdf->download('resignation-'.$member_data->member_number.'.pdf');
    }
	
	public function ServiceYear(Request $request)
    {
         $resign = $request->input('resign_date');
         $doj_one = $request->input('doj');
		 
		 $resign_date = explode("/",$resign);
		 $doj = explode("/",$doj_one);
		 
		 
		 $resign_month = date('m', strtotime($resign_date[2]."-".$resign_date[1]."-".$resign_date[0]));
		 $doj_month = date('m', strtotime($doj[2]."-".$doj[1]."-".$doj[0]));
		 
		 $date1 = Carbon::createMidnightDate($resign_date[2], $resign_month, $resign_date[0]);
         $date2 = Carbon::createMidnightDate($doj[2], $doj_month, $doj[0]);
         
         $rdate1 = Carbon::createMidnightDate($doj[2], $doj_month, $doj[0]);
         $rdate2 = Carbon::createMidnightDate('2017', '05', '31');
		 
		 /* $resign_dates = $resign_date[2]."-".$resign_date[1]."-".$resign_date[0];
         $resign_dates = date('Y-m-d 00:00:00', strtotime($resign_dates));
		 //dd($resign_dates);
		 
		 $dojs = $doj[2]."-".$doj[1]."-".$doj[0];
         $dojs = date('Y-m-d 00:00:00', strtotime($dojs));
		 // dd($dojs); */
		 
         $years = $date2->diffInYears($date1);
         $byears = $rdate2->diffInYears($rdate1);

         $data = ['status' => 1, 'service_year' => $years, 'benifit_year' => $byears];
         echo json_encode($data);
    }

    public function getBFAmount(Request $request){
        $service_year = $request->input('service_year');
        $resign_reason = $request->input('resign_reason');
        $resign_date = $request->input('resign_date');
        $auto_id = $request->input('auto_id');
        $doj = $request->input('doj');
        $fmmm_date = explode("/",$doj);           
        $resignfrom_date = explode("/",$resign_date);  	
        $dojtime = strtotime($fmmm_date[2]."-".$fmmm_date[1]."-".$fmmm_date[0]);
        $resigndate = date('Y-m-01',strtotime($resignfrom_date[2]."-".$resignfrom_date[1]."-01"));
        $bftime = strtotime('2017-05-31');
        $beforemay = 0;
        if($dojtime<=$bftime){
            $beforemay = 1;
        }
        $memberstatus =  DB::table('membermonthendstatus as ms')->where('StatusMonth', '=',$resigndate)->where('MEMBER_CODE', '=',$auto_id)->pluck('STATUS_CODE')->first();
        $bfamount = 0; 
        if($service_year>0 && $resign_reason!='' && $beforemay==1 && $memberstatus==1){
            $reasondata =  Reason::where('id',$resign_reason)->first();
            $five_year_amount = $reasondata->five_year_amount;
            $fiveplus_year_amount = $reasondata->fiveplus_year_amount;
            $one_year_amount = $reasondata->one_year_amount;
            $minimum_year = $reasondata->minimum_year;
            $minimum_refund = $reasondata->minimum_refund;
            $maximum_refund = $reasondata->maximum_refund;
            $bfamount = 0; 
            if($reasondata->is_benefit_valid==1){
                if($reasondata->reason_name=='DECEASED'){
                    if($service_year>=$minimum_year){
                        $fiveplusyears =$service_year - $minimum_year; 
                        $paid_amount = ($fiveplusyears*$fiveplus_year_amount)+$five_year_amount;
                        if($paid_amount<=$minimum_refund){
                            $bfamount = $minimum_refund; 
                        }else if($paid_amount>$minimum_refund && $paid_amount<$maximum_refund){
                            $bfamount = $paid_amount; 
                        }else{
                            $bfamount = $maximum_refund; 
                        }
                    }
                }else if($reasondata->reason_name=='MEDICAL GROUND'){
                    if($service_year>=$minimum_year){
                        if($service_year>=5){
                            $fiveplusyears =$service_year - 5; 
                            $paid_amount = ($fiveplusyears*$fiveplus_year_amount)+$five_year_amount;
                        }else{
                            $paid_amount = $service_year*$one_year_amount;
                        }
                        //$paid_amount = $service_year*$one_year_amount;
                        if($paid_amount>=$maximum_refund){
                            $bfamount = $maximum_refund;
                        }else{
                            $bfamount = $paid_amount;
                        }
                       
                    }
                }else{
                    if($service_year>=$minimum_year){
                        if($service_year>=5){
                            $fiveplusyears =$service_year - 5; 
                            $paid_amount = ($fiveplusyears*$fiveplus_year_amount)+$five_year_amount;
                        }else{
                            $paid_amount = $service_year*$one_year_amount;
                        }
                        //$paid_amount = $service_year*$one_year_amount;
                        if($paid_amount>=$maximum_refund){
                            $bfamount = $maximum_refund;
                        }else{
                            $bfamount = $paid_amount;
                        }
                       
                    }
                }
            }
            
        }else{
            $bfamount = 0; 
        }
        echo $bfamount;
    }

    public function MembersNewPrint(Request $request){
        $offset = $request->input('offset');
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $company_id = $request->input('company_id');
        $branch_id = $request->input('branch_id');
        $member_auto_id = $request->input('member_auto_id');
        $unionbranch_id = $request->input('unionbranch_id');
        $from_member_no = $request->input('from_member_no');
        $to_member_no = $request->input('to_member_no');
        $join_type = $request->input('join_type');
        $fromdate = CommonHelper::ConvertdatetoDBFormat($from_date);
        $todate = CommonHelper::ConvertdatetoDBFormat($to_date);

        $members = DB::table('membership as m')->select('c.id as cid','c.union_branch_id as unbid','m.name', 'm.member_number',DB::raw('IF(`d`.`designation_name`="CLERICAL","C","N") AS designation_name')
        ,'m.gender'
        ,'com.company_name'
        ,'m.doj'
        ,DB::raw('IF(`m`.`new_ic`=Null,`m`.`old_ic`,`m`.`new_ic`) as ic')
        ,DB::raw('IF(`m`.`levy`="Not Applicable","N/A",`m`.`levy`) as levy'),'m.levy_amount','m.tdf','m.tdf_amount'
        ,DB::raw('CONCAT( `com`.`short_code`, "/",  `c`.`branch_shortcode` ) AS companycode'),'c.branch_name as branch_name','mp.last_paid_date','c.address_one','c.address_two','city.city_name','c.postal_code')
            ->leftjoin('company_branch as c','c.id','=','m.branch_id')
            ->leftjoin('company as com','com.id','=','c.company_id')
            ->leftjoin('status as s','s.id','=','m.status_id')
            ->leftjoin('city as city','city.id','=','c.city_id')
            ->leftjoin('designation as d','m.designation_id','=','d.id')
            ->leftjoin('member_payments as mp','m.id','=','mp.member_id');
            if($fromdate!="" && $todate!=""){
                $members = $members->where(DB::raw('date(m.`doj`)'),'>=',$fromdate);
                $members = $members->where(DB::raw('date(m.`doj`)'),'<=',$todate);
            }
            if($branch_id!=""){
                $members = $members->where('m.branch_id','=',$branch_id);
            }else{
                if($unionbranch_id!=''){
                  $members = $members->where('c.union_branch_id','=',$unionbranch_id);
                }
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
            if($from_member_no!="" && $to_member_no!=""){
                  $members = $members->where('m.member_number','>=',$from_member_no);
                  $members = $members->where('m.member_number','<=',$to_member_no);
            }
            $members = $members->orderBy('m.member_number','asc');
        $members = $members->get();
        $data['member_view'] = $members;

      // dd($data);
        return view('membership.card_membership')->with('data',$data);  
    }

    public function MembersNewBackPrint(Request $request)
    {
        $offset = $request->input('offset');
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $company_id = $request->input('company_id');
        $branch_id = $request->input('branch_id');
        $member_auto_id = $request->input('member_auto_id');
        $unionbranch_id = $request->input('unionbranch_id');
        $from_member_no = $request->input('from_member_no');
        $to_member_no = $request->input('to_member_no');
        $join_type = $request->input('join_type');
        $fromdate = CommonHelper::ConvertdatetoDBFormat($from_date);
        $todate = CommonHelper::ConvertdatetoDBFormat($to_date);

        $members = DB::table('membership as m')->select('m.id')
             ->leftjoin('company_branch as c','c.id','=','m.branch_id')
            ->leftjoin('company as com','com.id','=','c.company_id')
            ->leftjoin('status as s','s.id','=','m.status_id')
            ->leftjoin('city as city','city.id','=','m.city_id')
            ->leftjoin('designation as d','m.designation_id','=','d.id')
            ->leftjoin('member_payments as mp','m.id','=','mp.member_id');
            if($fromdate!="" && $todate!=""){
                $members = $members->where(DB::raw('date(m.`doj`)'),'>=',$fromdate);
                $members = $members->where(DB::raw('date(m.`doj`)'),'<=',$todate);
            }
            if($branch_id!=""){
                $members = $members->where('m.branch_id','=',$branch_id);
            }else{
                if($unionbranch_id!=''){
                  $members = $members->where('c.union_branch_id','=',$unionbranch_id);
                }
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
            if($from_member_no!="" && $to_member_no!=""){
                  $members = $members->where('m.member_number','>=',$from_member_no);
                  $members = $members->where('m.member_number','<=',$to_member_no);
            }
            $members = $members->orderBy('m.member_number','asc');
        $members = $members->get();
        $data['member_view'] = $members;

       
        return view('membership.card_back_membership')->with('data',$data); 
    }

    public function viewMember(Request $request, $lang,$id){
        $irc_status = 0;
        $resign_status = 0;
        
        $id = Crypt::decrypt($id);
        $data['member_view'] = DB::table('membership')->select('membership.id as mid','membership.member_title_id','membership.member_number','membership.name','membership.gender','membership.designation_id','membership.email','membership.mobile',
        'membership.country_id','membership.state_id','membership.city_id','membership.address_one','membership.address_two','membership.address_three','membership.race_id','membership.old_ic','membership.new_ic',
        'membership.dob','membership.doj','membership.doe','membership.postal_code','membership.salary','membership.status_id','branch_id','membership.password','membership.user_type','membership.status','country.id','country.country_name','country.status','state.id','state.state_name','state.status',
        'city.id','city.city_name','city.status','company_branch.id','company_branch.branch_name','company_branch.status','designation.id','designation.designation_name','designation.status','race.id','race.race_name','race.status','persontitle.id','persontitle.person_title','persontitle.status','membership.old_member_number','membership.employee_id','membership.is_request_approved',
        'membership.levy','membership.levy_amount','membership.tdf','membership.tdf_amount','membership.current_salary','membership.last_update','membership.approval_status','membership.approval_reason','membership.designation_new_id','membership.designation_others','membership.approved_by')
        ->leftjoin('country','membership.country_id','=','country.id')
        ->leftjoin('state','membership.state_id','=','state.id')
        ->leftjoin('city','membership.city_id','=','city.id')
        ->leftjoin('company_branch','membership.branch_id','=','company_branch.id')
        ->leftjoin('persontitle','membership.member_title_id','=','persontitle.id')
        ->leftjoin('race','membership.race_id','=','race.id')
        ->leftjoin('designation','membership.designation_id','=','designation.id')
        ->where([
        ['membership.id','=',$id]
        ])->get();

        //     $queries = DB::getQueryLog();
        //   dd($queries);

        $country_id = $data['member_view'][0]->country_id;

        $state_id = $data['member_view'][0]->state_id;
        $city_id = $data['member_view'][0]->city_id;

        $company_id = CommonHelper::get_branch_company_id($data['member_view'][0]->branch_id);
        //$company_id = $data['member_view'][0]->company_id;
        $data['status_view'] = DB::table('status')->where('status','=','1')->get();
        $data['company_view'] = DB::table('company')->select('id','company_name')->where('status','=','1')->get();
        $data['state_view'] = DB::table('state')->select('id','state_name')->where('status','=','1')->where('country_id','=',$country_id)->get();
        $data['city_view'] = DB::table('city')->select('id','city_name')->where('status','=','1')->where('state_id','=',$state_id)->get();
        $data['country_view'] = DB::table('country')->select('id','country_name')->where('status','=','1')->get();
        $data['branch_view'] = DB::table('company_branch')->where('status','=','1')->where('company_id', $company_id)->get();
        $data['title_view'] = DB::table('persontitle')->where('status','=','1')->get();
        $data['designation_view'] = DB::table('designation')->where('status','=','1')->get();
        $data['race_view'] = DB::table('race')->where('status','=','1')->get();
        $data['relationship_view'] = DB::table('relation')->where('status','=','1')->get();
        $data['nominee_view'] = DB::table('member_nominees')->where('status','=','1')->where('member_id','=',$id)->get();
        $data['gardian_view'] = DB::table('member_guardian')->where('status','=','1')->where('member_id','=',$id)->get();

        $data['fee_list'] = DB::table('fee')->where('status','=','1')->get();
        $data['irc_status'] = $irc_status;
        $data['resign_status'] = $resign_status;
        $data['view_status'] = 1;

        $data['fee_view'] = DB::table('member_fee')->where('status','=','1')->where('member_id','=',$id)->get();
        return view('membership.edit_membership')->with('data',$data); 
    }

    public function SalaryUpload(Request $request, $lang){
        $data = [];
        return view('membership.salary_upload')->with('data',$data); 
    }

    public function getBankMembersList(Request $request, $lang){
        $sub_companyid = $request->input('sub_company');
        $member_auto_id = $request->input('member_auto_id');

        $members = DB::table('membership as m')->select('m.name','m.member_number','m.new_ic as icno','m.id as memberid')
                            ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
                            ->where('cb.company_id','=',$sub_companyid)
                            ->where('m.status_id','<=',2);

        if($member_auto_id!=''){
            $members = $members->where('m.id','=',$member_auto_id);
        }

        $data['members'] = $members->where('m.status','=','1')->get();
        $data['status'] = 1;
        
        return $data;
       // $data = [];
       // return view('membership.salary_upload')->with('data',$data); 
    }

    public function saveSalary(Request $request, $lang){
        //return $request->all();
        ini_set('memory_limit', -1);
		ini_set('max_execution_time', '1000');
        $datearr = explode("/",$request->input('entry_date'));
        $monthname = $datearr[0];
        $year = $datearr[1];
        $form_date = date('Y-m-d',strtotime('01-'.$monthname.'-'.$year));
        $sub_company = $request->input('sub_company');
        $is_increment = $request->input('is_increment');
        $is_incrementamt = $request->input('is_incrementamt');
        $inc_per = $request->input('inc_per');
        $memberids = $request->input('memberids');
        $is_increment == isset($is_increment) ? 1 : 0;
        $is_incrementamt == isset($is_incrementamt) ? 1 : 0;
        $inc_per = $inc_per=='' ? 0 : $inc_per;

        if($is_increment==1 || $is_incrementamt==1){
            if(isset($memberids)){
                $mcount = count($memberids);
                for($i=0;$i<$mcount;$i++){
                    $memberid = $memberids[$i];

                    $lastupdate = DB::table('salary_updations as s')->where('s.member_id','=',$memberid)
                                        ->where(DB::raw('DATE_FORMAT(s.date, "%Y-%m-%d")'),'<',$form_date)
                                        ->orderBy('s.date','desc')
                                        ->pluck('s.date')->first();

                    $lastsalary = DB::table('salary_updations as s')->where('s.member_id','=',$memberid)
                                        ->where(DB::raw('DATE_FORMAT(s.date, "%Y-%m-%d")'),'<',$form_date)
                                        ->orderBy('s.date','desc')
                                        ->pluck('s.basic_salary')->first();
                    
                    $basicsalry = DB::table('salary_updations as s')
                                        ->select(DB::raw("SUM(s.additional_amt) as additions"))
                                        ->where('s.member_id','=',$memberid)
                                        ->where(DB::raw('DATE_FORMAT(s.date, "%Y-%m-%d")'),'=',$lastupdate)
                                        ->where('s.increment_type_id','=',1)
                                        ->get();

                    if($lastupdate!=''){
                        $lastsalary = $lastsalary=='' ? 0 : $lastsalary;
                        $salary = $lastsalary+$basicsalry[0]->additions;
                    }else{
                        $salary = DB::table('membership as m')->select('m.salary')->where('m.id', '=', $memberid)->pluck('m.salary')->first();
                    }

                    $companyid = DB::table('membership as m')
                                ->select('c.company_id')
                                ->leftjoin('company_branch as c','c.id','=','m.branch_id')
                                ->where('m.id', '=', $memberid)->pluck('c.company_id')->first();

                   
                    $salary = $salary=='' ? 0 : $salary;
                    if($is_increment==1){
                        $additional_amt = ($salary*$inc_per/100);
                    }else{
                        $additional_amt = $inc_per;
                    }
                    
                    $newsalary = $salary+$additional_amt;

                    $salcount = DB::table('salary_updations')->where('member_id','=',$memberid)
                                        ->where(DB::raw('DATE_FORMAT(s.date, "%Y-%m-%d")'),'=',$form_date)
                                        ->where('increment_type_id','=',1)
                                        ->count();

                    if($salcount==0){
                        $insertdata = [];
                        $insertdata['member_id'] = $memberid;
                        $insertdata['date'] = $form_date;
                        $insertdata['company_id'] = $companyid;
                        $insertdata['increment_type_id'] = 1;
                        $insertdata['amount_type'] = 1;
                        $insertdata['basic_salary'] = $salary;
                        $insertdata['value'] = $inc_per;
                        $insertdata['updated_salary'] = $newsalary;
                        $insertdata['additional_amt'] = $additional_amt;
                        $insertdata['created_by'] = Auth::user()->id;

                        $savesal = DB::table('salary_updations')->insert($insertdata);
                        
                    }
                }
            }
        }else{
            if(isset($memberids)){
                $mcount = count($memberids);
                for($i=0;$i<$mcount;$i++){
                    $memberid = $memberids[$i];

                    $lastupdate = DB::table('salary_updations as s')->where('s.member_id','=',$memberid)
                                        ->where(DB::raw('DATE_FORMAT(s.date, "%Y-%m-%d")'),'<',$form_date)
                                        ->orderBy('s.date','desc')
                                        ->pluck('s.date')->first();
                    $lastsalary = DB::table('salary_updations as s')->where('s.member_id','=',$memberid)
                                        ->where(DB::raw('DATE_FORMAT(s.date, "%Y-%m-%d")'),'<',$form_date)
                                        ->orderBy('s.date','desc')
                                        ->pluck('s.basic_salary')->first();
                    
                    $basicsalry = DB::table('salary_updations as s')
                                        ->select(DB::raw("SUM(s.additional_amt) as additions"))
                                        ->where('s.member_id','=',$memberid)
                                        ->where(DB::raw('DATE_FORMAT(s.date, "%Y-%m-%d")'),'=',$lastupdate)
                                        ->where('s.increment_type_id','=',1)
                                        ->get();

                   

                    $companyid = DB::table('membership as m')
                                        ->select('c.company_id')
                                        ->leftjoin('company_branch as c','c.id','=','m.branch_id')
                                        ->where('m.id', '=', $memberid)->pluck('c.company_id')->first();

                    if($lastupdate!=''){
                        $lastsalary = $lastsalary=='' ? 0 : $lastsalary;
                        $salary = $lastsalary+$basicsalry[0]->additions;
                    }else{
                        $salary = DB::table('membership as m')->select('m.salary')->where('m.id', '=', $memberid)->pluck('m.salary')->first();
                    }

                    //$salary = DB::table('membership as m')->select('m.salary')->where('m.id', '=', $memberid)->pluck('m.salary')->first();
                    $salary = $salary=='' ? 0 : $salary;

                    $incvalue = $request->input('incvalueind_'.$memberid)[0];
                    $incidind = $request->input('incidind_'.$memberid)[0];
                    $typeidind = $request->input('typeidind_'.$memberid)[0];

                    if($incidind==1){
                        $additional_amt = ($salary*$incvalue)/100;
                        $newsalary = $salary+$additional_amt;
                    }else{
                        $additional_amt = $incvalue;
                        $newsalary = $salary+$incvalue;
                    }
                    

                    $salcount = DB::table('salary_updations')->where('member_id','=',$memberid)
                                        ->where(DB::raw('DATE_FORMAT(s.date, "%Y-%m-%d")'),'=',$form_date)
                                        ->where('increment_type_id','=',$typeidind)
                                        ->count();

                    if($salcount==0){
                        $insertdata = [];
                        $insertdata['member_id'] = $memberid;
                        $insertdata['date'] = $form_date;
                        $insertdata['company_id'] = $companyid;
                        $insertdata['increment_type_id'] = $typeidind;
                        $insertdata['amount_type'] = $incidind;
                        $insertdata['basic_salary'] = $salary;
                        $insertdata['value'] = $incvalue;
                        $insertdata['updated_salary'] = $newsalary;
                        $insertdata['additional_amt'] = $additional_amt;
                        $insertdata['created_by'] = Auth::user()->id;

                        $savesal = DB::table('salary_updations')->insert($insertdata);
                        
                    }
                }
            }
        }
        return redirect($lang.'/salary_upload')->with('message','Salary Updations Added successfully!!');
    }

    public function Salarylists(Request $request,$lang){
        $data = [];
        $data['types'] = '';
        $data['inctypes'] = DB::table('increment_types')->get();

        return view('membership.salary_list')->with('data',$data); 
    }

    public function getBankMembersSalaries(Request $request,$lang)
    {
        $sub_company = $request->input('sub_company');
        $member_auto_id = $request->input('member_auto_id');
        $entry_date = $request->input('entry_date');
        $branch_id = $request->input('branch_id');
        $types = $request->input('types');

        $datearr = explode("/",$entry_date);
        $monthname = $datearr[0];
        $year = $datearr[1];
        $form_date = date('Y-m-d',strtotime('01-'.$monthname.'-'.$year));

        $date_str = strtotime($form_date);
       
        $members = DB::table('salary_updations as s')
                        ->select('m.name','m.member_number','m.new_ic as icno','m.id as memberid','s.basic_salary','s.updated_salary',DB::raw("SUM(s.additional_amt) as additions"),DB::raw($date_str.' as datestr'),'i.type_name')
                        //->select(DB::raw("SUM(s.additional_amt) as additions"))
                        ->leftjoin('membership as m','m.id','=','s.member_id')
                        ->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
                        ->leftjoin('increment_types as i','i.id','=','s.increment_type_id')
                        ->where('s.company_id','=',$sub_company)
                        ->where(DB::raw('DATE_FORMAT(s.date, "%Y-%m-%d")'),'=',$form_date);

        if($branch_id!=""){
            $members = $members->where('m.branch_id','=',$branch_id);
        }
        if($member_auto_id!=""){
            $members = $members->where('m.id','=',$member_auto_id);
        }
        if($types!=""){
            $members = $members->where('s.increment_type_id','=',$types);
        }

        $data['members'] = $members->groupBy('m.id')->get();
        $data['status'] = 1;
        
        return $data;
    }

    public function getMembersIncrements(Request $request,$lang)
    {
        $member_id = $request->input('member_id');
        $datestr = $request->input('datestr');
        
        $form_date = date('Y-m-d',$datestr);

        $date_str = strtotime($form_date);
       
        $members = DB::table('salary_updations as s')
                        ->select('s.basic_salary','s.updated_salary','s.additional_amt','s.increment_type_id','i.type_name')
                        //->select(DB::raw("SUM(s.additional_amt) as additions"))
                        ->leftjoin('increment_types as i','i.id','=','s.increment_type_id')
                        ->where('s.member_id','=',$member_id)
                        ->where(DB::raw('DATE_FORMAT(s.date, "%Y-%m-%d")'),'=',$form_date);

        $data['members'] = $members->get();
        $data['status'] = 1;
        
        return $data;
    }
    
    public function ListStateMembers(Request $request,$lang)
    {
        $data = [];
        $data['state_view'] = DB::table('state as s')->where('status','=',1)->where('country_id','=',130)->get();
        return view('membership.state_clear')->with('data',$data); 
    }

    public function getStateMembersList(Request $request, $lang){
        $from_city_id = $request->input('from_city_id');
        $from_state_id = $request->input('from_state_id');
        $company_id = $request->input('company_id');
        $branch_id = $request->input('branch_id');

        $members = DB::table('membership as m')->select('m.name','m.member_number','m.new_ic as icno','m.id as memberid','cb.branch_name','c.company_name')
                            ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
                            ->leftjoin('company as c','cb.company_id','=','c.id');

        if($from_state_id!=''){
            if($from_state_id=='empty'){
                $members = $members->where('m.state_id','=',0);
            }else{
               
                if($from_city_id=='empty'){
                    $members = $members->where('m.state_id','=',$from_state_id);
                    $members = $members->where('m.city_id','=',0);
                }
            }
           
        }

        if($from_city_id!=''){
            // if($from_city_id=='empty'){
            //     $members = $members->where('m.city_id','=',0);
            // }else{
                $members = $members->where('m.city_id','=',$from_city_id);
            //}
           
        }

        if($company_id!=''){
            $members = $members->where('c.id','=',$company_id);
        }

        if($branch_id!=''){
            $members = $members->where('cb.id','=',$branch_id);
        }

        $data['members'] = $members->where('m.status','=','1')
        ->limit(1000)->get();
        $data['status'] = 1;
        
        return $data;
       // $data = [];
       // return view('membership.salary_upload')->with('data',$data); 
    }

    public function UpdateStateCity(Request $request, $lang){
        $from_state_id = $request->input('from_state_id');
        $from_city_id = $request->input('from_city_id');
        $to_state_id = $request->input('to_state_id');
        $to_city_id = $request->input('to_city_id');
        if($from_city_id!=$to_city_id && $to_city_id!="" && $to_state_id!=""){
            $memberids = $request->input('memberids');
            //return $memberids;
            if(isset($memberids)){
                $mcount = count($memberids);
                $data = DB::table('membership')->whereIn('id',$memberids)->update(['state_id' => $to_state_id, 'city_id' => $to_city_id]);
                // for($i=0;$i<$mcount;$i++){
                //     $memberid = $memberids[$i];
                    
                // }
                return redirect($lang.'/clean-state')->with('message','State,city updated successfully!!');
            }
        }else{
            return redirect($lang.'/clean-state')->with('error','Please select correct to city!!');
        }
       
    }

    public function ListMembers(Request $request){
        $data['from_date'] = date('1940-01-01');
        $data['to_date'] = date('Y-m-d');
        $data['status_id'] = '';
        $data['status_view'] = DB::table('status')->where('status','=','1')->get();
       // $data['members_list'] = DB::table('membership as m')->where('m.doj','>=','2019-01-01')->orderBY('m.doj','asc')->get();
        return view('subscription.promemberlist')->with('data',$data);  
    }

    public function memberlevy(Request $request, $lang,$encid)
    {
        $id = Crypt::decrypt($encid);
        $data['member_view'] = DB::table('membership as m')->select('m.id as mid','m.member_title_id','m.member_number','m.name','cb.branch_name','c.company_name','s.status_name','m.doj','m.salary','m.levy','m.levy_amount','m.tdf','m.tdf_amount','m.levy_update_date')
                            ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
                            ->leftjoin('company as c','cb.company_id','=','c.id')
                            ->leftjoin('status as s','m.status_id','=','s.id')
                            ->where('m.id','=',$id)
                            ->first();
        return view('subscription.levy_update')->with('data',$data);  
    }

    public function UpdateLevy(Request $request, $lang)
    {
        $memberid = $request->input('memberid');
        $levy = $request->input('levy');
        $levy_amount = $request->input('levy_amount');
        $tdf = $request->input('tdf');
        $tdf_amount = $request->input('tdf_amount');
        $update = date('Y-m-d h:i:s');

        $data = DB::table('membership')->where('id','=',$memberid)->update(['levy' => $levy, 'levy_amount' => $levy_amount, 'tdf' => $tdf, 'tdf_amount' => $tdf_amount, 'levy_update_date' => $update]);

        return redirect($lang.'/clean-membershiplist')->with('message','Details updated successfully!!');
    }

    public function ListSalaryMembers(Request $request, $lang){
        $data = [];
        return view('membership.salary_clear')->with('data',$data); 
    }

    public function getSalaryMembersList(Request $request, $lang){
        $company_id = $request->input('company_id');
        $branch_id = $request->input('branch_id');

        $members = DB::table('membership as m')->select('m.name','m.member_number','m.new_ic as icno','m.id as memberid','cb.branch_name','c.company_name',DB::raw('`c`.`short_code` AS companycode'))
                            ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
                            ->leftjoin('company as c','cb.company_id','=','c.id');

        $members = $members->where('m.salary','=',0);

        if($company_id!=''){
            $members = $members->where('c.id','=',$company_id);
        }

        if($branch_id!=''){
            $members = $members->where('cb.id','=',$branch_id);
        }

        $data['members'] = $members->where('m.status','=','1')
        //->limit(1000)
        ->get();
        $data['status'] = 1;
        
        return $data;
       // $data = [];
       // return view('membership.salary_upload')->with('data',$data); 
    }

    public function updateSalary(Request $request, $lang){
        //return $request->all();
        ini_set('memory_limit', -1);
		ini_set('max_execution_time', '1000');
       
        $sub_company = $request->input('sub_company');
        $branch_id = $request->input('branch_id');
       
        $memberids = $request->input('memberids');
      
        if($sub_company!=''){
            if(isset($memberids)){
                $mcount = count($memberids);
                for($i=0;$i<$mcount;$i++){
                    $memberid = $memberids[$i];
                    //$data = DB::table('mon_sub_member')->where('id','=',$memberid)->orderBy('m.id','DESC')
                    $members_amt =  DB::table('mon_sub_member as m')->select('m.Amount')
                    ->leftjoin('mon_sub_company as sc','m.MonthlySubscriptionCompanyId','=','sc.id')
                    ->leftjoin('mon_sub as sm','sc.MonthlySubscriptionId','=','sm.id')
                    ->where('m.MemberCode','=',$memberid)
                    ->orderBy('sm.Date','DESC')
                    ->pluck('m.Amount')
                    ->first();
                    $salary = $members_amt!='' ? $members_amt*100 : 0;
                    if($salary!=0){
                        $data = DB::table('membership')->where('id','=',$memberid)->update(['salary' => $salary]);
                        //print_r(['salary' => $salary,'id' => $memberid]);
                    }
                   
                }
            }
        }
        return redirect($lang.'/clean-salary')->with('message','Salary updated successfully!!');
    }

    public function AjaxPendingmembersList(Request $request,$lang, $type){
       
        $member_status = $request->input('status');
        $sl=0;
        $columns[$sl++] = 'm.id';
        $columns[$sl++] = 'm.member_number';
        $columns[$sl++] = 'm.name';
        $columns[$sl++] = 'm.designation_id';
        $columns[$sl++] = 'm.gender'; 
        $columns[$sl++] = 'com.short_code';
        $columns[$sl++] = 'c.branch_name';
        $columns[$sl++] = 'm.levy';
        $columns[$sl++] = 'm.levy_amount';
        $columns[$sl++] = 'm.tdf';
        $columns[$sl++] = 'm.tdf_amount';
        $columns[$sl++] = 'm.doj';
        $columns[$sl++] = 'm.city_id';
        $columns[$sl++] = 'm.state_id';
        $columns[$sl++] = 'm.old_ic';
        $columns[$sl++] = 'm.new_ic';
		$columns[$sl++] = 'm.mobile';
		$columns[$sl++] = 'm.email';
        
        $columns[$sl++] = 'm.race_id';
		//if($type==1){
			$columns[$sl++] = 'm.status_id';
		//}
        
		if($type==1){
			$approved_cond = 1;
		}else{
			$approved_cond = 0;
		}
		
		$get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;
		$user_id = Auth::user()->id; 
		$member_qry = '';
		
		$unionbranch_id = $request->input('unionbranch_id'); 
		$company_id = $request->input('company_id'); 
		$branch_id = $request->input('branch_id'); 
		$gender = $request->input('gender'); 
		$race_id = $request->input('race_id'); 
		$status_id = $request->input('status_id'); 
		$country_id = $request->input('country_id'); 
		$state_id = $request->input('state_id');
        $city_id = $request->input('city_id'); 
        $pending_type = trim($request->input('pending_type')); 
        //return $pending_type;
		
		
		if($user_role=='union' || $user_role=='data-entry'){
            //DB::enableQueryLog();
				
			$member_qry = DB::table('membership as m')->select(DB::raw("IFNULL(m.levy, '---') AS levy"),'m.member_number','m.id as id','m.name','m.gender','m.designation_id','m.email','m.branch_id','m.status_id','m.doj','c.branch_name','c.id as companybranchid','com.id as companyid','com.company_name' ,'d.designation_name','m.old_ic','m.new_ic','m.mobile',DB::raw("IFNULL(st.state_name, '---') AS state_name"),'cit.id as cityid',DB::raw("IFNULL(cit.city_name, '---') AS city_name"),'st.id as stateid','m.state_id','m.city_id','m.race_id',DB::raw("IFNULL(m.levy_amount, '---') AS levy_amount"),DB::raw("IFNULL(m.tdf, '---') AS tdf"),DB::raw("IFNULL(m.tdf_amount, '---') AS tdf_amount"),'com.short_code','r.race_name','r.short_code as raceshortcode','s.status_name','s.font_color','con.country_name','m.approval_status')
						 ->leftjoin('designation as d','m.designation_id','=','d.id')
						 ->leftjoin('company_branch as c','m.branch_id','=','c.id')
						 ->leftjoin('company as com','com.id','=','c.company_id')
						 ->leftjoin('union_branch as ub','c.union_branch_id','=','ub.id')
						 ->leftjoin('status as s','s.id','=','m.status_id')
						 ->leftjoin('country as con','con.id','=','m.country_id')
						 ->leftjoin('state as st','st.id','=','m.state_id')
						 ->leftjoin('city as cit','cit.id','=','m.city_id')
						 ->leftjoin('race as r','r.id','=','m.race_id')
                         ->where('m.is_request_approved','=',$approved_cond)
                         ->orderBy('m.id','DESC');
			if($branch_id!=""){
				  $member_qry = $member_qry->where('m.branch_id','=',$branch_id);
			  }elseif($company_id!= ''){
				   $member_qry = $member_qry->where('c.company_id','=',$company_id);
			  }
			  elseif($unionbranch_id!= ''){
				  $member_qry = $member_qry->where('c.union_branch_id','=',$unionbranch_id);
			  }
			 if($gender!="")
			 {
			  	$member_qry = $member_qry->where('m.gender','=',$gender);
             }
			 if($race_id != "")
			 {
				 $member_qry = $member_qry->where('m.race_id','=',$race_id);
			 }
			 if($status_id!=0 && $status_id != "")
			 {
				 $member_qry = $member_qry->where('m.status_id','=',$status_id);
			 }
			 if($country_id != "")
			 {
				 $member_qry = $member_qry->where('m.country_id','=',$country_id);
			 }
			 if($state_id != "")
			 {
				 $member_qry = $member_qry->where('m.state_id','=',$state_id);
			 }
			 if($city_id != "")
			 {
				 $member_qry = $member_qry->where('m.city_id','=',$city_id);
             }
             
             if($pending_type != "")
			 {
                $member_qry = $member_qry->where('m.approval_status','=',$pending_type);
                 //return $pending_type;
			 }
			  
			if($member_status !='all'){
				$member_qry = $member_qry->where('m.status_id','=',$member_status);
			}
			//$member_qry->dump()->get();			 
			// $queries = DB::getQueryLog();
			// dd($queries);         
         
                        
		}else if($user_role=='union-branch'){
           
            $union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id');
            $union_branch_id_val = '';
			if(count($union_branch_id)>0){
                $union_branch_id_val = $union_branch_id[0];
                $member_qry = DB::table('company_branch as c')->select(DB::raw("IFNULL(m.levy, '---') AS levy"),'c.id as cid','m.name','m.email','m.id as id','m.status_id as status_id','m.branch_id as branch_id',
                'm.member_number','m.designation_id','d.id as designationid','d.designation_name','m.gender','com.company_name','m.doj','m.old_ic','m.new_ic','m.mobile',DB::raw("IFNULL(st.state_name, '---') AS state_name"),'cit.id as cityid',DB::raw("IFNULL(cit.city_name, '---') AS city_name"),'st.id as stateid','m.state_id','m.city_id','m.race_id',DB::raw("IFNULL(m.levy_amount, '---') AS levy_amount"),DB::raw("IFNULL(m.tdf, '---') AS tdf"),DB::raw("IFNULL(m.tdf_amount, '---') AS tdf_amount"),'com.short_code','r.race_name','r.short_code as raceshortcode','s.font_color','m.approval_status')
                ->join('membership as m','c.id','=','m.branch_id')
                ->leftjoin('company as com','com.id','=','c.company_id')
                ->leftjoin('union_branch as ub','c.union_branch_id','=','ub.id')
                ->leftjoin('status as s','s.id','=','m.status_id')
                ->leftjoin('designation as d','m.designation_id','=','d.id')
                ->leftjoin('country as con','con.id','=','m.country_id')
                ->leftjoin('state as st','st.id','=','m.state_id')
                ->leftjoin('city as cit','cit.id','=','m.city_id')
                ->leftjoin('race as r','r.id','=','m.race_id')
                ->orderBy('m.id','DESC')
                ->where([
                    ['c.union_branch_id','=',$union_branch_id_val],
                    ['m.is_request_approved','=',$approved_cond]
                    ]);
                	if($branch_id!=""){
                        $member_qry = $member_qry->where('m.branch_id','=',$branch_id);
                    }elseif($company_id!= ''){
                         $member_qry = $member_qry->where('c.company_id','=',$company_id);
                    }
                    elseif($unionbranch_id!= ''){
                        $member_qry = $member_qry->where('c.union_branch_id','=',$unionbranch_id);
                    }
                   if($gender!="")
                   {
                        $member_qry = $member_qry->where('m.gender','=',$gender);
                    }
                   if($race_id != "")
                   {
                       $member_qry = $member_qry->where('m.race_id','=',$race_id);
                   }
                   if($status_id!=0 && $status_id != "")
                   {
                       $member_qry = $member_qry->where('m.status_id','=',$status_id);
                   }
                   if($country_id != "")
                   {
                       $member_qry = $member_qry->where('m.country_id','=',$country_id);
                   }
                   if($state_id != "")
                   {
                       $member_qry = $member_qry->where('m.state_id','=',$state_id);
                   }
                   if($city_id != "")
                   {
                       $member_qry = $member_qry->where('m.city_id','=',$city_id);
                   }
                   if($pending_type != "")
                    {
                        $member_qry = $member_qry->where('m.approval_status','=',$pending_type);
                    }
                if($member_status!='all'){
                    $member_qry = $member_qry->where('m.status_id','=',$member_status);
                }
			}
		}else if($user_role=='company'){
			$company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id');
			if(count($company_id)>0){
				$companyid = $company_id[0];
                $member_qry = DB::table('company_branch as c')->select(DB::raw("IFNULL(m.levy, '---') AS levy"),'c.id as cid','m.name','m.email','m.id','m.mobile','m.status_id as status_id','m.branch_id as branch_id',
                              'm.member_number','m.designation_id','d.designation_name','m.gender','com.company_name','m.doj','m.old_ic','m.new_ic','m.mobile',DB::raw("IFNULL(st.state_name, '---') AS state_name"),'cit.id as cityid',DB::raw("IFNULL(cit.city_name, '---') AS city_name"),'st.id as stateid','m.state_id','m.city_id','m.race_id',DB::raw("IFNULL(m.levy_amount, '---') AS levy_amount"),DB::raw("IFNULL(m.tdf, '---') AS tdf"),DB::raw("IFNULL(m.tdf_amount, '---') AS tdf_amount"),'com.short_code','r.race_name as raceshortcode','s.font_color','m.approval_status')
                ->join('membership as m','c.id','=','m.branch_id')
                ->leftjoin('designation as d','m.designation_id','=','d.id')
                ->leftjoin('company as com','com.id','=','c.company_id')
                ->leftjoin('union_branch as ub','c.union_branch_id','=','ub.id')
                ->leftjoin('status as s','s.id','=','m.status_id')
                ->leftjoin('country as con','con.id','=','m.country_id')
                ->leftjoin('state as st','st.id','=','m.state_id')
                ->leftjoin('city as cit','cit.id','=','m.city_id')
                ->leftjoin('race as r','r.id','=','m.race_id')
                ->orderBy('m.id','DESC')
                ->where([
                    ['c.company_id','=',$companyid],
                    ['m.is_request_approved','=',$approved_cond]
                    ]);
                    if($branch_id!=""){
                        $member_qry = $member_qry->where('m.branch_id','=',$branch_id);
                    }elseif($company_id!= ''){
                         $member_qry = $member_qry->where('c.company_id','=',$company_id);
                    }
                    elseif($unionbranch_id!= ''){
                        $member_qry = $member_qry->where('c.union_branch_id','=',$unionbranch_id);
                    }
                   if($gender!="")
                   {
                        $member_qry = $member_qry->where('m.gender','=',$gender);
                    }
                   if($race_id != "")
                   {
                       $member_qry = $member_qry->where('m.race_id','=',$race_id);
                   }
                   if($status_id!=0 && $status_id != "")
                   {
                       $member_qry = $member_qry->where('m.status_id','=',$status_id);
                   }
                   if($country_id != "")
                   {
                       $member_qry = $member_qry->where('m.country_id','=',$country_id);
                   }
                   if($state_id != "")
                   {
                       $member_qry = $member_qry->where('m.state_id','=',$state_id);
                   }
                   if($city_id != "")
                   {
                       $member_qry = $member_qry->where('m.city_id','=',$city_id);
                   }
                   if($pending_type != "")
                    {
                        $member_qry = $member_qry->where('m.approval_status','=',$pending_type);
                    }
                if($member_status!='all'){
                    $member_qry = $member_qry->where('m.status_id','=',$member_status);
                }
			}
		}else if($user_role=='company-branch'){
			$branch_id = CompanyBranch::where('user_id',$user_id)->pluck('id');
			if(count($branch_id)>0){
				$branchid = $branch_id[0];
                $member_qry = DB::table('company_branch as c')->select(DB::raw("IFNULL(m.levy, '---') AS levy"),'c.id as cid','m.name','m.email','m.id','m.mobile','m.status_id as status_id','m.branch_id as branch_id',
                              'm.member_number','m.designation_id','d.designation_name','m.gender','com.company_name','m.doj','m.old_ic','m.new_ic','m.mobile','m.state_id','m.city_id','m.race_id','m.mobile',DB::raw("IFNULL(st.state_name, '---') AS state_name"),'cit.id as cityid',DB::raw("IFNULL(cit.city_name, '---') AS city_name"),'st.id as stateid','m.state_id','m.city_id','m.race_id',DB::raw("IFNULL(m.levy_amount, '---') AS levy_amount"),DB::raw("IFNULL(m.tdf, '---') AS tdf"),DB::raw("IFNULL(m.tdf_amount, '---') AS tdf_amount"),'com.short_code','r.race_name','r.short_code','m.approval_status')
                ->join('membership as m','c.id','=','m.branch_id')
                ->leftjoin('designation as d','m.designation_id','=','d.id')
                ->leftjoin('company as com','com.id','=','c.company_id')
                ->leftjoin('union_branch as ub','c.union_branch_id','=','ub.id')
                ->leftjoin('status as s','s.id','=','m.status_id')
                ->leftjoin('country as con','con.id','=','m.country_id')
                ->leftjoin('state as st','st.id','=','m.state_id')
                ->leftjoin('city as cit','cit.id','=','m.city_id')
                ->leftjoin('race as r','r.id','=','m.race_id')
                ->orderBy('m.id','DESC')
                ->where([
                    ['m.branch_id','=',$branchid],
                    ['m.is_request_approved','=',$approved_cond]
                    ]);
                    if($branch_id!=""){
                        $member_qry = $member_qry->where('m.branch_id','=',$branch_id);
                    }elseif($company_id!= ''){
                         $member_qry = $member_qry->where('c.company_id','=',$company_id);
                    }
                    elseif($unionbranch_id!= ''){
                        $member_qry = $member_qry->where('c.union_branch_id','=',$unionbranch_id);
                    }
                   if($gender!="")
                   {
                        $member_qry = $member_qry->where('m.gender','=',$gender);
                    }
                   if($race_id != "")
                   {
                       $member_qry = $member_qry->where('m.race_id','=',$race_id);
                   }
                   if($status_id!=0 && $status_id != "")
                   {
                       $member_qry = $member_qry->where('m.status_id','=',$status_id);
                   }
                   if($country_id != "")
                   {
                       $member_qry = $member_qry->where('m.country_id','=',$country_id);
                   }
                   if($state_id != "")
                   {
                       $member_qry = $member_qry->where('m.state_id','=',$state_id);
                   }
                   if($city_id != "")
                   {
                       $member_qry = $member_qry->where('m.city_id','=',$city_id);
                   }
                   if($pending_type != "")
                    {
                        $member_qry = $member_qry->where('m.approval_status','=',$pending_type);
                    }
                if($member_status!='all'){
                    $member_qry = $member_qry->where('m.status_id','=',$member_status);
                }
			}
        }
		$totalData = 0;
		if($member_qry!=""){
            $totalData = $member_qry->count();
           
		}
								
        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            //DB::enableQueryLog();
				$compQuery = DB::table('membership as m')
				->select(DB::raw("IFNULL(m.levy, '---') AS levy"),DB::raw("IFNULL(c.branch_name, '---') AS branch_name"),DB::raw("IFNULL(d.designation_name, '---') AS designation_name"),'c.id as cid','m.name','m.email','m.id as id','m.status_id as status_id','m.branch_id as branch_id','s.status_name as status_name','m.member_number','m.designation_id',DB::raw("IFNULL(m.gender, '---') AS gender"),DB::raw("IFNULL(com.company_name, '---') AS company_name"),DB::raw("IFNULL(m.doj, '---') AS doj"),DB::raw("IFNULL(m.old_ic, '---') AS old_ic"),DB::raw("IFNULL(m.new_ic, '---') AS new_ic"),DB::raw("IFNULL(st.state_name, '---') AS state_name"),'cit.id as cityid',DB::raw("IFNULL(cit.city_name, '---') AS city_name"),'st.id as stateid','m.state_id','m.city_id','m.race_id',DB::raw("IFNULL(m.levy_amount, '---') AS levy_amount"),DB::raw("IFNULL(m.tdf, '---') AS tdf"),DB::raw("IFNULL(m.tdf_amount, '---') AS tdf_amount"),DB::raw("IFNULL(com.short_code, '---') AS short_code"),DB::raw("IFNULL(m.mobile, '---') AS mobile"),DB::raw("IFNULL(r.race_name, '---') AS race_name"),DB::raw("IFNULL(r.short_code, '---') AS raceshortcode"),'s.status_name','s.font_color','m.approval_status')
                ->leftjoin('company_branch as c','c.id','=','m.branch_id')
                ->leftjoin('designation as d','m.designation_id','=','d.id')
                ->leftjoin('company as com','com.id','=','c.company_id')
                ->leftjoin('status as s','s.id','=','m.status_id')
                ->leftjoin('state as st','st.id','=','m.state_id')
                ->leftjoin('city as cit','cit.id','=','m.city_id')
                ->leftjoin('race as r','r.id','=','m.race_id')
                ->where('m.is_request_approved','=',$approved_cond);
                if($member_status!='all'){
                    $compQuery = $compQuery->where('m.status_id','=',$member_status);
                }
				if($user_role=='union-branch'){
					$compQuery =  $compQuery->where([
                    ['c.union_branch_id','=',$union_branch_id_val]
                    ]);
				}
				if($user_role=='company'){
					$compQuery =  $compQuery->where([
                    ['c.company_id','=',$companyid]
                    ]);
				}
				if($user_role=='company-branch'){
					$compQuery =  $compQuery->where([
                    ['m.branch_id','=',$branchid]
                    ]);
                }
                if($branch_id!=""){
                    $compQuery = $compQuery->where('m.branch_id','=',$branch_id);
                }elseif($company_id!= ''){
                     $compQuery = $compQuery->where('c.company_id','=',$company_id);
                }
                elseif($unionbranch_id!= ''){
                    $compQuery = $compQuery->where('c.union_branch_id','=',$unionbranch_id);
                }
               // $compQuery->dump()->get();
               if($gender!="")
               {
                    $compQuery = $compQuery->where('m.gender','=',$gender);
              }
               if($race_id != "")
               {
                   $compQuery = $compQuery->where('m.race_id','=',$race_id);
               }
            //    if($status_id!=0 && $status_id != "")
            //    {
            //        $compQuery = $compQuery->where('m.status_id','=',$status_id);
            //    }
               if($country_id != "")
               {
                   $compQuery = $compQuery->where('m.country_id','=',$country_id);
               }
               if($state_id != "")
               {
                   $compQuery = $compQuery->where('m.state_id','=',$state_id);
               }
               if($city_id != "")
               {
                   $compQuery = $compQuery->where('m.city_id','=',$city_id);
               }
               if($pending_type != "")
               {
                  //$member_qry = $compQuery->where('m.approval_reason','=',$pending_type);
                  $member_qry = $compQuery->where('m.approval_status', 'LIKE',"%{$pending_type}%");
                  // return $pending_type;
               }
                
            //   if($member_status !='all'){
            //       $compQuery = $compQuery->where('m.status_id','=',$member_status);
            //   }
                
			if( $limit != -1){
				$compQuery = $compQuery->offset($start)
				->limit($limit);
            }
           /*  if($order =='m.member_number'){
                $memberslist = $compQuery->orderBy('m.id','desc')
			        ->get()->toArray(); 
            }else{
                $memberslist = $compQuery->orderBy($order,$dir)
                ->get()->toArray(); 
            } */
            $memberslist = $compQuery->orderBy($order,$dir)
            //->dump()
            ->get()->toArray(); 
            
        }
        else {
           // DB::enableQueryLog();
            $search = $request->input('search.value'); 
        
			$compQuery = DB::table('company_branch as c')
							->select(DB::raw("IFNULL(m.levy, '---') AS levy"),DB::raw("IFNULL(c.branch_name, '---') AS branch_name"),DB::raw("IFNULL(d.designation_name, '---') AS designation_name"),'c.id as cid','m.name','m.email','m.id as id','m.status_id as status_id','m.branch_id as branch_id','s.status_name as status_name','m.member_number','m.designation_id',DB::raw("IFNULL(m.gender, '---') AS gender"),DB::raw("IFNULL(com.company_name, '---') AS company_name"),DB::raw("IFNULL(m.doj, '---') AS doj"),DB::raw("IFNULL(m.old_ic, '---') AS old_ic"),DB::raw("IFNULL(m.new_ic, '---') AS new_ic"),DB::raw("IFNULL(st.state_name, '---') AS state_name"),'cit.id as cityid',DB::raw("IFNULL(cit.city_name, '---') AS city_name"),'st.id as stateid','m.state_id','m.city_id','m.race_id',DB::raw("IFNULL(m.levy_amount, '---') AS levy_amount"),DB::raw("IFNULL(m.tdf, '---') AS tdf"),DB::raw("IFNULL(m.tdf_amount, '---') AS tdf_amount"),DB::raw("IFNULL(com.short_code, '---') AS short_code"),DB::raw("IFNULL(m.mobile, '---') AS mobile"),DB::raw("IFNULL(r.race_name, '---') AS race_name"),DB::raw("IFNULL(r.short_code, '---') AS raceshortcode"),'s.status_name','s.font_color','m.approval_status')
                            ->join('membership as m','c.id','=','m.branch_id')
                            ->leftjoin('designation as d','m.designation_id','=','d.id')
                            ->leftjoin('company as com','com.id','=','c.company_id')
                            ->leftjoin('status as s','s.id','=','m.status_id')
                            ->leftjoin('state as st','st.id','=','m.state_id')
                            ->leftjoin('city as cit','cit.id','=','m.city_id')
                            ->leftjoin('race as r','r.id','=','m.race_id')
                            ->where('m.is_request_approved','=',$approved_cond);
                            if($member_status!='all'){
                                $compQuery = $compQuery->where('m.status_id','=',$member_status);
                            }
							if($user_role=='union-branch'){
								$compQuery =  $compQuery->where([
								['c.union_branch_id','=',$union_branch_id]
								]);
							}
							if($user_role=='company'){
								$compQuery =  $compQuery->where([
								['c.company_id','=',$companyid]
								]);
							}
							if($user_role=='company-branch'){
								$compQuery =  $compQuery->where([
								['m.branch_id','=',$branchid]
								]);
							}
                            $compQuery =  $compQuery->where(function($query) use ($search){
                                $query->orWhere('com.company_name', 'LIKE',"%{$search}%")
                                ->orWhere('m.member_number', '=',"{$search}")
                                ->orWhere('d.designation_name', 'LIKE',"%{$search}%")
                                ->orWhere('m.gender', 'LIKE',"%{$search}%")
                                ->orWhere('m.doj', 'LIKE',"%{$search}%")
                                ->orWhere('m.name', 'LIKE',"%{$search}%")
                                ->orWhere(DB::raw("TRIM(LEADING '0' FROM m.old_ic)"), 'LIKE',"{$search}")
                                ->orWhere(DB::raw("TRIM(LEADING '0' FROM m.new_ic)"), 'LIKE',"{$search}")
                                ->orWhere(DB::raw("TRIM(LEADING '0' FROM m.employee_id)"), 'LIKE',"{$search}")
								->orWhere('m.old_ic', 'LIKE',"{$search}")
								->orWhere('m.new_ic', 'LIKE',"{$search}")
								->orWhere('m.employee_id', 'LIKE',"{$search}")
                                ->orWhere(DB::raw("TRIM(LEADING '0' FROM m.new_ic)"), 'LIKE',"{$search}")
                                ->orWhere(DB::raw("TRIM(LEADING '0' FROM m.employee_id)"), 'LIKE',"{$search}")
                                ->orWhere('com.short_code', 'LIKE',"%{$search}%")
                                ->orWhere('st.state_name', 'LIKE',"%{$search}%")
                                ->orWhere('cit.city_name', 'LIKE',"%{$search}%")
                                ->orWhere('m.levy', 'LIKE',"%{$search}%")
                                ->orWhere('m.levy_amount', 'LIKE',"%{$search}%")
                                ->orWhere('m.tdf', 'LIKE',"%{$search}%")
                                ->orWhere('m.tdf_amount', 'LIKE',"%{$search}%")
                               // ->orWhere('m.email', 'LIKE',"%{$search}%")
                                ->orWhere('m.mobile', 'LIKE',"{$search}")
                                ->orWhere('r.short_code', 'LIKE',"%{$search}%");
                                //->orWhere('c.branch_name', 'LIKE',"%{$search}%")
                                //->orWhere('s.status_name', 'LIKE',"%{$search}%");
                            });
			if( $limit != -1){
				$compQuery = $compQuery->offset($start)
				->limit($limit);
			}
			$memberslist = $compQuery
			->orderBy($order,$dir)
			->get()->toArray();

             $totalFiltered = $compQuery->count();

           
          
    }
	$data = array();
        if(!empty($memberslist))
        {
            foreach ($memberslist as $member)
            {
                
                
                $designation = $member->designation_name[0];
                $nestedData['designation_id'] = $designation;
                $gender = $member->gender[0];
                $nestedData['gender'] = $gender;
                $nestedData['short_code'] = $member->short_code;
                $nestedData['branch_name'] = $member->branch_name;
            
                $nestedData['levy'] = $member->levy;
                $nestedData['levy_amount'] = $member->levy_amount;
                $nestedData['tdf'] = $member->tdf;
                $nestedData['tdf_amount'] = $member->tdf_amount;
                $nestedData['doj'] = $member->doj;
                $nestedData['city_id'] = $member->city_name;
                $nestedData['state_id'] = $member->state_name;
                $nestedData['old_ic'] = $member->old_ic; 
                $nestedData['new_ic'] = $member->new_ic;
                $nestedData['mobile'] = $member->mobile;
                $nestedData['race_id'] = $member->raceshortcode;
                $nestedData['status'] = $member->approval_status;
                $font_color = $member->font_color;
                $nestedData['font_color'] = $font_color;
                $nestedData['bg_color'] = $member->approval_status=='Rejected' ? '#e20f03' : '#fff';

                $nestedData['member_number'] = $member->approval_status=='Rejected' ? '<span style="background:#e20f03;color:#fff;padding:5px;">'.$member->member_number.'</span>' : $member->member_number ;

                $nestedData['name'] = $member->approval_status=='Rejected' ? '<span style="background:#e20f03;color:#fff;padding:5px;">'.$member->name.'</span>' : $member->name;
                
                $enc_id = Crypt::encrypt($member->id);
                $delete = "";
                               
               // $edit = route('master.editmembership', [app()->getLocale(),$enc_id]);
                $view = route('master.viewmembership', [app()->getLocale(),$enc_id]);
                $histry = route('member.history', [app()->getLocale(),$enc_id]);

                if($user_role=='union-branch'){
                    $edit = route('union.editmembership', [app()->getLocale(),$enc_id]);
                    $actions ="<a style='' id='$edit' onClick='showeditForm();' title='Edit' class='btn-sm waves-effect waves-light cyan modal-trigger' href='$edit'><i class='material-icons'>edit</i></a>";
                }else{
                    $edit = route('master.editmembership', [app()->getLocale(),$enc_id]);
                    $actions ="<a style='' id='$edit' onClick='showeditForm();' title='Edit' class='btn-sm waves-effect waves-light cyan modal-trigger' href='$edit'><i class='material-icons'>edit</i></a>";
                }

                
                //$actions ="<a style='' id='$edit' onClick='showeditForm();' title='Edit' class='btn-sm waves-effect waves-light cyan modal-trigger' href='$edit'><i class='material-icons'>edit</i></a>";

                if($user_role=='union'){
                      $actions .="<a style='margin-left: 10px;' title='View' class='btn-sm waves-effect waves-light purple modal-trigger' href='$view'><i class='material-icons'>remove_red_eye</i></a>";
                }
                
               // $actions ="<a style='float: left;' id='$edit' onClick='showeditForm();' title='Edit' class='modal-trigger' href='$edit'><i class='material-icons' style='color:#2196f3'>edit</i></a>";

                //DB::enableQueryLog();
                $history_list = DB::table('mon_sub_member')
                                    ->where('MemberCode','=',$member->id)->get();
									
				$ircstatus = CommonHelper::get_irc_confirmation_status($member->id);
				$irc_env = CommonHelper::getIRCVariable();
              
                           
                if(count($history_list)!=0)
                {
                   // $actions .="<a style='margin-left: 10px;' title='History'  class='' href='$histry'><i class='material-icons' style='color:#FF69B4;'>history</i></a>";
                }
                if($user_role=='union'){
                    $actions .="<a style='margin-left: 10px;' title='Payment History'  class='btn-sm waves-effect waves-light amber darken-4' href='$histry'><i class='material-icons'>history</i></a>";
                }
				
                $baseurl = URL::to('/');
               
                $member_transfer_link = $baseurl.'/'.app()->getLocale().'/member_transfer?member_id='.Crypt::encrypt($member->id).'&branch_id='.Crypt::encrypt($member->branch_id);

                $doj_url = date('d/m/Y',strtotime($member->doj));
                $member_card_link = $baseurl.'/'.app()->getLocale().'/get-new-members-print??offset=0&from_date='.$doj_url.'&to_date='.$doj_url.'&company_id=&branch_id=&member_auto_id='.$member->id.'&join_type=&unionbranch_id=&from_member_no=&to_member_no=';

                if($user_role=='union'){
                   $actions .="<a style='margin-left: 10px;' title='Member Transfer'  class='btn-sm waves-effect waves-light yellow darken-3' href='$member_transfer_link'><i class='material-icons' >transfer_within_a_station</i></a>";
                }

				if($ircstatus==1 && $irc_env){
					$editmemberirc_link = $baseurl.'/'.app()->getLocale().'/membership-edit/'.$enc_id.'?status=1';
					$actions .= "<a style='margin-left: 10px;' title='IRC Details'  class='btn-sm waves-effect waves-light purple' href='$editmemberirc_link'><i class='material-icons' >confirmation_number</i></a>";
				}
				if($irc_env==false){
					$editmemberirc_link = $baseurl.'/'.app()->getLocale().'/membership-edit/'.$enc_id.'?status=2';
					$actions .= "<a style='margin-left: 10px;' title='Resign Now'  class='btn-sm waves-effect waves-light red' href='$editmemberirc_link'><i class='material-icons' >block</i></a>";
                }
                
                if($user_role=='union'){
                    $actions .= "<a style='margin-left: 10px;' title='Card print'  class='btn-sm waves-effect waves-light blue' target='_blank' href='$member_card_link'><i class='material-icons' >card_membership</i></a>";
                }
                
               
                //$data = $this->CommonAjaxReturn($city, 0, 'master.citydestroy', 0);
                $nestedData['options'] = $actions;
                $data[] = $nestedData;

            }
        }
         $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );

        echo json_encode($json_data); 
    } 

    public function editMember(Request $request, $lang,$id)
    {
		
        $id = Crypt::decrypt($id);
        //print_r($id) ;
         DB::connection()->enableQueryLog();
         $data['member_view'] = DB::table('membership')->select('membership.id as mid','membership.member_title_id','membership.member_number','membership.name','membership.gender','membership.designation_id','membership.email','membership.mobile',
                                        'membership.country_id','membership.state_id','membership.city_id','membership.address_one','membership.address_two','membership.address_three','membership.race_id','membership.old_ic','membership.new_ic',
                                        'membership.dob','membership.doj','membership.doe','membership.postal_code','membership.salary','membership.status_id','branch_id','membership.password','membership.user_type','membership.status','country.id','country.country_name','country.status','state.id','state.state_name','state.status',
                                        'city.id','city.city_name','city.status','company_branch.id','company_branch.branch_name','company_branch.status','designation.id','designation.designation_name','designation.status','race.id','race.race_name','race.status','persontitle.id','persontitle.person_title','persontitle.status','membership.old_member_number','membership.employee_id','membership.is_request_approved',
                                        'membership.levy','membership.levy_amount','membership.tdf','membership.tdf_amount','membership.current_salary','membership.last_update','membership.approval_status','membership.approval_reason','membership.designation_new_id','membership.designation_others','membership.approved_by','membership.send_irc_request')
                                ->leftjoin('country','membership.country_id','=','country.id')
                                ->leftjoin('state','membership.state_id','=','state.id')
                                ->leftjoin('city','membership.city_id','=','city.id')
                                ->leftjoin('company_branch','membership.branch_id','=','company_branch.id')
                                ->leftjoin('persontitle','membership.member_title_id','=','persontitle.id')
                                ->leftjoin('race','membership.race_id','=','race.id')
                                ->leftjoin('designation','membership.designation_id','=','designation.id')
                                ->where([
                                   ['membership.id','=',$id]
                                ])->get();

                            //     $queries = DB::getQueryLog();
                            //   dd($queries);
                             
        $country_id = $data['member_view'][0]->country_id;
      
        $state_id = $data['member_view'][0]->state_id;
        $city_id = $data['member_view'][0]->city_id;
      
        $company_id = CommonHelper::get_branch_company_id($data['member_view'][0]->branch_id);
        //$company_id = $data['member_view'][0]->company_id;
        $data['status_view'] = DB::table('status')->where('status','=','1')->get();
        $data['company_view'] = DB::table('company')->select('id','company_name')->where('status','=','1')->get();
        $data['state_view'] = DB::table('state')->select('id','state_name')->where('status','=','1')->where('country_id','=',$country_id)->get();
        $data['city_view'] = DB::table('city')->select('id','city_name')->where('status','=','1')->where('state_id','=',$state_id)->get();
        $data['country_view'] = DB::table('country')->select('id','country_name')->where('status','=','1')->get();
        $data['branch_view'] = DB::table('company_branch')->where('status','=','1')->where('company_id', $company_id)->get();
        $data['title_view'] = DB::table('persontitle')->where('status','=','1')->get();
        $data['designation_view'] = DB::table('designation')->where('status','=','1')->get();
        $data['race_view'] = DB::table('race')->where('status','=','1')->get();
        $data['relationship_view'] = DB::table('relation')->where('status','=','1')->get();
        // $data['nominee_view'] = DB::table('member_nominees')->where('status','=','1')->where('member_id','=',$id)->get();
        // $data['gardian_view'] = DB::table('member_guardian')->where('status','=','1')->where('member_id','=',$id)->get();
       
        $data['fee_list'] = DB::table('fee')->where('status','=','1')->get();
        
        
        $data['fee_view'] = DB::table('member_fee')->where('status','=','1')->where('member_id','=',$id)->get();
      // return  $data; 
        // $data['user_type'] = 1;
        // return view('membership.add_membership')->with('data',$data);  
		//dd($data);
        return view('membership.edit_union_membership')->with('data',$data); 
   
    }

    public function DeleteFile(Request $request, $lang)
    {
        $fileid = $request->input('fileid');
        $filedata = DB::table('membership_attachments')->where('id','=',$fileid)->first();
        if(file_exists(storage_path('app/member/'.$filedata->file_name))) {
            unlink(storage_path('app/member/'.$filedata->file_name));
            $filedel = DB::table('membership_attachments')->where('id','=',$fileid)->delete();
        } else {
            $filedel = DB::table('membership_attachments')->where('id','=',$fileid)->delete();
        }
        echo 1;
       
    }

    public function AddResignation(Request $request, $lang){
        $data = [];
        return view('membership.add_resignation')->with('data',$data);  
    }

    public function getAllmemberslist(Request $request){
        $searchkey = $request->input('serachkey');
        $search = $request->input('query');

        $get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;
        $user_id = Auth::user()->id; 

        if($user_role=='union-branch'){
            $union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id')->first();
        }else{
            $union_branch_id = '';
        }
        

        //DB::enableQueryLog();
        $suggestions = DB::table('membership as m')->select(DB::raw('CONCAT(m.name, " - ", m.member_number) AS value'),'m.id as number','m.branch_id as branch_id','m.member_number as member_code','c.company_name','cb.branch_name','p.person_title','m.name','m.send_irc_request')
                            ->leftjoin('company_branch as cb','cb.id','=','m.branch_id')  
                            ->leftjoin('company as c','c.id','=','cb.company_id')      
                            ->leftjoin('persontitle as p','p.id','=','m.member_title_id')      
                            ->where(function($query) use ($search){
                                $query->orWhere('m.member_number', 'LIKE',"%{$search}%")
                                    ->orWhere('m.new_ic', 'LIKE',"{$search}")
                                    ->orWhere('m.old_ic', 'LIKE',"{$search}")
                                    ->orWhere('m.employee_id', 'LIKE',"{$search}")
                                    ->orWhere('m.name', 'LIKE',"%{$search}%");
                            });
        if($union_branch_id!=''){
           $suggestions =  $suggestions->where('cb.union_branch_id', '=',$union_branch_id);
        }

        $res['suggestions'] = $suggestions->where('m.status_id', '!=',4)->limit(25)
                            ->get();        
        //$queries = DB::getQueryLog();
                            //  dd($queries);
         return response()->json($res);
    }

    public function SendIrc(Request $request, $lang){
        //return $request->all();
        $member_code = $request->input('member_code');
        $redirect_url = app()->getLocale().'/add_resignation';
        if($member_code!=''){
            $user_dataone = [
                'send_irc_request' => 1,
            ];
            DB::table('membership')->where('id', $member_code)->update($user_dataone);
            
            return redirect($redirect_url)->with('message','IRC Confirmations sent Succesfully');
        }else{
            return redirect($redirect_url)->with('message','Please pick a member');
        }
    }

    public function VerifyList(Request $request, $lang){

        $data['member_type'] = 1;
        $data['country_view'] = DB::table('country')->select('id','country_name')->where('status','=','1')->get();
        $data['company_view'] = DB::table('company')->where('status','=','1')->get();
        $data['companybranch_view'] = DB::table('company_branch')->where('status','=','1')->get();
        $data['race_view'] = DB::table('race')->where('status','=','1')->get();
        $data['status_view'] = DB::table('status')->where('status','=','1')->get();
        $data['state_view'] = DB::table('state')->where('status','=','1')->get();
        $data['city_view'] = DB::table('city')->where('status','=','1')->get();
        $data['unionbranch_view'] = DB::table('union_branch')->where('status','=','1')->get();

        return view('membership.verifymembership')->with('data',$data); 
    }

    public function AjaxVerifymembersList(Request $request,$lang, $type){
        $member_status = $request->input('status');
        $sl=0;
        $columns[$sl++] = 'm.id';
        $columns[$sl++] = 'm.member_number';
        $columns[$sl++] = 'm.name';
        $columns[$sl++] = 'm.designation_id';
        $columns[$sl++] = 'm.gender'; 
        $columns[$sl++] = 'com.short_code';
        $columns[$sl++] = 'c.branch_name';
        $columns[$sl++] = 'm.levy';
        $columns[$sl++] = 'm.levy_amount';
        $columns[$sl++] = 'm.tdf';
        $columns[$sl++] = 'm.tdf_amount';
        $columns[$sl++] = 'm.doj';
        $columns[$sl++] = 'm.city_id';
        $columns[$sl++] = 'm.state_id';
        $columns[$sl++] = 'm.old_ic';
        $columns[$sl++] = 'm.new_ic';
        $columns[$sl++] = 'm.mobile';
        $columns[$sl++] = 'm.email';
        
        $columns[$sl++] = 'm.race_id';
        //if($type==1){
            $columns[$sl++] = 'm.status_id';
        //}
        
        if($type==1){
            $approved_cond = 1;
        }else{
            $approved_cond = 0;
        }
        
        $get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;
        $user_id = Auth::user()->id; 
        $member_qry = '';
        
        $unionbranch_id = $request->input('unionbranch_id'); 
        $company_id = $request->input('company_id'); 
        $branch_id = $request->input('branch_id'); 
        $gender = $request->input('gender'); 
        $race_id = $request->input('race_id'); 
        $status_id = $request->input('status_id'); 
        $country_id = $request->input('country_id'); 
        $state_id = $request->input('state_id');
        $city_id = $request->input('city_id'); 
        
        
        if($user_role=='union' || $user_role=='data-entry'){
            //DB::enableQueryLog();
                
            $member_qry = DB::table('membership as m')->select(DB::raw("IFNULL(m.levy, '---') AS levy"),'m.member_number','m.id as id','m.name','m.gender','m.designation_id','m.email','m.branch_id','m.status_id','m.doj','c.branch_name','c.id as companybranchid','com.id as companyid','com.company_name' ,'d.designation_name','m.old_ic','m.new_ic','m.mobile',DB::raw("IFNULL(st.state_name, '---') AS state_name"),'cit.id as cityid',DB::raw("IFNULL(cit.city_name, '---') AS city_name"),'st.id as stateid','m.state_id','m.city_id','m.race_id',DB::raw("IFNULL(m.levy_amount, '---') AS levy_amount"),DB::raw("IFNULL(m.tdf, '---') AS tdf"),DB::raw("IFNULL(m.tdf_amount, '---') AS tdf_amount"),'com.short_code','r.race_name','r.short_code as raceshortcode','s.status_name','s.font_color','con.country_name')
                         ->join('temp_membership as t','t.member_id','=','m.id')
                         ->leftjoin('designation as d','m.designation_id','=','d.id')
                         ->leftjoin('company_branch as c','m.branch_id','=','c.id')
                         ->leftjoin('company as com','com.id','=','c.company_id')
                         ->leftjoin('union_branch as ub','c.union_branch_id','=','ub.id')
                         ->leftjoin('status as s','s.id','=','m.status_id')
                         ->leftjoin('country as con','con.id','=','m.country_id')
                         ->leftjoin('state as st','st.id','=','c.state_id')
                         ->leftjoin('city as cit','cit.id','=','c.city_id')
                         ->leftjoin('race as r','r.id','=','m.race_id')
                         ->where('m.is_request_approved','=',$approved_cond)
                         ->whereNull('t.updated_by')
                         ->orderBy('m.id','DESC');
            if($branch_id!=""){
                  $member_qry = $member_qry->where('m.branch_id','=',$branch_id);
              }elseif($company_id!= ''){
                   $member_qry = $member_qry->where('c.company_id','=',$company_id);
              }
              if($unionbranch_id!= ''){
                  $member_qry = $member_qry->where('c.union_branch_id','=',$unionbranch_id);
              }
             if($gender!="")
             {
                $member_qry = $member_qry->where('m.gender','=',$gender);
             }
             if($race_id != "")
             {
                 $member_qry = $member_qry->where('m.race_id','=',$race_id);
             }
             if($status_id!=0 && $status_id != "")
             {
                 $member_qry = $member_qry->where('m.status_id','=',$status_id);
             }
             if($country_id != "")
             {
                 $member_qry = $member_qry->where('c.country_id','=',$country_id);
             }
             if($state_id != "")
             {
                 $member_qry = $member_qry->where('c.state_id','=',$state_id);
             }
             if($city_id != "")
             {
                 $member_qry = $member_qry->where('c.city_id','=',$city_id);
             }
              
            if($member_status !='all'){
                $member_qry = $member_qry->where('m.status_id','=',$member_status);
            }
            //$member_qry->dump()->get();            
            // $queries = DB::getQueryLog();
            // dd($queries);         
         
                        
        }else if($user_role=='union-branch'){
           
            $union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id');
            $union_branch_id_val = '';
            if(count($union_branch_id)>0){
                $union_branch_id_val = $union_branch_id[0];
                $member_qry = DB::table('company_branch as c')->select(DB::raw("IFNULL(m.levy, '---') AS levy"),'c.id as cid','m.name','m.email','m.id as id','m.status_id as status_id','m.branch_id as branch_id',
                'm.member_number','m.designation_id','d.id as designationid','d.designation_name','m.gender','com.company_name','m.doj','m.old_ic','m.new_ic','m.mobile',DB::raw("IFNULL(st.state_name, '---') AS state_name"),'cit.id as cityid',DB::raw("IFNULL(cit.city_name, '---') AS city_name"),'st.id as stateid','m.state_id','m.city_id','m.race_id',DB::raw("IFNULL(m.levy_amount, '---') AS levy_amount"),DB::raw("IFNULL(m.tdf, '---') AS tdf"),DB::raw("IFNULL(m.tdf_amount, '---') AS tdf_amount"),'com.short_code','r.race_name','r.short_code as raceshortcode','s.font_color')
                ->join('membership as m','c.id','=','m.branch_id')
                ->leftjoin('company as com','com.id','=','c.company_id')
                ->leftjoin('union_branch as ub','c.union_branch_id','=','ub.id')
                ->leftjoin('status as s','s.id','=','m.status_id')
                ->leftjoin('designation as d','m.designation_id','=','d.id')
                ->leftjoin('country as con','con.id','=','m.country_id')
                ->leftjoin('state as st','st.id','=','c.state_id')
                ->leftjoin('city as cit','cit.id','=','c.city_id')
                ->leftjoin('race as r','r.id','=','m.race_id')
                ->orderBy('m.id','DESC')
                ->where([
                    ['c.union_branch_id','=',$union_branch_id_val],
                    ['m.is_request_approved','=',$approved_cond]
                    ]);
                    if($branch_id!=""){
                        $member_qry = $member_qry->where('m.branch_id','=',$branch_id);
                    }elseif($company_id!= ''){
                         $member_qry = $member_qry->where('c.company_id','=',$company_id);
                    }
                    if($unionbranch_id!= ''){
                        $member_qry = $member_qry->where('c.union_branch_id','=',$unionbranch_id);
                    }
                   if($gender!="")
                   {
                        $member_qry = $member_qry->where('m.gender','=',$gender);
                    }
                   if($race_id != "")
                   {
                       $member_qry = $member_qry->where('m.race_id','=',$race_id);
                   }
                   if($status_id!=0 && $status_id != "")
                   {
                       $member_qry = $member_qry->where('m.status_id','=',$status_id);
                   }
                   if($country_id != "")
                   {
                       $member_qry = $member_qry->where('m.country_id','=',$country_id);
                   }
                   if($state_id != "")
                   {
                       $member_qry = $member_qry->where('c.state_id','=',$state_id);
                   }
                   if($city_id != "")
                   {
                       $member_qry = $member_qry->where('c.city_id','=',$city_id);
                   }
                if($member_status!='all'){
                    $member_qry = $member_qry->where('m.status_id','=',$member_status);
                }
            }
        }else if($user_role=='company'){
            $company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id');
            if(count($company_id)>0){
                $companyid = $company_id[0];
                $member_qry = DB::table('company_branch as c')->select(DB::raw("IFNULL(m.levy, '---') AS levy"),'c.id as cid','m.name','m.email','m.id','m.mobile','m.status_id as status_id','m.branch_id as branch_id',
                              'm.member_number','m.designation_id','d.designation_name','m.gender','com.company_name','m.doj','m.old_ic','m.new_ic','m.mobile',DB::raw("IFNULL(st.state_name, '---') AS state_name"),'cit.id as cityid',DB::raw("IFNULL(cit.city_name, '---') AS city_name"),'st.id as stateid','m.state_id','m.city_id','m.race_id',DB::raw("IFNULL(m.levy_amount, '---') AS levy_amount"),DB::raw("IFNULL(m.tdf, '---') AS tdf"),DB::raw("IFNULL(m.tdf_amount, '---') AS tdf_amount"),'com.short_code','r.race_name as raceshortcode','s.font_color')
                ->join('membership as m','c.id','=','m.branch_id')
                ->leftjoin('designation as d','m.designation_id','=','d.id')
                ->leftjoin('company as com','com.id','=','c.company_id')
                ->leftjoin('union_branch as ub','c.union_branch_id','=','ub.id')
                ->leftjoin('status as s','s.id','=','m.status_id')
                ->leftjoin('country as con','con.id','=','m.country_id')
                ->leftjoin('state as st','st.id','=','c.state_id')
                ->leftjoin('city as cit','cit.id','=','c.city_id')
                ->leftjoin('race as r','r.id','=','m.race_id')
                ->orderBy('m.id','DESC')
                ->where([
                    ['c.company_id','=',$companyid],
                    ['m.is_request_approved','=',$approved_cond]
                    ]);
                    if($branch_id!=""){
                        $member_qry = $member_qry->where('m.branch_id','=',$branch_id);
                    }elseif($company_id!= ''){
                         $member_qry = $member_qry->where('c.company_id','=',$company_id);
                    }
                    if($unionbranch_id!= ''){
                        $member_qry = $member_qry->where('c.union_branch_id','=',$unionbranch_id);
                    }
                   if($gender!="")
                   {
                        $member_qry = $member_qry->where('m.gender','=',$gender);
                    }
                   if($race_id != "")
                   {
                       $member_qry = $member_qry->where('m.race_id','=',$race_id);
                   }
                   if($status_id!=0 && $status_id != "")
                   {
                       $member_qry = $member_qry->where('m.status_id','=',$status_id);
                   }
                   if($country_id != "")
                   {
                       $member_qry = $member_qry->where('c.country_id','=',$country_id);
                   }
                   if($state_id != "")
                   {
                       $member_qry = $member_qry->where('c.state_id','=',$state_id);
                   }
                   if($city_id != "")
                   {
                       $member_qry = $member_qry->where('c.city_id','=',$city_id);
                   }
                if($member_status!='all'){
                    $member_qry = $member_qry->where('m.status_id','=',$member_status);
                }
            }
        }else if($user_role=='company-branch'){
            $branch_id = CompanyBranch::where('user_id',$user_id)->pluck('id');
            if(count($branch_id)>0){
                $branchid = $branch_id[0];
                $member_qry = DB::table('company_branch as c')->select(DB::raw("IFNULL(m.levy, '---') AS levy"),'c.id as cid','m.name','m.email','m.id','m.mobile','m.status_id as status_id','m.branch_id as branch_id',
                              'm.member_number','m.designation_id','d.designation_name','m.gender','com.company_name','m.doj','m.old_ic','m.new_ic','m.mobile','m.state_id','m.city_id','m.race_id','m.mobile',DB::raw("IFNULL(st.state_name, '---') AS state_name"),'cit.id as cityid',DB::raw("IFNULL(cit.city_name, '---') AS city_name"),'st.id as stateid','m.state_id','m.city_id','m.race_id',DB::raw("IFNULL(m.levy_amount, '---') AS levy_amount"),DB::raw("IFNULL(m.tdf, '---') AS tdf"),DB::raw("IFNULL(m.tdf_amount, '---') AS tdf_amount"),'com.short_code','r.race_name','r.short_code')
                ->join('membership as m','c.id','=','m.branch_id')
                ->leftjoin('designation as d','m.designation_id','=','d.id')
                ->leftjoin('company as com','com.id','=','c.company_id')
                ->leftjoin('union_branch as ub','c.union_branch_id','=','ub.id')
                ->leftjoin('status as s','s.id','=','m.status_id')
                ->leftjoin('country as con','con.id','=','m.country_id')
                ->leftjoin('state as st','st.id','=','c.state_id')
                ->leftjoin('city as cit','cit.id','=','c.city_id')
                ->leftjoin('race as r','r.id','=','m.race_id')
                ->orderBy('m.id','DESC')
                ->where([
                    ['m.branch_id','=',$branchid],
                    ['m.is_request_approved','=',$approved_cond]
                    ]);
                    if($branch_id!=""){
                        $member_qry = $member_qry->where('m.branch_id','=',$branch_id);
                    }elseif($company_id!= ''){
                         $member_qry = $member_qry->where('c.company_id','=',$company_id);
                    }
                    if($unionbranch_id!= ''){
                        $member_qry = $member_qry->where('c.union_branch_id','=',$unionbranch_id);
                    }
                   if($gender!="")
                   {
                        $member_qry = $member_qry->where('m.gender','=',$gender);
                    }
                   if($race_id != "")
                   {
                       $member_qry = $member_qry->where('m.race_id','=',$race_id);
                   }
                   if($status_id!=0 && $status_id != "")
                   {
                       $member_qry = $member_qry->where('m.status_id','=',$status_id);
                   }
                   if($country_id != "")
                   {
                       $member_qry = $member_qry->where('c.country_id','=',$country_id);
                   }
                   if($state_id != "")
                   {
                       $member_qry = $member_qry->where('c.state_id','=',$state_id);
                   }
                   if($city_id != "")
                   {
                       $member_qry = $member_qry->where('c.city_id','=',$city_id);
                   }
                if($member_status!='all'){
                    $member_qry = $member_qry->where('m.status_id','=',$member_status);
                }
            }
        }
        $totalData = 0;
        if($member_qry!=""){
            $totalData = $member_qry->count();
           
        }
                                
        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            //DB::enableQueryLog();
                $compQuery = DB::table('membership as m')
                ->select(DB::raw("IFNULL(m.levy, '---') AS levy"),DB::raw("IFNULL(c.branch_name, '---') AS branch_name"),DB::raw("IFNULL(d.designation_name, '---') AS designation_name"),'c.id as cid','m.name','m.email','m.id as id','m.status_id as status_id','m.branch_id as branch_id','s.status_name as status_name','m.member_number','m.designation_id',DB::raw("IFNULL(m.gender, '---') AS gender"),DB::raw("IFNULL(com.company_name, '---') AS company_name"),DB::raw("IFNULL(m.doj, '---') AS doj"),DB::raw("IFNULL(m.old_ic, '---') AS old_ic"),DB::raw("IFNULL(m.new_ic, '---') AS new_ic"),DB::raw("IFNULL(st.state_name, '---') AS state_name"),'cit.id as cityid',DB::raw("IFNULL(cit.city_name, '---') AS city_name"),'st.id as stateid','m.state_id','m.city_id','m.race_id',DB::raw("IFNULL(m.levy_amount, '---') AS levy_amount"),DB::raw("IFNULL(m.tdf, '---') AS tdf"),DB::raw("IFNULL(m.tdf_amount, '---') AS tdf_amount"),DB::raw("IFNULL(com.short_code, '---') AS short_code"),DB::raw("IFNULL(m.mobile, '---') AS mobile"),DB::raw("IFNULL(r.race_name, '---') AS race_name"),DB::raw("IFNULL(r.short_code, '---') AS raceshortcode"),'s.status_name','s.font_color')
                ->join('temp_membership as t','t.member_id','=','m.id')
                ->leftjoin('company_branch as c','c.id','=','m.branch_id')
                ->leftjoin('designation as d','m.designation_id','=','d.id')
                ->leftjoin('company as com','com.id','=','c.company_id')
                ->leftjoin('status as s','s.id','=','m.status_id')
                ->leftjoin('state as st','st.id','=','c.state_id')
                ->leftjoin('city as cit','cit.id','=','c.city_id')
                ->leftjoin('race as r','r.id','=','m.race_id')
                ->whereNull('t.updated_by')
                ->where('m.is_request_approved','=',$approved_cond);
                if($member_status!='all'){
                    $compQuery = $compQuery->where('m.status_id','=',$member_status);
                }
                if($user_role=='union-branch'){
                    $compQuery =  $compQuery->where([
                    ['c.union_branch_id','=',$union_branch_id_val]
                    ]);
                }
                if($user_role=='company'){
                    $compQuery =  $compQuery->where([
                    ['c.company_id','=',$companyid]
                    ]);
                }
                if($user_role=='company-branch'){
                    $compQuery =  $compQuery->where([
                    ['m.branch_id','=',$branchid]
                    ]);
                }
                if($branch_id!=""){
                    $compQuery = $compQuery->where('m.branch_id','=',$branch_id);
                }elseif($company_id!= ''){
                     $compQuery = $compQuery->where('c.company_id','=',$company_id);
                }
                if($unionbranch_id!= ''){
                    $compQuery = $compQuery->where('c.union_branch_id','=',$unionbranch_id);
                }
               // $compQuery->dump()->get();
               if($gender!="")
               {
                    $compQuery = $compQuery->where('m.gender','=',$gender);
              }
               if($race_id != "")
               {
                   $compQuery = $compQuery->where('m.race_id','=',$race_id);
               }
               if($status_id!=0 && $status_id != "")
               {
                   $compQuery = $compQuery->where('m.status_id','=',$status_id);
               }
               if($country_id != "")
               {
                   $compQuery = $compQuery->where('c.country_id','=',$country_id);
               }
               if($state_id != "")
               {
                   $compQuery = $compQuery->where('c.state_id','=',$state_id);
               }
               if($city_id != "")
               {
                   $compQuery = $compQuery->where('c.city_id','=',$city_id);
               }
                
              if($member_status !='all'){
                  $compQuery = $compQuery->where('m.status_id','=',$member_status);
              }
                
            if( $limit != -1){
                $compQuery = $compQuery->offset($start)
                ->limit($limit);
            }
           /*  if($order =='m.member_number'){
                $memberslist = $compQuery->orderBy('m.id','desc')
                    ->get()->toArray(); 
            }else{
                $memberslist = $compQuery->orderBy($order,$dir)
                ->get()->toArray(); 
            } */
            $memberslist = $compQuery->orderBy($order,$dir)
                ->get()->toArray(); 
            
        }
        else {
           // DB::enableQueryLog();
            $search = $request->input('search.value'); 
        
            $compQuery = DB::table('membership as m')
                            ->select(DB::raw("IFNULL(m.levy, '---') AS levy"),DB::raw("IFNULL(c.branch_name, '---') AS branch_name"),DB::raw("IFNULL(d.designation_name, '---') AS designation_name"),'c.id as cid','m.name','m.email','m.id as id','m.status_id as status_id','m.branch_id as branch_id','s.status_name as status_name','m.member_number','m.designation_id',DB::raw("IFNULL(m.gender, '---') AS gender"),DB::raw("IFNULL(com.company_name, '---') AS company_name"),DB::raw("IFNULL(m.doj, '---') AS doj"),DB::raw("IFNULL(m.old_ic, '---') AS old_ic"),DB::raw("IFNULL(m.new_ic, '---') AS new_ic"),DB::raw("IFNULL(st.state_name, '---') AS state_name"),'cit.id as cityid',DB::raw("IFNULL(cit.city_name, '---') AS city_name"),'st.id as stateid','m.state_id','m.city_id','m.race_id',DB::raw("IFNULL(m.levy_amount, '---') AS levy_amount"),DB::raw("IFNULL(m.tdf, '---') AS tdf"),DB::raw("IFNULL(m.tdf_amount, '---') AS tdf_amount"),DB::raw("IFNULL(com.short_code, '---') AS short_code"),DB::raw("IFNULL(m.mobile, '---') AS mobile"),DB::raw("IFNULL(r.race_name, '---') AS race_name"),DB::raw("IFNULL(r.short_code, '---') AS raceshortcode"),'s.status_name','s.font_color')
                            ->join('temp_membership as t','t.member_id','=','m.id')
                            ->leftjoin('company_branch as c','c.id','=','m.branch_id')
                            ->leftjoin('designation as d','m.designation_id','=','d.id')
                            ->leftjoin('company as com','com.id','=','c.company_id')
                            ->leftjoin('status as s','s.id','=','m.status_id')
                            ->leftjoin('state as st','st.id','=','c.state_id')
                            ->leftjoin('city as cit','cit.id','=','c.city_id')
                            ->leftjoin('race as r','r.id','=','m.race_id')
                            ->whereNull('t.updated_by')
                            ->where('m.is_request_approved','=',$approved_cond);
                            if($member_status!='all'){
                                $compQuery = $compQuery->where('m.status_id','=',$member_status);
                            }
                            if($user_role=='union-branch'){
                                $compQuery =  $compQuery->where([
                                ['c.union_branch_id','=',$union_branch_id]
                                ]);
                            }
                            if($user_role=='company'){
                                $compQuery =  $compQuery->where([
                                ['c.company_id','=',$companyid]
                                ]);
                            }
                            if($user_role=='company-branch'){
                                $compQuery =  $compQuery->where([
                                ['m.branch_id','=',$branchid]
                                ]);
                            }
                            $compQuery =  $compQuery->where(function($query) use ($search){
                                $query->orWhere('com.company_name', 'LIKE',"%{$search}%")
                                ->orWhere('m.member_number', '=',"{$search}")
                                ->orWhere('d.designation_name', 'LIKE',"%{$search}%")
                                ->orWhere('m.gender', 'LIKE',"%{$search}%")
                                ->orWhere('m.doj', 'LIKE',"%{$search}%")
                                ->orWhere('m.name', 'LIKE',"%{$search}%")
                                ->orWhere(DB::raw("TRIM(LEADING '0' FROM m.old_ic)"), 'LIKE',"{$search}")
                                ->orWhere(DB::raw("TRIM(LEADING '0' FROM m.new_ic)"), 'LIKE',"{$search}")
                                ->orWhere(DB::raw("TRIM(LEADING '0' FROM m.employee_id)"), 'LIKE',"{$search}")
                                ->orWhere('m.old_ic', 'LIKE',"{$search}")
                                ->orWhere('m.new_ic', 'LIKE',"{$search}")
                                ->orWhere('m.employee_id', 'LIKE',"{$search}")
                                ->orWhere(DB::raw("TRIM(LEADING '0' FROM m.new_ic)"), 'LIKE',"{$search}")
                                ->orWhere(DB::raw("TRIM(LEADING '0' FROM m.employee_id)"), 'LIKE',"{$search}")
                                ->orWhere('com.short_code', 'LIKE',"%{$search}%")
                                ->orWhere('st.state_name', 'LIKE',"%{$search}%")
                                ->orWhere('cit.city_name', 'LIKE',"%{$search}%")
                                ->orWhere('m.levy', 'LIKE',"%{$search}%")
                                ->orWhere('m.levy_amount', 'LIKE',"%{$search}%")
                                ->orWhere('m.tdf', 'LIKE',"%{$search}%")
                                ->orWhere('m.tdf_amount', 'LIKE',"%{$search}%")
                               // ->orWhere('m.email', 'LIKE',"%{$search}%")
                                ->orWhere('m.mobile', 'LIKE',"{$search}")
                                ->orWhere('r.short_code', 'LIKE',"%{$search}%");
                                //->orWhere('c.branch_name', 'LIKE',"%{$search}%")
                                //->orWhere('s.status_name', 'LIKE',"%{$search}%");
                            });
            if( $limit != -1){
                $compQuery = $compQuery->offset($start)
                ->limit($limit);
            }
            $memberslist = $compQuery
            ->orderBy($order,$dir)
            ->get()->toArray();

             $totalFiltered = $compQuery->count();

           
          
    }
    $data = array();
        if(!empty($memberslist))
        {
            foreach ($memberslist as $member)
            {
                $nestedData['member_number'] = $member->member_number;
                $nestedData['name'] = $member->name;
                $designation = $member->designation_name[0];
                $nestedData['designation_id'] = $designation;
                $gender = $member->gender[0];
                $nestedData['gender'] = $gender;
                $nestedData['short_code'] = $member->short_code;
                $nestedData['branch_name'] = $member->branch_name;
            
                $nestedData['levy'] = $member->levy;
                $nestedData['levy_amount'] = $member->levy_amount;
                $nestedData['tdf'] = $member->tdf;
                $nestedData['tdf_amount'] = $member->tdf_amount;
                $nestedData['doj'] = $member->doj;
                $nestedData['city_id'] = $member->city_name;
                $nestedData['state_id'] = $member->state_name;
                $nestedData['old_ic'] = $member->old_ic; 
                $nestedData['new_ic'] = $member->new_ic;
                $nestedData['mobile'] = $member->mobile;
                $nestedData['race_id'] = $member->raceshortcode;
                $nestedData['status'] = $member->status_name;
                $font_color = $member->font_color;
                $nestedData['font_color'] = $font_color;
                
                $enc_id = Crypt::encrypt($member->id);
                $delete = "";
                               
                
                $view = route('master.viewmembership', [app()->getLocale(),$enc_id]);
                $histry = route('member.history', [app()->getLocale(),$enc_id]);
                
                if($user_role=='union-branch'){
                    $edit = route('union.editmembership', [app()->getLocale(),$enc_id]);
                    $actions ="<a style='' id='$edit' onClick='showeditForm();' title='Edit' class='btn-sm waves-effect waves-light cyan modal-trigger' href='$edit'><i class='material-icons'>edit</i></a>";
                }else{
                    $edit = route('approve.editmembership', [app()->getLocale(),$enc_id]);
                    $actions ="<a style='' id='$edit' onClick='showeditForm();' title='Approve' class='btn-sm waves-effect waves-light cyan modal-trigger' href='$edit'><i class='material-icons'>edit</i></a>";
                }
                
                $baseurl = URL::to('/');
               
               
                //$data = $this->CommonAjaxReturn($city, 0, 'master.citydestroy', 0);
                $nestedData['options'] = $actions;
                $data[] = $nestedData;

            }
        }
         $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );

        echo json_encode($json_data); 
    }

    public function VerifyMember(Request $request, $lang,$id)
    {

        $id = Crypt::decrypt($id);
        //print_r($id) ;
         DB::connection()->enableQueryLog();
         $data['member_view'] = DB::table('membership')->select('membership.id as mid','membership.member_title_id','membership.member_number','membership.name','membership.gender','membership.designation_id','membership.email','membership.mobile',
                                        'membership.country_id','membership.state_id','membership.city_id','membership.address_one','membership.address_two','membership.address_three','membership.race_id','membership.old_ic','membership.new_ic',
                                        'membership.dob','membership.doj','membership.doe','membership.postal_code','membership.salary','membership.status_id','branch_id','membership.password','membership.user_type','membership.status','country.id','country.country_name','country.status','state.id','state.state_name','state.status',
                                        'city.id','city.city_name','city.status','company_branch.id','company_branch.branch_name','company_branch.status','designation.id','designation.designation_name','designation.status','race.id','race.race_name','race.status','persontitle.id','persontitle.person_title','persontitle.status','membership.old_member_number','membership.employee_id','membership.is_request_approved',
                                        'membership.levy','membership.levy_amount','membership.tdf','membership.tdf_amount','membership.current_salary','membership.last_update','membership.approval_status','membership.approval_reason','membership.designation_new_id','membership.designation_others','membership.approved_by','t.name as tname','t.gender as tgender','t.email as temail','t.dob as tdob','t.mobile as tmobile','t.member_title_id as tpersontitle')
                                ->leftjoin('temp_membership as t','t.member_id','=','membership.id')
                                ->leftjoin('country','membership.country_id','=','country.id')
                                ->leftjoin('state','membership.state_id','=','state.id')
                                ->leftjoin('city','membership.city_id','=','city.id')
                                ->leftjoin('company_branch','membership.branch_id','=','company_branch.id')
                                ->leftjoin('persontitle','t.member_title_id','=','persontitle.id')
                                ->leftjoin('race','membership.race_id','=','race.id')
                                ->leftjoin('designation','membership.designation_id','=','designation.id')
                                ->where([
                                   ['membership.id','=',$id]
                                ])->get();

                            //     $queries = DB::getQueryLog();
                            //   dd($queries);
                             
        $country_id = $data['member_view'][0]->country_id;
      
        $state_id = $data['member_view'][0]->state_id;
        $city_id = $data['member_view'][0]->city_id;
      
        $company_id = CommonHelper::get_branch_company_id($data['member_view'][0]->branch_id);
        //$company_id = $data['member_view'][0]->company_id;
        $data['status_view'] = DB::table('status')->where('status','=','1')->get();
        $data['company_view'] = DB::table('company')->select('id','company_name')->where('status','=','1')->get();
        $data['state_view'] = DB::table('state')->select('id','state_name')->where('status','=','1')->where('country_id','=',$country_id)->get();
        $data['city_view'] = DB::table('city')->select('id','city_name')->where('status','=','1')->where('state_id','=',$state_id)->get();
        $data['country_view'] = DB::table('country')->select('id','country_name')->where('status','=','1')->get();
        $data['branch_view'] = DB::table('company_branch')->where('status','=','1')->where('company_id', $company_id)->get();
        $data['title_view'] = DB::table('persontitle')->where('status','=','1')->get();
        $data['designation_view'] = DB::table('designation')->where('status','=','1')->get();
        $data['race_view'] = DB::table('race')->where('status','=','1')->get();
        $data['relationship_view'] = DB::table('relation')->where('status','=','1')->get();
        $data['nominee_view'] = DB::table('temp_member_nominees')->where('status','=','1')->where('member_id','=',$id)->get();
        $data['gardian_view'] = DB::table('temp_member_guardian')->where('status','=','1')->where('member_id','=',$id)->get();
       
        $data['view_status'] = 0;
        
        //$data['fee_view'] = DB::table('member_fee')->where('status','=','1')->where('member_id','=',$id)->get();
      // return  $data; 
        // $data['user_type'] = 1;
        // return view('membership.add_membership')->with('data',$data);  
        //dd($data);
        return view('membership.approve_membership')->with('data',$data); 

    }

    public function CheckMemberDate(Request $request)
    {
         $doj_one = $request->input('doj');
         $member_id = $request->input('auto_id');
         $doj = explode("/",$doj_one);
         $doj_month = date('Y-m-01', strtotime($doj[2]."-".$doj[1]."-".$doj[0]));

         $membercount = DB::table('membership')->where(DB::raw('month(doj)'),'=',$doj[1])->where(DB::raw('year(doj)'),'=',$doj[2])->where('id','=',$member_id)->count();
        
         if($membercount==1){
            $data = ['status' => 1];
         }else{
            $membercount = DB::table('membermonthendstatus')->where('StatusMonth','=',$doj_month)->where('TOTAL_MONTHS','=',1)->where('id','=',$member_id)->count();
            $data = ['status' => 0,'message' => ''];
         }
         echo json_encode($data);
    }
}


