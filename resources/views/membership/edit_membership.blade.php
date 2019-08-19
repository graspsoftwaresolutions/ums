@extends('layouts.admin')
@section('headSection')
@include('membership.member_common_styles')
@endsection
@section('headSecondSection')
	<style>
		.padding-left-10{
			padding-left:10px;
		}
		.padding-left-20{
			padding-left:20px;
		}
		.padding-left-40{
			padding-left:40px;
		}
		#irc_confirmation_area {
			pointer-events: none;
			background-color: #f4f8fb !important;
		}
		$("#irc_confirmation_area :input").attr("readonly", true);
	</style>
@endsection
@section('main-content')

<div id="">
<div class="row">
<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
<div class="col s12">
<div class="container">

<div class="section section-data-tables">
<!-- BEGIN: Page Main-->
<div class="row">
<!--div class="breadcrumbs-dark" id="breadcrumbs-wrapper">
<div class="container">
<div class="row">
<div class="col s10 m6 l6">
<h5 class="breadcrumbs-title mt-0 mb-0">Edit Membership</h5>
<ol class="breadcrumbs mb-0">
<li class="breadcrumb-item"><a href="index.html">Dashboard</a>
</li>
<li class="breadcrumb-item active"><a href="#">Member</a>
</li>

</ol>
</div>
</div>
</div>
</div-->
<div class="col s12">
<div class="card">
<div class="card-content">
<h4 class="card-title">Edit Membership</h4>
@include('includes.messages')
@php

$get_roles = Auth::user()->roles;
$user_role = $get_roles[0]->slug;

if(isset($data['member_view'])){

$values = $data['member_view'][0];
}else{
echo 'invalid access';
die;
}
$irc_status = $data['irc_status'];
@endphp

<form class="formValidate" id="member_formValidate" method="post" action="{{ url(app()->getLocale().'/membership_save') }}">
@csrf
<div class="row">
<div class="col s12">
<ul class="stepper horizontal" id="horizStepper">
@if($irc_status==1)
@php
	$irc_data = CommonHelper::getIrcDataByMember($values->mid);
	$resignedmemberno = $irc_data->resignedmemberno;
	$member_type = CommonHelper::getdesignationname($resignedmemberno);
	$resignedid = $irc_data->resignedreason;
	$irc_reason_name = CommonHelper::getircreason_byid($resignedid);
	
@endphp
<li class="step active">
	<div class="step-title waves-effect">IRC Confirmation</div>
	<div class="step-content">
		<div id="irc_confirmation_area" class="row">
			</br>
			 <div class="input-field col s6">
				<label for="irc_member_number"
					class="common-label force-active">{{__('Membership Number') }}*</label>
				<input id="irc_member_number" name="irc_member_number" value="{{ !empty($irc_data) ? $values->member_number : '' }}" class="common-input"
					type="text">
				
			</div>
			<div class="input-field col s6">
				<label for="irc_name_full" class="common-label force-active">{{__('IRC Name in Full') }}*</label>
				<input id="irc_name_full"  name="irc_name_full" class="common-input" value="{{ !empty($irc_data) ? $irc_data->ircname : '' }}"
					type="text">
				
			</div>
			<div class="input-field col s6">
				<div class="col s12 m3">
				 <p>{{__('IRC Position') }}</p>
				</div>
				<div class="col s12 m3">
					<p>
						<label>
						<input class="validate" required="" readonly aria-required="true" id="irc_position" name="irc_position" type="radio" {{ !empty($irc_data) && $irc_data->ircposition=='Chairman' ? 'checked' : '' }} >
						<span>{{__('Chairman') }}</span>
						</label> 
					</p>						
				</div>
				<div class="col s12 m3">
					<p>
						<label>
						<input class="validate" readonly required="" aria-required="true" id="irc_position" name="irc_position" type="radio" {{ !empty($irc_data) && $irc_data->ircposition=='Secretary' ? 'checked' : '' }} >
						<span>{{__('Secretary') }}</span>
						</label>
					</p>
				</div>
				<div class="col s12 m3">
					<p>
						<label>
						<input class="validate" readonly required="" aria-required="true" id="irc_position" name="irc_position" type="radio" {{ !empty($irc_data) && $irc_data->ircposition=='Commitee-Member' ? 'checked' : '' }} >
						<span>{{__('Commitee Member') }}</span>
						</label>
					</p>
				</div>
			</div>
			<div class="input-field col s6">
				<label for="irc_bank"
					class="common-label force-active">{{__('Bank') }}*</label>
				<input id="irc_bank"  name="irc_bank" class="common-input" value="{{ !empty($irc_data) ? $irc_data->ircbank : '' }}"
					type="text" >
				
			</div>
			<div class="input-field col s6">
				<label for="irc_bank_address"
					class="common-label force-active">{{__('Bank Address') }}*</label>
				<input id="irc_bank_address"  name="irc_bank_address" class="common-input" value="{{ !empty($irc_data) ? $irc_data->ircbankaddress : '' }}"
					type="text" >
				
			</div>
			<div class="input-field col s6">
				<label for="irc_office_telephone_number"
					class="common-label force-active">{{__('Office Number') }}*</label>
				<input id="irc_office_telephone_number"  name="irc_office_telephone_number" class="common-input" value="{{ !empty($irc_data) ? $irc_data->irctelephoneno : '' }}"
					type="text" >
				
			</div>
			<div class="clearfix" style="clear:both"></div>
			<div class="input-field col s6">
				<label for="irc_mobile"
					class="common-label force-active">{{__('Mobile') }}*</label>
				<input id="irc_mobile"  name="irc_mobile" class="common-input" value="{{ !empty($irc_data) ? $irc_data->ircmobileno : '' }}"
					type="text" >
				
			</div>
			<div class="input-field col s6">
				<label for="irc_fax"
					class="common-label force-active">{{__('Fax') }}*</label>
				<input id="irc_fax"  name="irc_fax" class="common-input" value="{{ !empty($irc_data) ? $irc_data->ircfaxno : '' }}"
					type="text" >
				
			</div>
			<div class="input-field col s12">
				<h6>Dear Sir,<br><br>
				I, the above named IRC hereby Confirmed that the following : [Tick all the boxes as confirmation]
				</h6>
			</div>
			<div class="col s12 m12">
				<div class="row padding-left-10">
					<div class="col s12 m4 input-field inline">
						<label>
							<input type="checkbox" class="filled-in" {{ !empty($irc_data) && $irc_data->nameofperson==1 ? 'checked' : '' }} />
							<span>Name of the Person appliying for BF is</span>
						</label> 
					</div>
					<div class="col s12 m3 ">
						<input type="text" name="irc_person_name" style="width:200%" value=" {{ !empty($irc_data) ? $irc_data->ircname : ''}}">
					</div>
				</div>						
			</div>
			<div class="col s12 m12">
				<div class="row padding-left-10">
					<div class="col s12 m4 input-field inline">
						<label>
							<input type="checkbox" class="filled-in" {{ !empty($irc_data) && $irc_data->waspromoted==1 ? 'checked' : '' }} />
							<span>She/He was @php echo strtolower(!empty($irc_reason_name) ? $irc_reason_name : '<span style="text-decoration:underline;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>') @endphp to</span>
						</label> 
					</div>
					<div class="col s12 m3 ">
						<input type="text" name="irc_promoted_person" style="width:110%" value="{{!empty($member_type) ? $member_type : ''}}">
					</div>
					<div class="col s12 m2 input-field inline">
						<label>
							grade w.e.f.
						</label>
					</div>
					<div class="col s12 m3">
						<input type="text" name="irc_grade" name="text" value=" {{ !empty($irc_data) ? $irc_data->gradewef : ''}}" placeholder="garde w.e.f" />	
					</div>
				</div>						
			</div>
			
			<div class="col s12 m12">
				<p class="padding-left-20">
					<label>
					<input type="checkbox" class="filled-in" {{ !empty($irc_data) && $irc_data->beforepromotion==1 ? 'checked' : '' }} />
					<span>I hearby confirm that She/He got She/He is no longer doing any clerical job function. </span>
					</label> 
				</p>		
			</div>
			<div class="col s12 m12">
				<p class="padding-left-20">
					<label>
					<input type="checkbox" class="filled-in" {{ !empty($irc_data) && $irc_data->attached==1 ? 'checked' : '' }} />
					<span>Attached Job function/Description (compulsory). </span>
					</label> 
				</p>		
			</div>
			<div class="col s12 m12">
				<p class="padding-left-20">
					<label>
					<input type="checkbox" class="filled-in" {{ !empty($irc_data) && $irc_data->herebyconfirm==1 ? 'checked' : '' }} />
					<span>I hereby confirm that he/she got promoted he/she no longer doing any clerical job function. </span>
					</label> 
				</p>		
			</div>
			<div class="col s12 m12">
				<p class="padding-left-20">
					<label>
					<input type="checkbox" class="filled-in" {{ !empty($irc_data) && $irc_data->filledby==1 ? 'checked' : '' }} />
					<span>The messenger clerical position has been filled by</span>
					</label> 
				</p>	
			</div>
			<div class="col s12 m12">
				</br>
				<h6>BRANCH COMMITEE VERIFICATION</h6>
				<div class="row">
					<div class="col s12 m12">
						<p class="padding-left-20">
							<label>
							<input type="checkbox" class="filled-in" {{ !empty($irc_data) && $irc_data->branchcommitteeverification1==1 ? 'checked' : '' }} />
							<span>I have verified the above and confirm that the declaration by the IRC is correct.The Messenger/Clerical position has filled by another Messenger/Clerical And; </span>
							</label> 
						</p>	
					</div>
					<div class="col s12 m12">
						<p class="padding-left-20">
							<label>
							<input type="checkbox" class="filled-in" {{ !empty($irc_data) && $irc_data->branchcommitteeverification2==1 ? 'checked' : '' }} />
							<span>I have promoted member is no longer doing Messenger/Clerical job functions. </span>
							</label> 
						</p>
					</div>
					</br>
					<div class="col s12 m12">	
						<div class="row">
							<div class="col s12">
							   Branch Commitee [Name in full]
							  <div class="input-field inline">
								<input id="irc_branch_committie_name" style="width:200%" type="text"  value="{{ !empty($irc_data) ? $irc_data->branchcommitteeName : '' }}" class="validate">
							  </div>
							</div>
						</div>
						
					</div>
					<div class="col s12 m8">	
						<div class="row">
							<div class="col s12">
							   Zone
							  <div class="input-field inline">
								<input id="irc_branch_committie_zone" type="text" value="{{ !empty($irc_data) ? $irc_data->branchcommitteeZone : '' }}" style="width:200%" class="validate">
							  </div>
							</div>
						</div>
						
					</div>
					<div class="col s12 m2">
						&nbsp;
					</div>
					<div class="col s12 m2 input-field inline">
						<!--<label>Date</label> -->
						<input type="text" class="" palceholder="Date" name="date" value="{{ !empty($irc_data) && $irc_data->branchcommitteedate!=""  ? date('d/M/Y',strtotime($irc_data->branchcommitteedate)) : '' }}" >
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col m12 s12 mb-1" style="text-align:right">
				<button id="controlled_nextone"  class="waves-effect waves dark btn btn-primary next-step"
					type="submit">
				Next
				<i class="material-icons right">arrow_forward</i>
				</button>
				
				<button class="waves-effect waves-dark btn btn-primary form-save-btn" onClick="return SubmitMemberForm()" 
					type="submit">Submit</button>
			</div>
		</div>
	</div>
