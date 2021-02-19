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
                                    <h5 class="breadcrumbs-title mt-0 mb-0">{{__('Eco Park Summary') }}</h5>
                                    
                                </div>
                                <div class="col s2 m6 l6 ">
                                    
                                 
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
                                        <form class="formValidate" id="subscribe_formValidate" method="post" action="{{ url(app()->getLocale().'/ecopark/summary') }}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">

                                                <div class="input-field col m3 s12">
                                                    <label for="doe">{{__('Upload Month') }}*</label>
                                                    <input type="text" name="entry_date" readonly="" id="entry_date" value="{{ date('M/Y',strtotime($data['parkdate'])) }}" class="" />
                                                </div>

                                                <div class="col s4 hide">
                                                    <label for="batch_type">{{__('Type') }}*</label>
                                                    <select name="batch_type" id="batch_type" class="error browser-default selectpicker" data-error=".errorTxt6">
                                                        <option value="" selected>{{__('Choose Type') }}</option>
                                                    
                                                        <option data-type="Batch 1 Member" value="1">Batch 1 Member</option>
                                                        <option data-type="Batch 1 Non Member" value="2">Batch 1 Non Member</option>
                                                        <option data-type="Batch 2 Member" value="3">Batch 2 Member</option>
                                                        <option data-type="Batch 2 Non Member" value="4">Batch 2 Non Member</option>
                                                      
                                                    </select>
                                                    <div class="errorTxt6"></div>
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
                    <div class="col s12 m6">
                        <div id="DivIdToPrint" class="card subscriber-list-card animate fadeRight">
                            <div class="card-content" style="border-bottom: #2d22d6 solid 1px;">
                                <h4 class="card-title mb-0">Member Status 
                                        <!-- <a id="printbutton" href="#" style="margin-left: 50px;" class="export-button btn btn-sm" style="background:#ccc;" onClick="return printDiv()"> <i class="material-icons">print</i></a> -->
                                        <span class="right datamonth hide">[{{ date('M/Y',strtotime($data['parkdate'])) }}]</span>
                                    </h4>
                            </div>
                            <table class="subscription-table responsive-table highlight">
                                <thead>
                                    <tr style="background: linear-gradient(45deg,#8e24aa,#ff6e40)!important;color:#fff;">
                                        <th>Sl No</th>
                                        <th>Status</th>
                                        <th>Count</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $slno =1; 
                                        $summary_slno =1; 
                                        $total_members_count = 0; 
                                        $total_members_amount = 0;
                                    @endphp
                                    @foreach($data['member_status'] as $key => $status)
                                        @php
                                            $member_status_count = CommonHelper::statusEcoParkMembersCount($status->id,$data['parkdate']); 
                                            $member_status_amount = CommonHelper::statusEcoParkMembersAmount($status->id,$data['parkdate']); 

                                            $member_sub_link = URL::to(app()->getLocale().'/ecopark-status?member_status='.$status->id.'&date='.strtotime($data['parkdate']).'&batch_type=&member_type='); 
                                        @endphp
                                    <tr class="monthly-sub-status " id="monthly_member_status_{{ $status->id }}" data-href="{{ $member_sub_link }}" style="cursor:pointer;color:{{ $status->font_color }};">
                                        <td>{{ $slno }}</td>
                                        <td>{{ $status->status_name }}</td>
                                        <td id="member_status_count_1">{{ $member_status_count }}</td>
                                        <td id="member_status_amount_1">{{ number_format($member_status_amount,2,".",",") }}</td>
                                    </tr>
                                    @php
                                        $slno++;
                                        $total_members_count += $member_status_count; 
                                        $total_members_amount += $member_status_amount; 
                                    @endphp
                                    @endforeach
                                 
                                </tbody>
                                <tfoot>
                                    <tr class="monthly-sub-status" id="monthly_member_status_all" data-href="{{ URL::to(app()->getLocale().'/ecopark-status?member_status=all&date='.strtotime($data['parkdate']).'&batch_type=&member_type=') }}" style="cursor:pointer;background: #dbdbf7;font-weight:bold;">
                                        <td colspan="2">Total</td>
                                        <td id="member_status_count_total">{{ $total_members_count }}</td>
                                        <td id="member_status_amount_total">{{ number_format($total_members_amount,2,".",",") }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <br>
                    </div>
                    <!--Approval Status-->
                    <div class="col s12 m6">
                        <div class="card subscriber-list-card animate fadeRight">
                            <div class="card-content" style="border-bottom: #2d22d6 solid 1px;">
                                <h4 class="card-title mb-0">Overall Summary
                                        <span class="right datamonth hide">[{{ date('M/Y',strtotime($data['parkdate'])) }}]</span>
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
                                        $lowpay_members_count = 0;
                                        $lowpay_nonmembers_count = 0;
                                        $lowpay_members_amount = 0;
                                        $lowpay_nonmembers_amount = 0;
                                        $zeropay_members_amount = 0;
                                        $zeropay_members_count = 0;
                                        $zeropay_nonmembers_count = 0;

                                        $fullpay_members_count = 0;
                                        $fullpay_nonmembers_count = 0;
                                        $fullpay_members_amount = 0;
                                        $fullpay_nonmembers_amount = 0;
                                        for($i=1;$i<=5;$i++){
                                            $lowpay_members_count += CommonHelper::EcoParkLowPaymentMembersCount($data['parkdate'],$i,1); 
                                            $lowpay_nonmembers_count += CommonHelper::EcoParkLowPaymentMembersCount($data['parkdate'],$i,0);

                                            $lowpay_members_amount += CommonHelper::EcoParkLowPaymentMembersAmount($data['parkdate'],$i,1); 
                                            $lowpay_nonmembers_amount += CommonHelper::EcoParkLowPaymentMembersAmount($data['parkdate'],$i,0);

                                            $zeropay_members_count += CommonHelper::EcoParkZeroPaymentMembersCount($data['parkdate'],$i,1); 
                                            $zeropay_nonmembers_count += CommonHelper::EcoParkZeroPaymentMembersCount($data['parkdate'],$i,0);

                                            if($i<5){
                                                $fullpay_members_count += CommonHelper::EcoParkFullPaymentMembersCount($data['parkdate'],$i,1); 
                                                $fullpay_nonmembers_count += CommonHelper::EcoParkFullPaymentMembersCount($data['parkdate'],$i,0);

                                                $fullpay_members_amount += CommonHelper::EcoParkFullPaymentMembersAmount($data['parkdate'],$i,1); 
                                                $fullpay_nonmembers_amount += CommonHelper::EcoParkFullPaymentMembersAmount($data['parkdate'],$i,0);
                                            }

                                        }

                                        $card_status_members_count = CommonHelper::EcoParkCardStatusMembersCount($data['parkdate'],'PC SEND OUT',1); 
                                        $card_status_nonmembers_count = CommonHelper::EcoParkCardStatusMembersCount($data['parkdate'],'PC SEND OUT',0);

                                        $card_status_members_amount = CommonHelper::EcoParkCardStatusMembersAmount($data['parkdate'],'PC SEND OUT',1); 
                                        $card_status_nonmembers_amount = CommonHelper::EcoParkCardStatusMembersAmount($data['parkdate'],'PC SEND OUT',0);
                                         

                                        //$member_status_amount = CommonHelper::statusEcoParkMembersAmount($status->id,$data['parkdate']); 
                                    @endphp
                                    <tr class="monthly-approval-status " id="monthly_approval_status_1" data-href="{{ URL::to(app()->getLocale().'/ecopark/members?&date='.strtotime($data['parkdate'])) }}" style="cursor:pointer;">
                                        <td>{{ $summary_slno++ }}</td>
                                        <td>Total Uploaded Members(Members & Non Members)</td>
                                        <td id="approval_status_count_1">{{ $data['members_sum']->count }}</td>
                                        <td id="approval_pending_amount_1">{{ number_format($data['members_sum']->amount,2,".",",") }}</td>
                                    </tr>
                                    <tr class="monthly-approval-status " id="monthly_approval_status_1" data-href="{{ URL::to(app()->getLocale().'/ecopark-status?member_status=&date='.strtotime($data['parkdate']).'&batch_type=&member_type=1&payment_type=low') }}" style="cursor:pointer;">
                                        <td>{{ $summary_slno++ }}</td>
                                        <td>Low Payment Members</td>
                                        <td id="approval_status_count_1">{{ $lowpay_members_count }}</td>
                                        <td id="approval_pending_amount_1">{{ number_format($lowpay_members_amount,2,".",",") }}</td>
                                    </tr>
                                    <tr class="monthly-approval-status " id="monthly_approval_status_1" data-href="{{ URL::to(app()->getLocale().'/ecopark-status?member_status=&date='.strtotime($data['parkdate']).'&batch_type=&member_type=0&payment_type=low') }}" style="cursor:pointer;">
                                        <td>{{ $summary_slno++ }}</td>
                                        <td>Low Payment Non Members</td>
                                        <td id="approval_status_count_1">{{ $lowpay_nonmembers_count }}</td>
                                        <td id="approval_pending_amount_1">{{ number_format($lowpay_nonmembers_amount,2,".",",") }}</td>
                                    </tr>
                                    <tr class="monthly-approval-status " id="monthly_approval_status_1" data-href="{{ URL::to(app()->getLocale().'/ecopark-status?member_status=&date='.strtotime($data['parkdate']).'&batch_type=&member_type=1&payment_type=zero') }}" style="cursor:pointer;">
                                        <td>{{ $summary_slno++ }}</td>
                                        <td>Zero Payment Members</td>
                                        <td id="approval_status_count_1">{{ $zeropay_members_count }}</td>
                                        <td id="approval_pending_amount_1">{{ number_format($zeropay_members_amount,2,".",",") }}</td>
                                    </tr>
                                     <tr class="monthly-approval-status " id="monthly_approval_status_1" data-href="{{ URL::to(app()->getLocale().'/ecopark-status?member_status=&date='.strtotime($data['parkdate']).'&batch_type=&member_type=0&payment_type=zero') }}" style="cursor:pointer;">
                                        <td>{{ $summary_slno++ }}</td>
                                        <td>Zero Payment Non Members</td>
                                        <td id="approval_status_count_1">{{ $zeropay_nonmembers_count }}</td>
                                        <td id="approval_pending_amount_1">{{ number_format($zeropay_members_amount,2,".",",") }}</td>
                                    </tr>
                                    <tr class="monthly-approval-statusa " id="monthly_approval_status_1" data-href="http://localhost/ums/index.php/en/subscription-status?approval_status=1&amp;date=1580495400" style="cursor:pointer;">
                                        <td>{{ $summary_slno++ }}</td>
                                        <td>New Members</td>
                                        <td id="approval_status_count_1">0</td>
                                        <td id="approval_pending_amount_1">0</td>
                                    </tr>
                                    <tr class="monthly-approval-status " id="monthly_approval_status_1" data-href="{{ URL::to(app()->getLocale().'/ecopark-status?member_status=&date='.strtotime($data['parkdate']).'&batch_type=&member_type=1&payment_type=full') }}" style="cursor:pointer;">
                                        <td>{{ $summary_slno++ }}</td>
                                        <td>Full Payment Members</td>
                                        <td id="approval_status_count_1">{{ $fullpay_members_count }}</td>
                                        <td id="approval_pending_amount_1">{{ number_format($fullpay_members_amount,2,".",",") }}</td>
                                    </tr>
                                     <tr class="monthly-approval-status " id="monthly_approval_status_1" data-href="{{ URL::to(app()->getLocale().'/ecopark-status?member_status=&date='.strtotime($data['parkdate']).'&batch_type=&member_type=0&payment_type=full') }}" style="cursor:pointer;">
                                        <td>{{ $summary_slno++ }}</td>
                                        <td>Full Payment Non Members</td>
                                        <td id="approval_status_count_1">{{ $fullpay_nonmembers_count }}</td>
                                        <td id="approval_pending_amount_1">{{ number_format($fullpay_nonmembers_amount,2,".",",") }}</td>
                                    </tr>
                                    <tr class="monthly-approval-status " id="monthly_approval_status_1" data-href="{{ URL::to(app()->getLocale().'/ecopark-status?member_status=&date='.strtotime($data['parkdate']).'&batch_type=&member_type=1&payment_type=&card_status=1') }}" style="cursor:pointer;">
                                        <td>{{ $summary_slno++ }}</td>
                                        <td>Paid Members have received card</td>
                                        <td id="approval_status_count_1">{{ $card_status_members_count }}</td>
                                        <td id="approval_pending_amount_1">{{ number_format($card_status_members_amount,2,".",",") }}</td>
                                    </tr>
                                    <tr class="monthly-approval-status " id="monthly_approval_status_1" data-href="{{ URL::to(app()->getLocale().'/ecopark-status?member_status=&date='.strtotime($data['parkdate']).'&batch_type=&member_type=0&payment_type=&card_status=1') }}" style="cursor:pointer;">
                                        <td>{{ $summary_slno++ }}</td>
                                        <td>Paid Non Members have received card</td>
                                        <td id="approval_status_count_1">{{ $card_status_nonmembers_count }}</td>
                                        <td id="approval_pending_amount_1">{{ number_format($card_status_nonmembers_amount,2,".",",") }}</td>
                                    </tr>
                                    @for($j=1;$j<=5;$j++)
                                    @php
                                        $batch_members_count = CommonHelper::EcoParkBatchMembersCount($data['parkdate'],$j,1); 
                                        $batch_nonmembers_count = CommonHelper::EcoParkBatchMembersCount($data['parkdate'],$j,0);

                                        $batch_members_amount = CommonHelper::EcoParkBatchMembersAmount($data['parkdate'],$j,1); 
                                        $batch_nonmembers_amount = CommonHelper::EcoParkBatchMembersAmount($data['parkdate'],$j,0);
                                        if($j==1){
                                            $batch_head = 'Batch 1 Members';
                                        }else if($j==2){
                                            $batch_head = 'Batch 1 Non Members';
                                        }else if($j==3){
                                            $batch_head = 'Batch 2 Members';
                                        }else if($j==4){
                                            $batch_head = 'Batch 2 Non Members';
                                        }else{
                                            $batch_head = 'Others';
                                        }
                                    @endphp
                                    <tr class=" " id="monthly_approval_status_1" data-href="" style="cursor:pointer;">
                                        <td colspan="4">{{$batch_head}}</td>
                                    </tr>
                                    <tr class="monthly-approval-status " id="monthly_approval_status_1" data-href="{{ URL::to(app()->getLocale().'/ecopark-status?member_status=&date='.strtotime($data['parkdate']).'&batch_type='.$j.'&member_type=1') }}" style="cursor:pointer;">
                                        <td>{{ $summary_slno++ }}</td>
                                        <td>Members</td>
                                        <td id="approval_status_count_1">{{ $batch_members_count }}</td>
                                        <td id="approval_pending_amount_1">{{ number_format($batch_members_amount,2,".",",") }}</td>
                                    </tr>
                                    <tr class="monthly-approval-status " id="monthly_approval_status_1" data-href="{{ URL::to(app()->getLocale().'/ecopark-status?member_status=&date='.strtotime($data['parkdate']).'&batch_type='.$j.'&member_type=0') }}" style="cursor:pointer;">
                                        <td>{{ $summary_slno++ }}</td>
                                        <td>Non Members</td>
                                        <td id="approval_status_count_1">{{ $batch_nonmembers_count }}</td>
                                        <td id="approval_pending_amount_1">{{ number_format($batch_nonmembers_amount,2,".",",") }}</td>
                                    </tr>
                                    @endfor


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
        $("#ecopark_sidebars_id").addClass('active');
        $("#ecopark_sidebar_li_id").addClass('active');
        $("#ecopark_sidebar_a_id").addClass('active');
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