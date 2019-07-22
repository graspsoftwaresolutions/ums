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
        <div class="col s12">
            <div class="container">
                <div class="section section-data-tables">
                    <!-- BEGIN: Page Main-->
                    <div class="row">
                        <div class="breadcrumbs-dark pb-0 pt-4" id="breadcrumbs-wrapper">
                            <!-- Search for small screen-->
                            <div class="container">
                                <div class="row">
                                    <div class="col s10 m6 l6">
                                        <h5 class="breadcrumbs-title mt-0 mb-0">{{__('Roles List') }}</h5>
                                        <ol class="breadcrumbs mb-0">
                                            <li class="breadcrumb-item"><a
                                                    href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard') }}</a>
                                            </li>
                                            <li class="breadcrumb-item active">{{__('Roles') }}
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
                                    <h4 class="card-title">{{__('Roles List') }}</h4>
                                    @include('includes.messages')
                                    <div class="row">
                                        <div class="col s12">
                                            <table id="page-length-option" class="display">
                                                <thead>
                                                    <tr>
                                                        <th>{{__('Roles Name') }}</th>
                                                        <th>{{__('Slug') }}</th>
                                                        <th style="text-align:center"> {{__('Action') }}</th>
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
                                <h4>Role Details</h4>
                                <form class="formValidate" id="rolesformValidate" method="post"
                                    action="{{ route('master.saverole', app()->getLocale()) }}">
                                    @csrf
                                    <input type="hidden" name="id" id="updateid">
                                    <div class="row">
                                        <div class="input-field col s12 m6">
                                            <label for="fee_name"
                                                class="common-label force-active">{{__('Role Name') }}*</label>
                                            <input id="name" class="common-input" name="name" type="text"
                                                data-error=".errorTxt1">
                                            <div class="errorTxt1"></div>
                                        </div>
                                        <div class="input-field col s12 m6">
                                            <label for="fee_amount"
                                                class="common-label force-active">{{__('Slug') }}*</label>
                                            <input id="slug" class="common-input" name="slug" type="text"
                                                data-error=".errorTxt2">
                                            <div class="errorTxt2"></div>
                                        </div>
                                       
                                       @foreach($data['form_type'] as $value)
                                        <div class="input-field col s12 m6 appform">
                                        <input type="hidden" name="formid" value="{{$value->id}}">
                                        <p>
                                        <label>
                                            <input type="checkbox" id="module_id" name="module_id[]" class="common-checkbox" id="isactive" value="{{$value->id}}" @isset($values) {{ $values->isactive == '1' ? 'checked' : '' }} @endisset />
                                            <span class="common-label force-active">{{$value->formname}}</span>
                                        </label>
                                        </p>
                                        </div>
                                        @endforeach
                                        <div class="clearfix" style="clear:both"></div>
                                        <div class="input-field col s12">
                                            <a href="#!"
                                                class="modal-action modal-close btn waves-effect waves-light cyan">Close</a>
                                            <button class="btn waves-effect waves-light right submit edit_hide_btn "
                                                type="submit" name="action">{{__('Update')}}
                                            </button>
                                            <button class="btn waves-effect waves-light right submit add_hide"
                                                style="display:none;" type="submit" name="action">{{__('Save')}}
                                            </button>
                                        </div>
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
<script>
$("#masters_sidebars_id").addClass('active');
$("#roles_sidebar_li_id").addClass('active');
$("#roles_sidebar_a_id").addClass('active');
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
            "url": "{{ url(app()->getLocale().'/ajax_roles_list') }}",
            "dataType": "json",
            "type": "POST",
            "data": {
                _token: "{{csrf_token()}}"
            }
        },
        "columns": [{
                "data": "name"
            },
            {
                "data": "slug"
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
$("#rolesformValidate").validate({
    rules: {
        name: {
            required: true,
            remote: {
                url: "{{ url(app()->getLocale().'/roles_nameexists')}}",
                data: {
                    role_id: function() {
                        return $("#updateid").val();
                    },
                    _token: "{{csrf_token()}}",
                    name: $(this).data('name')
                },
                type: "post",
            },
        },
        slug: {
            required: true,
        },
    },
    //For custom messages
    messages: {
        name: {
            required: '{{__("Please enter Name") }}',
            remote: '{{__("Role Name Already exists") }}',
        },
        slug: {
            required: '{{__("Please enter Slug") }}',
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
    $('.appform').hide();
    $('#name').val("");
    $('#form_id').val("");
    $('#slug').val("");
    $('.modal').modal();
}
function showeditForm(roleid) {
    $('.edit_hide').hide();
    $('.add_hide').hide();
    $('.edit_hide_btn').show();
    $('.appform').show();
    $('.modal').modal();
    var url = "{{ url(app()->getLocale().'/role_detail') }}" + '?id=' + roleid;
    $.ajax({
        url: url,
        type: "GET",
        success: function(result) {
            console.log(result);
            $('#updateid').val(result.id);
            $('#updateid').attr('data-autoid', result.id);
            $('#name').val(result.name);
            $('#slug').val(result.slug);
        }
    });
}
</script>
@endsection