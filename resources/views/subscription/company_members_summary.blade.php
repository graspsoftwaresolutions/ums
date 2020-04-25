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
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/chartist-js/chartist.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/chartist-js/chartist-plugin-tooltip.css') }}">
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
	@media (max-width: 500px) {
		.modal {
			width: 90% !important;
		}
	}
	.task-cat{
		//color: #000;
		font-size: 1.2rem;
    	font-weight: bold;
    	//margin : 20px;
    	padding: 10px 10px;
	}
	.collection-header,.task-cat{
		color: #fff;
	}
		@media print {
		#printbutton{
			display: none !important;
		}
		.sidenav-main,.nav-wrapper {
		 	display:none !important;
		}

		.gradient-45deg-indigo-purple{
			display:none !important;
		}
		#filterarea{
			display:none !important;
		}

		#subsfilter{
			display:none !important;
		}

		#tabdiv{
			display:none !important;
		}
		#advancedsearchs{
			display:none !important;
		}
		.btn{
			display:none !important;
		}

		#printableArea {
		 	display:block !important;
		}
		.collection-header,.task-cat{
			color: #000 !important;
		}
	}

	.ct-series-a .ct-area, .ct-series-a .ct-slice-donut-solid, .ct-series-a .ct-slice-pie {
	    stroke: #ff4bac !important;
		fill: #ff4bac !important;
	}
	.ct-series-b .ct-area, .ct-series-b .ct-slice-donut-solid, .ct-series-b .ct-slice-pie {
	    stroke: #BBBBBB !important;
		fill: #BBBBBB !important;
	}
	#current-balance-donutone-chart > .ct-series-a .ct-point, .ct-series-a .ct-slice-donut{
		stroke: #ff4bac !important;
	}