</li>
@endif
<li id="memberstepper" class="step @if($irc_status!=1) active @endif">
	<div class="step-title waves-effect">Member Details</div>
	<div class="step-content" >
		<div style="box-shadow: 0 2px 2px 0 rgba(0, 0, 0, .14), 0 3px 1px -2px rgba(0, 0, 0, .12), 0 1px 5px 0 rgba(0, 0, 0, .2);padding:50px 50px;">
			<div id="view-validations">
				<div class="row">
				<div class="col s12 m6">
				<input id="auto_id" name="auto_id" value="{{$values->mid}}"  type="text" class="hide">
					<label>Member Title*</label>
					<select name="member_title" id="member_title" data-error=".errorTxt1"  required class="validate error browser-default selectpicker">
						<option value="" disabled selected>Choose your option</option>
						@foreach($data['title_view'] as $key=>$value)
						<option value="{{$value->id}}" @php if($value->id == $values->member_title_id) { echo "selected";} @endphp>{{$value->person_title}}</option>
					@endforeach
					</select>
						
				  <div class="errorTxt1"></div>
				</div>
				<div class="input-field col s12 m6">
				  <label for="member_number" class="force-active">Member Number *</label>
				  <input id="member_number" name="member_number" value="{{$values->member_number}}" readonly type="text" data-error=".errorTxt29">
				  <div class="errorTxt29"></div>
				</div>
				<div class="input-field col s12 m6">
				  <label for="name" class="force-active">Member Name *</label>
				  <input id="name" name="name" value="{{$values->name}}" type="text" data-error=".errorTxt30">
				  <div class="errorTxt30"></div>
				</div>
				
				<div class="input-field col s12 m6">
				<div class="col s12 row">
						<div class="col s12 m4">
							<p>Gender</p>
						</div>
						<div class="col s12 m4">
							<label>
								<input class="validate" required="" aria-required="true" id="gender" name="gender" type="radio" value="Female" {{ $values->gender == 'Female' ? 'checked' : '' }}>
								<span>Female</span>
							</label>  
						</div>
						<div class="col s12 m4">
							<p>
								<label>
								<input class="validate" required="" aria-required="true" id="gender" name="gender" type="radio" value="Male" {{ $values->gender == 'Male' ? 'checked' : '' }}>
								<span>Male</span>
								</label>
							</p>
						</div>
						<div class="input-field">
						</div>
						</div>
				</div> 
				<div class="clearfix" style="clear:both"></div>
				<div class="input-field col s12 m6">
				  <label for="mobile" class="force-active">Mobile Number *</label>
				  <input id="mobile" name="mobile" value="{{$values->mobile}}" required type="text"  data-error=".errorTxt24">
				  <div class="errorTxt24"></div>
				</div>
				<div class="input-field col s12 m6">
				  <label for="email" class="force-active">Email *</label>
				  <input id="email" name="email" readonly type="text" value="{{$values->email}}" data-error=".errorTxt25">
				  <div class="errorTxt25"></div>
				</div>
				<div class="clearfix" style="clear:both"></div>
				<div class="input-field col s12 m6">
				  
				  <input type="text" value="{{ date('d/M/Y',strtotime($values->doe)) }}"  class="datepicker" id="doe" name="doe">
					<label for="doe" class="force-active">Date of Emp</label>
				  <div class="errorTxt26"></div>
				</div>
					<div class="col s12 m6">
						@if($user_role!='member')
						 <div class="input-field col s12 m6">
							<p>
							<label>
								<input type="checkbox" id="rejoined" @php echo $values->old_member_number!="" ? 'checked' : ''; @endphp/>
								<span>Rejoined</span>
								
							</label>
							</p>
						 </div>
						 <div class="col s12 m6 " style="display:@php echo $values->old_member_number!="" ? 'block' : 'none'; @endphp" id="member_old_div">
						 <span> 
						 <input type="text" value="{{$values->old_member_number}}" id="old_mumber_number" name="old_mumber_number">
						
						 </span>
						 </div>
						 @else
						 <input type="checkbox" id="rejoined" class="hide" @php echo $values->old_member_number!="" ? 'checked' : ''; @endphp/>
							 </br>
							 @php echo $values->old_member_number!="" ? 'Old Number: '.$values->old_member_number : ''; @endphp
						 @endif
					</div>
					<div class="clearfix" style="clear:both"></div>

					<div class="col s12 m6">
						<label>Designation*</label>
							<select name="designation" id="designation" data-error=".errorTxt2"  class="error browser-default selectpicker">
								<option value="" >Select</option>
								@foreach($data['designation_view'] as $key=>$value)
									<option value="{{$value->id}}" @php if($value->id == $values->designation_id) { echo "selected";} @endphp>{{$value->designation_name}}</option>
								@endforeach
						   </select>
							   
						   <div class="input-field">
							 <div class="errorTxt2"></div>
						</div>   
					</div>
					<div class="col s12 m6">
					<label>Race*</label>
						<select name="race" id="race" data-error=".errorTxt3"  class="error browser-default selectpicker">
							<option value="" >Select</option>
							@foreach($data['race_view'] as $key=>$value)
								 <option value="{{$value->id}}" @php if($value->id == $values->race_id) { echo "selected";} @endphp>{{$value->race_name}}</option>
							@endforeach
						 </select>
						 <div class="input-field">
							 <div class="errorTxt3"></div>
						</div>   
					</div>
					<div class="col s12 m6">
					<label>Country Name*</label>
						<select name="country_id" id="country_id" data-error=".errorTxt4"  class="error browser-default selectpicker">
							<option value="" >Select</option>
							@foreach($data['country_view'] as $value)
								<option value="{{$value->id}}" @php if($value->id == $values->country_id) { echo "selected";} @endphp>{{$value->country_name}}</option>
							@endforeach
						</select>
						<div class="input-field">
							 <div class="errorTxt4"></div>
						</div>       
						
					</div>
					<div class="col s12 m6">
						<label>State Name*</label>
						<select name="state_id" id="state_id" data-error=".errorTxt5"  class="error browser-default selectpicker">
							<option value="" >Select</option>
							@foreach($data['state_view'] as $key=>$value)
								<option value="{{$value->id}}" @php if($value->id == $values->state_id) { echo "selected";} @endphp>{{$value->state_name}}</option>
							@endforeach
						</select>
							  
						<div class="input-field">
							 <div class="errorTxt5"></div>
						</div>   
					</div>
					<div class="clearfix" style="clear:both"></div>
					<div class="col s12 m6">
						 <label>City Name*</label>
						<select name="city_id" id="city_id" data-error=".errorTxt6" class="error browser-default selectpicker">
							<option value="" >Select</option>
							@foreach($data['city_view'] as $key=>$value)
							<option value="{{$value->id}}" @php if($value->id == $values->city_id) { echo "selected";} @endphp>{{$values->city_name}}</option>
							@endforeach
						</select>
							   
						<div class="input-field">
							 <div class="errorTxt6"></div>
						</div>   
					</div>
					
					<div class="input-field col s12 m6">
					<label for="postal_code" class="force-active">Postal Code *</label>
						<input id="postal_code" name="postal_code" value="{{$values->postal_code}}" type="text" data-error=".errorTxt7">
						<div class="errorTxt7"></div>
					</div>
					<div class="clearfix" style="clear:both"></div>
					<div class="input-field col s12 m6">
					<label for="address_one" class="force-active">Address Line 1*</label>
						<input id="address_one" name="address_one" required type="text" value="{{$values->address_one}}" data-error=".errorTxt8">
						<div class="errorTxt8"></div>
					</div>
					<div class="input-field col s12 m6">
					<label for="address_two" class="force-active">Address Line 2*</label>
						<input id="address_two" name="address_two" required  type="text" value="{{$values->address_two}}" data-error=".errorTxt9">
						<div class="errorTxt9"></div>
					</div>
					<div class="clearfix" style="clear:both"></div>
					<div class="input-field col s12 m6">
					<label for="address_three" class="force-active">Address Line 3*</label>
						<input id="address_three" name="address_three" required  type="text" value="{{$values->address_three}}" data-error=".errorTxt10">
						<div class="errorTxt10"></div>
					</div>
					<div class="col s12 m6">
						<div class="row">
							<div class="input-field col s12 m8">
								<label for="dob" class="force-active">Date of Birth *</label>
								<input id="dob" name="dob" value="{{ date('d/M/Y',strtotime($values->dob)) }}" data-reflectage="member_age" class="datepicker"  type="text"> 
							</div>
							<div class="input-field col s12 m4">
								<label for="member_age" class="force-active">Age</label>
								<input type="text" id="member_age" value="{{ CommonHelper::calculate_age($values->dob) }}" readonly >
							</div>
						</div>
					</div>
					<div class="clearfix" style="clear:both"></div>
					<div class="input-field col s12 m6">
						<input type="text" class="datepicker" id="doj" value="{{ date('d/M/Y',strtotime($values->doj)) }}" name="doj">
							<label for="doj" class="force-active">Date of Joining</label>
						<div class="errorTxt"></div>
					</div>
					
					<div class="input-field col s12 m6">
					<label for="salary" class="force-active">Salary*</label>
						<input id="salary" name="salary" value="{{$values->salary}}" required  type="text" data-error=".errorTxt11">
						<div class="errorTxt11"></div>
					</div>
					<div class="clearfix" style="clear:both"></div>
					<div class="input-field col s12 m6">
					<label for="salary" class="force-active">Old IC Number</label>
						<input id="old_ic" name="old_ic" value="{{$values->old_ic}}" type="text" data-error=".errorTxt12">
						<div class="errorTxt12"></div>
					</div>
					<div class="input-field col s12 m6">
					<label for="new_ic" class="force-active">New IC Number*</label>
						<input id="new_ic" name="new_ic" type="text" value="{{$values->new_ic}}" data-error=".errorTxt13">
						<div class="errorTxt13"></div>
					</div>
					<div class="clearfix" style="clear:both"></div>
					<div class="col s12 m6 hide">
						<label>Company Name*</label>
						<select name="company_id" id="company" class="error browser-default selectpicker">
							<option value="" >Select</option>
							@foreach($data['company_view'] as $value)
							<option @php //if($value->id == $values->company_id) { echo "selected";} @endphp value="{{$value->id}}">{{$value->company_name}}</option>
							@endforeach
						 </select>
						<div class="input-field">     
							<div class="errorTxt14"></div>
						</div>
					</div>
					
					@php 
						$auth_user = Auth::user();
						
						$check_union = $auth_user->hasRole('union');
						if($check_union){
							$branch_requird = 'required';
							$branch_disabled = '';
							$branch_hide = '';
							$branch_id = '';
						}else{
							$branch_requird = '';
							$branch_disabled = 'disabled';
							$branch_hide = 'hide';
							$branch_id = $auth_user->branch_id;
						}
						$branch_hide = 'hide';
					@endphp
					
					<div class=" col s12 m6 {{ $branch_hide }}">
						<label>Branch Name*</label>
						<select name="branch_id" id="branch" data-error=".errorTxt15" class="error browser-default selectpicker">
							<option value="" >Select</option>
							 @foreach($data['branch_view'] as $value)
							<option @php if($value->id == $values->branch_id) { echo "selected";} @endphp value="{{$value->id}}">{{$value->branch_name}}</option>
							@endforeach
						</select>
						<div class="input-field">           
							 <div class="errorTxt15"></div>
						</div>
					</div>
					@if($branch_hide=='hide')
						<div class=" col s12 m6 ">
						<label>Branch Name*</label>
						</br>
						<p style="margin-top:10px;font-weight:bold;">
							{{ CommonHelper::getBranchName($values->branch_id) }}
						</p>
						<div class="input-field">           
							 <div class="errorTxt15"></div>
						</div>
					</div>
					@endif
					
					<div class="col s12 m6">
					<label>{{__('Levy') }}</label>
						<select name="levy" id="levy" class="error browser-default selectpicker">
							<option value="">{{__('Select levy') }}</option>
							<option value="Not Applicable" {{ $values->levy == 'Not Applicable' ? 'selected' : '' }}> N/A</option>
							<option value="Yes" {{ $values->levy == 'Yes' ? 'selected' : '' }}>Yes</option>
							<option value="NO" {{ $values->levy == 'NO' ? 'selected' : '' }}>No</option>
						</select>
					</div>
					<div class="clearfix" ></div>
					<div class="input-field col s12 m6">
					<input id="levy_amount" name="levy_amount" type="text" value="{{$values->levy_amount}}">
						<label for="levy_amount" class="force-active">{{__('Levy Amount') }} </label>
					</div>
					<div class="col s12 m6">
					<label>{{__('TDF') }}</label>
					<select name="tdf" id="tdf" class="error browser-default selectpicker">	
							<option value="0">Select TDF</option>
							<option value="Not Applicable" {{ $values->tdf == 'Not Applicable' ? 'selected' : '' }}> N/A</option>
							<option value="Yes" {{ $values->tdf == 'Yes' ? 'selected' : '' }}>Yes</option>
							<option value="NO" {{ $values->tdf == 'NO' ? 'selected' : '' }}>No</option>
						</select>
					</div>
					<div class="clearfix" ></div>
				<div class="input-field col s12 m6">
				<input id="tdf_amount" name="tdf_amount" type="text" value="{{$values->tdf_amount}}">
					<label for="tdf_amount" class="force-active">{{__('TDF Amount') }} </label>
				</div>
				<div class="input-field col s12 m6">
					<label for="employee_id" class="force-active">Employee ID</label>
					<input id="employee_id" name="employee_id" value="{{$values->employee_id}}" type="text">
				</div>
					@php
					if($values->is_request_approved==0 && $check_union==1){
					@endphp
						<div class="col s12 m6 ">
							<label>Status*</label>
							<label>
								<input type="checkbox" id="activate_account" name="activate_account" value='1'/>
								&nbsp; <span>Verify account</span>
							</label>
							<div class="input-field">       
								<div class="errorTxt16"></div>
							</div>
						</div>
					@php												
					}
					@endphp
					@php
					if($values->is_request_approved==0){
					@endphp
						<div class="col s12 m6 ">
							<label>Status*</label>
							<p style="margin-top:10px;">
								<span style="color: rgba(255, 255, 255, 0.901961);" class=" gradient-45deg-deep-orange-orange padding-2 medium-small">Pending</span>
							</p>
						</div>
					@php												
					}else{
					@endphp
						<div class="col s12 m6 ">
							<label>Status*</label>
							@if($check_union==1)
								<select name="status_id" id="status_id" {{ $branch_disabled }} data-error=".errorTxt16" class="error browser-default">
									@foreach($data['status_view'] as $key=>$value)
										<option value="{{$value->id}}" <?php if($value->id == $values->status_id) { echo "selected";} ?>>{{$value->status_name}}</option>
									@endforeach
								</select>
							@else
							<p style="margin-top:10px;">.
								@php
								$status_val = CommonHelper::getStatusName($values->status_id);
								if($status_val ==''){
									$status_val = 'Pending';
								}
								@endphp
								<span style="color: rgba(255, 255, 255, 0.901961);" class="gradient-45deg-indigo-light-blue padding-2 medium-small">{{ $status_val }}</span>
							</p>
							@endif
						</div>
					@php												
					}
					@endphp
					
				</div>
				<div class="clearfix" style="clear:both"></div>
				<div class="row">
					<!--div class="col m4 s12 mb-3">
						<button class="red btn btn-reset" type="reset">
							<i class="material-icons left">clear</i>Reset
						</button>
						</div>
						<div class="col m6 s12 mb-3">
						<button class="btn btn-light previous-step" disabled>
							<i class="material-icons left">arrow_back</i>
							Prev
						</button>
						</div-->
					<div class="col m12 s12 mb-3" style="text-align: right;">
						@if($irc_status==1)
						<button class="btn btn-light previous-step">
							<i class="material-icons left">arrow_back</i>
							Prev
						</button>
						@endif
						<button id="controlled_nextone"  class="waves-effect waves dark btn btn-primary next-step"
							type="submit">
						Next
						<i class="material-icons right">arrow_forward</i>
						</button>
						<button id="submit-member" class="waves-effect waves-dark btn btn-primary form-save-btn"
					type="submit">Submit</button>
					</div>
				</div>

				</div>
		</div>
	</div>
