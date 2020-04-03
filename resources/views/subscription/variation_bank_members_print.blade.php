
<!DOCTYPE html>
<html>

<head>
	<title>Subscription Variation Members</title>
	<script src="http://localhost/ums/public/assets/js/jquery-1.12.4.min.js" type="text/javascript"></script>
	<link href="http://localhost/ums/public/assets/material-font.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="http://localhost/ums/public/assets/css/vendors.min.css">
	<link rel="stylesheet" type="text/css" href="http://localhost/ums/public/assets/css/flag-icon.min.css">
	<!-- <link rel="stylesheet" type="text/css" href="http://localhost/ums/public/assets/css/vertical-modern-menu.css"> -->
	<link rel="stylesheet" type="text/css" href="http://localhost/ums/public/assets/css/materialize.css">
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
		
		@page  {
		  margin: 3mm
		}
		
		@media  print {
			@page  {
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
			  //border: 1px solid #ddd !important;
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
		@media  not print {
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
			//border: 1px solid #ddd !important;
			padding: 4px;
		}
		.hide{
			display: none !important;
		}
		
	</style>
	<style>
	  * {
	    box-sizing: border-box;
	  }

	  /* Create three equal columns that floats next to each other */
	  .column {
	    float: left;
	    width: 33%;
	    padding: 10px;
	    height: 300px; /* Should be removed. Only for demonstration */
	  }

	  /* Clear floats after the columns */
	  .row:after {
	    content: "";
	    display: table;
	    clear: both;
	  }
	  #page-summary td, #page-summary th {
		  //border: 1px solid #ddd !important;
		  padding: 15px;
	  }
  </style>
	<script type="text/javascript">
		//window.print();
	</script>
</head>
@php 
	$logo = CommonHelper::getLogo(); 
	$datacmpy = $data['subsdata']; 
@endphp
<body>
	<div class="" style="text-align: center">
		<table width="100%" style="border: none;">
			<tr>
								<td width="20%"></td>
				<td width="10%"></td>
				<td width="50%" style="text-align:center;">
					
				</td>
				<td width="20%">	
					<!--a href="#" class="exportToExcel export-button btn btn-sm" style="background:#227849;"><i class="material-icons">explicit</i></a>
					<a href="http://localhost/ums/index.php/en/export-pdf-members-new?offset=0&amp;from_date=2020-03-01&amp;to_date=2020-03-31&amp;company_id=&amp;branch_id=&amp;member_auto_id=&amp;join_type=&amp;unionbranch_id=&amp;from_member_no=&amp;to_member_no=" class="export-button btn btn-sm" style="background:#ff0000;"><i class="material-icons">picture_as_pdf</i></a>
					<a href="#" class="export-button btn btn-sm" style="background:#ccc;" onClick="window.print()"><i class="material-icons">print</i></a-->
				</td>
			</tr>
		</table>
	</div>
	<!-- <div class="page-footer">
    I'm The Footer
  </div>-->
  	<table id="page-length-option" class="display table2excel" width="100%">
		<thead>
			<tr class="">
				
				<td colspan="2" rowspan="2" style="text-align:right">
					<img src="{{ asset('public/assets/images/logo/'.$logo) }}" height="50" />
				</td>
				<td colspan="8" style="text-align:center;padding:10px;vertical-align:top;">
					<span style="text-align:center;font-weight: bold;font-size:18px;vertical-align:top;">NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</span>
					
				</td>
				<td colspan="3" rowspan="2" width="200px;">	
					</br>
				</td>
			</tr>
			<tr class="">
				
				<td colspan="8" style="text-align:center;padding:10px;font-weight: bold;">
				
					<span style="margin-top:0;">SUBSCRIPTION VARIATION MEMBERS - {{ strtoupper(date('M Y',strtotime($data['month_year_full']))) }}</span>
				</td>
				
			</tr>
			<tr class="" style="font-weight: bold;">
			
				<td colspan="2" style="border-bottom: 1px solid #988989 !important;">
					{{ isset($datacmpy) ? $datacmpy->company_name : ""}} 
									</td>
				<td colspan="8" align="center" style="text-align:center;vertical-align:top;border-bottom: 1px solid #988989 !important;">
					&nbsp;
				</td>
				<td colspan="3" style="border-bottom: 1px solid #988989 !important;">	
					
									</td>
			</tr>
				
			<!--  -->
		</thead>
		<!-- <tbody class="" width="100%">
						
			
				<tr >
					<td style="border: 1px solid #988989 !important; ">1</td>
					<td style="border: 1px solid #988989 !important;">MUHAMMAD ZHAFIR BIN AMINUDIN</td>
					
				</tr> 
					
			
		</tbody> -->		
	</table>	
	@php
		$userid = Auth::user()->id;
		$companyid = $data['companyid'];
		$get_roles = Auth::user()->roles;
		$user_role = $get_roles[0]->slug;


	@endphp
	
	<div class="row">
		<div class="column" width="30% !important;">
			&nbsp;
		</div>
		<div class="column" width="40%;">
			<center><h4>Subscription Summary</h4></center>
			<table id="page-summary" class="display table2excel" width="100%">
				<thead>
					<tr class="" style="">
						<th style="border: 1px solid #988989 !important;">PARTICULARS</th>
						<th style="border: 1px solid #988989 !important;">VALUE</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="border: 1px solid #988989 !important;">Total Members in NUBE</td>
						<td style="border: 1px solid #988989 !important;">{{ $data['members_count'] }}</td>
					</tr>
					<tr>
						<td style="border: 1px solid #988989 !important;">Total Members Uploaded</td>
						<td style="border: 1px solid #988989 !important;">{{ $data['company_subscription_list']-$data['doj_count'] }}</td>
					</tr>
					<tr>
						<td style="border: 1px solid #988989 !important;">Total Members Matched</td>
						<td style="border: 1px solid #988989 !important;">{{ $data['matched_count']-$data['doj_count'] }}</td>
					</tr>
					<tr>
						<td style="border: 1px solid #988989 !important;">Uploaded Amount (RM)</td>
						<td style="border: 1px solid #988989 !important;">{{ number_format($data['matched_amount'],2,".",",") }}</td>
					</tr>
					<tr>
						<td style="border: 1px solid #988989 !important;">Not Matched Members</td>
						<td style="border: 1px solid #988989 !important;">{{ count($data['submembers']) }}</td>
					</tr>
					
				</tbody>
			</table>
		</div>
		<div class="column" width="30%;">
			&nbsp;
		</div>
	</div>
	<div style="clear: both;"></div>
	<br>
	<br>
	@php
	
		$notmatched = $data['submembers'];
	@endphp
	<div class="row">
	<div id="notmatcheddetails" style="margin-top: 30px;padding: 10px;" class="@if(count($notmatched)==0) hide @endif"  >
		<p style="font-size: 16px;text-decoration: underline;font-size: 16px;font-weight:bold;">Not Matched Members List</p>
		<table id="page-length-option" class="display" width="100%">
			<thead>
				<tr width="100%">
					<th style="border: 1px solid #988989 !important;" width="5%">{{__('S.No')}}</th>
					<th style="border: 1px solid #988989 !important;" width="25%">{{__('Member Name')}}</th>
					
					<th style="border: 1px solid #988989 !important;" width="10%">{{__('NRIC')}}</th>
					<th style="border: 1px solid #988989 !important;" width="10%">{{__('Amount')}}</th>
					<th style="border: 1px solid #988989 !important;" width="15%">{{__('Reason')}}</th>
					<th style="border: 1px solid #988989 !important;" width="25%">{{__('Remarks')}}</th>
					@if($user_role=='company')
					<th style="border: 1px solid #988989 !important;" width="10%">{{__('Status')}}</th>
					
					@endif
					
				</tr> 
			</thead>
			<tbody>
				@php
					$slno=1;
					//dd($user_role);
				@endphp
				@foreach($notmatched as  $key => $member)
					@php
						//dd($member);
						$approval_status = $member->approval_status;
						
						$mismatchstatusdata = CommonHelper::get_mismatchstatus_data($member->sub_member_id);
											
						$unmatchdata = CommonHelper::get_unmatched_data($member->sub_member_id);
						$matchname = '';
						$unmatchreason = '';
						$approval_status = 0;
						
						if(!empty($unmatchdata)){
							$unmatchreason = $unmatchdata->remarks;
							$approval_status = $unmatchdata->approval_status;
						}
						

						if(!empty($mismatchstatusdata)){
							$matchid = $mismatchstatusdata->match_id;
							
							if($matchid==6){
								$matchname = 'Others';
							}else{
								$matchname = CommonHelper::get_member_match_name($matchid);
							}
							
							//$approval_status = $mismatchstatusdata->approval_status;
						}
						
					@endphp
					<tr style="overflow-x:auto;">
						<td style="border: 1px solid #988989 !important;">{{$slno}}</td>
						<td width="25%" style="border: 1px solid #988989 !important;">{{ $member->up_member_name }}</td>
						<!--td id="member_code_{{ $member->sub_member_id }}" >{{ $member->member_number }}</td-->
						
						<td style="border: 1px solid #988989 !important;">{{ $member->up_nric }}</td>
						<td style="border: 1px solid #988989 !important;">{{ number_format($member->Amount,2,".",",") }}</td>
						<td style="border: 1px solid #988989 !important;" id="unmatch_reason_{{ $member->sub_member_id }}">{{$matchname}}</td>
						<td style="border: 1px solid #988989 !important;" id="unmatch_reason_{{ $member->sub_member_id }}">{{$unmatchreason}}</td>
						@if($user_role=='company')

						<td style="border: 1px solid #988989 !important;" id="approve_status_{{ $member->sub_member_id }}"><span class="">{{ $approval_status==1 ? 'Updated' : 'Pending' }}</span></td>
						
						
						<td>
						</td>
						
						@endif
						
					</tr> 
					@php
						$slno++;
					@endphp
				@endforeach
			</tbody>
			
		</table>
		<br>
	</div>
	</div>
	@php
		$pre_company_members = CommonHelper::getLastMonthlyPaidMembersAll($companyid,$data['month_year_full'],2);
		$current_company_members = CommonHelper::getcurrentMonthlyPaidMembersAll($companyid,$data['month_year_full'],2);
	@endphp
	<div class="row">
		<div id="predetails" style="margin-top: 30px;padding: 10px;" class="@if(count($pre_company_members)==0) hide @endif"  >
			<p style="font-size: 16px;text-decoration: underline;font-size: 16px;font-weight:bold;">Previous Subscription Paid - Current Subscription Unpaid</p>
			<table id="page-length-option" class="display" width="100%">
				<thead>
					<tr class="" >
						<th width="5%" style="border: 1px solid #988989 !important;">{{__('S.No')}}</th>
						<th width="32%" style="border: 1px solid #988989 !important;">Member Name</th>
						<th width="10%" style="border: 1px solid #988989 !important;">NRIC</th>
						<th width="6%" style="border: 1px solid #988989 !important;">{{ date('M Y',strtotime($data['month_year_full'].' -1 Month')) }} <br> Amount</th>
						<th width="6%" style="border: 1px solid #988989 !important;">{{ date('M Y',strtotime($data['month_year_full'])) }} <br> Amount</th>
						<th width="32%" style="border: 1px solid #988989 !important;" width="10%">{{__('Reason')}}</th>
						
							
						
					</tr>
				</thead>
				<tbody class="tbody-area" width="100%">
					@php
						$slno = 1;
					@endphp
					@foreach($pre_company_members as $company)
						@php
							$unmpaiddata = CommonHelper::get_unpaid_data($company->sub_member_id);
							$unpaidreason = '';
							$approval_status = 0;
							if(!empty($unmpaiddata)){
								$unmatchreason = $unmpaiddata->reason;
								$unpaidreason = CommonHelper::get_unpaid_reason($unmatchreason);
								if($unmatchreason==5){
									$unpaidreason = $unmpaiddata->remarks;
								}
								$approval_status = 1;
							}
						@endphp
						<tr>
							<td style="border: 1px solid #988989 !important;">{{$slno}}</td>
							<td style="border: 1px solid #988989 !important;">{{ $company->name }}</td>
							<td style="border: 1px solid #988989 !important;">{{ $company->ic }}</td>
							<td style="border: 1px solid #988989 !important;">{{ number_format($company->SUBSCRIPTION_AMOUNT,2,".",",") }}</td>
							<td style="border: 1px solid #988989 !important;">0</td>
							<td style="border: 1px solid #988989 !important;" id="unpaid_reason_{{ $company->sub_member_id }}" width="10%">{{$unpaidreason}}</td>
							
							
						</tr>
						@php
							$slno++;
						@endphp
					@endforeach
					
				</tbody>
				
			</table>
			<br>
		</div>
	</div>
	<div class="row">
		<div id="currentdetails" style="margin-top: 30px;padding: 10px;" class="@if(count($current_company_members)==0) hide @endif"  >
			<p style="font-size: 16px;text-decoration: underline;font-weight:bold;">Previous Subscription Unpaid - Current Subscription Paid</p>
			<table id="page-length-option" class="display" width="100%">
				<thead>
					<tr class="" >
						<th width="5%" style="border: 1px solid #988989 !important;">{{__('S.No')}}</th>
						<th width="32%" style="border: 1px solid #988989 !important;">Member Name</th>
						<th width="10%" style="border: 1px solid #988989 !important;">NRIC</th>
						<th width="6%" style="border: 1px solid #988989 !important;">{{ date('M Y',strtotime($data['month_year_full'].' -1 Month')) }} <br> Amount</th>
						<th width="6%" style="border: 1px solid #988989 !important;">{{ date('M Y',strtotime($data['month_year_full'])) }} <br> Amount</th>
						<th width="32%" style="border: 1px solid #988989 !important;" width="10%">{{__('Reason')}}</th>
						
							
						
					</tr>
				</thead>
				<tbody class="tbody-area" width="100%">
					@php
						$slno1 = 1;
						
					@endphp
					@foreach($current_company_members as $company)
						@php
							$unmpaiddata = CommonHelper::get_unpaid_data($company->sub_member_id);
							$unpaidreason = '';
							$approval_status = 0;
							if(!empty($unmpaiddata)){
								$unmatchreason = $unmpaiddata->reason;
								$unpaidreason = CommonHelper::get_lastunpaid_reason($unmatchreason);
								if($unmatchreason==5){
									$unpaidreason = $unmpaiddata->remarks;
								}
								$approval_status = 1;
							}
						@endphp
						<tr>
							<td style="border: 1px solid #988989 !important;">{{$slno1}}</td>
							<td style="border: 1px solid #988989 !important;">{{ $company->name }}</td>
							<td style="border: 1px solid #988989 !important;">{{ $company->ic }}</td>
							<td style="border: 1px solid #988989 !important;">0</td>
							<td style="border: 1px solid #988989 !important;">{{ number_format($company->SUBSCRIPTION_AMOUNT,2,".",",") }}</td>
							
							<td style="border: 1px solid #988989 !important;" id="unpaid_reason_{{ $company->sub_member_id }}" width="10%">{{$unpaidreason}}</td>
							
						</tr>
						@php
							$slno1++;
						@endphp
					@endforeach

				</tbody>
				
			</table>
			<br>
		</div>

	</div>
	
</body>
<script src="http://localhost/ums/public/excel/jquery-ui.min.js"></script>
<script>
	var excelfilenames="New Members report";
</script>
<script src="http://localhost/ums/public/excel/jquery.table2excel.js"></script>
<!--script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.4.1/jspdf.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/2.3.5/jspdf.plugin.autotable.min.js"></script>
<script type="text/javascript" src="https://www.jqueryscript.net/demo/export-table-json-csv-txt-pdf/src/tableHTMLExport.js"></script-->
<script>
	$(document).ready( function() { 
		$("html").css('opacity',1);

		$(".exportToExcel").click(function(e){
			$("#page-length-option").table2excel();
		});
    }); 
	
</script>

</html>