<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Resign Report</title>
	<style>
		.company-details{
			text-align:center;
			font-weight: 900;
			font-size:15px;
		}
		.page{
			padding:30px;
		}
		.align-right{
			text-align:right;
		}
		.sign-top{
			padding-top:90px
		}
		.member-info{
			font-size:13px;
		}
		.member-info td{
			padding-top:5px;
		}
		.payment-info td{
			border-right: 2px dotted grey;
			border-collapse: separate;
			border-spacing: 2px;
			padding-right:10px;
		}
		.payment-info td{
			padding-top:7px;
		}
		.payment-info{
			font-size:14px;
		}
		body {
			font-family: Arial, Helvetica, sans-serif;
		}
	</style>
</head>
<body>
	<div class="page">
		<div class="company-details">
			<p class="address-lines">N.U.B.E</span>
			<p class="address-lines">12,NUBE HOUSE</span>
			<p class="address-lines">JALAN TUN SAMBANTHAN3,</span>
			<p class="address-lines">BRICKFIELDS</span>
			<p class="address-lines">MALAYSIA</span>
		</div>
		<h5 style="margin-bottom: 0;">PAYMENT VOUCHER</h5>
		<p style="text-align:center;padding-top:0; margin-top: 0;">N.U.B.E BENEVOLENT FUND</p>
		<table width="100%" class="member-info">
			<tr>
				<td width="20%">MEMBER NAME</td>
				<td width="1%">:</td>
				<td width="39%">{{ $member_data->name }}</td>
				<td width="20%">DATE</td>
				<td width="1%">:</td>
				<td width="19%">{{ date('d/M/Y',strtotime($resign_data->voucher_date)) }}</td>
			</tr>
			<tr>
				<td>MEMBERSHIP NO</td>
				<td>:</td>
				<td>{{ $member_data->member_number }}</td>
				<td>BANK CODE</td>
				<td>:</td>
				<td>{{ CommonHelper::getBankCode($member_data->branch_id) }}</td>
			</tr>
			<tr>
				<td>BRANCH CODE</td>
				<td>:</td>
				<td>{{ CommonHelper::getBranchShortCode($member_data->branch_id) }}</td>
				<td>CLAIMER NAME</td>
				<td>:</td>
				<td>{{ $resign_data->claimer_name }}</td>
			</tr>
			<tr>
				<td>IC NO</td>
				<td>:</td>
				<td>
					@if($member_data->new_ic!='')
					{{  $member_data->new_ic  }}
					@elseif($member_data->old_ic!='')
					{{  $member_data->old_ic  }}
					@else
					{{  $member_data->employee_id  }}
					@endif
				</td>
				<td>RESIGNATION DATE</td>
				<td>:</td>
				<td>{{ date('d/M/Y',strtotime($resign_data->resignation_date)) }}</td>
			</tr>
			<tr>
				<td>REASON FOR CLAIM</td>
				<td>:</td>
				<td colspan="3">{{ CommonHelper::getircreason_byid($resign_data->reason_code) }}</td>
			</tr>
		</table>
		<?php
			$resdatetime = strtotime($resign_data->resignation_date);
			$maydate = strtotime('2017-05-31');
			$tilldate = date('d/M/Y',strtotime('2017-05-31'));
			if($resdatetime<=$maydate){
				$tilldate = date('d/M/Y',strtotime($resign_data->resignation_date));
			}
		?>
		</br>
		<table width="100%" class="payment-info" style="border: 2px dotted grey;border-left:none; dotted;border-right:none;">
			<tr>
				<td width="60%" align="center">REFUND OF CONTRIBUTIONS: </td>
				<td width="20%" align="center">AMOUNT</td>
				<td width="20%" align="center">TOTAL</td>
			</tr>
			<tr>
				<td class="align-right"> @if($resign_data->months_contributed_till_may*3>0) PAID FROM {{ date('d/M/Y',strtotime($member_data->doj)) }} TO {{ $tilldate }} @endif</td>
				<td class="align-right"></td>
				<td class="align-right">{{ number_format($resign_data->amount,2,".",",") }}</td>
			</tr>
			@if($resign_data->months_contributed_till_may*3>0)
			<tr>
				<td class="align-right">@RM {{ 3 }} PER MONTH (RM {{ 3 }} x {{ $resign_data->months_contributed_till_may }} )</td>
				<td class="align-right">{{ number_format($resign_data->months_contributed_till_may*3,2,".",",") }}</td>
				<td class="align-right"></td>
			</tr>
			@endif
			@if($resign_data->accbenefit>0)
			<tr>
				<td class="align-right">BENEFIT PAYABLE FROM UNION : {{ $resign_data->benefit_year }} YEARS</td>
				<td class="align-right">{{ number_format($resign_data->accbenefit,2,".",",") }}</td>
				<td class="align-right"></td>
			</tr>
			@endif
			@php
				$member_dojstr = strtotime($member_data->doj);
				//echo $member_dojstr;
				$insdatestr = strtotime(date('2017-06-01'));
				//dd($insdatestr);
				$data = CommonHelper::getInsuranceData($member_data->id)[0];
				$reasonname = CommonHelper::getircreason_byid($resign_data->reason_code);
			@endphp
			@if($resign_data->insuranceamount>0)
			<tr>
				<td class="align-right">
					@if($reasonname=='RETIRED' || $reasonname=='DECEASED')
						@php
							$months = CommonHelper::getMonthendPaidMonths($member_data->id);
							//dd($data);
						@endphp
						INSURANCE [ {{date('M Y',strtotime($months[0]))}} - {{ date('M Y',strtotime($months[count($months)-1]))}} ]( {{$data->count}} Months )
					@else
						INSURANCE AMOUNT @if($data->count!=0)(RM {{ 3 }} x  {{$data->count}} )@endif
					@endif
				</td>
				<td class="align-right">{{ number_format($resign_data->insuranceamount,2,".",",") }}</td>
				<td class="align-right"></td>
			</tr>
			@endif
		</table>
		<table width="100%" class="signature-info">
			<tr>
				<td width="30%">Payment Authorized By</td>
				<td width="40%" align="center"></td>
				<td width="30%" align="center">Payment Make By</td>
			</tr>
			<tr>
				<td class="sign-top">President</td>
				<td class="sign-top" align="center">Hon Gen Secretary</td>
				<td class="sign-top" align="center">Hon Treasurer</td>
			</tr>
		</table>
		
	</div>
</body>
</body>
</html>