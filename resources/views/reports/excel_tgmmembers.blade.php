<!DOCTYPE html>
<html>

<head>
	<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/vendors.min.css') }}">
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
			.page-length-option {
			  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
			  border-collapse: collapse;
			  width: 100%;
			}

			.page-length-option td, .page-length-option th {
			  //border: 1px solid #ddd !important;
			  padding: 4px;
			}
			

			.page-header-area{
				display: none;
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
		.page-length-option td, .page-length-option th {
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

		
		
	</style>
	<script type="text/javascript">
	
	</script>
</head>

<body>
	
	@php 
	$logo = CommonHelper::getLogo(); 
	$totalmembers = 0;
	
	$lastbankid = '';
@endphp
@foreach($data['member_view'] as $member)
@php
	$pgm_members = CommonHelper::getPgmMembers($data['month_year'],$member->companyid,$data['branch_id'],$data['unionbranch_id'],$data['status_id']);
	$companyname = CommonHelper::getCompanyName($member->companyid);
	$totalmembers += count($pgm_members);
	
@endphp

<table id="page-length-option{{$totalmembers}}" class="display page-length-option table2excel" width="100%">
		<thead>
			<tr class="">
				
				<td colspan="3" rowspan="2" style="text-align:right">
					<img src="{{ public_path('/assets/images/logo/'.$logo) }}" height="50" />
				</td>
				<td colspan="7" style="text-align:center;padding:10px;vertical-align:top;">
					<span style="text-align:center;font-weight: bold;font-size:18px;vertical-align:top;">NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</span>
					
				</td>
				<td colspan="3" rowspan="2">	
					
				</td>
			</tr>
			<tr class="">
				
				<td colspan="7" align="center" style="text-align:center !important;padding:10px;vertical-align:top;margin-left: 100px;">
					<center><span style="text-align:center;font-weight: bold;font-size:28px;">TGM MEMBERS REPORT</span></center>
				</td>
				
			</tr>
			<tr class="" style="font-weight: bold;">
			
				<td colspan="3" style="border-bottom: 1px solid #988989 !important;height: 45px;">
					To Branch Hons. Secretary
					@if($data['unionbranch_id']!='' && $data['branch_id']=='')
						<p>
						Branch Name : {{ $data['unionbranch_name'] }}
					</p>
						
					@endif
					<p>
						Bank Name : {{ $companyname }}
					</p>
				</td>
				<td colspan="7" align="center" style="text-align:center;vertical-align:top;border-bottom: 1px solid #988989 !important;">
					{{ date('01 M Y',strtotime($data['month_year'])) }} - {{ date('t M Y',strtotime($data['month_year'])) }}
				</td>
				<td colspan="3" style="border-bottom: 1px solid #988989 !important;">	
					
					@if($data['unionbranch_id']!='' && $data['branch_id']=='')
						<p>
						Branch Code : {{ $data['unionbranch_id'] }}
					</p>
					@endif
				</td>
			</tr>
				
			<tr class="" style="" width="100%">
				<th style="border: 1px solid #988989 !important; " width="6%" align="center">SNO</th>
				<th style="border: 1px solid #988989 !important; " width="9%" >{{__('M/NO')}}</th>
                <th style="border: 1px solid #988989 !important; " width="36%">{{__('MEMBER NAME')}}</th>
               
                <th style="border: 1px solid #988989 !important; " width="15%" align="center">{{__('NRIC')}}</th>
                <th style="border: 1px solid #988989 !important; " width="9%">{{__('GENDER')}}</th>
                <th style="border: 1px solid #988989 !important; " width="11%">{{__('BANK')}}</th>
                <th style="border: 1px solid #988989 !important; " width="30%">{{__('BANK BRANCH')}}</th>
                <th style="border: 1px solid #988989 !important; " width="6%">{{__('TYPE')}}</th>
                
                <th style="border: 1px solid #988989 !important; " width="13%">{{__('DOJ')}}</th>
                <th style="border: 1px solid #988989 !important; " width="7%">{{__('LEVY')}}</th>
                <th style="border: 1px solid #988989 !important; " width="7%">{{__('TDF')}}</th>
                <th style="border: 1px solid #988989 !important;" width="15%">STATUS</th>
                <th style="border: 1px solid #988989 !important; " width="17%">{{__('LAST PAID DATE')}}</th>
			</tr>
		</thead>
		<tbody class="" width="100%">
	
				@php
					
					$sno = 1;
				@endphp
				@foreach($pgm_members as $pgmmember)

				<tr >
					<td style="border: 1px solid #988989 !important; ">{{ $sno }}</td>
					<td style="border: 1px solid #988989 !important;">{{ $pgmmember->member_number }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $pgmmember->name }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $pgmmember->ic=='' ? $pgmmember->employee_id : $pgmmember->ic }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $pgmmember->gender }}</td>
                    <td style="border: 1px solid #988989 !important;"> {{ $pgmmember->companycode }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $pgmmember->branch_name }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $pgmmember->designation_name }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ date('d/M/Y',strtotime($pgmmember->doj)) }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $pgmmember->levy }}</td>	
                    <td style="border: 1px solid #988989 !important;">{{ $pgmmember->tdf }}</td>	
                    <td style="border: 1px solid #988989 !important;">{{  $pgmmember->status_name }}</td>
                    @php
                    	if(strtotime($data['month_year'])>strtotime($pgmmember->last_paid_date)){
                    		$last_paid_date = $data['month_year'];
                 	    }else{
                 	    	$last_paid_date = $pgmmember->last_paid_date;
                 		}
                    @endphp
                    <td style="border: 1px solid #988989 !important;">{{ $last_paid_date!='' ? date('M/Y',strtotime($last_paid_date)) : '' }}</td>
					
					
				</tr> 
				@php
					$sno++;
				@endphp
				@endforeach
				
			
			<tr>
				<td colspan="13" style="font-weight:bold;">Total Member's Count : {{ $sno-1 }}</td>
			</tr> 
		</tbody>
		
	</table>
	

@endforeach
<p>Oveall Total Member's Count : {{ $totalmembers }}</p>

	
</body>


</html>