@php 
	$logo = CommonHelper::getLogo(); 
	$slno = 0;
@endphp
<table id="page-length-option" class="display" width="100%">
		<thead>
			<tr class="">
				
				<td colspan="1" rowspan="2" style="text-align:right">
					<img src="{{ asset('public/assets/images/logo/'.$logo) }}" height="50" />
				</td>
				<td colspan="11" style="text-align:center;padding:10px;vertical-align:top;">
					<span style="text-align:center;font-weight: bold;font-size:18px;vertical-align:top;">NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</span>
					
				</td>
				<td colspan="1" rowspan="2">	
					</br>
				</td>
			</tr>
			<tr class="">
				
				<td colspan="11" style="text-align:center;padding:10px;font-weight: bold;">
				
					<p style="margin-top:0;"> STATISTICS FOR {{ date('M Y',strtotime($data['from_month_year'])) }} - {{ date('M Y',strtotime($data['to_month_year'])) }}</p>
					@if(!isset($setwidth))
					</br>
					</br>
					@endif
				</td>
				
			</tr>
			<tr class="">
				<th style="border : 1px solid #988989;" colspan="1" align="center">DESCRIPTION</th>
				@for($i=0; $i<$data['diff_month_count'];$i++)
				<th style="border : 1px solid #988989;text-align: center;" width="7%" >{{ date('m/y', strtotime('+'.($i).' months',strtotime($data['from_month_year']))) }}</th>
				@endfor
			</tr>
			
			
		</thead>
		<tbody>
				<tr>
					<td style="border : 1px solid #988989;">New members</td>
					@for($i=0; $i<$data['diff_month_count'];$i++)
						@php
							$month = date('Y-m-01', strtotime('+'.($i).' months',strtotime($data['from_month_year'])));
							$newmembercount = CommonHelper::getMonthNewMembercount($month);
						@endphp
						<td style="border : 1px solid #988989;text-align: center;" >{{ $newmembercount }}</td>
					@endfor
				</tr>
				<tr>
					<td style="border : 1px solid #988989;">Rejoined</td>
					@for($i=0; $i<$data['diff_month_count'];$i++)
						@php
							$month = date('Y-m-01', strtotime('+'.($i).' months',strtotime($data['from_month_year'])));
							$rejoinedcount = CommonHelper::getMonthRejoinMembercount($month);
						@endphp
						<td style="border : 1px solid #988989;text-align: center;" >{{ $rejoinedcount }}</td>
					@endfor
				</tr>
				<tr>
					<td style="border : 1px solid #988989;">Special grade</td>
					@for($i=0; $i<$data['diff_month_count'];$i++)
						@php
							$month = date('Y-m-01', strtotime('+'.($i).' months',strtotime($data['from_month_year'])));
							$specialcount = CommonHelper::getMonthSpecialMembercount($month);
						@endphp
						<td style="border : 1px solid #988989;text-align: center;" >{{ $specialcount }}</td>
					@endfor
				</tr>
				<tr>
					<td style="border : 1px solid #988989;">Active members(All)</td>
					@for($i=0; $i<$data['diff_month_count'];$i++)
						@php
							$month = date('Y-m-01', strtotime('+'.($i).' months',strtotime($data['from_month_year'])));
							$activecount = CommonHelper::getMonthStatuscount($month,1);
						@endphp
						<td style="border : 1px solid #988989;text-align: center;" >{{ $activecount }}</td>
					@endfor
				</tr>
				<tr>
					<td style="border : 1px solid #988989;">Defaulter members</td>
					@for($i=0; $i<$data['diff_month_count'];$i++)
						@php
							$month = date('Y-m-01', strtotime('+'.($i).' months',strtotime($data['from_month_year'])));
							$defaultercount = CommonHelper::getMonthStatuscount($month,2);
						@endphp
						<td style="border : 1px solid #988989;text-align: center;" >{{ $defaultercount }}</td>
					@endfor
				</tr>
				<tr>
					<td style="border : 1px solid #988989;">Struckoff members</td>
					@for($i=0; $i<$data['diff_month_count'];$i++)
						@php
							$month = date('Y-m-01', strtotime('+'.($i).' months',strtotime($data['from_month_year'])));
							$struckoffcount = CommonHelper::getMonlthlyStruckoffMembercount($month);
						@endphp
						<td style="border : 1px solid #988989;text-align: center;" >{{ $struckoffcount }}</td>
					@endfor
				</tr>
				<tr>
					<td style="border : 1px solid #988989;">Resigned members</td>
					@for($i=0; $i<$data['diff_month_count'];$i++)
						@php
							$month = date('Y-m-01', strtotime('+'.($i).' months',strtotime($data['from_month_year'])));
							$resignedcount = CommonHelper::getMonlthlyResignedMembercount($month);
						@endphp
						<td style="border : 1px solid #988989;text-align: center;" >{{ $resignedcount }}</td>
					@endfor
				</tr>			
			
		</tbody>
		
	</table>
	