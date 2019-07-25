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
                                        <h5 class="breadcrumbs-title mt-0 mb-0">{{__('State List')}}</h5>
                                        <ol class="breadcrumbs mb-0">
                                            <li class="breadcrumb-item"><a
                                                    href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard')}}</a>
                                            </li>
                                            <li class="breadcrumb-item active">{{__('State')}}
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
                        <div class="row">
                        <div class="col s12">
                        <div class="card">
                            <div class="card-content">
                            <h4 class="card-title">{{__('State List')}}</h4>
                            @include('includes.messages')
                            <div class="row">
                                <div class="col s12">
                                <table id="page-length-option" class="display">
                                    <thead>
                                    <tr>
                                    <th>{{__('Country Name')}}</th>
                                    <th>{{__('State Name')}}</th>
                                    <th>{{__('Action')}}</th>
                                    </tr>
                                    </thead>
                                </table>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                        <!-- <div class="col s12">
                            <div class="card">
                                <div class="card-content">
                                    <h4 class="card-title">{{__('State List')}}</h4>
                                    @include('includes.messages')
                                    <div class="row">
                                        <div class="col s12">
                                            <table id="page-length-option" class="display">
                                                <thead>
                                                    <tr>
                                                        <th>{{__('Country Name')}}</th>
                                                        <th>{{__('State Name')}}</th>
                                                        <th style="text-align:center">{{__('Action')}}</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <div id="modal_add_edit" class="modal">
                            <div class="modal-content">
                                <h4>{{__('State Details')}}</h4>
                                <form class="formValidate" id="stateformValidate" method="post"
                                    action="{{ route('master.savestate',app()->getLocale()) }}">
                                    @csrf
                                    <input type="hidden" name="id" id="updateid">
                                    <div class="row">
                                        <div class="input-field col s12 m6">
                                        <label class="force-active">{{__('Country Name') }}*</label>
                                            <select class="error browser-default common-select" id="country_id"
                                                name="country_id" data-error=".errorTxt1">
                                                <option value="">{{__('Select country')}}</option>
                                                @php
                                                $data1 = CommonHelper::DefaultCountry();
                                                @endphp
                                                @foreach($data['country_view'] as $value)
                                                <option value="{{$value->id}}" @if($data1==$value->id) selected @endif >
                                                    {{$value->country_name}}</option>
                                                @endforeach
                                            </select>
                                            <div class="input-field">
                                                <div class="errorTxt1"></div>
                                            </div>
                                        </div>
                                        <div class="input-field col s12 m6">
                                            <input id="state_name" name="state_name" class="common-text" type="text"
                                                data-error=".errorTxt2">
                                            <div class="errorTxt2"></div>
                                            <label for="icon_room" class="force-active">{{__('State Name')}}</label>
                                        </div>
                                        <div class="clearfix" style="clear:both"></div>
                                        <div class="input-field col s12">
                                            <a href="#!"
                                                class="modal-action modal-close btn waves-effect waves-light cyan">{{__('Close')}}</a>
                                            <button id="modal-update-btn" class="btn waves-effect waves-light right submit edit_hide_btn "
                                                type="submit" name="action">{{__('Update')}}
                                            </button>
                                            <button id="modal-save-btn" class="btn waves-effect waves-light right submit add_hide"
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
$("#state_sidebar_li_id").addClass('active');
$("#state_sidebar_a_id").addClass('active');

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
            "url": "{{ url(app()->getLocale().'/ajax_state_list') }}",
            "dataType": "json",
            "type": "POST",
            "data": {
                _token: "{{csrf_token()}}"
            }
        },
        "columns": [{
                "data": "country_name"
            },
            {
                "data": "state_name"
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
$("#stateformValidate").validate({
    rules: {
        country_id: {
            required: true,
        },
        state_name: {
            required: true,
            remote: {
                url: "{{ url(app()->getLocale().'/state_nameexists')}}",
                data: {
                    state_id: function() {
                        return $("#updateid").val();
                    },
                    country_id: function() {
                        return $("#country_id").val();
                    },
                    _token: "{{csrf_token()}}",
                    state_name: $(this).data('state_name')
                },
                type: "post",
            },
        },
    },
    //For custom messages
    messages: {
        country_id: {
            required: '{{__("Please Choose Country Name") }}',
        },
        state_name: {
            required: '{{__("Please enter State Name") }}',
            remote: '{{__("State Name Already exists") }}',
        }
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
    $('#state_name').val("");
    $('.modal').modal();
    $('#updateid').val("");
}

function showeditForm(countryid) {
    $('.edit_hide').hide();
    $('.add_hide').hide();
    $('.edit_hide_btn').show();
    $('.modal').modal();
    loader.showLoader();
    var url = "{{ url(app()->getLocale().'/state_detail') }}" + '?id=' + countryid;
    $.ajax({
        url: url,
        type: "GET",
        success: function(result) {
            console.log(result);
            $('#updateid').val(result.id);
            $('#updateid').attr('data-autoid', result.id);
            $('#country_id').val(result.country_id);
            $('#state_name').val(result.state_name);
            loader.hideLoader();
            $("#modal_add_edit").modal('open');
        }
    });
}
$(document).on('submit','form#stateformValidate',function(){
    $("#modal-save-btn").prop('disabled',true);
    $("#modal-update-btn").prop('disabled',true);
});

</script>
@endsection