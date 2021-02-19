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
          <li class="breadcrumb-item active">{{__('Eco Park List') }}
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
                                  <h4 class="card-title">{{__('Eco Park List') }}</h4>
                                  @include('includes.messages')
                                  <div class="row">
                                      <div class="col s12">
                                          <table id="page-length-option" class="display">
                                              <thead>
                                                  <tr>
                                                      <th>{{__('MonthYear') }}</th>
                                                      <th>{{__('Count') }}</th>
                                                      <th>{{__('Amount') }}</th>
                                                      <th>{{__('Action') }}</th>
                                                     
                                                  </tr>
                                              </thead>
                                              <tbody>
                                                @foreach($data['parkdates'] as $parkdate)
                                                 <tr>
                                                   <td>{{ date('M/Y',strtotime($parkdate)) }}</td>
                                                   <td>{{ CommonHelper::EcoParkMembersCount($parkdate) }}</td>
                                                   <td>{{ number_format(CommonHelper::EcoParkMembersAmount($parkdate),2,".",",") }}</td>
                                                   <td>
                                                    <a class="mb6 btn btn-sm waves-light purple lightrn-1" href="{{ route('ecopark.summary', [app()->getLocale()]) }}?date={{ strtotime($parkdate) }}">Summary</a>
                                                     <a class="mb6 btn btn-sm waves-light orange lightrn-1" href="{{ route('ecopark.members', [app()->getLocale()]) }}?date={{ strtotime($parkdate) }}">View Members</a>
                                                   </td>
                                                 </tr>
                                                @endforeach
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
$("#ecoparklist_sidebar_li_id").addClass('active');
$("#ecoparklist_sidebar_a_id").addClass('active');

//Data table Ajax call
//Data table Ajax call
$(function() {
    $('#page-length-option').DataTable({});
      
});


</script>
@endsection