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
                                        <h5 class="breadcrumbs-title mt-0 mb-0">{{__('Pending Members List')}}</h5>
                                        <ol class="breadcrumbs mb-0">
                                            <li class="breadcrumb-item"><a
                                                    href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard')}}</a>
                                            </li>
                                            <li class="breadcrumb-item active">{{__('Members List')}}
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
							
								
                            @include('includes.messages')
                            <div class="row">
                            <div class="col s12">  
                                <ul class="tabs">  
									<li class="tab col s3"><a class="active tab_status" href="#inbox" id="all">{{__('All')}}</a></li>  
									
                                </ul>  
                            </div>
                            @php
                            $enccompany_auto_id = Crypt::encrypt($data['company_auto_id']);
                            @endphp  
                                <div id="inbox" class="col s12">
									<div class="col sm12 m12">
										<table id="page-length-option" class="display ">
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
                                            <tbody>
                                            </tbody>                                                       
										   
										</table>
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
            "url": "{{ url(app()->getLocale().'/ajax_pending_member_list') }}?company_id="+{{$data['company_auto_id']}},
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
        ]
    });
});

</script>
@endsection