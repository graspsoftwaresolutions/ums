@extends('layouts.admin')
@section('headSection')
@endsection
@section('headSecondSection')
<link href="{{ asset('public/assets/css/jquery-ui-month.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/css/MonthPicker.min.css') }}" rel="stylesheet" type="text/css" />
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
<div class="row">
	<div class="col s12">
		<div class="card">
			<div class="card-content">
				<h4 class="card-title">
				
				{{__('MonthEnd Filter')}} 
				<a href="#" class="export-button btn btn-sm-one" onClick="$('#hidesearch').toggle();" style="background:#ff26ff;"><i class="material-icons">indeterminate_check_box</i></a>
				</h4> 
				
				<form method="post" id="filtersubmit" action="">
					@csrf  
					<div id="hidesearch" class="row">                          
						<div class="col s12 m6 l3">
							<label for="month_year">{{__('From Date')}}</label>
							<input id="month_year" type="text" class="validate datepicker-custom" value="{{date('M/Y')}}" name="from_date">
						</div>
						
						
						<div class="col s12 m6 l4 ">
							<label for="member_auto_id">{{__('Member Number')}}</label>
							<input id="member_search" type="text" class="validate " name="member_search" data-error=".errorTxt24">
							<input id="member_auto_id" type="text" class="hide" class="validate " name="member_auto_id">
							<div class="input-field">
								<div class="errorTxt24"></div>
							</div>
						</div>
					
						<div class="row">
							<div class="input-field col s6 right">
								<input type="button" class="btn" style="width:130px" id="clear" name="clear" value="{{__('clear')}}">
							</div>
							<div class="input-field col s6 right-align">
								<input type="button" id="search" class="btn" name="search" value="{{__('Search')}}">
							
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
		<div class="card">
			<div class="card-content">
				<h4 class="card-title">
				
				{{__('MonthEnd Details')}} 
				
				</h4> 
				
				<form method="post" id="monthendsubmit" action="{{ url(app()->getLocale().'/monthend_save') }}">
					@csrf  
					<div class="row">                          
						
						
						
						<div class="col s12 m6 l4 ">
							<label for="subscription_amount">{{__('Subscription Amount')}}</label>
							<input id="month_auto_id" type="text" class="validate hide" name="month_auto_id" data-error=".errorTxt24">
							<input id="subscription_amount" type="text" class="validate " name="subscription_amount" data-error=".errorTxt24">
					
							<div class="input-field">
								<div class="errorTxt24"></div>
							</div>
						</div>

						<div class="col s12 m6 l3">
							<label for="bf_amount">{{__('BF Amount')}}</label>
							<input id="bf_amount" type="text" class="validate " value="" name="bf_amount" data-error=".errorTxt20">

							<div class="input-field">
								<div class="errorTxt20"></div>
							</div>
						</div>

						<div class="col s12 m6 l4 ">
							<label for="insurance_amount">{{__('Insurance Amount')}}</label>
							<input id="insurance_amount" type="text" class="validate " name="insurance_amount" data-error=".errorTxt25">
					
							<div class="input-field">
								<div class="errorTxt25"></div>
							</div>
						</div>
					
						<div class="row">
							
							<div class="input-field col s12 right-align">
								<input type="submit" id="savemonth" class="btn" name="savemonth" value="{{__('Submit')}}">
							
							</div>
						</div>
					</div>
				</form>  
			</div>
		</div>
	</div>
</div> 
@endsection
		
@section('footerSection')

<script>
	$("#dashboard_sidebar_a_id").addClass('active');
</script>

<script src="{{ asset('public/assets/js/jquery.autocomplete.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jquery.autocomplete.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jquery-ui-month.min.js')}}"></script>
<script src="{{ asset('public/js/MonthPicker.min.js')}}"></script>

<script>
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
						company_id:  function(){ return '';  },
						branch_id:  function(){ return '';  } 
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
		$('#search').click(function(){
			var month_year = $("#month_year").val();
			var member_auto_id = $("#member_auto_id").val();
			if(month_year!='' && member_auto_id!=''){
				additional_cond = '&month_year='+month_year+'&member_auto_id='+member_auto_id;
				 $.ajax({
					type: "GET",
					dataType: "json",
					url : "{{ URL::to('/en/get-monthend-record') }}?reference="+1+additional_cond,
					success:function(res){
						if(res)
						{
							if(res.status=1 && res.data!=null){
								var monthdata = res.data;
								$("#month_auto_id").val(monthdata.Id);
								$("#subscription_amount").val(monthdata.TOTALSUBCRP_AMOUNT);
								$("#bf_amount").val(monthdata.TOTALBF_AMOUNT);
								$("#insurance_amount").val(monthdata.TOTALINSURANCE_AMOUNT);
							}else{
								alert('not exists');
							}
						}else{
							
						}
					}
				 });
			}else{
				alert("Please choose member and date");
			}
		});
	});
    $('#clear').click(function(){
		$('#month_year').val("");
		$('#member_auto_id').val("");
		$('#member_search').val("");
		$(".selectpicker").val('').trigger("change"); 
	});
	$(document).on('submit','form#monthendsubmit',function(event){
		event.preventDefault();
		var month_auto_id = $("#month_auto_id").val();
		var subscription_amount = $("#subscription_amount").val();
		var bf_amount = $("#bf_amount").val();
		var insurance_amount = $("#insurance_amount").val();
		if(month_auto_id!="" && subscription_amount!='' && bf_amount!='' && insurance_amount!=''){
			var additional_cond = 'month_auto_id='+month_auto_id+'&subscription_amount='+subscription_amount+'&bf_amount='+bf_amount+'&insurance_amount='+insurance_amount;
			$.ajax({
				type: "GET",
				dataType: "json",
				url : "{{ URL::to('/en/monthend_save') }}?reference=1&"+additional_cond,
				success:function(res){
					if(res)
					{
						alert('current month updated , other months will start updates in background');
						location.reload();
					}else{
						alert('failed to update');
					}
				}
			});
			location.reload();
		}else{
			alert("Please Choose Month & Year");
		}
		
	});
</script>
@endsection
