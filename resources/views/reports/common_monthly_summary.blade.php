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
				
					<span style="margin-top:0;">{{ strtoupper(date('M Y',strtotime($data['month_year']))) }} MONTH SUMMARY REPORT</span>
				</td>
				
			</tr>
			
			
			
		</thead>
		<tbody class="" width="100%">
			<tr>
				<td colspan="10">
					<table>
						<thead>
							<tr class="">
								<th style="border : 1px solid #988989;" align="center">DESCRIPTION</th>
								<th style="border : 1px solid #988989;">{{__('COUNT')}}</th>
				                <th style="border : 1px solid #988989;">{{__('AMOUNT')}}</th>
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
								<td style="border : 1px solid #988989;font-weight:bold;">Total no of new members</td>
								<th style="border : 1px solid #988989;font-weight:bold;">{{ $newmembers->count }}</th>
								<th style="border : 1px solid #988989;font-weight:bold;">{{ round($newmembers->amount,2) }}</th>
							</tr>
							<tr>
								<td style="border : 1px solid #988989;font-weight:bold;">Total no of active members</td>
								<th style="border : 1px solid #988989;font-weight:bold;">{{ $activemembers->count }}</th>
								<th style="border : 1px solid #988989;font-weight:bold;">{{ round($activemembers->amount,2) }}</th>
							</tr>
							<tr>
								<td style="border : 1px solid #988989;font-weight:bold;">Total no of defaulter members</td>
								<th style="border : 1px solid #988989;font-weight:bold;">{{ $defaultermembers->count }}</th>
								<th style="border : 1px solid #988989;font-weight:bold;">{{ round($defaultermembers->amount,2) }}</th>
							</tr>
							<tr>
								<td style="border : 1px solid #988989;font-weight:bold;">Total no of struckoff members</td>
								<th style="border : 1px solid #988989;font-weight:bold;">{{ $struckoffmembers->count }}</th>
								<th style="border : 1px solid #988989;font-weight:bold;">{{ round($struckoffmembers->amount,2) }}</th>
							</tr>
							<tr>
								<td style="border : 1px solid #988989;font-weight:bold;">Total no of resigned members</td>
								<th style="border : 1px solid #988989;font-weight:bold;">{{ $resignedmembers->count }}</th>
								<th style="border : 1px solid #988989;font-weight:bold;">{{ round($resignedmembers->amount,2) }}</th>
							</tr>
							<tr>
								<td style="border : 1px solid #988989;font-weight:bold;">Total no of members</td>
								<th style="border : 1px solid #988989;font-weight:bold;">{{ $totalcount }}</th>
								<th style="border : 1px solid #988989;font-weight:bold;">{{ round($totalamount,2) }}</th>
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
	<p style="margin-left: 10px;font-weight:bold;">Total no of resigned members {{ strtoupper(date('M Y',strtotime($data['month_year']))) }} month : {{ $resigncount }}</p>