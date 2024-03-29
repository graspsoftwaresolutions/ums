
<!DOCTYPE html>
<html>

<head>
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
		
		.page {
		  page-break-after: always;
		}
		
		@page {
		  //margin: 3mm
		}
		
		@media print {
			@page {
				size: landscape; 
				//margin: 3mm;
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
			  //border: 1px solid #ddd !important;
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
			@php 
				$searchfilters = '&month_year='.$data['month_year'].'&company_id='.$data['company_id'];
			@endphp
			<tr>
				
				<td width="20%"></td>
				<td width="10%"></td>
				<td width="50%" style="text-align:center;">
					
				</td>
				<td width="20%">	
					<a href="#" class="export-button btn btn-sm exportToExcel" style="background:#227849;"><i class="material-icons">explicit</i></a>
					<a href="{{ url(app()->getLocale().'/export-pdf-subscription-bank?offset=0'.$searchfilters) }}" class="export-button btn btn-sm" style="background:#ff0000;"><i class="material-icons">picture_as_pdf</i></a>
					<a href="#" class="export-button btn btn-sm" style="background:#ccc;" onClick="window.print()"><i class="material-icons">print</i></a>
				</td>
			</tr>
		</table>
	</div>
	<!-- <div class="page-footer">
    I'm The Footer
  </div>-->
	@include('reports.common_subscription_bank')
	<input type="text" name="memberoffset" id="memberoffset" class="hide" value="{{$data['data_limit']}}"></input>
</body>
<script>
	var excelfilenames="Subscription Bank Report";
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
	//  $(window).scroll(function() {   
	//    var lastoffset = $("#memberoffset").val();
	//    var limit = "{{$data['data_limit']}}";
	//    if($(window).scrollTop() + $(window).height() == $(document).height()) {
	// 	    //loader.showLoader();
	// 		var month_year = "{{$data['month_year']}}";
	// 		var company_id = "{{$data['company_id']}}";
	// 		var searchfilters = '&month_year='+month_year+'&company_id='+company_id;
	// 	    $("#memberoffset").val(parseInt(lastoffset)+parseInt(limit));
	// 		$.ajax({
	// 			type: "GET",
	// 			dataType: "json",
	// 			url : "{{ url(app()->getLocale().'/get-subscription-more-report') }}?offset="+lastoffset+searchfilters,
	// 			success:function(res){
	// 				if(result)
	// 				{
	// 					res = result.company_view;
	// 					$.each(res,function(key,entry){
	// 						var new_member_sub_link =base_url+"/{{app()->getLocale()}}/sub-company-members/"+entry.enc_id;
	// 						var table_row = "<tr class='monthly-sub-status' data-href='"+new_member_sub_link+"'><td width='30%'>"+entry.company_name+"</td>";
	// 							table_row += "<td width='20%'>"+entry.total_members+"</td>";
	// 							table_row += "<td width='10%'>"+entry.total_amount+"</td>";
	// 							table_row += "<td width='15%'>"+entry.active_amt+"</td>";
	// 							table_row += "<td width='15%'>"+entry.default_amt+"</td>";
	// 							table_row += "<td width='15%'>"+entry.struckoff_amt+"</td>";
	// 							table_row += "<td width='15%'>"+entry.resign_amt+"</td>";
	// 							table_row += "<td width='15%'>"+entry.sundry_amt+"</td></tr>";
	// 							$('#scroll-vert-hor tbody').append(table_row);
	// 					});
	// 					if(!res){
	// 							var table_row = "<tr><td colspan='6'>No data found</td></tr>";
	// 							$('#scroll-vert-hor tbody').append(table_row);
	// 					}
	// 					loader.hideLoader();
	// 				}else{
						
	// 				}
	// 			}
	// 		});	
	//    }
	// });
</script>

</html>