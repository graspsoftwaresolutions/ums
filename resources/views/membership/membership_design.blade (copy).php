@extends($data['user_type']==1 ? 'layouts.admin' : 'layouts.new-member')
@section('headSection')
	@include('membership.member_common_styles')
@endsection
@section('headSecondSection')

<link rel="stylesheet" type="text/css" href="{{ asset('public/css/wizard-app.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/css/wizard-theme.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/datepicker.css') }}">
<link class="rtl_switch_page_css" href="{{ asset('public/css/steps.css') }}" rel="stylesheet" type="text/css">
<style type="text/css">
	fieldset{
		border: none;
	}
	input:not([type]), input[type=text]:not(.browser-default), input[type=password]:not(.browser-default), input[type=email]:not(.browser-default), input[type=url]:not(.browser-default), input[type=time]:not(.browser-default), input[type=date]:not(.browser-default), input[type=datetime]:not(.browser-default), input[type=datetime-local]:not(.browser-default), input[type=tel]:not(.browser-default), input[type=number]:not(.browser-default), input[type=search]:not(.browser-default), textarea.materialize-textarea {
		height: 2.5rem;
	}
	.input-field.col label {
	    left: 1rem;
	}
	.select2-container--default .select2-selection--single .select2-selection__rendered {
	    font-size: 15px !important;
	    line-height: 1.4;
	    padding-left: 0px !important;
	}
	.select2 .selection .select2-selection--single, .select2-container--default .select2-search--dropdown .select2-search__field {
	    height: 2.2rem !important;
	}
	.input-field {
	    position: relative;
	    margin-top: 1rem;
	    margin-bottom: 0.5rem; 
	}
	html {
	    
	     line-height: 1.4; 
	}
	label{
		vertical-align: text-top;
	}
	div.clearfix {
	    clear: both;
	    margin: 5px 0;
	}
	.sub-headings{
		font-weight: bold;
   		border-bottom: 1px solid #9e9e9e;
   		margin-left: 5px;
   		margin-bottom: 24px;
	    margin-top: 10px;
	    padding-bottom: 5px;
	}
	.modal {
	    position: fixed;
	    right: 0;
	    left: 0;
	    display: none;
	    overflow-y: auto;
	    width: 60%;
	    max-height: 90%;
	    margin: auto;
	    padding: 0;
	    border-radius: 2px;
	    background-color: #fafafa;
	    will-change: top, opacity;
	}
	.scrollable-element {
	  scrollbar-width: thin;
	}
	.datepicker-container{
		z-index: 99999 !important;;
	}