</li>
<li class="step" id="steptrigger">
	<!--onclick="return SubmitMemberForm()"-->
	<div class="step-title waves-effect">Additional Details</div>
	<div class="step-content" style="padding: 50px 50px;">
		<div class="row">
			<div class="col s12">
				<!--h4 class="header">Additional Details</h4-->
				<!--p>
					If you want a collapsible with a preopened section just add the
					<code class=" language-markup">active</code> class to the li of collapsible-header.
					</p-->
			</div>
			<div class="col s12">
				<ul class="collapsible collapsible-accordion" data-collapsible="accordion">
					<li class="active">
						<div class="collapsible-header gradient-45deg-indigo-purple white-text" ><i class="material-icons">blur_circular</i> {{__('Fee Details') }}</div>
						
						<div class="collapsible-body ">
							<div class="row">
								<div class="col s12 m6">
									<label for="new_fee_id">Fee name* </label>
									<select name="new_fee_id" id="new_fee_id" class="error browser-default selectpicker">
										<option value="">Select</option>
										@foreach($data['fee_list'] as $key=>$value)
											<option data-feename="{{$value->fee_name}}" data-feeamount="{{$value->fee_amount}}" value="{{$value->id}}">{{$value->fee_name}}</option>
										@endforeach
									</select>
									<div class="input-field">
										 <div class="errorTxt50"></div>
									</div>  
								</div>
								
								<div class="input-field col s12 m6">
								   <label for="fee_amount" class="force-active">Fee amount *</label>
								   <input id="fee_amount" name="fee_amount" value="0"  type="text">
								   
								   
								</div>
							   
								<div class="clearfix"> </div>
								<div class="col s12 m12">
									<button class="btn waves-effect waves-light right submit" id="add_fee" type="button" name="add_fee_row">Add Fee
									<i class="material-icons right">send</i>
								</button>
								</div>
							</div>
							</br>
							<div class="row">
								<div class="col s12">
									
									@php // print_r($data['nominee_view']); @endphp
									<table id="fee_table" width="100%">
										<thead>
											<tr>
											<th data-field="feename">Fee Name</th>
											<th data-field="feeamount">Amount</th>
											<th data-field="action" width="25%">Action</th>
											</tr>
										</thead>
									<tbody>
										@php
										{{ $sl = 0; }}
										@endphp
										@foreach($data['fee_view'] as $key=>$value)
										<tr id="nominee_{{ $sl }}">
											<td><span id="fee_name_label_{{ $sl }}">{{ CommonHelper::get_fee_name($value->fee_id) }}</span>
												<input type="text" class="hide" name="fee_auto_id[]" id="fee_auto_id_{{ $sl }}" value="{{$value->id}}"></input>
												<input type="text" class="hide" name="fee_name_id[]" id="fee_name_id_{{ $sl }}" value="{{$value->fee_id}}"></input>
											</td>
											<td><span id="fee_amount_label_{{ $sl }}">{{$value->fee_amount}}</span><input type="text" class="hide" name="fee_name_amount[]" id="fee_name_amount_{{ $sl }}" value="{{$value->fee_amount}}"></td>
											<td>
											<a class="btn-floating waves-effect waves-light cyan edit_fee_row " href="#modal_fee" data-id="{{$sl}}"><i class="material-icons left">edit</i></a>
											<a class="btn-floating waves-effect waves-light amber darken-4 delete_fee_db" data-id="{{$sl}}" data-autoid="{{$value->id}}" onclick="if (confirm('Are you sure you want to delete?')) return true; else return false;"><i class="material-icons left">delete</i></a>
											</td>
										</tr>
										@php
										{{ $sl++; }}
										@endphp
										
										@endforeach
										<input id="fee_row_id" class="hide" name="fee_row_id" value="{{ $sl }}"  type="text">
									</tbody>
									</table>
								</div>
							</div>
						</div>
					</li>
					
					<li >
						<div class="collapsible-header gradient-45deg-indigo-purple white-text"><i class="material-icons">details</i> {{__('Nominee Details') }}</div>
						<div class="collapsible-body">
							<div id="nominee_add_section" class="row">
								<div class="input-field col s12 m4">
									<label for="nominee_name">Nominee name* </label>
									<input id="nominee_name" name="nominee_name" value=""  type="text">
								</div>
								<div class="col s12 m4">
									<div class="row">
										<div class=" col s12 m8">
											<p>
												<label for="nominee_dob">DOB *</label>
												<input id="nominee_dob" data-reflectage="nominee_age" name="nominee_dob" value="" class="datepicker"  type="text"> 
												
											</p>
										</div>
										<div class="col s12 m4">
											<label for="nominee_dob">Age</label>
											<span> 
											<input type="text" id="nominee_age" readonly>
											</span>
										</div>
									 </div>
								</div>
								<div class="col s12 m4">
								   <label for="years">Sex *</label>
									<select name="sex" id="sex" class="error browser-default selectpicker">
										<option value="">Select</option>
										<option value="Male" >Male</option>
										<option value="Female" >Female</option>
									</select>
									<div class="input-field">
										 <div class="errorTxt50"></div>
									</div>  
								</div>
								<div class="clearfix"> </div>
								<div class="input-field col s12 m4">
									 <label class="force-active">Relationship*</label>
										<select name="relationship" id="relationship"   class="error browser-default selectpicker ">
											<option value="" >Select</option>
											@foreach($data['relationship_view'] as $key=>$value)
												<option value="{{$value->id}}" data-relationshipname="{{$value->relation_name}}" >{{$value->relation_name}}</option>
											@endforeach
									   </select>
										   
									   <div class="">
										 <div class="errorTxt31"></div>
									   </div>   
									
								</div>
								<div class="input-field col s12 m4">
									<label for="nric_n">NRIC-N *</label>
									<input id="nric_n" name="nric_n" value=""  type="text">
									
								</div>
								<div class="input-field col s12 m4">
									<label for="nric_o">NRIC-O </label>
									<input id="nric_o" name="nric_o" value=""  type="text">
									
								</div>
								<div class="clearfix"> </div>
							  
								<div class="col s12 m4">
									 <label class="force-active">Country Name*</label>
									<select name="nominee_country_id" id="nominee_country_id"  class="error browser-default selectpicker">
										<option value="">Select Country</option>
										@foreach($data['country_view'] as $value)
											<option value="{{$value->id}}" >{{$value->country_name}}</option>
										@endforeach
									</select>
									<div class="input-field">
										 <div class="errorTxt35"></div>
									</div>       
									
								</div>
								<div class="col s12 m4">
									 <label class="force-active">State Name*</label>
									<select name="nominee_state_id" required id="nominee_state_id"  class="error browser-default selectpicker">
										<option value="" >Select</option>
										@foreach($data['state_view'] as $key=>$value)
											<option value="{{$value->id}}" @php if($value->id == $values->state_id) { echo "selected";} @endphp>{{$value->state_name}}</option>
										@endforeach
									</select>
									<div class="input-field">
										 <div class="errorTxt36"></div>
									</div>       
									
								</div>
								<div class="col s12 m4">
									 <label class="force-active">City Name*</label>
									<select name="nominee_city_id" required id="nominee_city_id"  class="error browser-default selectpicker">
										<option value="" >Select</option>
										@foreach($data['city_view'] as $key=>$value)
										<option value="{{$value->id}}" @php if($value->id == $values->city_id) { echo "selected";} @endphp>{{$values->city_name}}</option>
										@endforeach
									</select>
									<div class="input-field">
										 <div class="errorTxt36"></div>
									</div>       
									
								</div>
								<div class="clearfix"> </div>
								<div class="input-field col s12 m4">
									<label for="nominee_postal_code" class="force-active">Postal code*</label>
									<input id="nominee_postal_code" name="nominee_postal_code" type="text" value="" >
									 
								</div>
								<div class="input-field col s12 m4">
									<label for="nominee_address_one" class="force-active">Address Line 1*</label>
									<input id="nominee_address_one" name="nominee_address_one" type="text" value="" >
								</div>
								<div class="input-field col s12 m4">
									<label for="nominee_address_two" class="force-active">Address Line 2*</label>
									<input id="nominee_address_two" name="nominee_address_two" type="text" value="" >
									 
								</div>
							   
								
								<div class="clearfix"> </div>
								<div class="input-field col s12 m4">
									<label for="nominee_address_three" class="force-active">Address Line 3*</label>
									<input id="nominee_address_three" name="nominee_address_three" type="text" value="" >
									 
								</div>
								<div class="input-field col s12 m4">
									<label for="nominee_mobile" class="force-active">Mobile No*</label>
									<input id="nominee_mobile" name="nominee_mobile" type="text" value="" >
									 
								</div>
								<div class="input-field col s12 m4">
									<label for="nominee_phone" class="force-active">Phone No</label>
									<input id="nominee_phone" name="nominee_phone" type="text" value="" >
									 
								</div>
								<div class="clearfix"> </div>
								<div class="col s12 m12">
									<button class="btn waves-effect waves-light right submit" id="add_nominee" type="button" name="add_nominee">Add Nominee
									<i class="material-icons right">send</i>
								</button>
								</div>
							</div>
							<div class="row">
								<div class="col s12">
									
									@php // print_r($data['nominee_view']); @endphp
									<table id="nominee_table" width="100%">
										<thead>
											<tr>
											<th data-field="name">Name</th>
											<th data-field="age">Age</th>
											<th data-field="sex">Sex</th>
											<th data-field="relationship">Relationship</th>
											<th data-field="nric_n">NRIC-N</th>
											<th data-field="nric_o">NRIC-0</th>
											<th data-field="action" width="25%">Action</th>
											</tr>
										</thead>
										
									<tbody>
										@php
										{{ $sl = 0; }}
										@endphp
										@foreach($data['nominee_view'] as $key=>$value)
										<tr>
											
											<td>
											<span id="nominee_name_label_{{ $sl }}">{{$value->nominee_name}}</span><input type="text" name="nominee_auto_id[]" class="hide" id="nominee_auto_id_{{ $sl }}" value="{{$value->id}}" ><input class="hide" type="text" name="nominee_name_value[]" id="nominee_name_value_{{ $sl }}" value="{{$value->nominee_name}}">
											</td>

											<td>
											<span id="nominee_age_label_{{ $sl }}">{{ CommonHelper::calculate_age($value->dob) }}</span><input type="text" class="hide" name="nominee_age_value[]" id="nominee_age_value_{{ $sl }}" value="{{ CommonHelper::calculate_age($value->dob) }}"><input type="text" class="hide" name="nominee_dob_value[]" id="nominee_dob_value_{{ $sl }}" value="{{ CommonHelper::convert_date_datepicker($value->dob) }}">
											</td>

											<td><span id="nominee_gender_label_{{ $sl }}">{{$value->gender}}</span><input type="text" class="hide" name="nominee_gender_value[]" id="nominee_gender_value_{{ $sl }}" value="{{ $value->gender }}"></td>

											<td>
											<span id="nominee_relation_label_{{ $sl }}">{{ CommonHelper::get_relationship_name($value->relation_id) }}</span>
											<input type="text" class="hide" name="nominee_relation_value[]" id="nominee_relation_value_{{ $sl }}" value="{{$value->relation_id}}">
											</td>

											<td><span id="nominee_nricn_label_{{ $sl }}">{{$value->nric_n}}</span><input class="hide" type="text" name="nominee_nricn_value[]" id="nominee_nricn_value_{{ $sl }}" value="{{$value->nric_n}}"></td>

											<td>
											<span id="nominee_nrico_label_{{ $sl }}">{{$value->nric_o}}</span><input type="text" class="hide" name="nominee_nrico_value[]" id="nominee_nrico_value_{{ $sl }}" value="{{$value->nric_o}}">
											</td>

											<td class="hide">
											<span id="nominee_addressone_label_{{ $sl }}">{{$value->address_one}}</span><input type="text" class="hide" name="nominee_addressone_value[]" id="nominee_addressone_value_{{ $sl }}" value="{{$value->address_one}}">
											</td>

											<td class="hide"><span id="nominee_addresstwo_label_{{ $sl }}">{{$value->address_two}}</span><input type="text" class="hide" name="nominee_addresstwo_value[]" id="nominee_addresstwo_value_{{ $sl }}" value="{{$value->address_two}}"></td>

											<td class="hide"><span id="nominee_addressthree_label_{{ $sl }}">{{$value->address_three}}</span><input type="text" class="hide" name="nominee_addressthree_value[]" id="nominee_addressthree_value_{{ $sl }}" value="{{$value->address_three}}"></td>

											<td class="hide"><span id="nominee_country_label_{{ $sl }}">{{$value->country_id}}</span><input type="text" name="nominee_country_value[]" id="nominee_country_value_{{ $sl }}" value="{{$value->country_id}}"></td>

											<td class="hide"><span id="nominee_state_label_{{ $sl }}">{{$value->state_id}}</span><input type="text" name="nominee_state_value[]" id="nominee_state_value_{{ $sl }}" value="{{$value->state_id}}"></td>

											<td class="hide"><span id="nominee_city_label_{{ $sl }}">{{$value->city_id}}</span><input type="text" name="nominee_city_value[]" id="nominee_city_value_{{ $sl }}" value="{{$value->city_id}}"></td>

											<td class="hide"><span id="nominee_postalcode_label_{{ $sl }}">{{$value->postal_code}}</span><input type="text" name="nominee_postalcode_value[]" id="nominee_postalcode_value_{{ $sl }}" value="{{$value->postal_code}}"></td>

											<td class="hide"><span id="nominee_mobile_label_{{ $sl }}">{{$value->mobile}}</span><input type="text" name="nominee_mobile_value[]" id="nominee_mobile_value_{{ $sl }}" value="{{$value->mobile}}"></td>

											<td class="hide"><span id="nominee_phone_label_{{ $sl }}">{{$value->phone}}</span><input type="text" name="nominee_phone_value[]" id="nominee_phone_value_{{ $sl }}" value="{{$value->phone}}"></td>

											<td>
											<a class="btn-floating waves-effect waves-light cyan edit_nominee_row " href="#modal_nominee" data-id="{{$sl}}"><i class="material-icons left">edit</i></a>
											<a class="btn-floating waves-effect waves-light amber darken-4 delete_nominee_db" data-id="{{$value->id}}" onclick="if (confirm('Are you sure you want to delete?')) return true; else return false;"><i class="material-icons left">delete</i></a>
											</td>
											
										</tr>
										@php
										{{ $sl++; }}
										@endphp
										@endforeach
										<input id="nominee_row_id" class="hide" name="nominee_row_id" value="{{ $sl }}"  type="text">
									</tbody>
									</table>
								</div>
							</div>
						</div>
					</li>
					<li>
						<div class="collapsible-header gradient-45deg-indigo-purple white-text"><i class="material-icons">filter_center_focus</i>{{__('Guardian Details') }}</div>
						<div class="collapsible-body">
							<div class="row">
								@php 
								if(count($data['gardian_view'])>0) {
									$gardian_row = $data['gardian_view'][0];  
								}
								
								@endphp
								<div class="input-field col s12 m4">
									<label for="guardian_name" class="force-active">Guardian name* </label>
									<input id="guardian_name" name="guardian_name" value="@isset($gardian_row) @php echo $gardian_row->guardian_name; @endphp @endisset"  type="text" >
								</div>
								<div class="col s12 m4">
									<div class="row">
										<div class=" col s12 m8">
											<p>
												<label for="gaurdian_dob" class="force-active">DOB *</label>
												<input id="gaurdian_dob" name="gaurdian_dob" data-reflectage="gaurdian_age" value="@isset($gardian_row) {{ date('d/M/Y',strtotime($gardian_row->dob)) }} @endisset" class="datepicker"  type="text"> 
												
											</p>
										</div>
										<div class="col s12 m4">
											<label for="gaurdian_age" class="force-active">Age</label>
											<span> 
											<input type="text" id="gaurdian_age" value="@isset($gardian_row) {{ CommonHelper::calculate_age($gardian_row->dob) }} @endisset" readonly >
											</span>
										</div>
									</div>
								</div>
								<div class="col s12 m4">
									<label for="guardian_sex" class="force-active">SEX *</label>
									<select name="guardian_sex" id="guardian_sex" class="error browser-default selectpicker">
										<option value="">Select</option>
										<option @isset($gardian_row) @php echo $gardian_row->gender=='Male' ? 'selected': ''; @endphp @endisset value="Male" >Male</option>
										<option @isset($gardian_row) @php echo $gardian_row->gender=='Female' ? 'selected': ''; @endphp @endisset value="Female" >Female</option>
									</select>
									
								</div>
								
								<div class="clearfix"> </div>
								<div class="col s12 m4">
									 <label>Relationship*</label>
										<select name="relationship_id" id="gaurd_relationship" data-error=".errorTxt31"  class="error browser-default selectpicker">
										   <option value="" >Select</option> @foreach($data['relationship_view'] as $key=>$value)
												<option @isset($gardian_row) @php echo $gardian_row->relationship_id==$value->id ? 'selected': ''; @endphp @endisset value="{{$value->id}}" >{{$value->relation_name}}</option>
											@endforeach
									   </select>
										   
									   <div class="input-field">
										 <div class="errorTxt31"></div>
									   </div>   
									
								</div>
								<div class="input-field col s12 m4">
									<label for="nric_n_guardian" class="force-active">NRIC-N *</label>
									<input id="nric_n_guardian" name="nric_n_guardian" value="@isset($gardian_row) @php echo $gardian_row->nric_n; @endphp @endisset"  type="text">
									
								</div>
								<div class="input-field col s12 m4">
									<label for="nric_o_guardian" class="force-active">NRIC-O </label>
									<input id="nric_o_guardian" name="nric_o_guardian" value="@isset($gardian_row) @php echo $gardian_row->nric_o; @endphp @endisset"  type="text">
									
								</div>
								<div class="clearfix"> </div>
							   
								<div class="col s12 m4">
									 <label class="force-active">Country Name*</label>
									<select name="guardian_country_id" id="guardian_country_id"  class="error browser-default selectpicker">
										<option value="" >Select</option>
										@foreach($data['country_view'] as $value)
											<option @isset($gardian_row) @php echo $gardian_row->country_id == $value->id ? 'selected' : ''; @endphp @endisset value="{{$value->id}}" >{{$value->country_name}}</option>
										@endforeach
									</select>
									<div class="input-field">
										 <div class="errorTxt35"></div>
									</div>       
									
								</div>
								<div class="col s12 m4">
									 <label class="force-active">State Name*</label>
									<select name="guardian_state_id" id="guardian_state_id"  class="error browser-default selectpicker">
										<option value="" >Select</option>
										@foreach($data['state_view'] as $key=>$value)
											<option value="{{$value->id}}" @isset($gardian_row) @php if($value->id == $gardian_row->state_id) { echo "selected";} @endphp @endisset>{{$value->state_name}}</option>
										@endforeach
									</select>
									<div class="input-field">
										 <div class="errorTxt36"></div>
									</div>       
									
								</div>
								<div class="col s12 m4">
									 <label class="force-active">City Name*</label>
									<select name="guardian_city_id" id="guardian_city_id"  class="error browser-default selectpicker">
										<option value="" >Select</option>
										@foreach($data['city_view'] as $key=>$value)
										<option value="{{$value->id}}" @isset($gardian_row) @php if($value->id == $gardian_row->city_id) { echo "selected"; } @endphp @endisset>{{$value->city_name}}</option>
										@endforeach
									</select>
									<div class="input-field">
										 <div class="errorTxt36"></div>
									</div>       
									
								</div>
								<div class="clearfix"> </div>
								<div class="input-field col s12 m4">
									<label for="guardian_postal_code" class="force-active">Postal code*</label>
									<input id="guardian_postal_code" name="guardian_postal_code" type="text" value="@isset($gardian_row) @php echo $gardian_row->postal_code; @endphp @endisset" >
									 
								</div>
								<div class="input-field col s12 m4">
									<label for="guardian_address_one" class="force-active">Address Line 1*</label>
									<input id="guardian_address_one" name="guardian_address_one" type="text" value="@isset($gardian_row) @php echo $gardian_row->guardian_name; @endphp @endisset" >
									 
								</div>
								<div class="input-field col s12 m4">
									<label for="guardian_address_two" class="force-active">Address Line 2*</label>
									<input id="guardian_address_two" name="guardian_address_two" type="text" value="@isset($gardian_row) @php echo $gardian_row->address_two; @endphp @endisset" >
									 
								</div>
								
							   
								<div class="clearfix"> </div>
								<div class="input-field col s12 m4">
									<label for="guardian_address_three" class="force-active">Address Line 3*</label>
									<input id="guardian_address_three" name="guardian_address_three" type="text" value="@isset($gardian_row) @php echo $gardian_row->address_three; @endphp @endisset" >
									 
								</div>
								<div class="input-field col s12 m4">
									<label for="guardian_mobile" class="force-active">Mobile No*</label>
									<input id="guardian_mobile" name="guardian_mobile" type="text" value="@isset($gardian_row) @php echo $gardian_row->mobile; @endphp @endisset" >
									 
								</div>
								<div class="input-field col s12 m4">
									<label for="guardian_phone" class="force-active">Phone No</label>
									<input id="guardian_phone" name="guardian_phone" type="text" value="@isset($gardian_row) @php echo $gardian_row->phone; @endphp @endisset" >
									 
								</div>
								<div class="clearfix"> </div>
								</div>
						</div>
				</li>
				
				</ul>
			</div>
		</div>
		<div class="row">
			<div class="col m12 s12 mb-1" style="text-align:right">
				<button class="btn btn-light previous-step">
					<i class="material-icons left">arrow_back</i>
					Prev
				</button>
				@if($irc_status==1)
				<button id="controlled_nextone"  class="waves-effect waves dark btn btn-primary next-step" type="submit">
						Next
				<i class="material-icons right">arrow_forward</i>
				</button>
				@endif
				<button class="waves-effect waves-dark btn btn-primary form-save-btn" onClick="return SubmitMemberForm()" 
					type="submit">Submit</button>
			</div>
		</div>
	</div>
