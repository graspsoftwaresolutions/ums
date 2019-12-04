@php 
	$logo = CommonHelper::getLogo(); 
	$member = $data['member_data'];
	//dd($data);
@endphp
<div class="" style="text-align: center">
	<table width="100%">
		<tr>
			<td width="100%" colspan="3">
				<table width="100%">
					<td width="1%" style="padding: 2px;">
						<img src="{{ asset('public/assets/images/logo/'.$logo) }}" style="vertical-align: middle;" alt="Membership logo" height="90">
					</td>
					<td width="79%" style="padding: 2px;">
						<span class="report-address" style="font-weight: bold;font-size:18px;">NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</span>
				
						<p style="padding-top: 0px;margin-top: 5px;">MEMBERS ANNUAL STATEMENT OF ACCOUNT OF BENEVOLENT FUND</p>
					</td>
					<td width="20%" style="padding: 2px;">
						<br>
						<br>
						<p style="margin-bottom: 10px;margin-top: 0;">Date Joined: {{ date('d/M/Y',strtotime($member->doj)) }}</p>
						DT Paid Till: {{  date('d/M/Y',strtotime($data['history_view'][count($data['history_view'])-1]->StatusMonth)) }}
					</td>
				</table>
				
				
			</td>
			
		</tr>
		<tr class="statement-address">
			<td width="40%" style="padding-top: 0px;">
				<p>Branch: {{ $member->branch_name }}</p>
				<p>Bank: {{ $member->company_name }}</p>
				<p>Address: {{ $member->address_one!="" ? $member->address_one.', ' : '' }}
					{{ $member->address_two!="" ? $member->address_two.', ' : '' }}
					{{ $member->city_name!="" ? $member->city_name.' - ' : '' }}
					{{ $member->postal_code }}
				</p>
				<p>Beneficiary's Name: Self</p>
			</td>
			<td width="40%" style="padding-top: 0px;">
				<p>Branch Code: {{ $member->branch_shortcode }}</p>
				<p>Bank Code: {{ $member->short_code }}</p>
				<p> </p>
				<p>Ben I/C No: </p>
			</td>
			<td width="10%" style="text-align:center;padding-top: 0px;">
				
			</td>
		</tr>
	</table>
</div>
<table id="page-length-option" class="display table2excel" width="100%">
		<thead>
				
			<tr class="" style="" width="100%">
				<!--th style="border: 1px solid #988989 !important;">S.NO</th-->
				<th style="border-bottom: 1px solid #988989 !important;">M/NO</th>
				<th style="border-bottom: 1px solid #988989 !important;">NAME</th>
				<th style="border-bottom: 1px solid #988989 !important;" class="nric_no">I/C NO:</th>
				<th  style="border-bottom: 1px solid #988989 !important;">PREVIOUS BALANCE</th>
				<th  style="border-bottom: 1px solid #988989 !important;">CURRENT PAYMENT</th>
				<th  style="border-bottom: 1px solid #988989 !important;">BALANCE <br> TO-DATE</th>
				<th  style="border-bottom: 1px solid #988989 !important;">ACCRUED BENEFIT</th>
				<th style="border-bottom: 1px solid #988989 !important;">ACCRUED INSURANCE</th>
				<th  style="border-bottom: 1px solid #988989 !important;">ARREARS MONTH</th>
				<th style="border-bottom: 1px solid #988989 !important;">AMT.DUE TO. UNION </th>
			</tr>
		</thead>
		<tbody class="" width="100%">
			@php
				$totalmembers = 0;
				$sno = 1;
				
			@endphp
			
			
			@foreach($data['history_view'] as $history)
				<tr >
					<!--td style="border: 1px solid #988989 !important; ">{{ $sno }}</td-->
					<td style="border: 1px solid #988989 !important;">{{ $member->member_number }}</td>
					<td style="border: 1px solid #988989 !important;">{{ $member->name }}</td>
					<td class="nric_no" style="border: 1px solid #988989 !important;">{{ $member->new_ic }}</td>
					<td style="border: 1px solid #988989 !important;">{{ $history->ACCSUBSCRIPTION }}</td>
					<td style="border: 1px solid #988989 !important;" >{{ $history->TOTALSUBCRP_AMOUNT }}</td>
					<td  style="border: 1px solid #988989 !important;">{{ $history->ACCSUBSCRIPTION+($history->TOTALSUBCRP_AMOUNT) }}</td>
					<td style="border: 1px solid #988989 !important;">{{  $history->ACCBF }}</td>
					<td style="border: 1px solid #988989 !important;">{{  $history->ACCINSURANCE }}</td>
					<td style="border: 1px solid #988989 !important;">{{  $history->TOTALMONTHSDUE }}</td>
					<td style="border: 1px solid #988989 !important;">{{  $history->SUBSCRIPTIONDUE }}</td>
					
				</tr> 
				
				@php
					$sno++;
				@endphp
			@endforeach
			<!-- //@if(!empty($data['member_view']))
			//@endif -->
			
		</tbody>
		
	</table>
	<br>