@extends('layouts.admin') @section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}"> @endsection @section('headSecondSection')
<link href="{{ asset('public/assets/css/jquery-ui-month.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/css/MonthPicker.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/css/sweetalert.css') }}" rel="stylesheet" type="text/css" />
<style>
    table.highlight > tbody > tr {
        -webkit-transition: background-color .25s ease;
        -moz-transition: background-color .25s ease;
        -o-transition: background-color .25s ease;
        transition: background-color .25s ease;
    }
    
    table.highlight > tbody > tr:hover {
        background-color: rgba(242, 242, 242, .5);
    }
    
    .monthly-sub-status:hover,
    .monthly-approval-status:hover,
    .monthly-company-sub-status:hover,
    .monthly-company-approval-status:hover {
        background-color: #eeeeee !important;
        cursor: pointer;
    }
    
    .card .card-content {
        padding: 10px;
        border-radius: 0 0 2px 2px;
    }
    
    .file-path-wrapper {
        //display:none;
    }
    
    .file-field .btn,
    .file-field .btn-large,
    .file-field .btn-small {
        margin-top: 10px;
        line-height: 2.4rem;
        float: left;
        height: 2.4rem;
    }
    
    .btn-sm {
        line-height: 36px;
        display: inline-block;
        height: 35px;
        padding: 0 7px;
        vertical-align: middle;
        text-transform: uppercase;
        border: none;
        border-radius: 4px;
        -webkit-tap-highlight-color: transparent;
    }
    
    @media print {
        #printbutton {
            display: none !important;
        }
        .sidenav-main,
        .nav-wrapper {
            display: none !important;
        }
        .gradient-45deg-indigo-purple {
            display: none !important;
        }
        #filterarea {
            display: none !important;
        }
        #subsfilter {
            display: none !important;
        }
        #tabdiv {
            display: none !important;
        }
        #monthly_status {
            display: block !important;
        }
        td,
        th {
            display: table-cell;
            padding: 10px 5px;
            text-align: left;
            vertical-align: middle;
            border-radius: 2px;
        }
    }
    
    .input-field.col label {
        left: 1rem;
    }
    
    input:not([type]),
    input[type=text]:not(.browser-default),
    input[type=password]:not(.browser-default),
    input[type=email]:not(.browser-default),
    input[type=url]:not(.browser-default),
    input[type=time]:not(.browser-default),
    input[type=date]:not(.browser-default),
    input[type=datetime]:not(.browser-default),
    input[type=datetime-local]:not(.browser-default),
    input[type=tel]:not(.browser-default),
    input[type=number]:not(.browser-default),
    input[type=search]:not(.browser-default),
    textarea.materialize-textarea {
        font-size: 1rem;
        -webkit-box-sizing: content-box;
        -moz-box-sizing: content-box;
        box-sizing: content-box;
        width: 100%;
        height: 2.5rem;
        margin-top: 10px;
    }
    #main.main-full {
        height: 750px;
        overflow: auto;
    }
    
    .footer {
       position: fixed;
       margin-top:50px;
       left: 0;
       bottom: 0;
       width: 100%;
       height:auto;
       background-color: red;
       color: white;
       text-align: center;
       z-index:999;
    } 
    .sidenav-main{
        z-index:9999;
    }

