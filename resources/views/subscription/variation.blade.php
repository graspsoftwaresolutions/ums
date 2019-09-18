@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">


@endsection
@section('headSecondSection')
<link href="{{ asset('public/assets/css/jquery-ui-month.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/css/MonthPicker.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/css/sweetalert.css') }}" rel="stylesheet" type="text/css" />
<style>
	table.highlight > tbody > tr
	{
		-webkit-transition: background-color .25s ease;
		   -moz-transition: background-color .25s ease;
			 -o-transition: background-color .25s ease;
				transition: background-color .25s ease;
	}
	table.highlight > tbody > tr:hover
	{
		background-color: rgba(242, 242, 242, .5);
	}
	.monthly-sub-status:hover,.monthly-approval-status:hover,.monthly-company-sub-status:hover,.monthly-company-approval-status:hover{
		background-color: #eeeeee !important;
		cursor:pointer;
	}
	
	.card .card-content {
		padding: 10px;
		border-radius: 0 0 2px 2px;
	}
	.file-path-wrapper{
		//display:none;
	}
	.file-field .btn, .file-field .btn-large, .file-field .btn-small {
		margin-top:10px;
		line-height: 2.4rem;
		float: left;
		height: 2.4rem;
	}
	.bold{
		font-weight: bold;
	}
	.table-footer {
		cursor:pointer;
		background: #dbdbf7;font-weight:bold;
	}
