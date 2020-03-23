<!DOCTYPE html>
<html>

<head>
	<script src="{{ asset('public/assets/js/jquery-1.12.4.min.js') }}" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/vendors.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/flag-icon.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/vertical-modern-menu.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/materialize.css') }}">
	<style>
		/* Styles go here */
		td, th {
			display: table-cell;
			padding: 6px 5px;
			text-align: none;
			vertical-align: middle;
			border-radius: 2px;
		}
		/*#page-length-option,#page-length-one {
		  border-collapse: collapse;
		  width: 100%;
		}*/

		/*#page-length-option td, #page-length-one th {
		  border: 1px solid #ddd;
		  width: auto;
			overflow: hidden;
			word-wrap: break-word;
			page-break-inside: avoid;
		  padding: 4px;
		}*/

		.page-length-option td, .page-length-option th{
		  border: 1px solid #ddd !important;
		  padding: 4px;
		}
		
		@media print{
			
			@page {size: portrait; margin: 3mm;}
			
			.export-button{
				display:none;
			}
			body {margin: 0;}
			.page-length-option td, .page-length-option th{
			  border: 1px solid #ddd !important;
			  padding: 4px;
			}
			.report-address{
				font-weight:bold;
				font-size:14px;
			}
		}
		@media print {
			.footer-summary {page-break-after: always;}
		}
		html { overflow: visible }
		html {
			    font-size: 12px;
			}
		.title-area{
			font-weight: bold;
			font-size:16px;
		}
		.table-title{
			font-weight: bold;
			font-size:14px;
		}
		.content-area{
			padding-left: 20px;
		}
		.total-summary p {
			font-size: 14px;
			line-height: 1;
		}

		/*.page-header {
		    display: block; 
		    position: fixed; 
		    top: 0;
		    margin-bottom: 20px;
	    } 

	    #page-length-option{
	    	top:100px;
	    }*/
		
	</style>
	<script type="text/javascript">
		
		@if($data['print']==1)
			window.print();
		@endif
	</script>
</head>

