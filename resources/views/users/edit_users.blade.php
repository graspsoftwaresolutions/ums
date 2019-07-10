@extends('layouts.admin')
@section('headSection')
@endsection
@section('headSecondSection')
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
                                        <h5 class="breadcrumbs-title mt-0 mb-0">{{__('Users List') }}</h5>
                                        <ol class="breadcrumbs mb-0">
                                            <li class="breadcrumb-item"><a href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard') }}</a>
                                            </li>
                                            <li class="breadcrumb-item active"><a href="{{route('users.index',app()->getLocale())}}">{{__('Users') }}</a>
                                            </li>
                                        </ol>
                                    </div>
                                    <div class="col s2 m6 l6 ">
                                        <a class="btn waves-effect waves-light breadcrumbs-btn right" href="{{ route('users.index',app()->getLocale()) }}">{{__('Back') }}</a> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12">
                            <div class="card">
                                <div class="card-content">
                                    <h4 class="card-title ">{{ __('Edit Users') }}</h4>
                                    @include('includes.messages')
                                   <div id="view-validations">
                                    <form class="formValidate" id="UsersformValidate" method="post" action="{{ route('users.update',[app()->getLocale(),$data['users_edit']->id]) }}">
                                    <?php $row = $data['users_edit'][0]; ?>
                                        @method('PATCH')
                                        @csrf
                                      <div class="row">
                                      <div class="input-field col s12 m6">
                                          <label for="name" class="common-label">{{__('Name') }}*</label>
                                          <input id="name" class="common-input" value="<?php echo $data['users_edit']->name; ?>" name="name" type="text" data-error=".errorTxt1">
                                          <div class="errorTxt1"></div>
                                        </div>
                                        <div class="input-field col s12 m6">
                                          <label for="email" class="common-label">{{__('email') }}*</label>
                                          <input id="email" class="common-input" value="<?php echo $data['users_edit']->email; ?>" name="email" type="email" data-error=".errorTxt2">
                                          <div class="errorTxt2"></div>
                                        </div>
                                        
                                        <div class="clearfix" style="clear:both"></div>
                                        <div class="input-field col s12">
                                          <button class="btn waves-effect waves-light right submit" type="submit" name="action">{{__('Update')}}
                                            <!-- <i class="material-icons right">send</i> -->
                                          </button>
                                        </div>
                                      </div>
                                    </form>
                                  </div>
                                </div>
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
<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
@endsection
@section('footerSecondSection')
<script src="{{ asset('public/assets/js/scripts/form-validation.js')}}" type="text/javascript"></script>
<script>
	$("#masters_sidebars_id").addClass('active');
	$("#users_sidebar_li_id").addClass('active');
	$("#users_sidebar_a_id").addClass('active');
</script>
<script>
    $("#RolesformValidate").validate({
        rules: {
            name:{
                required: true,
            },
            slug:{
                required: true,
            },
        },
        //For custom messages
        messages: {
            
            name: {
                required: '{{__("Please enter Name") }}', 
            },
            slug:{
                required: '{{__("Please enter Slug") }}',
            },
        },
        errorElement: 'div',
        errorPlacement: function (error, element) {
        var placement = $(element).data('error');
        if (placement) {
            $(placement).append(error)
        } else {
            error.insertAfter(element);
        }
        }
    });
</script>
@endsection