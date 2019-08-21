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
<style>
	#main.main-full {
		height: 750px;
		overflow: auto;
	}
	
	.footer {
	   position: fixed;
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
                                        <h5 class="breadcrumbs-title mt-0 mb-0">{{__('Fee List') }}</h5>
                                        <ol class="breadcrumbs mb-0">
                                            <li class="breadcrumb-item"><a
                                                    href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard') }}</a>
                                            </li>
                                            <li class="breadcrumb-item active">{{__('Fee') }}
                                            </li>

                                        </ol>
                                    </div>
                                    <div class="col s2 m6 l6 ">
                                        <a class="btn waves-effect waves-light breadcrumbs-btn right modal-trigger"
                                            onClick='showaddForm();' href="#modal_add_edit">{{__('Add')}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12">
                            <div class="card">
                                <div class="card-content">
                                    <h4 class="card-title">{{__('Fee List') }}</h4>
                                    @include('includes.messages')
                                    <div class="row">
                                        <div class="col s12">
                                            <table id="page-length-option" class="display">
                                                <thead>
                                                    <tr>
                                                        <th>{{__('Fee Name') }}</th>
                                                        <th>{{__('Fee Amount') }}</th>
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
                                <h4>{{__('Fee Details') }}</h4>
                                </div>
                                <div class="modal-content">
                                <form class="formValidate" id="feeformValidate" method="post"
                                    action="{{ route('master.savefee', app()->getLocale()) }}">
                                    @csrf
                                    <input type="hidden" name="id" id="updateid">
                                    <div class="row">
                                        <div class="input-field col s12 m6">
                                            <label for="fee_name"
                                                class="common-label force-active">{{__('Fee Name') }}*</label>
                                            <input id="fee_name" name="fee_name" class="common-input" type="text"
                                                data-error=".errorTxt1">
                                            <div class="errorTxt1"></div>
                                        </div>
                                        <div class="input-field col s12 m6">
                                            <label for="fee_amount"
                                                class="common-label force-active">{{__('Fee Amount') }}*</label>
                                            <input id="fee_amount" name="fee_amount" class="common-input" type="text"
                                                data-error=".errorTxt2">
                                            <div class="errorTxt2"></div>
                                        </div>
                                        <div class="input-field col s12 m6">
                                            <label for="fee_shortcode"
                                                class="common-label force-active">{{__('Fee Short Code') }}*</label>
                                            <input id="fee_shortcode" name="fee_shortcode" class="common-input" type="text"
                                                data-error=".errorTxt3">
                                            <div class="errorTxt3"></div>
                                        </div>
                                        <div class="clearfix" style="clear:both"></div>
                                        <div class="input-field col s12 m6">
                                            <p>
                                                <label>
                                                    <input type="checkbox" name="is_monthly_payment"
                                                        class="common-checkbox" id="is_monthly_payment" 
                                                        > 
                                                    <span>{{__('Is Monthly payment') }}</span>
                                                </label>
                                            </p>
                                        </div>
                                        </div></div>
                                        <div class="modal-footer">
                                            <a href="#!"
                                                class="modal-action modal-close btn waves-effect waves-light cyan">{{__('Close') }}</a>
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
<!--<script src="{{ asset('public/assets/vendors/data-tables/js/dataTables.select.min.js') }}" type="text/javascript"></script>-->
@endsection
@section('footerSecondSection')
<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/assets/js/scripts/form-validation.js')}}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/scripts/data-tables.js') }}" type="text/javascript"></script>
<script>
$("#masters_sidebars_id").addClass('active');
$("#fee_sidebar_li_id").addClass('active');
$("#fee_sidebar_a_id").addClass('active');
</script>
<script>
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
            "url": "{{ url(app()->getLocale().'/ajax_fees_list') }}",
            "dataType": "json",
            "type": "POST",
            "data": {
                _token: "{{csrf_token()}}"
            }
        },
        "columns": [{
                "data": "fee_name"
            },
            {
                "data": "fee_amount"
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

// Form Validation
$("#feeformValidate").validate({
    rules: {
        fee_name: {
            required: true,
            remote: {
                url: "{{ url(app()->getLocale().'/fee_nameexists')}}",
                data: {
                    fee_id: function() {
                        return $("#updateid").val();
                    },
                    _token: "{{csrf_token()}}",
                    fee_name: $(this).data('fee_name')
                },
                type: "post",
            },
        },
        fee_amount: {
            required: true,
            digits : true,
        },
        fee_shortcode: {
            required: true,
        },
    },
    //For custom messages
    messages: {
        fee_name: {
            required: '{{__("Enter a Fee Name") }}',
            remote: '{{__("Fee Name Already exists") }}',
        },
        fee_amount: {
            required: '{{__("Enter a Fee Amount") }}',
            digits : '{{__("Enter Numbers only") }}',
        },
        fee_shortcode: {
            required: '{{__("Enter a Fee Short Code") }}',
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
    $('#fee_name').val("");
    $('#fee_amount').val("");
    $('#is_monthly_payment').val("1");
    $('#fee_shortcode').val("");
    $("#fee_shortcode").prop("readonly", false);
    $('.modal').modal();
    $('#updateid').val("");
    $('.common-label').removeClass('force-active');
}

function showeditForm(feeid) {
    $('.edit_hide').hide();
    $('.add_hide').hide();
    $("#fee_shortcode").prop("readonly", true);
    $('.modal').modal();
    loader.showLoader();
    var url = "{{ url(app()->getLocale().'/fee_detail') }}" + '?id=' + feeid;
    $.ajax({
        url: url,
        type: "GET",
        success: function(result) {
           // console.log(result);
            $('#updateid').val(result.id);
            $('#updateid').attr('data-autoid', result.id);
            $('#fee_name').val(result.fee_name);
            $('#fee_amount').val(result.fee_amount);
            $('#fee_shortcode').val(result.fee_shortcode);
            if(result.is_monthly_payment)
            {
                $('#is_monthly_payment').prop('checked', true);
            }
            else{
                $('#is_monthly_payment').prop('checked', false);
            }
            $('.common-label').addClass('force-active');
            
            loader.hideLoader();
            $("#modal_add_edit").modal('open');
        }
    });
}
$(document).on('submit','form#feeformValidate',function(){
    $("#modal-save-btn").prop('disabled',true);
    $("#modal-update-btn").prop('disabled',true);
});
</script>
@endsection