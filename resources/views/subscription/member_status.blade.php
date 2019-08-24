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
</style>
@endsection
@section('main-content')
@php 

@endphp
<div class="row hide">
	<div class="col s12">
		<div class="card">
			<div class="card-content">
				<h4 class="card-title">
				
				{{__('Members')}} 
				
				</h4> 
				
				<form method="post" id="filtersubmit" action="">
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
						@foreach($data['member'] as  $key => $member)
							<tr>
								<td>{{ $member->member_name }}</td>
								<td>{{ $member->member_number }}</td>
								<td>{{ $member->company_name }}</td>
								<td>{{ $member->ic }}</td>
								<td>{{ $member->Amount }}</td>
								<td>{{ $member->due }}</td>
								<td>{{ $member->status_name }}</td>
								<td></td>
								
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

	$(document).ready(function(){
		
	
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
  

</script>
@endsection