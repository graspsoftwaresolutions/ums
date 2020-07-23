@php 
	$logo = CommonHelper::getLogo(); 
	$totalmembers = 0;
	
	$lastbankid = '';
@endphp
@foreach($data['member_view'] as $member)
@php
	$pgm_members = CommonHelper::getPgmMembers($data['month_year'],$member->companyid,$data['branch_id'],$data['unionbranch_id'],$data['status_id']);
	$totalmembers += count($pgm_members);
@endphp
@if(count($pgm_members)>0)
<table id="page-length-option" class="display table2excel" width="100%">
		<thead>
			<tr class="">
				
				<td colspan="2" rowspan="2" style="text-align:right">
					<img src="{{ asset('public/assets/images/logo/'.$logo) }}" height="50" />
				</td>
				<td colspan="8" style="text-align:center;padding:10px;vertical-align:top;">
					<span style="text-align:center;font-weight: bold;font-size:18px;vertical-align:top;">NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</span>
					
				</td>
				<td colspan="3" rowspan="2">	
					</br>
				</td>
			</tr>
			<tr class="">
				
				<td colspan="8" style="text-align:center;padding:10px;font-weight: bold;">
				
					<span style="margin-top:0;">PGM MEMBERS REPORT</span>
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
				<td colspan="8" align="center" style="text-align:center;vertical-align:top;border-bottom: 1px solid #988989 !important;">
					{{ date('01 M Y',strtotime($data['month_year'])) }} - {{ date('t M Y',strtotime($data['month_year'])) }}
				</td>
				<td colspan="3" style="border-bottom: 1px solid #988989 !important;">	
					
					@if($data['unionbranch_id']!='' && $data['branch_id']=='')
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
                <th style="border: 1px solid #988989 !important;">STATUS</th>
                <th style="border: 1px solid #988989 !important; ">{{__('LAST PAID DATE')}}</th>
			</tr>
		</thead>
		<tbody class="" width="100%">
	
				@php
					//dd($member);
					//$total_subs = $member->salary=='' ? 0 : ($member->salary*1)/100;
					
					$sno = 1;
				@endphp
				@foreach($pgm_members as $pgmmember)

				<tr >
					<td style="border: 1px solid #988989 !important; ">{{ $sno }}</td>
					<td style="border: 1px solid #988989 !important;">{{ $pgmmember->member_number }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $pgmmember->name }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $pgmmember->ic=='' ? $pgmmember->employee_id : $pgmmember->ic }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $pgmmember->gender }}</td>
                    <td style="border: 1px solid #988989 !important;"> {{ $pgmmember->companycode }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $pgmmember->branch_name }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $pgmmember->designation_name }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ date('d/M/Y',strtotime($pgmmember->doj)) }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $pgmmember->levy }}</td>	
                    <td style="border: 1px solid #988989 !important;">{{ $pgmmember->tdf }}</td>	
                    <td style="border: 1px solid #988989 !important;">{{  $pgmmember->status_name }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $pgmmember->last_paid_date }}</td>
					
					
				</tr> 
				@php
					$sno++;
				@endphp
				@endforeach
				
			<!-- //@if(!empty($data['member_view']))
			//@endif -->
			<tr>
				<td colspan="13" style="font-weight:bold;">Total Member's Count : {{ $sno-1 }}</td>
			</tr> 
		</tbody>
		
	</table>
	
	<div class="pagebreak"> </div>
	@endif
@endforeach
<p>Oveall Total Member's Count : {{ $totalmembers }}</p>