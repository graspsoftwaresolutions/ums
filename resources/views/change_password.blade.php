@extends('layouts.admin')
@section('headSection')
@endsection

@section('main-content')
<div id="main">
	<div class="row">
		<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
			<div class="col s12">
				<div class="container">
					<div class="col s12 m2">
					&nbsp;
					</div>
					<div class="col s12 m6 z-depth-4 border-radius-6 bg-opacity-8">
						<form method="POST" action="{{ route('changePassword', app()->getLocale()) }}">
							@csrf
							<div class="row">
								<div class="input-field col s12">
									<h5 class="ml-4">{{ __('Change Password') }}</h5>
									@include('includes.messages')
								</div>
							</div>
							<div class="input-field col s12{{ $errors->has('current-password') ? ' has-error' : '' }}">
								<i class="material-icons prefix pt-2">lock_outline</i>
                                <label for="current-password" class="col-md-4 control-label">Current Password</label>

								<input id="current-password" type="password" class="form-control" name="current-password" required>

								@if ($errors->has('current-password'))
									<span class="help-block">
									<strong>{{ $errors->first('current-password') }}</strong>
								</span>
								@endif
                            </div>
							<div class="row margin">
								<div class="input-field col s12">
									<i class="material-icons prefix pt-2">lock_outline</i>
									<input id="password" name="password" type="password" class="@error('new-password') is-invalid @enderror" required >
									<label for="password">{{ __('New Password') }}</label>
								</div>
							</div>
							<div class="row margin">
								<div class="input-field col s12">
									<i class="material-icons prefix pt-2">lock_outline</i>
									<input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required autocomplete="password">
									<label for="password_confirmation" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>
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
@endsection
		
@section('footerSection')

<script>
	$("#dashboard_sidebar_a_id").addClass('active');
</script>
@endsection
