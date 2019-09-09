<!DOCTYPE html>
<html>

<head>
	<script src="{{ asset('public/assets/js/jquery-1.12.4.min.js') }}" type="text/javascript"></script>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
		#page-length-option {
		  border-collapse: collapse;
		  width: 100%;
		}

		#page-length-option td, #page-length-option th {
		  border: 1px solid #ddd;
		  padding: 8px;
		}
		
		@media print{
			.export-button{
				display:none;
			}
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
				<td width="10%"></td>
				<td width="50%" style="text-align:center;">NUBE Monthly Subscription {{ date('M Y',strtotime($data['month_year_full'])) }} - Variation Report
					
				</td>
				<td width="20%">	
					<a href="#" class="export-button btn btn-sm" style="background:#ccc;" onClick="window.print()"><i class="material-icons">print</i></a>
				</td>
			</tr>
		</table>
	</div>
	<!-- <div class="page-footer">
    I'm The Footer
  </div>-->
	
	@foreach($data['company_view'] as $company)
	
	<table id="page-length-option" class="display" width="100%">
		<thead>
			
			<tr class="" >
				<th colspan="12">{{ $company->company_name }}</th>
			</tr>
			<tr class="" >
				<th width='5%'>{{__('S.No')}}</th>
				<th width='5%'>{{__('M.No')}}</th>
				<th width='10%'>{{__('Member Name')}}</th>
				<th width='10%'>{{__('Joining')}}</th>
				<th width='5%'>{{__('Last Paid')}}</th>
				<th width='5%'>{{__('Subs')}}</th>
				<th width='10%'>{{ date('M Y',strtotime($data['month_year_full'].' -5 Month')) }}</th>
				<th width='10%'>{{ date('M Y',strtotime($data['month_year_full'].' -4 Month')) }}</th>
				<th width='10%'>{{ date('M Y',strtotime($data['month_year_full'].' -3 Month')) }}</th>
				<th width='10%'>{{ date('M Y',strtotime($data['month_year_full'].' -2 Month')) }}</th>
				<th width='10%'>{{ date('M Y',strtotime($data['month_year_full'].' -1 Month')) }}</th>
				<th width='10%'>{{ date('M Y',strtotime($data['month_year_full'])) }}</th>
			</tr>
		</thead>
		<tbody class="tbody-area" width="100%">
			@php
				$company_members = CommonHelper::getCompanyMembers($company->company_id,$data['month_year_full']);
				$count=1;
				$total_fifth_new=0;
				$total_fourth_new=0;
				$total_third_new=0;
				$total_second_new=0;
				$total_last_new=0;
				$total_resigned=0;
				$total_fifth_unpaid=0;
				$total_fourth_unpaid=0;
				$total_third_unpaid=0;
				$total_second_unpaid=0;
				$total_last_unpaid=0;
				$total_this_unpaid=0;
				$total_fifth_inc=0;
				$total_fouth_inc=0;
				$total_third_inc=0;
				$total_second_inc=0;
				$total_last_inc=0;
				$total_fifth_dec=0;
				$total_fouth_dec=0;
				$total_third_dec=0;
				$total_second_dec=0;
				$total_last_dec=0;
				$total_this_inc=0;
				$total_this_dec=0;
			@endphp
			@foreach($company_members as $member)
			@php
				$salary = $member->salary;
				$total_subs = ($salary*1)/100;
				$bf_amt = 3;
				$ins_amt = 7;
				$payable_subs = $total_subs-($bf_amt+$ins_amt);
				$fifth_amt = CommonHelper::getCompanyPaidSubs($company->company_id, $member->member_id, date('Y-m-d',strtotime($data['month_year_full'].' -5 Month')));
				$fourth_amt = CommonHelper::getCompanyPaidSubs($company->company_id, $member->member_id, date('Y-m-d',strtotime($data['month_year_full'].' -4 Month')));
				$third_amt = CommonHelper::getCompanyPaidSubs($company->company_id, $member->member_id, date('Y-m-d',strtotime($data['month_year_full'].' -3 Month')));
				$second_amt = CommonHelper::getCompanyPaidSubs($company->company_id, $member->member_id, date('Y-m-d',strtotime($data['month_year_full'].' -2 Month')));
				$last_amt = CommonHelper::getCompanyPaidSubs($company->company_id, $member->member_id, date('Y-m-d',strtotime($data['month_year_full'].' -1 Month')));
				$fifth_paid_status = $fifth_amt;
				$fourth_paid_status = $fourth_amt;
				$third_paid_status = $third_amt;
				$second_paid_status = $second_amt;
				$last_paid_status = $last_amt;
				if($fifth_amt=='*'){
					$fifth_paid_status = CommonHelper::getMonthDifference(date('Y-m-d',strtotime($data['month_year_full'].' -5 Month')), $member->doj)>0 ? 'N' : '*';
					if($fifth_paid_status=='N'){
						$total_fifth_new++;
					}else{
						$total_fifth_unpaid++;
					}
				}else{
					$total_fifth_diff = $payable_subs-$fifth_amt;
					if($total_fifth_diff>0){
						$total_fifth_inc++;
					}
					if($total_fifth_diff<0){
						$total_fifth_dec++;
					}
				}
				if($fourth_amt=='*'){
					$fourth_paid_status = CommonHelper::getMonthDifference(date('Y-m-d',strtotime($data['month_year_full'].' -4 Month')), $member->doj)>0 ? 'N' : '*';
					if($fourth_paid_status=='N'){
						$total_fourth_new++;
					}else{
						$total_fourth_unpaid++;
					}
				}else{
					$total_fourth_diff = $payable_subs-$fourth_amt;
					if($total_fourth_diff>0){
						$total_fouth_inc++;
					}
					if($total_fourth_diff<0){
						$total_fouth_dec++;
					}
				}
				if($third_amt=='*'){
					$third_paid_status = CommonHelper::getMonthDifference(date('Y-m-d',strtotime($data['month_year_full'].' -3 Month')), $member->doj)>0 ? 'N' : '*';
					if($third_paid_status=='N'){
						$total_third_new++;
					}else{
						$total_third_unpaid++;
					}
				}else{
					$total_third_diff = $payable_subs-$third_amt;
					if($total_third_diff>0){
						$total_third_inc++;
					}
					if($total_third_diff<0){
						$total_third_dec++;
					}
				}
				if($second_amt=='*'){
					$second_paid_status = CommonHelper::getMonthDifference(date('Y-m-d',strtotime($data['month_year_full'].' -2 Month')), $member->doj)>0 ? 'N' : '*';
					if($second_paid_status=='N'){
						$total_second_new++;
					}else{
						$total_second_unpaid++;
					}
				}else{
					$total_second_diff = $payable_subs-$second_amt;
					if($total_second_diff>0){
						$total_second_inc++;
					}
					if($total_second_diff<0){
						$total_second_dec++;
					}
				}
				if($last_amt=='*'){
					$last_paid_status = CommonHelper::getMonthDifference(date('Y-m-d',strtotime($data['month_year_full'].' -1 Month')), $member->doj)>0 ? 'N' : '*';
					if($last_paid_status=='N'){
						$total_last_new++;
					}else{
						$total_last_unpaid++;
					}
				}else{
					$total_last_diff = $payable_subs-$last_amt;
					if($total_last_diff>0){
						$total_last_inc++;
					}
					if($total_last_diff<0){
						$total_last_dec++;
					}
				}
				$this_paid = $member->SUBSCRIPTION_AMOUNT;
				if($this_paid==Null || $this_paid==0){
					$this_paid = '*';
					$total_this_unpaid++;
				}else{
					$total_this_diff = $payable_subs-$this_paid;
					if($total_this_diff>0){
						$total_this_inc++;
					}
					if($total_this_diff<0){
						$total_this_dec++;
					}
				}
				if($member->STATUS_CODE==4){
					$total_resigned++;
				}
			@endphp
			<tr style="font-weight:bold;">
				<td>{{$count}}</td>
				<td>{{ $member->member_number }}</td>
				<td>{{ $member->name }}</td>
				<td>{{ date('M Y',strtotime($member->doj)) }}</td>
				<td>{{ date('M Y',strtotime($member->LASTPAYMENTDATE)) }}</td>
				<td>{{ $payable_subs }}</td>
				<td>{{ $fifth_paid_status }}</td>
				<td>{{ $fourth_paid_status }}</td>
				<td>{{ $third_paid_status }}</td>
				<td>{{ $second_paid_status }}</td>
				<td>{{ $last_paid_status }}</td>
				<td>{{ $this_paid }}</td>
			</tr>
			@php
				$count++;
			@endphp
			@endforeach
			<tr style="font-weight:bold;">
				<td colspan="6" style="text-align:right;">New Member</td>
				<td>{{$total_fifth_new}}</td>
				<td>{{$total_fourth_new}}</td>
				<td>{{$total_third_new}}</td>
				<td>{{$total_second_new}}</td>
				<td>{{$total_last_new}}</td>
				<td>0</td>
			</tr>
			<tr style="font-weight:bold;">
				<td colspan="6" style="text-align:right;">Resign Member</td>
				<td>{{$total_resigned}}</td>
				<td>{{$total_resigned}}</td>
				<td>{{$total_resigned}}</td>
				<td>{{$total_resigned}}</td>
				<td>{{$total_resigned}}</td>
				<td>{{$total_resigned}}</td>
			</tr>
			<tr style="font-weight:bold;">
				<td colspan="6" style="text-align:right;">Unpaid</td>
				<td>{{$total_fifth_unpaid}}</td>
				<td>{{$total_fifth_unpaid}}</td>
				<td>{{$total_third_unpaid}}</td>
				<td>{{$total_second_unpaid}}</td>
				<td>{{$total_last_unpaid}}</td>
				<td>{{$total_this_unpaid}}</td>
			</tr>
			<tr style="font-weight:bold;">
				<td colspan="6" style="text-align:right;">No Diffrence</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
			</tr>
			<tr style="font-weight:bold;">
				<td colspan="6" style="text-align:right;">Decrement</td>
				<td>{{$total_fifth_dec}}</td>
				<td>{{$total_fouth_dec}}</td>
				<td>{{$total_third_dec}}</td>
				<td>{{$total_second_dec}}</td>
				<td>{{$total_last_dec}}</td>
				<td>{{$total_this_dec}}</td>
			</tr>
			<tr style="font-weight:bold;">
				<td colspan="6" style="text-align:right;">Increment</td>
				<td>{{$total_fifth_inc}}</td>
				<td>{{$total_fouth_inc}}</td>
				<td>{{$total_third_inc}}</td>
				<td>{{$total_second_inc}}</td>
				<td>{{$total_last_inc}}</td>
				<td>{{$total_this_inc}}</td>
			</tr>
		</tbody>
		
		
	</table>
	</br>
	@endforeach
</body>
<script src="{{ asset('public/assets/js/jquery-3.2.1.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/xlsx.core.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/FileSaver.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jspdf.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jspdf_plugin_autotable.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/es6-promise.auto.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/html2canvas.min.js') }}" type="text/javascript"></script>
<!--<![endif]-->
<script type="text/javascript" src="{{ asset('public/assets/js/tableExport.js') }}"></script>
<script>
	
</script>

</html>