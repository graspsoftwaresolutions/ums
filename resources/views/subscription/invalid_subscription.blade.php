@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">


@endsection
@section('headSecondSection')
<link href="{{ asset('public/assets/css/jquery-ui-month.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/css/MonthPicker.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/css/sweetalert.css') }}" rel="stylesheet" type="text/css" />
<style>
	table.highlight > tbody > tr
	{
		-webkit-transition: background-color .25s ease;
		   -moz-transition: background-color .25s ease;
			 -o-transition: background-color .25s ease;
				transition: background-color .25s ease;
	}
	table.highlight > tbody > tr:hover
	{
		background-color: rgba(242, 242, 242, .5);
	}
	.monthly-sub-status:hover,.monthly-approval-status:hover,.monthly-company-sub-status:hover,.monthly-company-approval-status:hover{
		background-color: #eeeeee !important;
		cursor:pointer;
	}
	
	.card .card-content {
		padding: 10px;
		border-radius: 0 0 2px 2px;
	}
	.file-path-wrapper{
		//display:none;
	}
	
	.btn, .btn-large, .btn-small, .btn-flat {
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
	@media print{
		
		table {
		  border-collapse: collapse !important;
		  width: 100%;
		  padding: 10px !important;
		}
		#page-length-option td, #page-length-option th,#page-length-option1 td, #page-length-option1 th {
		  border: 1px solid #ddd !important;
		}
	}
	
