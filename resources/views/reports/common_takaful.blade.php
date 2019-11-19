@php 
	$logo = CommonHelper::getLogo(); 
@endphp
<table id="page-length-option" class="display" width="100%">
		<thead>
			<tr class="">
				
				<td colspan="2" rowspan="2" style="text-align:right">
					<img src="{{ asset('public/assets/images/logo/'.$logo) }}" height="50" />
				</td>
				<td colspan="3" style="text-align:center;padding:10px;vertical-align:top;">
					<span style="text-align:center;font-weight: bold;font-size:18px;vertical-align:top;">NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</span>
					
				</td>
				<td colspan="2" rowspan="2">	
					</br>
				</td>
			</tr>
			<tr class="">
				
				<td colspan="3" style="text-align:center;padding:10px;font-weight: bold;">
				
					<span style="margin-top:0;">NUBE RETIREMENT INSURANCE SCHEME</span>
				</td>
				
			</tr>
			<tr class="" style="font-weight: bold;">
			
				<td colspan="2" style="border-bottom: 1px solid #988989 !important;">
					To Branch Hons. Secretary
					@if($data['unionbranch_id']!='')
						<br>
						Branch Name : {{ $data['unionbranch_name'] }}
					@endif
				</td>
				<td colspan="3" align="center" style="text-align:center;vertical-align:top;border-bottom: 1px solid #988989 !important;">
					{{ date('01 M Y',strtotime($data['month_year'])) }} - {{ date('t M Y',strtotime($data['month_year'])) }}
				</td>
				<td colspan="2" style="border-bottom: 1px solid #988989 !important;">	
					
					@if($data['unionbranch_id']!='')
						<br>
						Branch Code : {{ $data['unionbranch_id'] }}
					@endif
				</td>
			</tr>
			<tr class="">
				<th style="border : 1px solid #988989;">{{__('SNO')}}</th>
				<th style="border : 1px solid #988989;">{{__('BANK')}}</th>
				<th style="border : 1px solid #988989;">{{__('BRANCH')}}</th>
				<th style="border : 1px solid #988989;">{{__('NAME')}}</th>
				<th style="border : 1px solid #988989;">{{__('MEMBERID')}}</th>
				<th style="border : 1px solid #988989;">{{__('NRIC')}}</th>
				<th style="border : 1px solid #988989;">{{__('INSURANCE AMOUNT(RM)')}}</th>
			</tr>
		</thead>
		<tbody class="" width="100%">
			@php
				$totalamt = 0;
				$sno = 1;
			@endphp
			@foreach($data['member_view'] as $member)
					<tr>
						<td style="border : 1px solid #988989;">{{$sno}}</td>
						<td style="border : 1px solid #988989;">{{$member->companycode}}</td>
						<td style="border : 1px solid #988989;">{{$member->branch_name}}</td>
						<td style="border : 1px solid #988989;">{{$member->name}}</td>
						<td style="border : 1px solid #988989;">{{$member->member_number}}</td>
						<td style="border : 1px solid #988989;">{{$member->new_ic}}</td>
						<td style="border : 1px solid #988989;">{{ number_format($data['total_ins'],2,".",",") }}</td>
					</tr> 
					@php
							$totalamt += $data['total_ins'];
							$sno++;
					@endphp
			@endforeach
			<tr style="font-weight:bold;">
				<td style="border : 1px solid #988989;" colspan="6" > Total Amount</td>
				<td style="border : 1px solid #988989;">{{ number_format($totalamt,2,".",",") }}</td>
			</tr> 
			<tr style="font-weight:bold;">
				<td style="border : 1px solid #988989;" colspan="6"> Total Members</td>
				<td style="border : 1px solid #988989;">{{ $sno-1 }}</td>
			</tr> 
		</tbody>
		
		
	</table>