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
				
				{{__('Eco Park Report')}} 
				
				<a href="#" class="export-button btn btn-sm-one" onClick="$('#hidesearch').toggle();" style="background:#ff26ff;"><i class="material-icons">indeterminate_check_box</i></a>
				</h4> 
				@php
					
					$userid = Auth::user()->id;
					$get_roles = Auth::user()->roles;
					$user_role = $get_roles[0]->slug;
					
				@endphp
				<form method="post" id="filtersubmit" action="">
					@csrf  
					<div id="hidesearch" class="row">
					<div class="row">                          
						 <div class="col s4">
                            <label for="card_type">{{__('Card Type') }}</label>
                            <select name="card_type" id="card_type" class="error browser-default selectpicker" data-error=".errorTxt5">
                                <option value="" selected>{{__('Choose Type') }}</option>
                              
								@foreach($data['cardstatus'] as  $key => $value)
									<option>{{ $value->card_status_name }}</option>
								@endforeach
                              
                            </select>
                            <div class="errorTxt5"></div>
                        </div>
                        <div class="col s4">
                            <label for="member_type">{{__('Member Type') }}</label>
                            <select name="member_type" id="member_type" class="error browser-default selectpicker" data-error=".errorTxt6">
                                <option value="" selected>{{__('Choose Type') }}</option>
                              	<option value="1">Members Matched</option>
                              	<option value="2">Members Not Matched</option>
                              	<option value="3">Members with No IC / No Member number</option>
                              
                            </select>
                            <div class="errorTxt6"></div>
                        </div>
                        <div class="col s4">
                            <label for="amount_type">{{__('Amount') }}</label>
                            <select name="amount_type" id="amount_type" class="error browser-default selectpicker" data-error=".errorTxt7">
                                <option value="" selected>{{__('Choose Amount') }}</option>
                              	<option value="7">1550</option>
                              	<option value="1">1550 - 2049</option>
                              	<option value="8">2050</option>
                              	<option value="2">2050 - 2549</option>
                              	<option value="9">2550</option>
                              	<option value="3">2550 - 5049</option>
                              	<option value="10">5050</option>
                              	<option value="4">5050 and above</option>
                              	<option value="5">Less Payment</option>
                              	<option value="6">Zero Payment</option>
                              
                            </select>
                            <div class="errorTxt7"></div>
                        </div>
                        <div class="clearfix"/>
                         <br>
                          <div class="clearfix"/>
                        <div class="col s4">
                            <label for="batch_type">{{__('Batch Type') }}</label>
                            <select name="batch_type" id="batch_type" class="error browser-default selectpicker" data-error=".errorTxt8">
                                <option value="" selected>{{__('Choose Type') }}</option>
                              	<option data-type="Others" value="5">Others</option>
                                <option data-type="Batch 1 Member" value="1">Batch 1 Members</option>
                                <option data-type="Batch 1 Non Member" value="2">Batch 1 Non Members</option>
                                <option data-type="Batch 2 Member" value="3">Batch 2 Members</option>
                                <option data-type="Batch 2 Non Member" value="4">Batch 2 Non Members</option>
                              
                            </select>
                            <div class="errorTxt8"></div>
                        </div>
                       

                        <div class="col s4">
                            <label for="member_status">{{__('Member Status') }}</label>
                            <select name="member_status" id="member_status" class="error browser-default selectpicker" data-error=".errorTxt7">
                                <option value="" selected>{{__('Choose Status') }}</option>
                              	@foreach($data['member_status'] as  $key => $stat)
									<option value="{{ $stat->id }}">{{ $stat->status_name }}</option>
								@endforeach
                              
                            </select>
                            <div class="errorTxt7"></div>
                        </div>
						
						<div class="row">
							<div class="input-field col s6 right">
								<input type="button" id="clear" style="width:130px"  class="btn" name="clear" value="{{__('Clear')}}">
							</div>
							<div class="input-field col s6 right-align">
								<input type="submit" id="search"  class="btn" name="search" value="{{__('Search')}}">
							</div>
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
		<iframe src="{{ route('reports.ecopark',[app()->getLocale()])  }}" id="myframe" height="400px" width="100%"></iframe>
	
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
$("#ecopark_sidebars_id").addClass('active');
$("#ecoparkreport_sidebar_li_id").addClass('active');
$("#ecoparkreport_sidebar_a_id").addClass('active');
	
		$('#filtersubmit').validate({
			rules: {
				
			},
			//For custom messages
			messages: {
				
			},
			errorElement: 'div',
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
		var card_type = $("#card_type").val();
		var member_type = $("#member_type").val();
		var amount_type = $("#amount_type").val();
		var batch_type = $("#batch_type").val();
		var member_status = $("#member_status").val();
		if(card_type!="" || member_type!="" || amount_type!="" || batch_type!="" || member_status!=""){
			var searchfilters = '&card_type='+card_type+'&member_type='+member_type+'&amount_type='+amount_type+'&batch_type='+batch_type+'&member_status='+member_status;
			
			$("#myframe").attr("src", "{{ url(app()->getLocale().'/iframe_eco_park') }}?offset=0"+searchfilters,);
			
			$("#search").attr('disabled',false);
		}else{
			alert("please choose any filter");
		}
		//$("#submit-download").prop('disabled',true);
	});
$('#clear').click(function(){
	$('#card_type').val("");
	$('#member_type').val("");
});
</script>
@endsection