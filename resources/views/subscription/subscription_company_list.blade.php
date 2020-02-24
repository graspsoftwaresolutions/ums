@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
<link rel="stylesheet" type="text/css"
href="{{ asset('public/assets/vendors/data-tables/css/jquery.dataTables.min.css') }}"> 
<link rel="stylesheet" type="text/css"
href="{{ asset('public/assets/vendors/data-tables/css/select.dataTables.min.css') }}"> 
<link rel="stylesheet" type="text/css"
href="{{ asset('public/assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css') }}">
@endsection
@section('headSecondSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/pages/data-tables.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/custom_respon.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/buttons.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/font-awesome.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/export-button.css') }}">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
@endsection
@section('main-content')
<div id="">
<div class="row">
<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
<div class="breadcrumbs-dark" id="breadcrumbs-wrapper">
<!-- Search for small screen-->
<div class="container">
<div class="row">
  <div class="col s10 m6 l6">
      <li class="breadcrumb-item"><a
              href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard') }}</a></li>
      <ol class="breadcrumbs mb-0">
          <li class="breadcrumb-item"><a href="">{{__('Company') }}</a>
          </li>
          <li class="breadcrumb-item active">{{__('Company List') }}
          </li>
          </li>
      </ol>
  </div>
  <div class="col s2 m6 l6">
     
  </div>
</div>
</div>
</div>
<div class=" col s12">
          <div class="container">
              <div class="section section-data-tables">
                  <!-- Page Length Options -->
                  <div class="row">
                      <div class="col s12">
                          <div class="card">
                              <div class="card-content">
                                  <h4 class="card-title">{{__('Company List') }}[Bank]</h4>
                                  @include('includes.messages')
                                  <div class="row">
                                      <div class="col s12">
                                          <table id="page-length-option" class="display">
                                              <thead>
                                                  <tr>
                                                      <th>{{__('MonthYear') }}</th>
                                                      <th>{{__('Company Name') }}</th>
                                                      <th>{{__('Count') }}</th>
                                                      <th>{{__('Amount') }}</th>
                                                     
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
                  <!-- Multi Select -->
              </div><!-- START RIGHT SIDEBAR NAV -->
              @include('layouts.right-sidebar')
              <!-- END RIGHT SIDEBAR NAV -->

          </div>
  </div>
</div>
</div>
<!-- END: Page Main-->
<!-- Theme Customizer -->
@endsection
@section('footerSection')
<script src="{{ asset('public/assets/vendors/data-tables/js/jquery.dataTables.min.js') }}"
type="text/javascript"></script>
<script
src="{{ asset('public/assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js') }}"
type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/data-tables/js/dataTables.select.min.js') }}"
type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/assets/js/scripts/form-validation.js')}}" type="text/javascript"></script>
@endsection
@section('footerSecondSection')
<script src="{{ asset('public/assets/js/scripts/data-tables.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/dataTables.buttons.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/buttons.flash.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jszip.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/pdfmake.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/vfs_fonts.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/buttons.html5.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/buttons.print.min.js') }}" type="text/javascript"></script>

<script>
$("#subscriptions_sidebars_id").addClass('active');
$("#subscompbank_sidebar_li_id").addClass('active');
$("#subscompbank_sidebar_a_id").addClass('active');

//Data table Ajax call
//Data table Ajax call
$(function() {
    $('#page-length-option').DataTable({
        "responsive": true,
		"order": [[ 0, "desc" ]],
         "lengthMenu": [
            [10, 25, 50, 100, 3000],
            [10, 25, 50, 100, 'All']
        ],
        /* "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ], */
        "processing": true,
        "serverSide": true,
         dom: 'lBfrtip', 
          buttons: [
         {
           extend: 'pdf',
           footer: true,
           exportOptions: {
            columns: [0,1]
          },
                  title : 'Company List',
                  text: '<i class="fa fa-file-pdf-o"></i>',
                  titleAttr: 'pdf'
         },
         {
                 extend: 'excel',
           footer: false,
           exportOptions: {
            columns: [0,1]
          },
                  title : 'Company List',
                  text:    '<i class="fa fa-file-excel-o"></i>',
                  titleAttr: 'excel'
         },
        {
                 extend: 'print', 
           footer: false,
           exportOptions: {
            columns: [0,1]
          },
                  title : 'Company List',
                  text:   '<i class="fa fa-files-o"></i>',
                  titleAttr: 'print'
         }  
      ],
        "ajax": {
            "url": "{{ url(app()->getLocale().'/ajax_subscriptioncompany_list') }}",
            "dataType": "json",
            "type": "POST",
            "data": {
                _token: "{{csrf_token()}}"
            },
            "error": function (jqXHR, textStatus, errorThrown) {
              if(jqXHR.status==419){
                alert('Your session has expired, please login again');
                window.location.href = base_url;
              }
            },
        },
        "columns": [{
                "data": "month_year"
            },
            {
                "data": "company_name"
            },
            {
                "data": "count"
            },
            {
                "data": "amount"
            }
        ]
    });
});


</script>
@endsection