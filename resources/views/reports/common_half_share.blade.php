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
				
					<span style="margin-top:0;">HALF SHARE REPORT</span>
				</td>
				
			</tr>
			<tr class="" style="font-weight: bold;">
			
				<td colspan="1" style="border-bottom: 1px solid #988989 !important;">
				
				</td>
				<td colspan="6" align="center" style="text-align:center;vertical-align:top;border-bottom: 1px solid #988989 !important;font-weight: bold;">
					NATIONAL UNION BANK OF EMPLOYEES
					</br>
					SUMMARY REMITTANCE MONTH : {{ date('M Y',strtotime($data['month_year'])) }} 
				</td>
				<td colspan="1" style="border-bottom: 1px solid #988989 !important;">	
					
				</td>
			</tr>
			<tr class="" >
				<th style="border: 1px solid #988989 !important;">{{__('UNION BRANCH NAME')}}</th>
				<th style="border: 1px solid #988989 !important;">{{__('TOTAL')}}</th>
				<th style="border: 1px solid #988989 !important;">{{__('BF')}}</th>
				<th style="border: 1px solid #988989 !important;">{{__('INS')}}</th>
				<th style="border: 1px solid #988989 !important;">{{__('SUBS')}}</th>
				<th style="border: 1px solid #988989 !important;">{{__('1/2 SHARE')}}</th>
				<th style="border: 1px solid #988989 !important;">{{__('10%ED - FUND')}}</th>
				<th style="border: 1px solid #988989 !important;">{{__('TOTAL AMOUNT')}}</th>
			</tr>
		</thead>
		<tbody class="" width="100%">
            @php
            $total_all=0;
            $bf=0;
            $ins=0;
            $sub=0;
            $hlf=0;
            $t_per=0;
            $bl_amt=0;
            @endphp
                @if(!empty($data))						
                @foreach($data['half_share'] as $hlfshre)
                @php
				$bf_amount = $hlfshre->count*$data['bf_amount'];
				$ins_amount = $hlfshre->count*$data['ins_amount'];
                $bf += $bf_amount;
                $ins += $ins_amount;
				$payamount = $hlfshre->subamt;
				$total_ins = $hlfshre->count*$data['total_ins'];
				$sub_amt = $payamount-$total_ins;
                $sub += $sub_amt;
                $tot = $payamount;
                $totall = bcdiv($tot,1,2);
                $total_all += $totall;
                $hlf_sr = bcdiv($sub_amt / 2,1,2);
                $hlf += $hlf_sr;
                $tenper = bcdiv($hlf_sr * 10/100,1,2);
                $t_per +=$tenper;
                $balamtgn = bcdiv($hlf_sr - $tenper,1,2);
                $bl_amt += $balamtgn;
                @endphp
                    <tr>
                        <td style="border: 1px solid #988989 !important;">{{ $hlfshre->union_branch }}</td>
                        <td style="border: 1px solid #988989 !important;">{{ bcdiv($totall,1,2) }}</td>
                        <td style="border: 1px solid #988989 !important;">{{ bcdiv($bf_amount,1,2) }}</td>
                        <td style="border: 1px solid #988989 !important;">{{ bcdiv($ins_amount,1,2) }}</td>
                        <td style="border: 1px solid #988989 !important;">{{ bcdiv($sub_amt,1,2) }}</td>
                        <td style="border: 1px solid #988989 !important;">{{ bcdiv($hlf_sr,1,2) }}</td>
                        <td style="border: 1px solid #988989 !important;">{{ bcdiv($tenper,1,2) }}</td>
                        <td style="border: 1px solid #988989 !important;">{{ bcdiv($balamtgn,1,2) }}</td>
                        
                    </tr> 
                @endforeach
                @endif
                <tr style="font-weight:bold;">
                
                        <td style="border: 1px solid #988989 !important;font-weight: bold;">Total</td>
                        <td style="border: 1px solid #988989 !important;font-weight: bold;">{{ bcdiv($total_all,1,2) }}</td>
                        <td style="border: 1px solid #988989 !important;font-weight: bold;">{{ bcdiv($bf,1,2) }}</td>
                        <td style="border: 1px solid #988989 !important;font-weight: bold;">{{ bcdiv($ins,1,2) }}</td>
                        <td style="border: 1px solid #988989 !important;font-weight: bold;">{{ bcdiv($sub,1,2) }}</td>
                        <td style="border: 1px solid #988989 !important;font-weight: bold;">{{ bcdiv($hlf,1,2) }}</td>
                        <td style="border: 1px solid #988989 !important;font-weight: bold;">{{ bcdiv($t_per,1,2) }}</td>
                        <td style="border: 1px solid #988989 !important;font-weight: bold;">{{ bcdiv($bl_amt,1,2) }}</td>
                </tr>
	
			
		</tbody>
		
	</table>