@php 
	$logo = CommonHelper::getLogo(); 
@endphp
<table id="page-length-option" class="display" width="100%">
		<thead>
			<tr class="">
				
				<td colspan="3" style="text-align:center;padding:0 10px;vertical-align:center;" width="100%">
					
					<img src="{{ asset('public/assets/images/logo/'.$logo) }}" height="50" style="margin-right: 20px;" />
					</br>
					<span style="text-align:center;font-weight: bold;font-size:18px;vertical-align:center;">NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</span>
					
				</td>
				
			</tr>
			<tr class="">
				
				<td colspan="3" style="text-align:center;padding:10px;font-weight: bold;">
				
					<span style="margin-top:0;">NUBE RETIREMENT INSURANCE SCHEME</span>
				</td>
				
			</tr>
			<tr class="" style="font-weight: bold;">
			
				<td colspan="3" align="center" style="text-align:center;vertical-align:top;border-bottom: 1px solid #988989 !important;">
					{{ date('01 M Y',strtotime($data['month_year'])) }} - {{ date('t M Y',strtotime($data['month_year'])) }}
				</td>
				
			</tr>
			<tr class="">
				<th style="border: 1px solid #988989 !important;">{{__('BANK')}}</th>
				<th style="border: 1px solid #988989 !important;">{{__('TOTAL MEMBERS')}}</th>
				<th style="border: 1px solid #988989 !important;">{{__('AMOUNT(RM)')}}</th>
			</tr>
		</thead>
		<tbody class="" width="100%">
			@php
				$totalamt = 0;
				$totalmembers = 0;
				$sno = 1;
			@endphp
			@foreach($data['head_company_view'] as $company)
				@php
					$company_data = CommonHelper::getMontendcompanySummary($company['company_list'],$data['month_year_full']);
					//dd($company_data);
				@endphp
				@if($company_data->total_members>0)
				<tr>
					<td style="border: 1px solid #988989 !important;">{{$company['companycode']}}</td>
					<td style="border: 1px solid #988989 !important;">{{ $company_data->total_members }}</td>
					<td style="border: 1px solid #988989 !important;">{{ number_format($data['total_ins']*$company_data->total_members,2,".",",") }}</td>
				</tr> 
				@php
					$totalamt += $data['total_ins']*$company_data->total_members;
					$totalmembers += $company_data->total_members;
					$sno++;
				@endphp
				@endif
			@endforeach
			<tr style="font-weight:bold;font-size:16px;">
				<td style="border: 1px solid #988989 !important;"> Grand Total </td>
				<td style="border: 1px solid #988989 !important;">{{ $totalmembers }}</td>
				<td style="border: 1px solid #988989 !important;">{{ number_format($totalamt,2,".",",") }}</td>
			</tr> 
			<tr style="font-weight:bold;font-size:16px;">
				<td colspan="3" style="border: 1px solid #988989 !important;text-align:right;padding-right:0px;">FACILITATION FEES FOR THE MONTH OF {{$data['month_year_read']}}</td>
			</tr>
			<tr style="font-weight:bold;font-size:16px;">
				<td colspan="3" style="border: 1px solid #988989 !important;text-align:right">5% : {{ number_format((($totalamt*5)/100),2,".",",") }}</td>
			</tr>
		</tbody>
		
	</table>