</li>
@if($irc_status==1)
<li class="step">
	<div class="step-title waves-effect">Resignation</div>
	@php
		$resignedrow = CommonHelper::getResignDataByMember($values->mid);
		$reasondata = CommonHelper::getResignData();
		$lastmonthendrow = CommonHelper::getlastMonthEndByMember($values->mid);
		$lastpaid = '';
		if(!empty($lastmonthendrow)){
			$lastpaid = date('M/Y',strtotime($lastmonthendrow->StatusMonth));
		}
	@endphp
	<div class="step-content">
		<div  class="row">
			</br>
			 <div class="input-field col s6">
				<label for="resign_date" class="force-active">Resign Date *</label>
				  <input type="text" class="datepicker" id="resign_date" data-error=".errorTxt500"  name="resign_date">
					
				  <div class="errorTxt500"></div>
			 </div>
			 <div class="col s6">
				 <div class="row">
					<div class="input-field col s6">
						<input type="text" placeholder="Paid Till" id="last_paid" data-error=".errorTxt501" name="last_paid" value="{{$lastpaid}}">
						<label for="last_paid" class="force-active">Paid Till *</label>
						  <div class="errorTxt501"></div>
					 </div>				 
					<div class="col s12 m6">
					<label for="resign_claimer" class="force-active">Claim</label>
						<select name="resign_claimer" id="resign_claimer" data-error=".errorTxt502" class="error browser-default selectpicker ">
							<option value="" >Select</option>
							@foreach($data['relationship_view'] as $key=>$value)
								<option value="{{$value->id}}" >{{$value->relation_name}}</option>
							@endforeach
					    </select>
						<div class="input-field">
							<div class="errorTxt502"></div>
						</div>
					 </div>
					 
				 </div>
			 </div>
			 <div class="clearfix" style="clear:both"></div>
			 <div class="col s6">
				<label for="resign_reason force-active" >Reason*</label>
				<select name="resign_reason" id="resign_reason" data-error=".errorTxt503" class="force-active error browser-default selectpicker" >
					<option value="">Select reason</option>
					@foreach($reasondata as $values)
						<option value="{{$values->id}}">{{$values->reason_name}}</option>
					@endforeach
					<div class="input-field">
						<div class="errorTxt503"></div>
					</div>
				</select>
			</div>
			<div class="input-field col s6">
				<input type="text" id="claimer_name" name="claimer_name" data-error=".errorTxt504">
				<label for="claimer_name" class="force-active">Claimer Name</label>
				 <div class="errorTxt504"></div>
			</div>
			 <div class="clearfix" style="clear:both"></div>
			 <div class="input-field col s6">
				<div class="row">
					<div class="input-field col s6">
						<input type="text"  id="service_year" name="service_year">
							<label for="service_year" class="force-active">Service Year</label>
						  <div class="errorTxt26"></div>
					 </div>
					 <div class="input-field col s6">
						<input type="text"  id="benefit_year" name="benefit_year" readonly >
							<label for="benefit_year" class="force-active">Benefit Year</label>
						  <div class="errorTxt26"></div>
					 </div>
				</div>
			 </div>
			 <div class="input-field col s6">
				<div class="row">
					<div class="input-field col s6">
						<input type="text"  id="contributed_months" name="contributed_months">
							<label for="contributed_months" class="force-active">Contributed Months</label>
						  <div class="errorTxt26"></div>
					 </div>
					 <div class="input-field col s6">
						<input type="text"  id="bf_contribution" name="bf_contribution">
							<label for="bf_contribution" class="force-active">BF Contribution</label>
						  <div class="errorTxt26"></div>
					 </div>
				</div>
			 </div>
			 <div class="clearfix" style="clear:both"></div>
			 <div class="input-field col s6">
				<div class="row">
					<div class="input-field col s6">
						<input type="text"  id="amount" name="amount">
							<label for="amount" class="force-active">Amount</label>
						  <div class="errorTxt26"></div>
					 </div>
					 <div class="input-field col s6">
						<input type="text"  id="benefit_amount" name="benefit_amount" readonly >
							<label for="benefit_amount" class="force-active">Benefit Amount</label>
						  <div class="errorTxt26"></div>
					 </div>
				</div>
			 </div>
			 
			 <div class="input-field col s6">
				<div class="row">
					<div class="input-field col s6">
						<input type="text" id="benefit_amount" name="benefit_amount" readonly >
							<label for="benefit_amount" class="force-active">Benefit Amount</label>
						  <div class="errorTxt26"></div>
					 </div>
					 <div class="input-field col s6">
						<input type="text"  id="sub_total" name="sub_total">
							<label for="sub_total" class="force-active">Sub Total</label>
						  <div class="errorTxt26"></div>
					 </div>
				</div>
			 </div>
			 <div class="clearfix" style="clear:both"></div>
			 <div class="col s12 m6">
				<div class="row">
					<div class="col s12 m6">
					<label>{{__('PayMode') }}*</label>
						<select name="pay_mode" id="pay_mode" class="error browser-default selectpicker">
						<option value="" disabled selected>{{__('Choose your option') }}</option>
						<option value="CHEQUE"> CHEQUE</option>
						<option value="ONLINE PAY"> ONLINE PAY</option>
						</select>
						  <div class="errorTxt26"></div>
					 </div>
					 <div class="input-field col s6">
						<input type="text"  id="reference_number" name="reference_number">
							<label for="reference_number" class="force-active">Reference Number</label>
						  <div class="errorTxt26"></div>
					 </div>
				</div>
			 </div>
			 <div class="col s12 m6">
				<div class="row">
					<div class="input-field col s6">
						<input type="text"  id="union_contribution" name="union_contribution">
							<label for="union_contribution" class="force-active">Union Contribution</label>
						  <div class="errorTxt26"></div>
					 </div>
					 <div class="input-field col s6 ">
						<input type="text"  id="insurance_amount" name="insurance_amount">
							<label for="insurance_amount" class="force-active">Insurance Amount</label>
						  <div class="errorTxt26"></div>
					 </div>
				</div>
			 </div>
			 <div class="clearfix" style="clear:both"></div>
			 <div class="col s12 m6">
				<div class="row">
					<div class="col s12 m6">
					<label>{{__('Cheque Date') }}*</label>
						<input type="text" name="cheque_date" id="cheque_date" class="datepicker">
						  <div class="errorTxt26"></div>
					 </div>
					 <div class="col s12 m6">
						<label>{{__('Payment Confirmation') }}*</label>
						<input type="text" name="payment_confirmation" id="payment_confirmation" class="datepicker">
						  <div class="errorTxt26"></div>
					 </div>
				</div>
			 </div>
			  <div class="col s12 m6">
				<label for="total_amount" class="force-active">Total Amount</label>
				  <input type="text" id="total_amount" name="total_amount">
				  <div class="errorTxt26"></div>
			 </div>
			 <div class="clearfix" style="clear:both"></div>
			 </div>
		<div class="row">
			<div class="col m12 s12 mb-1" style="text-align:right">
				<button class="btn btn-light previous-step">
					<i class="material-icons left">arrow_back</i>
					Prev
				</button>
				
				<button id="submitResignation" class="waves-effect waves-dark btn btn-primary form-save-btn"  
					type="button">Submit</button>
			</div>
		</div>
	</div>
