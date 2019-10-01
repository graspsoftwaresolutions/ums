@extends('layouts.admin')
@section('headSection')
@endsection
<style>
	#main.main-full {
		height: 750px;
		overflow: auto;
	}
	
	.footer {
	   position: fixed;
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
@section('main-content')
<div id="main">
	<div class="row">
		
			<div class="col s12">
				<div class="container">
					<div class="col s12 m2">
					&nbsp;
					</div>
					<br><br>
					<div class="col s12 m6 z-depth-4 border-radius-6 bg-opacity-8" style="margin-left: 86px;">
						<form method="POST" id="FormChangePasswordValidate" action="{{ route('changePassword', app()->getLocale()) }}">
							@csrf
							<div class="row">
								<div class="input-field col s12">
									<h5 class="ml-4">{{ __('Change Password') }} </h5>
									@include('includes.messages')
								</div>
							</div>
							<div class="row">
								<div class="input-field col s12 " style="padding:0px;margin:0px 30px;">
									NAME : {{ Auth::user()->name }}
								</div>
								<div class="input-field col s12 " style="padding:0px;margin:0px 30px;">
									EMAIL : {{ Auth::user()->email }}
								</div>
							</div>
							<div class="input-field col s12{{ $errors->has('current-password') ? ' has-error' : '' }}">
								
                                <label for="currentpassword" class="col-md-4 control-label">{{ __('Current Password') }}*</label>

								<input id="currentpassword" data-error=".errorTxt1" type="password" class="form-control" name="currentpassword" >
								<div class="errorTxt1"></div>
								@if ($errors->has('current-password'))
									<span class="help-block">
									<strong>{{ $errors->first('current-password') }}</strong>
								</span>
								@endif
                            </div>
							<div class="row margin">
								<div class="input-field col s12">
									
									<input id="password" data-error=".errorTxt2" name="password" type="password" class="@error('new-password') is-invalid @enderror"  >
									<label for="password">{{ __('New Password') }}*</label>
									<div class="errorTxt2"></div>
								</div>
							</div>
							<div class="row margin">
								<div class="input-field col s12">
									
									<input id="password_confirmation" data-error=".errorTxt3" type="password" class="form-control" name="password_confirmation"  autocomplete="password">
									<label for="password_confirmation" class="col-md-4 col-form-label text-md-right">{{__('Confirm Password') }}*</label>
									<div class="errorTxt3"></div>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s12">
									<button type="submit" class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12">
										{{ __('Update Password') }}
									</button>
								</div>
							</div>
						</form>
					</div>
			</div>
		</div>
	</div>
</div>
@endsection
		
@section('footerSection')

<script>
	$("#dashboard_sidebar_a_id").addClass('active');
</script>
<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/assets/js/scripts/form-validation.js')}}" type="text/javascript"></script>

<script>
    $("#FormChangePasswordValidate").validate({
        rules: {
            currentpassword:{
                required: true,
			},
			password:{
                required: true,
			},
			password_confirmation:{
                required: true,
            },
        },
        //For custom messages
        messages: {
            
			currentpassword: {
                required: '{{__("Please enter Current Password") }}', 
			},
			password: {
                required: '{{__("Please enter Password") }}', 
			},
			password_confirmation: {
                required: '{{__("Please enter Password Confirmation") }}', 
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
