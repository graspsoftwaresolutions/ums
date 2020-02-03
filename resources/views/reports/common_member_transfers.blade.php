@php 
	$logo = CommonHelper::getLogo(); 
@endphp
<table id="page-length-option" class="display" width="100%">
		<thead>
			<tr class="">
				
				<td colspan="1" rowspan="3" style="text-align:right">
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
				
					<span style="margin-top:0;">MEMBER TRANSFERS REPORT</span>
				</td>
				
			</tr>
			<tr class="" style="font-weight: bold;">
			
				<!-- <td colspan="2" style="border-bottom: 1px solid #988989 !important;">
					To Branch Hons. Secretary
					
				</td> -->
				<td colspan="4" align="center" style="text-align:center;vertical-align:top;border-bottom: 1px solid #988989 !important;">
					{{ date('01 M Y',strtotime($data['from_date'])) }} - {{ date('t M Y',strtotime($data['to_date'])) }}
				</td>
				<!-- <td colspan="3" style="border-bottom: 1px solid #988989 !important;">	
					
					
						<br>
						
					
				</td> -->
			</tr>
			
			<tr class="">
				<th style="border : 1px solid #988989;" align="center">SNO</th>
				<th style="border : 1px solid #988989;">{{__('M/NO')}}</th>
                <th style="border : 1px solid #988989;">{{__('MEMBER NAME')}}</th>
                <th style="border : 1px solid #988989;">{{__('FROM BRANCH')}}</th>
                <th style="border : 1px solid #988989;">{{__('TO BRANCH')}}</th>
              
                <th style="border : 1px solid #988989;">{{__('DATE')}}</th>
			</tr>
		</thead>
		<tbody class="" width="100%">
			@php
				$totalmembers = 0;
				$sno = 1;
				$status_arr = ['ACTIVE' => 0, 'DEFAULTER' => 0];
			@endphp
            @foreach($data['member_view'] as $member)
            	@php
					$frombranch = CommonHelper::getBranchName($member->old_branch_id);
               		$tobranch = CommonHelper::getBranchName($member->new_branch_id);
               		$frombankcode = CommonHelper::getBankCodeByBranch($member->old_branch_id);
               		$tobankcode = CommonHelper::getBankCodeByBranch($member->new_branch_id);
				@endphp
                <tr>
					<td style="border : 1px solid #988989;">{{ $sno }}</td>
					<td style="border : 1px solid #988989;">{{ $member->member_number }}</td>
                    <td style="border : 1px solid #988989;">{{ $member->name }}</td>
                    <td style="border : 1px solid #988989;"> {{ $frombankcode.'-'.$frombranch }}</td>
                    <td style="border : 1px solid #988989;">{{ $tobankcode.'-'.$tobranch }}</td>
                    <td style="border : 1px solid #988989;">{{ $member->transfer_date!="0000-00-00" ? date('d/M/Y', strtotime($member->transfer_date)) : '' }}</td>
                    
                </tr> 
				@php
					$sno++;
					
				@endphp
            @endforeach
            <tr>
				<td colspan="6" style="border : 1px solid #988989;;font-weight:bold;">Total Member's Count : {{ $sno-1 }}</td>
				
			</tr> 
		</tbody>
		
	</table>