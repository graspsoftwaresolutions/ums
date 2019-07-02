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
									<div class="row">
                                        <div class="col s12">
                                            <ul class="tabs">
                                            <li class="tab col m3"><a class="active"  href="#test1">Membership details</a></li>
                                            <li class="tab col m3"><a href="#test2">Nominee Details</a></li>
                                            <li class="tab col m3"><a href="#test4">Guardian Details</a></li>
                                            </ul>
                                        </div>
                                        <form class="formValidate" id="member_formValidate" method="post" action="{{ url('membership_update') }}">
                                          @foreach($data['member_view'] as $key=>$values)
                                          @csrf
                                          
										<div id="test1" class="col s12">
                                        <div id="view-validations">
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
                                          <input id="member_number" name="member_number" value="{{$values->member_number}}"  type="text" data-error=".errorTxt29">
                                          <div class="errorTxt29"></div>
                                        </div>
                                        <div class="input-field col s12 m6">
                                          <label for="name">Member Name *</label>
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
                                                        <input class="validate" required="" aria-required="true" id="gender" name="gender" type="radio" value="female" {{ $values->gender == 'female' ? 'checked' : '' }}>
                                                        <span>Female</span>
                                                    </label>  
                                                </div>
                                                <div class="col s12 m4">
                                                    <p>
                                                        <label>
                                                        <input class="validate" required="" aria-required="true" id="gender" name="gender" type="radio" checked="" value="male" {{ $values->gender == 'male' ? 'checked' : '' }}>
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
                                            </div>
                                            <div class="clearfix" style="clear:both"></div>

                                            <div class="col s12 m6">
                                                <label>Designation*</label>
                                                    <select name="designation" id="designation" data-error=".errorTxt2"  class="error browser-default">
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
                                                <select name="country_id" id="country" data-error=".errorTxt4"  class="error browser-default">
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
                                                <select name="state_id" id="state" data-error=".errorTxt5"  class="error browser-default">
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
                                                <select name="city_id" id="city" data-error=".errorTxt6" class="error browser-default">
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
                                            <div class="input-field col s12 m6">
                                                <input type="text" class="datepicker" id="dob" value="{{ date('d/M/Y',strtotime($values->dob)) }}" name="dob">
                                                    <label for="dob">Date of Birth</label>
                                                <div class="errorTxt"></div>
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
                                                    $branch_hide = '';
                                                    $branch_id = '';
                                                }else{
                                                    $branch_requird = '';
                                                    $branch_hide = 'hide';
                                                    $branch_id = $auth_user->branch_id;
                                                }
                                            ?>
                                            
                                            <div class=" col s12 m6 {{ $branch_hide }}">
                                                <label>Branch Name*</label>
                                                <select name="branch_id" id="branch" data-error=".errorTxt15" class="error browser-default">
                                                     @foreach($data['branch_view'] as $value)
                                                    <option <?php if($value->id == $values->branch_id) { echo "selected";} ?> value="{{$value->id}}">{{$value->branch_name}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="input-field">           
                                                     <div class="errorTxt15"></div>
                                                </div>
                                            </div>
                                            <div class="col s12 m6">
                                                <label>Status*</label>
                                                <select name="status_id" id="status_id" data-error=".errorTxt16" class="error browser-default">
                                                    @foreach($data['status_view'] as $key=>$value)
                                                        <option value="{{$value->id}}" <?php if($value->id == $values->status_id) { echo "selected";} ?>>{{$value->status_name}}</option>
                                                    @endforeach
                                                </select>
                                                      
                                                <div class="input-field">       
                                                    <div class="errorTxt16"></div>
                                                </div>
                                            </div>
                                          
                                      </div>
                                     
                                    
                                  </div>
                                    </div>
                                    <div id="test2" class="col s12">
                                        <div class="row">
                                            <div class="input-field col s12 m4">
                                                <label for="nominee_name">Nominee name* </label>
                                                <input id="nominee_name" name="nominee_name" value=""  type="text" >
                                                
                                            </div>
                                            <div class="input-field col s12 m4">
                                                <label for="nominee_dob">DOB *</label>
                                                <input id="nominee_dob" name="nominee_dob" value="" class="datepicker"  type="text">
                                                
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
                                                <table id="nominee_table">
                                                    <thead>
                                                        <tr>
                                                        <th data-field="name">Name</th>
                                                        <th data-field="age">Age</th>
                                                        <th data-field="sex">Sex</th>
                                                        <th data-field="relationship">Relationship</th>
                                                        <th data-field="nric_n">NRIC-N</th>
                                                        <th data-field="nric_o">NRIC-0</th>
                                                        </tr>
                                                    </thead>
                                                <tbody>
                                                    @foreach($data['nominee_view'] as $key=>$value)
                                                    <tr>
                                                        <td>{{$value->nominee_name}}</td>
                                                        <td>{{$value->years}}</td>
                                                        <td>{{$value->gender}}</td>
                                                        <td>Father</td>
                                                        <td>{{$value->nric_n}}</td>
                                                        <td>{{$value->nric_o}}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="test4" class="col s12">
                                    
                                    <?php $row = $data['member_view'];?>
                                    <div class="row">
                                            <?php $gardian_row = $data['gardian_view'][0];  ?>
                                            <div class="input-field col s12 m4">
                                                <label for="guardian_name">Guardian name* </label>
                                                <input id="guardian_name" name="guardian_name" value="<?php echo $gardian_row->guardian_name; ?>"  type="text" >
                                            </div>
                                            <div class="input-field col s12 m4">
                                                <label for="years">Years *</label>
                                                <input id="years" name="years" value="<?php echo $gardian_row->years; ?>"  type="text">
                                            </div>
                                            <div class="input-field col s12 m4">
                                                <label for="sex">SEX *</label>
                                                <input id="sex" name="sex" value="<?php echo $gardian_row->gender; ?>"  type="text">
                                                
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
                                                <input id="nric_n_guardian" name="nric_n_guardian" value="<?php echo $gardian_row->nric_n; ?>"  type="text">
                                                
                                            </div>
                                            <div class="input-field col s12 m4">
                                                <label for="nric_o_guardian">NRIC-O *</label>
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
                                                <label for="guardian_address_two">Address Line 2*</label>
                                                <input id="guardian_address_two" name="guardian_address_two" type="text" value="<?php echo $gardian_row->address_two; ?>" >
                                                 
                                            </div>
                                            <div class="col s12 m4">
                                                 <label>City Name*</label>
                                                <select name="guardiancity_id" id="guardian_city_id"  class="error browser-default">
                                                    @foreach($data['city_view'] as $key=>$value)
                                                    <option value="{{$value->id}}" <?php if($value->id == $values->city_id) { echo "selected";} ?>>{{$values->city_name}}</option>
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
                                                <label for="guardian_address_three">Address Line 2*</label>
                                                <input id="guardian_address_three" name="guardian_address_three" type="text" value="<?php echo $gardian_row->address_two; ?>" >
                                                 
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
                                            <i class="material-icons right">send</i>
                                          </button>
                                        </div>
										 @endforeach
                                    </form>
								</div>
							</div>
						</div>
					</div>
					<!-- END: Page Main-->
				</div>
			</div>
		</div>
	</div>
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
        
        // if(oldMemberID!= '' && oldMemberID != 'undefined'){
        //     $.ajax({
        //         type:"GET",
        //         dataType: "json",
        //         url:" {{ URL::to('/get-oldmember-list') }}",
        //         success:function(res){
        //             if(res){
        //             $("#old_mumber_number").parent().find(".select-dropdown").remove();
        //             $("#old_mumber_number").parent().find("svg.caret").remove();
        //             $("#old_mumber_number").empty();
        //             $('#old_mumber_number').append($('<option></option>').attr('value','0').text('Select'));
        //             //console.log('hi test');
        //             $.each(res,function(key,entry){
        //                 $("#old_mumber_number").append($('<option></option>').attr('value', entry.member_number).text(entry.member_number));
        //             });
        //             $('#old_mumber_number').material_select();
        //         }else{
        //         $("#old_mumber_number").empty();
        //         }
        //         console.log(res);
        //         }
        //     });
        // }

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
                    $("#state").empty();
                    //console.log('hi test');
                    $.each(res,function(key,entry){
                      
                        $("#state").append($('<option></option>').attr('value', entry.id).text(entry.state_name));
                       // var select = $("#state");
                       // select.material_select('destroy');
                        //select.empty();
                        
                    });
                   // $('#state').material_select();
                }else{
                  $("#state").empty();
                }
                console.log(res);
            }
            });
        }else{
            $("#state").empty();
            $("#city").empty();
        }      
    });
   // $("#country").trigger('change');
    //$("#company").trigger('change');
    $('#state').change(function(){
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
    $('#add_nominee').click(function(){
        var auto_id =   $("#auto_id").val();
        var nominee_name =   $("#nominee_name").val();
        var nominee_years =   $("#years").val();
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
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        if(nominee_name!="" && nominee_years!="" && nominee_sex!="" && nominee_relationship!="" && 
        nric_n!="" && nric_o!="" && nominee_address_one!="" && nominee_country_id!="" && nominee_state_id!="" && 
        nominee_address_two!="" && nominee_city_id!="" && nominee_postal_code != "" && nominee_address_three!="" && nominee_mobile!=""){
           $("#add_nominee").attr('disabled',true);
            $.ajax({
                method: 'POST', // Type of response and matches what we said in the route
                url: "{{ URL::to('/add-nominee') }}", // This is the url we gave in the route
                data: { 
                    'auto_id' : auto_id,
                    'nominee_name' : nominee_name,
                    'nominee_years' : nominee_years,
                    'nominee_sex' : nominee_sex,
                    'nominee_relationship' : nominee_relationship,
                    'nric_n' : nric_n,
                    'nric_o' : nric_o,
                    'nominee_address_one' : nominee_address_one,
                    'nominee_country_id' : nominee_country_id,
                    'nominee_state_id' : nominee_state_id,
                    'nominee_address_two' : nominee_address_two,
                    'nominee_city_id' : nominee_city_id,
                    'nominee_postal_code' : nominee_postal_code,
                    'nominee_address_three' : nominee_address_three,
                    'nominee_mobile' : nominee_mobile,
                    'nominee_phone' : nominee_phone,
                }, // a JSON object to send back
                success: function(response){ // What to do if we succeed
                    $("#add_nominee").attr('disabled',false);
                    var new_row = '<tr>';
                    new_row += '<td>'+nominee_name+'</td>';
                    new_row += '<td>'+nominee_years+'</td>';
                    new_row += '<td>'+nominee_sex+'</td>';
                    new_row += '<td>'+nominee_relationship+'</td>';
                    new_row += '<td>'+nric_n+'</td>';
                    new_row += '<td>'+nric_o+'</td>';
                    new_row += '</tr>';
                    $('#test2').find('input:text').val('');    
                    $('#nominee_table').append(new_row);
                    if(response.status ==1){
                        alert(response.message);
                    }
                    console.log(response); 
                },
                error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
            });
        }
        else{
            $("#add_nominee").attr('disabled',false);
            alert("Please fill requierd fields");
        }
        
            
           

    });
</script>
@endsection