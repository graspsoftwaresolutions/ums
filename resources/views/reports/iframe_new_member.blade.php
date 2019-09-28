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
			@page {size: landscape}
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
			  font-size: 14px;
			}
			.tbody-area{
				top:172px;
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
	.styleiframetble tr{
		border-bottom:1px solid black !important;
		
	}	
		
	</style>
	<script type="text/javascript">
		function updateIframe(){
		    	var myFrame = $("#myframe").contents().find('body');
		        var textareaValue = $("textarea").val();
		    	myFrame.html(textareaValue);
		    }
	</script>
</head>

<body>
	<div class="page-header" style="text-align: center">
		<table width="100%">
			<tr>
			@php $logo = CommonHelper::getLogo(); @endphp
				<td width="20%"></td>
				<td width="10%"><img src="{{ asset('public/assets/images/logo/'.$logo) }}" alt="Membership logo" height="50"></td>
				<td width="50%" style="text-align:center;">NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA
					<br/> 
					<h6 style="text-align:center;">New Members Report</h6>
				</td>
				<td width="20%">	
					<a href="#" class="export-button btn btn-sm" onClick="$('#page-length-option').tableExport({type:'excel',escape:'false',filename: 'New Members Report'});" style="background:#227849;"><i class="material-icons">explicit</i></a>
					<a href="#" class="export-button btn btn-sm" onClick="$('#page-length-option').tableExport({type:'pdf',escape:'false',filename: 'New Members Report',bootstrap: true});" style="background:#ff0000;"><i class="material-icons">picture_as_pdf</i></a>
					<a href="#" class="export-button btn btn-sm" style="background:#ccc;" onClick="window.print()"><i class="material-icons">print</i></a>
				</td>
			</tr>
		</table>
	</div>
	<!-- <div class="page-footer">
    I'm The Footer
  </div>-->
	<table id="page-length-option" class="display styleiframetble" width="100%">
		<thead>
			<tr style="border-bottom:none;">
				<td style="border:none;">
					<!--place holder for the fixed-position header-->
					<div class="page-header-space"></div>
				</td>
			</tr>
			<tr class="page-table-header-space">
				<th style="width:50px  !important ;border : 1px solid #343d9f;" align="center">S.NO</th>
				<th style="width:100px  !important ;border : 1px solid #343d9f;"  align="center">M/NO</th>
				<th style="width:351px  !important ;border : 1px solid #343d9f;" align="center">MEMBER NAME</th>
				<th style="width:180px  !important ;border : 1px solid #343d9f;" align="center">NRIC</th>
				<th  style="width:100px  !important ;border : 1px solid #343d9f;" align="center">GENDER</th>
				<th  style="width:120px  !important ;border : 1px solid #343d9f;" align="center">BANK</th>
				<th  style="width:200px  !important ;border : 1px solid #343d9f;" align="center">BANK </br>BRANCH</th>
				<th  style="width:100px  !important ;border : 1px solid #343d9f;" align="center">MEMBER </br> TYPE</th>
				<th style="width:140px  !important ;border : 1px solid #343d9f;" align="center">DATE OF </br> JOINING</th>
				<th  style="width:80px  !important ;border : 1px solid #343d9f;" align="center">LEVY</th>
				<th style="width:80px  !important ;border : 1px solid #343d9f;" align="center">TDF</th>
				<th  style="width:100px  !important ;border : 1px solid #343d9f;" align="center">LAST PAID </br> DATE</th>
			</tr>
		</thead>
		<tbody class="tbody-area" width="100% ">
			@php
				$totalmembers = 0;
				$sno = 1;
			@endphp
			@foreach($data['member_view'] as $member)
				<tr>
					<td style="width:50px !important ; border : 1px solid white;">{{ $sno }}</td>
					<td style="width:100px  !important ;border : 1px solid white;">{{ $member->member_number }}</td>
					<td style="width:351px !important ; border : 1px solid white;">{{ $member->name }}</td>
					<td style="width:160px  !important ;border : 1px solid white;">{{ $member->ic }}</td>
					<td style="width:100px  !important ;border : 1px solid white;">{{ $member->gender }}</td>
					<td style="width:100px  !important ;border : 1px solid white;" >{{ $member->companycode }}</td>
					<td  style="width:200px  !important ;border : 1px solid white;">{{ $member->branch_name }}</td>
					<td style="width:100px  !important ;border : 1px solid white;">{{ isset($member) ? $member->designation_name : ""}}</td>
					<td style="width:130px  !important ;border : 1px solid white;">{{ date('d/M/Y',strtotime($member->doj))}}</td>
					
					<td style="width:80px  !important ;border : 1px solid white;">{{  $member->levy }}</td>
					<td style="width:80px  !important ;border : 1px solid white;">{{  $member->tdf }}</td>
				
					<td style="width:100px  !important ;border : 1px solid white;">{{  $member->last_paid_date!="" ? date('d/M/Y',strtotime($member->last_paid_date)) : '' }}</td>
					
				</tr> 
				@php
					$sno++;
				@endphp
			@endforeach
			<tr width="100%">
				<td colspan="1" style="width:50px !important ; border : 1px solid white;font-weight:bold;">Total</td>
				<td colspan="1" style="width:100px !important ; border : 1px solid white;font-weight:bold;">Member's</td>
				<td colspan="1" style="width:351px !important ; border : 1px solid white;font-weight:bold;">Count : {{ $sno-1 }}</td>
			</tr> 
		</tbody>
		
	</table>
	<input type="text" name="memberoffset" id="memberoffset" class="hide" value="{{$data['data_limit']}}"></input>
</body>
<script src="{{ asset('public/assets/js/jquery-3.2.1.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/FileSaver.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jspdf.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jspdf_plugin_autotable.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/es6-promise.auto.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/html2canvas.min.js') }}" type="text/javascript"></script>
<!--<![endif]-->
<script type="text/javascript" src="{{ asset('public/assets/js/tableExport.js') }}"></script>
<!--script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.4.1/jspdf.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/2.3.5/jspdf.plugin.autotable.min.js"></script>
<script type="text/javascript" src="https://www.jqueryscript.net/demo/export-table-json-csv-txt-pdf/src/tableHTMLExport.js"></script-->
<script>
	
	//  $(window).scroll(function() {  
	//    var lastoffset = $("#memberoffset").val();
	//    var limit = "{{$data['data_limit']}}";
	//    if($(window).scrollTop() + $(window).height() == $(document).height()) {
	// 	    //loader.showLoader();
	// 	    var from_date = "{{$data['from_date']}}";
	// 		var to_date = "{{$data['to_date']}}";
	// 		var company_id = "{{$data['company_id']}}";
	// 		var branch_id = "{{$data['branch_id']}}";
	// 		var member_auto_id = "{{$data['member_auto_id']}}";
	// 		var join_type = "{{$data['join_type']}}";
	// 		var searchfilters = '&from_date='+from_date+'&to_date='+to_date+'&company_id='+company_id+'&branch_id='+branch_id+'&member_auto_id='+member_auto_id+'&join_type='+join_type;
	// 	    $("#memberoffset").val(parseInt(lastoffset)+parseInt(limit));
	// 		$.ajax({
	// 			type: "GET",
	// 			dataType: "json",
	// 			url : "{{ URL::to('/en/get-new-moremembers-report') }}?offset="+lastoffset+searchfilters,
	// 			success:function(res){
	// 				if(res)
	// 				{
	// 					$.each(res,function(key,entry){
	// 						var table_row = "<tr><td width='190px'>"+entry.name+"</td>";
	// 							table_row += "<td width='10%'>"+entry.member_number+"</td>";
	// 							table_row += "<td width='10%'>"+entry.new_ic+"</td>";
	// 							table_row += "<td width='10%'>"+entry.companycode+"</td>";
	// 							table_row += "<td width='21%'>"+entry.branch_name+"</td>";
	// 							table_row += "<td width='10%'>"+entry.doj+"</td>";
	// 							table_row += "<td width='10%'>"+entry.entryfee+"</td>";
	// 							table_row += "<td width='6%'>"+entry.insfee+"</td>";
	// 							table_row += "<td width='6%'>"+entry.subs+"</td></tr>";
	// 							$('#page-length-option tbody').append(table_row);
	// 					});
	// 					//loader.hideLoader();
	// 				}else{
						
	// 				}
	// 			}
	// 		});
		    
				
	//    }
	// });
</script>

</html>