@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/data-tables/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css') }}">
@endsection
@section('headSecondSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/pages/data-tables.css') }}">
<link href="{{ asset('public/assets/css/jquery-ui-month.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/css/MonthPicker.min.css') }}" rel="stylesheet" type="text/css" />

<style>
	#main .section-data-tables .dataTables_wrapper table.dataTable div.datepicker-calendar th, #main .section-data-tables .dataTables_wrapper table.dataTable  div.datepicker-calendar td {
		padding: 0;
		white-space: nowrap;
		color: black;
	}
	.datepicker-date-display .date-text {
		font-size: 1rem;
		font-weight: 500;
		line-height: 47px;
		display: block;
	}
</style>
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
										<h5 class="breadcrumbs-title mt-0 mb-0">{{__('Transfer History') }}</h5>
										<ol class="breadcrumbs mb-0">
										<li class="breadcrumb-item"><a href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard') }}</a>
											<li class="breadcrumb-item active"><a href="#">{{__('Member Transfer History List') }}</a>
											</li>
											
										</ol>
									</div>
									<div class="col s2 m6 l6 ">
										<a class="btn waves-effect waves-light breadcrumbs-btn hide right" href="{{ route('master.addmembership', app()->getLocale())  }}">{{__('New Registration') }}</a>
										
									</div>
								</div>
							</div>
						</div>
						<div class="col s12">
							<div class="card">
								<div class="card-content">
									<h4 class="card-title">
										<a class="btn waves-effect breadcrumbs-btn waves-light amber right hide" href="{{ route('master.membershipnew', app()->getLocale() )}}">{{__('Pending members list') }}</a>
										
									</h4>
									<div class="row">
										<input type="button" id="advancedsearchs" name="advancedsearch" style="margin-bottom: 10px" class="btn col s12 m4 l3" value="Advanced search">
									</div> 
									<div class="card advancedsearch" style="display:none;">
										<div class="col s12">
											<form method="post" id="advancedsearchform">
												@csrf 
												<div class="row">
													<div class="col s12 m6 l3">
														<label>{{__('Month/Year') }}</label>
														<input type="text" id="5" id="transferdate" class="transferdate datepicker-custom" autocomplete="off" required value="{{date('M/Y')}}" data-error=".errorTxt23" />
														<div class="input-field">
															<div class="errorTxt23"></div>
														</div>
													</div>
													<div class="col s12 m6 l3">
														<label for="member_auto_id">{{__('Member Number/Name')}}</label>
														<input id="member_search" type="text" class="validate " name="member_search" data-error=".errorTxt24">
														<input id="member_auto_id" type="text" class="hide" class="validate " name="member_auto_id">
														<div class="input-field">
															<div class="errorTxt24"></div>
														</div>
													</div>
													<div class="col s12 m6 l3">
														
													</div>
												</div> 
												<div class="row">
													<div class="input-field col s6 right">
														<input type="button" class="btn" style="width:130px" id="clear" name="clear" value="{{__('clear')}}">
													</div>
													<div class="input-field col s6 right-align">
														<input type="submit" id="search" class="btn" name="search" value="{{__('Search')}}">
													</div>
												</div>
											</form>
										</div>
									</div>
									@include('includes.messages')
									<div class="row">
										<div class="col s12">
											<table id="page-length-option" class="display" width="100%">
												<thead>
													
													<tr>
														<th>{{__('Member Name') }}</th>
														<th>{{__('MemberID') }}</th>
														<th>{{__('From bank')}} </th>
														<th>{{__('To Bank')}}</th>
														<th>{{__('Date')}}</th>
														<th  style="text-align:center;">{{__('Action') }}</th>
													</tr>
													 
												</thead>
												
											</table>
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
@endsection
@section('footerSection')
<script src="{{ asset('public/assets/vendors/data-tables/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/data-tables/js/dataTables.select.min.js') }}" type="text/javascript"></script>
@endsection
@section('footerSecondSection')

<script src="{{ asset('public/assets/js/scripts/data-tables.js') }}" type="text/javascript"></script>

 <script src="{{ asset('public/assets/js/jquery-ui-month.min.js')}}"></script>
 <script src="{{ asset('public/js/MonthPicker.min.js')}}"></script>
 <script src="{{ asset('public/assets/js/jquery.autocomplete.min.js') }}" type="text/javascript"></script>
<script>
 $("#member_transfer_sidebar_a_id").addClass('active');
	
 $(function () {
	
    var dataTable =  $('#page-length-option').DataTable({
			"responsive": true,
			"searching": false,
			"lengthMenu": [
				[10, 25, 50, -1],
				[10, 25, 50, "All"]
			],
			"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "{{ url(app()->getLocale().'/ajax_transfer_list/') }}",
				"dataType": "json",
				"type": "POST",
				'data': function(data){
				  var datefilter = $('.transferdate').val();
				  var member_auto_id = $('#member_auto_id').val();
				  //console.log(datefilter);
				  // Append to data
				  //data.search['value'] = datefilter;
				  data.datefilter = datefilter;
				  data.memberid = member_auto_id;
				  data._token = "{{csrf_token()}}";
			   }
				//"data": {_token: "{{csrf_token()}}", "datefilter" : $('#transferdate-search-input').val()},
			},
			"search-transfer" : 12,
			"columns": [
				{"data" : "member_name"},
				{"data" : "member_number"},
				{"data":  "frombank"},
				{"data" : "tobank"},
				{"data" : "transfer_date"},
				{"data":  "options"}
			]
		});
		$("#employee-grid_filter").css("display","none");  // hiding global search box
 
		// $('.transferdate').on( 'keyup click change', function () {
		// 	var i =$(this).attr('id');  // getting column index
		// 	var v =$(this).val();  // getting search input value
		// 	dataTable.draw();
		
		// } );
		$(document).on('submit','form#advancedsearchform',function(event){
			event.preventDefault();
			dataTable.draw();
			//loader.showLoader();
		});
		 
		 
});
$('.datepicker-custom').MonthPicker({ 
	Button: false, 
	MonthFormat: 'M/yy',
	OnAfterChooseMonth: function() { 
		//dataTable.draw();
	} 
});
$('#advancedsearchs').click(function(){
	$('.advancedsearch').toggle();
});
$('#clear').click(function(){
	$('.transferdate').val("");
	$('#member_search').val("");
	$('#member_auto_id').val("");
	$(".selectpicker").val('').trigger("change"); 
});
$(document).ready(function(){
		 $('.datepicker-custom').MonthPicker({ 
			Button: false, 
			MonthFormat: 'M/yy',
			OnAfterChooseMonth: function() { 
				//getDataStatus();
			} 
		 });
		$("#member_search").devbridgeAutocomplete({
			//lookup: countries,
			serviceUrl: "{{ URL::to('/get-company-member-list') }}?serachkey="+ $("#member_search").val(),
			params: { 
						company_id:  function(){ return $("#company_id").val();  },
						branch_id:  function(){ return $("#branch_id").val();  } 
					},
			type:'GET',
			//callback just to show it's working
			onSelect: function (suggestion) {
				 $("#member_search").val(suggestion.member_code);
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
		$(document.body).on('click', '.autocomplete-no-suggestion' ,function(){
			$("#member_search").val('');
		});
	
	});
 </script>
@endsection