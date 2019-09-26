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
<link href="{{ asset('public/assets/css/jquery-ui-month.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/css/MonthPicker.min.css') }}" rel="stylesheet" type="text/css" />
<style>
	
	
	.monthly-sub-status:hover{
		background-color: #eeeeee !important;
		cursor:pointer;
	}
	.btn, .btn-sm-one {
		line-height: 36px;
		display: inline-block;
		height: 35px;
		padding: 0 7px;
		vertical-align: middle;
		text-transform: uppercase;
		border: none;
		border-radius: 4px;
		-webkit-tap-highlight-color: transparent;
	}
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
				
				{{__('Due Report')}} 
				<a href="#" class="export-button btn btn-sm-one" onClick="$('#hidesearch').toggle();" style="background:#ff26ff;"><i class="material-icons">indeterminate_check_box</i></a>
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
						$companylist = $data['company_list'];
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
				<form method="post" id="filtersubmit" action="">
				<div id="hidesearch" class="row">    
					@csrf  
					<div class="row">
						<div class="col s12 m6 l3">
							<label for="month_year">{{__('Month and Year')}}</label>
							<input id="month_year" type="text" class="validate datepicker-custom" value="{{date('M/Y')}}" name="month_year">
						</div>        
						<div class="col s12 m6 l3 @if($user_role !='union') hide @endif">
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
						
						<div class="col s12 m6 l3">
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
						<div class="clearfix"></div>
						<div class="row">
							<div class="input-field col s6 right">
								<input type="button" id="clear" style="width:130px"  class="btn" name="clear" value="{{__('Clear')}}">
							</div>
							<div class="input-field col s6 right-align">
								<input type="submit" id="search"  class="btn" name="search" value="{{__('Search')}}">
							</div>
						</div>
					</div>
					</div>
				</form>  
			</div>
		</div>
		
	</div>
	<div class="row">
    <div class="col s12">
	<iframe src="{{ route('reports.due_iframe',[app()->getLocale()]) }}" id="myframe" height="400px" width="100%"></iframe>
    
	  </br>
		</br>
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


<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/assets/js/materialize.min.js') }}"></script>
<script src="{{ asset('public/assets/js/scripts/form-elements.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jquery.autocomplete.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jquery-ui-month.min.js')}}"></script>
<script src="{{ asset('public/js/MonthPicker.min.js')}}"></script>
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
$("#subscription_bank_sidebar_li_id").addClass('active');
$("#subscription_bank_sidebar_a_id").addClass('active');

	$(document).ready(function(){
		$('#scroll-vert-hor').DataTable({
			"scrollX": true,
			"searching": false,
			"paging":   false,
			"info":     false,
		  })
		 $('.datepicker-custom').MonthPicker({ 
			Button: false, 
			MonthFormat: 'M/yy',
			OnAfterChooseMonth: function() { 
				//getDataStatus();
			} 
		 });
		
	
	});
	
		$('#filtersubmit').validate({
			rules: {
				month_year: {
					  required: true,
				},
				 
			},
			//For custom messages
			messages: {
				month_year: {
					  required: '{{__("Please Select Month And Year") }}',
				}, 
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
    
	$(document).on('submit','form#filtersubmit',function(event){
		event.preventDefault();
		var month_year = $("#month_year").val();
		var company_id = $("#company_id").val();
		var unionbranch_id = $("#unionbranch_id").val();
		if(month_year!=""){
			var searchfilters = '&month_year='+month_year+'&company_id='+company_id+'&unionbranch_id='+unionbranch_id;
			$("#myframe").attr("src", "{{ url(app()->getLocale().'/get-due-report') }}?offset=0"+searchfilters,);
			// $('#scroll-vert-hor tbody').empty();
			// //loader.showLoader();
			// $.ajax({
			// 	type: "GET",
			// 	dataType: "json",
			// 	url : "{{ URL::to('/en/get-subscription-report') }}?offset=0"+searchfilters,
			// 	success:function(result){
			// 		if(result)
			// 		{
			// 			res = result.company_view;
			// 			$.each(res,function(key,entry){
			// 				var new_member_sub_link =base_url+"/{{app()->getLocale()}}/sub-company-members/"+entry.enc_id;
			// 				var table_row = "<tr class='monthly-sub-status' data-href='"+new_member_sub_link+"'><td>"+entry.company_name+"</td>";
			// 					table_row += "<td>"+entry.total_members+"</td>";
			// 					table_row += "<td>"+entry.total_amount+"</td>";
			// 					table_row += "<td>"+entry.active_amt+"</td>";
			// 					table_row += "<td>"+entry.default_amt+"</td>";
			// 					table_row += "<td>"+entry.struckoff_amt+"</td>";
			// 					table_row += "<td>"+entry.resign_amt+"</td>";
			// 					table_row += "<td>"+entry.sundry_amt+"</td></tr>";
			// 					$('#scroll-vert-hor tbody').append(table_row);
			// 			});
			// 			if(!res){
			// 					var table_row = "<tr><td colspan='6'>No data found</td></tr>";
			// 					$('#scroll-vert-hor tbody').append(table_row);
			// 			}
			// 			loader.hideLoader();
			// 		}else{
						
			// 		}
			// 	}
			// });
		}else{
			alert("Please Choose Month & Year");
		}
		//$("#submit-download").prop('disabled',true);
	});
$('#clear').click(function(){
	$('#month_year').val("");
	$('#company_id').val("");
	$(".selectpicker").val('').trigger("change"); 
});
$(document.body).on('click', '.monthly-sub-status' ,function(){
	if($(this).attr("data-href")!=""){
		win = window.open($(this).attr("data-href"), '_blank');
	}
});
</script>
@endsection