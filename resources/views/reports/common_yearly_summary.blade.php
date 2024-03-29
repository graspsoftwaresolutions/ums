@php 
	$logo = CommonHelper::getLogo(); 
@endphp
<table id="page-length-option" class="display" width="100%">
		<thead>
			<tr class="">
				
				<td colspan="1" rowspan="2" style="text-align:right">
					<img src="{{ asset('public/assets/images/logo/'.$logo) }}" height="50" />
				</td>
				<td colspan="6" style="text-align:center;padding:10px;vertical-align:top;">
					<br/>
					<span style="text-align:center;font-weight: bold;font-size:18px;vertical-align:top;">NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</span>
					
				</td>
				<td colspan="3" rowspan="2">	
					</br>
				</td>
			</tr>
			<tr class="">
				
				<td colspan="6" style="text-align:center;padding:10px;font-weight: bold;">
				
					</br>
				</td>
				
			</tr>
			
			
			
		</thead>
		<tbody class="" width="100%">
			<tr>
				<td colspan="10" style="">
					<center> <p style="margin-top:0;text-align: center;font-weight: bold;">NEW MEMBERSHIP RECRUITED FOR THE TERM {{$data['from_year']}}-{{$data['to_year']}}</p></center>
					</br>
					<table class="pdf-align" style="width: 90% ;margin: 0 5%;">
						<thead>
							<tr class="">
								<th style="border : 1px solid #988989;" @if(isset($setwidth)) width="150px;" @endif align="center">YEAR</th>
								<th style="border : 1px solid #988989;text-align: center;" @if(isset($setwidth)) width="100px;" @endif >{{__('PKP')}}</th>
				                <th style="border : 1px solid #988989;text-align: center;" @if(isset($setwidth)) width="100px;" @endif >{{__('IPOH')}}</th>
				                <th style="border : 1px solid #988989;text-align: center;" @if(isset($setwidth)) width="100px;" @endif >{{__('KLSP')}}</th>
				                <th style="border : 1px solid #988989;text-align: center;" @if(isset($setwidth)) width="100px;" @endif >{{__('SMJ')}}</th>
				                <th style="border : 1px solid #988989;text-align: center;" @if(isset($setwidth)) width="100px;" @endif >{{__('KELANTAN')}}</th>
				                <th style="border : 1px solid #988989;text-align: center;" @if(isset($setwidth)) width="250px;" @endif >{{__('NEW MEMBERS RECRUITED')}}</th>
							</tr>
						</thead>
						<tbody>
							@php
								$overall_total = 0;
								$totnewmembers1 = 0;
								$totnewmembers2 = 0;
								$totnewmembers3 = 0;
								$totnewmembers4 = 0;
								$totnewmembers5 = 0;
							@endphp
							@for($ty=$data['from_year']+1; $ty<=$data['to_year']; $ty++)
							@php
								$newmembers1 = CommonHelper::getYearlybasedCount($ty,1);
								$newmembers2 = CommonHelper::getYearlybasedCount($ty,2);
								$newmembers3 = CommonHelper::getYearlybasedCount($ty,3);
								$newmembers4 = CommonHelper::getYearlybasedCount($ty,4);
								$newmembers5 = CommonHelper::getYearlybasedCount($ty,5);
								$total = $newmembers1+$newmembers2+$newmembers3+$newmembers4+$newmembers5;
								$overall_total += $total;
								$totnewmembers1 += $newmembers1;
								$totnewmembers2 += $newmembers2;
								$totnewmembers3 += $newmembers3;
								$totnewmembers4 += $newmembers4;
								$totnewmembers5 += $newmembers5;
							@endphp
							<tr>
								<td style="border : 1px solid #988989;font-weight:bold;">{{$ty-1}}-{{ $ty }}</td>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $newmembers1 }}</th>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $newmembers2 }}</th>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $newmembers3 }}</th>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $newmembers4 }}</th>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $newmembers5 }}</th>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $total }}</th>
							</tr>
							@endfor
							<tr>
								<td style="border : 1px solid #988989;font-weight:bold;">TOTAL</td>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $totnewmembers1 }}</th>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $totnewmembers2 }}</th>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $totnewmembers3 }}</th>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $totnewmembers4 }}</th>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $totnewmembers5 }}</th>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $overall_total }}</th>
							</tr>
							
						</tbody>
						
					</table>

				</td>
				
			</tr> 
           <tr>
				<td colspan="10" style="">
					<br/>
					<br/>
					<center style="font-weight: bold;"> <p style="margin-top:0;text-align: center;font-weight: bold;">MEMBERSHIP RESIGNATION FROM {{$data['from_year']}}-{{$data['to_year']}}</p></center>

					<table class="pdf-align" style="width: 90% ;margin: 0 5%;">
						<thead>
							<tr class="">
								<th style="border : 1px solid #988989;" @if(isset($setwidth)) width="150px;" @endif align="center">YEAR</th>
								<th style="border : 1px solid #988989;text-align: center;" @if(isset($setwidth)) width="100px;" @endif >{{__('PROMOTED')}}</th>
				                <th style="border : 1px solid #988989;text-align: center;" @if(isset($setwidth)) width="100px;" @endif >{{__('RESIGNED')}}</th>
				                <th style="border : 1px solid #988989;text-align: center;" @if(isset($setwidth)) width="100px;" @endif >{{__('RETIRED')}}</th>
				                <th style="border : 1px solid #988989;text-align: center;" @if(isset($setwidth)) width="100px;" @endif >{{__('VSS')}}</th>
				                <th style="border : 1px solid #988989;text-align: center;" @if(isset($setwidth)) width="100px;" @endif >{{__('OTHERS')}}</th>
				                <th style="border : 1px solid #988989;text-align: center;" @if(isset($setwidth)) width="250px;" @endif >{{__('TOTAL')}}</th>
							</tr>
						</thead>
						<tbody>
							@php
								$overall_total = 0;
								$totnewmembers1 = 0;
								$totnewmembers2 = 0;
								$totnewmembers3 = 0;
								$totnewmembers4 = 0;
								$totnewmembers5 = 0;
							@endphp
							@for($ty=$data['from_year']+1; $ty<=$data['to_year']; $ty++)
							@php
								$newmembers1 = CommonHelper::getResignYearlybasedCount($ty,1);
								$newmembers2 = CommonHelper::getResignYearlybasedCount($ty,2);
								$newmembers3 = CommonHelper::getResignYearlybasedCount($ty,3);
								$newmembers4 = CommonHelper::getResignYearlybasedCount($ty,4);
								$newmembers5 = CommonHelper::getResignYearlybasedCount($ty,5);
								$total = $newmembers1+$newmembers2+$newmembers3+$newmembers4+$newmembers5;
								$overall_total += $total;
								$totnewmembers1 += $newmembers1;
								$totnewmembers2 += $newmembers2;
								$totnewmembers3 += $newmembers3;
								$totnewmembers4 += $newmembers4;
								$totnewmembers5 += $newmembers5;
							@endphp
							<tr>
								<td style="border : 1px solid #988989;font-weight:bold;">{{$ty-1}}-{{ $ty }}</td>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $newmembers1 }}</th>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $newmembers2 }}</th>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $newmembers3 }}</th>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $newmembers4 }}</th>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $newmembers5 }}</th>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $total }}</th>
							</tr>
							@endfor
							<tr>
								<td style="border : 1px solid #988989;font-weight:bold;">TOTAL</td>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $totnewmembers1 }}</th>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $totnewmembers2 }}</th>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $totnewmembers3 }}</th>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $totnewmembers4 }}</th>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $totnewmembers5 }}</th>
								<th style="border : 1px solid #988989;font-weight:bold;text-align: right;">{{ $overall_total }}</th>
							</tr>
							
						</tbody>
						
					</table>
				</td>
				
			</tr> 
		</tbody>
		
	</table>
