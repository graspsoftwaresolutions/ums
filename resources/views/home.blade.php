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
			 <!-- BEGIN: Page Main-->
				@role('union')
					@include('union.dashboard')
				@endrole
				@role('branch')
					@include('branch.dashboard')
				@endrole
				@role('member')
					@include('member.dashboard')
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
@endsection
