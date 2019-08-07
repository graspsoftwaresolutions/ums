
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
							$m_dob = $data['member_subscription_details']->doj;
							$member_id = $data['member_subscription_details']->memberid;
							$getsubsamount = CommonHelper::getSubscriptionAmt($member_id);
							$subscriptionamt = 0;
							$dueoverallamt = 0;
							if(!empty($getsubsamount)){
								$subscriptionamt = $getsubsamount['Amount'];	
								$dueoverallamt = CommonHelper::getOverallDue($member_id, $m_dob, $subscriptionamt);
							}
							
                            $row =  $data['member_subscription_details']; 
                            @endphp
                            <div class="card-content">
                            <h4 class="card-title">{{__('Member Subscription List')}}  </h4> 
							<table width="100%" style="font-weight:bold">
								<tr>
									<td width="25%">{{__('Member Name ')}}</td>
									<td width="25%">: {{ isset($row->membername) ? $row->membername : "Nill" }}</td>
									<td width="25%">{{ __('Current Month Paid Amount')}}</td>
									<td width="25%">: {{ $row->Date==date('Y-m-01') ? $subscriptionamt : "No Amount" }}</td>
								</tr>
								<tr>
									<td width="25%">{{__('Status ')}}</td>
									<td width="25%">: {{ isset($row->status_name) ? $row->status_name : "Nill" }}</td>
									<td width="25%">{{__('Overall Due')}}</td>
									<td width="25%">: {{ $dueoverallamt }}</td>
									
								</tr>
								<tr>
									<td width="25%">{{__('Current Month ')}}</td>
									<td width="25%">: @php echo date('M/Y') @endphp</td>
									
									<td width="25%"></td>
									<td width="25%"></td>
								</tr>
							</table>
                            <h6 class="">   </h6>
                            <h6 class="">  </h6>
                                                                   
							<h6 class="">  </h6>
                          
                            <h6 class="">   </h6>
                            <div class="card filter">
								<form method="post" id="filtersubmit" action="{{route('subscription.memberfilter',app()->getLocale())}}">
									@csrf  
									<input type="hidden" name="id" value="{{ isset($row->MemberCode) ? $row->MemberCode : '' }}">
									<input type="hidden" name="memberid" value="{{ isset($row->memberid) ? $row->memberid : ''}}">
									<div class="row">                          
										<div class="input-field col s4">
											<i class="material-icons prefix">date_range</i>
											<input id="from_date" type="text" required class="validate datepicker" name="from_date">
											<label for="from_date">{{__('From Month and Year')}}</label>
										</div>
										<div class="input-field col s4">
											<i class="material-icons prefix">date_range</i>
											<input id="to_date" type="text" required class="validate datepicker" name="to_date">
											<label for="to_date">{{__('To Month and Year')}}</label>
										</div>
										<div class="input-field col s4">
										<input type="submit"  class="btn" name="search" value="{{__('Search')}}">
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
                                            <th>{{__('Month and Year')}}</th>
                                            <th>{{__('Paid Amount')}}</th>
                                            <th>{{__('Due Amount')}}</th>
                                            <th>{{__('Status')}}</th>
                                            </tr>
                                            </tr>  
                                                @php if(count($data['member_subscription_list'])!=0 )
                                                {
                                                    foreach($data['member_subscription_list'] as $key=> $values)
                                                    {
                                                        $monthyear1 = explode("-",$values->Date);
                                                        $monthyear =$monthyear1[0].$monthyear1[1];
                                                        $ctyear = date("Ym");
                                                        if($ctyear == $monthyear){ $act = "Active"; } else{ $act =""; }
                                                        @endphp
                                                        <tr> 
                                                        <td> {{ isset($values->Date) ? date('M/Y', strtotime($values->Date)) : "Nill" }}  </td>
                                                        <td> {{ isset($values->Amount) ? $values->Amount : "Nill" }} </td>
                                                        <td> {{ $subscriptionamt>0 && $subscriptionamt >($values->Amount) ? $subscriptionamt-($values->Amount) : "Paid" }} </td>
														<td> {{ isset($values->status_name) ? $values->status_name : "Nill" }} <!--<span class="new" style "color:white;background:green;padding:2px"> {{ isset($act) ? $act:""  }}</span>--></td>
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