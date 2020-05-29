@extends('layouts.admin') 
@section('headSection') 
	@include('membership.member_common_styles') 
@endsection 
@section('headSecondSection')
<style>
    .padding-left-10 {
        padding-left: 10px;
    }
    
    .padding-left-20 {
        padding-left: 20px;
    }
    
    .padding-left-40 {
        padding-left: 40px;
    }
    
    #irc_confirmation_area {
        pointer-events: none;
        background-color: #f4f8fb !important;
    }
    .readonlyarea{
        pointer-events: none;
        background-color: #f4f8fb !important;  
    }
    $(".readonlyarea :input").attr("readonly", true);

    .select2 .selection .select2-selection--single, .select2-container--default .select2-search--dropdown .select2-search__field {
        border-width: 0 0 1px 0 !important;
        border-radius: 0 !important;
        height: 2.30rem !important;
    }
    
    $("#irc_confirmation_area :input").attr("readonly", true);
    .reasonsections .input-field {
        position: relative;
        margin: 0 !important;
        padding-left: 5px;
        padding-right: 5px;
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
        margin-bottom: 5px;
    }
    .inline-box{
        height: 2rem !important;
        margin-top: 10px !important;
    }

    .hidemember{
        pointer-events: none;
        background-color: #f4f8fb !important;  
    }

</style>
<link rel="stylesheet" type="text/css" href="{{ asset('public/css/wizard-app.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/css/wizard-theme.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/datepicker.css') }}">
<link class="rtl_switch_page_css" href="{{ asset('public/css/steps.css') }}" rel="stylesheet" type="text/css"> @endsection @section('main-content')
@php
    $userid = Auth::user()->id;
    $get_roles = Auth::user()->roles;
    $user_role = $get_roles[0]->slug;

    $hidemember='hide';
    if($user_role=='member'){
        $hidemember='hidemember';
    }
