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
                                        <h5 class="breadcrumbs-title mt-0 mb-0"> {{__('Edit Company Details') }}</h5>
                                        <ol class="breadcrumbs mb-0">
                                        <li class="breadcrumb-item"><a href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard') }}</a></li>
                                            </li>
                                            <li class="breadcrumb-item active">{{__('Edit Company') }}
                                            </li>
                                        </ol>
                                    </div>
                                    <div class="col s2 m6 l6 ">
                                        <a class="btn waves-effect waves-light breadcrumbs-btn right" href="{{ route('master.company', app()->getLocale())  }}">{{__('Back') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12">
                            <div class="card">
                                <div class="card-content">
                                    <h4 class="card-title">{{__('Edit Company') }}</h4>
                                    @include('includes.messages')
                                    
                                   <div id="view-validations">
                                    <form class="formValidate" id="company_formValidate" method="post" action="{{route('master.companyupdate', app()->getLocale()) }}">
                                     @foreach($data['company_edit'] as $row)
                                   
										@csrf
										<input type="hidden" name="id" value="{{$row->id}}">
                                      <div class="row">
                                      <div class="input-field col s12 m6">
                                          <label for="company_name" class="common-label">{{__('Company Name') }}*</label>
                                          <input id="company_name" name="company_name" value="{{$row->company_name}}" type="text" data-error=".errorTxt1">
                                          <div class="errorTxt1">
                                          @if($errors->has('company_name'))
                                    <span>{{$errors->first('company_name')}}</span>
                                    @endif</div>
                                        </div>
                                        <div class="input-field col s12 m6">
                                          <label for="short_code" class="common-label">{{__('Short Code') }}*</label>
                                          <input id="short_code" name="short_code" value="{{$row->short_code}}" type="text" data-error=".errorTxt2">
                                          <div class="errorTxt2">
                                          @if($errors->has('short_code'))
                                            <span class="text-danger">{{$errors->first('short_code')}}</span>
                                            @endif
                                          </div>
                                        </div>
                                        <div class="clearfix" ></div>
                                        <div class=" col s12 m6 union-data">
                                            <label class="common-label">{{__('Head of Company') }}*</label>
                                                <select id="head_of_company" name="head_of_company" class="error browser-default ">
                                                <option value="">Select Company</option>
                                                <!-- <option value="{{$row->id}}" selected style="display:none;"> {{$row->company_name}}</option> -->
                                                    @foreach($data['company'] as $value)
                                                    @if($value->id != $row->id)
                                                         <option value="{{$value->id}}" @if( $value->id == $row->head_of_company) {{"selected" }}  @endif  >
                                                           {{$value->company_name}}</option>
                                                           @endif
                                                    @endforeach
                                                </select>
                                                <div class="input-field">      
                                                    <div class="errorTxt22"></div>
                                                </div>
                                            </div>
                                          <div class="errorTxt3">
                                          @if($errors->has('head_of_company'))
                                    <span >{{$errors->first('head_of_company')}}</span>
                                    @endif
                                          </div>
                                        </div>
                                        @endforeach
                                        <div class="input-field col s12">
                                          <button class="btn waves-effect waves-light right submit" type="submit" name="action">{{__('Update') }}
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
            
        },
        //For custom messages
        messages: {
            company_name: {
            required: '{{__("Please Enter Company Name") }}',
            },
            short_code: {
                required: '{{__("Please Enter Short Code") }}',
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