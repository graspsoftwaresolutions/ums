<html style="opacity: 1;"><head>
	<script src="http://localhost/ums/public/assets/js/jquery-1.12.4.min.js" type="text/javascript"></script>
	<link href="http://localhost/ums/public/assets/material-font.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="http://localhost/ums/public/assets/css/vendors.min.css">
	<link rel="stylesheet" type="text/css" href="http://localhost/ums/public/assets/css/flag-icon.min.css">
	<!-- <link rel="stylesheet" type="text/css" href="http://localhost/ums/public/assets/css/vertical-modern-menu.css"> -->
	 <link rel="stylesheet" type="text/css" href="http://localhost/ums/public/assets/css/materialize.css"> 
	<title>2020-01-01</title>
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
		
		@page  {
		  margin: 3mm
		}
		
		@media  print {
			@page  {
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
		@media  not print {
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
				top:160px;
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
				
				<td colspan="2" rowspan="2" style="text-align:right">
				<img src="{{ public_path('/assets/images/logo/'.$logo) }}" height="50" />
				</td>
				<td colspan="3" style="text-align:center;padding:10px;vertical-align:top;">
					<span style="text-align:center;font-weight: bold;font-size:18px;vertical-align:top;">NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</span>
					
				</td>
				<td colspan="2" rowspan="2">	
					
				</td>
			</tr>
			<tr class="">
				
				<td colspan="3" style="text-align:center;padding:10px;font-weight: bold;">
				
					<span style="margin-top:0;">NUBE RETIREMENT INSURANCE SCHEME</span>
				</td>
				
			</tr>
			<tr class="" style="font-weight: bold;">
			
				<td colspan="2" style="border-bottom: 1px solid #988989 !important;">
					To Branch Hons. Secretary
									</td>
				<td colspan="3" align="center" style="text-align:center;vertical-align:top;border-bottom: 1px solid #988989 !important;">
					01 Jan 2020 - 31 Jan 2020
				</td>
				<td colspan="2" style="border-bottom: 1px solid #988989 !important;">	
					
									</td>
			</tr>
			<tr class="">
				<th style="border : 1px solid #988989;">SNO</th>
				<th style="border : 1px solid #988989;">BANK</th>
				<th style="border : 1px solid #988989;">BRANCH</th>
				<th style="border : 1px solid #988989;">NAME</th>
				<th style="border : 1px solid #988989;">MEMBERID</th>
				<th style="border : 1px solid #988989;">NRIC</th>
				<th style="border : 1px solid #988989;">INSURANCE AMOUNT(RM)</th>
			</tr>
		</thead>
		<tbody class="" width="100%">
			@php
				$totalamt = 0;
				$sno = 1;
			@endphp
			@foreach($data['member_view'] as $member)
					<tr>
						<td style="border : 1px solid #988989;">{{$sno}}</td>
						<td style="border : 1px solid #988989;">{{$member->companycode}}</td>
						<td style="border : 1px solid #988989;">{{$member->branch_name}}</td>
						<td style="border : 1px solid #988989;">{{$member->name}}</td>
						<td style="border : 1px solid #988989;">{{$member->member_number}}</td>
						<td style="border : 1px solid #988989;">{{$member->new_ic}}</td>
						<td style="border : 1px solid #988989;">{{ number_format($data['total_ins'],2,".",",") }}</td>
					</tr> 
					@php
							$totalamt += $data['total_ins'];
							$sno++;
					@endphp
			@endforeach
									<tr style="font-weight:bold;">
				<td style="border : 1px solid #988989;" colspan="6"> Total Amount</td>
				<td style="border : 1px solid #988989;">0.00</td>
			</tr> 
			<tr style="font-weight:bold;">
				<td style="border : 1px solid #988989;" colspan="6"> Total Members</td>
				<td style="border : 1px solid #988989;">0</td>
			</tr> 
		</tbody>
		
		
	</table>	
	<input type="text" name="memberoffset" id="memberoffset" class="hide" value="25">

<script>
	var excelfilenames="Takaful Report";
</script>
<script src="http://localhost/ums/public/assets/js/jquery-3.2.1.min.js" type="text/javascript"></script>
<script src="http://localhost/ums/public/assets/js/FileSaver.min.js" type="text/javascript"></script>
<script src="http://localhost/ums/public/assets/js/jspdf.min.js" type="text/javascript"></script>
<script src="http://localhost/ums/public/assets/js/jspdf_plugin_autotable.js" type="text/javascript"></script>
<script src="http://localhost/ums/public/assets/js/es6-promise.auto.min.js" type="text/javascript"></script>
<script src="http://localhost/ums/public/assets/js/html2canvas.min.js" type="text/javascript"></script>
<!--<![endif]-->
<script type="text/javascript" src="http://localhost/ums/public/assets/js/tableExport.js"></script>
<script src="http://localhost/ums/public/excel/jquery.table2excel.js"></script>


<script>
	$(".exportToExcel").click(function(e){
		$("#page-length-option").table2excel();
	});
	//loader.showLoader();
	$(document).ready( function() { 
		$("html").css('opacity',1);
    }); 
	
</script>

</body></html>