</style>
@endsection
@section('main-content')
<div id="">
<div class="row">
	<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
	<div class="col s12">
		<div class="container">
			<div class="section section-data-tables">
				<!-- BEGIN: Page Main-->
				<div class="row">
					<div class="breadcrumbs-dark hide" id="breadcrumbs-wrapper">
						<!-- Search for small screen-->
						<div class="container">
							<div class="row">
								<div class="col s10 m6 l6">
									<h5 class="breadcrumbs-title mt-0 mb-0">{{__('Subscription Summary')}}</h5>
									<ol class="breadcrumbs mb-0">
										<li class="breadcrumb-item"><a
												href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard')}}</a>
										</li>
										<li class="breadcrumb-item active">{{__('Subscription')}}
										</li>
									</ol>
								</div>
								<div class="col s2 m6 l6 ">
								
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col s12">
							<div class="card">
								@php
								$datacmpy = $data['subsdata'];   
								//dd($datacmpy);
								$enccompany_auto_id = Crypt::encrypt($data['company_auto_id']);
								$company_id = $data['company_auto_id'];
								
								$month = $datacmpy->Date;
								
								@endphp
								<div class="card-content">
									<div class="row">
										<div class="col m6">
											<div class="row">
												<div class="col m10">
													<h4 class="card-title" style="font-weight: bold; font-size: 16px">{{__('BANK : ')}} {{ isset($datacmpy) ? $datacmpy->short_code : ""}} - {{ isset($datacmpy) ? $datacmpy->company_name : ""}} </h4>	
													
												</div>
												
											</div>
											
											
											<!-- <h4 class="card-title" style="font-weight: bold; font-size: 16px">{{__('Total Members Count :')}} {{ isset($data['tot_count']) ? $data['tot_count'] : ""}}</h4>
											<div class="row">
												<div class="col m10">
													<h4 class="card-title" style="font-weight: bold; font-size: 16px">{{__('Bank : ')}} {{ isset($datacmpy) ? $datacmpy->short_code : ""}} - {{ isset($datacmpy) ? $datacmpy->company_name : ""}} </h4>	
												</div>
												
											</div> -->
										</div>
										<div class="col m6">
											<div class="row">
												<div class="col m10">
													
													<h4 class="card-title" style="font-weight: bold; font-size: 16px">{{__('MONTH :')}} @php echo strtoupper(date('M-Y',strtotime($datacmpy->Date))); @endphp</h4>
												</div>
												
											</div>
											
											
											<!-- <h4 class="card-title" style="font-weight: bold; font-size: 16px">{{__('Total Members Count :')}} {{ isset($data['tot_count']) ? $data['tot_count'] : ""}}</h4>
											<div class="row">
												<div class="col m10">
													<h4 class="card-title" style="font-weight: bold; font-size: 16px">{{__('Bank : ')}} {{ isset($datacmpy) ? $datacmpy->short_code : ""}} - {{ isset($datacmpy) ? $datacmpy->company_name : ""}} </h4>	
												</div>
												
											</div> -->
										</div>
										
									</div>
									
								@include('includes.messages')
								
								</div>
							
							</div>
							<ul id="projects-collection" class="collection z-depth-1 animate fadeLeft">
				               <li class="">
				                
				                  <h6 class="collection-header m-20" style="margin: 10px 10px;">Subscription Summary 

				                
									<form class="formValidate" id="subscribe_formValidate" method="post" action="{{ url(app()->getLocale().'/subscription_variance') }}" target="_blank" enctype="multipart/form-data">
										@csrf
										<div class="">
											
											<div class="hide">
												<label for="doe">{{__('From Month') }}*</label>
												<input type="text" name="from_date" id="from_date" required="" value="{{ date('M/Y',strtotime($datacmpy->Date.' -1 month')) }}" class="datepicker-custom" />
											
												<label for="doe">{{__('Subscription Month') }}*</label>
												<input type="text" name="to_date" id="to_date" required value="{{ date('M/Y',strtotime($datacmpy->Date)) }}" class="datepicker-custom" />
												<input type="text" name="companyid" id="companyid" required value="{{ $data['companyid'] }}" class="hide" />
											</div>
											
											
											<div class=" " >
												
												<button id="submit-upload" style="margin-left: 10px;margin-top: -24px;" class=" btn waves-effect waves-light purple lightrn-1 form-download-btn right" type="submit">{{__('Variation') }}</button>
												
											</div>
											
										</div>
										
									</form>
									
								

				                  	<!--a style="" target="_blank" href="{{ URL::to(app()->getLocale().'/summary-status?status=0&date='.strtotime('now').'&company_id='.$data['company_auto_id']) }}" title="View Members" class="waves-effect waves-light blue btn btn-sm" href="">View Variation</a-->

				                  	<a id="printbutton" href="#" class="export-button btn red right" style="background:#ccc;;margin-top: -24px;" onClick="window.print()"> Print</a></h6>
				                    <p>
			                  	
										
						 	
									
								    </p>
				               </li>
				               <li class="collection-item">
				                  <div class="row">
				                     <div class="col s6">
				                        <p class="collections-title">Total Members in NUBE</p>
				                        <p class="collections-content"></p>
				                     </div>
				                     <div class="col s3"> <p class="collections-title"><span class="task-cat deep-orange accent-2">{{ $data['members_count'] }}</span></p></div>
				                     <div class="col s3">
				                        <div id="project-line-1"></div>
				                     </div>
				                  </div>
				               </li>
				               <li class="collection-item">
				                  <div class="row">
				                     <div class="col s6">
				                        <p class="collections-title">Total Members Uploaded</p>
				                        <p class="collections-content"></p>
				                     </div>
				                     <div class="col s3"><p class="collections-title"><span class="task-cat deep-orange accent-2">{{ $data['company_subscription_list']-$data['doj_count'] }}</span></p></div>
				                     <div class="col s3">
				                        <div id="project-line-2"></div>
				                     </div>
				                  </div>
				               </li>
				               <li class="collection-item">
				                  <div class="row">
				                     <div class="col s6">
				                        <p class="collections-title">Total Members Matched</p>
				                        <p class="collections-content"></p>
				                     </div>
				                     <div class="col s3"><p class="collections-title"><span class="task-cat deep-orange accent-2">{{ $data['matched_count']-$data['doj_count'] }}</span></p></div>
				                     <div class="col s3">
				                        <a style="" target="_blank" href="{{ URL::to(app()->getLocale().'/summary-status?status=1&date='.strtotime('now').'&company_id='.$data['company_auto_id']) }}" title="View Members" class="waves-effect waves-light blue btn btn-sm" href="">View</a>
				                     </div>
				                  </div>
				               </li>
				               <li class="collection-item">
				                  <div class="row">
				                     <div class="col s6">
				                        <p class="collections-title">Uploaded Amount (RM)</p>
				                        <p class="collections-content"></p>
				                     </div>
				                     <div class="col s3"><p class="collections-title"><span class="task-cat deep-orange accent-2">{{ number_format($data['matched_amount'],2,".",",") }}</span></p></div>
				                     <div class="col s3">
				                        <div id="project-line-4"></div>
				                     </div>
				                  </div>
				               </li>
				               <li class="collection-item">
				                  <div class="row">
				                     <div class="col s6">
				                        <p class="collections-title">Not Matched Members</p>
				                        <p class="collections-content"></p>
				                     </div>
				                     <div class="col s3"><p class="collections-title"><span class="task-cat deep-orange accent-2">{{ $data['company_subscription_list']-$data['matched_count'] }}</span> </p></div>
				                     <div class="col s3">
				                       <a style="" target="_blank" href="{{ URL::to(app()->getLocale().'/summary-status?status=0&date='.strtotime('now').'&company_id='.$data['company_auto_id']) }}" title="View Members" class="waves-effect waves-light blue btn btn-sm" href="">View</a>
				                     </div>
				                  </div>
				               </li>

				            </ul>
				            <div class="row hide">
				            	<div class="col s12 m6 l4">
							      <div class="ct-chart card z-depth-2 border-radius-6">
							        <div class="card-content">
							          <div class="row">
							            <div class="col s12">
							              <h4 class="card-title center">Total Members in NUBE</h4>
							            </div>
							            <div class="col s10 offset-s2 display-flex" style="padding-left: 30px;">
								            <div class="current-balance-container">
								               <div id="current-balance-donut-chart" class="current-balance-shadow"></div>
								            </div>
							            </div>
							          </div>
							        </div>
							      </div>
							    </div>
							    <div class="col s12 m6 l4">
							      <div class="ct-chart card z-depth-2 border-radius-6">
							        <div class="card-content">
							          <div class="row">
							            <div class="col s12">
							              <h4 class="card-title center">Total Members Uploaded</h4>
							            </div>
							            <div class="col s10 offset-s2 display-flex" style="padding-left: 30px;">
								            <div class="current-balance-container">
								               <div id="current-balance-donutone-chart" class="current-balance-shadow"></div>
								            </div>
							            </div>
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
		</div>
		</div>
	</div>