</li>
@endif

</ul>
</div>
</div>
</form>
</div>
</div>
</div>
</div>
<!-- END: Page Main-->
@include('layouts.right-sidebar')
</div>
</div>
</div>
</div>
<!-- modal -->
<!-- Modal Trigger -->
<!-- Modal Structure --> 



</div>

@endsection
@section('footerSection')
<script src="{{ asset('public/assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/assets/vendors/noUiSlider/nouislider.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/materialize.min.js') }}"></script>
<script src="{{ asset('public/assets/js/scripts/form-elements.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jquery.autocomplete.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/mstepper.min.js') }}"></script>
@endsection

@section('footerSecondSection')
<script src="{{ asset('public/assets/js/scripts/form-wizard.js') }}" type="text/javascript"></script>
<script>
$(document).ready(function(){
//$('#member_old_div').hide();
$(".selectpicker-new").select2();
});
$('.datepicker').datepicker({
	format: 'yyyy-mm-dd'
});

$(document.body).on('click', '.delete_nominee_db' ,function(){
var nominee_id = $(this).data('id');
var parrent = $(this).parents("tr");
$.ajax({
type: "GET",
dataType: "json",
url : "{{ URL::to('/delete-nominee-data') }}?nominee_id="+nominee_id,
success:function(res){
if(res)
{
parrent.remove(); 
M.toast({
html: res.message
});
}else{
M.toast({
html: res.message
});
}
}
});
});


