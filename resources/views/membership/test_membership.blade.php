@extends($data['user_type']==1 ? 'layouts.admin' : 'layouts.new-member')
@section('headSection')
	@include('membership.member_common_styles')
@endsection
@section('headSecondSection')
<style>
ul.stepper .step .step-title:hover {
     background-color: #fff !important;
}

</style>
<link rel="stylesheet" type="text/css" href="{{ asset('public/css/wizard-app.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/css/wizard-theme.css') }}">
<link class="rtl_switch_page_css" href="{{ asset('public/css/steps.css') }}" rel="stylesheet" type="text/css">
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
								<div class="col s12">
									<div class="card theme-mda">
										<div class="card-content pb-0">
											<div class="card-header">
												<h4 class="card-title">{{__('New Membership') }}
													@if($data['user_type']!=1)
													<a class="btn waves-effect waves-light right" href="{{ route('login', app()->getLocale())  }}">{{__('Login') }}</a>
													@endif
												</h4>
												
											</div>
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
														
														if($user_role =='union'){
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
												<h3>Member Details</h3>
												<fieldset>	 		
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
																<div class="input-field col s12 m6 {{ $member_number_hide }}">
																	<input id="member_number" name="member_number" value="{{ CommonHelper::get_auto_member_number() }}" required type="text" class="validate" {{ $member_number_readonly }} data-error=".errorTxt2">
																	<label for="member_number" class="force-active">{{__('Member Number') }} *</label>
																	<div class="errorTxt2"></div>
																</div>
																<div class="clearfix" ></div>
																<div class="input-field col s12 m6">
																	<label for="name" class="force-active">{{__('Member Name') }} *</label>
																	<input id="name" name="name" type="text" class="validate" value="{{ old('name') }}" data-error=".errorTxt3">
																	<div class="errorTxt3"></div>
																</div>
																<div class="input-field col s12 m6">
																	<div class="col s12 row">
																		<div class="col s12 m4">
																			<p>{{__('Gender') }}</p>
																		</div>
																		<div class="col s12 m4">
																			<label>
																			<input class="validate" required="" aria-required="true" id="gender" name="gender" type="radio" value="Female">
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
																<div class="clearfix" style="clear:both"></div>
																<div class="input-field col s12 m6">
																	<label for="mobile" class="force-active">{{__('Mobile Number') }} *</label>
																	<input id="mobile" name="mobile" value="{{ old('mobile') }}" type="text" data-error=".errorTxt5">
																	<div class="errorTxt5"></div>
																</div>
																<div class="input-field col s12 m6">
																	<label for="email" class="force-active">{{__('Email') }} *</label>
																	<input id="email" name="email" value="{{ old('email') }}" type="email" data-error=".errorTxt6">
																	<div class="errorTxt6"></div>
																</div>
																<div class="clearfix" ></div>
																<div class="input-field col s12 m6">
																	<input type="text" class="datepicker" id="doe" value="{{ old('doe') }}" name="doe">
																	<label for="doe" class="force-active">{{__('Date of Emp') }}*</label>
																	<div class="errorTxt7"></div>
																</div>
																<div class="col s12 m6">
																	<div class="input-field col s12 m6">
																		<p>
																			<label>
																			<input type="checkbox" id="rejoined"/>
																			<span>{{__('Rejoined') }}</span>
																			</label>
																		</p>
																	</div>
																	<div class="input-field col s12 m6" id="member_old_div">
																		<input type="text" name="old_mumber_number" value="{{ old('old_mumber_number') }}" id="old_mumber_number" class="autocomplete">
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
																	<input id="postal_code" name="postal_code" class="padding-top-6" value="{{ old('postal_code') }}" type="text" data-error=".errorTxt13">
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
																	<label for="address_three" class="force-active">{{__('Address Line 3') }}*</label>
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
														<p>(*) Mandatory</p>
													</div><!-- /.col- -->
												</fieldset>
											 
												<h3>Additional Details</h3>
												<fieldset>
													<div class="col-sm-8 col-sm-offset-1">
														<div class="row">
															<div class="col s12">
																<ul class="collapsible collapsible-accordion" data-collapsible="accordion">
																	<li class="active">
																		<div class="collapsible-header gradient-45deg-indigo-purple white-text" ><i class="material-icons">blur_circular</i> {{__('Fee Details') }}</div>
																		
																		<div class="collapsible-body ">
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
																						<div class="input-field col s12 m8">
																							<label for="nominee_dob">DOB *</label>
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
																					<label for="nric_n">NRIC-N *</label>
																					<input id="nric_n" name="nric_n" value=""  type="text">
																				</div>
																				<div class="input-field col s12 m4">
																					<label for="nric_o">NRIC-O </label>
																					<input id="nric_o" name="nric_o" value=""  type="text">
																				</div>
																				<div class="clearfix"> </div>
																				<div class="col s12 m4">
																					<label>Country Name*</label>
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
																					<label>State Name*</label>
																					<select name="nominee_state_id" id="nominee_state_id"  class="error browser-default selectpicker">
																						<option value="">Select</option>
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
																				<div class="input-field col s12 m4">
																					<label for="nominee_postal_code">Postal code*</label>
																					<input id="nominee_postal_code" name="nominee_postal_code" type="text" value="" >
																				</div>
																				<div class="input-field col s12 m4">
																					<label for="nominee_address_one">Address Line 1*</label>
																					<input id="nominee_address_one" name="nominee_address_one" type="text" value="" >
																				</div>
																				<div class="input-field col s12 m4">
																					<label for="nominee_address_two">Address Line 2*</label>
																					<input id="nominee_address_two" name="nominee_address_two" type="text" value="" >
																				</div>
																				<div class="clearfix"> </div>
																				<div class="input-field col s12 m4">
																					<label for="nominee_address_three">Address Line 3*</label>
																					<input id="nominee_address_three" name="nominee_address_three" type="text" value="" >
																				</div>
																				<div class="input-field col s12 m4">
																					<label for="nominee_mobile">Mobile No*</label>
																					<input id="nominee_mobile" name="nominee_mobile" type="text" value="" >
																				</div>
																				<div class="input-field col s12 m4">
																					<label for="nominee_phone">Phone No</label>
																					<input id="nominee_phone" name="nominee_phone" type="text" value="" >
																				</div>
																				<div class="clearfix"> </div>
																				<div class="col s12 m12">
																					<button class="btn waves-effect waves-light right submit" id="add_nominee" type="button" name="add_nominee">Add Nominee
																					</button>
																				</div>
																			</div>
																			<div class="row">
																				<div class="col s12">
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
																	</li>
																	<li>
																		<div class="collapsible-header gradient-45deg-indigo-purple white-text"><i class="material-icons">filter_center_focus</i>{{__('Guardian Details') }}</div>
																		<div class="collapsible-body">
																			<div class="row">
																				<div class="input-field col s12 m4">
																					<label for="guardian_name">Guardian name* </label>
																					<input id="guardian_name" name="guardian_name" value=""  type="text" >
																				</div>
																				<div class="col s12 m4">
																					<div class="row">
																						<div class=" col s12 m8">
																							<p>
																								<label for="gaurdian_dob">DOB *</label>
																								<input id="gaurdian_dob" name="gaurdian_dob" data-reflectage="gaurdian_age" value="" class="datepicker"  type="text"> 	
																							</p>
																						</div>
																						<div class="col s12 m4">
																							<label for="gaurdian_age" class="force-active">Age</label>
																							<span> 
																							<input type="text" id="gaurdian_age" value="0" readonly >
																							</span>
																						</div>
																					</div>
																				</div>
																				<div class="col s12 m4">
																					<label for="guardian_sex">SEX *</label>
																					<select name="guardian_sex" id="guardian_sex" class="error browser-default selectpicker">
																						<option value="">Select</option>
																						<option value="Male" >Male</option>
																						<option value="Female" >Female</option>
																					</select>
																				</div>
																				<div class="clearfix"> </div>
																				<div class="col s12 m4">
																					<label>Relationship*</label>
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
																					<label for="nric_n_guardian">NRIC-N *</label>
																					<input id="nric_n_guardian" name="nric_n_guardian" value=""  type="text">
																				</div>
																				<div class="input-field col s12 m4">
																					<label for="nric_o_guardian">NRIC-O </label>
																					<input id="nric_o_guardian" name="nric_o_guardian" value=""  type="text">
																				</div>
																				<div class="clearfix"> </div>
																				<div class="col s12 m4">
																					<label>Country Name*</label>
																					<select name="guardian_country_id" id="guardian_country_id"  class="error browser-default selectpicker">
																						<option value="">Select</option>
																						@foreach($data['country_view'] as $value)
																						<option value="{{$value->id}}" >{{$value->country_name}}</option>
																						@endforeach
																					</select>
																					<div class="input-field">
																						<div class="errorTxt35"></div>
																					</div>
																				</div>
																				<div class="col s12 m4">
																					<label>State Name*</label>
																					<select name="guardian_state_id" id="guardian_state_id"  class="error browser-default selectpicker">
																						<option value="" >Select</option>
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
																					<label for="guardian_postal_code">Postal code*</label>
																					<input id="guardian_postal_code" name="guardian_postal_code" type="text" value="" >
																				</div>
																				<div class="input-field col s12 m4">
																					<label for="guardian_address_one">Address Line 1*</label>
																					<input id="guardian_address_one" name="guardian_address_one" type="text" value="" >
																				</div>
																				<div class="input-field col s12 m4">
																					<label for="guardian_address_two">Address Line 2*</label>
																					<input id="guardian_address_two" name="guardian_address_two" type="text" value="" >
																				</div>
																				<div class="clearfix"> </div>
																				<div class="input-field col s12 m4">
																					<label for="guardian_address_three">Address Line 3*</label>
																					<input id="guardian_address_three" name="guardian_address_three" type="text" value="" >
																				</div>
																				<div class="input-field col s12 m4">
																					<label for="guardian_mobile">Mobile No*</label>
																					<input id="guardian_mobile" name="guardian_mobile" type="text" value="" >
																				</div>
																				<div class="input-field col s12 m4">
																					<label for="guardian_phone">Phone No</label>
																					<input id="guardian_phone" name="guardian_phone" type="text" value="" >
																				</div>
																				<div class="clearfix"> </div>
																			</div>
																		</div>
																	</li>
																</ul>
															</div>
														
														</div>
													</div><!-- /.col- -->
												</fieldset>
											 
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
</div>
@endsection
@section('footerSection')
<script src="{{ asset('public/assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/assets/vendors/noUiSlider/nouislider.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/materialize.min.js') }}"></script>
<script src="{{ asset('public/assets/js/scripts/form-elements.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jquery.autocomplete.min.js') }}" type="text/javascript"></script>
<script src="http://materialdesignadmin.com/dist/js/plugins/wizard/jquery.steps.min.js"></script>
<!--script src = "{{ asset('public/assets/js/materialize.min.js') }}" type="text/javascript"></script-->
@endsection
@section('footerSecondSection')
<script>
    $("#old_mumber_number").devbridgeAutocomplete({
        //lookup: countries,
        serviceUrl: "{{ URL::to('/get-oldmember-list') }}?serachkey="+ $("#old_mumber_number").val(),
        type:'GET',
        //callback just to show it's working
        onSelect: function (suggestion) {
			 $("#old_mumber_number").val(suggestion.number);
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
	
	var form = $("#wizard2").show();
 
	form.steps({
	    headerTag: "h3",
	    bodyTag: "fieldset",
	    onStepChanging: function (event, currentIndex, newIndex)
	    {
	        // Allways allow previous action even if the current form is not valid!
	        if (currentIndex > newIndex)
	        {
	            return true;
	        }
	        // Forbid next action on "Warning" step if the user is to young
	        if (currentIndex === 1)
	        {
	            return true;
	        }
	        // Needed in some cases if the user went back (clean up)
	        if (currentIndex < newIndex)
	        {
	            // To remove error styles
	            form.find(".body:eq(" + newIndex + ") label.error").remove();
	            form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
	        }
	        form.validate().settings.ignore = ":disabled,:hidden";
	        return form.valid();
	    },
	    onFinishing: function (event, currentIndex)
	    {
	        form.validate().settings.ignore = ":disabled";
	        return form.valid();
	    },
	    onFinished: function (event, currentIndex)
	    {
			$('#wizard2').trigger('submit');
	       return true;
	    }
	}).validate({
	    rules: {
	    	member_title:{
                required: true,
            },
            member_number: {
                required: true,
            },
            name: {
                required: true,
				minlength:3,
            },
            gender: {
                required: true,
            },
            name: {
                required: true,
            },
            mobile: {
                required: true,	
            },
            email: {
                required: true,
				email:true,
				remote: {
					url: "{{ url(app()->getLocale().'/member_emailexists')}}",
					data: {
						db_autoid: function() {
							return $("#auto_id").val();
						},
						_token: "{{csrf_token()}}",
						email: $(this).data('email')
					},
					type: "post",
				},
            },
            doe: {
                required: true,
            },
            designation: {
                required: true,
            },
            race: {
                required: true,
            },
            country: {
                required: true,
            },
            state: {
                required: true,
            },
			state_id: {
                required: true,
            },
            city: {
                required: true,
            },
			city_id: {
                required: true,
            },
            postal_code: {
                required: true,
				number: true,
				minlength:5,
				maxlength:8,
            },
            address_one: {
                required:true,
            },
			address_two: {
                required:true,
            },
			address_three: {
                required:true,
            },
            dob: {
                required:true,
            }, 
			doj: {
                required:true,
            },
            new_ic: {
                required:true,
                minlength: 3,
                maxlength: 20,
            },
            salary: {
                required: true,
            },
            branch: {
                required: true,
            },
            uname: {
                required: true,
                minlength: 5
            },
            country_name: {
                required: true,
            },
            state_name: {
                required: true,
            },
            country_id: "required",
            cemail: {
                required: true,
                email: true
            },
            city_name : {
            required: true,
            },
            designation_name : {
            required: true,
            },  
	    },
	   	messages: {
			  member_title: {
                required: "Please Enter Your Title ",
                
            },
            member_number: {
                required: "Please Enter Member NUmber",
                
            },
            name: {
                required: "Please Enter Your Name",
                
            },
            gender: {
                required: "Please choose Gender",
            },
            mobile: {
                required: "Please Enter your Number",
                
            },
            email: {
                required: "Please enter valid email",
				remote: '{{__("Email Already exists") }}',
            },
            designation: {
                required: "Please choose  your Designation",
            },
            
            race: {
                required: "Please Choose your Race ",
            },
            country: {
                required:"Please choose  your Country",
            },
            state: {
                required:"Please choose  your State",
            },
            city: {
                required:"Please choose  your city",
            },
            address_one: {
                required:"Please Enter your Address",
            },
            dob: {
                required:"Please choose DOB",
            },
            new_ic: {
                required:"Please Enter New Ic Number",
            },
            salary: {
                required:"Please Enter salary Name",
            },
            branch: {
                required:"Please Choose Company Name",
            },
            uname: {
                required: "Enter a username",
                minlength: "Enter at least 5 characters"
            },
            country_name: {
                required: "Enter a Country Name",
            },
            state_name: {
                required: "Enter a State Name",
            },
            city_name: {
                required: "Enter a City Name",
            },
            designation_name: {
                required: "Enter a Designation Name",
            },
			guardian_name: {
                required: "Enter a Guardian Name",
            },
			employee_id: {
                required: "Enter a Employee ID",
            },
		},
		errorElement: 'div',
		errorPlacement: function (error, element) {
			var placement = $(element).data('error');
			if (placement) {
				$(placement).append(error)
			} else {
				error.insertAfter(element);
			}
		},
	});
</script>
<div id="modal_fee" class="modal" style="width:70%;height: 350px !important;">
	<form class="formValidate" id="fee_formValidate" method="post" action="{{ url('membership_update') }}">
	@csrf
	</br>
	<div class="modal-content">
		<h4>Edit Fee</h4>
		</br>
		<div class="row">
			<div class="col s12 m6">
				<label for="edit_fee_name">Fee name* </label>
				<select name="edit_fee_name" id="edit_fee_name" class="browser-default valid" aria-invalid="false">
					<option value="">Select</option>
				</select>
				<div class="input-field">
					<div class="errorTxt50"></div>
				</div>  
				<input id="edit_fee_auto_id" name="edit_fee_auto_id" class='hide' value=""  type="text" >
				<input id="edit_fee_row_id" name="edit_fee_row_id" class='hide' value=""  type="text" >
			</div>
			<div class="input-field col s12 m6">
				
				<input id="edit_fee_amount" name="edit_fee_amount" class="" value=" "  type="text">
				<label for="edit_fee_amount">Fee amount *</label>
			</div>
			<div class="clearfix"> </div>
			
		</div>
	</div>
	<div class="modal-footer">
		<div class="col s12 m12">
			<button class="btn waves-effect waves-light purple right submit" id="update_fee" type="submit" name="update_fee">Update Fee</button>
			<a href="#!" class="modal-action modal-close waves-effect waves-green btn left ">Close</a> 
		</div>
	</div>
	</form>
</div>
<div id="modal_nominee" class="modal" style="width:70%;height: 700px !important;">
	<form class="formValidate" id="nominee_formValidate" method="post" action="{{ url('membership_update') }}">
	@csrf
	<div class="modal-content">
		<h4>Edit Nominee</h4>
		<div class="row">
			<div class="input-field col s12 m4">
			   
				<input id="edit_nominee_auto_id" name="edit_nominee_auto_id" class='hide' value=""  type="text" >
				<input id="edit_nominee_row_id" name="edit_nominee_auto_id" class='hide' value=""  type="text" >
				<input id="edit_nominee_name" name="edit_nominee_name" value=" "  type="text" >
				
				<label for="edit_nominee_name">Nominee name* </label>
			</div>
			<div class="col s12 m4 row">
				<div class=" col s12 m8">
					<label for="edit_nominee_dob">DOB *</label>
					<input id="edit_nominee_dob" name="edit_nominee_dob" data-reflectage="edit_nominee_age" class="datepicker" value=" "  type="text">
				</div>
				<div class="col s12 m4">
					<label for="edit_nominee_age">Age</label>
					<span> 
					<input type="text" id="edit_nominee_age" readonly>
					</span>
				</div>
			</div>
			
			<div class="col s12 m4">
				<label for="edit_sex">Sex *</label>
				<select name="edit_sex" id="edit_sex" class="error browser-default">
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
				<select name="edit_relationship" id="edit_relationship" data-error=".errorTxt31"  class="error browser-default">
					<option value="">Select</option>
					@foreach($data['relationship_view'] as $key=>$value)
						<option value="{{$value->id}}" data-relationshipname="{{$value->relation_name}}" >{{$value->relation_name}}</option>
					@endforeach
				</select>
					
				<div class="input-field">
					<div class="errorTxt31"></div>
				</div>   
			</div>
			<div class="input-field col s12 m4">
			   
				<input id="edit_nric_n" name="edit_nric_n" value=" "  type="text">
				<label for="edit_nric_n">NRIC-N *</label>
			</div>
			<div class="input-field col s12 m4">
			   
				<input id="edit_nric_o" name="edit_nric_o" value=" "  type="text">
				<label for="edit_nric_o">NRIC-O </label>
			</div>
			<div class="clearfix"> </div>
			
			<div class="col s12 m4">
				<label>Country Name*</label>
				<select name="edit_nominee_country_id" id="edit_nominee_country_id"  class="error browser-default">
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
					<label>State Name*</label>
				<select name="edit_nominee_state_id" id="edit_nominee_state_id"  class="error browser-default">
					<option value="">Select</option>
				</select>
				<div class="input-field">
						<div class="errorTxt36"></div>
				</div>       
				
			</div>
			<div class="col s12 m4">
					<label>City Name*</label>
				<select name="edit_nominee_city_id" id="edit_nominee_city_id"  class="error browser-default">
					<option value="">Select</option>
				</select>
				<div class="input-field">
						<div class="errorTxt36"></div>
				</div>       
				
			</div>
			<div class="clearfix"> </div>
			<div class="input-field col s12 m4">
			   
				<input id="edit_nominee_postal_code" name="edit_nominee_postal_code" type="text" value=" " >
				<label for="edit_nominee_postal_code">Postal code*</label>    
			</div>
			<div class="input-field col s12 m4">
				
				<input id="edit_nominee_address_one" name="edit_nominee_address_one" type="text" value=" " >
				<label for="edit_nominee_address_one">Address Line 1*</label>   
			</div>
			<div class="input-field col s12 m4">
				
				<input id="edit_nominee_address_two" name="edit_nominee_address_two" type="text" value=" " >
				<label for="edit_nominee_address_two">Address Line 2*</label>  
			</div>
			
			
			<div class="clearfix"> </div>
			<div class="input-field col s12 m4">
			   
				<input id="edit_nominee_address_three" name="edit_nominee_address_three" type="text" value=" " >
				<label for="edit_nominee_address_three">Address Line 3*</label>    
			</div>
			<div class="input-field col s12 m4">
				
				<input id="edit_nominee_mobile" name="edit_nominee_mobile" type="text" value=" " >
				<label for="edit_nominee_mobile" class="active">Mobile No*</label>   
			</div>
			<div class="input-field col s12 m4">
				
				<input id="edit_nominee_phone" name="edit_nominee_phone" type="text" value=" " >
				<label for="edit_nominee_phone" class="active">Phone No</label>    
			</div>
			<div class="clearfix"> </div>
			
		</div>
	</div>
	<div class="modal-footer">
		<button class="btn waves-effect waves-light purple right submit" id="update_nominee" type="submit" name="update_nominee">Update Nominee<i class="material-icons right">send</i></button>
		<a href="#!" class="modal-action modal-close waves-effect waves-green btn left ">Close</a> 
	</div>
	</form>
</div>
@php	
	$ajaxcompanyid = '';
	$ajaxbranchid = '';
	$ajaxunionbranchid = '';
	if(!empty(Auth::user())){
		$userid = Auth::user()->id;
		
		if($user_role =='union'){

		}else if($user_role =='union-branch'){
			$ajaxunionbranchid = CommonHelper::getUnionBranchID($userid);
		}else if($user_role =='company'){
			$ajaxcompanyid = CommonHelper::getCompanyID($userid);
		}else if($user_role =='company-branch'){
			$ajaxbranchid = CommonHelper::getCompanyBranchID($userid);
		}else{

		}
	}
@endphp
<script>
	$('#rejoined').click(function(){
	$('#member_old_div').toggle();
	var  oldMemberID = $('#old_mumber_number').val();
	});

	
	
	function SubmitMemberForm(){
		 $('#member_formValidate').trigger('submit');
	}
	
    $("#member_formValidate").validate({
        rules: {
            member_title:{
                required: true,
            },
            member_number: {
                required: true,
            },
            name: {
                required: true,
				minlength:3,
            },
            gender: {
                required: true,
            },
            name: {
                required: true,
            },
            mobile: {
                required: true,	
            },
            email: {
                required: true,
				email:true,
				remote: {
					url: "{{ url(app()->getLocale().'/member_emailexists')}}",
					data: {
						db_autoid: function() {
							return $("#auto_id").val();
						},
						_token: "{{csrf_token()}}",
						email: $(this).data('email')
					},
					type: "post",
				},
            },
            doe: {
                required: true,
            },
            designation: {
                required: true,
            },
            race: {
                required: true,
            },
            country: {
                required: true,
            },
            state: {
                required: true,
            },
			state_id: {
                required: true,
            },
            city: {
                required: true,
            },
			city_id: {
                required: true,
            },
            postal_code: {
                required: true,
				number: true,
				minlength:5,
				maxlength:8,
            },
            address_one: {
                required:true,
            },
			address_two: {
                required:true,
            },
			address_three: {
                required:true,
            },
            dob: {
                required:true,
            }, 
			doj: {
                required:true,
            },
            new_ic: {
                required:true,
                minlength: 3,
                maxlength: 20,
            },
            salary: {
                required: true,
            },
            branch: {
                required: true,
            },
            uname: {
                required: true,
                minlength: 5
            },
            country_name: {
                required: true,
            },
            state_name: {
                required: true,
            },
            country_id: "required",
            cemail: {
                required: true,
                email: true
            },
            city_name : {
            required: true,
            },
            designation_name : {
            required: true,
            },  
			
        },
        //For custom messages
        messages: {
            member_title: {
                required: "Please Enter Your Title ",
                
            },
            member_number: {
                required: "Please Enter Member NUmber",
                
            },
            name: {
                required: "Please Enter Your Name",
                
            },
            gender: {
                required: "Please choose Gender",
            },
            mobile: {
                required: "Please Enter your Number",
                
            },
            email: {
                required: "Please enter valid email",
				remote: '{{__("Email Already exists") }}',
            },
            designation: {
                required: "Please choose  your Designation",
            },
            
            race: {
                required: "Please Choose your Race ",
            },
            country: {
                required:"Please choose  your Country",
            },
            state: {
                required:"Please choose  your State",
            },
            city: {
                required:"Please choose  your city",
            },
            address_one: {
                required:"Please Enter your Address",
            },
            dob: {
                required:"Please choose DOB",
            },
            new_ic: {
                required:"Please Enter New Ic Number",
            },
            salary: {
                required:"Please Enter salary Name",
            },
            branch: {
                required:"Please Choose Company Name",
            },
            uname: {
                required: "Enter a username",
                minlength: "Enter at least 5 characters"
            },
            country_name: {
                required: "Enter a Country Name",
            },
            state_name: {
                required: "Enter a State Name",
            },
            city_name: {
                required: "Enter a City Name",
            },
            designation_name: {
                required: "Enter a Designation Name",
            },
			guardian_name: {
                required: "Enter a Guardian Name",
            },
			employee_id: {
                required: "Enter a Employee ID",
            },
        },
        errorElement: 'div',
        errorPlacement: function (error, element) {
        var placement = $(element).data('error');
        if (placement) {
            $(placement).append(error)
        } else {
            error.insertAfter(element);
        }
        }
    });


$('.modal').modal();
$("#membership_sidebar_a_id").addClass('active');
$('#country_id').change(function(){
	var countryID = $(this).val();   
	
	if(countryID){
		$.ajax({
		type:"GET",
		dataType: "json",
		url:" {{ URL::to('/get-state-list') }}?country_id="+countryID,
		success:function(res){               
			if(res){
				$("#state_id").empty();
				$("#state_id").append($('<option></option>').attr('value', '').text("Select"));
				$.each(res,function(key,entry){
					$("#state_id").append($('<option></option>').attr('value', entry.id).text(entry.state_name));
				   // var select = $("#state");
				   // select.material_select('destroy');
					//select.empty();
					
				});
			   // $('#state').material_select();
			}else{
			  $("#state_id").empty();
			}
		}
		});
	}else{
		$("#state_id").empty();
		$("#city_id").empty();
	}      
});
$('#state_id').change(function(){
   var StateId = $(this).val();
  
   if(StateId!='' && StateId!='undefined')
   {
	 $.ajax({
		type: "GET",
		dataType: "json",
		url : "{{ URL::to('/get-cities-list') }}?State_id="+StateId,
		success:function(res){
			if(res)
			{
				$('#city_id').empty();
				$("#city_id").append($('<option></option>').attr('value', '').text("Select City"));
				$.each(res,function(key,entry){
					$('#city_id').append($('<option></option>').attr('value',entry.id).text(entry.city_name));
					
				});
			}else{
				$('#city_id').empty();
			}
		}
	 });
   }else{
	   $('#city_id').empty();
   }
});
$('#company').change(function(){
   var CompanyID = $(this).val();
   var ajaxunionbranchid = '{{ $ajaxunionbranchid }}';
   var ajaxbranchid = '{{ $ajaxbranchid }}';
   var additional_cond;
   if(CompanyID!='' && CompanyID!='undefined')
   {
	 additional_cond = '&unionbranch_id='+ajaxunionbranchid+'&branch_id='+ajaxbranchid;
	 $.ajax({
		type: "GET",
		dataType: "json",
		url : "{{ URL::to('/get-branch-list-register') }}?company_id="+CompanyID+additional_cond,
		success:function(res){
			if(res)
			{
				$('#branch').empty();
				$("#branch").append($('<option></option>').attr('value', '').text("Select"));
				$.each(res,function(key,entry){
					$('#branch').append($('<option></option>').attr('value',entry.id).text(entry.branch_name)); 
				});
			}else{
				$('#branch').empty();
			}
		}
	 });
   }else{
	   $('#branch').empty();
   }
});
$('.datepicker').datepicker({
	format: 'yyyy-mm-dd'
});
$('#add_fee').click(function(){
	var fee_row_id = parseInt($("#fee_row_id").val())+1;
	var member_auto_id =   $("#auto_id").val();
	var new_fee_id =   $("#new_fee_id").val();
	var selected = $("#new_fee_id").find('option:selected');
	var new_fee_name = selected.data('feename'); 
	var fee_amount =   $("#fee_amount").val();

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	if(new_fee_id!="" && fee_amount!="" && fee_amount!="0" ){
		
		$("#add_fee").attr('disabled',true);
		var row_id =1;
		var new_row = '<tr>';
		new_row += '<td><span id="fee_name_label_'+fee_row_id+'">'+new_fee_name+'</span><input type="text" class="hide" name="fee_auto_id[]" id="fee_auto_id_'+fee_row_id+'"></input><input type="text" name="fee_name_id[]" class="hide" id="fee_name_id_'+fee_row_id+'" value="'+new_fee_id+'"></input></td>';
		new_row += '<td><span id="fee_amount_label_'+fee_row_id+'">'+fee_amount+'</span><input type="text" name="fee_name_amount[]" class="hide" id="fee_name_amount_'+fee_row_id+'" value="'+fee_amount+'"></input></td>';
		new_row += '<td><a class="btn-floating waves-effect waves-light edit_fee_row " href="#modal_fee" data-id="'+fee_row_id+'"><i class="material-icons left">edit</i></a> <a class="btn-floating waves-effect waves-light amber darken-4 delete_fee" data-id="'+fee_row_id+'" ><i class="material-icons left">delete</i></a></td>';
		new_row += '</tr>';
		$("#fee_amount").val('');
		//$('#test3').find('input:text').val('');    
		$('#fee_table').append(new_row);
		$("#add_fee").attr('disabled',false);
		$("#fee_row_id").val(fee_row_id)
	}
	else{
		$("#add_fee").attr('disabled',false);
		M.toast({
			html: "Please choose fees and fill amount"
		});
	}    
});

$("#fee_formValidate").submit(function(e){
	e.preventDefault();
});
$("#fee_formValidate").validate({
	rules: {
		edit_fee_name: {
			required: true,
		},
		edit_fee_amount: {
			required: true,
		},
	},
	//For custom messages
	messages: {
		edit_fee_amount: {
			required: "Enter a Fee Amount",
		},
	},
	errorElement: 'div',
	errorPlacement: function (error, element) {
		var placement = $(element).data('error');
		if (placement) {
			$(placement).append(error)
		} else {
			error.insertAfter(element);
		}
	},
	submitHandler: function(form) {
		var row_id = $("#edit_fee_row_id").val();
		var edit_fee_auto_id = $("#edit_fee_name").val();
		var edit_fee_amount = $("#edit_fee_amount").val();
		var selected = $("#edit_fee_name").find('option:selected');
		var new_fee_name = selected.data('feename');
		//var formData = $("#fee_formValidate").serialize();
		$("#fee_name_id_"+row_id).val(edit_fee_auto_id);
		$("#fee_name_amount_"+row_id).val(edit_fee_amount);
		$("#fee_name_label_"+row_id).html(new_fee_name);
		$("#fee_amount_label_"+row_id).html(edit_fee_amount);
		$('#modal_fee').modal('close'); 
		return false;
	}
});

$('#new_fee_id').change(function(){
	var selected = $(this).find('option:selected');
	var feeamount = selected.data('feeamount'); 
	$("#fee_amount").val(feeamount);
});
$('#edit_fee_name').change(function(){
	var selected = $(this).find('option:selected');
	var feeamount = selected.data('feeamount'); 
	$("#edit_fee_amount").val(feeamount);
});
$(document.body).on('click', '.edit_fee_row' ,function(){
	var fee_id = $(this).data('id');
	$('#modal_fee').modal('open'); 
	var db_row_id = $('#fee_auto_id_'+fee_id).val(); 
	var fee_name_id = $('#fee_name_id_'+fee_id).val(); 
	
	//if(db_row_id==""){
		$('#edit_fee_auto_id').val(db_row_id); 
		var edit_fee_id = $('#fee_name_id_'+fee_id).val(); 
		var edit_fee_amount = $('#fee_name_amount_'+fee_id).val(); 
		$.ajax({
			type: "GET",
			dataType: "json",
			url : "{{ URL::to('/get-fee-options') }}",
			success:function(res){
				if(res)
				{
					$('#edit_fee_name').empty();
					$("#edit_fee_name").append($('<option></option>').attr('value', '').text("Select Fee"));
					$.each(res,function(key,entry){
						var selectval = edit_fee_id==entry.id ? 'selected' : '';
						$('#edit_fee_name').append($('<option '+selectval+' data-feeamount="'+entry.fee_amount+'" data-feename="'+entry.fee_name+'"></option>').attr('value',entry.id).text(entry.fee_name));
					});
				}else{
					$('#edit_fee_name').empty();
				}
			}
		});
		$('#edit_fee_amount').val(edit_fee_amount);
		$('#edit_fee_row_id').val(fee_id);
	//}
});
$(document.body).on('click', '.delete_fee' ,function(){
	if(confirm('Are you sure you want to delete?')){
		var fee_id = $(this).data('id');
		var parrent = $(this).parents("tr");
		parrent.remove(); 
	}else{
		return false;
	}
	
});
$('#nominee_dob, #gaurdian_dob, #dob, #edit_nominee_dob').change(function(){
   var Dob = $(this).val();
   var reflect_age = $(this).data('reflectage'); 
   if(Dob!=""){
		$.ajax({
			type:"GET",
			dataType:"json",
			url:"{{URL::to('/get-age') }}? dob="+Dob,
			success:function(res){
				if(res){
					$("#"+reflect_age).val(res);
				}else{
					$("#"+reflect_age).val(0);
				}
			}
		});
   }else{
	  $("#"+reflect_age).val(0);
   }
	
});

 $('#nominee_country_id').change(function(){
	var countryID = $(this).val();   
	
	if(countryID){
		$.ajax({
		type:"GET",
		dataType: "json",
		url:" {{ URL::to('/get-state-list') }}?country_id="+countryID,
		success:function(res){               
			if(res){
				$("#nominee_state_id").empty();
				$("#nominee_state_id").append($('<option></option>').attr('value', '').text("Select State"));
				$.each(res,function(key,entry){
				  
					$("#nominee_state_id").append($('<option></option>').attr('value', entry.id).text(entry.state_name));
				   // var select = $("#state");
				   // select.material_select('destroy');
					//select.empty();
					
				});
				$('#nominee_state_id').trigger('change');
			   // $('#state').material_select();
			}else{
			  $("#nominee_state_id").empty();
			}
		}
		});
	}else{
		$("#nominee_state_id").empty();
		$("#nominee_city_id").empty();
	}      
});
$('#nominee_state_id').change(function(){
   var StateId = $(this).val();
  
   if(StateId!='' && StateId!='undefined')
   {
	 $.ajax({
		type: "GET",
		dataType: "json",
		url : "{{ URL::to('/get-cities-list') }}?State_id="+StateId,
		success:function(res){
			if(res)
			{
				$('#nominee_city_id').empty();
				$("#nominee_city_id").append($('<option></option>').attr('value', '').text("Select City"));
				$.each(res,function(key,entry){
					$('#nominee_city_id').append($('<option></option>').attr('value',entry.id).text(entry.city_name));
					
				});
			}else{
				$('#nominee_city_id').empty();
			}
		}
	 });
   }else{
	   $('#nominee_city_id').empty();
   }
});
 $('#add_nominee').click(function(){
	var nominee_row_id = parseInt($("#nominee_row_id").val())+1;
	//alert(nominee_row_id);
	var auto_id =   $("#auto_id").val();
	var nominee_name =   $("#nominee_name").val();
	var nominee_dob =   $("#nominee_dob").val();
	var nominee_sex =   $("#sex").val();
	var nominee_relationship =   $("#relationship").val();
	var nric_n =   $("#nric_n").val();
	var nric_o =   $("#nric_o").val();
	var nominee_address_one =   $("#nominee_address_one").val();
	var nominee_country_id =   $("#nominee_country_id").val();
	var nominee_state_id =   $("#nominee_state_id").val();
	var nominee_address_two =   $("#nominee_address_two").val();
	var nominee_city_id =   $("#nominee_city_id").val();
	var nominee_postal_code =   $("#nominee_postal_code").val();
	var nominee_address_three =   $("#nominee_address_three").val();
	var nominee_mobile =   $("#nominee_mobile").val();
	var nominee_phone =   $("#nominee_phone").val();
	var nominee_age =   $("#nominee_age").val();
	
	var selected = $("#relationship").find('option:selected');
	var relationshipname = selected.data('relationshipname'); 
	$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
	if(nominee_name!="" && nominee_dob!="" && nominee_sex!="" && nominee_relationship!="" && 
	nric_n!="" && nominee_address_one!="" && nominee_country_id!="" && nominee_state_id!="" && 
	nominee_address_two!="" && nominee_city_id!="" && nominee_postal_code != "" && nominee_address_three!="" && nominee_mobile!=""){
	   $("#add_nominee").attr('disabled',true);
	    var new_row = '<tr>';
		new_row += '<td><span id="nominee_name_label_'+nominee_row_id+'">'+nominee_name+'</span><input type="text" name="nominee_auto_id[]" class="hide" id="nominee_auto_id_'+nominee_row_id+'"></input><input class="hide" type="text" name="nominee_name_value[]" id="nominee_name_value_'+nominee_row_id+'" value="'+nominee_name+'"></input></td>';
		new_row += '<td><span id="nominee_age_label_'+nominee_row_id+'">'+nominee_age+'</span><input type="text" class="hide" name="nominee_age_value[]" id="nominee_age_value_'+nominee_row_id+'" value="'+nominee_age+'"></input><input type="text" class="hide" name="nominee_dob_value[]" id="nominee_dob_value_'+nominee_row_id+'" value="'+nominee_dob+'"></input></td>';
		new_row += '<td><span id="nominee_gender_label_'+nominee_row_id+'">'+nominee_sex+'</span><input class="hide" type="text" name="nominee_gender_value[]" id="nominee_gender_value_'+nominee_row_id+'" value="'+nominee_sex+'"></input></td>';
		new_row += '<td><span id="nominee_relation_label_'+nominee_row_id+'">'+relationshipname+'</span><input type="text" class="hide" name="nominee_relation_value[]" id="nominee_relation_value_'+nominee_row_id+'" value="'+nominee_relationship+'"></input></td>';
		new_row += '<td><span id="nominee_nricn_label_'+nominee_row_id+'">'+nric_n+'</span><input class="hide" type="text" name="nominee_nricn_value[]" id="nominee_nricn_value_'+nominee_row_id+'" value="'+nric_n+'"></input></td>';
		new_row += '<td><span id="nominee_nrico_label_'+nominee_row_id+'">'+nric_o+'</span><input type="text" class="hide" name="nominee_nrico_value[]" id="nominee_nrico_value_'+nominee_row_id+'" value="'+nric_o+'"></input></td>';
		new_row += '<td class="hide"><span id="nominee_addressone_label_'+nominee_row_id+'">'+nominee_address_one+'</span><input type="text" class="hide" name="nominee_addressone_value[]" id="nominee_addressone_value_'+nominee_row_id+'" value="'+nominee_address_one+'"></input></td>';
		new_row += '<td class="hide"><span id="nominee_addresstwo_label_'+nominee_row_id+'">'+nominee_address_two+'</span><input type="text" class="hide" name="nominee_addresstwo_value[]" id="nominee_addresstwo_value_'+nominee_row_id+'" value="'+nominee_address_two+'"></input></td>';
		new_row += '<td class="hide"><span id="nominee_addressthree_label_'+nominee_row_id+'">'+nominee_address_three+'</span><input type="text" class="hide" name="nominee_addressthree_value[]" id="nominee_addressthree_value_'+nominee_row_id+'" value="'+nominee_address_three+'"></input></td>';
		new_row += '<td class="hide"><span id="nominee_country_label_'+nominee_row_id+'">'+nominee_country_id+'</span><input type="text" name="nominee_country_value[]" id="nominee_country_value_'+nominee_row_id+'" value="'+nominee_country_id+'"></input></td>';
		new_row += '<td class="hide"><span id="nominee_state_label_'+nominee_row_id+'">'+nominee_state_id+'</span><input type="text" name="nominee_state_value[]" id="nominee_state_value_'+nominee_row_id+'" value="'+nominee_state_id+'"></input></td>';
		new_row += '<td class="hide"><span id="nominee_city_label_'+nominee_row_id+'">'+nominee_city_id+'</span><input type="text" name="nominee_city_value[]" id="nominee_city_value_'+nominee_row_id+'" value="'+nominee_city_id+'"></input></td>';
		new_row += '<td class="hide"><span id="nominee_postalcode_label_'+nominee_row_id+'">'+nominee_postal_code+'</span><input type="text" name="nominee_postalcode_value[]" id="nominee_postalcode_value_'+nominee_row_id+'" value="'+nominee_postal_code+'"></input></td>';
		new_row += '<td class="hide"><span id="nominee_mobile_label_'+nominee_row_id+'">'+nominee_mobile+'</span><input type="text" name="nominee_mobile_value[]" id="nominee_mobile_value_'+nominee_row_id+'" value="'+nominee_mobile+'"></input></td>';
		new_row += '<td class="hide"><span id="nominee_phone_label_'+nominee_row_id+'">'+nominee_phone+'</span><input type="text" name="nominee_phone_value[]" id="nominee_phone_value_'+nominee_row_id+'" value="'+nominee_phone+'"></input></td>';
		new_row += '<td><a class="btn-floating waves-effect waves-light cyan edit_nominee_row " href="#modal_nominee" data-id="'+nominee_row_id+'"><i class="material-icons left">edit</i></a> <a class="btn-floating waves-effect waves-light amber darken-4 delete_nominee" data-id="'+nominee_row_id+'" ><i class="material-icons left">delete</i></a></td>';
		new_row += '</tr>';
		//$('#test2').find('input:text').val('');    
		$('#nominee_add_section').find('input:text').val('');  
		$('#nominee_table').append(new_row);
		M.toast({
			html: 'Nominee added successfully'
		});
		$("#add_nominee").attr('disabled',false);
		$("#nominee_row_id").val(nominee_row_id);
		
	}
	else{
		$("#add_nominee").attr('disabled',false);
		M.toast({
			html: "Please fill requierd fields"
		});
	}    
});

$('#edit_nominee_state_id').change(function(e, data){
   var StateId = $(this).val();
  
   if(StateId!='' && StateId!='undefined')
   {
	 $.ajax({
		type: "GET",
		dataType: "json",
		url : "{{ URL::to('/get-cities-list') }}?State_id="+StateId,
		success:function(res){
			if(res)
			{
				$('#edit_nominee_city_id').empty();
				$("#edit_nominee_city_id").append($('<option></option>').attr('value', '').text("Select City"));
				$.each(res,function(key,entry){
					$('#edit_nominee_city_id').append($('<option></option>').attr('value',entry.id).text(entry.city_name));
					
				});
				if(typeof data !='undefined'){
					loader.hideLoader();
					$('#edit_nominee_city_id').val(data.city_id);
				}
				
			}else{
				$('#edit_nominee_city_id').empty();
			}
		}
	 });
   }else{
	   $('#edit_nominee_city_id').empty();
   }
});


 $('#edit_nominee_country_id').change(function(e, data){
	var countryID = $(this).val();   
	
	if(countryID){
		$.ajax({
		type:"GET",
		dataType: "json",
		url:" {{ URL::to('/get-state-list') }}?country_id="+countryID,
		success:function(res){               
			if(res){
				$("#edit_nominee_state_id").empty();
				$("#edit_nominee_state_id").append($('<option></option>').attr('value', '').text("Select State"));
				$.each(res,function(key,entry){
				  
					$("#edit_nominee_state_id").append($('<option></option>').attr('value', entry.id).text(entry.state_name));
				   // var select = $("#state");
				   // select.material_select('destroy');
					//select.empty();
					
				});
				if(typeof data !='undefined'){
					$('#edit_nominee_state_id').val(data.state_id);
					$('#edit_nominee_state_id').trigger('change', [{state_id: data.state_id, city_id: data.city_id}]);
				}
			   // $('#state').material_select();
			}else{
			  $("#edit_nominee_state_id").empty();
			}
		}
		});
	}else{
		$("#edit_nominee_state_id").empty();
		$("#edit_nominee_city_id").empty();
	}      
});



$(document.body).on('click', '.edit_nominee_row' ,function(){
	var nominee_id = $(this).data('id');
	$('#modal_nominee').modal('open'); 
	var db_row_id = $('#nominee_auto_id_'+nominee_id).val(); 
	var nominee_age = $('#nominee_age_value_'+nominee_id).val(); 
	var nominee_dob = $('#nominee_dob_value_'+nominee_id).val(); 
	var nominee_name = $('#nominee_name_value_'+nominee_id).val(); 
	var nominee_gender = $('#nominee_gender_value_'+nominee_id).val(); 
	var nominee_relation = $('#nominee_relation_value_'+nominee_id).val(); 
	var nominee_nricn = $('#nominee_nricn_value_'+nominee_id).val(); 
	var nominee_nrico = $('#nominee_nrico_value_'+nominee_id).val(); 
	var nominee_addressone = $('#nominee_addressone_value_'+nominee_id).val(); 
	var nominee_addresstwo = $('#nominee_addresstwo_value_'+nominee_id).val(); 
	var nominee_addressthree = $('#nominee_addressthree_value_'+nominee_id).val(); 
	var nominee_country = $('#nominee_country_value_'+nominee_id).val(); 
	var nominee_state = $('#nominee_state_value_'+nominee_id).val(); 
	var nominee_city = $('#nominee_city_value_'+nominee_id).val(); 
	var nominee_postal_code = $('#nominee_postalcode_value_'+nominee_id).val(); 
	var nominee_mobile = $('#nominee_mobile_value_'+nominee_id).val(); 
	var nominee_phone = $('#nominee_phone_value_'+nominee_id).val(); 
	$("#edit_nominee_auto_id").val( db_row_id );
	$("#edit_nominee_row_id").val( nominee_id );
	$("#edit_nominee_name").val( nominee_name );
	$("#edit_nominee_dob").val( nominee_dob );
	$("#edit_sex").val( nominee_gender );
	$("#edit_relationship").val( nominee_relation );
	$("#edit_nric_n").val( nominee_nricn );
	$("#edit_nric_o").val( nominee_nrico );
	$("#edit_nominee_address_one").val( nominee_addressone );
	$("#edit_nominee_country_id").val( nominee_country );
	$("#edit_nominee_country_id").trigger('change',[{country_id: nominee_country, state_id: nominee_state, city_id: nominee_city}]);
	$("#edit_nominee_state_id").val( nominee_state );
	$("#edit_nominee_address_two").val( nominee_addresstwo );
	$("#edit_nominee_city_id").val( nominee_city );
	$("#edit_nominee_postal_code").val( nominee_postal_code );
	$("#edit_nominee_address_three").val( nominee_addressthree );
	$("#edit_nominee_mobile").val( nominee_mobile );
	$("#edit_nominee_phone").val( nominee_phone );
	$("#edit_nominee_age").val( nominee_age );
});

 $("#nominee_formValidate").validate({
	rules: {
		edit_nominee_name: {
			required: true,
		},
		edit_nominee_dob: {
			required: true,
		},
		edit_sex: {
			required: true,
		},
		edit_relationship: {
			required: true,
		},
		edit_nric_n: {
			required: true,
		},
		
		edit_nominee_address_one: {
			required: true,
		},
		edit_nominee_country_id: {
			required: true,
		},
		edit_nominee_state_id: {
			required: true,
		},
		edit_nominee_address_two: {
			required: true,
		},
		edit_nominee_city_id: {
			required: true,
		},
		edit_nominee_postal_code: {
			required: true,
		},
		edit_nominee_address_three: {
			required: true,
		},
		edit_nominee_mobile: {
			required: true,
		},
	},
	//For custom messages
	messages: {
		edit_nominee_name: {
			required: "Enter a Nominee name",
		},
	},
	errorElement: 'div',
	errorPlacement: function (error, element) {
		var placement = $(element).data('error');
		if (placement) {
			$(placement).append(error)
		} else {
			error.insertAfter(element);
		}
	},
	submitHandler: function(form) {
		var formData = $("#nominee_formValidate").serialize();
		var row_id = $("#edit_nominee_row_id").val();
		var nominee_name = $("#edit_nominee_name").val();
		var nominee_dob = $("#edit_nominee_dob").val();
		var nominee_age = $("#edit_nominee_age").val();
		var sex = $("#edit_sex").val();
		var relationship = $("#edit_relationship").val();
		var nric_n = $("#edit_nric_n").val();
		var nric_o = $("#edit_nric_o").val();
		var nominee_address_one = $("#edit_nominee_address_one").val();
		var nominee_address_two = $("#edit_nominee_address_two").val();
		var nominee_address_three = $("#edit_nominee_address_three").val();
		var country_id = $("#edit_nominee_country_id").val();
		var state_id = $("#edit_nominee_state_id").val();
		var city_id = $("#edit_nominee_city_id").val();
		var postal_code = $("#edit_nominee_postal_code").val();
		var mobile = $("#edit_nominee_mobile").val();
		var phone = $("#edit_nominee_phone").val();
		var selected = $("#edit_relationship").find('option:selected');
		var relationshipname = selected.data('relationshipname');
		//var formData = $("#fee_formValidate").serialize();
		
		$("#nominee_name_label_"+row_id).html(nominee_name);
		$("#nominee_name_value_"+row_id).val(nominee_name);
		$("#nominee_age_label_"+row_id).html(nominee_age);
		$("#nominee_age_value_"+row_id).val(nominee_age);
		$("#nominee_dob_value_"+row_id).val(nominee_dob);
		$("#nominee_gender_value_"+row_id).val(sex);
		$("#nominee_gender_label_"+row_id).html(sex);
		$("#nominee_relation_label_"+row_id).html(relationshipname);
		$("#nominee_relation_value_"+row_id).val(relationship);
		$("#nominee_nricn_value_"+row_id).val(nric_n);
		$("#nominee_nricn_label_"+row_id).html(nric_n);
		$("#nominee_nrico_label_"+row_id).html(nric_o);
		$("#nominee_nrico_value_"+row_id).val(nric_o);
		$("#nominee_addressone_value_"+row_id).val(nominee_address_one);
		$("#nominee_addresstwo_value_"+row_id).val(nominee_address_two);
		$("#nominee_addressthree_value_"+row_id).val(nominee_address_three);
		$("#nominee_country_value_"+row_id).val(country_id);
		$("#nominee_state_value_"+row_id).val(state_id);
		$("#nominee_city_value_"+row_id).val(city_id);
		$("#nominee_postalcode_value_"+row_id).val(postal_code);
		$("#nominee_mobile_value_"+row_id).val(mobile);
		$("#nominee_phone_value_"+row_id).val(phone);
		$('#modal_nominee').modal('close'); 
	}
});
$(document.body).on('click', '.delete_nominee' ,function(){
	if(confirm('Are you sure you want to delete?')){
		var fee_id = $(this).data('id');
		var parrent = $(this).parents("tr");
		parrent.remove(); 
	}else{
		return false;
	}
	
});

$('#guardian_country_id').change(function(){
	var countryID = $(this).val();   
	
	if(countryID){
		$.ajax({
		type:"GET",
		dataType: "json",
		url:" {{ URL::to('/get-state-list') }}?country_id="+countryID,
		success:function(res){               
			if(res){
				$("#guardian_state_id").empty();
				$("#guardian_state_id").append($('<option></option>').attr('value', '').text("Select State"));
				$.each(res,function(key,entry){
				  
					$("#guardian_state_id").append($('<option></option>').attr('value', entry.id).text(entry.state_name));
				   // var select = $("#state");
				   // select.material_select('destroy');
					//select.empty();
					
				});
				$('#guardian_state_id').trigger('change');
			   // $('#state').material_select();
			}else{
			  $("#guardian_state_id").empty();
			}
		}
		});
	}else{
		$("#guardian_state_id").empty();
		$("#guardian_city_id").empty();
	}      
});
$('#guardian_state_id').change(function(){
   var StateId = $(this).val();
  
   if(StateId!='' && StateId!='undefined')
   {
	 $.ajax({
		type: "GET",
		dataType: "json",
		url : "{{ URL::to('/get-cities-list') }}?State_id="+StateId,
		success:function(res){
			if(res)
			{
				$('#guardian_city_id').empty();
				$("#guardian_city_id").append($('<option></option>').attr('value', '').text("Select City"));
				$.each(res,function(key,entry){
					$('#guardian_city_id').append($('<option></option>').attr('value',entry.id).text(entry.city_name));
					
				});
			}else{
				$('#guardian_city_id').empty();
			}
		}
	 });
   }else{
	   $('#guardian_city_id').empty();
   }
});

$(document).on('submit','form#member_formValidate',function(){
    $(".form-save-btn").prop('disabled',true);
	loader.showLoader();
});


</script>
@endsection