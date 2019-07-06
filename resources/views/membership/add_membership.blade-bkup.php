@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">

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
						<div class="breadcrumbs-dark pb-0 pt-4" id="breadcrumbs-wrapper">
							<!-- Search for small screen-->
							<div class="container">
								<div class="row">
									<div class="col s10 m6 l6">
										<h5 class="breadcrumbs-title mt-0 mb-0">New Membership</h5>
										<ol class="breadcrumbs mb-0">
											<li class="breadcrumb-item"><a href="index.html">Dashboard</a>
											</li>
											<li class="breadcrumb-item active"><a href="#">Member</a>
											</li>
											
										</ol>
									</div>
								</div>
							</div>
						</div>
						<div class="col s12">
							<div class="card">
								<div class="card-content">
									<h4 class="card-title">New Membership</h4>
									@include('includes.messages')
									<div class="row">
                                        <div class="col s12">
                                            <ul class="tabs">
                                                <li class="tab col m3"><a class="active"  href="#test1">Membership details</a></li>
												<li class="tab col m3"><a href="#test3">Fee Details</a></li>
                                                <li class="tab col m3"><a href="#test2">Nominee Details</a></li>
                                                <li class="tab col m3"><a href="#test4">Guardian Details</a></li>
                                            </ul>
                                        </div>
										<form class="formValidate" id="member_formValidate" method="post" action="{{ url('membership_save') }}">
										@csrf
										<div id="test1" class="col s12">
											</br>
											<div id="view-validations">
													
												  <div class="row">
													<div class="col s12 m6">
													<label>Member Title*</label>
													  <select name="member_title" id="member_title" data-error=".errorTxt1" class="error browser-default">
															<option value="" disabled selected>Choose your option</option>
															@foreach($data['title_view'] as $key=>$value)
														<option value="{{$value->id}}">{{$value->person_title}}</option>
														@endforeach
															</select>
														   
													  <div class="errorTxt1"></div>
													</div>
													<div class="input-field col s12 m6">
													  <label for="member_number">Member Number *</label>
													  <input id="member_number" name="member_number" type="text" data-error=".errorTxt2">
													  <div class="errorTxt2"></div>
													</div>
													<div class="clearfix" ></div>
													<div class="input-field col s12 m6">
													  <label for="name">Member Name *</label>
													  <input id="name" name="name" type="text" data-error=".errorTxt3">
													  <div class="errorTxt3"></div>
													</div>
													
													<div class="input-field col s12 m6">
													<div class="col s12 row">
															<div class="col s12 m4">
																<p>Gender</p>
															</div>
															<div class="col s12 m4">
																<label>
																	<input class="validate" required="" aria-required="true" id="gender" name="gender" type="radio" value="female">
																	<span>Female</span>
																</label>  
															</div>
															<div class="col s12 m4">
																<p>
																	<label>
																	<input class="validate" required="" aria-required="true" id="gender" name="gender" type="radio" checked="" value="male">
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
													  <label for="phone">Mobile Number *</label>
													  <input id="phone" name="phone" type="text" data-error=".errorTxt5">
													  <div class="errorTxt5"></div>
													</div>
													<div class="input-field col s12 m6">
													  <label for="email">Email *</label>
													  <input id="email" name="email" type="text" data-error=".errorTxt6">
													  <div class="errorTxt6"></div>
													</div>
													<div class="clearfix" ></div>
													<div class="input-field col s12 m6">
													  
													  <input type="text" class="datepicker" id="doe" name="doe">
														<label for="doe">Date of Emp</label>
													  <div class="errorTxt7"></div>
													</div>
														<div class="col s12 m6">
															 <div class="input-field col s12 m6">
																<p>
																<label>
																	<input type="checkbox" id="rejoined"/>
																	<span>Rejoined</span>
																	
																</label>
																</p>
															 </div>
															 <div class="input-field col s12 m6" id="member_old_div">
																<input type="text" name="old_mumber_number" id="old_mumber_number" class="autocomplete">
																<label for="old_mumber_number">Old Number</label>
															 <span> 
																
															 </span>
															 </div>
														</div>
														<div class="clearfix" style="clear:both"></div>

														<div class="col s12 m6">
														<label>Designation*</label>
															<select name="designation" id="designation" class="error browser-default">
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
															<select name="race" id="race" class="error browser-default">
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
														   <label>Country Name*</label>
															<select name="country_id" id="country" class="error browser-default">
															<option value="">Select Country</option>
																@foreach($data['country_view'] as $value)
																<option value="{{$value->id}}">{{$value->country_name}}</option>
																@endforeach
															</select>
															<div class="input-field">        
																<div class="errorTxt10"></div>
															</div>
														</div>
														<div class="col s12 m6">
														   <label>State Name*</label>
															<select class="error browser-default" id="state_id" name="state_id" aria-required="true" required>
																<option value="" selected>State Name</option>
															</select>
															<div class="input-field"> 
																<div class="errorTxt11"></div>
															</div>
														</div>
														<div class="clearfix" style="clear:both"></div>
														<div class="col s12 m6">
															 <label>City Name*</label>
															<select name="city_id" id="city" class="error browser-default" aria-required="true" required>
															<option value="">Select City</option>
																	</select>
															<div class="input-field">        
																<div class="errorTxt12"></div>
															</div>
														</div>
														<div class="input-field col s12 m6">
														<label for="postal_code">Postal Code *</label>
															<input id="postal_code" name="postal_code" type="text" data-error=".errorTxt13">
															<div class="errorTxt13"></div>
														</div>
														<div class="clearfix" ></div>
														<div class="input-field col s12 m6">
														<label for="address_one">Address Line 1*</label>
															<input id="address_one" name="address_one" type="text" data-error=".errorTxt14">
															<div class="errorTxt14"></div>
														</div>
														<div class="input-field col s12 m6">
														<label for="address_two">Address Line 2*</label>
															<input id="address_two" name="address_two" type="text" data-error=".errorTxt15">
															<div class="errorTxt15"></div>
														</div>
														<div class="clearfix" ></div>
														<div class="input-field col s12 m6">
														<label for="address_three">Address Line 3*</label>
															<input id="address_three" name="address_three" type="text" data-error=".errorTxt16">
															<div class="errorTxt16"></div>
														</div>
														<div class="input-field col s12 m6">
															<input type="text" class="datepicker" id="dob" name="dob" data-error=".errorTxt17">
																<label for="dob">Date of Birth*</label>
															<div class="errorTxt17"></div>
														</div>
														<div class="clearfix" ></div>
														<div class="input-field col s12 m6">
															<input type="text" class="datepicker" id="doj" name="doj" data-error=".errorTxt18">
																<label for="doj">Date of Joining*</label>
															<div class="errorTxt18"></div>
														</div>
														
														<div class="input-field col s12 m6">
														<label for="salary">Salary*</label>
															<input id="salary" name="salary" type="text" data-error=".errorTxt19">
															<div class="errorTxt19"></div>
														</div>
														<div class="clearfix" ></div>
														<div class="input-field col s12 m6">
														<label for="salary">Old IC Number</label>
															<input id="old_ic" name="old_ic" type="text" data-error=".errorTxt20">
															<div class="errorTxt20"></div>
														</div>
														<div class="input-field col s12 m6">
														<label for="new_ic">New IC Number*</label>
															<input id="new_ic" name="new_ic" type="text" data-error=".errorTxt21">
															<div class="errorTxt21"></div>
														</div>
														<div class="clearfix" ></div>
														<?php 
															$auth_user = Auth::user();
															
															$check_union = $auth_user->hasRole('union');
															if($check_union){
																$branch_requird = 'required';
																$branch_hide = '';
																$branch_id = '';
																$member_status = 2;
															}else{
																$branch_requird = '';
																$branch_hide = 'hide';
																$branch_id = $auth_user->branch_id;
																$member_status = 1;
															}
															$branch_requird = 'required';
															$branch_hide = '';
														?>
														<div class=" col s12 m6 union-data {{ $branch_hide }}">
														<label>Company Name*</label>
															<select name="company_id" id="company" class="error browser-default" {{ $branch_requird }}>
															<option value="">Select Company</option>
																@foreach($data['company_view'] as $value)
																<option value="{{$value->id}}">{{$value->company_name}}</option>
																@endforeach
															</select>
															<div class="input-field">      
																<div class="errorTxt22"></div>
															</div>
														</div>
														
														<div class="col s12 m6 union-data {{ $branch_hide }}">
														 <label>Branch Name*</label>
															<select name="branch_id" id="branch" class="error browser-default" {{ $branch_requird }}>
																<option value="">Select Branch</option>
																<?php 
																	//  if(!$check_union){
																	//      echo '<option selected >'.$branch_id.'</option>';
																	//  }
																?>
															</select>
															<div class="input-field">      
																<div class="errorTxt23"></div>
															</div>       
														</div>
														<div class="clearfix" style="clear:both"></div>
														<div class=" col s12 m6 hide">
														<label>Status*</label>
															<select name="status_id" id="status_id" class="error browser-default">
															@foreach($data['status_view'] as $key=>$value)
																   <option <?php echo $value->id ==$member_status ? 'selected': ''; ?> value="{{$value->id}}">{{$value->status_name}}</option>
															@endforeach
															</select>
																   
															<div class="errorTxt24"></div>
														</div>
														
												  </div>
												
											</div>
										</div>
										
										<div id="test3" class="col s12">
											</br>
											<div class="row">
												<div class="col s12 m6">
													<label for="new_fee_id">Fee name* </label>
													<select name="new_fee_id" id="new_fee_id" class="error browser-default">
														<option value="">Select</option>
														@foreach($data['fee_list'] as $key=>$value)
															<option data-feename="{{$value->fee_name}}" value="{{$value->id}}">{{$value->fee_name}}</option>
														@endforeach
													</select>
													<div class="input-field">
														 <div class="errorTxt50"></div>
													</div>  
												</div>
												
												<div class="input-field col s12 m6">
												   <label for="fee_amount">Fee amount *</label>
												   <input id="fee_amount" name="fee_amount" value=""  type="text">
												   
												   
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
													
													<?php // print_r($data['nominee_view']); ?>
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
										<div id="test2" class="col s12">
											</br>
											<div class="row">
												<div class="input-field col s12 m4">
													<label for="nominee_name">Nominee name* </label>
													<input id="nominee_name" name="nominee_name" value=""  type="text">
												</div>
												<div class="col s12 m4">
													<div class="row">
														<div class=" col s12 m8">
															<p>
																<label for="nominee_dob">DOB *</label>
																<input id="nominee_dob" name="nominee_dob" value="" class="datepicker"  type="text"> 
																
																</label>
															</p>
														</div>
														<div class="col s12 m4">
															<label for="nominee_dob">Age</label>
															<span> 
															<input type="text" id="nominee_age">
															</span>
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
															@foreach($data['relationship_view'] as $key=>$value)
																<option value="{{$value->id}}" >{{$value->relation_name}}</option>
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
													<label for="nric_o">NRIC-O *</label>
													<input id="nric_o" name="nric_o" value=""  type="text">
													
												</div>
												<div class="clearfix"> </div>
												<div class="input-field col s12 m4">
													<label for="nominee_address_one">Address Line 1*</label>
													<input id="nominee_address_one" name="nominee_address_one" type="text" value="" >
												</div>
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
												<div class="clearfix"> </div>
												<div class="input-field col s12 m4">
													<label for="nominee_address_two">Address Line 2*</label>
													<input id="nominee_address_two" name="nominee_address_two" type="text" value="" >
													 
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
												<div class="input-field col s12 m4">
													<label for="nominee_postal_code">Postal code*</label>
													<input id="nominee_postal_code" name="nominee_postal_code" type="text" value="" >
													 
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
												</div>
											</div>
										</div>
										<div id="test4" class="col s12">
											</br>
											
											<div class="row">
												<div class="input-field col s12 m4">
													<label for="guardian_name">Guardian name* </label>
													<input id="guardian_name" name="guardian_name" value=""  type="text" >
												</div>
												<div class="input-field col s12 m4">
													<label for="years">Years *</label>
													<input id="years" name="years" value=""  type="text">
												</div>
												<div class="input-field col s12 m4">
													<label for="sex">SEX *</label>
													<input id="sex" name="sex" value=""  type="text">
													
												</div>
												<div class="clearfix"> </div>
												<div class="col s12 m4">
													<label>Relationship*</label>
													<select name="relationship_id" id="relationship" data-error=".errorTxt31"  class="error browser-default">
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
													<label for="nric_o_guardian">NRIC-O *</label>
													<input id="nric_o_guardian" name="nric_o_guardian" value=""  type="text">
													
												</div>
												<div class="clearfix"> </div>
												<div class="input-field col s12 m4">
													<label for="guardian_address_one">Address Line 1*</label>
													<input id="guardian_address_one" name="guardian_address_one" type="text" value="" >
													 
												</div>
												<div class="col s12 m4">
													 <label>Country Name*</label>
													<select name="guardian_country_id" id="guardian_country_id"  class="error browser-default">
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
												<div class="clearfix"> </div>
												<div class="input-field col s12 m4">
													<label for="guardian_address_two">Address Line 2*</label>
													<input id="guardian_address_two" name="guardian_address_two" type="text" value="" >
													 
												</div>
												<div class="col s12 m4">
													 <label>City Name*</label>
													<select name="guardiancity_id" id="guardian_city_id"  class="error browser-default">
														<option value="" >Select</option>
													</select>
													<div class="input-field">
														 <div class="errorTxt36"></div>
													</div>       
													
												</div>
												<div class="input-field col s12 m4">
													<label for="guardian_postal_code">Postal code*</label>
													<input id="guardian_postal_code" name="guardian_postal_code" type="text" value="" >
													 
												</div>
												<div class="clearfix"> </div>
												<div class="input-field col s12 m4">
													<label for="guardian_address_three">Address Line 2*</label>
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
										<div class="input-field col s12">
											<button class="btn waves-effect waves-light right submit" type="submit" name="action">Submit
											</button>
										</div>
										</form>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<!--script src = "{{ asset('public/assets/js/materialize.min.js') }}" type="text/javascript"></script-->
