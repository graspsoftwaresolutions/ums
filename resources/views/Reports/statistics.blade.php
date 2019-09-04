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

		#main.main-full {
			height: 750px;
		}
		
		.footer {
		   position: fixed;
		   margin-top:50px;
		   left: 0;
		   bottom: 0;
		   width: 100%;
		   height:auto;
		   background-color: red;
		   color: white;
		   text-align: center;
		   z-index:999;
		} 
		.sidenav-main{
			z-index:9999;
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
				
				{{__('statistics Filter')}} 
				
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
				<form method="post" id="filtersubmit" action="{{ route('statistic_filter',app()->getLocale()) }}">
                                                              
					@csrf  
					<div class="row">    
						<div class="col s12 m6 l3">
							<label for="month_year">{{__('Month')}}</label>
							<input id="month_year" type="text" class="validate datepicker-custom" value="{{$data['month_year']}}" name="month_year">
						</div>
						@php 
						if($user_role =='union')
						{
						@endphp
						<div class="col s12 m6 l3">
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
						<div class="col s12 m6 l3">
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
						<div class="col s12 m6 l3">
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
						<div class="row">
							<div class="input-field col s6 right">
								<input type="button" class="btn" style="width:130px" id="clear" name="clear" value="{{__('clear')}}">
							</div>
							<div class="input-field col s6 right-align">
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
	
		<div class="card">
		
			<div class="card-content">
				<table id="page-length-option" class="display" width="100%">
					<thead>
						<tr>
						  <td colspan="12">&nbsp;&nbsp;&nbsp;Benifit</td>
						  <td colspan="11">Non Benifit</td>
						  <td>&nbsp;</td>
						 </tr>
						<tr>
							<th >{{__('Branch Code')}}</th>
							@foreach($data['race_view'] as $values)
								<th >M{{$values->race_name[0]}}</th>
							@endforeach
							<th >{{__('S.Total')}}</th>
							@foreach($data['race_view'] as $values)
								<th >F{{$values->race_name[0]}}</th>
							@endforeach
							<th >{{__('S.Total')}}</th>
							<th >{{__('Total')}}</th>
							@foreach($data['race_view'] as $values)
								<th >M{{$values->race_name[0]}}</th>
							@endforeach
							<th >{{__('S.Total')}}</th>
							@foreach($data['race_view'] as $values)
								<th >F{{$values->race_name[0]}}</th>
							@endforeach
							<th >{{__('S.Total')}}</th>
							<th >{{__('Total')}}</th>
							<th>{{__('G.Total')}}</th>
						</tr> 
					</thead>
					<tbody>
                        @foreach($data['member_count'] as $values)
							<tr>
								<td>{{$values->branch_shortcode}}</td>
								@php
							 	$month_year = $data['month_year'];
								
								$subtotal1 = '0';
								$subtotal2 = '0';
								$subtotaldefaulter2 = '0';
								$defaultertotal = '0';
								 $total = '0';
								 $subtotaldefaulter1 = '0';
								 $status_active = 'ACTIVE';
								 $status_defaulter = 'DEFAULTER';
								 $grandtotal = '0';
								@endphp
                                @foreach($data['race_view'] as $value)
                                @php $race_id = $value->id;
                                 $male_count = CommonHelper::get_male_gender_race_count($race_id,$values->branch_shortcode,$status_active,$month_year)
                                  @endphp
                                    <td>{{$male_count}}</td>
								@php
								 $subtotal1 += $male_count; 
								 @endphp
                                @endforeach
                                <td> {{$subtotal1}}</td>
								@foreach($data['race_view'] as $value)
                                @php $race_id = $value->id;
                                 $female_count = CommonHelper::get_female_gender_race_count($race_id,$values->branch_shortcode,$status_active,$month_year)
                                  @endphp
                                    <td>{{$female_count}}</td>
									@php
								 $subtotal2 += $female_count; 
								 $total = $subtotal1 + $subtotal2; 
								 @endphp
								@endforeach
                                <td> {{$subtotal2}}</td>
                                <td>{{$total}}</td>
	
                                @foreach($data['race_view'] as $value)
                                @php $race_id = $value->id;
                                 $maledefaulter_count = CommonHelper::get_male_gender_race_count_defaulter($race_id,$values->branch_shortcode,$status_defaulter,$month_year)
                                  @endphp
                                    <td>{{$maledefaulter_count}}</td>
								@php
								 $subtotaldefaulter1 += $maledefaulter_count; 
								 @endphp
                                @endforeach
                                <td> {{$subtotaldefaulter1}}</td>
                                @foreach($data['race_view'] as $value)
                                @php $race_id = $value->id;
                                 $femaledefaulter_count = CommonHelper::get_female_gender_race_count_defaulter($race_id,$values->branch_shortcode,$status_defaulter,$month_year)
                                  @endphp
                                    <td>{{$femaledefaulter_count}}</td>
									@php
								 $subtotaldefaulter2 += $femaledefaulter_count; 
								 $defaultertotal = $subtotaldefaulter1 + $subtotaldefaulter2; 
								 $grandtotal = $defaultertotal + $total;
								 @endphp
								@endforeach
                                <td>{{$subtotaldefaulter2}}</td>
                                <td>{{$defaultertotal}}</td>
                                <td>{{$grandtotal}}</td>
							</tr> 
                         @endforeach
					</tbody>
				</table> 
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
	  
	  
	// 	 $(document).on('submit','form#filtersubmit',function(event){
	// 	event.preventDefault();
	// 	$("#search").attr('disabled',true);
	// 	var month_year = $("#month_year").val();
	// 	var company_id = $("#company_id").val();
	// 	var branch_id = $("#branch_id").val();
	// 	var unionbranch_id = $('#unionbranch_id').val();
		
	// 	$('#page-length-option tbody').empty();
	// 	if(month_year!=""){
	// 		var searchfilters = '&month_year='+month_year+'&company_id='+company_id+'&branch_id='+branch_id+'&unionbranch_id='+unionbranch_id;
	// 		//loader.showLoader();
	// 		$('#page-length-option tbody').empty();
	// 		loader.showLoader();
	// 		$.ajax({
	// 			type: "GET",
	// 			dataType: "json",
	// 			url : "{{ URL::to('/en/get-statistics-more-report') }}?offset=0"+searchfilters,
	// 			success:function(res){
	// 				if(res)
	// 				{
	// 					$.each(res,function(key,entry){
	// 						var table_row = "<tr><td>"+entry.short_code+"</td>";
	// 						// var table_row = "<tr><td>"+entry.short_code+"</td>";
	// 						// var table_row = "<tr><td>"+entry.short_code+"</td>";
	// 						// var table_row = "<tr><td>"+entry.short_code+"</td>";
	// 						// var table_row = "<tr><td>"+entry.short_code+"</td>";
	// 						// var table_row = "<tr><td>"+entry.short_code+"</td>";
	// 						// var table_row = "<tr><td>"+entry.short_code+"</td>";
	// 						// var table_row = "<tr><td>"+entry.short_code+"</td>";
	// 						// var table_row = "<tr><td>"+entry.short_code+"</td>";
	// 						// var table_row = "<tr><td>"+entry.short_code+"</td>";
	// 						// var table_row = "<tr><td>"+entry.short_code+"</td>";
	// 						// var table_row = "<tr><td>"+entry.short_code+"</td>";
	// 						// var table_row = "<tr><td>"+entry.short_code+"</td>";
	// 						// var table_row = "<tr><td>"+entry.short_code+"</td>";
	// 						// var table_row = "<tr><td>"+entry.short_code+"</td>";
	// 						// var table_row = "<tr><td>"+entry.short_code+"</td>";
	// 						// var table_row = "<tr><td>"+entry.short_code+"</td>";
	// 						// var table_row = "<tr><td>"+entry.short_code+"</td>";
	// 						// var table_row = "<tr><td>"+entry.short_code+"</td>";
	// 						// var table_row = "<tr><td>"+entry.short_code+"</td>";
	// 						// var table_row = "<tr><td>"+entry.short_code+"</td>";
	// 						// var table_row = "<tr><td>"+entry.short_code+"</td>";
							
								
	// 						$('#page-length-option tbody').append(table_row);
	// 					});
	// 					loader.hideLoader();
	// 					$("#search").attr('disabled',false);
	// 				}else{
						
	// 				}
	// 			}
	// 		});
	// 		//$("#search").attr('disabled',false);
	// 	}else{
	// 		alert("please choose any filter");
	// 	}
	// 	//$("#submit-download").prop('disabled',true);
	// });   

	$('#clear').click(function(){
	$('#month_year').val("");	
	$('#company_id').val("");
	$('#branch_id').val("");
	$('#member_search').val("");
	$(".selectpicker").val('').trigger("change"); 
});

</script>
@endsection