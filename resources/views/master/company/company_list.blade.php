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
<div id="main">
<div class="row">
<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
<div class="breadcrumbs-dark pb-0 pt-4" id="breadcrumbs-wrapper">
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
      <a class="btn waves-effect waves-light breadcrumbs-btn right modal-trigger"
          onClick='showaddForm();' href="#modal_add_edit">{{__('Add') }}</a>
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
                                  <h4 class="card-title">{{__('Company List') }}</h4>
                                  @include('includes.messages')
                                  <div class="row">
                                      <div class="col s12">
                                          <table id="page-length-option" class="display">
                                              <thead>
                                                  <tr>
                                                      <th>{{__('Company Name') }}</th>
                                                      <th>{{__('Short Name') }}</th>
                                                      <th> {{__('Action') }}</th>
                                                  </tr>
                                              </thead>
                                          </table>
                                      </div>
                                  </div>

                              </div>
                          </div>
                      </div>
                      <div id="modal_add_edit" class="modal">
                          <div class="modal-content">
                              <h4>{{__('Company Details') }}</h4>
                              <form class="formValidate" id="company_formValidate" method="post"
                                  action="{{ route('master.saveCompany',app()->getLocale()) }}">
                                  @csrf
                                  <input type="hidden" name="id" id="updateid">
                                  <div class="row">
                                      <div class="input-field col s12 m6">
                                          <label for="company_name"
                                              class="common-label force-active">{{__('Company Name') }}*</label>
                                          <input id="company_name" class="common-input"
                                              name="company_name" type="text" data-error=".errorTxt1">
                                          <div class="errorTxt1"></div>
                                      </div>
                                      <div class="input-field col s12 m6">
                                          <label for="company_name"
                                              class="common-label force-active">{{__('Short Code') }}</label>
                                          <input id="short_code" class="common-input"
                                              name="short_code" type="text" data-error=".errorTxt2">
                                      </div>
                                      <div class="clearfix" style="clear:both"></div>
                                      <div class="col s12 m6">
                                          <label
                                              class="common-label">{{__('Head of Company') }}</label>
                                          <select id="head_of_company" name="head_of_company"
                                              class="error browser-default common-select add-select">
                                              <option value="">{{__('Select Company') }}</option>

                                          </select>
                                      </div>
                                      <div class="clearfix" style="clear:both"></div>
                                      <div class="input-field col s12">
                                          <a href="#!"
                                              class="modal-action modal-close btn waves-effect waves-light cyan">{{__('Close')}}</a>
                                          <button id="modal-update-btn"
                                              class="btn waves-effect waves-light right submit edit_hide_btn "
                                              type="submit" name="action">{{__('Update')}}
                                          </button>
                                          <button id="modal-save-btn"
                                              class="btn waves-effect waves-light right submit add_hide"
                                              style="display:none;" type="submit"
                                              name="action">{{__('Save')}}
                                          </button>
                                      </div>
                                  </div>
                              </form>
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
<script>
$("#masters_sidebars_id").addClass('active');
$("#company_sidebar_li_id").addClass('active');
$("#company_sidebar_a_id").addClass('active');
$('#company_formValidate').validate({
rules: {
  company_name: {
      required: true,
      remote: {
          url: "{{ url(app()->getLocale().'/company_nameexists')}}",
          data: {
              company_id: function() {
                  return $("#updateid").val();
              },
              _token: "{{csrf_token()}}",
              company_name: $(this).data('company_name')
          },
          type: "post",
      },
  },
  short_code: {
      required: true,
  },
},
//For custom messages
messages: {
  company_name: {
      required: '{{__("Please Enter Company Name") }}',
      remote: '{{__("Company Name Already exists") }}',
  },
  short_code: {
      required: '{{__("Please Enter Short Code") }}',
  },
},
errorElement: 'div',
errorPlacement: function(error, element) {
  var placement = $(element).data('error');
  if (placement) {
      $(placement).append(error)
  } else {
      error.insertAfter(element);
  }
}
});
//Data table Ajax call
$(function() {
$('.selelctpicker-modal').select2({
  dropdownParent: $('#modal_add_edit')
});
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
      "url": "{{ url(app()->getLocale().'/ajax_company_list') }}",
      "dataType": "json",
      "type": "POST",
      "data": {
          _token: "{{csrf_token()}}"
      }
  },
  "columns": [{
          "data": "company_name"
      },
      {
          "data": "short_code"
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
//Model
$(document).ready(function() {
// the "href" attribute of the modal trigger must specify the modal ID that wants to be triggered
$('.modal').modal();
});

function showaddForm() {
$('.edit_hide').show();
$('.add_hide').show();
$('.edit_hide_btn').hide();
$('#company_name').val("");
$('#updateid').val("");
$('#short_code').val("");
$('.modal').modal();
var url = "{{ url(app()->getLocale().'/save_company_detail') }}";
$.ajax({
  url: url,
  type: 'GET',
  dataType: 'json', // added data type
  success: function(res) {
      $('#short_code').empty();
      $('#head_of_company').empty();
      $('#head_of_company').append($('<option></option>').attr('value','').text('Select'));
      $.each(res['company'], function(key, entry) {
          $('#head_of_company').append($('<option></option>').attr('value', entry
              .id).text(entry.company_name));
      });
  }
});
}

function showeditForm(companyid) {
$('#head_of_company').empty();
$('.edit_hide').hide();
$('.add_hide').hide();
$('.edit_hide_btn').show();
$('.modal').modal();
var url = "{{ url(app()->getLocale().'/company_detail') }}" + '?id=' + companyid;
$.ajax({
  url: url,
  type: "GET",
  success: function(resultdata) {
      result = resultdata['company'];
      $('#updateid').val(result.id);
      $('#updateid').attr('data-autoid', result.id);
      $('#company_name').val(result.company_name);
      $('#short_code').val(result.short_code);

      $('#head_of_company').empty();
      $('#head_of_company').append($('<option></option>').attr('value','').text('Select'));
      $.each(resultdata['head_company'], function(key, entry) {
        console.log(resultdata);
          $('#head_of_company').append($('<option></option>').attr('value', entry
              .id).text(entry.company_name));
      });
      $("#head_of_company").val(result.head_of_company);
    //  console.log(resultdata);
     // $('#head_of_company').val(result.head_of_company);
  }
});
}
$(document).on('submit','form#company_formValidate',function(){
    $("#modal-save-btn").prop('disabled',true);
    $("#modal-update-btn").prop('disabled',true);
});

</script>
@endsection