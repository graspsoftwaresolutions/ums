<!DOCTYPE html>
<html>

<head>
	<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/vendors.min.css') }}">
	<!-- <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/vertical-modern-menu.css') }}"> -->
	<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/materialize.css') }}">
	<style>
		/* Styles go here */
		
		tr {
		    border-bottom: none !important; 
		}

		.page-header, .page-header-space {
		  height: 100px;
		  z-index:999;
		}
		
		.page-footer, .page-footer-space {
		  height: 50px;
		
		}
		
		.page-footer {
		  position: fixed;
		  bottom: 0;
		  width: 100%;
		  //border-top: 1px solid black; /* for demo */
		  background: #fff; /* for demo */
		  color:#000;
		}
		
		.page-header {
		  position: fixed;
		  top: 0mm;
		  width: 100%;
		  background: #fff; /* for demo */
		  color:#000;
		}
		
		.page {
		  page-break-after: always;
		}
		
		@page {
		  margin: 3mm
		}
		
		@media print {
			@page {
				size: landscape; 
				margin: 3mm;
			}
		    thead {display: table-header-group;} 
		    tfoot {display: table-footer-group;}
		   
		    button {display: none;}
		   
		    body {margin: 0;}
			.export-button{
				display:none !important;
			}
			.page-header, .page-header-space {
			  height: 70px;
			  z-index:999;
			}
			.page-header,.page-table-header-space {
			  //background: #fff; /* for demo */
			  //color:#000;
			}
			#page-length-option {
			  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
			  border-collapse: collapse;
			  width: 100%;
			}

			#page-length-option td, #page-length-option th {
			  //border: 1px solid #ddd !important;
			  padding: 4px;
			}
			

			.page-header-area{
				display: none;
			}
			
		}
		@media not print {
			table {
			    display: table;
			    width: 100%;
			    border-spacing: 0;
			    border-collapse: none;
			}
			.page-table-header-space {
			  width: 100%;
			 // position: fixed;
			  top:101px;
			  margin-bottom:20px;
			  background: #343d9f; /* for demo */
			  z-index:999;
			  color:#fff;
			  font-size: 14px;
			}
			.tbody-area{
				top:140px;
				//position: absolute;
			}
			.nric_no{
				width:150px !important;
			}
			.page-header-area{
				display: none;
			}
		}
		td, th {
			display: table-cell;
			padding: 7px 5px;
			text-align: left;
			vertical-align: middle;
			//border-radius: 2px;
		}
		.btn, .btn-large, .btn-small, .btn-flat {
			line-height: 36px;
			display: inline-block;
			height: 35px;
			padding: 0 7px;
			vertical-align: middle;
			text-transform: uppercase;
			border: none;
			border-radius: 4px;
			-webkit-tap-highlight-color: transparent;
		}
		.tbody-area{
			color:#000;
		}
		#page-length-option td, #page-length-option th {
			//border: 1px solid #ddd !important;
			padding: 4px;
		}
		html {

		    //font-family: 'Muli', sans-serif;
		    font-weight: normal;
		    line-height: 1; 
		    color: rgba(0, 0, 0, .87);
		    font-size: 12px;
		}
		.nric_no{
			width:10% !important;
		}
		
		.report-address{
			font-weight:bold;
			font-size:14px;
		}

		
		
	</style>
	<script type="text/javascript">
	
	</script>
</head>

<body>
	@php 
		$logo = CommonHelper::getLogo(); 
	@endphp
<table id="page-length-option" class="display" width="100%">
		<thead>
			<tr class="">
				
				<td  rowspan="3" style="text-align:right">
					<img src="{{ public_path('/assets/images/logo/'.$logo) }}" height="50" />
				</td>
				<td colspan="4" style="text-align:center;padding:10px;vertical-align:top;font-weight:bold;">
					<span style="text-align:center;font-weight: bold;font-size:18px;vertical-align:top;">NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</span>
					
				</td>
				
			</tr>
			<tr class="">
				
				<td colspan="4" style="text-align:center;padding:10px;font-weight: bold;">
				
					<span style="margin-top:0;"> STATISTICS FOR {{$data['from_year']}}-{{$data['to_year']}}</span>
				</td>
				
			</tr>
			@php
				$allactivecount = CommonHelper::getUnionGroupStatusCount('2020-03-01',1,'');
				$alldefaulercount = CommonHelper::getUnionGroupStatusCount('2020-03-01',2,'');
			@endphp
			
			<tr class="">
				
				<td colspan="4" style="text-align:center;padding:10px;">
				
					<span style="margin-top:0;"> TOTAL IN BENEFIT MEMBERS : {{ $allactivecount }}, NOT IN BENEFIT : {{ $alldefaulercount }}</span>
				</td>
				
			</tr>
			<tr class="">
				<th style="border-top : 1px solid #988989;border-left: 1px solid #988989 !important;border-right: 1px solid #988989 !important;font-weight:bold;" align="center">UNION BRANCH NAME</th>
				<th style="border : 1px solid #988989;text-align: center;font-weight:bold;" colspan="2" >IN BENEFIT</th>
                <th style="border : 1px solid #988989;text-align: center;font-weight:bold;" colspan="2" >NOT IN BENEFIT</th>
			</tr>
			<tr>
				<th style="border-bottom : 1px solid #988989;border-left: 1px solid #988989 !important;border-right: 1px solid #988989 !important;width: 30px;" ></th>
				<th style="border : 1px solid #988989;font-weight:bold;width: 25px;" >2014-2017</th>
				<th style="border : 1px solid #988989;font-weight:bold;width: 25px;">2017-2020</th>
				<th style="border : 1px solid #988989;font-weight:bold;width: 25px;" >2014-2017</th>
				<th style="border : 1px solid #988989;font-weight:bold;width: 25px;">2017-2020</th>
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
			<!-- 
			<tr>
				<td colspan="10" style="">
					
					<table style="width: 90% !important;margin: 0 5%;">
						
						<tbody>
							
						</tbody>
						
					</table>
					<p style="margin-left: 85px;">NEW MEMBERS </p>
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
						
					</table>
				</td>
				
			</tr>  -->
            <!-- <tr>
				<td colspan="10" style="border : 1px solid #988989;font-weight:bold;">Total ACTIVE Member's Count : 0</td>
				
			</tr>  -->
		</tbody>
		
	</table>
	
</body>


</html>