</div>
</div>


@endsection
@section('footerSection')


@endsection
@section('footerSecondSection')
<script src="{{ asset('public/assets/vendors/chartjs/chart.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/chartist-js/chartist.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/chartist-js/chartist-plugin-tooltip.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/chartist-js/chartist-plugin-fill-donut.min.js') }}" type="text/javascript"></script>

<script>
$("#subscriptions_sidebars_id").addClass('active');
$("#subcomp_sidebar_li_id").addClass('active');
$("#subcomp_sidebar_a_id").addClass('active');
//Model


</script>
<script>
	var data = {
	  series: [97, 3]
	};

	var sum = function (a, b) { return a + b };
	new Chartist.Pie('#ct9-chart', data, {
	  labelInterpolationFnc: function (value) {
		return Math.round(value);
	  }
	});
	(function (window, document, $) {
		// Donut chart
		// -----------
		var CurrentBalanceDonutChart = new Chartist.Pie(
			"#current-balance-donut-chart",
			{
				labels: [1, 2],
				series: [{ meta: "", value: {{ 915555 }} }]
			},
			{
				donut: true,
				donutWidth: 8,
				showLabel: false,
				plugins: [
					Chartist.plugins.tooltip({ class: "current-balance-tooltip", appendToBody: true }),
					Chartist.plugins.fillDonut({
						items: [
							{
								content: '<p class="small">{{ "91,55,55" }}'
							}
						]
					})
				]
			}
		);

		var CurrentBalanceDonutChart = new Chartist.Pie(
			"#current-balance-donutone-chart",
			{
				labels: [1, 2],
				series: [{ meta: "", value: {{ 4500 }} }]
			},
			{
				donut: true,
				donutWidth: 8,
				showLabel: false,
				plugins: [
					Chartist.plugins.tooltip({ class: "current-balance-tooltip", appendToBody: true }),
					Chartist.plugins.fillDonut({
						items: [
							{
								content: {{4500}}
							}
						]
					})
				]
			}
		);

		var CurrentBalanceDonutChart = new Chartist.Pie(
			"#current-balance-donuttwo-chart",
			{
				labels: [1, 2],
				series: [{ meta: "", value: {{ 4500 }} }]
			},
			{
				donut: true,
				donutWidth: 8,
				showLabel: false,
				plugins: [
					Chartist.plugins.tooltip({ class: "current-balance-tooltip", appendToBody: true }),
					Chartist.plugins.fillDonut({
						items: [
							{
								content: '<p class="small">'+{{4500}}
							}
						]
					})
				]
			}
		);
   })(window, document, jQuery);
</script>
@endsection