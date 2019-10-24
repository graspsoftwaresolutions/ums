@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/vendors/data-tables/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css') }}">
@endsection
@section('headSecondSection')
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/custom_respon.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/pages/data-tables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/buttons.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/font-awesome.min.css') }}">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/export-button.css') }}">
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
                                        <h5 class="breadcrumbs-title mt-0 mb-0">{{__('Reason List') }}</h5>
                                        <ol class="breadcrumbs mb-0">
                                            <li class="breadcrumb-item"><a
                                                    href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard') }}</a>
                                            </li>
                                            <li class="breadcrumb-item active">{{__('Reason') }}
                                            </li>
                                        </ol>
                                    </div>
                                    <div class="col s2 m6 l6 ">
                                        <a class="btn waves-effect waves-light breadcrumbs-btn right modal-trigger"
                                            onClick='showaddForm();'
                                            href="#modal_add_edit">{{__('Add') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12">
                            <div class="card">
                                <div class="card-content">
                                    <h4 class="card-title">{{__('Reason List') }}</h4>
                                    @include('includes.messages')
                                    <div class="row">
                                        <div class="col s12">
                                            <table id="page-length-option" class="display">
                                                <thead>
                                                    <tr>
                                                        <th>{{__('Reason Name') }}</th>
                                                        <th style="text-align:center;"> {{__('Action') }}</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="modal_add_edit" class="modal">
							<div class="modal-header" id="modal-header">                            
                                <h4>{{__('Reason Details') }}</h4>
                            </div>
							 <form class="formValidate" id="add_formValidate" method="post"
                                    action="{{ route('master.reasonSave',app()->getLocale()) }}">
                                    @csrf
								<div class="modal-content">
									<input type="hidden" name="id" id="updateid">
									<div class="row">
										<div class="input-field col s12">
											<label for="reason_name"
												class="common-label force-active">{{__('Reason Name') }}*</label>
											<input id="reason_name" class="common-input" autofocus name="reason_name" type="text"
												data-error=".errorTxt1">
											<div class="errorTxt1"></div>
										</div>
										<div class="input-field col s6">
											<p>
												<label for="is_benefit_valid">
													<input id="is_benefit_valid" class="" name="is_benefit_valid" type="checkbox" value="1" />
													<span>{{__('IsBenefit Valid') }}</span>
												</label>
											</p>
										</div>
										<div class="input-field col s6 hide benefit-fields">
											<label for="minimum_year"
												class="common-label force-active">{{__('Minimum Year') }}*</label>
											<input id="minimum_year" class="common-input " autofocus name="minimum_year" type="text"
												data-error=".errorTxt2">
											<div class="errorTxt2"></div>
										</div>
										<div class="clearfix" style="clear:both"></div>
										<div class="input-field col s6 hide benefit-fields">
											<label for="minimum_refund"
												class="common-label force-active">{{__('Minimum Refund') }}*</label>
											<input id="minimum_refund" class="common-input" name="minimum_refund" type="text"
												data-error=".errorTxt3">
											<div class="errorTxt3"></div>
										</div>
										<div class="input-field col s6 hide benefit-fields">
											<label for="maximum_refund"
												class="common-label force-active">{{__('Maximum Refund') }}*</label>
											<input id="maximum_refund" class="common-input" name="maximum_refund" type="text"
												data-error=".errorTxt4">
											<div class="errorTxt4"></div>
										</div>
										<div class="clearfix" style="clear:both"></div>
										<div class="input-field col s6 hide benefit-fields">
											<label for="five_year_amount"
												class="common-label force-active">{{__('5yr Amount') }}*</label>
											<input id="five_year_amount" class="common-input" name="five_year_amount" type="text"
												data-error=".errorTxt5">
											<div class="errorTxt5"></div>
										</div>
										<div class="input-field col s6 hide benefit-fields">
											<label for="fiveplus_year_amount"
												class="common-label force-active">{{__('After 5yr Amount') }}*</label>
											<input id="fiveplus_year_amount" class="common-input" name="fiveplus_year_amount" type="text"
												data-error=".errorTxt6">
											<div class="errorTxt6"></div>
										</div>
										<div class="input-field col s6 hide benefit-fields">
											<label for="one_year_amount"
												class="common-label force-active">{{__('1yr Amount') }}*</label>
											<input id="one_year_amount" class="common-input" name="one_year_amount" type="text"
												data-error=".errorTxt7">
											<div class="errorTxt7"></div>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									
									<button id="modal-update-btn" class="btn waves-effect waves-light submit edit_hide_btn "
										type="submit" name="action">{{__('Update')}}
									</button>
									<button id="modal-save-btn" class="btn waves-effect waves-light submit add_hide"
										style="display:none;" type="submit" name="action">{{__('Save')}}
									</button>
									<a href="#!"
										class="modal-action modal-close btn waves-effect waves-light cyan">{{__('Close') }}</a>

								</div>
                            </form>
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
<script src="{{ asset('public/assets/js/dataTables.buttons.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/buttons.flash.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jszip.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/pdfmake.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/vfs_fonts.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/buttons.html5.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/buttons.print.min.js') }}" type="text/javascript"></script>
<script>
$("#masters_sidebars_id").addClass('active');
$("#reason_sidebar_li_id").addClass('active');
$("#reason_sidebar_a_id").addClass('active');
$("#add_formValidate").validate({
    rules: {
        reason_name: {
            required: true,
            remote: {
                url: "{{ url(app()->getLocale().'/reason_nameexists')}}",
                data: {
                    reason_id: function() {
                        return $("#updateid").val();
                    },
                    _token: "{{csrf_token()}}",
                    reason_name: $(this).data('reason_name')
                },
                type: "post",
            },
        },
    },
    //For custom messages
    messages: {
        reason_name: {
            required: '{{__("Enter a Reason Name") }}',
            remote: '{{__("Reason Name Already exists") }}',
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
    $('#page-length-option').DataTable({
        "responsive": true,
         dom: 'lBfrtip', 
			buttons: [
			   {
				   extend: 'pdf',
				   footer: true,
				   exportOptions: {
						columns: [0]
					},
					title : 'Reason List',
					titleAttr: 'pdf',
            		text:'<i class="fa fa-file-pdf-o"></i>'
			   },
			   {
				   extend: 'excel',
				   footer: false,
				   exportOptions: {
						columns: [0]
					},
					title : 'Reason List',
					text:      '<i class="fa fa-file-excel-o"></i>',
					titleAttr: 'excel'
			   },
				{
				   extend: 'print',
				   footer: false,
				   exportOptions: {
						columns: [0]
					},
					title : 'Reason List',
					text:   '<i class="fa fa-files-o"></i>',
           			titleAttr: 'print'
			   }  
			],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "{{ url(app()->getLocale().'/ajax_reason_list') }}",
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
                "data": "reason_name"
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
    $('#reason_name').val("");
    $('.modal').modal();
    $('#updateid').val("");
	$(".benefit-fields").addClass('hide');
	$("#is_benefit_valid").prop('checked',false);
    $('.common-label').removeClass('force-active');
}
function showeditForm(relationid) {
    $('.edit_hide').hide();
    $('.add_hide').hide();
    $('.edit_hide_btn').show();
    $('.modal').modal();
    loader.showLoader();
    var url = "{{ url(app()->getLocale().'/reason_detail') }}" + '?id=' + relationid;
    $.ajax({
        url: url,
        type: "GET",
        success: function(result) {
            $('#updateid').val(result.id);
            $('#updateid').attr('data-autoid', result.id);
            $('#reason_name').val(result.reason_name);
			if(result.is_benefit_valid==1){
				$("#is_benefit_valid").prop('checked',true);
				$(".benefit-fields").removeClass('hide');
			}else{
				$("#is_benefit_valid").prop('checked',false);
				$(".benefit-fields").addClass('hide');
			}
			$('#minimum_year').val(result.minimum_year);
			$('#minimum_refund').val(result.minimum_refund);
			$('#maximum_refund').val(result.maximum_refund);
			$('#five_year_amount').val(result.five_year_amount);
			$('#fiveplus_year_amount').val(result.fiveplus_year_amount);
			$('#one_year_amount').val(result.one_year_amount);
            loader.hideLoader();
            $('.common-label').addClass('force-active');
            $("#modal_add_edit").modal('open');
        }
    });
    //$('.edit_hide_btn').show();
}
$(document).on('submit','form#add_formValidate',function(){
    $("#modal-save-btn").prop('disabled',true);
    $("#modal-update-btn").prop('disabled',true);
});
$(document).on('click','#is_benefit_valid',function(){
	 if($(this).prop("checked") == true){
		$(".benefit-fields").removeClass('hide');
	 }else{
		 $(".benefit-fields").addClass('hide');
	 }
});
</script>
@endsection