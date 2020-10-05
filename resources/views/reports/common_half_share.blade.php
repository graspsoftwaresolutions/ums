@php 
	$logo = CommonHelper::getLogo();
	if(!isset($data['nobreak'])){
		$imgurl = asset('public/assets/images/logo/'.$logo);
	}else{
		$imgurl = public_path('/assets/images/logo/logo.png');
	}	
@endphp
	<table id="page-length-option" class="display" width="100%">
		<thead>
			<tr class="">
				
				<td colspan="1" rowspan="2" style="text-align:right">
					<img src="{{ $imgurl }}" height="50" />
				</td>
				<td colspan="6" style="text-align:center;padding:10px;vertical-align:top;">
					<span style="text-align:center;font-weight: bold;font-size:18px;vertical-align:top;">NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</span>
					
				</td>
				<td colspan="1" rowspan="2">
					@if(!isset($data['nobreak']))	
					</br>
					@endif
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
					@if(!isset($data['nobreak']))	
					</br>
					@endif
					SUMMARY REMITTANCE MONTH : {{ strtoupper(date('M Y',strtotime($data['month_year']))) }} 
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
	            $unionlist = CommonHelper::getUnionListAll();
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
                $totall = round($tot,2);
                $total_all += $totall;
                $hlf_sr = round($sub_amt / 2,2);
                $hlf += $hlf_sr;
                $tenper = round($hlf_sr * 10/100,2);
                $t_per +=$tenper;
                $balamtgn = round($hlf_sr - $tenper,2);
                $bl_amt += $balamtgn;
                @endphp
                    <tr>
                        <td style="border: 1px solid #988989 !important;">{{ $hlfshre->union_branch }}</td>
                        <td style="border: 1px solid #988989 !important;">{{ round($totall,2) }}</td>
                        <td style="border: 1px solid #988989 !important;">{{ round($bf_amount,2) }}</td>
                        <td style="border: 1px solid #988989 !important;">{{ round($ins_amount,2) }}</td>
                        <td style="border: 1px solid #988989 !important;">{{ round($sub_amt,2) }}</td>
                        <td style="border: 1px solid #988989 !important;">{{ round(($sub_amt/2),2) }}</td>
                        <td style="border: 1px solid #988989 !important;">{{ round((($sub_amt/2)*10/100),2) }}</td>
                        <td style="border: 1px solid #988989 !important;">{{ round(($sub_amt/2)-(($sub_amt/2)*10/100),2) }}</td>
                        
                    </tr> 
                @endforeach
                @endif
                <tr style="font-weight:bold;">
					@php
						$halfsub = ($total_all-($bf+$ins))/2;
						$halfsubper = (($total_all-($bf+$ins))/2)*(10/100);
						$toh_hs = round($halfsub,2) - round($halfsubper,2);
					@endphp
                        <td style="border: 1px solid #988989 !important;font-weight: bold;">Total</td>
                        <td style="border: 1px solid #988989 !important;font-weight: bold;">{{ round($total_all,2) }}</td>
                        <td style="border: 1px solid #988989 !important;font-weight: bold;">{{ round($bf,2) }}</td>
                        <td style="border: 1px solid #988989 !important;font-weight: bold;">{{ round($ins,2) }}</td>
                        <td style="border: 1px solid #988989 !important;font-weight: bold;">{{ round($sub,2) }}</td>
                        <td style="border: 1px solid #988989 !important;font-weight: bold;">{{ round($halfsub,2) }}</td>
                        <td style="border: 1px solid #988989 !important;font-weight: bold;">{{ round($halfsubper,2) }}</td>
                        <td style="border: 1px solid #988989 !important;font-weight: bold;">{{ round($toh_hs,2) }}</td>
                </tr>
	
			
		</tbody>
		
	</table>
	@if(!isset($data['nobreak']))	
	</br>
	@endif
	@php
		$struckoffmembers = CommonHelper::getStatusMembers($data['month_year'],3);
		$slno=1;
	@endphp
	@if(count($struckoffmembers)>0)
	<table>
		<thead>
			<tr>
				<th colspan="7" align="center" style="text-align: center;font-weight: bold;">STRUCKOFF MEMBERS</th>
			</tr>
			<tr>
				<th style="border: 1px solid #988989 !important;font-weight: bold;">SNO</th>
				<th style="border: 1px solid #988989 !important;font-weight: bold;">M/NO</th>
				<th style="border: 1px solid #988989 !important;font-weight: bold;">MEMBER NAME</th>
				<th style="border: 1px solid #988989 !important;font-weight: bold;">NRIC</th>
				<th style="border: 1px solid #988989 !important;font-weight: bold;">BANK</th>
				<th style="border: 1px solid #988989 !important;font-weight: bold;">BANK BRANCH</th>
				<th style="border: 1px solid #988989 !important;font-weight: bold;">AMOUNT</th>
			</tr>
		</thead>
		<tbody>
			@foreach($struckoffmembers as $struckoff)
			<tr>
				<td style="border : 1px solid #988989;">{{ $slno }}</td>
				<td style="border : 1px solid #988989;">{{ $struckoff->member_number }}</td>
				<td style="border : 1px solid #988989;">{{ $struckoff->name }}</td>
				<td style="border : 1px solid #988989;">{{ $struckoff->NRIC }}</td>
				<td style="border : 1px solid #988989;">{{ $struckoff->companycode }}</td>
				<td style="border : 1px solid #988989;">{{ $struckoff->branch_name }}</td>
				<td style="border : 1px solid #988989;">{{ $struckoff->Amount }}</td>
			</tr>
			@php
				$slno++;
			@endphp
			@endforeach
		</tbody>
	</table>
	@endif
	@if(!isset($data['nobreak']))	
	</br>
	@endif
	@php
		$resignmembers = CommonHelper::getStatusMembers($data['month_year'],4);
		$slno=1;
	@endphp
	@if(count($resignmembers)>0)
	<table>
		<thead>
			<tr>
				<th colspan="7" align="center" style="text-align: center;font-weight: bold;">RESIGNED MEMBERS</th>
			</tr>
			<tr>
				<th style="border: 1px solid #988989 !important;font-weight: bold;">SNO</th>
				<th style="border: 1px solid #988989 !important;font-weight: bold;">M/NO</th>
				<th style="border: 1px solid #988989 !important;font-weight: bold;">MEMBER NAME</th>
				<th style="border: 1px solid #988989 !important;font-weight: bold;">NRIC</th>
				<th style="border: 1px solid #988989 !important;font-weight: bold;">BANK</th>
				<th style="border: 1px solid #988989 !important;font-weight: bold;">BANK BRANCH</th>
				<th style="border: 1px solid #988989 !important;font-weight: bold;">AMOUNT</th>
			</tr>
		</thead>
		<tbody>
			@foreach($resignmembers as $struckoff)
			<tr>
				<td style="border : 1px solid #988989;">{{ $slno }}</td>
				<td style="border : 1px solid #988989;">{{ $struckoff->member_number }}</td>
				<td style="border : 1px solid #988989;">{{ $struckoff->name }}</td>
				<td style="border : 1px solid #988989;">{{ $struckoff->NRIC }}</td>
				<td style="border : 1px solid #988989;">{{ $struckoff->companycode }}</td>
				<td style="border : 1px solid #988989;">{{ $struckoff->branch_name }}</td>
				<td style="border : 1px solid #988989;">{{ $struckoff->Amount }}</td>
			</tr>
			@php
				$slno++;
			@endphp
			@endforeach
			<tr>
					<td colspan="7">
							@if(isset($breakexcel))
								<br/>
								<br/>
								<br/>
							@endif
					</td>
				</tr>
		</tbody>
	</table>
	@endif
	<div class="pagebreak"> </div>
	@foreach($unionlist as $union)
		@php
			$data_half_share = CommonHelper::getUnionBranchHalfshare($union->id,$data['month_year']);
			$unionno = '';
			if(isset($data['nobreak'])){
				$unionno = $union->id;
			}
		@endphp
		@if(count($data_half_share)>0)
		<table id="page-length-option{{$unionno}}" class="display" width="100%">
		<thead>
			<tr class="">
				
				<td colspan="1" rowspan="2" style="text-align:right">
					<img src="{{ $imgurl }}" height="50" />
				</td>
				<td colspan="6" style="text-align:center;padding:10px;vertical-align:top;">
					<span style="text-align:center;font-weight: bold;font-size:18px;vertical-align:top;">NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</span>
					
				</td>
				<td colspan="1" rowspan="2">	
					@if(!isset($data['nobreak']))	
					</br>
					@endif
				</td>
			</tr>
			<tr class="">
				
				<td colspan="6" style="text-align:center;padding:10px;font-weight: bold;">
				
					<span style="margin-top:0;">HALF SHARE REPORT</span>
				</td>
				
			</tr>
			<tr class="" style="font-weight: bold;">
			
				<td colspan="1" style="border-bottom: 1px solid #988989 !important;">
					@if(!isset($data['nobreak']))	
					</br>
					@endif
					UNION BRANCH : {{ CommonHelper::getUnionBranchName($union->id) }}
				</td>
				<td colspan="6" align="center" style="text-align:center;vertical-align:top;border-bottom: 1px solid #988989 !important;font-weight: bold;">
					NATIONAL UNION BANK OF EMPLOYEES
					@if(!isset($data['nobreak']))	
					</br>
					@endif
					SUMMARY REMITTANCE MONTH : {{ strtoupper(date('M Y',strtotime($data['month_year']))) }} 
				</td>
				<td colspan="1" style="border-bottom: 1px solid #988989 !important;">	
					
				</td>
			</tr>
			<tr class="" >
				<th style="border: 1px solid #988989 !important;">{{__('BANK NAME')}}</th>
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
                @foreach($data_half_share as $hlfshre)
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
                $totall = round($tot,2);
                $total_all += $totall;
                $hlf_sr = round($sub_amt / 2,2);
                $hlf += $hlf_sr;
                $tenper = round($hlf_sr * 10/100,2);
                $t_per +=$tenper;
                $balamtgn = round($hlf_sr - $tenper,2);
                $bl_amt += $balamtgn;
                @endphp
                    <tr>
                        <td style="border: 1px solid #988989 !important;">{{ $hlfshre->company_name }}</td>
                        <td style="border: 1px solid #988989 !important;">{{ round($totall,2) }}</td>
                        <td style="border: 1px solid #988989 !important;">{{ round($bf_amount,2) }}</td>
                        <td style="border: 1px solid #988989 !important;">{{ round($ins_amount,2) }}</td>
                        <td style="border: 1px solid #988989 !important;">{{ round($sub_amt,2) }}</td>
                        <td style="border: 1px solid #988989 !important;">{{ round(($sub_amt/2),2) }}</td>
                        <td style="border: 1px solid #988989 !important;">{{ round((($sub_amt/2)*10/100),2) }}</td>
                        <td style="border: 1px solid #988989 !important;">{{ round(($sub_amt/2)-(($sub_amt/2)*10/100),2) }}</td>
                        
                    </tr> 
                @endforeach
                @endif
                <tr style="font-weight:bold;">
					@php
						$halfsub = ($total_all-($bf+$ins))/2;
						$halfsubper = (($total_all-($bf+$ins))/2)*(10/100);
						$toh_hs = round($halfsub,2) - round($halfsubper,2);
					@endphp
                        <td style="border: 1px solid #988989 !important;font-weight: bold;">Total</td>
                        <td style="border: 1px solid #988989 !important;font-weight: bold;">{{ round($total_all,2) }}</td>
                        <td style="border: 1px solid #988989 !important;font-weight: bold;">{{ round($bf,2) }}</td>
                        <td style="border: 1px solid #988989 !important;font-weight: bold;">{{ round($ins,2) }}</td>
                        <td style="border: 1px solid #988989 !important;font-weight: bold;">{{ round($sub,2) }}</td>
                        <td style="border: 1px solid #988989 !important;font-weight: bold;">{{ round($halfsub,2) }}</td>
                        <td style="border: 1px solid #988989 !important;font-weight: bold;">{{ round($halfsubper,2) }}</td>
                        <td style="border: 1px solid #988989 !important;font-weight: bold;">{{ round($toh_hs,2) }}</td>
                </tr>
                @if(isset($breakexcel))
				<tr>
					<td colspan="8">
							
								<br/>
								<br/>
								<br/>
							
					</td>
				</tr>
				@endif
		</tbody>
		
	</table>
	<div class="pagebreak"> </div>
	@endif
	@endforeach