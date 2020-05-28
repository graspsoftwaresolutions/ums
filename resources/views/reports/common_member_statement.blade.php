@php 
	$logo = CommonHelper::getLogo(); 
	$member = $data['member_data'];
	$history = $data['history_view'];
	$last_history = $data['last_history_view'];
	//dd($history);
	$get_roles = Auth::user()->roles; 
	$user_role = $get_roles[0]->slug; 
@endphp
<div class="" style="text-align: center">
	<table width="100%">
		<tr>
			<td width="100%" colspan="3">
				<table width="100%">
					<tr>
						<td width="1%" style="padding: 2px;padding-right: 8px;">
							<img src="{{ asset('public/assets/images/logo/'.$logo) }}" style="vertical-align: middle;" alt="Membership logo" height="90">
						</td>
						<td width="79%" style="padding: 2px;">
							<span class="report-address" style="font-weight: bold;font-size:18px;">NATIONAL UNION OF BANK EMPLOYEES</span>
					
							<p style="padding-top: 0px;margin-top: 5px;">ANNUAL BENEVOLENT FUND STATEMENT OF ACCOUNT</p>
						</td>
						<td width="20%" style="padding: 2px;">
							<br>
							<br>
							<table>
								<tr>
									<td width="52%"><b>DATE JOINED</b></td>
									<td width="1%"><b> :</b></td>
									<td><b> {{ date('d/M/Y',strtotime($member->doj)) }}</b></td>
								</tr>
								<tr>
									<td><b>PAID TILL</b></td>
									<td><b> :</b></td>
									<td><b> {{  date('d/M/Y',strtotime($data['to_date'])) }}</b></td>
								</tr>
							</table>
							
						</td>
					</tr>
					@php
						$memberaddr = CommonHelper::getBranchAddress($member->branch_id);
					@endphp
					<tr>
						<td colspan="2">
							<table>
								<tr>
									<td width="5%" style="vertical-align: top;font-weight: bold;">BRANCH</td>
									<td>:</td>
									<td width="94%">
										 {{ $member->branch_name }}
											@if($memberaddr->address_one!="") , {{ $memberaddr->address_one }} @endif
											@if($memberaddr->address_two!="") , {{ $memberaddr->address_two }} @endif
											@if($memberaddr->city_name!="") , {{ $memberaddr->city_name }} @endif
											@if($memberaddr->postal_code!="") - {{ $memberaddr->postal_code }} @endif
										
									</td>
								</tr>
								<tr>
									<td ><b>BANK</b></td>
									<td>:</td>
									<td>
										{{ $member->company_name }}
									</td>
								</tr>
							</table>
						</td>
						<td style="padding: 2px;">
							<table>
								<tr>
									<td width="52%"><b>BRANCH CODE</b></td>
									<td width="1%"><b>:</b></td>
									<td><b> {{ $member->branch_shortcode }}</b></td>
								</tr>
								<tr>
									<td><b>BANK CODE</b></td>
									<td width="1%"><b>:</b></td>
									<td><b> {{ $member->short_code }}</b></td>
								</tr>
							</table>
							@if($user_role!='member')
								
							@endif
						</td>
					</tr>
				</table>
				
				
			</td>
			
		</tr>
		
		<!-- <tr class="statement-address">
			<td width="50%" style="padding-top: 0px;">
				
				
			</td>
			<td width="30%" style="padding-top: 0px;">
				
			</td>
			<td width="10%" style="text-align:center;padding-top: 0px;">
				
			</td>
		</tr> -->
	</table>
