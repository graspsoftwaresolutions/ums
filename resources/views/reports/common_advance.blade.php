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
				<td colspan="3" rowspan="2">	
					</br>
				</td>
			</tr>
			<tr class="">
				
				<td colspan="6" style="text-align:center;padding:10px;font-weight: bold;">
				
					<span style="margin-top:0;">ADVANCE REPORT</span>
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
					
				</td>
				<td colspan="3" style="border-bottom: 1px solid #988989 !important;">	
					
					@if($data['unionbranch_id']!='')
						<br>
						Branch Code : {{ $data['unionbranch_id'] }}
					@endif
				</td>
			</tr>
			
			<tr class="">
				<th style="border : 1px solid #988989;" align="center">SNO</th>
				<th style="border : 1px solid #988989;">{{__('M/NO')}}</th>
                <th style="border : 1px solid #988989;">{{__('MEMBER NAME')}}</th>
                <th style="border : 1px solid #988989;">{{__('BANK')}}</th>
                <th style="border : 1px solid #988989;">{{__('BANK BRANCH')}}</th>
              
                <th style="border : 1px solid #988989;">{{__('NRIC')}}</th>
                <th style="border : 1px solid #988989;">{{__('G')}}</th>
               
                <th style="border : 1px solid #988989;">{{__('DOJ')}}</th>
                <th style="border : 1px solid #988989;">ADVANCE MON</th>
                <th style="border : 1px solid #988989;">ADVANCE AMOUNT</th>
                <!-- <th style="border : 1px solid #988989;">{{__('STATUS')}}</th> -->
			</tr>
		</thead>
		<tbody class="" width="100%">
			@php
				$totalmembers = 0;
				$sno = 1;
				//dd($data['member_view']);
			@endphp
            @foreach($data['member_view'] as $membervalue)
            	@php
            		$memberid = $membervalue->memberid;
            		$member = CommonHelper::getMemberDetails($memberid);
            		$advance = CommonHelper::getAdvanceDetails($memberid);
            		//dd($member);
					if($member->new_ic!=""){
						$ic = $member->new_ic;
					}else if($member->old_ic!=""){
						$ic = $member->old_ic;
					}else{
						$ic = $member->employee_id;
					}
				@endphp
                <tr>
					<td style="border : 1px solid #988989;vertical-align: top;">{{ $sno }}</td>
					<td style="border : 1px solid #988989;vertical-align: top;">{{ $member->member_number }}</td>
                    <td style="border : 1px solid #988989;vertical-align: top;">{{ $member->name }}</td>
                    <td style="border : 1px solid #988989;vertical-align: top;"> {{ $member->companycode }}</td>
                    <td style="border : 1px solid #988989;vertical-align: top;">{{ $member->branch_name }}</td>
                    
                    <td style="border : 1px solid #988989;vertical-align: top;">{{ $ic }}</td>
                    <td style="border : 1px solid #988989;vertical-align: top;">{{ $member->gender[0] }}</td>
                    
                    <td style="border : 1px solid #988989;vertical-align: top;">{{ date('d/M/Y',strtotime($member->doj)) }}</td>
                    <td style="border : 1px solid #988989;vertical-align: top;"> 
                    	@php
                    		$scount = count($advance);
                    		$lastsubsamt = 0;
                    		$totalAmt = 0;
                    	@endphp
                    	@foreach($advance as $key => $alists)
                    		{{ date('M-Y',strtotime($alists->StatusMonth)) }} {{ $scount-1 != $key ? ',' : '' }} 
                    		@php
                    			if($scount-1 == $key){
                    				$advanceats = CommonHelper::getAdvanceAmount($memberid,$alists->StatusMonth);
                    				if($advanceats==null){
                    					$advanceone = CommonHelper::getAdvanceNextAmount($memberid,$alists->StatusMonth);
                    					$lastsubsamt = $advanceone->SUBSCRIPTION_AMOUNT+$advanceone->BF_AMOUNT+$advanceone->INSURANCE_AMOUNT;
                    				}else{
                    					$lastsubsamt = $advanceats->SUBSCRIPTION_AMOUNT+$advanceats->BF_AMOUNT+$advanceats->INSURANCE_AMOUNT;
                    				}
                    				$dues = CommonHelper::getCurrentDues($memberid);
                    				$totalAmt = $dues>=0 ? 0 : abs($dues)*$lastsubsamt;
                    			}
                    		@endphp
                    	@endforeach
                    	
                    </td>
                     <td style="border : 1px solid #988989;vertical-align: top;">{{ $totalAmt }}</td>
                   <!--  <td style="border : 1px solid #988989;">{{ $member->status_name[0] }}</td>	 -->
                </tr> 
				@php
					$sno++;
				@endphp
            @endforeach
           
		</tbody>
		
	</table>