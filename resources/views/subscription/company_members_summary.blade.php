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
				                
				                  <h6 class="collection-header m-20" style="margin: 10px 10px;color: #fff;">Subscription Summary</h6>
				                  <p></p>
				               </li>
				               <li class="collection-item">
				                  <div class="row">
				                     <div class="col s6">
				                        <p class="collections-title">Total Members Count</p>
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
				                        <p class="collections-title">Total Uploaded Members Count</p>
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
				                        <p class="collections-title">Uploaded Members Matched Count</p>
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
				                        <p class="collections-title">Uploaded Amount</p>
				                        <p class="collections-content"></p>
				                     </div>
				                     <div class="col s3"><p class="collections-title"><span class="task-cat deep-orange accent-2">{{ $data['matched_amount'] }}</span></p></div>
				                     <div class="col s3">
				                        <div id="project-line-4"></div>
				                     </div>
				                  </div>
				               </li>
				               <li class="collection-item">
				                  <div class="row">
				                     <div class="col s6">
				                        <p class="collections-title">Uploaded Not Matched Count</p>
				                        <p class="collections-content"></p>
				                     </div>
				                     <div class="col s3"><p class="collections-title"><span class="task-cat deep-orange accent-2">{{ $data['company_subscription_list']-$data['matched_count'] }}</span> </p></div>
				                     <div class="col s3">
				                       <a style="" target="_blank" href="{{ URL::to(app()->getLocale().'/summary-status?status=0&date='.strtotime('now').'&company_id='.$data['company_auto_id']) }}" title="View Members" class="waves-effect waves-light blue btn btn-sm" href="">View</a>
				                     </div>
				                  </div>
				               </li>
				            </ul>
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

<script src="{{ asset('public/assets/vendors/data-tables/js/jquery.dataTables.min.js') }}" type="text/javascript">
</script>
<script src="{{ asset('public/assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js') }}"
type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/data-tables/js/dataTables.select.min.js') }}" type="text/javascript">
</script>
@endsection
@section('footerSecondSection')
<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/assets/js/scripts/form-validation.js')}}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/scripts/data-tables.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jquery.autocomplete.min.js') }}" type="text/javascript"></script>
<script>
$("#subscriptions_sidebars_id").addClass('active');
$("#subcomp_sidebar_li_id").addClass('active');
$("#subcomp_sidebar_a_id").addClass('active');
//Model


</script>
@endsection