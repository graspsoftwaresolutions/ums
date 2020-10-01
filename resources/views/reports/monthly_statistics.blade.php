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
				
				{{__('Monthly Statistics')}} 
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
						$companylist = $data['company_view'];

						$unionbranchid = CommonHelper::getUnionBranchID($userid);
						$unionbranchlist = $data['unionbranch_view'];
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
					//dd($data['branch_id']);
				@endphp
				<form method="post" id="filtersubmit" action="">                                         
					@csrf  
					<div id="hidesearch" class="row">    
					<div class="row">    
						<div class="col s12 m6 l2">
							<label for="from_month_year">{{__('From Month')}}</label>
							<input id="from_month_year" type="text" class="validate datepicker-custom" value="{{$data['from_month_year']}}" name="from_month_year">
						</div>
						<div class="col s12 m6 l2">
							<label for="to_month_year">{{__('To Month')}}</label>
							<input id="to_month_year" type="text" class="validate datepicker-custom" value="{{$data['to_month_year']}}" name="to_month_year">
						</div>
						@php 
						if($user_role =='union')
						{
						@endphp
						<div class="col s12 m6 l2 hide">
							<label>{{__('Union Branch Name') }}</label>
							<select name="unionbranch_id" id="unionbranch_id" class="error browser-default selectpicker" data-error=".errorTxt22" >
								<option  value="">{{__('Select Union Branch') }}</option>
								@foreach($unionbranchlist as $value)
								<option @if($data['unionbranch_id']==$value->id) selected @endif value="{{$value->id}}">{{$value->union_branch}}</option>
								@endforeach 
							</select>
							<div class="input-field">
								<div class="errorTxt22"></div>
							</div>
						</div>
						@php 
						}
						@endphp
						<div class="col s12 m6 l3 hide">
							<label>{{__('Company Name') }}</label>
							<select name="company_id" id="company_id" class="error browser-default selectpicker" data-error=".errorTxt22" >
								<option value="">{{__('Select Company') }}</option>
								@foreach($companylist as $value)
								<option @if($data['company']==$value->id) selected @endif value="{{$value->id}}">{{$value->company_name}}</option>
								@endforeach
							</select>
							<div class="input-field">
								<div class="errorTxt22"></div>
							</div>
						</div>
						<div class="col s12 m6 l3 hide">
							<label>{{__('Company Branch Name') }}</label>
							<select name="branch_id" id="branch_id" class="error browser-default selectpicker" data-error=".errorTxt23" >
								<option value="">{{__('Select Branch') }}</option>
								@foreach($branchlist as $branch)
								<option @if($data['branch_id']==$branch->id) selected @endif value="{{$branch->id}}">{{$branch->branch_name}}</option>
								@endforeach
							</select>
							<div class="input-field">
								<div class="errorTxt23"></div>
							</div>
						</div>
						
						<div class="input-field col l3 ">
							<br/>
							<input type="submit" id="search" class="btn" name="search" value="{{__('Search')}}">
						</div>
						
					</div>
					</div>
				</form>  
			</div>
		</div>
	</div>
</div> 
<div class="row">
	<div class="col s12">
		<div class="row">
			<div class="col s12">
				<iframe src="{{ url(app()->getLocale().'/empty_report') }}" id="myframe" height="400px" width="100%"></iframe>
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
@php $data['data_limit'] = '0'; @endphp  
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
$("#member_statistic_sidebar_li_id").addClass('active');
$("#member_statistic_sidebar_a_id").addClass('active');

	$(document).ready(function(){
		 $('.datepicker-custom').MonthPicker({ 
			Button: false, 
			MonthFormat: 'M/yy',
			OnAfterChooseMonth: function() { 
				//getDataStatus();
			} 
		 });
	});
	$('#unionbranch_id').change(function(){
	var unionbranchID = $(this).val();   
	
	if(unionbranchID){
		$.ajax({
		type:"GET",
		dataType: "json",
		url:" {{ URL::to('/get-unionbankbranch-list') }}?unionbranch_id="+unionbranchID,
		success:function(res){              
			if(res){
				$("#branch_id").empty();
				$("#branch_id").append($('<option></option>').attr('value', '').text("Select"));
				$.each(res,function(key,entry){
					console.log(res);
					$("#branch_id").append($('<option></option>').attr('value', entry.id).text(entry.branch_name));	
				});   
			}else{
			  $("#branch_id").empty();
			}
		}
		});
	}else{
		$("#branch_id").empty();
		$("#company_id").empty();
	}      
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
		   $("#branch_id").append($('<option></option>').attr('value', '').text("Select"));
	   }
	    $('#member_auto_id').val('');
	    $('#member_search').val('');
	});

	 $("#filtersubmit").validate({
		rules: {
			month_year: {
				required: true,
			},
		},
		  //For custom messages
		  messages: {
				month_year:{
					required: "Enter date"
				},
		  },
		  errorElement : 'div',
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
		$("#search").attr('disabled',true);
		var from_month_year = $("#from_month_year").val();
		var to_month_year = $("#to_month_year").val();
		var company_id = $("#company_id").val();
		var branch_id = $("#branch_id").val();
		var unionbranch_id = $('#unionbranch_id').val();
		
		$('#page-length-option tbody').empty();
		if(from_month_year!="" && to_month_year!=""){
			var searchfilters = '&from_month_year='+from_month_year+'&to_month_year='+to_month_year+'&company_id='+company_id+'&branch_id='+branch_id+'&unionbranch_id='+unionbranch_id;
			//loader.showLoader();
			//$("#memberoffset").val("{{$data['data_limit']}}"); 
			$("#myframe").contents().find("html").css('opacity',0);
			$("#myframe").attr("src", "{{ url(app()->getLocale().'/get-monthly-statstics-report') }}?offset=0"+searchfilters,);
			//$('#page-length-option tbody').empty();
			 //loader.showLoader();
		
			$("#search").attr('disabled',false);
		}else{
			alert("please choose any filter");
		}
		//$("#submit-download").prop('disabled',true);
	});

	$('#clear').click(function(){
	$('#month_year').val("");	
	$('#company_id').val("");
	$('#branch_id').val("");
	$('#member_search').val("");
	$(".selectpicker").val('').trigger("change"); 
});

</script>
@endsection