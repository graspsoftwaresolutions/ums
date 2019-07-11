@if (count($errors) > 0)
  @foreach ($errors->all() as $error)
	<div class="card-alert card gradient-45deg-red-pink">
		<div class="card-content white-text">
		  <p>
			<i class="material-icons">check</i> {{ __('Error') }} : {{ __($error) }}</p>
		</div>
		<button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
		  <span aria-hidden="true">×</span>
		</button>
	 </div>
  @endforeach
@endif

@if (session()->has('success'))
	<div class="card-alert card gradient-45deg-green-teal">
		<div class="card-content white-text">
		  <p>
			<i class="material-icons">check</i> {{__('SUCCESS') }}: {{__(session('success')) }}</p>
		</div>
		<button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
		  <span aria-hidden="true">×</span>
		</button>
	 </div>
@endif

@if (session()->has('message'))
	<div class="card-alert card gradient-45deg-green-teal">
		<div class="card-content white-text">
		  <p>
			<i class="material-icons">check</i> {{__('SUCCESS') }}: {{__(session('message')) }}</p>
		</div>
		<button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
		  <span aria-hidden="true">×</span>
		</button>
	 </div>
@endif

@if (session('status'))
	<div class="card-alert card gradient-45deg-green-teal" role="alert">
		<div class="card-content white-text">
		  <p>
			<i class="material-icons">check</i> {{__('SUCCESS') }}: {{ session('status') }}</p>
		</div>
		<button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
		  <span aria-hidden="true">×</span>
		</button>
		
	</div>
@endif

@if (session()->has('error'))
	<div class="card-alert card red">
		<div class="card-content white-text">
		  <p>
			<i class="material-icons">error</i> {{__('Error') }}: {{__(session('error')) }}</p>
		</div>
		<button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
		  <span aria-hidden="true">×</span>
		</button>
	 </div>
@endif

@if (session()->has('warning'))
	<div class="card-alert card gradient-45deg-amber-amber">
		<div class="card-content white-text">
		  <p>
			<i class="material-icons">warning</i> {{__('SUCCESS') }} : {{__(session('message')) }}</p>
		</div>
		<button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
		  <span aria-hidden="true">×</span>
		</button>
	 </div>
@endif
