@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/datepicker.css') }}">
@endsection
@section('headSecondSection')
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/custom_respon.css') }}">
<style type="text/css">
	.autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; cursor:pointer; }
	.autocomplete-suggestion { padding: 8px 5px; white-space: nowrap; overflow: hidden; }
	.autocomplete-selected { background: #F0F0F0; }
	.autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
	.autocomplete-group { padding: 8px 5px; }
	.autocomplete-group strong { display: block; border-bottom: 1px solid #000; }
	.padding-left-10{
		padding-left:10px;
	}
	.padding-left-20{
		padding-left:20px;
	}
	.padding-left-24{
		padding-left:24px;
	}
	.padding-left-40{
		padding-left:40px;
	}
	#irc_confirmation_area {
		pointer-events: none;
	}
	.branch 
	{
    	pointer-events: none;
		background-color: #f4f8fb !important;
	}
</style>
<style>
	#main.main-full {
		height: 750px;
		overflow: auto;
	}
	
	.footer {
	   position: fixed;
	   margin-top:50px;
	   left: 0;
	   bottom: 0;
	   width: 100%;
	   height:auto;
	   background-color: red;
	   color: white;
	   text-align: center;
	   z-index:999;
	} 
	.sidenav-main{
		z-index:9999;
	}
</style>
@endsection
@section('main-content')
@php 

@endphp
<div class="row">
<div class="content-wrapper-before"></div>
	<form class="formValidate" id="addarrear_formValidate" method="post"
		action="{{ route('subscription.saveArrear',app()->getLocale()) }}">
		@csrf
		<div class="container">
			<h5 class="padding-left-10">Edit Arrear Entry Details <a class="btn waves-effect waves-light right" href="{{ route('subscription.arrearentry',app()->getLocale())  }}">{{__('Back') }}</a></h5>
			<div class="row">
				 <div class="input-field col s12 m6">
                 @php $edit_data = $data; @endphp
                 <input type="hidden" name="id" value="{{$edit_data->arrearid}}">
                                          <label for="nric" class="common-label force-active">{{__('NRIC') }}*</label>
                                          <input id="nric" class="common-input"
                                              name="nric" value="{{$edit_data->nric}}" readonly type="text" data-error=".errorTxt1">
                                          <div class="errorTxt1"></div>
                                      </div>
                                      <div class="input-field col s12 m6">
                                          <label for="company_name"
                                              class="common-label force-active">{{__('Member Number') }}*</label>
                                          <input id="member_number" readonly class="common-input"
                                              name="member_number" value="{{$edit_data->member_number}}" type="text" data-error=".errorTxt2">
                                              <input type="hidden" name="membercode" id="memberid" value="{{$edit_data->memberid}}">
                                              <div class="errorTxt2"></div>
                                      </div>
                                      <div class="clearfix" style="clear:both"></div>
                                      <div class="col s12 m6">
                                          <label
                                              class="common-label  force-active">{{__('Member Name') }}</label>
                                              <input id="member_name" class="common-input"
                                              name="member_name" value="{{$edit_data->membername}}" readonly type="text" data-error=".errorTxt3">
                                              <div class="errorTxt3"></div>
                                         
                                      </div>
                                      <div class="col s12 m6">
                                          <label
                                              class="common-label  force-active">{{__('Company Name') }}</label>
                                              <input id="company_name" class="common-input"
                                              name="company_name" value="{{$edit_data->company_name}}" readonly type="text" data-error=".errorTxt3">
                                              <input type="hidden" name="company_id" id="companyid" value="{{$edit_data->companyid}}">
                                              <div class="errorTxt3"></div>
                                          
                                      </div>
                                      <div class="col s12 m6">
                                          <label
                                              class="common-label  force-active">{{__('Company Branch') }}</label>
                                              <input id="company_branch" class="common-input"
                                              name="company_branch" value="{{$edit_data->branch_name}}" readonly type="text" data-error=".errorTxt3">
                                              <input type="hidden" name="branch_id" id="companybranchid" value="{{$edit_data->companybranchid}}">
                                              <div class="errorTxt3"
                                              ></div>
                                      </div>
                                      <div class="col s12 m6">
                                          <label
                                              class="common-label  force-active">{{__('Status') }}</label>
                                              <input id="status" class="common-input"
                                              name="status" type="text" value="{{$edit_data->status_name}}"  readonly data-error=".errorTxt3">
                                              <div class="errorTxt3"></div>
                                              <input type="hidden" name="status_id" id="statusid" value="{{$edit_data->statusid}}">
                                          
                                      </div>
                                      <div class="col s12 m6">
                                          <label
                                              class="common-label  force-active">{{__('Arrear Date') }}*</label>
                                              
												<input id="arrear_date"  value="{{$edit_data->arrear_date}}" type="text" required class="datepicker" name="arrear_date">
                                              <div class="errorTxt5"></div>
                                      </div>
                                      <div class="col s12 m6">
                                          <label
                                              class="common-label  force-active">{{__('Arrear Amount') }}*</label>
                                              <input id="arrear_amount" class="common-input"
                                              name="arrear_amount" type="text"  value="{{$edit_data->arrear_amount}}"  data-error=".errorTxt6">
                                              <div class="errorTxt6"></div>
                                      <div class="clearfix" style="clear:both"></div>
                                        </div>
										<div class="col s12 m12">
										<div class="row">
										<div class="input-field col s12 m2">
											<p>
											<input type="submit" class="btn" id="save" name="save" value="Update" >
											</P>
										</div>	
										<!-- <div class="input-field col s12 m2">
											<p>
											<input type="button" class="btn" id="clear" name="clear" onClick="refreshPage()" value="Clear" >
											</P>
										</div> -->
										</div>
									</div>
		</div>
		
	</form>
</div>
@endsection
@section('footerSection')
<!--<script src="{{ asset('public/assets/js/jquery.min.js') }}"></script> -->
<script src="{{ asset('public/assets/vendors/data-tables/js/jquery.dataTables.min.js') }}" type="text/javascript">
</script>
<script src="{{ asset('public/assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js') }}"
    type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/data-tables/js/dataTables.select.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/assets/js/materialize.min.js') }}"></script>
<script src="{{ asset('public/assets/js/scripts/form-elements.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/datepicker.js') }}"></script>
@endsection
@section('footerSecondSection')
<script>
$("#subscriptions_sidebars_id").addClass('active');
$("#subsarrear_sidebar_li_id").addClass('active');
$("#subarrear_sidebar_a_id").addClass('active');

	$('#addarrear_formValidate').validate({
rules: {
    nric: {
      required: true,
  },
  member_number: {
      required: true,
  },
  arrear_date: {
    required: true,
  },
  arrear_amount: {
    required: true,
  },
},
//For custom messages
messages: {
    nric: {
      required: '{{__("Please Enter NRIC") }}',
  },
  member_number: {
      required: '{{__("Please Enter Member Number") }}',
  },
  arrear_date: {
    required: '{{__("Please Enter Arrear Date") }}',
  },
  arrear_amount: {
    required: '{{__("Please Enter Arrear Amount") }}',
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
$('.datepicker').datepicker({
    format: 'dd/mm/yyyy',
    autoHide: true,
});
</script>
@endsection