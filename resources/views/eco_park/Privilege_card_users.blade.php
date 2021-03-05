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
          <li class="breadcrumb-item"><a href="">{{__('Eco Park') }}</a>
          </li>
          <li class="breadcrumb-item active">{{__('Privilege Card List') }}
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
                                  <h4 class="card-title">{{__('Privilege Card List') }}</h4>
                                  @include('includes.messages')
                                  <div class="row">
                                      <div class="col s12">
                                          <table id="page-length-option" class="display">
                                              <thead>
                                                  <tr>
                                                      <th width="10%">{{__('Member Name')}}</th>
                                                      <th width="9%">{{__('Member Id')}}</th>
                                                      
                                                      <th width="10%">{{__('NRIC-New')}}</th>
                                                      <th width="7%">{{__('Privilege Card No')}}</th>
                                                      <th width="10%">{{__('Member Status')}}</th>
                                                      <th width="10%">{{__('EP Card Status')}}</th>
                                                      <th width="10%">{{__('NPC Status')}}</th>
                                                      <th width="10%">{{__('Approval Status')}}</th>
                                                      <th width="15%">{{__('Action')}}</th>
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
$("#ecopark_sidebars_id").addClass('active');
$("#privilegecard_sidebar_li_id").addClass('active');
$("#privilegecard_sidebar_a_id").addClass('active');

//Data table Ajax call
//Data table Ajax call
$(function() {
    var dataTable =   $('#page-length-option').DataTable({
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
          "url": "{{ url(app()->getLocale().'/ajax_privilege_card_users_list') }}?status=all",
          "dataType": "json",
          "type": "POST",
          headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
          'data': function(data){
             var memberid      = $('#memberid').val();
             
            
            //console.log(data);
            data._token = "{{csrf_token()}}";
          },
          "error": function (jqXHR, textStatus, errorThrown) {
                  if(jqXHR.status==419){
                    alert('Your session has expired, please login again');
                    window.location.href = base_url;
                  }
             },
        },
        "columns": [{
            "data": "name"
          },
          {
            "data": "member_number"
          },
          {
            "data": "nric_new"
          },
          {
            "data": "privilege_card_no"
          },
          {
            "data": "status_name"
          },
          {
            "data": "card_status"
          },
          {
            "data": "pc_status_name"
          },
          {
            "data": "approval_status"
          },
          {
            "data": "options"
          }
        ],
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
          //  $('td', nRow).css('color', aData.pc_font_color );
            $('td', nRow).eq(6).css('background-color', aData.pc_font_color);
            $('td', nRow).eq(6).css('color', '#fff');
             
          }
      });
            
});

</script>
@endsection