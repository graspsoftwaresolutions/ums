@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/vendors/data-tables/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css') }}">
@endsection
@section('headSecondSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/pages/data-tables.css') }}">
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
                        @php
                        $datacmpy = $data['company_subscription_list'][0];   
                        @endphp
                            <div class="card-content">
                            <h4 class="card-title">{{__('Company Member List')}}</h4>
                           
                            <h4 class="card-title">{{__('Current Month :')}} @php echo date('M-Y'); @endphp</h4>
                            <h4 class="card-title">{{__('Total Members Count :')}} {{ isset($data['tot_count']) ? $data['tot_count'] : ""}}</h4>
                            <h4 class="card-title">{{__('Company Name : ')}} {{ isset($datacmpy) ? $datacmpy->company_name : ""}} </h4>
                            
                            <h4 class="card-title">{{__('Company Code : ')}} {{ isset($datacmpy) ? $datacmpy->short_code : ""}}</h4>
                            @include('includes.messages')
                            <div class="row">
                            <div class="col s12">  
                                <ul class="tabs">  
                                <li class="tab col s3"><a class="active tab_status" href="#inbox" id="all">Inbox</a></li>  
                                <li class="tab col s3"><a class="tab_status" href="#unread" id="1">Unread</a></li>  
                                <li class="tab col s3"><a class="tab_status" href="#outbox" id="2">Disabled Tab</a></li>  
                                <li class="tab col s3"><a class="tab_status" href="#sentitems" id="3">Sent Items</a></li>  
                                </ul>  
                            </div>  
                                <div id="inbox" class="col s12">
                                <div class="col sm12 m12">
                                                    <table id="page-length-option" class="display ">
                                                        <thead>
                                                        <tr>
                                                        <th>{{__('Member Name')}}</th>
                                                        <th>{{__('Member Code')}}</th>
                                                        <th>{{__('NRIC')}}</th>
                                                        <th>{{__('Amount')}}</th>
                                                        <th>{{__('Due')}}</th>
                                                        <th>{{__('Status')}}</th>
                                                        <th>{{__('Action')}}</th>
                                                        </tr>
                                                        </thead>                                                        
                                                       
                                                    </table>
													</div>	
                                </div>  
                                <div id="unread" class="col s12">Unread</div>  
                                <div id="outbox" class="col s12">Outbox</div>  
                                <div id="sentitems" class="col s12">Sent Items</div>  
                                
                               <!-- <div class="col s12">-->
                                <!-- tabs-->
                       
                                    <!-- Horizontal Stepper -->
								<!--	<div class="card">
										<div class="card-content pb-0">
											<div class="card-header">-->
												<!-- <h4 class="card-title">Horizontal Stepper</h4> -->
											<!--</div>
											<ul class="stepper horizontal" id="horizStepper">
                                               
                                               
                                                
												<li class="step active" >
                                                <form method="post" id="status_id">
                                                <input type="hidden" name="status_id" value="">
													<div class="step-title waves-effect">All</div>
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
                                                        isset($data['company_subscription_list']) ? $data['company_subscription_list'] : "";                   
                                                        @endphp 
                                                        @foreach($data['company_subscription_list'] as  $key => $member_stat)
                                                        <tr>
                                                        <td>{{ $key+1 }} </td>
                                                        <td>{{ $member_stat->Name}} </td>
                                                        <td>{{ $member_stat->MemberCode }} </td>                   
                                                        <td>{{ $member_stat->NRIC }} </td>
                                                        <td>{{ $member_stat->Amount }} </td>
                                                        <td></td>
                                                        <td>{{ isset($member_stat->StatusId) ? CommonHelper::get_member_status_name($member_stat->StatusId) : "" }} </td>
                                                        <td><a></a></td>
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
                                              
                                               
											</ul>
										</div>
									</div>

                               
									</div>-->
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
        </div>
    </div>
</div>
@endsection
@section('footerSection')
@endsection
@section('footerSection')
<script src="{{ asset('public/assets/vendors/data-tables/js/jquery.dataTables.min.js') }}" type="text/javascript">
</script>
<script src="{{ asset('public/assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js') }}"
    type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/data-tables/js/dataTables.select.min.js') }}" type="text/javascript">
</script>
@endsection
@section('footerSecondSection')
<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/assets/js/scripts/form-validation.js')}}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/scripts/data-tables.js') }}" type="text/javascript"></script>
<script>
$("#subscriptions_sidebars_id").addClass('active');
$("#subcomp_sidebar_li_id").addClass('active');
$("#subcomp_sidebar_a_id").addClass('active');
$(document).ready(function(){
    $('.tab_status').click(function(){
        console.log($(this).attr('id'));
    });
});
	/*$(document).ready(function(){
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
      //  $statid =$(this).closest($('#status_id').val());
       // console.log($statid);
		
		/* var inputs = activeStepContent.querySelectorAll('input, textarea, select');
	   for (let i = 0; i < inputs.length; i++) 
	   {
		   if (!inputs[i].checkValidity()) {
			   jQuery("#submit-member").trigger('submit');
			   return false;
		   }
	   } */
	  
	  // return true;
   // }*/

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
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "{{ url(app()->getLocale().'/ajax_submember_list') }}",
            "dataType": "json",
            "type": "POST",
            "data": {
                _token: "{{csrf_token()}}"
            }
        },
        "columns": [{
                "data": "member_name"
            },
            {
                "data": "member_code"
            },
            {
                "data": "nric"
            },
            {
                "data": "amount"
            },
            {
                "data": "due"
            },
            {
                "data": "status"
            },
            {
                "data": "options"
            }
        ]
    });
});

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