<script src="{{ asset('public/assets/vendors/noUiSlider/nouislider.js') }}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script src="{{ asset('public/assets/js/scripts/form-elements.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jquery.autocomplete.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/scripts/form-validation.js')}}" type="text/javascript"></script>


@endsection
@section('footerSecondSection')
<script>

$(document).ready(function(){
      //$('tabs').tabs();
     
      $('#member_old_div').hide();
      $('#rejoined').click(function(){
        $('#member_old_div').toggle();
       
        var  oldMemberID = $('#old_mumber_number').val();
        
        if(oldMemberID!= '' && oldMemberID != 'undefined'){
            $.ajax({
                type:"GET",
                dataType: "json",
                url:" {{ URL::to('/get-oldmember-list') }}",
                success:function(res){
                    if(res){
                    $("#old_mumber_number").empty();
                    //console.log('hi test');
                    $.each(res,function(key,entry){
                        $("#old_mumber_number").append($('<option></option>').attr('value', entry.member_number).text(entry.member_number));
                    });
                }else{
                   $("#old_mumber_number").empty();
                }
                console.log(res);
                }
            });
        }

    });
      //state
      $('#country').change(function(){
        var countryID = $(this).val();   
        
        if(countryID){
            $.ajax({
            type:"GET",
            dataType: "json",
            url:" {{ URL::to('/get-state-list') }}?country_id="+countryID,
            success:function(res){               
                if(res){
                   // console.log(res);
                    //console.log('hi test');
                    $("#state_id").empty();
                    $("#state_id").append($('<option></option>').attr('value', '').text("Select State"));
                    $.each(res,function(key,entry){
                        
                        $("#state_id").append($('<option></option>').attr('value', entry.id).text(entry.state_name));
                       // var select = $("#state");
                       // select.material_select('destroy');
                        //select.empty();
                        
                    });
                }else{
                    $("#state_id").empty();
                }
                console.log(res);
            }
            });
        }else{
            $("#state").empty();
            $("#city").empty();
        }      
    });
    //$("#country").trigger('change');
   // $("#state_id").trigger('change');
    $('#state_id').change(function(){
       var StateId = $(this).val();
      
       if(StateId!='' && StateId!='undefined')
       {
         $.ajax({
            type: "GET",
            dataType: "json",
            url : "{{ URL::to('/get-cities-list') }}?State_id="+StateId,
            success:function(res){
                console.log(res);
                if(res)
                {
                    $('#city').empty();
                    $("#city").append($('<option></option>').attr('value', '').text("Select City"));
                    $.each(res,function(key,entry){
                        $('#city').append($('<option></option>').attr('value',entry.id).text(entry.city_name));
                        
                    });
                }else{
                    $('#city').empty();
                }
               // console.log(res);
            }
         });
       }else{
           $('#city').empty();
       }
   });
   $('#company').change(function(){
       var CompanyID = $(this).val();
      
       if(CompanyID!='' && CompanyID!='undefined')
       {
         $.ajax({
            type: "GET",
            dataType: "json",
            url : "{{ URL::to('/get-branch-list') }}?company_id="+CompanyID,
            success:function(res){
                //console.log(res);
                if(res)
                {
                    $('#branch').empty();
                    
                    $.each(res,function(key,entry){
                        $('#branch').append($('<option></option>').attr('value',entry.id).text(entry.branch_name)); 
                    });
                }else{
                    $('#branch').empty();
                }
                console.log(res);
            }
         });
       }else{
           $('#city').empty();
       }
   });
    });
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd'
    });
