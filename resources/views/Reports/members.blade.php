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
				@php
					
					$userid = Auth::user()->id;
					$get_roles = Auth::user()->roles;
					$user_role = $get_roles[0]->slug;
					$companylist = [];
					$branchlist = [];
					$companyid = '';
					$branchid = '';
					if($user_role =='union'){
						$companylist = $data['company_view'];
					}
					else if($user_role =='union-branch'){
						$unionbranchid = CommonHelper::getUnionBranchID($userid);
						$companylist = CommonHelper::getUnionCompanyList($unionbranchid);
					} 
					else if($user_role =='company'){
						$branchid = CommonHelper::getCompanyBranchID($userid);
						$companyid = CommonHelper::getCompanyID($userid);
						$companylist = CommonHelper::getCompanyList($companyid);
						$branchlist = CommonHelper::getCompanyBranchList($companyid);
					}
					else if($user_role =='company-branch'){
						$branchid = CommonHelper::getCompanyBranchID($userid);
						$companyid = CommonHelper::getCompanyID($userid);
						$companylist = CommonHelper::getCompanyList($companyid);
						$branchlist = CommonHelper::getCompanyBranchList($companyid,$branchid);
					} 
					
				@endphp
				<form method="post" id="filtersubmit" action="{{route('subscription.memberfilter',app()->getLocale())}}">
					@csrf  
					<input type="hidden" name="id" value="{{ isset($row->MemberCode) ? $row->MemberCode : '' }}">
					<input type="hidden" name="memberid" value="{{ isset($row->memberid) ? $row->memberid : ''}}">
					<div class="row">                          
						<div class="col s3">
							<label for="month_year">{{__('Month and Year')}}</label>
							<input id="month_year" type="text" required class="validate datepicker" name="month_year">
						</div>
						<div class="col s3">
							<label>{{__('Company Name') }}*</label>
							<select name="company_id" id="company" class="error browser-default selectpicker" data-error=".errorTxt22" required >
								<option value="">{{__('Select Company') }}</option>
								@foreach($companylist as $value)
								<option @if($companyid==$value->id) selected @endif value="{{$value->id}}">{{$value->company_name}}</option>
								@endforeach
							</select>
							<div class="input-field">
								<div class="errorTxt22"></div>
							</div>
						</div>
						<div class="col s3">
							<label>{{__('Company Branch Name') }}*</label>
							<select name="branch_id" id="branch" class="error browser-default selectpicker" data-error=".errorTxt23" required >
								<option value="">{{__('Select Branch') }}</option>
								@foreach($branchlist as $branch)
								<option @if($branchid==$branch->id) selected @endif value="{{$branch->id}}">{{$branch->branch_name}}</option>
								@endforeach
							</select>
							<div class="input-field">
								<div class="errorTxt23"></div>
							</div>
						</div>
						<div class="col s3">
							<label for="member_number">{{__('Member Number')}}</label>
							<input id="member_search" type="text" required class="validate " name="member_search">
							<input id="member_number" type="text" required class="validate " name="member_number">
						</div>
						<div class="input-field col s12 right-align">
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
<script src="{{ asset('public/assets/js/jquery.autocomplete.min.js') }}" type="text/javascript"></script>
<style type="text/css">
	.autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; cursor:pointer; }
	.autocomplete-suggestion { padding: 8px 5px; white-space: nowrap; overflow: hidden; }
	.autocomplete-selected { background: #F0F0F0; }
	.autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
	.autocomplete-group { padding: 8px 5px; }
	.autocomplete-group strong { display: block; border-bottom: 1px solid #000; }
	#transfer_member{
		color:#fff;
	}
</style>
@endsection
@section('footerSecondSection')
<script>
$("#subscriptions_sidebars_id").addClass('active');
$("#subscomp_sidebar_li_id").addClass('active');
$("#subcomp_sidebar_a_id").addClass('active');

	$(document).ready(function(){
		$("#member_search").devbridgeAutocomplete({
			//lookup: countries,
			serviceUrl: "{{ URL::to('/get-auto-member-list') }}?serachkey="+ $("#member_search").val(),
			type:'GET',
			//callback just to show it's working
			onSelect: function (suggestion) {
				 $("#member_search").val(suggestion.value);
				 $("#member_number").val(suggestion.number);
			},
			showNoSuggestionNotice: true,
			noSuggestionNotice: 'Sorry, no matching results',
			onSearchComplete: function (query, suggestions) {
				if(!suggestions.length){
					$("#member_search").val('');
					$("#member_number").val('');
				}
			}
		}); 
	
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