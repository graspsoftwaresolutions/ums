@php $logo = CommonHelper::getLogo(); @endphp
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
				
					<span style="margin-top:0;">DUE REPORT</span>
				</td>
				
			</tr>
			<tr class="" style="font-weight: bold;">
			
				<td colspan="2" style="border-bottom: 1px solid #988989 !important;">
					To Branch Hons. Secretary
					
				</td>
				<td colspan="6" align="center" style="text-align:center;vertical-align:top;border-bottom: 1px solid #988989 !important;">
					{{ date('01 M Y',strtotime($data['month_year'])) }} - {{ date('t M Y',strtotime($data['month_year'])) }}
				</td>
				<td colspan="2" style="border-bottom: 1px solid #988989 !important;">	
					
					
				</td>
			</tr>
			<tr class="">
          		<th style="border : 1px solid #988989;">{{__('SNO')}}</th>
				<th style="border : 1px solid #988989;">{{__('# MEMBER')}}</th>
				<th style="border : 1px solid #988989;">{{__('MEMBER NAME')}}</th>
				<th style="border : 1px solid #988989;">{{__('NRIC')}}</th>
				<th style="border : 1px solid #988989;">{{__('BANK')}}</th>
				<th style="border : 1px solid #988989;">{{__('BANK BRANCH')}}</th>
				<th style="border : 1px solid #988989;">{{__('UNION BRANCH')}}</th>
				<th style="border : 1px solid #988989;">{{__('DOJ')}}</th>
				<th style="border : 1px solid #988989;">{{__('DUE MONTH')}}</th>
				<th style="border : 1px solid #988989;">{{__('LAST PAID DATE')}}</th>
			</tr>
		</thead>
		@php 
			$get_roles = Auth::user()->roles;
			$user_role = $get_roles[0]->slug;
			$user_id = Auth::user()->id;
			$slno = 1;
		@endphp 
		<tbody class="" width="100%">
            @foreach($data['member_view'] as $member)
						
						<tr class="monthly-sub-status">
							<td style="border : 1px solid #988989;">{{ $slno }}</td>
							<td style="border : 1px solid #988989;">{{ $member->member_number }}</td>
							<td style="border : 1px solid #988989;">{{ $member->name }}</td>
							<td style="border : 1px solid #988989;">{{ $member->ic }}</td>
							<td style="border : 1px solid #988989;">{{ $member->company_name }}</td>
							<td style="border : 1px solid #988989;">{{ $member->branch_name }}</td>
							<td style="border : 1px solid #988989;">{{ $member->unionbranch }}</td>
							<td style="border : 1px solid #988989;">{{ $member->doj }}</td>
							<td style="border : 1px solid #988989;">{{ $member->TOTALMONTHSDUE }}</td>
							<td style="border : 1px solid #988989;">{{ $member->LASTPAYMENTDATE }}</td>
						</tr> 
					@php
						$slno++;
					@endphp
				@endforeach

		</tbody>

	</table>