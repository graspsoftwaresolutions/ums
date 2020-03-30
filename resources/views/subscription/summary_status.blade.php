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
		    padding: 10px 4px;
		    text-align: left;
		    vertical-align: middle;
		    border-radius: 2px;
		}
	}
	#description{
		height: 58px !important;
	}

</style>
@php
	//dd($data['member']);
@endphp
<style>
	@if(count($data['member'])<10)
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
	.btn-sm{
		padding: 2.5px 8px;
		font-size: 8px;
		line-height: 1.5;
		border-radius: 3px;
		color: #fff;
		margin-right:5px;
	}
	.btn-sm-popup{
		padding: 2.5px 8px;
		font-size: 12px;
		line-height: 1.5;
		border-radius: 3px;
		color: #fff;
		margin-right:5px;
	}
	p.verify-approval{
		margin:0;
	}
	.m_date_row{
		pointer-events: none;
	}
	.m_date_row #search_date{
		background-color: #ddd !important;
	}
	

	input:disabled{
		background-color: #ddd !important;
	}
	#clear{
		color:#fff;
	}
	
</style>

@endsection
@section('main-content')
@php 

@endphp
<div class="row ">
	<div class="col s12">
		<div class="card">
			<div class="card-content">
				<h4 class="card-title">
				@if($data['status']==1)
					Members Matched List 
				@else
					Not Matched Members List 
				@endif
				<b>[Total Amount : {{ number_format($data['total_amt'],2,".",",") }}]</b>
				@php
					$companyid = $data['company_id'];
				@endphp
				
				<a class="btn waves-effect waves-light right " href="{{ route('subscription.sub_fileupload', app()->getLocale())  }}">{{__('Back')}}</a>
				</h4> 
				
				</h4> 
				
			</div>
		</div>
	</div>
</div> 
<div class="row">
	<div class="col s12">
		<div class="card">
			<div class="card-content">
				@php
					$userid = Auth::user()->id;
					$get_roles = Auth::user()->roles;
					$user_role = $get_roles[0]->slug;
				@endphp
				
				<input type="text" name="memberoffset" id="memberoffset" class="hide" value=""></input>
			
			 	<a id="printbutton" href="#" style="" class="export-button btn right" style="background:#ccc;" onClick="window.print()"> Print</a>
			 	<br>
				<table id="page-length-option" class="display nowrap" width="100%">
					<thead>
						<tr>
							<th width="3%">{{__('S.No')}}</th>
							<th width="10%">{{__('Member Name')}}</th>
							
							<th width="10%">{{__('NRIC')}}</th>
							<th width="7%">{{__('Amount')}}</th>
							@if($data['status']!=1)
							<th width="10%">{{__('Update Status')}}</th>

							
							@if($user_role=='company')
							<th width="15%">{{__('Action')}}</th>
							@endif
							@endif
							@if($data['status']!=1 && $user_role=='union')
								<th width="10%">{{__('Reason')}}</th>
							@endif
						</tr> 
					</thead>
					<tbody>
						@php
							$slno=1;
							//dd($user_role);
						@endphp
						@foreach($data['member'] as  $key => $member)
							@php
								//dd($member);
								$approval_status = $member->approval_status;
								//$approval_status = CommonHelper::get_overall_approval_status($member->sub_member_id);
								$unmatchdata = CommonHelper::get_unmatched_data($member->sub_member_id);
								$unmatchreason = '';
								$approval_status = 0;
								if(!empty($unmatchdata)){
									$unmatchreason = $unmatchdata->reason;
									$unmatchreason = CommonHelper::get_unmatch_reason($unmatchreason);
									$approval_status = 1;
								}
								//$duemonths = $member->memberid!="" ? CommonHelper::get_duemonths_monthend($member->memberid, $data['filter_date']) : '';
								//dd($duemonths );
								
							@endphp
							<tr style="overflow-x:auto;">
								<td>{{$slno}}</td>
								<td>{{ $member->up_member_name }}</td>
								<!--td id="member_code_{{ $member->sub_member_id }}" >{{ $member->member_number }}</td-->
								
								<td>{{ $member->up_nric }}</td>
								<td>{{ number_format($member->Amount,2,".",",") }}</td>
								@if($data['status']!=1)

								<td id="approve_status_{{ $member->sub_member_id }}"><span class="badge {{$approval_status==1 ? 'green' : 'red'}}">{{ $approval_status==1 ? 'Updated' : 'Pending' }}</span></td>
								
								@if($user_role=='company')
								<td>
								
								<a class="btn btn-sm waves-effect gradient-45deg-green-teal " onClick="return showApproval({{ $member->sub_member_id }})"  title="Update" type="button" name="action"><i class="material-icons">edit</i></a></td>
								@endif
								@endif
								@if($data['status']!=1 && $user_role=='union')
									<th width="10%">{{$unmatchreason}}</th>
								@endif
							</tr> 
							@php
								$slno++;
							@endphp
						@endforeach
					</tbody>
					<input type="text" name="memberoffset" id="memberoffset" class="hide" value=""></input>
					<input type="text" name="totalhistory" id="totalhistory" class="hide" value="{{$slno}}"></input>
				</table> 
			</div>
		</div>
		</br>
		</br>
	</div>
	  <!-- Modal Structure -->
	  <div id="modal-approval" class="modal">
		<form class="formValidate" id="approvalformValidate" method="post" action="{{ route('mismatched.save',app()->getLocale()) }}">
        @csrf
		<input type="text" class="hide" name="sub_member_id" id="sub_member_id">
		<div class="modal-content">
		  <h4>Monthly subscription member details</h4>
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
			
				<table>
					<thead>
						<tr>
							<th>Reason* </th>
							<th class="descriptiontd hide">Description</th>
							<th>Approval By</th>
						</tr>
					</thead>
					<tbody>
						<tr class="" >
							<td width="30%">
								
								<select name="reasonid" id="reasonid" onclick="return EnableDescription(this.value)" class="browser-default valid" required="" aria-invalid="false">
									<option value="">Select</option>
									<option value="1">Resigned</option>
									<option value="2">IC not match</option>
									<option value="3">Bank not match</option>
									<option value="4">Others</option>
								</select>
								
							</td>
							<td class="descriptiontd hide">
								<textarea id="description" name="description" style="height: 58px !important;" class="materialize-textarea" spellcheck="false"></textarea>
								
							</td>
							<td><span id="reason_approved_by" class="bold"></span></td>
						</tr>
						
					</tbody>
				</table>
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

