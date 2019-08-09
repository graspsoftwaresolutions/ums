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
						
						<div class="col s12">
							<!--h4 class="card-title">{{__('New Membership') }}</h4-->
							@include('includes.messages')
                            
						</div>
					</div>
					<div class="row">
						<form class="formValidate" id="transferformValidate" method="post" action="{{ route('transfer.updatebranch', app()->getLocale()) }}">
						@csrf
						<div class="card filter">
							<div class="">     
								@php
									$url_member_id = '';
									$url_branch_id = '';
									$url_member_name = '';
									
									if(isset($data['member_id'])){
										
										$url_member_id = $data['member_id'];
										if(isset($data['old_branch_id'])){
											$url_branch_id = $data['old_branch_id'];
										}
										if(isset($data['member_data'])){
										$url_member_name = $data['member_data']->name; }
									}
									
								@endphp
								<div class="col s4">
									<label for="member_search">{{__('member Name')}}</label>
									<input id="member_search" type="text" required readonly class="validate " value="{{ $url_member_name }}" name="member_search">
									
								</div>
								<div class=" col s4">
									<label for="member_code">{{__('Member Code')}}</label>
									<input id="transfer_member_code" type="text" required class="validate " name="transfer_member_code" value="{{ $url_member_id }}" readonly >
									<input id="transfer_member_branch_id" type="text" required class="validate hide" value="{{ $url_branch_id }}" name="transfer_member_branch_id" readonly >
									
								</div>
								<div class=" col s4">
									<label for="transfer_date">{{__('Transfer Date')}}</label>
									<input id="transfer_date" type="text" class=" datepicker" value="{{ date('d/M/Y', strtotime($data['historydata']->transfer_date)) }}" name="transfer_date"  >
									<input id="history_id" type="text" class=" hide" value="{{ $data['historydata']->id }}" name="history_id"  >
									
								</div>
								
								
							</div>
						</div>
						
						<div class="col s12">
							
							
							
							<div class="row">
								<div class="col s5">
									<div class="card-body">
										<div id="basic-form" class="card card card-default scrollspy">
											<div class="card-content">
												<h4 class="card-title">From Bank Branch Details</h4>
												<div id="old_companyinfo" class="row">
													<div class="row">
														<div class="col s6">
															<div class="input-field">
																<input placeholder="" id="old_company" name="old_company" type="text" value="@isset($data['current_branch_data']){{$data['current_branch_data']->company_name}}@endisset" class="validate" readonly >
																<label for="old_company" class="active">Old Bank</label>
															</div>
														</div>
														<div class="col s6">
															<div class="input-field">
																<input placeholder="" id="old_branch" name="old_branch" type="text" value="@isset($data['current_branch_data']){{$data['current_branch_data']->branch_name}}@endisset" readonly class="validate">
																<label for="old_branch" class="active">Old Branch</label>
															</div>
														</div>
													</div>
													
													<div class="row">
														<div class="col s6">
															<div class="input-field">
																<input placeholder="" id="old_country" name="old_country" type="text" value="@isset($data['current_branch_data']){{$data['current_branch_data']->country_name}}@endisset" readonly class="validate">
																<label for="old_country" class="active">{{__('Country') }}</label>
															</div>
														</div>
														<div class="col s6">
															<div class="input-field">
																<input placeholder="" id="old_state" name="old_state" type="text" value="@isset($data['current_branch_data']){{$data['current_branch_data']->state_name}}@endisset" readonly class="validate">
																<label for="old_state" class="active">{{__('State') }}</label>
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col s6">
															<div class="input-field">
																<input placeholder="" id="old_city" name="old_city" type="text" value="@isset($data['current_branch_data']){{$data['current_branch_data']->city_name}}@endisset" readonly class="validate">
																<label for="old_city" class="active">{{__('City') }}</label>
															</div>
														</div>
														<div class="col s6">
															<div class="input-field">
																<input placeholder="" id="old_postal" name="old_postal" type="text" value="@isset($data['current_branch_data']){{$data['current_branch_data']->postal_code}}@endisset" readonly class="validate">
																<label for="old_postal" class="active">{{__('Postal code') }}</label>
															</div>
															
														</div>
													</div>
													<div class="row">
														<div class="col s6">
															<div class="input-field">
																<input placeholder="" id="old_address_one" name="old_address_one" type="text" value="@isset($data['current_branch_data']){{$data['current_branch_data']->address_one}}@endisset" readonly class="validate">
																<label for="old_address_one" class="active">{{__('Address line 1') }}</label>
															</div>
														</div>
														<div class="col s6">
															<div class="input-field">
																<input placeholder="" id="old_address_two" name="old_address_two" type="text" value="@isset($data['current_branch_data']){{$data['current_branch_data']->address_two}}@endisset" readonly class="validate">
																<label for="old_address_two" class="active">{{__('Address line 2') }}</label>
															</div>
															
														</div>
													</div>
													<div class="row">
														<div class="col s6">
															<div class="input-field">
																<input placeholder="" id="old_address_three" name="old_address_three" type="text" value="@isset($data['current_branch_data']){{$data['current_branch_data']->address_three}}@endisset" readonly class="validate">
																<label for="old_address_three" class="active">{{__('Address line 3') }}</label>
															</div>
														</div>
														<div class="col s6">
															<div class="input-field">
																<input placeholder="" id="old_phone" name="old_phone" type="text" readonly value="@isset($data['current_branch_data']){{$data['current_branch_data']->phone}}@endisset" class="validate">
																<label for="old_phone" class="active">{{__('Phone number') }}</label>
															</div>
															
														</div>
													</div>
													<div class="row">
														<div class="col s6">
															<div class="input-field">
																<input placeholder="" id="old_mobile" name="old_mobile" type="text" value="@isset($data['current_branch_data']){{$data['current_branch_data']->mobile}}@endisset" readonly class="validate">
																<label for="old_mobile" class="active">{{__('Mobile number') }}</label>
															</div>
														</div>
														<div class="col s6">
															<div class="input-field">
																<input placeholder="" id="old_email" name="old_email" type="text" value="@isset($data['current_branch_data']){{$data['current_branch_data']->email}}@endisset" readonly class="validate">
																<label for="old_email" class="active">{{__('Email') }}</label>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col s5">
									<div class="card-body">
										<div id="basic-form" class="card card card-default scrollspy">
											<div class="card-content">
												<h4 class="card-title">To Bank Branch Details</h4>
												<div id="newbranchinfo" class="row">
													<div class="row">
														<div class="col s6">
															<div class="input-field">
																<select class="error browser-default selectpicker" id="new_company" name="new_company" data-error=".errorTxt1">
																	 <option value="">{{__('Select company') }}</option>
																	@foreach($data['company_view'] as $values)
																		<option @if($data['to_company_id']==$values->id) selected @endif value="{{$values->id}}">{{$values->company_name}}</option>
																	@endforeach
																</select>
																<label for="new_company" class="active">New Bank</label>
																<div class="input-field">
																	<div class="errorTxt1"></div>
																</div>
															</div>
														</div>
														<div class="col s6">
															<div class="input-field">
																<select class="error browser-default selectpicker" id="new_branch" name="new_branch" data-error=".errorTxt2">
																	<option value="">{{__('Select Branch') }}</option>
																	@foreach($data['to_branch_view'] as $values)
																		<option @if($data['to_branch_id']==$values->id) selected @endif value="{{$values->id}}">{{$values->branch_name}}</option>
																	@endforeach
																</select>
																<label for="new_company" class="active">New Branch</label>
																<div class="input-field">
																	<div class="errorTxt2"></div>
																</div>
															</div>
														</div>
													</div>
													
													<div class="row">
														<div class="col s6">
															<div class="input-field">
																<input placeholder="" id="new_country" name="new_country" type="text" value="{{$data['to_branch_data']->country_name}}" readonly class="validate">
																<label for="new_country" class="active">{{__('Country') }}</label>
															</div>
														</div>
														<div class="col s6">
															<div class="input-field">
																<input placeholder="" id="new_state" name="new_state" value="{{$data['to_branch_data']->state_name}}" type="text" readonly class="validate">
																<label for="new_state" class="active">{{__('State') }}</label>
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col s6">
															<div class="input-field">
																<input placeholder="" id="new_city" name="new_city" value="{{$data['to_branch_data']->city_name}}" type="text" readonly class="validate">
																<label for="new_city" class="active">{{__('City') }}</label>
															</div>
														</div>
														<div class="col s6">
															<div class="input-field">
																<input placeholder="" id="new_postal" name="new_postal" value="{{$data['to_branch_data']->postal_code}}" type="text" readonly class="validate">
																<label for="new_postal" class="active">{{__('Postal code') }}</label>
															</div>
															
														</div>
													</div>
													<div class="row">
														<div class="col s6">
															<div class="input-field">
																<input placeholder="" id="new_address_one" name="new_address_one" value="{{$data['to_branch_data']->address_one}}" type="text" readonly class="validate">
																<label for="new_address_one" class="active">{{__('Address line 1') }}</label>
															</div>
														</div>
														<div class="col s6">
															<div class="input-field">
																<input placeholder="" id="new_address_two" name="new_address_two" value="{{$data['to_branch_data']->address_two}}" type="text" readonly class="validate">
																<label for="new_address_two" class="active">{{__('Address line 2') }}</label>
															</div>
															
														</div>
													</div>
													<div class="row">
														<div class="col s6">
															<div class="input-field">
																<input placeholder="" id="new_address_three" name="new_address_three" value="{{$data['to_branch_data']->address_three}}" type="text" readonly class="validate">
																<label for="new_address_three" class="active">{{__('Address line 3') }}</label>
															</div>
														</div>
														<div class="col s6">
															<div class="input-field">
																<input placeholder="" id="new_phone" name="new_phone" value="{{$data['to_branch_data']->phone}}" type="text" readonly class="validate">
																<label for="new_phone" class="active">{{__('Phone number') }}</label>
															</div>
															
														</div>
													</div>
													<div class="row" style="padding-bottom:10px;">
														<div class="col s6">
															<div class="input-field">
																<input placeholder="" id="new_mobile" name="new_mobile" type="text" value="{{$data['to_branch_data']->mobile}}" readonly class="validate">
																<label for="new_mobile" class="active">{{__('Mobile number') }}</label>
															</div>
														</div>
														<div class="col s6">
															<div class="input-field">
																<input placeholder="" id="new_email" name="new_email" type="text" value="{{$data['to_branch_data']->email}}" readonly class="validate">
																<label for="new_email" class="active">{{__('Email') }}</label>
															</div>
														</div>
														
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col s2">
									<div style="margin-bottom:150px;">&nbsp;</br></div>
									<input type="submit" style="color: #fff !important;" class="btn waves-effect waves-light green darken-1"  name="transfer_member" id="transfer_member" value="{{__('Transfer')}}">
									</br>
									</br>
									<a href="#" class="btn waves-effect waves-light danger darken-1 hide" onclick="return ConfirmDeletion()" name="transfer_member" id="transfer_member" value="">{{__('Delete')}}</a>
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
	function ConfirmDeletion() {
		if (confirm("{{ __('Are you sure you want to delete?') }}")) {
		  return true;
		} else {
		  return false;
		}
	}
	//$("#member_filter").trigger('click');
	$("#member_transfer_sidebar_a_id").addClass('active');
    /* $("#member_search").devbridgeAutocomplete({
        //lookup: countries,
        serviceUrl: "{{ URL::to('/get-auto-member-list') }}?serachkey="+ $("#member_search").val(),
        type:'GET',
        //callback just to show it's working
        onSelect: function (suggestion) {
			 $("#member_search").val(suggestion.value);
			 $("#member_code").val(suggestion.number);
			 $("#transfer_member_code").val(suggestion.number);
			 $("#member_branch_id").val(suggestion.branch_id);
			 $("#transfer_member_branch_id").val(suggestion.branch_id);
			 $('#old_companyinfo').find('input:text').val('');  
        },
        showNoSuggestionNotice: true,
        noSuggestionNotice: 'Sorry, no matching results',
		onSearchComplete: function (query, suggestions) {
			if(!suggestions.length){
				$("#member_code").val('');
				$("#member_branch_id").val('');
				$("#member_branch_id").val('');
			    $("#transfer_member_branch_id").val('');
				$('#old_companyinfo').find('input:text').val('');  
			}
		}
    }); */
	$(document.body).on('click', '.autocomplete-no-suggestion' ,function(){
		$("#member_search").val('');
	});
	$(document).on('submit','form#member_filter',function(event){
		 event.preventDefault();
		 var member_code = $("#member_code").val();
		 var member_branch_id = $("#member_branch_id").val();
		 loader.showLoader();
		 if(member_code!='' && member_branch_id!="")
		 {
			 $.ajax({
				type: "GET",
				dataType: "json",
				url : "{{ URL::to('/get-branch-details') }}?branchid="+member_branch_id,
				success:function(res){
					if(res.status==1)
					{
						$("#old_company").val(res.data.company_name);
						$("#old_branch").val(res.data.branch_name);
						$("#old_country").val(res.data.country_name);
						$("#old_state").val(res.data.state_name);
						$("#old_city").val(res.data.city_name);
						$("#old_postal").val(res.data.postal_code);
						$("#old_address_one").val(res.data.address_one);
						$("#old_address_two").val(res.data.address_two);
						$("#old_address_three").val(res.data.address_three);
						$("#old_phone").val(res.data.phone);
						$("#old_mobile").val(res.data.mobile);
						$("#old_email").val(res.data.email);
						loader.hideLoader();
					}
				}
			 });
		  }else{
			  alert('please choose a valid member');
		  }
		 loader.hideLoader();
	});
	$('#new_company').change(function(){
	   $('#newbranchinfo').find('input:text').val('');  
	   var CompanyID = $(this).val();
	   var ajaxunionbranchid = '{{ $ajaxunionbranchid }}';
	   var ajaxbranchid = '{{ $ajaxbranchid }}';
	   var additional_cond;
	   if(CompanyID!='' && CompanyID!='undefined')
	   {
		 additional_cond = '&unionbranch_id='+ajaxunionbranchid+'&branch_id='+ajaxbranchid;
		 $.ajax({
			type: "GET",
			dataType: "json",
			url : "{{ URL::to('/get-branch-list-register') }}?company_id="+CompanyID+additional_cond,
			success:function(res){
				if(res)
				{
					$('#new_branch').empty();
					$("#new_branch").append($('<option></option>').attr('value', '').text("Select"));
					$.each(res,function(key,entry){
						$('#new_branch').append($('<option></option>').attr('value',entry.id).text(entry.branch_name)); 
					});
				}else{
					$('#new_branch').empty();
				}
			}
		 });
	   }else{
		   $('#new_branch').empty();
	   }
	});
	$('#new_branch').change(function(){
	   var branchid = $(this).val();
	   $('#newbranchinfo').find('input:text').val('');  
	   loader.showLoader();
	   if(branchid!='')
	   {
		 $.ajax({
			type: "GET",
			dataType: "json",
			url : "{{ URL::to('/get-branch-details') }}?branchid="+branchid,
			success:function(res){
				if(res.status==1)
				{
					$("#new_country").val(res.data.country_name);
					$("#new_state").val(res.data.state_name);
					$("#new_city").val(res.data.city_name);
					$("#new_postal").val(res.data.postal_code);
					$("#new_address_one").val(res.data.address_one);
					$("#new_address_two").val(res.data.address_two);
					$("#new_address_three").val(res.data.address_three);
					$("#new_phone").val(res.data.phone);
					$("#new_mobile").val(res.data.mobile);
					$("#new_email").val(res.data.email);
					 loader.hideLoader();
				}
			}
		 });
	   }else{
		   
	   }
	});
	$("#transferformValidate").validate({
		rules: {
			new_company: {
				required: true,
			},
			new_branch: {
				required: true,
			},
			
			old_company: {
				required: true,
			},
			old_branch: {
				required: true,
			},
			transfer_date: {
				required: true,
			},
			
		},
		//For custom messages
		messages: {
			new_company: {
				required: '{{__("Select company") }}',
			},
			new_branch: {
				required: '{{__("Select New Branch") }}',
			},
			old_company: {
				required: '{{__("Please select member") }}',
			},
			old_branch: {
				required: '{{__("Please select member") }}',
			},
			transfer_date: {
				required: '{{__("Please Choose date") }}',
			},
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
</script>
@endsection