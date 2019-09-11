<!DOCTYPE html>
<html>
<head>
	<script src="{{ asset('public/assets/js/jquery-1.12.4.min.js') }}" type="text/javascript"></script>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/vendors.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/flag-icon.min.css') }}">
	<!-- <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/vertical-modern-menu.css') }}"> -->
	 <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/materialize.css') }}"> 
	<title>{{$data['month_year']}}</title>
	<style>
		/* Styles go here */
		
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
		  margin: 20mm
		}
		
		@media print {
		    thead {display: table-header-group;} 
		    tfoot {display: table-footer-group;}
		   
		    button {display: none;}
		   
		    body {margin: 0;}
			.export-button{
				display:none !important;
			}
			.page-header,.page-table-header-space {
			  background: #fff; /* for demo */
			  color:#000;
			}
		}
		@media not print {
			.page-table-header-space {
			  width: 100%;
			  position: fixed;
			  top:101px;
			  margin-bottom:20px;
			  background: #343d9f; /* for demo */
			  z-index:999;
			  color:#fff;
			}
			.tbody-area{
				top: 162px;
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
	
</head>

<body>
	<div class="page-header" style="text-align: center">
		<table width="100%">
			<tr>
			@php $logo = CommonHelper::getLogo(); @endphp
				<td width="20%"></td>
				<td width="10%"><img src="{{ asset('public/assets/images/logo/'.$logo) }}" alt="Membership logo" height="50"></td>
				<td width="50%" style="text-align:center;">NATIONAL UNION BANK OF EMPLOYEES, MALAYSIA
					<br/> 
					<h6 style="text-align:center;">NUBE RETIREMENT INSURANCE SCHEME</h6>
				</td>
				<td width="20%">	
					<a href="#" class="export-button btn btn-sm" onClick="$('#page-length-option').tableExport({type:'excel',escape:'false'});" style="background:#227849;"><i class="material-icons">explicit</i></a>
					<a href="#" class="export-button btn btn-sm" onClick="$('#page-length-option').tableExport({type:'pdf',escape:'false'});" style="background:#ff0000;"><i class="material-icons">picture_as_pdf</i></a>
					<a href="#" class="export-button btn btn-sm" style="background:#ccc;" onClick="window.print()"><i class="material-icons">print</i></a>
				</td>
			</tr>
		</table>
	</div>
	<!-- <div class="page-footer">
    I'm The Footer
  </div>-->
	<table id="page-length-option" class="display" width="100%">
		@php
			$totalamt = 0;
			$sno = 1;
		@endphp
		<thead>
			<tr style="border-bottom:none;">
				<td style="border:none;">
					<!--place holder for the fixed-position header-->
					<div class="page-header-space"></div>
				</td>
			</tr>
			<tr class="page-table-header-space">
				<th style="width:100px  !important ;border : 1px solid #343d9f;">{{__('Bank')}}</th>
				<th style="width:200px  !important ;border : 1px solid #343d9f;">{{__('Branch')}}</th>
				<th style="width:300px  !important ;border : 1px solid #343d9f;">{{__('Name')}}</th>
				<th style="width:200px  !important ;border : 1px solid #343d9f;">{{__('Number')}}</th>
				<th style="width:150px  !important ;border : 1px solid #343d9f;">{{__('NRIC')}}</th>
				<th style="width:50px  !important ;border : 1px solid #343d9f;">{{__('Insurance Amount(RM)')}}</th>
			</tr>
		</thead>
		<tbody class="tbody-area" width="100%">
			@foreach($data['member_view'] as $member)
				<tr>
					<td style="width:100px  !important ;border : 1px solid white;">{{$member->companycode}}</td>
					<td style="width:200px  !important ;border : 1px solid white;">{{$member->branch_name}}</td>
					<td style="width:300px  !important ;border : 1px solid white;">{{$member->name}}</td>
					<td style="width:200px  !important ;border : 1px solid white;">{{$member->member_number}}</td>
					<td style="width:150px  !important ;border : 1px solid white;">{{$member->new_ic}}</td>
					<td style="width:50px  !important ;border : 1px solid white;">{{ number_format($member->total,2,".",",") }}</td>
				</tr> 
				@php
					$totalamt += $member->total;
					$sno++;
				@endphp
				
			@endforeach
				<tr>
					<td style="width:1100px  !important ;border : 1px solid white;" colspan="5" style="border : 1px solid white;"> Total </td>
					<td style="width:50px  !important ;border : 1px solid white;">{{ number_format($totalamt,2,".",",") }}</td>
				</tr> 
		</tbody>
		
	</table>
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
	$(document).ready( function() { 
		$("html").css('opacity',1);
    }); 
</script>

</html>