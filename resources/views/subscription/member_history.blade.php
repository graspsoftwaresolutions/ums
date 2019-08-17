
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
<style>
.filter{
    padding-top: 9px;
    background-color: #dad1d1c7;
}
</style>
@endsection
@section('main-content')
@php 

@endphp
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
                                        <h5 class="breadcrumbs-title mt-0 mb-0">{{__('History')}}</h5>
                                        <ol class="breadcrumbs mb-0">
                                            <li class="breadcrumb-item"><a
                                                    href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard')}}</a>
                                            </li>
                                            <li class="breadcrumb-item active">{{__('History')}}
                                            </li>
                                        </ol>
                                    </div>
                                  
                                </div>
                            </div>
                           
                        </div>
                        <div class="row">
							<div class="col s12">
								<div class="card">
									@php
										$member = $data['member_details'];
									@endphp
									<div class="card-content">
										<h4 class="card-title">{{__('History')}}  </h4> 
										<table width="100%" style="font-weight:bold">
											<tr>
												<td width="25%">{{__('Member Name ')}}</td>
												<td width="25%">: {{ $member->membername }}</td>
												<td width="25%">{{ __('NRIC-OLD')}}</td>
												<td width="25%">: {{ $member->old_ic }}</td>
											</tr>
											<tr>
												<td width="25%">{{__('NRIC-NEW')}}</td>
												<td width="25%" >: {{ $member->new_ic }}</td>
												<td width="25%">{{__('Bank')}}</td>
												<td width="25%" >: {{ $member->company_name }}</td>
												
											</tr>
											<tr>
												<td width="25%">{{__('Type')}}</td>
												<td width="25%">: {{ $member->membertype }}</td>
												
												<td width="25%">{{__('Status')}}</td>
												<td width="25%">{{ $member->status_name }}</td>
											</tr>
											<tr>
												<td width="25%">{{__('Date of joing')}}</td>
												<td width="25%">: {{ $member->doj }}</td>
												
												<td width="25%">{{__('Last paid Date')}}</td>
												<td width="25%">{{ date('M/ Y',strtotime($data['member_history'][count($data['member_history'])-1]->StatusMonth)) }}</td>
											</tr>
										</table>
								    </div>
                                </div>
                           
                             </div>		
							<div class="col s12">
								<div class="card">
                           
									<div class="card-content">
                            
                            @include('includes.messages')
                            <div class="row">
                                <div class="col s12">
                                    <!-- Horizontal Stepper -->
									<div class="">
                                    <div class="col sm12 m12">   
                                        <table id="page-length-option" class="display ">
                                            <thead>
												<tr>
													<th>{{__('Date')}}</th>
													<th>{{__('Subs')}}</th>
													<th>{{__('BF')}}</th>
													<th>{{__('Ins')}}</th>
													<th>{{__('Month')}}</th>
													<th>{{__('LastPaid')}}</th>
													<th>{{__('PAID')}}</th>
													<th>{{__('DUE')}}</th>
													<th>{{__('Total')}}</th>
													<th>{{__('AccSubs')}}</th>
													<th>{{__('AccBF')}}</th>
													<th>{{__('AccIns')}}</th>
												</tr>
											</thead>
											<tbody>
												@if(count($data['member_history'])>0 )
													
                                                    @foreach($data['member_history'] as $key=> $values)
													<tr>  
														<td>{{ date('M/ Y',strtotime($values->StatusMonth)) }}</td>
														<td>{{ $values->SUBSCRIPTION_AMOUNT }}</td>
														<td>{{ $values->BF_AMOUNT }}</td>
														<td>{{ $values->INSURANCE_AMOUNT }}</td>
														<td>{{ $values->TOTALMONTHSCONTRIBUTION }}</td>
														<td>{{ date('M/ Y',strtotime($values->LASTPAYMENTDATE)) }}</td>
														<td>{{ $values->TOTALMONTHSPAID }}</td>
														<td>{{ $values->SUBSCRIPTIONDUE }}</td>
														<td>{{ $values->SUBSCRIPTIONDUE+$values->TOTALMONTHSPAID }}</td>
														<td>{{ $values->ACCSUBSCRIPTION }}</td>
														<td>{{ $values->ACCBF }}</td>
														<td>{{ $values->ACCINSURANCE }}</td>
														
													</tr>
													@endforeach
												@endif
												
                                            </tbody>
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
@endsection
@section('footerSecondSection')
<script>
$("#subscriptions_sidebars_id").addClass('active');
$("#subscomp_sidebar_li_id").addClass('active');
$("#subcomp_sidebar_a_id").addClass('active');

	$(document).ready(function(){
		//loader.showLoader();
	
	});
	
    $("#filtersubmit").validate({
    rules: {
        from_date: {
			required: true,
			
		  },
		  to_date: {
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