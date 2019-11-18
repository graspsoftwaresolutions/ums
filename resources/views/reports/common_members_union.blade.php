@php 
	$logo = CommonHelper::getLogo(); 
	$status_name =  CommonHelper::get_member_status_name($data['status_id']);
	//dd($data);
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
				
					<span style="margin-top:0;">UNION BRANCH MEMBER REPORT</span>
				</td>
				
			</tr>
			<tr class="" style="font-weight: bold;">
			
				<td colspan="2" style="border-bottom: 1px solid #988989 !important;">
					To Branch Hons. Secretary
					@if($data['unionbranch_id']!='' && $data['branch_id']=='')
						<br>
						Branch Name : {{ $data['unionbranch_name'] }}
					@endif
				</td>
				<td colspan="5" align="center" style="text-align:center;vertical-align:top;border-bottom: 1px solid #988989 !important;">
					{{ date('01 M Y',strtotime($data['month_year'])) }} - {{ date('t M Y',strtotime($data['month_year'])) }}
				</td>
				<td colspan="3" style="border-bottom: 1px solid #988989 !important;">	
					
					@if($data['unionbranch_id']!='' && $data['branch_id']=='')
						<br>
						Branch Code : {{ $data['unionbranch_id'] }}
					@endif
				</td>
			</tr>
			<tr class="">
				<th style="border : 1px solid #988989;" align="center">SNO</th>
				<th style="border : 1px solid #988989;">{{__('M/NO')}}</th>
                <th style="border : 1px solid #988989;">{{__('MEMBER NAME')}}</th>
               
                <th style="border : 1px solid #988989;">{{__('GENDER')}}</th>
                <th style="border : 1px solid #988989;">{{__('BANK')}}</th>
                <th style="border : 1px solid #988989;">{{__('UNION BRANCH')}}</th>
                <th style="border : 1px solid #988989;">{{__('BANK BRANCH')}}</th>
                <th style="border : 1px solid #988989;">{{__('TYPE')}}</th>
                
                <th style="border : 1px solid #988989;">{{__('DOJ')}}</th>
       
                <th style="border : 1px solid #988989;">{{__('LAST PAID DATE')}}</th>

			</tr>
		</thead>
		<tbody class="" width="100%">
			@php
				$totalmembers = 0;
				$sno = 1;
			@endphp
            @foreach($data['member_view'] as $member)
                <tr>
					<td style="border : 1px solid #988989;">{{ $sno }}</td>
					<td style="border : 1px solid #988989;">{{ $member->member_number }}</td>
                    <td style="border : 1px solid #988989;">{{ $member->name }}</td>
                   
                    <td style="border : 1px solid #988989;">{{ $member->gender }}</td>
                    <td style="border : 1px solid #988989;"> {{ $member->companycode }}</td>
                    <td style="border : 1px solid #988989;">{{ $member->union_branch }}</td>
                    <td style="border : 1px solid #988989;">{{ $member->branch_name }}</td>
                    <td style="border : 1px solid #988989;">{{ $member->designation_name }}</td>
                    <td style="border : 1px solid #988989;">{{ date('d/M/Y',strtotime($member->doj)) }}</td>
                  
                    <td style="border : 1px solid #988989;">{{ $member->last_paid_date }}</td>	
                </tr> 
				@php
					$sno++;
				@endphp
            @endforeach
			<tr>
				<td colspan="10" style="border : 1px solid #988989;">Total Member's Count : {{ $sno-1 }}</td>
				
			</tr> 
		</tbody>
		
	</table>