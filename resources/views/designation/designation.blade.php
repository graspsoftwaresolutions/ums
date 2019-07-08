@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/data-tables/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css') }}">
@endsection
@section('headSecondSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/pages/data-tables.css') }}">
@endsection
@section('main-content')
<div id="main">
	<div class="row">
		<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
		<div class="col s12">
			<div class="container">
				<div class="section section-data-tables">
					<!-- BEGIN: Page Main-->
					<div class="row">
						<div class="breadcrumbs-dark pb-0 pt-4" id="breadcrumbs-wrapper">
							<!-- Search for small screen-->
							<div class="container">
								<div class="row">
									<div class="col s10 m6 l6">
										<h5 class="breadcrumbs-title mt-0 mb-0">{{__('Designation List') }}</h5>
										<ol class="breadcrumbs mb-0">
											<li class="breadcrumb-item"><a href="{{ route('home', app()->getLocale()) }}">{{__('Dashboard') }}</a>
											</li>
											<li class="breadcrumb-item active">{{__('Designation') }}
											</li>
											
										</ol>
									</div>
									<div class="col s2 m6 l6 ">
										<a class="btn waves-effect waves-light breadcrumbs-btn right" href="{{ route('master.adddesignation', app()->getLocale()) }}">{{__('Add New Designation') }}</a>
										
									</div>
								</div>
							</div>
						</div>
						<div class="col s12">
							<div class="card">
								<div class="card-content">
									<h4 class="card-title">{{__('Designation List') }}</h4>
									@include('includes.messages')
									<div class="row">
										<div class="col s12">
											<table id="page-length-option" class="display">
												<thead>
													<tr>
														<th>{{__('Designation Name') }}</th>
														
														<th style="text-align:center"> {{__('Action') }}</th>
													</tr>
												</thead>
												<tbody>
													 @foreach($data['designation_view'] as $key=>$value)
													<tr>
													<?php
													$parameter =[
														'id' =>$value->id,
													];
													 $parameter = Crypt::encrypt($parameter);  
													 ?>
													@php
													{{ $confirmAlert = __("Are you sure you want to delete?"); }}
													@endphp
														<td>{{$value->designation_name}}</td>
														
														<td style="text-align:center">
														<a class="btn-small waves-effect waves-light cyan" href="{{ route('master.editdesignation', [app()->getLocale(), $parameter]) }}">{{__('Edit') }}</a>
														<a class="btn-small waves-effect waves-light amber darken-4" href="{{ route('master.deletedesignation', [app()->getLocale(), $parameter]) }}" onclick="if (confirm('{{ $confirmAlert }}')) return true; else return false;">{{__('Delete') }}</a></td>
												  </tr>
												  @endforeach
												</tbody>
												
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
	$("#masters_sidebars_id").addClass('active');
	$("#designation_sidebar_li_id").addClass('active');
	$("#designation_sidebar_a_id").addClass('active');
</script>
@endsection