</style>
<style type="text/css">
    .autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; cursor:pointer; }
    .autocomplete-suggestion { padding: 8px 5px; white-space: nowrap; overflow: hidden; }
    .autocomplete-selected { background: #F0F0F0; }
    .autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
    .autocomplete-group { padding: 8px 5px; }
    .autocomplete-group strong { display: block; border-bottom: 1px solid #000; }
    #transfer_member{
        color:#fff;
    }
</style>
@endsection @section('main-content')
<div class="row">
    <div style="height:150px !important" class="content-wrapper-before gradient-45deg-indigo-purple"></div>
    <div id="filterarea" class="col s12">
        <div class="container">
            <div class="section section-data-tables">
                <!-- BEGIN: Page Main-->
                <div class="row">
                    <div class="breadcrumbs-dark" id="breadcrumbs-wrapper">
                        <!-- Search for small screen-->
                        <div class="container">
                            <div class="row">
                                <div class="col s10 m6 l6">
                                    <h5 class="breadcrumbs-title mt-0 mb-0">{{__('Subscription List') }}</h5>
                                    <ol class="breadcrumbs mb-0">
                                        <ol class="breadcrumbs mb-0">
                                            <li class="breadcrumb-item"><a href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard') }}</a>
                                            </li>
                                            <li class="breadcrumb-item active">{{__('Subscription') }}
                                            </li>
                                        </ol>
                                </div>
                                <div class="col s2 m6 l6 ">
                                    
                                    <a class="btn waves-light cyan breadcrumbs-btn right " href="{{ route('subscription.download', app()->getLocale())  }}">{{__('Download Sample')}}</a>
                                </div>
                            </div>
                            @include('includes.messages')
                        </div>
                    </div>
                </div>
                <!-- END: Page Main-->
                @include('layouts.right-sidebar')
            </div>
        </div>
    </div>
    <div id="subsfilter" class="row">
        <div class="col s12">
            <div class="container">
                @php 
                 $auth_user = Auth::user(); $companylist = []; $companyid = ''; if(!empty($auth_user)){ $userid = Auth::user()->id; $get_roles = Auth::user()->roles; $user_role = $get_roles[0]->slug; if($user_role =='union'){ 
                 $companylist = CommonHelper::getHeadCompanyListAll(); 
                } else if($user_role =='union-branch'){ $unionbranchid = CommonHelper::getUnionBranchID($userid); $companylist = CommonHelper::getUnionCompanyList($unionbranchid); } else if($user_role =='company'){ $companyid = CommonHelper::getCompanyID($userid); $companylist = CommonHelper::getCompanyList($companyid); } else if($user_role =='company-branch'){ $companyid = CommonHelper::getCompanyID($userid); $companylist = CommonHelper::getCompanyList($companyid); } $company_count = count($companylist); }
                @endphp 
                @if($user_role=='union')
                <!--Basic Form-->

                <!-- jQuery Plugin Initialization -->
                <div class="row">
                	<br>
					<div class="col s4 hide">
					
						<div class="card" style="padding:10px;">
							<header class="kanban-board-header blue" style="padding:10px;color: #fff;"><div class="kanban-title-board line-ellipsis" contenteditable="true">Members</div><div class="dropdown"><a class="dropdown-trigger" href="#" data-target="1"> </a></div></header>
							<main class="kanban-drag">

								<br>
									Subscription Additional entry
								<br>
								<br>
							</main>
						</div>
					</div>
                    <div class="col s12">
                        <div id="basic-form" class="card card card-default scrollspy">
                            <div class="card-content">

                                <div class="row">
                                <div class="col s12 m12">

                                    <div class="row">
                                        <form class="formValidate" id="subscribe_formValidate" method="post" action="{{ url(app()->getLocale().'/subscribe_entry') }}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">

                                                <div class="input-field col m2 s12">
                                                    <label for="doe">{{__('Subscription Month') }}*</label>
                                                    <input type="text" name="entry_date" id="entry_date" value="{{ date('M/Y') }}" class="datepicker-custom" />
                                                </div>
                                                <div class="col m3 s12">
                                                    <label for="sub_company">{{__('Company') }}*</label>
                                                    <select name="sub_company" id="sub_company" class="error browser-default selectpicker" data-error=".errorTxt6">
                                                        <option value="" selected>{{__('Choose Company') }}</option>
                                                        @foreach($companylist as $value)
                                                        <option data-companyname="{{$value->company_name}}" value="{{$value->id}}">{{$value->company_name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="errorTxt6"></div>
                                                </div>

                                                <div class="input-field col s12 m2" id="memberarea">
                                                    <label for="member_search" class="force-active">{{__('Member Name')}}</label>
                                                    <input id="member_search" type="text" autocomplete="off" class="validate " data-error=".errorTxt16" value="" name="member_search">
                                                    <input id="member_code" type="text" autocomplete="off" class="validate hide" name="member_code" data-error=".errorTxt6" value="" readonly >
                                                    <div class="errorTxt16"></div>
                                                </div>

                                                <div class="input-field col s2">
                                                    <label for="sub_member_amount">Amount</label>
                                                    <input  placeholder="Amount" name="sub_member_amount" data-error=".errorTxt17" id="sub_member_amount" type="text" class="validate allow_decimal">
                                                    <div class="errorTxt17"></div>
                                                </div>
                                               
                                               
                                                <div class="col m3 s12 " style="padding-top:5px;">
                                                    </br>
                                                    <button id="submit-upload" class="mb-6 btn waves-light purple lightrn-1 form-download-btn" type="button">{{__('Submit') }}</button>

                                                </div>

                                            </div>
                                            
                                        </form>

                                    </div>
                                </div>

                            </div>

                            </div>
                        </div>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                    </div>
                    @endif
					
                </div>
            </div>
        </div>
       
    </div>

    @endsection @section('footerSection')
    <script src="{{ asset('public/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>

    <script src="{{ asset('public/assets/vendors/noUiSlider/nouislider.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/assets/js/materialize.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/scripts/form-elements.js') }}" type="text/javascript"></script>

    @endsection @section('footerSecondSection')
    <script src="{{ asset('public/assets/js/scripts/form-validation.js')}}" type="text/javascript"></script>

    <script src="{{ asset('public/assets/js/jquery-ui-month.min.js')}}"></script>
    <script src="{{ asset('public/js/MonthPicker.min.js')}}"></script>
    <script src="{{ asset('public/js/sweetalert.min.js')}}"></script>
    <script src="{{ asset('public/assets/js/jquery.autocomplete.min.js') }}" type="text/javascript"></script>


    <script>
        $(document).ready(function() {
            // the "href" attribute of the modal trigger must specify the modal ID that wants to be triggered
            $('.modal').modal();
        });

        $("#member_search").devbridgeAutocomplete({
            //lookup: countries,
            serviceUrl: "{{ URL::to('/get-ircauto-member-list') }}?serachkey="+ $("#member_search").val(),
            type:'GET',
            //callback just to show it's working
            onSelect: function (suggestion) {
                 $("#member_search").val(suggestion.value);
                 $("#member_code").val(suggestion.number);
            },
            showNoSuggestionNotice: true,
            noSuggestionNotice: 'Sorry, no matching results',
            onSearchComplete: function (query, suggestions) {
                if(!suggestions.length){
                    $("#member_code").val('');
                }
            }
        });
        $(document.body).on('click', '.autocomplete-no-suggestion' ,function(){
            $("#member_search").val('');
        });

        $(document).ready(function() {
            $('.datepicker-custom').MonthPicker({
                Button: false,
                changeYear: true,
                MonthFormat: 'M/yy',
                OnAfterChooseMonth: function() {
                    //getDataStatus();
                }
            });
            $('.ui-button').removeClass("ui-state-disabled");
            //$('.datepicker-custom').MonthPicker({ Button: false,dateFormat: 'M/yy' });

        });

        $("#subscribe_formValidate").validate({
            rules: {
                entry_date: {
                    required: true,
                },
                sub_company: {
                    required: true,
                },
                 member_search:{
                	required: true,
                },   
                sub_member_amount:{
                    required: true,
                }, 
            },
            //For custom messages
            messages: {
                entry_date: {
                    required: "Please choose date",

                },
                sub_company: {
                    required: "Please choose Bank",

                },
                 member_search:{
                	required: 'Please choose member',
                },  
                sub_member_amount:{
                    required: 'Please enter Amount',
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
            }
        });

        $(document).on('click', '#submit-upload', function() {
            $('#subscribe_formValidate').trigger('submit');

        });

       
        $(document).on('change', '#entry_date,#sub_company', function() {
            //getDataStatus();
        });

        $("#subscriptions_sidebars_id").addClass('active');
        $("#subadd_sidebar_li_id").addClass('active');
        $("#subadd_sidebar_a_id").addClass('active');

        $(document).on('input', '.allow_decimal', function(){
           var self = $(this);
           self.val(self.val().replace(/[^0-9\.]/g, ''));
           if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) 
           {
             evt.preventDefault();
           }
         });
    </script>
    @endsection