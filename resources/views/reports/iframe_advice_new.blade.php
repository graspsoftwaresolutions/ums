<!DOCTYPE html>
<html>

<head>
	<script src="{{ asset('public/assets/js/jquery-1.12.4.min.js') }}" type="text/javascript"></script>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
			border: 1px solid #ddd !important;
			padding: 4px;
		}
		
		
	</style>
	<script type="text/javascript">
	
	</script>
</head>

<body>
	<div class="" style="text-align: center">
		<table width="100%">
			<tr>
				@php $logo = CommonHelper::getLogo(); 

				@endphp
				<td width="20%"></td>
				<td width="10%" style="display:none;"><img src="{{ asset('public/assets/images/logo/'.$logo) }}" style="vertical-align: middle;float: right;" alt="Membership logo" height="50"></td>
				<td width="50%" style="text-align:center;display:none;">
					<span class="report-address" style="font-weight: bold;font-size:14px;">NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</span>
					<br/> 
					<h6 style="text-align:center;">NUBE BRANCH'S ADVICE LIST</h6>
				</td>
				<td width="20%" style="text-align:right;">	
					<a href="#" class="exportToExcel export-button btn btn-sm"><i class="material-icons">explicit</i></a>
					<a href="#" class="export-button btn btn-sm" style="display:none;" onClick="$('#page-length-option').tableExport({type:'excel',escape:'false',filename: 'New Advice Members Report'});" style="background:#227849;"><i class="material-icons">explicit</i></a>
					<a href="#" class="export-button btn btn-sm" onClick="$('#page-length-option').tableExport({type:'pdf',escape:'false',filename: 'New Advice Members Report'});" style="background:#ff0000;"><i class="material-icons">picture_as_pdf</i></a>
					<a href="#" class="export-button btn btn-sm" style="background:#ccc;" onClick="window.print()"><i class="material-icons">print</i></a>
				</td>
			</tr>
		</table>
	</div>
	<!-- <div class="page-footer">
    I'm The Footer
  </div>-->
	<table id="page-length-option" class="display table2excel" width="100%" data-tableName="New Advice Members Report">
		<thead>
			
			<tr class="" style="width: 100%;">
				@php $logo = CommonHelper::getLogo(); @endphp
				
				<td colspan="2" rowspan="2">
					<img src="{{ asset('public/assets/images/logo/'.$logo) }}" height="50" />
				</td>
				<td colspan="7" style="text-align:center;padding:10px;vertical-align:top;">
					<span style="text-align:center;font-weight: bold;font-size:18px;vertical-align:top;">NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</span>
					
				</td>
				<td colspan="4" rowspan="2">	
					</br>
				</td>
			</tr>
			<tr class="" style="width: 100%;">
				
				<td colspan="7" style="text-align:center;padding:10px;font-weight: bold;">
				
					<span style="margin-top:0;">NUBE BRANCH'S ADVICE LIST</span>
				</td>
				
			</tr>
			<tr class="" style="width: 100%;font-weight: bold;">
				
				<td colspan="2">
					To Branch Hons. Secretary
					@if($data['unionbranch_id']!='')
						<br>
						Branch Name : {{ $data['unionbranch_name'] }}
					@endif
				</td>
				<td colspan="7" align="center" style="text-align:center;vertical-align:top;">
					{{ date('d M Y',strtotime($data['from_date'])) }} - {{ date('d M Y',strtotime($data['to_date'])) }}
				</td>
				<td colspan="4">	
					
					@if($data['unionbranch_id']!='')
						<br>
						Branch Code : {{ $data['unionbranch_id'] }}
					@endif
				</td>
			</tr>
			<tr class="" >
				<th align="center">SNO</th>
                <th style="">{{__('NAME')}}</th>
                <th style="">{{__('I.C.NO')}}</th>
                <th style="">{{__('BANK')}}</th>
                <th style="">{{__('BANK BRANCH')}}</th>
                <th style="">{{__('M/NO')}}</th>
                <th style="">{{__('DOJ')}}</th>
                <th style="">{{__('CLERK')}}</th>
                <th style="">{{__('ENT FEE')}}</th>
                <th style="">{{__('SUBS')}}</th>
                <th style="">{{__('B/F')}}</th>
                <th style="">{{__('HQ')}}</th>
                <th style="">{{__('RMK')}}</th>
			</tr>
		</thead>
		<tbody class="tbody-area" width="100%">
			@php
				$sno = 1;
				$total_paid = 0;
				$total_ent_amount = 0;
				$total_bf_amount = 0;
				$total_hq_amount = 0;
				$total_subs = 0;
			@endphp
			@foreach($data['member_view'] as $member)
				<tr>
					<td style="">{{ $sno }}</td>
                    <td style="">{{ $member->name }}</td>
                    <td style="">{{ $member->ic }}</td>
                    <td style="">{{ $member->companycode }}</td>
                    <td style="">{{ $member->branch_name }}</td>
                    <td style="">{{ $member->member_number }}</td>
                   
                    <td style="">{{ date("d/M/Y",strtotime($member->doj)) }}</td>
                    <td style="">{{ $member->designation_name }}</td>
                    
                    <td style="">{{ $data['ent_amount'] }}</td>
                    <td style="">{{ $member->subs }}</td>
                    <td style="">{{ $data['bf_amount'] }}</td>
                    <td style="">{{ $data['hq_amount'] }}</td>
                    <td style="">{{ 'N' }}</td>
				</tr> 
				@php
					$sno++;
					$total_ent_amount += $data['ent_amount'];
					$total_bf_amount += $data['bf_amount'];
					$total_hq_amount += $data['hq_amount'];
					$total_subs += $member->subs;
				@endphp
			@endforeach
			<tr style="font-weight:bold;">
				<td colspan="2" style="font-weight:bold;">Total Member's Count </td>
				<td align="left" colspan="6"> {{ $sno-1 }}</td>
				<td>{{ $total_ent_amount }}</td>
				<td>{{ $total_subs }}</td>
				<td>{{ $total_bf_amount }}</td>
				<td>{{ $total_hq_amount }}</td>
				<td></td>
			</tr> 
			@php
				$total_paid = round($total_ent_amount + $total_subs + $total_bf_amount + $total_hq_amount,2);
			@endphp
			<tr>
				<td colspan="2" style="font-weight:bold;">Total Amount Collected </td>
				<td colspan="11" style="font-weight:bold;" align="left">{{ $total_paid }}</td>
			</tr> 
			<tr>
				<td colspan="13"></td>
				
			</tr> 
			<tr>
				<td colspan="8"></td>
				<td colspan="5" style="text-align: center;margin-top: 100px;vertical-align:top;font-weight:bold;" align="center">
					Your Fraternally,
				</td>
			</tr>
		
			<tr>
				<td colspan="13" rowspan="2"></td>
			</tr> 
			<tr>
				<td colspan="13"></td>
			</tr> 
			<tr>
				<td colspan="8"></td>
				<td colspan="5" style="margin-top: 100px;text-align: center;font-weight:bold;border-top: 1px solid black !important;" align="center">
					Hons. General Secretary
					</br>
					NUBE H.Q
				</td>
			</tr>
		</tbody>
		
	</table>
	<input type="text" name="memberoffset" id="memberoffset" class="hide" value="{{$data['data_limit']}}"></input>
