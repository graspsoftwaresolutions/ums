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
							<th width="20%">{{__('Member Name')}}</th>
							<th width="10%">{{__('Member Id')}}</th>
							<th width="10%">{{__('Bank')}}</th>
							<th width="10%">{{__('NRIC')}}</th>
							<th width="10%">{{__('Amount')}}</th>
							<th width="10%">{{__('Due')}}</th>
							<th width="10%">{{__('Status')}}</th>
							<th>{{__('Action')}}</th>
						</tr> 
					</thead>
					<tbody>
						@php
							//dd($data['member'])
						@endphp
						@foreach($data['member'] as  $key => $member)
							<tr>
								<td>{{ $member->member_name }}</td>
								<td>{{ $member->member_number }}</td>
								<td>{{ $member->company_name }}</td>
								<td>{{ $member->ic }}</td>
								<td>{{ $member->Amount }}</td>
								<td>{{ $member->due }}</td>
								<td>{{ $member->status_name }}</td>
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
		<form class="formValidate" id="approvalformValidate" method="post" action="{{ route('master.savecountry',app()->getLocale()) }}">
        @csrf
		<input type="text" class="hide" name="match_auto_id" id="match_auto_id">
		<div class="modal-content">
		  <h4>Monthly subscription member approval</h4>
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
							<td></td>
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
							&nbsp;&nbsp;<span class="bold">From Bank: Affin bank</span>
							</br>
							&nbsp;&nbsp;<span class="bold">From Union: Affin bank Berhard</span>
							</td>
							<td></td>
						</tr>
						<tr class="nric_match_row">
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
						<tr class="hide">
							<td>
								<p class="verify-approval">
									<label>
										<input type="checkbox" name="approve" id="approve" value="1" />
										<span></span>
									</label>
								</p>
							</td>
							<td>Mismatched Previous Subscription</td>
							<td></td>
						</tr>
					</tbody>
				</table>
		</div>
		<div class="modal-footer">
		  <button type="button" class="modal-action modal-close btn waves-effect red accent-2 left">Close</button>
		  <button type="submit" class="btn waves-effect waves-light">Submit</button>
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
					if(match_data.match_id==3){
						$(".member_match_row").removeClass('hide');
						$(".bank_match_row").addClass('hide');
						$(".nric_match_row").addClass('hide');
						$("#registered_member_name").html(result.registered_member_name);
						$("#uploaded_member_name").html(result.uploaded_member_name);
					}else if(match_data.match_id==4){
						$(".member_match_row").addClass('hide');
						$(".bank_match_row").removeClass('hide');
						$(".nric_match_row").addClass('hide');
						$("#registered_member_name").html('');
						$("#uploaded_member_name").html('');
					}else if(match_data.match_id==2){
						$(".nric_match_row").removeClass('hide');
						$(".member_match_row").addClass('hide');
						$(".bank_match_row").addClass('hide');
						$("#registered_member_name").html('');
						$("#uploaded_member_name").html('');
					}else{
						$(".member_match_row").addClass('hide');
						$(".bank_match_row").addClass('hide');
						$(".nric_match_row").addClass('hide');
						$("#registered_member_name").html('');
						$("#uploaded_member_name").html('');
					}
					$("#modal-approval").modal('open');
					loader.hideLoader();
				}
			});
	  }
  

</script>
@endsection