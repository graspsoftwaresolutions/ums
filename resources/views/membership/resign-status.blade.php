@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
@endsection
@section('headSecondSection')
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/custom_respon.css') }}">
@endsection
@section('main-content')
<div class="row">
	<div class="col s12">
		<div class="container">
			<div class="section section-data-tables">
			<!-- BEGIN: Page Main-->
				<div class="row">
				
					<div class="col s12">
						<div class="card">
							<div class="card-content">
								<h4 class="card-title">{{__('Resign Info') }} 
									<a  href="{{ route('resign.pdf', [app()->getLocale(),Crypt::encrypt($member_data->id)])  }}" class="btn waves-effect waves-light right">Download PDF</a>
								</h4>
								<div class="card-alert-nonclose card  gradient-45deg-green-teal">
									<div class="card-content white-text">
									  <p>
										<i class="material-icons">check</i> {{__('SUCCESS') }}: {{__('Member resigned successfully') }}</p>
									</div>
									
								 </div>
								<div class="row">
									<div class="col s12">
										@include('membership.pdf_resign');
									</div>
								</div>
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

@endsection
@section('footerSection')

@endsection
@section('footerSecondSection')
<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/assets/js/scripts/form-validation.js')}}" type="text/javascript"></script>
<script>
$("#membership_sidebar_a_id").addClass('active');
</script>
@endsection