</style>
@endsection
@section('main-content')
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
	<div class="row">
		<div class="col s12">
			<div class="container">
				<div class="card">
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
									<form class="formValidate" id="subscribe_formValidate" method="post" action="{{ url(app()->getLocale().'/subscription_variation') }}" enctype="multipart/form-data">
										@csrf
										<div class="row">
											
											<div class="col m3 s12">
												<label for="doe">{{__('Group By') }}*</label>
												<p>
													<label>
														<input name="groupby" type="radio" value="1" {{ $data['groupby']==1 ? 'checked' : ''}} />
														<span>{{__('Union Branch') }} </span>
													</label>
												</p>
												<p>
													<label>
														<input name="groupby" type="radio" value="2"  {{ $data['groupby']==2 ? 'checked' : ''}} />
														<span>Bank </span>
													</label>
												</p>
												<p>
													<label>
														<input name="groupby" type="radio" value="3"  {{ $data['groupby']==3 ? 'checked' : ''}} />
														<span>Bank Branch</span>
													</label>
												</p>
											</div>
											<div class="input-field col m3 s12">
												<label for="doe">{{__('Subscription Month') }}*</label>
												<input type="text" name="entry_date" id="entry_date" value="{{ $data['month_year'] }}" class="datepicker-custom" />
											</div>
											<div class="col m3 s12">
												<label for="doe">&nbsp;</label>
												<p>
													<label>
														<input name="display_subs" type="checkbox" value="1" {{ $data['DisplaySubscription']==true ? 'checked' : ''}} />
														<span>{{__('Display Subscription') }} </span>
													</label>
												</p>
												<p style="padding-top:10px;">
													<label>
														<input name="6month-variation" type="checkbox" value="1" {{ $data['DisplaySubscription']==true ? 'checked' : ''}} />
														<span>{{__('Last 6 Months Variation') }} </span>
													</label>
												</p>
											</div>
											
											
											
											<div class="col m3 s12 " style="padding-top:5px;">
												</br>
												<button id="submit-upload" class="mb-6 btn waves-effect waves-light purple lightrn-1 form-download-btn" type="submit">{{__('Submit') }}</button>
												
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
	</div>
	<div class="row">
		<div class="col s12">  
			<div class="container">
				<div class="card">
					<div class="card-content">
						<h4 class="card-title">Subscription variation
						<div class="right">
							<a class="btn waves-effect waves-light cyan  " target="_blank" href="{{ URL::to(app()->getLocale().'/subscription-variation?date='.strtotime($data['month_year_full']).'&groupby='.$data['groupby'].'&display_subs='.$data['DisplaySubscription'].'&print=1') }}" >{{__('Print')}}</a>
							<a class="btn waves-effect waves-light hide" style="background:#ff0000;" href="{{ URL::to(app()->getLocale().'/subscription-variation?date='.strtotime($data['month_year_full']).'&groupby='.$data['groupby'].'&display_subs='.$data['DisplaySubscription'].'&print=') }}"style="padding-right:10px;">{{__('PDF')}}</a>
						</div>
						</h4>
					</div>
					<div class="card-body">
						@if($data['groupby']==1)
						<table id="page-length-option" class="display" width="100%">
							<thead>
								<tr class="" >
									<th>{{__('Union Branch Name')}}</th>
									<th>{{__('#Current')}}</th>
									<th>{{__('#Previous')}}</th>
									<th>{{__('Different')}}</th>
									<th>{{__('Unpaid')}}</th>
									<th>{{__('Paid')}}</th>
								</tr>
							</thead>
							<tbody class="tbody-area" width="100%">
								@php
									$totalno21=0;
									$totalno22=0;
									$totalno23=0;
									$totalno24=0;
									$totalno25=0;
								@endphp
								@foreach($data['union_branch_view'] as $union)
										@php
											$current_count = CommonHelper::getMonthEndPaidCount($union->union_branchid,$data['month_year_full'],1);
											$last_month_count = CommonHelper::getUnionMonthlyPaidCount($union->union_branchid,$data['last_month_year']);
											$last_paid_count = CommonHelper::getUnionLastMonthlyPaidCount($union->union_branchid,$data['month_year_full']);
											$current_unpaid_count = CommonHelper::getUnioncurrentMonthlyPaidCount($union->union_branchid,$data['month_year_full']);
										@endphp
										<tr class="monthly-sub-status">
											<td style="width:50%">{{ $union->union_branch_name }}</td>
											<td style="width:10%">{{ $current_count }}</td>
											<td style="width:10%">{{ $last_month_count }}</td>
											<td style="width:10%"><span style="color:#fff;" class="badge {{$current_count-$last_month_count>=0 ? 'green' : 'red'}}">{{ $current_count-$last_month_count }}</span></td>
											<td style="width:10%">{{ $current_unpaid_count }}</td>
											<td style="width:10%">{{ $last_paid_count }}</td>
										</tr> 
										@php
											$totalno21 += $current_count;
											$totalno22 += $last_month_count;
											$totalno24 += $current_unpaid_count;
											$totalno25 += $last_paid_count;
										@endphp
										
								@endforeach
								<tr class="bold table-footer">
									<td style="width:50%">Total</td>
									<td style="width:10%">{{ $totalno21 }}</td>
									<td style="width:10%">{{ $totalno22 }}</td>
									<td style="width:10%">--</td>
									<td style="width:10%">{{ $totalno24 }}</td>
									<td style="width:10%">{{ $totalno25 }}</td>
								</tr> 
							</tbody>
							
						</table>
						@elseif($data['groupby']==2)
						<table id="page-length-option" class="display" width="100%">
							<thead>
								<tr class="" >
									<th>{{__('Bank Name')}}</th>
									<th>{{__('#Current')}}</th>
									<th>{{__('#Previous')}}</th>
									<th>{{__('Different')}}</th>
									<th>{{__('Unpaid')}}</th>
									<th>{{__('Paid')}}</th>
								</tr>
							</thead>
							<tbody class="tbody-area" width="100%">
								@php
									$totalno11=0;
									$totalno12=0;
									$totalno13=0;
									$totalno14=0;
									$totalno15=0;
								@endphp
								@foreach($data['company_view'] as $company)
										@php
											$current_count = CommonHelper::getMonthEndPaidCount($company->company_id,$data['month_year_full'],2);
											//$current_count = CommonHelper::getMonthlyPaidCount($company->company_id,$data['month_year_full']);
											$last_month_count = CommonHelper::getMonthlyPaidCount($company->company_id,$data['last_month_year']);
											$last_paid_count = CommonHelper::getLastMonthlyPaidCount($company->company_id,$data['month_year_full']);
											$current_unpaid_count = CommonHelper::getcurrentMonthlyPaidCount($company->company_id,$data['month_year_full']);
										@endphp
										<tr class="monthly-sub-status">
											<td style="width:50%">{{ $company->company_name }}</td>
											<td style="width:10%">{{ $current_count }}</td>
											<td style="width:10%">{{ $last_month_count }}</td>
											<td style="width:10%"><span style="color:#fff;" class="badge {{$current_count-$last_month_count>=0 ? 'green' : 'red'}}">{{ $current_count-$last_month_count }}</span></td>
											<td style="width:10%">{{ $current_unpaid_count }}</td>
											<td style="width:10%">{{ $last_paid_count }}</td>
										</tr> 
										@php
											$totalno11 += $current_count;
											$totalno12 += $last_month_count;
											$totalno13 += $current_count-$last_month_count;
											$totalno14 += $current_unpaid_count;
											$totalno15 += $last_paid_count;
										@endphp
										
								@endforeach
								<tr class="bold table-footer">
									<td style="width:50%">Total</td>
									<td style="width:10%">{{ $totalno11 }}</td>
									<td style="width:10%">{{ $totalno12 }}</td>
									<td style="width:10%">--</td>
									<td style="width:10%">{{ $totalno14 }}</td>
									<td style="width:10%">{{ $totalno15 }}</td>
								</tr> 
							</tbody>
							
						</table>
						@elseif($data['groupby']==3)
						<table id="page-length-option" class="display" width="100%">
							<thead>
								<tr class="" >
									<th>{{__('Bank Name')}}</th>
									<th>{{__('Branch Name')}}</th>
									<th>{{__('#Current')}}</th>
									<th>{{__('#Previous')}}</th>
									<th>{{__('Different')}}</th>
									<th>{{__('Unpaid')}}</th>
									<th>{{__('Paid')}}</th>
								</tr>
							</thead>
							<tbody class="tbody-area" width="100%">
								@php
									$totalno1=0;
									$totalno2=0;
									$totalno3=0;
									$totalno4=0;
									$totalno5=0;
									//dd($data['month_year_full'])
								@endphp
								@foreach($data['branch_view'] as $branch)
										@php
											$current_count = CommonHelper::getMonthEndPaidCount($branch->branch_id,$data['month_year_full'],3);
											$last_month_count = CommonHelper::getBranchMonthlyPaidCount($branch->branch_id,$data['last_month_year']);
											$last_paid_count = CommonHelper::getBranchLastMonthlyPaidCount($branch->branch_id,$data['month_year_full']);
											$current_unpaid_count = CommonHelper::getBranchcurrentMonthlyPaidCount($branch->branch_id,$data['month_year_full']);
										@endphp
										<tr class="monthly-sub-status">
											<td style="width:20%">{{ $branch->company_name }}</td>
											<td style="width:30%">{{ $branch->branch_name }}</td>
											<td style="width:10%">{{ $current_count }}</td>
											<td style="width:10%">{{ $last_month_count }}</td>
											<td style="width:10%"><span style="color:#fff;" class="badge {{$current_count-$last_month_count>=0 ? 'green' : 'red'}}">{{ $current_count-$last_month_count }}</span></td>
											<td style="width:10%">{{ $current_unpaid_count }}</td>
											<td style="width:10%">{{ $last_paid_count }}</td>
										</tr> 
										@php
											$totalno1 += $current_count;
											$totalno2 += $last_month_count;
											$totalno3 += 0;
											$totalno4 += $current_unpaid_count;
											$totalno5 += $last_paid_count;
										@endphp
								@endforeach
								<tr class="bold table-footer">
									<td colspan="2" style="width:50%">Total</td>
									<td>{{ $totalno1 }}</td>
									<td>{{ $totalno2 }}</td>
									<td>--</td>
									<td>{{ $totalno4 }}</td>
									<td>{{ $totalno5 }}</td>
								</tr> 
							</tbody>
							
						</table>
						@endif
					</div> 
				</div> 
			</div> 
		</div> 
	</div>
	
