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
<div id="main">
    <div class="row">
        <div style="height:150px !important" class="content-wrapper-before gradient-45deg-indigo-purple"></div>
        <div class="col s12">
            <div class="container">
                <div class="section section-data-tables">
                    <!-- BEGIN: Page Main-->
                    <div class="row">
                        <div class="breadcrumbs-dark pb-0 pt-4" id="breadcrumbs-wrapper">
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
			<div class="card-content">
				<div class="card-title">
					<div class="row">
						<div class="col s12 m6 l10">
							<div class="row">
								<form class="formValidate" id="subscribe_formValidate" method="post" action="{{ url(app()->getLocale().'/subscribe_download') }}" enctype="multipart/form-data">
									@csrf
									<div class="row">
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
										<div class="input-field col s4">
											<label for="doe">{{__('Date of Entry') }}*</label>
											<input type="text" name="entry_date" id="entry_date" class="datepicker-custom" />
										</div>
										<div class="input-field col s4">
											@php
											$companylist = CommonHelper::getCompanyListAll();
											@endphp
											<select name="sub_company" id="sub_company" class="error browser-default selectpicker" data-error=".errorTxt6">
												<option value="" selected>Choose Company</option>
												@foreach($companylist as $value)
												<option value="{{$value->id}}">{{$value->company_name}}</option>
												@endforeach
											</select>
											<div class="errorTxt6"></div>
										</div>
										<div class="input-field col s2">
											 <select id="type" name="type"
											  class="error browser-default common-select add-select" onChange="return FileUploadEnable(this.value)">
												<option value="0">{{__('Download') }}</option>
												<option value="1">{{__('Upload') }}</option>

										     </select>
										</div>
										<div id="file-upload-div" class="input-field  file-field col s2 hide">
											
											<div class="btn ">
												<span>File</span>
												<input type="file" name="file" class="form-control browser-default"  accept=".xls,.xlsx">
											</div>
											<div class="file-path-wrapper ">
												<input class="file-path validate" type="text">
											</div>
										</div>
										<div class="input-field col s4 right">
											<button id="submit-download" class="waves-effect waves-dark btn btn-primary form-download-btn" type="submit">Download/Upload</button>
											
										</div>
									</div>
								</form>
								
							</div>
						</div>
						
					</div>
				</div>
			</div>
		</div>
	</div>
    <div class="col s12">
      <div id="validations" class="card card-tabs">
        <div class="card-content">
          <div class="card-title">
            <div class="row">
              <div class="col s12 m6 l10">
              <div class="row">
                <form class="col s12">
                    <div class="row">
                        <div class="input-field col s4">
                        <!--Month and year-->
                        
                        </div>
                        <div class="input-field col s4">
                        <select name="sub_company" class="error browser-default selectpicker">
						<option value="" disabled selected>Choose Company</option>
                        			
						</select>
                        
                        </div>
                        <div class="input-field  file-field col s4">
                        
                        <div class="btn">
                            <span>File</span>
                            <input type="file">
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text">
                        </div>
                        
                    </div>               
                </form>
            </div>
            <div class="row">
                <div class="col s12 m6">
                <div class="card darken-1">                    
                    <span style="text-align:center;padding:5px;" class="card-title">Status</span>
                    <table class="collection">
                    <tr style="background:#3f51b5;color:white;text-align:center;" class="collection-item avatar">
                    <td>Sl No</td>
                    <td>Status</td>
                    <td>Count</td>
                    <td>Amount</td>
                    </tr>
                    @php 
                    isset($data['member_stat']) ? $data['member_stat'] : "";                   
                    @endphp 
                    @foreach($data['member_stat'] as  $key => $stat)
                    <tr>
                    <td>{{ $key+1 }} </td>
                    <td>{{ $stat->status_name }}</td>                   
                    <td>{{ $stat->Subscription_members->count() }}</td>
                    <td>{{ $stat->Subscription_members->sum('Amount') }}</td>
                    </tr>                  
                    @endforeach
                    </table>

                   </div> 
                </div>
                <!--Approval Status-->
                <div class="col s12 m6">
                <div class="card darken-1">
                    
                    <span style="text-align:center;padding:5px;" class="card-title">Approval Status</span>
                    <table class="collection">
                    <tr style="background:#3f51b5;color:white;text-align:center;" class="collection-item avatar">
                    <td>Sl No</td>
                    <td>Description</td>
                    <td>Count</td>
                    <td>Approved</td>
                    <td>Pending</td>
                    </tr>
                    @php 
                    isset($data['member_stat']) ? $data['member_stat'] : "";                   
                    @endphp 
                    @foreach($data['member_stat'] as  $key => $stat)
                    <tr>
                    <td>{{ $key+1 }} </td>
                    <td>{{ $stat->status_name }}</td>                   
                    <td>{{ $stat->Subscription_members->count() }}</td>
                    <td>{{ $stat->Subscription_members->sum('Amount') }}</td>
                    <td>{{ $stat->Subscription_members->sum('Amount') }}</td>
                    </tr>                  
                    @endforeach
                    </table>

                   </div> 
                </div>
                </div>
                
            </div>
          
         
        </div>
      </div>
    </div>
  </div>
</div>

<!--dgfdgfdg-->


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
     $(document).ready(function(){
        $(".datepicker-custom").datepicker({
            autoclose: true,
            format: "mmm/yyyy"
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
				file:{
					required: true,
				},
			 },
        //For custom messages
        messages: {
					entry_date: {
						required: "Please choose date",
						
					},
					sub_company: {
						required: "Please choose company",
						
					},
					file:{
						required: 'required',
					},
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
		if(type == 0){
			$("#file-upload-div").addClass('hide');
		}else{
			$("#file-upload-div").removeClass('hide');
		}
	}
</script>
@endsection