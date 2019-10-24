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
                                        <h5 class="breadcrumbs-title mt-0 mb-0">{{__('IRC Users List') }}</h5>
                                        <ol class="breadcrumbs mb-0">
                                            <li class="breadcrumb-item"><a
                                                    href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard') }}</a>
                                            </li>
                                            <li class="breadcrumb-item active">{{__('IRC users') }}
                                            </li>
                                        </ol>
                                    </div>
                                    <div class="col s2 m6 l6 ">
                                        <a class="btn waves-effect waves-light breadcrumbs-btn right modal-trigger"
                                            onClick='showaddForm();' href="{{ route('irc.add',app()->getLocale()) }}">Add</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12">
                            <div class="card">
                                <div class="card-content">
                                    <h4 class="card-title">{{__('IRC Users List') }}</h4>
                                    @include('includes.messages')
                                    <div class="row">
                                        <div class="col s12">
                                            <table id="page-length-option" class="display">
                                                <thead>
                                                    <tr>
                                                        <th>{{__('User Name') }}</th>
                                                        <th>{{__('Email') }}</th>
                                                        <th>{{__('MemberCode') }}</th>
                                                        <th>{{__('Type') }}</th>
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
                            <div class="modal-content">
                                <h4>{{ __('Users Details') }}</h4>
                                <form class="formValidate" id="UsersformValidate" method="post"
                                    action="{{ route('master.saveuser',app()->getLocale()) }}">
                                    @csrf
                                    <input type="hidden" name="id" id="updateid">
                                    <div class="row">
                                        <div class="input-field col s12 m6">
                                            <label for="name" class="common-label force-active">{{__('Name') }}*</label>
                                            <input id="name" class="common-input" name="name" type="text"
                                                data-error=".errorTxt1" value="">
                                            <div class="errorTxt1"></div>
                                        </div>
                                        <div class="input-field col s12 m6">
                                            <label for="email"
                                                class="common-label force-active">{{__('email') }}*</label>
                                            <input id="email" data-autoid="" readonly class="common-input" name="email"
                                                type="email" data-error=".errorTxt2">
                                            <div class="errorTxt2"></div>
                                        </div>
                                        <div class="input-field col s12 m6 edit_hide">
                                            <label for="password" class="common-label">{{__('Password') }}*</label>
                                            <input id="password" class="common-input" name="password" type="password"
                                                data-error=".errorTxt3">
                                            <div class="errorTxt3"></div>
                                        </div>
                                        <div class="input-field col s12 m6 edit_hide">
                                            <label for="password"
                                                class="common-label">{{__('Confirm Password') }}*</label>
                                            <input id="confirm_password" class="common-input" name="confirm_password"
                                                type="password" data-error=".errorTxt4">
                                            <div class="errorTxt4"></div>
                                        </div>
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
<!--script src="{{ asset('public/assets/vendors/data-tables/js/dataTables.select.min.js') }}" type="text/javascript"></script-->
@endsection
@section('footerSecondSection')
<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/assets/js/scripts/form-validation.js')}}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/scripts/data-tables.js') }}" type="text/javascript"></script>
<script>
var deflanguage = '{{ app()->getLocale() }}';

$("#irc_account_sidebar_a_id").addClass('active');

$("#UsersformValidate").validate({
    rules: {
        name: {
            required: true,
        },
        email: {
            required: true,
            remote: {
                url: "{{ url(app()->getLocale().'/users_emailexists')}}",
                data: {
                    login_userid: function() {
                        return $("#updateid").val();
                    },
                    _token: "{{csrf_token()}}",
                    email: $(this).data('email')
                },
                type: "post",
            },
        },
        password: {
            required: true,
        },
        confirm_password: {
            required: true,
            equalTo: "#password",
        },
    },
    //For custom messages
    messages: {
        name: {
            required: '{{__("Please enter Name") }}',
        },
        email: {
            remote: '{{__("Email Already exists") }}',
        },
        password: {
            required: '{{__("Please enter Password") }}',
        },
        confirm_password: {
            required: '{{__("Please enter Confirm Password") }}',
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
            "url": "{{ url(app()->getLocale().'/ajax_irc_users_list') }}",
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
                "data": "email"
            },
			{
                "data": "MemberCode"
            },
			{
                "data": "account_type"
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

function showaddForm(userid) {
    $('.edit_hide').show();
    $('.add_hide').show();
    $('.edit_hide_btn').hide();
    $('#name').val("");
    $('#email').val("");
    $('.modal').modal();
}

function showeditForm(userid) {
    $('.edit_hide').hide();
    $('.add_hide').hide();
    $('.edit_hide_btn').show();
    $('.modal').modal();
    loader.showLoader();
    var url = "{{ url(app()->getLocale().'/users_detail') }}" + '?id=' + userid;
    $.ajax({
        url: url,
        type: "GET",
        success: function(result) {
            $('#updateid').val(result.id);
            $('#updateid').attr('data-autoid', result.id);
            $('#name').val(result.name);
            $('#email').val(result.email);
            $('#email').attr('data-autoid', result.id);
            loader.hideLoader();
            $("#modal_add_edit").modal('open');
        }
    });
}
$(document).ready(function() {
    // the "href" attribute of the modal trigger must specify the modal ID that wants to be triggered
    $('.modal').modal();
    if($("#page-length-option").height()<230){
        $(".footer").css('position','fixed');
      }
});
</script>
@endsection