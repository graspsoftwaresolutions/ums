
@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/materialize-stepper/materialize-stepper.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/themes/vertical-modern-menu-template/materialize.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/themes/vertical-modern-menu-template/style.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/pages/form-wizard.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/vendors/data-tables/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css') }}">
@endsection
@section('headSecondSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/pages/data-tables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/pages/form-wizard.css') }}">
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
                                        <h5 class="breadcrumbs-title mt-0 mb-0">{{__('Member Subscription List')}}</h5>
                                        <ol class="breadcrumbs mb-0">
                                            <li class="breadcrumb-item"><a
                                                    href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard')}}</a>
                                            </li>
                                            <li class="breadcrumb-item active">{{__('Subscription')}}
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
                            $row =  $data['member_subscription_details'][0]; 
                            @endphp
                            <div class="card-content">
                            <h4 class="card-title">{{__('Member Subscription List')}}  </h4> 
                            <h4 class="card-title">{{__('Member Name : ')}}  {{ isset($row->membername) ? $row->membername : "Nill" }} </h4>
                            <h4 class="card-title">{{__('Status :')}}  {{ isset($row->status_name) ? $row->status_name : "Nill" }}</h4>
                                                                   
                            <h4 class="card-title">{{__('Current Month :')}} @php echo date('M-Y') @endphp </h4>
                          
                            <h4 class="card-title">{{__('Amount Paid :')}}   {{ isset($row->Amount) ? $row->Amount : "No Amount" }}</h4>
                            <div class="card filter">
                            
                            <form method="post" action="{{route('subscription.memberfilter',app()->getLocale())}}">
                            @csrf  
                            <input type="hidden" name="id" value="{{ isset($row->MemberCode) ? $row->MemberCode : '' }}">
                            <input type="hidden" name="memberid" value="{{ isset($row->memberid) ? $row->memberid : ''}}">
                                <div class="row">                          
                                    <div class="input-field col s4">
                                        <i class="material-icons prefix">date_range</i>
                                        <input id="icon_prefix" type="text" class="validate datepicker" name="from_date">
                                        <label for="icon_prefix">From Month and Year</label>
                                    </div>
                                    <div class="input-field col s4">
                                        <i class="material-icons prefix">date_range</i>
                                        <input id="icon_telephone" type="tel" class="validate datepicker" name="to_date">
                                        <label for="icon_telephone">To Month and Year</label>
                                    </div>
                                    <div class="input-field col s4">
                                    <input type="submit"  class="btn" name="search" value="Search">
                                    </div>
                                </div>
                            </form>  
                            </div>
                            @include('includes.messages')
                            <div class="row">
                                <div class="col s12">
                                    <!-- Horizontal Stepper -->
									<div class="card">
                                    <div class="col sm12 m12">
                                       
                                        <table id="page-length-option" class="display ">
                                            <thead>
                                            <tr>
                                            <th>Month and Year</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            </tr>
                                            </tr>  
                                                @php if(count($data['member_subscription_list'])!=0 )
                                                {
                                                    foreach($data['member_subscription_list'] as $key=> $values)
                                                    {
                                                        @endphp
                                                        <tr> 
                                                        <td> {{ isset($values->Date) ? $values->Date : "Nill" }}  </td>
                                                        <td> {{ isset($values->Amount) ? $values->Amount : "Nill" }} </td>
                                                        <td> {{ isset($values->status_name) ? $values->status_name : "Nill" }} </td>
                                                        </tr> 
                                                     @php
                                                    }
                                                }
                                                else{ 
                                                    @endphp
                                                    <td><div calss="row"></td>
                                                @php
                                                }
                                                @endphp
                                                
                                            </thead>
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
<script src="{{ asset('public/assets/vendors/noUiSlider/nouislider.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/materialize.min.js') }}"></script>
<script src="{{ asset('public/assets/js/scripts/form-elements.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jquery.autocomplete.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/mstepper.min.js') }}"></script>
@endsection
@section('footerSecondSection')
<script src="{{ asset('public/assets/js/scripts/form-wizard.js') }}" type="text/javascript"></script>
<script>
$("#subscriptions_sidebars_id").addClass('active');
$("#subcomp_sidebar_li_id").addClass('active');
$("#subcomp_sidebar_a_id").addClass('active');

	$(document).ready(function(){
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
        $statid =$(this).closest($('#status_id').val());
        console.log($statid);
		
		/* var inputs = activeStepContent.querySelectorAll('input, textarea, select');
	   for (let i = 0; i < inputs.length; i++) 
	   {
		   if (!inputs[i].checkValidity()) {
			   jQuery("#submit-member").trigger('submit');
			   return false;
		   }
	   } */
	  
	   return true;
	}
/*

</script>
@endsection