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
                                        <h5 class="breadcrumbs-title mt-0 mb-0">{{__('App Form List') }}</h5>
                                        <ol class="breadcrumbs mb-0">
                                            <li class="breadcrumb-item"><a href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard') }}</a>
                                            </li>
                                            <li class="breadcrumb-item active"><a href="{{route('master.appform',app()->getLocale())}}">{{__('App Form') }}</a>
                                            </li>
                                        </ol>
                                    </div>
                                    <div class="col s2 m6 l6 ">
                                        <a class="btn waves-effect waves-light breadcrumbs-btn right" href="{{ route('master.appform',app()->getLocale()) }}">{{__('Back') }}</a>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12">
                            <div class="card">
                                <div class="card-content">
									
                                    @include('includes.messages')
									@if(isset($data['appform_edit']))
                                    <h4 class="card-title">{{__('Edit App Form') }}</h4>
                                    @else
                                    <h4 class="card-title">{{__('Add App Form') }}</h4>
                                    @endif
									@php
                                    if(isset($data['appform_edit'])){
                                        $values = $data['appform_edit'];
                                    }
                                    @endphp
                                   <div id="view-validations">
                                    <form class="formValidate" id="AppformValidate" method="post" action="{{ route('master.saveappform',app()->getLocale()) }}">
                                        @csrf
										
										<input type="hidden" name="id" id="auto_id" value="@isset($values){{$values->id}}@endisset">
                                      <div class="row">
                                        <div class="input-field col s12 m6">
                                          <label for="formname" class="common-label force-active">{{__('App Form Name') }}*</label>
                                          <input id="formname" class="common-input" name="formname" type="text" value="@isset($values){{ $values->formname }}@endisset" data-error=".errorTxt1">
                                          <div class="errorTxt1"></div>
                                        </div>
                                        <div class="col s12 m6">
                                          <label for="formtype_id" class="common-label force-active">{{__('Form Type') }}</label>
										   <select class="error browser-default" class="common-select" id="formtype_id" name="formtype_id" data-error=".errorTxt1">
												<option selected="" value="">{{__('Select Form Type')}}</option>
												@foreach($data['form_type'] as $value)
													<option value="{{$value->id}}" @isset($values)  @php if($value->id == $values->formtype_id) { echo "selected";} @endphp  @endisset>{{$value->formname}}</option>
												@endforeach
											</select>
											
                                        </div>
                                        <div class="clearfix" style="clear:both"></div>
                                        <div class="input-field col s12 m6">
                                          <label for="orderno" class="common-label force-active">{{__('Order No') }}</label>
                                          <input id="orderno" class="common-input" name="orderno" type="text" value="@isset($values){{ $values->orderno }}@endisset" >
                                        </div>
                                        <div class="input-field col s12 m6">
                                          <label for="route" class="common-label force-active">{{__('Route') }}</label>
                                          <input id="route" class="common-input" name="route" type="text" value="@isset($values){{ $values->route }}@endisset"> 
                                        </div>
                                        <div class="clearfix" style="clear:both"></div>
                                        <div class="input-field col s12 m6">
                                        <p>
                                        <label>
                                            <input type="checkbox" name="isactive" class="common-checkbox" id="isactive" value="1" @isset($values) {{ $values->isactive == '1' ? 'checked' : '' }} @endisset />
                                            <span class="common-label force-active">{{__('Is Active') }}</span>
                                        </label>
                                        </p>
                                        </div>
                                        <div class="input-field col s12 m6">
                                        <p>
                                        <label>
                                            <input type="checkbox" name="isinsert" class="common-checkbox" id="isinsert" value="1"  @isset($values) {{ $values->isinsert == '1' ? 'checked' : '' }} @endisset />
                                            <span class="common-label force-active">{{__('Is Insert') }}</span>
                                        </label>
                                        </p>
                                        </div>
                                        <div class="input-field col s12 m6">
                                        <p>
                                        <label>
                                            <input type="checkbox" name="isupdate" class="common-checkbox" id="isupdate" value="1" @isset($values) {{ $values->isupdate == '1' ? 'checked' : '' }} @endisset />
                                            <span class="common-label force-active">{{__('Is Update') }}</span>
                                        </label>
                                        </p>
                                        </div>
                                        <div class="input-field col s12 m6">
                                        <p>
                                        <label>
                                            <input type="checkbox" name="isdelete" class="common-checkbox" id="isdelete" value="1" @isset($values) {{ $values->isdelete == '1' ? 'checked' : '' }} @endisset />
                                            <span class="common-label force-active">{{__('Is Delete') }}</span>
                                        </label>
                                        </p>
                                        </div>
                                        <div class="input-field col s12 m6">
                                        <p>
                                        <label>
                                            <input type="checkbox" name="ismenu" class="common-checkbox" id="ismenu" value="1"  @isset($values) {{ $values->ismenu == '1' ? 'checked' : '' }} @endisset />
                                            <span class="common-label force-active">{{__('Is Menu') }}</span>
                                        </label>
                                        </p>
                                        </div>
                                        <div class="clearfix" style="clear:both"></div>
                                        <div class="input-field col s12 m6">
                                          <label for="description" class="common-label force-active">{{__('Description') }}</label>
                                          <input id="description" class="common-input" name="description" type="text" value="@isset($values){{ $values->description }}@endisset">
                                          
                                        </div>
                                        <div class="input-field col s12">
                                          <button class="btn waves-effect waves-light right submit" type="submit" name="action">
										  @if(isset($values))
											{{__('Update') }}
											@else{{__('Save') }}
                                            @endif
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
	$("#appform_sidebar_li_id").addClass('active');
	$("#appform_sidebar_a_id").addClass('active');
</script>
<script>
    $("#AppformValidate").validate({
        rules: {
            formname:{
                required: true,
				remote:{
                   url: "{{ url(app()->getLocale().'/appformexists')}}", 
                   data: {
                         formname_id: function() {
                            return $( "#auto_id" ).val();
                        },
                        _token: "{{csrf_token()}}",
                        formname: $(this).data('formname')
                        },
                   type: "post",
                },
            },
        },
        //For custom messages
        messages: {
            
            formname: {
                required: '{{__("Please enter Form Name") }}',
                remote: '{{__("Form Name Already Exists") }}',
                
            }
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
});
</script>
@endsection