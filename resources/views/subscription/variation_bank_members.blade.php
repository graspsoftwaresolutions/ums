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
	.bold{
		font-weight: bold;
	}
	.table-footer {
		cursor:pointer;
		background: #dbdbf7;font-weight:bold;
	}
	.btn-sm{
	    padding: 0 1rem;
	}
	@media print {
		#printbutton{
			display: none !important;
		}
		.sidenav-main,.nav-wrapper {
		 	display:none !important;
		}

		.gradient-45deg-indigo-purple{
			display:none !important;
		}
		#filterarea{
			display:none !important;
		}

		#subsfilter{
			display:none !important;
		}

		#tabdiv{
			display:none !important;
		}
		#advancedsearchs{
			display:none !important;
		}
		.btn{
			display:none !important;
		}

		#printableArea {
		 	display:block !important;
		}
		td, th {
		    display: table-cell;
		    padding: 5px 4px;
		    text-align: left;
		    vertical-align: middle;
		    border-radius: 2px;
		}
	}
</style>
<script src="{{ asset('public/assets/js/jquery.min.js') }}"></script>
<script type="text/javascript">

	function RemoveTR(memberid){
		$("#member"+memberid).remove();
		console.log("#member"+memberid);
	}
</script>
@endsection
@section('main-content')
<div class="row">
	<div style="height:150px !important" class="content-wrapper-before gradient-45deg-indigo-purple"></div>
	<div class="col s12">
		<div id="tabdiv" class="container">
			<div class="section section-data-tables">
				<!-- BEGIN: Page Main-->
				<div class="row">
					<div class="breadcrumbs-dark" id="breadcrumbs-wrapper">
						<!-- Search for small screen-->
						<div class="container">
							<div class="row">
								<div class="col s10 m6 l6">
									<h5 class="breadcrumbs-title mt-0 mb-0">{{__('Subscription Variation List') }}</h5>
									<ol class="breadcrumbs mb-0">
										<ol class="breadcrumbs mb-0">
											<li class="breadcrumb-item"><a
													href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard') }}</a>
											</li>
											<li class="breadcrumb-item active">{{__('Subscription Variation') }}
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
					<div id="filterarea" class="card-content">
						<div class="row">
							<div class="col s12 m12">
								@php
									$userid = Auth::user()->id;
									$companyid = $data['companyid'];
									$get_roles = Auth::user()->roles;
									$user_role = $get_roles[0]->slug;
								@endphp
								<div class="row @if($user_role!='company') hide @endif">
									<form class="formValidate" id="subscribe_formValidate" method="post" action="{{ url(app()->getLocale().'/subscription_variance') }}" enctype="multipart/form-data">
										@csrf
										<div class="row">
											
											<div class="input-field col m2 s12 hide">
												<label for="doe">{{__('From Month') }}*</label>
												<input type="text" name="from_date" id="from_date" required="" value="{{ date('M/Y',strtotime($data['from_year_full'])) }}" class="datepicker-custom" />
											</div>
											<div class="input-field col m2 s12">
												<label for="doe">{{__('Subscription Month') }}*</label>
												<input type="text" name="to_date" id="to_date" required value="{{ $data['to_month_year'] }}" class="datepicker-custom" />
												<input type="text" name="companyid" id="companyid" required value="{{ $companyid }}" class="hide" />
											</div>
											
											
											<div class="col m2 s12 " style="padding-top:5px;">
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
			<div class="container">
				<div class="card">
					<div class="card-content">
						<h4 class="card-title">Subscription Variation Members
						<div class="right">
							<a id="printbutton" target="_blank" href="{{ URL::to(app()->getLocale().'/subscription-variation-members?date='.strtotime($data['to_year_full']).'&companyid='.$companyid) }}" style="" class="export-button btn right" style="background:#ccc;" > Print</a>
			 	
						</div>
						</h4>
					</div>
					<div class="card-body">
						@php
						
							$notmatched = $data['submembers'];
							//dd($notmatched);
						@endphp
						<div id="notmatcheddetails" class="@if(count($notmatched)==0) hide @endif"  >
							<p style="font-size: 16px;text-decoration: underline;font-size: 16px;font-weight:bold;">Not Matched Members List</p>
							<table id="page-length-option" class="display" width="100%">
								@if($data['diff_in_months']==1)
								<thead>
									<tr>
										<th width="2%">{{__('S.No')}}</th>
										<th width="20%">{{__('Member Name')}}</th>
										
										<th width="8%">{{__('NRIC')}}</th>
										<th width="7%">{{__('Amount')}}</th>
										<th width="13%">{{__('Reason')}}</th>
										<th width="20%">{{__('Remarks')}}</th>
										@if($user_role=='company')
										<th width="8%">{{__('Status')}}</th>
										<th width="5%">{{__('Action')}}</th>
										@endif
										
									</tr> 
								</thead>
								<tbody>
									@php
										$slno=1;
										//dd($user_role);
										$notmembers = [];
									@endphp
									@foreach($notmatched as  $key => $member)
										@php
											//dd($member);
											$approval_status = $member->approval_status;

											$mismatchstatusdata = CommonHelper::get_mismatchstatus_data($member->sub_member_id);
											$notmembers[] = $member->sub_member_id;
											
											$unmatchdata = CommonHelper::get_unmatched_data($member->sub_member_id);
											$matchname = '';
											$unmatchreason = '';
											$approval_status = 0;
											
											if(!empty($unmatchdata)){
												$unmatchreason = $unmatchdata->remarks;
												$approval_status = $unmatchdata->approval_status;
											}
											

											if(!empty($mismatchstatusdata)){
												$matchid = $mismatchstatusdata->match_id;
												
												if($matchid==6){
													$matchname = 'Others';
												}else{
													$matchname = CommonHelper::get_member_match_name($matchid);
												}
												
												//$approval_status = $mismatchstatusdata->approval_status;
											}
											
										@endphp
										<tr style="overflow-x:auto;">
											<td>{{$slno}}</td>
											<td>{{ $member->up_member_name }}</td>
											<!--td id="member_code_{{ $member->sub_member_id }}" >{{ $member->member_number }}</td-->
											
											<td>{{ $member->up_nric }}</td>
											<td>{{ number_format($member->Amount,2,".",",") }}</td>
											<td id="unmatch_status_{{ $member->sub_member_id }}" width="10%">{{$matchname}}</td>
											<td id="unmatch_reason_{{ $member->sub_member_id }}" width="10%">{{$unmatchreason}}</td>
											@if($user_role=='company')

											<td id="approve_status_{{ $member->sub_member_id }}"><span class="badge {{$approval_status==1 ? 'green' : 'red'}}">{{ $approval_status==1 ? 'Updated' : 'Pending' }}</span></td>
											
											
											<td>
											
											<a class="btn btn-sm waves-effect gradient-45deg-green-teal " onClick="return showApproval({{ $member->sub_member_id }})"  title="Update" type="button" name="action"><i class="material-icons">edit</i></a></td>
											
											@endif
											
										</tr> 
										@php
											$slno++;
										@endphp
									@endforeach
								</tbody>
								@endif
								
							</table>
							<br>
						</div>
						@php
							$pre_company_members = CommonHelper::getBankLastMonthlyPaidMembersAll($companyid,$data['to_year_full'],2);
							$current_company_members = CommonHelper::getcurrentMonthlyPaidMembersAll($companyid,$data['to_year_full'],2);
							//$variance_company_members = CommonHelper::getSubscriptionVarianceMembers($data['to_year_full'],$companyid);
						@endphp
						<div id="predetails" class="@if(count($pre_company_members)==0) hide @endif"  >
							<p style="font-size: 16px;text-decoration: underline;font-size: 16px;font-weight:bold;">Previous Subscription Paid - Current Subscription Unpaid</p>
							<table id="page-length-option" class="display" width="100%">
								@if($data['diff_in_months']==1)
								<thead>
									<tr class="" >
										<th width="3%">{{__('S.No')}}</th>
										<th width="25%">Member Name</th>
										<th width="10%">NRIC</th>
										<th width="7%">{{ date('M Y',strtotime($data['to_year_full'].' -1 Month')) }} <br> Amount</th>
										<th width="7%">{{ date('M Y',strtotime($data['to_year_full'])) }} <br> Amount</th>
										<th width="20%">{{__('Reason')}}</th>
										@if($user_role=='company')
										<th width="5%">Action</th> 
										@endif
											
										
									</tr>
								</thead>
								<tbody class="tbody-area" width="100%">
									@php
										$slno = 1;
									@endphp
									@foreach($pre_company_members as $company)
										@if( !in_array( $company->sub_member_id ,$notmembers) )
										@php
											//dd($notmembers);
											if( in_array( $company->sub_member_id ,$notmembers) )
											{
											    dd($notmatched);
											}
											
											$unmpaiddata = CommonHelper::get_unpaid_data($company->sub_member_id);
											$unpaidreason = '';
											$approval_status = 0;
											if(!empty($unmpaiddata)){
												$unmatchreason = $unmpaiddata->reason;
												$unpaidreason = CommonHelper::get_unpaid_reason($unmatchreason);
												if($unmatchreason==5){
													$unpaidreason = $unmpaiddata->remarks;
												}
												$approval_status = 1;
											}
										@endphp
										<tr>
											<td>{{$slno}}</td>
											<td>{{ $company->name }}</td>
											<td>{{ $company->ic }}</td>
											<td>{{ number_format($company->SUBSCRIPTION_AMOUNT,2,".",",") }}</td>
											<td>0</td>
											<td id="unpaid_reason_{{ $company->sub_member_id }}" width="10%">{{$unpaidreason}}</td>
											@if($user_role=='company')
											<td class=""><a class="btn btn-sm waves-effect gradient-45deg-green-teal " onClick="return showVarianceApproval({{ $company->sub_member_id }})"  title="Update" type="button" name="action"><i class="material-icons">edit</i></a></td></td> 
											@endif
										</tr>
										@php
											$slno++;
										@endphp
										@endif
									@endforeach
									
								</tbody>
								@endif
								
							</table>
							<br>
						</div>
						@if($slno==1)
						<style type="text/css">
							
							#predetails{
								display: none !important;
							}
							
						</style>
						@endif
						
						<div id="currentdetails" class="@if(count($current_company_members)==0) hide @endif"  >
							<p style="font-size: 16px;text-decoration: underline;font-weight:bold;">Previous Subscription Unpaid - Current Subscription Paid</p>
							<table id="page-length-option" class="display" width="100%">
								@if($data['diff_in_months']==1)
								<thead>
									<tr class="" >
										<th width="3%">{{__('S.No')}}</th>
										<th width="25%">Member Name</th>
										<th width="10%">NRIC</th>
										<th width="7%">{{ date('M Y',strtotime($data['to_year_full'].' -1 Month')) }} <br> Amount</th>
										<th width="7%">{{ date('M Y',strtotime($data['to_year_full'])) }} <br> Amount</th>
										<th width="20%">{{__('Reason')}}</th>
										@if($user_role=='company')
										<th width="5%">Action</th>
										@endif
											
										
									</tr>
								</thead>
								<tbody class="tbody-area" width="100%">
									@php
										$slno1 = 1;
										
									@endphp
									@foreach($current_company_members as $company)
										@if( !in_array( $company->sub_member_id ,$notmembers) )
										@php

											$unmpaiddata = CommonHelper::get_unpaid_data($company->sub_member_id);
											$unpaidreason = '';
											$approval_status = 0;
											if(!empty($unmpaiddata)){
												$unmatchreason = $unmpaiddata->reason;
												$unpaidreason = CommonHelper::get_lastunpaid_reason($unmatchreason);
												if($unmatchreason==5){
													$unpaidreason = $unmpaiddata->remarks;
												}
												$approval_status = 1;
											}
										@endphp
										<tr>
											<td>{{$slno1}}</td>
											<td>{{ $company->name }}</td>
											<td>{{ $company->ic }}</td>
											<td>0</td>
											<td>{{ number_format($company->SUBSCRIPTION_AMOUNT,2,".",",") }}</td>
											
											<td id="unpaid_reason_{{ $company->sub_member_id }}" width="10%">{{$unpaidreason}}</td>
											@if($user_role=='company')
											<td class=""><a class="btn btn-sm waves-effect gradient-45deg-green-teal " onClick="return showVarianceApproval({{ $company->sub_member_id }}, 1)"  title="Update" type="button" name="action"><i class="material-icons">edit</i></a></td></td> 
											@endif
										</tr>
										@php
											$slno1++;
										@endphp
										@endif
									@endforeach

								</tbody>
								@endif
								
							</table>
							<br>
						</div>
						@if($slno1==1)
						<style type="text/css">
							
							#currentdetails{
								display: none !important;
							}
							
						</style>
						@endif
						
					</div> 
				</div> 
			</div> 
		</div> 
	</div>
	<!-- Modal Structure -->
	  <div id="modal-approval" class="modal">
		<form class="formValidate" id="approvalformValidate" method="post" action="{{ route('mismatched.save',app()->getLocale()) }}">
        @csrf
		<input type="text" class="hide" name="sub_member_id" id="sub_member_id">
		<div class="modal-content">
		  <h4>Not Matched Member details</h4>
			<div class="row">
				<div class="col s12 m6">
					 <p>
						Member Name: <span id="view_member_name" class="bold"></span>
						</br>
						NRIC: <span id="view_nric" class="bold"></span>
				   </p>
				</div>
				<div class="col s12 m6">
					 <p>
						Amount: <span id="view_paid" class="bold"></span>
				   </p>
				</div>
			</div>
		  
		   </hr>
				<div class="row">
	                <div class="col m4">
	                    <label for="typeid">{{__('Reason') }}</label>
	                    <input type="text" class="" name="reason" id="reason" readonly="" value="StruckOff Members" />
	                </div>
	                <div class="col m8 descriptiontd">
	                	<label for="description">{{__('Remarks') }}*</label>
	                	<textarea id="description" name="description" required="" style="height: 58px !important;" class="materialize-textarea" spellcheck="false"></textarea>
	                </div>
	            </div>
				
		</div>
		<div class="modal-footer">
		  <button type="button" class="modal-action modal-close btn waves-effect red accent-2 left">Close</button>
		  <button type="submit" class="btn waves-light submitApproval" >Submit</button>
		</div>
		 </form>
	  </div>
	<!-- Modal Trigger -->

	<div id="modal-variance" class="modal">
		<form class="formValidate" id="varianceformValidate" method="post" action="{{ route('mismatched.save',app()->getLocale()) }}">
        @csrf
			<input type="text" class="hide" name="vsub_member_id" id="vsub_member_id">
			<div class="modal-content">
				<h4>Subscription Unpaid Member details</h4>
				<div class="row">
					<div class="col s12 m6">
						 <p>
							Member Name: <span id="view_vmember_name" class="bold"></span>
							</br>
							NRIC: <span id="view_vnric" class="bold"></span>
					   </p>
					</div>
					<div class="col s12 m6">
						 <p>
							Amount: <span id="view_vpaid" class="bold"></span>
					   </p>
					</div>
				</div>
			  
			   </hr>
				<div class="row">
	                <div class="col m4">
	                    <label for="typeid">{{__('Reason') }}*</label>
	                    <select name="vreasonid" id="vreasonid" onclick="return EnableVDescription(this.value)" class="browser-default valid" required="" aria-invalid="false">
							
						</select>
	                </div>
	                <div class="col m8 vdescriptiontd hide">
	                	<label for="vdescription">{{__('Description') }}*</label>
	                	<textarea id="vdescription" name="vdescription" style="height: 58px !important;" class="materialize-textarea" spellcheck="false"></textarea>
	                </div>
	            </div>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="modal-action modal-close btn waves-effect red accent-2 left">Close</button>
			  	<button type="submit" class="btn waves-light submitApproval" >Submit</button>
			</div>
		</form>
	</div>
  
