@php 
	$logo = CommonHelper::getLogo(); 
	//dd($data);
@endphp
<table id="page-length-option" class="display table2excel" width="100%">
		<thead>
			<tr class="">
				
				<td colspan="2" rowspan="2" style="text-align:right">
					<img src="{{ asset('public/assets/images/logo/'.$logo) }}" height="50" />
				</td>
				<td @if($data['type']==1) colspan="8" @else colspan="6" @endif style="text-align:center;padding:10px;vertical-align:top;">
					<span style="text-align:center;font-weight: bold;font-size:18px;vertical-align:top;">NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</span>
					
				</td>
				<td colspan="3" rowspan="2">	
					</br>
				</td>
			</tr>
			<tr class="">
				@php
					if($data['type']==1){
						$jtypename = 'NEW MEMBER';
					}else if($data['type']==2){
						$jtypename = 'RESIGNED MEMBERS';
					}else{
						$jtypename = '';
					}
				@endphp
				<td @if($data['type']==1) colspan="8" @else colspan="6" @endif style="text-align:center;padding:10px;font-weight: bold;">
				
					<span style="margin-top:0;">{{$jtypename}} REPORT</span>
				</td>
				
			</tr>
			<tr class="" style="font-weight: bold;">
			
				<td colspan="2" style="border-bottom: 1px solid #988989 !important;">
					To Branch Hons. Secretary
					
				</td>
				<td @if($data['type']==1) colspan="8" @else colspan="6" @endif align="center" style="text-align:center;vertical-align:top;border-bottom: 1px solid #988989 !important;">
					{{ date('d M Y',strtotime($data['from_date'])) }} - {{ date('d M Y',strtotime($data['to_date'])) }}
				</td>
				<td colspan="3" style="border-bottom: 1px solid #988989 !important;">	
					
					
				</td>
			</tr>
				
			<tr class="" style="" width="100%">
				<th style="border: 1px solid #988989 !important;" width="2%">S.NO</th>
				<th style="border: 1px solid #988989 !important;">MEMBER NAME</th>
				<th style="border: 1px solid #988989 !important;" width="3%">M/NO</th>
				<th style="border: 1px solid #988989 !important;" width="4%" class="nric_no">NRIC</th>
				<th  style="border: 1px solid #988989 !important;" width="4%">GENDER</th>
				<th  style="border: 1px solid #988989 !important;" width="5%">BANK</th>
				<th  style="border: 1px solid #988989 !important;">BANK BRANCH</th>
				<!-- <th  style="border: 1px solid #988989 !important;" width="3%">TYPE</th> -->
				<th style="border: 1px solid #988989 !important;" width="9%">DOJ</th>
				@if($data['type']==1)
				<th  style="border: 1px solid #988989 !important;" width="3%">LEVY</th>
				<th style="border: 1px solid #988989 !important;" width="3%">TDF</th>
				<th style="border: 1px solid #988989 !important;" width="4%">AMOUNT</th>
				<th style="border: 1px solid #988989 !important;" width="4%">CREATED BY</th>
				@else
				<th  style="border: 1px solid #988989 !important;" width="3%">RESIGNED</th>
				<th style="border: 1px solid #988989 !important;" width="4%">IRC USER</th>
				@endif
				
				<!-- <th  style="border: 1px solid #988989 !important;" width="9%">LAST PAID DATE</th> -->
				<th style="border: 1px solid #988989 !important;" width="4%">STATUS</th>
			</tr>
		</thead>
		<tbody class="" width="100%">
			@php
				$totalmembers = 0;
				$sno = 1;
				
			@endphp
			
			
			@foreach($data['member_view'] as $member)
				@php
					$total_subs = $member->salary=='' ? 0 : ($member->salary*1)/100;
				@endphp
				<tr >
					<td style="border: 1px solid #988989 !important; ">{{ $sno }}</td>
					<td style="border: 1px solid #988989 !important;">{{ $member->name }}</td>
					<td style="border: 1px solid #988989 !important;">{{ $member->member_number==0 ? '' : $member->member_number }}</td>
					<td class="nric_no" style="border: 1px solid #988989 !important;">{{ $member->ic=='' ? $member->employee_id : $member->ic }}</td>
					<td style="border: 1px solid #988989 !important;">{{ $member->gender }}</td>
					<td style="border: 1px solid #988989 !important;" >{{ $member->companycode }}</td>
					<td  style="border: 1px solid #988989 !important;" width="18%">{{ $member->branch_name }}</td>
					<!-- <td style="border: 1px solid #988989 !important;">{{ isset($member) ? $member->designation_name : ""}}</td> -->
					<td style="border: 1px solid #988989 !important;">{{ date('d/M/Y',strtotime($member->doj))}}</td>
					@if($data['type']==1)
					<td style="border: 1px solid #988989 !important;">{{  $member->levy }}</td>
					<td style="border: 1px solid #988989 !important;">{{  $member->tdf }}</td>
					<td style="border: 1px solid #988989 !important;">{{  $total_subs }}</td>
					<td style="border: 1px solid #988989 !important;">{{  CommonHelper::getUserName($member->created_by) }}</td>
					<td style="border: 1px solid #988989 !important;">{{ $member->status_name=='' ? 'PENDING' : $member->status_name }}</td>
					@else
					<td style="border: 1px solid #988989 !important;">{{ $member->resignation_date=='' ? '' : date('d/M/Y',strtotime($member->resignation_date))}}</td>
					
					<td style="border: 1px solid #988989 !important;">{{ $member->irc_user_name }}</td>
					<td style="border: 1px solid #988989 !important;">{{ $member->resignation_date=='' ? 'PENDING' : 'RESIGNED' }}</td>
					@endif
					
				
					<!-- <td style="border: 1px solid #988989 !important;">{{  $member->last_paid_date!="" ? date('d/M/Y',strtotime($member->last_paid_date)) : '' }}</td> -->
					
				</tr> 
				
				@php
					$sno++;
				@endphp
			@endforeach
			<!-- //@if(!empty($data['member_view']))
			//@endif -->
			<tr>
				<td @if($data['type']==1) colspan="13" @else colspan="11" @endif style="font-weight:bold;">Total Member's Count : {{ $sno-1 }}</td>
			</tr> 
		</tbody>
		
	</table>