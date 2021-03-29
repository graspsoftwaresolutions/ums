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
</style>
@endsection @section('main-content')
<div class="row">
    <!--<div style="height:150px !important" class="content-wrapper-before gradient-45deg-indigo-purple"></div>-->
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
                                    <h5 class="breadcrumbs-title mt-0 mb-0">{{__('Upload TDF') }}</h5>
                                    <ol class="breadcrumbs mb-0">
                                        <ol class="breadcrumbs mb-0">
                                            <li class="breadcrumb-item"><a href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard') }}</a>
                                            </li>
                                            <li class="breadcrumb-item active">{{__('Upload TDF') }}
                                            </li>
                                        </ol>
                                </div>
                                <div class="col s2 m6 l6 ">
                                    
                                    <a class="btn waves-effect waves-light cyan breadcrumbs-btn right " href="{{ asset('storage/app/subscription/tdf.xlsx') }}">{{__('Download Sample')}}</a>
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
              
					@if($user_role!='company')
                    <div class="card">
                        

                        <div class="card-content">
                            <div class="row">
                                <div class="col s12 m12">

                                    <div class="row">
                                        <form class="formValidate" id="subscribe_formValidate" method="post" action="{{ url(app()->getLocale().'/tdf_update') }}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">

                                                <div class="input-field col m3 s12">
                                                    <label for="doe">{{__('Upload Month') }}*</label>
                                                    <input type="text" name="entry_date" id="entry_date" value="{{ date('M/Y') }}" class="datepicker-custom" />
                                                </div>

                                                <div class="col s4">
                                                    <label for="sub_company">{{__('Company') }}*</label>
                                                    <select name="sub_company" id="sub_company" required="" class="error browser-default selectpicker" data-error=".errorTxt6">
                                                        <option value="" selected>{{__('Choose Company') }}</option>
                                                        @foreach($companylist as $value)
                                                        <option data-companyname="{{$value->company_name}}" value="{{$value->id}}">{{$value->company_name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="errorTxt6"></div>
                                                </div>

                                                <div id="file-upload-div" class="input-field  file-field col m2 s12">
                                                    <div class="btn ">
                                                        <span>File</span>
                                                        <input type="file" name="file" class="form-control btn" accept=".xls,.xlsx">
                                                    </div>
                                                    <div class="file-path-wrapper ">
                                                        <input class="file-path validate" type="text">
                                                    </div>
                                                </div>
                                                
                                                <div class="col m3 s12 " style="padding-top:5px;">
                                                    </br>
                                                    <button id="submit-upload" class="mb-6 btn waves-effect purple lightrn-1 " type="submit">{{__('Submit') }}</button>

                                                </div>

                                            </div>
                                            
                                        </form>

                                    </div>
                                </div>

                            </div>
                        </div>
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

    <script>
        $("#tdfmembership_sidebar_a_id").addClass('active');
        $(document).ready(function() {
            // the "href" attribute of the modal trigger must specify the modal ID that wants to be triggered
            $('.modal').modal();
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
                // sub_company: {
                //     required: true,
                // },
                /* file:{
                	required: true,
                }, */
            },
            //For custom messages
            messages: {
                entry_date: {
                    required: "Please choose date",

                },
                // sub_company: {
                //     required: "Please choose Bank",

                // },
                /* file:{
                	required: 'required',
                }, */
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

        $(document).on('change', '#entry_date,#sub_company', function() {
            
        });

        $(document).on('submit', 'form#subscribe_formValidate', function() {
             return true;
        });

        $(document).on('click', '#submit-download', function() {
            //alert('hi');
            $('#subscribe_formValidate').trigger('submit');
        });
     
        // $("#subscriptions_sidebars_id").addClass('active');
        // $("#subupsalary_sidebar_li_id").addClass('active');
        // $("#subupsalary_sidebar_a_id").addClass('active');

        $(document).on('click', '#file', function() {
           

        });

        function printDiv() {

            var divToPrint = document.getElementById('DivIdToPrint');
            console.log(divToPrint.innerHTML);

            var newWin = window.open('', 'Print-Window');

            newWin.document.open();

            newWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');

            newWin.document.close();

            setTimeout(function() {
                newWin.close();
            }, 10);

        }
    </script>
    @endsection