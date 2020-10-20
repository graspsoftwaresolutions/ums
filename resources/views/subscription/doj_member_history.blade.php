
@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css"
href="{{ asset('public/assets/vendors/data-tables/css/jquery.dataTables.min.css') }}"> 
<link rel="stylesheet" type="text/css"
href="{{ asset('public/assets/vendors/data-tables/css/select.dataTables.min.css') }}"> 
<link rel="stylesheet" type="text/css"
href="{{ asset('public/assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css') }}">
@endsection
@section('headSecondSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/pages/data-tables.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/custom_respon.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/buttons.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/font-awesome.min.css') }}">
<style>
	.memberinfotable tr>td{
		font-weight: bold;
		font-size: 16px;
	}
</style>
@endsection
@section('main-content')

<div class="row">
	<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
	<div class="col s12">
		<div class="container">
			<!-- BEGIN: Page Main-->
			<div class="row">
				<div class="breadcrumbs-dark" id="breadcrumbs-wrapper">
					<!-- Search for small screen-->
					<div class="container">
						<div class="row">
							<div class="col s10 m6 l6">
								<h5 class="breadcrumbs-title mt-0 mb-0">{{__('Before DOJ History')}}</h5>
								<ol class="breadcrumbs mb-0">
									<li class="breadcrumb-item"><a
											href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard')}}</a>
									</li>
									<li class="breadcrumb-item active">{{__('History')}}
									</li>
								</ol>
							</div>
							<div class="col s2 m6 l6 ">
								   <a class="btn waves-effect waves-light breadcrumbs-btn right" href="{{route('master.membership',app()->getLocale())}}">{{__('Back') }}</a>
							   </div>
						</div>
					</div>
				   
				</div>
			</div>
			<div class="row">
				<div class="col s12">
					<div class="card">
						@php
							$member = $data['member_details'];
							$dojmonth = date('Y-m-01',strtotime($member->doj));
						@endphp
						<div class="card-content">
							<h4 class="card-title">{{__('Member Details')}}  </h4> 
							<table width="100%" class="memberinfotable" style="font-weight: bold; font-size: 16px">
								<tr>
									<td width="25%">{{__('Member Name ')}}</td>
									<td width="25%" style="color:{{$member->font_color}}">: {{ $member->membername }} [{{ $member->member_number }}]</td>
									<td width="25%">{{ __('NRIC-OLD')}}</td>
									<td width="25%"  style="color:{{$member->font_color}}">: {{ $member->old_ic }}</td>
								</tr>
								<tr>
									<td width="25%">{{__('NRIC-NEW')}}</td>
									<td width="25%"  style="color:{{$member->font_color}}">: {{ $member->new_ic }}</td>
									<td width="25%">{{__('Bank')}}</td>
									<td width="25%"  style="color:{{$member->font_color}}">: {{ $member->company_name }}</td>
									
								</tr>
								<tr>
									<td width="25%">{{__('Type')}}</td>
									<td width="25%"  style="color:{{$member->font_color}}">: {{ $member->membertype }}</td>
									
									<td width="25%">{{__('Status')}}</td>
									<td width="25%"  style="color:{{$member->font_color}}">: {{ $member->status_name }}</td>
								</tr>
								<tr>
									<td width="25%">{{__('Date of joing')}}</td>
									<td width="25%"  style="color:{{$member->font_color}}">: {{ date('d/M/Y',strtotime($member->doj)) }}</td>
									
									
								</tr>
							</table>
						</div>
					</div>
				</div>	
				<div class="row">
					
					<div id="current_history" class="col s12">
						<div class="card">
							<div class="card-content">
								<p class="orange" style="color: #fff;padding: 5px;">
								Note : Please verify DOJ before deletion
								</p>
								<br>	
								@php
					            	$memberhistory = $data['member_history'];
					            	$slno=1;
					            @endphp
								<table id="page-current-history" class="display ">
									<thead>
										<tr>
											<th>{{__('S.No')}}</th>
											<th>{{__('History Date')}}</th>
											<th>{{__('Subs.Paid')}}</th>
											<th>{{__('BF Paid')}}</th>
											<th>{{__('Ins.Paid')}}</th>
											<th>{{__('Month.Paid')}}</th>
											<th>{{__('LastPaymentDate')}}</th>
											<th>{{__('Tot.Mon.Paid')}}</th>
											<th>{{__('Tot.Mon.Due')}}</th>
											<th>{{__('Total')}}</th>
											<th>{{__('AccSubs')}}</th>
											<th>{{__('AccBF')}}</th>
											<th>{{__('AccIns')}}</th>
											<th></th>
											<!-- <th>{{__('DueSubs')}}</th> -->
										</tr>
									</thead>
									<tbody>
										@foreach($memberhistory as $history)
										<tr style="color:{{$history->font_color}}">
											<td>{{$slno}}</td>
											<td>{{ date('M/ Y',strtotime($history->StatusMonth)) }} @if($history->arrear_status==1) <span style="background-color: #5d3fa0;color: #fff;padding: 2px;border-radius: 5%;">Arrear</span>@endif</td>
											<td>{{ $history->SUBSCRIPTION_AMOUNT }}</td>
											<td>{{ $history->BF_AMOUNT }}</td>
											<td>{{ $history->INSURANCE_AMOUNT }}</td>
											<td>{{ $history->TOTAL_MONTHS }}</td>
											<td>{{ date('M/ Y',strtotime($history->LASTPAYMENTDATE)) }}</td>
											<td>{{ $history->TOTALMONTHSPAID }}</td>
											<td>{{ $history->TOTALMONTHSDUE }}</td>
											<td>{{ $history->TOTALMONTHSDUE+$history->TOTALMONTHSPAID }}</td>
											<td>{{ $history->ACCSUBSCRIPTION }}</td>
											<td>{{ $history->ACCBF }}</td>
											<td>{{ $history->ACCINSURANCE }}</td>
											<td> @if($dojmonth!=$history->StatusMonth) <i class='material-icons' style='color:red;'>check</i> @endif</td>
											
											<!-- <td style="background: #f2f2f2;">{{ $history->SUBSCRIPTIONDUE }}</td> -->
											
										</tr> 
										@php
											$slno++;
										@endphp
										@endforeach
										@if(count($memberhistory)==0)
											<tr>
												<td colspan="13">NO DATA AVAILABLE</td>
											</tr> 
										@endif
									</tbody>
								</table>
								<br>
								<div id="current_history" class="col s8">
									
								</div>
								<div id="current_history" class="col s4 right">
										Delete before DOJ History ( <i class='material-icons' style='color:red;padding-top: 5px;'>check</i> rows will be deleted)
										<a class="btn btn-sm btn-red" ><form style='display:inline-block;' action="{{ route('beforedoj.delete', [app()->getLocale()]) }}" method='POST'>
											<input type="hidden" name="_method" value="DELETE">
											<input type="hidden" name="memberid" value="{{ $member->memberid }}">
    										<input type="hidden" name="_token" value="{{ csrf_token() }}">
										<button  type='submit' class='' style='background:none;border:none;'  onclick='return ConfirmDeletion()'>Delete</button> </form></a>
									
								</div>
								<br>
					    	</div>
					    </div>
					</div>
					
				</div>
				</br>
			</div>
			
		</div>	
	</div>
</div>
@endsection
@section('footerSection')
<script src="{{ asset('public/assets/vendors/data-tables/js/jquery.dataTables.min.js') }}"
type="text/javascript"></script>
<script
src="{{ asset('public/assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js') }}"
type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/data-tables/js/dataTables.select.min.js') }}"
type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/assets/js/scripts/form-validation.js')}}" type="text/javascript"></script>
@endsection
@section('footerSecondSection')
<script src="{{ asset('public/assets/js/scripts/data-tables.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/dataTables.buttons.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/buttons.flash.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jszip.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/pdfmake.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/vfs_fonts.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/buttons.html5.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/buttons.print.min.js') }}" type="text/javascript"></script>
<script>
$("#data_cleaning_sidebars_id").addClass('active');
$("#beforedoj_history_sidebar_li_id").addClass('active');
$("#beforedoj_history_sidebar_a_id").addClass('active');

//Data table Ajax call
$(function() {
	
});
function ConfirmDeletion(){
	if (confirm("{{ __('Are you sure you want to delete?') }}")) {
        return true;
    } else {
        return false;
    }
}

</script>
@endsection