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
	.file-field .btn, .file-field .btn-large, .file-field .btn-small {
		margin-top:10px;
		line-height: 2.4rem;
		float: left;
		height: 2.4rem;
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
									<a class="btn waves-effect waves-light cyan orange breadcrumbs-btn hide" href="{{ route('subscription.monthend', app()->getLocale())  }}">{{__('Insert Monthend')}}</a>
									<a class="btn waves-effect waves-light cyan breadcrumbs-btn right " href="{{ route('subscription.download', app()->getLocale())  }}">{{__('Download Sample')}}</a>
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
									<form class="formValidate" id="subscribe_formValidate" method="post" action="{{ url(app()->getLocale().'/subscribe_download') }}" enctype="multipart/form-data">
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
												<input type="text" name="entry_date" id="entry_date" value="{{ date('M/Y') }}" class="datepicker-custom" />
											</div>
											<div class="col m4 s12">
												<label for="sub_company">{{__('Company') }}*</label>
												<select name="sub_company" id="sub_company" class="error browser-default selectpicker" data-error=".errorTxt6">
													<option value="" selected>{{__('Choose Company') }}</option>
													@foreach($companylist as $value)
													<option data-companyname="{{$value->company_name}}" value="{{$value->id}}">{{$value->company_name}}</option>
													@endforeach
												</select>
												<div class="errorTxt6"></div>
											</div>
											<div class="col m2 s12 hide" >
												<label for="type">{{__('Type') }}*</label>
												 <select id="type" name="type"
												  class="error browser-default common-select add-select" onChange="return FileUploadEnable(this.value)">
													<option value="0">{{__('Download Empty File') }}</option>
													<option value="1" selected>{{__('Upload File') }}</option>
												 </select>
											</div>
											<div id="file-upload-div" class="input-field  file-field col m2 s12">
												<div class="btn ">
													<span>File</span>
													<input type="file" name="file" class="form-control btn"  accept=".xls,.xlsx">
												</div>
												<div class="file-path-wrapper ">
													<input class="file-path validate" type="text">
												</div>
											</div>
											<div class="col m3 s12 " style="padding-top:5px;">
												</br>
												<button id="submit-upload" class="mb-6 btn waves-effect waves-light purple lightrn-1 form-download-btn" type="button">{{__('Submit') }}</button>
												
											</div>
											
										</div>
										<div class="row hide">
											<div class="col s7">
												
											</div>
											<div class="col s4 ">
												
												<button id="submit-download" class="waves-effect waves-light cyan btn btn-primary form-download-btn hide" type="button">{{__('Download Sample') }}</button>
												
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
				<li class="tab col s3"><a class="active " href="#monthly_status" id="all">Monthly Status</a></li>  
				@if($user_role!='company' && $user_role!='company-branch')
				<li class="tab col s3"><a class="" href="#company_status" id="all">Bankwise Monthly Status</a></li>  
				@endif
			</ul>  
		</div> 
		<div id="monthly_status" class="col s12">
			 <div class="">
				<div class="col s12 m6">
					<div class="card subscriber-list-card animate fadeRight">
						 <div class="card-content" style="border-bottom: #2d22d6 solid 1px;">
							<h4 class="card-title mb-0">{{__('Member Status') }} 
								<span class="right datamonth">[{{ date('M/Y') }}]</span>
							</h4>
						 </div>
						 <table class="subscription-table responsive-table highlight">
							<thead>
							   <tr style="background: -webkit-linear-gradient(45deg, #37459e, #7e27a2);color:#fff;">
								  <th>Sl No</th>
								  <th>Status</th>
								  <th>Count</th>
								  <th>Amount</th>
							   </tr>
							</thead>
							<tbody>
								@php 
									$get_roles = Auth::user()->roles;
									$user_role = $get_roles[0]->slug;
									$user_id = Auth::user()->id;
									$total_members_count = 0;
									$total_members_amount = 0;
									$slno =1;
								@endphp 
								@foreach($data['member_stat'] as  $key => $stat)
								@php
									$member_sub_link = URL::to(app()->getLocale().'/subscription-status?member_status='.$stat->id.'&date='.strtotime('now'));
									$member_status_count = CommonHelper::statusSubsMembersNotDojCount($stat->id, $user_role, $user_id);
									$member_status_amount = CommonHelper::statusMembersNotDojAmount($stat->id, $user_role, $user_id);
									//$member_status_amount = round(CommonHelper::statusMembersAmount($stat->id, $user_role, $user_id), 0);
									$hidestruckoff = '';
									if(($user_role=='company' || $user_role=='company-branch') && $stat->id >= 3){
										$hidestruckoff = 'hide';
									}
								@endphp
								
								<tr class="monthly-sub-status {{$hidestruckoff}}" id="monthly_member_status_{{ $stat->id }}" data-href="{{ $member_sub_link }}" style="cursor:pointer;color:{{ $stat->font_color }};">
									<td>{{ $slno }} </td>
									<td>{{ $stat->status_name }}</td>
									<td id="member_status_count_{{ $stat->id }}"> {{ $member_status_count }}</td>
									<td id="member_status_amount_{{ $stat->id }}">{{ number_format($member_status_amount,2,".",",") }} </td>
								</tr>
								@php
									if($hidestruckoff==''){
										$total_members_count += $member_status_count;
										$total_members_amount += $member_status_amount;
										$slno++;
									}
									
								@endphp
								@endforeach
								@php
									$total_sundry_count = CommonHelper::statusSubsMatchCount(2, $user_role, $user_id);
									$total_sundry_amount = CommonHelper::statusSubsMatchAmount(2, $user_role, $user_id);
								@endphp
								<tr class="monthly-sub-status" id="monthly_member_status_0" data-href="{{ URL::to(app()->getLocale().'/subscription-status?member_status=0&date='.strtotime('now')) }}" style="cursor:pointer;">
									<td>{{ $slno }} </td>
									<td>SUNDRY CREDITORS</td>
									<td id="member_status_count_sundry">{{ $total_sundry_count }}</td>
									<td id="member_status_amount_sundry">{{ number_format($total_sundry_amount,2,".",",") }} </td>
								</tr>
								@php
									$total_members_count += $total_sundry_count;
									$total_members_amount += $total_sundry_amount;
								@endphp
							</tbody>
							<tfoot>
								<tr class="monthly-sub-status" id="monthly_member_status_all" data-href="{{ URL::to(app()->getLocale().'/subscription-status?member_status=all&date='.strtotime('now')) }}" style="cursor:pointer;background: #dbdbf7;font-weight:bold;">
									<td colspan="2">Total</td>
									<td id="member_status_count_total">{{ $total_members_count }}</td>
									<td id="member_status_amount_total"> {{ number_format($total_members_amount,2,".",",") }}</td>
								</tr>
							</tfoot>
						 </table>
					</div>
				</div>
				<!--Approval Status-->
				<div class="col s12 m6">
					<div class="card subscriber-list-card animate fadeRight">
						 <div class="card-content" style="border-bottom: #2d22d6 solid 1px;">
							<h4 class="card-title mb-0">{{__('Approval Status') }} 
								<span class="right datamonth">[{{ date('M/Y') }}]</span>
							</h4>
						 </div>
						 <table class="subscription-table responsive-table highlight">
							<thead>
							   <tr style="background: -webkit-linear-gradient(45deg, #37459e, #7e27a2);color:#fff;">
								  <th>Sl No</th>
								  <th>Description</th>
								  <th>Count</th>
								  <th>Approved</th>
								  <th>Pending</th>
							   </tr>
							</thead>
							<tbody>
								@php 
									$total_match_members_count = 0;
									$total_match_approval_members_count = 0;
									$total_match_pending_members_count = 0;
									$slno1 = 1;
								@endphp 
								@foreach($data['approval_status'] as  $key => $stat)
								@php
									$match_members_count = CommonHelper::statusSubsMatchNotDojCount($stat->id, $user_role, $user_id);
									$match_approval_members_count = CommonHelper::statusSubsMatchNotApprovalCount($stat->id, $user_role, $user_id,1);
									$match_pending_members_count = CommonHelper::statusSubsMatchNotApprovalCount($stat->id, $user_role, $user_id,0);
									$hidestruckoffone = '';
									if(($user_role=='company' || $user_role=='company-branch') && ($stat->id == 6 || $stat->id == 7)){
										$hidestruckoffone = 'hide';
									}
								@endphp
								<tr class="monthly-approval-status {{ $hidestruckoffone }}" id="monthly_approval_status_{{ $stat->id }}" data-href="{{ URL::to(app()->getLocale().'/subscription-status?approval_status='.$stat->id.'&date='.strtotime('now')) }}" style="cursor:pointer;">
									<td>{{ $slno1 }} </td>
									<td>{{ $stat->match_name }}</td>
									<td id="approval_status_count_{{ $stat->id }}">{{ $match_members_count }}</td>
									<td id="approval_approved_count_{{ $stat->id }}">{{ $match_approval_members_count }}</td>
									<td id="approval_pending_count_{{ $stat->id }}">{{ $match_pending_members_count }}</td>
								</tr>
								@php
									if($hidestruckoffone==''){
										$total_match_members_count += $match_members_count;
										$total_match_approval_members_count += $match_approval_members_count;
										$total_match_pending_members_count += $match_pending_members_count;	
										$slno1++;			
									}
									
								@endphp
								@endforeach
							</tbody>
							<tfoot>
							<!--	<tr class="monthly-approval-status" id="monthly_approval_status_all" data-href="{{ URL::to(app()->getLocale().'/subscription-status?approval_status=all&date='.strtotime('now')) }}" style="cursor:pointer;background: #dbdbf7;font-weight:bold;">
									<td colspan="2">Total</td>
									<td id="approval_status_count_total">{{ $total_match_members_count }}</td>
									<td id="approval_approved_count_total">{{ $total_match_approval_members_count }}</td>
									<td id="approval_pending_count_total">{{ $total_match_pending_members_count }}</td>
								</tr>-->
							</tfoot>
						 </table>
					</div>
				</div>
				
			</div>
		</div>  
		<div id="company_status" class="col s12 @if($user_role=='company' || $user_role=='company-branch') hide @endif">
			<div class="col s12 m12">
				<h5 id="bankname-listing" class="center hide"><span class="badge green" style="float:none;padding: 5px;">Bank Name : <span class="subscription-bankname">---</span></span></h5>
			</div>
			 <div class="">
				<div class="col s12 m6">
					<div class="card subscriber-list-card animate fadeRight">
						 <div class="card-content" style="border-bottom: #2d22d6 solid 1px;">
							<h4 class="card-title mb-0">{{__('Member Status') }} 
								<span class="right datamonth">[{{ date('M/Y') }}]</span>
							</h4>
						 </div>
						 <table class="subscription-table responsive-table highlight">
							<thead>
							   <tr style="background: -webkit-linear-gradient(45deg, #37459e, #7e27a2);color:#fff;">
								  <th>Sl No</th>
								  <th>Status</th>
								  <th>Count</th>
								  <th>Amount</th>
							   </tr>
							</thead>
							<tbody>
								@php 
									$get_roles = Auth::user()->roles;
									$user_role = $get_roles[0]->slug;
									$user_id = Auth::user()->id;
								@endphp 
								@foreach($data['member_stat'] as  $key => $stat)
								<tr id="monthly_company_sub_status_{{ $stat->id }}" class="monthly-company-sub-status" data-href="" style="cursor:pointer;color:{{ $stat->font_color }};">
									<td>{{ $key+1 }} </td>
									<td>{{ $stat->status_name }}</td>
									<td class="clear-approval" id="company_member_status_count_{{ $stat->id }}">0</td>
									<td class="clear-approval" id="company_member_status_amount_{{ $stat->id }}">0 </td>
								</tr>
								@endforeach
								<tr id="monthly_company_sub_status_0" class="monthly-company-sub-status" data-href="" style="cursor:pointer;">
									<td>{{ count($data['member_stat'])+1 }} </td>
									<td>SUNDRY CREDITORS</td>
									<td class="clear-approval" id="company_member_status_count_sundry">0</td>
									<td class="clear-approval" id="company_member_status_amount_sundry">0 </td>
								</tr>
							</tbody>
							<tfoot>
								<tr class="monthly-company-sub-status" id="monthly_company_sub_status_all" data-href="" style="cursor:pointer;background: #dbdbf7;font-weight:bold;">
									<td colspan="2">Total</td>
									<td id="company_member_status_count_total">0</td>
									<td id="company_member_status_amount_total">0</td>
								</tr>
							</tfoot>
						 </table>
					</div>
					
				</div>
				<!--Approval Status-->
				<div class="col s12 m6">
					<div class="card subscriber-list-card animate fadeRight">
						 <div class="card-content" style="border-bottom: #2d22d6 solid 1px;">
							<h4 class="card-title mb-0">{{__('Approval Status') }} 
								<span class="right datamonth">[{{ date('M/Y') }}]</span>
							</h4>
						 </div>
						 <table class="subscription-table responsive-table highlight">
							<thead>
							   <tr style="background: -webkit-linear-gradient(45deg, #37459e, #7e27a2);color:#fff;">
								  <th>Sl No</th>
								  <th>Description</th>
								  <th>Count</th>
								  <th>Approved</th>
								  <th>Pending</th>
							   </tr>
							</thead>
							<tbody>
								@php 
								//isset($data['approval_status']) ? $data['approval_status'] : "";                   
								@endphp 
								@foreach($data['approval_status'] as  $key => $stat)
								<tr id="monthly_company_approval_status_{{ $stat->id }}" class="monthly-company-approval-status" data-href="">
									<td>{{ $key+1 }} </td>
									<td>{{ $stat->match_name }}</td>
									<td class="clear-approval" id="company_approval_status_count_{{ $stat->id }}">0</td>
									<td class="clear-approval" id="company_approval_approved_count_{{ $stat->id }}">0</td>
									<td class="clear-approval" id="company_approval_pending_count_{{ $stat->id }}">0</td>
								</tr>
								@endforeach
							</tbody>
							<tfoot>
								<!-- <tr class="monthly-company-approval-status" id="monthly_company_approval_status_all" data-href="" style="cursor:pointer;background: #dbdbf7;font-weight:bold;">
									<td colspan="2">Total</td>
									<td id="company_approval_status_count_total">0</td>
									<td id="company_approval_approved_count_total">0</td>
									<td id="company_approval_pending_count_total">0</td>
								</tr> -->
							</tfoot>
						 </table>
					</div>
					
				</div>
				
			</div>
		</div>  
	</div>
	
</div>

@endsection
@section('footerSection')
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
	
	function FileUploadEnable(type){
		if(type == 1){
			$("#file-upload-div").removeClass('hide');
		}else{
			$("#file-upload-div").addClass('hide');
		}
	}
	function getDataStatus(){
		var entry_date = $("#entry_date").val();
		var sub_company = $("#sub_company").val();
		$(".datamonth").text('['+entry_date+']');
		if(entry_date!="" && sub_company!=""){
			var selected = $("#sub_company").find('option:selected');
			var company_name = selected.data('companyname'); 
			$("#bankname-listing").removeClass('hide');
			$(".subscription-bankname").text(company_name);
			loader.showLoader();
			$("#type option[value='2']").remove();
			var url = "{{ url(app()->getLocale().'/check-subscription-exists') }}" + '?entry_date=' + entry_date + "&sub_company=" + sub_company;
			$.ajax({
				url: url,
				type: "GET",
				success: function(result) {
					loader.hideLoader();
					if(result.status==1 || result.status==2){
						if(result.status==1){
							swal({
								title: "Data Already Exists!",
								text: "Are you sure you want to download existance data?",
								icon: 'success',
								dangerMode: true,
								buttons: {
								  cancel: 'No, Please!',
								  delete: 'Yes, Download It'
								}
							  }).then(function (willDelete) {
								if (willDelete) {
								  DownloadExistance(1);
								} else {
								  DownloadExistance(0);
								}
							  });
							}else{
								//alert('test');
								$("#sub_company").val('').trigger('change');
								swal({
								    title: 'Subscription for this bank already uploaded by bank',
								    icon: 'error'
								  });
							}
						
						$.each(result.status_data.count, function(key, entry) {
							var baselink = base_url +'/{{ app()->getLocale() }}/';
							$("#monthly_company_sub_status_"+key).attr('data-href',baselink+"subscription-status?member_status="+key+"&date="+result.month_year_number+"&company_id="+result.company_auto_id);
							$("#company_member_status_count_"+key).html(entry);
                        });
						$.each(result.status_data.amount, function(key, entry) {
							$("#company_member_status_amount_"+key).html(entry);
                        });
						$("#memberstatustable").css('opacity',1);
						$.each(result.approval_data.count, function(key, entry) {
							var baselink = base_url +'/{{ app()->getLocale() }}/';
							$("#monthly_company_approval_status_"+key).attr('data-href',baselink+"subscription-status?approval_status="+key+"&date="+result.month_year_number+"&company_id="+result.company_auto_id);
							$("#company_approval_status_count_"+key).html(entry);
                        });
						$.each(result.approval_data.approved, function(key, entry) {
							$("#company_approval_approved_count_"+key).html(entry);
                        });
						$.each(result.approval_data.pending, function(key, entry) {
							$("#company_approval_pending_count_"+key).html(entry);
                        });
						var baselink = base_url +'/{{ app()->getLocale() }}/';
						$("#monthly_company_sub_status_0").attr('data-href',baselink+"subscription-status?member_status=0&date="+result.month_year_number+"&company_id="+result.company_auto_id);
						$("#monthly_company_sub_status_all").attr('data-href',baselink+"subscription-status?member_status=all&date="+result.month_year_number+"&company_id="+result.company_auto_id);
						$("#monthly_company_approval_status_all").attr('data-href',baselink+"subscription-status?approval_status=all&date="+result.month_year_number+"&company_id="+result.company_auto_id);
						$("#company_member_status_count_sundry").html(result.sundry_count);
						$("#company_member_status_amount_sundry").html(result.sundry_amount);
						$("#company_member_status_count_total").html(result.total_members_count);
						$("#company_member_status_amount_total").html(result.total_members_amount);
						$("#company_approval_status_count_total").html(result.total_match_members_count);
						$("#company_approval_approved_count_total").html(result.total_match_approval_members_count);
						$("#company_approval_pending_count_total").html(result.total_match_pending_members_count);
						$("#approvalstatustable").css('opacity',1);
						$("#type").append('<option value="2">Download Existance data</option>');
					}else{
						$(".subscription-bankname").text('');
						$(".clear-approval").html(0);
						$(".monthly-company-approval-status").attr('data-href','');
						$(".monthly-company-sub-status").attr('data-href','');
						$("#bankname-listing").addClass('hide');
					}
				}
			});
		}else{
			$(".subscription-bankname").text('');
			$(".clear-approval").html(0);
			$(".monthly-company-approval-status").attr('data-href','');
			$(".monthly-company-sub-status").attr('data-href','');
			$("#bankname-listing").addClass('hide');
		}
		if(entry_date!=""){
			$("#memberstatustable").css('opacity',0.5);
			$("#approvalstatustable").css('opacity',0.5);
			var url = "{{ url(app()->getLocale().'/get-datewise-status') }}" + '?entry_date=' + entry_date ;
			$.ajax({
				url: url,
				type: "GET",
				dataType: "json",
				success: function(result) {
					//console.log(result);
					if(result.status==1){
						$.each(result.status_data.count, function(key, entry) {
							var baselink = base_url +'/{{ app()->getLocale() }}/';
							var member_link = "<a target='_blank' href='"+baselink+"subscription-status?member_status="+key+"&date="+result.month_year_number+"'>";
							$("#member_status_count_"+key).html(entry);
							$("#monthly_member_status_"+key).attr('data-href',baselink+"subscription-status?member_status="+key+"&date="+result.month_year_number);
							//$("#monthly_member_status_"+key).html(entry);
                        });
						var baselink = base_url +'/{{ app()->getLocale() }}/';
						$("#monthly_member_status_0").attr('data-href',baselink+"subscription-status?member_status=0&date="+result.month_year_number);
						$("#monthly_member_status_all").attr('data-href',baselink+"subscription-status?member_status=all&date="+result.month_year_number);
						$.each(result.status_data.amount, function(key, entry) {
							$("#member_status_amount_"+key).html(entry);
                        });
						$("#memberstatustable").css('opacity',1);
						$.each(result.approval_data.count, function(key, entry) {
							var baselink = base_url +'/{{ app()->getLocale() }}/';
							var member_link = "<a target='_blank' href='"+baselink+"subscription-status?approval_status="+key+"&date="+result.month_year_number+"'>"
							$("#approval_status_count_"+key).html(entry);
							$("#monthly_approval_status_"+key).attr('data-href',baselink+"subscription-status?approval_status="+key+"&date="+result.month_year_number);
                        });
						$.each(result.approval_data.approved, function(key, entry) {
							//console.log("#approval_approved_count_"+key);
							//console.log("#approval_approved_count_"+entry);
							$("#approval_approved_count_"+key).html(entry);
                        });
						$("#monthly_approval_status_all").attr('data-href',baselink+"subscription-status?approval_status=all&date="+result.month_year_number);
						$.each(result.approval_data.pending, function(key, entry) {
							$("#approval_pending_count_"+key).html(entry);
                        });
						$("#approvalstatustable").css('opacity',1);
						$("#member_status_count_sundry").html(result.sundry_count);
						$("#member_status_amount_sundry").html(result.sundry_amount);
						$("#member_status_count_total").html(result.total_members_count);
						$("#member_status_amount_total").html(result.total_members_amount);
						$("#approval_status_count_total").html(result.total_match_members_count);
						$("#approval_approved_count_total").html(result.total_match_approval_members_count);
						$("#approval_pending_count_total").html(result.total_match_pending_members_count);
						//$("#member_status_count_1").html(5555);
					}else{
						
					}
				}
			});
		}
	}
	$(document).on('change','#entry_date,#sub_company',function(){
		getDataStatus();
	});
	function DownloadExistance(existance){
		if(existance==1){
			$("#type").val(2);
			$('#subscribe_formValidate').trigger('submit');
		}else{
			//$("#type option[value='2']").remove();
		}
		$("#modal_subscription").modal('close');
	}
	$(document).on('submit','form#subscribe_formValidate',function(){
		var type = $("#type").val();
		if(type==1){
			loader.showLoader();
		}
		//$("#submit-download").prop('disabled',true);
	});
	$(document).on('click','#submit-download',function(){
		$("#type").val(0);
		$('#subscribe_formValidate').trigger('submit');
	});
	$(document).on('click','#submit-upload',function(){
		$("#type").val(1);
		$('#subscribe_formValidate').trigger('submit');
		
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
	$("#subscription_sidebar_li_id").addClass('active');
	$("#subscription_sidebar_a_id").addClass('active');
</script>
@endsection