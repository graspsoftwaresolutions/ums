@if (count($errors) > 0)
  @foreach ($errors->all() as $error)
	<div class="card-alert card gradient-45deg-red-pink">
		<div class="card-content white-text">
		  <p>
			<i class="material-icons">check</i> Error : {{ $error }}</p>
		</div>
		<button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
		  <span aria-hidden="true">×</span>
		</button>
	 </div>
  @endforeach
@endif

@if (session()->has('message'))
	<div class="card-alert card gradient-45deg-green-teal">
		<div class="card-content white-text">
		  <p>
			<i class="material-icons">check</i> SUCCESS : {{ session('message') }}</p>
		</div>
		<button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
		  <span aria-hidden="true">×</span>
		</button>
	 </div>
@endif
