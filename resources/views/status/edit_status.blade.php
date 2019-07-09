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
                                        <h5 class="breadcrumbs-title mt-0 mb-0"> {{ __('Edit Status Details')}}</h5>
                                        <ol class="breadcrumbs mb-0">
                                            <li class="breadcrumb-item"><a href="{{ route('home', app()->getLocale()) }}">{{__('Dashboard') }}</a>
                                            </li>
                                            <li class="breadcrumb-item active">{{__('Status') }}
                                            </li>
                                            
                                        </ol>
                                    </div>
                                    <div class="col s2 m6 l6 ">
                                        <a class="btn  waves-effect waves-light breadcrumbs-btn right" href="{{ route('master.status', app()->getLocale()) }}">{{__('Status List') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12">
                            <div class="card">
                                <div class="card-content">
                                    <h4 class="card-title">{{ __('Edit Status')}}</h4>
                
                                   <div id="view-validations">
                                    <form class="formValidate" id="status_formValidate" method="post" action="{{ route('master.updatestatus', app()->getLocale()) }}">
                                       <?php $row = $data['status_edit'][0]; ?>
										@csrf
										<input type="hidden" name="id" value="{{$row->id}}">
                                      <div class="row">
                                        <div class="input-field col s12 m6">
                                          <label for="status_name" class="common-label">{{__('Status Name') }}*</label>
                                          <input id="status_name" name="status_name" class="common-input" type="text" value="{{ $row->status_name}}" data-error=".errorTxt1">
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
	$("#status_sidebar_li_id").addClass('active');
	$("#status_sidebar_a_id").addClass('active');
</script>
<script>
    $("#status_formValidate").validate({
        rules: {
            status_name: {
                required: true,
            },
        },
        //For custom messages
        messages: {
            status_name: {
                required: '{{__("Enter the Status Name") }}',
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