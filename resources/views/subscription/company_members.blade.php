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
                        <div class="breadcrumbs-dark" id="breadcrumbs-wrapper">
                            <!-- Search for small screen-->
                            <div class="container">
                                <div class="row">
                                    <div class="col s10 m6 l6">
                                        <h5 class="breadcrumbs-title mt-0 mb-0">{{__('Bank Subscription List')}}</h5>
                                        <ol class="breadcrumbs mb-0">
                                            <li class="breadcrumb-item"><a
                                                    href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard')}}</a>
                                            </li>
                                            <li class="breadcrumb-item active">{{__('Subscription')}}
                                            </li>
                                        </ol>
                                    </div>
                                    <div class="col s2 m6 l6 ">
                                        <a class="btn waves-effect waves-light breadcrumbs-btn right" href="{{route('subscription.sub_fileupload.sub_company',app()->getLocale())}}">{{__('Back') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        <div class="col s12">
                        <div class="card">
                        @php
                        $datacmpy = $data['company_subscription_list'][0];   
						$enccompany_auto_id = Crypt::encrypt($data['company_auto_id']);
                        @endphp
                            <div class="card-content">
								<div class="row">
									<div class="col m6">
                                      <h4 class="card-title" style="font-weight: bold; font-size: 16px">{{__('Bank Member List')}}</h4>
                                    <!-- <div class="col s12">
                                      <label>   {{__('Month :')}} </label>
                                    <div class="input-field inline">
                                      @php echo date('M-Y',strtotime($datacmpy->Date)); @endphp
                                   
                                    </div>
                                    </div>
                                    <div class="row">
                                        <div class="col s12">
                                            <label>   {{__('Total Members Count :')}} </label>
                                        <div class="input-field inline">
                                                {{ isset($data['tot_count']) ? $data['tot_count'] : ""}}
                                        </div>
                                    </div>  
                                    <div class="row">
                                        <div class="col s12">
                                            <label>   {{__('Company :')}} </label>
                                        <div class="input-field inline">
                                        {{ isset($datacmpy) ? $datacmpy->short_code : ""}} - {{ isset($datacmpy) ? $datacmpy->company_name : ""}}
                                        </div>
                                    </div> -->
										
										<h4 class="card-title" style="font-weight: bold; font-size: 16px">{{__('Month :')}} @php echo date('M-Y',strtotime($datacmpy->Date)); @endphp</h4>
										<h4 class="card-title" style="font-weight: bold; font-size: 16px">{{__('Total Members Count :')}} {{ isset($data['tot_count']) ? $data['tot_count'] : ""}}</h4>
										<h4 class="card-title" style="font-weight: bold; font-size: 16px">{{__('Bank : ')}} {{ isset($datacmpy) ? $datacmpy->short_code : ""}} - {{ isset($datacmpy) ? $datacmpy->company_name : ""}} </h4>
									</div>
									@if($data['non_updated_rows']>0)
									<div class="col m6">
										<div class="row">
											<div class="col m4">
												<div id="scanning-details" class="gradient-45deg-amber-amber padding-3 medium-small" style="color:#fff">
                                                {{__('Please update membership details')}}
													
												</div>
												
											</div>
											<div class="col m3">
												<a id="submit-download" href="{{ route('subscription.viewscan', [app()->getLocale(),$enccompany_auto_id])  }}" class="waves-effect waves-light cyan btn btn-primary form-download-btn right" type="button">{{__('Update details')}}</a>
											</div>
                                            <div class="col m5">
												<a id="pending_members" href="{{ route('subscription.pendingmembers', [app()->getLocale(),$enccompany_auto_id])  }}" class="waves-effect waves-light btn btn-primary form-download-btn right" type="button">{{__('Pending Members')}} </a>
											</div>
										</div>
									</div>
									@endif
								</div>
								
                            @include('includes.messages')
                            <div class="row">
                            <div class="col s12">  
                                <ul class="tabs">  
									<li class="tab col s3"><a class="active tab_status" href="#inbox" id="all">All</a></li>  
									@foreach($data['member_stat'] as  $key => $member_stat)
									<li class="tab col s3"><a class="tab_status"  href="#member{{ $member_stat->id }}" id="m{{ $member_stat->id }}" style="color:{{$member_stat->font_color}}">{{ isset($member_stat->id) ? CommonHelper::get_member_status_name($member_stat->id) : "" }}</a></li>  
									@endforeach
                                </ul>  
                            </div>  
                                <div id="inbox" class="col s12">
									<div class="col sm12 m12">
										<table id="page-length-option" class="datatable-display">
											<thead>
											<tr>
											<th>{{__('Member Name')}}</th>
											<th>{{__('Member Code')}}</th>
											<th>{{__('NRIC')}}</th>
											<th>{{__('Amount')}}</th>
											<th>{{__('Status')}}</th>
											<th>{{__('Action')}}</th>
											</tr>
											</thead>                                                        
										   
										</table>
									</div>	
                                </div>  
								@php
								$loopcount=0;
								@endphp
                                @foreach($data['member_stat'] as  $key => $member_stat)
                                <div id="member{{ $member_stat->id }}" class="col s12">
									<div class="col sm12 m12">
										<table id="page-length-option" class="datatable-display-{{ $member_stat->id }}" width="100%">
											<thead>
											<tr>
											<th>{{__('Member Name')}}</th>
											<th>{{__('Member Code')}}</th>
											<th>{{__('NRIC')}}</th>
											<th>{{__('Amount')}}</th>
											<th>{{__('Action')}}</th>
											</tr>
											</thead>                                                        
										   
										</table>
									</div>	
								</div>   
								@php
									$loopcount++;
								@endphp
                                @endforeach
                               
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

$(document).ready(function(){
    $('.datatable-display').DataTable({
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
            "url": "{{ url(app()->getLocale().'/ajax_submember_list') }}?company_id="+{{$data['company_auto_id']}}+"&status=all",
            "dataType": "json",
            "type": "POST",
            "data": {
                _token: "{{csrf_token()}}"
            }
        },
        "columns": [{
                "data": "Name"
            },
            {
                "data": "membercode"
            },
            {
                "data": "nric"
            },
            {
                "data": "amount"
            },
            
            {
                "data": "statusId"
            },
            {
                "data": "options"
            }
        ],
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
				$('td', nRow).css('color', aData.font_color );
			}
    });
	@if($loopcount == count($data['member_stat']))
	@foreach($data['member_stat'] as  $key => $member_stat)
	$('.datatable-display-{{$member_stat->id}}').DataTable({
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
		"url": "{{ url(app()->getLocale().'/ajax_submember_list') }}?company_id="+{{$data['company_auto_id']}}+"&status="+{{$member_stat->id}},
            "dataType": "json",
            "type": "POST",
            "data": {
                _token: "{{csrf_token()}}"
            }
        },
        "columns": [{
                "data": "Name"
            },
            {
                "data": "membercode"
            },
            {
                "data": "nric"
            },
            {
                "data": "amount"
            },
            {
                "data": "options"
            }
        ],
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
				$('td', nRow).css('color', aData.font_color );
			}
    });
	
	@endforeach
	@endif
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