<!DOCTYPE html>
<html>

<head>
	<script src="{{ asset('public/assets/js/jquery-1.12.4.min.js') }}" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/vendors.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/flag-icon.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/vertical-modern-menu.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/materialize.css') }}">
	<style>
		/* Styles go here */
		td, th {
			display: table-cell;
			padding: 6px 5px;
			text-align: none;
			vertical-align: middle;
			border-radius: 2px;
		}
		/*#page-length-option,#page-length-one {
		  border-collapse: collapse;
		  width: 100%;
		}*/

		/*#page-length-option td, #page-length-one th {
		  border: 1px solid #ddd;
		  width: auto;
			overflow: hidden;
			word-wrap: break-word;
			page-break-inside: avoid;
		  padding: 4px;
		}*/

		#page-length-option td, #page-length-option th, #page-length-one td, #page-length-one th{
		  border: 1px solid #ddd !important;
		  padding: 4px;
		}
		
		@media print{
			
			@page {size: landscape; margin: 3mm;}
			
			.export-button{
				display:none;
			}
			body {margin: 0;}
			#page-length-option td, #page-length-option th, #page-length-one td, #page-length-one th{
			  border: 1px solid #ddd !important;
			  padding: 4px;
			}
			.report-address{
				font-weight:bold;
				font-size:14px;
			}
		}
		@media print {
			.footer-summary {page-break-after: always;}
		}
		html { overflow: visible }
		html {
			    font-size: 12px;
			}
		.title-area{
			font-weight: bold;
			font-size:16px;
		}
		.table-title{
			font-weight: bold;
			font-size:14px;
		}
		
	</style>
	<script type="text/javascript">
		
		@if($data['print']==1)
			window.print();
		@endif
	</script>
</head>

