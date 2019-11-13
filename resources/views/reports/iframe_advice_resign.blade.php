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
				top:140px;
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
					<a href="#" class="export-button btn btn-sm" onClick="$('#page-length-option').tableExport({type:'pdf',escape:'false',filename: 'Resigned Members Report'});" style="background:#ff0000;"><i class="material-icons">picture_as_pdf</i></a>
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
				
				<td colspan="2" rowspan="2">
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
				
					<span style="margin-top:0;">UNION BRANCH'S ADVICE LIST</span>
				</td>
				
			</tr>
			<tr class="" style="font-weight: bold;">
			
				<td colspan="2" style="border-bottom: 1px solid #988989 !important;">
					To Branch Hons. Secretary
					@if($data['unionbranch_id']!='')
						<br>
						Branch Name : {{ $data['unionbranch_name'] }}
					@endif
				</td>
				<td colspan="5" align="center" style="text-align:center;vertical-align:top;border-bottom: 1px solid #988989 !important;">
					{{ date('d M Y',strtotime($data['from_date'])) }} - {{ date('d M Y',strtotime($data['to_date'])) }}
				</td>
				<td colspan="3" style="border-bottom: 1px solid #988989 !important;">	
					
					@if($data['unionbranch_id']!='')
						<br>
						Branch Code : {{ $data['unionbranch_id'] }}
					@endif
				</td>
			</tr>
			<tr class="" >
				<th style="border : 1px solid #988989;" align="center">SNO</th>
                <th style="border : 1px solid #988989;">{{__('NAME')}}</th>
                <th style="border : 1px solid #988989;">{{__('I.C.NO')}}</th>
                <th style="border : 1px solid #988989;">{{__('BANK')}}</th>
                <th style="border : 1px solid #988989;">{{__('BANK BRANCH')}}</th>
                <th style="border : 1px solid #988989;">{{__('M/NO')}}</th>
                <th style="border : 1px solid #988989;">{{__('DOJ')}}</th>
                <th style="border : 1px solid #988989;">{{__('CLERK')}}</th>
                <th style="border : 1px solid #988989;">{{__('ENT FEE')}}</th>
                <th style="border : 1px solid #988989;">{{__('SUBS')}}</th>
                <th style="border : 1px solid #988989;">{{__('B/F')}}</th>
                <th style="border : 1px solid #988989;">{{__('HQ')}}</th>
                <th style="border : 1px solid #988989;">{{__('RMK')}}</th>
			</tr>
		</thead>
		<tbody class="" width="100%">
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
					<td style="border : 1px solid #988989;">{{ $sno }}</td>
                    <td style="border : 1px solid #988989;">{{ $member->name }}</td>
                    <td style="border : 1px solid #988989;">{{ $member->ic }}</td>
                    <td style="border : 1px solid #988989;">{{ $member->companycode }}</td>
                    <td style="border : 1px solid #988989;">{{ $member->branch_name }}</td>
                    <td style="border : 1px solid #988989;">{{ $member->member_number }}</td>
                   
                    <td style="border : 1px solid #988989;">{{ date("d/M/Y",strtotime($member->doj)) }}</td>
                    <td style="border : 1px solid #988989;">{{ $member->designation_name }}</td>
                    
                    <td style="border : 1px solid #988989;">{{ $data['ent_amount'] }}</td>
                    <td style="border : 1px solid #988989;">{{ $member->subs }}</td>
                    <td style="border : 1px solid #988989;">{{ $data['bf_amount'] }}</td>
                    <td style="border : 1px solid #988989;">{{ $data['hq_amount'] }}</td>
                    <td style="border : 1px solid #988989;">{{ '' }}</td>
				</tr> 
				@php
					$sno++;
					$total_ent_amount += $data['ent_amount'];
					$total_bf_amount += $data['bf_amount'];
					$total_hq_amount += $data['hq_amount'];
					$total_subs += $member->subs;
				@endphp
			@endforeach
			<tr>
				<td colspan="2" style="border : 1px solid #988989;">Total Member's Count </td>
				<td style="border : 1px solid #988989;"> {{ $sno-1 }}</td>
				<td colspan="5" style="border : 1px solid #988989;"> </td>
				<td style="border : 1px solid #988989;">{{ $total_ent_amount }}</td>
				<td style="border : 1px solid #988989;">{{ $total_subs }}</td>
				<td style="border : 1px solid #988989;">{{ $total_bf_amount }}</td>
				<td style="border : 1px solid #988989;">{{ $total_hq_amount }}</td>
				<td style="border : 1px solid #988989;"></td>
			</tr> 
			@php
				$total_paid = round($total_ent_amount + $total_subs + $total_bf_amount + $total_hq_amount,2);
			@endphp
			<tr>
				<td colspan="2" style="border : 1px solid #988989;">Total Amount Collected </td>
				<td colspan="1" style="border : 1px solid #988989;">{{ $total_paid }}</td>
				<td colspan="10" style="border : 1px solid #988989;"></td>
			</tr> 
		</tbody>
		
	</table>
	<input type="text" name="memberoffset" id="memberoffset" class="hide" value="{{$data['data_limit']}}"></input>
</body>
<script>
	var excelfilenames="Resign Members report";
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

</script>

</html>