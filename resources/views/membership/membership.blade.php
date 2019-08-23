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
								 Membership List &nbsp; @if($data['member_status'] !='all')  <span class="custom-badge">Status : {{ CommonHelper::get_member_status_name($data['member_status']) }}</span> @endif
								@if($data['member_type'] ==1) 
									<a class="btn waves-effect breadcrumbs-btn waves-light amber right" href="{{ route('master.membershipnew', app()->getLocale() )}}">{{__('Pending members list') }}</a>
								@else
									<a class="btn waves-effect breadcrumbs-btn waves-light green darken-1 right" href="{{ route('master.membership', app()->getLocale()) }}">{{__('Approved members list') }}</a>
								@endif
								<input type="button" id="advancedsearchs" name="advancedsearch" class="btn" value="Advanced search">
								
							</h4>
							<div class="row">
							<div class="card advancedsearch" style="dispaly:none;">
								<div class="col s12">
									<form method="post" id="advancedsearch">
									@csrf  
									<div class="row">   
										<div class="col s3">
											<label>{{__('Union Branch Name') }}</label>
											<select name="unionbranch_id" id="unionbranch_id" class="error browser-default selectpicker" data-error=".errorTxt22" >
												<option value="">{{__('Select Union') }}</option>
												@foreach($data['unionbranch_view'] as $value)
                                                <option value="{{$value->id}}">
                                                    {{$value->union_branch}}</option>
                                                @endforeach
											</select>
											<div class="input-field">
												<div class="errorTxt22"></div>
											</div>
										</div>
										<div class="col s3">
											<label>{{__('Company Name') }}</label>
											<select name="company_id" id="company_id" class="error browser-default selectpicker" data-error=".errorTxt22">
												<option value="">{{__('Select Company') }}</option>
												@php
                                                $data1 = CommonHelper::DefaultCountry();
                                                @endphp
                                                @foreach($data['company_view'] as $value)
                                                <option value="{{$value->id}}" >
                                                    {{$value->company_name}}</option>
                                                @endforeach
											</select>
											<div class="input-field">
												<div class="errorTxt22"></div>
											</div>
										</div>
										
										<div class="col s3">
											<label>{{__('Company Branch Name') }}</label>
											<select name="branch_id" id="branch_id" class="error browser-default selectpicker" data-error=".errorTxt23" >
												<option value="">{{__('Select Branch') }}</option>
												 @foreach($data['companybranch_view'] as $value)
                                                <option value="{{$value->id}}">
                                                    {{$value->branch_name}}</option>
                                                @endforeach
												
											</select>
											<div class="input-field">
												<div class="errorTxt23"></div>
											</div>
										</div>
										<div class="col s3">
											<label>{{__('Gender') }}</label>
											<select name="gender" id="gender" class="error browser-default selectpicker" data-error=".errorTxt23" >
												<option value="">{{__('Select Gender') }}</option>
												<option value="Male">Male</option>
												<option value="Female"> Female</option>
											</select>
											<div class="input-field">
												<div class="errorTxt23"></div>
											</div>
										</div>
										<div class="col s3">
											<label>{{__('Race') }}</label>
											<select name="race_id" id="race_id" class="error browser-default selectpicker" data-error=".errorTxt23" >
												<option value="">{{__('Select Race') }}</option>
												 @foreach($data['race_view'] as $value)
                                                <option value="{{$value->id}}" @if($data1==$value->id) selected @endif >
                                                    {{$value->race_name}}</option>
                                                @endforeach
												
											</select>
											<div class="input-field">
												<div class="errorTxt23"></div>
											</div>
										</div>
										<div class="col s3">
											<label>{{__('Status') }}</label>
											<select name="status_id" id="status_id" class="error browser-default selectpicker" data-error=".errorTxt23" >
												<option value="">{{__('Select Status') }}</option>
												 @foreach($data['status_view'] as $value)
                                                <option value="{{$value->id}}" @if($data1==$value->id) selected @endif >
                                                    {{$value->status_name}}</option>
                                                @endforeach
												
											</select>
											<div class="input-field">
												<div class="errorTxt23"></div>
											</div>
										</div>
										<div class="col s3">
											<label>{{__('Country') }}</label>
											<select name="country_id" id="country_id" class="error browser-default selectpicker" data-error=".errorTxt23" >
												<option value="">{{__('Select Country') }}</option>
												
												@foreach($data['country_view'] as $value)
                                                <option value="{{$value->id}}" @if($data1==$value->id) selected @endif >
                                                    {{$value->country_name}}</option>
                                                @endforeach
											</select>
											<div class="input-field">
												<div class="errorTxt23"></div>
											</div>
										</div>
										<div class="col s3">
											<label>{{__('State') }}</label>
											<select name="state_id" id="state_id" class="error browser-default selectpicker" data-error=".errorTxt23" >
												<option value="">{{__('Select State') }}</option>
												@foreach($data['state_view'] as $value)
                                                <option value="{{$value->id}}">
                                                    {{$value->state_name}}</option>
                                                @endforeach
												
											</select>
											<div class="input-field">
												<div class="errorTxt23"></div>
											</div>
										</div>
										<div class="col s3">
											<label>{{__('City') }}</label>
											<select name="city_id" id="city_id" class="error browser-default selectpicker" data-error=".errorTxt23" >
												<option value="">{{__('Select City') }}</option>
												@foreach($data['city_view'] as $value)
                                                <option value="{{$value->id}}">
                                                    {{$value->city_name}}</option>
                                                @endforeach
												
											</select>
											<div class="input-field">
												<div class="errorTxt23"></div>
											</div>
										</div>
										</div>
										<div class="row">
											<div class="input-field col s6 right">
												<input type="submit" id="clear"  class="btn" name="clear" value="{{__('Clear')}}">
											</div>
											<div class="input-field col s6 right-align">
												<input type="submit"  class="btn" name="search" value="{{__('Search')}}">
											</div>
										</div>
									</div>
									</form> 
								</div>
								</div>
							</div>
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
$('#advancedsearch').hide();
 $('#advancedsearchs').click(function(){
	$('#advancedsearch').toggle();
	});
$('#clear').click(function(){
	$(".selectpicker").val('').trigger("change"); 
});	

$("#membership_sidebar_a_id").addClass('active');


$(function () {
 var dataTable = $('#page-length-option').DataTable({
	"responsive": true,
	//"searching": false,
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
		'data': function(data){
		  var unionbranch_id = $('#unionbranch_id').val();
		  var company_id      = $(company_id).val();
		  var branch_id = $('#branch_id').val();
		  var gender = $('#gender').val();
		  var race_id = $('#race_id').val();
		  var status_id = $('#status_id').val();
		  var country_id = $('#country_id').val();
		  var state_id = $('#state_id').val();
		  var city_id = $('#city_id').val();
		  
		  //console.log(datefilter);
		 
		  data.unionbranch_id = unionbranch_id;
		  data.company_id = company_id;
		  data.branch_id = branch_id;
		  data.gender = gender;
		  data.race_id = race_id;
		  data.status_id = status_id;
		  data.country_id = country_id;
		  data.state_id = state_id;
		  data.city_id = city_id;
		  console.log(data);
		  data._token = "{{csrf_token()}}";
	   }
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

$(document).on('submit','form#advancedsearch',function(event){
	event.preventDefault();
	dataTable.draw();
});
});



</script>
@endsection