<body>
	<div class="page-header" style="text-align: center">
		<table width="100%">
			<tr>
				<td width="20%"></td>
				<td width="10%"></td>
				<td width="50%" class="title-area" style="text-align:center;">NUBE Monthly Subscription {{ date('M Y',strtotime($data['month_year_full'])) }} - Discrepancy Report
					
				</td>
				<td width="20%">	
					<!--a href="#" class="export-button btn btn-sm" style="background:#ccc;" onClick="window.print()"><i class="material-icons">print</i></a-->
				</td>
			</tr>
		</table>
	</div>
	
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
	
	@foreach($memberslist as $company)
	
	@php
		if($data['groupby']==1){
			$typeidref = $company->union_branchid;
		}elseif($data['groupby']==2){
			$typeidref = $company->company_id;
		}else{
			$typeidref = $company->branch_id;
		}
	@endphp
	<table id="page-length-option-{{ $typeidref }}" class="display" width="100%">
		<thead>
			
			<tr class="title-area" >
				@if($data['groupby']==1)
				<th colspan="@if($data['variation']==6) 13 @else 11 @endif">{{ $company->union_branch_name }}</th>
				@elseif($data['groupby']==2)
				<th colspan="@if($data['variation']==6) 13 @else 11 @endif">{{ $company->company_name }}</th>
				@else
				<th colspan="@if($data['variation']==6) 13 @else 11 @endif">{{ $company->company_name }} - {{ $company->branch_name }}</th>
				
				@endif

			</tr>
			<tr class="table-title">
				<th>{{__('S.No')}}</th>
				<th>{{__('M.No')}}</th>
				<th>{{__('Member Name')}}</th>
				<th>{{__('Joining')}}</th>
				<th>{{__('Last Paid')}}</th>
				<th>{{__('Subs')}}</th>
				@if($data['variation']==6)
				<th>{{ date('M Y',strtotime($data['month_year_full'].' -5 Month')) }}</th>
				<th>{{ date('M Y',strtotime($data['month_year_full'].' -4 Month')) }}</th>
				@endif
				<th>{{ date('M Y',strtotime($data['month_year_full'].' -3 Month')) }}</th>
				<th>{{ date('M Y',strtotime($data['month_year_full'].' -2 Month')) }}</th>
				<th>{{ date('M Y',strtotime($data['month_year_full'].' -1 Month')) }}</th>
				<th>{{ date('M Y',strtotime($data['month_year_full'])) }}</th>
				<th>Remarks</th>
				
			</tr>
		</thead>
		<tbody class="tbody-area">
			@php
				if($data['groupby']==1){
					$typeid = $company->union_branchid;
				}elseif($data['groupby']==2){
					$typeid = $company->company_id;
				}else{
					$typeid = $company->branch_id;
				}
				$company_members = CommonHelper::getSubscriptionBankMembers($data['groupby'], $typeid,$data['month_year_first'],$data['month_year_full']);
				//dd(count($company_members));
				//$company_members = CommonHelper::getCompanyMembers($typeid,$data['month_year_full'],$data['groupby']);
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
			@endphp
			@foreach($company_members as $member)
			@php
				//dd($member);
				$lastpaiddate = CommonHelper::getLastPaidDate($member->member_id);
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

				if($data['variation']==6){
					$updated_salary = CommonHelper::getIncrementValue($member->member_id,$this_str,$fifth_str);
				}else{
					$updated_salary = CommonHelper::getIncrementValue($member->member_id,$this_str,$third_str);
				}
				$subremarks = '';
				$addonsalary = 0;
				if($data['inctype'] != ''){
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
							if($data['inctype'] != '' && $data['inctype'] == $upsalary->increment_type_id){
								$displaymember = 1;
							}
							if($upsalary->increment_type_id==5){
								$subremarks .= $upsalary->summary;
							}else{
								$subremarks .= $inctype;
							}
							$subremarks .= " [".date('M Y',strtotime($upsalary->date))."]";
							
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
								if($data['inctype'] != '' && $data['inctype'] == $upsalary->increment_type_id){
									$displaymember = 1;
								}
								$subremarks .= $inctype;
								$subremarks .= " [".date('M Y',strtotime($upsalary->date))."]";
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

				

				if($data['variation']==6){
					$fifth_amt = CommonHelper::getCompanyPaidSubs($typeid, $member->member_id, date('Y-m-d',strtotime($data['month_year_full'].' -5 Month')));
					$fourth_amt = CommonHelper::getCompanyPaidSubs($typeid, $member->member_id, date('Y-m-d',strtotime($data['month_year_full'].' -4 Month')));
				}
				

				$third_amt = CommonHelper::getCompanyPaidSubs($typeid, $member->member_id, date('Y-m-d',strtotime($data['month_year_full'].' -3 Month')));
				$second_amt = CommonHelper::getCompanyPaidSubs($typeid, $member->member_id, date('Y-m-d',strtotime($data['month_year_full'].' -2 Month')));
				$last_amt = CommonHelper::getCompanyPaidSubs($typeid, $member->member_id, date('Y-m-d',strtotime($data['month_year_full'].' -1 Month')));
				$this_paid = CommonHelper::getCompanyPaidSubs($typeid, $member->member_id,$data['month_year_full']);
				if($this_paid==Null || $this_paid==0){
					$this_paid = '*';
				}
				if($data['variation']==6){
					$fifth_paid_status = $fifth_amt;
					$fourth_paid_status = $fourth_amt;
				}
				$third_paid_status = $third_amt;
				$second_paid_status = $second_amt;
				$last_paid_status = $last_amt;

				$variedamt = 0;

				if($data['variation']==6){
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
				if($data['variation']==6){
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
				$lastpaydate = CommonHelper::getLastPayDate($member->member_id, $member->pay_date);
				$lastpaydate = $lastpaydate!='' ? date('M Y',strtotime($lastpaydate)) : '';
			@endphp
			<tr style="font-weight:bold;">
				<td>{{$count}}</td>
				<td>{{ $member->member_number }}</td>
				<td>{{ $member->name }}</td>
				<td>{{ date('M Y',strtotime($member->doj)) }}</td>
				<td>{{ date('M Y',strtotime($data['month_year_full'])) }}</td>
				<td>{{ $payable_subs }}</td>
				@if($data['variation']==6)
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
				<td>
					@php
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
				<td>
					@php
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
				<td>
					@php
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
				
				<td>
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
				<td>{{ $subremarks }}</td>
				
			</tr>
			@php
					$count++;
				}else{
					$no_diff++;
				}
			@endphp
			@endforeach
			<!--tr style="font-weight:bold;">
				<td colspan="6" style="text-align:right;">New Member</td>
				@if($data['variation']==6)
				<td>{{$total_fifth_new}}</td>
				<td>{{$total_fourth_new}}</td>
				<td>{{$total_third_new}}</td>
				<td>{{$total_second_new}}</td>
				<td>{{$total_last_new}}</td>
				@endif
				<td>0</td>
			</tr>
			<tr style="font-weight:bold;">
				<td colspan="6" style="text-align:right;">Resign Member</td>
				@if($data['variation']==6)
				<td>{{$total_resigned}}</td>
				<td>{{$total_resigned}}</td>
				<td>{{$total_resigned}}</td>
				<td>{{$total_resigned}}</td>
				<td>{{$total_resigned}}</td>
				@endif
				<td>{{$total_resigned}}</td>
			</tr-->
			<tr class="table-title">
				<td colspan="6" style="text-align:right;">Unpaid</td>
				@if($data['variation']==6)
				<td>{{$total_fifth_unpaid}}</td>
				<td>{{$total_fourth_unpaid}}</td>
				@endif
				<td>{{$total_third_unpaid}}</td>
				<td>{{$total_second_unpaid}}</td>
				<td>{{$total_last_unpaid}}</td>
				<td>{{$total_this_unpaid}}</td>
				<td></td>
				
			</tr>
			<tr class="table-title">
				<td colspan="6" style="text-align:right;">No Difference</td>
				@if($data['variation']==6)
				<td>{{$no_diff}}</td>
				<td>{{$no_diff}}</td>
				@endif
				<td>{{$no_diff}}</td>
				<td>{{$no_diff}}</td>
				<td>{{$no_diff}}</td>
				<td>{{$no_diff}}</td>
				<td></td>
				
			</tr>
			<!--tr style="font-weight:bold;">
				<td colspan="6" style="text-align:right;">Decrement</td>
				@if($data['variation']==6)
				<td>{{$total_fifth_dec}}</td>
				<td>{{$total_fouth_dec}}</td>
				<td>{{$total_third_dec}}</td>
				<td>{{$total_second_dec}}</td>
				<td>{{$total_last_dec}}</td>
				@endif
				<td>{{$total_this_dec}}</td>
			</tr>
			<tr style="font-weight:bold;">
				<td colspan="6" style="text-align:right;">Increment</td>
				@if($data['variation']==6)
				<td>{{$total_fifth_inc}}</td>
				<td>{{$total_fouth_inc}}</td>
				<td>{{$total_third_inc}}</td>
				<td>{{$total_second_inc}}</td>
				<td>{{$total_last_inc}}</td>
				@endif
				<td>{{$total_this_inc}}</td>
			</tr-->
		</tbody>
		
		
	</table>
	@if($count==1)
	<style type="text/css">
		#page-length-option-{{ $typeidref }}{
			display: none;
		}
		
	</style>
	@endif
	@php
		$overall_total_fifth_new+=$total_fifth_new;
		$overall_total_fourth_new+=$total_fourth_new;
		$overall_total_third_new+=$total_third_new;
		$overall_total_second_new+=$total_second_new;
		$overall_total_last_new+=$total_last_new;
		$overall_total_this_new+=0;
		
		$overall_total_resigned+=$total_resigned;
		$overall_total_fifth_unpaid+=$total_fifth_unpaid;
		$overall_total_fourth_unpaid+=$total_fourth_unpaid;
		$overall_total_third_unpaid+=$total_third_unpaid;
		$overall_total_second_unpaid+=$total_second_unpaid;
		$overall_total_last_unpaid+=$total_last_unpaid;
		$overall_total_this_unpaid+=$total_this_unpaid;
		
		$overall_total_fifth_inc+=$total_fifth_inc;
		$overall_total_fouth_inc+=$total_fouth_inc;
		$overall_total_third_inc+=$total_third_inc;
		$overall_total_second_inc+=$total_second_inc;
		$overall_total_last_inc+=$total_last_inc;
		$overall_total_this_inc+=$total_this_inc;
		
		$overall_total_fifth_dec+=$total_fifth_dec;
		$overall_total_fouth_dec+=$total_fouth_dec;
		$overall_total_third_dec+=$total_third_dec;
		$overall_total_second_dec+=$total_second_dec;
		$overall_total_last_dec+=$total_last_dec;
		$overall_total_this_dec+=$total_this_dec;
		$overall_no_diff+=$no_diff;
	@endphp
	@endforeach
	</br>
	<div class='footer-summary'></div>
	
	<table id="page-length-one" class="display" width="100%">
		<thead>
			
			<tr class="" >
				<th colspan="@if($data['variation']==6) 12 @else 10 @endif" align="center">{{ __('Overall Summary') }}</th>
			</tr>
			<tr class="" >
				<th colspan='6' width='40%'>{{__('Description')}}</th>
				@if($data['variation']==6)
				<th>{{ date('M Y',strtotime($data['month_year_full'].' -5 Month')) }}</th>
				<th>{{ date('M Y',strtotime($data['month_year_full'].' -4 Month')) }}</th>
				@endif
				<th>{{ date('M Y',strtotime($data['month_year_full'].' -3 Month')) }}</th>
				<th>{{ date('M Y',strtotime($data['month_year_full'].' -2 Month')) }}</th>
				<th>{{ date('M Y',strtotime($data['month_year_full'].' -1 Month')) }}</th>
				<th>{{ date('M Y',strtotime($data['month_year_full'])) }}</th>
				
			</tr>
		</thead>
		<tbody class="tbody-area">
			<!--tr style="font-weight:bold;">
				<td colspan="6" style="text-align:right;">New Member</td>
				@if($data['variation']==6)
				<td>{{$overall_total_fifth_new}}</td>
				<td>{{$overall_total_fourth_new}}</td>
				<td>{{$overall_total_third_new}}</td>
				<td>{{$overall_total_second_new}}</td>
				<td>{{$overall_total_last_new}}</td>
				@endif
				<td>0</td>
			</tr>
			<tr style="font-weight:bold;">
				<td colspan="6" style="text-align:right;">Resign Member</td>
				@if($data['variation']==6)
				<td>{{$overall_total_resigned}}</td>
				<td>{{$overall_total_resigned}}</td>
				<td>{{$overall_total_resigned}}</td>
				<td>{{$overall_total_resigned}}</td>
				<td>{{$overall_total_resigned}}</td>
				@endif
				<td>{{$overall_total_resigned}}</td>
			</tr-->
			<tr style="font-weight:bold;">
				<td colspan="6" style="text-align:right;">Unpaid</td>
				@if($data['variation']==6)
				<td>{{$overall_total_fifth_unpaid}}</td>
				<td>{{$overall_total_fourth_unpaid}}</td>
				@endif
				<td>{{$overall_total_third_unpaid}}</td>
				<td>{{$overall_total_second_unpaid}}</td>
				<td>{{$overall_total_last_unpaid}}</td>
				
				<td>{{$overall_total_this_unpaid}}</td>
				
			</tr>
			<tr style="font-weight:bold;">
				<td colspan="6" style="text-align:right;">No Difference</td>
				@if($data['variation']==6)
				<td>{{$overall_no_diff}}</td>
				<td>{{$overall_no_diff}}</td>
				@endif
				<td>{{$overall_no_diff}}</td>
				<td>{{$overall_no_diff}}</td>
				<td>{{$overall_no_diff}}</td>
				
				<td>{{$overall_no_diff}}</td>
				
			</tr>
			<!--tr style="font-weight:bold;">
				<td colspan="6" style="text-align:right;">Decrement</td>
				@if($data['variation']==6)
				<td>{{$overall_total_fifth_dec}}</td>
				<td>{{$overall_total_fouth_dec}}</td>
				<td>{{$overall_total_third_dec}}</td>
				<td>{{$overall_total_second_dec}}</td>
				<td>{{$overall_total_last_dec}}</td>
				@endif
				<td>{{$overall_total_this_dec}}</td>
			</tr>
			<tr style="font-weight:bold;">
				<td colspan="6" style="text-align:right;">Increment</td>
				@if($data['variation']==6)
				<td>{{$overall_total_fifth_inc}}</td>
				<td>{{$overall_total_fouth_inc}}</td>
				<td>{{$overall_total_third_inc}}</td>
				<td>{{$overall_total_second_inc}}</td>
				<td>{{$overall_total_last_inc}}</td>
				@endif
				<td>{{$overall_total_this_inc}}</td>
			</tr-->
		</tbody>
	</table>
	
</body>


</html>