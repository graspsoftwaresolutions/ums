@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/data-tables/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css') }}">
@endsection
@section('headSecondSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/pages/data-tables.css') }}">
<style type="text/css">
	#main .section-data-tables .dataTables_wrapper table.dataTable tbody th, #main .section-data-tables .dataTables_wrapper table.dataTable tbody td:last-child {
	    padding-top: 8px;
	    padding-bottom: 8px;
	    padding-left: 26px;
	    padding-right: 16px;
	    font-size: 12px;
	    white-space: nowrap;
	    text-transform: Uppercase;
	    border: none !important;
	}
	.btn-sm{
		padding: 0px 7px;
	    font-size: 8px;
	    line-height: 1.5;
	    border-radius: 3px;
		color: #fff;
	}
	#page-length-option td:not(:last-child) {
		word-break: break-word !important;
		white-space: unset !important;
		vertical-align: top;
	}
	#page-length-option td:nth-child(4) {
		word-break: normal !important;
		
	}

</style>
@endsection
@section('main-content')
<div id="">
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
										<h5 class="breadcrumbs-title mt-0 mb-0"> @if($data['irc_status']=="0"){{ 'IRC Pending List' }} @elseif($data['irc_status']=="1") IRC Confirmed List @else {{ 'IRC List' }} @endif</h5>
										<ol class="breadcrumbs mb-0">
										<li class="breadcrumb-item"><a href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard') }}</a>
											<li class="breadcrumb-item active"><a href="#">@if($data['irc_status']=="0"){{ 'IRC Pending List' }} @elseif($data['irc_status']=="1") IRC Confirmed List @else {{ 'IRC List' }} @endif</a>
											</li>
											
										</ol>
									</div>
									 @php
										$user_id = Auth::user()->id;
										$get_roles = Auth::user()->roles;
										$user_role = $get_roles[0]->slug;
									@endphp
									<div class="col s2 m6 l6 ">
										@if($user_role=='irc-confirmation' )
											<a class="btn waves-effect waves-light breadcrumbs-btn right" href="{{ route('irc.irc', 	app()->getLocale())  }}">{{__('New IRC') }}</a>
										@endif
										
									</div>
								</div>
							</div>
						</div>
						<div class="col s12">
							<div class="card">
								<div class="card-content">
									<h4 class="card-title">
										
									</h4>
									@include('includes.messages')
									<div class="row">
										<div class="col s12">
											<table id="page-length-option" class="display" width="100%">
												<thead>
													<tr>
														<th colspan="4">&nbsp;</th>
														<th colspan="2"><input type="text" id="common-search-input" class="common-search-input " placeholder="Search here" value="" ></th>
														<th>Status</th>
														<th colspan="2" valign="middle">
															<select id="common-search-status" class="browser-default">
																<option @if($data['irc_status']==""){{ 'selected' }} @endif value="">All</option>
																<option @if($data['irc_status']==1){{ 'selected' }} @endif value="1">Confirm</option>
																<option @if($data['irc_status']=="0"){{ 'selected' }} @endif value="0">Pending</option>
															</select>
														</th>
														
													</tr>
													<tr>
														<th>{{__('Status') }}</th>
														<th width="5%">{{__('M/ID')}} </th>
														<th width="10%">{{__('Full Name')}}</th>
														<th style="">{{__('ICNO')}}</th>
														<th width="5%">{{__('Company Name')}}</th>
														<th>{{__('Branch')}}</th>
														<th>{{__('Received')}}</th>
														<th>{{__('Submitted')}}</th>
														<th  style="text-align:center;">{{__('Action') }}</th>
													</tr>
													 
												</thead>
												
											</table>
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
<script src="{{ asset('public/assets/vendors/data-tables/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/data-tables/js/dataTables.select.min.js') }}" type="text/javascript"></script>
@endsection
@section('footerSecondSection')
<script src="{{ asset('public/assets/js/scripts/data-tables.js') }}" type="text/javascript"></script>
<script>
@if($data['irc_status']=="")
$("#irc_sidebar_a_id").addClass('active');
@elseif($data['irc_status']==0)
$("#pendirc_sidebar_a_id").addClass('active');
@elseif($data['irc_status']==1)
$("#conirc_sidebar_a_id").addClass('active');
@endif
$(function () {
	  $(".datepicker-cutom").datepicker({
		 autoclose: true,
		 format: "mmm/yyyy"
	  });
    var dataTable =  $('#page-length-option').DataTable({
			"responsive": true,
			"searching": false,
			"lengthMenu": [
				[10, 25, 50, -1],
				[10, 25, 50, "All"]
			],
			"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "{{ url(app()->getLocale().'/ajax_irc_list/') }}",
				"dataType": "json",
				"type": "POST",
				'data': function(data){
				  var searchfilter = $('#common-search-input').val();
				  var statusfilter = $('#common-search-status').val();
				
				  data.searchfilter = searchfilter;
				  data.statusfilter = statusfilter;
				  data._token = "{{csrf_token()}}";
			   },
			   "error": function (jqXHR, textStatus, errorThrown) {
		            if(jqXHR.status==419){
		            	alert('Your session has expired, please login again');
		            	window.location.href = base_url;
		            }
		       },
			},
			"search-transfer" : 12,
			"columns": [
				{"data" : "status"},
				{"data":  "resignedmemberno"},
				{"data":  "resignedmembername"},
				{"data" : "resignedmembericno"},
				{"data" : "resignedmemberbankname"},
				{"data" : "resignedmemberbranchname"},
				{"data" : "received"},
				{"data" : "received"},
				{"data":  "options"}
			]
		});
		$("#employee-grid_filter").css("display","none");  // hiding global search box
 
		$('#common-search-status,#common-search-input').on( 'keyup change', function () {
			dataTable.draw();
		} );
	 
});
 </script>
@endsection