@php 
	$logo = CommonHelper::getLogo(); 
	$status_name =  CommonHelper::get_member_status_name($data['status_id']);
	//dd($data);
@endphp
<table id="page-length-option" class="display table2excel" width="100%">
		<thead>
			<tr class="">
				
				<td colspan="2" rowspan="2">
					<img src="{{ asset('public/assets/images/logo/'.$logo) }}" height="50" />
				</td>
				<td colspan="7" style="text-align:center;padding:10px;vertical-align:top;">
					<span style="text-align:center;font-weight: bold;font-size:18px;vertical-align:top;">NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</span>
					
				</td>
				<td colspan="3" rowspan="2">	
					</br>
				</td>
			</tr>
			<tr class="">
				
				<td colspan="7" style="text-align:center;padding:10px;font-weight: bold;">
				
					<span style="margin-top:0;">{{$status_name}} MEMBERS REPORT</span>
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
				<td colspan="7" align="center" style="text-align:center;vertical-align:top;border-bottom: 1px solid #988989 !important;">
					{{ date('01 M Y',strtotime($data['month_year'])) }} - {{ date('t M Y',strtotime($data['month_year'])) }}
				</td>
				<td colspan="3" style="border-bottom: 1px solid #988989 !important;">	
					
					@if($data['unionbranch_id']!='')
						<br>
						Branch Code : {{ $data['unionbranch_id'] }}
					@endif
				</td>
			</tr>
				
			<tr class="" style="" width="100%">
				<th style="border: 1px solid #988989 !important; " align="center">SNO</th>
				<th style="border: 1px solid #988989 !important; " >{{__('M/NO')}}</th>
                <th style="border: 1px solid #988989 !important; ">{{__('MEMBER NAME')}}</th>
               
                <th style="border: 1px solid #988989 !important; " align="center">{{__('NRIC')}}</th>
                <th style="border: 1px solid #988989 !important; ">{{__('GENDER')}}</th>
                <th style="border: 1px solid #988989 !important; ">{{__('BANK')}}</th>
                <th style="border: 1px solid #988989 !important; ">{{__('BANK BRANCH')}}</th>
                <th style="border: 1px solid #988989 !important; ">{{__('TYPE')}}</th>
                
                <th style="border: 1px solid #988989 !important; ">{{__('DOJ')}}</th>
                <th style="border: 1px solid #988989 !important; ">{{__('LEVY')}}</th>
                <th style="border: 1px solid #988989 !important; ">{{__('TDF')}}</th>
                <th style="border: 1px solid #988989 !important; ">{{__('LAST PAID DATE')}}</th>
			</tr>
		</thead>
		<tbody class="" width="100%">
			@php
				$totalmembers = 0;
				$sno = 1;
				
			@endphp
			
			
			@foreach($data['member_view'] as $member)
				<tr >
					<td style="border: 1px solid #988989 !important; ">{{ $sno }}</td>
					<td style="border: 1px solid #988989 !important;">{{ $member->member_number }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $member->name }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $member->ic }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $member->gender }}</td>
                    <td style="border: 1px solid #988989 !important;"> {{ $member->companycode }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $member->branch_name }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $member->designation_name }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ date('d/M/Y',strtotime($member->doj)) }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $member->levy }}</td>	
                    <td style="border: 1px solid #988989 !important;">{{ $member->tdf }}</td>	
                    <td style="border: 1px solid #988989 !important;">{{ $member->last_paid_date }}</td>
					
					
				</tr> 
				
				@php
					$sno++;
				@endphp
			@endforeach
			<!-- //@if(!empty($data['member_view']))
			//@endif -->
			<tr>
				<td colspan="12" style="font-weight:bold;">Total Member's Count : {{ $sno-1 }}</td>
			</tr> 
		</tbody>
		
	</table>