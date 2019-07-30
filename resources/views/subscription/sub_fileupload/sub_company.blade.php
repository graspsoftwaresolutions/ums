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
<div id="main">
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
                                        <h5 class="breadcrumbs-title mt-0 mb-0">{{__('Company Subscription List')}}</h5>
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
                        @php $data = $data['company_subscription_list'][0];
                        @endphp
                            <div class="card-content">
                            <h4 class="card-title">{{__('Company Member List')}}</h4>
                           
                            <h4 class="card-title">{{__('Current Month :')}} @php echo date('M-Y'); @endphp</h4>
                            <h4 class="card-title">{{__('Total Members Count :')}} 3000</h4>
                            <h4 class="card-title">{{__('Company Name : ')}} {{$data->company_name}} </h4>
                            
                            <h4 class="card-title">{{__('Company Code : ')}} {{$data->short_code}}</h4>
                            @include('includes.messages')
                            <div class="row">
                                <div class="col s12">
                                    <!-- Horizontal Stepper -->
									<div class="card">
										<div class="card-content pb-0">
											<div class="card-header">
												<!-- <h4 class="card-title">Horizontal Stepper</h4> -->
											</div>
											<ul class="stepper horizontal" id="horizStepper">
                                                @php 
                                                isset($data['member_stat']) ? $data['member_stat'] : "";                   
                                                @endphp 
                                                @foreach($data['member_stat'] as  $key => $stat)
                                                
												<li class="step active" >
                                                <form method="post" id="status_id">
                                                <input type="hidden" name="status_id" value="{{$stat->id}}">
													<div class="step-title waves-effect">{{$stat->status_name}}</div>
													<div class="step-content">
                                                    <div class="col sm12 m12">
                                                    <table id="page-length-option" class="display ">
                                                        <thead>
                                                        <tr>
                                                        <th>{{__('SL No')}}</th>
                                                        <th>{{__('Member Name')}}</th>
                                                        <th>{{__('Member Code')}}</th>
                                                        <th>{{__('NRIC')}}</th>
                                                        <th>{{__('Amount')}}</th>
                                                        <th>{{__('Due')}}</th>
                                                        <th>{{__('Status')}}</th>
                                                        <th>{{__('Action')}}</th>
                                                        </tr>
                                                        </tr>
                                                        @php 
                                                        isset($data['member_stat']) ? $data['member_stat'] : "";                   
                                                        @endphp 
                                                        @foreach($data['member_stat'] as  $key => $stat)
                                                        <tr>
                                                        <td>{{ $key+1 }} </td>
                                                        <td>{{ $key+1 }} </td>
                                                        <td>{{ $stat->status_name }}</td>                   
                                                        <td>{{ $stat->Subscription_members->count() }}</td>
                                                        <td>{{ $stat->Subscription_members->sum('Amount') }}</td>
                                                        <td>{{ $stat->Subscription_members->sum('Amount') }}</td>
                                                        <td>{{ $stat->Subscription_members->sum('Amount') }}</td>
                                                        <td>{{ $stat->Subscription_members->sum('Amount') }}</td>
                                                        </tr>                  
                                                        @endforeach
                                                        </thead>
                                                    </table>
													</div>	
                                                        <div class="step-actions">
                                                      
														</div>
													</div>
                                                    </form>
												</li>
                                                @endforeach
                                               
											</ul>
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
$(function() {
    $('#page-length-option').DataTable({
        "responsive": true,
        "lengthMenu": [
            [10, 25, 50, 100],
            [10, 25, 50, 100]
        ],
        /* "lengthMenu": [
        	[10, 25, 50, -1],
        	[10, 25, 50, "All"]
        ], */
       /* "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "{{ url(app()->getLocale().'/ajax_state_list') }}",
            "dataType": "json",
            "type": "POST",
            "data": {
                _token: "{{csrf_token()}}"
            }
        },
        "columns": [{
                "data": "country_name"
            },
            {
                "data": "state_name"
            },
            {
                "data": "options"
            }
        ]
    });
});
*/
function ConfirmDeletion() {
    if (confirm("{{ __('Are you sure you want to delete?') }}")) {
        return true;
    } else {
        return false;
    }
}


function showaddForm() {
    $('.edit_hide').show();
    $('.add_hide').show();
    $('.edit_hide_btn').hide();
    $('#state_name').val("");
    $('.modal').modal();
    $('#updateid').val("");
}

function showeditForm(countryid) {
    $('.edit_hide').hide();
    $('.add_hide').hide();
    $('.edit_hide_btn').show();
    $('.modal').modal();
    loader.showLoader();
    var url = "{{ url(app()->getLocale().'/state_detail') }}" + '?id=' + countryid;
    $.ajax({
        url: url,
        type: "GET",
        success: function(result) {
            $('#updateid').val(result.id);
            $('#updateid').attr('data-autoid', result.id);
            $('#country_id').val(result.country_id);
            $('#state_name').val(result.state_name);
            loader.hideLoader();
            $("#modal_add_edit").modal('open');
        }
    });
}

</script>
@endsection