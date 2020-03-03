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
									<h5 class="breadcrumbs-title mt-0 mb-0">{{__('Subscription Variance List') }}</h5>
									<ol class="breadcrumbs mb-0">
										<ol class="breadcrumbs mb-0">
											<li class="breadcrumb-item"><a
													href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard') }}</a>
											</li>
											<li class="breadcrumb-item active">{{__('Subscription Variance') }}
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
									$companyid = CommonHelper::getCompanyID($userid);
								@endphp
								<div class="row">
									<form class="formValidate" id="subscribe_formValidate" method="post" action="{{ url(app()->getLocale().'/subscription_variance') }}" enctype="multipart/form-data">
										@csrf
										<div class="row">
											
											<div class="input-field col m2 s12">
												<label for="doe">{{__('From Month') }}*</label>
												<input type="text" name="from_date" id="from_date" required="" value="{{ date('M/Y',strtotime($data['from_year_full'])) }}" class="datepicker-custom" />
											</div>
											<div class="input-field col m2 s12">
												<label for="doe">{{__('To Month') }}*</label>
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
						<h4 class="card-title">Subscription variation Members
						<div class="right">
							<a id="printbutton" href="#" style="" class="export-button btn right" style="background:#ccc;" onClick="window.print()"> Print</a>
			 	
						</div>
						</h4>
					</div>
					<div class="card-body">
						<table id="page-length-option" class="display" width="100%">
							@if($data['diff_in_months']==1)
							<thead>
								<tr class="" >
									<th>{{__('S.No')}}</th>
									<th>Member Name</th>
									<th>NRIC</th>
									
									<th>{{ date('M Y',strtotime($data['to_year_full'])) }} <br> Amount</th>
									<th>{{ date('M Y',strtotime($data['to_year_full'].' -1 Month')) }} <br> Amount</th>

									<th>Action</th>
									
										
									
								</tr>
							</thead>
							<tbody class="tbody-area" width="100%">
								@php
									//dd($data['diff_in_months']);
									$variance_company_members = CommonHelper::getSubscriptionVarianceMembers($data['to_year_full'],$companyid);
									$slno=1;
								@endphp
								@foreach($variance_company_members as $member)
								<tr class="bold table-footer">
									<td style="width:3%">{{ $slno }}</td>
									<td style="width:30%">{{ $member->name }}</td>
									<td style="width:20%">{{ $member->ic }}</td>
									@if($data['diff_in_months']==1)
									<td style="width:20%">{{ $member->Amount }}</td>
									<td style="width:20%">{{ $member->last_amount }}</td>
									<td>
										<a class="btn btn-sm waves-effect gradient-45deg-green-teal " onClick="return UpdateRemark({{ $member->submemberid }})"  title="Update" type="button" name="action"><i class="material-icons">edit</i></a>
									</td>
									@endif
								</tr> 
								@php
									$slno++;
								@endphp
								@endforeach
							</tbody>
							@endif
							@if($data['diff_in_months']>1)
							<thead>
								<tr class="" >
									<th>{{__('S.No')}}</th>
									<th>Member Name</th>
									<th>NRIC</th>
							
									<th>{{ date('M Y',strtotime($data['to_year_full'])) }} <br> Amount</th>
									@for($i=1;$i<=$data['diff_in_months'];$i++)
										<th>{{ date('M Y',strtotime($data['to_year_full'].' -'.$i.' Month')) }} <br> Amount</th>
									@endfor
							
								</tr>
							</thead>
							<tbody class="tbody-area" width="100%">
								@php
									$slno=1;
								@endphp
								@foreach($data['submembers'] as $member)
									@php
										for($j=0;$j<=$data['diff_in_months'];$j++){
											$subsamt{$j} = CommonHelper::getMemberPaidSubs($member->member_id, date('Y-m-d',strtotime($data['to_year_full'].' -'.$j.' Month')));
											
										}
										//dd($subsamt);
									@endphp
									@if(count($subsamt) !== count(array_unique($subsamt)) && count(array_unique($subsamt))!=1)
									<tr id="member{{$member->submemberid}}" class="" >
										<td style="width:3%">{{ $slno }}</td>
										<td style="width:17%">{{$member->Name}}</td>
										<td style="width:10%">{{$member->NRIC}}</td>
										<td class="membersubs membersubs{{$member->submemberid}}" style="">{{$member->Amount}}</td>
										@for($i=1;$i<=$data['diff_in_months'];$i++)
											<td class="membersubs membersubs{{$member->submemberid}}">@php echo $subsamt{$i} @endphp</td>
										@endfor
									</tr>
										@php
											$slno++;
										@endphp
									@endif
									<!-- @for($k=1;$k<=$data['diff_in_months'];$k++)
										@if($member->Amount == $subsamt{$k})
										<style type="text/css">
											#member{{$member->submemberid}}{
												//display: none !important;
											}
										</style>
										<script type="text/javascript">
											//RemoveTR('{{$member->submemberid}}');
										</script>
										@endif
									@endfor -->
								@endforeach
							</tbody>
							@endif
						</table>
					</div> 
				</div> 
			</div> 
		</div> 
	</div>
	<!-- Modal Trigger -->

	<div id="modal-remarks" class="modal">
		<form class="formValidate" id="approvalformValidate" method="post" action="{{ route('mismatched.save',app()->getLocale()) }}">
        @csrf
			<input type="text" class="hide" name="sub_member_id" id="sub_member_id">
			<div class="modal-content">
				<h4>Remarks</h4>
				<textarea id="description" name="description" style="height: 58px !important;" class="materialize-textarea" spellcheck="false"></textarea>
			</div>
			<div class="modal-footer">
				<button type="button" class="modal-action modal-close btn waves-effect red accent-2 left">Close</button>
			  	<button type="submit" class="btn waves-effect waves-light submitApproval" onClick="return ConfirmSubmit()">Submit</button>
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
	
	$(document).on('submit','#approvalformValidate',function(event){
		event.preventDefault();
		$(".submitApproval").attr('disabled', true);
		var url = "{{ url(app()->getLocale().'/ajax_save_variation') }}" ;
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
					
					M.toast({
						html: result.message
					});
				}else{
					M.toast({
						html: result.message
					});
				}
				$("#modal-remarks").modal('close');
			}
		});
	});
	// $(".subscription_amount").each(function() {
	//       var subs_value = $(this).val()=='' ? 0 : $(this).val();
	//       total_subs += parseFloat(subs_value);
	//  });

	$("#subscriptions_sidebars_id").addClass('active');
	$("#subvariance_sidebar_li_id").addClass('active');
	$("#subvariance_sidebar_sidebar_a_id").addClass('active');
</script>
@endsection