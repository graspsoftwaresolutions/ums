
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
			.badge{
				color: #000 !important;
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
	<script type="text/javascript">
	
	</script>
</head>

<body>
	<div class="page-header" style="text-align: center">
		<table width="100%">
			<tr>
			@php $logo = CommonHelper::getLogo(); @endphp
				<td width="20%"></td>
				<td width="10%"><img src="{{ asset('public/assets/images/logo/'.$logo) }}" style="vertical-align: middle;float: right;" alt="Membership logo" height="50"></td>
				<td width="50%" style="text-align:center;">
					<span class="report-address" style="font-weight: bold;font-size:14px;">NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</span>
					<br/> 
					<h6 style="text-align:center;">DUE REPORT</h6>
				</td>
				<td width="20%">	
					<a href="#" class="export-button btn btn-sm" onClick="$('#page-length-option').tableExport({type:'excel',escape:'false',filename: 'Due Report'});" style="background:#227849;"><i class="material-icons">explicit</i></a>
					<a href="#" class="export-button btn btn-sm" onClick="$('#page-length-option').tableExport({type:'pdf',escape:'false',filename: 'Due Report'});" style="background:#ff0000;"><i class="material-icons">picture_as_pdf</i></a>
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
			<tr style="border-bottom:none;">
				<td style="border:none;">
					<!--place holder for the fixed-position header-->
					<div class="page-header-space"></div>
				</td>
			</tr>
			<tr class="page-table-header-space">
            <th style="width:50px  !important ;border : 1px solid #343d9f;">{{__('SNO')}}</th>
				<th style="width:100px  !important ;border : 1px solid #343d9f;">{{__('# MEMBER')}}</th>
				<th style="width:170px  !important ;border : 1px solid #343d9f;">{{__('MEMBER NAME')}}</th>
				<th style="width:120px  !important ;border : 1px solid #343d9f;">{{__('NRIC')}}</th>
				<th style="width:130px  !important ;border : 1px solid #343d9f;">{{__('BANK')}}</th>
				<th style="width:130px  !important ;border : 1px solid #343d9f;">{{__('BANK BRANCH')}}</th>
				<th style="width:120px  !important ;border : 1px solid #343d9f;">{{__('UNION BRANCH')}}</th>
				<th style="width:120px  !important ;border : 1px solid #343d9f;">{{__('DOJ')}}</th>
				<th style="width:100px  !important ;border : 1px solid #343d9f;">{{__('DUE MONTH')}}</th>
				<th style="width:100px  !important ;border : 1px solid #343d9f;">{{__('LAST PAID DATE')}}</th>
			</tr>
		</thead>
		@php 
			$get_roles = Auth::user()->roles;
			$user_role = $get_roles[0]->slug;
			$user_id = Auth::user()->id;
			$slno = 1;
		@endphp 
		<tbody class="tbody-area" width="100%">
            @foreach($data['member_view'] as $member)
						
						<tr class="monthly-sub-status">
							<td style="width:50px  !important ;border : 1px solid white;">{{ $slno }}</td>
							<td style="width:100px  !important ;border : 1px solid white;">{{ $member->member_number }}</td>
							<td style="width:170px  !important ;border : 1px solid white;">{{ $member->name }}</td>
							<td style="width:120px  !important ;border : 1px solid white;">{{ $member->ic }}</td>
							<td style="width:130px  !important ;border : 1px solid white;">{{ $member->company_name }}</td>
							<td style="width:130px  !important ;border : 1px solid white;">{{ $member->branch_name }}</td>
							<td style="width:120px  !important ;border : 1px solid white;">{{ $member->unionbranch }}</td>
							<td style="width:120px  !important ;border : 1px solid white;">{{ $member->doj }}</td>
							<td style="width:100px  !important ;border : 1px solid white;">{{ $member->TOTALMONTHSDUE }}</td>
							<td style="width:100px  !important ;border : 1px solid white;">{{ $member->LASTPAYMENTDATE }}</td>
						</tr> 
					@php
						$slno++;
					@endphp
				@endforeach

		</tbody>

	</table>
	</br>
		</br>	
</body>
<script src="{{ asset('public/assets/js/jquery-3.2.1.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/FileSaver.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jspdf.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jspdf_plugin_autotable.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/es6-promise.auto.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/html2canvas.min.js') }}" type="text/javascript"></script>
<!--<![endif]-->
<script type="text/javascript" src="{{ asset('public/assets/js/tableExport.js') }}"></script>
<script>
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