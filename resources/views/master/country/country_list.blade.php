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
										<h5 class="breadcrumbs-title mt-0 mb-0">{{__('Country List') }}</h5>
										<ol class="breadcrumbs mb-0">
											<li class="breadcrumb-item"><a href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard') }}</a>
											</li>
											<li class="breadcrumb-item active">{{__('Country') }}
											</li>
											
										</ol>
									</div>
									<div class="col s2 m6 l6 ">
										<a class="btn waves-effect waves-light breadcrumbs-btn right modal-trigger" href="#modal_add_edit 0">Add</a>	
									</div>
								</div>
							</div>
						</div>
						<div class="col s12">
							<div class="card">
								<div class="card-content">
									<h4 class="card-title">{{__('Country List') }}</h4>
									@include('includes.messages')
									<div class="row">
										<div class="col s12">
											<table id="page-length-option" class="display">
												<thead>
													<tr>
														<th>{{__('Country Name') }}</th>
														
														<th style="text-align:center"> {{__('Action') }}</th>
													</tr>
												</thead>
												<tbody>
                                                @foreach($data['country_view'] as $key=>$value)
												{{$value}}
													<?php
													 $parameter = Crypt::encrypt($value->id);  
													 ?>
													 <tr>
														<td>{{$value->country_name}}</td>
														@php
														{{ $confirmAlert = __("Are you sure you want to delete?"); }}
														@endphp
														<td style="text-align:center"><a id="modal_add_edit" class="btn-small waves-effect waves-light cyan modal-trigger edit_country" href="#modal_add_edit {{$value->id}}"  xhref="{{ route('master.editcountry',[app()->getLocale(),$parameter]) }}">{{__('Edit') }}</a> <a class="btn-small waves-effect waves-light amber darken-4" href="{{ route('master.deletecountry',[app()->getLocale(),$parameter])}}" onclick="if (confirm('{{ $confirmAlert }}')) return true; else return false;">{{__('Delete') }}</a></td>
													</tr>
													  <div id="modal_add_edit @if(isset($value->id)){{$value->id}}@else{{0}}}@endif" class="modal">
														<div class="modal-content">
														<h4>Country Details{{$value->id}}</h4>
														<form class="formValidate" id="countryformValidate" method="post" action="{{ route('master.savecountry',app()->getLocale()) }}">
															@isset($data[0])
															<input type="hidden" name="id" value="{{$data[0]->id}}">
															@endisset
															@csrf
															<div class="row">
															<div class="input-field col s12 m6">
																<label for="country_name" class="common-label">{{__('Country Name') }}*</label>
																<input id="country_name" class="common-input" name="country_name" type="text" value="@if(isset($data[0]->country_name)){{ $data[0]->country_name }}@endif" data-error=".errorTxt1">
																<div class="errorTxt1"></div>
															</div>
															<div class="col s12 m6">
																<button class="btn waves-effect waves-light right submit" type="submit" name="action">{{__('Save')}}
																</button>
															</div>
															</div>
														</form>
														</div>
													</div>
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
    <!-- Modal -->
    <!-- Modal Structure -->
   

@endsection
@section('footerSection')
<script src="{{ asset('public/assets/vendors/data-tables/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/data-tables/js/dataTables.select.min.js') }}" type="text/javascript"></script>
@endsection
@section('footerSecondSection')
<script src="{{ asset('public/assets/js/scripts/data-tables.js') }}" type="text/javascript"></script>
<script>
    $(document).ready(function(){
    // the "href" attribute of the modal trigger must specify the modal ID that wants to be triggered
    $('.modal').modal();
  });
	$("#masters_sidebars_id").addClass('active');
	$("#country_sidebar_li_id").addClass('active');
	$("#country_sidebar_a_id").addClass('active');
</script>
@endsection