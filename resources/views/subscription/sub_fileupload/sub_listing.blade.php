@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/vendors/data-tables/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css') }}">
@endsection
@section('headSecondSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/pages/data-tables.css') }}">
@endsection
@section('main-content')
<div id="">
    <div class="row">
        <div style="height:150px !important" class="content-wrapper-before gradient-45deg-indigo-purple"></div>
        <div class="col s12">
            <div class="container">
                <div class="section section-data-tables">
                    <!-- BEGIN: Page Main-->
                    <div class="row">
                        <div class="breadcrumbs-dark" id="breadcrumbs-wrapper">
                            <!-- Search for small screen-->
                            <div class="container">
                                <div class="row">
                                    <div class="col s10 m6 l6">
                                        <h5 class="breadcrumbs-title mt-0 mb-0">{{__('Subscription List') }}</h5>
                                        <ol class="breadcrumbs mb-0">
                                            <ol class="breadcrumbs mb-0">
                                                <li class="breadcrumb-item"><a
                                                        href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard') }}</a>
                                                </li>
                                                <li class="breadcrumb-item active">{{__('Subscription') }}
                                                </li>
                                        </ol>
                                    </div>
									<div class="col s2 m6 l6 ">
                                        <a class="btn waves-effect waves-light cyan breadcrumbs-btn right " href="{{ route('subscription.download', app()->getLocale())  }}">{{__('Download Sample')}}</a>
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
<!--sfsdgfdg-->
	<div class="clearfix"></div>
    <div class="col s12">
		<div id="validations" class="card card-tabs">
			<div class="card-title">
				@if ($errors->any())
						<div class="card-alert card gradient-45deg-red-pink">
							<div class="card-content white-text">
							  <p>
								<i class="material-icons">check</i> {{ __('Error') }} : {{ implode('', $errors->all(':message')) }}</p>
							</div>
							<button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
							  <span aria-hidden="true">×</span>
							</button>
						 </div>
						
					@endif
			</div>
			<div class="card-content">
					<div class="row">
						<div class="col s12 m12">
							<div class="row">
								<form class="formValidate" id="subscribe_formValidate" method="post" action="{{ url(app()->getLocale().'/subscribe_download') }}" enctype="multipart/form-data">
									@csrf
									<div class="row">
										
										@php 
									
											$auth_user = Auth::user();
											$companylist = [];
											$companyid = '';
											if(!empty($auth_user)){
												$userid = Auth::user()->id;
												$get_roles = Auth::user()->roles;
												$user_role = $get_roles[0]->slug;
												
												if($user_role =='union'){
													$companylist = CommonHelper::getCompanyListAll();
												}
												else if($user_role =='union-branch'){
													$unionbranchid = CommonHelper::getUnionBranchID($userid);
													$companylist = CommonHelper::getUnionCompanyList($unionbranchid);
												} 
												else if($user_role =='company'){
													$companyid = CommonHelper::getCompanyID($userid);
													$companylist = CommonHelper::getCompanyList($companyid);
												}
												else if($user_role =='company-branch'){
													$companyid = CommonHelper::getCompanyID($userid);
													$companylist = CommonHelper::getCompanyList($companyid);
												}  
												$company_count = count($companylist);
											}
											
										@endphp
										<div class="input-field col s3">
											<label for="doe">{{__('Subscription Month') }}*</label>
											<input type="text" name="entry_date" id="entry_date" value="{{ date('M/Y') }}" class="datepicker-custom" />
										</div>
										<div class="col s4">
											<label for="sub_company">{{__('Company') }}*</label>
											<select name="sub_company" id="sub_company" class="error browser-default selectpicker" data-error=".errorTxt6">
												<option value="" selected>{{__('Choose Company') }}</option>
												@foreach($companylist as $value)
												<option value="{{$value->id}}">{{$value->company_name}}</option>
												@endforeach
											</select>
											<div class="errorTxt6"></div>
										</div>
										<div class="col s2 hide" >
											<label for="type">{{__('Type') }}*</label>
											 <select id="type" name="type"
											  class="error browser-default common-select add-select" onChange="return FileUploadEnable(this.value)">
												<option value="0">{{__('Download Empty File') }}</option>
												<option value="1" selected>{{__('Upload File') }}</option>
										     </select>
										</div>
										<div id="file-upload-div" class="input-field  file-field col s2">
											
											<div class="btn ">
												<span>File</span>
												<input type="file" name="file" class="form-control browser-default"  accept=".xls,.xlsx">
											</div>
											<div class="file-path-wrapper ">
												<input class="file-path validate" type="text">
											</div>
										</div>
										<div class="col s3 " >
											</br>
											
											
										</div>
										
									</div>
									<div class="row">
										<div class="col s7">
											
										</div>
										<div class="col s4 ">
											<button id="submit-upload" class="waves-effect waves-dark btn btn-primary form-download-btn" type="button">{{__('Submit') }}</button>
											<button id="submit-download" class="waves-effect waves-light cyan btn btn-primary form-download-btn hide" type="button">{{__('Download Sample') }}</button>
											
										</div>
									</div>
								</form>
								
							</div>
						</div>
						
					</div>
				
			</div>
		</div>
	</div>
    <div class="row">
		<div class="col s12 m6">
			<div class="card darken-1">
				<span style="text-align:center;padding:5px;" class="card-title">{{__('Member Status') }}</span>
				<table class="collection">
					<tr style="background:#3e57e6;color:white;text-align:center;" class="collection-item avatar">
						<td>{{__('Sl No') }}</td>
						<td>{{__('Status') }}</td>
						<td>{{__('Count') }}</td>
						<td>{{__('Amount') }}</td>
					</tr>
					@php 
						$get_roles = Auth::user()->roles;
						$user_role = $get_roles[0]->slug;
						$user_id = Auth::user()->id;
					@endphp 
					@foreach($data['member_stat'] as  $key => $stat)
					<tr>
						<td>{{ $key+1 }} </td>
						<td>{{ $stat->status_name }}</td>
						<td>{{ CommonHelper::statusSubsMembersCount($stat->id, $user_role, $user_id) }}</td>
						<td>{{ number_format(CommonHelper::statusMembersAmount($stat->id, $user_role, $user_id), 2) }} </td>
					</tr>
					@endforeach
				</table>
			</div>
		</div>
		<!--Approval Status-->
		<div class="col s12 m6">
			<div class="card darken-1">
				<span style="text-align:center;padding:5px;" class="card-title">{{__('Approval Status') }}</span>
				<table class="collection">
					<tr style="background:#3e57e6;color:white;text-align:center;" class="collection-item avatar">
						<td>{{__('Sl No') }}</td>
						<td>{{__('Description') }}</td>
						<td>{{__('Count') }}</td>
					</tr>
					@php 
					//isset($data['approval_status']) ? $data['approval_status'] : "";                   
					@endphp 
					@foreach($data['approval_status'] as  $key => $stat)
					<tr>
						<td>{{ $key+1 }} </td>
						<td>{{ $stat->match_name }}</td>
						<td>{{ $stat->count }}</td>
					</tr>
					@endforeach
				</table>
			</div>
		</div>
		
	</div>
	</br>
	</br>
