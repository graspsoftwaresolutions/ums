@extends($data['user_type']==1 ? 'layouts.admin' : 'layouts.new-member')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/materialize-stepper/materialize-stepper.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/themes/vertical-modern-menu-template/materialize.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/themes/vertical-modern-menu-template/style.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/pages/form-wizard.css') }}">
<style type="text/css">
  	.autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; cursor:pointer; }
	.autocomplete-suggestion { padding: 8px 5px; white-space: nowrap; overflow: hidden; }
	.autocomplete-selected { background: #F0F0F0; }
	.autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
	.autocomplete-group { padding: 8px 5px; }
	.autocomplete-group strong { display: block; border-bottom: 1px solid #000; }
</style>
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
						@if($data['user_type']==1)
						<div class="breadcrumbs-dark pb-0 pt-4" id="breadcrumbs-wrapper">
							<!-- Search for small screen-->
							<div class="container">
								<div class="row">
									<div class="col s10 m6 l6">
										<h5 class="breadcrumbs-title mt-0 mb-0">{{__('New Membership') }}</h5>
										<ol class="breadcrumbs mb-0">
											<li class="breadcrumb-item"><a href="index.html">{{__('Dashboard') }}</a>
											</li>
											<li class="breadcrumb-item active"><a href="#">{{__('Member') }}</a>
											</li>
											
										</ol>
									</div>
								</div>
							</div>
						</div>
						@endif
						<div class="col s12">
							
									<h4 class="card-title">{{__('New Membership') }}</h4>
									@include('includes.messages')
									<div class="row">
										<div class="col s12">
											<div class="card">
												<div class="card-content pb-0">
													<div class="card-header">
														<h4 class="card-title">Horizontal Stepper</h4>
													</div>

													<ul class="stepper horizontal" id="horizStepper">
														<li class="step active">
															<div class="step-title waves-effect">Step 1</div>
															<div class="step-content">
																<div class="row">
																	<div class="input-field col m6 s12">
																		<label for="firstName">First Name: <span class="red-text">*</span></label>
																		<input type="text" id="firstName" name="firstName" class="validate"
																			aria-required="true" required="" required>
																	</div>
																	<div class="input-field col m6 s12">
																		<label for="lastName">Last Name: <span class="red-text">*</span></label>
																		<input type="text" id="lastName" class="validate" aria-required="true"
																			name="lastName" required="" required>
																	</div>
																</div>
																<div class="row">
																	<div class="input-field col m6 s12">
																		<label for="Email">Email: <span class="red-text">*</span></label>
																		<input type="email" class="validate" name="Email" id="Email" required=""
																			required>
																	</div>
																	<div class="input-field col m6 s12">
																		<label for="contactNum">Contact Number: <span class="red-text">*</span></label>
																		<input type="number" class="validate" name="contactNum" id="contactNum"
																			required="" required>
																	</div>
																</div>
																<div class="step-actions">
																	<div class="row">
																		<div class="col m4 s12 mb-3">
																			<button class="red btn btn-reset" type="reset">
																				<i class="material-icons left">clear</i>Reset
																			</button>
																		</div>
																		<div class="col m4 s12 mb-3">
																			<button class="btn btn-light previous-step" disabled>
																				<i class="material-icons left">arrow_back</i>
																				Prev
																			</button>
																		</div>
																		<div class="col m4 s12 mb-3">
																			<button class="waves-effect waves dark btn btn-primary next-step"
																				type="submit">
																				Next
																				<i class="material-icons right">arrow_forward</i>
																			</button>
																		</div>
																	</div>
																</div>
															</div>
														</li>
														<li class="step">
															<div class="step-title waves-effect">Step 2</div>
															<div class="step-content">
																<div class="row">
																	<div class="input-field col m6 s12">
																		<label for="proposal">Proposal Title: <span class="red-text">*</span></label>
																		<input type="text" class="validate" id="proposal" name="proposal" required=""
																			required>
																	</div>
																	<div class="input-field col m6 s12">
																		<label for="job">Job Title: <span class="red-text">*</span></label>
																		<input type="text" class="validate" id="job" name="job" required="" required>
																	</div>
																</div>
																<div class="row">
																	<div class="input-field col m6 s12">
																		<label for="company">Previous Company:</label>
																		<input type="text" class="validate" id="company" name="company">
																	</div>
																	<div class="input-field col m6 s12">
																		<label for="url">Video Url:</label>
																		<input type="url" class="validate" id="url">
																	</div>
																</div>
																<div class="row">
																	<div class="input-field col m6 s12">
																		<label for="exp">Experience: <span class="red-text">*</span></label>
																		<input type="text" class="validate" id="exp" name="exp">
																	</div>
																	<div class="input-field col m6 s12">
																		<label for="desc">Short Description: <span class="red-text">*</span></label>
																		<textarea name="dec" id="desc" rows="4" class="materialize-textarea"></textarea>
																	</div>
																</div>
																<div class="step-actions">
																	<div class="row">
																		<div class="col m4 s12 mb-3">
																			<button class="red btn btn-reset" type="reset">
																				<i class="material-icons left">clear</i>Reset
																			</button>
																		</div>
																		<div class="col m4 s12 mb-3">
																			<button class="btn btn-light previous-step">
																				<i class="material-icons left">arrow_back</i>
																				Prev
																			</button>
																		</div>
																		<div class="col m4 s12 mb-3">
																			<button class="waves-effect waves dark btn btn-primary next-step"
																				type="submit">
																				Next
																				<i class="material-icons right">arrow_forward</i>
																			</button>
																		</div>
																	</div>
																</div>
															</div>
														</li>
														<li class="step">
															<div class="step-title waves-effect">Step 3</div>
															<div class="step-content">
																<div class="row">
																	<div class="input-field col m6 s12">
																		<label for="eventName">Event Name: <span class="red-text">*</span></label>
																		<input type="text" class="validate" id="eventName" name="eventName" required=""
																			required>
																	</div>
																	<div class="input-field col m6 s12">
																		<select>
																			<option value="Select" disabled selected>Select Event Type</option>
																			<option value="Wedding">Wedding</option>
																			<option value="Party">Party</option>
																			<option value="FundRaiser">Fund Raiser</option>
																		</select>
																	</div>
																</div>
																<div class="row">
																	<div class="input-field col m6 s12">
																		<select>
																			<option value="Select" disabled selected>Select Event Status</option>
																			<option value="Planning">Planning</option>
																			<option value="In Progress">In Progress</option>
																			<option value="Completed">Completed</option>
																		</select>
																	</div>
																	<div class="input-field col m6 s12">
																		<select>
																			<option value="Select" disabled selected>Event Location</option>
																			<option value="New York">New York</option>
																			<option value="Queens">Queens</option>
																			<option value="Washington">Washington</option>
																		</select>
																	</div>
																</div>
																<div class="row">
																	<div class="input-field col m6 s12">
																		<label for="Budget">Event Budget: <span class="red-text">*</span></label>
																		<input type="Number" class="validate" id="Budget" name="Budget">
																	</div>
																	<div class="input-field col m6 s12">
																		<p> <label>Requirments</label></p>
																		<p> <label>
																				<input type="checkbox">
																				<span>Staffing</span>
																			</label></p>
																		<p><label>
																				<input type="checkbox">
																				<span>Catering</span>
																			</label></p>
																	</div>
																</div>
																<div class="step-actions">
																	<div class="row">
																		<div class="col m6 s12 mb-1">
																			<button class="red btn mr-1 btn-reset" type="reset">
																				<i class="material-icons">clear</i>
																				Reset
																			</button>
																		</div>
																		<div class="col m6 s12 mb-1">
																			<button class="waves-effect waves-dark btn btn-primary"
																				type="submit">Submit</button>
																		</div>
																	</div>
																</div>
															</div>
														</li>
													</ul>
												</div>
											</div>
										</div>
									</div>
                                       
							
						</div>
					</div>
                    <div class="row hide">
					<div class="input-field col s6">
					<input id="country_test" type="text" name="country_test" class="validate">
					<label for="country_test">Search Country here</label>
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
<script src="{{ asset('public/assets/vendors/materialize-stepper/materialize-stepper.min.js')}}"></script>
<!--script src = "{{ asset('public/assets/js/materialize.min.js') }}" type="text/javascript"></script-->



@endsection
@section('footerSecondSection')
<script src="{{ asset('public/assets/js/scripts/form-wizard.js') }}" type="text/javascript"></script>

@endsection