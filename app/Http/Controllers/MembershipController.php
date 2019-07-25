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
    public function index()
    {
		return $this->CommonMembershipList(1);
    }
	public function CommonMembershipList($type){
		$get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;
        $user_id = Auth::user()->id;
                                                
       
        $data['member_type'] = $type;
		if($type==1){
			$status_cond = '!=';
		}else{
			$status_cond = '=';
		}
		if($user_role=='union'){
			$data['member_view'] = DB::table('membership')
				->where('membership.status','=','1')->where('membership.status_id',$status_cond,'1')->get();
		}else if($user_role=='union-branch'){
			$union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id');
			if(count($union_branch_id)>0){
				$union_branch_id = $union_branch_id[0];
				$data['member_view'] = DB::table('company_branch as c')->select('c.id as cid','m.name','m.email','m.id','m.mobile','m.status_id as status_id','m.branch_id as branch_id')
                ->join('membership as m','c.id','=','m.branch_id')
                ->orderBy('m.id','DESC')
                ->where([
                    ['c.union_branch_id','=',$union_branch_id],
                    ['m.status_id',$status_cond,'1']
                    ])->get();
				/* $data['member_view'] = DB::table('company_branch as c')
										->innerjoin('membership.status','=','1')->where('membership.status_id','!=','1')->get();
										->where('membership.status','=','1')->where('membership.status_id','!=','1')->get(); */
			}else{
				$data['member_view'] = array();
			}
		}else if($user_role=='company'){
			$company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id');
			if(count($company_id)>0){
				$companyid = $company_id[0];
				$data['member_view'] = DB::table('company_branch as c')->select('c.id as cid','m.name','m.email','m.id','m.mobile','m.status_id as status_id','m.branch_id as branch_id')
                ->join('membership as m','c.id','=','m.branch_id')
                ->orderBy('m.id','DESC')
                ->where([
                    ['c.company_id','=',$companyid],
                    ['m.status_id',$status_cond,'1']
                    ])->get();
			}else{
				$data['member_view'] = array();
			}
		}else if($user_role=='company-branch'){
			$branch_id = CompanyBranch::where('user_id',$user_id)->pluck('id');
			if(count($branch_id)>0){
				$branchid = $branch_id[0];
				$data['member_view'] = DB::table('company_branch as c')->select('c.id as cid','m.name','m.email','m.id','m.mobile','m.status_id as status_id','m.branch_id as branch_id')
                ->join('membership as m','c.id','=','m.branch_id')
                ->orderBy('m.id','DESC')
                ->where([
                    ['m.branch_id','=',$branchid],
                    ['m.status_id',$status_cond,'1']
                    ])->get();
			}else{
				$data['member_view'] = array();
			}
		}else{
			return view('errors.404'); 
		}
        /* if($check_union){
            $data['member_view'] = DB::table('membership')
            ->where('membership.status','=','1')->where('membership.status_id','!=','1')->get();
        }else{
			$data['member_view'] = DB::table('membership')
            ->where('membership.status','=','1')->where('membership.status_id','!=','1')->get();
           // $data['member_view'] = DB::table('membership')
            //->where('membership.status','=','1')->where('membership.status_id','!=','1')->where('branch_id','=',$branch_id)->get();
        } */
       
        return view('membership.membership')->with('data',$data); 
	}
    public function view($id)
    {
        $id = Crypt::decrypt($id);
        $data['member_view'] = DB::table('membership')
                                ->join('country','membership.country_id','=','country.id')
                                ->join('state','membership.state_id','=','state.id')
                                ->join('city','membership.city_id','=','city.id')
                                ->join('company_branch','membership.branch_id','=','company_branch.id')
                                ->join('persontitle','membership.member_title_id','=','persontitle.id')
                                ->join('race','membership.race_id','=','race.id')
                                ->join('designation','membership.designation_id','=','designation.id')
                                ->join('user_type','user_type.uid','=','membership.user_type')
                                ->where([
                                    ['membership.status','=','1'],
                                    ['membership.id','=',$id]
                                ])->get();
        return view('membership.view_membership')->with('data',$data); 
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
    
    
    public function edit($lang,$id)
    {
       
        $id = Crypt::decrypt($id);
        //print_r($id) ;
         DB::connection()->enableQueryLog();
         $data['member_view'] = DB::table('membership')->select('membership.id as mid','membership.member_title_id','membership.member_number','membership.name','membership.gender','membership.designation_id','membership.email','membership.mobile',
                                        'membership.country_id','membership.state_id','membership.city_id','membership.address_one','membership.address_two','membership.address_three','membership.race_id','membership.old_ic','membership.new_ic',
                                        'membership.dob','membership.doj','membership.doe','membership.postal_code','membership.salary','membership.status_id','branch_id','membership.password','membership.user_type','membership.status','country.id','country.country_name','country.status','state.id','state.state_name','state.status',
                                        'city.id','city.city_name','city.status','company_branch.id','company_branch.branch_name','company_branch.status','designation.id','designation.designation_name','designation.status','race.id','race.race_name','race.status','persontitle.id','persontitle.person_title','persontitle.status','membership.old_member_number','membership.employee_id')
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

                                //$queries = DB::getQueryLog();
                              // dd($queries);
                             
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
    

    public function new_members(){
		return $this->CommonMembershipList(0);
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
        DB::enableQueryLog();
		$sl=0;
		$columns[$sl++] = 'm.branch_id';
		$columns[$sl++] = 'm.name';
		$columns[$sl++] = 'm.email';
		$columns[$sl++] = 'm.mobile';
		if($type==1){
			$columns[$sl++] = 'm.status_id';
		}
		$columns[$sl++] = 'm.id';
        /* $columns = array( 
            0 => 'company_id', 
            1 => 'branch_name',
            2 => 'email',
            3 => 'is_head',
            4 => 'id'
        ); */
		if($type==1){
			$status_cond = '!=';
		}else{
			$status_cond = '=';
		}
		
		$get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;
		$user_id = Auth::user()->id;
		$member_qry = '';
		if($user_role=='union'){
			$member_qry = DB::table('membership as m')
				->where('m.status_id',$status_cond,'1');
		}else if($user_role=='union-branch'){
			$union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id');
			if(count($union_branch_id)>0){
				$union_branch_id = $union_branch_id[0];
				$member_qry = DB::table('company_branch as c')->select('c.id as cid','m.name','m.email','m.id','m.mobile','m.status_id as status_id','m.branch_id as branch_id')
                ->join('membership as m','c.id','=','m.branch_id')
                ->orderBy('m.id','DESC')
                ->where([
                    ['c.union_branch_id','=',$union_branch_id],
                    ['m.status_id',$status_cond,'1']
                    ]);
			}
		}else if($user_role=='company'){
			$company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id');
			if(count($company_id)>0){
				$companyid = $company_id[0];
				$member_qry = DB::table('company_branch as c')->select('c.id as cid','m.name','m.email','m.id','m.mobile','m.status_id as status_id','m.branch_id as branch_id')
                ->join('membership as m','c.id','=','m.branch_id')
                ->orderBy('m.id','DESC')
                ->where([
                    ['c.company_id','=',$companyid],
                    ['m.status_id',$status_cond,'1']
                    ]);
			}
		}else if($user_role=='company-branch'){
			$branch_id = CompanyBranch::where('user_id',$user_id)->pluck('id');
			if(count($branch_id)>0){
				$branchid = $branch_id[0];
				$member_qry = DB::table('company_branch as c')->select('c.id as cid','m.name','m.email','m.id','m.mobile','m.status_id as status_id','m.branch_id as branch_id')
                ->join('membership as m','c.id','=','m.branch_id')
                ->orderBy('m.id','DESC')
                ->where([
                    ['m.branch_id','=',$branchid],
                    ['m.status_id',$status_cond,'1']
                    ]);
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
				->select('c.id as cid','m.name','m.email','m.id','m.mobile','m.status_id as status_id','m.branch_id as branch_id','c.branch_name as branch_name','s.status_name as status_name')
                ->join('membership as m','c.id','=','m.branch_id')
				->leftjoin('status as s','s.id','=','m.status_id')
                ->where('m.status_id',$status_cond,'1');
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
			if( $limit != -1){
				$compQuery = $compQuery->offset($start)
				->limit($limit);
			}
			$memberslist = $compQuery->orderBy($order,$dir)
			->get()->toArray(); 
        
        }
        else {
            $search = $request->input('search.value'); 
        
			$compQuery = DB::table('company_branch as c')
							->select('c.id as cid','m.name','m.email','m.id','m.mobile','m.status_id as status_id','m.branch_id as branch_id','c.branch_name as branch_name','s.status_name as status_name')
							->join('membership as m','c.id','=','m.branch_id')
							->leftjoin('status as s','s.id','=','m.status_id')
							->where('m.status_id',$status_cond,'1');
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
                                ->orWhere('m.name', 'LIKE',"%{$search}%")
                                ->orWhere('m.email', 'LIKE',"%{$search}%")
                                ->orWhere('m.mobile', 'LIKE',"%{$search}%")
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
          
    }
	$data = array();
        if(!empty($memberslist))
        {
            foreach ($memberslist as $member)
            {
                $nestedData['branch_name'] = $member->branch_name;
                $nestedData['name'] = $member->name;
                $nestedData['email'] = $member->email;
                $nestedData['mobile'] = $member->mobile;
                $nestedData['status'] = $member->status_name;
                
                $enc_id = Crypt::encrypt($member->id);
				$delete = "";
                
                    $edit = route('master.editmembership', [app()->getLocale(),$enc_id]);
                

                $actions ="<a style='float: left;' id='$edit' onClick='showeditForm();' class='btn-floating waves-effect waves-light cyan modal-trigger' href='$edit'><i class='material-icons'>edit</i></a>";
                                
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

    

    
}


