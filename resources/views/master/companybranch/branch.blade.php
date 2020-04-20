@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/data-tables/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/buttons.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/font-awesome.min.css') }}">
@endsection
@section('headSecondSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/pages/data-tables.css') }}">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/export-button.css') }}">
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
										<h5 class="breadcrumbs-title mt-0 mb-0">{{__('Company Branch List') }}</h5>
										<ol class="breadcrumbs mb-0">
											<li class="breadcrumb-item"><a href="{{ route('home', app()->getLocale()) }}">{{__('Dashboard') }}</a>
											</li>
											<li class="breadcrumb-item active">{{__('Company Branch') }}
											</li>
											
										</ol>
									</div>
									<div class="col s2 m6 l6 ">
										<a class="btn waves-effect waves-light breadcrumbs-btn right" href="{{ route('master.addbranch', app()->getLocale()) }}">{{__('Add')}}</a>
										
									</div>
								</div>
							</div>
						</div>
						<div class="col s12">
							<div class="card">
								<div class="card-content">
									<h4 class="card-title">{{__('Company Branch List') }}</h4>
									@include('includes.messages')
									<div class="row">
										<div class="col s12">
											<table id="page-length-option" class="display" width="100%">
												<thead>
													<tr>
														<th>{{__('Company Name') }}(is_head)</th>
														<th>{{__('Branch Name') }}</th>
														<th>{{__('Email') }}</th>
														<th>{{__('State') }}</th>
														<th>{{__('City') }}</th>
														<!-- <th>{{__('Empty State') }}</th> -->
														<th>{{__('Head') }}</th>
														<th style="text-align:center;">{{__('Action') }}</th>
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
<script src="{{ asset('public/assets/js/dataTables.buttons.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/buttons.flash.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jszip.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/pdfmake.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/vfs_fonts.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/buttons.html5.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/buttons.print.min.js') }}" type="text/javascript"></script>
@endsection
@section('footerSecondSection')
<script src="{{ asset('public/assets/js/scripts/data-tables.js') }}" type="text/javascript"></script>
<script>
	$("#masters_sidebars_id").addClass('active');
	$("#branch_sidebar_li_id").addClass('active');
	$("#branch_sidebar_a_id").addClass('active');
</script>
<script>
	$(function () {
		$('#page-length-option').DataTable({
			"responsive": true,
			dom: 'lBfrtip', 
			"lengthMenu": [
	            [500, 10, 25, 50, 100, 4000],
	            [500, 10, 25, 50, 100, 'All']
	        ],
			buttons: [
			   {
				   extend: 'pdf',
				   footer: true,
				   exportOptions: {
						columns: [0,1,2,3,4,5]
					},
					title : 'Bank Branch List',
					titleAttr: 'pdf',
            		text:'<i class="fa fa-file-pdf-o"></i>'
			   },
			   {
				   extend: 'excel',
				   footer: false,
				   exportOptions: {
						columns: [0,1,2,3,4,5]
					},
					title : 'Bank Branch List',
					text:      '<i class="fa fa-file-excel-o"></i>',
					titleAttr: 'excel'
			   },
				{
				   extend: 'print',
				   footer: false,
				   exportOptions: {
						columns: [0,1,2,3,4,5]
					},
					title : 'Bank Branch List',
					text:   '<i class="fa fa-files-o"></i>',
           			 titleAttr: 'print'
			   }  
			],
			"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "{{ url(app()->getLocale().'/ajax-company-branchlist') }}",
				"dataType": "json",
				"type": "POST",
				"data": {_token: "{{csrf_token()}}"},
				"error": function (jqXHR, textStatus, errorThrown) {
		            if(jqXHR.status==419){
		            	alert('Your session has expired, please login again');
		            	window.location.href = base_url;
		            }
		       },
			},
			"columns": [
				{"data": "head_of_company"},
				{"data": "branch_name"},
				{"data": "email"},
				//{"data": "empty"},
				{"data": "statename"},
				{"data": "cityname"},
				{"data": "is_head"},
				{"data": "options"}
			]

		});
	});
	function ConfirmDeletion() {
		if (confirm("{{ __('Are you sure you want to delete?') }}")) {
			return true;
		} else {
			return false;
		}
	}
</script>
@endsection