</style>
@endsection
@section('main-content')
<div class="row">
	<div style="height:150px !important" class="content-wrapper-before gradient-45deg-indigo-purple"></div>
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
									<h5 class="breadcrumbs-title mt-0 mb-0">{{__('Subscription List') }}</h5>
									<ol class="breadcrumbs mb-0">
										<ol class="breadcrumbs mb-0">
											<li class="breadcrumb-item"><a
													href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard') }}</a>
											</li>
											<li class="breadcrumb-item active">{{__('Subscription') }}
											</li>
									</ol>
								</div>
								<div class="col s2 m6 l6 ">
									
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
	<div class="row">
		<div class="col s12">
			<div class="container">
				<div class="card">
					<div class="card-title">
						@if ($errors->any())
							<div class="card-alert card gradient-45deg-red-pink">
								<div class="card-content white-text">
								  <p>
									<i class="material-icons">check</i> {{ __('Error') }} : {{ implode('', $errors->all(':message')) }}</p>
								</div>
								<button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
								  <span aria-hidden="true">×</span>
								</button>
							 </div>
						@endif
					</div>
					<div class="card-content">
						<div class="row">
							<div class="col s12 m12">
								<div class="row">
									<form class="formValidate" id="subscribe_formValidate" method="post" action="{{ url(app()->getLocale().'/invalidsubs') }}" enctype="multipart/form-data">
										@csrf
										<div class="row">
											
											@php 
										
												$auth_user = Auth::user();
												$companylist = [];
												$companyid = '';
												if(!empty($auth_user)){
													$userid = Auth::user()->id;
													$get_roles = Auth::user()->roles;
													$user_role = $get_roles[0]->slug;
													
													if($user_role =='union'){
														$companylist = CommonHelper::getCompanyListAll();
													}
													else if($user_role =='union-branch'){
														$unionbranchid = CommonHelper::getUnionBranchID($userid);
														$companylist = CommonHelper::getUnionCompanyList($unionbranchid);
													} 
													else if($user_role =='company'){
														$companyid = CommonHelper::getCompanyID($userid);
														$companylist = CommonHelper::getCompanyList($companyid);
													}
													else if($user_role =='company-branch'){
														$companyid = CommonHelper::getCompanyID($userid);
														$companylist = CommonHelper::getCompanyList($companyid);
													}  
													$company_count = count($companylist);
												}
												
											@endphp
											<div class="input-field col m3 s12">
												<label for="doe">{{__('Subscription Month') }}*</label>
												<input type="text" name="entry_date" id="entry_date" value="{{ date('M/Y',strtotime($data['date'])) }}" class="datepicker-custom" />
											</div>
											<div class="col m4 s12 hide">
												<label for="sub_company">{{__('Company') }}*</label>
												<select name="sub_company" id="sub_company" class="error browser-default selectpicker" data-error=".errorTxt6">
													<option value="" selected>{{__('Choose Company') }}</option>
													@foreach($companylist as $value)
													<option data-companyname="{{$value->company_name}}" value="{{$value->id}}">{{$value->company_name}}</option>
													@endforeach
												</select>
												<div class="errorTxt6"></div>
											</div>
											
											<div class="col m3 s12 " style="padding-top:5px;">
												</br>
												<button id="submit-upload" class="mb-6 btn waves-effect waves-light purple lightrn-1 form-download-btn" type="submit">{{__('Submit') }}</button>
												
											</div>
											
										</div>
										
									</form>
									
								</div>
							</div>
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col s12">  
			<ul class="tabs">  
				<li class="tab col s3"><a class="active " href="#struckoff_list" id="all">StruckOff</a></li>  
				
				<li class="tab col s3"><a class="" href="#resigned_list" id="all">Resigned</a></li>  
				
			</ul>  
		</div> 
		<div id="struckoff_list" class="col s12">
			 <div class="">
				<div class="col s12 m12">
					<div class="card subscriber-list-card animate fadeRight">
						 <div class="card-content" style="border-bottom: #2d22d6 solid 1px;">
							<h4 class="card-title mb-0">Struckoff Members List
								<span class="right">
										<a href="#" class="export-button btn btn-sm exportToExcel" style="background:#227849;"><i class="material-icons">explicit</i></a>
									<a href="#" class="export-button btn btn-sm" style="background:#ccc;" onClick="return printData()"><i class="material-icons">print</i></a>
								</span>
							</h4>
						 </div>
						<div class="card-content">
							<table id="page-length-option" style="border-collapse: collapse !important;padding: 10px;" class="display nowrap" width="100%">
								<thead>
									<tr>
										<th colspan="8" style="text-align: center;">STRUCKOFF MEMBERS</th>
									</tr> 
									<tr>
										<th colspan="4" style="text-align: left;">BANK: {{ $data['company_name'] }}</th>
										<th colspan="4" style="text-align: right;">Month: <span class=" datamonth">{{ date('M/Y',strtotime($data['date'])) }}</span></th>
									</tr> 
									<tr style="">
										<th style="border : 1px solid #988989;" width="3%">S.No</th>
										<th style="border : 1px solid #988989;" width="10%">Member Name</th>
										<th style="border : 1px solid #988989;" width="9%">Member Id</th>
										<th style="border : 1px solid #988989;" width="10%">Bank</th>
										<th style="border : 1px solid #988989;" width="10%">NRIC</th>
										<th style="border : 1px solid #988989;" width="7%">Amount</th>
										<th style="border : 1px solid #988989;" width="5%">Due</th>
										<th style="border : 1px solid #988989;" width="10%">Member Status</th>
									</tr> 
								</thead>
								<tbody>
									@php
										$slno=1;
									@endphp
									@foreach($data['struckoff_members'] as  $key => $member)
										@php
											//dd($member);
											$approval_status = $member->approval_status;
											//$approval_status = CommonHelper::get_overall_approval_status($member->sub_member_id);
											$duemonths = $member->memberid!="" ? CommonHelper::get_duemonths_monthend($member->memberid, strtotime($data['date'])) : '';
											//dd($duemonths );
											
										@endphp
										<tr style="overflow-x:auto;">
											<td style="border : 1px solid #988989;">{{$slno}}</td>
											<td style="border : 1px solid #988989;">{{ $member->up_member_name }}</td>
											<td style="border : 1px solid #988989;" id="member_code_{{ $member->sub_member_id }}" >{{ $member->member_number }}</td>
											<td style="border : 1px solid #988989;">{{ $member->company_name }}</td>
											<td style="border : 1px solid #988989;">{{ $member->up_nric }}</td>
											<td style="border : 1px solid #988989;">{{ $member->Amount }}</td>
											<td style="border : 1px solid #988989;">{{ $duemonths }}</td>
											<td style="border : 1px solid #988989;" id="member_status_{{ $member->sub_member_id }}">{{ $member->status_name }}</td>
											
											
										</tr> 
										@php
											$slno++;
										@endphp 
									@endforeach
								</tbody>
								<tfoot>
									<tr>
										<td colspan="8"> These struckoff members subscription amount could not be accepted by HQ, Please rejoin these members. </td>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>  
		<div id="resigned_list" class="col s12 ">
			
			 <div class="">
				<div class="col s12 m12">
					<div class="card subscriber-list-card animate fadeRight">
						 <div class="card-content" style="border-bottom: #2d22d6 solid 1px;">
							<h4 class="card-title mb-0">Resigned Members List
								<span class="right">
										<a href="#" class="export-button btn btn-sm exportToExcel1" style="background:#227849;"><i class="material-icons">explicit</i></a>
									<a href="#" class="export-button btn btn-sm" style="background:#ccc;" onClick="return printData()"><i class="material-icons">print</i></a>
								</span>
							</h4>
						 </div>
						<div class="card-content">
							<table id="page-length-option1" style="border-collapse: collapse !important;padding: 10px;" class="display nowrap" width="100%">
								<thead>
									<tr>
										<th colspan="8" style="text-align: center;">RESIGNED MEMBERS</th>
									</tr> 
									<tr>
										<th colspan="4" style="text-align: left;">BANK: {{ $data['company_name'] }}</th>
										<th colspan="4" style="text-align: right;">Month: <span class=" datamonth">{{ date('M/Y',strtotime($data['date'])) }}</span></th>
									</tr> 
									<tr style="">
										<th style="border : 1px solid #988989;" width="3%">S.No</th>
										<th style="border : 1px solid #988989;" width="10%">Member Name</th>
										<th style="border : 1px solid #988989;" width="9%">Member Id</th>
										<th style="border : 1px solid #988989;" width="10%">Bank</th>
										<th style="border : 1px solid #988989;" width="10%">NRIC</th>
										<th style="border : 1px solid #988989;" width="7%">Amount</th>
										<th style="border : 1px solid #988989;" width="5%">Due</th>
										<th style="border : 1px solid #988989;" width="10%">Member Status</th>
									</tr> 
								</thead>
								<tbody>
									@php
										$slno=1;
									@endphp
									@foreach($data['resigned_members'] as  $key => $member)
										@php
											//dd($member);
											$approval_status = $member->approval_status;
											//$approval_status = CommonHelper::get_overall_approval_status($member->sub_member_id);
											$duemonths = $member->memberid!="" ? CommonHelper::get_duemonths_monthend($member->memberid, strtotime($data['date'])) : '';
											//dd($duemonths );
											
										@endphp
										<tr style="overflow-x:auto;">
											<td style="border : 1px solid #988989;">{{$slno}}</td>
											<td style="border : 1px solid #988989;">{{ $member->up_member_name }}</td>
											<td style="border : 1px solid #988989;" id="member_code_{{ $member->sub_member_id }}" >{{ $member->member_number }}</td>
											<td style="border : 1px solid #988989;">{{ $member->company_name }}</td>
											<td style="border : 1px solid #988989;">{{ $member->up_nric }}</td>
											<td style="border : 1px solid #988989;">{{ $member->Amount }}</td>
											<td style="border : 1px solid #988989;">{{ $duemonths }}</td>
											<td style="border : 1px solid #988989;" id="member_status_{{ $member->sub_member_id }}">{{ $member->status_name }}</td>
											
											
										</tr> 
										@php
											$slno++;
										@endphp 
									@endforeach
								</tbody>
								<tfoot>
									<tr>
										<td colspan="8"> These resigned members subscription amount could not be accepted by HQ, Please rejoin these members. </td>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			
			</div>
		</div>  
	</div>
	
