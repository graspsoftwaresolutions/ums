@php 
	$logo = CommonHelper::getLogo(); 
@endphp
<table id="page-length-option" class="display" width="100%">
		<thead>
			<tr class="">
				
				<td colspan="2" rowspan="2" style="text-align:right">
					<img src="{{ asset('public/assets/images/logo/'.$logo) }}" height="50" />
				</td>
				<td colspan="5" style="text-align:center;padding:10px;vertical-align:top;">
					<span style="text-align:center;font-weight: bold;font-size:18px;vertical-align:top;">NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</span>
					
				</td>
				<td colspan="3" rowspan="2">	
					</br>
				</td>
			</tr>
			<tr class="">
				
				<td colspan="5" style="text-align:center;padding:10px;font-weight: bold;">
				
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
				<td colspan="5" align="center" style="text-align:center;vertical-align:top;border-bottom: 1px solid #988989 !important;">
					
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
                <th style="border : 1px solid #988989;">ADVANCE MON / SUBS</th>
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
					<td style="border : 1px solid #988989;">{{ $sno }}</td>
					<td style="border : 1px solid #988989;">{{ $member->member_number }}</td>
                    <td style="border : 1px solid #988989;">{{ $member->name }}</td>
                    <td style="border : 1px solid #988989;"> {{ $member->companycode }}</td>
                    <td style="border : 1px solid #988989;">{{ $member->branch_name }}</td>
                    
                    <td style="border : 1px solid #988989;">{{ $ic }}</td>
                    <td style="border : 1px solid #988989;">{{ $member->gender[0] }}</td>
                    
                    <td style="border : 1px solid #988989;">{{ date('d/M/Y',strtotime($member->doj)) }}</td>
                    <td style="border : 1px solid #988989;"> 
                    	@foreach($advance as $alists)
                    		{{ date('M-Y',strtotime($alists->StatusMonth)) }} / {{ $alists->TOTALSUBCRP_AMOUNT+$alists->TOTALBF_AMOUNT+$alists->TOTALINSURANCE_AMOUNT }} <br>
                    	@endforeach
                    </td>
                   <!--  <td style="border : 1px solid #988989;">{{ $member->status_name[0] }}</td>	 -->
                </tr> 
				@php
					$sno++;
				@endphp
            @endforeach
           
		</tbody>
		
	</table>