@php 
	$logo = CommonHelper::getLogo(); 
@endphp
<table id="page-length-option" class="display" width="100%">
		<thead>
			<tr class="">
				
				<td colspan="2" rowspan="2" style="text-align:right">
					<img src="{{ asset('public/assets/images/logo/'.$logo) }}" height="50" />
				</td>
				<td colspan="6" style="text-align:center;padding:10px;vertical-align:top;">
					<span style="text-align:center;font-weight: bold;font-size:18px;vertical-align:top;">NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</span>
					
				</td>
				<td colspan="2" rowspan="2">	
					</br>
				</td>
			</tr>
			<tr class="">
				
				<td colspan="6" style="text-align:center;padding:10px;font-weight: bold;">
				
					<span style="margin-top:0;">UNION BRANCH'S ADVICE LIST</span>
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
				<td colspan="6" align="center" style="text-align:center;vertical-align:top;border-bottom: 1px solid #988989 !important;">
					{{ date('d M Y',strtotime($data['from_date'])) }} - {{ date('d M Y',strtotime($data['to_date'])) }}
				</td>
				<td colspan="2" style="border-bottom: 1px solid #988989 !important;">	
					
					@if($data['unionbranch_id']!='')
						<br>
						Branch Code : {{ $data['unionbranch_id'] }}
					@endif
				</td>
			</tr>
			<tr class="" >
				<th style="border : 1px solid #988989;" align="center">SNO</th>
                <th style="border : 1px solid #988989;">{{__('NAME')}}</th>
                <th style="border : 1px solid #988989;">{{__('I.C.NO')}}</th>
                <th style="border : 1px solid #988989;">{{__('BANK')}}</th>
                <th style="border : 1px solid #988989;">{{__('BANK BRANCH')}}</th>
                <th style="border : 1px solid #988989;">{{__('M/NO')}}</th>
                <th style="border : 1px solid #988989;">{{__('DOJ')}}</th>
                <th style="border : 1px solid #988989;">{{__('CLERK')}}</th>
                <th style="border : 1px solid #988989;">{{__('ENT FEE')}}</th>
                <th style="border : 1px solid #988989;">{{__('SUBS')}}</th>
                <th style="border : 1px solid #988989;">{{__('B/F')}}</th>
                <th style="border : 1px solid #988989;">{{__('HQ')}}</th>
                <th style="border : 1px solid #988989;">{{__('RMK')}}</th>
			</tr>
		</thead>
		<tbody class="" width="100%">
			@php
				$sno = 1;
				$total_paid = 0;
				$total_ent_amount = 0;
				$total_bf_amount = 0;
				$total_hq_amount = 0;
				$total_subs = 0;
			@endphp
			@foreach($data['member_view'] as $member)
				<tr>
					<td style="border : 1px solid #988989;">{{ $sno }}</td>
                    <td style="border : 1px solid #988989;">{{ $member->name }}</td>
                    <td style="border : 1px solid #988989;">{{ $member->ic }}</td>
                    <td style="border : 1px solid #988989;">{{ $member->companycode }}</td>
                    <td style="border : 1px solid #988989;">{{ $member->branch_name }}</td>
                    <td style="border : 1px solid #988989;">{{ $member->member_number }}</td>
                   
                    <td style="border : 1px solid #988989;">{{ date("d/M/Y",strtotime($member->doj)) }}</td>
                    <td style="border : 1px solid #988989;">{{ $member->designation_name }}</td>
                    
                    <td style="border : 1px solid #988989;">{{ $data['ent_amount'] }}</td>
                    <td style="border : 1px solid #988989;">{{ $member->subs }}</td>
                    <td style="border : 1px solid #988989;">{{ $data['bf_amount'] }}</td>
                    <td style="border : 1px solid #988989;">{{ $data['hq_amount'] }}</td>
                    <td style="border : 1px solid #988989;">{{ '' }}</td>
				</tr> 
				@php
					$sno++;
					$total_ent_amount += $data['ent_amount'];
					$total_bf_amount += $data['bf_amount'];
					$total_hq_amount += $data['hq_amount'];
					$total_subs += $member->subs;
				@endphp
			@endforeach
			<tr>
				<td colspan="2" style="border : 1px solid #988989;">Total Member's Count </td>
				<td style="border : 1px solid #988989;"> {{ $sno-1 }}</td>
				<td colspan="5" style="border : 1px solid #988989;"> </td>
				<td style="border : 1px solid #988989;">{{ $total_ent_amount }}</td>
				<td style="border : 1px solid #988989;">{{ $total_subs }}</td>
				<td style="border : 1px solid #988989;">{{ $total_bf_amount }}</td>
				<td style="border : 1px solid #988989;">{{ $total_hq_amount }}</td>
				<td style="border : 1px solid #988989;"></td>
			</tr> 
			@php
				$total_paid = round($total_ent_amount + $total_subs + $total_bf_amount + $total_hq_amount,2);
			@endphp
			<tr>
				<td colspan="2" style="border : 1px solid #988989;">Total Amount Collected </td>
				<td colspan="1" style="border : 1px solid #988989;">{{ $total_paid }}</td>
				<td colspan="10" style="border : 1px solid #988989;"></td>
			</tr> 
		</tbody>
		
	</table>