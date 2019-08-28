@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
<link rel="stylesheet" type="text/css"
href="{{ asset('public/assets/vendors/data-tables/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css"
href="{{ asset('public/assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css') }}">
@endsection
@section('headSecondSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/pages/data-tables.css') }}">
<style type="text/css">
	.autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; cursor:pointer; }
	.autocomplete-suggestion { padding: 8px 5px; white-space: nowrap; overflow: hidden; }
	.autocomplete-selected { background: #F0F0F0; }
	.autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
	.autocomplete-group { padding: 8px 5px; }
	.autocomplete-group strong { display: block; border-bottom: 1px solid #000; }
	.padding-left-10{
		padding-left:10px;
	}
	.padding-left-20{
		padding-left:20px;
	}
	.padding-left-24{
		padding-left:24px;
	}
	.padding-left-40{
		padding-left:40px;
	}
	#irc_confirmation_area {
		pointer-events: none;
	}
	.branch 
	{
    	pointer-events: none;
		background-color: #f4f8fb !important;
	}
	@media (max-width: 500px) {
		.modal {
			width: 90% !important;
		}
	}
</style>
@endsection
@section('main-content')
<div id="">
<div class="row">
	<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
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
									<h5 class="breadcrumbs-title mt-0 mb-0">{{__('Bank Subscription List')}}</h5>
									<ol class="breadcrumbs mb-0">
										<li class="breadcrumb-item"><a
												href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard')}}</a>
										</li>
										<li class="breadcrumb-item active">{{__('Subscription')}}
										</li>
									</ol>
								</div>
								<div class="col s2 m6 l6 ">
									<a class="btn waves-effect waves-light breadcrumbs-btn right" href="{{route('subscription.sub_fileupload.sub_company',app()->getLocale())}}">{{__('Back') }}</a>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
					<div class="col s12">
					<div class="card">
					@php
					$datacmpy = $data['company_subscription_list'][0];   
					$enccompany_auto_id = Crypt::encrypt($data['company_auto_id']);
					$company_id = $data['company_auto_id'];
					
					@endphp
						<div class="card-content">
							<div class="row">
								<div class="col m6">
									<div class="row">
										<div class="col m6"> 
										<h4 class="card-title" style="font-weight: bold; font-size: 16px">{{__('Bank Member List')}}</h4>
										</div>
										<div class="col m6">
										<a class="waves-effect waves-light gradient-45deg-purple-deep-purple btn modal-trigger" href="#modal1">Subscription Details</a>
										</div>
									</div>
								
									<h4 class="card-title" style="font-weight: bold; font-size: 16px">{{__('Month :')}} @php echo date('M-Y',strtotime($datacmpy->Date)); @endphp</h4>
									<h4 class="card-title" style="font-weight: bold; font-size: 16px">{{__('Total Members Count :')}} {{ isset($data['tot_count']) ? $data['tot_count'] : ""}}</h4>
									<div class="row">
										<div class="col m10">
											<h4 class="card-title" style="font-weight: bold; font-size: 16px">{{__('Bank : ')}} {{ isset($datacmpy) ? $datacmpy->short_code : ""}} - {{ isset($datacmpy) ? $datacmpy->company_name : ""}} </h4>	
										</div>
										<div class="col m2">
											<input type="button" name="submit" id="advancedsearchs" class="btn" value="Advanced Search">
										</div>
									</div>
								</div>
								@if($data['non_updated_rows']>0)
								<div class="col m6">
									<div class="row">
										<div class="col m5">
											<div id="scanning-details" class="gradient-45deg-amber-amber padding-3 medium-small" style="color:#fff;    font-size: 13px !important;line-height:24px;">
											{{__('Please update membership details')}}
												
											</div>
											
										</div>
										<div class="col m3">
											<a id="submit-download" href="{{ route('subscription.viewscan', [app()->getLocale(),$enccompany_auto_id])  }}" class="waves-effect waves-light cyan btn btn-primary form-download-btn right" type="button">{{__('Update details')}}</a>
										</div>
										<div class="col m4">
											<a id="pending_members" href="{{ route('subscription.pendingmembers', [app()->getLocale(),$enccompany_auto_id])  }}" class="waves-effect waves-light btn btn-primary form-download-btn right" type="button">{{__('Pending Members')}} </a>
										</div>
									</div>
								</div>
								@endif
							</div>
							<div class="row">
								<div class="card advancedsearch" style="dispaly:none;">
								<div class="col s12">
								<form method="post" id="advancedsearch" >
								@csrf  
									<div class="row">   
										<div class="col s4">
											<label>{{__('Race') }}</label>
											<select name="race_id" id="race_id" class="error browser-default selectpicker" data-error=".errorTxt22" >
												<option value="">{{__('Select Race') }}</option>
												@foreach($data['race_view'] as $values)
													<option value="{{$values->id}}">{{$values->race_name}}</option>
												@endforeach
											</select>
											<div class="input-field">
												<div class="errorTxt22"></div>
											</div>
										</div>
										<div class="col s4">
											<label for="member_number"
												class="common-label force-active">{{__('Membership Number') }}*</label>
											<input id="member_number" name="member_number"  class="common-input"
												type="text" data-error=".errorTxt1" autocomplete="off">
											<input type="hidden" name="memberid" id="memberid">
											<div class="errorTxt1"></div>
										</div>
										
										<div class="col s4">
											<label>{{__('Designation') }}</label>
											<select name="designation_id" id="designation_id" class="error browser-default selectpicker" data-error=".errorTxt23" >
												<option value="">{{__('Select Designation') }}</option>
												 @foreach($data['designation_view'] as $values)
													<option value="{{$values->id}}">{{$values->designation_name}}</option>
												@endforeach
											</select>
											<div class="input-field">
												<div class="errorTxt23"></div>
											</div>
										</div>
										</div>
										<div class="row">
											<div class="input-field col s6 right">
												<input type="button" id="clear"  class="btn" name="clear" value="{{__('Clear')}}">
											</div>
											<div class="input-field col s6 right-align">
												<input type="submit"  class="btn" name="search" value="{{__('Search')}}">
											</div>
										</div>
								</div>
								</form> 
							</div>
								</div>
							
						@include('includes.messages')
						<div class="row">
						<div class="col s12">  
							<ul class="tabs">  
								<li class="tab col s3"><a class="active tab_status" href="#inbox" id="all">All</a></li>  
								@foreach($data['member_stat'] as  $key => $member_stat)
								<li class="tab col s2"><a class="tab_status"  href="#member{{ $member_stat->id }}" id="m{{ $member_stat->id }}" style="color:{{$member_stat->font_color}}">{{ isset($member_stat->id) ? CommonHelper::get_member_status_name($member_stat->id) : "" }}</a></li>  
								@endforeach
							</ul>  
						</div>  
							<div id="inbox" class="col s12">
								<div class="col sm12 m12">
									<table id="page-length-option" class="datatable-display">
										<thead>
										<tr>
										<th>{{__('Member Name')}}</th>
										<th>{{__('Member Code')}}</th>
										<th>{{__('NRIC')}}</th>
										<th>{{__('Amount')}}</th>
										<th>{{__('Status')}}</th>
										<th>{{__('Action')}}</th>
										</tr>
										</thead>                                                        
									   
									</table>
								</div>	
							</div>  
							@php
							$loopcount=0;
							@endphp
							@foreach($data['member_stat'] as  $key => $member_stat)
							<div id="member{{ $member_stat->id }}" class="col s12">
								<div class="col sm12 m12">
									<table id="page-length-option" class="datatable-display-{{ $member_stat->id }}" width="100%">
										<thead>
										<tr>
										<th>{{__('Member Name')}}</th>
										<th>{{__('Member Code')}}</th>
										<th>{{__('NRIC')}}</th>
										<th>{{__('Amount')}}</th>
										<th>{{__('Action')}}</th>
										</tr>
										</thead>                                                        
									   
									</table>
								</div>	
							</div>   
							@php
								$loopcount++;
							@endphp
							@endforeach
						   
							</div>
							
						</div>
						<div id="modal1" class="modal">
						<div class="modal-content">
							<div class="row">
								<div class="col s12 m12">
									<span class="btn btn-sm-all right modal-close">x</span>
								</div>
								<div class="col s12 m6">
									@php $cond_date = date('Y-m-01',strtotime($datacmpy->Date)); @endphp
									<div class="card darken-1" id="member_status_div">
										
										<span style="text-align:center;padding:5px;" class="card-title">{{__('Member Status') }} <span class="right datamonth"></span> </span>
										<table class="collection" id="memberstatustable">
											<thead>
												<tr style="background:#3e57e6;color:white;text-align:center;" class="collection-item avatar">
													<td>{{__('SlNo') }}</td>
													<td>{{__('Status') }}</td>
													 <td>{{__('Count') }}</td>
													<td>{{__('Amount') }}</td>
												</tr>
												
												@php 
												$get_roles = Auth::user()->roles;
												$user_role = $get_roles[0]->slug;
												$user_id = Auth::user()->id;
												@endphp 
												@foreach($data['member_stat'] as  $key => $stat)
													<tr>
														<td>{{ $key+1 }} </td>
														<td>{{ $stat->status_name }}</td>
														<td id="member_status_count_{{ $stat->id }}">{{ CommonHelper::statusSubsMembersCompanyCount($stat->id, $user_role, $user_id,$company_id,$cond_date) }}</td>
														<td id="member_status_amount_{{ $stat->id }}">{{ round(CommonHelper::statusMembersCompanyAmount($stat->id, $user_role, $user_id,$company_id,$cond_date), 0) }} </td>
													</tr>
												@endforeach
												<tr>
													<td>{{ count($data['member_stat'])+1 }} </td>
													<td>SUNDRY CREDITORS</td>
													<td id="company_member_status_count_sundry">{{ CommonHelper::statusSubsCompanyMatchCount(2, $user_role, $user_id,$company_id,$cond_date) }}</td>
													<td id="company_member_status_amount_sundry">{{ round(CommonHelper::statusSubsCompanyMatchAmount(2, $user_role, $user_id,$company_id,$cond_date), 0) }}</td>
												</tr>
											</thead>
											<tbody>
												
											</tbody>
										</table>
									</div>
								</div>
								<!--Approval Status-->
								<div class="col s12 m6">
									<div class="card darken-1">
										<span style="text-align:center;padding:5px;" class="card-title">{{__('Approval Status') }} <span class="right datamonth"></span></span>
										<table class="collection" id="approvalstatustable">
											<tr style="background:#3e57e6;color:white;text-align:center;" class="collection-item avatar">
												<td>{{__('SlNo') }}</td>
												<td>{{__('Description') }}</td>
												<td>{{__('Count') }}</td>
											</tr>
											@foreach($data['approval_status'] as  $key => $stat)
											<tr>
												<td>{{ $key+1 }} </td>
												<td>{{ $stat->match_name }}</td>
												<td id="approval_status_count_{{ $stat->id }}">{{ CommonHelper::statusSubsCompanyMatchCount($stat->id, $user_role, $user_id,$company_id,$cond_date) }}</td>
											</tr>
											@endforeach
										</table>
									</div>
								</div>
							</div>
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
		</div>
		</div>
	</div>
</div>
</div>
@endsection
@section('footerSection')

<script src="{{ asset('public/assets/vendors/data-tables/js/jquery.dataTables.min.js') }}" type="text/javascript">
</script>
<script src="{{ asset('public/assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js') }}"
type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/data-tables/js/dataTables.select.min.js') }}" type="text/javascript">
</script>
@endsection
@section('footerSecondSection')
<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/assets/js/scripts/form-validation.js')}}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/scripts/data-tables.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jquery.autocomplete.min.js') }}" type="text/javascript"></script>
<script>
$("#subscriptions_sidebars_id").addClass('active');
$("#subcomp_sidebar_li_id").addClass('active');
$("#subcomp_sidebar_a_id").addClass('active');
//Model
$(document).ready(function() {
$('.modal').modal();
});
$(document).ready(function(){
$('.tab_status').click(function(){
	console.log($(this).attr('id'));
});
});
$('#advancedsearch').hide();
 $('#advancedsearchs').click(function(){
	$('#advancedsearch').toggle();
	});
