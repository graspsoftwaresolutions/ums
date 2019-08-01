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
										Updating Member details, please dont refresh page....
									</div>
									<div class="row">
										<div class="col s6">
											<table width="100%">	
												<thead>
													<tr>
														<th width="80%">Total number of members</th>
														<th>Updated</th>
													</tr>
												</thead>
												
												<tbody>
													@php
														$lastrow=0;
														$no_of_rows = $data['row_count']/200;
														$halfrows = round($no_of_rows/2);
														$count =0;
														$half = $data['row_count']%2;
														//dd($halfrows);
													@endphp
													@if($data['row_count']>0)
													@for ($i = 0; $count <= $halfrows; $i+=200,$count++)
														<tr>
															<th>{{ $i }} - @php if( $data['row_count'] < $i+200 ){ echo $i.'++'; }else{ echo $i+200; }  @endphp</th>
															<th><span id="check_updated_{{ $i }}"><i class="material-icons"></i><span></th>
														</tr>
														@php
															$lastrow+=200;
														@endphp
													@endfor
													@endif
												</tbody>
												
											</table>
										</div>
										<div class="col s6">
											@if($data['row_count']>0)
											<table width="100%">	
												<thead>
													<tr>
														<th width="80%">Total number of members</th>
														<th>Updated</th>
													</tr>
												</thead>
												<tbody>
													
													@for ( ; $count <= $no_of_rows; $i+=200,$count++)
														<tr>
															<th>{{ $i }}- @php if( $data['row_count'] < $i+200 ){ echo $i.'++'; }else{ echo $i+200; }  @endphp</th>
															<th><span id="check_updated_{{ $i }}"><i class="material-icons"></i><span></th>
														</tr>
														@php
															$lastrow+=200;
														@endphp
													@endfor
													
												</tbody>
											</table>
											@endif
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
    </div>
@endsection
		
@section('footerSection')

<script>
	$("#subscriptions_sidebars_id").addClass('active');
	$("#subscription_sidebar_li_id").addClass('active');
	$("#subscription_sidebar_a_id").addClass('active');
	$(document).ready(function() {
		$("#scanning-details").removeClass('hide');
		var row_count = {{ $data['row_count'] }};
		if(row_count>0){
			setTimeout(function(){
			  loader.showLoader();
			  ScanMembership({{ $company_auto_id }},0);
			}, 1500);
		}else{
			$('#scanning-details').addClass('gradient-45deg-green-teal ');
			$('#scanning-details').html('Mebership details already updated');
		}
	});
	
	
	function ScanMembership(company_id,start){
		var lastrow ={{$lastrow}};
		if(company_id!=""){
		var url = "{{ url(app()->getLocale().'/process-scanning') }}" + '?company_auto_id=' + company_id+ '&start='+start;
			$.ajax({
				url: url,
				type: "GET",
				dataType: 'JSON',
				success: function(result) {
					
					if(result.status==1){
						
						
						if(start!=lastrow){
							$('#check_updated_'+start+' span').html('<i class="material-icons">done</i>');
							ScanMembership(company_id,parseInt(start+200));
						}else{
							$('#scanning-details').html(result.message);
							$('#scanning-details').removeClass('gradient-45deg-amber-amber');
							$('#scanning-details').addClass('gradient-45deg-green-teal ');
							$('#check_updated_'+start+' span').html('<i class="material-icons">done</i>');
							loader.hideLoader();
							setTimeout(function(){
								window.location.href = result.redirect_url;
							}, 1500);
						}
						
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