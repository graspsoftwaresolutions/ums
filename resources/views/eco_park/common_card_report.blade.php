@php 
	$logo = CommonHelper::getLogo(); 
@endphp
<table id="page-length-option" class="display" width="100%">
		<thead>
			<!-- <tr class="">
				
				<td colspan="3" rowspan="2" style="text-align:right">
					<img src="{{ asset('public/assets/images/logo/'.$logo) }}" height="50" />
				</td>
				<td colspan="5" style="text-align:center;padding:10px;vertical-align:top;">
					<span style="text-align:center;font-weight: bold;font-size:18px;vertical-align:top;">NATIONAL UNION OF BANK EMPLOYEES,
					@if(!isset($isprint))
					<br/>
					@endif
					PENINSULAR MALAYSIA</span>
					
				</td>
				<td colspan="2" rowspan="2">	
					</br>
				</td>
			</tr> -->
			<tr class="">
				
				<td colspan="9" style="text-align:center;padding:10px;font-weight: bold;">
				
					<span style="margin-top:0;">ECO PARK REPORT</span>
				</td>
				
			</tr>
			
			
			<tr class="">
				<th style="border : 1px solid #988989;" align="center">SNO</th>
				<th style="border : 1px solid #988989;">{{__('M/NO')}}</th>
                <th style="border : 1px solid #988989;width: 25%;">{{__('MEMBER NAME')}}</th>
                <th style="border : 1px solid #988989;">{{__('NRIC-NEW')}}</th>
                <th style="border : 1px solid #988989;">{{__('BANK')}}</th>
                <th style="border : 1px solid #988989;">{{__('AMOUNT')}}</th>
                <th style="border : 1px solid #988989;">{{__('BATCH TYPE')}}</th>
              
                <th style="border : 1px solid #988989;">{{__('MEMBER STATUS')}}</th>
                <th style="border : 1px solid #988989;">{{__('CARD STATUS')}}</th>
			</tr>
		</thead>
		<tbody class="" width="100%">
			@php
				$totalmembers = 0;
				$sno = 1;
			@endphp
            @foreach($data['member_view'] as $member)
            	@php
					if($member->type==1){
					    $batch_head = 'Batch 1 Members';
					}else if($member->type==2){
					    $batch_head = 'Batch 1 Non Members';
					}else if($member->type==3){
					    $batch_head = 'Batch 2 Members';
					}else if($member->type==4){
					    $batch_head = 'Batch 2 Non Members';
					}else{
					    $batch_head = 'Others';
					}
				@endphp
                <tr>
					<td style="border : 1px solid #988989;">{{ $sno }}</td>
					<td style="border : 1px solid #988989;">{{ $member->member_number }}</td>
                    <td style="border : 1px solid #988989;">{{ $member->full_name }}</td>
                    <td style="border : 1px solid #988989;">{{ $member->nric_new }}</td>
                    <td style="border : 1px solid #988989;"> {{ $member->bank }}</td>
                    <td style="border : 1px solid #988989;">{{ $member->payment_fee }}</td>
                    <td style="border : 1px solid #988989;">{{ $batch_head }}</td>
                    
                    
                    <td style="border : 1px solid #988989;">{{ CommonHelper::get_member_status_name($member->status_id) }}</td>
                    <td style="border : 1px solid #988989;">{{ $member->card_status }}</td>
                    
                </tr> 
				@php
					$sno++;
					
				@endphp
            @endforeach
            
		</tbody>
		
	</table>