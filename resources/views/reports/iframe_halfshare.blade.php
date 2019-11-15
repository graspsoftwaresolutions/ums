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
				<td width="20%"></td>
				<td width="10%"></td>
				<td width="50%" style="text-align:center;">
					
				</td>
				<td width="20%">	
					<a href="#" class="export-button btn btn-sm exportToExcel" style="background:#227849;"><i class="material-icons">explicit</i></a>
					<a href="#" class="export-button btn btn-sm" onClick="$('#page-length-option').tableExport({type:'pdf',escape:'false',filename: 'Half share Report'});" style="background:#ff0000;"><i class="material-icons">picture_as_pdf</i></a>
					<a href="#" class="export-button btn btn-sm" style="background:#ccc;" onClick="window.print()"><i class="material-icons">print</i></a>
				</td>
			</tr>
		</table>
	</div>
	<!-- <div class="page-footer">
    I'm The Footer
  </div>-->
  @php $logo = CommonHelper::getLogo(); @endphp
	<table id="page-length-option" class="display" width="100%">
		<thead>
			<tr class="">
				
				<td colspan="1" rowspan="2" style="text-align:right">
					<img src="{{ asset('public/assets/images/logo/'.$logo) }}" height="50" />
				</td>
				<td colspan="6" style="text-align:center;padding:10px;vertical-align:top;">
					<span style="text-align:center;font-weight: bold;font-size:18px;vertical-align:top;">NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</span>
					
				</td>
				<td colspan="1" rowspan="2">	
					</br>
				</td>
			</tr>
			<tr class="">
				
				<td colspan="6" style="text-align:center;padding:10px;font-weight: bold;">
				
					<span style="margin-top:0;">HALF SHARE REPORT</span>
				</td>
				
			</tr>
			<tr class="" style="font-weight: bold;">
			
				<td colspan="1" style="border-bottom: 1px solid #988989 !important;">
				
				</td>
				<td colspan="6" align="center" style="text-align:center;vertical-align:top;border-bottom: 1px solid #988989 !important;font-weight: bold;">
					NATIONAL UNION BANK OF EMPLOYEES
					</br>
					SUMMARY REMITTANCE MONTH : {{ date('M Y',strtotime($data['month_year'])) }} 
				</td>
				<td colspan="1" style="border-bottom: 1px solid #988989 !important;">	
					
				</td>
			</tr>
			<tr class="" >
				<th style="border: 1px solid #988989 !important;">{{__('UNION BRANCH NAME')}}</th>
				<th style="border: 1px solid #988989 !important;">{{__('TOTAL')}}</th>
				<th style="border: 1px solid #988989 !important;">{{__('BF')}}</th>
				<th style="border: 1px solid #988989 !important;">{{__('INS')}}</th>
				<th style="border: 1px solid #988989 !important;">{{__('SUBS')}}</th>
				<th style="border: 1px solid #988989 !important;">{{__('1/2 SHARE')}}</th>
				<th style="border: 1px solid #988989 !important;">{{__('10%ED - FUND')}}</th>
				<th style="border: 1px solid #988989 !important;">{{__('TOTAL AMOUNT')}}</th>
			</tr>
		</thead>
		<tbody class="" width="100%">
            @php
            $total_all=0;
            $bf=0;
            $ins=0;
            $sub=0;
            $hlf=0;
            $t_per=0;
            $bl_amt=0;
            @endphp
                @if(!empty($data))						
                @foreach($data['half_share'] as $hlfshre)
                @php
				$bf_amount = $hlfshre->count*$data['bf_amount'];
				$ins_amount = $hlfshre->count*$data['ins_amount'];
                $bf += $bf_amount;
                $ins += $ins_amount;
				$payamount = $hlfshre->subamt;
				$total_ins = $hlfshre->count*$data['total_ins'];
				$sub_amt = $payamount-$total_ins;
                $sub += $sub_amt;
                $tot = $payamount;
                $totall = round($tot,2);
                $total_all += $totall;
                $hlf_sr = round($sub_amt / 2,2);
                $hlf += $hlf_sr;
                $tenper = round($hlf_sr * 10/100,2);
                $t_per +=$tenper;
                $balamtgn = round($hlf_sr - $tenper,2);
                $bl_amt += $balamtgn;
                @endphp
                    <tr>
                        <td style="border: 1px solid #988989 !important;">{{ $hlfshre->union_branch }}</td>
                        <td style="border: 1px solid #988989 !important;">{{ number_format($totall,2,".",",") }}</td>
                        <td style="border: 1px solid #988989 !important;">{{ number_format($bf_amount,2,".",",") }}</td>
                        <td style="border: 1px solid #988989 !important;">{{ number_format($ins_amount,2,".",",") }}</td>
                        <td style="border: 1px solid #988989 !important;">{{ number_format($sub_amt,2,".",",") }}</td>
                        <td style="border: 1px solid #988989 !important;">{{ number_format($hlf_sr,2,".",",") }}</td>
                        <td style="border: 1px solid #988989 !important;">{{ number_format($tenper,2,".",",") }}</td>
                        <td style="border: 1px solid #988989 !important;">{{ number_format($balamtgn,2,".",",") }}</td>
                        
                    </tr> 
                @endforeach
                @endif
                <tr style="font-weight:bold;">
                
                        <td style="border: 1px solid #988989 !important;font-weight: bold;">Total</td>
                        <td style="border: 1px solid #988989 !important;font-weight: bold;">{{ number_format($total_all,2,".",",") }}</td>
                        <td style="border: 1px solid #988989 !important;font-weight: bold;">{{ number_format($bf,2,".",",") }}</td>
                        <td style="border: 1px solid #988989 !important;font-weight: bold;">{{ number_format($ins,2,".",",") }}</td>
                        <td style="border: 1px solid #988989 !important;font-weight: bold;">{{ number_format($sub,2,".",",") }}</td>
                        <td style="border: 1px solid #988989 !important;font-weight: bold;">{{ number_format($hlf,2,".",",") }}</td>
                        <td style="border: 1px solid #988989 !important;font-weight: bold;">{{ number_format($t_per,2,".",",") }}</td>
                        <td style="border: 1px solid #988989 !important;font-weight: bold;">{{ number_format($bl_amt,2,".",",") }}</td>
                </tr>
	
			
		</tbody>
		
	</table>
	<input type="text" name="memberoffset" id="memberoffset" class="hide" value="{{$data['data_limit']}}"></input>