</div>

@endsection
@section('footerSection')
<script>
	var excelfilenames="Members report";
</script>
<script src="{{ asset('public/assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>

<script src="{{ asset('public/assets/vendors/noUiSlider/nouislider.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/materialize.min.js') }}"></script>
<script src="{{ asset('public/assets/js/scripts/form-elements.js') }}" type="text/javascript"></script>

@endsection
@section('footerSecondSection')
<script src="{{ asset('public/assets/js/scripts/form-validation.js')}}" type="text/javascript"></script>

 <script src="{{ asset('public/assets/js/jquery-ui-month.min.js')}}"></script>
 <script src="{{ asset('public/js/MonthPicker.min.js')}}"></script>
 <script src="{{ asset('public/js/sweetalert.min.js')}}"></script>
 <script src="{{ asset('public/excel/jquery.table2excel.js') }}"></script>

<script>
$(document).ready(function() {
    // the "href" attribute of the modal trigger must specify the modal ID that wants to be triggered
    $('.modal').modal();
});

     $(document).ready(function(){
	 $('.datepicker-custom').MonthPicker({ 
		Button: false, 
		changeYear: true,
		MonthFormat: 'M/yy',
		OnAfterChooseMonth: function() { 
			getDataStatus();
		} 
	 });
	 $('.ui-button').removeClass("ui-state-disabled");
		 //$('.datepicker-custom').MonthPicker({ Button: false,dateFormat: 'M/yy' });
       
    });
	
	$("#subscribe_formValidate").validate({
        rules: {
				entry_date:{
					required: true,
				},
				sub_company:{
					required: true,
				},
				/* file:{
					required: true,
				}, */
			 },
        //For custom messages
        messages: {
					entry_date: {
						required: "Please choose date",
						
					},
					sub_company: {
						required: "Please choose Bank",
						
					},
					/* file:{
						required: 'required',
					}, */
				},
        errorElement: 'div',
        errorPlacement: function (error, element) {
        var placement = $(element).data('error');
			if (placement) {
				$(placement).append(error)
			} else {
				error.insertAfter(element);
			}
        }
    });
	
	function getDataStatus(){
		var entry_date = $("#entry_date").val();
		var sub_company = $("#sub_company").val();
	//	$(".datamonth").text('['+entry_date+']');
		
	}
	$(document).on('change','#entry_date',function(){
		getDataStatus();
	});
	
	
	$(".monthly-sub-status").click(function() {
		//console.log($(this).data("href"));
		if($(this).attr("data-href")!=""){
			win = window.location.replace($(this).attr("data-href"));
		}
    });
	$(".monthly-approval-status").click(function() {
		if($(this).attr("data-href")!=""){
			win = window.location.replace($(this).attr("data-href"));
		}
    });
	$(".monthly-company-sub-status").click(function() {
		if($(this).attr("data-href")!=""){
			win = window.location.replace($(this).attr("data-href"));
		}
    });
	$(".monthly-company-approval-status").click(function() {
		if($(this).attr("data-href")!=""){
			win = window.location.replace($(this).attr("data-href"));
		}
    });
	$("#subscriptions_sidebars_id").addClass('active');
	$("#invalidsubs_sidebar_li_id").addClass('active');
	$("#invalidsubs_sidebar_a_id").addClass('active');
	function printData()
	{
	   var divToPrint=document.getElementById("page-length-option");
	   newWin= window.open("");
	   newWin.document.write(divToPrint.outerHTML);
	   newWin.print();
	   newWin.close();
	}
	$(document).ready( function() { 
		//$("html").css('opacity',1);

		$(".exportToExcel").click(function(e){
			$("#page-length-option").table2excel();
		});
		$(".exportToExcel1").click(function(e){
			$("#page-length-option1").table2excel();
		});
    });
</script>
@endsection