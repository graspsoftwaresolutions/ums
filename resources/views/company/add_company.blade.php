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
                                        <h5 class="breadcrumbs-title mt-0 mb-0">Add New Company</h5>
                                        <ol class="breadcrumbs mb-0">
                                            <li class="breadcrumb-item"><a href="#">Dashboard</a>
                                            </li>
                                            <li class="breadcrumb-item active">Add Company
                                            </li>
                                        </ol>
                                    </div>
                                    <div class="col s2 m6 l6 ">
                                        <a class="btn  waves-effect waves-light breadcrumbs-btn right" href="{{url('company')}}">Company List</a>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12">
                            <div class="card">
                                <div class="card-content">
                                    <h4 class="card-title">Add Company</h4>
                                    @include('includes.messages')
                                   <div id="view-validations">
                                    <form class="formValidate" autocomplete="off" id="company_formValidate" method="post" action="{{ url('company_save') }}">
                                        @csrf
                                      <div class="row">
                                        <div class="input-field col s12 m6">
                                          <label for="company_name">Company Name*</label>
                                          <input id="company_name" name="company_name" type="text" data-error=".errorTxt1">
                                          <div class="errorTxt1">
                                            @if($errors->has('company_name'))
                                                <span>{{$errors->first('company_name')}}</span>
                                            @endif
                                            </div>
                                        </div>
                                        <div class="input-field col s12 m6">
                                          <label for="company_name">Short Code*</label>
                                          <input id="short_code" name="short_code" type="text" data-error=".errorTxt2">
                                          <div class="errorTxt2">
                                          @if($errors->has('short_code'))
                                            <span class="text-danger">{{$errors->first('short_code')}}</span>
                                            @endif
                                          </div>
                                        </div>
                                        <div class="input-field col s12 m6">
                                          <label for="head_of_company">Head of Company*</label>
                                          <input id="head_of_company" name="head_of_company" type="text" data-error=".errorTxt3">
                                          <div class="errorTxt3">
                                          @if($errors->has('head_of_company'))
                                    <span >{{$errors->first('head_of_company')}}</span>
                                    @endif
                                          </div>
                                        </div>
                                        
                                        <div class="input-field col s12">
                                          <button class="btn waves-effect waves-light right submit" type="submit" name="action">Submit
                                            <i class="material-icons right">send</i>
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
	$("#company_sidebar_li_id").addClass('active');
    $("#company_sidebar_a_id").addClass('active');
    $('#company_formValidate').validate({
        rules: {
            company_name: {
                required: true,
            },
            short_code: {
                required: true,
            },
            head_of_company: {
                required: true,
            },
        },
        //For custom messages
        messages: {
            company_name: {
            required: "Please Enter Company Name",
            },
            short_code: {
                required: "Please Enter Short Code",
            },
            head_of_company: {
                required: "Please Enter Head of company",
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