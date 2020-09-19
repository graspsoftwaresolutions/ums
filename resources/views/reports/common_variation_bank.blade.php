@php $logo = CommonHelper::getLogo(); @endphp
<table id="page-length-option" class="display" width="100%">
		<thead>
			<tr class="">
				
				<td colspan="1" rowspan="2" style="text-align:right">
					<img src="{{ asset('public/assets/images/logo/'.$logo) }}" height="50" />
				</td>
				<td colspan="4" style="text-align:center;padding:10px;vertical-align:top;">
					<span style="text-align:center;font-weight: bold;font-size:18px;vertical-align:top;">NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</span>
					
				</td>
				<td colspan="1" rowspan="2">	
					</br>
				</td>
			</tr>
			<tr class="">
				
				<td colspan="4" style="text-align:center;padding:10px;font-weight: bold;">
				
					<span style="margin-top:0;">VARIATION BANK REPORT</span>
				</td>
				
			</tr>
			<tr class="" style="font-weight: bold;">
			
				<td colspan="1" style="border-bottom: 1px solid #988989 !important;">
					To Branch Hons. Secretary
					
				</td>
				<td colspan="4" align="center" style="text-align:center;vertical-align:top;border-bottom: 1px solid #988989 !important;">
					{{ date('01 M Y',strtotime($data['month_year'])) }} - {{ date('t M Y',strtotime($data['month_year'])) }}
				</td>
				<td colspan="1" style="border-bottom: 1px solid #988989 !important;">	
					
					
				</td>
			</tr>
			<tr class="" >
				<th style="border : 1px solid #988989;">{{__('BANK NAME')}}</th>
				<th style="border : 1px solid #988989;">{{ strtoupper(date('M Y',strtotime($data['month_year']))) }}</th>
				<th style="border : 1px solid #988989;">{{ strtoupper(date('M Y',strtotime($data['last_month_year']))) }}</th>
				<th style="border : 1px solid #988989;">{{__('DIFFERENT')}}</th>
				<th style="border : 1px solid #988989;">{{__('UNPAID')}}</th>
				<th style="border : 1px solid #988989;">{{__('PAID')}}</th>
			</tr>
		</thead>
		<tbody class="" width="100%">
			@php
				$totalno21=0;
				$totalno22=0;
				$totalno23=0;
				$totalno24=0;
				$totalno25=0;
			@endphp
            @foreach($data['company_view'] as $company)
                    @php
                        $current_count = CommonHelper::getMonthlyPaidCount($company->cid,$data['month_year']);
                        $last_month_count = CommonHelper::getMonthlyPaidCount($company->cid,$data['last_month_year']);
                        $member_sub_link = URL::to(app()->getLocale().'/sub-company-members/'.Crypt::encrypt($company->id));
						$last_paid_count = CommonHelper::getLastMonthlyPaidCount($company->cid,$data['month_year']);
						$current_unpaid_count = CommonHelper::getcurrentMonthlyPaidCount($company->cid,$data['month_year']);
                    @endphp
                    <tr class="monthly-sub-status" data-href="{{ $member_sub_link }}">
                        <td style="border : 1px solid #988989;">{{ $company->company_name }}</td>
                        <td style="border : 1px solid #988989;">{{ $current_count }}</td>
                        <td style="border : 1px solid #988989;">{{ $last_month_count }}</td>
                        <td style="border : 1px solid #988989;"><span class="badge {{$current_count-$last_month_count>=0 ? 'green' : 'red'}}">{{ $current_count-$last_month_count }}</span></td>
                        <td style="border : 1px solid #988989;">{{ $current_unpaid_count }}</td>
                        <td style="border : 1px solid #988989;">{{ $last_paid_count }}</td>
                    </tr> 
                    @php
					
						$totalno21 += $current_count;
						$totalno22 += $last_month_count;
						$totalno24 += $current_unpaid_count;
						$totalno25 += $last_paid_count;
						
					@endphp
            @endforeach
             <tr class="" >
                    <td style="border : 1px solid #988989;">TOTAL</td>
                    <td style="border : 1px solid #988989;">{{ $totalno21 }}</td>
                    <td style="border : 1px solid #988989;">{{ $totalno22 }}</td>
                    <td style="border : 1px solid #988989;">--</td>
                    <td style="border : 1px solid #988989;">{{ $totalno24 }}</td>
                    <td style="border : 1px solid #988989;">{{ $totalno25 }}</td>
                </tr> 
		</tbody>
		
	</table>