<!--dgfdgfdg-->
	 <div id="modal_subscription" class="modal">
		<div class="modal-content">
			<p>{{__('Company Data Already Exists, Are you sure you want to download existance data') }}</p>
			<div class="row">
				<div class="input-field col s12 m6">
					<button id="modal-update-btn" class="btn waves-effect waves-light submit edit_hide_btn " onClick="return DownloadExistance(1)"
						type="button" name="action">{{__('Yes')}}
					</button>
				</div>
				<div class="input-field col s12 m6 right">
					<button id="modal-update-btn" class="btn waves-effect waves-light submit edit_hide_btn right" onClick="return DownloadExistance(0)"
						type="button" name="action">{{__('No')}}
					</button>
				</div>
				<div class="clearfix" style="clear:both"></div>
				
			</div>
		</div>
	</div>

    </div>
</div> 

@endsection
@section('footerSection')
<script src="{{ asset('public/assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/assets/vendors/data-tables/js/jquery.dataTables.min.js') }}" type="text/javascript">
</script>
<script src="{{ asset('public/assets/vendors/noUiSlider/nouislider.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/materialize.min.js') }}"></script>
<script src="{{ asset('public/assets/js/scripts/form-elements.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js') }}"
    type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/data-tables/js/dataTables.select.min.js') }}" type="text/javascript">
</script>
@endsection
@section('footerSecondSection')
<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/assets/js/scripts/form-validation.js')}}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/scripts/data-tables.js') }}" type="text/javascript"></script>
<script>
$(document).ready(function() {
    // the "href" attribute of the modal trigger must specify the modal ID that wants to be triggered
    $('.modal').modal();
});
     $(document).ready(function(){
        $(".datepicker-custom").datepicker({
            autoclose: true,
            format: "mmm/yyyy",
			/* today: 'Today',
			defaultDate: '01/Jul/2019', */
        });
    });
	$("#subscribe_formValidate").validate({
        rules: {
				entry_date:{
					required: true,
				},
				sub_company:{
					required: true,
				},
				/* file:{
					required: true,
				}, */
			 },
        //For custom messages
        messages: {
					entry_date: {
						required: "Please choose date",
						
					},
					sub_company: {
						required: "Please choose company",
						
					},
					/* file:{
						required: 'required',
					}, */
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
	
	function FileUploadEnable(type){
		if(type == 1){
			$("#file-upload-div").removeClass('hide');
		}else{
			$("#file-upload-div").addClass('hide');
		}
	}
	$(document).on('change','#entry_date,#sub_company',function(){
		var entry_date = $("#entry_date").val();
		var sub_company = $("#sub_company").val();
		if(sub_company!="" && sub_company!=""){
			loader.showLoader();
			$("#type option[value='2']").remove();
			var url = "{{ url(app()->getLocale().'/check-subscription-exists') }}" + '?entry_date=' + entry_date + "&sub_company=" + sub_company;
			$.ajax({
				url: url,
				type: "GET",
				success: function(result) {
					loader.hideLoader();
					if(result.status==1){
						$("#modal_subscription").modal('open');
						$("#type").append('<option value="2">Download Existance data</option>');
					}else{
						
					}
				}
			});
		}
	});
	function DownloadExistance(existance){
		if(existance==1){
			$("#type").val(2);
			$('#subscribe_formValidate').trigger('submit');
		}else{
			//$("#type option[value='2']").remove();
		}
		$("#modal_subscription").modal('close');
	}
	$(document).on('submit','form#subscribe_formValidate',function(){
		var type = $("#type").val();
		if(type==1){
			loader.showLoader();
		}
		//$("#submit-download").prop('disabled',true);
	});
	$(document).on('click','#submit-download',function(){
		$("#type").val(0);
		$('#subscribe_formValidate').trigger('submit');
	});
	$(document).on('click','#submit-upload',function(){
		$("#type").val(1);
		$('#subscribe_formValidate').trigger('submit');
		
	});
	$("#subscriptions_sidebars_id").addClass('active');
	$("#subscription_sidebar_li_id").addClass('active');
	$("#subscription_sidebar_a_id").addClass('active');
</script>
@endsection