</style>
@endsection
@section('main-content')
<div id="">
	<div class="row">
		<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
		<div class="col s12">
			<div class="container">
				<div class="loading-overlay"></div>
				<div class="loading-overlay-image-container">
					<img src="{{ asset('public/images/loading.gif') }}" class="loading-overlay-img"/>
				</div>
				<div class="section section-data-tables">
					<!-- BEGIN: Page Main-->
					<div class="row">
						@if($data['user_type']==1)
						<!--div class="breadcrumbs-dark" id="breadcrumbs-wrapper">
							<div class="container">
								<div class="row">
									<div class="col s10 m6 l6">
										<h5 class="breadcrumbs-title mt-0 mb-0">{{__('New Membership') }}</h5>
										<ol class="breadcrumbs mb-0">
											<li class="breadcrumb-item"><a href="index.html">{{__('Dashboard') }}</a>
											</li>
											<li class="breadcrumb-item active"><a href="#">{{__('Member') }}</a>
											</li>
										</ol>
									</div>
								</div>
							</div>
						</div-->
						@endif
						<div class="col s12">
							<!--h4 class="card-title">{{__('New Membership') }}</h4-->
							@include('includes.messages')
							<div class="row">
								<div class="col s9">
									<div class="card theme-mda">
										<div class="card-content pb-0">
											<div class="card-header">
												<h4 class="card-title">{{__('New Membership') }}
													@if($data['user_type']!=1)
													<a class="btn waves-effect waves-light right" href="{{ route('login', app()->getLocale())  }}">{{__('Login') }}</a>
													@endif
												</h4>
												
											</div>
											<a class="waves-effect waves-light btn modal-trigger" href="#modal1">Modal</a>
											<form class="formValidate hide" id="wizard2" method="post" action="{{ url(app()->getLocale().'/membership_save') }}">
											@csrf
												@php 
													$auth_user = Auth::user();
													$member_number_readonly = 'readonly';
													$member_number_hide = 'hide';
													$companylist = $data['company_view'];
													$branchlist = [];
													$companyid = '';
													$branchid = '';
													if(!empty($auth_user)){
														$userid = Auth::user()->id;
														$get_roles = Auth::user()->roles;
														$user_role = $get_roles[0]->slug;
														
														$companylist = [];
														
														if($user_role =='union' || $user_role=='data-entry'){
															$member_number_readonly = '';
															$member_number_hide = '';
															$member_status = 2;
															$companylist = $data['company_view'];
														}
														else if($user_role =='union-branch'){
															$unionbranchid = CommonHelper::getUnionBranchID($userid);
															$companylist = CommonHelper::getUnionCompanyList($unionbranchid);
															
															$member_status = 1;
														} 
														else if($user_role =='company'){
															$branchid = CommonHelper::getCompanyBranchID($userid);
															$companyid = CommonHelper::getCompanyID($userid);
															$companylist = CommonHelper::getCompanyList($companyid);
															$branchlist = CommonHelper::getCompanyBranchList($companyid);
															//print_r($branchlist);die;
															$member_status = 1;
														}
														else if($user_role =='company-branch'){
															$branchid = CommonHelper::getCompanyBranchID($userid);
															$member_status = 1;
															$companyid = CommonHelper::getCompanyID($userid);
															$companylist = CommonHelper::getCompanyList($companyid);
															$branchlist = CommonHelper::getCompanyBranchList($companyid,$branchid);
														}  
													}
													
												@endphp
												
												<fieldset>
													</br>
													<div class="col-sm-8 col-sm-offset-1">
														<div class="row">
																<div class="col s12 m6">
																	<label >{{__('Member Title') }}*</label>
																	<select name="member_title" id="member_title" required data-error=".errorTxt1" class="error browser-default selectpicker">
																		<option value="" disabled selected>{{__('Choose your option') }}</option>
																		@foreach($data['title_view'] as $key=>$value)
																		@if (old('member_title') == $value->id)
																		<option value="{{$value->id}}" selected>{{$value->person_title}}</option>
																		@else
																		<option value="{{$value->id}}">{{$value->person_title}}</option>
																		@endif
																		@endforeach
																	</select>
																	<div class="input-field">
																		<div class="errorTxt1"></div>
																	</div>
																	<input id="auto_id" name="auto_id" value=""  type="text" class="hide">
																</div>
																<!--input id="member_number" name="member_number" value=""  type="hidden"  data-error=".errorTxt2"-->
																	
																<div class="input-field col s12 m6 hide">
																	<input id="member_number" name="member_number" value="{{ CommonHelper::get_auto_member_number() }}"   type="hidden" {{ $member_number_readonly }} data-error=".errorTxt2">
																	<label for="member_number" class="force-active">{{__('Member Number') }} *</label>
																	<div class="errorTxt2"></div>
																</div>
																<div class="input-field col s12 m6">
																	<label for="name" class="force-active">{{__('Member Name') }} *</label>
																	<input id="name" name="name" type="text" class="validate" value="{{ old('name') }}" data-error=".errorTxt3">
																	<div class="errorTxt3"></div>
																</div>

																<div class="clearfix" style="margin: 0px;"></div>
																<div class="input-field col s12 m6" style="margin-top: 0px;margin-bottom: 19px;">
																	<div class="row">
																		<div class="col s12 m4">
																			<p>{{__('Gender') }}</p>
																		</div>
																		<div class="col s12 m4">
																			<label>
																			<input class="validate" required="" aria-required="true" id="gender1" name="gender" type="radio" value="Female">
																			<span>{{__('Female') }}</span>
																			</label>  
																		</div>
																		<div class="col s12 m4">
																			<p>
																				<label>
																				<input class="validate" required="" aria-required="true" id="gender" name="gender" type="radio" checked="" value="Male">
																				<span>{{__('Male') }}</span>
																				</label>
																			</p>
																		</div>
																		<div class="input-field">
																		</div>
																	</div>
																</div>

																<div class="clearfix" ></div>
																<div class="clearfix" style="clear:both"></div>
																<div class="input-field col s12 m6">
																	<label for="mobile" class="force-active">{{__('Mobile Number') }} *</label>
																	<input id="mobile" name="mobile" value="{{ old('mobile') }}" type="text" data-error=".errorTxt5">
																	<div class="errorTxt5"></div>
																</div>
																<div class="input-field col s12 m6">
																	<label for="email" class="force-active">{{__('Email') }} </label>
																	<input id="email" name="email" value="{{ old('email') }}" type="email" data-error=".errorTxt6">
																	<div class="errorTxt6"></div>
																</div>
																<div class="clearfix" ></div>
																<div class="input-field col s12 m6">
																	<input type="text" class="datepicker-normal" id="doe" value="{{ old('doe') }}" name="doe">
																	<label for="doe" class="force-active">{{__('Date of Emp') }}*</label>
																	<div class="errorTxt7"></div>
																</div>
																<div class="col s12 m6">
																	<div class="input-field col s12 m3">
																		<p>
																			<label>
																			<input type="checkbox" id="rejoined"/>
																			<span>{{__('Rejoined') }}</span>
																			</label>
																		</p>
																	</div> 
																	<div class="input-field col s12 m8" style="display:none;margin-left: 20px;" id="member_old_div">
																		<input type="text" name="old_mumber_number" value="{{ old('old_mumber_number') }}" id="old_mumber_number" class="autocomplete">
																		<input type="text" name="old_member_id" value="" id="old_member_id" class="autocomplete hide">
																		<label for="old_mumber_number">{{__('Old Number') }}</label>
																		<span> 
																		</span>
																	</div>
																</div>
																<div class="clearfix" style="clear:both"></div>
																<div class="col s12 m6">
																	<label>{{__('Designation') }}*</label>
																	<select name="designation" id="designation" class="error browser-default selectpicker" data-error=".errorTxt8">
																		<option value="" >{{__('Select') }}</option>
																		@foreach($data['designation_view'] as $key=>$value)
																		<option value="{{$value->id}}">{{$value->designation_name}}</option>
																		@endforeach
																	</select>
																	<div class="input-field">
																		<div class="errorTxt8"></div>
																	</div>
																</div>
																<div class="col s12 m6">
																	<label>Race*</label>
																	<select name="race" id="race" value="{{ old('race') }}" class="error browser-default selectpicker" data-error=".errorTxt9">
																		<option value="" >{{__('Select Race') }}</option>
																		@foreach($data['race_view'] as $key=>$value)
																		<option value="{{$value->id}}">{{$value->race_name}}</option>
																		@endforeach
																	</select>
																	<div class="input-field">
																		<div class="errorTxt9"></div>
																	</div>
																</div>
																<div class="clearfix" ></div>
																<div class="col s12 m6">
																	<label>{{__('Country Name') }}*</label>
																	@php
																	$Defcountry = CommonHelper::DefaultCountry();
																	@endphp
																	<select name="country_id" id="country_id" class="error browser-default selectpicker" data-error=".errorTxt10">
																		<option value="">{{__('Select Country') }}</option>
																		@foreach($data['country_view'] as $value)
																		<option @if($Defcountry==$value->id) selected @endif value="{{$value->id}}">{{$value->country_name}}</option>
																		@endforeach
																	</select>
																	<div class="input-field">
																		<div class="errorTxt10"></div>
																	</div>
																</div>
																@php
																$statelist = CommonHelper::getStateList($Defcountry);
																@endphp
																<div class="col s12 m6">
																	<label>{{__('State Name') }}*</label>
																	<select class="error browser-default selectpicker" id="state_id" name="state_id" data-error=".errorTxt11" aria-required="true" required>
																		<option value="" selected>{{__('State Name') }}</option>
																		@foreach($statelist as $key=>$value)
																			<option value="{{$value->id}}" >{{$value->state_name}}</option>
																		@endforeach
																	</select>
																	<div class="input-field">
																		<div class="errorTxt11"></div>
																	</div>
																</div>
																<div class="clearfix" style="clear:both"></div>
																<div class="col s12 m6">
																	<label>{{__('City Name') }}*</label>
																	<select name="city_id" id="city_id" class="error browser-default selectpicker" aria-required="true" required data-error=".errorTxt12">
																		<option value="">{{__('Select City') }}</option>
																	</select>
																	<div class="input-field">
																		<div class="errorTxt12"></div>
																	</div>
																</div>
																<div class="input-field col s12 m6">
																	<label for="postal_code" class="force-active">{{__('Postal Code') }} *</label>
																	<input id="postal_code" name="postal_code" class="" value="{{ old('postal_code') }}" type="text" data-error=".errorTxt13">
																	<div class="errorTxt13"></div>
																</div>
																<div class="clearfix" ></div>
																<div class="input-field col s12 m6">
																	<label for="address_one" class="force-active">{{__('Address Line 1') }}*</label>
																	<input id="address_one" name="address_one" value="{{ old('address_one') }}" type="text" data-error=".errorTxt14">
																	<div class="errorTxt14"></div>
																</div>
																<div class="input-field col s12 m6">
																	<label for="address_two" class="force-active">{{__('Address Line 2') }}*</label>
																	<input id="address_two" name="address_two" value="{{ old('address_two') }}" type="text" data-error=".errorTxt15">
																	<div class="errorTxt15"></div>
																</div>
																<div class="clearfix" ></div>
																<div class="input-field col s12 m6">
																	<label for="address_three" class="force-active">{{__('Address Line 3') }}</label>
																	<input id="address_three" name="address_three" value="{{ old('address_three') }}" type="text" data-error=".errorTxt16">
																	<div class="errorTxt16"></div>
																</div>
																<div class="col s12 m6">
																	<div class="row">
																		<div class="input-field col s12 m8">
																			<label for="dob" class="force-active">{{__('Date of Birth') }} *</label>
																			<input id="dob" name="dob" value="{{ old('dob') }}" data-reflectage="member_age" value="{{ old('dob') }}" class="datepicker"  type="text"> 
																		</div>
																		<div class="input-field col s12 m4">
																			<label for="member_age" class="force-active">{{__('Age') }}</label>
																			<input type="text" id="member_age" value="{{ old('member_age') != '' ? old('member_age') : 0 }}" readonly >
																		</div>
																	</div>
																</div>
																<div class="clearfix" ></div>
																<div class="input-field col s12 m6">
																	<input type="text" class="datepicker" id="doj" value="{{ old('doj') }}" name="doj" data-error=".errorTxt18">
																	<label for="doj" class="force-active">{{__('Date of Joining') }}*</label>
																	<div class="errorTxt18"></div>
																</div>
																<div class="input-field col s12 m6">
																	<label for="salary" class="force-active">{{__('Salary') }}*</label>
																	<input id="salary" name="salary" value="{{ old('salary') }}" type="text" data-error=".errorTxt19">
																	<div class="errorTxt19"></div>
																</div>
																<div class="clearfix" ></div>
																<div class="input-field col s12 m6">
																	<label for="salary" class="force-active">{{__('Old IC Number') }}</label>
																	<input id="old_ic" name="old_ic" type="text" value="{{ old('old_ic') }}" data-error=".errorTxt20">
																	<div class="errorTxt20"></div>
																</div>
																<div class="input-field col s12 m6">
																	<label for="new_ic" class="force-active">{{__('New IC Number') }}*</label>
																	<input id="new_ic" name="new_ic" type="text" value="{{ old('new_ic') }}" data-error=".errorTxt21">
																	<div class="errorTxt21"></div>
																</div>
																<div class="clearfix" ></div>
																<div class=" col s12 m6 union-data ">
																	<label>{{__('Company Name') }}*</label>
																	<select name="company_id" id="company" class="error browser-default selectpicker" data-error=".errorTxt22" required >
																		<option value="">{{__('Select Company') }}</option>
																		@foreach($companylist as $value)
																		<option @if($companyid==$value->id) selected @endif value="{{$value->id}}">{{$value->company_name}}</option>
																		@endforeach
																	</select>
																	<div class="input-field">
																		<div class="errorTxt22"></div>
																	</div>
																</div>

																<div class="col s12 m6 union-data ">
																	<label>{{__('Company Branch Name') }}*</label>
																	<select name="branch_id" id="branch" class="error browser-default selectpicker" data-error=".errorTxt23" required >
																		<option value="">{{__('Select Branch') }}</option>
																		@foreach($branchlist as $branch)
																		<option @if($branchid==$branch->id) selected @endif value="{{$branch->id}}">{{$branch->branch_name}}</option>
																		@endforeach
																	</select>
																	<div class="input-field">
																		<div class="errorTxt23"></div>
																	</div>
																</div>
																<div class="clearfix" ></div>	
																<div class="col s12 m6">
																<label>{{__('Levy') }}</label>
																  <select name="levy" id="levy" class="error browser-default selectpicker" >
																		<option value="">{{__('Select levy') }}</option>
																		<option value="Not Applicable">N/A</option>
																		<option value="Yes">Yes</option>
																		<option value="NO">No</option>
																	</select>
																</div>
																<div class="input-field col s12 m6">
																<input id="levy_amount" name="levy_amount" type="text">
																	<label for="levy_amount" class="force-active">{{__('Levy Amount') }} </label>
																	
																</div>
																<div class="clearfix" ></div>

																<div class="col s12 m6">
																<label>{{__('TDF') }}</label>
																  <select name="tdf" id="tdf" class="error browser-default selectpicker">
																		<option value="">{{__('Select TDF') }}</option>
																		<option value="Not Applicable">N/A</option>
																		<option value="Yes">Yes</option>
																		<option value="NO">No</option>
																	</select>
																</div>
																<div class="input-field col s12 m6">
																<input id="tdf_amount" name="tdf_amount" type="text">
																	<label for="tdf_amount" class="force-active">{{__('TDF Amount') }} </label>
																</div>
																<div class="clearfix" ></div>
																<div class="input-field col s12 m6">
																	<label for="employee_id" class="force-active">{{__('Employee ID') }}</label>
																	<input id="employee_id" name="employee_id" value="{{ old('employee_id') }}" type="text">
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
																	
																</div>
																
															</div>
														
													</div><!-- /.col- -->
												</fieldset>
											 
												
												<fieldset>
													<div class="col-sm-8 col-sm-offset-1">
														<div class="row">
															<div id="moredata" class="hide">
																
																		<div class="sub-headings"> {{__('Nominee Details') }}</div>
																		<div class="">
																			<div id="nominee_add_section" class="row" style="margin-left: 0px;">
																				<div class="input-field col s12 m4">
																					<label for="nominee_name" class="force-active">Nominee name* </label>
																					<input id="nominee_name" name="nominee_name" value=""  type="text">
																				</div>
																				<div class="col s12 m4">
																					<div class="row">
																						<div class="input-field col s12 m8">
																							<label for="nominee_dob" class="force-active">DOB *</label>
																							<input id="nominee_dob" name="nominee_dob" data-reflectage="nominee_age" value="" class="datepicker"  type="text"> 
																						</div>
																						<div class="input-field col s12 m4">
																							<label for="nominee_dob" class="force-active">Age</label>
																							<input type="text" id="nominee_age" value="0" readonly >
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
																				<div class="col s12 m4">
																					<label>Relationship*</label>
																					<select name="relationship" id="relationship" data-error=".errorTxt31"  class="error browser-default selectpicker">
																						<option value="" selected>State Relationship</option>
																						@foreach($data['relationship_view'] as $key=>$value)
																						<option value="{{$value->id}}" data-relationshipname="{{$value->relation_name}}" >{{$value->relation_name}}</option>
																						@endforeach
																					</select>
																					<div class="input-field">
																						<div class="errorTxt31"></div>
																					</div>
																				</div>
																				<div class="input-field col s12 m4">
																					<label for="nric_n" class="force-active">NRIC-N *</label>
																					<input id="nric_n" name="nric_n" value=""  type="text">
																				</div>
																				<div class="input-field col s12 m4">
																					<label for="nric_o" class="force-active">NRIC-O </label>
																					<input id="nric_o" name="nric_o" value=""  type="text">
																				</div>
																				<div class="clearfix"> </div>
																				<div class="col s12 m4">
																					<label>Country Name*</label>
																					@php
																					$Defcountry = CommonHelper::DefaultCountry();
																					@endphp
																					<select name="nominee_country_id" id="nominee_country_id"  class="error browser-default selectpicker">
																						<option value="">Select Country</option>
																						@foreach($data['country_view'] as $value)
																							<option value="{{$value->id}}" @isset($row) @php if($value->id
																								== $row->country_id) { echo "selected";} @endphp @endisset @if($Defcountry==$value->id) selected @endif
																								>{{$value->country_name}}</option>
																						@endforeach
																					</select>
																					<div class="input-field">
																						<div class="errorTxt35"></div>
																					</div>
																				</div>
																				<div class="col s12 m4">
																					<label>State Name*</label>
																					<select name="nominee_state_id" id="nominee_state_id"  class="error browser-default selectpicker">
																						
																						<option value="" selected>{{__('State Name') }}</option>
																							@foreach($statelist as $key=>$value)
																								<option value="{{$value->id}}" >{{$value->state_name}}</option>
																							@endforeach
																					</select>
																					<div class="input-field">
																						<div class="errorTxt36"></div>
																					</div>
																				</div>
																				<div class="col s12 m4">
																					<label>City Name*</label>
																					<select name="nominee_city_id" id="nominee_city_id"  class="error browser-default selectpicker">
																						<option value="">Select</option>
																					</select>
																					<div class="input-field">
																						<div class="errorTxt36"></div>
																					</div>
																				</div>
																				<div class="clearfix"> </div>
																				<div class="">
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
																				</div>
																				<div class="clearfix"> </div>
																				<div class="">
																					<div class="input-field col s12 m4">
																						<label for="nominee_address_three" class="force-active">Address Line 3</label>
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
																				</div>
																				<div class="clearfix"> </div>
																				<div class="col s12 m12">
																					<button class="btn waves-effect waves-light right submit" id="add_nominee" type="button" name="add_nominee">Add Nominee
																					</button>
																				</div>
																			</div>
																			<div class="row">
																				<div class="col s12" style="margin-left: 5px;">
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
																						</tbody>
																					</table>
																					@php
																					{{ $sl = 0; }}
																					@endphp
																					<input id="nominee_row_id" class="hide" name="nominee_row_id" value="{{ $sl }}"  type="text">
																				</div>
																			</div>
																		</div>
																		<br>
																		<br>
																		<div class="sub-headings">{{__('Guardian Details') }}</div>
																		<div class="">
																			<div class="row" style="margin-left: 0px;">
																				<div class="input-field col s12 m4">
																					<label for="guardian_name" class="force-active">Guardian name* </label>
																					<input id="guardian_name" name="guardian_name" value=""  type="text" >
																				</div>
																				<div class="col s12 m4">
																					<div class="row">
																						<div class="input-field col s12 m8">
																							
																								<label for="gaurdian_dob" class="force-active">DOB *</label>
																								<input id="gaurdian_dob" name="gaurdian_dob" data-reflectage="gaurdian_age" value="" class="datepicker"  type="text"> 	
																							
																						</div>
																						<div class="input-field col s12 m4">
																							<label for="gaurdian_age" class="force-active">Age</label>
																							<span> 
																							<input type="text" id="gaurdian_age" value="0" readonly >
																							</span>
																						</div>
																					</div>
																				</div>
																				<div class="col s12 m4">
																					<label for="guardian_sex" class="force-active">SEX *</label>
																					<select name="guardian_sex" id="guardian_sex" class="error browser-default selectpicker">
																						<option value="">Select</option>
																						<option value="Male" >Male</option>
																						<option value="Female" >Female</option>
																					</select>
																				</div>
																				<div class="clearfix"> </div>
																				<div class="col s12 m4">
																					<label class="force-active">Relationship*</label>
																					<select name="g_relationship_id" id="g_relationship" data-error=".errorTxt31"  class="error browser-default selectpicker">
																						<option value="">Select</option>
																						@foreach($data['relationship_view'] as $key=>$value)
																						<option value="{{$value->id}}" >{{$value->relation_name}}</option>
																						@endforeach
																					</select>
																					<div class="input-field">
																						<div class="errorTxt31"></div>
																					</div>
																				</div>
																				<div class="input-field col s12 m4">
																					<label for="nric_n_guardian" class="force-active">NRIC-N *</label>
																					<input id="nric_n_guardian" name="nric_n_guardian" value=""  type="text">
																				</div>
																				<div class="input-field col s12 m4">
																					<label for="nric_o_guardian" class="force-active">NRIC-O </label>
																					<input id="nric_o_guardian" name="nric_o_guardian" value=""  type="text">
																				</div>
																				<div class="clearfix"> </div>
																				<div class="col s12 m4">
																					<label>Country Name*</label>
																					@php
																					$Defcountry = CommonHelper::DefaultCountry();
																					@endphp
																					<select name="guardian_country_id" id="guardian_country_id"  class="error browser-default selectpicker">
																						<option value="">Select</option>
																						@foreach($data['country_view'] as $value)
																							<option value="{{$value->id}}" @isset($row) @php if($value->id
																								== $row->country_id) { echo "selected";} @endphp @endisset @if($Defcountry==$value->id) selected @endif
																								>{{$value->country_name}}</option>
																						@endforeach
																					</select>
																					<div class="input-field">
																						<div class="errorTxt35"></div>
																					</div>
																				</div>
																				<div class="col s12 m4">
																					<label>State Name*</label>
																					<select name="guardian_state_id" id="guardian_state_id"  class="error browser-default selectpicker">
																						
																						<option value="" selected>{{__('State Name') }}</option>
																							@foreach($statelist as $key=>$value)
																								<option value="{{$value->id}}" >{{$value->state_name}}</option>
																							@endforeach
																					</select>
																					<div class="input-field">
																						<div class="errorTxt36"></div>
																					</div>
																				</div>
																				<div class="col s12 m4">
																					<label>City Name*</label>
																					<select name="guardian_city_id" id="guardian_city_id"  class="error browser-default selectpicker">
																						<option value="" >Select</option>
																					</select>
																					<div class="input-field">
																						<div class="errorTxt36"></div>
																					</div>
																				</div>
																				<div class="clearfix"> </div>
																				<div class="input-field col s12 m4">
																					<label for="guardian_postal_code" class="force-active">Postal code*</label>
																					<input id="guardian_postal_code" name="guardian_postal_code" type="text" value="" >
																				</div>
																				<div class="input-field col s12 m4">
																					<label for="guardian_address_one" class="force-active">Address Line 1*</label>
																					<input id="guardian_address_one" name="guardian_address_one" type="text" value="" >
																				</div>
																				<div class="input-field col s12 m4">
																					<label for="guardian_address_two" class="force-active">Address Line 2*</label>
																					<input id="guardian_address_two" name="guardian_address_two" type="text" value="" >
																				</div>
																				<div class="clearfix"> </div>
																				<div class="input-field col s12 m4">
																					<label for="guardian_address_three" class="force-active">Address Line 3</label>
																					<input id="guardian_address_three" name="guardian_address_three" type="text" value="" >
																				</div>
																				<div class="input-field col s12 m4">
																					<label for="guardian_mobile" class="force-active">Mobile No*</label>
																					<input id="guardian_mobile" name="guardian_mobile" type="text" value="" >
																				</div>
																				<div class="input-field col s12 m4">
																					<label for="guardian_phone" class="force-active">Phone No</label>
																					<input id="guardian_phone" name="guardian_phone" type="text" value="" >
																				</div>
																				<div class="clearfix"> </div>
																			</div>
																		</div>
																	
																		<div class="hide" >{{__('Fee Details') }}</div>
																		
																		<div class="hide">
																			<form id="fee_new_form" name="fee_new_form">
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
																					</button>
																				</div>
																			</div>
																			</form>
																			</br>
																			<div class="row">
																				<div class="col s12">
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
																							<input id="fee_row_id" class="hide" name="fee_row_id" value="{{ $sl }}"  type="text">
																						</tbody>
																					</table>
																				</div>
																			</div>
																		</div>
																	
															</div>
														
														</div>
													</div><!-- /.col- -->
												</fieldset>
												<br>
												<br>
												<br>
											 	<!--div class="input-field col s12 hide" style="position: fixed;left: 0;bottom: 0; width: 100%;background-color: #6a72cc;color: white;text-align: center; padding: 10px;">
											 		<button onclick="myShowMoreFunction()" class="btn waves-effect waves-light gradient-45deg-indigo-purple left submit" id="myBtn">Show more</button>
													<button id="form-save-btn"  class="btn waves-effect waves-light purple right submit" type="submit" name="action">Finish</button>
												</div-->
											</form>

										</div>
										
									</div>
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

  <!-- Modal Trigger -->
  
  <!-- Modal Structure -->
  <div id="modal1" class="modal modal-fixed-footer">
    <div class="modal-content">
      <h5>New Registration</h5>
     <form class="formValidate" id="wizard2" method="post" action="{{ url(app()->getLocale().'/membership_save') }}">
											@csrf
												@php 
													$auth_user = Auth::user();
													$member_number_readonly = 'readonly';
													$member_number_hide = 'hide';
													$companylist = $data['company_view'];
													$branchlist = [];
													$companyid = '';
													$branchid = '';
													if(!empty($auth_user)){
														$userid = Auth::user()->id;
														$get_roles = Auth::user()->roles;
														$user_role = $get_roles[0]->slug;
														
														$companylist = [];
														
														if($user_role =='union' || $user_role=='data-entry'){
															$member_number_readonly = '';
															$member_number_hide = '';
															$member_status = 2;
															$companylist = $data['company_view'];
														}
														else if($user_role =='union-branch'){
															$unionbranchid = CommonHelper::getUnionBranchID($userid);
															$companylist = CommonHelper::getUnionCompanyList($unionbranchid);
															
															$member_status = 1;
														} 
														else if($user_role =='company'){
															$branchid = CommonHelper::getCompanyBranchID($userid);
															$companyid = CommonHelper::getCompanyID($userid);
															$companylist = CommonHelper::getCompanyList($companyid);
															$branchlist = CommonHelper::getCompanyBranchList($companyid);
															//print_r($branchlist);die;
															$member_status = 1;
														}
														else if($user_role =='company-branch'){
															$branchid = CommonHelper::getCompanyBranchID($userid);
															$member_status = 1;
															$companyid = CommonHelper::getCompanyID($userid);
															$companylist = CommonHelper::getCompanyList($companyid);
															$branchlist = CommonHelper::getCompanyBranchList($companyid,$branchid);
														}  
													}
													
												@endphp
												
												<fieldset>
													</br>
													<div class="col-sm-8 col-sm-offset-1">
														<div class="row">
																<div class="col s12 m6">
																	<label >{{__('Member Title') }}*</label>
																	<select name="member_title" id="member_title" required data-error=".errorTxt1" class="error browser-default selectpicker">
																		<option value="" disabled selected>{{__('Choose your option') }}</option>
																		@foreach($data['title_view'] as $key=>$value)
																		@if (old('member_title') == $value->id)
																		<option value="{{$value->id}}" selected>{{$value->person_title}}</option>
																		@else
																		<option value="{{$value->id}}">{{$value->person_title}}</option>
																		@endif
																		@endforeach
																	</select>
																	<div class="input-field">
																		<div class="errorTxt1"></div>
																	</div>
																	<input id="auto_id" name="auto_id" value=""  type="text" class="hide">
																</div>
																<!--input id="member_number" name="member_number" value=""  type="hidden"  data-error=".errorTxt2"-->
																	
																<div class="input-field col s12 m6 hide">
																	<input id="member_number" name="member_number" value="{{ CommonHelper::get_auto_member_number() }}"   type="hidden" {{ $member_number_readonly }} data-error=".errorTxt2">
																	<label for="member_number" class="force-active">{{__('Member Number') }} *</label>
																	<div class="errorTxt2"></div>
																</div>
																<div class="input-field col s12 m6">
																	<label for="name" class="force-active">{{__('Member Name') }} *</label>
																	<input id="name" name="name" type="text" class="validate" value="{{ old('name') }}" data-error=".errorTxt3">
																	<div class="errorTxt3"></div>
																</div>

																<div class="clearfix" style="margin: 0px;"></div>
																<div class="input-field col s12 m6" style="margin-top: 0px;margin-bottom: 19px;">
																	<div class="row">
																		<div class="col s12 m3">
																			<p>{{__('Gender') }}</p>
																		</div>
																		<div class="col s12 m5">
																			<p>
																			<label>
																			<input class="validate" required="" aria-required="true" id="gender1" name="gender" type="radio" value="Female">
																			<span>{{__('Female') }}</span>
																			</label>  
																			</p>
																		</div>
																		<div class="col s12 m4">
																			<p>
																				<label>
																				<input class="validate" required="" aria-required="true" id="gender" name="gender" type="radio" checked="" value="Male">
																				<span>{{__('Male') }}</span>
																				</label>
																			</p>
																		</div>
																		<div class="input-field">
																		</div>
																	</div>
																</div>

																<div class="clearfix" ></div>
																<div class="clearfix" style="clear:both"></div>
																<div class="input-field col s12 m6">
																	<label for="mobile" class="force-active">{{__('Mobile Number') }} *</label>
																	<input id="mobile" name="mobile" value="{{ old('mobile') }}" type="text" data-error=".errorTxt5">
																	<div class="errorTxt5"></div>
																</div>
																<div class="input-field col s12 m6">
																	<label for="email" class="force-active">{{__('Email') }} </label>
																	<input id="email" name="email" value="{{ old('email') }}" type="email" data-error=".errorTxt6">
																	<div class="errorTxt6"></div>
																</div>
																<div class="clearfix" ></div>
																<div class="input-field col s12 m6">
																	<input type="text" class="datepicker-normal" id="doe" value="{{ old('doe') }}" name="doe">
																	<label for="doe" class="force-active">{{__('Date of Emp') }}*</label>
																	<div class="errorTxt7"></div>
																</div>
																<div class="col s12 m6">
																	<div class="input-field col s12 m3">
																		<p>
																			<label>
																			<input type="checkbox" id="rejoined"/>
																			<span>{{__('Rejoined') }}</span>
																			</label>
																		</p>
																	</div> 
																	<div class="input-field col s12 m8" style="display:none;margin-left: 20px;" id="member_old_div">
																		<input type="text" name="old_mumber_number" value="{{ old('old_mumber_number') }}" id="old_mumber_number" class="autocomplete">
																		<input type="text" name="old_member_id" value="" id="old_member_id" class="autocomplete hide">
																		<label for="old_mumber_number">{{__('Old Number') }}</label>
																		<span> 
																		</span>
																	</div>
																</div>
																<div class="clearfix" style="clear:both"></div>
																<div class="col s12 m6">
																	<label>{{__('Designation') }}*</label>
																	<select name="designation" id="designation" class="error browser-default selectpicker" data-error=".errorTxt8">
																		<option value="" >{{__('Select') }}</option>
																		@foreach($data['designation_view'] as $key=>$value)
																		<option value="{{$value->id}}">{{$value->designation_name}}</option>
																		@endforeach
																	</select>
																	<div class="input-field">
																		<div class="errorTxt8"></div>
																	</div>
																</div>
																<div class="col s12 m6">
																	<label>Race*</label>
																	<select name="race" id="race" value="{{ old('race') }}" class="error browser-default selectpicker" data-error=".errorTxt9">
																		<option value="" >{{__('Select Race') }}</option>
																		@foreach($data['race_view'] as $key=>$value)
																		<option value="{{$value->id}}">{{$value->race_name}}</option>
																		@endforeach
																	</select>
																	<div class="input-field">
																		<div class="errorTxt9"></div>
																	</div>
																</div>
																<div class="clearfix" ></div>
																<div class="col s12 m6">
																	<label>{{__('Country Name') }}*</label>
																	@php
																	$Defcountry = CommonHelper::DefaultCountry();
																	@endphp
																	<select name="country_id" id="country_id" class="error browser-default selectpicker" data-error=".errorTxt10">
																		<option value="">{{__('Select Country') }}</option>
																		@foreach($data['country_view'] as $value)
																		<option @if($Defcountry==$value->id) selected @endif value="{{$value->id}}">{{$value->country_name}}</option>
																		@endforeach
																	</select>
																	<div class="input-field">
																		<div class="errorTxt10"></div>
																	</div>
																</div>
																@php
																$statelist = CommonHelper::getStateList($Defcountry);
																@endphp
																<div class="col s12 m6">
																	<label>{{__('State Name') }}*</label>
																	<select class="error browser-default selectpicker" id="state_id" name="state_id" data-error=".errorTxt11" aria-required="true" required>
																		<option value="" selected>{{__('State Name') }}</option>
																		@foreach($statelist as $key=>$value)
																			<option value="{{$value->id}}" >{{$value->state_name}}</option>
																		@endforeach
																	</select>
																	<div class="input-field">
																		<div class="errorTxt11"></div>
																	</div>
																</div>
																<div class="clearfix" style="clear:both"></div>
																<div class="col s12 m6">
																	<label>{{__('City Name') }}*</label>
																	<select name="city_id" id="city_id" class="error browser-default selectpicker" aria-required="true" required data-error=".errorTxt12">
																		<option value="">{{__('Select City') }}</option>
																	</select>
																	<div class="input-field">
																		<div class="errorTxt12"></div>
																	</div>
																</div>
																<div class="input-field col s12 m6">
																	<label for="postal_code" class="force-active">{{__('Postal Code') }} *</label>
																	<input id="postal_code" name="postal_code" class="" value="{{ old('postal_code') }}" type="text" data-error=".errorTxt13">
																	<div class="errorTxt13"></div>
																</div>
																<div class="clearfix" ></div>
																<div class="input-field col s12 m6">
																	<label for="address_one" class="force-active">{{__('Address Line 1') }}*</label>
																	<input id="address_one" name="address_one" value="{{ old('address_one') }}" type="text" data-error=".errorTxt14">
																	<div class="errorTxt14"></div>
																</div>
																<div class="input-field col s12 m6">
																	<label for="address_two" class="force-active">{{__('Address Line 2') }}*</label>
																	<input id="address_two" name="address_two" value="{{ old('address_two') }}" type="text" data-error=".errorTxt15">
																	<div class="errorTxt15"></div>
																</div>
																<div class="clearfix" ></div>
																<div class="input-field col s12 m6">
																	<label for="address_three" class="force-active">{{__('Address Line 3') }}</label>
																	<input id="address_three" name="address_three" value="{{ old('address_three') }}" type="text" data-error=".errorTxt16">
																	<div class="errorTxt16"></div>
																</div>
																<div class="col s12 m6">
																	<div class="row">
																		<div class="input-field col s12 m8">
																			<label for="dob" class="force-active">{{__('Date of Birth') }} *</label>
																			<input id="dob" name="dob" value="{{ old('dob') }}" data-reflectage="member_age" value="{{ old('dob') }}" class="datepicker"  type="text"> 
																		</div>
																		<div class="input-field col s12 m4">
																			<label for="member_age" class="force-active">{{__('Age') }}</label>
																			<input type="text" id="member_age" value="{{ old('member_age') != '' ? old('member_age') : 0 }}" readonly >
																		</div>
																	</div>
																</div>
																<div class="clearfix" ></div>
																<div class="input-field col s12 m6">
																	<input type="text" class="datepicker" id="doj" value="{{ old('doj') }}" name="doj" data-error=".errorTxt18">
																	<label for="doj" class="force-active">{{__('Date of Joining') }}*</label>
																	<div class="errorTxt18"></div>
																</div>
																<div class="input-field col s12 m6">
																	<label for="salary" class="force-active">{{__('Salary') }}*</label>
																	<input id="salary" name="salary" value="{{ old('salary') }}" type="text" data-error=".errorTxt19">
																	<div class="errorTxt19"></div>
																</div>
																<div class="clearfix" ></div>
																<div class="input-field col s12 m6">
																	<label for="salary" class="force-active">{{__('Old IC Number') }}</label>
																	<input id="old_ic" name="old_ic" type="text" value="{{ old('old_ic') }}" data-error=".errorTxt20">
																	<div class="errorTxt20"></div>
																</div>
																<div class="input-field col s12 m6">
																	<label for="new_ic" class="force-active">{{__('New IC Number') }}*</label>
																	<input id="new_ic" name="new_ic" type="text" value="{{ old('new_ic') }}" data-error=".errorTxt21">
																	<div class="errorTxt21"></div>
																</div>
																<div class="clearfix" ></div>
																<div class=" col s12 m6 union-data ">
																	<label>{{__('Company Name') }}*</label>
																	<select name="company_id" id="company" class="error browser-default selectpicker" data-error=".errorTxt22" required >
																		<option value="">{{__('Select Company') }}</option>
																		@foreach($companylist as $value)
																		<option @if($companyid==$value->id) selected @endif value="{{$value->id}}">{{$value->company_name}}</option>
																		@endforeach
																	</select>
																	<div class="input-field">
																		<div class="errorTxt22"></div>
																	</div>
																</div>

																<div class="col s12 m6 union-data ">
																	<label>{{__('Company Branch Name') }}*</label>
																	<select name="branch_id" id="branch" class="error browser-default selectpicker" data-error=".errorTxt23" required >
																		<option value="">{{__('Select Branch') }}</option>
																		@foreach($branchlist as $branch)
																		<option @if($branchid==$branch->id) selected @endif value="{{$branch->id}}">{{$branch->branch_name}}</option>
																		@endforeach
																	</select>
																	<div class="input-field">
																		<div class="errorTxt23"></div>
																	</div>
																</div>
																<div class="clearfix" ></div>	
																<div class="col s12 m6">
																<label>{{__('Levy') }}</label>
																  <select name="levy" id="levy" class="error browser-default selectpicker" >
																		<option value="">{{__('Select levy') }}</option>
																		<option value="Not Applicable">N/A</option>
																		<option value="Yes">Yes</option>
																		<option value="NO">No</option>
																	</select>
																</div>
																<div class="input-field col s12 m6">
																<input id="levy_amount" name="levy_amount" type="text">
																	<label for="levy_amount" class="force-active">{{__('Levy Amount') }} </label>
																	
																</div>
																<div class="clearfix" ></div>

																<div class="col s12 m6">
																<label>{{__('TDF') }}</label>
																  <select name="tdf" id="tdf" class="error browser-default selectpicker">
																		<option value="">{{__('Select TDF') }}</option>
																		<option value="Not Applicable">N/A</option>
																		<option value="Yes">Yes</option>
																		<option value="NO">No</option>
																	</select>
																</div>
																<div class="input-field col s12 m6">
																<input id="tdf_amount" name="tdf_amount" type="text">
																	<label for="tdf_amount" class="force-active">{{__('TDF Amount') }} </label>
																</div>
																<div class="clearfix" ></div>
																<div class="input-field col s12 m6">
																	<label for="employee_id" class="force-active">{{__('Employee ID') }}</label>
																	<input id="employee_id" name="employee_id" value="{{ old('employee_id') }}" type="text">
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
																	
																</div>
																
															</div>
														
													</div><!-- /.col- -->
												</fieldset>
											 
												
												<fieldset>
													<div class="col-sm-8 col-sm-offset-1">
														<div class="row">
															<div id="moredata" class="moredata hide">
																
																		<div class="sub-headings"> {{__('Nominee Details') }}</div>
																		<div class="">
																			<div id="nominee_add_section" class="row" style="margin-left: 0px;">
																				<div class="input-field col s12 m4">
																					<label for="nominee_name" class="force-active">Nominee name* </label>
																					<input id="nominee_name" name="nominee_name" value=""  type="text">
																				</div>
																				<div class="col s12 m4">
																					<div class="row">
																						<div class="input-field col s12 m8">
																							<label for="nominee_dob" class="force-active">DOB *</label>
																							<input id="nominee_dob" name="nominee_dob" data-reflectage="nominee_age" value="" class="datepicker"  type="text"> 
																						</div>
																						<div class="input-field col s12 m4">
																							<label for="nominee_dob" class="force-active">Age</label>
																							<input type="text" id="nominee_age" value="0" readonly >
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
																				<div class="col s12 m4">
																					<label>Relationship*</label>
																					<select name="relationship" id="relationship" data-error=".errorTxt31"  class="error browser-default selectpicker">
																						<option value="" selected>State Relationship</option>
																						@foreach($data['relationship_view'] as $key=>$value)
																						<option value="{{$value->id}}" data-relationshipname="{{$value->relation_name}}" >{{$value->relation_name}}</option>
																						@endforeach
																					</select>
																					<div class="input-field">
																						<div class="errorTxt31"></div>
																					</div>
																				</div>
																				<div class="input-field col s12 m4">
																					<label for="nric_n" class="force-active">NRIC-N *</label>
																					<input id="nric_n" name="nric_n" value=""  type="text">
																				</div>
																				<div class="input-field col s12 m4">
																					<label for="nric_o" class="force-active">NRIC-O </label>
																					<input id="nric_o" name="nric_o" value=""  type="text">
																				</div>
																				<div class="clearfix"> </div>
																				<div class="col s12 m4">
																					<label>Country Name*</label>
																					@php
																					$Defcountry = CommonHelper::DefaultCountry();
																					@endphp
																					<select name="nominee_country_id" id="nominee_country_id"  class="error browser-default selectpicker">
																						<option value="">Select Country</option>
																						@foreach($data['country_view'] as $value)
																							<option value="{{$value->id}}" @isset($row) @php if($value->id
																								== $row->country_id) { echo "selected";} @endphp @endisset @if($Defcountry==$value->id) selected @endif
																								>{{$value->country_name}}</option>
																						@endforeach
																					</select>
																					<div class="input-field">
																						<div class="errorTxt35"></div>
																					</div>
																				</div>
																				<div class="col s12 m4">
																					<label>State Name*</label>
																					<select name="nominee_state_id" id="nominee_state_id"  class="error browser-default selectpicker">
																						
																						<option value="" selected>{{__('State Name') }}</option>
																							@foreach($statelist as $key=>$value)
																								<option value="{{$value->id}}" >{{$value->state_name}}</option>
																							@endforeach
																					</select>
																					<div class="input-field">
																						<div class="errorTxt36"></div>
																					</div>
																				</div>
																				<div class="col s12 m4">
																					<label>City Name*</label>
																					<select name="nominee_city_id" id="nominee_city_id"  class="error browser-default selectpicker">
																						<option value="">Select</option>
																					</select>
																					<div class="input-field">
																						<div class="errorTxt36"></div>
																					</div>
																				</div>
																				<div class="clearfix"> </div>
																				<div class="">
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
																				</div>
																				<div class="clearfix"> </div>
																				<div class="">
																					<div class="input-field col s12 m4">
																						<label for="nominee_address_three" class="force-active">Address Line 3</label>
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
																				</div>
																				<div class="clearfix"> </div>
																				<div class="col s12 m12">
																					<button class="btn waves-effect waves-light right submit" id="add_nominee" type="button" name="add_nominee">Add Nominee
																					</button>
																				</div>
																			</div>
																			<div class="row">
																				<div class="col s12" style="margin-left: 5px;">
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
																						</tbody>
																					</table>
																					@php
																					{{ $sl = 0; }}
																					@endphp
																					<input id="nominee_row_id" class="hide" name="nominee_row_id" value="{{ $sl }}"  type="text">
																				</div>
																			</div>
																		</div>
																		<br>
																		<br>
																		<div class="sub-headings">{{__('Guardian Details') }}</div>
																		<div class="">
																			<div class="row" style="margin-left: 0px;">
																				<div class="input-field col s12 m4">
																					<label for="guardian_name" class="force-active">Guardian name* </label>
																					<input id="guardian_name" name="guardian_name" value=""  type="text" >
																				</div>
																				<div class="col s12 m4">
																					<div class="row">
																						<div class="input-field col s12 m8">
																							
																								<label for="gaurdian_dob" class="force-active">DOB *</label>
																								<input id="gaurdian_dob" name="gaurdian_dob" data-reflectage="gaurdian_age" value="" class="datepicker"  type="text"> 	
																							
																						</div>
																						<div class="input-field col s12 m4">
																							<label for="gaurdian_age" class="force-active">Age</label>
																							<span> 
																							<input type="text" id="gaurdian_age" value="0" readonly >
																							</span>
																						</div>
																					</div>
																				</div>
																				<div class="col s12 m4">
																					<label for="guardian_sex" class="force-active">SEX *</label>
																					<select name="guardian_sex" id="guardian_sex" class="error browser-default selectpicker">
																						<option value="">Select</option>
																						<option value="Male" >Male</option>
																						<option value="Female" >Female</option>
																					</select>
																				</div>
																				<div class="clearfix"> </div>
																				<div class="col s12 m4">
																					<label class="force-active">Relationship*</label>
																					<select name="g_relationship_id" id="g_relationship" data-error=".errorTxt31"  class="error browser-default selectpicker">
																						<option value="">Select</option>
																						@foreach($data['relationship_view'] as $key=>$value)
																						<option value="{{$value->id}}" >{{$value->relation_name}}</option>
																						@endforeach
																					</select>
																					<div class="input-field">
																						<div class="errorTxt31"></div>
																					</div>
																				</div>
																				<div class="input-field col s12 m4">
																					<label for="nric_n_guardian" class="force-active">NRIC-N *</label>
																					<input id="nric_n_guardian" name="nric_n_guardian" value=""  type="text">
																				</div>
																				<div class="input-field col s12 m4">
																					<label for="nric_o_guardian" class="force-active">NRIC-O </label>
																					<input id="nric_o_guardian" name="nric_o_guardian" value=""  type="text">
																				</div>
																				<div class="clearfix"> </div>
																				<div class="col s12 m4">
																					<label>Country Name*</label>
																					@php
																					$Defcountry = CommonHelper::DefaultCountry();
																					@endphp
																					<select name="guardian_country_id" id="guardian_country_id"  class="error browser-default selectpicker">
																						<option value="">Select</option>
																						@foreach($data['country_view'] as $value)
																							<option value="{{$value->id}}" @isset($row) @php if($value->id
																								== $row->country_id) { echo "selected";} @endphp @endisset @if($Defcountry==$value->id) selected @endif
																								>{{$value->country_name}}</option>
																						@endforeach
																					</select>
																					<div class="input-field">
																						<div class="errorTxt35"></div>
																					</div>
																				</div>
																				<div class="col s12 m4">
																					<label>State Name*</label>
																					<select name="guardian_state_id" id="guardian_state_id"  class="error browser-default selectpicker">
																						
																						<option value="" selected>{{__('State Name') }}</option>
																							@foreach($statelist as $key=>$value)
																								<option value="{{$value->id}}" >{{$value->state_name}}</option>
																							@endforeach
																					</select>
																					<div class="input-field">
																						<div class="errorTxt36"></div>
																					</div>
																				</div>
																				<div class="col s12 m4">
																					<label>City Name*</label>
																					<select name="guardian_city_id" id="guardian_city_id"  class="error browser-default selectpicker">
																						<option value="" >Select</option>
																					</select>
																					<div class="input-field">
																						<div class="errorTxt36"></div>
																					</div>
																				</div>
																				<div class="clearfix"> </div>
																				<div class="input-field col s12 m4">
																					<label for="guardian_postal_code" class="force-active">Postal code*</label>
																					<input id="guardian_postal_code" name="guardian_postal_code" type="text" value="" >
																				</div>
																				<div class="input-field col s12 m4">
																					<label for="guardian_address_one" class="force-active">Address Line 1*</label>
																					<input id="guardian_address_one" name="guardian_address_one" type="text" value="" >
																				</div>
																				<div class="input-field col s12 m4">
																					<label for="guardian_address_two" class="force-active">Address Line 2*</label>
																					<input id="guardian_address_two" name="guardian_address_two" type="text" value="" >
																				</div>
																				<div class="clearfix"> </div>
																				<div class="input-field col s12 m4">
																					<label for="guardian_address_three" class="force-active">Address Line 3</label>
																					<input id="guardian_address_three" name="guardian_address_three" type="text" value="" >
																				</div>
																				<div class="input-field col s12 m4">
																					<label for="guardian_mobile" class="force-active">Mobile No*</label>
																					<input id="guardian_mobile" name="guardian_mobile" type="text" value="" >
																				</div>
																				<div class="input-field col s12 m4">
																					<label for="guardian_phone" class="force-active">Phone No</label>
																					<input id="guardian_phone" name="guardian_phone" type="text" value="" >
																				</div>
																				<div class="clearfix"> </div>
																			</div>
																		</div>
																	
																		<div class="hide" >{{__('Fee Details') }}</div>
																		
																		<div class="hide">
																			<form id="fee_new_form" name="fee_new_form">
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
																					</button>
																				</div>
																			</div>
																			</form>
																			</br>
																			<div class="row">
																				<div class="col s12">
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
																							<input id="fee_row_id" class="hide" name="fee_row_id" value="{{ $sl }}"  type="text">
																						</tbody>
																					</table>
																				</div>
																			</div>
																		</div>
																	
															</div>
														
														</div>
													</div><!-- /.col- -->
												</fieldset>
												<br>
												<br>
												<br>
											 	
											</form>
    </div>
    <div class="modal-footer">
    	<button onclick="myShowMoreFunction()" class="myBtn btn waves-effect waves-light gradient-45deg-indigo-purple left submit" id="myBtn">Show more</button>
      <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Agree</a>
    </div>
  </div>