$('#clear').click(function(){
	$(".selectpicker").val('').trigger("change"); 
	$("#member_number").val(''); 
});	
/*$(document).ready(function(){
	//loader.showLoader();
	var horizStepper = document.querySelector('#horizStepper');
	var horizStepperInstace = new MStepper(horizStepper, {
		// options
		firstActive: 0,
		showFeedbackPreloader: true,
		autoFormCreation: true,
		validationFunction: defaultValidationFunction,
		stepTitleNavigation: true,
		feedbackPreloader: '<div class="spinner-layer spinner-blue-only">...</div>'
	});

	horizStepperInstace.resetStepper();
	

});
function defaultValidationFunction(horizStepper, activeStepContent) {
  //  $statid =$(this).closest($('#status_id').val());
   // console.log($statid);
	
	/* var inputs = activeStepContent.querySelectorAll('input, textarea, select');
   for (let i = 0; i < inputs.length; i++) 
   {
	   if (!inputs[i].checkValidity()) {
		   jQuery("#submit-member").trigger('submit');
		   return false;
	   }
   } */
  
  // return true;
// }*/
$("#member_number").devbridgeAutocomplete({
	//lookup: countries,
	serviceUrl: "{{ URL::to('/get-member-list') }}?searchkey="+ $("#member_number").val(),
	type:'GET',
	//callback just to show it's working
	onSelect: function (suggestion) {
			$("#member_number").val(suggestion.member_number);
			$.ajax({
				url: "{{ URL::to('/get-member-list-values') }}?member_id="+ $("#member_number").val(),
                type: "GET",
				dataType: "json",
				success: function(res) {		
					$('#member_number').val(res.member_number);
					$('#memberid').val(res.memberid);
				}
			});
			
	},
	showNoSuggestionNotice: true,
	noSuggestionNotice: 'Sorry, no matching results',
	onSearchComplete: function (query, suggestions) {
		if(!suggestions.length){
			//$("#member_number").val('');
		}
	}
});
$(document.body).on('click', '.autocomplete-no-suggestion' ,function(){
	$("#member_number").val('');
});

