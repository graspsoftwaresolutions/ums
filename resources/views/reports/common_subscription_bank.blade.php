@php $logo = CommonHelper::getLogo(); @endphp
<table id="page-length-option" class="display" width="100%">
		<thead>
			<tr class="">
				
				<td colspan="1" rowspan="2" style="text-align:right">
					<img src="{{ asset('public/assets/images/logo/'.$logo) }}" height="50" />
				</td>
				<td colspan="6" style="text-align:center;padding:10px;vertical-align:top;">
					<span style="text-align:center;font-weight: bold;font-size:18px;vertical-align:top;">NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</span>
					
				</td>
				<td colspan="1" rowspan="2">	
					</br>
				</td>
			</tr>
			<tr class="">
				
				<td colspan="6" style="text-align:center;padding:10px;font-weight: bold;">
				
					<span style="margin-top:0;">SUBSCRIPTION BANK REPORT</span>
				</td>
				
			</tr>
			<tr class="" style="font-weight: bold;">
			
				<td colspan="1" style="border-bottom: 1px solid #988989 !important;">
					To Branch Hons. Secretary
					
				</td>
				<td colspan="6" align="center" style="text-align:center;vertical-align:top;border-bottom: 1px solid #988989 !important;">
					{{ date('01 M Y',strtotime($data['month_year'])) }} - {{ date('t M Y',strtotime($data['month_year'])) }}
				</td>
				<td colspan="1" style="border-bottom: 1px solid #988989 !important;">	
					
					
				</td>
			</tr>
			<tr class="">
           		<th style="border : 1px solid #988989;">{{__('BANK NAME')}}</th>
				<th style="border : 1px solid #988989;">{{__('# MEMBER')}}</th>
				<th style="border : 1px solid #988989;">{{__('TOTAL AMOUNT')}}</th>
				<th style="border : 1px solid #988989;">{{__('ACTIVE')}}</th>
				<th style="border : 1px solid #988989;">{{__('DEFAULTER')}}</th>
				<th style="border : 1px solid #988989;">{{__('STRUCKOFF')}}</th>
				<th style="border : 1px solid #988989;">{{__('RESIGNED')}}</th>
				<th style="border : 1px solid #988989;">{{__('SUNDRYCR')}}</th>
			</tr>
		</thead>
		@php 
			$get_roles = Auth::user()->roles;
			$user_role = $get_roles[0]->slug;
			$user_id = Auth::user()->id;
			$dateformat = $data['month_year'];
		@endphp 
		<tbody class="" width="100%">
            @foreach($data['company_view'] as $company)
				@php
					$active_amt = CommonHelper::statusMembersCompanyAmount(1, $user_role, $user_id,$company->id, $dateformat);
					$default_amt = CommonHelper::statusMembersCompanyAmount(2, $user_role, $user_id,$company->id, $dateformat);
					$struckoff_amt = CommonHelper::statusMembersCompanyAmount(3, $user_role, $user_id,$company->id, $dateformat);
					$resign_amt = CommonHelper::statusMembersCompanyAmount(4, $user_role, $user_id,$company->id, $dateformat);
					$sundry_amt = CommonHelper::statusSubsCompanyMatchAmount(2, $user_role, $user_id,$company->id, $dateformat);
					
					$total_members = CommonHelper::statusSubsMembersCompanyTotalCount($user_role, $user_id,$company->id,$dateformat);
					$member_sub_link = URL::to(app()->getLocale().'/sub-company-members/'.Crypt::encrypt($company->id));
				@endphp
				<tr class="monthly-sub-status" data-href="{{ $member_sub_link }}">
					<td style="border : 1px solid #988989;">{{ $company->company_name }}</td>
					<td style="border : 1px solid #988989;">{{ $total_members }}</td>
					<td style="border : 1px solid #988989;">{{ number_format(($active_amt+$default_amt+$struckoff_amt+$resign_amt+$sundry_amt), 2, '.', ',') }}</td>
					<td style="border : 1px solid #988989;">{{ number_format($active_amt,2, '.', ',') }}</td>
					<td style="border : 1px solid #988989;">{{ number_format($default_amt,2, '.', ',') }}</td>
					<td style="border : 1px solid #988989;">{{ number_format($struckoff_amt,2, '.', ',') }}</td>
					<td style="border : 1px solid #988989;">{{ number_format($resign_amt,2, '.', ',') }}</td>
					<td style="border : 1px solid #988989;">{{ number_format($sundry_amt,2, '.', ',') }}</td>
				</tr> 
			@endforeach
		</tbody>
		
	</table>