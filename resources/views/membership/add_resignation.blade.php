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
                                    <h4 class="card-title">Add Resignation</h4> 
									@include('includes.messages') 
									@php 

										$get_roles = Auth::user()->roles; 
										$user_role = $get_roles[0]->slug; 
										
									@endphp 
									
                                    <form class="formValidate" id="wizard2" method="post" enctype="multipart/form-data" action="{{ url(app()->getLocale().'/resignation_save') }}">
                                        @csrf 
										
                                        
                                            </br>
                                            <div class="col-sm-8 col-sm-offset-1" >
                                                <div class="row">
                                                    <div class="input-field col s12 m6" id="memberarea">
                                                        <label for="member_search" class="force-active">{{__('Member Number')}}</label>
                                                        <input id="member_search" required="" type="text" autocomplete="off" class="validate " data-error=".errorTxt6" value="" name="member_search">
                                                        <input id="member_code" type="text" autocomplete="off" class="validate hide" name="member_code" data-error=".errorTxt6" value="" readonly >
                                                        <div class="errorTxt6"></div>
                                                    </div>
                                                    <div class="col s12 m6">
                                                        <div class="row">
                                                            <div class="input-field col s12 m4">
                                                                <label>Member Title*</label>
                                                                <input id="member_title" placeholder="Member Title" readonly="" type="text" autocomplete="off" class="validate " data-error=".errorTxt7" value="" name="member_title">
                                                               
                                                                <div class="errorTxt7"></div>
                                                            </div>
                                                            <div class="input-field col s12 m8">
                                                                <label for="name" class="force-active">Member Name as per NRIC *</label>
                                                                <input id="name" name="name" readonly="" value="" type="text" data-error=".errorTxt30">
                                                                <div class="errorTxt30"></div>
                                                                   
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix" style="clear:both"></div>
                                                    <div class="col s12 m6 ">
                                                        <label>Bank Name*</label>
                                                        
                                                        </br>
                                                        <p style="margin-top:10px;font-weight:bold;">
                                                           <span id="companyname_label">
                                                               
                                                           </span>
                                                        </p>
                                                        
                                                    </div>
                                                    <div class="col s12 m6 ">
                                                        <label>Branch Name*</label>
                                                        
                                                        </br>
                                                        <p style="margin-top:10px;font-weight:bold;">
                                                            <span id="branchname_label">
                                                               
                                                            </span>
                                                        </p>
                                                        
                                                    </div>
                                                  
                                                    <div class="clearfix" style="clear:both"></div>
                                                    
                                                </div>
                                            </div>
                                        
                                       
                                        <div class="actions clearfix right">
                                            <button type="submit" name="finish" class="mb-6 btn waves-effect waves-light purple lightrn-1" id="finish">Send Irc Confirmation</button>
                                            
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
     $("#member_search").devbridgeAutocomplete({
            //lookup: countries,
            serviceUrl: "{{ URL::to('/get-all-member-list') }}?serachkey="+ $("#member_search").val(),
            type:'GET',
            //callback just to show it's working
            onSelect: function (suggestion) {
                if(suggestion.send_irc_request!=1){
                    $("#member_search").val(suggestion.member_code);
                    $("#member_code").val(suggestion.number);
                    $("#companyname_label").text(suggestion.company_name);
                    $("#branchname_label").text(suggestion.branch_name);
                    $("#member_title").val(suggestion.person_title);
                    $("#name").val(suggestion.name);
                }else{
                    alert('Already irc Confirmations sent to this member');
                    $("#member_search").val('');
                    $("#member_code").val('');
                }
                 
                 //getDataStatus();
            },
            showNoSuggestionNotice: true,
            noSuggestionNotice: 'Sorry, no matching results',
            onSearchComplete: function (query, suggestions) {
                if(!suggestions.length){
                    //$("#member_search_match").val('');
                    //$("#member_search_auto_id").val('');
                }
            }
        }); 
    $(document.body).on('click', '.autocomplete-no-suggestion' ,function(){
        $("#member_search").val('');
    });

    
</script>
@endsection