$(document.body).on('click', '.delete_fee_db' ,function(){
var fee_id = $(this).data('autoid');
var parrent = $(this).parents("tr");
$.ajax({
type: "GET",
dataType: "json",
url : "{{ URL::to('/delete-fee-data') }}?fee_id="+fee_id,
success:function(res){
if(res)
{
parrent.remove(); 
M.toast({
html: res.message
});
}else{
M.toast({
html: res.message
});
}
}
});
});

$(document).ready(function(){
//loader.showLoader();
$('#member_old_div').hide();
var horizStepper = document.querySelector('#horizStepper');
var horizStepperInstace = new MStepper(horizStepper, {
// options
firstActive: 0,
showFeedbackPreloader: true,
autoFormCreation: true,
validationFunction: defaultValidationFunction,
stepTitleNavigation: false,
feedbackPreloader: '<div class="spinner-layer spinner-blue-only">...</div>'
});

horizStepperInstace.resetStepper();


});
function defaultValidationFunction(horizStepper, activeStepContent) {
stepper_id = $("#horizStepper li.active").attr('id');
if(stepper_id=="memberstepper"){
var inputs = activeStepContent.querySelectorAll('input, textarea, select');
for (let i = 0; i < inputs.length; i++) 
{
if (!inputs[i].checkValidity()) {
jQuery("#submit-member").trigger('submit');
return false;
}
}

return true;
}
return true;
}

$(document.body).on('click', '#submitResignation' ,function(){
	var resign_date = $("#resign_date").val();
	var last_paid = $("#last_paid").val();
	var resign_reason = $("#resign_reason").val();
});
/* function nextBtn() {
$("#controlled_next").trigger('submit');
return false;		
} */
</script>
@include('membership.member_common_script')
@endsection