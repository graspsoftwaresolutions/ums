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
				<td colspan="5" style="text-align:center;padding:10px;vertical-align:top;">
					<span style="text-align:center;font-weight: bold;font-size:18px;vertical-align:top;">NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</span>
					
				</td>
				<td colspan="1" rowspan="2">	
					</br>
				</td>
			</tr>
			<tr class="">
				
				<td colspan="5" style="text-align:center;padding:10px;font-weight: bold;">
				
					<p style="margin-top:0;"> STATISTICS FOR {{$data['from_year']}}-{{$data['to_year']}}</p>
					@if(!isset($setwidth))
					</br>
					</br>
					</br>
					@endif
				</td>
				
			</tr>
			<tr class="">
				<th style="border : 1px solid #988989;" colspan="2" align="center">DESCRIPTION</th>
				<th style="border : 1px solid #988989;text-align: center;" @if(isset($setwidth)) width="80px;" @endif >{{__('MARCH 2017')}}</th>
                <th style="border : 1px solid #988989;text-align: center;" @if(isset($setwidth)) width="220px;" @endif  >APRIL 2017 - </br>MARCH 2018</th>
                <th style="border : 1px solid #988989;text-align: center;" @if(isset($setwidth)) width="220px;" @endif >APRIL 2018 - </br>MARCH 2019</th>
                <th style="border : 1px solid #988989;text-align: center;" @if(isset($setwidth)) width="220px;" @endif >APRIL 2019 - </br>MARCH 2020</th>
                <th style="border : 1px solid #988989;text-align: center;" @if(isset($setwidth)) width="220px;" @endif >APRIL 2017 - </br>MARCH 2020</th>
			</tr>
			
			
		</thead>
		<tbody>
							
			@foreach($data['unionbranch_view'] as $value)
			@php
				$activecount = CommonHelper::getYearStatuscount('2017-03-01',1,$value->id);
				$defaultercount = CommonHelper::getYearStatuscount('2017-03-01',2,$value->id);
				$struckoffcount = CommonHelper::getYearStatuscount('2017-03-01',3,$value->id);
				$active_defaultercount = $activecount+$defaultercount;

				$newmembercount = CommonHelper::getYearNewMembercount('2017-04-01','2018-03-31',$value->id);
				$newmembercount1 = CommonHelper::getYearNewMembercount('2018-04-01','2019-03-31',$value->id);
				$newmembercount2 = CommonHelper::getYearNewMembercount('2019-04-01','2020-03-31',$value->id);
				$totnewmembercount = $newmembercount+$newmembercount1+$newmembercount2;

				$resignmembercount = CommonHelper::getResignedMembercount('2017-04-01','2018-03-31',$value->id);
				$resignmembercount1 = CommonHelper::getResignedMembercount('2018-04-01','2019-03-31',$value->id);
				$resignmembercount2 = CommonHelper::getResignedMembercount('2019-04-01','2020-03-31',$value->id);
				$totresignmembercount = $resignmembercount+$resignmembercount1+$resignmembercount2;

				$activecount1 = CommonHelper::getYearStatuscount('2018-03-01',1,$value->id);
				$defaultercount1 = CommonHelper::getYearStatuscount('2018-03-01',2,$value->id);
				//$struckoffcount1 = CommonHelper::getYearStatuscount('2018-03-01',3,$value->id);
				$active_defaultercount1 = $activecount1+$defaultercount1;

				$activecount2 = CommonHelper::getYearStatuscount('2019-03-01',1,$value->id);
				$defaultercount2 = CommonHelper::getYearStatuscount('2019-03-01',2,$value->id);
				//$struckoffcount2 = CommonHelper::getYearStatuscount('2019-03-01',3,$value->id);
				$active_defaultercount2 = $activecount2+$defaultercount2;

				$activecount3 = CommonHelper::getYearStatuscount('2020-03-01',1,$value->id);
				$defaultercount3 = CommonHelper::getYearStatuscount('2020-03-01',2,$value->id);
				//$struckoffcount3 = CommonHelper::getYearStatuscount('2020-03-01',3,$value->id);
				$active_defaultercount3 = $activecount3+$defaultercount3;

				$totactivecount = $activecount+($activecount1-$activecount)+($activecount2-$activecount1)+($activecount3-$activecount2);
				$totdefaultercount = $defaultercount+($defaultercount1-$defaultercount)+($defaultercount2-$defaultercount1)+($defaultercount3-$defaultercount2);
				$totactive_defaultercount = $active_defaultercount+($active_defaultercount1-$active_defaultercount)+($active_defaultercount2-$active_defaultercount1)+($active_defaultercount3-$active_defaultercount2);

				//$totalunionmembers1 = CommonHelper::getTotalUnionMembers('2018-03-31',$value->id);
				//$totalunionmembers2 = CommonHelper::getTotalUnionMembers('2019-03-31',$value->id);
				//$totalunionmembers3 = CommonHelper::getTotalUnionMembers('2020-03-31',$value->id);

				$totactivetodefaulter = $active_defaultercount+$struckoffcount;

				$struckoffcount1 = $totactivetodefaulter+$newmembercount-$resignmembercount-$active_defaultercount1;

				$totactivetodefaulter1 = $active_defaultercount1+$struckoffcount1;

				$struckoffcount2 = $totactivetodefaulter1+$newmembercount1-$resignmembercount1-$active_defaultercount2;
				
				$totactivetodefaulter2 = $active_defaultercount2+$struckoffcount2;

				$struckoffcount3 = $totactivetodefaulter2+$newmembercount2-$resignmembercount2-$active_defaultercount3;

				$totstruckoffcount = $struckoffcount+($struckoffcount1-$struckoffcount)+($struckoffcount2-$struckoffcount1)+($struckoffcount3-$struckoffcount2);
			@endphp
			<tr @if($slno==3) class="page-break" @endif >
				<td style="border : 1px solid #988989;font-weight:bold;" rowspan="6">[{{$value->union_branch}}]</td>
				<td style="border : 1px solid #988989;font-weight:bold;" @if(isset($setwidth)) width="250px;" @endif >New members</td>
				<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ '--' }}</th>
				<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $newmembercount }}</th>
				<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $newmembercount1 }}</th>
				<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $newmembercount2 }}</th>
				<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $totnewmembercount }}</th>
			</tr>
			<tr>
				<td style="border : 1px solid #988989;font-weight:bold;">Active members</td>
				<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $activecount }}</th>
				<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $activecount1 }}</th>
				<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $activecount2 }}</th>
				<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $activecount3 }}</th>
				<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $totactivecount }}</th>
			</tr>
			<tr>
				<td style="border : 1px solid #988989;font-weight:bold;">Defaulter members</td>
				<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $defaultercount }}</th>
				<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $defaultercount1 }}</th>
				<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $defaultercount2 }}</th>
				<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $defaultercount3 }}</th>
				<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $totdefaultercount }}</th>
			</tr>
			<tr>
				<td style="border : 1px solid #988989;font-weight:bold;">Active & Defaulter members</td>
				<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $active_defaultercount }}</th>
				<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $active_defaultercount1 }}</th>
				<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $active_defaultercount2 }}</th>
				<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $active_defaultercount3 }}</th>
				<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $totactive_defaultercount }}</th>
			</tr>
			<tr>
				<td style="border : 1px solid #988989;font-weight:bold;">Struckoff members</td>
				<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $struckoffcount }}</th>
				<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $struckoffcount1 }}</th>
				<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $struckoffcount2 }}</th>
				<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $struckoffcount3 }}</th>
				<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $totstruckoffcount }}</th>
			</tr>
			<tr>
				<td style="border : 1px solid #988989;font-weight:bold;">Resigned members</td>
				<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ '--' }}</th>
				<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $resignmembercount }}</th>
				<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $resignmembercount1 }}</th>
				<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $resignmembercount2 }}</th>
				<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $totresignmembercount
				 }}</th>
			</tr>
			@php
				$slno++;
			@endphp
			@endforeach
		</tbody>
		
	</table>
	