</script>
<script>
	$("#membership_sidebar_a_id").addClass('active');
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
            },
            gender: {
                required: true,
            },
            name: {
                required: true,
            },
            phone: {
                required: true,
            },
            email: {
                required: true,
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
            city: {
                required: true,
            },
            postal_code: {
                required: true,
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
			guardian_name : {
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
            phone: {
                required: "Please Enter your Number",
                
            },
            email: {
                required: "Please enter valid email",
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
//     document.addEventListener('DOMContentLoaded', function() {
//     var elems = document.querySelectorAll('.autocomplete');
//     var instances = M.Autocomplete.init(elems, options);
//   });


  // Or with jQuery

  

  
$(function() {
    // var options = {
    //     url: function(phrase) {
    //         return "https://restcountries.eu/rest/v2/all?fields=name";
    //     },

    //     getValue: "name"
    // };

        //$("#old_mumber_number").autocomplete(options);
        // $('#old_mumber_number').keyup(function(){
        //     var searchkey = $(this).val();
        //         $.ajax({
        //         type: 'GET',
        //         url: 'https://restcountries.eu/rest/v2/all?fields=name',
        //        // url: "{{ URL::to('/get-oldmember-list') }}?number="+searchkey,
        //         success: function(response) {
        //             var countryArray = response;
        //             var dataCountry = {};
        //             for (var i = 0; i < countryArray.length; i++) {
        //             //console.log(countryArray[i].name);
        //             dataCountry[countryArray[i].name] = countryArray[i].flag; //countryArray[i].flag or null
        //             }
        //             $('input#old_mumber_number').autocomplete({
        //                 data: dataCountry,
        //                 limit: 5, // The max amount of results that can be shown at once. Default: Infinity.
        //             });
        //         }
        //         });
        //     });
    });
    $("#country_test").devbridgeAutocomplete({
        //lookup: countries,
        serviceUrl: "{{ URL::to('/get-oldmember-list') }}?key=1",
        type:'GET',
        //callback just to show it's working
        onSelect: function (suggestion) {
             console.log('You selected: ' + suggestion.value + ', ' + suggestion.data);
        },
        showNoSuggestionNotice: true,
        noSuggestionNotice: 'Sorry, no matching results',
    });
</script>
@include('membership.member_common_script');
@endsection