@endphp
<div id="">
    <div class="row">
        <div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
        <div class="col s12">
            <div class="container">
                <div class="section section-data-tables">
                    <!-- BEGIN: Page Main-->
                    <div class="row">
                        <div class="col s12">
                            <div class="card theme-mda">
                                <div class="card-content">
                                    <h4 class="card-title">Edit Membership</h4> 
									@include('includes.messages') 
									@php 

										$get_roles = Auth::user()->roles; 
										$user_role = $get_roles[0]->slug; 
										if(isset($data['member_view'])){
											$values = $data['member_view'][0]; 
										}else{ 
											echo 'invalid access'; die; 
										}
									@endphp 
									@php 
										$member_autoid = $values->mid; 
									@endphp
                                    <form class="formValidate" id="wizard2" method="post" enctype="multipart/form-data" action="{{ url(app()->getLocale().'/u_membership_save') }}">
                                        @csrf 
										
                                        
                                            </br>
                                            <div class="col-sm-8 col-sm-offset-1" >
                                                <div class="row">
                                                    <div class="col s12 m6">
                                                        <input id="auto_id" name="auto_id" value="{{$values->mid}}" type="text" class="hide">
                                                        <label>Member Title*</label>
                                                        <select name="member_title" required="" id="member_title" data-error=".errorTxt1" required class="validate error browser-default selectpicker">
                                                            <option value="" disabled selected>Choose your option</option>
                                                            @foreach($data['title_view'] as $key=>$value)
                                                            <option value="{{$value->id}}" @php if($value->id == $values->member_title_id) { echo "selected";} @endphp>{{$value->person_title}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="errorTxt1"></div>
                                                    </div>
                                                    <div class="col s12 m6 hide">
                                                        <label for="member_number" class="force-active">Member Number *</label>
                                                        <input id="member_number" name="member_number" value="{{$values->member_number}}" readonly type="text" data-error=".errorTxt29">
                                                        <div class="errorTxt29"></div>
                                                    </div>
                                                    <div class="col s12 m6">
                                                        <label for="name" class="force-active">Member Name as per NRIC *</label>
                                                        <input id="name" name="name" required="" value="{{$values->name}}" type="text" data-error=".errorTxt30">
                                                        <div class="errorTxt30"></div>
                                                            <br> 
                                                    </div>
                                                    <div class="input-field col s12 m6 hide">
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
                                                    
                                                    <div class="input-field col s12 m6 hide">
                                                        <label for="mobile" class="force-active">Mobile Number *</label>
                                                        <input id="mobile" name="mobile" value="{{$values->mobile}}" required type="text" data-error=".errorTxt24">
                                                        <div class="errorTxt24"></div>
                                                    </div>
                                                    <div class="input-field col s12 m6 hide">
                                                        <label for="email" class="force-active">Email *</label>
                                                        <input id="email" name="email" type="text" value="{{$values->email}}" data-error=".errorTxt25">
                                                        <div class="errorTxt25"></div>
                                                    </div>
                                                   
                                                    <div class="input-field col s12 m6 hide">
                                                        <input type="text" value="{{ date('d/m/Y',strtotime($values->doe)) }}" class="datepicker" id="doe" name="doe">
                                                        <label for="doe" class="force-active">Date of Emp</label>
                                                        <div class="errorTxt26"></div>
                                                    </div>
                                                      <div class="col s12 m6 hide">
                                                        @php
                                                            $designname = '';
                                                        @endphp
                                                        <label>Grade* </label>
                                                        <select name="designation" id="designation" data-error=".errorTxt2" class="error browser-default selectpicker"  onchange="return ChangeRejoinLabel(this.value)">
                                                            <option value="">Select</option>
                                                            @foreach($data['designation_view'] as $key=>$value)
                                                            <option value="{{$value->id}}" @php if($value->id == $values->designation_id) { $designname = $value->designation_name;  echo "selected";} @endphp>{{$value->designation_name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="input-field">
                                                            <div class="errorTxt2"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col s12 m6 hide">
                                                        @php
                                                            $old_membercode = '';
                                                            if($values->old_member_number!="" && $values->old_member_number!=Null){
                                                                $old_membercode = CommonHelper::getmembercode_byid($values->old_member_number);
                                                            }
                                                            
                                                        @endphp
                                                        @if($user_role!='member')
                                                        <div class="input-field col s12 m3">
                                                            <p>
                                                                <label>
                                                                    <input type="checkbox" id="rejoined" @php echo $values->old_member_number!="" && $values->old_member_number!=Null ? 'checked' : ''; @endphp/>
                                                                    <span id="rejoined_label">@if($designname=='SPECIAL GRADE') Redesignated @else Rejoined @endif</span>
                                                                </label>
                                                            </p>
                                                        </div>
                                                        <div class="col s12 m9 hide" style="display:@php echo $values->old_member_number!="" && $values->old_member_number!=Null ? 'block' : 'none'; @endphp" id="member_old_div">
                                                            <span> 
														<input type="text" value="{{$old_membercode}}" id="old_mumber_number" name="old_mumber_number">
														</span>
                                                        </div>
                                                        @else
                                                        <input type="checkbox" id="rejoined" class="hide" @php echo $values->old_member_number!="" ? 'checked' : ''; @endphp/>
                                                        </br>
                                                        @php echo $values->old_member_number!="" && $values->old_member_number!=Null ? 'Old Number: '.$old_membercode : ''; @endphp @endif
                                                        <input type="text" name="old_member_id" value="{{$values->old_member_number}}" id="old_member_id" class=" hide">
                                                    </div>
                                                    @php
                                                        $newdesignation = CommonHelper::getNewDesignationList();
                                                    @endphp
                                                    
                                                    <div class="col s12 m6 hide">
                                                        <div class="row">
                                                            <div class="col s12 m6">
                                                                <label>{{__('Designation') }}</label>
                                                                <select name="designationnew" id="designationnew" class="error browser-default selectpicker" onchange="return ShowRemarks(this.value)" style="line-height: 0.8;">
                                                                    <option value="" >{{__('Select') }}</option>
                                                                    @foreach($newdesignation as $key=>$des)
                                                                    <option @php if($des->id == $values->designation_new_id) { echo "selected";} @endphp value="{{$des->id}}">{{$des->designation_name}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <div class="input-field">
                                                                   
                                                                </div>
                                                            </div>
                                                            <div id="remarksdiv" class="input-field col s12 m6 @if($values->designation_new_id!=12) hide @endif">
                                                                <input type="text" class="" id="remarks" value="{{ $values->designation_others }}" name="remarks">
                                                                <label for="remarks" class="force-active">{{__('Remarks') }}*</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                     <div class="col s12 m6 hide">
                                                        <label>Race*</label>
                                                        <select name="race" id="race" data-error=".errorTxt3" class="error browser-default selectpicker">
                                                            <option value="">Select</option>
                                                            @foreach($data['race_view'] as $key=>$value)
                                                            <option value="{{$value->id}}" @php if($value->id == $values->race_id) { echo "selected";} @endphp>{{$value->race_name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="input-field">
                                                            <div class="errorTxt3"></div>
                                                        </div>
                                                    </div>
                                                    
                                                  
                                                   
                                                    <div class="col s12 m6 hide">
                                                        <label>Country Name*</label>
                                                        <select name="country_id" id="country_id" data-error=".errorTxt4" class="error browser-default selectpicker">
                                                            <option value="">Select</option>
                                                            @foreach($data['country_view'] as $value)
                                                            <option value="{{$value->id}}" @php if($value->id == $values->country_id) { echo "selected";} @endphp>{{$value->country_name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="input-field">
                                                            <div class="errorTxt4"></div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col s12 m6 hide">
                                                        <label>State Name*</label>
                                                        <select name="state_id" id="state_id" data-error=".errorTxt5" class="error browser-default selectpicker">
                                                            <option value="">Select</option>
                                                            @foreach($data['state_view'] as $key=>$value)
                                                            <option value="{{$value->id}}" @php if($value->id == $values->state_id) { echo "selected";} @endphp>{{$value->state_name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="input-field">
                                                            <div class="errorTxt5"></div>
                                                        </div>
                                                    </div>
                                                   
                                                    <div class="col s12 m6 hide">
                                                        <label>City Name*</label>
                                                        <select name="city_id" id="city_id" data-error=".errorTxt6" class="error browser-default selectpicker">
                                                            <option value="">Select</option>
                                                            @foreach($data['city_view'] as $key=>$value)
                                                            <option value="{{$value->id}}" @php if($value->id == $values->city_id) { echo "selected";} @endphp>{{$value->city_name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="input-field">
                                                            <div class="errorTxt6"></div>
                                                        </div>
                                                    </div>
                                                     
                                                    <div class="input-field col s12 m6 {{ $hidemember }}">
                                                        <label for="postal_code" class="force-active">Postal Code *</label>
                                                        <input id="postal_code" name="postal_code" value="{{$values->postal_code}}" type="text" data-error=".errorTxt7">
                                                        <div class="errorTxt7"></div>
                                                    </div>
                                                    
                                                    <div class="input-field col s12 m6 {{ $hidemember }}">
                                                        <label for="address_one" class="force-active">Address Line 1*</label>
                                                        <input id="address_one" name="address_one" required type="text" value="{{$values->address_one}}" data-error=".errorTxt8">
                                                        <div class="errorTxt8"></div>
                                                    </div>
                                                    
                                                    <div class="input-field col s12 m6 {{ $hidemember }}">
                                                        <label for="address_two" class="force-active">Address Line 2*</label>
                                                        <input id="address_two" name="address_two" required type="text" value="{{$values->address_two}}" data-error=".errorTxt9">
                                                        <div class="errorTxt9"></div>
                                                    </div>
                                                    
                                                    <div class="input-field col s12 m6 {{ $hidemember }}">
                                                        <label for="address_three" class="force-active">Address Line 3</label>
                                                        <input id="address_three" name="address_three"  type="text" value="{{$values->address_three}}" data-error=".errorTxt10">
                                                        <div class="errorTxt10"></div>
                                                    </div>
                                                    
                                                    <div class="col s12 m6 hide">
                                                        <div class="row">
                                                            <div class="input-field col s12 m8">
                                                                <label for="dob" class="force-active">Date of Birth *</label>
                                                                <input id="dob" name="dob" value="{{ date('d/m/Y',strtotime($values->dob)) }}" data-reflectage="member_age" class="datepicker-normal" type="text">
                                                            </div>
                                                            <div class="input-field col s12 m4">
                                                                <label for="member_age" class="force-active">Age</label>
                                                                <input type="text" id="member_age" value="{{ CommonHelper::calculate_age($values->dob) }}" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="input-field col s12 m6 {{ $hidemember }}">
                                                        <input type="text" class="datepicker" id="doj" value="{{ date('d/m/Y',strtotime($values->doj)) }}" name="doj">
                                                        <label for="doj" class="force-active">Date Joined</label>
                                                        <div class="errorTxt"></div>
                                                    </div>
                                                    
                                                    <div class="col s12 m6 {{ $hidemember }}">
                                                    	<div class="row">
                                                            @php
                                                               $basicsalary = CommonHelper::getBasicSalary($values->mid,date('Y-m-d',strtotime($values->last_update))); 
                                                            @endphp
	                                                    	<div class="input-field col s12 m3">
		                                                        <label for="basicsalary" class="force-active">Basic Salary</label>
		                                                        <input id="basicsalary" name="basicsalary" value="{{$basicsalary=='' ? $values->salary : $basicsalary}}" readonly="" type="text" data-error=".errorTxt11">
                                                                <input id="salary" name="salary" class="hide" value="{{$values->salary}}" required type="text" data-error=".errorTxt11">
		                                                        <div class="errorTxt11"></div>
		                                                    </div>
		                                                    <div class="input-field col s12 m3 ">
		                                                        <label for="cursalary" class="force-active">Current Salary</label>
		                                                        <input id="cursalary" name="cursalary" readonly="" value="{{$values->current_salary==0 ? '' : $values->current_salary}}" type="text" data-error=".errorTxt11">
		                                                        <div class="errorTxt11"></div>
		                                                    </div>
                                                            <div class="input-field col s12 m6 ">
                                                                <label for="lastupdate" class="force-active">Updated at</label>
                                                                <input id="lastupdate" name="lastupdate" readonly="" value="{{ $values->last_update!='' ? date('d-m-Y  h:i:s',strtotime($values->last_update)) : '' }}" type="text" data-error=".errorTxt11">
                                                                <div class="errorTxt11"></div>
                                                            </div>
	                                                    </div>
                                                    </div>
                                                    
                                                    
                                                    
                                                    <div class="input-field col s12 m6 {{ $hidemember }}">
                                                        <label for="old_ic" class="force-active">Old IC Number</label>
                                                        <input id="old_ic" name="old_ic" value="{{$values->old_ic}}" type="text" data-error=".errorTxt12">
                                                        <div class="errorTxt12"></div>
                                                    </div>
                                                    
                                                    <div class="input-field col s12 m6 {{ $hidemember }}">
                                                        <label for="new_ic" class="force-active">New IC Number*</label>
                                                        <input id="new_ic" name="new_ic" type="text" value="{{$values->new_ic}}" data-error=".errorTxt13">
                                                        <div class="errorTxt13"></div>
                                                    </div>
                                                

                                                    @php 

                                                    $auth_user = Auth::user(); 
                                                    $m_companyid = CommonHelper::getcompanyidbyBranchid($values->branch_id);
                                                    $check_union = $auth_user->hasRole('union'); 
                                                    $check_unionbranch = $auth_user->hasRole('union-branch'); 
                                                    $check_entry = $auth_user->hasRole('data-entry'); 
                                                    $showfee = 0;
                                                    if($check_union || $check_entry){ 
                                                        $showfee = 1;
                                                        $branch_requird = 'required'; 
                                                        $branch_disabled = ''; 
                                                        $branch_hide = ''; 
                                                        $branch_id = ''; 
                                                    }
                                                    else{ 
                                                        $branch_requird = ''; 
                                                        $branch_disabled = 'disabled'; 
                                                        $branch_hide = 'hide'; 
                                                        $branch_id = $auth_user->branch_id; 
                                                    }
                                                    //$branch_hide = 'hide'; 
                                                    @endphp
                                                    <div class="col s12 m6 ">
                                                        <label>Bank Name*</label>
                                                        @if($branch_hide=='hide')
                                                            </br>
                                                            <p style="margin-top:10px;font-weight:bold;">
                                                                @php
                                                                   $m_companyid = CommonHelper::getcompanyidbyBranchid($values->branch_id);
                                                                   
                                                                @endphp
                                                                {{ CommonHelper::getCompanyName($m_companyid) }}
                                                            </p>
                                                        @endif
                                                        <div class="{{ $branch_hide }}">
                                                            <select name="company_id" id="company" class="error browser-default selectpicker hide">
                                                                <option value="">Select</option>
                                                                @foreach($data['company_view'] as $value)
                                                                <option @php if($value->id == $m_companyid) { echo "selected";} @endphp value="{{$value->id}}">{{$value->company_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="input-field">
                                                                 <div class="errorTxt14"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    
                                                    <div class=" col s12 m6 {{ $branch_hide }}">
                                                        <label>Branch Name*</label>
                                                        <select name="branch_id" id="branch" data-error=".errorTxt15" class="error browser-default selectpicker">
                                                            <option value="">Select</option>
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
                                                     
                                                    <div class="col s12 m6 {{ $hidemember }}">
                                                        <label>{{__('Levy') }}</label>
                                                        <select name="levy" id="levy" onChange="return HideLevy(this.value)" class="error browser-default selectpicker">
                                                            <option value="">{{__('Select levy') }}</option>
                                                            <option value="Not Applicable" {{ $values->levy == 'Not Applicable' ? 'selected' : '' }}> N/A</option>
                                                            <option value="Yes" {{ $values->levy == 'Yes' ? 'selected' : '' }}>Yes</option>
                                                            <option value="NO" {{ $values->levy == 'NO' ? 'selected' : '' }}>No</option>
                                                        </select>
                                                    </div>
                                                    <div class="clearfix" style="clear:both"></div>
                                                    <div id="levydiv" class="input-field col s12 m6 @if($values->levy == 'NO') hide @endif {{ $hidemember }}">
                                                        <input id="levy_amount" name="levy_amount" type="text" value="{{$values->levy_amount}}">
                                                        <label for="levy_amount" class="force-active">{{__('Levy Amount') }} </label>
                                                    </div>
                                                   
                                                    <div class="col s12 m6 {{ $hidemember }}">
                                                        <label>{{__('TDF') }}</label>
                                                        <select name="tdf" id="tdf" onChange="return HideTDF(this.value)" class="error browser-default selectpicker">
                                                            <option value="0">Select TDF</option>
                                                            <option value="Not Applicable" {{ $values->tdf == 'Not Applicable' ? 'selected' : '' }}> N/A</option>
                                                            <option value="Yes" {{ $values->tdf == 'Yes' ? 'selected' : '' }}>Yes</option>
                                                            <option value="NO" {{ $values->tdf == 'NO' ? 'selected' : '' }}>No</option>
                                                        </select>
                                                    </div>
                                                   <div class="clearfix" style="clear:both"></div>
                                                    <div id="tdfdiv" class="input-field col s12 m6 @if($values->tdf == 'NO') hide @endif {{ $hidemember }}">
                                                        <input id="tdf_amount" name="tdf_amount" type="text" value="{{$values->tdf_amount}}">
                                                        <label for="tdf_amount" class="force-active">{{__('TDF Amount') }} </label>
                                                    </div>
                                                     
                                                    <div class="input-field col s12 m6 {{ $hidemember }}">
                                                        <label for="employee_id" class="force-active">Employee ID</label>
                                                        <input id="employee_id" name="employee_id" value="{{$values->employee_id}}" type="text">
                                                    </div>
                                                    @php if($values->is_request_approved==0 && $check_union==1){ @endphp
                                                   
                                                    @php } @endphp @php if($values->is_request_approved==0){ @endphp
                                                    
                                                    @php }else{ @endphp
                                                    <div class="col s12 m6 ">
                                                        <label>Status*</label>
                                                        @if($check_union==1)
                                                        <select name="status_id" id="status_id" {{ $branch_disabled }} data-error=".errorTxt16" class="error browser-default">
                                                            @foreach($data['status_view'] as $key=>$value)
                                                            <option value="{{$value->id}}" @php if($value->id == $values->status_id) { echo "selected";} @endphp >{{$value->status_name}}</option>
                                                            @endforeach
                                                        </select>
                                                        @else
                                                        <p style="margin-top:10px;">. @php $status_val = CommonHelper::getStatusName($values->status_id); if($status_val ==''){ $status_val = 'Pending'; } @endphp
                                                            <span style="color: rgba(255, 255, 255, 0.901961);" class="gradient-45deg-indigo-light-blue padding-1 medium-small">{{ $status_val }}</span>
                                                        </p>
                                                        @endif
                                                    </div>
                                                    @php } @endphp
                                                    <div class="clearfix" style="clear:both"></div>
                                                    <div class="row">
                                                   <!--  <div class="col s12 m4">
                                                         <div id="">
                                                            <div class=" ">
                                                                <br>
                                                                <input type="file" name="attachmentone[]" multiple="" class="" accept="" style="width: 500px;">
                                                            </div>
                                                            <div class="file-path-wrapper hide">
                                                                <input class="file-path validate" type="text">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col s12 m6 hide">
                                                        <input type="text" id="attachedone" name="attachedone" class="inline-box" style="width: 500px;" >
                                                    </div> -->
                                                    @php
                                                        $getfiles = CommonHelper::getMemberAttachaments($member_autoid);
                                                        //dd($getfiles);
                                                    @endphp
                                                    <div class="col s12 m12">
                                                        <br>
                                                         <input type="button" class="btn btn-sm purple " name="addattach" id="addattach" value="Add Attachment" />
                                                        <input type="text" name="attachmentcount" class="hide" readonly id="attachmentcount" value="0" />
                                                         <table id="filetable">
                                                            <thead>
                                                                <tr>
                                                                    <th width="42%">Particular</th>
                                                                    <th width="42%">File</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="attachmentarea">
                                                                @foreach($getfiles as $file)
                                                                <tr id="del_{{ $file->id }}">
                                                                    <td>{{$file->title}}</td>
                                                                    <td>{{$file->file_name}}  &nbsp;&nbsp; <a href="{{ asset('storage/app/member/'.$file->file_name) }}" class="btn btn-sm download-link" target="_blank">VIEW ATTACHMENT</a></td>
                                                                    <td>
                                                                        <a href="#" onclick="return DeleteImage('{{ $file->id }}')">
                                                                             Delete
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                            
                                                         </table>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                        
                                        <!-- <h3>Additional Details</h3> -->
                                       
                                            <div class="row "">
                                               
                                                @php if($values->is_request_approved==0 && ($check_union==1 || $check_unionbranch==1)){ @endphp
                                                    <br>
                                                    <div class="col s12 m10 ">
                                                        <div class="row">

                                                            <div class="col s12 m1">
                                                                <br>
                                                                <label>Status*</label>
                                                               
                                                            </div>
                                                            @if($check_union==1)
                                                            <div class="col s12 m4">
                                                                <div class="">
                                                                     <select name="approval_status" id="approval_status" onclick="return EnableReason(this.value)" class="error browser-default">
                                                                        <option value="0">Select Status</option>
                                                                        <option selected="" {{ $values->approval_status == 'Pending' ? 'selected' : '' }}> Pending</option>
                                                                        <option {{ $values->approval_status == 'Approved' ? 'selected' : '' }}>Approved</option>
                                                                        <option {{ $values->approval_status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                                                    </select>
                                                                </div>
                                                               
                                                            </div>
                                                            @endif
                                                            @if($check_unionbranch==1)
                                                                <div class="col s12 m2">
                                                                    <br>

                                                                    {{ $values->approval_status }}
                                                                </div>
                                                            @endif
                                                            <div id="app_reason" class="col s12 m7 @if($values->approval_status != 'Rejected') hide @endif">
                                                                <div class="">
                                                                    <input name="approval_reason" placeholder="Reason" id="approval_reason" type="text" value="{{ $values->approval_reason }}" width="1200px;" class="validate" style="">(Remarks)
                                                                </div>
                                                               
                                                            </div>
                                                            
                                                            @if($check_union==1)
                                                            <div class="col s12 m3 ">
                                                                
                                                                <label>
                                                                    <input type="checkbox" id="activate_account" data-error=".errorTxt129" name="activate_account" @if($values->approval_status == 'Rejected') checked @endif value='1' /> &nbsp; <span>Verify account</span>
                                                                </label>
                                                                <div class="input-field">
                                                                    <div class="errorTxt129"></div>
                                                                </div>
                                                                
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <br>
                                                    @php } @endphp @php if($values->is_request_approved==0){ @endphp
                                                   <!--  <div class="col s12 m6 ">
                                                        <label>Status*</label>
                                                        <p style="margin-top:10px;">
                                                            <span style="color: rgba(255, 255, 255, 0.901961);" class=" gradient-45deg-deep-orange-orange padding-2 medium-small">Pending</span>
                                                        </p>
                                                    </div> -->
                                                @php } @endphp
                                            </div>
                                        
                                      @php
                                        $lastmonthendrecord = CommonHelper::getlastMonthEndByMember($values->mid); 
                                    @endphp
										<div class="row">
											<div class="col s12 m8 center">
													@php

														$color = CommonHelper::getStatusColor($values->status_id);
                                                        $resignedrow = CommonHelper::getResignDataByMember($values->mid); 
                                                        if(!empty($resignedrow)){
                                                             $voucher_date = $resignedrow->voucher_date != '0000-00-00 00:00:00' ? date('d/m/Y',strtotime($resignedrow->voucher_date)) : ''; 
                                                        }
                                                       
													@endphp
                                                  
													<h4 style="color:{{$color}};font-size:2rem;">
                                                    {{ $values->status_id==null ? $values->approval_status : CommonHelper::getStatusName($values->status_id) }} 
                                                    @if(!empty($resignedrow))
                                                       - {{ $voucher_date }}
                                                    @endif
                                                    @if($values->status_id!=4)
													@if(!empty($lastmonthendrecord))
														@if($lastmonthendrecord->TOTALMONTHSDUE>0)
															, {{$lastmonthendrecord->TOTALMONTHSDUE}} Arrears pending
														@else
															, 0 Arrears pending
														@endif
													@else
														, 0 Arrears pending
													@endif
                                                    @endif
													</h4>
											</div>
											<div class="col s4 m4 right" style="padding-top: 15px;">
												Last Paid Date: 
												<div class="input-field inline" style="margin:0;">
													<input id="email_inline" type="text" class="validate" value="@if(!empty($lastmonthendrecord)){{ date('d/m/Y',strtotime($lastmonthendrecord->LASTPAYMENTDATE)) }}@endif" readonly style="height:2rem;">
												</div>
											</div>
										</div>
                                        <div class="actions clearfix right">
                                            <button type="submit" name="finish" class="mb-6 btn waves-effect waves-light purple lightrn-1" id="finish">finish</button>
                                            
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
@endsection @section('footerSection')
<script src="{{ asset('public/assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/assets/vendors/noUiSlider/nouislider.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/materialize.min.js') }}"></script>
<script src="{{ asset('public/assets/js/scripts/form-elements.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jquery.autocomplete.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/js/jquery.steps.js') }}"></script>
<script src="{{ asset('public/assets/js/datepicker.js') }}"></script>
@endsection @section('footerSecondSection')
<script>
   

    
    $(document).ready(function() {
        $(".selectpicker-new").select2();
    });
    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        autoHide: true,
    });
    $('.datepicker-normal').datepicker({
        format: 'dd/mm/yyyy',
        autoHide: true,
    });

    $(document.body).on('click', '.delete_nominee_db', function() {
        var nominee_id = $(this).data('id');
        var parrent = $(this).parents("tr");
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "{{ URL::to('/delete-nominee-data') }}?nominee_id=" + nominee_id,
            success: function(res) {
                if (res) {
                    parrent.remove();
                    M.toast({
                        html: res.message
                    });
                } else {
                    M.toast({
                        html: res.message
                    });
                }
            }
        });
    });

    $(document.body).on('click', '.delete_fee_db', function() {
        var fee_id = $(this).data('autoid');
        var parrent = $(this).parents("tr");
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "{{ URL::to('/delete-fee-data') }}?fee_id=" + fee_id,
            success: function(res) {
                if (res) {
                    parrent.remove();
                    M.toast({
                        html: res.message
                    });
                } else {
                    M.toast({
                        html: res.message
                    });
                }
            }
        });
    });

    $(document.body).on('click', '#submitResignation', function() {
        var resign_date = $("#resign_date").val();
        var last_paid = $("#last_paid").val();
        var resign_reason = $("#resign_reason").val();
        var payment_confirmation = $("#payment_confirmation").val();
        if (resign_date != "" && last_paid != "" && resign_reason != "" && payment_confirmation!="") {
            @if(empty($resignedrow))
            if (confirm("{{ __('Are you sure you want to Resign?') }}")) {
                $("#resignstatus").val(1);
                $('#wizard2').trigger('submit');
            } else {
                return false;
            }
            @endif

        } else {
            M.toast({
                html: "please fill the required fields"
            });
        }
    });
    $(document.body).on('change', '#resign_claimer', function() {
        var resign_claimer = $('#resign_claimer').val();
        var member_id = '{{ $member_autoid }}';
        if (resign_claimer != "" && member_id != "") {
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "{{ URL::to('/'.app()->getLocale().'/get-relatives-info') }}?member_id=" + member_id + "&resign_claimer=" + resign_claimer,
                success: function(res) {
                    if (res) {
                        $("#claimer_name").val(res);
                    } else {
                        $("#claimer_name").val(res);
                    }
                }
            });
        } else {
            $("#claimer_name").val('');
        }
    });
    $(document.body).on('change', '#pay_mode', function() {
        var pay_mode = $('#pay_mode').val();
        if (pay_mode == 1) {
            $('#reference_number_text').text('Cheque No');
        } else {
            $('#reference_number_text').text('Reference No');
        }
    });
	$(document).on('submit','form#fee_new_form',function(){
		$("#new_fee_id").val("");
	});
	$('#resign_date,#doj').change(function(){

	   var resign_date = $('#resign_date').val();
	   var doj = $("#doj").val();
	   if(resign_date!="" && doj!=''){
			$.ajax({
				type:"GET",
				dataType:"json",
				url:"{{URL::to('/get-serviceyear') }}?resign_date="+resign_date+"&doj="+doj,
				success:function(res){
                    var servie_year = res.service_year;
                    var benifit_year = res.benifit_year;
					if(res){
						$("#service_year").val(servie_year);
                        //$("#benefit_year").val(benifit_year);
                        var bf_monthly= $("#bf_monthly").val();
                        var contributed_months= $("#may_contributed_months").val();
                        var total_bf = (bf_monthly*contributed_months);
                        $("#bf_contribution").val(total_bf);
					}else{
						$("#service_year").val(0);
                        $("#benefit_year").val(0);
					}
					getBfAmount();
				}
			});
			
	   }else{
		  $("#"+reflect_age).val(0);
	   }
		
	});
	$('#resign_reason').change(function(){
		getBfAmount();
	});
	function getBfAmount(){
        //alert('test');
		var service_year = $('#service_year').val();
        var benefit_year = $('#benefit_year').val();
		var resign_reason = $('#resign_reason').val();
		var resign_date = $('#resign_date').val();
		var status_id = $('#status_id').val();
		var auto_id = $('#auto_id').val();
        var doj = $('#doj').val();
		if(service_year!=0 && resign_reason!='' && doj!=''){
			$.ajax({
				type:"GET",
				dataType:"json",
				url:"{{URL::to('/get-bf-amount') }}?service_year="+benefit_year+"&resign_reason="+resign_reason+"&doj="+doj+"&resign_date="+resign_date+"&auto_id="+auto_id,
				success:function(res){
					if(res){
						$("#benefit_amount").val(res);
					}else{
						$("#benefit_amount").val(0);
					}
                    $('#benefit_amount').trigger('keyup');
				}
			});
		}else{
			$("#benefit_amount").val(0);
            $('#benefit_amount').trigger('keyup');
		}
		
	}
    $("#old_mumber_number").devbridgeAutocomplete({
        //lookup: countries,
        serviceUrl: "{{ URL::to('/get-oldmember-list') }}?serachkey="+ $("#old_mumber_number").val(),
        type:'GET',
        //callback just to show it's working
        onSelect: function (suggestion) {
             $("#old_mumber_number").val(suggestion.number);
             $("#old_member_id").val(suggestion.auto_id);
            
        },
        showNoSuggestionNotice: true,
        noSuggestionNotice: 'Sorry, no matching results',
        onSearchComplete: function (query, suggestions) {
            if(!suggestions.length){
                //$("#old_mumber_number").val('');
            }
        }
    });
    function ChangeRejoinLabel(data){
        var designation = $( "#designation option:selected" ).text();
        if(designation=="SPECIAL GRADE"){
            $("#rejoined_label").text('Redesignated');
        }else{
            $("#rejoined_label").text('Rejoined');
        }
        
    }
    function EnableReason(type){
        if(type=='Rejected'){
            $("#app_reason").removeClass('hide');
            $("#activate_account").attr('required',true);
        }else if(type=='Approved'){
            $("#app_reason").addClass('hide');
            $("#activate_account").attr('required',true);
        }else{
            $("#activate_account").attr('required',false);
            $("#app_reason").addClass('hide');
        }
       
    }
    function HideLevy(levytitle){
        if(levytitle=='Yes'){
            $("#levydiv").removeClass('hide');
        }else{
            $("#levydiv").addClass('hide');
        }
    }
    function HideTDF(tdftitle){
        if(tdftitle=='Yes'){
            $("#tdfdiv").removeClass('hide');
        }else{
            $("#tdfdiv").addClass('hide');
        }
    }
    function ShowRemarks(designationid){
        if(designationid==12){
            $("#remarksdiv").removeClass('hide');
        }else{
            $("#remarksdiv").addClass('hide');
        }
    }

    function DeleteImage(fileid){
        if (confirm('Are you sure you want to delete?')){
            $.ajax({
                type:"GET",
                dataType:"json",
                url:"{{URL::to(app()->getLocale().'/delete_member_file') }}?fileid="+fileid,
                success:function(res){
                    if(res){
                        alert('File deleted successfully');
                        $('table#filetable tr#del_'+fileid).remove();
                    }else{
                        alert('Failed to delete');
                    }
                }
            });
        }else{
            return false;
        }
        
    }
    $(document.body).on('click', '.delete_attachment' ,function(){
        if(confirm('Are you sure you want to delete?')){
            var attach_id = $(this).data('id');
            var parrent = $(this).parents("tr");
            parrent.remove(); 
        }else{
            return false;
        }
        
    });
    $('#addattach').click(function(){
        var attachmentcount = $("#attachmentcount").val();
        var attachrow = '<tr><td><input type="text" name="serialnumber[]" id="serialnumber" class="hide" readonly value="'+attachmentcount+'" /><input id="attachmentname_'+attachmentcount+'" name="attachmentname'+attachmentcount+'" type="text" /></td>';
        attachrow += '<td><input type="file" id="attachmentone_'+attachmentcount+'" name="attachmentone'+attachmentcount+'[]" multiple="" class="" accept="" style="width: 500px;" /></td>';
        attachrow += '<td><button type="button" data-id="'+attachmentcount+'" class="delete_attachment waves-light btn">Delete</button></td></tr>';
        $('#attachmentarea').append(attachrow);
        attachmentcount = parseInt(1)+parseInt(attachmentcount);
        $("#attachmentcount").val(attachmentcount);
    });
</script>
@include('membership.member_common_script') 
@endsection