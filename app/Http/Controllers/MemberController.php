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
use App\Model\Resignation;
use App\Model\Membermonthendstatus;
use App\Helpers\CommonHelper;
use App\Mail\SendMemberMailable;
use URL;
use Auth;
use Artisan;
use Facades\App\Repository\CacheMembers;
use App\Model\MonthlySubscription;
use App\Model\MonthlySubscriptionCompany;
use App\Model\MonthlySubscriptionMember;
use App\Model\MonSubCompanyAttach;
use App\Model\MonthlySubMemberMatch;
use Log;


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
        $this->membermonthendstatus_table = "membermonthendstatus";
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
		if($user_role=='data-entry'){
			$user_role='union';
		}
        
        //return $request->all();
        $request->validate([
            'member_title'=>'required',
            //'member_number'=>'required',
            'name'=>'required',
            'gender'=>'required',
            'mobile'=>'required',
            //'email'=>'required',
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
            //'member_number.required'=>'Please Enter Member NUmber',
            'name.required'=>'Please Enter Your Name',
            'gender.required'=>'Please choose Gender',
            'mobile.required'=>'Please Enter Mobile Number',
            //'email.required'=>'Please Enter Email Address',
            'doe.required'=>'Please choose DOE',
            'designation.required'=>'Please choose  your Designation',
            'race.required'=>'Please Choose your Race ',
            'country_id.required'=>'Please choose  your Country',
            'state_id.required'=>'Please choose  your State',
            'city_id.required'=>'Please choose  your city',
            'postal_code.required'=>'Please Enter postal code',
            'address_one.required'=>'Please Enter your Address',
			'dob.required'=>'Please choose DOB',
			'doj.required'=>'Please choose DOJ',
            'salary.required'=>'Enter your Salary',
            'new_ic.required'=>'Please Enter New Ic Number',

        ]);

        $member_name = $request->input('name');
		$member_email = $request->input('email');

		

		$number_count = 0;
        if($member_name!=""){
			
			if($auto_id==''){

				if($request->input('member_number')!=""){
					$number_count = DB::table('membership')->where('member_number', $request->input('member_number'))->count();
				}
				
				if($number_count>0){
					$member_number = CommonHelper::get_auto_member_number();
				}else{
					$member_number = $request->input('member_number');
				}

				if($member_email==''){
					$membername_nospace = strtolower(str_replace(' ', '', $member_name));
					$member_email = $membername_nospace.'_'.$member_number.'@gmail.com';
				}

				$member_role = Role::where('slug', 'member')->first();
				//$randompass = CommonHelper::random_password(5,true);
				$randompass = bcrypt('nube12345');
				$randompassone = 'nube12345';

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
					$old_ic = $request->input('old_ic');
					$new_ic = $request->input('new_ic');
					$employee_id = $request->input('employee_id');

					if($new_ic!=''){
						$randompass = bcrypt($new_ic);
						$randompassone = $new_ic;
					}else if($old_ic!=''){
						$randompass = bcrypt($old_ic);
						$randompassone = $old_ic;
						//return 1;
					}else if($employee_id!=''){
						$randompass = bcrypt($employee_id);
						$randompassone = $employee_id;
						//return 1;
					}
					//return $randompass;

					$member_user = new User();
					$member_user->name = $member_name;
					$member_user->email = $member_email;
					$member_user->password = $randompass;
					$member_user->save();
					$member_user->roles()->attach($member_role);
					// return $member_user;die;
					$member['user_id'] = $member_user->id;
					$member['old_member_number'] = $request->input('old_member_id');
					
					if($user_role == 'union' || $user_role == 'data-entry'){
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
				$member_email = $request->input('email');
				$member_number = $request->input('member_number');
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
					if($user_role=='union' || $user_role=='data-entry'){
						$activate_account = $request->input('activate_account');
						$activate_account = isset($activate_account) ? 1 : 0;
						if($activate_account==1){

							$member['is_request_approved'] = $activate_account;
							$member['status_id'] = 1;
							
							if($auto_id!=''){
								// $new_fee = new MemberFee();
								// $new_fee->member_id = $auto_id;
								// $new_fee->status = 1;
								// $new_fee->flag = 0;
								// $new_fee->save();
								// if($new_fee == true)
								// {
								// 	$fee_id = DB::table('member_fee')->select('fee_id')->where('member_id','=',$auto_id)->count();
								// 	$feeline_id = DB::table('member_fee')->select('fee_id')->where('member_id','=',$auto_id)->first();
								// 	if($fee_id > 0)
								// 	{
								// 		
								// 		$new_monthend =	DB::insert('insert into membermonthendstatus(MEMBER_CODE) values (?)', array($auto_id));
								// 		if($new_monthend == true)
								// 		{
								// 			$update = DB::table('member_fee')->where('member_id','=',$auto_id)->update(['flag'=>'1']);
								// 		}
								// 	}
								// }
							}else{
								return 0;
							}
						}
						$edit_status_id = $request->input('status_id');
						if(isset($edit_status_id)){
							$member['status_id'] = $edit_status_id;
						}
					}

					$db_member_email = DB::table('membership')->where('id', $auto_id)->pluck('email')->first();
					if($member_email!=$db_member_email){
						
						$new_email_exists = DB::table('membership')->where([
							['email','=',$member_email],
							['status','=','1']
							])->count();
				
						$new_email_one_exists = DB::table('users')->where([
								['email','=',$member_email]])->count();
						
						if($new_email_exists > 0 || $new_email_one_exists > 0)
						{
							return redirect()->back()->withInput()->with('error','Email already Exists');
						}else{
							$db_user_id = DB::table('membership')->where('id', $auto_id)->pluck('user_id')->first();
							$user_data = [
								'email' => $member_email,
							];
							DB::table('users')->where('id', $db_user_id)->update($user_data);
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
			
			// if(!empty($request->input('member_number'))){
			// 	$member['member_number']  = $request->input('member_number');
			// }
			$member['member_number'] = $member_number;
			$member['name'] = $request->input('name');
			$member['gender'] = $request->input('gender');
			$member['mobile'] = $request->input('mobile');
			$member['email'] = $member_email;
			$member['designation_id'] = $request->input('designation');
			$member['old_member_number'] = $request->input('old_member_id');
			//$member['old_member_number'] = $request->input('old_mumber_number');
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
			//return $request->input('dob');     						
			$dob1 = $fm_date[2]."-".$fm_date[1]."-".$fm_date[0];
			$dob = date('Y-m-d', strtotime($dob1));
			//return $dob;        	
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
				//$member['member_number'] = CommonHelper::get_auto_member_number();
				//dd($member);
				//$update_memno = DB::table('membership')->where('id','=',$member_id)->update($member);
				
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
			if($auto_id!=''){
				$statuscode = DB::table('membership')->where('id', $member_id)->pluck('status_id')->first();
				$resign_date = $request->input('resign_date');
				$last_paid = $request->input('last_paid');
				$resign_claimer = $request->input('resign_claimer');
				$resign_reason = $request->input('resign_reason');
				$resignstatus = $request->input('resignstatus');
				if($resign_date!="" && $last_paid!="" && $resign_reason!="" && $resignstatus==1){
					$check_resign_exists = Resignation::where('member_code','=',$member_id)->count();
					if($check_resign_exists>0){
						$resign = Resignation::where('member_code','=',$member_id)->first();
					}else{
						$resign = new Resignation();
						$resign->member_code = $member_id;
					}
					$resign->resignation_date = CommonHelper::ConvertdatetoDBFormat($resign_date);
					$resign->resignstatus_code = $statuscode;
					$resign->relation_code = $resign_claimer;
					$resign->reason_code = $resign_reason;
					$resign->claimer_name = $request->input('claimer_name');
					$resign->months_contributed = $request->input('contributed_months');
					$resign->service_year = $request->input('service_year');
					$resign->accbf = $request->input('bf_contribution');
					$resign->accbenefit = $request->input('benefit_amount');
					$resign->benefit_year = $request->input('benefit_year');
					$resign->months_contributed_till_may = $request->input('may_contributed_months');
					$resign->amount = $request->input('total_amount');
					$resign->totalarrears = $request->input('totalarrears');
					$resign->paymode = $request->input('pay_mode');
					$resign->chequeno = $request->input('reference_number');
					$resign->unioncontribution = $request->input('union_contribution');
					$resign->insuranceamount = $request->input('insurance_amount');
					$resign->chequedate = CommonHelper::ConvertdatetoDBFormat($request->input('cheque_date'));
					$resign->voucher_date = CommonHelper::ConvertdatetoDBFormat($request->input('payment_confirmation'));
					$resign->icno = $member['new_ic'];
					$resign->icno_old = $member['old_ic'];
					$resign->entry_date = date('Y-m-d');
					$resign->created_by = Auth::user()->id;
					$resign->created_at = date('Y-m-d');
					$resign->save();
					if($check_resign_exists==0 && $resign && $resignstatus==1){
						$resmember = Membership::find($member_id);
						$resmember['status_id'] = 4;
						$resmember->save();
						$enc_id = Crypt::encrypt($member_id);
						$redirect_url_pdf = app()->getLocale().'/resign-pdf/'.$enc_id;
						return redirect($redirect_url_pdf)->with('message','Member has resigned successfully');
					}
					
				}
			}
			Artisan::call('cache:clear');

			if(!empty(Auth::user())){
				$created_by = Auth::user()->id;
			}else{
				$created_by = $member_user->id;
			}
			$hq_amt=0;
			$ent_amt=0;
			$hqfeecount = DB::table('member_fee as mf')
			->select('f.fee_shortcode','mf.fee_amount as fee_amount')
			->leftjoin('fee as f','f.id','=','mf.fee_id')
			->where('mf.member_id','=',$member_id)
			->where('f.fee_shortcode','=','BUF');
			if($hqfeecount->count()==1){
				$hq_amt = $hqfeecount->pluck('mf.fee_amount')->first();
			}
			$entfeecount = DB::table('member_fee as mf')
			->select('f.fee_shortcode','mf.fee_amount as fee_amount')
			->leftjoin('fee as f','f.id','=','mf.fee_id')
			->where('mf.member_id','=',$member_id)
			->where('f.fee_shortcode','=','EF');
			if($entfeecount->count()==1){
				$ent_amt = $entfeecount->pluck('mf.fee_amount')->first();
			}
			$payment_data = [
				'entrance_fee' => $ent_amt,
				'hq_fee' => $hq_amt,
				'member_id' => $member_id,
				'due_amount' => 0,
				'created_by' => $created_by,
				'created_at' => date('Y-m-d'),
			];
			$update_history = 0;
			if($user_role == 'union'){
				$memberdata = Membership::find($member_id);
				$branch_data = CacheMembers::getbranchbyBranchid($memberdata->branch_id);
				$feedata = DB::table('member_fee as mf')
							->select('f.fee_shortcode','mf.fee_amount as fee_amount')
							->leftjoin('fee as f','f.id','=','mf.fee_id')
							->where('mf.member_id','=',$member_id)
							->where(function($query) use ($member_id){
									$query->orWhere('f.fee_shortcode','=','INS')
										->orWhere('f.fee_shortcode','=','BF');
								})       
							->get();
				$update_history = 0;
				if(count($feedata)==2 && $memberdata->is_request_approved==1 && $memberdata->status_id<=2){
					
					//return 1;
					if($memberdata->salary>0){
						$update_history = 1;
						$subsamount = CommonHelper::getsubscription_bysalary($memberdata->salary);
						$bf_amt = 0;
						$ins_amt = 0;
						foreach ($feedata as $key => $value) {
							if($value->fee_shortcode=='BF'){
								$bf_amt = $value->fee_amount;
							}
							elseif($value->fee_shortcode=='INS'){
								$ins_amt = $value->fee_amount;
							}
						}
						$doj = $memberdata->doj;
						$subs_month = date('Y-m-01',strtotime($doj));
						$mont_count = DB::table($this->membermonthendstatus_table)->where('StatusMonth', '=', $subs_month)->where('MEMBER_CODE', '=', $member_id)->count();
						
						$monthend_data = [
												'StatusMonth' => $subs_month, 
												'MEMBER_CODE' => $member_id,
												'SUBSCRIPTION_AMOUNT' => $subsamount,
												'BF_AMOUNT' => $bf_amt,
												'LASTPAYMENTDATE' => $subs_month,
												'TOTALSUBCRP_AMOUNT' => $subsamount,
												'TOTALBF_AMOUNT' => $bf_amt,
												'TOTAL_MONTHS' => 1,
												'BANK_CODE' => $branch_data->company_id,
												'NUBE_BRANCH_CODE' => $branch_data->union_branch_id,
												'BRANCH_CODE' => $memberdata->branch_id,
												'MEMBERTYPE_CODE' => $memberdata->designation_id,
												//'ENTRYMODE' => 0,
												//'DEFAULTINGMONTHS' => 0,
												'TOTALMONTHSDUE' => 0,
												'TOTALMONTHSPAID' => 1,
												'SUBSCRIPTIONDUE' => 0,
												'BFDUE' => 0,
												'ACCSUBSCRIPTION' => $subsamount,
												'ACCBF' => $bf_amt,
												'ACCBENEFIT' => $bf_amt,
												//'CURRENT_YDTBF' => 0,
												//'CURRENT_YDTSUBSCRIPTION' => 0,
												'STATUS_CODE' => $memberdata->status_id,
												'RESIGNED' => $memberdata->status_id==4 ? 1 : 0,
												'ENTRY_DATE' => date('Y-m-d'),
												'ENTRY_TIME' => date('h:i:s'),
												'STRUCKOFF' => $memberdata->status_id==3 ? 1 : 0,
												'INSURANCE_AMOUNT' => $ins_amt,
												'TOTALINSURANCE_AMOUNT' => $ins_amt,
												'TOTALMONTHSCONTRIBUTION' => 1,
												'INSURANCEDUE' => 0,
												'ACCINSURANCE' => $ins_amt,
												//'CURRENT_YDTINSURANCE' => 0,
											];
							
						if($mont_count>0){
							DB::table($this->membermonthendstatus_table)->where('StatusMonth', $subs_month)->where('MEMBER_CODE', $member_id)->update($monthend_data);
						}else{
							DB::table($this->membermonthendstatus_table)->insert($monthend_data);
						}

						$subscription_qry = MonthlySubscription::where('Date','=',$subs_month);
						$subscription_count = $subscription_qry->count();
						if($subscription_count>0){
							$subscription_month = $subscription_qry->get();
							$month_auto_id = $subscription_month[0]->id;
						}else{
							$subscription_month = new MonthlySubscription();
							$subscription_month->Date = $subs_month;
							$subscription_month->created_by = Auth::user()->id;
							$subscription_month->created_on = date('Y-m-d');
							$subscription_month->save();
							$month_auto_id =  $subscription_month->id;
						}
						
						$subscription_company_qry = MonthlySubscriptionCompany::where('MonthlySubscriptionId','=',$month_auto_id)->where('CompanyCode',$branch_data->company_id);
						$subscription_company_count = $subscription_company_qry->count();
						if($subscription_company_count>0){
							$subscription_company =$subscription_company_qry->get();
							$company_auto_id = $subscription_company[0]->id;
						}else{
							$subscription_company = new MonthlySubscriptionCompany();
							$subscription_company->MonthlySubscriptionId = $month_auto_id;
							$subscription_company->CompanyCode = $branch_data->company_id;
							$subscription_company->created_by = Auth::user()->id;
							$subscription_company->created_on = date('Y-m-d');
							$subscription_company->save();
					
							$company_auto_id =  $subscription_company->id;
						}

						$nric_no = $memberdata->new_ic;
						if($nric_no==''){
							$nric_no = $memberdata->old_ic;
						}
						if($nric_no==''){
							$nric_no = $memberdata->employee_id;
						}
						if($nric_no!='' && $nric_no!=Null){
							$subscription_member_qry = MonthlySubscriptionMember::where('MonthlySubscriptionCompanyId','=',$company_auto_id)
                                                ->where('MemberCode',$member_id);
                  			$subscription_member_count = $subscription_member_qry->count();

                  			if($subscription_member_count>0){
		                        $subscription_member_res = MonthlySubscriptionMember::where('MonthlySubscriptionCompanyId','=',$company_auto_id)
		                        ->where('MemberCode',$member_id)->get();
		                        $company_member_id = $subscription_member_res[0]->id;
		                        $subscription_member = MonthlySubscriptionMember::find($company_member_id);
		                    }else{
		                        $subscription_member = new MonthlySubscriptionMember();
		                        $subscription_member->MonthlySubscriptionCompanyId = $company_auto_id;
		                    }
		                    $subscription_member->NRIC = $nric_no;
		                    $subscription_member->Name = $memberdata->name;
		                    $subscription_member->Amount = $subsamount+$bf_amt+$ins_amt;
		                    $subscription_member->StatusId = $memberdata->status_id;
		                    $subscription_member->update_status = 1;
		                    $subscription_member->MemberCode = $member_id;
		                    $subscription_member->created_by = Auth::user()->id;
							$subscription_member->created_on = date('Y-m-d');
							$subscription_member->approval_status =1;
							$subscription_member->save();
							
							$subMemberMatch = new MonthlySubMemberMatch();
							$subMemberMatch->mon_sub_member_id = $subscription_member->id;
							$subMemberMatch->created_by = Auth::user()->id;
							$subMemberMatch->created_on = date('Y-m-d');
							$subMemberMatch->match_id = 1;
							$subMemberMatch->approval_status =1;
							$subMemberMatch->save();
						}

					}
					$payment_data = [
						'entrance_fee' => $ent_amt,
						'hq_fee' => $hq_amt,
						'last_paid_date' => $subs_month, 
						'member_id' => $member_id,
						'due_amount' => 0,
						'totpaid_months' => 1,
						'ins_monthly_amount' => $ins_amt,
						'sub_monthly_amount' => $subsamount,
						'bf_monthly_amount' => $bf_amt,
						'accbf_amount' => $bf_amt,
						'accsub_amount' => $subsamount,
						'accins_amount' => $ins_amt, 
						'created_by' => Auth::user()->id,
						'created_at' => date('Y-m-d'),
					];
					// /return $payment_data;
				}else{
					$subs_month = date('Y-m-01',strtotime($doj));
					$member_subs_id = DB::table("mon_sub_member as mm")->select('mm.id as msid')
					->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
					->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
					->where('ms.Date', '=', $subs_month)
					->where('mm.MemberCode', '=', $member_id)
					->pluck('msid')->first();

					if($member_subs_id!=''){
						$mont_count = DB::table('mon_sub_member_match')->where('mon_sub_member_id', '=', $member_subs_id)->delete();
						$mont_count = DB::table('mon_sub_member')->where('id', '=', $member_subs_id)->delete();
					}

					$mont_count = DB::table($this->membermonthendstatus_table)->where('StatusMonth', '=', $subs_month)->where('MEMBER_CODE', '=', $member_id)->delete();
					$payment_data = [
						'entrance_fee' => $ent_amt,
						'hq_fee' => $hq_amt,
						'member_id' => $member_id,
						'due_amount' => 0,
						'created_by' => Auth::user()->id,
						'created_at' => date('Y-m-d'),
					];
				}
			}else{
				$memberdata = Membership::find($member_id);
				$doj = $memberdata->doj;
				$subs_month = date('Y-m-01',strtotime($doj));
				$feecount = DB::table('member_fee as mf')
				->select('f.fee_shortcode','mf.fee_amount as fee_amount')
				->leftjoin('fee as f','f.id','=','mf.fee_id')
				->where('mf.member_id','=',$member_id)
				->where(function($query) use ($member_id){
						$query->orWhere('f.fee_shortcode','=','INS')
							->orWhere('f.fee_shortcode','=','BF');
					})       
				->count();
				if($feecount<2){
					$mont_count = DB::table($this->membermonthendstatus_table)->where('StatusMonth', '=', $subs_month)->where('MEMBER_CODE', '=', $member_id)->delete();

					$subs_month = date('Y-m-01',strtotime($doj));
					$member_subs_id = DB::table("mon_sub_member as mm")->select('mm.id')
					->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
					->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
					->where('ms.Date', '=', $subs_month)
					->where('mm.MemberCode', '=', $member_id)
					->first();

					if($member_subs_id!=''){
						$mont_count = DB::table('mon_sub_member')->where('id', '=', $member_subs_id)->delete();
					}
				}
			}
			$member_pay_count = DB::table('member_payments')->where('member_id', '=', $member_id)->count();
			
			if($member_pay_count==0){
				DB::table('member_payments')->insert($payment_data);
			}

			if($update_history==1){
					
				//update below history
				$last_mont_record = DB::table($this->membermonthendstatus_table." as ms")
				->select('ms.StatusMonth','ms.LASTPAYMENTDATE','ms.ACCBF','ms.ACCSUBSCRIPTION','ms.SUBSCRIPTION_AMOUNT','ms.BF_AMOUNT','ms.TOTALMONTHSPAID','ms.ACCINSURANCE','ms.TOTALMONTHSDUE','ms.SUBSCRIPTIONDUE','ms.TOTALMONTHSCONTRIBUTION','ms.INSURANCEDUE','ms.BFDUE','ms.INSURANCE_AMOUNT','ms.TOTAL_MONTHS')
				->where('StatusMonth', '<', $subs_month)->where('MEMBER_CODE', '=', $member_id)
				->orderBY('StatusMonth','desc')
				->limit(1)
				->first();

				$below_mont_records = DB::table($this->membermonthendstatus_table." as ms")
				->select('ms.StatusMonth','ms.Id','ms.SUBSCRIPTION_AMOUNT','ms.BF_AMOUNT','ms.INSURANCE_AMOUNT','ms.TOTAL_MONTHS')
				->where('StatusMonth', '>=', $subs_month)->where('MEMBER_CODE', '=', $member_id)
				->orderBY('StatusMonth','asc')
				->get();

				$last_ACCINSURANCE = !empty($last_mont_record) ? $last_mont_record->ACCINSURANCE : 0;
				$last_ACCSUBSCRIPTION = !empty($last_mont_record) ? $last_mont_record->ACCSUBSCRIPTION : 0;
				$last_ACCBF = !empty($last_mont_record) ? $last_mont_record->ACCBF : 0;
				$last_SUBSCRIPTIONDUE = !empty($last_mont_record) ? $last_mont_record->SUBSCRIPTIONDUE : 0;
				$last_BFDUE = !empty($last_mont_record) ? $last_mont_record->BFDUE : 0;
				$last_INSURANCEDUE = !empty($last_mont_record) ? $last_mont_record->INSURANCEDUE : 0;
				$last_TOTALMONTHSDUE = !empty($last_mont_record) ? $last_mont_record->TOTALMONTHSDUE : 0;
				$last_TOTALMONTHSPAID = !empty($last_mont_record) ? $last_mont_record->TOTALMONTHSPAID : 0;
				$last_TOTALMONTHSCONTRIBUTION = !empty($last_mont_record) ? $last_mont_record->TOTALMONTHSCONTRIBUTION : 0;
				$last_paid_date = !empty($last_mont_record) ? $last_mont_record->LASTPAYMENTDATE : $subs_month;
				
				foreach($below_mont_records as $monthend){
					$m_subs_amt = $monthend->SUBSCRIPTION_AMOUNT;
					$m_bf_amt = $monthend->BF_AMOUNT;
					$m_ins_amt = $monthend->INSURANCE_AMOUNT;
					$m_total_months = $monthend->TOTAL_MONTHS;

					if($m_total_months==1){
						$new_ACCINSURANCE = $last_ACCINSURANCE+$m_ins_amt;
						$new_ACCSUBSCRIPTION = $last_ACCSUBSCRIPTION+$m_subs_amt;
						$new_ACCBF = $last_ACCBF+$m_bf_amt;
						$new_SUBSCRIPTIONDUE = $last_SUBSCRIPTIONDUE;
						$new_BFDUE = $last_BFDUE;
						$new_INSURANCEDUE = $last_INSURANCEDUE;
						$new_TOTALMONTHSDUE = $last_TOTALMONTHSDUE;
						$new_TOTALMONTHSPAID = $last_TOTALMONTHSPAID+1;
						$new_TOTALMONTHSCONTRIBUTION = $last_TOTALMONTHSCONTRIBUTION+1;
						$last_paid_date = $monthend->StatusMonth;
					}else{
						$new_ACCINSURANCE = $last_ACCINSURANCE;
						$new_ACCSUBSCRIPTION = $last_ACCSUBSCRIPTION;
						$new_ACCBF = $last_ACCBF;
						$new_SUBSCRIPTIONDUE = $last_SUBSCRIPTIONDUE+$m_subs_amt;
						$new_BFDUE = $last_BFDUE+$m_bf_amt;
						$new_INSURANCEDUE = $last_INSURANCEDUE+$m_ins_amt;
						$new_TOTALMONTHSDUE = $last_TOTALMONTHSDUE+1;
						$new_TOTALMONTHSPAID = $last_TOTALMONTHSPAID;
						$new_TOTALMONTHSCONTRIBUTION = $last_TOTALMONTHSCONTRIBUTION;
						
						$last_paid_date = DB::table($this->membermonthendstatus_table." as ms")
                        ->select('ms.LASTPAYMENTDATE')
                        ->where('StatusMonth', '<', $monthend->StatusMonth)->where('MEMBER_CODE', '=', $member_id)
                        ->orderBY('StatusMonth','desc')
                        ->limit(1)
                        ->pluck('ms.LASTPAYMENTDATE')
                        ->first();
					}
				
					
					$monthend_datas = [
								'TOTALMONTHSDUE' => $new_TOTALMONTHSDUE,
								'TOTALMONTHSPAID' => $new_TOTALMONTHSPAID,
								'SUBSCRIPTIONDUE' => $new_SUBSCRIPTIONDUE,
								'BFDUE' => $new_BFDUE,
								'INSURANCEDUE' => $new_INSURANCEDUE,
								'ACCSUBSCRIPTION' => $new_ACCSUBSCRIPTION,
								'ACCBF' => $new_ACCBF,
								'ACCINSURANCE' => $new_ACCINSURANCE,
								'TOTALMONTHSCONTRIBUTION' => $new_TOTALMONTHSCONTRIBUTION,
								'LASTPAYMENTDATE' => $last_paid_date,
							];
					$m_upstatus = DB::table('membermonthendstatus')->where('Id', '=', $monthend->Id)->update($monthend_datas);

					$last_ACCINSURANCE = $new_ACCINSURANCE;
					$last_ACCSUBSCRIPTION = $new_ACCSUBSCRIPTION;
					$last_ACCBF = $new_ACCBF;
					$last_SUBSCRIPTIONDUE = $new_SUBSCRIPTIONDUE;
					$last_BFDUE = $new_BFDUE;
					$last_INSURANCEDUE = $new_INSURANCEDUE;
					$last_TOTALMONTHSDUE = $new_TOTALMONTHSDUE;
					$last_TOTALMONTHSPAID = $new_TOTALMONTHSPAID;
					$last_TOTALMONTHSCONTRIBUTION = $new_TOTALMONTHSCONTRIBUTION;
				}

				$payment_data = [
						'entrance_fee' => $ent_amt,
						'hq_fee' => $hq_amt,
						'last_paid_date' => $last_paid_date,
						'totpaid_months' => $last_TOTALMONTHSPAID,
						'totcontribution_months' => $last_TOTALMONTHSCONTRIBUTION,
						'totdue_months' => $last_TOTALMONTHSDUE,
						'accbf_amount' => $last_ACCBF,
						'accsub_amount' => $last_ACCSUBSCRIPTION,
						'accins_amount' => $last_ACCINSURANCE,
						'duebf_amount' =>  $last_BFDUE,
						'dueins_amount' => $last_INSURANCEDUE,
						'duesub_amount' =>  $last_SUBSCRIPTIONDUE,
						'updated_by' => Auth::user()->id,
					];
					DB::table('member_payments')->where('member_id', $member_id)->update($payment_data);
				if($last_TOTALMONTHSDUE<=3){
					$m_status = 1;
					DB::table('membership')->where('id', $member_id)->update(['status_id' => $m_status]);
				}else if($last_TOTALMONTHSDUE<=13){
					$m_status = 2;
					DB::table('membership')->where('id', $member_id)->update(['status_id' => $m_status]);
				}
			}
			

			if($auto_id==''){
				//return redirect($redirect_url)->with('message','Member Account created successfully');
				$mail_data = array(
					'name' => $member_name,
					'email' => $member_email,
					'password' => $randompassone,
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
			Artisan::call('cache:clear');
            return redirect($redirect_failurl)->with('error','Name and email is invalid');
        }
		
        
    }
	
	public function getoldMemberList(Request $request)
    {
        $search = $request->input('query');
       // return $search;
        $res['suggestions'] = DB::table('membership as m')->select(DB::raw('CONCAT(m.name, " - ", m.member_number) AS value'),'m.member_number as number','m.id as auto_id','m.member_title_id as member_title_id','m.gender as gender','m.gender as gender','m.mobile as mobile','m.email as email',DB::raw("DATE_FORMAT(`m`.`doe`, '%d/%m/%Y') as doe"),'m.designation_id as designation_id','m.race_id as race_id','m.state_id as state_id','m.city_id as city_id','m.postal_code as postal_code','m.address_one as address_one','m.address_two as address_two','m.address_three as address_three',DB::raw("DATE_FORMAT(`m`.`doj`, '%d/%m/%Y') as doj"),DB::raw("DATE_FORMAT(`m`.`dob`, '%d/%m/%Y') as dob"),'m.salary as salary','m.old_ic as old_ic','m.new_ic as new_ic','m.branch_id as branch_id','m.levy as levy','m.levy_amount as levy_amount','m.tdf as tdf','m.tdf_amount as tdf_amount','m.employee_id as employee_id','c.id as company_id','m.name as membername')
			->join('status','m.status_id','=','status.id') 
			->leftjoin('company_branch as cb','cb.id','=','m.branch_id')       
			->leftjoin('company as c','c.id','=','cb.company_id')
			->where(function($query) use ($search){
					$query->where('status.status_name','=','RESIGNED')
						->orWhere('status.status_name','=','STRUCKOFF');
			})       
		->where(function($query) use ($search){
				$query->orWhere('m.member_number', 'LIKE',"%{$search}%")
					->orWhere('m.name', 'LIKE',"%{$search}%");
		})->limit(20)->get();
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
		$irc_status = 0;
		$resign_status = 0;
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
										'membership.levy','membership.levy_amount','membership.tdf','membership.tdf_amount','membership.current_salary','membership.last_update')
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
					$data['resign_status'] = $resign_status;
					$data['view_status'] = 0;
                    
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

	public function checkMemberNewicExists(Request $request)
	{
		$new_ic =  $request->input('new_ic');
        $db_autoid = $request->input('db_autoid');
		$old_db_autoid = $request->input('old_db_autoid');
		if(!empty($autoid))
        {
            $usernewic_exists = Membership::where([
                ['new_ic','=',$new_ic],
                ['id','!=',$autoid]
                ])->count(); 
        }
        else
        {
			if(!empty($old_db_autoid)){
				$usernewic_exists = Membership::where([
					['new_ic','=',$new_ic],
					['id','!=',$old_db_autoid],
					])->count();
			}else{
				$usernewic_exists = Membership::where('new_ic','=',$new_ic)->count(); 
			}
            
        } 
        if($usernewic_exists > 0)
        {
            return "false";
        }
        else{
            return "true";
        }
	}

	//getMembersList
	public function getMembersList(Request $request)
	{
	    $searchkey = $request->input('searchkey');
        $search = $request->input('query');
        $res['suggestions'] = DB::table('membership as m')->select(DB::raw('CONCAT(m.name, " - ", m.member_number) AS value'),'m.id as number','m.branch_id as branch_id','m.member_number')      
                            ->where(function($query) use ($search){
                                $query->orWhere('m.id','LIKE',"%{$search}%")
                                    ->orWhere('m.member_number', 'LIKE',"%{$search}%")
									->orWhere('m.name', 'LIKE',"%{$search}%")
									->orWhere('m.old_ic', 'LIKE',"%{$search}%")
									->orWhere('m.new_ic', 'LIKE',"%{$search}%");
                            })->limit(25)
                            ->get();  
         return response()->json($res);
	}
	public function getMembersListValues(Request $request)
	{
		//DB::connection()->enableQueryLog();
		$member_id = $request->member_id;
		
		$res = DB::table('membership as m')->select(DB::raw("if(count('m.new_ic') > 0  ,m.new_ic,m.old_ic) as nric"),'m.member_number','m.id as memberid','d.designation_name as membertype','p.person_title as persontitle','m.name as membername','cb.branch_name','c.company_name',DB::raw("DATE_FORMAT(m.dob,'%d/%b/%Y') as dob"),'m.gender',DB::raw("DATE_FORMAT(m.doj,'%d/%b/%Y') as doj"),DB::raw("(PERIOD_DIFF( DATE_FORMAT(CURDATE(), '%Y%m') , DATE_FORMAT(dob, '%Y%m') )) DIV 12 AS age"),'r.race_name','cb.address_one','cb.phone','cb.mobile','cb.union_branch_id')
							->leftjoin('designation as d','d.id','=','m.designation_id')
							->leftjoin('persontitle as p','p.id','=','m.member_title_id')
							->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
							->leftjoin('company as c','c.id','=','cb.company_id')
							->leftjoin('race as r','r.id','=','m.race_id')
							->where('m.member_number','=',$member_id)
							->first();
			// $queries = DB::getQueryLog();
			// dd($queries);
			return response()->json($res);
	}

	public function AddPaymentEntry(){
		if(!empty(Auth::user())){
			$members = CacheMembers::all('id');
			foreach($members as $member){
				$memberid = $member->id;
				$payment_data = [
					'member_id' => $memberid,
					'due_amount' => 0,
					'created_by' => Auth::user()->id,
					'created_at' => date('Y-m-d'),
				];
				$member_pay_count = DB::table('member_payments')->where('member_id', '=', $memberid)->count();
				
			
				if($member_pay_count<1){
					//Log::info($memberid);
					DB::table('member_payments')->insert($payment_data);
				}
			}
		}else{
			echo 'please login to insert';
		}
	}

	public function NewRegisterDesign(){
		$data['country_view'] = DB::table('country')->select('id','country_name')->where('status','=','1')->get();
		$data['title_view'] = DB::table('persontitle')->where('status','=','1')->get();
		$data['company_view'] = DB::table('company')->where('status','=','1')->get();
		$data['designation_view'] = DB::table('designation')->where('status','=','1')->get();
		$data['race_view'] = DB::table('race')->where('status','=','1')->get();
		$data['status_view'] = DB::table('status')->where('status','=','1')->get();
		$data['fee_list'] = DB::table('fee')->where('status','=','1')->get();
		$data['relationship_view'] = DB::table('relation')->where('status','=','1')->get();
		$data['user_type'] = 0;

		return view('membership.membership_design')->with('data',$data);  
		//return view('membership.register-resign');
	}

	public function UpdateMemberPassword(Request $request){
		ini_set('memory_limit', -1);
		ini_set('max_execution_time', '1000');
		$members = DB::table('membership')->select('id','user_id','new_ic','old_ic','employee_id')->limit(10000)->offset(0)->get();
		foreach ($members as $key => $value) {
			$encpassword = '';
			if($value->new_ic!=''){
				$encpassword = bcrypt($value->new_ic);
			}else if($value->old_ic!=''){
				$encpassword = bcrypt($value->old_ic);
			}else if($value->employee_id!=''){
				$encpassword = bcrypt($value->employee_id);
			}
			if($encpassword!=''){
				DB::table('users')->where('id', '=', $value->user_id)->update(['password' => $encpassword]);
				Log::info($value->id);
			}
			
		}
	}
}
