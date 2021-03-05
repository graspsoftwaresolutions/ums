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
				
				
				{{ $data['title_name'] }} List
				
				&nbsp; <input type="button" id="advancedsearchs" name="advancedsearch" style="margin-bottom: 10px" class="btn hide" value="Advanced search">
				<a class="btn waves-light right " href="{{ route('ecopark.list', app()->getLocale())  }}">{{__('Back')}}</a>
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
							<input id="payment_type" type="text" class="validate" value="{{ $data['payment_type'] }}" readonly name="payment_type">
						</div>
						<div class="col m3 s12 member_status_row @if($data['status']=='') hide @endif">
							<label for="batch_type">{{__('Batch Type') }}</label>
							<select name="batch_type" id="batch_type" class="error browser-default" data-error=".errorTxt6" >
								<option value="">{{__('All') }}</option>
								<option data-type="Others" @if($data['batch_type']==5) selected @endif value="5">Others</option>
                                <option data-type="Batch 1 Member" @if($data['batch_type']==1) selected @endif value="1">Batch 1 Members</option>
                                <option data-type="Batch 1 Non Member" @if($data['batch_type']==2) selected @endif value="2">Batch 1 Non Members</option>
                                <option data-type="Batch 2 Member" @if($data['batch_type']==3) selected @endif value="3">Batch 2 Members</option>
                                <option data-type="Batch 2 Non Member" @if($data['batch_type']==4) selected @endif value="4">Batch 2 Non Members</option>
								
							</select>
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
							<th width="10%">{{__('Member Name')}}</th>
							<th width="9%">{{__('Member Id')}}</th>
							
							<th width="10%">{{__('NRIC-New')}}</th>
							<th width="7%">{{__('Amount')}}</th>
							<th width="7%">{{__('Batch Type')}}</th>
							<th width="10%">{{__('Member Status')}}</th>
							<th width="10%">{{__('Card Status')}}</th>
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
								if($member->type==1){
								    $batch_head = 'Batch 1 Members';
								}else if($member->type==2){
								    $batch_head = 'Batch 1 Non Members';
								}else if($member->type==3){
								    $batch_head = 'Batch 2 Members';
								}else if($member->type==4){
								    $batch_head = 'Batch 2 Non Members';
								}else{
								    $batch_head = 'Others';
								}
							@endphp
							<tr style="overflow-x:auto;">
								<td>{{$slno}}</td>
								<td>{{ $member->full_name }}</td>
								<td id="member_code_" >{{ $member->member_number }}</td>
								
								<td>{{ $member->nric_new }}</td>
								<td>{{ $member->payment_fee }}</td>
								<td>{{ $batch_head }}</td>
								<td id="member_status_">{{ CommonHelper::get_member_status_name($member->status_id) }}</td>
								<td>{{ $member->card_status }}</td>
								<td>
								@if($member->member_id!='' && $member->member_id!=0 )
								<a class="btn btn-sm " href="{{ route('master.viewmembership', [app()->getLocale(), Crypt::encrypt($member->member_id)]) }}" target="_blank" title="Member details" type="button" name="action"><i class="material-icons">remove_red_eye</i></a>
								<a class="btn btn-sm amber darken-4" href="{{ route('member.history', [app()->getLocale(),Crypt::encrypt($member->member_id)]) }}" target="_blank" title="Member History" type="button" name="action"><i class="material-icons">history</i></a>
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
$("#ecopark_sidebars_id").addClass('active');
$("#ecopark_sidebar_li_id").addClass('active');
$("#ecopark_sidebar_a_id").addClass('active');
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

$(window).scroll(function() {   
   var lastoffset = $("#memberoffset").val();
   var limit = "{{$data['data_limit']}}";
   var baselink = base_url +'/{{ app()->getLocale() }}/';
   if($(window).scrollTop() + $(window).height() >= $(document).height()) {
   		var totalhistory = parseInt($("#totalhistory").val());
		loader.showLoader();
		var filter_date = $("#filter_date").val();
		var member_status = $("#member_status").val();
		var approval_status = $("#approval_status").val();
		var company_id = $("#company_id").val();
		var member_auto_id = $("#member_auto_id").val();
		var status_type = $("#status_type").val();
		var searchfilters = '&filter_date='+filter_date+'&member_status='+member_status+'&company_id='+company_id+'&approval_status='+approval_status+'&member_auto_id='+member_auto_id+'&status_type='+status_type;
		$("#memberoffset").val(parseInt(lastoffset)+parseInt(limit));
		$.ajax({
			type: "GET",
			dataType: "json",
			url : "{{ URL::to('/en/get-subscription-more') }}?offset="+lastoffset+searchfilters,
			success:function(result){
				if(result)
				{
					res = result.member;
					//console.log(res);
					$.each(res,function(key,entry){
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
							var actions='';
							var member_no = parseInt(entry.member_number);
							if(!isNaN(member_no)){
								actions += '<a class="btn btn-sm " href="'+baselink+'membership-edit/'+entry.enc_member+'" target="_blank" title="Member details" type="button" name="action"><i class="material-icons">account_circle</i></a>';
								actions += ' <a class="btn btn-sm amber darken-4" href="'+baselink+'member-history/'+entry.enc_member+'" target="_blank" title="Member History" type="button" name="action"><i class="material-icons">history</i></a>';
							}
							actions += ' <a class="btn btn-sm gradient-45deg-green-teal " onclick="return showApproval('+entry.sub_member_id+')" title="Approval" type="button" name="action"><i class="material-icons">check</i></a>';
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
			
   }
});

</script>
@endsection