@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/chartist-js/chartist.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/chartist-js/chartist-plugin-tooltip.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/pages/dashboard-modern.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/pages/intro.css') }}">
@endsection

@section('main-content')
<div id="main">
	<div class="row">
		<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
		<div class="col s12">
			<div class="container">
				@if (session()->has('error'))
					<div class="card-alert card red">
						<div class="card-content white-text">
						  <p>
							<i class="material-icons">error</i> {{__('Error') }}: {{__(session('error')) }}</p>
						</div>
						<button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
						  <span aria-hidden="true">×</span>
						</button>
					 </div>
				@endif
			 <!-- BEGIN: Page Main-->
				@role('union')
					@include('dashboard.union')
				@endrole
				@role('union-branch')
					@include('dashboard.union_branch')
				@endrole
				@role('company')
					@include('dashboard.company')
				@endrole
				@role('company-branch')
					@include('dashboard.company_branch')
				@endrole
				@role('member')
					@include('dashboard.member')
				@endrole
				@include('layouts.right-sidebar')
			 <!-- END: Page Main-->
			</div>
		</div>
	</div>
</div>
@endsection
		
@section('footerSection')
<script src="{{ asset('public/assets/vendors/chartjs/chart.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/chartist-js/chartist.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/chartist-js/chartist-plugin-tooltip.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/chartist-js/chartist-plugin-fill-donut.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/scripts/dashboard-modern.js') }}" type="text/javascript"></script>
<script>
	$("#dashboard_sidebar_a_id").addClass('active');
</script>
@endsection
