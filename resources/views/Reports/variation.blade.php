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
	@if(count($data['company_view'])<10)
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
	@endif
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
					<div class="row">                          
						<div class="col s12 m6 l3">
							<label for="month_year">{{__('Month and Year')}}</label>
							<input id="month_year" type="text" class="validate datepicker-custom" value="{{date('M/Y')}}" name="month_year">
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
				</form>  
			</div>
		</div>
		<div class="card hide">
			<div class="card-content">
				<table id="page-length-option" class="display ">
					<thead>
						<tr>
							<th>{{__('Bank Name')}}</th>
							<th>{{__('# Current')}}</th>
							<th>{{__('# Previous')}}</th>
							<th>{{__('Different')}}</th>
							<th>{{__('Unpaid')}}</th>
							<th>{{__('Paid')}}</th>
						</tr> 
					</thead>
					<tbody>
						
						
					</tbody>
				</table> 
				
			</div>
			
		</div>
		
	</div>
	<div class="row">
    <div class="col s12">
      <div class="card">
        <div class="card-content">
          <h4 class="card-title">Variation by bank Report
		  @php
			$last_month = date("Y-m-01", strtotime("first day of previous month"));
		  @endphp
          </h4>
          <div class="row">
            <div class="col s12">
              <table id="scroll-vert-hor" class="display nowrap" width="100%">
                <thead>
                  <tr>
                    	<th>{{__('Bank Name')}}</th>
						<th>{{__('# Current')}}</th>
						<th>{{__('# Previous')}}</th>
						<th>{{__('Different')}}</th>
						<th>{{__('Unpaid')}}</th>
						<th>{{__('Paid')}}</th>
                  </tr>
                </thead>
                <tbody>
						@foreach($data['company_view'] as $company)
							@php
								$current_count = CommonHelper::getMonthlyPaidCount($company->cid,date('Y-m-01'));
								$last_month_count = CommonHelper::getMonthlyPaidCount($company->cid,$last_month);
							@endphp
							<tr>
								<td>{{ $company->company_name }}</td>
								<td>{{ $current_count }}</td>
								<td>{{ $last_month_count }}</td>
								<td><span class="badge {{$current_count-$last_month_count>0 ? 'green' : 'red'}}">{{ abs($current_count-$last_month_count) }}</span></td>
								<td>{{ 0 }}</td>
								<td>{{ $current_count }}</td>
							</tr> 
						@endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
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
$("#variation_bank_sidebar_li_id").addClass('active');
$("#variation_bank_sidebar_a_id").addClass('active');

	$(document).ready(function(){
		$('#scroll-vert-hor').DataTable({
			"scrollY": 200,
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
    /* $(window).scroll(function() {   
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
	}); */
	$(document).on('submit','form#filtersubmit',function(event){
		event.preventDefault();
		var month_year = $("#month_year").val();
		var company_id = $("#company_id").val();
		if(month_year!="" || company_id!=""){
			var searchfilters = '&month_year='+month_year+'&company_id='+company_id;
			//loader.showLoader();
			$('#scroll-vert-hor tbody').empty();
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
$('#clear').click(function(){
	$('#month_year').val("");
	$('#company_id').val("");
	$(".selectpicker").val('').trigger("change"); 
});
</script>
@endsection