<body>
	<div class="page-header" style="text-align: center">
		<table width="100%">
			<tr>
				<td width="20%"></td>
				<td width="10%"></td>
				<td width="50%" class="title-area" style="text-align:center;">NUBE Monthly Subscription {{ date('M Y',strtotime($data['month_year_full'])) }} - Discrepancy Report
					
				</td>
				<td width="20%">	
					<!--a href="#" class="export-button btn btn-sm" style="background:#ccc;" onClick="window.print()"><i class="material-icons">print</i></a-->
				</td>
			</tr>
		</table>
	</div>
	
	@php
	//dd($data);
	if($data['groupby']==1){
		$memberslist = $data['union_branch_view'];
	}elseif($data['groupby']==2){
		$memberslist = $data['company_view'];
	}else{
		$memberslist = $data['branch_view'];
	}
	$overall_total_fifth_new=0;
	$overall_total_fourth_new=0;
	$overall_total_third_new=0;
	$overall_total_second_new=0;
	$overall_total_last_new=0;
	$overall_total_this_new=0;

	$total_previous_subs=0;
	
	if($data['groupby']==2){
		$memberslist = $data['head_company_view'];
	}
	//dd($data);
	
	
	@endphp
	
	@foreach($memberslist as $company)

	@php
		if($data['groupby']==1){
			$typeid = $company->union_branchid;
			$current_count = CommonHelper::getMonthEndPaidCount($company->union_branchid,$data['month_year_full'],1);
			$last_month_count = CommonHelper::getUnionMonthlyPaidCount($company->union_branchid,$data['last_month_year']);
			$current_amount = CommonHelper::getMonthEndPaidAmount($company->union_branchid,$data['month_year_full'],1);
			$last_month_amount = CommonHelper::getMonthEndPaidAmount($company->union_branchid,$data['last_month_year'],1);
		}elseif($data['groupby']==2){
		
			$typeid = $company['id'];

			$company_data = CommonHelper::getMontendcompanyVariation($company['company_list'],$data['month_year_full']);
			$last_company_data = CommonHelper::getMontendcompanyVariation($company['company_list'],$data['last_month_year']);
			$current_count = $company_data->total_members;
			$last_month_count = $last_company_data->total_members;

			$current_amount = CommonHelper::getMontendcompanyVariationAmount($company['company_list'],$data['month_year_full']);
			$last_month_amount = CommonHelper::getMontendcompanyVariationAmount($company['company_list'],$data['last_month_year']);
		}else{
			$typeid = $company->branch_id;
			$current_count = CommonHelper::getMonthEndPaidCount($company->branch_id,$data['month_year_full'],3);
			$last_month_count = CommonHelper::getBranchMonthlyPaidCount($company->branch_id,$data['last_month_year']);
			$current_amount = CommonHelper::getMonthEndPaidAmount($company->branch_id,$data['month_year_full'],3);
			$last_month_amount = CommonHelper::getMonthEndPaidAmount($company->branch_id,$data['last_month_year'],3);
		}
		$pre_company_members = CommonHelper::getLastMonthlyPaidMembers($typeid,$data['month_year_full'],$data['groupby']);
		$current_company_members = CommonHelper::getcurrentMonthlyPaidMembers($typeid,$data['month_year_full'],$data['groupby']);
		$newjoin_company_members = CommonHelper::getNewJoinPaidMembers($typeid,$data['month_year_full'],$data['groupby']);
		$inc_company_members = CommonHelper::getSubscriptionIncDecMembers($typeid,$data['month_year_full'],$data['groupby'],1);
		$dec_company_members = CommonHelper::getSubscriptionIncDecMembers($typeid,$data['month_year_full'],$data['groupby'],0);

		//dd($company_members);
		//$company_members = CommonHelper::getCompanyMembers($typeid,$data['month_year_full'],$data['groupby']);
		$count=1;
		$countone=1;
		$countj=1;
		$countinc=1;
		$countdec=1;
		$total_previous_subs=0;
		$total_current_subs=0;
		$total_newjoin_subs=0;
		$total_inc_subs=0;
		$total_inc_subs_old=0;
		$total_inc_subs_diff=0;
		$total_dec_subs=0;
		$total_dec_subs_old=0;
		$total_dec_subs_diff=0;

		
	@endphp
	@if($last_month_count!=0 || $current_count!=0)
	<h5 style="text-decoration: underline;font-weight:bold;" class="">
		@if($data['groupby']==1)
			{{ $company->union_branch_name }}
		@elseif($data['groupby']==2)
			{{ $company['company_name'] }}
		@else
			{{ $company->company_name }} - {{ $company->branch_name }}
		@endif
	</h5>
	
	<div class="total-summary" style="font-weight:bold;">
		<p style="padding-left: 40px;">{{ $last_month_count }} members paid RM {{ number_format($last_month_amount,2,".",",") }} Subscription on {{ date('M Y',strtotime($data['month_year_full'].' -1 Month')) }}</p>
		<p style="padding-left: 40px;">{{ $current_count }} members paid RM {{ number_format($current_amount,2,".",",") }} Subscription on {{ date('M Y',strtotime($data['month_year_full'])) }} </p>
		<p style="padding-left: 40px;">({{ $current_count-$last_month_count }}) members and RM ({{ number_format($current_amount-$last_month_amount,2,".",",") }}) Subscription are Different </p>
		
	</div>
	@endif
	<div class="content-area">
		@if(count($newjoin_company_members)>0)
		<p style="font-size: 16px;text-decoration: underline;font-size: 16px;font-weight:bold;">New Join on {{ date('M Y',strtotime($data['month_year_full'])) }}</p>
		<table id="page-lengthtwo" class="display page-length-option" width="100%">
		<thead>
			<tr class="table-title">
				<th>{{__('S.No')}}</th>
				<th>{{__('NRIC')}}</th>
				<th>{{__('Member Name')}}</th>
				<th>{{ date('M Y',strtotime($data['month_year_full'].' -1 Month')) }}</th>
				<th>{{ date('M Y',strtotime($data['month_year_full'])) }}</th>
				
				<th>{{__('Different')}}</th>
				
			</tr>
			
		</thead>
		<tbody class="tbody-area">
			@foreach($newjoin_company_members as $member)
			@php

				$mdoj= $member->doj;
				//$dojmonth = date('Y-m',strtotime($mdoj));
				//$paymonth = date('Y-m',strtotime($data['month_year_full']));
				
				$salary = $member->salary==Null ? 0 : $member->salary;
				$subsamt = $member->SUBSCRIPTION_AMOUNT;
				$total_subs = ($salary*1)/100;
				$bf_amt = 3;
				$ins_amt = 7;
				$payable_subs = $subsamt;
				//$payable_subs = $total_subs-($bf_amt+$ins_amt);
			@endphp
		
			
			<tr style="font-weight:bold;">
				<td>{{$countj}}</td>
				<td>{{ $member->ic }}</td>
				<td>{{ $member->name }}</td>
				<td>{{ 0 }}</td>
				<td>{{ $payable_subs }}</td>
				
				<td>{{ $payable_subs }}</td>
				
			</tr>
			@php
				$countj++;
				$total_newjoin_subs+=$payable_subs;
			
			@endphp
			@endforeach
		</tbody>
		<tfoot>
			<tr class="table-title">
				<th colspan="3" style="text-align: right;" >Total</th>
				<th>{{ 0 }}</th>
				<th>{{ $total_newjoin_subs }}</th>
				
				<th>{{ $total_newjoin_subs }}</th>
				
			</tr>
		</tfoot>
		
		
		</table>
		@endif

		@if(count($pre_company_members)>0)
		<p style="font-size: 16px;text-decoration: underline;font-size: 16px;font-weight:bold;">Previous Subscription Paid - Current Subscription Unpaid</p>
		
		<table id="page-length" class="display page-length-option" width="100%">
		<thead>
			
			<tr class="table-title">
				<th>{{__('S.No')}}</th>
				<th>{{__('NRIC')}}</th>
				<th>{{__('Member Name')}}</th>
				<th>{{ date('M Y',strtotime($data['month_year_full'].' -1 Month')) }}</th>
				<th>{{ date('M Y',strtotime($data['month_year_full'])) }}</th>
				
				<th>{{__('Different')}}</th>
				
			</tr>
		</thead>
		<tbody class="tbody-area">
			
			
			@foreach($pre_company_members as $member)
			@php

				//$salary = $member->salary==Null ? 0 : $member->salary;
				//$total_subs = ($salary*1)/100;
				$total_subs = $member->SUBSCRIPTION_AMOUNT;
				$bf_amt = 3;
				$ins_amt = 7;
				$payable_subs = $total_subs;
			@endphp
			<tr style="font-weight:bold;">
				<td>{{$count}}</td>
				<td>{{ $member->ic }}</td>
				<td>{{ $member->name }}</td>
				<td>{{ $payable_subs }}</td>
				<td>{{ 0 }}</td>
				
				<td>{{ $payable_subs }}</td>
				
			</tr>
			@php
				$count++;
				$total_previous_subs+=$payable_subs;
			@endphp
			@endforeach
		
		</tbody>
		<tfoot>
			<tr class="table-title">
				<th colspan="3" style="text-align: right;" >Total</th>
				<th>{{ $total_previous_subs }}</th>
				<th>{{ 0 }}</th>
				
				<th>{{ $total_previous_subs }}</th>
				
				
			</tr>
		</tfoot>
		
		
		</table>
		@endif
		@php
			
		@endphp
		@if(count($current_company_members)>0)
		<p style="font-size: 16px;text-decoration: underline;font-size: 16px;font-weight:bold;">Previous Subscription Unpaid - Current Subscription Paid</p>
		<table id="page-lengthone" class="display  page-length-option" width="100%">
		<thead>
			
			<tr class="table-title">
				<th>{{__('S.No')}}</th>
				<th>{{__('NRIC')}}</th>
				<th>{{__('Member Name')}}</th>
				<th>{{ date('M Y',strtotime($data['month_year_full'].' -1 Month')) }}</th>
				<th>{{ date('M Y',strtotime($data['month_year_full'])) }}</th>
				
				<th>{{__('Different')}}</th>
				
				
			</tr>
		</thead>
		<tbody class="tbody-area">
			@foreach($current_company_members as $member)
			@php

				//$salary = $member->salary==Null ? 0 : $member->salary;
				//$total_subs = ($salary*1)/100;
				$total_subs = $member->SUBSCRIPTION_AMOUNT;
				$bf_amt = 3;
				$ins_amt = 7;
				$payable_subs = $total_subs;
			@endphp
		
			
			<tr style="font-weight:bold;">
				<td>{{$countone}}</td>
				<td>{{ $member->ic }}</td>
				<td>{{ $member->name }}</td>
				<td>{{ 0 }}</td>
				<td>{{ $payable_subs }}</td>
				
				<td>{{ $payable_subs }}</td>
				
			</tr>
			@php
				$countone++;
				$total_current_subs+=$payable_subs;
			@endphp
			@endforeach
		
		</tbody>
		<tfoot>
			<tr class="table-title">
				<th colspan="3" style="text-align: right;" >Total</th>
				<th>{{ 0 }}</th>
				<th>{{ $total_current_subs }}</th>
				
				<th>{{ $total_current_subs }}</th>
				
				
			</tr>
		</tfoot>
		
		
		</table>

		@endif

		@if(count($inc_company_members)>0)
		<p style="font-size: 16px;text-decoration: underline;font-size: 16px;font-weight:bold;">Subscription Increment</p>
		<table id="page-lengthone" class="display  page-length-option" width="100%">
		<thead>
			
			<tr class="table-title">
				<th>{{__('S.No')}}</th>
				<th>{{__('NRIC')}}</th>
				<th>{{__('Member Name')}}</th>
				<th>{{ date('M Y',strtotime($data['month_year_full'].' -1 Month')) }}</th>
				<th>{{ date('M Y',strtotime($data['month_year_full'])) }}</th>
				
				<th>{{__('Different')}}</th>
				
				
			</tr>
		</thead>
		<tbody class="tbody-area">
			@foreach($inc_company_members as $member)
			@php
				//dd($member);
			//dd($inc_company_members);
				//$salary = $member->salary==Null ? 0 : $member->salary;
				//$total_subs = ($salary*1)/100;
				$total_subs = $member->SUBSCRIPTION_AMOUNT;
				//$total_subs_old = ($salary*1)/100;
				$bf_amt = 3;
				$ins_amt = 7;
				$payable_subs = $total_subs;
				//$payable_subs = $total_subs-($bf_amt+$ins_amt);

			@endphp
		
			
			<tr style="font-weight:bold;">
				<td>{{$countinc}}</td>
				<td>{{ $member->ic }}</td>
				<td>{{ $member->name }}</td>
				<td>{{ $member->last_amount }}</td>
				<td>{{ $member->Amount }}</td>
				
				<td>{{ number_format($member->Amount-$member->last_amount,2,".","") }}</td>
				
			</tr>
			@php
				$countinc++;
				$total_inc_subs+=$member->Amount;
				$total_inc_subs_old+=$member->last_amount;
				$total_inc_subs_diff+=$member->Amount-$member->last_amount;
			@endphp
			@endforeach
		
		</tbody>
		<tfoot>
			<tr class="table-title">
				<th colspan="3" style="text-align: right;" >Total</th>
				<th>{{ $total_inc_subs_old }}</th>
				<th>{{ $total_inc_subs }}</th>
				
				<th>{{ number_format($total_inc_subs_diff,2,".","") }}</th>
				
				
			</tr>
		</tfoot>
		
		
		</table>

		@endif

		@if(count($dec_company_members)>0)
		<p style="font-size: 16px;text-decoration: underline;font-size: 16px;font-weight:bold;">Subscription Decrement</p>
		<table id="page-lengthone" class="display  page-length-option" width="100%">
		<thead>
			
			<tr class="table-title">
				<th>{{__('S.No')}}</th>
				<th>{{__('NRIC')}}</th>
				<th>{{__('Member Name')}}</th>
				<th>{{ date('M Y',strtotime($data['month_year_full'].' -1 Month')) }}</th>
				<th>{{ date('M Y',strtotime($data['month_year_full'])) }}</th>
				
				<th>{{__('Different')}}</th>
				
				
			</tr>
		</thead>
		<tbody class="tbody-area">
			@foreach($dec_company_members as $member)
			@php
			
				//$salary = $member->salary==Null ? 0 : $member->salary;
				//$total_subs = ($salary*1)/100;
				$total_subs = $member->SUBSCRIPTION_AMOUNT;
				//$total_subs_old = ($salary*1)/100;
				$bf_amt = 3;
				$ins_amt = 7;
				$payable_subs = $total_subs;
				//$payable_subs = $total_subs-($bf_amt+$ins_amt);

			@endphp
		
			
			<tr style="font-weight:bold;">
				<td>{{$countdec}}</td>
				<td>{{ $member->ic }}</td>
				<td>{{ $member->name }}</td>
				<td>{{ $member->last_amount }}</td>
				<td>{{ $member->Amount }}</td>
				
				<td>{{ abs($member->Amount-$member->last_amount) }}</td>
				
			</tr>
			@php
				$countdec++;
				$total_dec_subs+=$member->Amount;
				$total_dec_subs_old+=$member->last_amount;
				$total_dec_subs_diff+=abs($member->Amount-$member->last_amount);
			@endphp
			@endforeach
		
		</tbody>
		<tfoot>
			<tr class="table-title">
				<th colspan="3" style="text-align: right;" >Total</th>
				<th>{{ $total_dec_subs_old }}</th>
				<th>{{ $total_dec_subs }}</th>
				
				<th>{{ $total_dec_subs_diff }}</th>
				
				
			</tr>
		</tfoot>
		
		
		</table>

		@endif

		
		 
	</div>
	
	@endforeach
	
</body>


</html>