</div>
@endsection
@section('footerSection')
<script src="{{ asset('public/assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/assets/vendors/noUiSlider/nouislider.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/materialize.min.js') }}"></script>
<script src="{{ asset('public/assets/js/scripts/form-elements.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jquery.autocomplete.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/js/jquery.steps.js') }}"></script>
<script src="{{ asset('public/assets/js/datepicker.js') }}"></script>
@endsection
@section('footerSecondSection')
<script>
   	var csrfToken = $('[name="csrf-token"]').attr('content');

    setInterval(refreshToken, 300000); // 1 hour  120000

    function refreshToken(){
    	$.ajax({
		    url: 'refresh-csrf',
		    type: 'GET',
		    success: function(data){ 
		       csrfToken = data;
		    },
		    "error": function (jqXHR, textStatus, errorThrown) {
                if(jqXHR.status==419){
                    alert('Your session has expired, please login again');
                    window.location.href = base_url;
                }
            },
		});
        
    }

    //setInterval(refreshToken, 3600000);
	// var form = $("#wizard2").show();

	// form.steps({
	//     headerTag: "h3",
	//     bodyTag: "fieldset",
	//     onStepChanging: function (event, currentIndex, newIndex)
	//     {
	//         // Allways allow previous action even if the current form is not valid!
	//         if (currentIndex > newIndex)
	//         {
	//             return true;
	//         }
	//         // Forbid next action on "Warning" step if the user is to young
	//         if (currentIndex === 1)
	//         {
	//             return true;
	//         }
	//         // Needed in some cases if the user went back (clean up)
	//         if (currentIndex < newIndex)
	//         {
	//             // To remove error styles
	//             form.find(".body:eq(" + newIndex + ") label.error").remove();
	//             form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
	//         }
	//         form.validate().settings.ignore = ":disabled,:hidden";
	//         return form.valid();
	//     },
	//     onFinishing: function (event, currentIndex)
	//     {
	//         form.validate().settings.ignore = ":disabled";
	//         return form.valid();
	//     },
	//     onFinished: function (event, currentIndex)
	//     {
	// 		loader.showLoader();
	// 		$('#wizard2').trigger('submit');
	//         return true;
	//     }
	// }).validate({
	//     rules: {
	//     	member_title:{
 //                required: true,
 //            },
 //          /*  member_number: {
 //                required: true,
 //            },*/
 //            name: {
 //                required: true,
	// 			minlength:3,
 //            },
 //            gender: {
 //                required: true,
 //            },
 //            name: {
 //                required: true,
 //            },
 //            mobile: {
 //                required: true,	
 //            },
 //            email: {
 //                //required: true,
	// 			email:true,
	// 			remote: {
	// 				url: "{{ url(app()->getLocale().'/member_emailexists')}}",
	// 				type: "post",
	// 				data: {
	// 					db_autoid: function() {
	// 						return $("#auto_id").val();
	// 					},
	// 					_token: "{{csrf_token()}}",
	// 					email: $(this).data('email')
	// 				},
	// 				"error": function (jqXHR, textStatus, errorThrown) {
	// 	                if(jqXHR.status==419){
	// 	                    alert('Your session has expired, please login again');
	// 	                    window.location.href = base_url;
	// 	                }
	// 	            },
					
	// 			},
 //            },
 //            doe: {
 //                required: true,
 //            },
 //            designation: {
 //                required: true,
 //            },
 //            race: {
 //                required: true,
 //            },
 //            country: {
 //                required: true,
 //            },
 //            state: {
 //                required: true,
 //            },
	// 		state_id: {
 //                required: true,
 //            },
 //            city: {
 //                required: true,
 //            },
	// 		city_id: {
 //                required: true,
 //            },
 //            postal_code: {
 //                required: true,
	// 			number: true,
	// 			minlength:5,
	// 			maxlength:8,
 //            },
 //            address_one: {
 //                required:true,
 //            },
	// 		address_two: {
 //                required:true,
 //            },
	// 		// address_three: {
 //   //              required:true,
 //   //          },
 //            dob: {
 //                required:true,
 //            }, 
	// 		doj: {
 //                required:true,
 //            },
 //            new_ic: {
 //                required:true,
 //                minlength: 3,
	// 			maxlength: 20,
	// 			remote: {
	// 				url: "{{ url(app()->getLocale().'/member_newicexists')}}",
	// 				type: "post",
	// 				data: {
	// 					db_autoid: function() {
	// 						return $("#auto_id").val();
	// 					},
	// 					old_db_autoid: function() {
	// 						return $("#old_member_id").val();
	// 					},
	// 					_token: "{{csrf_token()}}",
	// 					new_ic: $(this).data('new_ic')
	// 				},
	// 				"error": function (jqXHR, textStatus, errorThrown) {
	// 	                if(jqXHR.status==419){
	// 	                    alert('Your session has expired, please login again');
	// 	                    window.location.href = base_url;
	// 	                }
	// 	            },
					
 //            	},
				
 //            },
 //            salary: {
	// 			required: true,
	// 			digits: true,
 //            },
 //            branch: {
 //                required: true,
 //            },
 //            uname: {
 //                required: true,
 //                minlength: 5
 //            },
 //            country_name: {
 //                required: true,
 //            },
 //            state_name: {
 //                required: true,
 //            },
 //            country_id: "required",
 //            cemail: {
 //                required: true,
 //                email: true
 //            },
 //            city_name : {
 //            required: true,
 //            },
 //            designation_name : {
 //            required: true,
	// 		},
	// 		levy_amount: {
	// 			digits : true,
	// 		},
	// 		tdf_amount: {
 //                digits: true,
 //            },
	//     },
	//    	messages: {
	// 		  member_title: {
 //                required: "Please Enter Your Title ",
                
 //            },
 //            /*member_number: {
 //                required: "Please Enter Member NUmber",
                
 //            },*/
 //            name: {
 //                required: "Please Enter Your Name",
                
 //            },
 //            gender: {
 //                required: "Please choose Gender",
 //            },
 //            mobile: {
 //                required: "Please Enter your Number",
                
 //            },
 //            email: {
 //                //required: "Please enter valid email",
	// 			remote: '{{__("Email Already exists") }}',
 //            },
 //            designation: {
 //                required: "Please choose  your Designation",
 //            },
            
 //            race: {
 //                required: "Please Choose your Race ",
 //            },
 //            country: {
 //                required:"Please choose  your Country",
 //            },
 //            state: {
 //                required:"Please choose  your State",
 //            },
 //            city: {
 //                required:"Please choose  your city",
 //            },
 //            address_one: {
 //                required:"Please Enter your Address",
 //            },
 //            dob: {
 //                required:"Please choose DOB",
 //            },
 //            new_ic: {
	// 			required:"Please Enter New Ic Number",
	// 			remote: '{{__("IC Already Exists") }}',
 //            },
 //            salary: {
	// 			required:"Please Enter salary",
	// 			digits: "{{__("Please Enter numbers only") }}",
	// 		},
	// 		levy_amount: {
 //                digits: "{{__("Please Enter numbers only") }}",
	// 		},
	// 		tdf_amount: {
 //                digits: "{{__("Please Enter numbers only") }}",
 //            },
 //            branch: {
 //                required:"Please Choose Company Name",
 //            },
 //            uname: {
 //                required: "Enter a username",
 //                minlength: "Enter at least 5 characters"
 //            },
 //            country_name: {
 //                required: "Enter a Country Name",
 //            },
 //            state_name: {
 //                required: "Enter a State Name",
 //            },
 //            city_name: {
 //                required: "Enter a City Name",
 //            },
 //            designation_name: {
 //                required: "Enter a Designation Name",
 //            },
	// 		guardian_name: {
 //                required: "Enter a Guardian Name",
 //            },
	// 		employee_id: {
 //                required: "Enter a Employee ID",
 //            },
	// 	},
	// 	errorElement: 'div',
	// 	errorPlacement: function (error, element) {
	// 		var placement = $(element).data('error');
	// 		if (placement) {
	// 			$(placement).append(error)
	// 		} else {
	// 			error.insertAfter(element);
	// 		}
	// 	},
	// });
	$("#old_mumber_number").devbridgeAutocomplete({
        //lookup: countries,
        serviceUrl: "{{ URL::to('/get-oldmember-list') }}?serachkey="+ $("#old_mumber_number").val(),
        type:'GET',
        //callback just to show it's working
        onSelect: function (suggestion) {
			 $("#old_mumber_number").val(suggestion.number);
			 $("#old_member_id").val(suggestion.auto_id);
			 $("#name").val(suggestion.membername);
			 $("#gender1").prop('checked',suggestion.gender=='Female' ? true : false);
			 $("#gender").prop('checked',suggestion.gender=='Male' ? true : false);
			 $("#address_one").val(suggestion.address_one);
			 $("#address_three").val(suggestion.address_three);
			 $("#address_two").val(suggestion.address_two);
			 $("#branch").val(suggestion.branch_id).trigger("change");
			 $("#city_id").val(suggestion.city_id).trigger("change");
			 $("#company").val(suggestion.company_id).trigger("change");
			 $("#designation").val(suggestion.designation_id).trigger("change");
			 $("#dob").val(suggestion.dob);
			 $("#doe").val(suggestion.doe);
			 $("#doj").val(suggestion.doj);
			 $("#email").val(suggestion.email);
			 $("#employee_id").val(suggestion.employee_id);
			 //$("#old_mumber_number").val(suggestion.gender);
			 $("#levy").val(suggestion.levy).trigger("change");
			 $("#levy_amount").val(suggestion.levy_amount);
			 $("#member_title").val(suggestion.member_title_id).trigger("change");
			 $("#mobile").val(suggestion.mobile);
			 $("#new_ic").val(suggestion.new_ic);
			 $("#old_ic").val(suggestion.old_ic);
			 $("#postal_code").val(suggestion.postal_code);
			 $("#race").val(suggestion.race_id).trigger("change");
			 $("#salary").val(suggestion.salary);
			 $("#state_id").val(suggestion.state_id).trigger("change");
			 $("#tdf").val(suggestion.tdf).trigger("change");
			 $("#tdf_amount").val(suggestion.tdf_amount);
			 $('#dob').trigger('change');
        },
        showNoSuggestionNotice: true,
        noSuggestionNotice: 'Sorry, no matching results',
		onSearchComplete: function (query, suggestions) {
			if(!suggestions.length){
				//$("#old_mumber_number").val('');
			}
		}
    });
	$(document.body).on('click', '.autocomplete-no-suggestion' ,function(){
		$("#old_mumber_number").val('');
	});
	$(document).on('submit','form#fee_new_form',function(){
		$("#new_fee_id").val("");
	});
	$('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        autoHide: true,
    });
    $('.datepicker-normal').datepicker({
        format: 'dd/mm/yyyy',
        autoHide: true,
    });

    function myShowMoreFunction(){
    	if ($('#moredata,.moredata').hasClass("hide")) {
    		$('#moredata,.moredata').removeClass("hide");
    		$("#myBtn,.myBtn").html( 'Show less');
    	}else{
    		$('#moredata,.moredata').addClass("hide");
    		$("#myBtn,.myBtn").html('Show more');
    	}
    }
</script>
@include('membership.member_common_script')
@endsection