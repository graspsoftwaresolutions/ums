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
use App\Helpers\CommonHelper;
use App\Mail\SendMemberMailable;
use URL;
use Auth;
use Carbon\Carbon;


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
        if(!empty($request->all())){
			if($request->input('status')==1){
				 $irc_status = 1;
			}
	    }
        $id = Crypt::decrypt($id);
        //print_r($id) ;
         DB::connection()->enableQueryLog();
         $data['member_view'] = DB::table('membership')->select('membership.id as mid','membership.member_title_id','membership.member_number','membership.name','membership.gender','membership.designation_id','membership.email','membership.mobile',
                                        'membership.country_id','membership.state_id','membership.city_id','membership.address_one','membership.address_two','membership.address_three','membership.race_id','membership.old_ic','membership.new_ic',
                                        'membership.dob','membership.doj','membership.doe','membership.postal_code','membership.salary','membership.status_id','branch_id','membership.password','membership.user_type','membership.status','country.id','country.country_name','country.status','state.id','state.state_name','state.status',
                                        'city.id','city.city_name','city.status','company_branch.id','company_branch.branch_name','company_branch.status','designation.id','designation.designation_name','designation.status','race.id','race.race_name','race.status','persontitle.id','persontitle.person_title','persontitle.status','membership.old_member_number','membership.employee_id','membership.is_request_approved',
                                        'membership.levy','membership.levy_amount','membership.tdf','membership.tdf_amount')
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
        
        $data['fee_view'] = DB::table('member_fee')->where('status','=','1')->where('member_id','=',$id)->get();
      // return  $data; 
        // $data['user_type'] = 1;
        // return view('membership.add_membership')->with('data',$data);  
        return view('membership.edit_membership')->with('data',$data); 
   
    }
    
    public function delete($id)
	{
		$data = DB::table('membership')->where('id','=',$id)->update(['status'=>'0']);
		return redirect('membership')->with('message','Member Deleted Succesfully');
    }
    

    public function new_members(Request $request){
        $data['member_status'] = 'all';
        if(!empty($request->all())){
            $data['member_status'] = $request->input('status');
        }
        $data['member_type'] = 0;
        return view('membership.membership')->with('data',$data); 
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
        $columns[$sl++] = 'm.member_number';
        $columns[$sl++] = 'm.name';
        $columns[$sl++] = 'm.designation_id';
        $columns[$sl++] = 'm.gender'; 
        $columns[$sl++] = 'm.short_code';
        $columns[$sl++] = 'm.branch_id';
        $columns[$sl++] = 'm.levy';
        $columns[$sl++] = 'm.levy_amount';
        $columns[$sl++] = 'm.tdf';
        $columns[$sl++] = 'm.tdf_amount';
        $columns[$sl++] = 'm.doj';
        $columns[$sl++] = 'm.city_id';
        $columns[$sl++] = 'm.state_id';
        $columns[$sl++] = 'm.old_ic';
        $columns[$sl++] = 'm.new_ic';
		
		$columns[$sl++] = 'm.email';
        $columns[$sl++] = 'm.mobile';
        $columns[$sl++] = 'm.race_id';
		//if($type==1){
			$columns[$sl++] = 'm.status_id';
		//}
		$columns[$sl++] = 'm.id';
        
		if($type==1){
			$approved_cond = 1;
		}else{
			$approved_cond = 0;
		}
		
		$get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;
		$user_id = Auth::user()->id; 
		$member_qry = '';
		if($user_role=='union'){
            //DB::enableQueryLog();
        $member_qry = DB::table('membership as m')->select('m.member_number','m.id as id','m.name','m.gender','m.designation_id','m.email','m.branch_id','m.status_id','m.doj','c.branch_name','c.id as companybranchid','com.id as companyid','com.company_name' ,'d.designation_name','m.old_ic','m.new_ic','m.mobile','st.state_name','cit.id as cityid','cit.city_name','st.id as stateid','m.state_id','m.city_id','m.race_id','m.levy','m.levy_amount','m.tdf','m.tdf_amount','com.short_code','r.race_name','r.short_code as raceshortcode','s.status_name','s.font_color')
                     ->leftjoin('designation as d','m.designation_id','=','d.id')
                     ->leftjoin('company_branch as c','m.branch_id','=','c.id')
                     ->leftjoin('company as com','com.id','=','c.company_id')
                     ->leftjoin('status as s','s.id','=','m.status_id')
                     ->leftjoin('state as st','st.id','=','m.state_id')
                     ->leftjoin('city as cit','cit.id','=','m.city_id')
                     ->leftjoin('race as r','r.id','=','m.race_id')
                     ->where('m.is_request_approved','=',$approved_cond);
        if($member_status!='all'){
            $member_qry = $member_qry->where('m.status_id','=',$member_status);
        }
                     
        // $queries = DB::getQueryLog();
        // dd($queries);         
         
                        
		}else if($user_role=='union-branch'){
            DB::enableQueryLog();
           
            $union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id');
            $union_branch_id_val = '';
			if(count($union_branch_id)>0){
                $union_branch_id_val = $union_branch_id[0];
                $member_qry = DB::table('company_branch as c')->select('c.id as cid','m.name','m.email','m.id as id','m.status_id as status_id','m.branch_id as branch_id',
                'm.member_number','m.designation_id','d.id as designationid','d.designation_name','m.gender','com.company_name','m.doj','m.old_ic','m.new_ic','m.mobile','st.state_name','cit.id as cityid','cit.city_name','st.id as stateid','m.state_id','m.city_id','m.race_id','m.levy','m.levy_amount','m.tdf','m.tdf_amount','com.short_code','r.race_name','r.short_code as raceshortcode','s.font_color')
                ->join('membership as m','c.id','=','m.branch_id')
                ->leftjoin('company as com','com.id','=','c.company_id')
                ->leftjoin('status as s','s.id','=','m.status_id')
                ->leftjoin('designation as d','m.designation_id','=','d.id')
                ->leftjoin('state as st','st.id','=','m.state_id')
                ->leftjoin('city as cit','cit.id','=','m.city_id')
                ->leftjoin('race as r','r.id','=','m.race_id')
                ->orderBy('m.id','DESC')
                ->where([
                    ['c.union_branch_id','=',$union_branch_id_val],
                    ['m.is_request_approved','=',$approved_cond]
                    ]);
                if($member_status!='all'){
                    $member_qry = $member_qry->where('m.status_id','=',$member_status);
                }
			}
		}else if($user_role=='company'){
			$company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id');
			if(count($company_id)>0){
				$companyid = $company_id[0];
                $member_qry = DB::table('company_branch as c')->select('c.id as cid','m.name','m.email','m.id','m.mobile','m.status_id as status_id','m.branch_id as branch_id',
                              'm.member_number','m.designation_id','d.designation_name','m.gender','com.company_name','m.doj','m.old_ic','m.new_ic','m.mobile','st.state_name','cit.id as cityid','cit.city_name','st.id as stateid','m.state_id','m.city_id','m.race_id','m.levy','m.levy_amount','m.tdf','m.tdf_amount','com.short_code','r.race_name as raceshortcode','s.font_color')
                ->join('membership as m','c.id','=','m.branch_id')
                ->leftjoin('designation as d','m.designation_id','=','d.id')
                ->leftjoin('company as com','com.id','=','c.company_id')
                ->leftjoin('status as s','s.id','=','m.status_id')
                ->leftjoin('state as st','st.id','=','m.state_id')
                ->leftjoin('city as cit','cit.id','=','m.city_id')
                ->leftjoin('race as r','r.id','=','m.race_id')
                ->orderBy('m.id','DESC')
                ->where([
                    ['c.company_id','=',$companyid],
                    ['m.is_request_approved','=',$approved_cond]
                    ]);
                if($member_status!='all'){
                    $member_qry = $member_qry->where('m.status_id','=',$member_status);
                }
			}
		}else if($user_role=='company-branch'){
			$branch_id = CompanyBranch::where('user_id',$user_id)->pluck('id');
			if(count($branch_id)>0){
				$branchid = $branch_id[0];
                $member_qry = DB::table('company_branch as c')->select('c.id as cid','m.name','m.email','m.id','m.mobile','m.status_id as status_id','m.branch_id as branch_id',
                              'm.member_number','m.designation_id','d.designation_name','m.gender','com.company_name','m.doj','m.old_ic','m.new_ic','m.mobile','m.state_id','m.city_id','m.race_id','m.mobile','st.state_name','cit.id as cityid','cit.city_name','st.id as stateid','m.state_id','m.city_id','m.race_id','m.levy','m.levy_amount','m.tdf','m.tdf_amount','com.short_code','r.race_name','r.short_code')
                ->join('membership as m','c.id','=','m.branch_id')
                ->leftjoin('designation as d','m.designation_id','=','d.id')
                ->leftjoin('company as com','com.id','=','c.company_id')
                ->leftjoin('status as s','s.id','=','m.status_id')
                ->leftjoin('state as st','st.id','=','m.state_id')
                ->leftjoin('city as cit','cit.id','=','m.city_id')
                ->leftjoin('race as r','r.id','=','m.race_id')
                ->orderBy('m.id','DESC')
                ->where([
                    ['m.branch_id','=',$branchid],
                    ['m.is_request_approved','=',$approved_cond]
                    ]);
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
           $compQuery = DB::table('company_branch as c')
				->select('c.id as cid','m.name','m.email','m.id as id','m.status_id as status_id','m.branch_id as branch_id','c.branch_name as branch_name','s.status_name as status_name','m.member_number','m.designation_id','d.designation_name','m.gender','com.company_name','m.doj','m.old_ic','m.new_ic','st.state_name','cit.id as cityid','cit.city_name','st.id as stateid','m.state_id','m.city_id','m.race_id','m.levy','m.levy_amount','m.tdf','m.tdf_amount','com.short_code','m.mobile','r.race_name','r.short_code as raceshortcode','s.status_name','s.font_color')
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
			if( $limit != -1){
				$compQuery = $compQuery->offset($start)
				->limit($limit);
			}
			$memberslist = $compQuery->orderBy($order,$dir)
			->get()->toArray(); 
        
        }
        else {
            DB::enableQueryLog();
            $search = $request->input('search.value'); 
        
			$compQuery = DB::table('company_branch as c')
							->select('c.id as cid','m.name','m.email','m.id as id','m.status_id as status_id','m.branch_id as branch_id','c.branch_name as branch_name','s.status_name as status_name','m.member_number','m.designation_id','d.designation_name','m.gender','com.company_name','m.doj','st.state_name','cit.id as cityid','cit.city_name','st.id as stateid','m.state_id','m.city_id','m.race_id','m.levy','m.levy_amount','m.tdf','m.tdf_amount','com.short_code','m.mobile','m.old_ic','m.new_ic','r.race_name','r.short_code as raceshortcode','s.font_color')
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
                                $query->orWhere('m.id','LIKE',"%{$search}%")
                                ->orWhere('com.company_name', 'LIKE',"%{$search}%")
                                ->orWhere('m.member_number', 'LIKE',"%{$search}%")
                                ->orWhere('d.designation_name', 'LIKE',"%{$search}%")
                                ->orWhere('m.gender', 'LIKE',"%{$search}%")
                                ->orWhere('m.doj', 'LIKE',"%{$search}%")
                                ->orWhere('m.name', 'LIKE',"%{$search}%")
                                ->orWhere('m.new_ic', 'LIKE',"%{$search}%")
                                ->orWhere('m.old_ic', 'LIKE',"%{$search}%")
                                ->orWhere('m.new_ic', 'LIKE',"%{$search}%")
                                ->orWhere('com.short_code', 'LIKE',"%{$search}%")
                                ->orWhere('st.state_name', 'LIKE',"%{$search}%")
                                ->orWhere('cit.city_name', 'LIKE',"%{$search}%")
                                ->orWhere('m.levy', 'LIKE',"%{$search}%")
                                ->orWhere('m.levy_amount', 'LIKE',"%{$search}%")
                                ->orWhere('m.tdf', 'LIKE',"%{$search}%")
                                ->orWhere('m.tdf_amount', 'LIKE',"%{$search}%")
                               // ->orWhere('m.email', 'LIKE',"%{$search}%")
                                ->orWhere('m.mobile', 'LIKE',"%{$search}%")
                                ->orWhere('r.short_code', 'LIKE',"%{$search}%")
                                ->orWhere('c.branch_name', 'LIKE',"%{$search}%")
                                ->orWhere('s.status_name', 'LIKE',"%{$search}%");
                            });
			if( $limit != -1){
				$compQuery = $compQuery->offset($start)
				->limit($limit);
			}
			$memberslist = $compQuery
			->orderBy($order,$dir)
			->get()->toArray();

             $totalFiltered = $compQuery->count();

            // $queries = DB::getQueryLog();
            //   dd($queries);
          
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
                               
                $edit = route('master.editmembership', [app()->getLocale(),$enc_id]);
                $histry = route('subscription.submember', [app()->getLocale(),$enc_id]);
                
              //  $actions ="<a style='float: left;' id='$edit' onClick='showeditForm();' title='Edit' class='btn-floating waves-effect waves-light cyan modal-trigger' href='$edit'><i class='material-icons'>edit</i></a>";
                
                $actions ="<a style='float: left;' id='$edit' onClick='showeditForm();' title='Edit' class='modal-trigger' href='$edit'><i class='material-icons' style='color:#2196f3'>edit</i></a>";

                DB::enableQueryLog();
                $history_list = DB::table('mon_sub_member')
                                    ->where('MemberCode','=',$member->id)->get();
									
				$ircstatus = CommonHelper::get_irc_confirmation_status($member->id);
              
                           
                if(count($history_list)!=0)
                {
                    $actions .="<a style='float: left; margin-left: 10px;' title='History'  class='' href='$histry'><i class='material-icons' style='color:#FF69B4;'>history</i></a>";
                }
                $baseurl = URL::to('/');
                $editmemberirc_link = $baseurl.'/'.app()->getLocale().'/membership-edit/'.$enc_id.'?status=1';
                $member_transfer_link = $baseurl.'/'.app()->getLocale().'/member_transfer?member_id='.Crypt::encrypt($member->id).'&branch_id='.Crypt::encrypt($member->branch_id);
                $actions .="<a style='float: left; margin-left: 10px;' title='Member Transfer'  class='' href='$member_transfer_link'><i class='material-icons' style='color:#FFC107'>transfer_within_a_station</i></a>";
				if($ircstatus==1){
					$actions .= "<a style='float: left; margin-left: 10px;' title='IRC Details'  class='' href='$editmemberirc_link'><i class='material-icons' style='color:#c36ac3'>confirmation_number</i></a>";
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
        $res['suggestions'] = DB::table('membership as m')->select(DB::raw('CONCAT(m.name, " - ", m.id) AS value'),'m.id as number','m.branch_id as branch_id')      
                            ->where(function($query) use ($search){
                                $query->orWhere('m.id','LIKE',"%{$search}%")
                                    ->orWhere('m.member_number', 'LIKE',"%{$search}%")
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
         $datefilter = $request->input('datefilter');
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
		 $columns = array( 
            0 => 'h.MemberCode', 
            1 => 'h.old_branch_id',
            2 => 'h.new_branch_id',
            3 => 'h.transfer_date',
            4 => 'h.id',
        );
        $commonselect = DB::table('member_transfer_history as h')->select('m.name','h.old_branch_id','h.new_branch_id','h.transfer_date','h.id','h.MemberCode');
        $commoncount = DB::table('member_transfer_history as h');
        $commonselectqry = $commonselect->leftjoin('membership as m','m.id','=','h.MemberCode')->where(DB::raw('DATE_FORMAT(h.`transfer_date`,"%Y-%m")'), '=',"{$dateformat}");
        //DB::enableQueryLog();
        $commoncountqry = $commoncount->leftjoin('membership as m','m.id','=','h.MemberCode')->where(DB::raw('DATE_FORMAT(h.`transfer_date`,"%Y-%m")'), '=',"{$dateformat}");
        $totalData = $commoncountqry->count();
       // $queries = DB::getQueryLog();
        //dd($queries);

        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        

        if(empty($request->input('search.value')))
        {            
            if( $limit == -1){
                $historylist = $commonselectqry->get();
            }else{
                $historylist = $commonselectqry->offset($start)
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
                            
                            
                            if( $limit != -1){
                                $historylist = $historylist->offset($start)->limit($limit);
                            }
                            $historylist = $historylist->orderBy($order,$dir)
                                ->get();

        $historycount = $commoncountqry->leftjoin('company_branch as cb','cb.id','=','h.old_branch_id')
                             ->leftjoin('company_branch as cbone','cbone.id','=','h.new_branch_id');
							
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

    

    
}


