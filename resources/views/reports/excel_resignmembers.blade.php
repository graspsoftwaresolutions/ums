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
			#page-length-option {
			  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
			  border-collapse: collapse;
			  width: 100%;
			}

			#page-length-option td, #page-length-option th {
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
		
		 p span { 
            display: block; 
        } 

		
		
	</style>
	<script type="text/javascript">
	
	</script>
</head>

<body>
	
	@php 
		$logo = CommonHelper::getLogo(); 
	@endphp
<table id="page-length-option" class="display table2excel" width="100%">
		<thead>
			<tr class="">
				
				<td colspan="4" rowspan="1" style="text-align:right">
					<img src="{{ public_path('/assets/images/logo/'.$logo) }}" height="50" />
				</td>
				<td colspan="10" style="text-align:center;padding:10px;vertical-align:top;">
					<span style="text-align:center;font-weight: bold;font-size:18px;vertical-align:top;">NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</span>
					<br/>
					RESIGNATION REPORT
					
				</td>
				<td colspan="3" rowspan="1">	
					
				</td>
			</tr>
			
			<tr class="" style="font-weight: bold;">
			
				<td colspan="4" style="border-bottom: 1px solid #988989 !important;" height="40">
					
					<p>
						To Branch Hons. Secretary <br/>
					
					@if($data['unionbranch_id']!='' && $data['branch_id']=='')
						
							Branch Name : {{ $data['unionbranch_name'] }}
						
					@endif
					</p>
				</td>
				<td colspan="10" align="center" style="text-align:center;vertical-align:top;border-bottom: 1px solid #988989 !important;">
					{{ date('d M Y',strtotime($data['from_date'])) }} - {{ date('d M Y',strtotime($data['to_date'])) }}
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
				<th style="border: 1px solid #988989 !important;font-weight:bold;">SNO</th>
                <th style="border: 1px solid #988989 !important;font-weight:bold; width:47px;">{{__('NAME')}}</th>
                <th style="border: 1px solid #988989 !important;font-weight:bold;">{{__('M/NO')}}</th>
                <th style="border: 1px solid #988989 !important;font-weight:bold; width:20px;">{{__('IC NO')}}</th>
                <th style="border: 1px solid #988989 !important;font-weight:bold;width:12px;">{{__('JOINED')}}</th>
                <th style="border: 1px solid #988989 !important;font-weight:bold;width:12px;">{{__('RESIGNED')}}</th>
                <th style="border: 1px solid #988989 !important;font-weight:bold;">{{__('BCH')}}</th>
                <th style="border: 1px solid #988989 !important;font-weight:bold;width:20px;">{{__('BANK')}}</th>
                <th style="border: 1px solid #988989 !important;font-weight:bold;width:35px; ">{{__('BANK BRANCH')}}</th>
                <th style="border: 1px solid #988989 !important;font-weight:bold;">{{__('CONT')}}</th>
                <th style="border: 1px solid #988989 !important;font-weight:bold;">{{__('BEN')}}</th>
				<th style="border: 1px solid #988989 !important;font-weight:bold;">{{__('INS')}}</th>
                <th style="border: 1px solid #988989 !important;font-weight:bold;">{{__('TOTAL')}}</th>
                <th style="border: 1px solid #988989 !important;font-weight:bold;width:15px;">{{__('PAYMODE')}}</th>
                <th style="border: 1px solid #988989 !important;font-weight:bold;width:12px;">{{__('DATE')}}</th>
                <th style="border: 1px solid #988989 !important;font-weight:bold;">{{__('REASON')}}</th>
                <th style="border: 1px solid #988989 !important;font-weight:bold;width:45px;">{{__('CLAIMED BY')}}</th>

			</tr>
		</thead>
		<tbody class="" width="100%">
			@php
				$sno = 1;
				$tot_benifit = 0;
				$tot_contribution = 0;
				$tot_ins = 0;
				$tot_amt = 0;
				$reasoncodes = [];

			@endphp
			@foreach($data['member_view'] as $member)
				@php
					if($member->new_ic!=""){
						$ic = $member->new_ic;
					}else if($member->old_ic!=""){
						$ic = $member->old_ic;
					}else{
						$ic = $member->employee_id;
					}
				@endphp
				<tr>
					<td style="border: 1px solid #988989 !important;">{{ $sno }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $member->name }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $member->member_number }}</td>
                    <td style="border: 1px solid #988989 !important;text-align: left;">{{ $ic }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ date("d/M/Y",strtotime($member->doj)) }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ date("d/M/Y",strtotime($member->resignation_date)) }}</td>
                    <td style="border: 1px solid #988989 !important;text-align: left;">{{ $member->unioncode }}</td>
                    <td style="border: 1px solid #988989 !important;text-align: left;">{{ $member->companycode }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $member->branch_name }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $member->contribution }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $member->benifit }}</td>
					<td style="border: 1px solid #988989 !important;">{{ $member->insuranceamount }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $member->total }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $member->paymode }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ date("d/M/Y",strtotime($member->voucher_date)) }}</td>
                    <td style="border: 1px solid #988989 !important;text-align: left;">{{ $member->reason_code }}</td>
                    <td style="border: 1px solid #988989 !important;">{{ $member->claimer_name }}</td>
				</tr> 
				@php
					$sno++;
					$tot_benifit += $member->benifit;
					$tot_contribution += $member->contribution;
					$tot_ins += $member->insuranceamount;
					$tot_amt += $member->total;
					if(!in_array($member->reason_code, $reasoncodes, true)){
				        array_push($reasoncodes, $member->reason_code);
				    }
				@endphp
			@endforeach
			<tr style="font-weight: bold;">
				<td colspan="8" style="border: 1px solid #988989 !important;">Total Member's Count : {{ $sno-1 }}</td>
				<td style="border: 1px solid #988989 !important;">Total</td>
				<td style="border: 1px solid #988989 !important;">{{ $tot_contribution }}</td>
				<td style="border: 1px solid #988989 !important;">{{ $tot_benifit }}</td>
				<td style="border: 1px solid #988989 !important;">{{ $tot_ins }}</td>
				<td style="border: 1px solid #988989 !important;">{{ $tot_amt }}</td>
				<td colspan="4" style="border: 1px solid #988989 !important;"></td>
				
			</tr> 
			@php
				 sort($reasoncodes,SORT_NUMERIC);

			@endphp
			@if($sno>1)
			<tr>
				<td style=""></td>
				<td style=""></td>
				<td style=""></td>
				<td style=""></td>
				<td style=""></td>
				<td style=""></td>
				<td style=""></td>
				<td style=""></td>
				<td style=""></td>
				<td style=""></td>
				<td style=""></td>
				<td style=""></td>
				<td style=""></td>
				<td style=""></td>
				<td style=""></td>
				<td style=""></td>
			</tr>
			
			<tr style="font-weight: bold;">
				<td style="border: 1px solid #988989 !important;">
					Code
				</td>
				<td style="border: 1px solid #988989 !important;">
					Reason
				</td>
				<td rowspan="4" colspan="2" >
					CON - CONTRIBUTION <br/>
					BEN - BENEFIT <br/>
					INS - INSURANCE <br/>
				</td>
			</tr>
			@foreach($reasoncodes as $r)
			<tr>
				<td style="border: 1px solid #988989 !important;text-align: left;">
					{{ $r }}
				</td>
				<td style="border: 1px solid #988989 !important;">
					{{ CommonHelper::getReasonNameBYCode($r) }}
				</td>
			</tr>
			@endforeach
			@endif
		</tbody>
		
	</table>

</body>


</html>