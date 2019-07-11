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
                                            <li class="breadcrumb-item active"><a href="{{route('appform.index',app()->getLocale())}}">{{__('App Form') }}</a>
                                            </li>
                                        </ol>
                                    </div>
                                    <div class="col s2 m6 l6 ">
                                        <a class="btn waves-effect waves-light breadcrumbs-btn right" href="{{ route('appform.index',app()->getLocale()) }}">{{__('Back') }}</a>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12">
                            <div class="card">
                                <div class="card-content">
                                    <h4 class="card-title ">{{ __('Edit App Form') }}</h4>
                                    @include('includes.messages')
                                   <div id="view-validations">
                                    <form class="formValidate" id="AppformValidate" method="post" action="{{ route('appform.update',[app()->getLocale(),$data['appform_edit']->id]) }}">
                                    <?php $row = $data['appform_edit'][0]; ?>
                                        @method('PATCH')
                                        @csrf
                                      <div class="row">
                                        <div class="input-field col s12 m6">
                                          <label for="formname" class="common-label">{{__('App Form Name') }}*</label>
                                          <input id="formname" class="common-input" value="<?php echo $data['appform_edit']->formname; ?>" name="formname" type="text"  data-error=".errorTxt1">
                                          <div class="errorTxt1"></div>
                                        </div>
                                        <div class="input-field col s12 m6">
                                          <label for="formtype" class="common-label">{{__('Form Type') }}</label>
                                          <input id="formtype" class="common-input" value="<?php echo $data['appform_edit']->formtype; ?>" name="formtype" type="text">
                                        </div>
                                        <div class="clearfix" style="clear:both"></div>
                                        <div class="input-field col s12 m6">
                                          <label for="orderno" class="common-label">{{__('Order No') }}</label>
                                          <input id="orderno" class="common-input" value="<?php echo $data['appform_edit']->orderno; ?>" name="orderno" type="text" >
                                        </div>
                                        <div class="input-field col s12 m6">
                                          <label for="route" class="common-label">{{__('Route') }}</label>
                                          <input id="route" class="common-input" value="<?php echo $data['appform_edit']->route; ?>" name="route" type="text" > 
                                        </div>
                                        <div class="clearfix" style="clear:both"></div>
                                        <div class="input-field col s12 m6">
                                        <p>
                                        <label>                                                                    
                                            <input type="checkbox" name="isactive" value="1" <?php  echo $data['appform_edit']->isactive == '1' ? 'checked' : '' ; ?>  class="common-checkbox" id="isactive"  />
                                            <span>{{__('Is Active') }}</span>
                                        </label>
                                        </p>
                                        </div>
                                        <div class="input-field col s12 m6">
                                        <p>
                                        <label>
                                            <input type="checkbox" name="isinsert" class="common-checkbox" id="isinsert" value="1"<?php  echo $data['appform_edit']->isinsert == '1' ? 'checked' : '' ; ?>  />
                                            <span>{{__('Is Insert') }}</span>
                                        </label>
                                        </p>
                                        </div>
                                        <div class="input-field col s12 m6">
                                        <p>
                                        <label>
                                            <input type="checkbox" name="isupdate" class="common-checkbox" id="isupdate" value="1" <?php  echo $data['appform_edit']->isupdate == '1' ? 'checked' : '' ; ?>  />
                                            <span>{{__('Is Update') }}</span>
                                        </label>
                                        </p>
                                        </div>
                                        <div class="input-field col s12 m6">
                                        <p>
                                        <label>
                                            <input type="checkbox" name="isdelete" class="common-checkbox" id="isdelete" value="1"<?php  echo $data['appform_edit']->isdelete == '1' ? 'checked' : '' ; ?> />
                                            <span>{{__('Is Delete') }}</span>
                                        </label>
                                        </p>
                                        </div>
                                        <div class="input-field col s12 m6">
                                        <p>
                                        <label>
                                            <input type="checkbox" name="ismenu" class="common-checkbox" id="ismenu" value="1"<?php  echo $data['appform_edit']->ismenu == '1' ? 'checked' : '' ; ?>  />
                                            <span>{{__('Is Menu') }}</span>
                                        </label>
                                        </p>
                                        </div>
                                        <div class="clearfix" style="clear:both"></div>
                                        <div class="input-field col s12 m6">
                                          <label for="description" class="common-label">{{__('Description') }}</label>
                                          <input id="route" value="<?php echo $data['appform_edit']->description; ?>" class="common-input" name="description" type="text" >
                                        </div>
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
	$("#appform_sidebar_li_id").addClass('active');
	$("#appform_sidebar_a_id").addClass('active');
</script>
<script>
    $("#AppformValidate").validate({
        rules: {
            formname:{
                required: true,
            },
        },
        //For custom messages
        messages: {
            
            formname: {
                required: '{{__("Please enter Form Name") }}',
                
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
</script>
@endsection