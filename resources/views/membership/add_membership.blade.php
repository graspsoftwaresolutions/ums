@extends($data['user_type']==1 ? 'layouts.admin' : 'layouts.new-member')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/materialize-stepper/materialize-stepper.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/themes/vertical-modern-menu-template/materialize.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/themes/vertical-modern-menu-template/style.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/pages/form-wizard.css') }}">
<style type="text/css">
  	.autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; cursor:pointer; }
	.autocomplete-suggestion { padding: 8px 5px; white-space: nowrap; overflow: hidden; }
	.autocomplete-selected { background: #F0F0F0; }
	.autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
	.autocomplete-group { padding: 8px 5px; }
	.autocomplete-group strong { display: block; border-bottom: 1px solid #000; }
</style>
@endsection
@section('headSecondSection')

@endsection
@section('main-content')
<div id="main">
	<div class="row">
		<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
		<div class="col s12">
			<div class="container">
				<div class="section section-data-tables">
					<!-- BEGIN: Page Main-->
					<div class="row">
						@if($data['user_type']==1)
						<div class="breadcrumbs-dark pb-0 pt-4" id="breadcrumbs-wrapper">
							<!-- Search for small screen-->
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
						</div>
						@endif
						<div class="col s12">
							
									<!--h4 class="card-title">{{__('New Membership') }}</h4-->
									@include('includes.messages')
									<div class="row">
										<div class="col s12">
											<div class="card">
												<div class="card-content pb-0">
													<div class="card-header">
														<h4 class="card-title">{{__('New Membership') }}</h4>
													</div>

													<ul class="stepper horizontal" id="horizStepper">
														<li class="step active">
															<div class="step-title waves-effect">Member Details</div>
															<div class="step-content" style="padding: 50px 50px;">
																<div style="box-shadow: 0 2px 2px 0 rgba(0, 0, 0, .14), 0 3px 1px -2px rgba(0, 0, 0, .12), 0 1px 5px 0 rgba(0, 0, 0, .2);padding:50px 50px;">
																<div class="row">
																	<div class="input-field col m6 s12">
																		<label for="firstName">First Name: <span class="red-text">*</span></label>
																		<input type="text" id="firstName" name="firstName" class="validate"
																			aria-required="true" required="" required>
																	</div>
																	<div class="input-field col m6 s12">
																		<label for="lastName">Last Name: <span class="red-text">*</span></label>
																		<input type="text" id="lastName" class="validate" aria-required="true"
																			name="lastName" required="" required>
																	</div>
																</div>
																<div class="row">
																	<div class="input-field col m6 s12">
																		<label for="Email">Email: <span class="red-text">*</span></label>
																		<input type="email" class="validate" name="Email" id="Email" required=""
																			required>
																	</div>
																	<div class="input-field col m6 s12">
																		<label for="contactNum">Contact Number: <span class="red-text">*</span></label>
																		<input type="number" class="validate" name="contactNum" id="contactNum"
																			required="" required>
																	</div>
																</div>
																
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
																			<button class="waves-effect waves dark btn btn-primary next-step"
																				type="submit">
																				Next
																				<i class="material-icons right">arrow_forward</i>
																			</button>
																		</div>
																	</div>
																
																</div>
															</div>
														</li>
														<li class="step">
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
													<select name="new_fee_id" id="new_fee_id" class="error browser-default">
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
												   <label for="fee_amount">Fee amount *</label>
												   <input id="fee_amount" name="fee_amount" value="0"  type="text">
												   
												   
												</div>
											   
												<div class="clearfix"> </div>
												<div class="col s12 m12">
													<button class="btn waves-effect waves-light right submit" id="add_fee" type="button" name="add_fee_row">Add Fee
													</button>
												</div>
											</div>
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
															<label for="nominee_dob">Age</label>
															<input type="text" id="nominee_age" value="0" readonly >
														</div>
													 </div>
												</div>
												<div class="col s12 m4">
												   <label for="years">Sex *</label>
													<select name="sex" id="sex" class="error browser-default">
														<option value="">Select</option>
														<option value="male" >Male</option>
														<option value="female" >Female</option>
													</select>
													<div class="input-field">
														 <div class="errorTxt50"></div>
													</div>  
												</div>
												<div class="clearfix"> </div>
												<div class="col s12 m4">
													 <label>Relationship*</label>
														<select name="relationship" id="relationship" data-error=".errorTxt31"  class="error browser-default">
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
													<select name="nominee_country_id" id="nominee_country_id"  class="error browser-default">
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
													<select name="nominee_state_id" id="nominee_state_id"  class="error browser-default">
														<option value="">Select</option>
													</select>
													<div class="input-field">
														 <div class="errorTxt36"></div>
													</div>       
													
												</div>
												<div class="col s12 m4">
													 <label>City Name*</label>
													<select name="nominee_city_id" id="nominee_city_id"  class="error browser-default">
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
															<label for="gaurdian_age">Age</label>
															<span> 
															<input type="text" id="gaurdian_age" value="0" readonly >
															</span>
														</div>
													 </div>
												</div>
												
												<div class="col s12 m4">
													<label for="guardian_sex">SEX *</label>
													<select name="guardian_sex" id="guardian_sex" class="error browser-default">
														<option value="">Select</option>
														<option value="male" >Male</option>
														<option value="female" >Female</option>
													</select>
													
												</div>
												<div class="clearfix"> </div>
												<div class="col s12 m4">
													<label>Relationship*</label>
													<select name="g_relationship_id" id="g_relationship" data-error=".errorTxt31"  class="error browser-default">
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
													<select name="guardian_country_id" id="guardian_country_id"  class="error browser-default">
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
													<select name="guardian_state_id" id="guardian_state_id"  class="error browser-default">
														<option value="" >Select</option>
													</select>
													<div class="input-field">
														 <div class="errorTxt36"></div>
													</div>
												</div>
												<div class="col s12 m4">
													 <label>City Name*</label>
													<select name="guardian_city_id" id="guardian_city_id"  class="error browser-default">
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
																<div class="row">
														
																	<div class="col m12 s12 mb-1" style="text-align:right">
																		<button class="waves-effect waves-dark btn btn-primary"
																			type="submit">Submit</button>
																	</div>
																</div>	
																
															</div>
														</li>
														
													</ul>

												
												</div>
											</div>
										</div>
									</div>
                                       
							
						</div>
					</div>
                    <div class="row hide">
					<div class="input-field col s6">
					<input id="country_test" type="text" name="country_test" class="validate">
					<label for="country_test">Search Country here</label>
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
<script src="{{ asset('public/assets/vendors/materialize-stepper/materialize-stepper.min.js')}}"></script>
<!--script src = "{{ asset('public/assets/js/materialize.min.js') }}" type="text/javascript"></script-->



@endsection
@section('footerSecondSection')
<script src="{{ asset('public/assets/js/scripts/form-wizard.js') }}" type="text/javascript"></script>

@endsection