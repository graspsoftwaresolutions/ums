<!DOCTYPE html>
<html>

<head>
	<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/vendors/vendors.min.css">
	<link rel="stylesheet" type="text/css" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/vendors/flag-icon/css/flag-icon.min.css">
	<link rel="stylesheet" type="text/css" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/css/themes/vertical-modern-menu-template/style.css">
	<link rel="stylesheet" type="text/css" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/css/themes/vertical-modern-menu-template/materialize.css">
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
		  background: #0b97d3; /* for demo */
		  color:#fff;
		}
		
		.page-header {
		  position: fixed;
		  top: 0mm;
		  width: 100%;
		  background: #0b97d3; /* for demo */
		  color:#fff;
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
			  background: #0000ff; /* for demo */
			  z-index:999;
			  color:#fff;
			}
			.tbody-area{
				top:142px;
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
		function updateIframe(){
		    	var myFrame = $("#myframe").contents().find('body');
		        var textareaValue = $("textarea").val();
		    	myFrame.html(textareaValue);
		    }
	</script>
</head>

<body>
	<div class="page-header" style="text-align: center">New Members Report
		<br/> 
		<a href="#" class="export-button btn btn-sm" onClick="$('#page-length-option').tableExport({type:'excel',escape:'false'});" style="background:#227849;"><i class="material-icons">explicit</i></a>
		<a href="#" class="export-button btn btn-sm" onClick="$('#page-length-option').tableExport({type:'pdf',escape:'false'});" style="background:#ff0000;"><i class="material-icons">picture_as_pdf</i></a>
		<a href="#" class="export-button btn btn-sm" style="background:#ccc;" onClick="window.print()"><i class="material-icons">print</i></a>
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
			<tr class="page-table-header-space" >
				<th width="19%" align="center">Name</th>
				<th width="10%" align="center">Number</th>
				<th width="10%" align="center">NRIC</th>
				<th width="10%" align="center">Bank</th>
				<th width="21%" align="center">Branch</th>
				<th width="10%" align="center">DOJ</th>
				<th width="10%" align="center">ENT</th>
				<th width="6%" align="center">INS</th>
				<th width="6%" align="center">SUBS</th>
			</tr>
		</thead>
		<tbody class="tbody-area" width="100%">
			@foreach($data['member_view'] as $member)
				<tr>
					<td width="19%">{{ $member->name }}</td>
					<td width="10%">{{ $member->member_number }}</td>
					<td width="10%">{{ $member->new_ic }}</td>
					<td width="10%">{{ $member->companycode }}</td>
					<td width="21%">{{ $member->branch_name }}</td>
					<td width="10%">{{ $member->doj }}</td>
					<td width="10%">{{ $member->entryfee }}</td>
					<td width="6%">{{ $member->insfee }}</td>
					<td width="6%">{{ $member->subs }}</td>
					
				</tr> 
			@endforeach
			
		</tbody>
		
	</table>
	<input type="text" name="memberoffset" id="memberoffset" class="hide" value="{{$data['data_limit']}}"></input>
</body>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="https://d1v5pkn4fpocaq.cloudfront.net/js/libs/js-xlsx/xlsx.core.min.js"></script>
<script type="text/javascript" src="https://d1v5pkn4fpocaq.cloudfront.net/js/libs/FileSaver/FileSaver.min.js"></script>
<script type="text/javascript" src="https://d1v5pkn4fpocaq.cloudfront.net/js/libs/jsPDF/jspdf.min.js"></script>
<script type="text/javascript" src="https://d1v5pkn4fpocaq.cloudfront.net/js/libs/jsPDF-AutoTable/jspdf.plugin.autotable.js"></script>
<script type="text/javascript" src="https://d1v5pkn4fpocaq.cloudfront.net/js/libs/es6-promise/es6-promise.auto.min.js"></script>
<script type="text/javascript" src="https://d1v5pkn4fpocaq.cloudfront.net/js/libs/html2canvas/html2canvas.min.js"></script>
<!--<![endif]-->
<script type="text/javascript" src="https://d1v5pkn4fpocaq.cloudfront.net/js/tableExport.js"></script>
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
	 $(window).scroll(function() {   
	   var lastoffset = $("#memberoffset").val();
	   var limit = "{{$data['data_limit']}}";
	   if($(window).scrollTop() + $(window).height() == $(document).height()) {
		    //loader.showLoader();
		    var from_date = "{{$data['from_date']}}";
			var to_date = "{{$data['to_date']}}";
			var company_id = "{{$data['company_id']}}";
			var branch_id = "{{$data['branch_id']}}";
			var member_auto_id = "{{$data['member_auto_id']}}";
			var join_type = "{{$data['join_type']}}";
			var searchfilters = '&from_date='+from_date+'&to_date='+to_date+'&company_id='+company_id+'&branch_id='+branch_id+'&member_auto_id='+member_auto_id+'&join_type='+join_type;
		    $("#memberoffset").val(parseInt(lastoffset)+parseInt(limit));
			$.ajax({
				type: "GET",
				dataType: "json",
				url : "{{ URL::to('/en/get-new-moremembers-report') }}?offset="+lastoffset+searchfilters,
				success:function(res){
					if(res)
					{
						$.each(res,function(key,entry){
							var table_row = "<tr><td width='19%'>"+entry.name+"</td>";
								table_row += "<td width='10%'>"+entry.member_number+"</td>";
								table_row += "<td width='10%'>"+entry.new_ic+"</td>";
								table_row += "<td width='10%'>"+entry.companycode+"</td>";
								table_row += "<td width='21%'>"+entry.branch_name+"</td>";
								table_row += "<td width='10%'>"+entry.doj+"</td>";
								table_row += "<td width='10%'>"+entry.entryfee+"</td>";
								table_row += "<td width='6%'>"+entry.insfee+"</td>";
								table_row += "<td width='6%'>"+entry.subs+"</td></tr>";
								$('#page-length-option tbody').append(table_row);
						});
						//loader.hideLoader();
					}else{
						
					}
				}
			});
		    
				
	   }
	});
</script>

</html>