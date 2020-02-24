@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">

@endsection
@section('headSecondSection')
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/custom_respon.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/datepicker.css') }}">
<style type="text/css">
	.autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; cursor:pointer; }
	.autocomplete-suggestion { padding: 8px 5px; white-space: nowrap; overflow: hidden; }
	.autocomplete-selected { background: #F0F0F0; }
	.autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
	.autocomplete-group { padding: 8px 5px; }
	.autocomplete-group strong { display: block; border-bottom: 1px solid #000; }
	.padding-left-10{
		padding-left:10px;
	}
	.padding-left-20{
		padding-left:20px;
	}
	.padding-left-24{
		padding-left:24px;
	}
	.padding-left-40{
		padding-left:40px;
	}
	#irc_confirmation_area {
		pointer-events: none;
	}
	.branch 
	{
    	pointer-events: none;
		background-color: #f4f8fb !important;
	}
</style>
@endsection
@section('main-content')
<div id="">
<div class="row">
<div class="content-wrapper-before"></div>
	@php 
	if(!empty(Auth::user())){
		
		$userid = Auth::user()->id;
		$get_roles = Auth::user()->roles;
		$user_role = $get_roles[0]->slug;	
		}
	@endphp
		<form class="formValidate" id="irc_formValidate" method="post"
		action="{{ route('irc.saveIrc',app()->getLocale()) }}">
		@csrf
		<div class="container">
		<div class="card">
		
		
			<h5 class="padding-left-10"> Resignation Member <a class="btn waves-effect waves-light right" href="{{ route('irc.irc_list',app()->getLocale())  }}">{{__('IRC Confirmation List') }}</a></h5>
			<div class="row">
				 <div class="input-field col s4">
					<label for="member_number"
						class="common-label force-active">{{__('Membership Number or Name or NRIC') }}*</label>
					<input id="member_number" name="member_number"  class="common-input"
						type="text" required data-error=".errorTxt1" autocomplete="off">
					<input type="hidden" name="resignedmemberno" id="memberid">
					<input type="hidden" name="union_branch_id" id="union_branch_id">
					<div class="errorTxt1"></div>
				</div>
				<div class="input-field col s4">
					<label for="member_type"
						class="common-label force-active">{{__('Member Type') }}*</label>
					<input id="member_type" name="member_type" readonly class="common-input"
						type="text" >
				</div>
				<div class="input-field col s4">
					<label for="member_title"
						class="common-label force-active">{{__('Member Title') }}*</label>
					<input id="member_title" name="member_title" readonly class="common-input"
						type="text">
				</div>
				 <div class="clearfix" style="clear:both"></div>
				 <div class="input-field col s4">
					<label for="member_name"
						class="common-label force-active">{{__('Member Name') }}*</label>
					<input id="member_name" name="resignedmembername" readonly class="common-input"
						type="text" >
				</div>
				<div class="input-field col s4">
					<label for="bank_name"
						class="common-label force-active">{{__('Bank') }}*</label>
					<input id="bank_name" name="resignedmemberbankname" readonly class="common-input"
						type="text" data-error=".errorTxt2">
					<div class="errorTxt2"></div>
				</div>
				<div class="input-field col s4">
					<label for="branch_name"
						class="common-label force-active">{{__('Bank Branch') }}*</label>
					<input id="branch_name" name="resignedmemberbranchname" readonly class="common-input"
						type="text" data-error=".errorTxt3">
					<div class="errorTxt3"></div>
				</div>
				 <div class="clearfix" style="clear:both"></div>
				 <div class="input-field col s4">
					<div class="row">
						<div class="input-field col s12 m8">
							<label for="dob" class="force-active">{{__('Date of Birth') }} *</label>
							<input id="dob" readonly name="dob" data-reflectage="dob" class="datepicker-custom"  type="text"> 
						</div>
						<div class="input-field col s12 m4">
							<label for="member_age" class="force-active">{{__('Age') }}</label>
							<input type="text" readonly id="member_age" >
						</div>
					</div>
					<div class="errorTxt4"></div>
				</div>
				<div class="input-field col s4">
					<div class="col s12 m4">
					 <p>{{__('Gender') }}</p>
					</div>
					<div class="col s12 m4">
						<p>
							<label>
							<input class="validate"   aria-required="true" id="femalegender" name="gender" type="radio" value="Female" disabled="disabled">
							<span>{{__('Female') }}</span>
							</label> 
						</p>						
					</div>
					<div class="col s12 m4">
						<p>
							<label>
							<input class="validate"  aria-required="true" id="malegender" name="gender" type="radio"  value="Male" disabled="disabled">
							<span>{{__('Male') }}</span>
							</label>
						</p>
					</div>
				<div class="errorTxt5"></div>
				</div>
				<div class="input-field col s4">
					<label for="doj"
						class="common-label force-active">{{__('DOJ') }}*</label>
					<input id="doj" name="doj" readonly class="common-input"
						type="text" data-error=".errorTxt6">
					<div class="errorTxt6"></div>
				</div>
				<div class="clearfix" style="clear:both"></div>
				<div class="input-field col s4">
					<label for="race_name"
						class="common-label force-active">{{__('Race') }}*</label>
					<input id="race_name" readonly name="race_name" class="common-input"
						type="text" data-error=".errorTxt7">
					<div class="errorTxt7"></div>
				</div>
				<div class="input-field col s4">
					<label for="nric_n"
						class="common-label force-active">{{__('NRIC-N') }}*</label>
					<input id="nric_n" readonly name="resignedmembericno" class="common-input"
						type="text" >
				</div>
				<div class="input-field col s4">
					<label for="remarks"
						class="common-label force-active">{{__('Remarks') }}*</label>
					<input id="remarks" name="remarks" class="common-input"
						type="text" >
				</div>
			</div>
		</div>
	</div>
	<div class=" col s12 ">
	  <div class="container">
	 
		 <div class="card">
		 <h5 class="padding-left-10">IRC CONFIRMATION OF BENEVOLENT FUND APPLICATION</h5>
			  <div class="row">
				<div class="input-field col s6">
					<label for="irc_member_no"
						class="common-label force-active">{{__('Membership Number or Name or NRIC') }}*</label>
					<input id="irc_member_no" name="ircmember" class="common-input"
						type="text" required data-error=".errorTxt8">
						<input type="hidden" name="ircmembershipno" id="irc_member_code">
					<div class="errorTxt8"></div>
				</div>
				<div class="input-field col s6">
					<label for="irc_name"
						class="common-label force-active">{{__('IRC Name in Full') }}*</label>
					<input id="irc_name"  readonly name="ircname" class="common-input"
						type="text" data-error=".errorTxt9">
					<div class="errorTxt9"></div>
				</div>
				<div class="clearfix" style="clear:both"></div>
				<div class="input-field col s6">
					<div class="col s12 m3">
					 <p>{{__('IRC Position') }}*</p>
					</div>
					<div class="col s12 m3">
						<p>
							<label>
							<input class="validate" required="" readonly aria-required="true" id="ircposition" name="ircposition" type="radio" value="Chairman">
							<span>{{__('Chairman') }}</span>
							</label> 
						</p>						
					</div>
					<div class="col s12 m3">
						<p>
							<label>
							<input class="validate" readonly required="" aria-required="true" id="ircposition" name="ircposition" type="radio" checked="" value="Secretary">
							<span>{{__('Secretary') }}</span>
							</label>
						</p>
					</div>
					<div class="col s12 m3">
						<p>
							<label>
							<input class="validate" readonly required="" aria-required="true" id="ircposition" name="ircposition" type="radio" checked="" value="Commitee-Member">
							<span>{{__('Commitee Member') }}</span>
							</label>
						</p>
					</div>
					<div class="errorTxt10"></div>
				</div>
				<div class="input-field col s6">
					<label for="irc_bank"
						class="common-label force-active">{{__('Bank') }}*</label>
					<input id="irc_bank" readonly  name="ircbank" class="common-input"
						type="text" data-error=".errorTxt1">
				</div>
				<div class="clearfix" style="clear:both"></div>
				<div class="input-field col s6">
					<label for="bank_address"
						class="common-label force-active">{{__('Bank Branch Address') }}</label>
					<input id="bank_address" readonly  name="ircbankaddress" class="common-input"
						type="text" data-error=".errorTxt1">
				</div>
				<div class="input-field col s6">
					<label for="irctelephoneno"
						class="common-label force-active">{{__('Office Number') }}</label>
					<input id="irctelephoneno" readonly name="irctelephoneno" class="common-input"
						type="text" data-error=".errorTxt1">
				</div>
				<div class="clearfix" style="clear:both"></div>
				<div class="input-field col s6">
					<label for="ircmobileno"
						class="common-label force-active">{{__('Mobile') }}</label>
					<input id="ircmobileno" readonly  name="ircmobileno" class="common-input"
						type="text" data-error=".errorTxt1">
				</div>
				<div class="input-field col s6">
					<label for="ircfaxno"
						class="common-label force-active">{{__('Fax') }}</label>
					<input id="ircfaxno"  name="ircfaxno" class="common-input"
						type="text" data-error=".errorTxt1">
				</div>
				<div class="input-field col s12">
					<h6>Dear Sir,<br><br>
					I,The above named IRC hereby Confirmed that the following : [Tick all the boxes as confirmation]
					</h6>
				</div>
					<div class="col s12 m12">
						<div class="row padding-left-20">
							<div class="col s12 m1">
								<p style="font-size: 16px;">
									Reason
								</p>
							</div>
							<div class="col s12 m3">
								<select name="resignedreason" id="reason" onchange="return ChangeFields()" class="error browser-default selectpicker">
									<option value="">Select reason</option>
									@foreach($data['reason_view'] as $values)
										<option value="{{$values->id}}">{{$values->reason_name}}</option>
									@endforeach
								</select>
							</div>
						</div>
											
					</div>
					<div class="col s12 m12">
						<div id="retired_section" class="reasonsections hide"> 

							<div class="row padding-left-20">
								<div class="col s12 m4 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span>BF Applicant’s Name is:</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
										<input type="text" id="person_name" readonly>
								</div>
							</div>	

							<div class="row padding-left-20">
								<div class="col s12 m4 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span><span class="gender"></span> <span style="text-decoration: underline;">RETIRED</span> w.e.f.</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
										<input type="text" 	name="gradewef" id="gradewef" placeholder="grade w.e.f"  class="datepicker-custom"/>
								</div>
							</div>	

							<div class="row padding-left-20">
								<div class="col s12 m12 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span><span class="gender"></span> was a MESSENGER / CLERICAL / SPECIAL GRADE CLERK / OTHER before RETIEMENT [Delete which is not applicable]</span>
										</label> 
									</p>	
								</div>
							
							</div>	
							<div class="row padding-left-20">
								<div class="col s12 m5 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span>Attached is <span class="genderone"></span> RETIREMENT Letter (compulsory)</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m6">
									 <div id="">
                                        <div class=" ">
                                         	<br>
                                            <input type="file" name="file" class="" accept="">
                                        </div>
                                        <div class="file-path-wrapper hide">
                                            <input class="file-path validate" type="text">
                                        </div>
                                    </div>
								</div>
							</div>	
							<div class="row padding-left-20">
								<div class="col s12 m5 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span>Member’s job functions have been taken over by</span>
										</label> 
									</p>	

								</div>
								<div class="col s12 m3 ">								
									<input type="text" 	name="jobtakenby" id="jobtakenby" placeholder=""  class=""/>
								</div>
								<div class="col s12 m3 ">								
										<p>and</p>
								</div>
							</div>
							<div class="row padding-left-20">
								<div class="col s12 m9 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span>Member’s position has not been filled up by another Member / Non-Member - Other [Please specify others in detail]</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
									<input type="text" 	name="positionfilledby" id="positionfilledby" placeholder=""  class=""/>
								</div>
							</div>	
							<div class="row padding-left-20">
								<div class="col s12 m9 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span>REPLACEMENT Staff Grade is Non-Clerical / Clerical / Special Grade Clerical / Other [Please specify others in detail] </span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
									<input type="text" 	name="replacestaff" id="replacestaff" placeholder=""  class=""/>
								</div>
							</div>	
							<div class="row padding-left-20">
								<div class="col s12 m2 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span>Applicant Contact</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
									<input type="text" 	name="appcontact" id="appcontact" placeholder=""  class=""/>
								</div>
								<div class="col s12 m1 ">
									<p>
										<label>
										
										<span>Office</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
									<input type="text" 	name="appoffice" id="appoffice" placeholder=""  class=""/>
								</div>
								<div class="clearfix"></div>
								<div class="col s12 m1 ">
									<p>
										&nbsp;
									</p>	
								</div>
								<div class="col s12 m1 ">
									<p>
										<label>
										
										<span>H/P</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
									<input type="text" 	name="apphp" id="apphp" placeholder=""  class=""/>
								</div>
								<div class="col s12 m1 ">
									<p>
										<label>
										
										<span>Fax</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
									<input type="text" 	name="appfax" id="appfax" placeholder=""  class=""/>
								</div>
								<div class="clearfix"></div>
								<div class="col s12 m1 ">
									<p>
										&nbsp;
									</p>	
								</div>
								<div class="col s12 m1 ">
									<p>
										<label>
										
										<span>Email</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
									<input type="text" 	name="appemail" id="appemail" placeholder=""  class=""/>
								</div>
							</div>	
						</div>
						<div id="deceased_section" class="reasonsections hide"> 

							<div class="row padding-left-20">
								<div class="col s12 m4 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="memberdemisedon" id="memberdemisedon"  value="1"/>
										<span>Member DEMISED on</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
										<input type="text" id="memberdemised" name="memberdemised" >
								</div>
							</div>	

							<div class="row padding-left-20">
								<div class="col s12 m4 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="nameofmemberbox" id="nameofmemberbox"  value="1"/>
										<span>Name of Member’s next of kin is</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
									<input type="text" 	name="nameofmember" id="nameofmember" placeholder=""  class=""/>
								</div>
							</div>	

							<div class="row padding-left-20">
								<div class="col s12 m4 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="relationshipbox" id="relationshipbox"  value="1"/>
										<span>Relationship is</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
									<input type="text" 	name="relationship" id="relationship" placeholder=""  class=""/>
								</div>
							</div>	

							<div class="row padding-left-20">
								<div class="col s12 m2 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span>Applicant</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m1 ">
									<p>
										<label>
										<input type="radio" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span>Has</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m5 ">
									<p>
										<label>
										<input type="radio" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span>Does Not have Legal Authority (LA) to claim.  </span>
										</label> 
									</p>	
								</div>
							
							</div>	
							
							<div class="row padding-left-20">
								<div class="col s12 m5 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span>Member’s job functions have been taken over by</span>
										</label> 
									</p>	

								</div>
								<div class="col s12 m3 ">								
									<input type="text" 	name="jobtakenby" id="jobtakenby" placeholder=""  class=""/>
								</div>
								<div class="col s12 m3 ">								
										<p>and</p>
								</div>
							</div>
							<div class="row padding-left-20">
								<div class="col s12 m9 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span>Member’s position has not been filled up by another Member / Non-Member - Other [Please specify others in detail]</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
									<input type="text" 	name="positionfilledby" id="positionfilledby" placeholder=""  class=""/>
								</div>
							</div>	
							<div class="row padding-left-20">
								<div class="col s12 m9 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span>REPLACEMENT Staff Grade is Non-Clerical / Clerical / Special Grade Clerical / Other [Please specify others in detail] </span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
									<input type="text" 	name="replacestaff" id="replacestaff" placeholder=""  class=""/>
								</div>
							</div>	
							<div class="row padding-left-20">
								<div class="col s12 m3 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span>Next of Kin’s Contact:</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
									<input type="text" 	name="appcontact" id="appcontact" placeholder=""  class=""/>
								</div>
								<div class="col s12 m1 ">
									<p>
										<label>
										
										<span>Office</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
									<input type="text" 	name="appoffice" id="appoffice" placeholder=""  class=""/>
								</div>
								<div class="clearfix"></div>
								<div class="col s12 m2 ">
									<p>
										&nbsp;
									</p>	
								</div>
								<div class="col s12 m1 ">
									<p>
										<label>
										
										<span>Mobile</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
									<input type="text" 	name="apphp" id="apphp" placeholder=""  class=""/>
								</div>
								<div class="col s12 m1 ">
									<p>
										<label>
										
										<span>Fax</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
									<input type="text" 	name="appfax" id="appfax" placeholder=""  class=""/>
								</div>
								<div class="clearfix"></div>
								<div class="col s12 m2 ">
									<p>
										&nbsp;
									</p>	
								</div>
								<div class="col s12 m1 ">
									<p>
										<label>
										
										<span>Email</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
									<input type="text" 	name="appemail" id="appemail" placeholder=""  class=""/>
								</div>
							</div>	
						</div>
						<div id="promoted_section" class="reasonsections hide"> 

							<div class="row padding-left-20">
								<div class="col s12 m4 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span>BF Applicant’s Name is:</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
										<input type="text" id="person_name" readonly>
								</div>
							</div>	

							<div class="row padding-left-20">
								<div class="col s12 m12 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span><span class="gender"></span> was a MESSENGER / CLERICAL / SPECIAL GRADE CLERK / OTHER before PROMOTION [Delete which is not applicable]</span>
										</label> 
									</p>	
								</div>
							
							</div>	
							<div class="row padding-left-20">
								<div class="col s12 m4 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span><span class="gender"></span> was <span style="text-decoration: underline;">PROMOTED</span> w.e.f.</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
										<input type="text" 	name="gradewef" id="gradewef" placeholder="grade w.e.f"  class="datepicker-custom"/>
								</div>
							</div>	

						
							<div class="row padding-left-20">
								<div class="col s12 m5 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span>Attached is <span class="genderone"></span> Job Description (compulsory)</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m6">
									 <div id="">
                                        <div class=" ">
                                         	<br>
                                            <input type="file" name="file" class="" accept="">
                                        </div>
                                        <div class="file-path-wrapper hide">
                                            <input class="file-path validate" type="text">
                                        </div>
                                    </div>
								</div>
							</div>	
							<div class="row padding-left-20">
								<div class="col s12 m4 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="toplacebox" id="toplacebox"  value="1"/>
										<span>He promoted and transfer to new place</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
										<input type="text" id="toplace" >
								</div>
							</div>	
							<div class="row padding-left-20">
								<div class="col s12 m12 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span>Member is still in the same Branch / Department performing the same job functions. </span>
										</label> 
									</p>	
								</div>
							
							</div>	
							<div class="row padding-left-20">
								<div class="col s12 m5 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span>Member’s job functions have been taken over by</span>
										</label> 
									</p>	

								</div>
								<div class="col s12 m3 ">								
									<input type="text" 	name="jobtakenby" id="jobtakenby" placeholder=""  class=""/>
								</div>
								<div class="col s12 m3 ">								
										<p>and</p>
								</div>
							</div>
							<div class="row padding-left-20">
								<div class="col s12 m9 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span>Member’s position has not been filled up by another Member / Non-Member - Other [Please specify others in detail]</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
									<input type="text" 	name="positionfilledby" id="positionfilledby" placeholder=""  class=""/>
								</div>
							</div>	
							<div class="row padding-left-20">
								<div class="col s12 m9 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span>REPLACEMENT Staff Grade is Non-Clerical / Clerical / Special Grade Clerical / Other [Please specify others in detail] </span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
									<input type="text" 	name="replacestaff" id="replacestaff" placeholder=""  class=""/>
								</div>
							</div>	
							<div class="row padding-left-20">
								<div class="col s12 m2 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span>Applicant Contact</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
									<input type="text" 	name="appcontact" id="appcontact" placeholder=""  class=""/>
								</div>
								<div class="col s12 m1 ">
									<p>
										<label>
										
										<span>Office</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
									<input type="text" 	name="appoffice" id="appoffice" placeholder=""  class=""/>
								</div>
								<div class="clearfix"></div>
								<div class="col s12 m1 ">
									<p>
										&nbsp;
									</p>	
								</div>
								<div class="col s12 m1 ">
									<p>
										<label>
										
										<span>H/P</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
									<input type="text" 	name="apphp" id="apphp" placeholder=""  class=""/>
								</div>
								<div class="col s12 m1 ">
									<p>
										<label>
										
										<span>Fax</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
									<input type="text" 	name="appfax" id="appfax" placeholder=""  class=""/>
								</div>
								<div class="clearfix"></div>
								<div class="col s12 m1 ">
									<p>
										&nbsp;
									</p>	
								</div>
								<div class="col s12 m1 ">
									<p>
										<label>
										
										<span>Email</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
									<input type="text" 	name="appemail" id="appemail" placeholder=""  class=""/>
								</div>
							</div>	
						</div>
						<div id="resign_section" class="reasonsections hide"> 

							<div class="row padding-left-20">
								<div class="col s12 m4 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span>BF Applicant’s Name is:</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
										<input type="text" id="person_name" readonly>
								</div>
							</div>	
							<div class="row padding-left-20">
								<div class="col s12 m4 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span><span class="gender"></span> RESIGNED / TERMINATED on </span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
										<input type="text" 	name="gradewef" id="gradewef" placeholder="grade w.e.f"  class="datepicker-custom"/>
								</div>
							</div>

							<div class="row padding-left-20">
								<div class="col s12 m12 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span><span class="gender"></span> was a MESSENGER / CLERICAL / SPECIAL GRADE CLERK / OTHER before RESIGNATION [Delete which is not applicable]</span>
										</label> 
									</p>	
								</div>
							
							</div>	
								

						
							<div class="row padding-left-20">
								<div class="col s12 m8 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span>Attached is <span class="genderone"></span> RESIGNATION / TERMINATION / EXPULSION / STRUCK OFF Letter (compulsory)</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m4">
									 <div id="">
                                        <div class=" ">
                                         	<br>
                                            <input type="file" name="file" class="" accept="">
                                        </div>
                                        <div class="file-path-wrapper hide">
                                            <input class="file-path validate" type="text">
                                        </div>
                                    </div>
								</div>
							</div>	
								
							<div class="row padding-left-20">
								<div class="col s12 m5 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span>Member’s job functions have been taken over by</span>
										</label> 
									</p>	

								</div>
								<div class="col s12 m3 ">								
									<input type="text" 	name="jobtakenby" id="jobtakenby" placeholder=""  class=""/>
								</div>
								<div class="col s12 m3 ">								
										<p>and</p>
								</div>
							</div>
							<div class="row padding-left-20">
								<div class="col s12 m9 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span>Member’s position has not been filled up by another Member / Non-Member - Other [Please specify others in detail]</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
									<input type="text" 	name="positionfilledby" id="positionfilledby" placeholder=""  class=""/>
								</div>
							</div>	
							<div class="row padding-left-20">
								<div class="col s12 m9 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span>REPLACEMENT Staff Grade is Non-Clerical / Clerical / Special Grade Clerical / Other [Please specify others in detail] </span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
									<input type="text" 	name="replacestaff" id="replacestaff" placeholder=""  class=""/>
								</div>
							</div>	
							<div class="row padding-left-20">
								<div class="col s12 m2 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span>Applicant Contact</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
									<input type="text" 	name="appcontact" id="appcontact" placeholder=""  class=""/>
								</div>
								<div class="col s12 m1 ">
									<p>
										<label>
										
										<span>Office</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
									<input type="text" 	name="appoffice" id="appoffice" placeholder=""  class=""/>
								</div>
								<div class="clearfix"></div>
								<div class="col s12 m1 ">
									<p>
										&nbsp;
									</p>	
								</div>
								<div class="col s12 m1 ">
									<p>
										<label>
										
										<span>H/P</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
									<input type="text" 	name="apphp" id="apphp" placeholder=""  class=""/>
								</div>
								<div class="col s12 m1 ">
									<p>
										<label>
										
										<span>Fax</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
									<input type="text" 	name="appfax" id="appfax" placeholder=""  class=""/>
								</div>
								<div class="clearfix"></div>
								<div class="col s12 m1 ">
									<p>
										&nbsp;
									</p>	
								</div>
								<div class="col s12 m1 ">
									<p>
										<label>
										
										<span>Email</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
									<input type="text" 	name="appemail" id="appemail" placeholder=""  class=""/>
								</div>
							</div>	
						</div>
						<div id="expelled_section" class="reasonsections hide"> 

							<div class="row padding-left-20">
								<div class="col s12 m6 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span>Member was EXPELLED / STRUCK OFF / BLACKLISTED on
										</label> 
									</p>	
								</div>
								<div class="col s12 m3 ">								
										<input type="text" 	name="gradewef" id="gradewef" placeholder="grade w.e.f"  class="datepicker-custom"/>
								</div>
							</div>

								
							<div class="row padding-left-20">
								<div class="col s12 m12 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span>Member’s is still performing the same job functions.</span>
										</label> 
									</p>	

								</div>
								
							</div>
							<div class="row padding-left-20">
								<div class="col s12 m12 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span>Member is still in the same Branch / Department.</span>
										</label> 
									</p>	
								</div>
								
							</div>	
							<div class="row padding-left-20">
								<div class="col s12 m12 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"/>
										<span>Member HAS STOPPED / HAS NOT STOPPED the Check-Off [Delete whichever is applicable] </span>
										</label> 
									</p>	
								</div>
								
							</div>	
							<div class="row padding-left-20">
								<div class="col s12 m12 " style="font-size: 18px;">
									<p>
										VOLUNTARY SEPARATION SCHEME (VSS/MSS), RESIGNED FOR MEDICAL GROUNDS OR PERSONAL REASONS OR RESIGNED FROM NUBE OR EXPELLED OR STRUCK OFF – processing same as Code 04.
									</p>
										
									<p>
										REJOIN and /or DUPLICATE MEMBERSHIP or JOIN SBEU will either be processed as per RETIRE, PROMOTION, DEMISED, RESIGNATION or EXPELLED. 
									</p>
								</div>
								
							</div>	
						</div>
					</div>

					
					
				
					</div>
			  </div>
			  <div class="card @php if($user_role =='irc-confirmation') echo 'branch'; @endphp">
			  <h5 class="padding-left-10">BRANCH COMMITEE VERIFICATION</h5>
				<div class="row">
					<div class="col s12 m12">
						<div class="row padding-left-20">
							<div class="col s12 m1 ">
								<p>
									<label>
									
										<input type="checkbox"  name="waspromoted" id="waspromoted" class="common-checkbox"  value="1"/>
										<span>I</span>
									</label> 
								</p>	
							</div>
							<div class="col s12 m3">
								<input type="text" id="type" readonly name="type" placeholder="" value="">	
							</div>
							<div class="col s12 m3">
								<p>Branch Committee of NUBE </p>
								
								
							</div>
							<div class="col s12 m3">
								<input type="text" 	name="gradewef" id="gradewef" value="" placeholder="" name="text" class=""/>
							</div>
							
							<div class="col s12 m12">
								<p>&nbsp;&nbsp;&nbsp;&nbsp; Branch have verified the above and confirm that the declaration by the IRC is correct.  </p>
							</div>
						</div>	
					</div>
					<div class="col s12 m12">
						<p class="padding-left-24">
							<label>
							<input type="checkbox" class="common-checkbox"  />
							<span>Staff who has taken over the job functions under CODE 01 / 02 / 03 / 04 is a NUBE Member. </span>
							</label> 
						</p>
					</div>
					<div class="col s12 m12">
						<p class="padding-left-24">
							<label>
							<input type="checkbox" class="common-checkbox"  />
							<span>Staff who is under CODE 05 is still performing the same job function.  The additional information for this staff is as follows:  </span>
							</label> 
						</p>
					</div>
					<div class="col s12 m12">
						<div class="row">
							<div class="col s12 m4 ">
								<p >
									<label>
									
									<span>Remark</span>
									</label> 
								</p>	
							</div>
							<div class="col s12 m3 ">
									<input type="text" name="person_name">
							</div>
						</div>	
					</div>
					<div class="col s12 m12">
						<div class="row">
							<div class="col s12 m4 ">
								<p >
									<label>
									
									<span>Branch Commitee [Name in full]</span>
									</label> 
								</p>	
							</div>
							<div class="col s12 m3 ">
									<input type="text" name="person_name">
							</div>
						</div>	
					</div>
					<div class="col s12 m12">
						<div class="row">
							<div class="col s12 m4 ">
								<p >
									<label>
									<span>Zone</span>
									</label> 
								</p>	
							</div>
							<div class="col s12 m3 ">
									<input type="text" name="person_name">
							</div>
							<div class="col s12 m3 ">
							<!--<label>Date</label> -->
									<input type="text" class="datepicker-custom" palceholder="Date" name="date">
							</div>
						</div>	
					</div>
				</div>
			  </div>
			  <div class="col s12 m12">
					<div class="row">
						
						<div class="input-field col s12 m4">
						  <i class="material-icons prefix">date_range</i>
						  <input id="submitted_at" name="submitted_at" data-reflectage="dob" required="" class="datepicker-custom"  type="text">
						  <label for="icon_prefix" class="force-active">File Submitted</label>
						</div> 
					<div class="input-field col s12 m2">
						<p>
						<input type="submit" class="btn" id="save" name="save" value="Submit" >
						</P>
					</div>	
					<div class="input-field col s12 m2">
						<p>
						<input type="button" class="btn" id="clear" name="clear" onClick="refreshPage()" value="Clear" >
						</P>
					</div>
					</div>
			   </div>
		 </div>
		
	  </div><!-- START RIGHT SIDEBAR NAV -->
	  </form>
		  @include('layouts.right-sidebar')
		  <!-- END RIGHT SIDEBAR NAV -->

	</div>
  </div>
</div>
</div>
<!-- END: Page Main-->
<!-- Theme Customizer -->
@endsection
@section('footerSection')
<script
type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/assets/js/scripts/form-validation.js')}}" type="text/javascript"></script>
@endsection
@section('footerSecondSection')
<script src="{{ asset('public/assets/js/jquery.autocomplete.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/datepicker.js') }}"></script>
<script>
function refreshPage(){
    window.location.reload();
}
$("#irc_sidebar_a_id").addClass('active');
$(document).ready(function() {
	$("#irc_formValidate").validate({
		rules: {
			member_number: {
				required: true,
			},
			irc_member_no: {
				required: true,
			},
		},
		//For custom messages
		messages: {
			member_number: {
				required: '{{__("Enter a Member Number") }}',
			},
			irc_member_no: {
				required: '{{__("Enter irc Member Number") }}',
			},
		},
		errorElement: 'div',
		errorPlacement: function(error, element) {
			var placement = $(element).data('error');
			if (placement) {
				$(placement).append(error)
			} else {
				error.insertAfter(element);
			}
		}
	});
});
//Model
$(document).ready(function() {
// the "href" attribute of the modal trigger must specify the modal ID that wants to be triggered
$('.modal').modal();
});
$("#member_number").devbridgeAutocomplete({
	//lookup: countries,
	serviceUrl: "{{ URL::to('/get-activemember-list') }}?searchkey="+ $("#member_number").val(),
	type:'GET',
	//callback just to show it's working
	onSelect: function (suggestion) {
			$("#member_number").val(suggestion.member_number);
			$.ajax({
				url: "{{ URL::to('/get-member-list-values') }}?member_id="+ $("#member_number").val(),
                type: "GET",
				dataType: "json",
				success: function(res) {
					
					$('#member_type').val(res.membertype);
					$('#type').val(res.membertype);
					$('#member_title').val(res.persontitle);
					$('#member_name').val(res.membername);
					$('#person_name').val(res.membername);
					$('#memberid').val(res.memberid);
					$('#member_id').val(res.memberid);
					$('#union_branch_id').val(res.union_branch_id);
					$('#branch_name').val(res.branch_name);
					$('#bank_name').val(res.company_name);
					$('#dob').val(res.dob);
					$('#member_age').val(res.age);
					if(res.gender == 'Male')
					{
						$(".gender").text('He');
						$(".genderone").text('His');
						//alert('male');
						$("#malegender").attr("checked",true);
					}
					else if(res.gender == 'Female')
					{
						//alert('Female');
						$(".gender").text('She');
						$(".genderone").text('Her');
						$("#femalegender").attr("checked",true);
					}
					$('#doj').val(res.doj);
					$('#race_name').val(res.race_name);
					$('#nric_n').val(res.nric);
					$('#genders').val(res.gender);

					if(res.gender == 'Male')
					{
						//$('#gen').html("He was");
						//$('#gend').html("I hearby confirm that He got He is no longer doing any clerical job function.");
					}
					else{
						//$('#gen').html("She was");
						//$('#gend').html("I hearby confirm that She got She is no longer doing any clerical job function.");
					}
				}
			});
			
	},
	showNoSuggestionNotice: true,
	noSuggestionNotice: 'Sorry, no matching results',
	onSearchComplete: function (query, suggestions) {
		if(!suggestions.length){
			//$("#member_number").val('');
		}
	}
});
$(document.body).on('click', '.autocomplete-no-suggestion' ,function(){
	$("#member_number").val('');
});
$('.datepicker').datepicker({
	format: 'dd/mm/yyyy'
});
//IRC Member Details 
$("#irc_member_no").devbridgeAutocomplete({
	//lookup: countries,
	serviceUrl: "{{ URL::to('/get-ircmember-list') }}?searchkey="+ $("#irc_member_no").val(),
	type:'GET',
	params: { 
		union_branch_id:  function(){ return $("#union_branch_id").val();  },
	},
	//callback just to show it's working
	onSelect: function (suggestion) {
			$("#irc_member_no").val(suggestion.member_number);	
			$.ajax({
				url: "{{ URL::to('/get-ircmember-list-values') }}?member_id="+ $("#irc_member_no").val(),
                type: "GET",
				dataType: "json",
				success: function(res) {
					$('#irc_name').val(res.membername);
					$('#irc_bank').val(res.bankname);
					$('#irc_member_code').val(res.mid);
					$('#bank_address').val(res.address_one);
					$('#irctelephoneno').val(res.phone);
					$('#ircmobileno').val(res.mobile);
				}
			});

	},
	showNoSuggestionNotice: true,
	noSuggestionNotice: 'Sorry, no matching results',
	onSearchComplete: function (query, suggestions) {
		if(!suggestions.length){
			//$("#irc_member_no").val('');
		}
	}
});
$(document.body).on('click', '.autocomplete-no-suggestion' ,function(){
	$("#irc_member_no").val('');
});
$(document).on('submit','form#irc_formValidate',function(){
    $("#save").prop('disabled',true);
    loader.showLoader();
});
$(".datepicker-custom").datepicker({
    format: 'dd/mm/yyyy',
	autoHide: true,
});
function ChangeFields(){
	var reason = $("#reason option:selected").text();
	//alert(reason);
	$(".reasonsections").addClass('hide');
	if(reason=='RETIRED'){
		$("#retired_section").removeClass('hide');
	}else if(reason=='DECEASED'){
		$("#deceased_section").removeClass('hide');
	}else if(reason=='PROMOTED'){
		$("#promoted_section").removeClass('hide');
	}
	else if(reason=='RESIGN FROM BANK' || reason=='RESIGN FROM UNION' || reason=='TERMINATED BY BANK'){
		$("#resign_section").removeClass('hide');
	}else if(reason=='EXPELLED' || reason=='STRUCK OFF' || reason=='BLACK LIST'){
		$("#expelled_section").removeClass('hide');
	}else{

	}
}
</script>
@endsection