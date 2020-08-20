@php 
	$logo = CommonHelper::getLogo(); 
@endphp
<table id="page-length-option" class="display" >
		<thead>
			<tr class="">
				
				<td rowspan="3" style="text-align:right;"><img src="{{ asset('public/assets/images/logo/'.$logo) }}" height="50" /></td>
				<td colspan="{{ (count($data['race_view'])*4)+6 }}" style="text-align:center;padding:10px;vertical-align:top;">
					
					<span style="text-align:center;font-weight: bold;font-size:18px;vertical-align:top;">NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</span>
					
				</td>
				<td colspan="1"></td>
				
			</tr>
			<tr class="">
				<td></td>
				<td colspan="{{ (count($data['race_view'])*4)+6 }}" style="text-align:center;padding:10px;font-weight: bold;">
				
					<span style="margin-top:0;">OVER ALL BANK BRANCH REPORT</span>
				</td>
				<td colspan="1"></td>
				
			</tr>
			<tr class="" style="font-weight: bold;">
				<td></td>
				<td colspan="{{ (count($data['race_view'])*4)+6 }}" align="center" style="text-align:center;vertical-align:top;">
					{{ date('01 M Y',strtotime($data['from_month_year'])) }} - {{ date('t M Y',strtotime($data['to_month_year'])) }}
					</br>
				</td>
				<td colspan="1"></td>
			</tr>
			<tr class="" style="border: none;">
				<td></td>
				<td colspan="{{ (count($data['race_view'])*4)+6 }}" align="center" style="">
					</br>
				</td>
				<td colspan="1"></td>
			</tr>
			<tr class="" style="border:1px solid #988989;">
				<th style="border: 1px solid #988989 !important;"></th>
				<th colspan="{{ (count($data['race_view'])*2)+3 }}" style="border: 1px solid #988989 !important;">BENEFIT</th>
				<th colspan="{{ (count($data['race_view'])*2)+3 }}" style="border: 1px solid #988989 !important;">NON BENEFIT</th>
				
				<th colspan="" style="border: 1px solid #988989 !important;"></th>
			</tr>
			<tr class="" >
				<th style="border: 1px solid #988989 !important;">{{__('BRANCH CODE')}}</th>
				@foreach($data['race_view'] as $values)
					<th style="border: 1px solid #988989 !important;">M{{$values->race_name[0]}}</th>
				@endforeach
				<th style="border: 1px solid #988989 !important;">{{__('S.TOTAL')}}</th>
				@foreach($data['race_view'] as $values)
					<th style="border: 1px solid #988989 !important;">F{{$values->race_name[0]}}</th>
				@endforeach
				<th style="border: 1px solid #988989 !important;">{{__('S.TOTAL')}}</th>
				<th style="border: 1px solid #988989 !important;">{{__('TOTAL')}}</th>
				@foreach($data['race_view'] as $values)
					<th style="border: 1px solid #988989 !important;">M{{$values->race_name[0]}}</th>
				@endforeach
				<th style="border: 1px solid #988989 !important;">{{__('S.TOTAL')}}</th>
				@foreach($data['race_view'] as $values)
					<th style="border: 1px solid #988989 !important;">F{{$values->race_name[0]}}</th>
				@endforeach
				<th style="border: 1px solid #988989 !important;">{{__('S.TOTAL')}}</th>
				<th style="border: 1px solid #988989 !important;">{{__('TOTAL')}}</th>
				<th style="border: 1px solid #988989 !important;">{{__('G.TOTAL')}}</th>
			</tr>
		</thead>
		<tbody class="" width="100%">
		@php
			$total_grandtotal = 0;
			$from_month_year = $data['from_month_year'];
			$to_month_year = $data['to_month_year'];
			$uniques = array();
			foreach ($data['member_count'] as $obj) {
			    $uniques[$obj->branchid] = $obj;
			}
			foreach($data['race_view'] as $race){
				$raceid = $race->id;
				$total_benifit_m_race{$raceid} = 0;
				$total_benifit_f_race{$raceid} = 0;
				$total_nonbenifit_m_race{$raceid} = 0;
				$total_nonbenifit_f_race{$raceid} = 0;
			}
			


		@endphp
        @foreach($uniques as $values)
        	@php
        		$over_all_count = CommonHelper::group_all_gender_race_count($data['member_count'],$values->branchid,$from_month_year,$to_month_year);
        	@endphp
            <tr style="">
				<td style='border: 1px solid #988989 !important;'>
					@php 
						if($values->branch_shortcode==''){
							echo $branch_name = $values->companycode.'_'.substr($values->branch_name, 0, 16); 
						}
						else 
						{
							echo $values->companycode.'_'.$values->branch_shortcode;
						}
						
					@endphp
				</td>
			    @php
					$from_month_year = $data['from_month_year'];
					$subtotal1 = 0;
					$subtotal2 = 0;
					$subtotaldefaulter2 = 0;
					$defaultertotal = 0;
					$total = 0;
					$subtotaldefaulter1 = 0;
					
					$grandtotal = 0;
					$male_count = 0;
				@endphp
				@foreach($data['race_view'] as $race)
				@php 
					$race_id = $race->id;
					$male_count = CommonHelper::get_group_gender_race_count($over_all_count,$race_id,1,'Male');
					$total_benifit_m_race{$race_id} += $male_count;
				@endphp
					<td style="border: 1px solid #988989 !important; ">{{$male_count}}</td>
				@php
					$subtotal1 += $male_count; 
				@endphp
				@endforeach
				<td style="border: 1px solid #988989 !important;"> {{$subtotal1}}</td>
				@foreach($data['race_view'] as $value)
					@php 
						$race_id = $value->id;
						$female_count = CommonHelper::get_group_gender_race_count($over_all_count,$race_id,1,'Female');
						$total_benifit_f_race{$race_id} += $female_count;
					@endphp
				<td style="border: 1px solid #988989 !important;">{{$female_count}}</td>
					@php
					$subtotal2 += $female_count; 
					@endphp
				@endforeach
				@php 
					$total = $subtotal1 + $subtotal2; 
				@endphp
				<td style="border: 1px solid #988989 !important;"> {{$subtotal2}}</td>
				<td style="border: 1px solid #988989 !important;">{{$total}}</td>
				@foreach($data['race_view'] as $value)
				@php 
					$race_id = $value->id;
					$maledefaulter_count = CommonHelper::get_group_gender_race_count($over_all_count,$race_id,2,'Male');
					$total_nonbenifit_m_race{$race_id} += $maledefaulter_count;
				@endphp
					<td style="border: 1px solid #988989 !important;">{{$maledefaulter_count}}</td>
				@php
					$subtotaldefaulter1 += $maledefaulter_count; 
					@endphp
				@endforeach
				<td style="border: 1px solid #988989 !important;"> {{$subtotaldefaulter1}}</td>
				@foreach($data['race_view'] as $value)
				@php $race_id = $value->id;
					$femaledefaulter_count = CommonHelper::get_group_gender_race_count($over_all_count,$race_id,2,'Female');
					$total_nonbenifit_f_race{$race_id} += $femaledefaulter_count;
				@endphp
					<td style="border: 1px solid #988989 !important;">{{$femaledefaulter_count}}</td>
					@php
						$subtotaldefaulter2 += $femaledefaulter_count; 
						$defaultertotal = $subtotaldefaulter1 + $subtotaldefaulter2; 
						$grandtotal = $defaultertotal + $total;
					@endphp
				@endforeach
				@php
					$total_grandtotal += $grandtotal;
				@endphp
				<td style="border: 1px solid #988989 !important;">{{$subtotaldefaulter2}}</td>
				<td style="border: 1px solid #988989 !important;">{{$defaultertotal}}</td>
				<td style="border: 1px solid #988989 !important;">{{$grandtotal}}</td>
            </tr> 
            @endforeach
			<tr style="">
				<td style='border: 1px solid #988989 !important;'>
					Total
				</td>
			    @php
			    	$total_benifit_m = 0;
			    	$total_benifit_f = 0;
			    	$total_nonbenifit_m = 0;
			    	$total_nonbenifit_f = 0;
			    @endphp
				@foreach($data['race_view'] as $race)
					<td style="border: 1px solid #988989 !important;">{{ $total_benifit_m_race{$race->id} }}</td>
					 @php
				    	$total_benifit_m += $total_benifit_m_race{$race->id};
				    @endphp
				@endforeach
				<td style="border: 1px solid #988989 !important;"> {{ $total_benifit_m }}</td>
				@foreach($data['race_view'] as $race)
				
				<td style="border: 1px solid #988989 !important;">{{ $total_benifit_f_race{$race->id} }}</td>
					@php
				    	$total_benifit_f += $total_benifit_f_race{$race->id};
				    @endphp
				@endforeach
			
				<td style="border: 1px solid #988989 !important;"> {{ $total_benifit_f }} </td>
				<td style="border: 1px solid #988989 !important;">{{ $total_benifit_m+$total_benifit_f }}</td>
				@foreach($data['race_view'] as $race)
					<td style="border: 1px solid #988989 !important;">{{ $total_nonbenifit_m_race{$race->id} }}</td>
					@php
				    	$total_nonbenifit_m += $total_nonbenifit_m_race{$race->id};
				    @endphp
				@endforeach
				<td style="border: 1px solid #988989 !important;">{{ $total_nonbenifit_m }}</td>
				@foreach($data['race_view'] as $race)
					<td style="border: 1px solid #988989 !important;">{{ $total_nonbenifit_f_race{$race->id} }}</td>
					@php
				    	$total_nonbenifit_f += $total_nonbenifit_f_race{$race->id};
				    @endphp
				@endforeach
				<td style="border: 1px solid #988989 !important;">{{ $total_nonbenifit_f }}</td>
				<td style="border: 1px solid #988989 !important;">{{ $total_nonbenifit_m+$total_nonbenifit_f }}</td>
				<td style="border: 1px solid #988989 !important;">{{$total_grandtotal}}</td>
            </tr> 
		</tbody>
	</table>