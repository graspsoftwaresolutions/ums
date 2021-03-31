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

</style>
@php
	$member_status = '';
	
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
				<h4 class="card-title ">
				
				
				{{ $data['title_name'] }} List [ {{ date('Y',$data['str_date']) }} ] 
				
				&nbsp; <input type="button" id="advancedsearchs" name="advancedsearch" style="margin-bottom: 10px" class="btn hide" value="Advanced search">
				<a class="btn waves-light right " href="{{ route('tdf.list', app()->getLocale())  }}">{{__('Back')}}</a>
				</h4> 
				@php
					$userid = Auth::user()->id;
					$get_roles = Auth::user()->roles;
					$user_role = $get_roles[0]->slug;
					
				@endphp
				</h4> 
				
				<form method="post" id="filtersubmit" class="" action="{{ url(app()->getLocale().'/ecopark-status') }}">
					@csrf  
					<div id="advancedsearch" class="row" style="">    
						<div class="col m3 s12 m_date_row hide">
							<label for="search_date">{{__('Date')}}</label>
							<input id="search_date" type="text" class="validate hide" value="{{ date('M/Y',$data['filter_date']) }}" readonly name="search_date">
							<input id="filter_date" type="text" class="validate" value="{{ $data['str_date'] }}" readonly name="date">
							<input id="member_status" type="text" class="validate" value="{{ $data['status'] }}" readonly name="member_status">
							
							<input id="member_type" type="text" class="validate" value="{{ $data['member_type'] }}" readonly name="member_type">
						</div>
						
						<div class="input-field col s12 right-align @if($data['status']=='') hide @endif">
							<input type="button" class="btn waves-light amber darken-4" style="width:130px;color:#fff !important;" id="clear" name="clear" value="{{__('clear')}}">
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
				@php
				
				@endphp
				
				<input type="text" name="memberoffset" id="memberoffset" class="hide" value="{{$data['data_limit']}}"></input>
			
			 	<a id="printbutton" href="#" style="" class="export-button btn right" style="background:#ccc;" onClick="window.print()"> Print</a>
			 	<br>
				<table id="page-length-option" class="display nowrap" width="100%">
					<thead>
						<tr>
							<th width="3%">{{__('S.No')}}</th>
							<th width="25%">{{__('Member Name')}}</th>
							<th width="9%">{{__('Member Id')}}</th>
							
							<th width="10%">{{__('NRIC')}}</th>
							<th width="7%">{{__('Amount')}}</th>
							<th width="10%">{{__('Paid Date')}}</th>
							<th width="10%">{{__('Cheque No')}}</th>
							<th width="15%">{{__('Member Status')}}</th>
							<th width="15%">{{__('Action')}}</th>
						</tr> 
					</thead>
					<tbody>
						@php
							$slno=1;
							//dd($data['member'])
						@endphp
						@foreach($data['member'] as  $key => $member)
							@php
								$batch_head = 'Others';
							@endphp
							<tr id="tr_member_{{ $member->id }}" style="overflow-x:auto;">
								<td>{{$slno}}</td>
								<td>{{ $member->name }}</td>
								<td id="member_code_" >{{ $member->member_number }}</td>
								
								<td>{{ $member->icno }}</td>
								<td>{{ $member->amount }}</td>
								
								<td>{{ $member->paid_date=='0000-00-00' ? '' : date('d-m-Y',strtotime($member->paid_date)) }}</td>
								<td>{{ $member->cheque_no }}</td>
								<td id="member_status_">{{ CommonHelper::get_member_status_name($member->status_id) }}</td>
								<td>
								@if($member->member_id!='' && $member->member_id!=0 )
								<a class="btn btn-sm " href="{{ route('master.viewmembership', [app()->getLocale(), Crypt::encrypt($member->member_id)]) }}" target="_blank" title="Member details" type="button" name="action"><i class="material-icons">remove_red_eye</i></a>

								@else

								<a class="btn btn-sm gradient-45deg-green-teal " onClick="return ChangeMember({{ $member->id }})"  title="Change Member No" type="button" name="action"><i class="material-icons">check</i></a></td>

								@endif
								</td>
								
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
	 <!-- model structure -->

	 <div id="modal-approval" class="modal">
		<form class="formValidate" id="approvalformValidate" method="post" action="{{ route('approval.tdfsave',app()->getLocale()) }}">
			 @csrf	
			<input type="text" class="hide" name="tdf_auto_id" id="tdf_auto_id">
			<div class="modal-content">
				<h4>Member Approval</h4>
				<div class="row">
					<div class="col s12 m6">
						<p>
							Member Name: <span id="view_member_name" class="bold"></span>
							</br>
							</br>
							NRIC: <span id="view_nric" class="bold"></span>
						</p>
					</div>
					<div class="col s12 m6">
						<p>
							Member Id: <span id="view_member_number" class="bold"></span>
							</br>
							</br>
							Amount: <span id="view_paid" class="bold"></span>
						</p>
					</div>
				</div>
				<table>
					<thead>
						<tr>
							
							<th style="padding: 0;">&nbsp;</th>
							
						</tr>
					</thead>
					<tbody>
						<tr class="match_row_2 match_case_row">
							
							<td>
								Member Number Not Matched
								</br>
								<div class="row">
									<div class="col s12">
										Search Name/MemberID/NRIC
										<div class="input-field inline">
											<input id="member_search_match" name="member_search_match" type="text" class="validate" style="width:380px;">
											<input id="member_search_auto_id" name="member_search_auto_id" type="text" class="validate hide">
										</div>
									</div>
									
								</div>
							</td>
							
						</tr>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="modal-action modal-close btn red accent-2 left">Close</button>
				<button type="submit" class="btn waves-light submitApproval" onClick="return ConfirmSubmit()">Submit</button>
			</div>
		</form>
	</div>
	 <!-- model structure -->
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
$("#tdf_sidebars_id").addClass('active');
$("#tdflist_sidebar_li_id").addClass('active');
$("#tdflist_sidebar_a_id").addClass('active');
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
		serviceUrl: "{{ URL::to('/get-auto-member-list') }}?serachkey="+ $("#member_search").val(),
		params: { 
					company_id:  function(){ return '';  },
					filter_date:  function(){ return '';  }, 
					member_status:  function(){ return '';  }, 
					approval_status:  function(){ return '';  }, 
				},
		type:'GET',
		//callback just to show it's working
		onSelect: function (suggestion) {
			 $("#member_search").val(suggestion.number);
			 $("#member_auto_id").val(suggestion.number);
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

function ChangeMember(tdfautoid){
	$(".submitApproval").attr('disabled', false);
	$('.modal').modal();
	$("#tdf_auto_id").val(tdfautoid);

	var url = "{{ url(app()->getLocale().'/tdf_member_info') }}" + '?tdf_auto_id=' + tdfautoid;
	$.ajax({
		url: url,
		type: "GET",
		dataType: "json",
		success: function(result) {
			console.log(result);
			
			$("#view_member_name").html(result.up_member_data.name);
			$("#view_nric").html(result.up_member_data.icno);
			$("#view_member_number").html(result.up_member_data.member_number);
			$("#view_paid").html(result.up_member_data.amount);
		
			$("#modal-approval").modal('open');
			loader.hideLoader();
		}
	});

	loader.showLoader();
}

$(document).on('submit','#approvalformValidate',function(event){
	event.preventDefault();
	$(".submitApproval").attr('disabled', true);
	var url = "{{ url(app()->getLocale().'/ajax_save_tdf_member') }}" ;
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
				
				$("#tr_member_"+result.tdf_auto_id).remove();
				
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
function ConfirmSubmit(){
	if (confirm("{{ __('Are you sure you want to update?') }}")) {
        return true;
    } else {
        return false;
    }
}
</script>
@endsection