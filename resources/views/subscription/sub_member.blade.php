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
                        <div class="breadcrumbs-dark pb-0 pt-4" id="breadcrumbs-wrapper">
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
                        
                            <div class="card-content">
                            <h4 class="card-title">{{__('Member Subscription List')}}</h4>
                            <h4 class="card-title">{{__('Member Name : ')}} Mounika </h4>
                            <h4 class="card-title">{{__('Company Name & Code : ')}} XXXX:1234 </h4>
                            <h4 class="card-title">{{__('Company Branch :')}} XXX</h4>
                            <h4 class="card-title">{{__('Status :')}} XXXX</h4>
                            <h4 class="card-title">{{__('Current Month :')}} @php echo date('M-Y') @endphp </h4>
                            <h4 class="card-title">{{__('Amount Paid :')}} 3000</h4>
                            <div class="card" style="padding-top: 9px;">
                            <form method="post" action="">
                            <div class="row">
                                <div class="col sm4 m4">
                                <label for="doj">{{__('From Date') }}*</label>
                                    <input type="text" class="datepicker"  name="from_date">
                                   
                                </div>
                                <div class="col sm4 m4">
                                <label for="doj">{{__('To date') }}*</label>
                                    <input type="text"  class="datepicker"  name="to_date">
                                    
                                </div>
                                <div class="col sm4 m4">
                                <br>
                                     <input type="submit"  class="btn" name="Search" value="Search">
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
                                                        <th>Date</th>
                                                        <th>Amount</th>
                                                        <th>Due</th>
                                                        <th>Status</th>
                                                        </tr>
                                                        </tr>
                                                       
                                                        <tr>
                                                        <td>Jul 2019</td>
                                                        <td>200</td>
                                                        <td>xxx</td>
                                                        <td>xxx</td>
                                                        </tr>    
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