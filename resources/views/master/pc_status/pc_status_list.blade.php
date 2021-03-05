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
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/export-button.css') }}">
<style type="text/css">
    #main .section-data-tables .dataTables_wrapper table.dataTable tbody th, #main .section-data-tables .dataTables_wrapper table.dataTable tbody td:last-child {
        padding-top: 8px;
        padding-bottom: 8px;
        padding-left: 26px;
        padding-right: 16px;
        font-size: 12px;
        white-space: nowrap;
        text-transform: Uppercase;
        border: none !important;
        border-top: 1px solid lightgrey !important;
    }
    .btn-sm{
        padding: 0px 7px;
        font-size: 8px;
        line-height: 1.5;
        border-radius: 3px;
        color: #fff;
    }
    #page-length-option td:not(:last-child) {
        word-break: break-word !important;
        white-space: unset !important;
        vertical-align: top;
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
                                        <h5 class="breadcrumbs-title mt-0 mb-0">{{__('Privilege Card Status List') }}</h5>
                                        <ol class="breadcrumbs mb-0">
                                            <li class="breadcrumb-item"><a
                                                    href="{{ route('home', app()->getLocale()) }}">{{__('Dashboard') }}</a>
                                            </li>
                                            <li class="breadcrumb-item active">{{__('Privilege Card Status') }}
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
                                    <h4 class="card-title">{{__('Privilege Card Status List') }}</h4>
                                    @include('includes.messages')
                                    <div class="row">
                                        <div class="col s12">
                                            <table id="page-length-option" class="display" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>{{__('Status Name') }}</th>
                                                        <th style="text-align:center;"> {{__('Action') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($data['card_status'] as $status)
                                                    @php
                                                        $autoid = $status->id;
                                                        $delete =  route('pc.statusdestroy', [app()->getLocale(),$status->id]) ;
                                                        $enc_id = Crypt::encrypt($status->id);
                                                        $edit =  "#";
                                                        $actions ="<label style='width:100% !important;float:left;text-align:center;'><a style='' id='$edit' onClick='showeditForm($autoid);' class='' href='$edit'><i class='material-icons' style='color:#2196f3'>edit</i></a>";
                                                        $actions .="<a><form style='display:inline-block;' action='$delete' method='POST'>".method_field('DELETE').csrf_field();
                                                        $actions .="<button  type='submit' class='' style='background:none;border:none;'  onclick='return ConfirmDeletion()'><i class='material-icons' style='color:red;'>delete</i></button> </form>";
                                                    @endphp
                                                    <tr>
                                                        <td style="color: {{ $status->font_color }}"> {{ $status->status_name }} </td>
                                                        <td>{!! $actions !!}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="modal_add_edit" class="modal modal-fixed-header">
                        <div class="modal-header" id="modal-header">                            
                                <h4>{{__('Status Details') }}</h4>
                            </div>
                            <div class="modal-content">
                                <form class="formValidate" id="status_formValidate" method="post"
                                    action="{{ route('pc.saveStatus',app()->getLocale()) }}">
                                    @csrf
                                    <input type="hidden" name="id" id="updateid">
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <label for="status_name"
                                                class="common-label force-active">{{__('Status Name') }}*</label>
                                            <input id="status_name" name="status_name" autofocus class="common-input" type="text"
                                                data-error=".errorTxt1">
                                            <div class="errorTxt1"></div>
                                        </div>
                                        <div class="input-field col s12">
                                            <label for="font_color"
                                                class="common-label force-active">{{__('Font Color') }}*</label><br><br>
                                            <input id="font_color"  name="font_color" class="common-input" type="color"
                                                data-error=".errorTxt2">
                                            <div class="errorTxt2"></div>
                                        </div>
                                        <div class="clearfix" style="clear:both"></div>
                                        </div></div>
                                        <div class="modal-footer">
                                            <button  id="modal-update-btn" class="btn waves-effect waves-light submit edit_hide_btn "
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
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
<script>
$("#masters_sidebars_id").addClass('active');
$("#pcstatus_sidebar_li_id").addClass('active');
$("#pcstatus_sidebar_a_id").addClass('active');
$("#status_formValidate").validate({
    rules: {
        status_name: {
            required: true,
            remote: {
                url: "{{ url(app()->getLocale().'/pcstatus_nameexists')}}",
                data: {
                    status_id: function() {
                        return $("#updateid").val();
                    },
                    _token: "{{csrf_token()}}",
                    race_name: $(this).data('race_name')
                },
                type: "post",
            },
        },
        font_color: {
            required: true,
        },
    },
    //For custom messages
    messages: {
        status_name: {
            required: '{{__("Enter the Status Name") }}',
            remote: '{{__("Status Name Already exists") }}',
        },
        font_color: {
            required: '{{__("Enter the Color Code") }}',
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
    $('#status_name').val("");
    $('#font_color').val("");
    $('.modal').modal();
    $('#updateid').val("");
    $('.common-label').removeClass('force-active');
}

function showeditForm(stateid) {
    $('.edit_hide').hide();
    $('.add_hide').hide();
    $('.edit_hide_btn').show();
    $('.modal').modal();
    loader.showLoader();
    var url = "{{ url(app()->getLocale().'/pcstatus_details') }}" + '?id=' + stateid;
    $.ajax({
        url: url,
        type: "GET",
        dataType: "json",
        success: function(result) {
            $('#updateid').val(result.id);
            $('#updateid').attr('data-autoid', result.id);
            $('#status_name').val(result.status_name);
            $('#font_color').val(result.font_color);
            loader.hideLoader();
            $('.common-label').addClass('force-active');
            $("#modal_add_edit").modal('open');
        }
    });
}
$(document).on('submit','form#status_formValidate',function(){
    $("#modal-save-btn").prop('disabled',true);
    $("#modal-update-btn").prop('disabled',true);
});
</script>
@endsection