@endsection
@section('footerSecondSection')
<script>
$("#subscriptions_sidebars_id").addClass('active');
$("#subscompbank_sidebar_li_id").addClass('active');
$("#subscompbank_sidebar_a_id").addClass('active');
$(document).on('click','#clear',function(event){
	$('#member_search').val("");
	$('#member_auto_id').val("");
});
$('#advancedsearchs').click(function(){
	$('#advancedsearch').toggle();
});
$(document).ready(function(){
	$("#member_search").devbridgeAutocomplete({
		//lookup: countries,
		serviceUrl: "{{ URL::to('/get-subscription-status-list') }}?serachkey="+ $("#member_search").val(),
		params: { 
					company_id:  function(){ return $("#company_id").val();  },
					filter_date:  function(){ return $("#filter_date").val();  }, 
					member_status:  function(){ return $("#member_status").val();  }, 
					approval_status:  function(){ return $("#approval_status").val();  }, 
				},
		type:'GET',
		//callback just to show it's working
		onSelect: function (suggestion) {
			 $("#member_search").val(suggestion.number);
			 $("#member_auto_id").val(suggestion.sub_member_id);
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
	$("#member_search_match").devbridgeAutocomplete({
		//lookup: countries,
		serviceUrl: "{{ URL::to('/get-auto-member-list') }}?serachkey="+ $("#member_search").val(),
		type:'GET',
		//callback just to show it's working
		onSelect: function (suggestion) {
			 $("#member_search_match").val(suggestion.value);
			 $("#member_search_auto_id").val(suggestion.number);
		},
		showNoSuggestionNotice: true,
		noSuggestionNotice: 'Sorry, no matching results',
		onSearchComplete: function (query, suggestions) {
			if(!suggestions.length){
				//$("#member_search_match").val('');
				//$("#member_search_auto_id").val('');
			}
		}
	}); 
	$(document.body).on('click', '.autocomplete-no-suggestion' ,function(){
		$("#member_search").val('');
		//$("#member_search_match").val('');
	});
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
  function showApproval(sub_member_id){
  		//alert(123);
	   $(".submitApproval").attr('disabled', false);
	   $('.modal').modal();
	   $("#sub_member_id").val(sub_member_id);
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
				$(".descriptiontd").addClass('hide');
				$("#reasonid").val('');
					//console.log(res);
				if(unmatchinfo!=null){
					$("#reasonid").val(unmatchinfo.reason);
					if(unmatchinfo.reason==5){
						$(".descriptiontd").removeClass('hide');
						$("#description").val(unmatchinfo.remarks);
					}
					
				}
				// $.each(matchinfo,function(key,entry){
				// 	$(".match_row_"+entry.match_id).removeClass('hide');
				// 	if(entry.match_id==1){
				// 		$("#nric_approved_by").html(entry.updated_user);
				// 		$("#nric_approve").prop('checked',entry.approval_status==1 ? true : false);
				// 	}
				// 	else if(entry.match_id==2){
				// 		$("#nric_not_approve").prop('checked',entry.approval_status==1 ? true : false);
				// 		$("#nric_not_approved_by").html(entry.updated_user);
						
				// 		$("#not-registered-area").removeClass('hide');
				// 		if(entry.approval_status == 1){
				// 			$("#member_search_auto_id").val(result.up_member_data.MemberCode);
				// 			$("#member_search_match").val(result.registered_member_name+'/'+result.registered_member_number);
				// 			$("#member_search_match").attr('readonly',true);
				// 			$(".match_row_2").css('pointer-events','none');
				// 			$("#not-registered-area").addClass('hide');
				// 		}
				// 	}
				// 	else if(entry.match_id==3){
				// 		$("#member_approve").prop('checked',entry.approval_status==1 ? true : false);
				// 		$("#registered_member_name").html(result.registered_member_name);
				// 		$("#uploaded_member_name").html(entry.uploaded_member_name);
				// 		$("#name_approved_by").html(entry.updated_user);
				// 		if(result.registered_member_name == entry.uploaded_member_name){
				// 			$(".match_row_3").css('pointer-events','none');
				// 		}
				// 	}
				// 	else if(entry.match_id==4){
				// 		$("#bank_approve").prop('checked',entry.approval_status==1 ? true : false);
				// 		$("#registered_bank_name").html(entry.registered_bank_name);
				// 		$("#uploaded_bank_name").html(entry.uploaded_bank_name);
				// 		$("#registered_bank_id").val(entry.registered_bank_id);
				// 		$("#uploaded_bank_id").val(entry.uploaded_bank_id);
				// 		$("#memebr_tansfer_link").prop('href',entry.transfer_link);
				// 		$("#bank_approved_by").html(entry.updated_user);
				// 		$("#memebr_tansfer_link").removeClass('hide');
				// 		if(entry.registered_bank_id == entry.uploaded_bank_id && entry.approval_status==1){
				// 			$(".match_row_4").css('pointer-events','none');
				// 			$("#memebr_tansfer_link").addClass('hide');
				// 		}
				// 	}else if(entry.match_id==5){
				// 		$("#previous_approve").prop('checked',entry.approval_status==1 ? true : false);
				// 		$("#current_month_amount").html(result.up_member_data.Amount);
				// 		$("#last_month_amount").html(entry.old_payment);
				// 		$("#previous_approved_by").html(entry.updated_user);
				// 	}
				// 	else if(entry.match_id==6){
				// 		$("#struckoff_approve").prop('checked',entry.approval_status==1 ? true : false);
				// 		$("#struckoff_approved_by").html(entry.updated_user);
				// 	}
				// 	else if(entry.match_id==7){
				// 		$("#resign_approve").prop('checked',entry.approval_status==1 ? true : false);
				// 		$("#resign_approved_by").html(entry.updated_user);
				// 	}
				// 	else if(entry.match_id==8){
				// 		$("#nric_old_approve").prop('checked',entry.approval_status==1 ? true : false);
				// 		$("#nric_old_approved_by").html(entry.updated_user);
				// 	}else if(entry.match_id==9){
				// 		$("#nric_bank_approve").prop('checked',entry.approval_status==1 ? true : false);
				// 		$("#nric_bank_approved_by").html(entry.updated_user);
				// 	}else if(entry.match_id==10){
				// 		$("#previous_unpaid_approve").prop('checked',entry.approval_status==1 ? true : false);
				// 		$("#previous_unpaid_approved_by").html(entry.updated_user);
				// 	}
					
				// });
				//var match_data = result.match;
				/* $(".bank_match_row").addClass('hide');
				$(".nric_match_row").addClass('hide');
				$(".member_match_row").addClass('hide');
				$(".nric_not_match_row").addClass('hide');
				$(".struckoff_match_row").addClass('hide');
				$(".resign_match_row").addClass('hide');
				$(".nric_old_match_row").addClass('hide');
				$(".previous_subscription_match_row").addClass('hide');
				$(".nric_bank_match_row").addClass('hide');
				$(".previous_unpaid_match_row").addClass('hide');
				$("#view_member_name").html(result.up_member_name); */
				/* if(match_data.match_id==1){
					$(".nric_match_row").removeClass('hide');
					$("#nric_approved_by").html(result.created_user);
				}
				else if(match_data.match_id==3){
					$("#member_approve").prop('checked',match_data.approval_status==1 ? true : false);
					$(".member_match_row").removeClass('hide');
					$("#registered_member_name").html(result.registered_member_name);
					$("#uploaded_member_name").html(result.uploaded_member_name);
					$("#name_approved_by").html(result.updated_user);
					$(".nric_match_row").removeClass('hide');
					$("#nric_approved_by").html(result.created_user);
				}else if(match_data.match_id==4){
					$("#bank_approve").prop('checked',match_data.approval_status==1 ? true : false);
					$(".bank_match_row").removeClass('hide');
					$("#registered_bank_name").html(result.registered_bank_name);
					$("#uploaded_bank_name").html(result.uploaded_bank_name);
					$("#bank_approved_by").html(result.updated_user);
					$(".nric_match_row").removeClass('hide');
					$("#nric_approved_by").html(result.created_user);
				}else if(match_data.match_id==5){
					$(".previous_subscription_match_row").removeClass('hide');
					$("#previous_approve").prop('checked',match_data.approval_status==1 ? true : false);
					$("#previous_approved_by").html(result.created_user);
				}
				else if(match_data.match_id==6){
					$(".nric_match_row").removeClass('hide');
					$("#struckoff_approve").prop('checked',match_data.approval_status==1 ? true : false);
					$(".struckoff_match_row").removeClass('hide');
					$("#nric_approved_by").html(result.created_user);
					$("#struckoff_approved_by").html(result.updated_user);
				}
				else if(match_data.match_id==7){
					$(".nric_match_row").removeClass('hide');
					$("#resign_approve").prop('checked',match_data.approval_status==1 ? true : false);
					$(".resign_match_row").removeClass('hide');
					$("#nric_approved_by").html(result.created_user);
					$("#resign_approved_by").html(result.updated_user);
				}
				else if(match_data.match_id==8){
					$("#nric_old_approve").prop('checked',match_data.approval_status==1 ? true : false);
					$(".nric_old_match_row").removeClass('hide');
					$("#nric_old_approved_by").html(result.updated_user);
				}else if(match_data.match_id==9){
					$(".nric_bank_match_row").removeClass('hide');
					$("#nric_bank_approve").prop('checked',match_data.approval_status==1 ? true : false);
					$("#nric_bank_approved_by").html(result.created_user);
				}else if(match_data.match_id==10){
					$(".previous_unpaid_match_row").removeClass('hide');
					$("#previous_unpaid_approve").prop('checked',match_data.approval_status==1 ? true : false);
					$("#previous_unpaid_approved_by").html(result.created_user);
				}else if(match_data.match_id==2){
					$(".nric_not_match_row").removeClass('hide');
				}else{
					$(".nric_match_row").removeClass('hide');
				} */
				$("#modal-approval").modal('open');
				loader.hideLoader();
			}
		});
  }
$(document).on('submit','#approvalformValidate',function(event){
	event.preventDefault();
	$(".submitApproval").attr('disabled', true);
	var url = "{{ url(app()->getLocale().'/ajax_save_summary') }}" ;
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
				$("#approve_status_"+result.sub_member_auto_id).html('<span class="badge '+badge_color+'">'+badge_label+'</span>');
				if(result.member_match==2){
					$("#member_code_"+result.sub_member_auto_id).html(result.member_number);
					$("#member_status_"+result.sub_member_auto_id).html(result.member_status);
				}
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
$(document).on('submit','form#filtersubmit',function(event){
	event.preventDefault();
	var baselink = base_url +'/{{ app()->getLocale() }}/';
	$("#search").attr('disabled',true);
	var filter_date = $("#filter_date").val();
	var member_status = $("#member_status").val();
	var approval_status = $("#approval_status").val();
	var company_id = $("#company_id").val();
	var member_auto_id = $("#member_auto_id").val();
	var status_type = $("#status_type").val();
	$('#page-length-option tbody').empty();
	if(filter_date!=""){
		var searchfilters = '&filter_date='+filter_date+'&member_status='+member_status+'&company_id='+company_id+'&approval_status='+approval_status+'&member_auto_id='+member_auto_id+'&status_type='+status_type;
		$("#memberoffset").val("");
		//loader.showLoader();
		$('#page-length-option tbody').empty();
		$("#totalhistory").val(1);
		var totalhistory = parseInt($("#totalhistory").val());
		loader.showLoader();
		$.ajax({
			type: "GET",
			dataType: "json",
			url : "{{ URL::to('/en/get-subscription-more') }}?offset=0"+searchfilters,
			success:function(result){
				if(result)
				{
					res = result.member;
					//console.log(res);
					$.each(res,function(key,entry){
						//console.log(entry);
						var table_row = "<tr><td>"+totalhistory+"</td>";
							table_row += "<td>"+entry.up_member_name+"</td>";
							table_row += "<td id='member_code_"+entry.sub_member_id+"'>"+entry.member_number+"</td>";
							table_row += "<td>"+entry.company_name+"</td>";
							table_row += "<td>"+entry.up_nric+"</td>";
							table_row += "<td>"+entry.Amount+"</td>";
							table_row += "<td>"+entry.due_months+"</td>";
							table_row += "<td id='member_status_"+entry.sub_member_id+"'>"+entry.status_name+"</td>";
							var app_status = entry.approval_status==1 ? '<span class="badge green">Approved</span>' : '<span class="badge red">Pending</span>';
							table_row += "<td id='approve_status_"+entry.sub_member_id+"'>"+app_status+"</td>";
							var actions ='';
							var member_no = parseInt(entry.member_number);
							if(!isNaN(member_no)){
								actions += '<a class="btn btn-sm waves-effect " href="'+baselink+'membership-edit/'+entry.enc_member+'" target="_blank" title="Member details" type="button" name="action"><i class="material-icons">account_circle</i></a>';
								actions += ' <a class="btn btn-sm waves-effect amber darken-4" href="'+baselink+'member-history/'+entry.enc_member+'" target="_blank" title="Member History" type="button" name="action"><i class="material-icons">history</i></a>';
							}
							actions += ' <a class="btn btn-sm waves-effect gradient-45deg-green-teal " onclick="return showApproval('+entry.sub_member_id+')" title="Approval" type="button" name="action"><i class="material-icons">check</i></a>';
							table_row += "<td>"+actions+"</td></tr>";
							$('#page-length-option tbody').append(table_row);
							totalhistory+=1;
					});
					$("#totalhistory").val(totalhistory);
					
					loader.hideLoader();
				}else{
					
				}
			}
		});
		$("#search").attr('disabled',false);
	}else{
		alert("please choose any filter");
	}
	//$("#submit-download").prop('disabled',true);
});
// $(window).scroll(function() {   
//    var lastoffset = $("#memberoffset").val();
//    var limit = 0;
//    var baselink = base_url +'/{{ app()->getLocale() }}/';
//    if($(window).scrollTop() + $(window).height() >= $(document).height()) {
//    		var totalhistory = parseInt($("#totalhistory").val());
// 		loader.showLoader();
// 		var filter_date = $("#filter_date").val();
// 		var member_status = $("#member_status").val();
// 		var approval_status = $("#approval_status").val();
// 		var company_id = $("#company_id").val();
// 		var member_auto_id = $("#member_auto_id").val();
// 		var status_type = $("#status_type").val();
// 		var searchfilters = '&filter_date='+filter_date+'&member_status='+member_status+'&company_id='+company_id+'&approval_status='+approval_status+'&member_auto_id='+member_auto_id+'&status_type='+status_type;
// 		$("#memberoffset").val(parseInt(lastoffset)+parseInt(limit));
// 		$.ajax({
// 			type: "GET",
// 			dataType: "json",
// 			url : "{{ URL::to('/en/get-subscription-more') }}?offset="+lastoffset+searchfilters,
// 			success:function(result){
// 				if(result)
// 				{
// 					res = result.member;
// 					//console.log(res);
// 					$.each(res,function(key,entry){
// 						var table_row = "<tr><td>"+totalhistory+"</td>";
// 							table_row += "<td>"+entry.up_member_name+"</td>";
// 							table_row += "<td id='member_code_"+entry.sub_member_id+"'>"+entry.member_number+"</td>";
// 							table_row += "<td>"+entry.company_name+"</td>";
// 							table_row += "<td>"+entry.up_nric+"</td>";
// 							table_row += "<td>"+entry.Amount+"</td>";
// 							table_row += "<td>"+entry.due_months+"</td>";
// 							table_row += "<td id='member_status_"+entry.sub_member_id+"'>"+entry.status_name+"</td>";
// 							var app_status = entry.approval_status==1 ? '<span class="badge green">Approved</span>' : '<span class="badge red">Pending</span>';
// 							table_row += "<td id='approve_status_"+entry.sub_member_id+"'>"+app_status+"</td>";
// 							var actions='';
// 							var member_no = parseInt(entry.member_number);
// 							if(!isNaN(member_no)){
// 								actions += '<a class="btn btn-sm waves-effect " href="'+baselink+'membership-edit/'+entry.enc_member+'" target="_blank" title="Member details" type="button" name="action"><i class="material-icons">account_circle</i></a>';
// 								actions += ' <a class="btn btn-sm waves-effect amber darken-4" href="'+baselink+'member-history/'+entry.enc_member+'" target="_blank" title="Member History" type="button" name="action"><i class="material-icons">history</i></a>';
// 							}
// 							actions += ' <a class="btn btn-sm waves-effect gradient-45deg-green-teal " onclick="return showApproval('+entry.sub_member_id+')" title="Approval" type="button" name="action"><i class="material-icons">check</i></a>';
// 							table_row += "<td>"+actions+"</td></tr>";
// 							$('#page-length-option tbody').append(table_row);
// 							totalhistory+=1;
// 					});
// 					$("#totalhistory").val(totalhistory);
// 					loader.hideLoader();
// 				}else{
					
// 				}
// 			}
// 		});
		
			
//    }
// });
function ConfirmSubmit(){
	if (confirm("{{ __('Are you sure you want to update?') }}")) {
        return true;
    } else {
        return false;
    }
}
function SubmitAllVerication(){
	var approval_status = '{{ $data['status'] }}';
	var companyid = '{{ $companyid }}';
	var approval_date = '{{ $data['filter_date'] }}';
	var bulknameverify = $("input[name='bulknameverify']:checked").val();
	if ( typeof bulknameverify == 'undefined') {
	  bulknameverify='';
	}
	loader.showLoader();
	var searchfilters = '&approval_status='+approval_status+'&companyid='+companyid+'&approval_date='+approval_date+'&bulknameverify='+bulknameverify;
	$.ajax({
		type: "GET",
		dataType: "json",
		url : "{{ URL::to('/en/save-subscription-approve') }}?offset=0"+searchfilters,
		success:function(result){
			if(result)
			{
				alert('successfully updated');
				loader.hideLoader();
			}else{
				
			}
		},
		error: function( objRequest ){
			alert('Server error, page will get refreshed and start it again');
			location.reload();
		}
	});
}

function checkBankVerify(){
	if($("#bankverify").prop("checked") == true){
		$("#bank_approve").prop("checked",true);
	}else{
		$("#bank_approve").prop("checked",false);
	}
}

function EnableDescription(reasonval){
	if(reasonval==5){
		$(".descriptiontd").removeClass('hide');
	}else{
		$(".descriptiontd").addClass('hide');
	}
}

</script>
@endsection