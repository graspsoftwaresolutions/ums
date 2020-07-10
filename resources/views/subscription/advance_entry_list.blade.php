

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
<link href="{{ asset('public/assets/css/jquery-ui-month.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/css/MonthPicker.min.css') }}" rel="stylesheet" type="text/css" />
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
          <li class="breadcrumb-item"><a href="">{{__('Advance Entry') }}</a>
          </li>
          <li class="breadcrumb-item active">{{__('Advance List') }}
          </li>
          </li>
      </ol>
  </div>
  <div class="col s2 m6 l6">
      <div class="right">
      
        <a class="btn waves-effect waves-light blue breadcrumbs-btn"
         href="{{ route('subscription.advance', app()->getLocale())  }}">{{__('Add Advance') }}</a>
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
                                  <h4 class="card-title">{{__('Advance List') }}</h4>
                                  @include('includes.messages')
                                  <div class="row">
                                      <div class="col s12">
                                          <table id="page-length-option" class="display">
                                              <thead>
                                                  <tr>
                                                      <th>{{__('MemberID') }}</th>
                                                      <th>{{__('MemberName') }}</th>
                                                      <th>{{__('Company') }}</th>
                                                      <th>{{__('Branch') }}</th>
                                                      <th>{{__('Advance_date') }}</th>
                                                      <!-- <th>{{__('To_date') }}</th> -->
                                                      <th>{{__('Amount') }}</th>
                                                      <th>{{__('No of Months') }}</th>
                                                      <th>{{__('Paid Amount') }}</th>
                                                      <th>{{__('Balance Amount') }}</th>
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
<!-- Theme Customizer -->  <!-- Modal Structure -->
<div id="modal-approval" class="modal">
    <form class="formValidate" id="approvalformValidate" method="post" action="{{ route('advance_approve.save',app()->getLocale()) }}">
        @csrf
    <input type="text" class="hide" name="member_autoid" id="member_autoid"/>
    <input type="text" class="hide" name="advance_autoid" id="advance_autoid"/>
    <div class="modal-content">
      <h4>Advance details</h4>
      <div class="row">
        <div class="col s12 m6">
           <p>
            Member Name: <span id="view_member_name" class="bold"></span>
            </br>
            M/O: <span id="view_mno" class="bold"></span>
           </p>
        </div>
        <div class="col s12 m6">
           <p>
            Amount: <span id="view_paid" class="bold"></span>
            </br>
            Month(No of months): <span id="view_months" class="bold"></span>(<span id="no_of_monthsid" class="bold"></span>)
           </p>
        </div>
      </div>
      
       </hr>
      
        <div class="row">
            <div class="col m2">
                <label for="appmonth">{{__('Approval Month') }}*</label>
                <input type="text" class="datepicker-custom" name="appmonth" id="appmonth" required="" readonly="" value="" />
            </div>
            <div class="col m2">
              <label for="subscriptionamt">{{__('Subscription') }}*</label>
              <input type="text" class="allow_decimal" onkeyup="return CalculateTotal()" name="subscriptionamt" id="subscriptionamt" required="" value="" />
            </div>
            <div class="col m2">
              <label for="bfamt">{{__('BF') }}*</label>
              <input type="text" class="allow_decimal" onkeyup="return CalculateTotal()" name="bfamt" id="bfamt" value="" />
            </div>
            <div class="col m2">
              <label for="insamt">{{__('Insurance') }}*</label>
              <input type="text" class="allow_decimal" onkeyup="return CalculateTotal()" name="insamt" id="insamt" value="" />
            </div>
            <div class="col m2">
              <label for="totalamt">{{__('Total') }}</label>
              <input type="text" class="" name="totalamt" id="totalamt" readonly="" value="" />
            </div>
        </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="modal-action modal-close btn waves-effect red accent-2 left">Close</button>
      <button type="submit" class="btn waves-light submitApproval" onClick="return ConfirmSubmit()">Submit</button>
    </div>
     </form>
</div>

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
<script src="{{ asset('public/assets/js/jquery-ui-month.min.js')}}"></script>
<script src="{{ asset('public/js/MonthPicker.min.js')}}"></script>
<script src="{{ asset('public/js/sweetalert.min.js')}}"></script>
<script>

$("#subscriptions_sidebars_id").addClass('active');
$("#subsadvance_sidebar_li_id").addClass('active');
$("#subadvance_sidebar_a_id").addClass('active');


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
            columns: [0,1]
          },
                  title : 'Advance List',
                  text: '<i class="fa fa-file-pdf-o"></i>',
                  titleAttr: 'pdf'
         },
         {
                 extend: 'excel',
           footer: false,
           exportOptions: {
            columns: [0,1]
          },
                  title : 'Advance List',
                  text:    '<i class="fa fa-file-excel-o"></i>',
                  titleAttr: 'excel'
         },
        {
                 extend: 'print', 
           footer: false,
           exportOptions: {
            columns: [0,1]
          },
                  title : 'Advance List',
                  text:   '<i class="fa fa-files-o"></i>',
                  titleAttr: 'print'
         }  
      ],
			"ajax": {
				"url": "{{ url(app()->getLocale().'/ajax_advance_list') }}",
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
                {"data": "membercode"},
                {"data": "membername"},
                {"data": "company_id"},
                {"data": "branch_id"},
                {"data": "from_date"},
                // {"data": "to_date"},
				        {"data": "advance_amount"},
                {"data": "no_of_months"},
                {"data": "paid_amount"},
                {"data": "balance_amount"},
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


function PaySubscription(advanceid){
      //alert(123);
    $(".submitApproval").attr('disabled', false);
    $('.modal').modal();
    $("#advanceid").val(advanceid);
    loader.showLoader();
    var url = "{{ url(app()->getLocale().'/advance_payment_info') }}" + '?advanceid=' + advanceid;
    $.ajax({
      url: url,
      type: "GET",
      dataType: "json",
      success: function(result) {
        console.log(result.member_id);
        if(result){
          $("#view_member_name").text(result.name);
          $("#view_mno").text(result.member_number);
          $("#view_paid").text(result.advance_amount);
          $("#view_months").text(result.from_date);
          $("#no_of_monthsid").text(result.no_of_months);
          $("#member_autoid").val(result.member_id);
          $("#advance_autoid").val(result.advanceid);
        }
       
        $("#modal-approval").modal('open');
        loader.hideLoader();
      }
    });
  }
  $(document).ready(function() {
    $('.datepicker-custom').MonthPicker({
        Button: false,
        changeYear: true,
        MonthFormat: 'M/yy',
        OnAfterChooseMonth: function() {
           //getMonthsNumber();
        }
    });
    $('.ui-button').removeClass("ui-state-disabled");
    //$('.datepicker-custom').MonthPicker({ Button: false,dateFormat: 'M/yy' });

});
  $(document).on('input', '.allow_decimal', function(){
   var self = $(this);
   self.val(self.val().replace(/[^0-9\.]/g, ''));
   if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) 
   {
     evt.preventDefault();
   }
 });
  function CalculateTotal(){
    var subscriptionamt = $("#subscriptionamt").val();
    var bfamt = $("#bfamt").val();
    var insamt = $("#insamt").val();
    subscriptionamt = subscriptionamt=='' ? 0 : subscriptionamt;
    bfamt = bfamt=='' ? 0 : bfamt;
    insamt = insamt=='' ? 0 : insamt;
    totalamt = parseFloat(subscriptionamt) + parseFloat(bfamt) + parseFloat(insamt);
    $("#totalamt").val(totalamt);
  }
</script>
@endsection