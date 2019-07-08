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
                                        <h5 class="breadcrumbs-title mt-0 mb-0"> {{__('Edit Designation Details')}}</h5>
                                        <ol class="breadcrumbs mb-0">
                                            <li class="breadcrumb-item"><a href="{{ route('home', app()->getLocale()) }}">{{__('Dashboard') }}</a>
                                            </li>
                                            <li class="breadcrumb-item active">{{__('Designation') }}
                                            </li>
                                            
                                        </ol>
                                    </div>
                                    <div class="col s2 m6 l6 ">
                                        <a class="btn waves-effect waves-light breadcrumbs-btn right" href="{{ route('master.designation', app()->getLocale()) }}">{{__('Designation List') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12">
                            <div class="card">
                                <div class="card-content">
                                    <h4 class="card-title">{{__('Edit Designation')}}</h4>
                                    
                                   <div id="view-validations">
                                    <form class="formValidate" id="designation_formValidate" method="post" action="{{ route('master.updatedesignation', app()->getLocale()) }}">
                                       <?php $row = $data['designation_view'][0]; ?>
										@csrf
										<input type="hidden" name="id" value="{{$row->id}}">
                                      <div class="row">
                                        <div class="input-field col s12 m6">
                                          <label for="designation_name" class="common-label">{{__('Designation Name')}}*</label>
                                          <input id="designation_name" name="designation_name" class="common-input" type="text" value="{{ $row->designation_name}}" data-error=".errorTxt1">
                                          <div class="errorTxt1"></div>
                                        </div>
                                        <div class="input-field col s12">
                                          <button class="btn waves-effect waves-light right submit" type="submit" name="action">{{__('Update')}}
                                            <!--i class="material-icons right">send</i-->
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
	$("#designation_sidebar_li_id").addClass('active');
	$("#designation_sidebar_a_id").addClass('active');

    $("#designation_formValidate").validate({
        rules: {
            designation_name: {
                required: true,
            },
        },
        //For custom messages
        messages: {
            designation_name: {
                required: '{{__("Enter a Designation Name") }}',
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