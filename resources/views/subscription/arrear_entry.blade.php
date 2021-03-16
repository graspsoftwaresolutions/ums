

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
    <link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/custom_respon.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/buttons.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/font-awesome.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/export-button.css') }}">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
    <style>
	#main.main-full {
		height: 750px;
		overflow: auto;
	}
	
	.footer {
	   //position: fixed;
	   margin-top:50px;
	   left: 0;
	   bottom: 0;
	   width: 100%;
	   height:auto;
	   background-color: red;
	   color: white;
	   text-align: center;
	   z-index:999;
	} 
	.sidenav-main{
		z-index:9999;
	}
</style>
@endsection
@section('main-content')

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
          <li class="breadcrumb-item"><a href="">{{__('Arrear Entry') }}</a>
          </li>
          <li class="breadcrumb-item active">{{__('Arrear List') }}
          </li>
          </li>
      </ol>
  </div>
  <div class="col s2 m6 l6">
      <div class="right">
        <a class="btn waves-effect waves-light breadcrumbs-btn hide"
         href="{{ route('subscription.addarrearentry', app()->getLocale())  }}">{{__('Add') }}</a>

        <a class="btn waves-effect waves-light blue breadcrumbs-btn"
         href="{{ route('subs.addbulkarrearentry', app()->getLocale())  }}">{{__('Add Bulk') }}</a>
      </div>
      
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
                                  <h4 class="card-title">{{__('Arrear List') }}</h4>
                                  @include('includes.messages')
                                  <div class="row">
                                      <div class="col s12">
                                          <table id="page-length-option" class="display">
                                              <thead>
                                                  <tr>
                                                      <th>{{__('NRIC') }}</th>
                                                      <th>{{__('MemberID') }}</th>
                                                      <th>{{__('MemberName') }}</th>
                                                      <th>{{__('Company') }}</th>
                                                      <th>{{__('Branch') }}</th>
                                                      <th>{{__('arrear_date') }}</th>
                                                      <th>{{__('Arrear Amount') }}</th>
                                                     <!--  <th>{{__('No of Months') }}</th> -->
                                                      <th>{{__('Status') }}</th>
                                                      <th style="text-align:center;"> {{__('Action') }}</th>
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
<script src="{{ asset('public/assets/js/jquery.autocomplete.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/dataTables.buttons.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/buttons.flash.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jszip.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/pdfmake.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/vfs_fonts.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/buttons.html5.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/buttons.print.min.js') }}" type="text/javascript"></script>
<script>

$("#subscriptions_sidebars_id").addClass('active');
$("#subsarrear_sidebar_li_id").addClass('active');
$("#subarrear_sidebar_a_id").addClass('active');


$('.datepicker').datepicker({
	format: 'dd/mmm/yyyy'
});

//Data table Ajax call
$(function () {
    $('#page-length-option').DataTable({
			"responsive": true,
		   "lengthMenu": [
            [10, 25, 50, 100, 3000],
            [10, 25, 50, 100, 'All']
        ],
			"processing": true,
			"serverSide": true,
      dom: 'lBfrtip', 
          buttons: [
         {
           extend: 'pdf',
           footer: true,
           exportOptions: {
            columns: [0,1,2,3,4,5,6,7]
          },
                  title : 'Arrear List',
                  text: '<i class="fa fa-file-pdf-o"></i>',
                  titleAttr: 'pdf'
         },
         {
                 extend: 'excel',
           footer: false,
           exportOptions: {
            columns: [0,1,2,3,4,5,6,7]
          },
                  title : 'Arrear List',
                  text:    '<i class="fa fa-file-excel-o"></i>',
                  titleAttr: 'excel'
         },
        {
                 extend: 'print', 
           footer: false,
           exportOptions: {
            columns: [0,1,2,3,4,5,6,7]
          },
                  title : 'Arrear List',
                  text:   '<i class="fa fa-files-o"></i>',
                  titleAttr: 'print'
         }  
      ],
			"ajax": {
				"url": "{{ url(app()->getLocale().'/ajax_arrear_list') }}",
				"dataType": "json",
				"type": "POST",
				"data": {_token: "{{csrf_token()}}"},
        "error": function (jqXHR, textStatus, errorThrown) {
            if(jqXHR.status==419){
              alert('Your session has expired, please login again');
              window.location.href = base_url;
            }
       },
			},
			"columns": [
				{"data" : "nric"},
                {"data": "membercode"},
                {"data": "membername"},
                {"data": "company_id"},
                {"data": "branch_id"},
                {"data": "arrear_date"},
				{"data": "arrear_amount"},
                // {"data": "no_of_months"},
				{"data": "status_id"},
                {"data": "options"}
			],
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
				$('td', nRow).css('color', aData.font_color );
			}
		});
       
});
function ConfirmDeletion() {
    if (confirm("{{ __('Are you sure you want to delete?') }}")) {
        return true;
    } else {
        return false;
    }
}
//Model
$(document).ready(function() {
// the "href" attribute of the modal trigger must specify the modal ID that wants to be triggered
  $('.modal').modal();
  if($("#page-length-option").height()<230){
    $(".footer").css('position','fixed');
  }
});
</script>
@endsection