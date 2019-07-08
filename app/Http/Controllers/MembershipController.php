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
use App\Helpers\CommonHelper;
use App\Mail\SendMemberMailable;
use URL;
use Auth;
use Carbon\Carbon;


class MembershipController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
		$this->middleware('role:union|branch');
        $this->Membership = new Membership;
        $this->MemberGuardian = new MemberGuardian;       
    }
    public function index()
    {
        $auth_user = Auth::user();
                                                
        $check_union = $auth_user->hasRole('union');
        $branch_id = $auth_user->branch_id;
       
        $data['member_type'] = 1;
        if($check_union){
            $data['member_view'] = DB::table('membership')
            ->where('membership.status','=','1')->where('membership.status_id','!=','1')->get();
        }else{
			$data['member_view'] = DB::table('membership')
            ->where('membership.status','=','1')->where('membership.status_id','!=','1')->get();
           // $data['member_view'] = DB::table('membership')
            //->where('membership.status','=','1')->where('membership.status_id','!=','1')->where('branch_id','=',$branch_id)->get();
        }
       
        return view('membership.membership')->with('data',$data); 
       
    }
    public function view($id)
    {
        $id = Crypt::decrypt($id);
        $data['member_view'] = DB::table('membership')
                                ->join('country','membership.country_id','=','country.id')
                                ->join('state','membership.state_id','=','state.id')
                                ->join('city','membership.city_id','=','city.id')
                                ->join('branch','membership.branch_id','=','branch.id')
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
         
       
        return view('membership.add_membership')->with('data',$data);  
        
    }
    
    
    public function edit($id)
    {
       
        $id = Crypt::decrypt($id);
        //print_r($id) ;
         DB::connection()->enableQueryLog();
         $data['member_view'] = DB::table('membership')->select('membership.id as mid','membership.member_title_id','membership.member_number','membership.name','membership.gender','membership.designation_id','membership.email','membership.phone',
                                        'membership.country_id','membership.state_id','membership.city_id','membership.address_one','membership.address_two','membership.address_three','membership.race_id','membership.old_ic','membership.new_ic',
                                        'membership.dob','membership.doj','membership.doe','membership.postal_code','membership.salary','membership.status_id','branch_id','membership.password','membership.user_type','membership.status','country.id','country.country_name','country.status','state.id','state.state_name','state.status',
                                        'city.id','city.city_name','city.status','branch.id','branch.branch_name','branch.status','designation.id','designation.designation_name','designation.status','race.id','race.race_name','race.status','persontitle.id','persontitle.person_title','persontitle.status','membership.old_member_number','membership.employee_id')
                                ->leftjoin('country','membership.country_id','=','country.id')
                                ->leftjoin('state','membership.state_id','=','state.id')
                                ->leftjoin('city','membership.city_id','=','city.id')
                                ->leftjoin('branch','membership.branch_id','=','branch.id')
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
        $data['branch_view'] = DB::table('branch')->where('status','=','1')->where('company_id', $company_id)->get();
        $data['title_view'] = DB::table('persontitle')->where('status','=','1')->get();
        $data['designation_view'] = DB::table('designation')->where('status','=','1')->get();
        $data['race_view'] = DB::table('race')->where('status','=','1')->get();
        $data['relationship_view'] = DB::table('relation')->where('status','=','1')->get();
        $data['nominee_view'] = DB::table('member_nominees')->where('status','=','1')->where('member_id','=',$id)->get();
        $data['gardian_view'] = DB::table('member_guardian')->where('status','=','1')->where('member_id','=',$id)->get();
       
        $data['fee_list'] = DB::table('fee')->where('status','=','1')->get();
        
        $data['fee_view'] = DB::table('member_fee')->where('status','=','1')->where('member_id','=',$id)->get();
      // return  $data; 
        return view('membership.edit_membership')->with('data',$data); 
   
    }
    public function update(Request $request)
    {
        //return $request->all();
       
       // die;
        //return $request->all();
		$user_id = Auth::user()->id;
        $get_roles = User::find($user_id)->roles;
        $user_role = $get_roles[0]->slug;
        $id = $request->input('auto_id');
        
         $fm_date = explode("/",$request->input('dob'));         							
         $dob1 = $fm_date[2]."-".$fm_date[1]."-".$fm_date[0];
         $dob = date('Y-m-d', strtotime($dob1));

         $fmm_date = explode("/",$request->input('doe'));           							
         $doe1 = $fmm_date[2]."-".$fmm_date[1]."-".$fmm_date[0];
         $doe = date('Y-m-d', strtotime($doe1));
         $member['doe'] = $doe;

         $fmmm_date = explode("/",$request->input('doj'));           							
         $doj1 = $fmmm_date[2]."-".$fmmm_date[1]."-".$fmmm_date[0];
         $doe = date('Y-m-d', strtotime($doj1));
       
        $member['member_title_id'] = $request->input('member_title');
        $member['member_number'] = $request->input('member_number');
        $member['name'] = $request->input('name');
        $member['gender'] = $request->input('gender');
        $member['phone'] = $request->input('phone');
        //$member['email'] = $request->input('email');
        $member['designation_id'] = $request->input('designation');
       // $member['race_id'] = 1;
        $member['country_id'] = $request->input('country_id');
        $member['state_id'] = $request->input('state_id');
        $member['city_id'] = $request->input('city_id');
        $member['address_one'] = $request->input('address_one');
        $member['address_two'] = $request->input('address_two');
        $member['address_three'] = $request->input('address_three');
        $member['dob'] = $dob;
        $member['old_ic'] = $request->input('old_ic');
        $member['new_ic'] = $request->input('new_ic');
        $member['branch_id'] = $request->input('branch_id');
        $member['employee_id'] = $request->input('employee_id');
		if($user_role=='union'){
			$activate_account = $request->input('activate_account');
			$activate_account = isset($activate_account) ? 2 : 1;
			if($activate_account==2){
				$member['status_id'] = $activate_account;
			}
		}
        //$member['race_id'] = 1;
        //return $member;

        $up_id = DB::table('membership')->where('id','=',$id)->update($member);
        //return redirect('membership')->with('message','Member Details Updated Successfull');

        //Guardian Edit/Insert
        $member_guardian_id = $id;
        $guardian['member_id'] = $member_guardian_id;
        $guardian['guardian_name'] = $request->input('guardian_name');
        $guardian['gender'] = $request->input('guardian_sex');
        $guardian['relationship_id'] = $request->input('relationship_id');
        $guardian['nric_n'] = $request->input('nric_n_guardian');
        $guardian['nric_o'] = $request->input('nric_o_guardian');
        $guardian['address_one'] = $request->input('guardian_address_one');
        $guardian['country_id'] = $request->input('guardian_country_id');
        $guardian['state_id'] = $request->input('guardian_state_id');
        $guardian['city_id'] = $request->input('guardiancity_id'); 
        $guardian['address_two'] = $request->input('guardian_address_two');
        $guardian['postal_code'] = $request->input('guardian_postal_code');
        $guardian['address_three'] = $request->input('guardian_address_three');
        $guardian['mobile'] = $request->input('guardian_mobile');
        $guardian['phone'] = $request->input('guardian_phone');
		
		$guardian_dob  = $request->input('gaurdian_dob');

		if($guardian_dob!=""){
			$fmmm_date = explode("/",$guardian_dob);
			$dob1 = $fmmm_date[2]."-".$fmmm_date[1]."-".$fmmm_date[0];
			$dob = date('Y-m-d', strtotime($dob1));
			$guardian['dob'] =  $dob;
		}

		$check_fee_auto_id = $request->input('fee_auto_id');
        if( isset($check_fee_auto_id)){
			$feecount = count($request->input('fee_auto_id'));
			for($i=0; $i<$feecount; $i++){
				$fee_auto_id = $request->input('fee_auto_id')[$i];
				$fee_name_id = $request->input('fee_name_id')[$i];
				$fee_name_amount = $request->input('fee_name_amount')[$i];
				if($fee_auto_id ==null){
					$new_fee = new MemberFee();
					$new_fee->member_id = $id;
					$new_fee->fee_id = $fee_name_id;
					$new_fee->fee_amount = $fee_name_amount;
					$new_fee->status = 1;
					$new_fee->save();
				}else{
					$old_fee = MemberFee::find($fee_auto_id);
					$old_fee->fee_id = $fee_name_id;
					$old_fee->fee_amount = $fee_name_amount;
					$old_fee->save();
				}
			}
		}
		
		$check_nominee_auto_id = $request->input('nominee_auto_id');
		if( isset($check_nominee_auto_id)){
			$nominee_count = count($request->input('nominee_auto_id'));
			for($j =0; $j<$nominee_count; $j++){
				$nominee_auto_id = $request->input('nominee_auto_id')[$j];
				$nominee_name = $request->input('nominee_name_value')[$j];
				$nominee_age = $request->input('nominee_age_value')[$j];
				$nominee_dob = $request->input('nominee_dob_value')[$j];
				$nominee_gender = $request->input('nominee_gender_value')[$j];
				$nominee_relation = $request->input('nominee_relation_value')[$j];
				$nominee_nricn = $request->input('nominee_nricn_value')[$j];
				$nominee_nrico = $request->input('nominee_nrico_value')[$j];
				$nominee_address_one = $request->input('nominee_addressone_value')[$j];
				$nominee_address_two = $request->input('nominee_addresstwo_value')[$j];
				$nominee_address_three = $request->input('nominee_addressthree_value')[$j];
				$nominee_country = $request->input('nominee_country_value')[$j];
				$nominee_state = $request->input('nominee_state_value')[$j];
				$nominee_city = $request->input('nominee_city_value')[$j];
				$nominee_postalcode = $request->input('nominee_postalcode_value')[$j];
				$nominee_mobile = $request->input('nominee_mobile_value')[$j];
				$nominee_phone = $request->input('nominee_phone_value')[$j];
				
				if($nominee_auto_id ==null){
					$nominee = new MemberNominees();
				}else{
					$nominee = MemberNominees::find($nominee_auto_id);
				}

				$nominee->member_id = $id;
				$nominee->relation_id = $nominee_relation;
				$nominee->nominee_name = $nominee_name;
				$nominee->country_id = $nominee_country;
				$nominee->state_id = $nominee_state;
				$nominee->postal_code = $nominee_postalcode;
				$nominee->city_id = $nominee_city;
				$nominee->address_one = $nominee_address_one;
				$nominee->address_two = $nominee_address_two;
				$nominee->address_three = $nominee_address_three;
				$nominee->gender = $nominee_gender;
				$nominee->nric_n = $nominee_nricn;
				$nominee->nric_o = $nominee_nrico;
				$nominee->mobile = $nominee_mobile;
				$nominee->phone = $nominee_phone;
				
				if($nominee_dob!=""){
					$fmmm_date = explode("/",$nominee_dob);           							
					$dob1 = $fmmm_date[2]."-".$fmmm_date[1]."-".$fmmm_date[0];
					$dob = date('Y-m-d', strtotime($dob1));
					$nominee->dob =  $dob;
				}
			   
				$nominee->save();
			}
		}
        //return $guardian; 

        $id = $this->MemberGuardian->where('member_id','=',$member_guardian_id)->update($guardian);
        return redirect('membership')->with('message','Member Details Updated Succesfully');

    }
    public function delete($id)
	{
		$data = DB::table('membership')->where('id','=',$id)->update(['status'=>'0']);
		return redirect('membership')->with('message','Member Deleted Succesfully');
    }
    

    public function new_members(){
        $data['member_type'] = 0;
        $auth_user = Auth::user();
        $check_union = $auth_user->hasRole('union');
        $branch_id = $auth_user->branch_id;
        if($check_union){
            $data['member_view'] = DB::table('membership')
            ->where('membership.status','=','1')->where('membership.status_id','=','1')->get();
        }else{
			$data['member_view'] = DB::table('membership')
            ->where('membership.status','=','1')->where('membership.status_id','=','1')->get();
            //$data['member_view'] = DB::table('membership')
            //->where('membership.status','=','1')->where('membership.status_id','=','1')->where('branch_id','=',$branch_id)->get();
        }
        

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

    

    
}


