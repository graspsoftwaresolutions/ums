@extends('layouts.admin')
@section('headSection')
<style type="text/css">
	.progress {
	    position: relative;
	    display: block;
	    overflow: hidden;
	    width: 100%;
	    height: 25px !important;
	    margin: .5rem 0 1rem 0;
	    border-radius: 2px;
	    background-color: white;
	    border: 1px solid #ff4081;
	}
	.notification-badge {
	    font-family: Muli,sans-serif;
	    position: relative;
	    //top: -20px;
	    //right: 5px;
	    //margin: 0 -.8em;
	    padding: 2px 5px;
	    color: #fff;
	    border-radius: 20%;
	    background-color: #ff4081;
	    box-shadow: 0 0 10px 0 #ff4081;
	}
</style>
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
                                        <h5 class="breadcrumbs-title mt-0 mb-0">{{__('TDF') }}</h5>
                                        <ol class="breadcrumbs mb-0">
                                            <li class="breadcrumb-item"><a
                                                    href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard') }}</a>
                                            </li>
                                            <li class="breadcrumb-item active">{{__('TDF') }}
                                            </li>

                                        </ol>
                                    </div>
                                    <div class="col s2 m6 l6 ">
                                       
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12">
                            <div class="card">
                                <div class="card-content">
                                    <h4 class="card-title">{{__('TDF') }}</h4>
                                    @include('includes.messages')
                                    <div id="updatedalert" class="card-alert-nonclose card gradient-45deg-green-teal hide">
						                <div class="card-content white-text">
						                  <p>
						                    <i class="material-icons">check</i> SUCCESS : File Uploaded Successfully.</p>
						                </div>
						                <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
						                  <span aria-hidden="true">×</span>
						                </button>
						            </div>

						            @php
						            	$month_year = $data['month_year'];
						            @endphp

                                    <div id="" class="">
										@if (session()->has('success'))
										{{__('File Submitted successfully') }}
										@endif
										{{__('File Submitted successfully, Scanning process will begin shortly') }}
									</div>
									
									</br>
									</br>
									<p class="card-title">Year :  {{ date('Y',strtotime($data['month_year'])) }}</p>
									</br>
									
									<div class="row">
										<div class="col offset-s2 s8">
											<h5 class="">{{__('Updating Member details, please dont refresh the page....') }}</h5>
											
											<!-- <div id="scanning-details" class="hide center-align gradient-45deg-amber-amber padding-1 medium-small" style="color:#fff">
											{{__('Updating Member details, please dont refresh page....') }}
											</div> -->
											<br>
											<br>
											<input type="text" class="hide" name="progress_percentage" id="progress_percentage" value="0">
											<span id="progress_percent" class="notification-badge right">0%</span>
											<div class="progress">
										      <div class="determinate" style="width: 0%"></div>
										      
										    </div>
										</div>
										<div class="col s6 hide">
											<table width="100%">
												<thead>
													<tr>
														<th width="80%">{{__('Total number of members ') }}</th>
														<th>{{__('Updated ') }}</th>
													</tr>
												</thead>
												<tbody>
													@php
														$callcount = ceil($data['row_count']/10);
														//dd($callcount);
														$lastrow=0;
														$limit=$callcount;
														$no_of_rows = $data['row_count']/$limit;
														$halfrows = round($no_of_rows/2);
														$count =0;
														$half = $data['row_count']%2;
														//dd($halfrows);
													@endphp
													@if($data['row_count']>0)
														@for ($i = 0; $count < $halfrows; $i+=$limit,$count++)
															<tr>
																<th>{{ $i }} - @php if( $data['row_count'] < $i+$limit && $data['row_count'] >= $callcount){ echo $i.'++'; }else if( $data['row_count'] <= $callcount ){ echo $data['row_count']; }else{ echo $i+$limit; }  @endphp</th>
																<th><span id="check_updated_{{ $i }}"><i class="material-icons"></i><span></th>
															</tr>
															@php
																$lastrow+=$limit;
															@endphp
														@endfor
													@endif
												</tbody>
											</table>
										</div>
										<div class="col s6 hide">
											@if($data['row_count']>0)
											<table width="100%">
												<thead>
													<tr>
														<th width="80%">{{__('Total number of members') }}</th>
														<th>{{__('Updated') }}</th>
													</tr>
												</thead>
												<tbody>
													@for ( ; $count <= $no_of_rows; $i+=$limit,$count++)
													<tr>
														<th>{{ $i }}- @php if( $data['row_count'] < $i+$limit ){ echo $i.'++'; }else{ echo $i+$limit; }  @endphp</th>
														<th><span id="check_updated_{{ $i }}"><i class="material-icons"></i><span></th>
													</tr>
														@php
															$lastrow+=$limit;
														@endphp
													@endfor
												</tbody>
											</table>
											@endif
											<input type="text" class="hide" name="number_of_calls" id="number_of_calls" value="{{ $count }}">
											<input type="text" class="hide" name="percent_per_call" id="percent_per_call" value="{{ $count>0 ? 10 : 1 }}">
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
	$("#tdf_sidebars_id").addClass('active');
    $("#tdfupload_sidebar_li_id").addClass('active');
    $("#tdfupload_sidebar_a_id").addClass('active');
        
	$(document).ready(function() {
		$("#scanning-details").removeClass('hide');
		var row_count = {{ $data['row_count'] }};
		if(row_count>0){
			setTimeout(function(){
			  jQuery( '.loading-overlay' ).show();
			  ScanMembership(0);
			}, 1500);
		}else{
			$('#scanning-details').addClass('gradient-45deg-green-teal ');
			$('#scanning-details').html('Mebership details already updated');
		}
	});
	
	
	function ScanMembership(start){
		var lastrow ={{$lastrow}};
		var tdfid = {{ $data['tdfid'] }};
		var limit = {{ $limit }};
		
		var url = "{{ url(app()->getLocale().'/tdf-scanning') }}" + '?start='+start+'&tdfid='+tdfid+ '&limit='+limit;
			$.ajax({
				url: url,
				type: "GET",
				dataType: 'JSON',
				success: function(result) {
					
					if(result.status==1){
						
						
						if(start!=lastrow){
							var limit = {{ $limit }};
							var progress_count = $("#progress_percentage").val();
							var percent_per_call = $("#percent_per_call").val();
							progress_count = parseInt(progress_count)+parseInt(percent_per_call);
							progress_count = progress_count>100 ? 100 : progress_count;
							$('#check_updated_'+start+' span').html('<i class="material-icons">done</i>');
							ScanMembership(parseInt(start+limit));
							$(".determinate").css('width',progress_count+'%');
							$("#progress_percent").text(progress_count+'%');
							console.log(progress_count);
							
							$("#progress_percentage").val(progress_count);
						}else{
							$('#scanning-details').html(result.message);
							$('#scanning-details').removeClass('gradient-45deg-amber-amber');
							$('#scanning-details').addClass('gradient-45deg-green-teal ');
							$('#check_updated_'+start+' span').html('<i class="material-icons">done</i>');
							loader.hideLoader();
							$("#updatedalert").removeClass('hide');
							setTimeout(function(){
								window.location.href = result.redirect_url;
							}, 1500);
						}
						
					}else{
						
					}
				},
				"error": function (jqXHR, textStatus, errorThrown) {
					if(jqXHR.status==419){
						alert('Your session has expired, please login again');
						window.location.href = base_url;
					}
					if(jqXHR.status==524){
						alert('Server does not respond, page will get reloaded');
						location.reload();
					}
				},
			});
		
	}
	
</script>
@endsection