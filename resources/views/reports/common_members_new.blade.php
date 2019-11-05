@php 
	$logo = CommonHelper::getLogo(); 
	//dd($data);
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
				
					<span style="margin-top:0;">NEW MEMBERS REPORT</span>
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
				<th style="border: 1px solid #988989 !important;">S.NO</th>
				<th style="border: 1px solid #988989 !important;">MEMBER NAME</th>
				<th style="border: 1px solid #988989 !important;">M/NO</th>
				<th style="border: 1px solid #988989 !important;" class="nric_no">NRIC</th>
				<th  style="border: 1px solid #988989 !important;">GENDER</th>
				<th  style="border: 1px solid #988989 !important;">BANK</th>
				<th  style="border: 1px solid #988989 !important;">BANK BRANCH</th>
				<th  style="border: 1px solid #988989 !important;">TYPE</th>
				<th style="border: 1px solid #988989 !important;">DOJ</th>
				<th  style="border: 1px solid #988989 !important;">LEVY</th>
				<th style="border: 1px solid #988989 !important;">TDF</th>
				<th  style="border: 1px solid #988989 !important;">LAST PAID DATE</th>
			</tr>
		</thead>
		<tbody class="" width="100%">
			@php
				$totalmembers = 0;
				$sno = 1;
				
			@endphp
			
			
			@foreach($data['member_view'] as $member)
				<tr >
					<td style="border: 1px solid #988989 !important; ">{{ $sno }}</td>
					<td style="border: 1px solid #988989 !important;">{{ $member->name }}</td>
					<td style="border: 1px solid #988989 !important;">{{ $member->member_number }}</td>
					<td class="nric_no" style="border: 1px solid #988989 !important;">{{ $member->ic }}</td>
					<td style="border: 1px solid #988989 !important;">{{ $member->gender }}</td>
					<td style="border: 1px solid #988989 !important;" >{{ $member->companycode }}</td>
					<td  style="border: 1px solid #988989 !important;">{{ $member->branch_name }}</td>
					<td style="border: 1px solid #988989 !important;">{{ isset($member) ? $member->designation_name : ""}}</td>
					<td style="border: 1px solid #988989 !important;">{{ date('d/M/Y',strtotime($member->doj))}}</td>
					
					<td style="border: 1px solid #988989 !important;">{{  $member->levy }}</td>
					<td style="border: 1px solid #988989 !important;">{{  $member->tdf }}</td>
				
					<td style="border: 1px solid #988989 !important;">{{  $member->last_paid_date!="" ? date('d/M/Y',strtotime($member->last_paid_date)) : '' }}</td>
					
				</tr> 
				
				@php
					$sno++;
				@endphp
			@endforeach
			<!-- //@if(!empty($data['member_view']))
			//@endif -->
			<tr>
				<td colspan="12" style="font-weight:bold;">Total Member's Count : {{ $sno-1 }}</td>
			</tr> 
		</tbody>
		
	</table>