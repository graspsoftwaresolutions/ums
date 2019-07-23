@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col s12">
		<div class="container">
			<div id="login-page" class="row">
				<div class="col s12 m6 l4 z-depth-4 card-panel border-radius-6 login-card bg-opacity-8">
					<form method="POST" id="LoginformValidate" action="{{ route('password.update', app()->getLocale()) }}">
                        @csrf
						<div class="row">
							<div class="input-field col s12">
								<h5 class="ml-4">{{ __('Reset Password') }}</h5>
								@include('includes.messages')
							</div>
						</div>
						<div class="row margin">
							<div class="input-field col s12">
								 <input type="hidden" name="token" value="{{ $token }}">
								<i class="material-icons prefix pt-2">person_outline</i>
								<input id="email" name="email" type="email" class="@error('email') is-invalid @enderror" value="{{ $email }}" readonly required >
								<label for="email" class="center-align">{{ __('E-Mail Address') }}</label>
								@error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
							</div>
						</div>
						<div class="row margin">
							<div class="input-field col s12">
								<i class="material-icons prefix pt-2">lock_outline</i>
								<input id="password" name="password" type="password" class="@error('password') is-invalid @enderror" required >
								<label for="password">{{ __('Password') }}</label>
							</div>
						</div>
						<div class="row margin">
							<div class="input-field col s12">
								<i class="material-icons prefix pt-2">lock_outline</i>
								<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
								<label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>
							</div>
                        </div>
						<div class="row">
							<div class="input-field col s12">
								<button type="submit" class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12">
                                    {{ __('Reset Password') }}
                                </button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<!--div class="container hide">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    
                </div>
            </div>
        </div>
    </div>
</div-->
@endsection