</div>

@endsection
@section('footerSection')
<script src="{{ asset('public/assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>

<script src="{{ asset('public/assets/vendors/noUiSlider/nouislider.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/materialize.min.js') }}"></script>
<script src="{{ asset('public/assets/js/scripts/form-elements.js') }}" type="text/javascript"></script>

@endsection
@section('footerSecondSection')
<script src="{{ asset('public/assets/js/scripts/form-validation.js')}}" type="text/javascript"></script>

 <script src="{{ asset('public/assets/js/jquery-ui-month.min.js')}}"></script>
 <script src="{{ asset('public/js/MonthPicker.min.js')}}"></script>
 <script src="{{ asset('public/js/sweetalert.min.js')}}"></script>

<script>
$(document).ready(function() {
    // the "href" attribute of the modal trigger must specify the modal ID that wants to be triggered
    $('.modal').modal();
});

     $(document).ready(function(){
	 $('.datepicker-custom').MonthPicker({ 
		Button: false, 
		changeYear: true,
		MonthFormat: 'M/yy',
		OnAfterChooseMonth: function() { 
			//getDataStatus();
		} 
	 });
	 $('.ui-button').removeClass("ui-state-disabled");
		 //$('.datepicker-custom').MonthPicker({ Button: false,dateFormat: 'M/yy' });
       
    });
	
	$("#subscribe_formValidate").validate({
        rules: {
				entry_date:{
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
	
	$(document).on('change','#entry_date',function(){
		//getDataStatus();
	});
	
	$(document).on('submit','form#subscribe_formValidate',function(){
		
		//$("#submit-download").prop('disabled',true);
	});
	
	
	$("#subscriptions_sidebars_id").addClass('active');
	$("#subvariation_sidebar_li_id").addClass('active');
	$("#subvariation_sidebar_sidebar_a_id").addClass('active');
</script>
@endsection