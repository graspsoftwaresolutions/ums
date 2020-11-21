@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
<link rel="stylesheet" type="text/css"
href="{{ asset('public/assets/vendors/data-tables/css/jquery.dataTables.min.css') }}"> 
<link rel="stylesheet" type="text/css"
href="{{ asset('public/assets/vendors/data-tables/css/select.dataTables.min.css') }}"> 
<link rel="stylesheet" type="text/css"
href="{{ asset('public/assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css') }}">
@endsection
@section('headSecondSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/pages/data-tables.css') }}">
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
	
	#irc_confirmation_area,#resignation_area {
		pointer-events: none;
		background-color: #f4f8fb !important;
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
	.branchconfirmarea .input-field {
    	position: relative;
    	margin: 0 !important;
    }
     .inline-box{
    	height: 2rem !important;
    	margin-top: 10px !important;
    }
    .download-link{
    	line-height: 33px;
	    display: inline-block;
	    height: 33px;
	    padding: 0 1rem;
    }
</style>
@endsection
@section('main-content')
<div id="">
	<form class="formValidate" id="irc_formValidate" method="post" enctype="multipart/form-data"
		action="{{ route('irc.updateIrc',app()->getLocale()) }}">
		@csrf
<div class="row">
<div class="content-wrapper-before"></div>
	@php 
	if(!empty(Auth::user())){
		
		$userid = Auth::user()->id;
		$get_roles = Auth::user()->roles;
		$user_role = $get_roles[0]->slug;
			
		}
	@endphp
		<div class="container">

		<div class="card">
		
        @php 
			$dataresigneddata = $data['resignedmember']; 
        @endphp
		<h5 class="padding-left-10"> Resignation Member 
			<a class="btn waves-effect waves-light right" href="{{ route('irc.irc_list',app()->getLocale())  }}">{{__('IRC List') }}</a>
		</h5>
			<div class="row" id="resignation_area"> 
            <input type="hidden" name="ircid" id="ircid" value="{{$dataresigneddata->ircid}}">
				 <div class="input-field col s4">
					<label for="member_number"
						class="common-label force-active">{{__('Membership Number') }}*</label>
					<input id="member_number" name="resignedmembernoE" readonly value="{{$dataresigneddata->member_number}}"  class="common-input autocompleteoff"
						type="text" data-error=".errorTxt1">
					<input type="hidden" name="resignedmemberno" id="memberid" value="{{$dataresigneddata->resignedmemberno}}">
					<input type="hidden" name="union_branch_id" id="union_branch_id" value="{{$dataresigneddata->union_branch_id}}">
					<div class="errorTxt1"></div>
				</div>
				<div class="input-field col s4">
					<label for="member_type"
						class="common-label force-active">{{__('Member Type') }}*</label>
					<input id="member_type" name="member_type"  value="{{$dataresigneddata->designation_name}}" readonly class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="input-field col s4">
					<label for="member_title"
						class="common-label force-active">{{__('Member Title') }}*</label>
					<input id="member_title" name="member_title" value="{{$dataresigneddata->person_title}}"  readonly class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				 <div class="clearfix" style="clear:both"></div>
				 <div class="input-field col s4">
					<label for="member_name"
						class="common-label force-active">{{__('Member Name') }}*</label>
					<input id="member_name" name="resignedmembername" value="{{$dataresigneddata->resignedmembername}}" readonly class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="input-field col s4">
					<label for="bank_name"
						class="common-label force-active">{{__('Bank') }}*</label>
					<input id="bank_name" name="resignedmemberbankname" value="{{$dataresigneddata->resignedmemberbankname}}" readonly class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="input-field col s4">
					<label for="branch_name"
						class="common-label force-active">{{__('Bank Branch') }}*</label>
					<input id="branch_name" name="resignedmemberbranchname" value="{{$dataresigneddata->resignedmemberbranchname}}" readonly class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				 <div class="clearfix" style="clear:both"></div>
				 <div class="input-field col s4">
					<div class="row">
						<div class="input-field col s12 m8">
							<label for="dob" class="force-active">{{__('Date of Birth') }} *</label>
							<input id="dob" readonly name="dob" data-reflectage="dob" value="{{$dataresigneddata->dob}}"  class="datepicker"  type="text"> 
						</div>
						<div class="input-field col s12 m4">
							<label for="member_age" class="force-active">{{__('Age') }}</label>
							<input type="text" value="{{$dataresigneddata->age}}" readonly id="member_age" >
						</div>
					</div>
					<div class="errorTxt1"></div>
				</div>
				<div class="input-field col s4">
					<div class="col s12 m4">
					 <p>{{__('Gender') }}</p>
					</div>
					<div class="col s12 m4">
						<p>
							<label>
							<input class="validate" required="" disabled="disabled" aria-required="true" id="femalegender" name="gender" type="radio" value="Female" {{ $dataresigneddata->gender == 'Female' ? 'checked' : '' }} >
							<span>{{__('Female') }}</span>
							</label> 
						</p>						
					</div>
					<div class="col s12 m4">
						<p>
							<label>
							<input class="validate" disabled="disabled" required="" aria-required="true" id="malegender" name="gender" type="radio"  value="Male" {{ $dataresigneddata->gender == 'Male' ? 'checked' : '' }}>
							<span>{{__('Male') }}</span>
							</label>
						</p>
					</div>
				<div class="errorTxt1"></div>
				</div>
				<div class="input-field col s4">
					<label for="doj"
						class="common-label force-active">{{__('DOJ') }}*</label>
					<input id="doj" value="{{$dataresigneddata->doj}}" name="doj" readonly class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="clearfix" style="clear:both"></div>
				<div class="input-field col s4">
					<label for="race_name"
						class="common-label force-active">{{__('Race') }}*</label>
					<input id="race_name" readonly name="race_name" value="{{$dataresigneddata->race_name}}"  class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="input-field col s4">
					<label for="nric_n"
						class="common-label force-active">{{__('NRIC-N') }}*</label>
					<input id="nric_n" readonly name="resignedmembericno"  value="{{$dataresigneddata->resignedmembericno}}" class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="input-field col s4">
					<label for="remarks"
						class="common-label force-active">{{__('Remarks') }}*</label>
					<input id="remarks" name="remarks" readonly value="{{$dataresigneddata->remarks}}" class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
			</div>
		</div>
	</div>
	<div class=" col s12 ">
	  <div class="container"> 
	 
		 <div class="card @php if($user_role =='irc-branch-committee' || $user_role =='irc-branch-committee-officials') echo 'branch'; @endphp">
		 <h5 class="padding-left-10">IRC BRANCH COMMITEE OF BENEVOLENT FUND APPLICATION</h5>
			  <div class="row">
			  	@if($user_role !='irc-confirmation-officials')
				<div class="input-field col s6">
					<label for="irc_member_no"
						class="common-label force-active">{{__('Membership No') }}*</label>
                        @php $id = $dataresigneddata->ircmembershipno;
                           $value = CommonHelper::getmembercode_byid($id);  
                         @endphp
						<input id="irc_member_no"  value="{{$value}}" readonly="" name="ircmember" class="common-input"
						type="text" data-error=".errorTxt1">
						<input type="hidden" name="ircmembershipno" id="irc_member_code" value="{{$dataresigneddata->ircmembershipno}}">
					<div class="errorTxt1"></div>
				</div>
				@else
				<input type="hidden" name="ircmembershipno" id="irc_member_code" value="" >
				@endif
				<div class="input-field col s6">
					<label for="irc_name"
						class="common-label force-active">{{__('IRC Name in Full') }}*</label>
					<input id="irc_name"  readonly name="ircname" value="{{$dataresigneddata->ircname}}"  class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="clearfix" style="clear:both"></div>
				<div class="input-field col s6">
					<div class="col s12 m3">
					 <p>{{__('IRC Position') }}*</p>
					</div>
					<div class="col s12 m3">
						<p>
							<label>
							<input class="validate" required="true" readonly  aria-required="true" id="ircposition" name="ircposition" type="radio" value="Chairman" {{ $dataresigneddata->ircposition == 'Chairman' ? 'checked' : '' }}>
							<span>{{__('Chairman') }}</span>
							</label> 
						</p>						
					</div>
					<div class="col s12 m3">
						<p>
							<label>
							<input class="validate"  readonly required="" aria-required="true" id="ircposition" name="ircposition" type="radio" value="Secretary" {{ $dataresigneddata->ircposition == 'Secretary' ? 'checked' : '' }}>
							<span>{{__('Secretary') }}</span>
							</label>
						</p>
					</div>
					<div class="col s12 m3">
						<p>
							<label>
							<input class="validate" readonly required="" aria-required="true" id="ircposition" name="ircposition" type="radio"  value="Commitee-Member" {{ $dataresigneddata->ircposition == 'Commitee-Member' ? 'checked' : '' }}>
							<span>{{__('Commitee Member') }}</span>
							</label>
						</p>
					</div>

					<div class="col s12 m3">
						&nbsp;
					</div>
					<div class="col s12 m3">
						<p>
							<label>
							<input class="validate" readonly required="" aria-required="true" id="ircposition" name="ircposition" type="radio" {{ $dataresigneddata->ircposition == 'Officials' ? 'checked' : '' }} value="Officials">
							<span>{{__('Officials') }}</span>
							</label>
						</p>
					</div>
					
				</div>
				<div class="input-field col s6">
					<label for="irc_bank"
						class="common-label force-active">{{__('Bank') }}*</label>
					<input id="irc_bank" readonly value="{{$dataresigneddata->ircbank}}"  name="ircbank" class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="clearfix" style="clear:both"></div>
				<div class="input-field col s6">
					<label for="bank_address"
						class="common-label force-active">{{__('Bank Branch Address') }}</label>
					<input id="bank_address" readonly  name="ircbankaddress" value="{{ isset($dataresigneddata->ircbankaddress) ? $dataresigneddata->ircbankaddress : '' }}" class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="input-field col s6">
					<label for="irctelephoneno"
						class="common-label force-active">{{__('Office Number') }}</label>
					<input id="irctelephoneno" readonly name="irctelephoneno"  value="{{ isset($dataresigneddata->irctelephoneno) ? $dataresigneddata->irctelephoneno : '' }}" class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="clearfix" style="clear:both"></div>
				<div class="input-field col s6">
					<label for="ircmobileno"
						class="common-label force-active">{{__('Mobile') }}</label>
					<input id="ircmobileno" readonly name="ircmobileno" value="{{ isset($dataresigneddata->ircmobileno) ? $dataresigneddata->ircmobileno : '' }}" class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="input-field col s6">
					<label for="ircfaxno"
						class="common-label force-active">{{__('Fax') }}</label>
					<input id="ircfaxno" name="ircfaxno" value="{{ isset($dataresigneddata->ircfaxno) ? $dataresigneddata->ircfaxno : '' }}" class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
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
								@php
									$reasonlabel = '';
								@endphp
								<select name="resignedreason" id="reason" required="" onchange="return ChangeFields()" class="error browser-default selectpicker">
									<option value="">Select reason</option>
									@foreach($data['reason_view'] as $values)
										<option value="{{$values->id}}" @php if($values->id == $dataresigneddata->reasonid) { $reasonlabel = $values->reason_name; echo "selected";} @endphp >{{$values->reason_name}}</option>
									@endforeach
								</select>
							</div>
						</div>
											
					</div>
					@php
						$irc_details = $data['irc_details'];
						$section_type_val = '';
						if($reasonlabel=='RETIRED'){
							$section_type_val = 1;
						}else if($reasonlabel=='DECEASED'){
							$section_type_val = 2;
						}else if($reasonlabel=='PROMOTED'){
							$section_type_val = 3;
						}
						else if($reasonlabel=='RESIGN FROM BANK' || $reasonlabel=='RESIGN FROM UNION' || $reasonlabel=='TERMINATED BY BANK'){
							$section_type_val = 4;
						}else if($reasonlabel=='EXPELLED' || $reasonlabel=='STRUCK OFF' || $reasonlabel=='BLACK LIST' || $reasonlabel=='BLACKLISTED FROM UNION'){
							$section_type_val = 5;
						}else{
							$section_type_val = 4;
						}

						if($dataresigneddata->gender == 'Female'){
							$genderlable = 'She';
							$genderlableone = 'Her';
						}else{
							$genderlable = 'He';
							$genderlableone = 'His';
						}
					@endphp
					<div class="col s12 m12">
						<input type="text" class="hide" name="section_type" id="section_type" value="{{ $section_type_val }}">
						<div id="retired_section" class="reasonsections @if($section_type_val != 1) hide @endif "> 

							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" name="personnameboxone" id="personnameboxone"  value="1" @if($irc_details->nameofperson ==1) checked @endif />
						          		<span>1. BF Applicant’s Name is</span>
						            </label> 
									<div class="input-field inline">
										<input type="text" id="person_nameone" name="person_nameone" style="width: 500px;" class="inline-box" readonly value="{{$dataresigneddata->resignedmembername}}">
										
									</div>
						        </div>
						        <div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" name="retiredboxone" id="retiredboxone"  @if($irc_details->retiredbox ==1) checked @endif value="1"/>
						          		<span>2. <span class="gender">{{ $genderlable }}</span> <span style="text-decoration: underline;">RETIRED</span> w.e.f.</span>
						            </label> 
									<div class="input-field inline">
										<input type="text" 	name="gradewefone" id="gradewefone" placeholder="grade w.e.f"  class="datepicker-custom inline-box" value="{{$dataresigneddata->gradewef}}" />
										
									</div>
						        </div>
								
							</div>	


							<div class="row padding-left-20">
								<div class="col s12 m12 ">
									
										<label>
										<input type="checkbox" class="common-checkbox" name="messengerboxone" @if($irc_details->messengerbox ==1) checked @endif id="messengerboxone"  value="1"/>
										<span>3. <span class="gender">{{ $genderlable }}</span> was a 
										</label> 
										<div class="input-field inline">
											<select id="messengerone" name="messengerone" class="browser-default">
											    <option value="" disabled selected>Choose your option</option>
											    <option @if($irc_details->messengertype =='MESSENGER') selected @endif >MESSENGER</option>
											    <option @if($irc_details->messengertype =='CLERICAL') selected @endif >CLERICAL</option>
											    <option @if($irc_details->messengertype =='SPECIAL GRADE CLERK') selected @endif >SPECIAL GRADE CLERK</option>
											    <option @if($irc_details->messengertype =='OTHER') selected @endif >OTHER</option>
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
										<input type="checkbox" class="common-checkbox" name="attachedboxone" @if($irc_details->attachedbox ==1) checked @endif id="attachedboxone"  value="1"/>
						          		<span>4. Attached is <span class="genderone">{{ $genderlableone }}</span> RETIREMENT Letter (compulsory)</span>
						            </label> 
						            <div class="input-field inline">
										<div class="row">
											<div class="col s12 m8">
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
											<div class="col s12 m4">
												<br>
												@if($irc_details->attachment_file!="")
												<a href="{{ asset('storage/app/irc/'.$irc_details->attachment_file) }}" class="btn btn-sm download-link" style="" target="_blank">VIEW ATTACHMENT</a>
												@endif
											</div>
											<div class="col s12 m6 hide">
												<input type="text" id="attachedone" name="attachedone" class="inline-box" style="margin-left:30px; width: 500px;" value="{{$irc_details->attached_desc}}" >
											</div>
										</div>
									</div>
									
						        </div>
								
							</div>	
							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" @if($irc_details->jobtakenbox ==1) checked @endif name="jobtakenboxone" id="jobtakenboxone"  value="1"/>
						          		<span>5. Member’s job functions have been taken over by</span>
						            </label> 
									<div class="input-field inline">
										<input type="text" 	name="jobtakenbyone" id="jobtakenbyone" placeholder="" value="{{$irc_details->jobtakenby}}" class="inline-box" style="width: 500px;"/>
										
									</div>
									 and
						        </div>
							
							</div>
							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" @if($irc_details->posfilledbybox ==1) checked @endif name="posfilledbyboxone" id="posfilledbyboxone"  value="1"/>
						          		<span>6. Member’s position has not been filled up by another </span>
						            </label> 
						            <div class="input-field inline">
										<select id="posfilledbytypeone" name="posfilledbytypeone" onchange="return showAutocomplete(this.value,'posfillarea')" class="browser-default">
										    <option value="" disabled selected>Choose your option</option>
										    <option @if($irc_details->posfilledbytype == 1) selected @endif value="1">Member</option>
										    <option @if($irc_details->posfilledbytype == 2) selected @endif  value="2">Non-Member</option>
										    <option @if($irc_details->posfilledbytype == 3) selected @endif  value="3">Other</option>
										</select>
									</div>
									<div id="posfillarea" class="input-field inline @if($irc_details->posfilledbytype != 1) hide @endif">
										<input type="text"	name="posfilledbymemberone" id="posfilledbymemberone" placeholder="Member No/NRIC" class="inline-box posfilledbymemberone" value="{{ CommonHelper::getmembercode_byid($irc_details->posfilledmemberid) }}" style="width: 250px;"/>
										<input type="hidden" name="posfilledbymemberidone" value="{{$irc_details->posfilledmemberid}}" id="posfilledbymemberidone" class="posfilledbymemberidone">
									</div>
									<span>[Please specify others in detail]</span>
									
									<div class="input-field inline">
										<input type="text" 	name="posfilledbyone" id="posfilledbyone" value="{{$irc_details->posfilledby}}" placeholder="" class="inline-box posfilledbyone" style="width: 500px;"/>
										
									</div>
						        </div>
								
							</div>	
							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" @if($irc_details->replacestaffbox ==1) checked @endif  name="replacestaffboxone" id="replacestaffboxone"  value="1"/>
						          		<span>7. REPLACEMENT Staff Grade is </span>
						            </label>
						            <div class="input-field inline">
										<select id="replacestafftypeone" name="replacestafftypeone" class="browser-default">
										    <option value="" disabled selected>Choose your option</option>
										    <option @if($irc_details->replacestafftype == 'Non-Clerical') selected @endif >Non-Clerical</option>
										    <option  @if($irc_details->replacestafftype == 'Clerical') selected @endif>Clerical</option>
										    <option @if($irc_details->replacestafftype == 'Special Grade Clerical') selected @endif >Special Grade Clerical</option>
										    <option @if($irc_details->replacestafftype == 'Other') selected @endif >Other</option>
										</select>
									</div>
									[Please specify others in detail]
									<div class="input-field inline">
										<input type="text" 	name="replacestaffone" id="replacestaffone" value="{{$irc_details->replacestaff}}" placeholder="" class="inline-box" style="width: 250px;"/>
										
									</div> 
									
						        </div>
								
							</div>	
							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" @if($irc_details->appcontactbox ==1) checked @endif name="appcontactboxone" id="appcontactboxone" onclick="return MakeRequired('appcontactboxone',1)"  value="1"/>
						          		<span>8. Applicant Contact</span>
						            </label> 
									<div class="input-field inline">
										<input type="text" 	name="appcontactone" id="appcontactone" value="{{$irc_details->appcontact}}" placeholder="" class="inline-box allow_contactnumbers" style="width: 250px;"/>
										
									</div>
									 <span>Office</span>
									 <div class="input-field inline">
										<input type="text" 	name="appofficeone" id="appofficeone" value="{{$irc_details->appoffice}}" placeholder="" class="inline-box allow_contactnumbers" style="width: 250px;"/>
										
									</div>
						        </div>

								<div class="clearfix"></div>
								<div class="col s12">
									
									<span>
										&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; <span>H/P</span>
									</span>	
									
									<div class="input-field inline">
										<input type="text" 	name="apphpone" id="apphpone" placeholder="" value="{{$irc_details->appmobile}}" class="inline-box allow_contactnumbers" style="width: 250px;"/>
										
									</div>
									 <span>Fax</span>
									 <div class="input-field inline">
										<input type="text" 	name="appfaxone" id="appfaxone" placeholder="" value="{{$irc_details->appfax}}" class="inline-box allow_contactnumbers" style="width: 250px;"/>
										
									</div>
									<span>Email</span>
									 <div class="input-field inline">
										<input type="text"	name="appemailone" id="appemailone" placeholder="" value="{{$irc_details->appemail}}" class="inline-box " style="width: 250px;"/>
										
									</div>
						        </div>
								
							</div>	
						</div>
						<div id="deceased_section" class="reasonsections  @if($section_type_val != 2) hide @endif "> 

							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" name="memberdemisedboxtwo" id="memberdemisedboxtwo" @if($irc_details->demised_onboxtwo ==1) checked @endif value="1"/>
						          		<span>1. Member DEMISED on</span>
						            </label> 
									<div class="input-field inline">
										<input type="text" id="memberdemisedtwo" class="datepicker-custom inline-box" value="{{$irc_details->demised_ontwo}}" name="memberdemisedtwo" >
										
									</div>
						        </div>

							</div>	

							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" @if($irc_details->member_nameboxtwo ==1) checked @endif name="nameofpersonboxtwo" id="nameofpersonboxtwo"  value="1"/>
						          		<span>2. Name of Member’s next of kin is</span>
						            </label> 
									<div class="input-field inline">
										<input type="text" 	name="nameofpersontwo" id="nameofpersontwo" placeholder="" value="{{$irc_details->member_nametwo}}" class="inline-box" style="width: 500px;"/>
										
									</div>
						        </div>

						        <div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" @if($irc_details->relationshipboxtwo ==1) checked @endif name="relationshipboxtwo" id="relationshipboxtwo"  value="1"/>
						          		<span>3. Relationship is</span>
						            </label> 
									<div class="input-field inline">
										<input type="text" 	name="relationshiptwo" value="{{$irc_details->relationshiptwo}}" id="relationshiptwo" placeholder="" class="inline-box" style="width: 300px;"/>
										
									</div>
						        </div>
							
							</div>	


							<div class="row padding-left-20">
								<div class="col s12 m2 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" @if($irc_details->applicantboxtwo ==1) checked @endif name="applicantboxtwo" id="applicantboxtwo"  value="1"/>
										<span>4. Applicant</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m1 " style="margin-left: 20px;">
									<p>
										<label>
										<input type="radio" class="common-checkbox" @if($irc_details->applicanttwo ==1) checked @endif name="applicanttwo" id="applicanttwo"  value="1"/>
										<span>Has</span>
										</label> 
									</p>	
								</div>
								<div class="col s12 m5 ">
									<p>
										<label>
										<input type="radio" class="common-checkbox" name="applicanttwo" @if($irc_details->applicanttwo ==2) checked @endif id="applicanttwo"  value="2"/>
										<span>Does Not have Legal Authority (LA) to claim  </span>
										</label> 
									</p>	
								</div>
							
							</div>	

							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" name="jobtakenboxtwo" @if($irc_details->jobtakenbox ==1) checked @endif id="jobtakenboxtwo"  value="1"/>
						          		<span>5. Member’s job functions have been taken over by</span>
						            </label> 
									<div class="input-field inline">
										<input type="text" 	name="jobtakenbytwo" id="jobtakenbytwo" value="{{$irc_details->jobtakenby}}" placeholder="" class="inline-box" style="width: 400px;"/>
										
									</div>
									 and
						        </div>
							
							</div>
							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" @if($irc_details->posfilledbybox ==1) checked @endif name="posfilledbyboxtwo" id="posfilledbyboxtwo"  value="1"/>
						          		<span>6. Member’s position has not been filled up by another </span>
						            </label> 
						            <div class="input-field inline">
										<select id="posfilledbytypetwo" name="posfilledbytypetwo" onchange="return showAutocomplete(this.value,'posfillareatwo')" class="browser-default">
										    <option value="" disabled selected>Choose your option</option>
										    <option @if($irc_details->posfilledbytype == 1) selected @endif value="1">Member</option>
										    <option @if($irc_details->posfilledbytype == 2) selected @endif value="2">Non-Member</option>
										    <option @if($irc_details->posfilledbytype == 3) selected @endif value="3">Other</option>
										</select>
									</div>
									<div id="posfillareatwo" class="input-field inline @if($irc_details->posfilledbytype != 1) hide @endif">
										<input type="text"	name="posfilledbymembertwo" id="posfilledbymembertwo" placeholder="Member No/NRIC" class="inline-box posfilledbymemberone" value="{{ CommonHelper::getmembercode_byid($irc_details->posfilledmemberid) }}" style="width: 250px;"/>
										<input type="hidden" name="posfilledbymemberidtwo" id="posfilledbymemberidtwo" value="{{ $irc_details->posfilledmemberid }}" class="posfilledbymemberidone">
									</div>
									<span>[Please specify others in detail]</span>
									
									<div class="input-field inline">
										<input type="text" 	name="posfilledbytwo" value="{{$irc_details->posfilledby}}" id="posfilledbytwo" placeholder="" class="inline-box posfilledbyone" style="width: 500px;"/>
										
									</div>
						        </div>
								
							</div>	
							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" @if($irc_details->replacestaffbox ==1) checked @endif name="replacestaffboxtwo" id="replacestaffboxtwo"  value="1"/>
						          		<span>7. REPLACEMENT Staff Grade is </span>
						            </label> 
						            <div class="input-field inline">
										<select id="replacestafftypetwo" name="replacestafftypetwo" class="browser-default">
										    <option value="" disabled selected>Choose your option</option>
										    <option @if($irc_details->replacestafftype == 'Non-Clerical') selected @endif >Non-Clerical</option>
										    <option  @if($irc_details->replacestafftype == 'Clerical') selected @endif>Clerical</option>
										    <option @if($irc_details->replacestafftype == 'Special Grade Clerical') selected @endif >Special Grade Clerical</option>
										    <option @if($irc_details->replacestafftype == 'Other') selected @endif >Other</option>
										</select>
									</div>
									[Please specify others in detail]
									<div class="input-field inline">
										<input type="text" 	name="replacestafftwo" value="{{$irc_details->replacestaff}}" id="replacestafftwo" placeholder="" class="inline-box" style="width: 250px;"/>
										
									</div>
									
						        </div>
								
							</div>	

							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" name="appcontactboxtwo" @if($irc_details->appcontactbox ==1) checked @endif name="appcontactboxone" id="appcontactboxtwo" onclick="return MakeRequired('appcontactboxtwo',2)"  value="1"/>
						          		<span>8. Next of Kin’s Contact</span>
						            </label> 
									<div class="input-field inline">
										<input type="text" 	name="appcontacttwo" value="{{$irc_details->appcontact}}" id="appcontacttwo" placeholder="" class="inline-box allow_contactnumbers" style="width: 250px;"/>
										
									</div>
									 <span>Office</span>
									 <div class="input-field inline">
										<input type="text" 	name="appofficetwo" id="appofficetwo" placeholder="" value="{{$irc_details->appoffice}}" class="inline-box allow_contactnumbers" style="width: 250px;"/>
										
									</div>
						        </div>

								<div class="clearfix"></div>
								<div class="col s12">
									
									<span>
										&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; <span>Mobile</span>
									</span>	
									
									<div class="input-field inline">
										<input type="text" 	name="appmobiletwo" id="appmobiletwo" placeholder="" value="{{$irc_details->appmobile}}" class="inline-box allow_contactnumbers" style="width: 250px;"/>
										
									</div>
									 <span>Fax</span>
									 <div class="input-field inline">
										<input type="text" 	name="appfaxtwo" id="appfaxtwo" placeholder="" value="{{$irc_details->appfax}}" class="inline-box allow_contactnumbers" style="width: 250px;"/>
										
									</div>
									<span>Email</span>
									 <div class="input-field inline">
										<input type="text" name="appemailtwo" id="appemailtwo" placeholder="" value="{{$irc_details->appemail}}" class="inline-box " style="width: 250px;"/>
										
									</div>
						        </div>
								
							</div>	
						
							
						</div>
						<div id="promoted_section" class="reasonsections  @if($section_type_val != 3) hide @endif "> 

							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" @if($irc_details->nameofperson ==1) checked @endif name="nameofpersonboxthree" id="nameofpersonboxthree"  value="1"/>
						          		<span>1. BF Applicant’s Name:</span>
						            </label> 
									<div class="input-field inline">
										<input type="text" id="person_namethree" name="person_namethree" readonly value="{{$dataresigneddata->resignedmembername}}" class="inline-box" style="width: 560px;">
										
									</div>
						        </div>
								
							</div>	

							<div class="row padding-left-20">
								<div class="col s12 m12 ">
									
										<label>
										<input type="checkbox" class="common-checkbox" @if($irc_details->messengerbox ==1) checked @endif name="messengerboxthree" id="messengerboxthree"  value="1"/>
										<span>2. <span class="gender">{{ $genderlable }}</span> was a </span>
										</label> 
										<div class="input-field inline">
											<select id="messengerthree" name="messengerthree" class="browser-default">
											    <option value="" disabled selected>Choose your option</option>
											    <option @if($irc_details->messengertype =='MESSENGER') selected @endif >MESSENGER</option>
											    <option @if($irc_details->messengertype =='CLERICAL') selected @endif >CLERICAL</option>
											    <option @if($irc_details->messengertype =='SPECIAL GRADE CLERK') selected @endif >SPECIAL GRADE CLERK</option>
											    <option @if($irc_details->messengertype =='OTHER') selected @endif >OTHER</option>
											</select>
										</div>
										<span> before PROMOTION [Delete which is not applicable]</span>
									
								</div>
							
							</div>	
							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" name="promotedboxthree" @if($irc_details->promotedboxthree ==1) checked @endif id="promotedboxthree"  value="1"/>
						          		<span>3. <span class="gender">{{ $genderlable }}</span> was <span style="text-decoration: underline;">PROMOTED</span> to</span>
						            </label> 
									<div class="input-field inline">
										<input type="text" id="promotedthree" name="promotedthree" value="{{$irc_details->promotedto}}" @if($section_type_val == 3) required @endif class="inline-box" style="width: 300px;" >
										
									</div>
									grade w.e.f.
									<div class="input-field inline">
										<input type="text" 	name="gradewefthree" value="{{$dataresigneddata->gradewef}}" id="gradewefthree" placeholder="grade w.e.f"  class="datepicker-custom inline-box"/>
										
									</div>
						        </div>
								
							</div>	

						
							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" @if($irc_details->attachedbox ==1) checked @endif name="attachedboxthree" id="attachedboxthree"  value="1"/>
						          		<span>4. Attached is <span class="genderone">{{ $genderlableone }}</span> Job Description (compulsory)</span>
						            </label> 
						            <div class="input-field inline">
										<div class="row">
											<div class="col s12 m8">
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
											<div class="col s12 m4">
												<br>
												@if($irc_details->attachment_file!="")
												<a href="{{ asset('storage/app/irc/'.$irc_details->attachment_file) }}" class="btn btn-sm download-link" target="_blank">VIEW ATTACHMENT</a>
												@endif
											</div>
											<div class="col s12 m6 hide">
												<input type="text" name="attachedthree" value="{{$irc_details->attached_desc}}" id="attachedthree" class="inline-box" style="margin-left:30px; width: 500px;">
											</div>
										</div>
										
										
										
									</div>
									
						        </div>
						        <div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" @if($irc_details->transfertoplaceboxthree ==1) checked @endif name="transfertoplaceboxthree" id="transfertoplaceboxthree" value="1"/>
						          		<span>5. <span class="gender">{{ $genderlable }}</span> Promoted and transfer to new place</span>
						            </label> 
									<div class="input-field inline">
										<input type="text" name="transfertoplacethree" value="{{$irc_details->transfertoplacethree}}" id="transfertoplacethree" class="inline-box" style="width: 500px;" >
										
									</div>
						        </div>
								
							</div>	
							
							<div class="row padding-left-20">
								<div class="col s12 m12 ">
									
									<label>
									<input type="checkbox" class="common-checkbox" @if($irc_details->samebranchbox ==1) checked @endif name="samebranchboxthree" id="samebranchboxthree"  value="1"/>
									<span>6. Member is still in the same </span>
									</label> 
									<div class="input-field inline">
										<select id="samebranchtype" name="samebranchtype" onchange="return ChangePromoted(this.value)" class="browser-default">
										    <option value="" disabled selected>Choose your option</option>
										    <option @if($irc_details->samebranchtype =='Branch') selected @endif >Branch</option>
										    <option @if($irc_details->samebranchtype =='Department') selected @endif >Department</option>
										    <option @if($irc_details->samebranchtype =='Others') selected @endif >Others</option>
										</select>
									</div>
									<div id="samebranchothersdiv" class="input-field inline @if($irc_details->samebranchtype !='Others') hide @endif" style="">
										<input type="text" name="samebranchothers" id="samebranchothers" value="{{ $irc_details->samebranchothers }}" class="inline-box" style="width: 500px;margin-top: 20px !important;" >
									</div>
									performing the same job functions
										
								</div>
							
							</div>	
							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" name="jobtakenboxthree" @if($irc_details->jobtakenbox ==1) checked @endif id="jobtakenboxthree"  value="1"/>
						          		<span>7. Member’s job functions have been taken over by</span>
						            </label> 
									<div class="input-field inline">
										<input type="text" 	name="jobtakenbythree" value="{{$irc_details->jobtakenby}}" id="jobtakenbythree" placeholder="" class="inline-box" style="width: 500px;"/>
										
									</div>
									 and
						        </div>
							
							</div>
							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" @if($irc_details->posfilledbybox ==1) checked @endif name="posfilledbyboxthree" id="posfilledbyboxthree"  value="1"/>
						          		<span>8. Member’s position has not been filled up by another </span>
						            </label> 
						            <div class="input-field inline">
										<select id="posfilledbytypethree" name="posfilledbytypethree" onchange="return showAutocomplete(this.value,'posfillareathree')" class="browser-default">
										    <option value="" disabled selected>Choose your option</option>
										    <option @if($irc_details->posfilledbytype == 1) selected @endif  value="1">Member</option>
										    <option  @if($irc_details->posfilledbytype == 2) selected @endif value="2">Non-Member</option>
										    <option @if($irc_details->posfilledbytype == 3) selected @endif  value="3">Other</option>
										</select>
									</div>
									<div id="posfillareathree" class="input-field inline @if($irc_details->posfilledbytype != 1) hide @endif">
										<input type="text"	name="posfilledbymemberthree" id="posfilledbymemberthree" value="{{ CommonHelper::getmembercode_byid($irc_details->posfilledmemberid) }}" placeholder="Member No/NRIC" class="inline-box posfilledbymemberone" style="width: 250px;"/>
										<input type="hidden" name="posfilledbymemberidthree" id="posfilledbymemberidthree" value="{{ $irc_details->posfilledmemberid }}" class="posfilledbymemberidone">
									</div>
									<span>[Please specify others in detail]</span>
									
									
									<div class="input-field inline">
										<input type="text" 	name="posfilledbythree" value="{{$irc_details->posfilledby}}" id="posfilledbythree" placeholder="" class="inline-box posfilledbyone" style="width: 500px;"/>
										
									</div>
						        </div>
								
							</div>	
							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" @if($irc_details->replacestaffbox ==1) checked @endif name="replacestaffboxthree" id="replacestaffboxthree"  value="1"/>
						          		<span>9. REPLACEMENT Staff Grade is </span>
						            </label> 
						            <div class="input-field inline">
										<select id="replacestafftypethree" name="replacestafftypethree" class="browser-default">
										    <option value="" disabled selected>Choose your option</option>
										    <option @if($irc_details->replacestafftype == 'Non-Clerical') selected @endif >Non-Clerical</option>
										    <option  @if($irc_details->replacestafftype == 'Clerical') selected @endif>Clerical</option>
										    <option @if($irc_details->replacestafftype == 'Special Grade Clerical') selected @endif >Special Grade Clerical</option>
										    <option @if($irc_details->replacestafftype == 'Other') selected @endif >Other</option>
										</select>
									</div>
									[Please specify others in detail]
									
									<div class="input-field inline">
										<input type="text" 	name="replacestaffthree" id="replacestaffthree" value="{{$irc_details->replacestaff}}" placeholder="" class="inline-box" style="width: 250px;"/>
										
									</div>
						        </div>
								
							</div>	
							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" @if($irc_details->appcontactbox ==1) checked @endif name="appcontactboxthree" id="appcontactboxthree" onclick="return MakeRequired('appcontactboxthree',3)"  value="1"/>
						          		<span>10. Applicant Contact</span>
						            </label> 
									<div class="input-field inline">
										<input type="text" 	name="appcontactthree" value="{{$irc_details->appcontact}}" id="appcontactthree" placeholder="" class="inline-box allow_contactnumbers" style="width: 250px;"/>
										
									</div>
									 <span>Office</span>
									 <div class="input-field inline">
										<input type="text" 	name="appofficethree" id="appofficethree" value="{{$irc_details->appoffice}}" placeholder="" class="inline-box allow_contactnumbers" style="width: 250px;"/>
										
									</div>
						        </div>

								<div class="clearfix"></div>
								<div class="col s12">
									
									<span>
										&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; <span>H/P</span>
									</span>	
									
									<div class="input-field inline">
										<input type="text" 	name="apphpthree" id="apphpthree" value="{{$irc_details->appmobile}}" placeholder="" class="inline-box allow_contactnumbers" style="width: 250px;"/>
										
									</div>
									 <span>Fax</span>
									 <div class="input-field inline">
										<input type="text" 	name="appfaxthree" id="appfaxthree" value="{{$irc_details->appfax}}" placeholder=""  class="inline-box allow_contactnumbers" style="width: 250px;"/>
										
									</div>
									<span>Email</span>
									 <div class="input-field inline">
										<input type="text" name="appemailthree" id="appemailthree" value="{{$irc_details->appemail}}" placeholder=""  class="inline-box"/>
										
									</div>
						        </div>
								
							</div>

								
						</div>
						<div id="resign_section" class="reasonsections  @if($section_type_val != 4) hide @endif "> 

							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" @if($irc_details->nameofperson ==1) checked @endif name="personnameboxfour" id="personnameboxfour"  value="1"/>
						          		<span>1. BF Applicant’s Name is</span>
						            </label> 
									<div class="input-field inline">
										<input type="text" id="person_namefour" name="person_namefour" readonly value="{{$dataresigneddata->resignedmembername}}" class="inline-box" style="width: 560px;">
										
									</div>
						        </div>
						        <div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" @if($irc_details->resignedonboxfour ==1) checked @endif name="resignedonboxfour" id="resignedonboxfour"  value="1"/>
						          		<span>2. <span class="gender">{{ $genderlable }}</span> </span>
						            </label> 
						            <div class="input-field inline">
										<select id="resigntypefour" name="resigntypefour" class="browser-default">
										    <option value="" disabled selected>Choose your option</option>
										    <option @if($irc_details->resigntypefour =='RESIGNED') selected @endif >RESIGNED</option>
										    <option @if($irc_details->resigntypefour =='TERMINATED') selected @endif >TERMINATED</option>
										</select>
									</div>	
									on
									<div class="input-field inline">
										<input type="text" 	name="gradeweffour" id="gradeweffour" placeholder="grade w.e.f" value="{{$dataresigneddata->gradewef}}" class="datepicker-custom inline-box"/>
										
									</div>
						        </div>
								
							</div>	
							

							<div class="row padding-left-20">
								<div class="col s12 m12 ">
									
										<label>
										<input type="checkbox" class="common-checkbox" @if($irc_details->messengerbox ==1) checked @endif name="messengerboxfour" id="messengerboxfour"  value="1"/>
										<span>3. <span class="gender">{{ $genderlable }}</span> was a </span>
										</label> 
										<div class="input-field inline">
											<select id="messengerfour" name="messengerfour" class="browser-default">
											    <option value="" disabled selected>Choose your option</option>
											    <option @if($irc_details->messengertype =='MESSENGER') selected @endif >MESSENGER</option>
											    <option @if($irc_details->messengertype =='CLERICAL') selected @endif >CLERICAL</option>
											    <option @if($irc_details->messengertype =='SPECIAL GRADE CLERK') selected @endif >SPECIAL GRADE CLERK</option>
											    <option @if($irc_details->messengertype =='OTHER') selected @endif >OTHER</option>
											</select>
										</div>
										<span> before RESIGNATION [Delete which is not applicable]</span>
										
								</div>
							
							</div>	
								

						
							<div class="row padding-left-20">
								<div class="col s12 m12 ">
									
									<label>
									<input type="checkbox" class="common-checkbox" @if($irc_details->attachedbox ==1) checked @endif name="attachedboxfour" id="attachedboxfour"  value="1"/>
									<span>4. Attached is <span class="genderone">{{ $genderlableone }}</span> </span>
									</label> 
									
									<div class="input-field inline">
										<select id="attachfourtype" name="attachfourtype" class="browser-default">
										    <option value="" disabled selected>Choose your option</option>
										    <option @if($irc_details->attachfourtype == 'RESIGNATION') selected @endif>RESIGNATION</option>
										    <option @if($irc_details->attachfourtype == 'TERMINATION') selected @endif>TERMINATION</option>
										    <option @if($irc_details->attachfourtype == 'EXPULSION') selected @endif>EXPULSION</option>
										    <option @if($irc_details->attachfourtype == 'STRUCK OFF') selected @endif>STRUCK OFF</option>
										</select>
									</div>
									Letter (compulsory)
										
									<div class="input-field inline">
										<div class="row">
											<div class="col s12 m8">
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
											<div class="col s12 m4">
												<br>
												@if($irc_details->attachment_file!="")
												<a href="{{ asset('storage/app/irc/'.$irc_details->attachment_file) }}" class="btn btn-sm download-link" target="_blank">VIEW ATTACHMENT</a>
												@endif
											</div>
											<div class="col s12 m6 hide">
												 <input type="text" id="attachedfour" value="{{$irc_details->attached_desc}}" name="attachedfour" class="inline-box" style="margin-left:30px; width: 330px;">
											</div>
										</div>
									</div>
									
								</div>
								
							</div>	
								
							<div class="row padding-left-20">
								<div class="col s12 m12 ">
									
									<label>
									<input type="checkbox" class="common-checkbox" @if($irc_details->jobtakenbox ==1) checked @endif name="jobtakenboxfour" id="jobtakenboxfour"  value="1"/>
									<span>5. Member’s job functions have been taken over by</span>
									</label> 
									
									<div class="input-field inline">
										 <input type="text" name="jobtakenbyfour" value="{{$irc_details->jobtakenby}}" id="jobtakenbyfour" placeholder="" class="inline-box" style="width: 400px;"/>
									</div>
									and
								</div>
								
							</div>
							<div class="row padding-left-20">
								<div class="col s12 m12 ">
								
									<label>
									<input type="checkbox" class="common-checkbox" @if($irc_details->posfilledbybox ==1) checked @endif name="posfilledbyboxfour" id="posfilledbyboxfour"  value="1"/>
									<span>6. Member’s position has not been filled up by another </span>
									</label> 
									<div class="input-field inline">
										<select id="posfilledbytypefour" name="posfilledbytypefour" onchange="return showAutocomplete(this.value,'posfillareafour')" class="browser-default">
										    <option value="" disabled selected>Choose your option</option>
										    <option @if($irc_details->posfilledbytype == 1) selected @endif  value="1">Member</option>
										    <option @if($irc_details->posfilledbytype == 2) selected @endif  value="2">Non-Member</option>
										    <option @if($irc_details->posfilledbytype == 3) selected @endif  value="3">Other</option>
										</select>
									</div>
									<div id="posfillareafour" class="input-field inline @if($irc_details->posfilledbytype != 1) hide @endif">
										<input type="text"	name="posfilledbymemberfour" id="posfilledbymemberfour" value="{{ CommonHelper::getmembercode_byid($irc_details->posfilledmemberid) }}" placeholder="Member No/NRIC" class="inline-box posfilledbymemberone" style="width: 250px;"/>
										<input type="hidden" name="posfilledbymemberidfour" id="posfilledbymemberidfour" value="{{ $irc_details->posfilledmemberid }}" class="posfilledbymemberidone">
									</div>
									<span>[Please specify others in detail]</span>
									

									<div class="input-field inline">
										<input type="text" 	name="posfilledbyfour" id="posfilledbyfour" value="{{$irc_details->posfilledby}}" placeholder="" class="inline-box posfilledbyone" style="width: 500px;"/>
									</div>	
								</div>
								
							</div>	
							<div class="row padding-left-20">
								<div class="col s12 m12 ">
									
									<label>
									<input type="checkbox" class="common-checkbox" @if($irc_details->replacestaffbox ==1) checked @endif name="replacestaffboxfour" id="replacestaffboxfour"  value="1"/>
									<span>7. REPLACEMENT Staff Grade is  </span>
									</label> 
									<div class="input-field inline">
										<select id="replacestafftypefour" name="replacestafftypefour" class="browser-default">
										    <option value="" disabled selected>Choose your option</option>
										    <option @if($irc_details->replacestafftype == 'Non-Clerical') selected @endif >Non-Clerical</option>
										    <option  @if($irc_details->replacestafftype == 'Clerical') selected @endif>Clerical</option>
										    <option @if($irc_details->replacestafftype == 'Special Grade Clerical') selected @endif >Special Grade Clerical</option>
										    <option @if($irc_details->replacestafftype == 'Other') selected @endif >Other</option>
										</select>
									</div>
									[Please specify others in detail]
										
									
									<div class="input-field inline">						
										<input type="text" 	name="replacestafffour" value="{{$irc_details->replacestaff}}" id="replacestafffour" placeholder="" class="inline-box" style="width: 250px;"/>
									</div>
								</div>
							</div>	
							<div class="row padding-left-20">
								<div class="col s12">
									<label>
										<input type="checkbox" class="common-checkbox" @if($irc_details->appcontactbox ==1) checked @endif name="appcontactboxfour" id="appcontactboxfour" onclick="return MakeRequired('appcontactboxfour',4)"  value="1"/>
						          		<span>8. Applicant Contact</span>
						            </label> 
									<div class="input-field inline">
										<input type="text" 	name="appcontactfour" value="{{$irc_details->appcontact}}" id="appcontactfour" placeholder="" class="inline-box allow_contactnumbers" style="width: 250px;"/>
										
									</div>
									 <span>Office</span>
									 <div class="input-field inline">
										<input type="text" 	name="appofficefour" value="{{$irc_details->appoffice}}" id="appofficefour" placeholder="" class="inline-box allow_contactnumbers" style="width: 250px;"/>
										
									</div>
						        </div>

								<div class="clearfix"></div>
								<div class="col s12">
									
									<span>
										&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; <span>H/P</span>
									</span>	
									
									<div class="input-field inline">
										<input type="text" 	name="apphpfour" id="apphpfour" value="{{$irc_details->appmobile}}" placeholder="" class="inline-box allow_contactnumbers" style="width: 250px;"/>
										
									</div>
									 <span>Fax</span>
									 <div class="input-field inline">
										<input type="text" 	name="appfaxfour" id="appfaxfour" value="{{$irc_details->appfax}}" placeholder="" class="inline-box allow_contactnumbers" style="width: 250px;"/>
										
									</div>
									<span>Email</span>
									 <div class="input-field inline">
										<input type="text" name="appemailfour" id="appemailfour" value="{{$irc_details->appemail}}" placeholder=""  class="inline-box "/>
										
									</div>
						        </div>
								
							</div>	
						</div>
						<div id="expelled_section" class="reasonsections  @if($section_type_val != 5) hide @endif "> 

							<div class="row padding-left-20">
								<div class="col s12 m12">
								
									<label>
									<input type="checkbox" class="common-checkbox" @if($irc_details->expelledboxfive ==1) checked @endif name="expelledboxfive" id="expelledboxfive"  value="1"/>
									<span>1. Member was 
									</label> 

									<div class="input-field inline">
										<select id="expelledtypefive" name="expelledtypefive" class="browser-default">
										    <option value="" disabled selected>Choose your option</option>
										    <option @if($irc_details->expelledtypefive == 'EXPELLED') selected @endif >EXPELLED</option>
										    <option @if($irc_details->expelledtypefive == 'STRUCK OFF') selected @endif >STRUCK OFF</option>
										    <option @if($irc_details->expelledtypefive == 'BLACKLISTED') selected @endif >BLACKLISTED</option>
										</select>
									</div>
									 on

									<div class="input-field inline">						
										<input type="text" 	name="gradeweffive" id="gradewef" value="{{$dataresigneddata->gradewef}}" placeholder="grade w.e.f"  class="datepicker-custom inline-box"/>
									</div>
								</div>
							</div>

								
							<div class="row padding-left-20">
								<div class="col s12 m12 ">
									<p>
										<label>
										<input type="checkbox" class="common-checkbox" @if($irc_details->samejobboxfive ==1) checked @endif name="samejobboxfive" id="samejobboxfive"  value="1"/>
										<span>2. Member’s is still performing the same job functions.</span>
										</label> 
									</p>	

								</div>
								
							</div>
							<div class="row padding-left-20">
								<div class="col s12 m12 ">
								
									<label>
									<input type="checkbox" class="common-checkbox" @if($irc_details->samebranchbox ==1) checked @endif name="samebranchboxfive" id="samebranchboxfive"  value="1"/>
									<span>3. Member is still in the same </span>
									</label> 

									<div class="input-field inline">
										<select id="samebranchtypefive" name="samebranchtypefive" class="browser-default">
										    <option value="" disabled selected>Choose your option</option>
										    <option @if($irc_details->samebranchtype == 'Branch') selected @endif >Branch</option>
										    <option @if($irc_details->samebranchtype == 'Department') selected @endif >Department</option>
										</select>
									</div>
									
								</div>
								
							</div>	
							<div class="row padding-left-20">
								<div class="col s12 m12 ">
									
									<label>
									<input type="checkbox" class="common-checkbox" @if($irc_details->memberstoppedboxfive ==1) checked @endif name="memberstoppedboxfive" id="memberstoppedboxfive"  value="1"/>
									<span>4. Member </span>
									</label> 
									<div class="input-field inline">
										<select id="stoppedtypefive" name="stoppedtypefive" class="browser-default">
										    <option value="" disabled selected>Choose your option</option>
										    <option @if($irc_details->stoppedtypefive == 'HAS STOPPED') selected @endif >HAS STOPPED</option>
										    <option @if($irc_details->stoppedtypefive == 'HAS NOT STOPPED') selected @endif >HAS NOT STOPPED</option>
										</select>
									</div>	
									 the Check-Off [Delete whichever is applicable]
									 <br>
									 <br>
									
								</div>
								
							</div>	
							
						</div>
					</div>


					<!-- <div class="col s12 m12">
						<div class="row padding-left-20">
							<div class="col s12 m4 ">
								<p>
									<label>
									<input type="hidden" name="nameofperson" value="0">
									<input type="checkbox"  class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"  @isset($dataresigneddata) {{ $dataresigneddata->nameofperson == '1' ? 'checked' : '' }} @endisset >
                                                                
									<span>Name of the Person appliying for BF is</span>
									</label> 
								</p>	
							</div>
							<div class="col s12 m3 ">								
									<input type="text" id="person_name" readonly value="{{$dataresigneddata->resignedmembername}}">
							</div>
						</div>						
					</div> -->
					<!-- <div class="col s12 m12">
						<div class="row padding-left-20">
							<div class="col s12 m3 ">
								<p>
									<label>
										<input type="hidden" name="waspromoted" value="0">
										<input type="checkbox"  name="waspromoted" id="waspromoted" class="common-checkbox"  value="1" {{ $dataresigneddata->waspromoted == '1' ? 'checked' : '' }}/>
										<span>She/He was</span>
									</label> 
								</p>	
							</div>
							<div class="col s12 m3">
									<select name="resignedreason"  id="reason" class="error browser-default selectpicker">
									<option value="">Select reason</option>
										@foreach($data['reason_view'] as $values)
                                        <option value="{{$values->id}}" @php if($values->id == $dataresigneddata->reasonid) { echo "selected";} @endphp>{{$values->reason_name}}</option>
										@endforeach
									</select> 
							</div>
							<div class="col s12 m3">
							
								<input type="text" id="type" readonly name="type" placeholder="" value="{{$dataresigneddata->designation_name}}">	
								
							</div>
							<div class="col s12 m3">
								<input type="text" 	name="gradewef" id="gradewef" value="{{$dataresigneddata->gradewef}}" placeholder="grade w.e.f" name="text" class="datepicker"/>
							</div>
						</div>						
					</div>
					<div class="col s12 m12">
						<p class=" padding-left-24">
							<label>
								<input type="hidden" name="beforepromotion" value="0">
								<input type="checkbox" name="beforepromotion"  id="beforepromotion" class="common-checkbox"  value="1" {{ $dataresigneddata->beforepromotion == '1' ? 'checked' : '' }}/>
								<span>I hearby confirm that She/He got She/He is no longer doing any clerical job function. </span>
							</label> 
						</p>		
					</div> -->
					<!-- <div class="col s12 m12 padding-left-20">
						<p class=" padding-left-24">
							<label>
							<input type="hidden" name="attached" value="0">
							<input type="checkbox" name="attached" id="attached" class="common-checkbox"   value="1"  {{ $dataresigneddata->attached == '1' ? 'checked' : '' }} />
							<span>Attached Job function/Description (compulsory). </span>
							</label> 
						</p>		
					</div>
					<div class="col s12 m12">
						<p class=" padding-left-24">
							<label>
							<input type="hidden" name="herebyconfirm" value="0">
							<input type="checkbox" name="herebyconfirm" id="herebyconfirm" class="common-checkbox" value="1" {{ $dataresigneddata->herebyconfirm == '1' ? 'checked' : '' }}/>
							<span>I hereby confirm that he/she got promoted he/she no longer doing any clerical job function. </span>
							</label> 
						</p>		
					</div>
					<div class="col s12 m12">
						<div class="row padding-left-20">
							<div class="col s12 m4 ">
								<p>
									<label>
									<input type="hidden" name="filledby" value="0">
									<input type="checkbox" name="filledby" id="filledby"  class="common-checkbox"  value="1"  {{ $dataresigneddata->filledby == '1' ? 'checked' : '' }}/>
									<span>The messenger clerical position has been filled by</span>
									</label> 
								</p>	
							</div>
							<div class="col s12 m3 ">				
									<input type="text" name="nameforfilledby" id="nameforfilledby" value="{{$dataresigneddata->nameforfilledby}}">
							</div>
						</div>						
					</div>
					<div class="padding-left-20" style="pointer-events: auto;background-color: unset;">
						<div class="input-field col s12" style="background-color: #fff !important;">
							<textarea id="comments" name="comments" class="materialize-textarea">{{$dataresigneddata->comments}}</textarea>
							<label for="comments">Comments</label>
						</div>
					</div> -->
					</div>
			  </div>
			  <div class="card branchconfirmarea @php if($user_role =='irc-confirmation' || $user_role =='irc-confirmation-officials') echo 'branch'; @endphp">
			  <h5 class="padding-left-10">IRC SECRETARY VERIFICATION</h5>

				@php
					$userid = Auth::user()->id;
					$get_roles = Auth::user()->roles;
					$user_role = $get_roles[0]->slug;
					$branchcommitteeName = $dataresigneddata->branchcommitteeName;
					$branchcommitteeZone = $dataresigneddata->branchcommitteeZone;

					$committieverifyname = $irc_details->committieverifyname;
					$committiename = $irc_details->committiename;
					if($user_role=='irc-branch-committee' && $branchcommitteeName==''){
						$commitiiedata = CommonHelper::getCommittieinfo($userid);  
						//dd($commitiiedata);
						$branchcommitteeName = $commitiiedata->name;
					}
					if($user_role=='irc-branch-committee' && $branchcommitteeZone==''){
						$commitiiedata = CommonHelper::getCommittieinfo($userid);  
						$committieverifyname = $commitiiedata->union_branch;
						
						//$committieverifyname = $branchcommitteeZone;

						/* if($branchcommitteeZone==1){
							$committieverifyname = 'SMJ';
						}else if($branchcommitteeZone==2){
							$committieverifyname = 'PKP';
						}else if($branchcommitteeZone==3){
							$committieverifyname = 'PERAK';
						}else if($branchcommitteeZone==4){
							$committieverifyname = 'KELANTAN TERENGGANU';
						}else{
							$committieverifyname = 'KLSP';
						} */
						//dd($branchcommitteeZone);

					}

					if($user_role=='irc-branch-committee' && $committiename==''){
						$commitiiedata = CommonHelper::getCommittieinfo($userid);  
						$committiename = $commitiiedata->name;
					}

				@endphp
			   <div class="row padding-left-20">
					<div class="col s12 m12" style="line-height: 5px;">
						<label>
						
							<input type="checkbox"  @if($irc_details->committieverificationboxone ==1) checked @endif name="committieverificationboxone" id="committieverificationboxone" class="common-checkbox"  value="1"/>
							<span>I</span>
						</label> 
						<div class="input-field inline">	
							<input type="text" id="committiename" name="committiename" value="{{$committiename}}" placeholder="" class="inline-box" style="width: 280px;" >	
						</div>
						Secretary of NUBE
						<div class="input-field inline">	
							<input type="text" id="committieverifyname" name="committieverifyname" placeholder="" value="{{$committieverifyname}}" class="inline-box" style="width: 280px;">	
						</div>
						Branch have verified the above and <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; confirm that the declaration 
						by the IRC is correct.
					</div>
					<div class="col s12 m12 " style="margin-top: 10px;">
						<p>
							<label>
							<input type="checkbox" class="common-checkbox" @if($irc_details->committieverificationboxtwo ==1) checked @endif name="committieverificationboxtwo" id="committieverificationboxtwo"  value="1"/>
							<span>Staff who has taken over the job functions under CODE 0{{$section_type_val}} is a NUBE Member. </span>
							</label> 
						</p>	
					</div>
					<div class="col s12 m12 ">
							
							<label>
								<input type="checkbox" class="common-checkbox" @if($irc_details->committieverificationboxthree ==1) checked @endif name="committieverificationboxthree" id="committieverificationboxthree"  value="1" />
								<span>Staff who is under 
								<div class="input-field inline">
									<select id="committiecode" name="committiecode" class="browser-default">
									    <option value="" disabled selected>Choose code</option>
									    <option @if($irc_details->committiecode == '3') selected @endif value="3">CODE 03</option>
									    <option @if($irc_details->committiecode == '5') selected @endif value="5">CODE 05</option>
									</select>
								</div>
								 is still performing the same job function.  The additional information for this staff is as follows:  </span>
								
							</label> 
							<br>
							<div class="input-field inline" style="margin: 0 0 0 27px !important;">	
								<input type="text" name="committieremark" id="committieremark" value="{{$irc_details->committieremark}}" style="width: 650px;">
							</div>
							<span>(Remark)</span>
							
						
					</div>
					<div class="col s12 m12" style="margin-left: 25px;">
						@if($user_role == 'irc-branch-committee-officials')
							<span>Official</span>
							<div class="input-field inline">
								<input type="text" 	name="committie_official_name" id="committie_official_name" value="{{$irc_details->committie_official_name}}" placeholder="" class="inline-box " style="width: 500px;"/>
							</div>
						@else
							<input type="text" 	name="committie_official_name" id="committie_official_name" value="{{$irc_details->committie_official_name}}" placeholder="" class="inline-box hide" style="width: 500px;"/>
						@endif
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
									<input type="text" name="branchcommitteeName" id="branchcommitteeName" placeholder="Name" value="{{$branchcommitteeName}}">
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
									<input type="text"  name="branchcommitteeZone" id="branchcommitteeZone" value="{{$dataresigneddata->branchcommitteeZone}}">
							</div>
							<div class="col s12 m3 ">
							<!--<label>Date</label> -->
								<div class="input-field inline"> 
									<input type="text" class="datepicker-custom" name="branchcommitteedate" id="branchcommitteedate" value="@isset($dataresigneddata->branchcommitteedate){{$dataresigneddata->branchcommitteedate}}@endisset" placeholder="DD/MM/YYYY" name="date">
								</div>(Date)
							</div>
						</div>	
					</div>
				</div>
				<!-- <div class="row">
					<div class="col s12 m12">
					
						<p class="padding-left-24">
							<label>
							<input type="hidden" name="branchcommitteeverification1" value="0">
							<input type="checkbox" name="branchcommitteeverification1" class="common-checkbox"  value="1"  {{ $dataresigneddata->branchcommitteeverification1 == '1' ? 'checked' : '' }}/>
							<span>I have verified the above and confirm that the declaration by the IRC is correct.The messenger/clerical And; </span>
							</label> 
						</p>	
					
					</div>
					<div class="col s12 m12">
					
						<p class="padding-left-24">
							<label>
							<input type="hidden" name="branchcommitteeverification2" value="0">
							<input type="checkbox" id="" name="branchcommitteeverification2" class="common-checkbox"  value="1" {{ $dataresigneddata->branchcommitteeverification2 == '1' ? 'checked' : '' }}/>
							<span>I have promoted member is no longer doing Messenger/Clerical job functions. </span>
							</label> 
						</p>
					</div>						
					
					<div class="col s12 m12">
						<div class="row">
							<div class="col s12 m4 ">
								<p>
									<label>
									
									<span>Branch Commitee [Name in full]</span>
									</label> 
								</p>	
							</div>
							<div class="col s12 m3 ">
									<input type="text" name="branchcommitteeName" placeholder="Name" value="{{$dataresigneddata->branchcommitteeName}}">
							</div>
						</div>	
					</div>
					<div class="col s12 m12">
						<div class="row">
							<div class="col s12 m4 ">
								<p>
									<label>
									<span>Zone</span>
									</label> 
								</p>	
							</div>
							<div class="col s12 m3 ">
									<input type="text" name="branchcommitteeZone" value="{{$dataresigneddata->branchcommitteeZone}}" Placeholder="Zone">
							</div>
							<div class="col s12 m3 ">
									<input type="text" class="datepicker" Placeholder="Date" value="@isset($dataresigneddata->branchcommitteedate){{$dataresigneddata->branchcommitteedate}}@endisset" name="branchcommitteedate">
							</div>
						</div>	
					</div>
				</div> -->
			  </div>
			  <div class="col s12 m12">
						<div class="row">
							
							<div class="input-field col s12 m4">
							  <i class="material-icons prefix">date_range</i>
							  <input id="submitted_at" name="submitted_at" data-reflectage="dob" value="{{$dataresigneddata->submitted_at}}" class="datepicker"  type="text">
							  <label for="icon_prefix">File Submitted</label>
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
							<div class="input-field col s12 m2">
	                            <p>
								@if($irc_details->attachment_fullform!="")
								<a href="{{ asset('storage/app/irc/'.$irc_details->attachment_fullform) }}" class="btn blue download-link" style="" target="_blank">VIEW ATTACHMENT</a>
								@endif
								</p>
							</div>
						<div class="input-field col s12 m2">
							<p>
							<input type="submit" class="btn" id="save" name="save" value="Submit" >
							</P>
						</div>	
						<div class="input-field col s12 m2 hide">
							<p>
							<input type="button" class="btn" id="cancel" name="cancel" value="Cancel" >
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
<script src="{{ asset('public/assets/vendors/data-tables/js/jquery.dataTables.min.js') }}"
type="text/javascript"></script>
<script
src="{{ asset('public/assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js') }}"
type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/data-tables/js/dataTables.select.min.js') }}"
type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/assets/js/scripts/form-validation.js')}}" type="text/javascript"></script>
@endsection
@section('footerSecondSection')
<script src="{{ asset('public/assets/js/scripts/data-tables.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jquery.autocomplete.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/mstepper.min.js') }}"></script>
<script src="{{ asset('public/assets/js/datepicker.js') }}"></script>
<script>

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
	var section_type = $("#section_type").val();
	if(section_type==1){
		MakeRequired('appcontactboxone',1);
	}else if(section_type==2){
		MakeRequired('appcontactboxtwo',2);
	}else if(section_type==3){
		MakeRequired('appcontactboxthree',3);
	}else if(section_type==4){
		MakeRequired('appcontactboxfour',4);
	}else if(section_type==5){
		MakeRequired('appcontactboxfive',5);
	}
	$('.modal').modal();
});
/* $("#member_number").devbridgeAutocomplete({
	//lookup: countries,
	serviceUrl: "{{ URL::to('/get-member-list') }}?searchkey="+ $("#member_number").val(),
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
					$('#bank_name').val(res.company_name);
					$('#memberid').val(res.memberid);
					$('#branch_name').val(res.branch_name);
					$('#dob').val(res.dob);
					$('#member_age').val(res.age);
					if(res.gender == 'Male')
					{
						$("#malegender").attr("checked",true);
					}
					else if(res.gender == 'Female')
					{
						$("#femalegender").attr("checked",true);
					}
					$('#doj').val(res.doj);
					$('#race_name').val(res.race_name);
					$('#nric_n').val(res.nric);
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
}); */
$(document.body).on('click', '.autocomplete-no-suggestion' ,function(){
	$("#member_number").val('');
});
$('.datepicker').datepicker({
	format: 'dd/mm/yyyy',
	autoHide: true,
});
// $("#irc_member_no").devbridgeAutocomplete({
// 	//lookup: countries,
// 	serviceUrl: "{{ URL::to('/get-ircmember-list') }}?searchkey="+ $("#irc_member_no").val(),
// 	type:'GET',
// 	params: { 
// 		union_branch_id:  function(){ return $("#union_branch_id").val();  },
// 	},
// 	//callback just to show it's working
// 	onSelect: function (suggestion) {
// 			$("#irc_member_no").val(suggestion.member_number);	
// 			$.ajax({
// 				url: "{{ URL::to('/get-ircmember-list-values') }}?member_id="+ $("#irc_member_no").val(),
//                 type: "GET",
// 				dataType: "json",
// 				success: function(res) {
// 					$('#irc_name').val(res.membername);
// 					$('#irc_bank').val(res.bankname);
// 					$('#irc_member_code').val(res.mid);
// 					$('#bank_address').val(res.address_one);
// 					$('#irctelephoneno').val(res.phone);
// 					$('#ircmobileno').val(res.mobile);
// 				}
// 			});

// 	},
// 	showNoSuggestionNotice: true,
// 	noSuggestionNotice: 'Sorry, no matching results',
// 	onSearchComplete: function (query, suggestions) {
// 		if(!suggestions.length){
// 			//$("#irc_member_no").val('');
// 		}
// 	}
// });
$(document.body).on('click', '.autocomplete-no-suggestion' ,function(){
	$("#irc_member_no").val('');
});
//IRC Member Details 
/* $("#irc_member_no").devbridgeAutocomplete({
	//lookup: countries,
	serviceUrl: "{{ URL::to('/get-ircmember-list') }}?searchkey="+ $("#irc_member_no").val(),
	type:'GET',
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
}); */
$(document.body).on('click', '.autocomplete-no-suggestion' ,function(){
	$("#irc_member_no").val('');
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
$(".datepicker-custom").datepicker({
    format: 'dd/mm/yyyy',
	autoHide: true,
});

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