@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col s12">
		<div class="container">
			<div id="login-page" class="row">
				<div class="col s12 m6 l4 z-depth-4 card-panel border-radius-6 login-card bg-opacity-8">
					<form method="POST" id="LoginformValidate" action="{{ route('login', app()->getLocale()) }}">
                        @csrf
						<div class="row">
							<div class="input-field col s12">
								<h5 class="ml-4">{{ __('Login') }}
									<select name="language" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" id="language" class="browser-default" style="float: right;color: #333;font:size:16px;">
										<option {{ app()->getLocale()=='en' ? 'selected' : '' }} value="{{ url('en') }}" >English</option>
										<option {{ app()->getLocale()=='my' ? 'selected' : '' }} value="{{ url('my') }}">Malay</option>
									</select>
								</h5>
								<div class="clearfix"/>
								@include('includes.messages')
							</div>
						</div>
						<div class="row margin">
							<div class="input-field col s12">
								<i class="material-icons prefix pt-2">person_outline</i>
								<input id="email" name="email" type="email" class="@error('email') is-invalid @enderror" value="{{ old('email') }}" required >
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
								<input id="password" name="password" type="password" class="@error('password') is-invalid @enderror" required onCopy="return false" onDrag="return false" onDrop="return false" onPaste="return false">
								<label for="password">{{ __('Password') }}</label>
							</div>
						</div>
						<div class="row hide">
							<div class="col s12 m12 l12 ml-2 mt-1">
								<p>
									<label>
									<input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} />
									<span> {{ __('Remember Me') }}</span>
									</label>
								</p>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s12">
								<button type="submit" class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12">
                                    {{ __('Login') }}
                                </button>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s6 m6 l6">
								<p class="margin medium-small">
									 <a href="{{ route('register', app()->getLocale()) }}">{{ __('Register Now!') }}</a>
								</p>
							</div>
							<div class="input-field col s6 m6 l6">
								<p class="margin right-align medium-small">
									<a href="{{ route('password.request', app()->getLocale()) }}">
										{{ __('Forgot Your Password?') }}
									</a>
								</p>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
 $('#password').bind("cut copy paste",function(e) {
          e.preventDefault();
      });
</script>

@endsection
