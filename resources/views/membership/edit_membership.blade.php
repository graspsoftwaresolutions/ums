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
    
    $("#irc_confirmation_area :input").attr("readonly", true);
</style>
<link rel="stylesheet" type="text/css" href="{{ asset('public/css/wizard-app.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/css/wizard-theme.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/datepicker.css') }}">
<link class="rtl_switch_page_css" href="{{ asset('public/css/steps.css') }}" rel="stylesheet" type="text/css"> @endsection @section('main-content')
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
										$irc_status = $data['irc_status']; 
										$new_resign_status = $data['resign_status']; 
									@endphp 
									@php 
										$member_autoid = $values->mid; 
									@endphp
                                    <form class="formValidate" id="wizard2" method="post" action="{{ url(app()->getLocale().'/membership_save') }}">
                                        @csrf 
										@if($irc_status==1) 
											@php 
												$irc_data = CommonHelper::getIrcDataByMember($values->mid); 
												$resignedmemberno = $irc_data->resignedmemberno; 
												$member_type = CommonHelper::getdesignationname($resignedmemberno); 
												$resignedid = $irc_data->resignedreason; 
												$irc_reason_name = CommonHelper::getircreason_byid($resignedid); 
											@endphp
                                        <h3>IRC Confirmation</h3>
                                        <fieldset>
                                            </br>
                                            <div class="col-sm-8 col-sm-offset-1">
                                                <div id="irc_confirmation_area" class="row">
                                                    </br>
                                                    <div class="input-field col s6">
                                                        <label for="irc_member_number" class="common-label force-active">{{__('Membership Number') }}*</label>
                                                        <input id="irc_member_number" name="irc_member_number" value="{{ !empty($irc_data) ? $values->member_number : '' }}" class="common-input" type="text">
                                                    </div>
                                                    <div class="input-field col s6">
                                                        <label for="irc_name_full" class="common-label force-active">{{__('IRC Name in Full') }}*</label>
                                                        <input id="irc_name_full" name="irc_name_full" class="common-input" value="{{ !empty($irc_data) ? $irc_data->ircname : '' }}" type="text">
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
                                                        <label for="irc_bank" class="common-label force-active">{{__('Bank') }}*</label>
                                                        <input id="irc_bank" name="irc_bank" class="common-input" value="{{ !empty($irc_data) ? $irc_data->ircbank : '' }}" type="text">
                                                    </div>
                                                    <div class="input-field col s6">
                                                        <label for="irc_bank_address" class="common-label force-active">{{__('Bank Address') }}*</label>
                                                        <input id="irc_bank_address" name="irc_bank_address" class="common-input" value="{{ !empty($irc_data) ? $irc_data->ircbankaddress : '' }}" type="text">
                                                    </div>
                                                    <div class="input-field col s6">
                                                        <label for="irc_office_telephone_number" class="common-label force-active">{{__('Office Number') }}*</label>
                                                        <input id="irc_office_telephone_number" name="irc_office_telephone_number" class="common-input" value="{{ !empty($irc_data) ? $irc_data->irctelephoneno : '' }}" type="text">
                                                    </div>
                                                    <div class="clearfix" style="clear:both"></div>
                                                    <div class="input-field col s6">
                                                        <label for="irc_mobile" class="common-label force-active">{{__('Mobile') }}*</label>
                                                        <input id="irc_mobile" name="irc_mobile" class="common-input" value="{{ !empty($irc_data) ? $irc_data->ircmobileno : '' }}" type="text">
                                                    </div>
                                                    <div class="input-field col s6">
                                                        <label for="irc_fax" class="common-label force-active">{{__('Fax') }}*</label>
                                                        <input id="irc_fax" name="irc_fax" class="common-input" value="{{ !empty($irc_data) ? $irc_data->ircfaxno : '' }}" type="text">
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
                                                                <input type="text" name="irc_person_name" style="width:200%" value=" {{ !empty($irc_data) ? $irc_data->resignedmembername : ''}}">
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
                                                                            <input id="irc_branch_committie_name" style="width:200%" type="text" value="{{ !empty($irc_data) ? $irc_data->branchcommitteeName : '' }}" class="validate">
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
                                                                <input type="text" class="" palceholder="Date" name="date" value="{{ !empty($irc_data) && $irc_data->branchcommitteedate!=" "  ? date('d/m/Y',strtotime($irc_data->branchcommitteedate)) : '' }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.col- -->
                                        </fieldset>
                                        @endif
                                        <h3>Member Details</h3>
                                        <fieldset>
                                            </br>
                                            <div class="col-sm-8 col-sm-offset-1">
                                                <div class="row">
                                                    <div class="col s12 m6">
                                                        <input id="auto_id" name="auto_id" value="{{$values->mid}}" type="text" class="hide">
                                                        <label>Member Title*</label>
                                                        <select name="member_title" id="member_title" data-error=".errorTxt1" required class="validate error browser-default selectpicker">
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
                                                        <input id="mobile" name="mobile" value="{{$values->mobile}}" required type="text" data-error=".errorTxt24">
                                                        <div class="errorTxt24"></div>
                                                    </div>
                                                    <div class="input-field col s12 m6">
                                                        <label for="email" class="force-active">Email *</label>
                                                        <input id="email" name="email" readonly type="text" value="{{$values->email}}" data-error=".errorTxt25">
                                                        <div class="errorTxt25"></div>
                                                    </div>
                                                    <div class="clearfix" style="clear:both"></div>
                                                    <div class="input-field col s12 m6">
                                                        <input type="text" value="{{ date('d/m/Y',strtotime($values->doe)) }}" class="datepicker" id="doe" name="doe">
                                                        <label for="doe" class="force-active">Date of Emp</label>
                                                        <div class="errorTxt26"></div>
                                                    </div>
                                                    <div class="col s12 m6">
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
                                                                    <span>Rejoined</span>
                                                                </label>
                                                            </p>
                                                        </div>
                                                        <div class="col s12 m9 " style="display:@php echo $values->old_member_number!="" && $values->old_member_number!=Null ? 'block' : 'none'; @endphp" id="member_old_div">
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
                                                    <div class="clearfix" style="clear:both"></div>
                                                    <div class="col s12 m6">
                                                        <label>Designation*</label>
                                                        <select name="designation" id="designation" data-error=".errorTxt2" class="error browser-default selectpicker">
                                                            <option value="">Select</option>
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
                                                    <div class="col s12 m6">
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
                                                    <div class="col s12 m6">
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
                                                    <div class="clearfix" style="clear:both"></div>
                                                    <div class="col s12 m6">
                                                        <label>City Name*</label>
                                                        <select name="city_id" id="city_id" data-error=".errorTxt6" class="error browser-default selectpicker">
                                                            <option value="">Select</option>
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
                                                        <input id="address_two" name="address_two" required type="text" value="{{$values->address_two}}" data-error=".errorTxt9">
                                                        <div class="errorTxt9"></div>
                                                    </div>
                                                    <div class="clearfix" style="clear:both"></div>
                                                    <div class="input-field col s12 m6">
                                                        <label for="address_three" class="force-active">Address Line 3</label>
                                                        <input id="address_three" name="address_three"  type="text" value="{{$values->address_three}}" data-error=".errorTxt10">
                                                        <div class="errorTxt10"></div>
                                                    </div>
                                                    <div class="col s12 m6">
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
                                                    <div class="clearfix" style="clear:both"></div>
                                                    <div class="input-field col s12 m6">
                                                        <input type="text" class="datepicker" id="doj" value="{{ date('d/m/Y',strtotime($values->doj)) }}" name="doj">
                                                        <label for="doj" class="force-active">Date of Joining</label>
                                                        <div class="errorTxt"></div>
                                                    </div>
                                                    <div class="input-field col s12 m6">
                                                        <label for="salary" class="force-active">Salary*</label>
                                                        <input id="salary" name="salary" value="{{$values->salary}}" required type="text" data-error=".errorTxt11">
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
                                                    <div class="col s12 m6 ">
                                                        <label>Bank Name*</label>
                                                        </br>
                                                        <p style="margin-top:10px;font-weight:bold;">
                                                            @php
                                                                $m_companyid = CommonHelper::getcompanyidbyBranchid($values->branch_id);
                                                            @endphp
                                                            {{ CommonHelper::getCompanyName($m_companyid) }}
                                                        </p>
                                                       
                                                        <div class="input-field hide">
                                                             <select name="company_id" id="company" class="error browser-default selectpicker hide">
                                                            <option value="">Select</option>
                                                            @foreach($data['company_view'] as $value)
                                                            <option @php //if($value->id == $values->company_id) { echo "selected";} @endphp value="{{$value->id}}">{{$value->company_name}}</option>
                                                            @endforeach
                                                        </select>
                                                            <div class="errorTxt14"></div>
                                                        </div>
                                                    </div>

                                                    @php 

                                                    $auth_user = Auth::user(); $check_union = $auth_user->hasRole('union'); if($check_union){ $branch_requird = 'required'; $branch_disabled = ''; $branch_hide = ''; $branch_id = ''; }else{ $branch_requird = ''; $branch_disabled = 'disabled'; $branch_hide = 'hide'; $branch_id = $auth_user->branch_id; } $branch_hide = 'hide'; @endphp
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
                                                     <div class="clearfix" style="clear:both"></div>
                                                    <div class="col s12 m6">
                                                        <label>{{__('Levy') }}</label>
                                                        <select name="levy" id="levy" class="error browser-default selectpicker">
                                                            <option value="">{{__('Select levy') }}</option>
                                                            <option value="Not Applicable" {{ $values->levy == 'Not Applicable' ? 'selected' : '' }}> N/A</option>
                                                            <option value="Yes" {{ $values->levy == 'Yes' ? 'selected' : '' }}>Yes</option>
                                                            <option value="NO" {{ $values->levy == 'NO' ? 'selected' : '' }}>No</option>
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="input-field col s12 m6">
                                                        <input id="levy_amount" name="levy_amount" type="text" value="{{$values->levy_amount}}">
                                                        <label for="levy_amount" class="force-active">{{__('Levy Amount') }} </label>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="col s12 m6">
                                                        <label>{{__('TDF') }}</label>
                                                        <select name="tdf" id="tdf" class="error browser-default selectpicker">
                                                            <option value="0">Select TDF</option>
                                                            <option value="Not Applicable" {{ $values->tdf == 'Not Applicable' ? 'selected' : '' }}> N/A</option>
                                                            <option value="Yes" {{ $values->tdf == 'Yes' ? 'selected' : '' }}>Yes</option>
                                                            <option value="NO" {{ $values->tdf == 'NO' ? 'selected' : '' }}>No</option>
                                                        </select>
                                                    </div>
                                                   
                                                    <div class="input-field col s12 m6">
                                                        <input id="tdf_amount" name="tdf_amount" type="text" value="{{$values->tdf_amount}}">
                                                        <label for="tdf_amount" class="force-active">{{__('TDF Amount') }} </label>
                                                    </div>
                                                     <div class="clearfix"></div>
                                                    <div class="input-field col s12 m6">
                                                        <label for="employee_id" class="force-active">Employee ID</label>
                                                        <input id="employee_id" name="employee_id" value="{{$values->employee_id}}" type="text">
                                                    </div>
                                                    @php if($values->is_request_approved==0 && $check_union==1){ @endphp
                                                    <div class="col s12 m6 ">
                                                        <label>Status*</label>
                                                        <label>
                                                            <input type="checkbox" id="activate_account" name="activate_account" value='1' /> &nbsp; <span>Verify account</span>
                                                        </label>
                                                        <div class="input-field">
                                                            <div class="errorTxt16"></div>
                                                        </div>
                                                    </div>
                                                    @php } @endphp @php if($values->is_request_approved==0){ @endphp
                                                    <div class="col s12 m6 ">
                                                        <label>Status*</label>
                                                        <p style="margin-top:10px;">
                                                            <span style="color: rgba(255, 255, 255, 0.901961);" class=" gradient-45deg-deep-orange-orange padding-2 medium-small">Pending</span>
                                                        </p>
                                                    </div>
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
                                                    </div>
                                                    @php } @endphp
                                                </div>
                                            </div>
                                        </fieldset>
                                        <h3>Additional Details</h3>
                                        <fieldset>
                                            <div class="row">
                                                <ul class="collapsible collapsible-accordion" data-collapsible="accordion">
                                                    <li class="active">
                                                        <div class="collapsible-header gradient-45deg-indigo-purple white-text"><i class="material-icons">details</i> {{__('Nominee Details') }}</div>
                                                        <div class="collapsible-body">
                                                            <div id="nominee_add_section" class="row">
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
                                                                                <th data-field="nric_o">NRIC-0</th>
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
                                                            <div class="row">
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
													<li>
													<div class="collapsible-header gradient-45deg-indigo-purple white-text"><i class="material-icons">blur_circular</i> {{__('Fee Details') }}</div>
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
                                                                    <input id="fee_amount" name="fee_amount" value="0" type="text">
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
                                                                    @php // print_r($data['fee_view']); @endphp
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

                                                                                $m_salary = $values->salary;
                                                                                $m_subscription = CommonHelper::getsubscription_bysalary( $m_salary);
                                                                            @endphp

                                                                            <tr>
                                                                                <td>Subscription</td>
                                                                                <td>{{$m_subscription}}</td>
                                                                                <td></td>
                                                                            </tr>

                                                                            @php {{ $sl = 0; }} @endphp @foreach($data['fee_view'] as $key=>$value)
                                                                            <tr id="nominee_{{ $sl }}">
                                                                                <td><span id="fee_name_label_{{ $sl }}">{{ CommonHelper::get_fee_name($value->fee_id) }}</span>
                                                                                    <input type="text" class="hide" name="fee_auto_id[]" id="fee_auto_id_{{ $sl }}" value="{{$value->id}}"></input>
                                                                                    <input type="text" class="hide" name="fee_name_id[]" id="fee_name_id_{{ $sl }}" value="{{$value->fee_id}}"></input>
                                                                                </td>

                                                                                <td><span id="fee_amount_label_{{ $sl }}">{{$value->fee_amount}}</span>
                                                                                    <input type="text" class="hide" name="fee_name_amount[]" id="fee_name_amount_{{ $sl }}" value="{{$value->fee_amount}}">
                                                                                </td>

                                                                                <td>
                                                                                    <a class="btn-floating waves-effect waves-light cyan edit_fee_row hide" href="#modal_fee" data-id="{{$sl}}"><i class="material-icons left">edit</i></a>
                                                                                    <a class="btn-floating waves-effect waves-light amber darken-4 delete_fee_db" data-id="{{$sl}}" data-autoid="{{$value->id}}" onclick="if (confirm('Are you sure you want to delete?')) return true; else return false;"><i class="material-icons left">delete</i></a>
                                                                                </td>

                                                                            </tr>
                                                                            @php {{ $sl++; }} @endphp @endforeach

                                                                            <input id="fee_row_id" class="hide" name="fee_row_id" value="{{ $sl }}" type="text">
                                                                        </tbody>

                                                                    </table>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                    <div class="collapsible-header gradient-45deg-indigo-purple white-text"><i class="material-icons">blur_circular</i> {{__('Fund Details') }}</div>
                                                        <div class="collapsible-body ">
															</br>
                                                            <div class="row">
																@php
																	$lastmonthendrecord = CommonHelper::getlastMonthEndByMember($values->mid); 
																@endphp
                                                                <div class="col s6 m3">
																	<ul id="task-card" class="collection with-header">
																		<li class="collection-header" style="padding:5px;background:#6857af;">
																		   <p class="task-card-date">Monthly</p>
																		</li>
																	</ul>
																	<div class="row">
																		<div class="col s12">
																			Subs:
																		    <div class="input-field inline" style="margin:0;">
																				<input id="email_inline" type="text" class="validate" value="@if(!empty($lastmonthendrecord)){{$lastmonthendrecord->SUBSCRIPTION_AMOUNT}}@endif" readonly style="height:2rem;">
																		    </div>
																		</div>
																		<div class="col s12">
																			BF &nbsp; &nbsp;:
																		    <div class="input-field inline" style="margin:0;">
																				<input id="email_inline" type="text" class="validate" value="@if(!empty($lastmonthendrecord)){{$lastmonthendrecord->BF_AMOUNT}}@endif" readonly style="height:2rem;">
																		    </div>
																		</div>
                                                                        <div class="col s12">
                                                                            UC &nbsp;&nbsp; :
                                                                            <div class="input-field inline" style="margin:0;">
                                                                                <input id="email_inline" type="text" value="@if(!empty($lastmonthendrecord)){{$lastmonthendrecord->INSURANCE_AMOUNT}}@endif" class="validate" readonly style="height:2rem;">
                                                                            </div>
                                                                        </div>
																		<div class="col s12">
																			Ins &nbsp;&nbsp; :
																		    <div class="input-field inline" style="margin:0;">
																				<input id="email_inline" type="text" value="@if(!empty($lastmonthendrecord)){{$lastmonthendrecord->BF_AMOUNT+$lastmonthendrecord->INSURANCE_AMOUNT}}@endif" class="validate" readonly style="height:2rem;">
																		    </div>
																		</div>
																	</div>
																</div>
																<div class="col s6 m3">
																	<ul id="task-card" class="collection with-header">
																		<li class="collection-header " style="padding:5px;background:#6857af;">
																		   <p class="task-card-date">Acc</p>
																		</li>
																	</ul>
																	<div class="row">
																		<div class="col s12">
																			Subs:
																		    <div class="input-field inline" style="margin:0;">
																				<input id="email_inline" type="text" class="validate" readonly style="height:2rem;" value="@if(!empty($lastmonthendrecord)){{$lastmonthendrecord->ACCSUBSCRIPTION}}@endif">
																		    </div>
																		</div>
																		<div class="col s12">
																			BF &nbsp; &nbsp;:
																		    <div class="input-field inline" style="margin:0;">
																				<input id="email_inline" type="text" class="validate" readonly style="height:2rem;" value="@if(!empty($lastmonthendrecord)){{$lastmonthendrecord->ACCBF}}@endif">
																		    </div>
																		</div>
                                                                        <div class="col s12">
																			UC &nbsp;&nbsp; :
																		    <div class="input-field inline" style="margin:0;">
																				<input id="email_inline" type="text" class="validate" readonly value="@if(!empty($lastmonthendrecord)){{$lastmonthendrecord->ACCINSURANCE}}@endif" style="height:2rem;">
																		    </div>
																		</div>
                                                                        @php
                                                                            $total_ins_count = CommonHelper::getTotalInsCount($values->mid);
                                                                        @endphp
																		<div class="col s12">
																			Ins &nbsp;&nbsp; :
																		    <div class="input-field inline" style="margin:0;">
																				<input id="email_inline" type="text" class="validate" readonly value="@if(!empty($lastmonthendrecord)){{ ($lastmonthendrecord->BF_AMOUNT+$lastmonthendrecord->INSURANCE_AMOUNT) * $total_ins_count }}@endif" style="height:2rem;">
																		    </div>
																		</div>
																	</div>
																</div>
																<div class="col s6 m3">
																	<ul id="task-card" class="collection with-header">
																		<li class="collection-header " style="padding:5px;background:#6857af;">
																		   <p class="task-card-date">Total Months Paid</p>
																		</li>
																	</ul>
																	<div class="row">
																		<div class="col s12">
																			Subs:
																		    <div class="input-field inline" style="margin:0;">
																				<input id="email_inline" type="text" class="validate" readonly value="@if(!empty($lastmonthendrecord)){{$lastmonthendrecord->TOTALMONTHSPAID}}@endif" style="height:2rem;">
																		    </div>
																		</div>
																		<div class="col s12">
																			BF &nbsp; &nbsp;:
																		    <div class="input-field inline" style="margin:0;">
																				<input id="email_inline" type="text" class="validate" value="@if(!empty($lastmonthendrecord)){{$lastmonthendrecord->TOTALMONTHSPAID}}@endif" readonly style="height:2rem;">
																		    </div>
																		</div>
                                                                        @php
                                                                            $total_ins_count = CommonHelper::getTotalInsCount($values->mid);
                                                                        @endphp
																		<div class="col s12">
																			UC &nbsp;&nbsp; :
																		    <div class="input-field inline" style="margin:0;">
																				<input id="email_inline" type="text" class="validate" value="{{$total_ins_count}}" readonly style="height:2rem;">
																		    </div>
																		</div>
                                                                        <div class="col s12">
                                                                            Ins &nbsp;&nbsp; :
                                                                            <div class="input-field inline" style="margin:0;">
                                                                                <input id="email_inline" type="text" class="validate" value="{{$total_ins_count}}" readonly style="height:2rem;">
                                                                            </div>
                                                                        </div>
																	</div>
																</div>
																<div class="col s6 m3">
																	<ul id="task-card" class="collection with-header">
																		<li class="collection-header" style="padding:5px;background:#6857af;">
																		   <p class="task-card-date">Total Months Due</p>
																		</li>
																	</ul>
																	<div class="row">
																		<div class="col s12">
																			Subs:
																		    <div class="input-field inline" style="margin:0;">
																				<input id="email_inline" type="text" class="validate" value="@if(!empty($lastmonthendrecord)){{$lastmonthendrecord->TOTALMONTHSDUE}}@endif" readonly style="height:2rem;">
																		    </div>
																		</div>
																		<div class="col s12">
																			BF &nbsp; &nbsp;:
																		    <div class="input-field inline" style="margin:0;">
																				<input id="email_inline" type="text" value="@if(!empty($lastmonthendrecord)){{$lastmonthendrecord->TOTALMONTHSDUE}}@endif" class="validate" readonly style="height:2rem;">
																		    </div>
																		</div>
																		<div class="col s12">
																			Ins &nbsp;&nbsp; :
																		    <div class="input-field inline" style="margin:0;">
																				<input id="email_inline" value="@if(!empty($lastmonthendrecord)){{$lastmonthendrecord->TOTALMONTHSDUE}}@endif" type="text" class="validate" readonly style="height:2rem;">
																		    </div>
																		</div>
																	</div>
																</div>
                                                            </div>
                                                            </br>
                                                            <div class="row">
                                                                <div class="col s8 m8">
																	&nbsp;
																</div>
																
																
                                                            </div>
															
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </fieldset>
                                        @if($irc_status==1 || $new_resign_status==1)
                                        <h3>Resignation</h3> 
											@php 
												
												$resignedrow = CommonHelper::getResignDataByMember($values->mid); 
												$reasondata = CommonHelper::getResignData(); 
												$lastmonthendrow = CommonHelper::getlastMonthEndByMember($values->mid); 
												$lastpaid = ''; $totalmonthspaid = ''; $bfcontribuion = ''; $insamount = ''; $service_year = ''; $unioncontribution = ''; $accbenefit = ''; 
												if(!empty($lastmonthendrow)){ 
													$lastpaid = date('M/Y',strtotime($lastmonthendrow->StatusMonth)); 
													$totalmonthspaid = $lastmonthendrow->TOTALMONTHSPAID; 
													$bfcontribuion = $lastmonthendrow->ACCBF; 
													$insamount = $lastmonthendrow->ACCINSURANCE; 
													$service_year = CommonHelper::calculate_age($values->doj); 
													$unioncontribution = $lastmonthendrow->ACCINSURANCE; 
													$accbenefit = $lastmonthendrow->ACCBENEFIT; 
												} 
												$resignstatus = 0; $resign_date = ''; $relation_code = ''; $pay_mode = ''; $chequeno = ''; $voucher_date = ''; $chequedate = ''; $chequedate = ''; $totamount = 0; 
												if(!empty($resignedrow)){ 
													$resignstatus = 0; 
													$resign_date = date('d/m/Y',strtotime($resignedrow->resignation_date)); 
													$totalmonthspaid = $resignedrow->months_contributed; 
													$bfcontribuion = $resignedrow->accbf; 
													$insamount = $resignedrow->insuranceamount; 
													$relation_code = $resignedrow->relation_code; 
													$service_year = $resignedrow->service_year; 
													$unioncontribution = $resignedrow->unioncontribution; 
													$accbenefit = $resignedrow->accbenefit; 
													$pay_mode = $resignedrow->paymode; 
													$chequeno = $resignedrow->chequeno; 
													$voucher_date = $resignedrow->voucher_date != '0000-00-00 00:00:00' ? date('d/m/Y',strtotime($resignedrow->voucher_date)) : ''; 
													$chequedate = $resignedrow->chequedate != '0000-00-00 00:00:00' ? date('d/m/Y',strtotime($resignedrow->chequedate)) : ''; 
													$totamount = $resignedrow->amount; 
												} 
												
											@endphp
                                        <fieldset>
                                            <div class="col-sm-8 col-sm-offset-1">
                                                <div class="row">
                                                    </br>
                                                    <div class="input-field col s6">
                                                        <label for="resign_date" class="force-active">Resign Date *</label>
                                                        <input type="text" class="datepicker" id="resign_date" data-error=".errorTxt500" name="resign_date" value="{{$resign_date}}">
                                                        <input type="text" class="hide" id="totalarrears" value="{{$totalmonthspaid}}" name="totalarrears">
                                                        <input type="text" class="hide" id="resignstatus" value="{{$resignstatus}}" name="resignstatus">
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
                                                                    <option value="">Select</option>
                                                                    @foreach($data['relationship_view'] as $key=>$value)
                                                                    <option @if($relation_code!='' ) @php echo $relation_code==$value->id ? 'selected' :''; @endphp @endif value="{{$value->id}}" >{{$value->relation_name}}</option>
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
                                                        <label for="resign_reason force-active">Reason*</label>
                                                        <select name="resign_reason" id="resign_reason" data-error=".errorTxt503" class="force-active error browser-default selectpicker">
                                                            <option value="">Select reason</option>
                                                            @foreach($reasondata as $reason)
                                                            <option @if(!empty($resignedrow)) @php echo $resignedrow->reason_code==$reason->id ? 'selected' :''; @endphp @endif value="{{$reason->id}}">{{$reason->reason_name}}</option>
                                                            @endforeach
                                                            <div class="input-field">
                                                                <div class="errorTxt503"></div>
                                                            </div>
                                                        </select>
                                                    </div>
                                                    <div class="input-field col s6">
                                                        <input type="text" id="claimer_name" name="claimer_name" data-error=".errorTxt504" value="@if(!empty($resignedrow)){{$resignedrow->claimer_name}}@endif">
                                                        <label for="claimer_name" class="force-active">Claimer Name</label>
                                                        <div class="errorTxt504"></div>
                                                    </div>
                                                    <div class="clearfix" style="clear:both"></div>
                                                    <div class="input-field col s6">
                                                        <div class="row">
                                                            <div class="input-field col s6">
                                                                <input type="text" id="service_year" name="service_year" value="{{ $service_year }}" readonly  >
                                                                <label for="service_year" class="force-active">Service Year</label>
                                                            </div>
                                                            <div class="input-field col s6">
                                                                <input type="text" id="benefit_year" name="benefit_year" readonly>
                                                                <label for="benefit_year" class="force-active">Benefit Year</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="input-field col s6">
                                                        <div class="row">
                                                            <div class="input-field col s12">
                                                                <input type="text" id="bf_contribution" name="bf_contribution" value="{{$bfcontribuion}}">
                                                                <label for="bf_contribution" class="force-active">BF Contribution</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix" style="clear:both"></div>
                                                    <div class="input-field col s6">
                                                        <div class="row">
                                                            <div class="input-field col s6">
                                                                <input type="text" id="contributed_months" name="contributed_months" value="{{$totalmonthspaid}}">
                                                                <label for="contributed_months" class="force-active">Contributed Months</label>
                                                            </div>
                                                            <div class="input-field col s6">
                                                                <input type="text" id="union_contribution" name="union_contribution" value="{{$unioncontribution}}">
                                                                <label for="union_contribution" class="force-active">Union Contribution</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="input-field col s6">
                                                        <div class="row">
                                                            <div class="input-field col s12">
                                                                <input type="text" id="benefit_amount" name="benefit_amount" value="{{$accbenefit}}">
                                                                <label for="benefit_amount" class="force-active">Benefit Amount</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix" style="clear:both"></div>
                                                    <div class="col s12 m6">
                                                        <div class="row">
                                                            <div class="col s12 m6">
                                                                <label>{{__('PayMode') }}</label>
                                                                <select name="pay_mode" id="pay_mode" class="error browser-default selectpicker">
                                                                    <option value="" selected>{{__('Choose your option') }}</option>
                                                                    <option @if($pay_mode==1) selected @endif value="1"> CHEQUE</option>
                                                                    <option @if($pay_mode==2) selected @endif value="2"> ONLINE PAY</option>
                                                                </select>
                                                            </div>
                                                            <div class="input-field col s6">
                                                                <input type="text" id="reference_number" name="reference_number" value="{{$chequeno}}">
                                                                <label id="reference_number_text" for="reference_number" class="force-active">Reference Number</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col s12 m6">
                                                        <div class="row">
                                                            <div class="input-field col s12 ">
                                                                <input type="text" id="insurance_amount" name="insurance_amount" value="{{$insamount}}">
                                                                <label for="insurance_amount" class="force-active">Insurance Amount</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix" style="clear:both"></div>
                                                    <div class="col s12 m6">
                                                        <div class="row">
                                                            <div class="col s12 m6">
                                                                <label>{{__('Cheque Date') }}*</label>
                                                                <input type="text" name="cheque_date" id="cheque_date" class="datepicker" value="{{$chequedate}}">
                                                            </div>
                                                            <div class="col s12 m6">
                                                                <label>{{__('Payment Confirmation') }}*</label>
                                                                <input type="text" name="payment_confirmation" id="payment_confirmation" class="datepicker" value="{{$voucher_date}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col s12 m6">
                                                        <label for="total_amount" class="force-active">Total Amount</label>
                                                        <input type="text" id="total_amount" name="total_amount" value="{{$totamount}}" readonly>
                                                    </div>
                                                    <div class="clearfix" style="clear:both"></div>
                                                    <div class="col s12 m12 center">
                                                        @if(empty($resignedrow))
                                                        <button id="submitResignation" class="waves-effect waves-dark btn btn-primary form-save-btn right" type="button">{{'Resign'}}</button>
                                                        @endif 
														@if(!empty($resignedrow))
                                                        <a target="_blank" href="{{ route('resign.status', [app()->getLocale(),Crypt::encrypt($values->mid)])  }}" class="btn waves-effect waves-light pink m2 mdl-button">View resign Details</a>
														<div class="col m2 hide">
															<h5 style="color: rgba(255, 255, 255, 0.901961);" class="gradient-45deg-indigo-light-blue padding-2 medium-small">Member already resigned</h5>
														</div>
														@endif
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                        @endif
										<div class="row">
											<div class="col s12 m8 center">
													@php

														$color = CommonHelper::getStatusColor($values->status_id);
													@endphp
                                                  
													<h4 style="color:{{$color}};font-size:2rem;">
                                                    {{ CommonHelper::getStatusName($values->status_id) }} 
                                                    @if(!empty($resignedrow))
                                                       - {{ $resign_date }}
                                                    @endif
													@if(!empty($lastmonthendrecord))
														@if($lastmonthendrecord->TOTALMONTHSDUE>0)
															, {{$lastmonthendrecord->TOTALMONTHSDUE}} Arrears pending
														@else
															, 0 Arrears pending
														@endif
													@else
														, 0 Arrears pending
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
            /* email: {
                required: "Please enter valid email",
                remote: '{{__("Email Already exists") }}',
            }, */
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
    $('#bf_contribution,#benefit_amount,#insurance_amount').keyup(function() {
        var bf_contribution = $('#bf_contribution').val();
        var insurance_amount = $('#insurance_amount').val();
        var benefit_amount = $('#benefit_amount').val();
        bf_contribution = bf_contribution != "" && typeof(bf_contribution) != "number" ? parseInt(bf_contribution) : 0;
        insurance_amount = insurance_amount != "" && typeof(insurance_amount) != "number" ? parseInt(insurance_amount) : 0;
        benefit_amount = benefit_amount != "" && typeof(benefit_amount) != "number" ? parseInt(benefit_amount) : 0;
        var total_amount = parseInt(insurance_amount) + parseInt(bf_contribution) + parseInt(benefit_amount);
        $('#total_amount').val(total_amount);
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
        if (resign_date != "" && last_paid != "" && resign_reason != "") {
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
					if(res){
						$("#service_year").val(res);
					}else{
						$("#service_year").val(0);
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
		var resign_reason = $('#resign_reason').val();
		var status_id = $('#status_id').val();
        var doj = $('#doj').val();
		if(service_year!=0 && resign_reason!='' && status_id==1 && doj!=''){
			$.ajax({
				type:"GET",
				dataType:"json",
				url:"{{URL::to('/get-bf-amount') }}?service_year="+service_year+"&resign_reason="+resign_reason+"&doj="+doj,
				success:function(res){
					if(res){
						$("#benefit_amount").val(res);
					}else{
						$("#benefit_amount").val(0);
					}
				}
			});
		}else{
			$("#benefit_amount").val(0);
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
</script>
@include('membership.member_common_script') 
@endsection