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
	<div class="page-header" style="text-align: center">
		<table width="100%">
			<tr>
				<td width="20%"></td>
				<td width="10%"><img src="http://membership.graspsoftwaresolutions.com/public/assets/images/logo/logo.png" alt="Membership logo" height="50"></td>
				<td width="50%" style="text-align:center;">NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA
					<br/> 
					<h6 style="text-align:center;">HALF SHARE REPORT</h6>
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
		<thead>
			<tr style="border-bottom:none;">
				<td style="border:none;">
					<!--place holder for the fixed-position header-->
					<div class="page-header-space"></div>
				</td>
			</tr>
			<tr class="page-table-header-space" >
				<th width='15%'>{{__('Union Branch Name')}}</th>
				<th width='10%'>{{__('Total')}}</th>
				<th width='10%'>{{__('BF')}}</th>
				<th width='10%'>{{__('INS')}}</th>
				<th width='10%'>{{__('SUBS')}}</th>
				<th width='10%'>{{__('1/2 Share')}}</th>
				<th width='10%'>{{__('10%ED - Fund')}}</th>
				<th width='10%'>{{__('Total Amount')}}</th>
			</tr>
		</thead>
		<tbody class="tbody-area" width="100%">
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
                $bf += $hlfshre->bfamount;
                $ins += $hlfshre->insamt;
                $sub += round($hlfshre->subamt,2);
                $tot = $hlfshre->bfamount + $hlfshre->insamt + round($hlfshre->subamt,2);
                $totall = round($tot,2);
                $total_all += $totall;
                $hlf_sr = $totall / 2;
                $hlf += $hlf_sr;
                $tenper = round($hlf_sr * 10/100,2);
                $t_per +=$tenper;
                $balamtgn = round($hlf_sr - $tenper,2);
                $bl_amt += $balamtgn;
                @endphp
                    <tr>
                        <td style="width:170px !important; border:1px ;">{{ $hlfshre->union_branch }}</td>
                        <td style="width:100px !important; border:1px ;">{{ $totall }}</td>
                        <td style="width:100px !important; border:1px ;">{{ $hlfshre->bfamount }}</td>
                        <td style="width:100px !important; border:1px ;">{{ $hlfshre->insamt }}</td>
                        <td style="width:100px !important; border:1px ;">{{ round($hlfshre->subamt,2) }}</td>
                        <td style="width:100px !important; border:1px ;">{{ $hlf_sr }}</td>
                        <td style="width:100px !important; border:1px ;">{{ $tenper }}</td>
                        <td style="width:100px !important; border:1px ;">{{ $balamtgn }}</td>
                        
                    </tr> 
                @endforeach
                @endif
                <tr style="font-weight:bold;">
                
                        <td style="width:200px !important; border:1px  ;">Total</td>
                        <td style="width:130px !important; border:1px ;">{{ $total_all }}</td>
                        <td style="width:130px !important; border:1px ;">{{ $bf }}</td>
                        <td style="width:140px !important; border:1px  ;">{{ $ins }}</td>
                        <td style="width:130px !important; border:1px  ;">{{ $sub }}</td>
                        <td style="width:130px !important; border:1px  ;">{{ $hlf }}</td>
                        <td style="width:150px !important; border:1px  ;">{{ $t_per }}</td>
                        <td style="width:140px !important; border:1px;">{{ $bl_amt }}</td>
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