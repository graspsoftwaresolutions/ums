
@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css"
href="{{ asset('public/assets/vendors/data-tables/css/jquery.dataTables.min.css') }}"> 
<link rel="stylesheet" type="text/css"
href="{{ asset('public/assets/vendors/data-tables/css/select.dataTables.min.css') }}"> 
<link rel="stylesheet" type="text/css"
href="{{ asset('public/assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css') }}">
@endsection
@section('headSecondSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/pages/data-tables.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/custom_respon.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/buttons.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/font-awesome.min.css') }}">
<style>
	.memberinfotable tr>td{
		font-weight: bold;
		font-size: 16px;
	}
</style>
@endsection
@section('main-content')

<div class="row">
   
        <div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
        <div class="col s12">
            <div class="container">
                <div class="section section-data-tables">
                    <!-- BEGIN: Page Main-->
                    <div class="row">
                        <div class="breadcrumbs-dark" id="breadcrumbs-wrapper">
                            <!-- Search for small screen-->
                            <div class="container">
                                <div class="row">
                                    <div class="col s10 m6 l6">
                                        <h5 class="breadcrumbs-title mt-0 mb-0">{{__('History')}}</h5>
                                        <ol class="breadcrumbs mb-0">
                                            <li class="breadcrumb-item"><a
                                                    href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard')}}</a>
                                            </li>
                                            <li class="breadcrumb-item active">{{__('History')}}
                                            </li>
                                        </ol>
                                    </div>
                                    <div class="col s2 m6 l6 ">
										   <a class="btn waves-effect waves-light breadcrumbs-btn right" href="{{route('master.membership',app()->getLocale())}}">{{__('Back') }}</a>
									   </div>
                                </div>
                            </div>
                           
                        </div>
                        <div class="row">
							<div class="col s12">
								<div class="card">
									@php
										$member = $data['member_details'];
									@endphp
									<div class="card-content">
										<h4 class="card-title">{{__('Member Details')}}  </h4> 
										<table width="100%" class="memberinfotable" style="font-weight: bold; font-size: 16px">
											<tr>
												<td width="25%">{{__('Member Name ')}}</td>
												<td width="25%" style="color:{{$member->font_color}}">: {{ $member->membername }} [{{ $member->member_number }}]</td>
												<td width="25%">{{ __('NRIC-OLD')}}</td>
												<td width="25%"  style="color:{{$member->font_color}}">: {{ $member->old_ic }}</td>
											</tr>
											<tr>
												<td width="25%">{{__('NRIC-NEW')}}</td>
												<td width="25%"  style="color:{{$member->font_color}}">: {{ $member->new_ic }}</td>
												<td width="25%">{{__('Bank')}}</td>
												<td width="25%"  style="color:{{$member->font_color}}">: {{ $member->company_name }}</td>
												
											</tr>
											<tr>
												<td width="25%">{{__('Type')}}</td>
												<td width="25%"  style="color:{{$member->font_color}}">: {{ $member->membertype }}</td>
												
												<td width="25%">{{__('Status')}}</td>
												<td width="25%"  style="color:{{$member->font_color}}">: {{ $member->status_name }}</td>
											</tr>
											<tr>
												<td width="25%">{{__('Date of joing')}}</td>
												<td width="25%"  style="color:{{$member->font_color}}">: {{ date('d/M/Y',strtotime($member->doj)) }}</td>
												
												<td width="25%">{{__('Last paid Date')}}</td>
												<td width="25%"  style="color:{{$member->font_color}}">: 
												@if(count($data['member_history'])>0)
												{{ date('M/ Y',strtotime($data['member_history'][count($data['member_history'])-1]->StatusMonth)) }}
												@endif
												</td>
											</tr>
										</table>
								    </div>
                                </div>
                           
                             </div>		
							<div class="col s12">
								<div class="card">
                           
									<div class="card-content">
                            <h4 class="card-title">{{__('Member History') }}</h4>
                            @include('includes.messages')
                            <div class="row">
                                <div class="col s12">
                                    <!-- Horizontal Stepper -->
									<div class="">
                                    <div class="col sm12 m12">   
                                        <table id="page-length-option" class="display ">
                                            <thead>
												<tr>
													<th>{{__('Date')}}</th>
													<th>{{__('Subs')}}</th>
													<th>{{__('BF')}}</th>
													<th>{{__('Ins')}}</th>
													<th>{{__('Month')}}</th>
													<th>{{__('LastPaid')}}</th>
													<th>{{__('PAID')}}</th>
													<th>{{__('DUE')}}</th>
													<th>{{__('Total')}}</th>
													<th>{{__('AccSubs')}}</th>
													<th>{{__('AccBF')}}</th>
													<th>{{__('AccIns')}}</th>
												</tr>
											</thead>
											
                                        </table>
                                        </div>          
									</div>
									</div>
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
    </div>
</div>
@endsection
@section('footerSection')
<script src="{{ asset('public/assets/vendors/data-tables/js/jquery.dataTables.min.js') }}"
type="text/javascript"></script>
<script
src="{{ asset('public/assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js') }}"
type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/data-tables/js/dataTables.select.min.js') }}"
type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/assets/js/scripts/form-validation.js')}}" type="text/javascript"></script>
@endsection
@section('footerSecondSection')
<script src="{{ asset('public/assets/js/scripts/data-tables.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/dataTables.buttons.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/buttons.flash.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jszip.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/pdfmake.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/vfs_fonts.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/buttons.html5.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/buttons.print.min.js') }}" type="text/javascript"></script>
<script>
$("#subscriptions_sidebars_id").addClass('active');
$("#subscomp_sidebar_li_id").addClass('active');
$("#subcomp_sidebar_a_id").addClass('active');

//Data table Ajax call
$(function() {
    $('#page-length-option').DataTable({
        "responsive": true,
        "searching": false,
        
        
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "{{ url(app()->getLocale().'/ajax_member_history') }}?member_code="+{{$member->memberid}},
            "dataType": "json",
            "type": "POST",
            "data": {
                _token: "{{csrf_token()}}"
            }
        },
        "columns": [
			{
                "data": "StatusMonth"
            },
            {
                "data": "SUBSCRIPTION_AMOUNT"
            },
            {
                "data": "BF_AMOUNT"
            },
            {
                "data": "INSURANCE_AMOUNT"
            },
            {
                "data": "TOTALMONTHSCONTRIBUTION"
            },
            {
                "data": "LASTPAYMENTDATE"
            },
            {
                "data": "TOTALMONTHSPAID"
            },
            {
                "data": "SUBSCRIPTIONDUE"
            },
            {
                "data": "Total"
            },
            {
                "data": "ACCSUBSCRIPTION"
            },
            {
                "data": "ACCBF"
            },
            {
                "data": "ACCINSURANCE"
            }
        ],
		"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
			$('td', nRow).css('color', aData.font_color );
		}
    });
});

</script>
@endsection