@php 
	$logo = CommonHelper::getLogo(); 
@endphp
<table id="page-length-option" class="display" width="100%">
		<thead>
			<tr class="">
				
				<td colspan="2" rowspan="2" style="text-align:right">
					<img src="{{ asset('public/assets/images/logo/'.$logo) }}" height="50" />
				</td>
				<td colspan="5" style="text-align:center;padding:10px;vertical-align:top;">
					<span style="text-align:center;font-weight: bold;font-size:18px;vertical-align:top;">NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</span>
					
				</td>
				<td colspan="3" rowspan="2">	
					</br>
				</td>
			</tr>
			<tr class="">
				
				<td colspan="5" style="text-align:center;padding:10px;font-weight: bold;">
				
					<span style="margin-top:0;">{{ strtoupper(date('M Y',strtotime($data['month_year']))) }} SUMMARY REPORT</span>
				</td>
				
			</tr>
			
			
			
		</thead>
		<tbody class="" width="100%">
			<tr>
				<td colspan="10" style="">
					<table style="width: 90% !important;margin: 0 5%;">
						<thead>
							<tr class="">
								<th style="border : 1px solid #988989;" align="center">DESCRIPTION</th>
								<th style="border : 1px solid #988989;text-align: center;" width="7%">{{__('COUNT')}}</th>
				                <th style="border : 1px solid #988989;text-align: center;" width="7%">{{__('AMOUNT')}}</th>
							</tr>
						</thead>
						<tbody>
							@php
								$newmembers = CommonHelper::getSubscriptionNewMembers($data['month_year'])[0];
								$activemembers = CommonHelper::getSubscriptionStatusMembers($data['month_year'],1)[0];
								$defaultermembers = CommonHelper::getSubscriptionStatusMembers($data['month_year'],2)[0];
								$struckoffmembers = CommonHelper::getSubscriptionStatusMembers($data['month_year'],3)[0];
								$resignedmembers = CommonHelper::getSubscriptionStatusMembers($data['month_year'],4)[0];
								$totalcount = $newmembers->count+$activemembers->count+$defaultermembers->count+$struckoffmembers->count+$resignedmembers->count;
								$totalamount = $newmembers->amount+$activemembers->amount+$defaultermembers->amount+$struckoffmembers->amount+$resignedmembers->amount;

								$resigncount = CommonHelper::getResignMembersCount($data['month_year']);
								
								//dd($newmembers);
							@endphp
							<tr>
								<td style="border : 1px solid #988989;font-weight:bold;">New Members</td>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $newmembers->count }}</th>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ number_format(round($newmembers->amount,2),2,".","") }}</th>
							</tr>
							<tr>
								<td style="border : 1px solid #988989;font-weight:bold;">Resigned Members From NUBE HQ</td>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $resigncount }}</th>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">--</th>
							</tr>
							<tr>
								<td style="border : 1px solid #988989;font-weight:bold;">Active Members</td>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $activemembers->count }}</th>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ number_format(round($activemembers->amount,2),2,".","") }}</th>
							</tr>
							<tr>
								<td style="border : 1px solid #988989;font-weight:bold;">Defaulter Members</td>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $defaultermembers->count }}</th>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ number_format(round($defaultermembers->amount,2),2,".","") }}</th>
							</tr>
							<tr>
								<td style="border : 1px solid #988989;font-weight:bold;">Struckoff Members</td>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $struckoffmembers->count }}</th>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ number_format(round($struckoffmembers->amount,2),2,".","") }}</th>
							</tr>
							<tr>
								<td style="border : 1px solid #988989;font-weight:bold;">Resigned Members</td>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $resignedmembers->count }}</th>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ number_format(round($resignedmembers->amount,2),2,".","") }}</th>
							</tr>
							<tr>
								<td style="border : 1px solid #988989;font-weight:bold;">Total Members</td>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $totalcount }}</th>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ number_format(round($totalamount,2),2,".","") }}</th>
							</tr>
						</tbody>
						
					</table>

				</td>
				
			</tr> 
            <!-- <tr>
				<td colspan="10" style="border : 1px solid #988989;font-weight:bold;">Total ACTIVE Member's Count : 0</td>
				
			</tr>  -->
		</tbody>
		
	</table>
	<br>
	<table id="page-length-option" class="display" width="100%">
		<thead>
			
			<tr class="">
				
				<td colspan="5" style="text-align:center;padding:10px;font-weight: bold;">
				
					<span style="margin-top:0;">OVERALL SUMMARY REPORT</span>
				</td>
				
			</tr>
			
			
			
		</thead>
		<tbody class="" width="100%">
			<tr>
				<td colspan="10" style="">
					<table style="width: 90% !important;margin: 0 5%;">
						<thead>
							<tr class="">
								<th style="border : 1px solid #988989;" align="center">DESCRIPTION</th>
								<th style="border : 1px solid #988989;text-align: center;" width="10%">{{ date('M Y',strtotime($data['month_year'].' -1 Month')) }}</th>
				                <th style="border : 1px solid #988989;text-align: center;" width="10%">{{ date('M Y',strtotime($data['month_year'])) }}</th>
				                <th style="border : 1px solid #988989;text-align: center;" width="10%">Difference</th>
				                <th style="border : 1px solid #988989;text-align: center;" width="10%">Unpaid</th>
							</tr>
						</thead>
						<tbody>
							@php
								$current_active = CommonHelper::getMonthendStatuscount($data['month_year'],1);
								$current_defauler = CommonHelper::getMonthendStatuscount($data['month_year'],2);

								$lastmonth = date('Y-m-01',strtotime($data['month_year'].' -1 Month'));

								$last_active = CommonHelper::getMonthendStatuscount($lastmonth,1);
								$last_defauler = CommonHelper::getMonthendStatuscount($lastmonth,2);

								$active_diff = $current_active-$last_active;
								$defaulter_diff = $current_defauler-$last_defauler;

								$active_unpaid = CommonHelper::getLastPaidCurrentUnpaidCount($data['month_year'],1);
								$defaulter_unpaid = CommonHelper::getLastPaidCurrentUnpaidCount($data['month_year'],2);

								$current_resigned = CommonHelper::getResignedMemberscount($data['month_year']);
								$last_resigned = CommonHelper::getResignedMemberscount($lastmonth);
								$resign_diff = $current_resigned-$last_resigned;

								$resigned_unpaid = CommonHelper::getLastPaidCurrentUnpaidCount($data['month_year'],4);
							@endphp
							<tr>
								<td style="border : 1px solid #988989;font-weight:bold;">Active Members</td>
								<td style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $last_active }}</td>
								<td style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $current_active }}</td>
								<td style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $active_diff }}</td>
								<td style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $active_unpaid }}</td>	
							</tr>
							<tr>
								<td style="border : 1px solid #988989;font-weight:bold;">Defaulter Members</td>
								<td style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $last_defauler }}</td>
								<td style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $current_defauler }}</td>
								<td style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $defaulter_diff }}</td>
								<td style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $defaulter_unpaid }}</td>	
							</tr>
							<tr>
								<td style="border : 1px solid #988989;font-weight:bold;">Struckoff Members</td>
								<td style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ 0 }}</td>
								<td style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ 0 }}</td>
								<td style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ 0 }}</td>
								<td style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ 0 }}</td>	
							</tr>
							<tr>
								<td style="border : 1px solid #988989;font-weight:bold;">Resigned Members</td>
								<td style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $last_resigned }}</td>
								<td style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $current_resigned }}</td>
								<td style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $resign_diff }}</td>
								<td style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $resigned_unpaid }}</td>	
							</tr>
							
						</tbody>
						
					</table>

				</td>
				
			</tr> 
            <!-- <tr>
				<td colspan="10" style="border : 1px solid #988989;font-weight:bold;">Total ACTIVE Member's Count : 0</td>
				
			</tr>  -->
		</tbody>
		
	</table>
	