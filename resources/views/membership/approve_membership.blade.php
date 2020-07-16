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
       // $hidemember='hide';
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
                                    <h4 class="card-title">
                                        Verify Membership
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
                                        @if($user_role!='member')
                                        <a class="btn waves-effect waves-light right" target="_blank" href="{{ route('master.viewmembership', [app()->getLocale(),Crypt::encrypt($values->mid)]) }}">{{__('Current Member Info') }}</a>
                                        @else
                                         <a class="btn waves-effect waves-light right" target="_blank" href="{{ route('member.membership.profile', [app()->getLocale()]) }}">{{__('Current Member Info') }}</a>
                                        @endif
                                    </h4> 
									@include('includes.messages') 
									
                                    <form class="formValidate" id="wizard2" method="post" enctype="multipart/form-data" 
                                   action="{{ url(app()->getLocale().'/v_membership_save') }}"
                                     >
                                        @csrf 
										
                                        <h3>Member Details</h3>
                                        <fieldset>
                                            </br>
                                            <div class="col-sm-8 col-sm-offset-1 @if($data['view_status']==1) readonlyarea @endif" >
                                                <div class="row">
                                                    <div class="col s12 m6">
                                                        <input id="auto_id" name="auto_id" value="{{$values->mid}}" type="text" class="hide">
                                                        <label>Member Title*</label>
                                                        <select name="member_title" id="member_title" data-error=".errorTxt1" required class="validate error browser-default selectpicker">
                                                            <option value="" disabled selected>Choose your option</option>
                                                            @foreach($data['title_view'] as $key=>$value)
                                                            <option value="{{$value->id}}" @php if($value->id == $values->tpersontitle) { echo "selected";} @endphp>{{$value->person_title}}</option>
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
                                                        <label for="name" class="force-active">Member Name as per NRIC *</label>
                                                        <input id="name" name="name" value="{{$values->tname}}" type="text" data-error=".errorTxt30">
                                                        <div class="errorTxt30"></div>
                                                    </div>
                                                    <div class="input-field col s12 m6">
                                                        <div class="col s12 row">
                                                            <div class="col s12 m4">
                                                                <p>Gender</p>
                                                            </div>
                                                            <div class="col s12 m4">
                                                                <label>
                                                                    <input class="validate" required="" aria-required="true" id="gender" name="gender" type="radio" value="Female" {{ $values->tgender == 'Female' ? 'checked' : '' }}>
                                                                    <span>Female</span>
                                                                </label>
                                                            </div>
                                                            <div class="col s12 m4">
                                                                <p>
                                                                    <label>
                                                                        <input class="validate" required="" aria-required="true" id="gender" name="gender" type="radio" value="Male" {{ $values->tgender == 'Male' ? 'checked' : '' }}>
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
                                                        <input id="mobile" name="mobile" value="{{$values->tmobile}}" required type="text" data-error=".errorTxt24">
                                                        <div class="errorTxt24"></div>
                                                    </div>
                                                    <div class="input-field col s12 m6">
                                                        <label for="email" class="force-active">Email *</label>
                                                        <input id="email" name="email" type="text" value="{{$values->temail}}" data-error=".errorTxt25">
                                                        <div class="errorTxt25"></div>
                                                    </div>
                                                    <div class="clearfix" style="clear:both"></div>
                                                    <div class="input-field col s12 m6 {{ $hidemember }}">
                                                        <input type="text" value="{{ date('d/m/Y',strtotime($values->doe)) }}" class="datepicker" id="doe" name="doe">
                                                        <label for="doe" class="force-active">Date of Emp</label>
                                                        <div class="errorTxt26"></div>
                                                    </div>
                                                      <div class="col s12 m6 {{ $hidemember }}">
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
                                                    <div class="col s12 m6 {{ $values->status_id>2 ? 'hide' : '' }} {{ $hidemember }}">
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
                                                        <div class="col s12 m9 " style="display:@php echo $values->old_member_number!="" && $values->old_member_number!=Null ? 'block' : 'none'; @endphp" id="member_old_div">
                                                            <span> 
                                                                 <label for="old_mumber_number">{{__('Old Member Number') }}</label>
														<input type="text" value="{{$old_membercode}}" id="old_mumber_number" placeholder="Old Member Number" name="old_mumber_number">

														</span>
                                                        </div>
                                                        @else
                                                        <input type="checkbox" id="rejoined" class="hide" @php echo $values->old_member_number!="" ? 'checked' : ''; @endphp/>
                                                        </br>
                                                        @php echo $values->old_member_number!="" && $values->old_member_number!=Null ? 'Old Member Number: '.$old_membercode : ''; @endphp @endif
                                                        <input type="text" name="old_member_id" value="{{$values->old_member_number}}" id="old_member_id" class=" hide">
                                                    </div>
                                                    @php
                                                        $newdesignation = CommonHelper::getNewDesignationList();
                                                    @endphp
                                                    
                                                    <div class="col s12 m6 {{ $hidemember }}">
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
                                                     <div class="col s12 m6 {{ $hidemember }}">
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
                                                    
                                                  
                                                   
                                                    <div class="col s12 m6 {{ $hidemember }}">
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
                                                    <div class="clearfix" style="clear:both"></div>
                                                    <div class="col s12 m6 {{ $hidemember }}">
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
                                                   
                                                    <div class="col s12 m6 {{ $hidemember }}">
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
                                                     <div class="clearfix" style="clear:both"></div>
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
                                                    <div class="clearfix" style="clear:both"></div>
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
                                                    <div class="clearfix" style="clear:both"></div>
                                                    <div class="col s12 m6">
                                                        <div class="row">
                                                            <div class="input-field col s12 m8">
                                                                <label for="dob" class="force-active">Date of Birth *</label>
                                                                <input id="dob" name="dob" value="{{ date('d/m/Y',strtotime($values->tdob)) }}" data-reflectage="member_age" class="datepicker-normal" type="text">
                                                            </div>
                                                            <div class="input-field col s12 m4">
                                                                <label for="member_age" class="force-active">Age</label>
                                                                <input type="text" id="member_age" value="{{ CommonHelper::calculate_age($values->tdob) }}" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="input-field col s12 m6 {{ $hidemember }}">
                                                        <input type="text" class="datepicker" id="doj" value="{{ date('d/m/Y',strtotime($values->doj)) }}" name="doj">
                                                        <label for="doj" class="force-active">Date Joined</label>
                                                        <div class="errorTxt"></div>
                                                    </div>
                                                    <div class="clearfix" style="clear:both"></div>
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
                                                    <div class="clearfix" style="clear:both"></div>
                                                    <div class="input-field col s12 m6 {{ $hidemember }}">
                                                        <label for="new_ic" class="force-active">New IC Number*</label>
                                                        <input id="new_ic" name="new_ic" type="text" value="{{$values->new_ic}}" data-error=".errorTxt13">
                                                        <div class="errorTxt13"></div>
                                                    </div>
                                                    
                                                    @php 

                                                    $auth_user = Auth::user(); 
                                                    $m_companyid = CommonHelper::getcompanyidbyBranchid($values->branch_id);
                                                    $check_union = 0; 
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
                                                    $branch_hide = 'hide'; 
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
                                                    <div class="clearfix" style="clear:both"></div>
                                                    
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
                                                    <div id="levydiv" class="input-field col s12 m6 @if($values->levy != 'Yes') hide @endif {{ $hidemember }}">
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
                                                    <div id="tdfdiv" class="input-field col s12 m6 @if($values->tdf != 'Yes') hide @endif {{ $hidemember }}">
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
                                                            <span style="color: rgba(255, 255, 255, 0.901961);" class="gradient-45deg-indigo-light-blue padding-2 medium-small">{{ $status_val }}</span>
                                                        </p>
                                                        @endif
                                                        <br>
                                                    </div>
                                                    @php } @endphp
                                                    <div class="clearfix" style="clear:both"></div>
                                                    <div id="approveddiv" class="input-field col s12 m6 {{ $hidemember }}">
                                                        <input id="approvedby" readonly="" name="approvedby" type="text" value="{{ $values->approved_by=='' ? '' : CommonHelper::getUserName($values->approved_by) }}">
                                                        <label for="approvedby" class="force-active">{{__('Approved By') }} </label>
                                                    </div>

                                                </div>
                                                <div class="row">
                                                    <!-- <div class="col s12 m4 hide">
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
                                                     <input type="button" class="btn btn-sm purple " name="addattach" id="addattach" value="Add Attachment" />
                                                    <input type="text" name="attachmentcount" class="hide" readonly id="attachmentcount" value="0" />
                                                    @php
                                                        $getfiles = CommonHelper::getMemberAttachaments($member_autoid);
                                                        //dd($getfiles);
                                                    @endphp
                                                    <div class="col s12 m12">
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
                                                                    <td>{{$file->file_name}} &nbsp;&nbsp; <a href="{{ asset('storage/app/member/'.$file->file_name) }}" class="btn btn-sm download-link" target="_blank">VIEW ATTACHMENT</a></td>
                                                                   
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
                                        </fieldset>
                                        <h3>Additional Details</h3>
                                        <fieldset>
                                            <div class="row ">
                                                <ul class="collapsible collapsible-accordion" data-collapsible="accordion">
                                                    <li class="active">
                                                        <div class="collapsible-header gradient-45deg-indigo-purple white-text"><i class="material-icons">details</i> {{__('Nominee Details') }}</div>
                                                        <div class="collapsible-body">
                                                            <div id="nominee_add_section" class="row @if($data['view_status']==1) readonlyarea @endif">
                                                                <div class="input-field col s12 m4">
                                                                    <label for="nominee_name">Nominee name* </label>
                                                                    <input id="nominee_name" name="nominee_name" value="" type="text">
                                                                </div>
                                                                <div class="col s12 m4">
                                                                    <div class="row">
                                                                        <div class=" col s12 m8">
                                                                            <p>
                                                                                <label for="nominee_dob">DOB *</label>
                                                                                <input id="nominee_dob" data-reflectage="nominee_age" name="nominee_dob" value="" class="datepicker" type="text">
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
                                                                        <option value="Male">Male</option>
                                                                        <option value="Female">Female</option>
                                                                    </select>
                                                                    <div class="input-field">
                                                                        <div class="errorTxt50"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="clearfix"> </div>
                                                                <div class="input-field col s12 m4">
                                                                    <label class="force-active">Relationship*</label>
                                                                    <select name="relationship" id="relationship" class="error browser-default selectpicker ">
                                                                        <option value="">Select</option>
                                                                        @foreach($data['relationship_view'] as $key=>$value)
                                                                        <option value="{{$value->id}}" data-relationshipname="{{$value->relation_name}}">{{$value->relation_name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="">
                                                                        <div class="errorTxt31"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="input-field col s12 m4">
                                                                    <label for="nric_n">NRIC-N *</label>
                                                                    <input id="nric_n" name="nric_n" value="" type="text">
                                                                </div>
                                                                <div class="input-field col s12 m4">
                                                                    <label for="nric_o">NRIC-O </label>
                                                                    <input id="nric_o" name="nric_o" value="" type="text">
                                                                </div>
                                                                <div class="clearfix"> </div>
                                                                <div class="col s12 m4">
                                                                    <label class="force-active">Country Name*</label>
                                                                    <select name="nominee_country_id" id="nominee_country_id" class="error browser-default selectpicker">
                                                                        <option value="">Select Country</option>
                                                                        @php
                                                                            $Defcountry = CommonHelper::DefaultCountry();
                                                                            @endphp
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
                                                                    <label class="force-active">State Name*</label>
                                                                    <select name="nominee_state_id" id="nominee_state_id" class="error browser-default selectpicker">
                                                                        <option value="">Select</option>
                                                                        @foreach($data['state_view'] as $key=>$value)
                                                                        <option value="{{$value->id}}">{{$value->state_name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="input-field">
                                                                        <div class="errorTxt36"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col s12 m4">
                                                                    <label class="force-active">City Name*</label>
                                                                    <select name="nominee_city_id" id="nominee_city_id" class="error browser-default selectpicker">
                                                                        <option value="">Select</option>
                                                                       
                                                                    </select>
                                                                    <div class="input-field">
                                                                        <div class="errorTxt36"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="clearfix"> </div>
                                                                <div class="input-field col s12 m4">
                                                                    <label for="nominee_postal_code" class="force-active">Postal code*</label>
                                                                    <input id="nominee_postal_code" name="nominee_postal_code" type="text" value="">
                                                                </div>
                                                                <div class="input-field col s12 m4">
                                                                    <label for="nominee_address_one" class="force-active">Address Line 1*</label>
                                                                    <input id="nominee_address_one" name="nominee_address_one" type="text" value="">
                                                                </div>
                                                                <div class="input-field col s12 m4">
                                                                    <label for="nominee_address_two" class="force-active">Address Line 2*</label>
                                                                    <input id="nominee_address_two" name="nominee_address_two" type="text" value="">
                                                                </div>
                                                                <div class="clearfix"> </div>
                                                                <div class="input-field col s12 m4">
                                                                    <label for="nominee_address_three" class="force-active">Address Line 3*</label>
                                                                    <input id="nominee_address_three" name="nominee_address_three" type="text" value="">
                                                                </div>
                                                                <div class="input-field col s12 m4">
                                                                    <label for="nominee_mobile" class="force-active">Mobile No*</label>
                                                                    <input id="nominee_mobile" name="nominee_mobile" type="text" value="">
                                                                </div>
                                                                <div class="input-field col s12 m4">
                                                                    <label for="nominee_phone" class="force-active">Phone No</label>
                                                                    <input id="nominee_phone" name="nominee_phone" type="text" value="">
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
                                                                    <table id="nominee_table" class="responsive-table" width="100%">
                                                                        <thead>
                                                                            <tr>
                                                                                <th data-field="name">Name</th>
                                                                                <th data-field="age">Age</th>
                                                                                <th data-field="sex">Sex</th>
                                                                                <th data-field="relationship">Relationship</th>
                                                                                <th data-field="nric_n">NRIC-N</th>
                                                                                <th data-field="nric_o">NRIC-O</th>
                                                                                <th data-field="action" width="25%">Action</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @php {{ $sl = 0; }} @endphp @foreach($data['nominee_view'] as $key=>$value)
                                                                            <tr>
                                                                                <td>
                                                                                    <span id="nominee_name_label_{{ $sl }}">{{$value->nominee_name}}</span>
                                                                                    <input type="text" name="nominee_auto_id[]" class="hide" id="nominee_auto_id_{{ $sl }}" value="{{$value->id}}">
                                                                                    <input class="hide" type="text" name="nominee_name_value[]" id="nominee_name_value_{{ $sl }}" value="{{$value->nominee_name}}">
                                                                                </td>
                                                                                <td>
                                                                                    <span id="nominee_age_label_{{ $sl }}">{{ CommonHelper::calculate_age($value->dob) }}</span>
                                                                                    <input type="text" class="hide" name="nominee_age_value[]" id="nominee_age_value_{{ $sl }}" value="{{ CommonHelper::calculate_age($value->dob) }}">
                                                                                    <input type="text" class="hide" name="nominee_dob_value[]" id="nominee_dob_value_{{ $sl }}" value="{{ CommonHelper::convert_date_datepicker($value->dob) }}">
                                                                                </td>
                                                                                <td><span id="nominee_gender_label_{{ $sl }}">{{$value->gender}}</span>
                                                                                    <input type="text" class="hide" name="nominee_gender_value[]" id="nominee_gender_value_{{ $sl }}" value="{{ $value->gender }}">
                                                                                </td>
                                                                                <td>
                                                                                    <span id="nominee_relation_label_{{ $sl }}">{{ CommonHelper::get_relationship_name($value->relation_id) }}</span>
                                                                                    <input type="text" class="hide" name="nominee_relation_value[]" id="nominee_relation_value_{{ $sl }}" value="{{$value->relation_id}}">
                                                                                </td>
                                                                                <td><span id="nominee_nricn_label_{{ $sl }}">{{$value->nric_n}}</span>
                                                                                    <input class="hide" type="text" name="nominee_nricn_value[]" id="nominee_nricn_value_{{ $sl }}" value="{{$value->nric_n}}">
                                                                                </td>
                                                                                <td>
                                                                                    <span id="nominee_nrico_label_{{ $sl }}">{{$value->nric_o}}</span>
                                                                                    <input type="text" class="hide" name="nominee_nrico_value[]" id="nominee_nrico_value_{{ $sl }}" value="{{$value->nric_o}}">
                                                                                </td>
                                                                                <td class="hide">
                                                                                    <span id="nominee_addressone_label_{{ $sl }}">{{$value->address_one}}</span>
                                                                                    <input type="text" class="hide" name="nominee_addressone_value[]" id="nominee_addressone_value_{{ $sl }}" value="{{$value->address_one}}">
                                                                                </td>
                                                                                <td class="hide"><span id="nominee_addresstwo_label_{{ $sl }}">{{$value->address_two}}</span>
                                                                                    <input type="text" class="hide" name="nominee_addresstwo_value[]" id="nominee_addresstwo_value_{{ $sl }}" value="{{$value->address_two}}">
                                                                                </td>
                                                                                <td class="hide"><span id="nominee_addressthree_label_{{ $sl }}">{{$value->address_three}}</span>
                                                                                    <input type="text" class="hide" name="nominee_addressthree_value[]" id="nominee_addressthree_value_{{ $sl }}" value="{{$value->address_three}}">
                                                                                </td>
                                                                                <td class="hide"><span id="nominee_country_label_{{ $sl }}">{{$value->country_id}}</span>
                                                                                    <input type="text" name="nominee_country_value[]" id="nominee_country_value_{{ $sl }}" value="{{$value->country_id}}">
                                                                                </td>
                                                                                <td class="hide"><span id="nominee_state_label_{{ $sl }}">{{$value->state_id}}</span>
                                                                                    <input type="text" name="nominee_state_value[]" id="nominee_state_value_{{ $sl }}" value="{{$value->state_id}}">
                                                                                </td>
                                                                                <td class="hide"><span id="nominee_city_label_{{ $sl }}">{{$value->city_id}}</span>
                                                                                    <input type="text" name="nominee_city_value[]" id="nominee_city_value_{{ $sl }}" value="{{$value->city_id}}">
                                                                                </td>
                                                                                <td class="hide"><span id="nominee_postalcode_label_{{ $sl }}">{{$value->postal_code}}</span>
                                                                                    <input type="text" name="nominee_postalcode_value[]" id="nominee_postalcode_value_{{ $sl }}" value="{{$value->postal_code}}">
                                                                                </td>
                                                                                <td class="hide"><span id="nominee_mobile_label_{{ $sl }}">{{$value->mobile}}</span>
                                                                                    <input type="text" name="nominee_mobile_value[]" id="nominee_mobile_value_{{ $sl }}" value="{{$value->mobile}}">
                                                                                </td>
                                                                                <td class="hide"><span id="nominee_phone_label_{{ $sl }}">{{$value->phone}}</span>
                                                                                    <input type="text" name="nominee_phone_value[]" id="nominee_phone_value_{{ $sl }}" value="{{$value->phone}}">
                                                                                </td>
                                                                                <td>
                                                                                    <a class="btn-floating waves-effect waves-light cyan edit_nominee_row " href="#modal_nominee" data-id="{{$sl}}"><i class="material-icons left">edit</i></a>
                                                                                    <a class="btn-floating waves-effect waves-light amber darken-4 delete_nominee_db" data-id="{{$value->id}}" onclick="if (confirm('Are you sure you want to delete?')) return true; else return false;"><i class="material-icons left">delete</i></a>
                                                                                </td>
                                                                            </tr>
                                                                            @php {{ $sl++; }} @endphp @endforeach
                                                                            <input id="nominee_row_id" class="hide" name="nominee_row_id" value="{{ $sl }}" type="text">
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="collapsible-header gradient-45deg-indigo-purple white-text"><i class="material-icons">filter_center_focus</i>{{__('Guardian Details') }}</div>
                                                        <div class="collapsible-body">
                                                            <div class="row @if($data['view_status']==1) readonlyarea @endif">
                                                                @php 

                                                                if(count($data['gardian_view'])>0) { 
                                                                 
                                                                 $gardian_row = $data['gardian_view'][0]; } @endphp
                                                                <div class="input-field col s12 m4">
                                                                    <label for="guardian_name" class="force-active">Guardian name* </label>
                                                                    <input id="guardian_name" name="guardian_name" value="@isset($gardian_row) @php echo $gardian_row->guardian_name; @endphp @endisset" type="text">
                                                                </div>
                                                                <div class="col s12 m4">
                                                                    <div class="row">
                                                                        <div class=" col s12 m8">
                                                                            <p>
                                                                                <label for="gaurdian_dob" class="force-active">DOB *</label>
                                                                                <input id="gaurdian_dob" name="gaurdian_dob" data-reflectage="gaurdian_age" value="@isset($gardian_row) {{ date('d/m/Y',strtotime($gardian_row->dob)) }} @endisset" class="datepicker" type="text">
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
                                                                    <select name="relationship_id" id="gaurd_relationship" data-error=".errorTxt31" class="error browser-default selectpicker">
                                                                        <option value="">Select</option> @foreach($data['relationship_view'] as $key=>$value)
                                                                        <option @isset($gardian_row) @php echo $gardian_row->relationship_id==$value->id ? 'selected': ''; @endphp @endisset value="{{$value->id}}" >{{$value->relation_name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="input-field">
                                                                        <div class="errorTxt31"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="input-field col s12 m4">
                                                                    <label for="nric_n_guardian" class="force-active">NRIC-N *</label>
                                                                    <input id="nric_n_guardian" name="nric_n_guardian" value="@isset($gardian_row) @php echo $gardian_row->nric_n; @endphp @endisset" type="text">
                                                                </div>
                                                                <div class="input-field col s12 m4">
                                                                    <label for="nric_o_guardian" class="force-active">NRIC-O </label>
                                                                    <input id="nric_o_guardian" name="nric_o_guardian" value="@isset($gardian_row) @php echo $gardian_row->nric_o; @endphp @endisset" type="text">
                                                                </div>
                                                                <div class="clearfix"> </div>
                                                                <div class="col s12 m4">
                                                                    <label class="force-active">Country Name*</label>
                                                                    <select name="guardian_country_id" id="guardian_country_id" class="error browser-default selectpicker">
                                                                        <option value="">Select</option>
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
                                                                    <select name="guardian_state_id" id="guardian_state_id" class="error browser-default selectpicker">
                                                                        <option value="">Select</option>
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
                                                                    <select name="guardian_city_id" id="guardian_city_id" class="error browser-default selectpicker">
                                                                        <option value="">Select</option>
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
                                                                    <input id="guardian_postal_code" name="guardian_postal_code" type="text" value="@isset($gardian_row) @php echo $gardian_row->postal_code; @endphp @endisset">
                                                                </div>
                                                                <div class="input-field col s12 m4">
                                                                    <label for="guardian_address_one" class="force-active">Address Line 1*</label>
                                                                    <input id="guardian_address_one" name="guardian_address_one" type="text" value="@isset($gardian_row) @php echo $gardian_row->guardian_name; @endphp @endisset">
                                                                </div>
                                                                <div class="input-field col s12 m4">
                                                                    <label for="guardian_address_two" class="force-active">Address Line 2*</label>
                                                                    <input id="guardian_address_two" name="guardian_address_two" type="text" value="@isset($gardian_row) @php echo $gardian_row->address_two; @endphp @endisset">
                                                                </div>
                                                                <div class="clearfix"> </div>
                                                                <div class="input-field col s12 m4">
                                                                    <label for="guardian_address_three" class="force-active">Address Line 3*</label>
                                                                    <input id="guardian_address_three" name="guardian_address_three" type="text" value="@isset($gardian_row) @php echo $gardian_row->address_three; @endphp @endisset">
                                                                </div>
                                                                <div class="input-field col s12 m4">
                                                                    <label for="guardian_mobile" class="force-active">Mobile No*</label>
                                                                    <input id="guardian_mobile" name="guardian_mobile" type="text" value="@isset($gardian_row) @php echo $gardian_row->mobile; @endphp @endisset">
                                                                </div>
                                                                <div class="input-field col s12 m4">
                                                                    <label for="guardian_phone" class="force-active">Phone No</label>
                                                                    <input id="guardian_phone" name="guardian_phone" type="text" value="@isset($gardian_row) @php echo $gardian_row->phone; @endphp @endisset">
                                                                </div>
                                                                <div class="clearfix"> </div>

                                                            </div>
                                                        </div>
                                                    </li>
                                                    
                                                    
                                                </ul>
                                               
                                                    <br>
                                                    <div class="col s12 m10 ">
                                                        @if($user_role!='member')
                                                        <div class="row">

                                                            <div class="col s12 m2">
                                                                
                                                                <label>Confirmation Status*</label>
                                                               
                                                            </div>
                                                            
                                                            <div class="col s12 m4">
                                                                <div class="">
                                                                     <select name="m_approval_status" id="m_approval_status" required="" class="error browser-default">
                                                                        <option value="">Select Status</option>
                                                                        
                                                                        <option >Confirmation</option>
                                                                        <option >Rejected</option>
                                                                    </select>
                                                                </div>
                                                               
                                                            </div>
                                                           
                                                           
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <br>
                                                   
                                            </div>
                                        </fieldset>
                                        
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
    var form = $("#wizard2").show();

    @if($user_role=='member')
        form.steps({
           headerTag: "h3",
           bodyTag: "fieldset",
        enableAllSteps: true,
         enablePagination: false
         
        });
    @else
    form.steps({
        headerTag: "h3",
        bodyTag: "fieldset",
        onStepChanging: function(event, currentIndex, newIndex) {
            // Allways allow previous action even if the current form is not valid!
            if (currentIndex > newIndex) {
                return true;
            }
            // Forbid next action on "Warning" step if the user is to young

            // Needed in some cases if the user went back (clean up)
            if (currentIndex < newIndex) {
                // To remove error styles
                form.find(".body:eq(" + newIndex + ") label.error").remove();
                form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
            }
            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();
        },
        onFinishing: function(event, currentIndex) {
            form.validate().settings.ignore = ":disabled";
            return form.valid();
        },
        onFinished: function(event, currentIndex) {
            loader.showLoader();
            $('#wizard2').trigger('submit');
            return true;
        }
    }).validate({
        rules: {
            member_title: {
                required: true,
            },
            member_number: {
                required: true,
            },
            name: {
                required: true,
                minlength: 3,
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
                //required: true,
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
           /*  email: {
                required: true,
                email: true,
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
            }, */
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
                minlength: 5,
                maxlength: 8,
            },
            address_one: {
                required: true,
            },
            address_two: {
                required: true,
            },
            // address_three: {
            //     required: true,
            // },
            dob: {
                required: true,
            },
            doj: {
                required: true,
            },
            new_ic: {
                required: true,
                minlength: 3,
                maxlength: 20,
            },
            salary: {
                required: true,
                digits: true,
            },
            levy_amount: {
                digits: true,
            },
            tdf_amount: {
                digits: true,
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
            /* cemail: {
                required: true,
                email: true
            }, */
            city_name: {
                required: true,
            },
            designation_name: {
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
                required: "Please choose  your Country",
            },
            state: {
                required: "Please choose  your State",
            },
            city: {
                required: "Please choose  your city",
            },
            address_one: {
                required: "Please Enter your Address",
            },
            dob: {
                required: "Please choose DOB",
            },
            new_ic: {
                required: "Please Enter New Ic Number",
            },
            salary: {
                required: "Please Enter salary",
				digits: "{{__("Please Enter numbers only") }}",
            },
            levy_amount: {
                digits: "{{__("Please Enter numbers only") }}",
            },
            tdf_amount: {
                digits: "{{__("Please Enter numbers only") }}",
            },
            branch: {
                required: "Please Choose Company Name",
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
        errorPlacement: function(error, element) {
            var placement = $(element).data('error');
            if (placement) {
                $(placement).append(error)
            } else {
                error.insertAfter(element);
            }
        },
    });
    @endif
    $('#bf_contribution,#benefit_amount,#insurance_amount').keyup(function() {
        var bf_contribution = $('#bf_contribution').val();
        var insurance_amount = $('#insurance_amount').val();
        var benefit_amount = $('#benefit_amount').val();
        bf_contribution = bf_contribution != "" && typeof(bf_contribution) != "number" ? parseFloat(bf_contribution) : 0;
        insurance_amount = insurance_amount != "" && typeof(insurance_amount) != "number" ? parseFloat(insurance_amount) : 0;
        benefit_amount = benefit_amount != "" && typeof(benefit_amount) != "number" ? parseFloat(benefit_amount) : 0;
        var total_amount = parseFloat(insurance_amount) + parseFloat(bf_contribution) + parseFloat(benefit_amount);
        $('#total_amount').val(total_amount.toFixed(2));
    });

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
<script>
$("#vmembership_sidebar_a_id").addClass('active');
$("#membership_sidebar_a_id").removeClass('active');
</script>
@endsection