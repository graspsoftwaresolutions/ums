@extends('layouts.admin')
@section('headSection')
@endsection

@section('main-content')
	<div class="row">
        <div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
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
                                        <h5 class="breadcrumbs-title mt-0 mb-0">{{__('Subscription') }}</h5>
                                        <ol class="breadcrumbs mb-0">
                                            <li class="breadcrumb-item"><a
                                                    href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard') }}</a>
                                            </li>
                                            <li class="breadcrumb-item active">{{__('Subscription') }}
                                            </li>

                                        </ol>
                                    </div>
                                    <div class="col s2 m6 l6 ">
                                        <a class="btn waves-effect waves-light breadcrumbs-btn right modal-trigger hide"
                                            onClick='showaddForm();' href="#modal_add_edit">{{__('Add') }}</a>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12">
                            <div class="card">
                                <div class="card-content">
                                    <h4 class="card-title">{{__('Subscription') }}</h4>
                                    @include('includes.messages')
									@if (session()->has('success'))
										File Updated successfully
									@endif
									File Updated successfully, Scanning process will begin shortly
									@php	
										$company_auto_id = $data['company_auto_id'];
									@endphp
									</br>
									</br>
									<div id="scanning-details" class="hide gradient-45deg-amber-amber padding-1 medium-small" style="color:#fff">
										Updating Member status and member code, please dont refresh page....
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
    </div>
@endsection
		
@section('footerSection')

<script>
	$("#subscriptions_sidebars_id").addClass('active');
	$("#subscription_sidebar_li_id").addClass('active');
	$("#subscription_sidebar_a_id").addClass('active');
	$(document).ready(function() {
		$("#scanning-details").removeClass('hide');
		setTimeout(function(){
		  loader.showLoader();
		  ScanMembership({{ $company_auto_id }});
		}, 1500);
	});
	
	
	function ScanMembership(company_id){
		if(company_id!=""){
			var url = "{{ url(app()->getLocale().'/process-scanning') }}" + '?company_auto_id=' + company_id;
			$.ajax({
				url: url,
				type: "GET",
				dataType: 'JSON',
				success: function(result) {
					loader.hideLoader();
					if(result.status==1){
						$('#scanning-details').removeClass('gradient-45deg-amber-amber');
						$('#scanning-details').addClass('gradient-45deg-green-teal ');
						$('#scanning-details').html(result.message);
						setTimeout(function(){
						  window.location.href = result.redirect_url;
						}, 1500);
					}else{
						
					}
				}
			});
		}else{
			//$("#type option[value='2']").remove();
		}
	}
</script>
@endsection