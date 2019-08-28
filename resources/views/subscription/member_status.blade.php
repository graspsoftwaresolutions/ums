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
	p.verify-approval{
		margin:0;
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
				
				@if($data['status_type']==1)
					{{ CommonHelper::get_member_status_name($data['status']) }} Members List
				@elseif($data['status_type']==2)
					{{ CommonHelper::get_member_match_name($data['status']) }}'s List
				@elseif($data['status_type']==3)
					SUNDRY CREDITORS List
				@endif
				
				</h4> 
				
				<form method="post" class="hide" id="filtersubmit" action="">
					@csrf  
					<div class="row">    
						<div class="col s3">
							<label for="month_year">{{__('Month')}}</label>
							<input id="month_year" type="text" class="validate datepicker-custom" value="{{date('M/Y')}}" name="month_year">
						</div>
					
						<div class="col s3">
							<label>{{__('Company Name') }}</label>
							<select name="company_id" id="company_id" class="error browser-default selectpicker" data-error=".errorTxt22" >
								<option value="">{{__('Select Company') }}</option>
								
							</select>
							<div class="input-field">
								<div class="errorTxt22"></div>
							</div>
						</div>
						<div class="col s3">
							<label>{{__('Company Branch Name') }}</label>
							<select name="branch_id" id="branch_id" class="error browser-default selectpicker" data-error=".errorTxt23" >
								<option value="">{{__('Select Branch') }}</option>
								
							</select>
							<div class="input-field">
								<div class="errorTxt23"></div>
							</div>
						</div>
						
						
						<div class="input-field col s12 right-align">
							<input type="submit" class="btn" id="search" name="search" value="{{__('Search')}}">
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
							<th width="10%">{{__('Member Name')}}</th>
							<th width="9%">{{__('Member Id')}}</th>
							<th width="10%">{{__('Bank')}}</th>
							<th width="10%">{{__('NRIC')}}</th>
							<th width="7%">{{__('Amount')}}</th>
							<th width="5%">{{__('Due')}}</th>
							<th width="10%">{{__('Member Status')}}</th>
							<th width="10%">{{__('Description')}}</th>
							<th width="10%">{{__('Approval Status')}}</th>
							<th width="15%">{{__('Action')}}</th>
						</tr> 
					</thead>
					<tbody>
						@php
							//dd($data['member'])
						@endphp
						@foreach($data['member'] as  $key => $member)
							<tr>
								<td>{{ $member->up_member_name }}</td>
								<td>{{ $member->member_number }}</td>
								<td>{{ $member->company_name }}</td>
								<td>{{ $member->up_nric }}</td>
								<td>{{ $member->Amount }}</td>
								<td>{{ $member->due }}</td>
								<td>{{ $member->status_name }}</td>
								<td>{{ CommonHelper::get_member_match_name($member->match_id) }}</td>
								<td id="approve_status_{{ $member->match_auto_id }}"><span class="badge {{$member->approval_status==1 ? 'green' : 'red'}}">{{ $member->approval_status==1 ? 'Approved' : 'Pending' }}</span></td>
								<td><a class="btn btn-sm waves-effect " href="{{ route('master.editmembership', [app()->getLocale(), Crypt::encrypt($member->memberid)]) }}" target="_blank" title="Member details" type="button" name="action"><i class="material-icons">account_circle</i></a>
								<a class="btn btn-sm waves-effect amber darken-4" href="{{ route('member.history', [app()->getLocale(),Crypt::encrypt($member->memberid)]) }}" target="_blank" title="Member History" type="button" name="action"><i class="material-icons">history</i></a>
								<a class="btn btn-sm waves-effect gradient-45deg-green-teal " onClick="return showApproval({{$member->match_auto_id}})"  title="Approval" type="button" name="action"><i class="material-icons">check_box</i></a></td>
								
							</tr> 
						@endforeach
					</tbody>
					<input type="text" name="memberoffset" id="memberoffset" class="hide" value=""></input>
				</table> 
			</div>
		</div>
		</br>
		</br>
	</div>
	  <!-- Modal Structure -->
	  <div id="modal-approval" class="modal">
		<form class="formValidate" id="approvalformValidate" method="post" action="{{ route('approval.save',app()->getLocale()) }}">
        @csrf
		<input type="text" class="hide" name="match_auto_id" id="match_auto_id">
		<div class="modal-content">
		  <h4>Monthly subscription member approval</h4>
		   <p>Member Name: <span id="view_member_name" class="bold"></span></p>
		   </hr>
			
				<table>
					<thead>
						<tr>
							<th></th>
							<th>Description</th>
							<th>Approval By</th>
						</tr>
					</thead>
					<tbody>
						<tr class="nric_match_row" style="pointer-events: none;">
							<td>
								<p class="verify-approval">
									<label>
										<input type="checkbox" name="nric_approve" id="nric_approve" value="1" checked />
										<span></span>
									</label>
								</p>
							</td>
							<td>
							NRIC Matched
							
							<td><span id="nric_approved_by" class="bold"></span></td>
						</tr>
						<tr class="nric_not_match_row">
							<td>
								<p class="verify-approval">
									<label>
										<input type="checkbox" name="nric_approve" id="nric_approve" value="1" />
										
									</label>
								</p>
							</td>
							<td>
							NRIC Not Matched
							
							<td></td>
						</tr>
						<tr class="member_match_row">
							<td>
								<p class="verify-approval">
									<label>
										<input type="checkbox" name="member_approve" id="member_approve" value="1" />
										<span></span>
									</label>
								</p>
							</td>
							<td>
								Mismatched Member Name
								</br>
								&nbsp;&nbsp;<span class="bold">From Bank: <span id="registered_member_name" class="bold"></span></span>
								</br>
								&nbsp;&nbsp;<span class="bold">From Union: <span id="uploaded_member_name" class="bold"></span></span>
							</td>
							<td><span id="name_approved_by" class="bold"></span></td>
						</tr>
						<tr class="bank_match_row">
							<td>
								<p class="verify-approval">
									<label>
										<input type="checkbox" name="bank_approve" id="bank_approve" value="1" />
										<span></span>
									</label>
								</p>
							</td>
							<td>
							Mismatched Bank
							</br>
							&nbsp;&nbsp;<span class="bold">From Bank: <span id="registered_bank_name" class="bold"></span></span>
							</br>
							&nbsp;&nbsp;<span class="bold">From Union: <span id="uploaded_bank_name" class="bold"></span></span>
							</td>
							<td><span id="bank_approved_by" class="bold"></span></td>
						</tr>
						
						<tr class="previous_subscription_match_row">
							<td>
								<p class="verify-approval">
									<label>
										<input type="checkbox" name="previous_approve" id="previous_approve" value="1" />
										<span></span>
									</label>
								</p>
							</td>
							<td>Mismatched Previous Subscription</td>
							<td><span id="previous_approved_by" class="bold"></span></td>
						</tr>
						<tr class="struckoff_match_row">
							<td>
								<p class="verify-approval">
									<label>
										<input type="checkbox" name="struckoff_approve" id="struckoff_approve" value="1" />
										<span></span>
									</label>
								</p>
							</td>
							<td>StruckOff Members</td>
							<td><span id="struckoff_approved_by" class="bold"></span></td>
						</tr>
						<tr class="resign_match_row">
							<td>
								<p class="verify-approval">
									<label>
										<input type="checkbox" name="resign_approve" id="resign_approve" value="1" />
										<span></span>
									</label>
								</p>
							</td>
							<td>Resigned Members</td>
							<td><span id="resign_approved_by" class="bold"></span></td>
						</tr>
						<tr class="nric_old_match_row">
							<td>
								<p class="verify-approval">
									<label>
										<input type="checkbox" name="nric_old_approve" id="nric_old_approve" value="1" />
										<span></span>
									</label>
								</p>
							</td>
							<td>NRIC Old Matched</td>
							<td><span id="nric_old_approved_by" class="bold"></span></td>
						</tr>
						<tr class="nric_bank_match_row">
							<td>
								<p class="verify-approval">
									<label>
										<input type="checkbox" name="nric_bank_approve" id="nric_bank_approve" value="1" />
										<span></span>
									</label>
								</p>
							</td>
							<td>NRIC By Bank Matched</td>
							<td><span id="nric_bank_approved_by" class="bold"></span></td>
						</tr>
						<tr class="previous_unpaid_match_row">
							<td>
								<p class="verify-approval">
									<label>
										<input type="checkbox" name="previous_unpaid_approve" id="previous_unpaid_approve" value="1" />
										<span></span>
									</label>
								</p>
							</td>
							<td>Previous Subscription Unpaid</td>
							<td><span id="previous_unpaid_approved_by" class="bold"></span></td>
						</tr>
					</tbody>
				</table>
		</div>
		<div class="modal-footer">
		  <button type="button" class="modal-action modal-close btn waves-effect red accent-2 left">Close</button>
		  <button type="submit" class="btn waves-effect waves-light submitApproval">Submit</button>
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
$("#subscriptions_sidebars_id").addClass('active');
$("#subscription_sidebar_li_id").addClass('active');
$("#subscription_sidebar_a_id").addClass('active');
	
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
	  function showApproval(match_id){
		   $(".submitApproval").attr('disabled', false);
		   $('.modal').modal();
		   $("#match_auto_id").val(match_id);
		   loader.showLoader();
		    var url = "{{ url(app()->getLocale().'/subscription_member_info') }}" + '?sub_match_auto_id=' + match_id;
			$.ajax({
				url: url,
				type: "GET",
				dataType: "json",
				success: function(result) {
					var match_data = result.match;
					$(".bank_match_row").addClass('hide');
					$(".nric_match_row").addClass('hide');
					$(".member_match_row").addClass('hide');
					$(".nric_not_match_row").addClass('hide');
					$(".struckoff_match_row").addClass('hide');
					$(".resign_match_row").addClass('hide');
					$(".nric_old_match_row").addClass('hide');
					$(".previous_subscription_match_row").addClass('hide');
					$(".nric_bank_match_row").addClass('hide');
					$(".previous_unpaid_match_row").addClass('hide');
					$("#view_member_name").html(result.up_member_name);
					if(match_data.match_id==1){
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
					}
					$("#modal-approval").modal('open');
					loader.hideLoader();
				}
			});
	  }
	$(document).on('submit','#approvalformValidate',function(event){
		event.preventDefault();
		$(".submitApproval").attr('disabled', true);
		var url = "{{ url(app()->getLocale().'/ajax_save_approval') }}" ;
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
					var badge_label = result.approval_status == 1 ? 'Approved' : 'Pending';
					$("#approve_status_"+result.match_auto_id).html('<span class="badge '+badge_color+'">'+badge_label+'</span>');
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

</script>
@endsection