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
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/datepicker.css') }}">
@endsection
@section('main-content')
@php 

@endphp
<div class="row">
	<div class="col s12">
		<div class="card">
			<div class="card-content">
				<h4 class="card-title">
				
				{{__('Branch Advice Filter')}} 
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
					@csrf
					<div id="hidesearch" class="row">   
					<div class="row">    
						<div class="col s12 m6 l4 @if($user_role !='union') hide @endif">
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
						<div class="col s12 m6 l4 @if($user_role =='company-branch' || $user_role =='company') hide @endif">
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
						<div class="col s12 m6 l4 @if($user_role =='company-branch') hide @endif">
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
						<div class="clearfix"/>
						<div class="col s12 m6 l4">
							<label for="date_type">{{__('Date Type')}}</label>
							<select name="date_type" id="date_type" class="error browser-default selectpicker" autocomplete="off" data-error=".errorTxt6">
								<option value="1">Resign Date</option>
								<option value="2" selected >Payment Date</option>
							</select>
						</div>
						<div class="col s12 m6 l4">
							<label for="from_date">{{__('From Date')}}</label>
							<input id="from_date" type="text" class="validate datepicker-custom" autocomplete="off" value="{{date('01/m/Y')}}" name="from_date">
						</div>
						<div class="col s12 m6 l4">
							<label for="to_date">{{__('To Date')}}</label>
							<input id="to_date" type="text" class="validate datepicker-custom" autocomplete="off" value="{{date('t/m/Y')}}" name="to_date">
						</div>
						
						<div class="clearfix"/>

						<div class="col s12 m6 l4 hide">
							<label for="member_auto_id">{{__('Member Number')}}</label>
							<input id="member_search" type="text" class="validate " autocomplete="off" name="member_search" data-error=".errorTxt24">
							<input id="member_auto_id" type="text" class="hide" class="validate " name="member_auto_id">
							<div class="input-field">
								<div class="errorTxt24"></div>
							</div>
						</div>
						<div class="clearfix"/>
						<div class="row">
							<div class="input-field col s6 right">
								<input type="button" id="clear" style="width:130px"  class="btn" name="clear" value="{{__('Clear')}}">
							</div>
							<div class="input-field col s6 right-align">
								<input type="submit" id="search" class="btn" name="search" value="{{__('Search')}}">
							</div>
						</div>
					</div>
					</div>
				</form>  
			</div>
		</div>
	</div>
</div> 
<div class="clearfix"/>
<div class="row">
	<div class="col s12">
		<div class="row">
			<div class="col s12">
				<ul class="tabs">
					<li class="tab col m3"><a class="active" id="report1" href="#new_member_report">New Member Report</a></li>
					<li class="tab col m3"><a href="#resign_member_report" id="report2">Resign Member Report</a></li>
				</ul>
			</div>
			<div id="new_member_report" class="col s12">
				<iframe src="{{ route('advice.newmember',[app()->getLocale()]) }}" id="myframe" height="400px" width="100%"></iframe>
			</div>
			<div id="resign_member_report" class="col s12">
				<iframe src="{{ route('advice.resignmember',[app()->getLocale()]) }}" id="myresignframe" height="400px" width="100%"></iframe>
			</div>
			
		</div>
		</br>
		</br>
		
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
<script src="{{ asset('public/assets/js/datepicker.js') }}"></script>
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
$("#branch_advice_sidebar_li_id").addClass('active');
$("#branch_advice_sidebar_a_id").addClass('active');

	$(document).ready(function(){
		
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
		   $("#branch_id").append($('<option></option>').attr('value', '').text("Select"));
	   }
	    $('#member_auto_id').val('');
	    $('#member_search').val('');
	});
	$('#branch_id').change(function(){
		$('#member_auto_id').val('');
	    $('#member_search').val('');
	});
	 $("#filtersubmit").validate({
		rules: {
			from_date: {
				required: true,
			},
			to_date: {
				required: true,
			},
		},
		  //For custom messages
		  messages: {
				from_date:{
					required: "Enter From Date"
				},
				to_date:{
					required: "Enter To Date"
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
		var from_date = $("#from_date").val();
		var to_date = $("#to_date").val();
		var company_id = $("#company_id").val();
		var branch_id = $("#branch_id").val();
		var member_auto_id = $("#member_auto_id").val();
		var unionbranch_id = $("#unionbranch_id").val();
		var date_type = $("#date_type").val();
		$('#page-length-option tbody').empty();
		if(from_date!="" && to_date!=""){
			var searchfilters = '&from_date='+from_date+'&to_date='+to_date+'&company_id='+company_id+'&branch_id='+branch_id+'&member_auto_id='+member_auto_id+'&date_type='+date_type+'&unionbranch_id='+unionbranch_id;
			$("#myresignframe").attr("src", "{{ url(app()->getLocale().'/get-filter-resignedadvice-report') }}?offset=0"+searchfilters,);
			$("#myframe").attr("src", "{{ url(app()->getLocale().'/get-filter-newadvice-report') }}?offset=0"+searchfilters,);
			$("#search").attr('disabled',false);
		}else{
			alert("please choose any filter");
		}
		//$("#submit-download").prop('disabled',true);
	});
$('#clear').click(function(){
	$('#date_type').val("");
	$('#from_date').val("");
	$('#to_date').val("");
	$('#company_id').val("");
	$('#branch_id').val("");
	$('#member_search').val("");
	$(".selectpicker").val('').trigger("change"); 
});
$('.datepicker,.datepicker-custom').datepicker({
    format: 'dd/mm/yyyy',
    autoHide: true,
});

</script>
@endsection