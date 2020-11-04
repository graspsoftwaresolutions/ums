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
				<td colspan="2" rowspan="2">	
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
				<th style="border : 1px solid #988989;text-align: center;" width="6%" >{{ date('m/y', strtotime('+'.($i).' months',strtotime($data['from_month_year']))) }}</th>
				@endfor
				<th style="border : 1px solid #988989;text-align: center;">Total</th> 
			</tr>
			
		</thead>
		<tbody>
				<tr>
					<td style="border : 1px solid #988989;">New members</td>
					@php
						$totalnewmwmber = 0;
						$totalrejoined = 0;
						$totalspecial = 0;
						$totalactive = 0;
						$totaldefaulter = 0;
						$totaldefault = 0;
						$totalstruckoff = 0;
						$totalresigned = 0;
						$totalresignedpay = 0;
						$totalresigncount = 0;
						$totalresigncount1 = 0;
						$totalactivecount1 = 0;
						$totalstruckoffcount1 = 0;
					@endphp
					@for($i=0; $i<$data['diff_month_count'];$i++)
						@php
							$month = date('Y-m-01', strtotime('+'.($i).' months',strtotime($data['from_month_year'])));
							$newmembercount = CommonHelper::getMonthNewMembercount($month);
							$totalnewmwmber += $newmembercount;
						@endphp
						<td style="border : 1px solid #988989;text-align: center;" >{{ $newmembercount }}</td>
					@endfor
					<td style="border : 1px solid #988989;text-align: center;">{{ $totalnewmwmber }}</td>
				</tr>
				<tr>
					<td style="border : 1px solid #988989;">Rejoined</td>
					@for($i=0; $i<$data['diff_month_count'];$i++)
						@php
							$month = date('Y-m-01', strtotime('+'.($i).' months',strtotime($data['from_month_year'])));
							$rejoinedcount = CommonHelper::getMonthRejoinMembercount($month);
							$specialcount = CommonHelper::getMonthSpecialMembercount($month);
							$rejoinedcount = $rejoinedcount+$specialcount;
							$totalrejoined += $rejoinedcount;
						@endphp
						<td style="border : 1px solid #988989;text-align: center;" >{{ $rejoinedcount }}</td>
					@endfor
					<td style="border : 1px solid #988989;text-align: center;">{{ $totalrejoined }}</td>
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
					<td style="border : 1px solid #988989;text-align: center;">--</td>
				</tr>
				<tr>
					<td style="border : 1px solid #988989;">Defaulter members(All)</td>
					@for($i=0; $i<$data['diff_month_count'];$i++)
						@php
							$month = date('Y-m-01', strtotime('+'.($i).' months',strtotime($data['from_month_year'])));
							$defaultercount = CommonHelper::getMonthStatuscount($month,2);
							
						@endphp
						<td style="border : 1px solid #988989;text-align: center;" >{{ $defaultercount }}</td>
					@endfor
					<td style="border : 1px solid #988989;text-align: center;">--</td>
				</tr>
				 <tr>
					<td style="border : 1px solid #988989;">Defaulter members</td>
					@for($i=0; $i<$data['diff_month_count'];$i++)
						@php
							$month = date('Y-m-01', strtotime('+'.($i).' months',strtotime($data['from_month_year'])));
							$defaultcount = CommonHelper::getMonlthlyDefaulterMembercount($month);
							//$defaultcount = 0;
							$totaldefault += $defaultcount;
						@endphp
						<td style="border : 1px solid #988989;text-align: center;" >{{ $defaultcount }}</td>
					@endfor
					<td style="border : 1px solid #988989;text-align: center;">{{ $totaldefault }}</td>
				</tr> 
				<tr>
					<td style="border : 1px solid #988989;">Resigned members(Resigned Date)</td>
					@for($i=0; $i<$data['diff_month_count'];$i++)
						@php
							$month = date('Y-m-01', strtotime('+'.($i).' months',strtotime($data['from_month_year'])));
							$resignedcount = CommonHelper::getMonlthlyResignedMembercount($month,1);
							$totalresigned += $resignedcount;
						@endphp
						<td style="border : 1px solid #988989;text-align: center;" >{{ $resignedcount }}</td>
					@endfor
					<td style="border : 1px solid #988989;text-align: center;">{{ $totalresigned }}</td>
				</tr>	
				<tr>
					<td style="border : 1px solid #988989;">Resigned members(Payment Date)</td>
					@for($i=0; $i<$data['diff_month_count'];$i++)
						@php
							$month = date('Y-m-01', strtotime('+'.($i).' months',strtotime($data['from_month_year'])));
							$resignedcount = CommonHelper::getMonlthlyResignedMembercount($month,2);
							$totalresignedpay += $resignedcount;
						@endphp
						<td style="border : 1px solid #988989;text-align: center;" >{{ $resignedcount }}</td>
					@endfor
					<td style="border : 1px solid #988989;text-align: center;">{{ $totalresignedpay }}</td>
				</tr>		
				<tr>
					<td style="border : 1px solid #988989;">Defaulter to Active members</td>
					@for($i=0; $i<$data['diff_month_count'];$i++)
						@php
							$month = date('Y-m-01', strtotime('+'.($i).' months',strtotime($data['from_month_year'])));
							$activecount1 = CommonHelper::getMonlthlyDefaultToActivecount($month);
							$totalactivecount1 += $activecount1;
						@endphp
						<td style="border : 1px solid #988989;text-align: center;" >{{ $activecount1 }}</td>
					@endfor
					<td style="border : 1px solid #988989;text-align: center;">{{ $totalactivecount1 }}</td>
				</tr>
				<!--tr>
					<td style="border : 1px solid #988989;">Struckoff to Active members</td>
					@for($i=0; $i<$data['diff_month_count'];$i++)
						@php
							$month = date('Y-m-01', strtotime('+'.($i).' months',strtotime($data['from_month_year'])));
							//$struckoffcount1 = CommonHelper::getMonlthlyStruckoffToActivecount($month);
							$struckoffcount1 = 0;
							$totalstruckoffcount1 += $struckoffcount1;
						@endphp
						<td style="border : 1px solid #988989;text-align: center;" >{{ $struckoffcount1 }}</td>
					@endfor
					<td style="border : 1px solid #988989;text-align: center;">{{ $totalstruckoffcount1 }}</td>
				</tr-->
				<tr>
					<td style="border : 1px solid #988989;">Struckoff members</td>
					@for($i=0; $i<$data['diff_month_count'];$i++)
						@php
							$month = date('Y-m-01', strtotime('+'.($i).' months',strtotime($data['from_month_year'])));
							$struckoffcount = CommonHelper::getMonlthlyStruckoffMembercount($month);
							$totalstruckoff += $struckoffcount;
						@endphp
						<td style="border : 1px solid #988989;text-align: center;" >{{ $struckoffcount }}</td>
					@endfor
					<td style="border : 1px solid #988989;text-align: center;">{{ $totalstruckoff }}</td>
				</tr>
				<tr>
					<td style="border : 1px solid #988989;">Active to resigned members</td>
					@for($i=0; $i<$data['diff_month_count'];$i++)
						@php
							$month = date('Y-m-01', strtotime('+'.($i).' months',strtotime($data['from_month_year'])));
							$resigncount = CommonHelper::getMonlthlyActiveToResigncount($month);
							$totalresigncount += $resigncount;
						@endphp
						<td style="border : 1px solid #988989;text-align: center;" >{{ $resigncount }}</td>
					@endfor
					<td style="border : 1px solid #988989;text-align: center;">{{ $totalresigncount }}</td>
				</tr>
				<tr>
					<td style="border : 1px solid #988989;">Defaulter to resigned members</td>
					@for($i=0; $i<$data['diff_month_count'];$i++)
						@php
							$month = date('Y-m-01', strtotime('+'.($i).' months',strtotime($data['from_month_year'])));
							$resigncount1 = CommonHelper::getMonlthlyDefaultToResigncount($month);
							$totalresigncount1 += $resigncount1;
						@endphp
						<td style="border : 1px solid #988989;text-align: center;" >{{ $resigncount1 }}</td>
					@endfor
					<td style="border : 1px solid #988989;text-align: center;">{{ $totalresigncount1 }}</td>
				</tr>
				
			
		</tbody>
		
	</table>
	