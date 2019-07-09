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


class MemberController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
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
        //return $request->all();
		$user_role = '';
		$redirect_failurl = app()->getLocale().'/register';
		$redirect_url = app()->getLocale().'/login';
		if(!empty(Auth::user())){
			$user_id = Auth::user()->id;
			$get_roles = User::find($user_id)->roles;
			$user_role = $get_roles[0]->slug;
			$redirect_failurl = 'membership_register';
			$redirect_url = 'membership';
		}
        
        //return $request->all();
        $request->validate([
            'member_title'=>'required',
            'member_number'=>'required',
            'name'=>'required',
            'gender'=>'required',
            'phone'=>'required',
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
            'salary'=>'required',
            'new_ic'=>'required',
        ],
        [
            'member_title.required'=>'Please Enter Your Title',
            'member_number.required'=>'Please Enter Member NUmber',
            'name.required'=>'Please Enter Your Name',
            'gender.required'=>'Please choose Gender',
            'phone.required'=>'Please Enter Mobile Number',
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

                $member['member_title_id'] = $request->input('member_title');
                $member['member_number'] = $request->input('member_number');
                $member['name'] = $request->input('name');
                $member['gender'] = $request->input('gender');
                $member['phone'] = $request->input('phone');
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
                $member['status_id'] = $request->input('status_id');
                $member['user_id'] = $member_user->id;
                $member['employee_id'] =  $request->input('employee_id');
                $member['status'] = 1;
                if($user_role == 'union'){
                    $member['status_id'] = 2;
                }else{
                    $member['status_id'] = 1;
                }

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
                $doe = date('Y-m-d', strtotime($doj1));
                $member['doj'] = $doe;
                $member['old_ic'] = $request->input('old_ic');
                $member['new_ic'] = $request->input('new_ic');
                $member['branch_id'] = $request->input('branch_id');
                $member_id = $this->Membership->StoreMembership($member);

                if($member_id>0){
                    $guardian['member_id'] = $member_id;
                    $guardian['guardian_name'] = $request->input('guardian_name');
                    $guardian['gender'] = $request->input('guardian_sex');
                    $guardian['relationship_id'] = $request->input('g_relationship_id');
                    $guardian['nric_n'] = $request->input('nric_n_guardian');
                    $guardian['nric_o'] = $request->input('nric_o_guardian');
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

                    $gaurdian_id = $this->MemberGuardian->StoreMemberGaurdian($guardian);
        
                    // return $guardian; 
                    $check_fee_auto_id = $request->input('fee_auto_id');
                    if( isset($check_fee_auto_id)){
                        $fee_count = count($request->input('fee_auto_id'));
                        for($i =0; $i<$fee_count; $i++){
                            $fee_auto_id = $request->input('fee_auto_id')[$i];
                            $fee_name_id = $request->input('fee_name_id')[$i];
                            $fee_amount = $request->input('fee_name_amount')[$i];

                            $new_fee = new MemberFee();
                            $new_fee->member_id = $member_id;
                            $new_fee->fee_id = $fee_name_id;
                            $new_fee->fee_amount = $fee_amount;
                            $new_fee->status = 1;
                            $new_fee->save();
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

                            $nominee = new MemberNominees();

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

                    $mail_data = array(
                        'name' => $member_name,
                        'email' => $member_email,
                        'password' => $randompass,
                        'site_url' => URL::to("/"),
                    );
                    $status = Mail::to($member_email)->send(new SendMemberMailable($mail_data));

                    if( count(Mail::failures()) > 0 ) {
                        return redirect($redirect_url)->with('message','Member Account created successfully, Failed to send mail');
                    }else{
                        return redirect($redirect_url)->with('message','Member Account created successfully, password sent to mail');
                    }

                }else{
                    return redirect($redirect_failurl)->with('error','Failed to Register');
                }

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
                                   ['membership.user_id','=',$auth_user_id]
                                ])->get();

                               // $queries = DB::getQueryLog();
                              // dd($queries);

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
                    $data['branch_view'] = DB::table('branch')->where('status','=','1')->where('company_id', $company_id)->get();
                    $data['title_view'] = DB::table('persontitle')->where('status','=','1')->get();
                    $data['designation_view'] = DB::table('designation')->where('status','=','1')->get();
                    $data['race_view'] = DB::table('race')->where('status','=','1')->get();
                    $data['relationship_view'] = DB::table('relation')->where('status','=','1')->get();
                    $data['nominee_view'] = DB::table('member_nominees')->where('status','=','1')->where('member_id','=',$member_id)->get();
                    $data['gardian_view'] = DB::table('member_guardian')->where('status','=','1')->where('member_id','=',$member_id)->get();
                
                    $data['fee_list'] = DB::table('fee')->where('status','=','1')->get();
                    
                    $data['fee_view'] = DB::table('member_fee')->where('status','=','1')->where('member_id','=',$member_id)->get();
                // return  $data; 
                    return view('membership.edit_membership')->with('data',$data); 
            }else{
                return redirect( app()->getLocale().'/home'); 
            }
			 
		}
    }
    
    public function update(Request $request)
    {
        //return $request->all();
       
       // die;
        //return $request->all();
        if(!empty(Auth::user())){
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
            if($user_role=="member"){
                return redirect('edit-membership-profile')->with('message','Member Details Updated Succesfully');
            }else{
                return redirect('membership')->with('message','Member Details Updated Succesfully');
            }
            
        }else{
            return redirect('home')->with('message','Invalid access');
        }
    }
	
	/* public function register(Request $request)
    {
		$randompass = CommonHelper::random_password(5,true);
		
		$this->validate(request(), [
			'member_name' => 'required',
			'phone'=>'required',
			'company_id'=>'required',
			'branch_id'=>'required',
            'email' => 'required|email',
        ]);
		
		$member_role = Role::where('slug', 'member')->first();
		$status = 0;
		$new_user = new User();
	    $new_user->name = $request->member_name;
	    $new_user->email = $request->email;
	    $new_user->password = bcrypt($randompass);
		$new_user->save();
		$user_id =  $new_user->id;

		$New_member_user = new Membership();
		$New_member_user->name = $request->member_name;
		$New_member_user->phone = $request->phone;
		$New_member_user->branch_id = $request->branch_id;
		$New_member_user->email = $request->email;
		$New_member_user->user_id = $user_id;
		$New_member_user->status = 1;
		$New_member_user->status_id =1;
		$New_member_user->save();
		
	    $new_user->roles()->attach($member_role);
		
		$mail_data = array(
							'name' => $request->member_name,
							'email' => $request->email,
							'password' => $randompass,
							'site_url' => URL::to("/"),
						);
		
		if(!empty($new_user)){
			 $status = Mail::to($request->email)->send(new SendMemberMailable($mail_data));
		}
		if( count(Mail::failures()) > 0 ) {
			return redirect('/')->with('message','Account created successfully, Failed to send mail');
		}else{
			return redirect('/')->with('message','Account created successfully, please check your mail');
		}
    } */
}