</body>
<script src="{{ asset('public/excel/jquery311.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/excel/jquery.tableToExcel.js') }}" type="text/javascript"></script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.1/dist/jquery.table2excel.min.js"></script>  -->
<script src="{{ asset('public/assets/js/FileSaver.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jspdf.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jspdf_plugin_autotable.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/es6-promise.auto.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/html2canvas.min.js') }}" type="text/javascript"></script>

<script type="text/javascript" src="{{ asset('public/assets/js/tableExport.js') }}"></script>
<script>
	
	$(function() {

				$(".exportToExcel").click(function(e){
					$('.table2excel').tblToExcel({
						ignoreRows:null,   
						trimWhitespace: true,   
					});
					//exportTableToExcel('page-length-option','New Branch Advice Report');

					// var table = $('.table2excel');

					// if(table && table.length){
					// 	var preserveColors = (table.hasClass('table2excel_with_colors') ? true : false);
					// 	$(table).table2excel({
					// 		exclude: ".noExl",
					// 		name: "New Advice Members Report",
					// 		filename: "new_advice_members_report" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
					// 		fileext: ".xls",
					// 		exclude_img: true,
					// 		exclude_links: true,
					// 		exclude_inputs: true,
					// 		preserveColors: preserveColors
					// 	});
					// }
				});
				
			});

	
	function exportTableToExcel(tableID, filename = ''){
	    var downloadLink;
	    var dataType = 'application/vnd.ms-excel';
	    var tableSelect = document.getElementById(tableID);
	    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
	    
	    // Specify file name
	    filename = filename?filename+'.xls':'excel_data.xls';
	    
	    // Create download link element
	    downloadLink = document.createElement("a");
	    
	    document.body.appendChild(downloadLink);
	    
	    if(navigator.msSaveOrOpenBlob){
	    	//tableHTML = encodeURIComponent(tableHTML);
	        var blob = new Blob(['\ufeff', tableHTML], {
	            type: dataType
	        });
	        navigator.msSaveOrOpenBlob( blob, filename);
	        //colsole.log(blob);
	    }else{

	        // Create a link to the file
	       // tableHTML = encodeURIComponent(tableHTML);
	       //console.log(tableHTML);
	        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
	    
	        // Setting the file name
	        downloadLink.download = filename;
	        
	        //triggering the function
	        downloadLink.click();
	    }
	}
	
</script>

</html>