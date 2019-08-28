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
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/custom_respon.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/buttons.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/font-awesome.min.css') }}">
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
                                        <h5 class="breadcrumbs-title mt-0 mb-0">{{__('Race List') }}</h5>
                                        <ol class="breadcrumbs mb-0">
                                            <li class="breadcrumb-item"><a
                                                    href="{{ route('home', app()->getLocale()) }}">{{__('Dashboard') }}</a>
                                            </li>
                                            <li class="breadcrumb-item active">{{__('Race') }}
                                            </li>
                                        </ol>
                                    </div>
                                    <div class="col s2 m6 l6 ">
                                        <a class="btn waves-effect waves-light breadcrumbs-btn right modal-trigger"
                                            onClick='showaddForm();' href="#modal_add_edit">{{__('Add') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12">
                            <div class="card">
                                <div class="card-content">
                                    <h4 class="card-title">{{__('Race List') }}</h4>
                                    @include('includes.messages')
                                    <div class="row">
                                        <div class="col s12">
                                            <table id="page-length-option" class="display">
                                                <thead>
                                                    <tr>
                                                        <th>{{__('Race Name') }}</th>

                                                        <th style="text-align:center;"> {{__('Action') }}</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="modal_add_edit" class="modal modal-fixed-header">
							    <div class="modal-header" id="modal-header">
									<h4>{{__('Race Details') }}</h4>
								</div>
                                <div class="modal-content">
                                <form class="formValidate" id="race_formValidate" method="post"
                                    action="{{ route('master.saverace',app()->getLocale()) }}">
                                    @csrf
                                    <input type="hidden" name="id" id="updateid">
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <label for="race_name"
                                                class="common-label force-active">{{ __('Race Name') }}*</label>
                                            <input id="race_name" class="common-input" name="race_name" type="text"
                                                data-error=".errorTxt1">
                                            <div class="errorTxt1"></div>
                                        </div>
										<div class="input-field col s12">
                                            <label for="short_code"
                                                class="common-label force-active">{{ __('Short Code') }}*</label>
                                            <input id="short_code" class="common-input" name="short_code" type="text"
                                                data-error=".errorTxt2">
                                            <div class="errorTxt2"></div>
                                        </div>
                                        <div class="clearfix" style="clear:both"></div>
                                        </div>
								</div>
								<div class="modal-footer">
									<a href="#!"
										class="modal-action modal-close btn waves-effect waves-light cyan">{{__('Close')}}</a>
									<button id="modal-update-btn" class="btn waves-effect waves-light submit edit_hide_btn "
										type="submit" name="action">{{__('Update')}}
									</button>
									<button id="modal-save-btn" class="btn waves-effect waves-light submit add_hide"
										style="display:none;" type="submit" name="action">{{__('Save')}}
									</button>
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
<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/assets/js/scripts/form-validation.js')}}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/dataTables.buttons.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/buttons.flash.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jszip.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/pdfmake.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/vfs_fonts.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/buttons.html5.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/buttons.print.min.js') }}" type="text/javascript"></script>
@endsection
@section('footerSecondSection')
<script src="{{ asset('public/assets/js/scripts/data-tables.js') }}" type="text/javascript"></script>
<script>
$("#masters_sidebars_id").addClass('active');
$("#race_sidebar_li_id").addClass('active');
$("#race_sidebar_a_id").addClass('active');

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
					title : 'Race List'
			   },
			   {
				   extend: 'csv',
				   footer: false,
				   exportOptions: {
						columns: [0]
					},
					title : 'Race List'
			   },
			   {
				   extend: 'excel',
				   footer: false,
				   exportOptions: {
						columns: [0]
					},
					title : 'Race List'
			   },
				{
				   extend: 'print',
				   footer: false,
				   exportOptions: {
						columns: [0]
					},
					title : 'Race List'
			   }  
			],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "{{ url(app()->getLocale().'/ajax_race_list') }}",
            "dataType": "json",
            "type": "POST",
            "data": {
                _token: "{{csrf_token()}}"
            }
        },
        "columns": [{
                "data": "race_name"
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
$("#race_formValidate").validate({
    rules: {
        race_name: {
            required: true,
            remote: {
                url: "{{ url(app()->getLocale().'/race_nameexists')}}",
                data: {
                    race_id: function() {
                        return $("#updateid").val();
                    },
                    _token: "{{csrf_token()}}",
                    race_name: $(this).data('race_name')
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
        race_name: {
            required: '{{__("Enter a Race Name") }}',
            remote: '{{__("Race Name Already exists") }}',
        },
		short_code: {
			required: '{{__("Enter a Short Code") }}',
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
//Model
$(document).ready(function() {
    // the "href" attribute of the modal trigger must specify the modal ID that wants to be triggered
    $('.modal').modal();
});

function showaddForm() {
    $('.edit_hide').show();
    $('.add_hide').show();
    $('.edit_hide_btn').hide();
    $('#race_name').val("");
	$('#short_code').val("");
    $('.modal').modal();
    $('#updateid').val("");
    $('.common-label').removeClass('force-active');
}

function showeditForm(raceid) {
    $('.edit_hide').hide();
    $('.add_hide').hide();
    $('.edit_hide_btn').show();
    $('.modal').modal();
    loader.showLoader();
    var url = "{{ url(app()->getLocale().'/race_detail') }}" + '?id=' + raceid;
    $.ajax({
        url: url,
        type: "GET",
        success: function(result) {
            $('#updateid').val(result.id);
            $('#updateid').attr('data-autoid', result.id);
            $('#race_name').val(result.race_name);
			$('#short_code').val(result.short_code);
            loader.hideLoader();
            $('.common-label').addClass('force-active');
            $("#modal_add_edit").modal('open');
        }
    });
}
$(document).on('submit','form#race_formValidate',function(){
    $("#modal-save-btn").prop('disabled',true);
    $("#modal-update-btn").prop('disabled',true);
});
</script>
@endsection