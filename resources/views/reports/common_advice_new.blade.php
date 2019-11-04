@php 
	$logo = CommonHelper::getLogo(); 
@endphp
<table id="page-length-option" class="display table2excel" width="100%" data-tableName="New Advice Members Report">
	<thead>

		<tr class="" style="width: 100%;">
			
			<td colspan="2" rowspan="2">
				<img src="{{ asset('public/assets/images/logo/'.$logo) }}" height="50" />
			</td>
			<td colspan="7" style="text-align:center;padding:10px;vertical-align:top;">
				<span style="text-align:center;font-weight: bold;font-size:18px;vertical-align:top;">NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</span>
				
			</td>
			<td colspan="4" rowspan="2">	
				</br>
			</td>
		</tr>
		<tr class="" style="width: 100%;">
			
			<td colspan="7" style="text-align:center;padding:10px;font-weight: bold;">
			
				<span style="margin-top:0;">NUBE BRANCH'S ADVICE LIST</span>
			</td>
			
		</tr>
		<tr class="" style="width: 100%;font-weight: bold;">
			
			<td colspan="2" style="border-bottom: 1px solid #988989 !important;">
				To Branch Hons. Secretary
				@if($data['unionbranch_id']!='')
					<br>
					Branch Name : {{ $data['unionbranch_name'] }}
				@endif
			</td>
			<td colspan="7" align="center" style="text-align:center;vertical-align:top;border-bottom: 1px solid #988989 !important;">
				{{ date('d M Y',strtotime($data['from_date'])) }} - {{ date('d M Y',strtotime($data['to_date'])) }}
			</td>
			<td colspan="4" style="border-bottom: 1px solid #988989 !important;">	
				
				@if($data['unionbranch_id']!='')
					<br>
					Branch Code : {{ $data['unionbranch_id'] }}
				@endif
			</td>
		</tr>
		<tr class="" >
			<th align="center" style="border: 1px solid #988989 !important;">SNO</th>
            <th style="border: 1px solid #988989 !important;">{{__('NAME')}}</th>
            <th style="border: 1px solid #988989 !important;">{{__('I.C.NO')}}</th>
            <th style="border: 1px solid #988989 !important;">{{__('BANK')}}</th>
            <th style="border: 1px solid #988989 !important;">{{__('BANK BRANCH')}}</th>
            <th style="border: 1px solid #988989 !important;">{{__('M/NO')}}</th>
            <th style="border: 1px solid #988989 !important;">{{__('DOJ')}}</th>
            <th style="border: 1px solid #988989 !important;">{{__('CLERK')}}</th>
            <th style="border: 1px solid #988989 !important;">{{__('ENT FEE')}}</th>
            <th style="border: 1px solid #988989 !important;">{{__('SUBS')}}</th>
            <th style="border: 1px solid #988989 !important;">{{__('B/F')}}</th>
            <th style="border: 1px solid #988989 !important;">{{__('HQ')}}</th>
            <th style="border: 1px solid #988989 !important;">{{__('RMK')}}</th>
		</tr>
	</thead>
	<tbody class="tbody-area" width="100%">
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
				<td style="border: 1px solid #988989 !important;">{{ $sno }}</td>
                <td style="border: 1px solid #988989 !important;">{{ $member->name }}</td>
                <td style="border: 1px solid #988989 !important;">{{ $member->ic }}</td>
                <td style="border: 1px solid #988989 !important;">{{ $member->companycode }}</td>
                <td style="border: 1px solid #988989 !important;">{{ $member->branch_name }}</td>
                <td style="border: 1px solid #988989 !important;">{{ $member->member_number }}</td>
               
                <td style="border: 1px solid #988989 !important;">{{ date("d/M/Y",strtotime($member->doj)) }}</td>
                <td style="border: 1px solid #988989 !important;text-align: center;" align="center">{{ $member->designation_name }}</td>
                
                <td style="border: 1px solid #988989 !important;">{{ $data['ent_amount'] }}</td>
                <td style="border: 1px solid #988989 !important;">{{ $member->subs }}</td>
                <td style="border: 1px solid #988989 !important;">{{ $data['bf_amount'] }}</td>
                <td style="border: 1px solid #988989 !important;">{{ $data['hq_amount'] }}</td>
                <td style="border: 1px solid #988989 !important;text-align: center;" align="center">{{ 'N' }}</td>
			</tr> 
			@php
				$sno++;
				$total_ent_amount += $data['ent_amount'];
				$total_bf_amount += $data['bf_amount'];
				$total_hq_amount += $data['hq_amount'];
				$total_subs += $member->subs;
			@endphp
		@endforeach
		<tr style="font-weight:bold;">
			<td colspan="2" style="font-weight:bold;border: 1px solid #988989 !important;">Total Member's Count </td>
			<td style="border: 1px solid #988989 !important;" align="left" colspan="6"> {{ $sno-1 }}</td>
			<td style="border: 1px solid #988989 !important;">{{ $total_ent_amount }}</td>
			<td style="border: 1px solid #988989 !important;">{{ $total_subs }}</td>
			<td style="border: 1px solid #988989 !important;">{{ $total_bf_amount }}</td>
			<td style="border: 1px solid #988989 !important;">{{ $total_hq_amount }}</td>
			<td style="border: 1px solid #988989 !important;"></td>
		</tr> 
		@php
			$total_paid = round($total_ent_amount + $total_subs + $total_bf_amount + $total_hq_amount,2);
		@endphp
		<tr>
			<td colspan="2" style="font-weight:bold;border: 1px solid #988989 !important;">Total Amount Collected </td>
			<td colspan="11" style="font-weight:bold;border: 1px solid #988989 !important;" align="left">{{ $total_paid }}</td>
		</tr> 
		<tr>
			<td colspan="13"></td>
			
		</tr> 
		<tr>
			<td colspan="8"></td>
			<td colspan="5" style="text-align: center;margin-top: 100px;vertical-align:top;font-weight:bold;" align="center">
				Your Fraternally,
			</td>
		</tr>
	
		<tr>
			<td colspan="13" rowspan="2"></td>
		</tr> 
		<tr>
			<td colspan="13"></td>
		</tr> 
		<tr>
			<td colspan="8"></td>
			<td colspan="5" style="margin-top: 100px;text-align: center;font-weight:bold;border-top: 1px solid black !important;" align="center">
				Hons. General Secretary
				</br>
				NUBE H.Q
			</td>
		</tr>
	</tbody>
	
</table>