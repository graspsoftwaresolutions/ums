@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/data-tables/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css') }}">
@endsection
@section('headSecondSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/pages/data-tables.css') }}">
<style>
	@media (min-width: 1025px) {
		ul.dtr-details li {
		  display:inline;
		  margin-right: 13px;
		}
		ul.dtr-details {
		   width: 1180px; overflow: auto
		}
		
		
	}
	span.dtr-title{
		font-weight: bold;
		color: #5a2da1 !important;
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
										<h5 class="breadcrumbs-title mt-0 mb-0">{{__('Membership List') }}</h5>
										<ol class="breadcrumbs mb-0">
										<li class="breadcrumb-item"><a href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard') }}</a>
											<li class="breadcrumb-item active"><a href="#">{{__('Member') }}</a>
											</li>
											
										</ol>
									</div>
									<div class="col s2 m6 l6 ">
										<a class="btn waves-effect waves-light breadcrumbs-btn right" href="{{ route('master.addmembership', app()->getLocale())  }}">{{__('New Registration') }}</a>
										
									</div>
								</div>
							</div>
						</div>
						<div class="col s12">
							<div class="card">
								<div class="card-content">
									<h4 class="card-title">
										@if($data['member_type'] ==1)
											Approved
										@else
											Pending
										@endif
										 Membership List
										@if($data['member_type'] ==1) 
											<a class="btn waves-effect breadcrumbs-btn waves-light amber right" href="{{ route('master.membershipnew', app()->getLocale() )}}">{{__('Pending members list') }}</a>
										@else
											<a class="btn waves-effect breadcrumbs-btn waves-light green darken-1 right" href="{{ route('master.membership', app()->getLocale()) }}">{{__('Approved members list') }}</a>
										@endif
									</h4>
									@include('includes.messages')
									<div class="row">
										<div class="col s12">
											<table id="page-length-option" class="display" width="100%">
												<thead>
													<tr>
														<th width="30%" style="text-align:center;">{{__('Action') }}</th>
													    <th>{{__('Member ID') }}</th>
														<th>{{__('Member Name') }}</th>
														<th>{{__('Type')}} </th>
														<th>{{__('M/F')}}</th>
														<th>{{__('Bank Short Code') }}</th>
														<th>{{__('Branch Name') }}</th>
														<th>{{__('Levy') }}</th>
														<th>{{__('Levy Amount') }}</th>
														<th>{{__('TDF') }}</th>
														<th>{{__('TDF Amount') }}</th>
														<th>{{__('DOJ')}}</th>
														<th>{{__('City') }}</th>
														<th>{{__('State') }}</th>
														<th>{{__('NRIC Old') }}</th>
														<th>{{__('NRIC New') }}</th>
														<th>{{__('Mobile') }}</th>
														<th>{{__('Race Short Code') }}</th>
														<!-- <th>{{__('Union Branch Name') }}</th> -->
														<th>{{__('Status') }}</th>
														
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
 $("#membership_sidebar_a_id").addClass('active');
 $(function () {
    $('#page-length-option').DataTable({
			"responsive": true,
			"lengthMenu": [
				[10, 25, 50, -1],
				[10, 25, 50, "All"]
			],
			"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "{{ url(app()->getLocale().'/ajax_members_list/'.$data['member_type']) }}?status={{$data['member_status']}}",
				"dataType": "json",
				"type": "POST",
				"data": {_token: "{{csrf_token()}}"}
			},
			"columns": [
				{"data": "options"},
				{"data" : "member_number"},
				{"data": "name"},
				{"data" : "designation_id"},
				{"data" : "gender"},
				{"data" : "short_code"},
				{"data": "branch_name"},
				{"data": "levy"},
				{"data": "levy_amount"},	
				{"data": "tdf"},
				{"data": "tdf_amount"},
				{"data": "doj"},
				{"data": "city_id"},
				{"data": "state_id"},
				{"data": "old_ic"},
				{"data": "new_ic"},
				{"data": "mobile"},
				{"data": "race_id"},
				{"data": "status"}
				
			],
			"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
				$('td', nRow).css('color', aData.font_color );
			}
		});
});
 </script>
@endsection