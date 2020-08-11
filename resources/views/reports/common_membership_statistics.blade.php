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
				
					<span style="margin-top:0;"> STATISTICS FOR {{$data['from_year']-3}}-{{$data['to_year']}}</span>
				</td>
				
			</tr>
			
			
			
		</thead>
		<tbody>
			<tr>
				<td colspan="10" style="">
					@php
						$allactivecount = CommonHelper::getUnionGroupStatusCount('2020-03-01',1,'');
						$alldefaulercount = CommonHelper::getUnionGroupStatusCount('2020-03-01',2,'');
					@endphp
					<p style="margin-left: 85px;">TOTAL IN BENEFIT MEMBERS : {{ $allactivecount }}, NOT IN BENEFIT : {{ $alldefaulercount }} </p>
					<table style="width: 90% !important;margin: 0 5%;">
						<thead>
							<tr class="">
								<th style="border : 1px solid #988989;" align="center">UNION BRANCH NAME</th>
								<th style="border : 1px solid #988989;text-align: center;" colspan="2" >IN BENEFIT</th>
				                <th style="border : 1px solid #988989;text-align: center;" colspan="2" >NOT IN BENEFIT</th>
							</tr>
							<tr>
								<th style="border : 1px solid #988989;font-weight:bold;" ></th>
								<th style="border : 1px solid #988989;font-weight:bold;" >2014-2017</th>
								<th style="border : 1px solid #988989;font-weight:bold;">2017-2020</th>
								<th style="border : 1px solid #988989;font-weight:bold;" >2014-2017</th>
								<th style="border : 1px solid #988989;font-weight:bold;">2017-2020</th>
							</tr>
						</thead>
						<tbody>
							@php
								$totalactive = 0;
								$totalactive1 = 0;
								$totaldefaulter = 0;
								$totaldefaulter1 = 0;
							@endphp
							@foreach($data['unionbranch_view'] as $value)
							@php
								$activecount = CommonHelper::getUnionGroupStatusCount('2017-03-01',1,$value->id);
								$defaulercount = CommonHelper::getUnionGroupStatusCount('2017-03-01',2,$value->id);

								$sactivecount = CommonHelper::getUnionGroupStatusCount('2020-03-01',1,$value->id);
								$sdefaulercount = CommonHelper::getUnionGroupStatusCount('2020-03-01',2,$value->id);

								$totalactive += $activecount;
								$totalactive1 += $sactivecount;
								$totaldefaulter += $defaulercount;
								$totaldefaulter1 += $sdefaulercount;
							@endphp
							<tr>
								<td style="border : 1px solid #988989;" >{{ $value->group_name=='KELANTAN TERENGGANU' ? 'KELANTAN' : $value->group_name }}</td>
								<td style="border : 1px solid #988989;" >{{$activecount}}</td>
								<td style="border : 1px solid #988989;">{{ $sactivecount }}</td>
								<td style="border : 1px solid #988989;" >{{ $defaulercount }}</td>
								<td style="border : 1px solid #988989;">{{ $sdefaulercount }} </td>
							</tr>
							
							@endforeach
							<tr>
								<td style="border : 1px solid #988989;" >TOTAL</td>
								<td style="border : 1px solid #988989;" >{{ $totalactive }}</td>
								<td style="border : 1px solid #988989;">{{ $totalactive1 }}</td>
								<td style="border : 1px solid #988989;" >{{ $totaldefaulter }}</td>
								<td style="border : 1px solid #988989;">{{ $totaldefaulter1 }} </td>
							</tr>
						</tbody>
						
					</table>
					<!-- <p style="margin-left: 85px;">NEW MEMBERS </p>
					<table style="width: 90% !important;margin: 0 5%;">
						<thead>
							<tr class="">
								<th style="border : 1px solid #988989;" align="center">UNION BRANCH NAME</th>
								<th style="border : 1px solid #988989;font-weight:bold;" >2014-2017</th>
								<th style="border : 1px solid #988989;font-weight:bold;">2017-2020</th>
							</tr>
						</thead>
						<tbody>
							@php
								$totalnewmembers = 0;
								$totalnewmembers1 = 0;
							@endphp
							@foreach($data['unionbranch_view'] as $value)
							@php
								$newmemberscount = CommonHelper::getUnionNewMembersCount('2014-04-01','2017-03-31',$value->id);
								$newmemberscount1 = CommonHelper::getUnionNewMembersCount('2017-04-01','2020-03-31',$value->id);

								$totalnewmembers += $newmemberscount;
								$totalnewmembers1 += $newmemberscount1;
							@endphp
							<tr>
								<td style="border : 1px solid #988989;" >{{ $value->group_name=='KELANTAN TERENGGANU' ? 'KELANTAN' : $value->group_name }}</td>
								<td style="border : 1px solid #988989;" >{{ $newmemberscount }}</td>
								<td style="border : 1px solid #988989;">{{ $newmemberscount1 }}</td>
							</tr>
							
							@endforeach
							<tr>
								<td style="border : 1px solid #988989;" >TOTAL</td>
								<td style="border : 1px solid #988989;" >{{ $totalnewmembers }}</td>
								<td style="border : 1px solid #988989;">{{ $totalnewmembers1 }}</td>
							</tr>
						</tbody>
						
					</table> -->
				</td>
				
			</tr> 
            <!-- <tr>
				<td colspan="10" style="border : 1px solid #988989;font-weight:bold;">Total ACTIVE Member's Count : 0</td>
				
			</tr>  -->
		</tbody>
		
	</table>
	