</body>
<script>
	var excelfilenames="Half Share Report";
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
	$(document).ready( function() { 
		$("html").css('opacity',1);
    });
	$(".exportToExcel").click(function(e){
		$("#page-length-option").table2excel();
	});

	//  $(window).scroll(function() {   
	//    var lastoffset = $("#memberoffset").val();
	//    var limit = "{{$data['data_limit']}}";
	//    if($(window).scrollTop() + $(window).height() == $(document).height()) {
	// 	    //loader.showLoader();
	// 	    var month_year = $("#month_year").val();
	// 		var searchfilters = '&month_year='+month_year;
	// 	    $("#memberoffset").val(parseInt(lastoffset)+parseInt(limit));
	// 		$.ajax({
	// 			type: "GET",
	// 			dataType: "json",
	// 			url : "{{ url(app()->getLocale().'/get-new-morehalfshare-report') }}?offset="+lastoffset+searchfilters,
	// 			success:function(res){
	// 				if(res)
	// 				{
	// 					$.each(res,function(key,entry){
	// 						var table_row = "<tr><td width='19%'>"+Total+"</td>";
	// 							table_row += "<td width='10%'>"+entry.total_all+"</td>";
	// 							table_row += "<td width='10%'>"+entry.bf+"</td>";
	// 							table_row += "<td width='10%'>"+entry.ins+"</td>";
	// 							table_row += "<td width='21%'>"+entry.sub+"</td>";
	// 							table_row += "<td width='10%'>"+entry.hlf+"</td>";
	// 							table_row += "<td width='10%'>"+entry.t_per+"</td>";
	// 							table_row += "<td width='6%'>"+entry.bl_amt+"</td>";
	// 							$('#page-length-option tbody').append(table_row);
	// 					});
	// 					loader.hideLoader();
	// 				}else{
						
	// 				}
	// 			}
	// 		});		
	//    }
	// });
</script>

</html>