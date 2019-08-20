@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/materialize-stepper/materialize-stepper.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/themes/vertical-modern-menu-template/materialize.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/themes/vertical-modern-menu-template/style.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/vendors/data-tables/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css') }}">
@endsection
@section('headSecondSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/pages/data-tables.css') }}">
<style>

</style>
@endsection
@section('main-content')
@php 

@endphp
<div class="row">
	<div class="col s12">
		<div class="card">
			<div class="card-content">
				<h4 class="card-title">{{__('Members Filter')}}  </h4> 
				<form method="post" id="filtersubmit" action="{{route('subscription.memberfilter',app()->getLocale())}}">
					@csrf  
					<input type="hidden" name="id" value="{{ isset($row->MemberCode) ? $row->MemberCode : '' }}">
					<input type="hidden" name="memberid" value="{{ isset($row->memberid) ? $row->memberid : ''}}">
					<div class="row">                          
						<div class="input-field col s4">
							<i class="material-icons prefix">date_range</i>
							<input id="from_date" type="text" required class="validate datepicker" name="from_date">
							<label for="from_date">{{__('From Month and Year')}}</label>
						</div>
						<div class="input-field col s4">
							<i class="material-icons prefix">date_range</i>
							<input id="to_date" type="text" required class="validate datepicker" name="to_date">
							<label for="to_date">{{__('To Month and Year')}}</label>
						</div>
						<div class="input-field col s4">
						<input type="submit"  class="btn" name="search" value="{{__('Search')}}">
						</div>
					</div>
				</form>  
			</div>
		</div>
		<div class="card">
			<div class="card-content">
				<table id="page-length-option" class="display ">
					<thead>
						<tr>
							<th>{{__('Member Number')}}</th>
							<th>{{__('Member Name')}}</th>
							<th>{{__('NRIC')}}</th>
							<th>{{__('Gender')}}</th>
							<th>{{__('Bank')}}</th>
							<th>{{__('Branch')}}</th>
							<th>{{__('DOJ')}}</th>
							<th>{{__('Levy')}}</th>
						</tr> 
					</thead>
					<tbody>
						
						@foreach($data['member_view'] as $member)
							<tr>
								<td>{{ $member->name }}</td>
								<td>{{ $member->member_number }}</td>
								<td>{{ $member->new_ic }}</td>
								<td>{{ $member->gender }}</td>
								<td>{{ $member->companycode }}</td>
								<td>{{ $member->branch_name }}</td>
								<td>{{ $member->doj }}</td>
								<td>{{ $member->levy }}</td>
								
							</tr> 
						@endforeach
					</tbody>
					<input type="text" name="memberoffset" id="memberoffset" class="hide" value="0"></input>
				</table> 
			</div>
		</div>
	</div>
</div> 
@endsection
@section('footerSection')
<!--<script src="{{ asset('public/assets/js/jquery.min.js') }}"></script> -->
<script src="{{ asset('public/assets/vendors/data-tables/js/jquery.dataTables.min.js') }}" type="text/javascript">
</script>
<script src="{{ asset('public/assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js') }}"
    type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/data-tables/js/dataTables.select.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/assets/js/materialize.min.js') }}"></script>
<script src="{{ asset('public/assets/js/scripts/form-elements.js') }}" type="text/javascript"></script>
@endsection
@section('footerSecondSection')
<script>
$("#subscriptions_sidebars_id").addClass('active');
$("#subscomp_sidebar_li_id").addClass('active');
$("#subcomp_sidebar_a_id").addClass('active');

	$(document).ready(function(){
		//loader.showLoader();
	
	});
	
    $(window).scroll(function() {   
	   var lastoffset = $("#memberoffset").val();
	   var limit = "{{$data['data_limit']}}";
	   if($(window).scrollTop() + $(window).height() == $(document).height()) {
		    $("#memberoffset").val(parseInt(lastoffset)+parseInt(limit));
			$.ajax({
				type: "GET",
				dataType: "json",
				url : "{{ URL::to('/en/get-members-report') }}?offset="+lastoffset,
				success:function(res){
					if(res)
					{
						$.each(res,function(key,entry){
							var table_row = "<tr><td>"+entry.name+"</td>";
								table_row += "<td>"+entry.member_number+"</td>";
								table_row += "<td>"+entry.new_ic+"</td>";
								table_row += "<td>"+entry.gender+"</td>";
								table_row += "<td>"+entry.companycode+"</td>";
								table_row += "<td>"+entry.branch_name+"</td>";
								table_row += "<td>"+entry.doj+"</td>";
								table_row += "<td>"+entry.levy+"</td></tr>";
								$('#page-length-option tbody').append(table_row);
							
						});
					}else{
						
					}
				}
			});
		    
				
	   }
	});


</script>
@endsection