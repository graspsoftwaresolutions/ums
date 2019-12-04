@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/themes/vertical-modern-menu-template/materialize.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/themes/vertical-modern-menu-template/style.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/vendors/data-tables/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css') }}">
@endsection
@section('headSecondSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/pages/data-tables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/datepicker.css') }}">
<style>
	.btn, .btn-sm-one {
		line-height: 36px;
		display: inline-block;
		height: 35px;
		padding: 0 7px;
		vertical-align: middle;
		text-transform: uppercase;
		border: none;
		border-radius: 4px;
		-webkit-tap-highlight-color: transparent;
	}
</style>
@endsection
@section('main-content')
@php 

@endphp
<div class="row">
	<div class="col s12">
		<div class="card">
			<div class="card-content">
				<h4 class="card-title">
				
				{{__('Member Statement Filter')}} 
				<a href="#" class="export-button btn btn-sm-one" onClick="$('#hidesearch').toggle();" style="background:#ff26ff;"><i class="material-icons">indeterminate_check_box</i></a>
				</h4> 
				@php
					
				@endphp
				<form method="post" id="filtersubmit" action="">
					@csrf  
					<div id="hidesearch" class="row">

						<div class="col s12 m6 l4">
							<label for="member_auto_id">{{__('Member Number')}}</label>
							<input id="member_search" type="text" class="validate " name="member_search" data-error=".errorTxt24">
							<input id="member_auto_id" type="text" class="hide" class="validate " name="member_auto_id">
							<div class="input-field">
								<div class="errorTxt24"></div>
							</div>
						</div>                          
						<div class="col s12 m6 l3">
							<label for="from_date">{{__('From Date')}}</label>
							<input id="from_date" type="text" class="validate datepicker-custom" value="" name="from_date">
						</div>
						<div class="col s12 m6 l3">
							<label for="to_date">{{__('To Date')}}</label>
							<input id="to_date" type="text" class="validate datepicker-custom" value="" name="to_date">
						</div>
						
						
						
						
						<div class="clearfix"/>
						<div class="row">
							<div class="input-field col s6 right">
								<input type="button" class="btn" style="width:130px" id="clear" name="clear" value="{{__('clear')}}">
							</div>
							<div class="input-field col s6 right-align">
								<input type="submit" id="search" class="btn" name="search" value="{{__('Search')}}">
							</div>
						</div>
					</div>
				</form>  
			</div>
		</div>
	</div>
</div> 
<div class="row">
	<div class="col s12">
		<iframe src="{{ route('statement.member',[app()->getLocale()]) }}" id="myframe" height="400px" width="100%"></iframe>
		</br>
		</br>
		
	</div>
</div> 

@php	
	
@endphp
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
<script src="{{ asset('public/assets/js/datepicker.js') }}"></script>
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
$("#reports_sidebars_id").addClass('active');
$("#member_statement_sidebar_li_id").addClass('active');
$("#member_statement_sidebar_a_id").addClass('active');

	$(document).ready(function(){
		
		$("#member_search").devbridgeAutocomplete({
			//lookup: countries,
			serviceUrl: "{{ URL::to('/get-company-member-list') }}?serachkey="+ $("#member_search").val(),
			params: {
						company_id:  '',
						branch_id:  '',
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
	
	$('#branch_id').change(function(){
		$('#member_auto_id').val('');
	    $('#member_search').val('');
	});
	 $("#filtersubmit").validate({
		rules: {
			from_date: {
				required: true,
			},
			to_date: {
				required: true,
			},
			member_search: {
				required: true,
			},
		},
		  //For custom messages
		  messages: {
				from_date:{
					required: "Enter From Date"
				},
				to_date:{
					required: "Enter To Date"
				},
				member_search:{
					required: "Enter Member Name"
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
    
	$(document).on('submit','form#filtersubmit',function(event){
		event.preventDefault();
		
		$("#search").attr('disabled',true);
		var from_date = $("#from_date").val();
		var to_date = $("#to_date").val();
	
		var member_auto_id = $("#member_auto_id").val();
		
		//$('#page-length-option tbody').empty();
		if(from_date!="" && to_date!=""){
			var searchfilters = '&from_date='+from_date+'&to_date='+to_date+'&member_auto_id='+member_auto_id;
			$("#myframe").contents().find("html").css('opacity',0);
		//	
			//loader.showLoader();
			$("#myframe").attr("src", "{{ url(app()->getLocale().'/member_statement') }}?offset=0"+searchfilters,);
			
			//loader.hideLoader();
			$("#search").attr('disabled',false);
		}else{
			alert("please choose any filter");
		}
		//$("#submit-download").prop('disabled',true);
	});
$('#clear').click(function(){
	$('#from_date').val("");
	$('#to_date').val("");
	$('#join_type').val("");
	$('#company_id').val("");
	$('#branch_id').val("");
	$('#member_search').val("");
	$(".selectpicker").val('').trigger("change"); 
});

$('.datepicker,.datepicker-custom').datepicker({
    format: 'dd/mm/yyyy',
    autoHide: true,
});
</script>

@endsection