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
			}
			.tbody-area{
				top: 172px;
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
					@php $status_name =  CommonHelper::get_member_status_name($data['status_id']);
						//dd($data['member_view'][0]);	
					 @endphp
					<h6 style="text-align:center;">UNION BRANCH REPORT</h6>
				</td>
				<td width="20%">	
					<a href="#" class="export-button btn btn-sm" onClick="$('#page-length-option').tableExport({type:'excel',escape:'false',filename: 'Members Report'});" style="background:#227849;"><i class="material-icons">explicit</i></a>
					<a href="#" class="export-button btn btn-sm" onClick="$('#page-length-option').tableExport({type:'pdf',escape:'false',filename: 'Members Report'});" style="background:#ff0000;"><i class="material-icons">picture_as_pdf</i></a>
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
				<th style="width:51px  !important ;border : 1px solid #343d9f;" align="center">SNO</th>
				 <th style="width:130px; border:1px  ;">{{__('M/NO')}}</th>
                <th style="width:230px; border:1px  ;">{{__('MEMBER NAME')}}</th>
               
                <th style="width:60px; border:1px  ;">{{__('GENDER')}}</th>
                <th style="width:130px; border:1px  ;">{{__('BANK')}}</th>
                <th style="width:100px; border:1px  ;">{{__('UNION BRANCH')}}</th>
                <th style="width:230px; border:1px  ;">{{__('BANK BRANCH')}}</th>
                <th style="width:60px; border:1px  ;">{{__('MEMBER TYPE')}}</th>
                
                <th style="width:100px; border:1px  ;">{{__('DOJ')}}</th>
       
                 <th style="width:100px; border:1px  ;">{{__('LAST PAID DATE')}}</th>

			</tr>
		</thead>
		<tbody class="tbody-area" width="100%">
			@php
				$totalmembers = 0;
				$sno = 1;
			@endphp
            @foreach($data['member_view'] as $member)
                <tr>
					<td style="width:51px !important ; border : 1px solid white;">{{ $sno }}</td>
					<td style="width:130px; border:1px  ;">{{ $member->member_number }}</td>
                    <td style="width:200px; border:1px  ;">{{ $member->name }}</td>
                   
                    <td style="width:60px; border:1px  ;">{{ $member->gender }}</td>
                    <td style="width:130px; border:1px  ;"> {{ $member->companycode }}</td>
                    <td style="width:100px; border:1px  ;">{{ $member->union_branch }}</td>
                    <td style="width:200px; border:1px  ;">{{ $member->branch_name }}</td>
                    <td style="width:60px; border:1px  ;">{{ $member->designation_name }}</td>
                    <td style="width:100px; border:1px  ;">{{ date('d/M/Y',strtotime($member->doj)) }}</td>
                  
                    <td style="width:100px; border:1px  ;">{{ $member->last_paid_date }}</td>	
                </tr> 
				@php
					$sno++;
				@endphp
            @endforeach
			<tr>
				<td colspan="10" style="width:651px !important ; border : 1px solid white;font-weight:bold;">Total Member's Count : {{ $sno-1 }}</td>
				
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
	$(document).ready( function() { 
		$("html").css('opacity',1);
    }); 
    // $(window).scroll(function() {   
	//    var lastoffset = $("#memberoffset").val();
	//    var limit = "{{$data['data_limit']}}";
	//    if($(window).scrollTop() + $(window).height() == $(document).height()) {
	// 	    //loader.showLoader();
	// 	    var month_year = "{{$data['month_year']}}";
	// 		var company_id = "{{$data['company_id']}}";
	// 		var branch_id = "{{$data['branch_id']}}";
	// 		var member_auto_id = "{{$data['member_auto_id']}}";
    //         var status_id = $("#member_status").val();

	// 		var searchfilters = '&month_year='+month_year+'&company_id='+company_id+'&branch_id='+branch_id+'&member_auto_id='+member_auto_id+'&status_id='+status_id;
	// 	    $("#memberoffset").val(parseInt(lastoffset)+parseInt(limit));
	// 		$.ajax({
	// 			type: "GET",
	// 			dataType: "json",
	// 			url : "{{ url(app()->getLocale().'/get-new-moremembers-report') }}?offset="+lastoffset+searchfilters,
	// 			success:function(res){
	// 				if(res)
	// 				{
    //                     $.each(res,function(key,entry){
	// 						var table_row = "<tr><td>"+entry.name+"</td>";
	// 							table_row += "<td>"+entry.member_number+"</td>";
	// 							table_row += "<td>"+entry.new_ic+"</td>";
	// 							table_row += "<td>"+entry.gender+"</td>";
	// 							table_row += "<td>"+entry.companycode+"</td>";
	// 							table_row += "<td>"+entry.branch_name+"</td>";
	// 							table_row += "<td>"+entry.doj+"</td>";
	// 							table_row += "<td>"+entry.levy+"</td></tr>";
	// 							$('#page-length-option tbody').append(table_row);
							
	// 					});
	// 				}else{
						
	// 				}
	// 			}
	// 		});		
	//    }
	// });
</script>

</html>