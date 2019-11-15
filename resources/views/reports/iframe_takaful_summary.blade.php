<!DOCTYPE html>
<html>
<head>
	<script src="{{ asset('public/assets/js/jquery-1.12.4.min.js') }}" type="text/javascript"></script>
	<link href="{{ asset('public/assets/material-font.css') }}" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/vendors.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/flag-icon.min.css') }}">
	<!-- <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/vertical-modern-menu.css') }}"> -->
	 <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/materialize.css') }}"> 
	<title>{{$data['month_year']}}</title>
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
			.nric_no{
				width:10% !important;
			}
			
			.report-address{
				font-weight:bold;
				font-size:14px;
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
			  position: fixed;
			  top:101px;
			  margin-bottom:20px;
			  background: #343d9f; /* for demo */
			  z-index:999;
			  color:#fff;
			  font-size: 14px;
			}
			.tbody-area{
				top:150px;
				position: absolute;
			}
			.nric_no{
				width:150px !important;
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
	</style>

</head>

<body>
	<div class="" style="text-align: center">
		<table width="100%">
			<tr>
				@php $logo = CommonHelper::getLogo(); @endphp
				<td width="20%"></td>
				<td width="10%"></td>
				<td width="50%" style="text-align:center;">
					
				</td>
				<td width="20%">	
					<a href="#" class="export-button btn btn-sm exportToExcel" style="background:#227849;"><i class="material-icons">explicit</i></a>
					<a href="#" class="export-button btn btn-sm" onClick="$('#page-length-option').tableExport({type:'pdf',escape:'false',filename: 'Takaful Summary Report'});" style="background:#ff0000;"><i class="material-icons">picture_as_pdf</i></a>
					<a href="#" class="export-button btn btn-sm" style="background:#ccc;" onClick="window.print()"><i class="material-icons">print</i></a>
				</td>
			</tr>
		</table>
	</div>
	<!-- <div class="page-footer">
    I'm The Footer
  </div>-->
	<table id="page-length-option" class="display" width="100%">
		<thead>
			<tr class="">
				
				
				<td colspan="3" style="text-align:center;padding:10px;vertical-align:top;" width="100%">
					<img src="{{ asset('public/assets/images/logo/'.$logo) }}" height="50" style="margin-right: 20px;" />
					<span style="text-align:center;font-weight: bold;font-size:18px;vertical-align:top;">NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</span>
					
				</td>
				
			</tr>
			<tr class="">
				
				<td colspan="3" style="text-align:center;padding:10px;font-weight: bold;">
				
					<span style="margin-top:0;">NUBE RETIREMENT INSURANCE SCHEME</span>
				</td>
				
			</tr>
			<tr class="" style="font-weight: bold;">
			
				<td colspan="3" align="center" style="text-align:center;vertical-align:top;border-bottom: 1px solid #988989 !important;">
					{{ date('01 M Y',strtotime($data['month_year'])) }} - {{ date('t M Y',strtotime($data['month_year'])) }}
				</td>
				
			</tr>
			<tr class="">
				<th style="border: 1px solid #988989 !important;">{{__('BANK')}}</th>
				<th style="border: 1px solid #988989 !important;">{{__('TOTAL MEMBERS')}}</th>
				<th style="border: 1px solid #988989 !important;">{{__('AMOUNT(RM)')}}</th>
			</tr>
		</thead>
		<tbody class="" width="100%">
			@php
				$totalamt = 0;
				$totalmembers = 0;
				$sno = 1;
			@endphp
			@foreach($data['head_company_view'] as $company)
				@php
					$company_data = CommonHelper::getMontendcompanySummary($company['company_list'],$data['month_year_full']);
					//dd($company_data);
				@endphp
				@if($company_data->total_members>0)
				<tr>
					<td style="border: 1px solid #988989 !important;">{{$company['companycode']}}</td>
					<td style="border: 1px solid #988989 !important;">{{ $company_data->total_members }}</td>
					<td style="border: 1px solid #988989 !important;">{{ number_format($data['total_ins']*$company_data->total_members,2,".",",") }}</td>
				</tr> 
				@php
					$totalamt += $data['total_ins']*$company_data->total_members;
					$totalmembers += $company_data->total_members;
					$sno++;
				@endphp
				@endif
			@endforeach
			<tr style="font-weight:bold;font-size:16px;">
				<td style="border: 1px solid #988989 !important;"> Grand Total </td>
				<td style="border: 1px solid #988989 !important;">{{ $totalmembers }}</td>
				<td style="border: 1px solid #988989 !important;">{{ number_format($totalamt,2,".",",") }}</td>
			</tr> 
			<tr style="font-weight:bold;font-size:16px;">
				<td colspan="3" style="border: 1px solid #988989 !important;text-align:right;padding-right:0px;">FACILITATION FEES FOR THE MONTH OF {{$data['month_year_read']}}</td>
			</tr>
			<tr style="font-weight:bold;font-size:16px;">
				<td colspan="3" style="border: 1px solid #988989 !important;text-align:right">5% : {{ number_format((($totalamt*5)/100),2,".",",") }}</td>
			</tr>
		</tbody>
		
	</table>
	<div style="clear:both;">&nbsp;</div>
	
</body>
<script>
	var excelfilenames="Takaful Summary Report";
</script>
<script src="{{ asset('public/assets/js/jquery-3.2.1.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/FileSaver.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jspdf.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jspdf_plugin_autotable.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/es6-promise.auto.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/html2canvas.min.js') }}" type="text/javascript"></script>
<!--<![endif]-->
<script type="text/javascript" src="{{ asset('public/assets/js/tableExport.js') }}"></script>
<script src="{{ asset('public/excel/jquery.table2excel.js') }}"></script>
<script>
	$(".exportToExcel").click(function(e){
		$("#page-length-option").table2excel();
	});
	$(document).ready( function() { 
		$("html").css('opacity',1);
    }); 
</script>

</html>