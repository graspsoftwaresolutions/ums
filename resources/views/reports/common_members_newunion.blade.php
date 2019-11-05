@php 
	$logo = CommonHelper::getLogo(); 
@endphp
<table id="page-length-option" class="display table2excel" width="100%">
		<thead>
			<tr class="">
				
				<td colspan="2" rowspan="2">
					<img src="{{ asset('public/assets/images/logo/'.$logo) }}" height="50" />
				</td>
				<td colspan="7" style="text-align:center;padding:10px;vertical-align:top;">
					<span style="text-align:center;font-weight: bold;font-size:18px;vertical-align:top;">NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</span>
					
				</td>
				<td colspan="3" rowspan="2">	
					</br>
				</td>
			</tr>
			<tr class="">
				
				<td colspan="7" style="text-align:center;padding:10px;font-weight: bold;">
				
					<span style="margin-top:0;">Union Branch Report</span>
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
				<td colspan="7" align="center" style="text-align:center;vertical-align:top;border-bottom: 1px solid #988989 !important;">
					{{ date('d M Y',strtotime($data['from_date'])) }} - {{ date('d M Y',strtotime($data['to_date'])) }}
				</td>
				<td colspan="3" style="border-bottom: 1px solid #988989 !important;">	
					
					@if($data['unionbranch_id']!='')
						<br>
						Branch Code : {{ $data['unionbranch_id'] }}
					@endif
				</td>
			</tr>
				
			<tr class="" style="" width="100%">
				<th style="border : 1px solid #988989;" align="center">S.NO</th>
				<th style="border : 1px solid #988989;"  align="center">M/NO</th>
				<th style="border : 1px solid #988989;" align="center">MEMBER NAME</th>
				<th  style="border : 1px solid #988989;" align="center">GENDER</th>
				<th  style="border : 1px solid #988989;" align="center">BANK</th>
				<th  style="border : 1px solid #988989;" align="center">UNION BRANCH</th>
				<th  style="border : 1px solid #988989;" align="center">BANK </br>BRANCH</th>
				<th  style="border : 1px solid #988989;" align="center">TYPE</th>
				<th style="border : 1px solid #988989;" align="center">DOJ</th>
				<th  style="border : 1px solid #988989;" align="center">LAST PAID </br> DATE</th>
			</tr>
		</thead>
		<tbody class="" width="100%">
			@php
				$totalmembers = 0;
				$sno = 1;
			@endphp
			@foreach($data['member_view'] as $member)
				<tr >
					<td style=" border : 1px solid #988989;">{{ $sno }}</td>
					<td style="border : 1px solid #988989;">{{ $member->member_number }}</td>
					<td style="border : 1px solid #988989;">{{ $member->name }}</td>
					<td style="border : 1px solid #988989;">{{ $member->gender }}</td>
					<td style="border : 1px solid #988989;" >{{ $member->companycode }}</td>
					<td  style="border : 1px solid #988989;">{{ $member->union_branchname }}</td>
					<td  style="border : 1px solid #988989;">{{ $member->branch_name }}</td>
					<td style="border : 1px solid #988989;">{{ isset($member) ? $member->designation_name : ""}}</td>
					<td style="border : 1px solid #988989;">{{ date('d/M/Y',strtotime($member->doj))}}</td>
					
					<td style="border : 1px solid #988989;">{{  $member->last_paid_date!="" ? date('d/M/Y',strtotime($member->last_paid_date)) : '' }}</td>
					
				</tr> 
				
				@php
					$sno++;
				@endphp
			@endforeach
			<tr>
				<td colspan="12" style="font-weight:bold;">Total Member's Count : {{ $sno-1 }}</td>
			</tr> 
		</tbody>
		
	</table>