</div>
<table id="page-length-option" class="display table2excel" style="margin: 10px;width: 99% !important;">
		<thead>
				
			<tr class="bold" style="text-align:center;">
				<!--th style="border: 1px solid #988989 !important;">S.NO</th-->
				<th style="border-bottom: 1px solid #988989 !important;text-align: center;">M/NO</th>
				<th width="15%" style="border-bottom: 1px solid #988989 !important;text-align: center;">NAME</th>
				<th style="border-bottom: 1px solid #988989 !important;text-align: center;" >I/C NO</th>
				<th  style="border-bottom: 1px solid #988989 !important;text-align: center;">PREVIOUS<br>BALANCE</th>
				<th  style="border-bottom: 1px solid #988989 !important;text-align: center;">CURRENT<br>PAYMENT</th>
				<th  style="border-bottom: 1px solid #988989 !important;text-align: center;">BALANCE<br>TO-DATE</th>
				<th  style="border-bottom: 1px solid #988989 !important;text-align: center;">ACCRUED<br>BENEFIT</th>
				<th style="border-bottom: 1px solid #988989 !important;text-align: center;">ACCRUED<br>INSURANCE(MONTHS)</th>
				<th  style="border-bottom: 1px solid #988989 !important;text-align: center;">ARREARS<br>MONTH</th>
				<th style="border-bottom: 1px solid #988989 !important;text-align: center;">AMT.DUE <br>TO. UNION </th>
			</tr>
		</thead>
		<tbody class="">
			@php
				$totalmembers = 0;
				$sno = 1;
				$lastallsubs = !empty($last_history) ? $last_history->ACCSUBSCRIPTION : 0;
				$currentsubs = !empty($history) ? $history->TOTALSUBCRP_AMOUNT : 0;
				$acc_bf = CommonHelper::getBFAmountByDate($member->id,$member->doj,$data['to_date']);
				$ic='';
				if($member->new_ic!=""){
					$ic=$member->new_ic;
				}elseif($member->old_ic!=""){
					$ic=$member->old_ic;
				}else{
					$ic=$member->employee_id;	
				}

				$acc_ins = 0;
				$total_dues = 0;
				$subsdues = $data['due_subs'];

				if(!empty($history)){
					$acc_ins = $history->ACCINSURANCE;
					$total_dues = $history->TOTALMONTHSDUE;
					//$subsdues = $history->SUBSCRIPTIONDUE;
				}else if(!empty($last_history)){
					$acc_ins = $last_history->ACCINSURANCE;
					$total_dues = $last_history->TOTALMONTHSDUE+1;
					//$subsdues = $last_history->SUBSCRIPTIONDUE+$last_history->TOTALSUBCRP_AMOUNT;
				}

			@endphp
		
		
			<tr >
				<!--td style="border: 1px solid #988989 !important; ">{{ $sno }}</td-->
				<td style="border: 1px solid #988989 !important;text-align: center;">{{ $member->member_number }}</td>
				<td style="border: 1px solid #988989 !important;">{{ $member->name }}</td>
				<td class="nric_no" style="border: 1px solid #988989 !important;text-align: center;">{{ $ic }}</td>
				<td style="border: 1px solid #988989 !important;text-align: center;">{{ !empty($last_history) ? $last_history->ACCSUBSCRIPTION : 0 }}</td>
				<td style="border: 1px solid #988989 !important;text-align: center;" >{{ !empty($history) ? $history->TOTALSUBCRP_AMOUNT : 0 }}</td>
				<td  style="border: 1px solid #988989 !important;text-align: center;">{{ $lastallsubs+$currentsubs }}</td>
				<td style="border: 1px solid #988989 !important;text-align: center;">{{  $acc_bf }}</td>
				<td style="border: 1px solid #988989 !important;text-align: center;">{{  $data['insurance_count'] }}</td>
				<td style="border: 1px solid #988989 !important;text-align: center;">{{  $total_dues }}</td>
				<td style="border: 1px solid #988989 !important;text-align: center;">{{  $subsdues }} *</td>
				
			</tr> 
				
			
		</tbody>
		
	</table>
	
	<table width="100%">
		<tr>
			<td width="5%" style="vertical-align: top;">NOTE: </td>
			<td>
				<p style="padding-top: 0px;margin-top: 0px;">1) Any queries on this statement should be communicated to the Hon. Gen Secretary within 14 days upon receipt of this statement, otherwise it would be treated as correct. </p>
				<p>2) If you wish to change the nominee, kindly request for the nominee form from you Branch.</p>
				<!--p>3) The Accrued Benefit will be payable according to Rule 6 of the Benevolent Fund Rules.</p-->
				<!--<p>3) If arrears indicates less than 2 months then it may be due to transit of payment from Bank to NUBE Headquarters.</p>-->
				<p>* Payment due for 

				<b style="font-size: 16px;font-weight:bold;">
					@if($total_dues!=0 && $data['due_months']!='')
						{{ $data['due_months'] }}
					@endif
				</b>

				</p>
				@if($total_dues!=0 && $data['due_months']!='')
				<!--table id="reason-area" style="width:auto">
					<tbody>
						<tr style="font-weight: bold;">
							<td style="border: 1px solid #988989 !important;">
								Months
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid #988989 !important;">
								{{ $data['due_months'] }}
							</td>
						</tr>
					</tbody>
				</table-->
				@endif
				<br>
				<p align="center">National Union of Bank Employees, 12 NUBE House, 3rd Floor Jalan Tun Sambanthan 3, Brickfields 50470 Kuala Lumpur.
					<br>
					Tel: (603) 2274 9800 &nbsp;&nbsp; Fox: (603) 2260 1800 &nbsp;&nbsp; Email: nube_hq@nube.org.my &nbsp;&nbsp; Website: www.nube.org.my
				</p>
				
			</td>
		</tr>
	</table>
	<br>