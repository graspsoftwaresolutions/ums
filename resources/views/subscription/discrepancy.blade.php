@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">


@endsection
@section('headSecondSection')
<link href="{{ asset('public/assets/css/jquery-ui-month.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/css/MonthPicker.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/css/sweetalert.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ asset('public/assets/js/jquery.min.js') }}"></script>
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
	td, th {
	    display: table-cell;
	    padding: 4px 1px;
	    text-align: left;
	    vertical-align: middle;
	    border-radius: 2px;
	}
	[type='checkbox'] + span:not(.lever) {
	    font-size: 1rem;
	    line-height: 20px;
	    position: relative;
	    display: inline-block;
	    height: 15px;
	    padding-left: 35px;
	    cursor: pointer;
	    -webkit-user-select: none;
	    -moz-user-select: none;
	    -ms-user-select: none;
	    user-select: none;
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
									<h5 class="breadcrumbs-title mt-0 mb-0">{{__('Subscription Discrepancy List') }}</h5>
									<ol class="breadcrumbs mb-0">
										<ol class="breadcrumbs mb-0">
											<li class="breadcrumb-item"><a
													href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard') }}</a>
											</li>
											<li class="breadcrumb-item active">{{__('Subscription Discrepancy') }}
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
	@php
		$companylist = CommonHelper::getHeadCompanyListAll();
		$unionbranchlist = CommonHelper::getUnionListAll();
	@endphp
	<form class="formValidate" id="subscribe_formValidate" method="post" action="{{ url(app()->getLocale().'/subscription_discrepancy') }}" enctype="multipart/form-data">
	@csrf
	<div class="row">
		<div class="col s12">
			 @include('includes.messages')
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
									
										<div class="row">
											
											<div class="col m2 s12">
												<label for="doe">{{__('Group By') }}*</label>
												<p>
													<label>
														<input name="groupby" onclick="return ViewLists(1)" type="radio" value="1" {{ $data['groupby']==1 ? 'checked' : ''}} />
														<span>{{__('Union Branch') }} </span>
													</label>
												</p>
												<p>
													<label>
														<input name="groupby" onclick="return ViewLists(2)" type="radio" value="2"  {{ $data['groupby']==2 ? 'checked' : ''}} />
														<span>Bank </span>
													</label>
												</p>
												<p>
													<label>
														<input name="groupby" onclick="return ViewLists(2)" type="radio" value="3"  {{ $data['groupby']==3 ? 'checked' : ''}} />
														<span>Bank Branch</span>
													</label>
												</p>
											</div>
											<div class="col m3 s12">
												<div id="banksection" class="{{ $data['groupby']==1 ? 'hide' : '' }}">
	                                                <label for="sub_company">{{__('Company') }}</label>
	                                                <select name="sub_company" id="sub_company" class="error browser-default selectpicker" data-error=".errorTxt6">
	                                                    <option value="" selected>{{__('Choose Company') }}</option>
	                                                    @foreach($companylist as $value)
	                                                    <option {{ $data['sub_company']==$value->id ? 'selected' : ''}} data-companyname="{{$value->company_name}}" value="{{$value->id}}">{{$value->company_name}}</option>
	                                                    @endforeach
	                                                </select>
	                                            </div>
	                                            <div id="unionsection" class="{{ $data['groupby']>1 ? 'hide' : '' }}">
	                                               <label>{{__('Union Branch Name') }}</label>
													<select name="unionbranch_id" id="unionbranch_id" class="error browser-default selectpicker" data-error=".errorTxt22" >
														<option value="">{{__('Select Union') }}</option>
														@foreach($unionbranchlist as $value)
						                                <option {{ $data['unionbranch_id']==$value->id ? 'selected' : ''}}  value="{{$value->id}}">
						                                    {{$value->union_branch}}</option>
						                                @endforeach
													</select>
	                                            </div>
                                                <div class="errorTxt6"></div>
                                            </div>
											<div class="input-field col m1 s12">
												<label style="left: 1rem;" for="doe">{{__('Month') }}*</label>
												<input type="text" name="entry_date" id="entry_date" value="{{ $data['month_year'] }}" class="datepicker-custom" />
											</div>
											<div class="col m1 s12 ">
												<label for="doe">&nbsp;</label>
												<p>
													<label>
														<input name="display_subs" type="checkbox" value="1" {{ $data['DisplaySubscription']==true ? 'checked' : ''}} />
														<span style="font-size: 12px;">{{__('Display') }} 
														<br>
														Subscription
														</span>
													</label>
												</p>
											</div>
											<div class="col m2 s12 ">
												
												<label for="doe">{{__('Variation') }}</label>
												<p>
													<label>
														<input name="variationtype" class="variationtype" type="radio" value="4" {{ $data['variationtype']==4 ? 'checked' : ''}} />
														<span style="font-size: 12px;">{{__('Last 4 Months') }} </span>
													</label>
												</p>
												<p>
													<label>
														<input name="variationtype" class="variationtype" type="radio" value="6"  {{ $data['variationtype']==6 ? 'checked' : ''}} />
														<span style="font-size: 12px;">{{__('Last 6 Months') }} </span>
													</label>
												</p>

												
											</div>
											<div class="col m2 s12 ">
												<label for="types">{{__('Increment Types') }}</label>
												<select name="types" id="types" class="browser-default valid" aria-invalid="false">
						                            <option {{ $data['types']=='' ? 'selected' : ''}} value="">Select</option>
						                            @foreach($data['inctypes'] as $type)
									                	<option {{ $data['types']==$type->id ? 'selected' : ''}} value="{{$type->id}}">{{$type->type_name}}</option>
									                @endforeach
						                           
						                        </select>
											</div>
											
											
											<div class="col m1 s12 " style="padding-top:5px;">
												</br>
												<button id="submit-upload" style="margin: 0;padding: 1px 10px;line-height: 30px;" class="mb-6 btn waves-effect waves-light purple lightrn-1 form-download-btn" type="submit">{{__('Submit') }}</button>
												
											</div>
											
										</div>
										
									
									
								</div>
							</div>
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</form>
	<form class="formValidate" id="update_formValidate" method="post" action="{{ url(app()->getLocale().'/update_discrepancy') }}" enctype="multipart/form-data">
	@csrf
	<div class="row">
		<div class="col s12">  
			<div class="container">
				<div class="card">
					<div class="card-content">
						<h4 class="card-title">Subscription Discrepancy
						<button id="submit-upload" style="margin-left: 50px; " class="btn waves-effect waves-light purple lightrn-1 form-download-btn center" type="submit">{{__('Update Salary') }}</button>
						<div class="right">
							<a id="printdiscrepancy" class="btn waves-effect waves-light cyan  " target="_blank" href="{{ URL::to(app()->getLocale().'/discrepancy-print?date='.strtotime($data['month_year_full']).'&groupby='.$data['groupby'].'&display_subs='.$data['DisplaySubscription'].'&print=1&variation='.$data['variationtype'].'&inctype='.$data['types'].'&sub_company='.$data['sub_company'].'&unionbranch_id='.$data['unionbranch_id']) }}" >{{__('Print')}}</a>
							<a class="btn waves-effect waves-light hide" style="background:#ff0000;" href="{{ URL::to(app()->getLocale().'/subscription-variation?date='.strtotime($data['month_year_full']).'&groupby='.$data['groupby'].'&display_subs='.$data['DisplaySubscription'].'&print=') }}"style="padding-right:10px;">{{__('PDF')}}</a>

							<a id="exceldiscrepancy" class="btn waves-effect waves-light cyan orange " href="{{ URL::to(app()->getLocale().'/discrepancy-excel?date='.strtotime($data['month_year_full']).'&groupby='.$data['groupby'].'&display_subs='.$data['DisplaySubscription'].'&print=1&variation='.$data['variationtype'].'&inctype='.$data['types'].'&sub_company='.$data['sub_company'].'&unionbranch_id='.$data['unionbranch_id']) }}" >{{__('Excel')}}</a>
						</div>
						</h4>
					</div>
					<div class="card-body">
		@php
			//dd($data);
			if($data['groupby']==1){
				$memberslist = $data['union_branch_view'];
			}elseif($data['groupby']==2){
				$memberslist = $data['company_view'];
			}else{
				$memberslist = $data['branch_view'];
			}
			$overall_total_fifth_new=0;
			$overall_total_fourth_new=0;
			$overall_total_third_new=0;
			$overall_total_second_new=0;
			$overall_total_last_new=0;
			$overall_total_this_new=0;
			
			$overall_total_resigned=0;
			$overall_total_fifth_unpaid=0;
			$overall_total_fourth_unpaid=0;
			$overall_total_third_unpaid=0;
			$overall_total_second_unpaid=0;
			$overall_total_last_unpaid=0;
			$overall_total_this_unpaid=0;
			
			$overall_total_fifth_inc=0;
			$overall_total_fouth_inc=0;
			$overall_total_third_inc=0;
			$overall_total_second_inc=0;
			$overall_total_last_inc=0;
			$overall_total_this_inc=0;
			
			$overall_total_fifth_dec=0;
			$overall_total_fouth_dec=0;
			$overall_total_third_dec=0;
			$overall_total_second_dec=0;
			$overall_total_last_dec=0;
			$overall_total_this_dec=0;
			$overall_no_diff=0;
			//dd($memberslist);
	@endphp
	<input name="disgroupby" id="disgroupby" type="text" class="hide" value="{{ $data['groupby'] }}" />
	<input type="text" name="disentry_date" id="disentry_date" value="{{ $data['month_year'] }}" class="hide" />
	@php
		if($data['groupby']==1){
			if($data['unionbranch_id']!=''){
				foreach($unionbranchlist as $union){
					if($union->id==$data['unionbranch_id']){
						$membertypelist[] = $union;
					}
				}
			}else{
				$membertypelist = $unionbranchlist;
			}
		}
		else if($data['groupby']==2){
			if($data['sub_company']!=''){
				foreach($companylist as $cmp){
					if($cmp->id==$data['sub_company']){
						$membertypelist[] = $cmp;
					}
				}
			}else{
				$membertypelist = $companylist;
			}
			
		}
		else{
			$membertypelist = $data['branch_view'];
		}
		
	@endphp
	
		@foreach($membertypelist as $type)
			@php
				if($data['groupby']==3){
					$typeidref = $type->branch_id;
					$typeid = $type->branch_id;
				}else{
					$typeidref = $type->id;
					$typeid = $type->id;
				}
				
				$companymembers = CommonHelper::getSubscriptionBankMembers($data['groupby'], $typeidref,$data['month_year_first'],$data['month_year_full']);
				//dd(count($companymembers));
				$count=1;
				$total_fifth_new=0;
				$total_fourth_new=0;
				$total_third_new=0;
				$total_second_new=0;
				$total_last_new=0;
				$total_resigned=0;
				$total_fifth_unpaid=0;
				$total_fourth_unpaid=0;
				$total_third_unpaid=0;
				$total_second_unpaid=0;
				$total_last_unpaid=0;
				$total_this_unpaid=0;
				$total_fifth_inc=0;
				$total_fouth_inc=0;
				$total_third_inc=0;
				$total_second_inc=0;
				$total_last_inc=0;
				$total_fifth_dec=0;
				$total_fouth_dec=0;
				$total_third_dec=0;
				$total_second_dec=0;
				$total_last_dec=0;
				$total_this_inc=0;
				$total_this_dec=0;
				$total_fifth_diff=0;
				$total_fourth_diff=0;
				$total_third_diff=0;
				$total_second_diff=0;
				$total_last_diff=0;
				$total_this_diff=0;
				$no_diff=0;
				$no_diff_fifth=0;
				$no_diff_fourth=0;
				$no_diff_third=0;
				$no_diff_second=0;
				$no_diff_last=0;
				$no_diff_this=0;
				//dd($companymembers);
			@endphp
			@if(count($companymembers)>0)
			<div id="companyheading_{{ $typeidref }}" class="row tablesall">
				<br>

				<div class="col m2" style="font-weight: bold;">
					@if($data['groupby']==1)
						{{ $type->union_branch }}
					@elseif($data['groupby']==2)
						{{ $type->company_name }}
					@else
						{{ $type->company_name }} - {{ $type->branch_name }}
					@endif
				</div>
				<div class="col m2">
					<input type="text" name="disrefid[]" id="disrefid" value="{{ $typeidref }}" class="hide" />
					<select name="inctype_{{ $typeidref }}" id="inctype_{{ $typeidref }}" onchange="return HideOthers{{ $typeidref }}(this.value)" class="browser-default valid" aria-invalid="false">
		                <option value="">Select</option>
		                @foreach($data['inctypes'] as $type)
		                	<option value="{{$type->id}}">{{$type->type_name}}</option>
		                @endforeach
		            </select>
				</div>
				<div id="othersdiv_{{ $typeidref }}" class="col m2 hide">
					<input type="text" name="others_{{ $typeidref }}" id="others" value="" class="" />
				</div>
			</div>
			<table id="page-length-option-{{ $typeidref }}" class="display tablesall" width="100%">
				<thead>
					
					<tr class="title-area" >
						

						
					</tr>
					<tr class="table-title">
						<th><p style="margin-left: 10px; "><label><input class="checkall_{{ $typeidref }}" id="checkAll_{{ $typeidref }}" type="checkbox" /> <span>Check All</span> </label> </p></th>
						<th>{{__('M.No')}}</th>
						<th>{{__('Member Name')}}</th>
						<th>{{__('Joining')}}</th>
						<th>{{__('Last Paid')}}</th>
						<th>{{__('Subs')}}</th>
						@if($data['variationtype']==6)
						<th>{{ date('M Y',strtotime($data['month_year_full'].' -5 Month')) }}</th>
						<th>{{ date('M Y',strtotime($data['month_year_full'].' -4 Month')) }}</th>
						@endif
						<th>{{ date('M Y',strtotime($data['month_year_full'].' -3 Month')) }}</th>
						<th>{{ date('M Y',strtotime($data['month_year_full'].' -2 Month')) }}</th>
						<th>{{ date('M Y',strtotime($data['month_year_full'].' -1 Month')) }}</th>
						<th>{{ date('M Y',strtotime($data['month_year_full'])) }}</th>
						<th class="hide">Remarks</th>
						
					</tr>
				</thead>
				<tbody class="tbody-area">
					@foreach($companymembers as $member)
					@php
						//$lastpaiddate = CommonHelper::getLastPaidDate($member->member_id);
						$salary = $member->salary==Null ? 0 : $member->salary;
				
						$bf_amt = 3;
						$ins_amt = 7;
						

						$doj_str = date('Y-m-01',strtotime($member->doj));
						$fifth_str = date('Y-m-01',strtotime($data['month_year_full'].' -5 Month'));
						$fourth_str = date('Y-m-01',strtotime($data['month_year_full'].' -4 Month'));
						$third_str = date('Y-m-01',strtotime($data['month_year_full'].' -3 Month'));
						$second_str = date('Y-m-01',strtotime($data['month_year_full'].' -2 Month'));
						$last_str = date('Y-m-01',strtotime($data['month_year_full'].' -1 Month'));
						$this_str = date('Y-m-01',strtotime($data['month_year_full']));

						//print_r($member);

						if($data['variationtype']==6){
							$updated_salary = CommonHelper::getIncrementValue($member->member_id,$this_str,$fifth_str);
						}else{
							$updated_salary = CommonHelper::getIncrementValue($member->member_id,$this_str,$third_str);

						}
						$subremarks = '';
						$addonsalary = 0;
						if($data['types'] != ''){
							$displaymember = 0;
						}else{
							$displaymember = 1;
						}
						
						if(!empty($updated_salary)){
							//dd($updated_salary);
							$newbasicsal = $salary;
							foreach($updated_salary as $key => $upsalary){

								if($upsalary->date==$this_str){
									if($upsalary->increment_type_id==4){
										$addonsalary -= $upsalary->additional_amt;
									}else{
										$addonsalary += $upsalary->additional_amt;
									}

									$inctype = CommonHelper::getIncrementTypeName($upsalary->increment_type_id);
									if($key!=0){
										$subremarks .= ', ';
									}
									if($key==0){
										$newbasicsal = $upsalary->basic_salary;
									}
									if($data['types'] != '' && $data['types'] == $upsalary->increment_type_id){
										$displaymember = 1;
									}
									$subremarks .= $inctype;
								}else{
									if($upsalary->increment_type_id==1 || $upsalary->increment_type_id==4){
										if($upsalary->increment_type_id==4){
											$addonsalary -= $upsalary->additional_amt;
										}else{
											$addonsalary += $upsalary->additional_amt;
										}

										$inctype = CommonHelper::getIncrementTypeName($upsalary->increment_type_id);
										if($key!=0){
											$subremarks .= ', ';
										}
										if($key==0){
											$newbasicsal = $upsalary->basic_salary;
										}
										if($data['types'] != '' && $data['types'] == $upsalary->increment_type_id){
											$displaymember = 1;
										}
										$subremarks .= $inctype;
									}
								}
								
							}
							$newsalary = $newbasicsal+$addonsalary;

							$total_subs = ($newsalary*1)/100;
							$payable_subs = $total_subs;

							//$payable_subs = number_format($total_subs,2,".","");

						}else{
							$total_subs = ($salary*1)/100;
							$payable_subs = $total_subs;
						}
						if($data['variationtype']==6){
							//$fifthmonth = date('Y-m-d',strtotime($data['month_year_full'].' -5 Month'));
							//$forthmonth = date('Y-m-d',strtotime($data['month_year_full'].' -4 Month'));
							
							$fifth_amt = $member->pay_date == $fifth_str ? $member->SUBSCRIPTION_AMOUNT : CommonHelper::getCompanyPaidSubs($typeid, $member->member_id, $fifth_str);
							$fourth_amt = $member->pay_date == $fourth_str ? $member->SUBSCRIPTION_AMOUNT : CommonHelper::getCompanyPaidSubs($typeid, $member->member_id, $fourth_str);
						}
						
						//$thirdmonth = date('Y-m-d',strtotime($data['month_year_full'].' -3 Month'));
						//$secondmonth = date('Y-m-d',strtotime($data['month_year_full'].' -2 Month'));
						//$lastmonth = date('Y-m-d',strtotime($data['month_year_full'].' -1 Month'));

						$third_amt = $member->pay_date == $third_str ? $member->SUBSCRIPTION_AMOUNT : CommonHelper::getCompanyPaidSubs($typeid, $member->member_id, $third_str);
						//dd($third_amt);
						$second_amt = $member->pay_date == $second_str ? $member->SUBSCRIPTION_AMOUNT : CommonHelper::getCompanyPaidSubs($typeid, $member->member_id, $second_str);
						$last_amt = $member->pay_date == $last_str ? $member->SUBSCRIPTION_AMOUNT : CommonHelper::getCompanyPaidSubs($typeid, $member->member_id, $last_str);
						$this_paid = $member->pay_date == $data['month_year_full'] ? $member->SUBSCRIPTION_AMOUNT : CommonHelper::getCompanyPaidSubs($typeid, $member->member_id,$data['month_year_full']);
						if($this_paid==Null || $this_paid==0){
							$this_paid = '*';
						}
						if($data['variationtype']==6){
							$fifth_paid_status = $fifth_amt;
							$fourth_paid_status = $fourth_amt;
						}
						$third_paid_status = $third_amt;
						$second_paid_status = $second_amt;
						$last_paid_status = $last_amt;

						$variedamt = 0;

						if($data['variationtype']==6){
							//print_r($fifth_str);
							//print_r($doj_str);
							if($fifth_amt!=$payable_subs || $fourth_amt!=$payable_subs || $third_amt!=$payable_subs || $second_amt!=$payable_subs || $last_amt!=$payable_subs || $this_paid!=$payable_subs){
								$variedamt = 1;
								if($this_str==$doj_str)
								{
									$variedamt = 0;
								}
								elseif($last_str==$doj_str)
								{
									if($last_paid_status==$this_paid)
									{
										$variedamt = 0;
									}else{
										$variedamt = 1;
									}
									
								}elseif($second_str==$doj_str){
									if($second_paid_status==$last_paid_status && $last_paid_status==$this_paid)
									{
										$variedamt = 0;
									}else{
										$variedamt = 1;
									}
									
								}elseif($third_str==$doj_str){
									if($third_paid_status==$second_paid_status && $second_paid_status==$last_paid_status && $last_paid_status==$this_paid)
									{
										$variedamt = 0;
									}else{
										$variedamt = 1;
									}
								}elseif($fourth_str==$doj_str){
									if($fourth_paid_status==$third_paid_status && $third_paid_status==$second_paid_status && $second_paid_status==$last_paid_status && $last_paid_status==$this_paid)
									{
										$variedamt = 0;
									}else{
										$variedamt = 1;
									}
								}elseif($fifth_str==$doj_str){
									if($fifth_paid_status==$fourth_paid_status && $fourth_paid_status==$third_paid_status && $third_paid_status==$second_paid_status && $second_paid_status==$last_paid_status && $last_paid_status==$this_paid)
									{
										$variedamt = 0;
									}else{
										$variedamt = 1;
									}
								}
								
							}
						}else{

							if($third_amt!=$payable_subs || $second_amt!=$payable_subs || $last_amt!=$payable_subs || $this_paid!=$payable_subs){	
								$variedamt = 1;
								
								if($this_str==$doj_str)
								{
									$variedamt = 0;
								}
								elseif($last_str==$doj_str)
								{
									if($last_paid_status==$this_paid)
									{
										$variedamt = 0;
									}else{
										$variedamt = 1;
									}
									
								}elseif($second_str==$doj_str){
									if($second_paid_status==$last_paid_status && $last_paid_status==$this_paid)
									{
										$variedamt = 0;
									}else{
										$variedamt = 1;
									}
									
								}elseif($third_str==$doj_str){
								//dd($third_amt);
									if($third_paid_status==$second_paid_status && $second_paid_status==$last_paid_status && $last_paid_status==$this_paid)
									{
										$variedamt = 0;
									}else{
										$variedamt = 1;
									}
								}
							}
						}
					if($variedamt && $displaymember==1){
						if($data['variationtype']==6){
							if($fifth_amt=='*'){
								$fifth_paid_status = CommonHelper::getMonthDifference(date('Y-m-d',strtotime($data['month_year_full'].' -5 Month')), $member->doj)>0 ? 'N' : '*';
								if($fifth_paid_status=='N'){
									$total_fifth_new++;
								}else{
									$total_fifth_unpaid++;
								}
							}else{
								$total_fifth_diff = $payable_subs-$fifth_amt;
								if($total_fifth_diff>0){
									$total_fifth_dec++;
								}
								if($total_fifth_diff<0){
									$total_fifth_inc++;
								}
							}
							if($fourth_amt=='*'){
								$fourth_paid_status = CommonHelper::getMonthDifference(date('Y-m-d',strtotime($data['month_year_full'].' -4 Month')), $member->doj)>0 ? 'N' : '*';
								if($fourth_paid_status=='N'){
									$total_fourth_new++;
								}else{
									$total_fourth_unpaid++;
								}
							}else{
								$total_fourth_diff = $payable_subs-$fourth_amt;
								if($total_fourth_diff>0){
									$total_fouth_dec++;
								}
								if($total_fourth_diff<0){
									$total_fouth_inc++;
								}
							}
						}
						if($third_amt=='*'){
							$third_paid_status = CommonHelper::getMonthDifference(date('Y-m-d',strtotime($data['month_year_full'].' -3 Month')), $member->doj)>0 ? 'N' : '*';
							if($third_paid_status=='N'){
								$total_third_new++;
							}else{
								$total_third_unpaid++;
							}
						}else{
							$total_third_diff = $payable_subs-$third_amt;
							if($total_third_diff>0){
								$total_third_dec++;
							}
							if($total_third_diff<0){
								$total_third_inc++;
							}
						}
						if($second_amt=='*'){
							$second_paid_status = CommonHelper::getMonthDifference(date('Y-m-d',strtotime($data['month_year_full'].' -2 Month')), $member->doj)>0 ? 'N' : '*';
							if($second_paid_status=='N'){
								$total_second_new++;
							}else{
								$total_second_unpaid++;
							}
						}else{
							$total_second_diff = $payable_subs-$second_amt;
							if($total_second_diff>0){
								$total_second_dec++;
							}
							if($total_second_diff<0){
								$total_second_inc++;
							}
						}
						if($last_amt=='*'){
							$last_paid_status = CommonHelper::getMonthDifference(date('Y-m-d',strtotime($data['month_year_full'].' -1 Month')), $member->doj)>0 ? 'N' : '*';
							if($last_paid_status=='N'){
								$total_last_new++;
							}else{
								$total_last_unpaid++;
							}
						}else{
							$total_last_diff = $payable_subs-$last_amt;
							if($total_last_diff>0){
								$total_last_dec++;
							}
							if($total_last_diff<0){
								$total_last_inc++;
							}
						}
						
						if($this_paid=='*'){
							$total_this_unpaid++;
						}else{
							$total_this_diff = $payable_subs-$this_paid;
							if($total_this_diff>0){
								$total_this_dec++;
							}
							if($total_this_diff<0){
								$total_this_inc++;
							}
						}
						
						if($member->STATUS_CODE==4){
							$total_resigned++;
						}
						if($data['variationtype']==6){
							if($this_paid!='*'){
								$lastpaiddate = $data['month_year_full'];
							}else if($last_amt!='*'){
								$lastpaiddate = date('Y-m-d',strtotime($data['month_year_full'].' -1 Month'));
							}else if($second_amt!='*'){
								$lastpaiddate = date('Y-m-d',strtotime($data['month_year_full'].' -2 Month'));
							}else if($third_amt!='*'){
								$lastpaiddate = date('Y-m-d',strtotime($data['month_year_full'].' -3 Month'));
							}else if($fourth_amt!='*'){
								$lastpaiddate = date('Y-m-d',strtotime($data['month_year_full'].' -4 Month'));
							}else{
								$lastpaiddate = date('Y-m-d',strtotime($data['month_year_full'].' -5 Month'));
							}
						
						}else{
							if($this_paid!='*'){
								$lastpaiddate = $data['month_year_full'];
							}else if($last_amt!='*'){
								$lastpaiddate = date('Y-m-d',strtotime($data['month_year_full'].' -1 Month'));
							}else if($second_amt!='*'){
								$lastpaiddate = date('Y-m-d',strtotime($data['month_year_full'].' -2 Month'));
							}else{
								$lastpaiddate = date('Y-m-d',strtotime($data['month_year_full'].' -3 Month'));
							}
						}
					@endphp
					<tr style="font-weight:bold;">
						<td><p style="margin-left: 10px; "><label><input name="memberids_{{ $typeidref }}[]" class="checkboxes_{{ $typeidref }}" value="{{ $member->member_id }}" type="checkbox"> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> </label> </p></td>
						<td>{{ $member->member_number }}</td>
						<td>{{ $member->name }}</td>
						<td>{{ date('M Y',strtotime($member->doj)) }}</td>
						<td>{{ date('M Y',strtotime($lastpaiddate)) }}</td>
						<td>{{ $payable_subs }}</td>
						@if($data['variationtype']==6)
						<td>
							@php
								if(is_numeric($fifth_paid_status)){
									if($total_fifth_diff!=0 ){
										$fifth_disp= $total_fifth_diff<0 ? '+'.number_format(abs($total_fifth_diff),2,".","") : '-'.number_format(abs($total_fifth_diff),2,".","");
										echo $fifth_disp;
										if($data['DisplaySubscription']==1){
											echo '('. $fifth_paid_status .')';
										}
										
									}else{
										echo '-';
									}
								}else{
									echo $fifth_paid_status;
								}
								
							@endphp
								
							
						</td>
						<td>
							@php
								if(is_numeric($fourth_paid_status)){
									if($total_fourth_diff!=0 ){
										$fourth_disp= $total_fourth_diff<0 ? '+'.number_format(abs($total_fourth_diff),2,".","") : '-'.number_format(abs($total_fourth_diff),2,".","");
										echo $fourth_disp;
										if($data['DisplaySubscription']==1){
											echo '('. $fourth_paid_status .')';
										}
									}else{
										echo '-';
									}
								}else{
									echo $fourth_paid_status;
								}
							@endphp
								
							
						</td>
						@endif
						<td>@php
								if(is_numeric($third_paid_status)){
									if($total_third_diff!=0 ){
										$third_disp= $total_third_diff<0 ? '+'.number_format(abs($total_third_diff),2,".","") : '-'.number_format(abs($total_third_diff),2,".","");
										echo $third_disp;
										if($data['DisplaySubscription']==1){
											echo '('. $third_paid_status .')';
										}
									}else{
										echo '-';
									}
								}else{
									echo $third_paid_status;
								}
							@endphp
								
							
						</td>
						<td>@php
								if(is_numeric($second_paid_status)){
									if($total_second_diff!=0 ){
										$second_disp= $total_second_diff<0 ? '+'.number_format(abs($total_second_diff),2,".","") : '-'.number_format(abs($total_second_diff),2,".","");
										echo $second_disp;
										if($data['DisplaySubscription']==1){
											echo '('. $second_paid_status .')';
										}
									}else{
										echo '-';
									}
								}else{
									echo $second_paid_status;
								}
							@endphp
							
							
						</td>
						<td>@php
								if(is_numeric($last_paid_status)){
									if($total_last_diff!=0 ){
										$last_disp= $total_last_diff<0 ? '+'.number_format(abs($total_last_diff),2,".","") : '-'.number_format(abs($total_last_diff),2,".","");
										echo $last_disp;
										if($data['DisplaySubscription']==1){
											echo '('. $last_paid_status .')';
										}
									}else{
										echo '-';
									}
								}else{
									echo $last_paid_status;
								}
							@endphp
														
						</td>
						
						<td><input type="text" name="thissubs_{{ $member->member_id }}" class="hide" value="{{ $this_paid }}" />
							@php
								if(is_numeric($this_paid)){
									if($total_this_diff!=0 ){
										$this_disp= $total_this_diff<0 ? '+'.number_format(abs($total_this_diff),2,".","") : '-'.number_format(abs($total_this_diff),2,".","");
										echo $this_disp;
										if($data['DisplaySubscription']==1){
											echo '('. $this_paid .')';
										}
									}else{
										echo '-';
									}
								}else{
									echo $this_paid;
								}
							@endphp
							
						</td>
						<td  class="hide">{{ $subremarks }}</td>
						
					</tr>
					@php
						$count++;
						}else{
							$no_diff++;
						}
					@endphp
					@endforeach
				</tbody>
				<br>
				
			</table>
				<script type="text/javascript">
					$("#checkAll_{{ $typeidref }}").click(function(){
			            $('.checkboxes_{{ $typeidref }}').not(this).prop('checked', this.checked);
			        });
			        $(document).on('click', '.checkboxes_{{ $typeidref }}', function() {
			            $('#checkAll_{{ $typeidref }}').prop('checked', false);
			        });
			        function HideOthers{{ $typeidref }}(getval){
			        	if(getval==5){
			        		$("#othersdiv_{{ $typeidref }}").removeClass('hide');
			        	}else{
			        		$("#othersdiv_{{ $typeidref }}").addClass('hide');
			        	}
			        	
			        }
				</script>
				@if($count==1)
				<style type="text/css">
					#page-length-option-{{ $typeidref }}{
						display: none;
					}
					#companyheading_{{ $typeidref }}{
						display: none;
					}
					
				</style>
				@endif
			@endif
		@endforeach
	

				</br>
	
					</div> 
				</div> 
			</div> 
		</div> 
	</div>
	</form>
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
	
	$(document).on('submit','form#update_formValidate',function(e){
		e.preventDefault();
		var url = "{{ url(app()->getLocale().'/update_discrepancy') }}" ;
		$.ajax({
			url: url,
			type: "POST",
			dataType: "json",
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data: $('#update_formValidate').serialize(),
			success: function(result) {
				if(result.status==1){
					$(".tablesall").remove();
					alert(result.message);
					var link = $('#printdiscrepancy').attr('href');
					window.open( link, "_blank");
				}else{
					alert('failed to update');
				}
			}
		});
		//$("#submit-download").prop('disabled',true);
	});
	// $(document).on('click','.variationtype',function(){
	// 	$('#variation_uncheck').prop('checked', true); 
	// });
	
	function UncheckVariation(){
		$('.variationtype').prop('checked', false); 
	}

	function ViewVarianceList(thisdata){
		window.open($(thisdata).attr("data-href"), '_blank');
	}

	function ViewLists(refid){
		$("#sub_company").val('').trigger("change");
		$("#unionbranch_id").val('').trigger("change");
		if(refid==1){
			$("#banksection").addClass('hide');
			$("#unionsection").removeClass('hide');
		}else{
			$("#banksection").removeClass('hide');
			$("#unionsection").addClass('hide');
		}
	}
	
	$("#subscriptions_sidebars_id").addClass('active');
	$("#subvariationdis_sidebar_li_id").addClass('active');
	$("#subvariationdis_sidebar_sidebar_a_id").addClass('active');
</script>
@endsection