$(function () {
   var dataTable =   $('.datatable-display').DataTable({
	"responsive": true,
	"lengthMenu": [
		[10, 25, 50, 100],
		[10, 25, 50, 100]
	],
	
	/* "lengthMenu": [
		[10, 25, 50, -1],
		[10, 25, 50, "All"]
	], */
	"processing": true,
	"serverSide": true,
	"ajax": {
		"url": "{{ url(app()->getLocale().'/ajax_submember_list') }}?company_id="+{{$data['company_auto_id']}}+"&status=all",
		"dataType": "json",
		"type": "POST",
		headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
       	},
		'data': function(data){
			 var race_id = $('#race_id').val();
			 var memberid      = $('#memberid').val();
			 var designation_id = $('#designation_id').val();
			
			 data.race_id = race_id;
			 data.memberid = memberid;
			 data.designation_id = designation_id;
		  //console.log(data);
		  data._token = "{{csrf_token()}}";
		}
	},
	"columns": [{
			"data": "Name"
		},
		{
			"data": "membercode"
		},
		{
			"data": "nric"
		},
		{
			"data": "amount"
		},
		
		{
			"data": "statusId"
		},
		{
			"data": "options"
		}
	],
	"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
			$('td', nRow).css('color', aData.font_color );
		}
});
/* @if($loopcount == count($data['member_stat']))
@foreach($data['member_stat'] as  $key => $member_stat)
@if($member_stat->id==1)
@endif
@endforeach
@endif */
  var dataTable_1 = $('.datatable-display-1').DataTable({
	"responsive": true,
	"lengthMenu": [
		[10, 25, 50, 100],
		[10, 25, 50, 100]
	],
	/* "lengthMenu": [
		[10, 25, 50, -1],
		[10, 25, 50, "All"]
	], */
	  
	"processing": true,
	"serverSide": true,
	"ajax": {
	"url": "{{ url(app()->getLocale().'/ajax_submember_list') }}?company_id="+{{$data['company_auto_id']}}+"&status=1",
		"dataType": "json",
		"type": "POST",
		headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
       },
		'data': function(data){
			var race_id = $('#race_id').val();
			 var memberid      = $('#memberid').val();
			 var designation_id = $('#designation_id').val();

				data.race_id = race_id;
				data.memberid = memberid;
				data.designation_id = designation_id;
			_token: "{{csrf_token()}}";
		}
	},
	"columns": [{
			"data": "Name"
		},
		{
			"data": "membercode"
		},
		{
			"data": "nric"
		},
		{
			"data": "amount"
		},
		{
			"data": "options"
		}
	],
	"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
			$('td', nRow).css('color', aData.font_color );
	},
	error: function (xhr, error, thrown) {
		alert( 'You are not logged in' );
	}
});
 var dataTable_2 = $('.datatable-display-2').DataTable({
	"responsive": true,
	"lengthMenu": [
		[10, 25, 50, 100],
		[10, 25, 50, 100]
	],
	/* "lengthMenu": [
		[10, 25, 50, -1],
		[10, 25, 50, "All"]
	], */
	  
	"processing": true,
	"serverSide": true,
	"ajax": {
	"url": "{{ url(app()->getLocale().'/ajax_submember_list') }}?company_id="+{{$data['company_auto_id']}}+"&status=2",
		"dataType": "json",
		"type": "POST",
		headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
       },
		'data': function(data){
			var race_id = $('#race_id').val();
			 var memberid      = $('#memberid').val();
			 var designation_id = $('#designation_id').val();

				data.race_id = race_id;
				data.memberid = memberid;
				data.designation_id = designation_id;
			_token: "{{csrf_token()}}";
		}
	},
	"columns": [{
			"data": "Name"
		},
		{
			"data": "membercode"
		},
		{
			"data": "nric"
		},
		{
			"data": "amount"
		},
		{
			"data": "options"
		}
	],
	"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
			$('td', nRow).css('color', aData.font_color );
	},
	error: function (xhr, error, thrown) {
		alert( 'You are not logged in' );
	}
});
 var dataTable_3 = $('.datatable-display-3').DataTable({
	"responsive": true,
	"lengthMenu": [
		[10, 25, 50, 100],
		[10, 25, 50, 100]
	],
	/* "lengthMenu": [
		[10, 25, 50, -1],
		[10, 25, 50, "All"]
	], */
	  
	"processing": true,
	"serverSide": true,
	"ajax": {
	"url": "{{ url(app()->getLocale().'/ajax_submember_list') }}?company_id="+{{$data['company_auto_id']}}+"&status=3",
		"dataType": "json",
		"type": "POST",
		headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
       },
		'data': function(data){
			var race_id = $('#race_id').val();
			 var memberid      = $('#memberid').val();
			 var designation_id = $('#designation_id').val();

				data.race_id = race_id;
				data.memberid = memberid;
				data.designation_id = designation_id;
			_token: "{{csrf_token()}}";
		}
	},
	"columns": [{
			"data": "Name"
		},
		{
			"data": "membercode"
		},
		{
			"data": "nric"
		},
		{
			"data": "amount"
		},
		{
			"data": "options"
		}
	],
	"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
			$('td', nRow).css('color', aData.font_color );
	},
	error: function (xhr, error, thrown) {
		alert( 'You are not logged in' );
	}
});
 var dataTable_4 = $('.datatable-display-4').DataTable({
	"responsive": true,
	"lengthMenu": [
		[10, 25, 50, 100],
		[10, 25, 50, 100]
	],
	/* "lengthMenu": [
		[10, 25, 50, -1],
		[10, 25, 50, "All"]
	], */
	  
	"processing": true,
	"serverSide": true,
	"ajax": {
	"url": "{{ url(app()->getLocale().'/ajax_submember_list') }}?company_id="+{{$data['company_auto_id']}}+"&status=4",
		"dataType": "json",
		"type": "POST",
		headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
       },
		'data': function(data){
			var race_id = $('#race_id').val();
			 var memberid      = $('#memberid').val();
			 var designation_id = $('#designation_id').val();

				data.race_id = race_id;
				data.memberid = memberid;
				data.designation_id = designation_id;
			_token: "{{csrf_token()}}";
		}
	},
	"columns": [{
			"data": "Name"
		},
		{
			"data": "membercode"
		},
		{
			"data": "nric"
		},
		{
			"data": "amount"
		},
		{
			"data": "options"
		}
	],
	"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
			$('td', nRow).css('color', aData.font_color );
	},
	error: function (xhr, error, thrown) {
		alert( 'You are not logged in' );
	}
});
$(document).on('submit','form#advancedsearch',function(event){
	event.preventDefault();
	dataTable.draw();
	dataTable_1.draw();
	dataTable_2.draw();
	dataTable_3.draw();
	dataTable_4.draw();
	/* @if($loopcount == count($data['member_stat']))
	@foreach($data['member_stat'] as  $key => $member_stat)
	dataTable_{{$member_stat->id}}.draw();
	@endforeach
	@endif */
});
});

function ConfirmDeletion() {
if (confirm("{{ __('Are you sure you want to delete?') }}")) {
	return true;
} else {
	return false;
}
}

function showaddForm() {
$('.edit_hide').show();
$('.add_hide').show();
$('.edit_hide_btn').hide();
$('#state_name').val("");
$('.modal').modal();
$('#updateid').val("");
}

function showeditForm(countryid) {
$('.edit_hide').hide();
$('.add_hide').hide();
$('.edit_hide_btn').show();
$('.modal').modal();
loader.showLoader();
var url = "{{ url(app()->getLocale().'/state_detail') }}" + '?id=' + countryid;
$.ajax({
	url: url,
	type: "GET",
	success: function(result) {
		$('#updateid').val(result.id);
		$('#updateid').attr('data-autoid', result.id);
		$('#country_id').val(result.country_id);
		$('#state_name').val(result.state_name);
		loader.hideLoader();
		$("#modal_add_edit").modal('open');
	}
});
}
</script>
@endsection