</div>

@endsection
@section('footerSection')

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
			//getDataStatus();
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
				
				/* file:{
					required: true,
				}, */
			 },
        //For custom messages
        messages: {
					entry_date: {
						required: "Please choose date",
						
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
	
	$(document).on('change','#entry_date',function(){
		//getDataStatus();
	});
	
	$(document).on('submit','form#subscribe_formValidate',function(){
		
		//$("#submit-download").prop('disabled',true);
	});
	// $(document).on('click','.variationtype',function(){
	// 	$('#variation_uncheck').prop('checked', true); 
	// });
	
	function UncheckVariation(){
		$('.variationtype').prop('checked', false); 
	}

	function ViewVarianceList(thisdata){
		window.open($(thisdata).attr("data-href"), '_blank');
	}

	function UpdateRemark(sub_member_id){
		$('.modal').modal();
		$("#sub_member_id").val(sub_member_id);
		$("#modal-remarks").modal('open');
			var url = "{{ url(app()->getLocale().'/varation_member_info') }}" + '?sub_member_auto_id=' + sub_member_id;
		$.ajax({
			url: url,
			type: "GET",
			dataType: "json",
			success: function(result) {
				//console.log(result);
				//$(".match_case_row").addClass('hide');
				//$(".match_case_row").css('pointer-events','unset');
				//$("#view_member_name").html(result.up_member_data.Name);
				//$("#view_nric").html(result.up_member_data.NRIC);
				//$("#view_paid").html(result.up_member_data.Amount);
				unmatchinfo = result.unmatchdata;
				$("#description").val('');
					//console.log(res);
				if(unmatchinfo!=null){
					$("#description").val(unmatchinfo.remarks);
					
					
				}
			
				$("#modal-approval").modal('open');
				loader.hideLoader();
			}
		});
	}
	
	
	function showApproval(sub_member_id){
  		//alert(123);
	   $(".submitApproval").attr('disabled', false);
	   $('.modal').modal();
	   $("#sub_member_id").val(sub_member_id);
	   var unmatch_status = $("#unmatch_status_"+sub_member_id).text();
	   loader.showLoader();
		var url = "{{ url(app()->getLocale().'/unmatched_member_info') }}" + '?sub_member_auto_id=' + sub_member_id;
		$.ajax({
			url: url,
			type: "GET",
			dataType: "json",
			success: function(result) {
				//console.log(result);
				$(".match_case_row").addClass('hide');
				$(".match_case_row").css('pointer-events','unset');
				$("#view_member_name").html(result.up_member_data.Name);
				$("#view_nric").html(result.up_member_data.NRIC);
				$("#view_paid").html(result.up_member_data.Amount);
				unmatchinfo = result.unmatchdata;
				//$(".descriptiontd").addClass('hide');
				$("#reasonid").val('');
				$("#reason").val(unmatch_status);
				$("#description").val('');
					//console.log(res);
				if(unmatchinfo!=null){
					$("#description").val(unmatchinfo.remarks);
					
					
				}
				
				$("#modal-approval").modal('open');
				loader.hideLoader();
			}
		});
    }

    function showVarianceApproval(sub_member_id, refno=false){
    	$(".submitApproval").attr('disabled', false);
	    $('.modal').modal();
	    $("#vsub_member_id").val(sub_member_id);
	    loader.showLoader();
		var url = "{{ url(app()->getLocale().'/unpaid_member_info') }}" + '?sub_member_auto_id=' + sub_member_id;
		$.ajax({
			url: url,
			type: "GET",
			dataType: "json",
			success: function(result) {
				//console.log(result);
				$(".match_case_row").addClass('hide');
				$(".match_case_row").css('pointer-events','unset');
				$("#view_vmember_name").html(result.up_member_data.Name);
				$("#view_vnric").html(result.up_member_data.NRIC);
				$("#view_vpaid").html(result.up_member_data.Amount);

				var prepaid = '<option value="">Select</option><option value="1">Resigned</option><option value="2">Retired</option><option value="3">Promoted</option><option value="4">Demised</option><option value="5">Others</option>';

				var preunpaid = '<option value="">Select</option><option value="1">No pay leave</option><option value="2">Excessive medical leave</option><option value="5">Others</option>';

				//alert(refno);
				if(refno==1){
					$('#vreasonid').empty().append(preunpaid);
				}else{
					$('#vreasonid').empty().append(prepaid);
				}

				unmatchinfo = result.unmatchdata;
				$(".vdescriptiontd").addClass('hide');
				$("#vreasonid").val('');
					//console.log(res);
				if(unmatchinfo!=null){
					$("#vreasonid").val(unmatchinfo.reason);
					if(unmatchinfo.reason==5){
						$(".vdescriptiontd").removeClass('hide');
						$("#vdescription").val(unmatchinfo.remarks);
					}
					
				}
				
				$("#modal-variance").modal('open');
				loader.hideLoader();
			}
		});	
    }
	// $(".subscription_amount").each(function() {
	//       var subs_value = $(this).val()=='' ? 0 : $(this).val();
	//       total_subs += parseFloat(subs_value);
	//  });

	function EnableDescription(reasonval){
		if(reasonval==4){
			$(".descriptiontd").removeClass('hide');
		}else{
			$(".descriptiontd").addClass('hide');
		}
	}

	function EnableVDescription(reasonval){
		if(reasonval==5){
			$(".vdescriptiontd").removeClass('hide');
		}else{
			$(".vdescriptiontd").addClass('hide');
		}
	}

	$(document).on('submit','#approvalformValidate',function(event){
		event.preventDefault();
		$(".submitApproval").attr('disabled', true);
		var url = "{{ url(app()->getLocale().'/ajax_save_summary') }}" ;
		//var vreasonval = $("#reasonid option:selected").html();
		//alert(vreasonval);
		$.ajax({
			url: url,
			type: "POST",
			dataType: "json",
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data: $('#approvalformValidate').serialize(),
			success: function(result) {
				if(result.status==1){
					var badge_color = result.approval_status == 1 ? 'green' : 'red';
					var badge_label = result.approval_status == 1 ? 'Updated' : 'Pending';
					var vreasonval = $("#description").val();
					$("#approve_status_"+result.sub_member_auto_id).html('<span class="badge '+badge_color+'">'+badge_label+'</span>');
					//$("#unmatch_reason_"+result.sub_member_auto_id).html('<span class="badge '+badge_color+'">'+badge_label+'</span>');
					// if(result.member_match==2){
					// 	$("#member_code_"+result.sub_member_auto_id).html(result.member_number);
					// 	$("#member_status_"+result.sub_member_auto_id).html(result.member_status);
						
						
					// }
					$("#unmatch_reason_"+result.sub_member_auto_id).html(vreasonval);
					M.toast({
						html: result.message
					});
				}else{
					M.toast({
						html: result.message
					});
				}
				$("#modal-approval").modal('close');
			}
		});
	});


	$(document).on('submit','#varianceformValidate',function(event){
		event.preventDefault();
		$(".submitApproval").attr('disabled', true);
		var url = "{{ url(app()->getLocale().'/ajax_save_variation') }}" ;
		var vreasonval = $("#vreasonid option:selected").html();
		$.ajax({
			url: url,
			type: "POST",
			dataType: "json",
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data: $('#varianceformValidate').serialize(),
			success: function(result) {
				if(result.status==1){
					$("#unpaid_reason_"+result.sub_member_auto_id).html(vreasonval);
					M.toast({
						html: result.message
					});
				}else{
					M.toast({
						html: result.message
					});
				}
				$("#modal-variance").modal('close');
			}
		});
	});

	$("#subscriptions_sidebars_id").addClass('active');
	$("#subvariance_sidebar_li_id").addClass('active');
	$("#subvariance_sidebar_sidebar_a_id").addClass('active');
</script>
@endsection