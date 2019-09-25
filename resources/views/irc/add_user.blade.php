@extends('layouts.admin')
@section('headSection')
	<style type="text/css">
		.autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; cursor:pointer; }
		.autocomplete-suggestion { padding: 8px 5px; white-space: nowrap; overflow: hidden; }
		.autocomplete-selected { background: #F0F0F0; }
		.autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
		.autocomplete-group { padding: 8px 5px; }
		.autocomplete-group strong { display: block; border-bottom: 1px solid #000; }
		#transfer_member{
			color:#fff;
		}
	</style>
@endsection
@section('headSecondSection')
@endsection
@section('main-content')
@php
	$userid = Auth::user()->id;
	$get_roles = Auth::user()->roles;
	$user_role = $get_roles[0]->slug;
@endphp
<div id="">
	<div class="row">
		<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
		<div class="col s12">
			<div class="container">
				<div class="loading-overlay"></div>
				<div class="loading-overlay-image-container">
					<img src="{{ asset('public/images/loading.gif') }}" class="loading-overlay-img"/>
				</div>
				<div class="section section-data-tables">
					<!-- BEGIN: Page Main-->
					<div class="row">
						 <div class="breadcrumbs-dark" id="breadcrumbs-wrapper">
                            <!-- Search for small screen-->
                            <div class="container">
                                <div class="row">
                                    <div class="col s10 m6 l6">
                                        <h5 class="breadcrumbs-title mt-0 mb-0">{{__('Add IRC Account') }}</h5>
                                        <ol class="breadcrumbs mb-0">
                                            <li class="breadcrumb-item"><a
                                                    href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard') }}</a>
                                            </li>
                                            <li class="breadcrumb-item active">{{__('Add IRC Account') }}
                                            </li>
                                        </ol>
                                    </div>
                                    <div class="col s2 m6 l6 ">
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="col s12">
							<!--h4 class="card-title">{{__('New Membership') }}</h4-->
							@include('includes.messages')
                            
						</div>
					</div>
					<div class="row">
						<form class="formValidate" id="UsersformValidate" method="post" action="{{ route('irc.add-irc-account', app()->getLocale()) }}">
						@csrf
						<div class="col s12">
							
							<div class="row">
								<div class="col s12">
									<div class="card">
										<div class="card-content">
											<div class="input-field col s12 m6">
												<label for="name" class="common-label force-active">{{__('Name') }}*</label>
												<input id="name" class="common-input" name="name" type="text"
													data-error=".errorTxt1" value="">
												<div class="errorTxt1"></div>
											</div>
											<div class="input-field col s12 m6">
												<label for="email"
													class="common-label force-active">{{__('email') }}*</label>
													<input id="email" data-autoid="" class="common-input" name="email"
													type="email" data-error=".errorTxt2">
												<div class="errorTxt2"></div>
											</div>
											<div class="input-field col s12 m6 edit_hide">
												<label for="password" class="common-label force-active">{{__('Password') }}*</label>
												<input id="password" class="common-input" name="password" type="password"
													data-error=".errorTxt3">
												<div class="errorTxt3"></div>
											</div>
											<div class="input-field col s12 m6 edit_hide">
												<label for="password"
													class="common-label force-active">{{__('Confirm Password') }}*</label>
												<input id="confirm_password" class="common-input" name="confirm_password"
													type="password" data-error=".errorTxt4">
												<div class="errorTxt4"></div>
											</div>
											
											<div class="input-field col s12 m6">
												<select class="error browser-default selectpicker" id="account_type" name="account_type" data-error=".errorTxt5">
													<option value="">{{__('Select Type') }}</option>
													<option value="irc-confirmation">IRC Confirmation</option>
													<option value="irc-branch-committee">IRC Branch Committee</option>
												</select>
												<label for="account_type" class="active">Account Type</label>
												<div class="input-field">
													<div class="errorTxt5"></div>
												</div>
											</div>
											<div class="input-field col s12 m6 hide" id="memberarea">
												<label for="member_search" class="force-active">{{__('Member Name')}}</label>
												<input id="member_search" type="text" autocomplete="off" class="validate " data-error=".errorTxt6" value="" name="member_search">
												<input id="member_code" type="text" autocomplete="off" class="validate hide" name="member_code" data-error=".errorTxt6" value="" readonly >
												<div class="errorTxt6"></div>
											</div>
											<div class="input-field col s12 m6 hide" id="brancharea">
												<label for="member_search" class="force-active">{{__('Union Branch Name')}}</label>
												<select class="error browser-default common-select selectpicker"
													id="union_branch_id" name="union_branch_id"
													data-error=".errorTxt7" style="height: 4rem;">
													<option value="" disabled="" selected="">
														{{__('Select Union Branch') }}</option>
													@foreach($data['union_view'] as $value)
													<option value="{{$value->id}}" @isset($row) @php if($value->id
														== $row->union_branch_id) { echo "selected";} @endphp
														@endisset >{{$value->union_branch}}</option>
													@endforeach
												</select>
												 <div class="input-field">
													<div class="errorTxt7"></div>
												</div>
											</div>
											<div class="clearfix" style="clear:both"></div>
											<div class="input-field col s12">
												<button class="btn waves-effect waves-light right submit" type="submit" name="action">{{__('Save')}}
												</button>
											</div>
										</div>
									</div>
								</div>
							</div>
							
						</div>
						</form>
						
					</div>
					<!-- END: Page Main-->
					@include('layouts.right-sidebar')
				</div>
			</div>
		</div>
	</div>
</div>
@php	
	$ajaxcompanyid = '';
	$ajaxbranchid = '';
	$ajaxunionbranchid = '';
	if(!empty(Auth::user())){
		$userid = Auth::user()->id;
		
		if($user_role =='union'){

		}else if($user_role =='union-branch'){
			$ajaxunionbranchid = CommonHelper::getUnionBranchID($userid);
		}else if($user_role =='company'){
			$ajaxcompanyid = CommonHelper::getCompanyID($userid);
		}else if($user_role =='company-branch'){
			$ajaxbranchid = CommonHelper::getCompanyBranchID($userid);
		}else{

		}
	}
@endphp
@endsection
@section('footerSection')
<script src="{{ asset('public/assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/assets/vendors/noUiSlider/nouislider.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/materialize.min.js') }}"></script>
<script src="{{ asset('public/assets/js/scripts/form-elements.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jquery.autocomplete.min.js') }}" type="text/javascript"></script>

@endsection
@section('footerSecondSection')
<script>
	//$("#member_filter").trigger('click');

	$("#irc_account_sidebar_a_id").addClass('active');
    $("#member_search").devbridgeAutocomplete({
        //lookup: countries,
        serviceUrl: "{{ URL::to('/get-ircauto-member-list') }}?serachkey="+ $("#member_search").val(),
        type:'GET',
        //callback just to show it's working
        onSelect: function (suggestion) {
			 $("#member_search").val(suggestion.value);
			 $("#member_code").val(suggestion.number);
        },
        showNoSuggestionNotice: true,
        noSuggestionNotice: 'Sorry, no matching results',
		onSearchComplete: function (query, suggestions) {
			if(!suggestions.length){
				$("#member_code").val('');
			}
		}
    });
	$(document.body).on('click', '.autocomplete-no-suggestion' ,function(){
		$("#member_search").val('');
	});
	$("#UsersformValidate").validate({
		rules: {
			name: {
				required: true,
			},
			email: {
				required: true,
				remote: {
					url: "{{ url(app()->getLocale().'/users_emailexists')}}",
					data: {
						login_userid: function() {
							return $("#updateid").val();
						},
						_token: "{{csrf_token()}}",
						email: $(this).data('email')
					},
					type: "post",
				},
			},
			password: {
				required: true,
			},
			confirm_password: {
				required: true,
				equalTo: "#password",
			},
			account_type: {
				required: true,
			},
			/* member_code: {
				required: true,
			},
			member_search: {
				required: true,
			}, */
		},
		//For custom messages
		messages: {
			name: {
				required: '{{__("Please enter Name") }}',
			},
			email: {
				remote: '{{__("Email Already exists") }}',
			},
			password: {
				required: '{{__("Please enter Password") }}',
			},
			confirm_password: {
				required: '{{__("Please enter Confirm Password") }}',
			},
			account_type: {
				required: '{{__("Please select account type") }}',
			},
			/* member_code: {
				required: '{{__("Please Pick a name") }}',
			}, */

		},
		errorElement: 'div',
		errorPlacement: function(error, element) {
			var placement = $(element).data('error');
			if (placement) {
				$(placement).append(error)
			} else {
				error.insertAfter(element);
			}
		}
	});
	$(document.body).on('change', '#account_type' ,function(){
		var acttype = this.value;
		if(acttype=='irc-confirmation'){
			$("#memberarea").removeClass('hide');
			$("#brancharea").addClass('hide');
		}else if(acttype=='irc-branch-committee'){
			$("#memberarea").addClass('hide');
			$("#brancharea").removeClass('hide');
		}else{
			$("#memberarea").addClass('hide');
			$("#brancharea").addClass('hide');
		}
	});
</script>
@endsection