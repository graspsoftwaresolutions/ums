@extends('layouts.admin') @section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}"> @endsection @section('headSecondSection')
<link href="{{ asset('public/assets/css/jquery-ui-month.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/css/MonthPicker.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/css/sweetalert.css') }}" rel="stylesheet" type="text/css" />
<style>
    .autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; cursor:pointer; }
    .autocomplete-suggestion { padding: 8px 5px; white-space: nowrap; overflow: hidden; }
    .autocomplete-selected { background: #F0F0F0; }
    .autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
    .autocomplete-group { padding: 8px 5px; }
    .autocomplete-group strong { display: block; border-bottom: 1px solid #000; }

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


    td, th {
        display: table-cell;
        padding: 10px;
        text-align: left;
        vertical-align: middle;
        border-radius: 2px;
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
                                    <h5 class="breadcrumbs-title mt-0 mb-0">{{__('Salary List') }}</h5>
                                    <ol class="breadcrumbs mb-0">
                                        <ol class="breadcrumbs mb-0">
                                            <li class="breadcrumb-item"><a href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard') }}</a>
                                            </li>
                                            <li class="breadcrumb-item active">{{__('Salary List') }}
                                            </li>
                                        </ol>
                                </div>
                                <div class="col s2 m6 l6 ">
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: Page Main-->
                @include('layouts.right-sidebar')
            </div>
        </div>
    </div>
    <form class="formValidate" id="subscribe_formValidate" method="post" action="{{ url(app()->getLocale().'/upload_salary') }}" enctype="multipart/form-data">
    @csrf
    <div id="subsfilter" class="row">
        <div class="col s12">
           
            <div class="container">
                @php 
                 $auth_user = Auth::user(); $companylist = []; $companyid = ''; if(!empty($auth_user)){ $userid = Auth::user()->id; $get_roles = Auth::user()->roles; $user_role = $get_roles[0]->slug; if($user_role =='union'){ $companylist = CommonHelper::getCompanyListAll(); } else if($user_role =='union-branch'){ $unionbranchid = CommonHelper::getUnionBranchID($userid); $companylist = CommonHelper::getUnionCompanyList($unionbranchid); } else if($user_role =='company'){ $companyid = CommonHelper::getCompanyID($userid); $companylist = CommonHelper::getCompanyList($companyid); } else if($user_role =='company-branch'){ $companyid = CommonHelper::getCompanyID($userid); $companylist = CommonHelper::getCompanyList($companyid); } $company_count = count($companylist); }
                @endphp 
                @if($user_role=='company')
                
                    @endif
					@if($user_role!='company')
                    <div class="card">
                        <div class="card-title">
                            @if ($errors->any())
                            <div class="card-alert card gradient-45deg-red-pink">
                                <div class="card-content white-text">
                                    <p>
                                        <i class="material-icons">check</i> {{ __('Error') }} : {{ implode('', $errors->all(':message')) }}</p>
                                </div>
                                <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            @endif
                        </div>

                        <div class="card-content">
                            <div class="row">
                                <div class="col s12 m12">
                                    @include('includes.messages')
                                    <div class="row">
                                        
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
                                                <div class="col s12 m6 l3 ">
                                                    <label>{{__('Company Branch Name') }}</label>
                                                    <select name="branch_id" id="branch_id" class="error browser-default selectpicker" data-error=".errorTxt23" >
                                                        <option value="">{{__('Select Branch') }}</option>
                                                        
                                                    </select>
                                                    <div class="input-field">
                                                        <div class="errorTxt23"></div>
                                                    </div>
                                                </div>
                                                <div class="input-field col m2 s12 ">
                                                    <label for="member_search"><span class="bold" style="color: #000;">{{ __('NRIC / ') }}</span>{{__('Member Name / Member Code')}}</label>
                                                    <input id="member_search" type="text" class="validate " name="member_search" data-error=".errorTxt24">
                                                    <input id="member_auto_id" type="text" class="hide" class="validate " name="member_auto_id">
                                                    <div class="input-field">
                                                        <div class="errorTxt24"></div>
                                                    </div>
                                                </div>
                                               
                                                <div class="col m1 s12 " style="padding-top:5px;">
                                                    </br>
                                                    <button id="submit-upload" class="mb-6 btn waves-effect waves-light purple lightrn-1 form-download-btn" type="submit">{{__('Submit') }}</button>

                                                </div>

                                            </div>
                                            <div class="row hide">
                                                <div class="col s7">

                                                </div>
                                                <div class="col s4 ">

                                                    <button id="submit-download" class="waves-effect waves-light cyan btn btn-primary form-download-btn hide" type="button">{{__('Download Sample') }}</button>

                                                </div>
                                            </div>
                                        

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
					@endif
                </div>
            </div>
        </div>
       
        </form>
        <div class="row">
         
            <div id="monthly_status" class="col s12" style="padding: 020px;">
                <div id="DivIdToPrint" class="card subscriber-list-card animate fadeRight">
                    <div class="card-content" style="border-bottom: #2d22d6 solid 1px;">
                        <h4 class="card-title mb-0">{{__('Members List ') }} 
                        <!-- <a id="printbutton" href="#" style="margin-left: 50px;" class="export-button btn btn-sm" style="background:#ccc;" onClick="return printDiv()"> <i class="material-icons">print</i></a> -->
                        <span class="right datamonth">[{{ date('M/Y') }}]</span>
                    </h4>
                    </div>
                    <table class="subscription-table responsive-table highlight">
                        <thead>
                            <tr style="">
                                <th>Slno</th>
                                <th>Name</th>
                                <th>NRIC</th>
                                <th>M/NO</th>
                                <th>Basic Salary</th>
                                <th>Updated Salary</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="memberslist">
                            
                        </tbody>
                    </table>
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
     <script src="{{ asset('public/assets/js/jquery.autocomplete.min.js') }}" type="text/javascript"></script>
    @endsection @section('footerSecondSection')
    <script src="{{ asset('public/assets/js/scripts/form-validation.js')}}" type="text/javascript"></script>
   

    <script src="{{ asset('public/assets/js/jquery-ui-month.min.js')}}"></script>
    <script src="{{ asset('public/js/MonthPicker.min.js')}}"></script>
    <script src="{{ asset('public/js/sweetalert.min.js')}}"></script>

    <script>
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
                    getDataStatus();
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
                /* file:{
                	required: true,
                }, */
            },
            //For custom messages
            messages: {
                entry_date: {
                    required: "Please choose date",

                },
                sub_company: {
                    required: "Please choose Bank",

                },
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


        function getDataStatus() {
            var entry_date = $("#entry_date").val();
            var sub_company = $("#sub_company").val();
            $(".datamonth").text('[' + entry_date + ']');
            $("#entry_date_one").val(entry_date);
        }
        // $(document).on('change', '#entry_date,#sub_company', function() {
        //     $("#member_search,#member_auto_id,#inc_per").val('');
        //     $("#is_increment").prop('checked',false);
        //     $("#incrementperdiv").addClass('hide');
        //     getDataStatus();
        // });

        $(document).on('submit', 'form#subscribe_formValidate', function(e) {
           e.preventDefault();
           var sub_company = $("#sub_company").val();
           var entry_date = $("#entry_date").val();
           var member_auto_id = $("#member_auto_id").val();
           var branch_id = $("#branch_id").val();
           loader.showLoader();
           var url = "{{ url(app()->getLocale().'/get_memberssalaries') }}" + '?sub_company=' + sub_company + '&member_auto_id=' + member_auto_id+ '&entry_date=' + entry_date+ '&branch_id=' + branch_id;
            $.ajax({
                    url: url,
                    type: "GET",
                    success: function(result) {
                        loader.hideLoader();
                        console.log(result);
                        if (result.status == 1 ) {
                         
                            $("#memberslist").empty();
                            var slno = 1;
                            $.each(result.members, function(key, entry) {

                                 actions = ' <a class="btn btn-sm waves-effect gradient-45deg-green-teal " onclick="return showUpload('+entry.memberid+')" title="Approval" type="button" name="action"><i class="material-icons">edit</i></a>';
                                
                                 $("#memberslist").append('<tr style=""> <td width="15%"> '+slno+'</td><td>'+entry.name+'</td><td>'+entry.icno+'</td><td>'+entry.member_number+'</td><td>'+entry.basic_salary+'</td><td>'+entry.updated_salary+'</td><td>'+actions+'</td></tr>');

                                var baselink = base_url + '/{{ app()->getLocale() }}/';
                                //$("#monthly_company_sub_status_" + key).attr('data-href', baselink + "subscription-status?member_status=" + key + "&date=" + result.month_year_number + "&company_id=" + result.company_auto_id);
                               // $("#company_member_status_count_" + key).html(entry);
                               slno++;
                            });
                      
                           
                        } else {
                            //$(".subscription-bankname").text('');
                            $(".clear-approval").html(0);
                            $(".monthly-company-approval-status").attr('data-href', '');
                            $(".monthly-company-sub-status").attr('data-href', '');

                            //$("#bankname-listing").addClass('hide');
                        }
                    }
                });
            //$("#submit-download").prop('disabled',true);
        });
        
        $("#sal_updates_sidebars_id").addClass('active');
        $("#sal_updatelist_sidebar_li_id").addClass('active');
        $("#sal_updatelist_sidebar_a_id").addClass('active');

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

        $("#member_search").devbridgeAutocomplete({
            //lookup: countries,
            serviceUrl: "{{ URL::to('/get-auto-member-list') }}?serachkey="+ $("#member_search").val(),
            type:'GET',
            //callback just to show it's working
            onSelect: function (suggestion) {
                 $("#member_search").val(suggestion.value);
                 $("#member_auto_id").val(suggestion.number);
                 getDataStatus();
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

        $('#sub_company').change(function(){
           var CompanyID = $(this).val();
           var ajaxunionbranchid = '';
           var ajaxbranchid = '';
           var additional_cond;
           if(CompanyID!='' && CompanyID!='undefined')
           {
             additional_cond = '&unionbranch_id='+ajaxunionbranchid+'&branch_id='+ajaxbranchid;
             $.ajax({
                type: "GET",
                dataType: "json",
                url : "{{ URL::to('/get-branch-list-register') }}?company_id="+CompanyID+additional_cond,
                success:function(res){
                    if(res)
                    {
                        $('#branch_id').empty();
                        $("#branch_id").append($('<option></option>').attr('value', '').text("Select"));
                        $.each(res,function(key,entry){
                            $('#branch_id').append($('<option></option>').attr('value',entry.id).text(entry.branch_name)); 
                        });
                    }else{
                        $('#branch_id').empty();
                    }
                }
             });
           }else{
               $('#branch_id').empty();
           }
          
        });

        
    </script>
    @endsection