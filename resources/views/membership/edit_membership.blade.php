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
						</div>
						<div class="col s12">
							<div class="card">
								<div class="card-content">
									<h4 class="card-title">Edit Membership</h4>
									@include('includes.messages')
                                    @php
                                    $get_roles = Auth::user()->roles;
									$user_role = $get_roles[0]->slug;
                                    @endphp
									<div class="row">
                                        <div class="col s12">
                                            <ul class="tabs">
                                            <li class="tab col m3"><a class="active"  href="#test1">Membership details</a></li>
                                            <li class="tab col m3"><a href="#test3">Fee Details</a></li>
                                            <li class="tab col m3"><a href="#test2">Nominee Details</a></li>
                                            <li class="tab col m3"><a href="#test4">Guardian Details</a></li>
                                            </ul>
                                        </div>
                                        <form class="formValidate" id="member_formValidate" method="post" action="{{ url('membership_update') }}">
                                          @foreach($data['member_view'] as $key=>$values)
                                          @csrf
                                          
										<div id="test1" class="col s12">
                                        <div id="view-validations">
                                        </br>
                                      <div class="row">
                                        <div class="col s12 m6">
                                        <input id="auto_id" name="auto_id" value="{{$values->mid}}"  type="text" class="hide">
                                            <label>Member Title*</label>
                                            <select name="member_title" id="member_title" data-error=".errorTxt1"  class="error browser-default">
                                                <option value="" disabled selected>Choose your option</option>
                                                @foreach($data['title_view'] as $key=>$value)
                                            <option value="{{$value->id}}" <?php if($value->id == $values->member_title_id) { echo "selected";} ?>>{{$value->person_title}}</option>
                                            @endforeach
                                                </select>
                                                
                                          <div class="errorTxt1"></div>
                                        </div>
                                        <div class="input-field col s12 m6">
                                          <label for="member_number">Member Number *</label>
                                          <input id="member_number" name="member_number" value="{{$values->member_number}}" readonly type="text" data-error=".errorTxt29">
                                          <div class="errorTxt29"></div>
                                        </div>
                                        <div class="input-field col s12 m6">
                                          <label for="name">Member Name *</label>
                                          <input id="name" name="name" value="{{$values->name}}" type="text" data-error=".errorTxt30">
                                          <div class="errorTxt30"></div>
                                        </div>
                                        @php
                                        @endphp
                                        <div class="input-field col s12 m6">
                                        <div class="col s12 row">
                                                <div class="col s12 m4">
                                                    <p>Gender</p>
                                                </div>
                                                <div class="col s12 m4">
                                                    <label>
                                                        <input class="validate" required="" aria-required="true" id="gender" name="gender" type="radio" value="female" {{ $values->gender == 'female' ? 'checked' : '' }}>
                                                        <span>Female</span>
                                                    </label>  
                                                </div>
                                                <div class="col s12 m4">
                                                    <p>
                                                        <label>
                                                        <input class="validate" required="" aria-required="true" id="gender" name="gender" type="radio" value="male" {{ $values->gender == 'male' ? 'checked' : '' }}>
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
                                          <input id="phone" name="phone" value="{{$values->phone}}" type="text" data-error=".errorTxt24">
                                          <div class="errorTxt24"></div>
                                        </div>
                                        <div class="input-field col s12 m6">
                                          <label for="email">Email *</label>
                                          <input id="email" name="email" readonly type="text" value="{{$values->email}}" data-error=".errorTxt25">
                                          <div class="errorTxt25"></div>
                                        </div>
                                        <div class="input-field col s12 m6">
                                          
                                          <input type="text" value="{{ date('d/M/Y',strtotime($values->doe)) }}"  class="datepicker" id="doe" name="doe">
                                            <label for="doe">Date of Emp</label>
                                          <div class="errorTxt26"></div>
                                        </div>
                                            <div class="col s12 m6">
                                                @if($user_role!='member')
                                                 <div class="input-field col s12 m6">
                                                    <p>
                                                    <label>
                                                        <input type="checkbox" id="rejoined" <?php echo $values->old_member_number!="" ? 'checked' : ''; ?>/>
                                                        <span>Rejoined</span>
                                                        
                                                    </label>
                                                    </p>
                                                 </div>
                                                 <div class="col s12 m6 " style="display:<?php echo $values->old_member_number!="" ? 'block' : 'none'; ?>" id="member_old_div">
                                                 <span> 
                                                 <input type="text" value="{{$values->old_member_number}}" id="old_mumber_number" name="old_mumber_number">
                                                
                                                 </span>
                                                 </div>
                                                 @else
                                                 <input type="checkbox" id="rejoined" class="hide" <?php echo $values->old_member_number!="" ? 'checked' : ''; ?>/>
                                                     </br>
                                                     <?php echo $values->old_member_number!="" ? 'Old Number: '.$values->old_member_number : ''; ?>
                                                 @endif
                                            </div>
                                            <div class="clearfix" style="clear:both"></div>

                                            <div class="col s12 m6">
                                                <label>Designation*</label>
                                                    <select name="designation" id="designation" data-error=".errorTxt2"  class="error browser-default">
														<option value="" >Select</option>
                                                        @foreach($data['designation_view'] as $key=>$value)
                                                            <option value="{{$value->id}}" <?php if($value->id == $values->designation_id) { echo "selected";} ?>>{{$value->designation_name}}</option>
                                                        @endforeach
                                                   </select>
                                                       
                                                   <div class="input-field">
                                                     <div class="errorTxt2"></div>
                                                </div>   
                                            </div>
                                            <div class="col s12 m6">
                                            <label>Race*</label>
                                                <select name="race" id="race" data-error=".errorTxt3"  class="error browser-default">
													<option value="" >Select</option>
                                                    @foreach($data['race_view'] as $key=>$value)
                                                         <option value="{{$value->id}}" <?php if($value->id == $values->race_id) { echo "selected";} ?>>{{$value->race_name}}</option>
                                                    @endforeach
                                                 </select>
                                                 <div class="input-field">
                                                     <div class="errorTxt3"></div>
                                                </div>   
                                            </div>
                                            <div class="col s12 m6">
                                            <label>Country Name*</label>
                                                <select name="country_id" id="country_id" data-error=".errorTxt4"  class="error browser-default">
													<option value="" >Select</option>
                                                    @foreach($data['country_view'] as $value)
                                                        <option value="{{$value->id}}" <?php if($value->id == $values->country_id) { echo "selected";} ?>>{{$value->country_name}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="input-field">
                                                     <div class="errorTxt4"></div>
                                                </div>       
                                                
                                            </div>
                                            <div class="col s12 m6">
                                                <label>State Name*</label>
                                                <select name="state_id" id="state_id" data-error=".errorTxt5"  class="error browser-default">
													<option value="" >Select</option>
                                                    @foreach($data['state_view'] as $key=>$value)
                                                        <option value="{{$value->id}}" <?php if($value->id == $values->state_id) { echo "selected";} ?>>{{$value->state_name}}</option>
                                                    @endforeach
                                                </select>
                                                      
                                                <div class="input-field">
                                                     <div class="errorTxt5"></div>
                                                </div>   
                                            </div>
                                            <div class="clearfix" style="clear:both"></div>
                                            <div class="col s12 m6">
                                                 <label>City Name*</label>
                                                <select name="city_id" id="city_id" data-error=".errorTxt6" class="error browser-default">
													<option value="" >Select</option>
                                                    @foreach($data['city_view'] as $key=>$value)
                                                    <option value="{{$value->id}}" <?php if($value->id == $values->city_id) { echo "selected";} ?>>{{$values->city_name}}</option>
                                                    @endforeach
                                                </select>
                                                       
                                                <div class="input-field">
                                                     <div class="errorTxt6"></div>
                                                </div>   
                                            </div>
                                            <div class="input-field col s12 m6">
                                            <label for="postal_code">Postal Code *</label>
                                                <input id="postal_code" name="postal_code" value="{{$values->postal_code}}" type="text" data-error=".errorTxt7">
                                                <div class="errorTxt7"></div>
                                            </div>
                                            <div class="input-field col s12 m6">
                                            <label for="address_one">Address Line 1*</label>
                                                <input id="address_one" name="address_one" type="text" value="{{$values->address_one}}" data-error=".errorTxt8">
                                                <div class="errorTxt8"></div>
                                            </div>
                                            <div class="input-field col s12 m6">
                                            <label for="address_two">Address Line 2*</label>
                                                <input id="address_two" name="address_two" type="text" value="{{$values->address_two}}" data-error=".errorTxt9">
                                                <div class="errorTxt9"></div>
                                            </div>
                                            <div class="input-field col s12 m6">
                                            <label for="address_three">Address Line 3*</label>
                                                <input id="address_three" name="address_three" type="text" value="{{$values->address_three}}" data-error=".errorTxt10">
                                                <div class="errorTxt10"></div>
                                            </div>
                                            <div class="col s12 m6">
                                                <div class="row">
                                                    <div class="input-field col s12 m8">
                                                        <label for="dob">Date of Birth *</label>
                                                        <input id="dob" name="dob" value="{{ date('d/M/Y',strtotime($values->dob)) }}" data-reflectage="member_age" class="datepicker"  type="text"> 
                                                    </div>
                                                    <div class="input-field col s12 m4">
                                                        <label for="member_age">Age</label>
                                                        <input type="text" id="member_age" value="{{ CommonHelper::calculate_age($values->dob) }}" readonly >
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="input-field col s12 m6">
                                                <input type="text" class="datepicker" id="doj" value="{{ date('d/M/Y',strtotime($values->doj)) }}" name="doj">
                                                    <label for="doj">Date of Joining</label>
                                                <div class="errorTxt"></div>
                                            </div>
                                            
                                            <div class="input-field col s12 m6">
                                            <label for="salary">Salary</label>
                                                <input id="salary" name="salary" value="{{$values->salary}}" type="text" data-error=".errorTxt11">
                                                <div class="errorTxt11"></div>
                                            </div>
                                            <div class="input-field col s12 m6">
                                            <label for="salary">Old IC Number</label>
                                                <input id="old_ic" name="old_ic" value="{{$values->old_ic}}" type="text" data-error=".errorTxt12">
                                                <div class="errorTxt12"></div>
                                            </div>
                                            <div class="input-field col s12 m6">
                                            <label for="new_ic">New IC Number</label>
                                                <input id="new_ic" name="new_ic" type="text" value="{{$values->new_ic}}" data-error=".errorTxt13">
                                                <div class="errorTxt13"></div>
                                            </div>
                                            <div class="col s12 m6 hide">
                                                <label>Company Name*</label>
                                                <select name="company_id" id="company" class="error browser-default">
													<option value="" >Select</option>
                                                    @foreach($data['company_view'] as $value)
                                                    <option <?php //if($value->id == $values->company_id) { echo "selected";} ?> value="{{$value->id}}">{{$value->company_name}}</option>
                                                    @endforeach
                                                 </select>
                                                <div class="input-field">     
                                                    <div class="errorTxt14"></div>
                                                </div>
                                            </div>
                                            
                                            <?php 
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
                                            ?>
                                            
                                            <div class=" col s12 m6 {{ $branch_hide }}">
                                                <label>Branch Name*</label>
                                                <select name="branch_id" id="branch" data-error=".errorTxt15" class="error browser-default">
													<option value="" >Select</option>
                                                     @foreach($data['branch_view'] as $value)
                                                    <option <?php if($value->id == $values->branch_id) { echo "selected";} ?> value="{{$value->id}}">{{$value->branch_name}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="input-field">           
                                                     <div class="errorTxt15"></div>
                                                </div>
                                            </div>
                                            <div class="input-field col s12 m6">
                                                <label for="employee_id">Employee ID</label>
                                                <input id="employee_id" name="employee_id" value="{{$values->employee_id}}" type="text">
                                            </div>
											@php
											if($values->status_id==1 && $check_union==1){
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
											if($values->status_id==1 && $check_union!=1){
											@endphp
                                            <div class="col s12 m6 ">
                                                <label>Status*</label>
                                                <span class="badge red">Account is not verified</span>
                                            </div>
											@php												
											}else{
											@endphp
                                            <div class="col s12 m6 ">
                                                <label>Status*</label>
                                                <span class=" badge gradient-45deg-light-blue-cyan">Account is verified</span>
												
                                            </div>
                                            @php												
											}
											@endphp
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
                                                <i class="material-icons right">send</i>
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
                                                    @foreach($data['fee_view'] as $key=>$value)
                                                    <tr id="nominee_{{ $sl }}">
                                                        <td><span id="fee_name_label_{{ $sl }}">{{ CommonHelper::get_fee_name($value->fee_id) }}</span>
                                                            <input type="text" class="hide" name="fee_auto_id[]" id="fee_auto_id_{{ $sl }}" value="{{$value->id}}"></input>
                                                            <input type="text" class="hide" name="fee_name_id[]" id="fee_name_id_{{ $sl }}" value="{{$value->fee_id}}"></input>
                                                        </td>
                                                        <td><span id="fee_amount_label_{{ $sl }}">{{$value->fee_amount}}</span><input type="text" class="hide" name="fee_name_amount[]" id="fee_name_amount_{{ $sl }}" value="{{$value->fee_amount}}"></td>
                                                        <td>
                                                        <a class="btn-small waves-effect waves-light cyan edit_fee_row " href="#modal_fee" data-id="{{$sl}}">Edit</a>
														<a class="btn-small waves-effect waves-light amber darken-4 delete_fee_db" data-id="{{$sl}}" data-autoid="{{$value->id}}" onclick="if (confirm('Are you sure you want to delete?')) return true; else return false;">Delete</a>
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
                                    <div id="test2" class="col s12">
                                    </br>
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
														<option value="" >Select</option>
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
													<option value="" >Select</option>
                                                    @foreach($data['state_view'] as $key=>$value)
                                                        <option value="{{$value->id}}" <?php if($value->id == $values->state_id) { echo "selected";} ?>>{{$value->state_name}}</option>
                                                    @endforeach
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
													<option value="" >Select</option>
                                                    @foreach($data['city_view'] as $key=>$value)
                                                    <option value="{{$value->id}}" <?php if($value->id == $values->city_id) { echo "selected";} ?>>{{$values->city_name}}</option>
                                                    @endforeach
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
                                                <i class="material-icons right">send</i>
                                            </button>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col s12">
                                                
                                                <?php // print_r($data['nominee_view']); ?>
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
                                                        <a class="btn-small waves-effect waves-light cyan edit_nominee_row " href="#modal_nominee" data-id="{{$sl}}">Edit</a>
														<a class="btn-small waves-effect waves-light amber darken-4 delete_nominee_db" data-id="{{$value->id}}" onclick="if (confirm('Are you sure you want to delete?')) return true; else return false;">Delete</a>
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
                                    <div id="test4" class="col s12">
                                    </br>
                                    <?php $row = $data['member_view'];?>
                                    <div class="row">
                                            <?php $gardian_row = $data['gardian_view'][0];  ?>
                                            <div class="input-field col s12 m4">
                                                <label for="guardian_name">Guardian name* </label>
                                                <input id="guardian_name" name="guardian_name" value="<?php echo $gardian_row->guardian_name; ?>"  type="text" >
                                            </div>
                                            <div class="col s12 m4">
                                                <div class="row">
                                                    <div class=" col s12 m8">
                                                        <p>
                                                            <label for="gaurdian_dob">DOB *</label>
                                                            <input id="gaurdian_dob" name="gaurdian_dob" data-reflectage="gaurdian_age" value="{{ date('d/M/Y',strtotime($gardian_row->dob)) }}" class="datepicker"  type="text"> 
                                                            
                                                        </p>
                                                    </div>
                                                    <div class="col s12 m4">
                                                        <label for="gaurdian_age">Age</label>
                                                        <span> 
                                                        <input type="text" id="gaurdian_age" value="{{ CommonHelper::calculate_age($gardian_row->dob) }}" readonly >
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col s12 m4">
                                                <label for="guardian_sex">SEX *</label>
                                                <select name="guardian_sex" id="guardian_sex" class="error browser-default">
                                                    <option value="">Select</option>
                                                    <option <?php echo $gardian_row->gender=='male' ? 'selected': ''; ?> value="male" >Male</option>
                                                    <option <?php echo $gardian_row->gender=='female' ? 'selected': ''; ?> value="female" >Female</option>
                                                </select>
                                                
                                            </div>
                                            
                                            <div class="clearfix"> </div>
                                            <div class="col s12 m4">
                                                 <label>Relationship*</label>
                                                    <select name="relationship_id" id="relationship" data-error=".errorTxt31"  class="error browser-default">
                                                       <option value="" >Select</option> @foreach($data['relationship_view'] as $key=>$value)
                                                            <option <?php echo $gardian_row->relationship_id==$value->id ? 'selected': ''; ?> value="{{$value->id}}" >{{$value->relation_name}}</option>
                                                        @endforeach
                                                   </select>
                                                       
                                                   <div class="input-field">
                                                     <div class="errorTxt31"></div>
                                                   </div>   
                                                
                                            </div>
                                            <div class="input-field col s12 m4">
                                                <label for="nric_n_guardian">NRIC-N *</label>
                                                <input id="nric_n_guardian" name="nric_n_guardian" value="<?php echo $gardian_row->nric_n; ?>"  type="text">
                                                
                                            </div>
                                            <div class="input-field col s12 m4">
                                                <label for="nric_o_guardian">NRIC-O </label>
                                                <input id="nric_o_guardian" name="nric_o_guardian" value="<?php echo $gardian_row->nric_o; ?>"  type="text">
                                                
                                            </div>
                                            <div class="clearfix"> </div>
                                            <div class="input-field col s12 m4">
                                                <label for="guardian_address_one">Address Line 1*</label>
                                                <input id="guardian_address_one" name="guardian_address_one" type="text" value="<?php echo $gardian_row->guardian_name; ?>" >
                                                 
                                            </div>
                                            <div class="col s12 m4">
                                                 <label>Country Name*</label>
                                                <select name="guardian_country_id" id="guardian_country_id"  class="error browser-default">
													<option value="" >Select</option>
                                                    @foreach($data['country_view'] as $value)
                                                        <option <?php echo $gardian_row->country_id == $value->id ? 'selected' : ''; ?> value="{{$value->id}}" >{{$value->country_name}}</option>
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
                                                    @foreach($data['state_view'] as $key=>$value)
                                                        <option value="{{$value->id}}" <?php if($value->id == $gardian_row->state_id) { echo "selected";} ?>>{{$value->state_name}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="input-field">
                                                     <div class="errorTxt36"></div>
                                                </div>       
                                                
                                            </div>
                                            <div class="clearfix"> </div>
                                            <div class="input-field col s12 m4">
                                                <label for="guardian_address_two">Address Line 2*</label>
                                                <input id="guardian_address_two" name="guardian_address_two" type="text" value="<?php echo $gardian_row->address_two; ?>" >
                                                 
                                            </div>
                                            <div class="col s12 m4">
                                                 <label>City Name*</label>
                                                <select name="guardiancity_id" id="guardian_city_id"  class="error browser-default">
													<option value="" >Select</option>
                                                    @foreach($data['city_view'] as $key=>$value)
                                                    <option value="{{$value->id}}" <?php if($value->id == $gardian_row->city_id) { echo "selected";} ?>>{{$values->city_name}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="input-field">
                                                     <div class="errorTxt36"></div>
                                                </div>       
                                                
                                            </div>
                                            <div class="input-field col s12 m4">
                                                <label for="guardian_postal_code">Postal code*</label>
                                                <input id="guardian_postal_code" name="guardian_postal_code" type="text" value="<?php echo $gardian_row->postal_code; ?>" >
                                                 
                                            </div>
                                            <div class="clearfix"> </div>
                                            <div class="input-field col s12 m4">
                                                <label for="guardian_address_three">Address Line 3*</label>
                                                <input id="guardian_address_three" name="guardian_address_three" type="text" value="<?php echo $gardian_row->address_three; ?>" >
                                                 
                                            </div>
                                            <div class="input-field col s12 m4">
                                                <label for="guardian_mobile">Mobile No*</label>
                                                <input id="guardian_mobile" name="guardian_mobile" type="text" value="<?php echo $gardian_row->mobile; ?>" >
                                                 
                                            </div>
                                            <div class="input-field col s12 m4">
                                                <label for="guardian_phone">Phone No</label>
                                                <input id="guardian_phone" name="guardian_phone" type="text" value="<?php echo $gardian_row->phone; ?>" >
                                                 
                                            </div>
                                            <div class="clearfix"> </div>
                                            </div>
                                    </div>
                                    </div>
                                   
										<div class="input-field col s12">
                                          <button class="btn waves-effect waves-light right submit" type="submit" name="action">Submit
                                          </button>
                                        </div>
										 @endforeach
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
<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/assets/vendors/noUiSlider/nouislider.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/scripts/form-elements.js') }}" type="text/javascript"></script>
@endsection

@section('footerSecondSection')
<script src="{{ asset('public/assets/js/scripts/form-validation.js')}}" type="text/javascript"></script>
<script>

$(document).ready(function(){
      $('tabs').tabs();
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
            dob: {
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
      //$('#member_old_div').hide();
      $('#rejoined').click(function(){
        $('#member_old_div').toggle();
       
        var  oldMemberID = $('#old_mumber_number').val();

    });
      //state
      
   // $("#country").trigger('change');
    //$("#company").trigger('change');
    
    });
    
    // $('#add_nominee').click(function(){
    //     var auto_id =   $("#auto_id").val();
    //     var nominee_name =   $("#nominee_name").val();
    //     var nominee_dob =   $("#nominee_dob").val();
    //     var nominee_sex =   $("#sex").val();
    //     var nominee_relationship =   $("#relationship").val();
    //     var nric_n =   $("#nric_n").val();
    //     var nric_o =   $("#nric_o").val();
    //     var nominee_address_one =   $("#nominee_address_one").val();
    //     var nominee_country_id =   $("#nominee_country_id").val();
    //     var nominee_state_id =   $("#nominee_state_id").val();
    //     var nominee_address_two =   $("#nominee_address_two").val();
    //     var nominee_city_id =   $("#nominee_city_id").val();
    //     var nominee_postal_code =   $("#nominee_postal_code").val();
    //     var nominee_address_three =   $("#nominee_address_three").val();
    //     var nominee_mobile =   $("#nominee_mobile").val();
    //     var nominee_phone =   $("#nominee_phone").val();
    //     $.ajaxSetup({
    //             headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //             }
    //         });
    //     if(nominee_name!="" && nominee_dob!="" && nominee_sex!="" && nominee_relationship!="" && 
    //     nric_n!="" && nric_o!="" && nominee_address_one!="" && nominee_country_id!="" && nominee_state_id!="" && 
    //     nominee_address_two!="" && nominee_city_id!="" && nominee_postal_code != "" && nominee_address_three!="" && nominee_mobile!=""){
    //        $("#add_nominee").attr('disabled',true);
    //         $.ajax({
    //             method: 'POST', // Type of response and matches what we said in the route
    //             url: "{{ URL::to('/add-nominee') }}", // This is the url we gave in the route
    //             data: { 
    //                 'auto_id' : auto_id,
    //                 'nominee_name' : nominee_name,
    //                 'nominee_dob' : nominee_dob,
    //                 'nominee_sex' : nominee_sex,
    //                 'nominee_relationship' : nominee_relationship,
    //                 'nric_n' : nric_n,
    //                 'nric_o' : nric_o,
    //                 'nominee_address_one' : nominee_address_one,
    //                 'nominee_country_id' : nominee_country_id,
    //                 'nominee_state_id' : nominee_state_id,
    //                 'nominee_address_two' : nominee_address_two,
    //                 'nominee_city_id' : nominee_city_id,
    //                 'nominee_postal_code' : nominee_postal_code,
    //                 'nominee_address_three' : nominee_address_three,
    //                 'nominee_mobile' : nominee_mobile,
    //                 'nominee_phone' : nominee_phone,
    //             }, // a JSON object to send back
    //             dataType: "json",
    //             success: function(response){ // What to do if we succeed
    //                 $("#add_nominee").attr('disabled',false);
    //                 var alert_confirm = "confirm('Are you sure you want to delete?')";
    //                 console.log(response.data); 
    //                 if(response.status ==1){
    //                     var new_row = '<tr>';
    //                     new_row += '<td id="nominee_name_'+ response.data.nominee_id +'">'+nominee_name+'</td>';
    //                     new_row += '<td id="nominee_age_'+ response.data.nominee_id +'">'+response.data.age+'</td>';
    //                     new_row += '<td id="nominee_gender_'+ response.data.nominee_id +'">'+nominee_sex+'</td>';
    //                     new_row += '<td id="nominee_relation_'+ response.data.nominee_id +'">'+response.data.relationship+'</td>';
    //                     new_row += '<td id="nominee_nricn_'+ response.data.nominee_id +'">'+nric_n+'</td>';
    //                     new_row += '<td id="nominee_nrico_'+ response.data.nominee_id +'">'+nric_o+'</td>';
    //                     new_row += '<td><a class="btn-small waves-effect waves-light cyan edit_nominee_row " href="#modal_nominee" data-id="'+response.data.nominee_id+'">Edit</a> <a class="btn-small waves-effect waves-light amber darken-4 delete_nominee" data-id="'+response.data.nominee_id+'" onclick="if ('+alert_confirm+') return true; else return false;">Delete</a></td>';
    //                     new_row += '</tr>';
    //                     $('#test2').find('input:text').val('');    
    //                     $('#nominee_table').append(new_row);
    //                     M.toast({
    //                         html: response.message
    //                     });
    //                 }
    //                 console.log(response.data); 
    //             },
    //             error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
    //                 console.log(JSON.stringify(jqXHR));
    //                 console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
    //             }
    //         });
    //     }
    //     else{
    //         $("#add_nominee").attr('disabled',false);
    //         M.toast({
    //             html: "Please fill requierd fields"
    //         });
    //     }    
    // });
   
   $('.modal').modal();
   
    // $("#nominee_formValidate").validate({
    //     rules: {
    //         edit_nominee_name: {
    //             required: true,
    //         },
    //         edit_nominee_dob: {
    //             required: true,
    //         },
    //         edit_sex: {
    //             required: true,
    //         },
    //         edit_relationship: {
    //             required: true,
    //         },
    //         edit_nric_n: {
    //             required: true,
    //         },
    //         edit_nric_o: {
    //             required: true,
    //         },
    //         edit_nominee_address_one: {
    //             required: true,
    //         },
    //         edit_nominee_country_id: {
    //             required: true,
    //         },
    //         edit_nominee_state_id: {
    //             required: true,
    //         },
    //         edit_nominee_address_two: {
    //             required: true,
    //         },
    //         edit_nominee_city_id: {
    //             required: true,
    //         },
    //         edit_nominee_postal_code: {
    //             required: true,
    //         },
    //         edit_nominee_address_three: {
    //             required: true,
    //         },
    //         edit_nominee_mobile: {
    //             required: true,
    //         },
    //     },
    //     //For custom messages
    //     messages: {
    //         edit_nominee_name: {
    //             required: "Enter a Nominee name",
    //         },
    //     },
    //     errorElement: 'div',
    //     errorPlacement: function (error, element) {
    //         var placement = $(element).data('error');
    //         if (placement) {
    //             $(placement).append(error)
    //         } else {
    //             error.insertAfter(element);
    //         }
    //     },
    //     submitHandler: function(form) {
    //         var formData = $("#nominee_formValidate").serialize();
    //         $.ajax({
    //             method: 'POST', // Type of response and matches what we said in the route
    //             url: "{{ URL::to('/update-nominee') }}", // This is the url we gave in the route
    //             data: formData, // a JSON object to send back
    //             dataType: "json",
    //             success: function(response){ // What to do if we succeed
    //                 $("#update_nominee").attr('disabled',false);
                    
    //                 console.log(response.data); 
    //                 if(response.status ==1){
    //                     var row_id = response.data.nominee_id;
    //                     $("#nominee_name_"+row_id).html(response.data.name);
    //                     $("#nominee_age_"+row_id).html(response.data.age);
    //                     $("#nominee_gender_"+row_id).html(response.data.gender);
    //                     $("#nominee_relation_"+row_id).html(response.data.relationship);
    //                     $("#nominee_nricn_"+row_id).html(response.data.nric_n);
    //                     $("#nominee_nrico_"+row_id).html(response.data.nric_o);
    //                     $('#modal_nominee').modal('close'); 
    //                 }
                   
    //                 console.log(response.data); 
    //             },
    //             error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
    //                 console.log(JSON.stringify(jqXHR));
    //                 console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
    //             }
    //         });
    //     }
    // });
    $(document.body).on('click', '.delete_nominee_db' ,function(){
        var nominee_id = $(this).data('id');
        var parrent = $(this).parents("tr");
        $.ajax({
            type: "GET",
            dataType: "json",
            url : "{{ URL::to('/delete-nominee-data') }}?nominee_id="+nominee_id,
            success:function(res){
                console.log(res);
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
               // console.log(res);
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
                console.log(res);
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
               // console.log(res);
            }
         });
    });
</script>
@include('membership.member_common_script');
@endsection