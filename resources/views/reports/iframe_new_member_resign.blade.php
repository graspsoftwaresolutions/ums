<!DOCTYPE html>
<html>

<head>
	<title>New Member/Resign Report</title>
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

		 table { page-break-inside:auto }
    tr    { page-break-inside:avoid; page-break-after:auto }
    thead { display:table-header-group }
    tfoot { display:table-footer-group }
		
		.page {
		  page-break-after: always;
		}

		@page {
		  //margin: 1cm;
		}
		@page:first {
			  /* No margin on the first page. */
			  //margin-top: 0.7cm !important;
		}
		
		
		@media print {
			 table { page-break-after:auto }
			  tr    { page-break-inside:avoid; page-break-after:auto }
			  td    { page-break-inside:avoid; page-break-after:auto }
			  thead { display:table-header-group }
			  tfoot { display:table-footer-group }
			@page {
				size: landscape; 
				//margin-left: 1.1cm;
				//margin-right: 1.1cm;
			}

			body { margin-right: 0.2cm ; }
		
		   
		    button {display: none;}
		   
		   
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
			html {

			    //font-family: 'Muli', sans-serif;
			    font-weight: normal;
			    line-height: 1; 
			    color: rgba(0, 0, 0, .87);
			    font-size: 12px;
			}
			.nric_no{
				width:4% !important;
			}
			
			.report-address{
				font-weight:bold;
				font-size:14px;
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
				width:123px !important;
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
		
	</style>
	<script type="text/javascript">
		
	</script>
</head>

<body>
	<div class="" style="text-align: center">
		<table width="100%" style="border: none;">
			<tr>
				@php 
					$searchfilters = '&from_date='.$data['from_date'].'&to_date='.$data['to_date'].'&type='.$data['type'].'&uniongroup_id='.$data['uniongroup_id'].'&unionbranch_id='.$data['unionbranch_id'];
				@endphp
				<td width="20%"></td>
				<td width="10%"></td>
				<td width="50%" style="text-align:center;">
					
				</td>
				<td width="20%">	
				<!-- 	<a href="#" class="exportToExcel export-button btn btn-sm" style="background:#227849;"><i class="material-icons">explicit</i></a>
					<a href="{{ url(app()->getLocale().'/export-pdf-members-new?offset=0'.$searchfilters) }}" class="export-button btn btn-sm" style="background:#ff0000;"><i class="material-icons">picture_as_pdf</i></a> -->
					<a href="#" class="export-button btn btn-sm" style="background:#ccc;" onClick="window.print()"><i class="material-icons">print</i></a>
				</td>
			</tr>
		</table>
	</div>
	<!-- <div class="page-footer">
    I'm The Footer
  </div>-->
  	@include('reports.common_members_new_resign')
	
	<input type="text" name="memberoffset" id="memberoffset" class="hide" value="100"></input>
</body>
<script src="{{ asset('public/excel/jquery-ui.min.js') }}"></script>
<script>
	var excelfilenames="New Members report";
</script>
<script src="{{ asset('public/excel/jquery.table2excel.js') }}"></script>
<!--script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.4.1/jspdf.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/2.3.5/jspdf.plugin.autotable.min.js"></script>
<script type="text/javascript" src="https://www.jqueryscript.net/demo/export-table-json-csv-txt-pdf/src/tableHTMLExport.js"></script-->
<script>
	$(document).ready( function() { 
		$("html").css('opacity',1);

		$(".exportToExcel").click(function(e){
			$("#page-length-option").table2excel();
		});
    }); 
	
</script>

</html>