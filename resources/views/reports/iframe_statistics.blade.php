<!DOCTYPE html>
<html>
<head>
	<title>Statistics Report</title>
	<script src="{{ asset('public/assets/js/jquery-1.12.4.min.js') }}" type="text/javascript"></script>
	<link href="{{ asset('public/assets/material-font.css') }}" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/vendors.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/flag-icon.min.css') }}">
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
		  border-top: 1px solid black; /* for demo */
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
			
			.page-header,.page-table-header-space1 {
			  background: #fff; /* for demo */
			  color:#000;
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
			  border: 1px solid #ddd !important;
			  padding: 4px;
			}
			html {
			    font-family: 'Muli', sans-serif;
			    font-weight: normal;
			    line-height: 1; 
			    color: rgba(0, 0, 0, .87);
			    font-size: 12px;
			}
		}
		@media not print {
			.page-table-header-space {
			  width: 100%;
			  position: fixed;
			  top:101px;
			  z-index:999;
                background: #343d9f; /* for demo */
				  color:#fff;
			}
			
			.page-table-header-space1 {
			  width: 100%;
			  position: fixed;
			  top:160px;
			  background: #343d9f; /* for demo */
			  z-index:999;
			  color:#fff;
              margin-top: -26px;
              
			}
			.tbody-area{
				top:182px;
				position: absolute;
			}
		}
		td, th {
			display: table-cell;
			padding: 7px 5px;
			text-align: left;
			vertical-align: middle;
			border-radius: 2px;
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
		
		
	</style>
	<script type="text/javascript">
		
	</script>
</head>

<body>
	<div class="" style="text-align: center">
		@php $logo = CommonHelper::getLogo(); @endphp
		<table width="100%">
			<tr>
				<td width="20%"></td>
				<td width="10%"></td>
				<td width="50%" style="text-align:center;">
				
				</td>
				<td width="20%">	
					<a href="#" class="export-button btn btn-sm exportToExcel" style="background:#227849;"><i class="material-icons">explicit</i></a>
					<a href="#" class="export-button btn btn-sm" onClick="$('#page-length-option').tableExport({type:'pdf',escape:'false',filename: 'Statistics Report'});" style="background:#ff0000;"><i class="material-icons">picture_as_pdf</i></a>
					<a href="#" class="export-button btn btn-sm" style="background:#ccc;" onClick="window.print()"><i class="material-icons">print</i></a>
				</td>
			</tr>
		</table>
	</div>
	<!-- <div class="page-footer">
    I'm The Footer
  </div>-->
	<table id="page-length-option" class="display" >
		<thead>
			<tr class="">
				
				<td></td>
				<td colspan="{{ (count($data['race_view'])*4)+6 }}" style="text-align:center;padding:10px;vertical-align:top;">
					<img src="{{ asset('public/assets/images/logo/'.$logo) }}" height="50" style="margin-right: 20px;" />
					<span style="text-align:center;font-weight: bold;font-size:18px;vertical-align:top;">NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</span>
					
				</td>
				<td colspan="2"></td>
				
			</tr>
			<tr class="">
				<td></td>
				<td colspan="{{ (count($data['race_view'])*4)+6 }}" style="text-align:center;padding:10px;font-weight: bold;">
				
					<span style="margin-top:0;">OVER ALL BANK BRANCH REPORT</span>
				</td>
				<td colspan="2"></td>
				
			</tr>
			<tr class="" style="font-weight: bold;">
				<td></td>
				<td colspan="{{ (count($data['race_view'])*4)+6 }}" align="center" style="text-align:center;vertical-align:top;border-bottom: 1px solid #988989 !important;">
					{{ date('01 M Y',strtotime($data['month_year'])) }} - {{ date('t M Y',strtotime($data['month_year'])) }}
				</td>
				<td colspan="2"></td>
			</tr>
			<tr class="" style="border:1px solid #000;">
				<th style="border:1px ;"></th>
				<th colspan="{{ (count($data['race_view'])*2)+3 }}" style="width:487px !important;border:1px ;">BENEFIT</th>
				<th colspan="{{ (count($data['race_view'])*2)+3 }}" style="width:487px !important;border:1px ;">NON BENEFIT</th>
				
				<th colspan="2" style="border:1px ;"></th>
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
			$month_year = $data['month_year'];
			$uniques = array();
			foreach ($data['member_count'] as $obj) {
			    $uniques[$obj->branchid] = $obj;
			}

		@endphp
        @foreach($uniques as $values)
        	@php
        		$over_all_count = CommonHelper::group_all_gender_race_count($data['member_count'],$values->branchid,$month_year);
        	@endphp
            <tr style="margin-top:50px !important;">
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
					$month_year = $data['month_year'];
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
				<td style=''>
					Total
				</td>
			    
				@foreach($data['race_view'] as $race)
					<td style="border: 1px solid #988989 !important;"></td>
				@endforeach
				<td style="border: 1px solid #988989 !important;"> </td>
				@foreach($data['race_view'] as $value)
				
				<td style="border: 1px solid #988989 !important;"></td>
					
				@endforeach
			
				<td style="border: 1px solid #988989 !important;"> </td>
				<td style="border: 1px solid #988989 !important;"></td>
				@foreach($data['race_view'] as $value)
					<td style="border: 1px solid #988989 !important;"></td>
				@endforeach
				<td style="border: 1px solid #988989 !important;"></td>
				@foreach($data['race_view'] as $value)
					<td style="border: 1px solid #988989 !important;"></td>
				@endforeach
				<td style="border: 1px solid #988989 !important;"></td>
				<td style="border: 1px solid #988989 !important;"></td>
				<td style="border: 1px solid #988989 !important;">{{$total_grandtotal}}</td>
            </tr> 
		</tbody>
	</table>
	<input type="text" name="memberoffset" id="memberoffset" class="hide" value="{{$data['data_limit']}}"></input>
</body>
<script src="{{ asset('public/assets/js/jquery-3.2.1.min.js') }}" type="text/javascript"></script>
<!-- <script src="{{ asset('public/assets/js/xlsx.core.min.js') }}" type="text/javascript"></script> -->
<script src="{{ asset('public/assets/js/FileSaver.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jspdf.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jspdf_plugin_autotable.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/es6-promise.auto.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/html2canvas.min.js') }}" type="text/javascript"></script>
<!--<![endif]-->
<script type="text/javascript" src="{{ asset('public/assets/js/tableExport.js') }}"></script>
<script>
	$(document).ready( function() { 
		$("html").css('opacity',1);
    });
	$('#tableID').tableExport({
		type:'pdf',
		jspdf: {
			orientation: 'p',
			margins: {
				left:20, top:10
			},
			autotable: false
		}
	});
</script>

</html>
