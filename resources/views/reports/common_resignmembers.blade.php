@php 
	$logo = CommonHelper::getLogo(); 
@endphp
<table id="page-length-option" class="display table2excel" width="100%">
		<thead>
			<tr class="">
				
				<td colspan="4" rowspan="2" style="text-align:right">
					<img src="{{ asset('public/assets/images/logo/'.$logo) }}" height="50" />
				</td>
				<td colspan="10" style="text-align:center;padding:10px;vertical-align:top;">
					<span style="text-align:center;font-weight: bold;font-size:18px;vertical-align:top;">NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</span>
					
				</td>
				<td colspan="3" rowspan="2">	
					</br>
				</td>
			</tr>
			<tr class="">
				
				<td colspan="10" style="text-align:center;padding:10px;font-weight: bold;">
				
					<span style="margin-top:0;">RESIGNATION REPORT</span>
				</td>
				
			</tr>
			<tr class="" style="font-weight: bold;">
			
				<td colspan="4" style="border-bottom: 1px solid #988989 !important;">
					To Branch Hons. Secretary
					@if($data['unionbranch_id']!='')
						<br>
						Branch Name : {{ $data['unionbranch_name'] }}
					@endif
				</td>
				<td colspan="10" align="center" style="text-align:center;vertical-align:top;border-bottom: 1px solid #988989 !important;">
					{{ date('d M Y',strtotime($data['from_date'])) }} - {{ date('d M Y',strtotime($data['to_date'])) }}
				</td>
				<td colspan="3" style="border-bottom: 1px solid #988989 !important;">	
					
					@if($data['unionbranch_id']!='')
						<br>
						Branch Code : {{ $data['unionbranch_id'] }}
					@endif
				</td>
			</tr>
				
			<tr class="" >
				<th style="border: 1px solid #988989 !important;">SNO</th>
                <th style="border: 1px solid #988989 !important;">{{__('NAME')}}</th>
                <th style="border: 1px solid #988989 !important;">{{__('M/NO')}}</th>
                <th style="border: 1px solid #988989 !important;">{{__('IC NO')}}</th>
                <th style="border: 1px solid #988989 !important;">{{__('JOINED')}}</th>
                <th style="border: 1px solid #988989 !important;">{{__('RESIGNED')}}</th>
                <th style="border: 1px solid #988989 !important;">{{__('BCH')}}</th>
                <th style="border: 1px solid #988989 !important;">{{__('BANK')}}</th>
                <th style="border: 1px solid #988989 !important;">{{__('BANK BRANCH')}}</th>
                <th style="border: 1px solid #988989 !important;">{{__('CONTRIBUTION')}}</th>
                <th style="border: 1px solid #988989 !important;">{{__('BENEFIT')}}</th>
				<th style="border: 1px solid #988989 !important;">{{__('INSURANCE')}}</th>
                <th style="border: 1px solid #988989 !important;">{{__('TOTAL')}}</th>
                <th style="border: 1px solid #988989 !important;">{{__('PAYMODE')}}</th>
                <th style="border: 1px solid #988989 !important;">{{__('DATE')}}</th>
                <th style="border: 1px solid #988989 !important;">{{__('REASON')}}</th>
                <th style="border: 1px solid #988989 !important;">{{__('CLAIMED BY')}}</th>
			</tr>
		</thead>
		<tbody class="" width="100%">
			@php
				$sno = 1;
				$tot_benifit = 0;
				$tot_contribution = 0;
				$tot_ins = 0;
				$tot_amt = 0;
				$reasoncodes = [];
			@endphp
			@foreach($data['member_view'] as $member)
				<tr>
					<td style="border: 1px solid #988989 !important;">{{ $sno }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $member->name }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $member->member_number }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $member->new_ic }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ date("d/M/Y",strtotime($member->doj)) }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ date("d/M/Y",strtotime($member->resignation_date)) }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $member->unioncode }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $member->companycode }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $member->branch_name }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $member->contribution }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $member->benifit }}</td>
					<td style="border: 1px solid #988989 !important;">{{ $member->insuranceamount }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $member->total }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $member->paymode }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ date("d/M/Y",strtotime($member->voucher_date)) }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $member->reason_code }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $member->claimer_name }}</td>
				</tr> 
				@php
					$sno++;
					$tot_benifit += $member->benifit;
					$tot_contribution += $member->contribution;
					$tot_ins += $member->insuranceamount;
					$tot_amt += $member->total;
					if(!in_array($member->reason_code, $reasoncodes, true)){
				        array_push($reasoncodes, $member->reason_code);
				    }
				@endphp
			@endforeach
			<tr style="font-weight: bold;">
				<td colspan="8" style="border: 1px solid #988989 !important;">Total Member's Count : {{ $sno-1 }}</td>
				<td style="border: 1px solid #988989 !important;">Total</td>
				<td style="border: 1px solid #988989 !important;">{{ $tot_contribution }}</td>
				<td style="border: 1px solid #988989 !important;">{{ $tot_benifit }}</td>
				<td style="border: 1px solid #988989 !important;">{{ $tot_ins }}</td>
				<td style="border: 1px solid #988989 !important;">{{ $tot_amt }}</td>
				<td colspan="4" style="border: 1px solid #988989 !important;"></td>
				
			</tr> 
			@php
				 sort($reasoncodes,SORT_NUMERIC);
			@endphp
			@if($sno>1)
			<tr>
				<td colspan="3" style="border: none !important;">
					<table id="reason-area" width="100%">
						<tbody>
							<tr style="font-weight: bold;">
								<td style="border: 1px solid #988989 !important;">
									Code
								</td>
								<td style="border: 1px solid #988989 !important;">
									Reason
								</td>
							</tr>
							@foreach($reasoncodes as $r)
							<tr>
								<td style="border: 1px solid #988989 !important;">
									{{ $r }}
								</td>
								<td style="border: 1px solid #988989 !important;">
									{{ CommonHelper::getReasonNameBYCode($r) }}
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</td>
				
				
				
			</tr> 
			@endif
		</tbody>
		
	</table>