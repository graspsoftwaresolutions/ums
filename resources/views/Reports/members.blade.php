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
			<h4 class="card-title">
			
			{{__('Members Filter')}} 
			[
				@if($data['status_id']!=0)
				{{ CommonHelper::getStatusName($data['status_id']) }}
				@else
					New members
				@endif
			]
			</h4> 
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
			<form method="post" id="filtersubmit" name="">
				@csrf  
				<div class="row">                          
					<div class="col s3">
						<label for="month_year">{{__('Month and Year')}}</label>
						<input id="month_year" type="text" class="validate datepicker-custom" value="@if($data['status_id']==0){{date('M/Y')}}@endif" name="month_year">
					</div>
					<div class="col s3">
						<label>{{__('Company Name') }}</label>
						<select name="company_id" id="company_id" class="error browser-default selectpicker" data-error=".errorTxt22" >
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
						<label>{{__('Company Branch Name') }}</label>
						<select name="branch_id" id="branch_id" class="error browser-default selectpicker" data-error=".errorTxt23" >
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
						<label for="member_auto_id">{{__('Member Number')}}</label>
						<input id="member_search" type="text" class="validate " name="member_search" data-error=".errorTxt24">
						<input id="member_auto_id" type="text" class="hide" class="validate " name="member_auto_id">
						<input id="member_status" type="text" class="hide" readonly class="validate " value="{{$data['status_id']}}" name="member_status">
						<div class="input-field">
							<div class="errorTxt24"></div>
						</div>
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
						<th>{{__('Member Name')}}</th>
						<th>{{__('Member Number')}}</th>
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
@php	
$ajaxcompanyid = '';
$ajaxbranchid = '';
$ajaxunionbranchid = '';
if(!empty(Auth::user())){
	$userid = Auth::user()->id;
	
	if($user_role =='union'){

	}else if($user_role =='union-branch'){
		$ajaxunionbranchid = CommonHelper::getUnionBranchID($userid);
	}else if($user_role =='company'){
		$ajaxcompanyid = CommonHelper::getCompanyID($userid);
	}else if($user_role =='company-branch'){
		$ajaxbranchid = CommonHelper::getCompanyBranchID($userid);
	}else{

	}
}
@endphp
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
$("#reports_sidebars_id").addClass('active');
$("#member_status{{strtolower($data['status_id'])}}_sidebar_li_id").addClass('active');
$("#member_status{{strtolower($data['status_id'])}}_sidebar_a_id").addClass('active');

$(document).ready(function(){
	$(".datepicker-custom").datepicker({
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		autoClose: true,
		weekdaysAbbrev: ['sun'],
		format: "mmm/yyyy",
	});
	$("#member_search").devbridgeAutocomplete({
		//lookup: countries,
		serviceUrl: "{{ URL::to('/get-company-member-list') }}?serachkey="+ $("#member_search").val(),
		params: { 
					company_id:  function(){ return $("#company_id").val();  },
					branch_id:  function(){ return $("#branch_id").val();  } 
				},
		type:'GET',
		//callback just to show it's working
		onSelect: function (suggestion) {
			 $("#member_search").val(suggestion.member_code);
			 $("#member_auto_id").val(suggestion.number);
		},
		showNoSuggestionNotice: true,
		noSuggestionNotice: 'Sorry, no matching results',
		onSearchComplete: function (query, suggestions) {
			if(!suggestions.length){
				$("#member_search").val('');
				$("#member_auto_id").val('');
			}
		}
	}); 
	$(document.body).on('click', '.autocomplete-no-suggestion' ,function(){
		$("#member_search").val('');
	});

});
$('#company_id').change(function(){
   var CompanyID = $(this).val();
   var ajaxunionbranchid = '{{ $ajaxunionbranchid }}';
   var ajaxbranchid = '{{ $ajaxbranchid }}';
   var additional_cond;
   if(CompanyID!='' && CompanyID!='undefined')
   {
	 additional_cond = '&unionbranch_id='+ajaxunionbranchid+'&branch_id='+ajaxbranchid;
	 $.ajax({
		type: "GET",
		dataType: "json",
		url : "{{ URL::to('/get-branch-list-register') }}?company_id="+CompanyID+additional_cond,
		success:function(res){
			if(res)
			{
				$('#branch_id').empty();
				$("#branch_id").append($('<option></option>').attr('value', '').text("Select"));
				$.each(res,function(key,entry){
					$('#branch_id').append($('<option></option>').attr('value',entry.id).text(entry.branch_name)); 
				});
			}else{
				$('#branch_id').empty();
			}
		}
	 });
   }else{
	   $('#branch_id').empty();
   }
	$('#member_auto_id').val('');
	$('#member_search').val('');
});
	$('#filtersubmit').validate({
		rules: {
			/* month_year: {
				  required: true,
			  },
			  company_id: {
				  required: true,
			  },
			  branch_id: {
				required: true,
			  },
			  member_search: {
				required: true,
			  }, */
		},
		//For custom messages
		messages: {
			/* month_year: {
				  required: '{{__("Please Select Month And Year") }}',
			 }, */
			/*   company_id: {
				  required: '{{__("Please Select Company ID") }}',
			  },
			  branch_id: {
				required: '{{__("Please Select Branch ID") }}',
			  },
			  member_search: {
				required: '{{__("Please Enter Member") }}',
			  }, */
		},
		errorElement: 'div',
		errorPlacement: function(error, element) {
		  var placement = $(element).data('error');
		  if (placement) {
			  $(placement).append(error)
		  } else {
			  error.insertAfter(element);
		  }
		}
	});
$(window).scroll(function() {   
   var lastoffset = $("#memberoffset").val();
   var limit = "{{$data['data_limit']}}";
   if($(window).scrollTop() + $(window).height() == $(document).height()) {
		var month_year = $("#month_year").val();
		var company_id = $("#company_id").val();
		var branch_id = $("#branch_id").val();
		var member_auto_id = $("#member_auto_id").val();
		var status_id = $("#member_status").val();
		var searchfilters = '&month_year='+month_year+'&company_id='+company_id+'&branch_id='+branch_id+'&member_auto_id='+member_auto_id+'&status_id='+status_id;
		$("#memberoffset").val(parseInt(lastoffset)+parseInt(limit));
		$.ajax({
			type: "GET",
			dataType: "json",
			url : "{{ URL::to('/en/get-members-report') }}?offset="+lastoffset+searchfilters,
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
$(document).on('submit','form#filtersubmit',function(event){
	event.preventDefault();
	var month_year = $("#month_year").val();
	var company_id = $("#company_id").val();
	var branch_id = $("#branch_id").val();
	var member_auto_id = $("#member_auto_id").val();
	var status_id = $("#member_status").val();
	if(month_year!="" || company_id!="" || branch_id!="" || member_auto_id!=""){
		var searchfilters = '&month_year='+month_year+'&company_id='+company_id+'&branch_id='+branch_id+'&member_auto_id='+member_auto_id+'&status_id='+status_id;
		$("#memberoffset").val(0);
		//loader.showLoader();
		$('#page-length-option tbody').empty();
		loader.showLoader();
		$.ajax({
			type: "GET",
			dataType: "json",
			url : "{{ URL::to('/en/get-members-report') }}?offset=0"+searchfilters,
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
					loader.hideLoader();
				}else{
					
				}
			}
		});
	}else{
		alert("please choose any filter");
	}
	//$("#submit-download").prop('disabled',true);
});

</script>
@endsection