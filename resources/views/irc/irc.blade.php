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
	.reasonsections .input-field {
    	position: relative;
    	margin: 0 !important;
    	padding-left: 5px;
    	padding-right: 5px;
	}
	.branch .input-field {
    	position: relative;
    	margin: 0 !important;
    }
    .inline-box{
    	height: 2rem !important;
    	margin-top: 10px !important;
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
		//dd($data['irc_data']);
	@endphp
		<form class="formValidate" id="irc_formValidate" method="post"
		action="{{ route('irc.saveIrc',app()->getLocale()) }}" enctype="multipart/form-data">
		@csrf
		<div class="container">
		<div class="card">
		
		
			<h5 class="padding-left-10"> Resignation Member <a class="btn waves-effect waves-light right" href="{{ route('irc.irc_list',app()->getLocale())  }}">{{__('IRC List') }}</a></h5>
			<div id="memberdetailssection" class="row">
				 <div class="input-field col s4">
					<label for="member_number"
						class="common-label force-active">{{__('Membership Number or Name or NRIC') }}*</label>
					<input id="member_number" name="member_number" value="{{ $data['member_id']!='' ? $data['irc_data']->member_number : '' }}" class="common-input"
						type="text" required data-error=".errorTxt1" autocomplete="off">
					<input type="hidden" name="resignedmemberno" value="{{ $data['member_id'] }}" id="memberid">
					<input type="hidden" name="union_branch_id" value="{{ $data['member_id']!='' ? $data['irc_data']->union_branch_id : '' }}" id="union_branch_id">
					<div class="errorTxt1"></div>
				</div>
				<div class="input-field col s4">
					<label for="member_type"
						class="common-label force-active">{{__('Member Type') }}*</label>
					<input id="member_type" name="member_type" value="{{ $data['member_id']!='' ? $data['irc_data']->membertype : '' }}" readonly class="common-input"
						type="text" >
				</div>
				<div class="input-field col s4">
					<label for="member_title"
						class="common-label force-active">{{__('Member Title') }}*</label>
					<input id="member_title" name="member_title" value="{{ $data['member_id']!='' ? $data['irc_data']->persontitle : '' }}" readonly class="common-input"
						type="text">
				</div>
				 <div class="clearfix" style="clear:both"></div>
				 <div class="input-field col s4">
					<label for="member_name"
						class="common-label force-active">{{__('Member Name') }}*</label>
					<input id="member_name" name="resignedmembername" value="{{ $data['member_id']!='' ? $data['irc_data']->membername : '' }}" readonly class="common-input"
						type="text" >
				</div>
				<div class="input-field col s4">
					<label for="bank_name"
						class="common-label force-active">{{__('Bank') }}*</label>
					<input id="bank_name" name="resignedmemberbankname" value="{{ $data['member_id']!='' ? $data['irc_data']->company_name : '' }}" readonly class="common-input"
						type="text" data-error=".errorTxt2">
					<div class="errorTxt2"></div>
				</div>
				<div class="input-field col s4">
					<label for="branch_name"
						class="common-label force-active">{{__('Bank Branch') }}*</label>
					<input id="branch_name" name="resignedmemberbranchname" value="{{ $data['member_id']!='' ? $data['irc_data']->branch_name : '' }}" readonly class="common-input"
						type="text" data-error=".errorTxt3">
					<div class="errorTxt3"></div>
				</div>
				 <div class="clearfix" style="clear:both"></div>
				 <div class="input-field col s4">
					<div class="row">
						<div class="input-field col s12 m8">
							<label for="dob" class="force-active">{{__('Date of Birth') }} *</label>
							<input id="dob" readonly name="dob" data-reflectage="dob" value="{{ $data['member_id']!='' ? $data['irc_data']->dob : '' }}" class="datepicker-custom"  type="text"> 
						</div>
						<div class="input-field col s12 m4">
							<label for="member_age" class="force-active">{{__('Age') }}</label>
							<input type="text" value="{{ $data['member_id']!='' ? $data['irc_data']->age : '' }}" readonly id="member_age" >
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
							<input class="validate" @php if($data['member_id']!=''){ echo $data['irc_data']->gender == 'Female' ? 'checked' : ''; } @endphp  aria-required="true" id="femalegender" name="gender" type="radio" value="Female" disabled="disabled">
							<span>{{__('Female') }}</span>
							</label> 
						</p>						
					</div>
					<div class="col s12 m4">
						<p>
							<label>
							<input class="validate" @php if($data['member_id']!=''){ echo $data['irc_data']->gender == 'Male' ? 'checked' : ''; } @endphp  aria-required="true" id="malegender" name="gender" type="radio"  value="Male" disabled="disabled">
							<span>{{__('Male') }}</span>
							</label>
						</p>
					</div>
				<div class="errorTxt5"></div>
				</div>
				<div class="input-field col s4">
					<label for="doj"
						class="common-label force-active">{{__('DOJ') }}*</label>
					<input id="doj" name="doj" value="{{ $data['member_id']!='' ? $data['irc_data']->doj : '' }}" readonly class="common-input"
						type="text" data-error=".errorTxt6">
					<div class="errorTxt6"></div>
				</div>
				<div class="clearfix" style="clear:both"></div>
				<div class="input-field col s4">
					<label for="race_name"
						class="common-label force-active">{{__('Race') }}*</label>
					<input id="race_name" value="{{ $data['member_id']!='' ? $data['irc_data']->race_name : '' }}" readonly name="race_name" class="common-input"
						type="text" data-error=".errorTxt7">
					<div class="errorTxt7"></div>
				</div>
				<div class="input-field col s4">
					<label for="nric_n"
						class="common-label force-active">{{__('NRIC-N') }}*</label>
					<input id="nric_n" value="{{ $data['member_id']!='' ? $data['irc_data']->nric : '' }}" readonly name="resignedmembericno" class="common-input"
						type="text" >
				</div>
				<div class="input-field col s4">
					<label for="remarks"
						class="common-label force-active">{{__('Remarks') }}*</label>
					<input id="remarks" value="" name="remarks" class="common-input"
						type="text" >
				</div>
			</div>
		</div>
	</div>
	<div class=" col s12 ">
	  <div class="container">
	 
		 <div class="card">
		 <h5 class="padding-left-10">IRC BRANCH COMMITEE OF BENEVOLENT FUND APPLICATION</h5>
		 	@php
		 		$userid = Auth::user()->id;
		 		$confirmdata = CommonHelper::getconfirmtionmember($userid);  
		 		//dd($confirmdata);
		 	@endphp
			  <div class="row">
			  	@if($user_role !='irc-confirmation-officials')
				<div class="input-field col s6">
					<label for="irc_member_no"
						class="common-label force-active">{{__('Membership Number or Name or NRIC') }}*</label>
					<input id="irc_member_no" name="ircmember" class="common-input"
						type="text" required data-error=".errorTxt8" @if(!empty($confirmdata)) readonly="" value="{{ $confirmdata->member_number }}" @else  value="" @endif >
						<input type="hidden" name="ircmembershipno" id="irc_member_code" @if(!empty($confirmdata)) value="{{ $confirmdata->mid }}" @else value="" @endif >
					<div class="errorTxt8"></div>
				</div>
				@else
				<input type="hidden" name="ircmembershipno" id="irc_member_code" value="" >
				@endif
				<div class="input-field col s6">
					<label for="irc_name"
						class="common-label force-active">{{__('IRC Name in Full') }}*</label>
					<input id="irc_name" @if($user_role !='irc-confirmation-officials') readonly value="{{ !empty($confirmdata) ? $confirmdata->membername : '' }}" @else value="{{ Auth::user()->name }}"  @endif name="ircname" class="common-input"
						type="text"  data-error=".errorTxt9">
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
							<input class="validate" readonly required="" aria-required="true" id="ircposition" name="ircposition" type="radio" value="Secretary">
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
					@if($user_role =='irc-confirmation-officials')
					<div class="col s12 m3">
						&nbsp;
					</div>
					<div class="col s12 m3">
						<p>
							<label>
							<input class="validate" readonly required="" aria-required="true" id="ircposition" name="ircposition" type="radio" checked="" value="Officials">
							<span>{{__('Officials') }}</span>
							</label>
						</p>
					</div>
					@endif
					<div class="errorTxt10"></div>
				</div>
				<div class="input-field col s6">
					<label for="irc_bank"
						class="common-label force-active">{{__('Bank') }}*</label>
					<input id="irc_bank" readonly  name="ircbank" class="common-input"
						type="text" value="{{ !empty($confirmdata) ? $confirmdata->bankname : '' }}" data-error=".errorTxt1">
				</div>
				<div class="clearfix" style="clear:both"></div>
				<div class="input-field col s6">
					<label for="bank_address"
						class="common-label force-active">{{__('Bank Branch Address') }}</label>
					<input id="bank_address" readonly  value="{{ !empty($confirmdata) ? $confirmdata->address_one : '' }}" name="ircbankaddress" class="common-input"
						type="text" data-error=".errorTxt1">
				</div>
				<div class="input-field col s6">
					<label for="irctelephoneno"
						class="common-label force-active">{{__('Office Number') }}</label>
					<input id="irctelephoneno" readonly name="irctelephoneno" value="{{ !empty($confirmdata) ? $confirmdata->phone : '' }}" class="common-input"
						type="text" data-error=".errorTxt1">
				</div>
				<div class="clearfix" style="clear:both"></div>
				<div class="input-field col s6">
					<label for="ircmobileno"
						class="common-label force-active">{{__('Mobile') }}</label>
					<input id="ircmobileno" readonly  name="ircmobileno" value="{{ !empty($confirmdata) ? $confirmdata->mobile : '' }}" class="common-input"
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
							<div class="col s12 m5">
								<select name="resignedreason" id="reason" required="" onchange="return ChangeFields()" class="error browser-default selectpicker">
									<option value="">Select reason</option>
									@foreach($data['reason_view'] as $values)
										<option value="{{$values->id}}">{{$values->reason_name}}</option>
									@endforeach
								</select>
							</div>
						</div>
											
					</div>
					<div class="col s12 m12">
						<input type="text" class="hide" name="section_type" id="section_type" value="">
						<div id="retired_section" class="reasonsections hide"> 

							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" name="personnameboxone" id="personnameboxone"  value="1"/>
						          		<span>1. BF Applicant’s Name is</span>
						            </label> 
									<div class="input-field inline">
										<input type="text" id="person_nameone" style="width: 500px;" name="person_nameone" value="{{ $data['member_id']!='' ? $data['irc_data']->membername : '' }}" class="inline-box" readonly>
										
									</div>
						        </div>
						        <div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" name="retiredboxone" id="retiredboxone"  value="1"/>
						          		<span>2. <span class="gender"> @php if($data['member_id']!=''){ echo $data['irc_data']->gender == 'Female' ? 'She' : 'He'; } @endphp </span> <span style="text-decoration: underline;">RETIRED</span> w.e.f.</span>
						            </label> 
									<div class="input-field inline">
										<input type="text" 	name="gradewefone" id="gradewefone" placeholder="grade w.e.f"  value="{{ date('d/m/Y') }}" class="datepicker-custom inline-box"/>
										
									</div>
						        </div>
								
							</div>	


							<div class="row padding-left-20">
								<div class="col s12 m12 ">
									
										<label>
											<input type="checkbox" class="common-checkbox" name="messengerboxone" id="messengerboxone"  value="1"/>
											<span>3. <span class="gender"> @php if($data['member_id']!=''){ echo $data['irc_data']->gender == 'Female' ? 'She' : 'He'; } @endphp </span> was a </span>
										</label> 
										<div class="input-field inline">
											<select id="messengerone" name="messengerone" class="browser-default">
											    <option value="" disabled selected>Choose your option</option>
											    <option>MESSENGER</option>
											    <option>CLERICAL</option>
											    <option>SPECIAL GRADE CLERK</option>
											    <option>OTHER</option>
											</select>
										</div>
										<span>
											before RETIEMENT [Delete which is not applicable]
										</span>
										
								</div>
							
							</div>	
							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" name="attachedboxone" id="attachedboxone"  value="1"/>
						          		<span>4. Attached is <span class="genderone"> @php if($data['member_id']!=''){ echo $data['irc_data']->gender == 'Female' ? 'Her' : 'His'; } @endphp </span> RETIREMENT Letter (compulsory)</span>
						            </label> 
									<div class="input-field inline">
										<div class="row">
											<div class="col s12 m3">
												 <div id="">
			                                        <div class=" ">
			                                         	<br>
			                                            <input type="file" name="attachmentone" class="" accept="" style="width: 500px;">
			                                        </div>
			                                        <div class="file-path-wrapper hide">
			                                            <input class="file-path validate" type="text">
			                                        </div>
			                                    </div>
											</div>
											<div class="col s12 m6 hide">
												<input type="text" id="attachedone" name="attachedone" class="inline-box" style="width: 500px;" >
											</div>
										</div>
									</div>
						        </div>
								
							</div>	
							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" name="jobtakenboxone" id="jobtakenboxone"  value="1"/>
						          		<span>5. Member’s job functions have been taken over by</span>
						            </label> 
									<div class="input-field inline">
										<input type="text" 	name="jobtakenbyone" id="jobtakenbyone" placeholder="" class="inline-box" style="width: 500px;"/>
										
									</div>
									 and
						        </div>
							
							</div>
							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" name="posfilledbyboxone" id="posfilledbyboxone"  value="1"/>
						          		<span>6. Member’s position has not been filled up by another  </span>
						            </label> 
									<div class="input-field inline">
										<select id="posfilledbytypeone" name="posfilledbytypeone" onchange="return showAutocomplete(this.value,'posfillarea')" class="browser-default">
										    <option value="" disabled selected>Choose your option</option>
										    <option value="1">Member</option>
										    <option value="2">Non-Member</option>
										    <option value="3">Other</option>
										</select>
									</div>
									<div id="posfillarea" class="input-field inline hide">
										<input type="text"	name="posfilledbymemberone" id="posfilledbymemberone" placeholder="Member No/NRIC" class="inline-box posfilledbymemberone" style="width: 250px;"/>
										<input type="hidden" name="posfilledbymemberidone" id="posfilledbymemberidone" class="posfilledbymemberidone">
									</div>
									<span>[Please specify others in detail]</span>
									<div class="input-field inline">
										<input type="text" 	name="posfilledbyone" id="posfilledbyone" placeholder="" class="inline-box posfilledbyone" style="width: 500px;"/>
										
									</div>
						        </div>
								
							</div>	
							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" name="replacestaffboxone" id="replacestaffboxone"  value="1"/>
						          		<span>7. REPLACEMENT Staff Grade is </span>
						            </label> 
						            <div class="input-field inline">
										<select id="replacestafftypeone" name="replacestafftypeone" class="browser-default">
										    <option value="" disabled selected>Choose your option</option>
										    <option >Non-Clerical</option>
										    <option >Clerical</option>
										    <option >Special Grade Clerical</option>
										    <option >Other</option>
										</select>
									</div>
									[Please specify others in detail]
									<div class="input-field inline">
										<input type="text" 	name="replacestaffone" id="replacestaffone" placeholder="" class="inline-box" style="width: 250px;"/>
										
									</div>
						        </div>
								
							</div>	
							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" name="appcontactboxone" onclick="return MakeRequired('appcontactboxone',1)" id="appcontactboxone"  value="1"/>
						          		<span>8. Applicant Contact</span>
						            </label> 
									<div class="input-field inline">
										<input type="text" 	name="appcontactone" id="appcontactone" placeholder=""  class="inline-box allow_contactnumbers" style="width: 250px;"/>
										
									</div>
									 <span>Office</span>
									 <div class="input-field inline">
										<input type="text" 	name="appofficeone" id="appofficeone" placeholder="" class="inline-box allow_contactnumbers" style="width: 250px;"/>
										
									</div>
						        </div>

								<div class="clearfix"></div>
								<div class="col s12">
									
									<span>
										&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; <span>H/P</span>
									</span>	
									
									<div class="input-field inline">
										<input type="text" 	name="apphpone" id="apphpone" placeholder="" class="inline-box allow_contactnumbers" style="width: 250px;"/>
										
									</div>
									 <span>Fax</span>
									 <div class="input-field inline">
										<input type="text" 	name="appfaxone" id="appfaxone" placeholder="" class="inline-box allow_contactnumbers" style="width: 250px;"/>
										
									</div>
									<span>Email</span>
									 <div class="input-field inline">
										<input type="email" name="appemailone" id="appemailone" placeholder="" class="inline-box " style="width: 250px;"/>
										
									</div>
						        </div>
								
							</div>	
						</div>
						<div id="deceased_section" class="reasonsections hide"> 

							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" name="memberdemisedboxtwo" id="memberdemisedboxtwo"  value="1"/>
						          		<span>1. Member DEMISED on</span>
						            </label> 
									<div class="input-field inline">
										<input type="text" class="datepicker-custom inline-box" id="memberdemisedtwo" value="{{ date('d/m/Y') }}" name="memberdemisedtwo" >
										
									</div>
						        </div>

							</div>	

							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" name="nameofpersonboxtwo" id="nameofpersonboxtwo"  value="1"/>
						          		<span>2. Name of Member’s next of kin is</span>
						            </label> 
									<div class="input-field inline">
										<input type="text" class="inline-box" style="width: 500px;" name="nameofpersontwo" id="nameofpersontwo" placeholder="" />
										
									</div>
						        </div>

						        <div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" name="relationshipboxtwo" id="relationshipboxtwo"  value="1"/>
						          		<span>3. Relationship is</span>
						            </label> 
									<div class="input-field inline">
										<input type="text" 	name="relationshiptwo" id="relationshiptwo" placeholder="" class="inline-box" style="width: 300px;"/>
										
									</div>
						        </div>
							
							</div>	


							<div class="row padding-left-20">
								<div class="col s12 m2 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="applicantboxtwo" id="applicantboxtwo"  value="1"/>
										<span>4. Applicant</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m1 " style="margin-left: 20px;">
									<p>
										<label>
										<input type="radio" class="common-checkbox" name="applicanttwo" id="applicanttwo"  value="1"/>
										<span>Has</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m5 ">
									<p>
										<label>
										<input type="radio" class="common-checkbox" name="applicanttwo" id="applicanttwo"  value="2"/>
										<span>Does Not have Legal Authority (LA) to claim  </span>
										</label> 
									</p>	
								</div>
							
							</div>	

							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" name="jobtakenboxtwo" id="jobtakenboxtwo"  value="1"/>
						          		<span>5. Member’s job functions have been taken over by</span>
						            </label> 
									<div class="input-field inline">
										<input type="text" 	name="jobtakenbytwo" id="jobtakenbytwo" placeholder="" class="inline-box" style="width: 400px;"/>
										
									</div>
									 and
						        </div>
							
							</div>
							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" name="posfilledbyboxtwo" id="posfilledbyboxtwo"  value="1"/>
						          		<span>6. Member’s position has not been filled up by another </span>
						            </label> 
						            <div class="input-field inline">
										<select id="posfilledbytypetwo" name="posfilledbytypetwo" onchange="return showAutocomplete(this.value,'posfillareatwo')" class="browser-default">
										    <option value="" disabled selected>Choose your option</option>
										    <option value="1">Member</option>
										    <option value="2">Non-Member</option>
										    <option value="3">Other</option>
										</select>
									</div>
									<div id="posfillareatwo" class="input-field inline hide">
										<input type="text"	name="posfilledbymembertwo" id="posfilledbymembertwo" placeholder="Member No/NRIC" class="inline-box posfilledbymemberone" style="width: 250px;"/>
										<input type="hidden" name="posfilledbymemberidtwo" id="posfilledbymemberidtwo" class="posfilledbymemberidone">
									</div>
									<span>[Please specify others in detail]</span>
									
									<div class="input-field inline">
										<input type="text" 	name="posfilledbytwo" class="inline-box posfilledbyone" style="width: 500px;" id="posfilledbytwo" placeholder=""/>
										
									</div>
						        </div>
								
							</div>	
							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" name="replacestaffboxtwo" id="replacestaffboxtwo"  value="1"/>
						          		<span>7. REPLACEMENT Staff Grade is </span>
						            </label> 
						             <div class="input-field inline">
										<select id="replacestafftypetwo" name="replacestafftypetwo" class="browser-default">
										    <option value="" disabled selected>Choose your option</option>
										    <option >Non-Clerical</option>
										    <option >Clerical</option>
										    <option >Special Grade Clerical</option>
										    <option >Other</option>
										</select>
									</div>
									[Please specify others in detail]
									<div class="input-field inline">
										<input type="text" 	name="replacestafftwo" id="replacestafftwo" placeholder=""  class="inline-box" style="width: 250px;"/>
										
									</div>
						        </div>
								
							</div>	

							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" name="appcontactboxtwo" id="appcontactboxtwo"  onclick="return MakeRequired('appcontactboxtwo',2)" value="1"/>
						          		<span>8. Next of Kin’s Contact</span>
						            </label> 
									<div class="input-field inline">
										<input type="text" 	name="appcontacttwo" id="appcontacttwo" placeholder="" class="inline-box allow_contactnumbers" style="width: 250px;"/>
										
									</div>
									 <span>Office</span>
									 <div class="input-field inline">
										<input type="text" 	name="appofficetwo" id="appofficetwo" placeholder="" class="inline-box allow_contactnumbers" style="width: 250px;"/>
										
									</div>
						        </div>

								<div class="clearfix"></div>
								<div class="col s12">
									
									<span>
										&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; <span>Mobile</span>
									</span>	
									
									<div class="input-field inline">
										<input type="text" 	name="appmobiletwo" id="appmobiletwo" placeholder="" class="inline-box allow_contactnumbers" style="width: 250px;"/>
										
									</div>
									 <span>Fax</span>
									 <div class="input-field inline">
										<input type="text" 	name="appfaxtwo" id="appfaxtwo" placeholder="" class="inline-box allow_contactnumbers" style="width: 250px;"/>
										
									</div>
									<span>Email</span>
									 <div class="input-field inline">
										<input type="email" name="appemailtwo" id="appemailtwo" placeholder="" class="inline-box " style="width: 250px;"/>
										
									</div>
						        </div>
								
							</div>	
						
							
						</div>
						<div id="promoted_section" class="reasonsections hide"> 

							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" name="nameofpersonboxthree" id="nameofpersonboxthree"  value="1"/>
						          		<span>1. BF Applicant’s Name:</span>
						            </label> 
									<div class="input-field inline">
										<input type="text" id="person_namethree" name="person_namethree" value="{{ $data['member_id']!='' ? $data['irc_data']->membername : '' }}" class="inline-box" style="width: 560px;" readonly>
										
									</div>
						        </div>
								
							</div>	

							<div class="row padding-left-20">
								<div class="col s12 m12 ">
									
										<label>
										<input type="checkbox" class="common-checkbox" name="messengerboxthree" id="messengerboxthree"  value="1"/>
										<span>2. <span class="gender"> @php if($data['member_id']!=''){ echo $data['irc_data']->gender == 'Female' ? 'She' : 'He'; } @endphp </span> was a </span>
										</label> 
										<div class="input-field inline">
											<select id="messengerthree" name="messengerthree" class="browser-default">
											    <option value="" disabled selected>Choose your option</option>
											    <option>MESSENGER</option>
											    <option>CLERICAL</option>
											    <option>SPECIAL GRADE CLERK</option>
											    <option>OTHER</option>
											</select>
										</div>
										<span> before PROMOTION [Delete which is not applicable]</span>
								</div>
							
							</div>	
							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" name="promotedboxthree" id="promotedboxthree"  value="1"/>
						          		<span>3. <span class="gender"> @php if($data['member_id']!=''){ echo $data['irc_data']->gender == 'Female' ? 'She' : 'He'; } @endphp </span> was <span style="text-decoration: underline;">PROMOTED</span> to</span>
						            </label> 
									<div class="input-field inline">
										<input type="text" id="promotedthree" name="promotedthree" class="inline-box" style="width: 300px;" required="">
										
									</div>
									grade w.e.f.
									<div class="input-field inline">
										<input type="text" 	name="gradewefthree" id="gradewefthree" placeholder="grade w.e.f" value="{{ date('d/m/Y') }}"  class="datepicker-custom inline-box"/>
										
									</div>
						        </div>
								
							</div>	

						
							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" name="attachedboxthree" id="attachedboxthree"  value="1"/>
						          		<span>4. Attached is <span class="genderone"> @php if($data['member_id']!=''){ echo $data['irc_data']->gender == 'Female' ? 'Her' : 'His'; } @endphp </span> Job Description (compulsory)</span>
						            </label> 

									<div class="input-field inline">
										<div class="row">
											<div class="col s12 m3">
												 <div id="">
			                                        <div class=" ">
			                                         	<br>
			                                            <input type="file" name="attachmentthree" class="" accept="" style="width: 500px;">
			                                        </div>
			                                        <div class="file-path-wrapper hide">
			                                            <input class="file-path validate" type="text">
			                                        </div>
			                                    </div>
											</div>
											<div class="col s12 m6 hide">
												<input type="text" name="attachedthree" id="attachedthree" class="inline-box" style="width: 500px;">
											</div>
										</div>
										
										
										
									</div>
						        </div>
						        <div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" name="transfertoplaceboxthree" id="transfertoplaceboxthree" value="1"/>
						          		<span>5. <span class="gender"> @php if($data['member_id']!=''){ echo $data['irc_data']->gender == 'Female' ? 'She' : 'He'; } @endphp </span> Promoted and transfer to new place</span>
						            </label> 
									<div class="input-field inline">
										<input type="text" name="transfertoplacethree" id="transfertoplacethree" class="inline-box" style="width: 500px;" >
										
									</div>
						        </div>
								
							</div>	
							
							<div class="row padding-left-20">
								<div class="col s12 m12 ">
									
									<label>
									<input type="checkbox" class="common-checkbox" name="samebranchboxthree" id="samebranchboxthree"  value="1"/>
									<span>6. Member is still in the same </span>
									</label> 
									<div class="input-field inline">
										<select id="samebranchtype" name="samebranchtype" onchange="return ChangePromoted(this.value)" class="browser-default">
										    <option value="" disabled selected>Choose your option</option>
										    <option>Branch</option>
										    <option>Department</option>
										    <option>Others</option>
										</select>
									</div>
									<div id="samebranchothersdiv" class="input-field inline hide" style="">
										<input type="text" name="samebranchothers" id="samebranchothers" class="inline-box" style="width: 500px;margin-top: 20px !important;" >
									</div>
									performing the same job functions
								</div>
							
							</div>	
							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" name="jobtakenboxthree" id="jobtakenboxthree"  value="1"/>
						          		<span>7. Member’s job functions have been taken over by</span>
						            </label> 
									<div class="input-field inline">
										<input type="text" 	name="jobtakenbythree" id="jobtakenbythree" placeholder="" class="inline-box" style="width: 500px;"/>
										
									</div>
									 and
						        </div>
							
							</div>
							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" name="posfilledbyboxthree" id="posfilledbyboxthree"  value="1"/>
						          		<span>8. Member’s position has not been filled up by another </span>
						            </label> 
						            <div class="input-field inline">
										<select id="posfilledbytypethree" name="posfilledbytypethree" onchange="return showAutocomplete(this.value,'posfillareathree')" class="browser-default">
										    <option value="" disabled selected>Choose your option</option>
										    <option value="1">Member</option>
										    <option value="2">Non-Member</option>
										    <option value="3">Other</option>
										</select>
									</div>
									<div id="posfillareathree" class="input-field inline hide">
										<input type="text"	name="posfilledbymemberthree" id="posfilledbymemberthree" placeholder="Member No/NRIC" class="inline-box posfilledbymemberone" style="width: 250px;"/>
										<input type="hidden" name="posfilledbymemberidthree" id="posfilledbymemberidthree" class="posfilledbymemberidone">
									</div>
									<span>[Please specify others in detail]</span>
									
									
									<div class="input-field inline">
										<input type="text" 	name="posfilledbythree" id="posfilledbythree" placeholder="" class="inline-box posfilledbyone" style="width: 500px;"/>
										
									</div>
						        </div>
								
							</div>	
							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" name="replacestaffboxthree" id="replacestaffboxthree"  value="1"/>
						          		<span>9. REPLACEMENT Staff Grade is </span>
						            </label> 
						            <div class="input-field inline">
										<select id="replacestafftypethree" name="replacestafftypethree" class="browser-default">
										    <option value="" disabled selected>Choose your option</option>
										    <option >Non-Clerical</option>
										    <option >Clerical</option>
										    <option >Special Grade Clerical</option>
										    <option >Other</option>
										</select>
									</div>
									[Please specify others in detail]
									<div class="input-field inline">
										<input type="text" 	name="replacestaffthree" id="replacestaffthree" placeholder=""  class="inline-box" style="width: 250px;"/>
										
									</div>
						        </div>
								
							</div>	
							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" name="appcontactboxthree" id="appcontactboxthree" onclick="return MakeRequired('appcontactboxthree',3)"  value="1"/>
						          		<span>10. Applicant Contact</span>
						            </label> 
									<div class="input-field inline">
										<input type="text" 	name="appcontactthree" id="appcontactthree" placeholder=""  class="inline-box allow_contactnumbers" style="width: 250px;"/>
										
									</div>
									 <span>Office</span>
									 <div class="input-field inline">
										<input type="text" 	name="appofficethree" id="appofficethree" placeholder="" class="inline-box allow_contactnumbers" style="width: 250px;"/>
										
									</div>
						        </div>

								<div class="clearfix"></div>
								<div class="col s12">
									
									<span>
										&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; <span>H/P</span>
									</span>	
									
									<div class="input-field inline">
										<input type="text" 	name="apphpthree" id="apphpthree" placeholder=""  class="inline-box allow_contactnumbers" style="width: 250px;"/>
										
									</div>
									 <span>Fax</span>
									 <div class="input-field inline">
										<input type="text" 	name="appfaxthree" id="appfaxthree" placeholder=""  class="inline-box allow_contactnumbers" style="width: 250px;"/>
										
									</div>
									<span>Email</span>
									 <div class="input-field inline">
										<input type="email" name="appemailthree" id="appemailthree" placeholder="" class="inline-box " style="width: 250px;"/>
										
									</div>
						        </div>
								
							</div>

								
						</div>
						<div id="resign_section" class="reasonsections hide"> 

							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" name="personnameboxfour" id="personnameboxfour"  value="1"/>
						          		<span>1. BF Applicant’s Name is</span>
						            </label> 
									<div class="input-field inline">
										<input type="text" id="person_namefour" class="inline-box" style="width: 560px;" name="person_namefour" value="{{ $data['member_id']!='' ? $data['irc_data']->membername : '' }}" readonly>
										
									</div>
						        </div>
						        <div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" name="resignedonboxfour" id="resignedonboxfour"  value="1"/>
						          		<span>2. <span class="gender"> @php if($data['member_id']!=''){ echo $data['irc_data']->gender == 'Female' ? 'She' : 'He'; } @endphp </span>  </span>
						            </label> 
						            <div class="input-field inline">
										<select id="resigntypefour" name="resigntypefour" class="browser-default">
										    <option value="" disabled selected>Choose your option</option>
										    <option>RESIGNED</option>
										    <option>TERMINATED</option>
										</select>
									</div>	
									on
									<div class="input-field inline">
										<input type="text" 	name="gradeweffour" id="gradeweffour" placeholder="grade w.e.f"  value="{{ date('d/m/Y') }}" class="datepicker-custom inline-box"/>
										
									</div>
						        </div>
								
							</div>	
							

							<div class="row padding-left-20">
								<div class="col s12 m12 ">
									
										<label>
											<input type="checkbox" class="common-checkbox" name="messengerboxfour" id="messengerboxfour"  value="1"/>
											<span>3. <span class="gender"> @php if($data['member_id']!=''){ echo $data['irc_data']->gender == 'Female' ? 'She' : 'He'; } @endphp </span> was a </span>
										</label> 
										<div class="input-field inline">
											<select id="messengerfour" name="messengerfour" class="browser-default">
											    <option value="" disabled selected>Choose your option</option>
											    <option>MESSENGER</option>
											    <option>CLERICAL</option>
											    <option>SPECIAL GRADE CLERK</option>
											    <option>OTHER</option>
											</select>
										</div>
										<span> before RESIGNATION [Delete which is not applicable]</span>
								</div>
							
							</div>	
								

						
							<div class="row padding-left-20">
								<div class="col s12 m12 ">
									
									<label>
									<input type="checkbox" class="common-checkbox" name="attachedboxfour" id="attachedboxfour"  value="1"/>
									<span>4. Attached is <span class="genderone"> @php if($data['member_id']!=''){ echo $data['irc_data']->gender == 'Female' ? 'Her' : 'His'; } @endphp </span> </span>
									</label> 
									<div class="input-field inline">
										<select id="attachfourtype" name="attachfourtype" class="browser-default">
										    <option value="" disabled selected>Choose your option</option>
										    <option>RESIGNATION</option>
										    <option>TERMINATION</option>
										    <option>EXPULSION</option>
										    <option>STRUCK OFF</option>
										</select>
									</div>
									Letter (compulsory)
										
									<div class="input-field inline">
										<div class="row">
											<div class="col s12 m4">
												 <div id="">
			                                        <div class=" ">
			                                         	<br>
			                                            <input type="file" name="attachmentfour" class="" accept="" style="width: 500px;">
			                                        </div>
			                                        <div class="file-path-wrapper hide">
			                                            <input class="file-path validate" type="text">
			                                        </div>
			                                    </div>
											</div>
											<div class="col s12 m6 hide">
												 <input type="text" id="attachedfour" name="attachedfour" class="inline-box" style="width: 330px;">
											</div>
										</div>
									</div>
								</div>
								
							</div>	
								
							<div class="row padding-left-20">
								<div class="col s12 m12 ">
									
									<label>
									<input type="checkbox" class="common-checkbox" name="jobtakenboxfour" id="jobtakenboxfour"  value="1"/>
									<span>5. Member’s job functions have been taken over by</span>
									</label> 
									
									<div class="input-field inline">
										 <input type="text" name="jobtakenbyfour" id="jobtakenbyfour" placeholder="" class="inline-box" style="width: 400px;"/>
									</div>
									and
								</div>
								
							</div>
							<div class="row padding-left-20">
								<div class="col s12 m12 ">
								
									<label>
									<input type="checkbox" class="common-checkbox" name="posfilledbyboxfour" id="posfilledbyboxfour"  value="1"/>
									<span>6. Member’s position has not been filled up by another </span>
									</label> 
									<div class="input-field inline">
										<select id="posfilledbytypefour" name="posfilledbytypefour" onchange="return showAutocomplete(this.value,'posfillareafour')" class="browser-default">
										    <option value="" disabled selected>Choose your option</option>
										    <option value="1">Member</option>
										    <option value="2">Non-Member</option>
										    <option value="3">Other</option>
										</select>
									</div>
									<div id="posfillareafour" class="input-field inline hide">
										<input type="text"	name="posfilledbymemberfour" id="posfilledbymemberfour" placeholder="Member No/NRIC" class="inline-box posfilledbymemberone" style="width: 250px;"/>
										<input type="hidden" name="posfilledbymemberidfour" id="posfilledbymemberidfour" class="posfilledbymemberidone">
									</div>
									<span>[Please specify others in detail]</span>
									
									

									<div class="input-field inline">
										<input type="text" 	name="posfilledbyfour" id="posfilledbyfour" placeholder="" class="inline-box posfilledbyone" style="width: 500px;"/>
									</div>	
								</div>
								
							</div>	
							<div class="row padding-left-20">
								<div class="col s12 m12 ">
									
									<label>
									<input type="checkbox" class="common-checkbox" name="replacestaffboxfour" id="replacestaffboxfour"  value="1"/>
									<span>7. REPLACEMENT Staff Grade is </span>
									</label> 
									<div class="input-field inline">
										<select id="replacestafftypefour" name="replacestafftypefour" class="browser-default">
										    <option value="" disabled selected>Choose your option</option>
										    <option >Non-Clerical</option>
										    <option >Clerical</option>
										    <option >Special Grade Clerical</option>
										    <option >Other</option>
										</select>
									</div>
									[Please specify others in detail]
										
									<div class="input-field inline">						
										<input type="text" 	name="replacestafffour" id="replacestafffour" placeholder="" class="inline-box" style="width: 250px;"/>
									</div>
								</div>
							</div>	
							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" name="appcontactboxfour" id="appcontactboxfour" onclick="return MakeRequired('appcontactboxfour',4)"  value="1"/>
						          		<span>8. Applicant Contact</span>
						            </label> 
									<div class="input-field inline">
										<input type="text" 	name="appcontactfour" id="appcontactfour" placeholder="" class="inline-box allow_contactnumbers" style="width: 250px;"/>
										
									</div>
									 <span>Office</span>
									 <div class="input-field inline">
										<input type="text" 	name="appofficefour" id="appofficefour" placeholder="" class="inline-box allow_contactnumbers" style="width: 250px;"/>
										
									</div>
						        </div>

								<div class="clearfix"></div>
								<div class="col s12">
									
									<span>
										&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; <span>H/P</span>
									</span>	
									
									<div class="input-field inline">
										<input type="text" 	name="apphpfour" id="apphpfour" placeholder=""  class="inline-box allow_contactnumbers" style="width: 250px;"/>
										
									</div>
									 <span>Fax</span>
									 <div class="input-field inline">
										<input type="text" 	name="appfaxfour" id="appfaxfour" placeholder=""  class="inline-box allow_contactnumbers" style="width: 250px;"/>
										
									</div>
									<span>Email</span>
									 <div class="input-field inline">
										<input type="email" name="appemailfour" id="appemailfour" placeholder=""  style="width: 250px;"  class="inline-box"/>
										
									</div>
						        </div>
								
							</div>	
						</div>
						<div id="expelled_section" class="reasonsections hide"> 

							<div class="row padding-left-20">
								<div class="col s12 m12">
								
									<label>
									<input type="checkbox" class="common-checkbox" name="expelledboxfive" id="expelledboxfive"  value="1"/>
									<span>1. Member was 
									</label> 

									<div class="input-field inline">
										<select id="expelledtypefive" name="expelledtypefive" class="browser-default">
										    <option value="" disabled selected>Choose your option</option>
										    <option>EXPELLED</option>
										    <option>STRUCK OFF</option>
										    <option>BLACKLISTED</option>
										</select>
									</div>
									 on
									<div class="input-field inline">						
										<input type="text" 	name="gradeweffive" id="gradewef" placeholder="grade w.e.f" value="{{ date('d/m/Y') }}"  class="datepicker-custom inline-box"/>
									</div>
								</div>
							</div>

								
							<div class="row padding-left-20">
								<div class="col s12 m12 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" name="samejobboxfive" id="samejobboxfive"  value="1"/>
										<span>2. Member’s is still performing the same job functions</span>
										</label> 
									</p>	

								</div>
								
							</div>
							<div class="row padding-left-20">
								<div class="col s12 m12 ">
									
									<label>
									<input type="checkbox" class="common-checkbox" name="samebranchboxfive" id="samebranchboxfive"  value="1"/>
									<span>3. Member is still in the same </span>
									</label> 
									
									<div class="input-field inline">
										<select id="samebranchtypefive" name="samebranchtypefive" class="browser-default">
										    <option value="" disabled selected>Choose your option</option>
										    <option>Branch</option>
										    <option>Department</option>
										</select>
									</div>
								</div>
								
							</div>	
							<div class="row padding-left-20">
								<div class="col s12 m12 ">
									
									<label>
									<input type="checkbox" class="common-checkbox" name="memberstoppedboxfive" id="memberstoppedboxfive"  value="1"/>
									<span>4. Member </span>
									</label> 
									
									<div class="input-field inline">
										<select id="stoppedtypefive" name="stoppedtypefive" class="browser-default">
										    <option value="" disabled selected>Choose your option</option>
										    <option>HAS STOPPED</option>
										    <option>HAS NOT STOPPED</option>
										</select>
									</div>	
									 the Check-Off [Delete whichever is applicable]
									 <br>
									 <br>
								</div>
								
							</div>	
							
						</div>
					</div>

					
					
				
					</div>
			  </div>
			  <div class="card @php if($user_role =='irc-confirmation' || $user_role =='irc-confirmation-officials') echo 'branch'; @endphp">
			  <h5 class="padding-left-10">BRANCH SECRETARY VERIFICATION</h5>
			  <div class="row padding-left-20">
					<div class="col s12 m12" style="line-height: 5px;">
						<label>
						
							<input type="checkbox"  name="committieverificationboxone" id="committieverificationboxone" class="common-checkbox"  value="1"/>
							<span>I</span>
						</label> 
						<div class="input-field inline">	
							<input type="text" id="committiename" name="committiename" placeholder="" value="" class="inline-box" style="width: 280px;">	
						</div>
						Secretary of NUBE
						<div class="input-field inline">	
							<input type="text" id="committieverifyname" name="committieverifyname" placeholder="" value="" class="inline-box" style="width: 280px;">	
						</div>
						Branch have verified the above and <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; confirm that the declaration 
						 by the IRC is correct.
					</div>
					<div class="col s12 m12 " style="margin-top: 10px;">
						<p>
							<label>
							<input type="checkbox" class="common-checkbox" name="committieverificationboxtwo" id="committieverificationboxtwo"  value="1"/>
							<span>Staff who has taken over the job functions under CODE <span id="codenumber"></span> is a NUBE Member. </span>
							</label> 
						</p>	
					</div>
					<div class="col s12 m12 ">
							
							<label>
								<input type="checkbox" class="common-checkbox" name="committieverificationboxthree" id="committieverificationboxthree"  value="1" />
								<span>Staff who is under 
								<div class="input-field inline">
									<select id="committiecode" name="committiecode" class="browser-default">
									    <option value="" disabled selected>Choose code</option>
									    <option value="3">CODE 03</option>
									    <option value="5">CODE 05</option>
									</select>
								</div>
								  is still performing the same job function.  The additional information for this staff is as follows:  </span>
								
							</label> 
							<br>
							<div class="input-field inline" style="margin: 0 0 0 27px !important;">	
								<input type="text" name="committieremark" id="committieremark" style="width: 650px;">
							</div>
							<span>(Remark)</span>
							
						
					</div>
			   </div>
			    <div class="row">
			    	<div class="col s12 m12">
			    		<div class="col s12 m12">
				    		<p class="bold">
				    			Note: 
				    		</p>
				    		<p>
				    			01 - RETIRED, &nbsp; &nbsp; 02 - DECEASED, &nbsp; &nbsp; 03 - PROMOTED, &nbsp; &nbsp; 04 - RESIGNED FROM BANK / RESIGNED FROM NUBE / TERMINATED BY BANK / TRANSFERRED TO SABAH / SARAWAK, &nbsp; &nbsp; 05 - EXPELLED / STRUCK OFF / BLACKLISTED FROM NUBE
				    		</p>
				    	</div>
			    		
			    	</div>
			   	</div>
				<div class="row">
					
					<div class="padding-left-20 ">
						<br>
					</div>
				
					<div class="col s12 m12">
						<div class="row">
							<div class="col s12 m3 ">
								<p >
									<label>
									
									<span>Secretary [Name in full]</span>
									</label> 
								</p>	
							</div>
							<div class="col s12 m4 ">
									<input type="text" name="person_name">
							</div>
						</div>	
					</div>
					<div class="col s12 m12">
						<div class="row">
							<div class="col s12 m3 ">
								<p >
									<label>
									<span>Zone</span>
									</label> 
								</p>	
							</div>
							<div class="col s12 m4 ">
									<input type="text" name="person_name">
							</div>
							<div class="col s12 m3 ">
							<!--<label>Date</label> -->
								<div class="input-field inline"> 
									<input type="text" class="datepicker-custom" placeholder="DD/MM/YYYY" name="date">
								</div>(Date)
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
						<div class="col s12 m4">
                            <div class=" ">
                             	
                             	<br>
                                <input type="file" name="formupload" id="formupload" class="" accept="" style="width: 500px;">
                            </div>
                            <div class="file-path-wrapper hide">
                                <input class="file-path validate" type="text">
                            </div>
						</div>
						
					</div>
					<div class="row">
						
						<div class="input-field col s12 m8">
						  <i class="material-icons prefix">person_outline</i>
						  <input id="irc_user_name" name="irc_user_name" class=""  type="text">
						  <label for="" class="force-active">IRC Branch Committee Username</label>
						</div> 
						
						<div class="input-field col s12 m2">
							<p>
							<input type="submit" class="btn" id="save" name="save" value="Submit" />
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
				url: "{{ URL::to('/get-member-irclist-values') }}?member_id="+ $("#member_number").val(),
                type: "GET",
				dataType: "json",
				success: function(res) {

					if(res!=1){
						$('#member_type').val(res.membertype);
						$('#type').val(res.membertype);
						$('#member_title').val(res.persontitle);
						$('#member_name').val(res.membername);
						$('#person_nameone,#person_namethree,#person_namefour').val(res.membername);
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
					}else{
						alert("IRC entry already added for this member");
						$('#memberdetailssection').find('input:text').val('');    
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
	var codenumber = '';
	if(reason=='RETIRED'){
		$("#retired_section").removeClass('hide');
		$("#section_type").val(1);
		
		codenumber = '01';
	}else if(reason=='DECEASED'){
		$("#deceased_section").removeClass('hide');
		$("#section_type").val(2);
		
		codenumber = '02';
	}else if(reason=='PROMOTED'){
		$("#promoted_section").removeClass('hide');
		$("#section_type").val(3);
		
		codenumber = '03';
	}
	else if(reason=='RESIGN FROM BANK' || reason=='RESIGN FROM UNION' || reason=='TERMINATED BY BANK'){
		$("#resign_section").removeClass('hide');
		$("#section_type").val(4);
		
		codenumber = '04';
	}else if(reason=='EXPELLED' || reason=='STRUCK OFF' || reason=='BLACKLISTED FROM UNION' || reason=='BLACK LIST'){
		$("#expelled_section").removeClass('hide');
		$("#section_type").val(5);
		
		codenumber = '05';
	}else{
		$("#resign_section").removeClass('hide');
		$("#section_type").val(4);
		
		codenumber = '04';
	}
	$("#codenumber").text(codenumber);
}
$(document).on('input', '.allow_contactnumbers', function(){
   var self = $(this);
   self.val(self.val().replace(/[^0-9\+ .]/g, ''));
   if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) 
   {
     evt.preventDefault();
   }
 });

$(".posfilledbymemberone").devbridgeAutocomplete({
	//lookup: countries,
	serviceUrl: "{{ URL::to('/get-activemember-list') }}?searchkey="+ $(".posfilledbymemberone").val(),
	type:'GET',
	//callback just to show it's working
	onSelect: function (suggestion) {
			$(".posfilledbymemberone").val(suggestion.member_number);
			$.ajax({
				url: "{{ URL::to('/get-member-irclist-values') }}?member_id="+ $(".posfilledbymemberone").val(),
                type: "GET",
				dataType: "json",
				success: function(res) {

					if(res!=1){
						
						$('.posfilledbyone').val(res.membername);
						$('.posfilledbymemberidone').val(res.memberid);
						
					}else{
						$('.posfilledbyone').val('');    
						$('.posfilledbymemberidone').val('');    
						alert("IRC entry already added for this member");
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
function showAutocomplete(type,refname){
	if(type==1){
		$("#"+refname).removeClass('hide');
	}else{
		$("#"+refname).addClass('hide');
		$('.posfilledbyone').val('');
		$('.posfilledbymemberidone').val('');
		$('.posfilledbymemberone').val('');
	}
}
function MakeRequired(refid,refno){
	if(refno==1){
		if($("#"+refid).is(':checked')){
			$("#appcontactone,#appofficeone,#apphpone,#appemailone").attr('required',true);
		}else{
			$("#appcontactone,#appofficeone,#apphpone,#appemailone").attr('required',false);
		}
		$("#appcontacttwo,#appofficetwo,#appmobiletwo,#appemailtwo").attr('required',false);
		$("#appcontactthree,#appofficethree,#apphpthree,#appemailthree").attr('required',false);
		$("#appcontactfour,#appofficefour,#apphpfour,#appemailfour").attr('required',false);
	}else if(refno==2){
		if($("#appcontactboxtwo").is(':checked')){
			$("#appcontacttwo,#appofficetwo,#appmobiletwo,#appemailtwo").attr('required',true);
		}else{
			$("#appcontacttwo,#appofficetwo,#appmobiletwo,#appemailtwo").attr('required',false);
		}

		$("#appcontactone,#appofficeone,#apphpone,#appemailone").attr('required',false);
		$("#appcontactthree,#appofficethree,#apphpthree,#appemailthree").attr('required',false);
		$("#appcontactfour,#appofficefour,#apphpfour,#appemailfour").attr('required',false);
	}else if(refno==3){
		if($("#appcontactboxthree").is(':checked')){
			$("#appcontactthree,#appofficethree,#apphpthree,#appemailthree").attr('required',true);
		}else{
			$("#appcontactthree,#appofficethree,#apphpthree,#appemailthree").attr('required',false);
		}

		$("#appcontactone,#appofficeone,#apphpone,#appemailone").attr('required',false);
		$("#appcontacttwo,#appofficetwo,#appmobiletwo,#appemailtwo").attr('required',false);
		$("#appcontactfour,#appofficefour,#apphpfour,#appemailfour").attr('required',false);
	}else if(refno==4){
		if($("#appcontactboxfour").is(':checked')){
			$("#appcontactfour,#appofficefour,#apphpfour,#appemailfour").attr('required',true);
		}else{
			$("#appcontactfour,#appofficefour,#apphpfour,#appemailfour").attr('required',false);
		}

		$("#appcontactone,#appofficeone,#apphpone,#appemailone").attr('required',false);
		$("#appcontacttwo,#appofficetwo,#appmobiletwo,#appemailtwo").attr('required',false);
		$("#appcontactthree,#appofficethree,#apphpthree,#appemailthree").attr('required',false);
	}else if(refno==5){
		$("#appcontactone,#appofficeone,#apphpone,#appemailone").attr('required',false);
		$("#appcontacttwo,#appofficetwo,#appmobiletwo,#appemailtwo").attr('required',false);
		$("#appcontactthree,#appofficethree,#apphpthree,#appemailthree").attr('required',false);
		$("#appcontactfour,#appofficefour,#apphpfour,#appemailfour").attr('required',false);
	}else{

	}
	
}

function ChangePromoted(provalue){
	if(provalue=='Others'){
		$("#samebranchothersdiv").removeClass('hide');
	}else{
		$("#samebranchothersdiv").addClass('hide');
	}
}

</script>
@endsection