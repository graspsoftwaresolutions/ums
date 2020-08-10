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
				
				{{__('Membership Statistics Filter')}} 
				<a href="#" class="export-button btn btn-sm-one" onClick="$('#hidesearch').toggle();" style="background:#ff26ff;"><i class="material-icons">indeterminate_check_box</i></a>
				</h4> 
				@php	
					$userid = Auth::user()->id;
					$get_roles = Auth::user()->roles;
					$user_role = $get_roles[0]->slug;
					$unionbranchlist = $data['unionbranch_view'];
				@endphp
				<form method="post" id="filtersubmit" action="">                                         
					@csrf  
					<div id="hidesearch" class="row">    
					<div class="row">    
						<div class="col s12 m6 l3">
							<label for="from_year">{{__('From Year')}}</label>
							<select name="from_year" id="from_year" class="error browser-default selectpicker" data-error=".errorTxt22" >
								<option value="">{{__('Select Year') }}</option>
								@for($y=date('Y'); $y>=2000; $y--)
								<option @if($y==$data['from_year']-3) selected @endif value="{{ $y }}">{{ $y }}</option>
								@endfor
							</select>
						</div>
						<div class="col s12 m6 l3">
							<label for="to_year">{{__('To Year')}}</label>
							<select name="to_year" id="to_year" class="error browser-default selectpicker" data-error=".errorTxt22" >
								<option value="">{{__('Select Year') }}</option>
								@for($ty=date('Y'); $ty>=2000; $ty--)
								<option @if($ty==$data['to_year']) selected @endif value="{{ $ty }}">{{ $ty }}</option>
								@endfor
							</select>
						</div>
						<div class="clearfix"/>
						@php 
						if($user_role =='union')
						{
						@endphp
						<div class="col s12 m6 l2 hide">
							<label>{{__('Union Branch Name') }}</label>
							<select name="unionbranch_id" id="unionbranch_id" class="error browser-default selectpicker" data-error=".errorTxt22" >
								<option  value="">{{__('Select Union Branch') }}</option>
								@foreach($unionbranchlist as $value)
								<option value="{{$value->id}}">{{$value->union_branch}}</option>
								@endforeach 
							</select>
							<div class="input-field">
								<div class="errorTxt22"></div>
							</div>
						</div>
						@php 
						}
						@endphp
						
						<div class="row hide">
							<div class="input-field col s6 right">
								<input type="button" class="btn" style="width:130px" id="clear" name="clear" value="{{__('clear')}}">
							</div>
							<div class="input-field col s6 right-align">
								<input type="submit" id="search" class="btn" name="search" value="{{__('Search')}}">
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
		<iframe src="{{ url(app()->getLocale().'/get-membership-statistics-report') }}?from_year={{$data['from_year']}}&to_year={{$data['to_year']}}" id="myframe" height="450px" width="100%"></iframe>
		
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
$("#reports_sidebars_id").addClass('active');
$("#member_statistic_sidebar_li_id").addClass('active');
$("#member_statistic_sidebar_a_id").addClass('active');

	$(document).ready(function(){
		 $('.datepicker-custom').MonthPicker({ 
			Button: false, 
			MonthFormat: 'M/yy',
			OnAfterChooseMonth: function() { 
				//getDataStatus();
			} 
		 });
	});
	$('#unionbranch_id').change(function(){
	var unionbranchID = $(this).val();   
	
	if(unionbranchID){
		$.ajax({
		type:"GET",
		dataType: "json",
		url:" {{ URL::to('/get-unionbankbranch-list') }}?unionbranch_id="+unionbranchID,
		success:function(res){              
			if(res){
				$("#branch_id").empty();
				$("#branch_id").append($('<option></option>').attr('value', '').text("Select"));
				$.each(res,function(key,entry){
					console.log(res);
					$("#branch_id").append($('<option></option>').attr('value', entry.id).text(entry.branch_name));	
				});   
			}else{
			  $("#branch_id").empty();
			}
		}
		});
	}else{
		$("#branch_id").empty();
		$("#company_id").empty();
	}      
});

	 $("#filtersubmit").validate({
		rules: {
			from_year: {
				required: true,
			},
			to_year: {
				required: true,
			},
		},
		  //For custom messages
		  messages: {
				from_year:{
					required: "Select Year"
				},
				to_year:{
					required: "Select Year"
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
		var from_year = $("#from_year").val();
		var to_year = $("#to_year").val();
		
		$('#page-length-option tbody').empty();
		if(from_year!="" && to_year!=""){
			var searchfilters = '&from_year='+from_year+'&to_year='+to_year;
			//loader.showLoader();
			$("#myframe").contents().find("html").css('opacity',0);
			$("#myframe").attr("src", "{{ url(app()->getLocale().'/get-yearly-statistics-report') }}?offset=0"+searchfilters,);
			//$('#page-length-option tbody').empty();
			 //loader.showLoader();
		
			$("#search").attr('disabled',false);
		}else{
			alert("please choose any filter");
		}
		//$("#submit-download").prop('disabled',true);
	});

	$('#clear').click(function(){
	$('#month_year').val("");	
	$('#company_id').val("");
	$('#branch_id').val("");
	$('#member_search').val("");
	$(".selectpicker").val('').trigger("change"); 
});

</script>
@endsection