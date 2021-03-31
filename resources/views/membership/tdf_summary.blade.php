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
                                    <h5 class="breadcrumbs-title mt-0 mb-0">{{__('TDF Summary') }}</h5>
                                    
                                </div>
                                <div class="col s2 m6 l6 ">
                                 <a class="mb6 btn btn-sm waves-light orange lightrn-1 right" href="{{ route('tdf.members', [app()->getLocale()]) }}?date={{ strtotime($data['tdfdate']) }}">View Members</a>
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
                    $userid = Auth::user()->id; 
                    $get_roles = Auth::user()->roles; 
                    $user_role = $get_roles[0]->slug; 
                    $year = date('Y',strtotime($data['tdfdate']));
                    //dd($year);
                @endphp 
              
					@if($user_role!='company')
                    <div class="card">

                        <div class="card-content">
                            <div class="row">
                                <div class="col s12 m12">

                                    <div class="row">
                                        <form class="formValidate" id="subscribe_formValidate" method="post" action="{{ url(app()->getLocale().'/tdf/summary') }}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">

                                                <div class="col m3 s12">
                                                   <label for="upload_year">{{__('Upload Year') }}*</label>
                                                   <select name="upload_year" id="upload_year" required="" class="error browser-default selectpicker" data-error=".errorTxt7">
                                                        <option value="">{{__('Choose Year') }}</option>
                                                        @for($y=2008;$y<=2016;$y++)
                                                        <option value="{{ $y }}" @if($y==$year) selected="" @endif >{{ $y }}</option>
                                                        @endfor
                                                        
                                                    </select>
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
        <div class="row">
            <div id="monthly_status" class="col s12 active">
                <div class="">
                    <br> <a id="printbutton" href="#" style="margin-left: 50px;" class="export-button btn btn-sm right" onclick="window.print()"> Print</a>
                    <div class="clearfix"></div>
                  
                    <!--Approval Status-->
                    <div class="col s12 m12">
                        <div class="card subscriber-list-card animate fadeRight">
                            <div class="card-content" style="border-bottom: #2d22d6 solid 1px;">
                                <h4 class="card-title mb-0">Overall Summary
                                        <span class="right datamonth hide">[{{ date('M/Y',strtotime($data['tdfdate'])) }}]</span>
                                    </h4>
                            </div>
                            <table class="subscription-table responsive-table highlight">
                                <thead>
                                    <tr style="background: linear-gradient(45deg,#8e24aa,#ff6e40)!important;color:#fff;">
                                        <th>Sl No</th>
                                        <th>Description</th>
                                        <th>Count</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $summary_slno = 1;
                                      
                                        $matched_members_count = CommonHelper::TDFMatchedMembersData($data['tdfdate'],1,1); 
                                        $matched_members_amount = CommonHelper::TDFMatchedMembersData($data['tdfdate'],1,2); 

                                        $notmatched_members_count = CommonHelper::TDFMatchedMembersData($data['tdfdate'],0,1); 
                                        $notmatched_members_amount = CommonHelper::TDFMatchedMembersData($data['tdfdate'],0,2); 
                                      
                                    @endphp
                                    <tr class="monthly-approval-status " id="monthly_approval_status_1" data-href="{{ URL::to(app()->getLocale().'/tdf/members?&date='.strtotime($data['tdfdate'])) }}" style="cursor:pointer;">
                                        <td>{{ $summary_slno++ }}</td>
                                        <td>Total Uploaded Members(Members & Not Matched Members)</td>
                                        <td id="approval_status_count_1">{{ $data['members_sum']->count }}</td>
                                        <td id="approval_pending_amount_1">{{ number_format($data['members_sum']->amount,2,".",",") }}</td>
                                    </tr>
                                    <tr class="monthly-approval-status " id="monthly_approval_status_1" data-href="{{ URL::to(app()->getLocale().'/tdf-status?member_status=&date='.strtotime($data['tdfdate']).'&member_type=1') }}" style="cursor:pointer;">
                                        <td>{{ $summary_slno++ }}</td>
                                        <td>Matched Members</td>
                                        <td id="approval_status_count_1">{{ $matched_members_count }}</td>
                                        <td id="approval_pending_amount_1">{{ number_format($matched_members_amount,2,".",",") }}</td>
                                    </tr>
                                    <tr class="monthly-approval-status " id="monthly_approval_status_1" data-href="{{ URL::to(app()->getLocale().'/tdf-status?member_status=&date='.strtotime($data['tdfdate']).'&member_type=0') }}" style="cursor:pointer;background: #f9f2f2;">
                                        <td>{{ $summary_slno++ }}</td>
                                        <td>Not Matched Members</td>
                                        <td id="approval_status_count_1">{{ $notmatched_members_count }}</td>
                                        <td id="approval_pending_amount_1">{{ number_format($notmatched_members_amount,2,".",",") }}</td>
                                    </tr>
                                  
                                </tbody>
                               
                            </table>
                        </div>
                    </div>
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
        $("#tdf_sidebars_id").addClass('active');
        $("#tdfupload_sidebar_li_id").addClass('active');
        $("#tdfupload_sidebar_a_id").addClass('active');
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
                file:{
                	required: true,
                },
            },
            //For custom messages
            messages: {
                entry_date: {
                    required: "Please choose date",

                },
                // sub_company: {
                //     required: "Please choose Bank",

                // },
                file:{
                	required: 'required',
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
        $(".monthly-sub-status,.monthly-approval-status").click(function() {
            //console.log($(this).data("href"));
            if ($(this).attr("data-href") != "") {
                window.open($(this).attr("data-href"), '_blank');
                //win = window.location.replace($(this).attr("data-href"));
            }
        });
    </script>
    @endsection