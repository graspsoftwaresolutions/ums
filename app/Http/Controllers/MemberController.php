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


class MemberController extends CommonController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		ini_set('memory_limit', '-1');
        //$this->middleware('auth');
		$this->Membership = new Membership;
        $this->MemberGuardian = new MemberGuardian;  
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		//$user = Auth::user();
		//dd($user->hasRole('developer'));
        return view('home');
    }
	
	public function Save(Request $request)
    {
        //return count($request->input('nominee_auto_id'));
       // return $request->all();
		$auto_id = $request->input('auto_id');
		$user_role = '';
		$redirect_failurl = app()->getLocale().'/membership';
		if($auto_id==''){
			$redirect_failurl = app()->getLocale().'/register';
		}
		if(!empty(Auth::user())){
			$user_id = Auth::user()->id;
			$get_roles = User::find($user_id)->roles;
			$user_role = $get_roles[0]->slug;
		}
        
        //return $request->all();
        $request->validate([
            'member_title'=>'required',
            'member_number'=>'required',
            'name'=>'required',
            'gender'=>'required',
            'mobile'=>'required',
            'email'=>'required',
            'doe'=>'required',
            'designation'=>'required',
            'race'=>'required',
            'country_id'=>'required',
            'state_id'=>'required',
            'city_id'=>'required',
            'postal_code'=>'required',
            'address_one'=>'required',
            'dob'=>'required',
            'doj'=>'required',
            'salary'=>'required',
            'new_ic'=>'required',
        ],
        [
            'member_title.required'=>'Please Enter Your Title',
            'member_number.required'=>'Please Enter Member NUmber',
            'name.required'=>'Please Enter Your Name',
            'gender.required'=>'Please choose Gender',
            'mobile.required'=>'Please Enter Mobile Number',
            'email.required'=>'Please Enter Email Address',
            'doe.required'=>'Please choose DOE',
            'designation.required'=>'Please choose  your Designation',
            'race.required'=>'Please Choose your Race ',
            'country_id.required'=>'Please choose  your Country',
            'state_id.required'=>'Please choose  your State',
            'city_id.required'=>'Please choose  your city',
            'postal_code.required'=>'Please Enter postal code',
            'address_one.required'=>'Please Enter your Address',
            'dob.required'=>'Please choose DOB',
            'salary.required'=>'Enter your Salary',
            'new_ic.required'=>'Please Enter New Ic Number',

        ]);

        $member_name = $request->input('name');
        $member_email = $request->input('email');

        if($member_name!="" &&  $member_email!=""){
			
			if($auto_id==''){
				$member_role = Role::where('slug', 'member')->first();
				$randompass = CommonHelper::random_password(5,true);

				$email_exists = DB::table('membership')->where([
					['email','=',$member_email],
					['status','=','1']
					])->count();
		
				$email_one_exists = DB::table('users')->where([
						['email','=',$member_email]])->count();

				if($email_exists > 0 || $email_one_exists > 0)
				{
					return redirect()->back()->withInput()->with('error','Email already Exists');
				}
				else{
					$member_user = new User();
					$member_user->name = $member_name;
					$member_user->email = $member_email;
					$member_user->password = bcrypt($randompass);
					$member_user->save();
					$member_user->roles()->attach($member_role);
					// return $member_user;die;
					$member['user_id'] = $member_user->id;
					
					if($user_role == 'union'){
						$member['is_request_approved'] = 1;
						$member['status_id'] = 1;
					}else{
						$member['is_request_approved'] = 0;
						$member['status_id'] = NUll;
					}
					
					// if($user_role == 'union'){
					// 	$member['status_id'] = 2;
					// }else{
					// 	$member['status_id'] = 1;
					// }
					$redirect_failurl = app()->getLocale().'/register';
					$redirect_url = app()->getLocale().'/login';
					if(!empty(Auth::user())){
						$redirect_failurl = app()->getLocale().'/membership_register';
						$redirect_url = app()->getLocale().'/membership';
					}
					
				}
				
			}else{
				if(!empty(Auth::user())){
					$user_id = Auth::user()->id;
					$get_roles = User::find($user_id)->roles;
					$user_role = $get_roles[0]->slug;
					
					$redirect_failurl = app()->getLocale().'/membership';
					$redirect_url = app()->getLocale().'/membership';
					if($user_role=="member"){
						$redirect_failurl = app()->getLocale().'/edit-membership-profile';
						$redirect_url = app()->getLocale().'/edit-membership-profile';
					}
					
					if($user_role=='union'){
						$activate_account = $request->input('activate_account');
						$activate_account = isset($activate_account) ? 1 : 0;
						if($activate_account==1){
							$member['is_request_approved'] = $activate_account;
						}
					}
					
					//return redirect('membership')->with('message','Member Details Updated Successfull');

				/* 	if($user_role=="member"){
						return redirect('edit-membership-profile')->with('message','Member Details Updated Succesfully');
					}else{
						return redirect(app()->getLocale().'/membership')->with('message','Member Details Updated Succesfully');
					} */
					
				}else{
					return redirect(app()->getLocale().'/home')->with('message','Invalid access');
				}
			}
			$member['member_title_id'] = $request->input('member_title');
			$member['member_number'] = $request->input('member_number');
			$member['name'] = $request->input('name');
			$member['gender'] = $request->input('gender');
			$member['mobile'] = $request->input('mobile');
			$member['email'] = $request->input('email');
			$member['designation_id'] = $request->input('designation');
			$member['old_member_number'] = $request->input('old_mumber_number');
			$member['salary'] = $request->input('salary');
			$member['postal_code'] = $request->input('postal_code');
			$member['race_id'] = $request->input('race');
			$member['country_id'] = $request->input('country_id');
			$member['state_id'] = $request->input('state_id');
			$member['city_id'] = $request->input('city_id');
			$member['address_one'] = $request->input('address_one');
			$member['address_two'] = $request->input('address_two');
			$member['address_three'] = $request->input('address_three');
			$member['levy'] = $request->input('levy');
			$member['levy_amount'] = $request->input('levy_amount');
			$member['tdf'] = $request->input('tdf');
			$member['tdf_amount'] = $request->input('tdf_amount');
			
			$member['employee_id'] =  $request->input('employee_id');
			$member['status'] = 1;
			
			$fm_date = explode("/",$request->input('dob'));         							
			$dob1 = $fm_date[2]."-".$fm_date[1]."-".$fm_date[0];
			$dob = date('Y-m-d', strtotime($dob1));
			$member['dob'] = $dob;

			$fmm_date = explode("/",$request->input('doe'));           							
			$doe1 = $fmm_date[2]."-".$fmm_date[1]."-".$fmm_date[0];
			$doe = date('Y-m-d', strtotime($doe1));
			$member['doe'] = $doe;

			$fmmm_date = explode("/",$request->input('doj'));           							
			$doj1 = $fmmm_date[2]."-".$fmmm_date[1]."-".$fmmm_date[0];
			$doj = date('Y-m-d', strtotime($doj1));
			$member['doj'] = $doj;
			
			$member['old_ic'] = $request->input('old_ic');
			$member['new_ic'] = $request->input('new_ic');
			$member['branch_id'] = $request->input('branch_id');
			//return $member;
			
			if($auto_id==''){
				$member_id = $this->Membership->StoreMembership($member);
				if(!$member_id){
					return redirect($redirect_failurl)->with('error','Failed to Register');
				}
			}else{
				$up_id = DB::table('membership')->where('id','=',$auto_id)->update($member);
				$member_id = $auto_id;
			}
			
			
			$g_nric_o=$request->input('nric_o_guardian');
			$g_nric_o=isset($g_nric_o) ? $g_nric_o: '';
			
			$guardian['member_id'] = $member_id;
			$guardian['guardian_name'] = $request->input('guardian_name');
			$guardian['gender'] = $request->input('guardian_sex');
			$guardian['relationship_id'] = $request->input('g_relationship_id');
			$guardian['nric_n'] = $request->input('nric_n_guardian');
			$guardian['nric_o'] = $g_nric_o;
			$guardian['address_one'] = $request->input('guardian_address_one');
			$guardian['country_id'] = $request->input('guardian_country_id');
			$guardian['state_id'] = $request->input('guardian_state_id');
			$guardian['city_id'] = $request->input('guardian_city_id');
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
			if($auto_id==''){
				$gaurdian_id = $this->MemberGuardian->StoreMemberGaurdian($guardian);
			}else{
				$gaurdian_count = MemberGuardian::where('member_id','=',$member_id)->count();
				if($gaurdian_count>0){
					$update_gaurd_id = $this->MemberGuardian->where('member_id','=',$member_id)->update($guardian);
				}else{
					$gaurdian_id = $this->MemberGuardian->StoreMemberGaurdian($guardian);
				}
				
			}
			$check_fee_auto_id = $request->input('fee_auto_id');
			if( isset($check_fee_auto_id)){
				$fee_count = count($request->input('fee_auto_id'));
				for($i =0; $i<$fee_count; $i++){
					$fee_auto_id = $request->input('fee_auto_id')[$i];
					$fee_name_id = $request->input('fee_name_id')[$i];
					$fee_amount = $request->input('fee_name_amount')[$i];
					
					if($fee_auto_id ==null){
						$new_fee = new MemberFee();
						$new_fee->member_id = $member_id;
						$new_fee->fee_id = $fee_name_id;
						$new_fee->fee_amount = $fee_amount;
						$new_fee->status = 1;
						$new_fee->save();
					}else{
						$old_fee = MemberFee::find($fee_auto_id);
						$old_fee->fee_id = $fee_name_id;
						$old_fee->fee_amount = $fee_amount;
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

					$nominee_nrico=isset($nominee_nrico) ? $nominee_nrico: '';
					
					if($nominee_auto_id ==null){
						$nominee = new MemberNominees();
					}else{
						$nominee = MemberNominees::find($nominee_auto_id);
					}

					$nominee->member_id = $member_id;
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
			if($auto_id==''){
				$mail_data = array(
					'name' => $member_name,
					'email' => $member_email,
					'password' => $randompass,
					'site_url' => URL::to("/"),
				);
				$cc_mail = CommonHelper::getCCTestMail();
				$status = Mail::to($member_email)->cc([$cc_mail])->send(new SendMemberMailable($mail_data));

				if( count(Mail::failures()) > 0 ) {
					return redirect($redirect_url)->with('message','Member Account created successfully, Failed to send mail');
				}else{
					return redirect($redirect_url)->with('message','Member Account created successfully, password sent to mail');
				}
			}else{
				 return redirect($redirect_url)->with('message','Member Details Updated Succesfully');
			}
        }else{
            return redirect($redirect_failurl)->with('error','Name and email is invalid');
        }

        
    }
	
	public function getoldMemberList(Request $request)
    {
        $search = $request->serachkey;
       // return $search;
        $res['suggestions'] = DB::table('membership')->select(DB::raw('CONCAT(membership.name, " - ", membership.member_number) AS value'),'membership.member_number as number')
            ->join('status','membership.status_id','=','status.id')        
        ->where([
            ['status.status_name','=','Inactive']
        ])->get();
        //$res['suggestions'] = [ array('value' => 'United States', 'data' => 'us'), array('value' => 'India', 'data' => 'in') ];
        //echo json_encode($res); die;
        return response()->json($res);
        
    }
	
	public function addNominee(Request $request){
       $nominee = new MemberNominees();
       $returndata = array('status' => 0, 'message' => '', 'data' => '');

       $nominee->member_id = $request->auto_id;
       $nominee->relation_id = $request->nominee_relationship;
       $nominee->nominee_name = $request->nominee_name;
       $nominee->country_id = $request->nominee_country_id;
       $nominee->state_id = $request->nominee_state_id;
       $nominee->postal_code = $request->nominee_postal_code;
       $nominee->city_id = $request->nominee_city_id;
       $nominee->address_one = $request->nominee_address_one;
       $nominee->address_two = $request->nominee_address_two;
       $nominee->address_three = $request->nominee_address_three;
       $nominee->gender = $request->nominee_sex;
       $nominee->nric_n = $request->nric_n;
       $nominee->nric_o = $request->nric_o;
       $nominee->mobile = $request->nominee_mobile;
       $nominee->phone = $request->nominee_phone;

       $fmmm_date = explode("/",$request->input('nominee_dob'));           							
       $dob1 = $fmmm_date[2]."-".$fmmm_date[1]."-".$fmmm_date[0];
       $dob = date('Y-m-d', strtotime($dob1));
       $nominee->dob =  $dob;
      
       $nominee->years =  Carbon::parse($dob)->age;

       //$nominee->save();
       if($nominee){
            $returndata = array('status' => 1, 'message' => 'Nominee added successfully', 'data' => array('age'=> $nominee->years,'relationship'=> CommonHelper::get_relationship_name($nominee->relation_id), 'nominee_id' => ''));
       }else{
            $returndata = array('status' => 0, 'message' => 'Failed to add', 'data' => '');
       }
       echo json_encode($returndata);
    }
	
	public function editMemberProfile(){
 
		$auth_user = Auth::user();
		$auth_user_id = Auth::user()->id;
		$check_member = $auth_user->hasRole('member');
		if($check_member == 0){
			abort(401);
		}else{
            $member_exists = DB::table('membership')->where([
                ['user_id','=',$auth_user_id],
                ['status','=','1']
                ])->count();
            if($member_exists){
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
                                   ['membership.user_id','=',$auth_user_id]
								])->get();
								
					//dd($data['member_view']);
                            //    $queries = DB::getQueryLog();
                            //   dd($queries);

                    $country_id = $data['member_view'][0]->country_id;
                    $member_id = $data['member_view'][0]->mid;
                
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
                    $data['nominee_view'] = DB::table('member_nominees')->where('status','=','1')->where('member_id','=',$member_id)->get();
                    $data['gardian_view'] = DB::table('member_guardian')->where('status','=','1')->where('member_id','=',$member_id)->get();
                
                    $data['fee_list'] = DB::table('fee')->where('status','=','1')->get();
					$data['irc_status'] = 0;
                    
                    $data['fee_view'] = DB::table('member_fee')->where('status','=','1')->where('member_id','=',$member_id)->get();
               
                    return view('membership.edit_membership')->with('data',$data); 
            }else{
                return redirect( app()->getLocale().'/home'); 
            }
			 
		}
	}
	
	public function checkMemberemailExists(Request $request){
		//return $request->all();
		$email =  $request->input('email');
        $db_autoid = $request->input('db_autoid');
		if($db_autoid=='' || $db_autoid==null)
        {
			return $memberexists = $this->membermailExists($email);
		}else{
			return $memberexists = $this->membermailExists($email,$db_autoid);
		}
		//return Response::json($return_status);
	}

	//getMembersList
	public function getMembersList(Request $request)
	{
	    $searchkey = $request->input('searchkey');
        $search = $request->input('query');
        $res['suggestions'] = DB::table('membership as m')->select(DB::raw('CONCAT(m.member_number, " - ", m.id) AS value'),'m.id as number','m.branch_id as branch_id')      
                            ->where(function($query) use ($search){
                                $query->orWhere('m.id','LIKE',"%{$search}%")
                                    ->orWhere('m.member_number', 'LIKE',"%{$search}%")
                                    ->orWhere('m.name', 'LIKE',"%{$search}%");
                            })->limit(25)
                            ->get();   
         return response()->json($res);
	}
	public function getMembersListValues(Request $request)
	{
		$searchkey = $request->input('searchkey');
		$search = $request->input('query');
		$res['suggestions'] = DB::table('membership as m')->select(DB::raw("if(count('m.new_ic') > 0  ,'m.new_ic','m.old_ic') as nric"),'m.id as memberid','d.dignation_name as membertype','p.person_title','cb.branch_name','r.race_name')
							->leftjoin('designation as d','d.id','=','m.designation_id')
							->leftjoin('persontitle as p','p.id','=','m.member_title_id')
							->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
							->leftjoin('company as c','c.id','=','cb.company_id')
							->leftjoin('race as r','r.id','=','m.race_id');
	}
}
