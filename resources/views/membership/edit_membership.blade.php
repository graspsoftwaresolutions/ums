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

    $hidemember='';
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
                                    <h4 class="card-title">@if($data['view_status']==1) View @else Edit @endif Membership</h4> 
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
                                    <form class="formValidate" id="wizard2" method="post" enctype="multipart/form-data" action="{{ url(app()->getLocale().'/membership_save') }}">
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
                                                        <div class="row padding-left-20">
                                                            <div class="col s12 m2">
                                                                <p style="font-size: 16px;">
                                                                    Reason : 
                                                                </p>
                                                            </div>
                                                            <div class="col s12 m6">
                                                                {{ $irc_reason_name }}
                                                            </div>
                                                        </div>
                                                                            
                                                    </div>
                                                    @php
                                                        $reasonlabel = $irc_reason_name;
                                                        $irc_details = $irc_data;
                                                        $section_type_val = '';
                                                        if($reasonlabel=='RETIRED'){
                                                            $section_type_val = 1;
                                                        }else if($reasonlabel=='DECEASED'){
                                                            $section_type_val = 2;
                                                        }else if($reasonlabel=='PROMOTED'){
                                                            $section_type_val = 3;
                                                        }
                                                        else if($reasonlabel=='RESIGN FROM BANK' || $reasonlabel=='RESIGN FROM UNION' || $reasonlabel=='TERMINATED BY BANK'){
                                                            $section_type_val = 4;
                                                        }else if($reasonlabel=='EXPELLED' || $reasonlabel=='STRUCK OFF' || $reasonlabel=='BLACK LIST' || $reasonlabel=='BLACKLISTED FROM UNION'){
                                                            $section_type_val = 5;
                                                        }else{
                                                            $section_type_val = 4;
                                                        }

                                                        if($irc_details->gender == 'Female'){
                                                            $genderlable = 'She';
                                                            $genderlableone = 'Her';
                                                        }else{
                                                            $genderlable = 'He';
                                                            $genderlableone = 'His';
                                                        }

                                                        if($irc_details->posfilledbytype==1){
                                                            $filledbytype = 'Member';
                                                        }elseif($irc_details->posfilledbytype==2){
                                                            $filledbytype = 'Non-Member';
                                                        }else{
                                                            $filledbytype = 'Other';
                                                        }
                                                    @endphp
                                                   
                                                    <div class="col s12 m12">
                                                        <input type="text" class="hide" name="section_type" id="section_type" value="{{ $section_type_val }}">
                                                        <div id="retired_section" class="reasonsections @if($section_type_val != 1) hide @endif "> 

                                                            <div class="row padding-left-20">
                                                                <div class="col s12">
                                                                    <label>
                                                                        <input type="checkbox" class="common-checkbox" name="personnameboxone" id="personnameboxone"  value="1" @if($irc_details->nameofperson ==1) checked @endif />
                                                                        <span>BF Applicant’s Name is</span>
                                                                    </label> 
                                                                    <div class="input-field inline">
                                                                        <input type="text" id="person_nameone" name="person_nameone" readonly value="{{$irc_details->resignedmembername}}" class="inline-box" style="width: 500px;">
                                                                        
                                                                    </div>
                                                                </div>
                                                                <div class="col s12">
                                                                    <label>
                                                                        <input type="checkbox" class="common-checkbox" name="retiredboxone" id="retiredboxone"  @if($irc_details->retiredbox ==1) checked @endif value="1"/>
                                                                        <span><span class="gender">{{$genderlable}}</span> <span style="text-decoration: underline;">RETIRED</span> w.e.f.</span>
                                                                    </label> 
                                                                    <div class="input-field inline">
                                                                        <input type="text"  name="gradewefone" id="gradewefone" placeholder="grade w.e.f"  class="datepicker-custom inline-box" value="{{$irc_details->gradewef}}" />
                                                                        
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>  


                                                            <div class="row padding-left-20">
                                                                <div class="col s12 m12 ">
                                                                    <p>
                                                                        <label>
                                                                        <input type="checkbox" class="common-checkbox" name="messengerboxone" @if($irc_details->messengerbox ==1) checked @endif id="messengerboxone"  value="1"/>
                                                                        <span><span class="gender">{{$genderlable}}</span> was a {{$irc_details->messengertype}} before RETIEMENT [Delete which is not applicable]</span>
                                                                        </label> 
                                                                    </p>    
                                                                </div>
                                                            
                                                            </div>  
                                                            <div class="row padding-left-20">
                                                                <div class="col s12">
                                                                    <label>
                                                                        <input type="checkbox" class="common-checkbox" name="attachedboxone" @if($irc_details->attachedbox ==1) checked @endif id="attachedboxone"  value="1"/>
                                                                        <span>Attached is <span class="genderone">{{$genderlableone}}</span> RETIREMENT Letter (compulsory)</span>
                                                                    </label> 
                                                                    <div class="input-field inline hide">
                                                                        <input type="text" id="attachedone" name="attachedone" value="{{$irc_details->attached_desc}}" class="inline-box" style="width: 500px;" >
                                                                        
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>  
                                                            <div class="row padding-left-20">
                                                                <div class="col s12">
                                                                    <label>
                                                                        <input type="checkbox" class="common-checkbox" @if($irc_details->jobtakenbox ==1) checked @endif name="jobtakenboxone" id="jobtakenboxone"  value="1"/>
                                                                        <span>Member’s job functions have been taken over by</span>
                                                                    </label> 
                                                                    <div class="input-field inline">
                                                                        <input type="text"  name="jobtakenbyone" id="jobtakenbyone" placeholder="" value="{{$irc_details->jobtakenby}}" class="inline-box" style="width: 500px;"/>
                                                                        
                                                                    </div>
                                                                     and
                                                                </div>
                                                            
                                                            </div>
                                                            <div class="row padding-left-20">
                                                                <div class="col s12">
                                                                    <label>
                                                                        <input type="checkbox" class="common-checkbox" @if($irc_details->posfilledbybox ==1) checked @endif name="posfilledbyboxone" id="posfilledbyboxone"  value="1"/>
                                                                        <span>Member’s position has not been filled up by another {{$filledbytype}} [Please specify others in detail]</span>
                                                                    </label> 
                                                                    <div class="input-field inline">
                                                                        <input type="text"  name="posfilledbyone" id="posfilledbyone" value="{{$irc_details->posfilledby}}" placeholder=""  class="inline-box" style="width: 200px;" />
                                                                        
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>  
                                                            <div class="row padding-left-20">
                                                                <div class="col s12">
                                                                    <label>
                                                                        <input type="checkbox" class="common-checkbox" @if($irc_details->replacestaffbox ==1) checked @endif  name="replacestaffboxone" id="replacestaffboxone"  value="1"/>
                                                                        <span>REPLACEMENT Staff Grade is {{$irc_details->replacestafftype}} [Please specify others in detail] </span>
                                                                    </label> 
                                                                    <div class="input-field inline">
                                                                        <input type="text"  name="replacestaffone" id="replacestaffone" value="{{$irc_details->replacestaff}}" placeholder=""  class="inline-box" style="width: 200px;" />
                                                                        
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>  
                                                            <div class="row padding-left-20">
                                                                <div class="col s12">
                                                                    <label>
                                                                        <input type="checkbox" class="common-checkbox" @if($irc_details->appcontactbox ==1) checked @endif name="appcontactboxone" id="appcontactboxone"  value="1"/>
                                                                        <span>Applicant Contact</span>
                                                                    </label> 
                                                                    <div class="input-field inline">
                                                                        <input type="text"  name="appcontactone" id="appcontactone" value="{{$irc_details->appcontact}}" placeholder="" class="inline-box allow_contactnumbers" style="width: 250px;" />
                                                                        
                                                                    </div>
                                                                     <span>Office</span>
                                                                     <div class="input-field inline">
                                                                        <input type="text"  name="appofficeone" id="appofficeone" value="{{$irc_details->appoffice}}" placeholder="" class="inline-box allow_contactnumbers" style="width: 250px;" />
                                                                        
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>
                                                                <div class="col s12">
                                                                    
                                                                    <span>
                                                                        &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; <span>H/P</span>
                                                                    </span> 
                                                                    
                                                                    <div class="input-field inline">
                                                                        <input type="text"  name="apphpone" id="apphpone" placeholder="" value="{{$irc_details->appmobile}}" class="inline-box allow_contactnumbers" style="width: 250px;" />
                                                                        
                                                                    </div>
                                                                     <span>Fax</span>
                                                                     <div class="input-field inline">
                                                                        <input type="text"  name="appfaxone" id="appfaxone" placeholder="" value="{{$irc_details->appfax}}" class="inline-box allow_contactnumbers" style="width: 250px;" />
                                                                        
                                                                    </div>
                                                                    <span>Email</span>
                                                                     <div class="input-field inline">
                                                                        <input type="text"  name="appemailone" id="appemailone" placeholder="" value="{{$irc_details->appemail}}" class="inline-box " style="width: 250px;"/>
                                                                        
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>  
                                                        </div>
                                                        <div id="deceased_section" class="reasonsections  @if($section_type_val != 2) hide @endif "> 

                                                            <div class="row padding-left-20">
                                                                <div class="col s12">
                                                                    <label>
                                                                        <input type="checkbox" class="common-checkbox" name="memberdemisedboxtwo" id="memberdemisedboxtwo" @if($irc_details->demised_onboxtwo ==1) checked @endif value="1"/>
                                                                        <span>Member DEMISED on</span>
                                                                    </label> 
                                                                    <div class="input-field inline">
                                                                        <input type="text" id="memberdemisedtwo" value="{{$irc_details->demised_ontwo}}" class="inline-box" style="width: 560px;" name="memberdemisedtwo" >
                                                                        
                                                                    </div>
                                                                </div>

                                                            </div>  

                                                            <div class="row padding-left-20">
                                                                <div class="col s12">
                                                                    <label>
                                                                        <input type="checkbox" class="common-checkbox" @if($irc_details->member_nameboxtwo ==1) checked @endif name="nameofpersonboxtwo" id="nameofpersonboxtwo"  value="1"/>
                                                                        <span>Name of Member’s next of kin is</span>
                                                                    </label> 
                                                                    <div class="input-field inline">
                                                                        <input type="text"  name="nameofpersontwo" id="nameofpersontwo" placeholder="" value="{{$irc_details->member_nametwo}}" class="inline-box" style="width: 500px;"/>
                                                                        
                                                                    </div>
                                                                </div>

                                                                <div class="col s12">
                                                                    <label>
                                                                        <input type="checkbox" class="common-checkbox" @if($irc_details->relationshipboxtwo ==1) checked @endif name="relationshipboxtwo" id="relationshipboxtwo"  value="1"/>
                                                                        <span>Relationship is</span>
                                                                    </label> 
                                                                    <div class="input-field inline">
                                                                        <input type="text"  name="relationshiptwo" value="{{$irc_details->relationshiptwo}}" id="relationshiptwo" placeholder="" class="inline-box" style="width: 300px;"/>
                                                                        
                                                                    </div>
                                                                </div>
                                                            
                                                            </div>  


                                                            <div class="row padding-left-20">
                                                                <div class="col s12 m1 ">
                                                                    <p>
                                                                        <label>
                                                                        <input type="checkbox" class="common-checkbox" @if($irc_details->applicantboxtwo ==1) checked @endif name="applicantboxtwo" id="applicantboxtwo"  value="1"/>
                                                                        <span>Applicant</span>
                                                                        </label> 
                                                                    </p>    
                                                                </div>
                                                                <div class="col s12 m1 " style="margin-left: 20px;">
                                                                    <p>
                                                                        <label>
                                                                        <input type="radio" class="common-checkbox" @if($irc_details->applicanttwo ==1) checked @endif name="applicanttwo" id="applicanttwo"  value="1"/>
                                                                        <span>Has</span>
                                                                        </label> 
                                                                    </p>    
                                                                </div>
                                                                <div class="col s12 m5 ">
                                                                    <p>
                                                                        <label>
                                                                        <input type="radio" class="common-checkbox" name="applicanttwo" @if($irc_details->applicanttwo ==2) checked @endif id="applicanttwo"  value="2"/>
                                                                        <span>Does Not have Legal Authority (LA) to claim  </span>
                                                                        </label> 
                                                                    </p>    
                                                                </div>
                                                            
                                                            </div>  

                                                            <div class="row padding-left-20">
                                                                <div class="col s12">
                                                                    <label>
                                                                        <input type="checkbox" class="common-checkbox" name="jobtakenboxtwo" @if($irc_details->jobtakenbox ==1) checked @endif id="jobtakenboxtwo"  value="1"/>
                                                                        <span>Member’s job functions have been taken over by</span>
                                                                    </label> 
                                                                    <div class="input-field inline">
                                                                        <input type="text"  name="jobtakenbytwo" id="jobtakenbytwo" value="{{$irc_details->jobtakenby}}" placeholder=""  class="inline-box" style="width: 400px;" />
                                                                        
                                                                    </div>
                                                                     and
                                                                </div>
                                                            
                                                            </div>
                                                            <div class="row padding-left-20">
                                                                <div class="col s12">
                                                                    <label>
                                                                        <input type="checkbox" class="common-checkbox" @if($irc_details->posfilledbybox ==1) checked @endif name="posfilledbyboxtwo" id="posfilledbyboxtwo"  value="1"/>
                                                                        <span>Member’s position has not been filled up by another {{$filledbytype}} [Please specify others in detail]</span>
                                                                    </label> 
                                                                    <div class="input-field inline">
                                                                        <input type="text"  name="posfilledbytwo" value="{{$irc_details->posfilledby}}" id="posfilledbytwo" placeholder=""  class="inline-box" style="width: 200px;" />
                                                                        
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>  
                                                            <div class="row padding-left-20">
                                                                <div class="col s12">
                                                                    <label>
                                                                        <input type="checkbox" class="common-checkbox" @if($irc_details->replacestaffbox ==1) checked @endif name="replacestaffboxtwo" id="replacestaffboxtwo"  value="1"/>
                                                                        <span>REPLACEMENT Staff Grade is {{$irc_details->replacestafftype}} [Please specify others in detail] </span>
                                                                    </label> 
                                                                    <div class="input-field inline">
                                                                        <input type="text"  name="replacestafftwo" value="{{$irc_details->replacestaff}}" id="replacestafftwo" placeholder=""  class="inline-box" style="width: 200px;" />
                                                                        
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>  

                                                            <div class="row padding-left-20">
                                                                <div class="col s12">
                                                                    <label>
                                                                        <input type="checkbox" class="common-checkbox" name="appcontactboxtwo" @if($irc_details->appcontactbox ==1) checked @endif name="appcontactboxone" id="appcontactboxtwo"  value="1"/>
                                                                        <span>Next of Kin’s Contact</span>
                                                                    </label> 
                                                                    <div class="input-field inline">
                                                                        <input type="text"  name="appcontacttwo" value="{{$irc_details->appcontact}}" id="appcontacttwo" placeholder=""  class="inline-box allow_contactnumbers" style="width: 250px;" />
                                                                        
                                                                    </div>
                                                                     <span>Office</span>
                                                                     <div class="input-field inline">
                                                                        <input type="text"  name="appofficetwo" id="appofficetwo" placeholder="" value="{{$irc_details->appoffice}}" class="inline-box allow_contactnumbers" style="width: 250px;" />
                                                                        
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>
                                                                <div class="col s12">
                                                                    
                                                                    <span>
                                                                        &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; <span>Mobile</span>
                                                                    </span> 
                                                                    
                                                                    <div class="input-field inline">
                                                                        <input type="text"  name="appmobiletwo" id="appmobiletwo" placeholder="" value="{{$irc_details->appmobile}}" class="inline-box allow_contactnumbers" style="width: 250px;" />
                                                                        
                                                                    </div>
                                                                     <span>Fax</span>
                                                                     <div class="input-field inline">
                                                                        <input type="text"  name="appfaxtwo" id="appfaxtwo" placeholder="" value="{{$irc_details->appfax}}"  class="inline-box allow_contactnumbers" style="width: 250px;" />
                                                                        
                                                                    </div>
                                                                    <span>Email</span>
                                                                     <div class="input-field inline">
                                                                        <input type="email"  name="appemailtwo" id="appemailtwo" placeholder="" value="{{$irc_details->appemail}}" class="inline-box " style="width: 250px;" />
                                                                        
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>  
                                                        
                                                            
                                                        </div>
                                                        <div id="promoted_section" class="reasonsections  @if($section_type_val != 3) hide @endif "> 

                                                            <div class="row padding-left-20">
                                                                <div class="col s12">
                                                                    <label>
                                                                        <input type="checkbox" class="common-checkbox" @if($irc_details->nameofperson ==1) checked @endif name="nameofpersonboxthree" id="nameofpersonboxthree"  value="1"/>
                                                                        <span>BF Applicant’s Name:</span>
                                                                    </label> 
                                                                    <div class="input-field inline">
                                                                        <input type="text" id="person_namethree" name="person_namethree" readonly value="{{$irc_details->resignedmembername}}" class="inline-box" style="width: 560px;">
                                                                        
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>  

                                                            <div class="row padding-left-20">
                                                                <div class="col s12 m12 ">
                                                                    <p>
                                                                        <label>
                                                                        <input type="checkbox" class="common-checkbox" @if($irc_details->messengerbox ==1) checked @endif name="messengerboxthree" id="messengerboxthree"  value="1"/>
                                                                        <span><span class="gender">{{$genderlable}}</span> was a {{$irc_details->messengertype}}before PROMOTION [Delete which is not applicable]</span>
                                                                        </label> 
                                                                    </p>    
                                                                </div>
                                                            
                                                            </div>  
                                                            <div class="row padding-left-20">
                                                                <div class="col s12">
                                                                    <label>
                                                                        <input type="checkbox" class="common-checkbox" name="promotedboxthree" @if($irc_details->promotedboxthree ==1) checked @endif id="promotedboxthree"  value="1"/>
                                                                        <span><span class="gender">{{$genderlable}}</span> was <span style="text-decoration: underline;">PROMOTED</span> to</span>
                                                                    </label> 
                                                                    <div class="input-field inline">
                                                                        <input type="text" id="promotedthree" name="promotedthree" value="{{$irc_details->promotedto}}" @if($section_type_val == 3) required @endif class="inline-box" style="width: 300px;" >
                                                                        
                                                                    </div>
                                                                    grade w.e.f.
                                                                    <div class="input-field inline">
                                                                        <input type="text"  name="gradewefthree" value="{{$irc_details->gradewef}}" id="gradewefthree" placeholder="grade w.e.f"  class="datepicker-custom inline-box"/>
                                                                        
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>  

                                                        
                                                            <div class="row padding-left-20">
                                                                <div class="col s12">
                                                                    <label>
                                                                        <input type="checkbox" class="common-checkbox" @if($irc_details->attachedbox ==1) checked @endif name="attachedboxthree" id="attachedboxthree"  value="1"/>
                                                                        <span>Attached is <span class="genderone">{{$genderlableone}}</span> Job Description (compulsory)</span>
                                                                    </label> 
                                                                    <div class="input-field inline hide">
                                                                        <input type="text" name="attachedthree" value="{{$irc_details->attached_desc}}" id="attachedthree" class="inline-box" style="width: 500px;" >
                                                                        
                                                                    </div>
                                                                </div>
                                                                <div class="col s12">
                                                                    <label>
                                                                        <input type="checkbox" class="common-checkbox" @if($irc_details->transfertoplaceboxthree ==1) checked @endif name="transfertoplaceboxthree" id="transfertoplaceboxthree" value="1"/>
                                                                        <span>He promoted and transfer to new place</span>
                                                                    </label> 
                                                                    <div class="input-field inline">
                                                                        <input type="text" name="transfertoplacethree" value="{{$irc_details->transfertoplacethree}}" id="transfertoplacethree" class="inline-box" style="width: 500px;" >
                                                                        
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>  
                                                            
                                                            <div class="row padding-left-20">
                                                                <div class="col s12 m12 ">
                                                                    <p>
                                                                        <label>
                                                                        <input type="checkbox" class="common-checkbox" @if($irc_details->samebranchbox ==1) checked @endif name="samebranchboxthree" id="samebranchboxthree"  value="1"/>
                                                                        <span>Member is still in the same {{$irc_details->samebranchtype}} performing the same job functions </span>
                                                                        </label> 
                                                                    </p>    
                                                                </div>
                                                            
                                                            </div>  
                                                            <div class="row padding-left-20">
                                                                <div class="col s12">
                                                                    <label>
                                                                        <input type="checkbox" class="common-checkbox" name="jobtakenboxthree" @if($irc_details->jobtakenbox ==1) checked @endif id="jobtakenboxthree"  value="1"/>
                                                                        <span>Member’s job functions have been taken over by</span>
                                                                    </label> 
                                                                    <div class="input-field inline">
                                                                        <input type="text"  name="jobtakenbythree" value="{{$irc_details->jobtakenby}}" id="jobtakenbythree" placeholder="" class="inline-box" style="width: 500px;"/>
                                                                        
                                                                    </div>
                                                                     and
                                                                </div>
                                                            
                                                            </div>
                                                            <div class="row padding-left-20">
                                                                <div class="col s12">
                                                                    <label>
                                                                        <input type="checkbox" class="common-checkbox" @if($irc_details->posfilledbybox ==1) checked @endif name="posfilledbyboxthree" id="posfilledbyboxthree"  value="1"/>
                                                                        <span>Member’s position has not been filled up by another {{$filledbytype}} [Please specify others in detail]</span>
                                                                    </label> 
                                                                    <div class="input-field inline">
                                                                        <input type="text"  name="posfilledbythree" value="{{$irc_details->posfilledby}}" id="posfilledbythree" placeholder="" class="inline-box" style="width: 200px;"/>
                                                                        
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>  
                                                            <div class="row padding-left-20">
                                                                <div class="col s12">
                                                                    <label>
                                                                        <input type="checkbox" class="common-checkbox" @if($irc_details->replacestaffbox ==1) checked @endif name="replacestaffboxthree" id="replacestaffboxthree"  value="1"/>
                                                                        <span>REPLACEMENT Staff Grade is {{$irc_details->replacestafftype}} [Please specify others in detail] </span>
                                                                    </label> 
                                                                    <div class="input-field inline">
                                                                        <input type="text"  name="replacestaffthree" id="replacestaffthree" value="{{$irc_details->replacestaff}}" placeholder=""  class="inline-box" style="width: 200px;" />
                                                                        
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>  
                                                            <div class="row padding-left-20">
                                                                <div class="col s12">
                                                                    <label>
                                                                        <input type="checkbox" class="common-checkbox" @if($irc_details->appcontactbox ==1) checked @endif name="appcontactboxthree" id="appcontactboxthree"  value="1"/>
                                                                        <span>Applicant Contact</span>
                                                                    </label> 
                                                                    <div class="input-field inline">
                                                                        <input type="text"  name="appcontactthree" value="{{$irc_details->appcontact}}" id="appcontactthree" placeholder="" class="inline-box" style="width: 250px;" />
                                                                        
                                                                    </div>
                                                                     <span>Office</span>
                                                                     <div class="input-field inline">
                                                                        <input type="text"  name="appofficethree" id="appofficethree" value="{{$irc_details->appoffice}}" placeholder=""  class="inline-box" style="width: 250px;" />
                                                                        
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>
                                                                <div class="col s12">
                                                                    
                                                                    <span>
                                                                        &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; <span>H/P</span>
                                                                    </span> 
                                                                    
                                                                    <div class="input-field inline">
                                                                        <input type="text"  name="apphpthree" id="apphpthree" value="{{$irc_details->appmobile}}" placeholder="" class="inline-box" style="width: 250px;" />
                                                                        
                                                                    </div>
                                                                     <span>Fax</span>
                                                                     <div class="input-field inline">
                                                                        <input type="text"  name="appfaxthree" id="appfaxthree" value="{{$irc_details->appfax}}" placeholder="" class="inline-box" style="width: 250px;" />
                                                                        
                                                                    </div>
                                                                    <span>Email</span>
                                                                     <div class="input-field inline">
                                                                        <input type="email"  name="appemailthree" id="appemailthree" value="{{$irc_details->appemail}}" placeholder=""  class="inline-box"/>
                                                                        
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>

                                                                
                                                        </div>
                                                        <div id="resign_section" class="reasonsections  @if($section_type_val != 4) hide @endif "> 

                                                            <div class="row padding-left-20">
                                                                <div class="col s12">
                                                                    <label>
                                                                        <input type="checkbox" class="common-checkbox" @if($irc_details->nameofperson ==1) checked @endif name="personnameboxfour" id="personnameboxfour"  value="1"/>
                                                                        <span>BF Applicant’s Name is</span>
                                                                    </label> 
                                                                    <div class="input-field inline">
                                                                        <input type="text" id="person_namefour" name="person_namefour" readonly value="{{$irc_details->resignedmembername}}" class="inline-box" style="width: 560px;">
                                                                        
                                                                    </div>
                                                                </div>
                                                                <div class="col s12">
                                                                    <label>
                                                                        <input type="checkbox" class="common-checkbox" @if($irc_details->resignedonboxfour ==1) checked @endif name="resignedonboxfour" id="resignedonboxfour"  value="1"/>
                                                                        <span><span class="gender">{{$genderlable}}</span> {{$irc_details->resigntypefour}} on </span>
                                                                    </label> 
                                                                    <div class="input-field inline">
                                                                        <input type="text"  name="gradeweffour" id="gradeweffour" placeholder="grade w.e.f" value="{{$irc_details->gradewef}}" class="datepicker-custom"/>
                                                                        
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>  
                                                            

                                                            <div class="row padding-left-20">
                                                                <div class="col s12 m12 ">
                                                                    <p>
                                                                        <label>
                                                                        <input type="checkbox" class="common-checkbox" @if($irc_details->messengerbox ==1) checked @endif name="messengerboxfour" id="messengerboxfour"  value="1"/>
                                                                        <span><span class="gender">{{$genderlable}}</span> was a {{$irc_details->messengertype}} before RESIGNATION [Delete which is not applicable]</span>
                                                                        </label> 
                                                                    </p>    
                                                                </div>
                                                            
                                                            </div>  
                                                                

                                                        
                                                            <div class="row padding-left-20">
                                                                <div class="col s12 m12 ">
                                                                    
                                                                    <label>
                                                                    <input type="checkbox" class="common-checkbox" @if($irc_details->attachedbox ==1) checked @endif name="attachedboxfour" id="attachedboxfour"  value="1"/>
                                                                    <span>Attached is <span class="genderone">{{$genderlableone}}</span> {{$irc_details->attachfourtype}} Letter (compulsory)</span>
                                                                    </label> 
                                                                        
                                                                    <div class="input-field inline hide">
                                                                         <input type="text" id="attachedfour" value="{{$irc_details->attached_desc}}" name="attachedfour" class="">
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>  
                                                                
                                                            <div class="row padding-left-20">
                                                                <div class="col s12 m12 ">
                                                                    
                                                                    <label>
                                                                    <input type="checkbox" class="common-checkbox" @if($irc_details->jobtakenbox ==1) checked @endif name="jobtakenboxfour" id="jobtakenboxfour"  value="1"/>
                                                                    <span>Member’s job functions have been taken over by</span>
                                                                    </label> 
                                                                    
                                                                    <div class="input-field inline">
                                                                         <input type="text" name="jobtakenbyfour" value="{{$irc_details->jobtakenby}}" id="jobtakenbyfour" placeholder=""  class=""/>
                                                                    </div>
                                                                    and
                                                                </div>
                                                                
                                                            </div>
                                                            <div class="row padding-left-20">
                                                                <div class="col s12 m12 ">
                                                                
                                                                    <label>
                                                                    <input type="checkbox" class="common-checkbox" @if($irc_details->posfilledbybox ==1) checked @endif name="posfilledbyboxfour" id="posfilledbyboxfour"  value="1"/>
                                                                    <span>Member’s position has not been filled up by another {{$filledbytype}} [Please specify others in detail]</span>
                                                                    </label> 

                                                                    <div class="input-field inline">
                                                                        <input type="text"  name="posfilledbyfour" id="posfilledbyfour" value="{{$irc_details->posfilledby}}" placeholder=""  class=""/>
                                                                    </div>  
                                                                </div>
                                                                
                                                            </div>  
                                                            <div class="row padding-left-20">
                                                                <div class="col s12 m12 ">
                                                                    
                                                                    <label>
                                                                    <input type="checkbox" class="common-checkbox" @if($irc_details->replacestaffbox ==1) checked @endif name="replacestaffboxfour" id="replacestaffboxfour"  value="1"/>
                                                                    <span>REPLACEMENT Staff Grade is {{$irc_details->replacestafftype}} [Please specify others in detail] </span>
                                                                    </label> 
                                                                        
                                                                    <div class="input-field inline">                        
                                                                        <input type="text"  name="replacestafffour" value="{{$irc_details->replacestaff}}" id="replacestafffour" placeholder=""  class=""/>
                                                                    </div>
                                                                </div>
                                                            </div>  
                                                            <div class="row padding-left-20">
                                                                <div class="col s12">
                                                                    <label>
                                                                        <input type="checkbox" class="common-checkbox" @if($irc_details->appcontactbox ==1) checked @endif name="appcontactboxfour" id="appcontactboxfour"  value="1"/>
                                                                        <span>Applicant Contact</span>
                                                                    </label> 
                                                                    <div class="input-field inline">
                                                                        <input type="text"  name="appcontactfour" value="{{$irc_details->appcontact}}" id="appcontactfour" placeholder=""  class=""/>
                                                                        
                                                                    </div>
                                                                     <span>Office</span>
                                                                     <div class="input-field inline">
                                                                        <input type="text"  name="appofficefour" value="{{$irc_details->appoffice}}" id="appofficefour" placeholder=""  class=""/>
                                                                        
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>
                                                                <div class="col s12">
                                                                    
                                                                    <span>
                                                                        &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; <span>H/P</span>
                                                                    </span> 
                                                                    
                                                                    <div class="input-field inline">
                                                                        <input type="text"  name="apphpfour" id="apphpfour" value="{{$irc_details->appmobile}}" placeholder=""  class=""/>
                                                                        
                                                                    </div>
                                                                     <span>Fax</span>
                                                                     <div class="input-field inline">
                                                                        <input type="text"  name="appfaxfour" id="appfaxfour" value="{{$irc_details->appfax}}" placeholder=""  class=""/>
                                                                        
                                                                    </div>
                                                                    <span>Email</span>
                                                                     <div class="input-field inline">
                                                                        <input type="text"  name="appemailfour" id="appemailfour" value="{{$irc_details->appemail}}" placeholder=""  class=""/>
                                                                        
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>  
                                                        </div>
                                                        <div id="expelled_section" class="reasonsections  @if($section_type_val != 5) hide @endif "> 

                                                            <div class="row padding-left-20">
                                                                <div class="col s12 m12">
                                                                
                                                                    <label>
                                                                    <input type="checkbox" class="common-checkbox" @if($irc_details->expelledboxfive ==1) checked @endif name="expelledboxfive" id="expelledboxfive"  value="1"/>
                                                                    <span>Member was {{$irc_details->expelledtypefive}} on
                                                                    </label> 

                                                                    <div class="input-field inline">                        
                                                                        <input type="text"  name="gradeweffive" id="gradewef" value="{{$irc_details->gradewef}}" placeholder="grade w.e.f"  class="datepicker-custom"/>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                                
                                                            <div class="row padding-left-20">
                                                                <div class="col s12 m12 ">
                                                                    <p>
                                                                        <label>
                                                                        <input type="checkbox" class="common-checkbox" @if($irc_details->samejobboxfive ==1) checked @endif name="samejobboxfive" id="samejobboxfive"  value="1"/>
                                                                        <span>Member’s is still performing the same job functions</span>
                                                                        </label> 
                                                                    </p>    

                                                                </div>
                                                                
                                                            </div>
                                                            <div class="row padding-left-20">
                                                                <div class="col s12 m12 ">
                                                                    <p>
                                                                        <label>
                                                                        <input type="checkbox" class="common-checkbox" @if($irc_details->samebranchbox ==1) checked @endif name="samebranchboxfive" id="samebranchboxfive"  value="1"/>
                                                                        <span>Member is still in the same {{$irc_details->samebranchtype}}</span>
                                                                        </label> 
                                                                    </p>    
                                                                </div>
                                                                
                                                            </div>  
                                                            <div class="row padding-left-20">
                                                                <div class="col s12 m12 ">
                                                                    <p>
                                                                        <label>
                                                                        <input type="checkbox" class="common-checkbox" @if($irc_details->memberstoppedboxfive ==1) checked @endif name="memberstoppedboxfive" id="memberstoppedboxfive"  value="1"/>
                                                                        <span>Member {{$irc_details->stoppedtypefive}} the Check-Off [Delete whichever is applicable] </span>
                                                                        </label> 
                                                                    </p>    
                                                                </div>
                                                                
                                                            </div>  
                                                            
                                                        </div>
                                                    </div>
                                                  
                                                    <div class="col s12 m12 branchconfirmarea">
                                                        </br>
                                                        <h6>BRANCH COMMITEE VERIFICATION</h6>
                                                         <div class="row padding-left-20">
                                                            <div class="col s12 m12" style="line-height: 5px;">
                                                                <label>
                                                                
                                                                    <input type="checkbox"  @if($irc_details->committieverificationboxone ==1) checked @endif name="committieverificationboxone" id="committieverificationboxone" class="common-checkbox"  value="1"/>
                                                                    <span>I</span>
                                                                </label> 
                                                                <div class="input-field inline">    
                                                                    <input type="text" id="committiename" name="committiename" value="{{$irc_details->committiename}}" placeholder="" > 
                                                                </div>
                                                                Branch Committee of NUBE
                                                                <div class="input-field inline">    
                                                                    <input type="text" id="committieverifyname" name="committieverifyname" placeholder="" value="{{$irc_details->committieverifyname}}">    
                                                                </div>
                                                                Branch have verified the above and <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; confirm that the declaration 
                        by the IRC is correct.
                                                            </div>
                                                            <div class="col s12 m12 " style="margin-top: 20px;">
                                                                <p>
                                                                    <label>
                                                                    <input type="checkbox" class="common-checkbox" @if($irc_details->committieverificationboxtwo ==1) checked @endif name="committieverificationboxtwo" id="committieverificationboxtwo"  value="1"/>
                                                                    <span>Staff who has taken over the job functions under CODE 0{{$section_type_val}} is a NUBE Member. </span>
                                                                    </label> 
                                                                </p>    
                                                            </div>
                                                            <div class="col s12 m12 ">
                                                                    
                                                                    <label>
                                                                        <input type="checkbox" class="common-checkbox" @if($irc_details->committieverificationboxthree ==1) checked @endif name="committieverificationboxthree" id="committieverificationboxthree"  value="1" />
                                                                        <span>Staff who is under CODE 0{{$irc_details->committiecode}} is still performing the same job function.  The additional information for this staff is as follows:  </span>
                                                                        
                                                                    </label> 
                                                                    <br>
                                                                    <div class="input-field inline" style="margin: 0 0 0 27px !important;"> 
                                                                        <input type="text" name="committieremark" id="committieremark" value="{{$irc_details->committieremark}}" style="width: 650px;">
                                                                    </div>
                                                                    <span>(Remark)</span>
                                                                    
                                                                
                                                            </div>
                                                       </div>
                                                        <div class="row">
                                                           
                                                            <div class="col s12 m12">
                                                                <br>
                                                                <div class="row">
                                                                    <div class="col s12 m3 ">
                                                                        <br>
                                                                        <p >
                                                                        
                                                                        <span>   Branch Commitee [Name in full]</span>
                                                                          
                                                                        </p >
                                                                     </div>
                                                                     <div class="col s12 m4 ">
                                                                        <input id="irc_branch_committie_name" type="text" value="{{ !empty($irc_data) ? $irc_data->branchcommitteeName : '' }}" class="validate">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col s12 m12">
                                                                <div class="row">
                                                                    <div class="col s12 m3 ">
                                                                        <p >
                                                                           <br>
                                                                            <span>Zone</span>
                                                                           
                                                                        </p>    
                                                                    </div>
                                                                    <div class="col s12 m4 ">
                                                                           <input id="irc_branch_committie_zone" type="text" value="{{ !empty($irc_data) ? $irc_data->branchcommitteeZone : '' }}"  class="validate">
                                                                    </div>
                                                                    <div class="col s12 m3 ">
                                                                    <!--<label>Date</label> -->
                                                                            <div class="input-field inline"> 
                                                                              <input type="text" class="" palceholder="Date" name="date" value="{{ !empty($irc_data) && $irc_data->branchcommitteedate!=" "  ? date('d/m/Y',strtotime($irc_data->branchcommitteedate)) : '' }}">
                                                                            </div>
                                                                            (Date)
                                                                    </div>
                                                                </div>  
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
                                            <div class="col-sm-8 col-sm-offset-1 @if($data['view_status']==1) readonlyarea @endif" >
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
                                                        <label for="name" class="force-active">Member Name as per NRIC *</label>
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
                                                        <input id="email" name="email" type="text" value="{{$values->email}}" data-error=".errorTxt25">
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
                                                    
                                                    <div class="col s12 m6">
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
                                                    <div class="clearfix" style="clear:both"></div>
                                                    <div class="col s12 m6">
                                                    	<div class="row">
                                                            @php
                                                               $basicsalary = CommonHelper::getBasicSalary($values->mid,date('Y-m-d',strtotime($values->last_update))); 
                                                            @endphp
	                                                    	<div class="input-field col s12 m3 {{ $hidemember }}">
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
                                                            <span style="color: rgba(255, 255, 255, 0.901961);" class="gradient-45deg-indigo-light-blue padding-2 medium-small">{{ $status_val }}</span>
                                                        </p>
                                                        @endif
                                                    </div>
                                                    @php } @endphp

                                                </div>
                                                <div class="row">
                                                    <div class="col s12 m4 hide">
                                                         <div id="">
                                                            <div class=" ">
                                                                <br>
                                                                <input type="file" name="attachmentone" multiple="" class="" accept="" style="width: 500px;">
                                                            </div>
                                                            <div class="file-path-wrapper hide">
                                                                <input class="file-path validate" type="text">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col s12 m6 hide">
                                                        <input type="text" id="attachedone" name="attachedone" class="inline-box" style="width: 500px;" >
                                                    </div>
                                                    @php
                                                        $getfiles = CommonHelper::getMemberAttachaments($member_autoid);
                                                        //dd($getfiles);
                                                    @endphp
                                                    <div class="col s12 m6">
                                                         <table>
                                                            <thead>
                                                                <tr>
                                                                    <th>File</th>
                                                                    @if($check_unionbranch==1)
                                                                    <th>Action</th>
                                                                    @endif
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($getfiles as $file)
                                                                <tr>
                                                                    <td>{{$file->file_name}} &nbsp;&nbsp; <a href="{{ asset('storage/app/member/'.$file->file_name) }}" class="btn btn-sm download-link" target="_blank">VIEW ATTACHMENT</a></td>
                                                                    @if($check_unionbranch==1)
                                                                    <td>Delete</td>
                                                                    @endif
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
                                            <div class="row "">
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
                                                    @if($showfee==1)
													<li>
													<div class="collapsible-header gradient-45deg-indigo-purple white-text"><i class="material-icons">blur_circular</i> {{__('Fee Details') }}</div>
                                                        <div class="collapsible-body ">
                                                              
															<div class="row @if($data['view_status']==1) readonlyarea @endif">
                                                                <div class="col s12 m6">
                                                                    <label for="new_fee_id">Fee name* </label>
                                                                    <select name="new_fee_id" id="new_fee_id" class="error browser-default selectpicker">
                                                                        <option value="">Select</option>
                                                                        @foreach($data['fee_list'] as $key=>$value)
                                                                        <option data-feename="{{$value->fee_name}}" data-feeamount="{{$value->fee_amount}}" value="{{$value->id}}">{{$value->fee_name}} ({{$value->fee_shortcode}})</option>
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
                                                            <div class="row @if($data['view_status']==1) readonlyarea @endif">
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
                                                                                    @if($user_role!='member')
                                                                                    <a class="btn-floating waves-effect waves-light cyan edit_fee_row hide" href="#modal_fee" data-id="{{$sl}}"><i class="material-icons left">edit</i></a>
                                                                                    <a class="btn-floating waves-effect waves-light amber darken-4 delete_fee_db" data-id="{{$sl}}" data-autoid="{{$value->id}}" onclick="if (confirm('Are you sure you want to delete?')) return true; else return false;"><i class="material-icons left">delete</i></a>
                                                                                    @endif
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
                                                     @endif
                                                    <li>
                                                    <div class="collapsible-header gradient-45deg-indigo-purple white-text"><i class="material-icons">blur_circular</i> {{__('Fund Details') }}</div>
                                                        <div class="collapsible-body ">
															</br>
                                                            <div class="row @if($data['view_status']==1) readonlyarea @endif">
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
																				<input id="email_inline" type="text" class="validate" value="{{ $total_ins_count==0 ? '' : $total_ins_count }}" readonly style="height:2rem;">
																		    </div>
																		</div>
                                                                        <div class="col s12">
                                                                            Ins &nbsp;&nbsp; :
                                                                            <div class="input-field inline" style="margin:0;">
                                                                                <input id="email_inline" type="text" class="validate" value="{{ $total_ins_count==0 ? '' : $total_ins_count }}" readonly style="height:2rem;">
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
																			UC &nbsp;&nbsp; :
																		    <div class="input-field inline" style="margin:0;">
																				<input id="email_inline" value="@if(!empty($lastmonthendrecord)){{$lastmonthendrecord->TOTALMONTHSDUE}}@endif" type="text" class="validate" readonly style="height:2rem;">
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
                                                            <div id="app_reason" class="col s12 m4 @if($values->approval_status != 'Rejected') hide @endif">
                                                                <div class="">
                                                                    <input name="approval_reason" placeholder="Reason" id="approval_reason" type="text" value="{{ $values->approval_reason }}" class="validate" style="">(Remarks)
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
                                        </fieldset>
                                        @if($irc_status==1 || $new_resign_status==1)
                                        <h3>Resignation</h3> 
											@php 
												
												$resignedrow = CommonHelper::getResignDataByMember($values->mid); 
												$reasondata = CommonHelper::getResignData(); 
												$lastmonthendrow = CommonHelper::getlastMonthEndByMember($values->mid);
                                               // dd($values->mid); 
												$lastpaid = ''; $totalmonthspaid = ''; $bfcontribuion = ''; $insamount = ''; $service_year = ''; $unioncontribution = ''; $accbenefit = ''; 
												$totamount = 0; 
                                                $monthly_bf = 0; 
                                                $totalmonthsmay = 0;
                                                $benifit_year = 0;

                                                $maymonthendrow = CommonHelper::getlastMonthEndByMemberMay($values->mid);
                                               // dd($maymonthendrow);
                                                if(!empty($maymonthendrow)){ 
                                                    $totalmonthsmay = $maymonthendrow->TOTALMONTHSPAID; 
                                                    $benifit_year = (int) ($totalmonthsmay/12);
                                                }


												if(!empty($lastmonthendrow)){ 
													$lastpaid = date('M/Y',strtotime($lastmonthendrow->LASTPAYMENTDATE)); 
													$totalmonthspaid = $lastmonthendrow->TOTALMONTHSPAID; 
													$bfcontribuion = $lastmonthendrow->ACCBF; 
													$insamount = $lastmonthendrow->ACCINSURANCE; 
													//$service_year = CommonHelper::calculate_age($values->doj); 
                                                    $service_year = 0; 
													$unioncontribution = $lastmonthendrow->ACCINSURANCE; 
													$accbenefit = $lastmonthendrow->BF_AMOUNT; 
													//$totamount = number_format($accbenefit+$bfcontribuion+$insamount,2,".",",");
                                                    $totamount = 0;
                                                    $monthly_bf = $lastmonthendrow->BF_AMOUNT; 
                                                    $insamount = 0; 
                                                    $bfcontribuion = 0;
												} 
												$resignstatus = 0; $resign_date = ''; $relation_code = ''; $pay_mode = ''; $chequeno = ''; $voucher_date = ''; $chequedate = ''; $chequedate = ''; 
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
                                                        <input type="text" class="datepicker" id="resign_date" data-error=".errorTxt500" name="resign_date" value="{{$resign_date}}" autocomplete="false">
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
                                                                <input type="text" id="benefit_year" name="benefit_year" value="{{ $benifit_year }}" readonly>
                                                                <label for="benefit_year" class="force-active">Benefit Year</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="input-field col s6">
                                                        <div class="row">
                                                            <div class="input-field col s12">
                                                                <input type="text" id="bf_monthly" class="hide" name="bf_monthly" value="{{$monthly_bf}}"/>
                                                                 <input type="text" class="hide" id="may_contributed_months" name="may_contributed_months" value="{{$totalmonthsmay}}">
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
                                                                    <option @if($pay_mode=='CHEQUE') selected @endif value="CHEQUE"> CHEQUE</option>
                                                                    <option @if($pay_mode=='ONLINE PAY') selected @endif value="ONLINE PAY"> ONLINE PAY</option>
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

    @if($data['view_status']==1)
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
        }else if(type=='Completed'){
            $("#app_reason").addClass('hide');
            $("#activate_account").attr('required',true);
        }else{
            $("#activate_account").attr('required',false);
            $("#app_reason").addClass('hide');
        }
       
    }
    function HideLevy(levytitle){
        if(levytitle=='NO'){
            $("#levydiv").addClass('hide');
        }else{
            $("#levydiv").removeClass('hide');
        }
    }
    function HideTDF(tdftitle){
        if(tdftitle=='NO'){
            $("#tdfdiv").addClass('hide');
        }else{
            $("#tdfdiv").removeClass('hide');
        }
    }
    function ShowRemarks(designationid){
        if(designationid==12){
            $("#remarksdiv").removeClass('hide');
        }else{
            $("#remarksdiv").addClass('hide');
        }
    }
</script>
@include('membership.member_common_script') 
@endsection