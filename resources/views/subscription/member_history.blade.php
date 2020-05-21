
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
								<h5 class="breadcrumbs-title mt-0 mb-0">{{__('History')}}</h5>
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
									
									<td width="25%">{{__('Last paid Date')}}</td>
									<td width="25%"  style="color:{{$member->font_color}}">: 
									
									@if(!empty($data['last_month_record']))
									{{ date('M/ Y',strtotime($data['last_month_record']->LASTPAYMENTDATE)) }}
									@endif
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>	
				<div class="row">
					<div class="col s12">
						<ul class="tabs">
							<li class="tab col m3"><a class="active" id="history1" href="#current_history">Current History</a></li>
							@if( $data['old_member_id']!='')
							<li class="tab col m3"><a href="#previous_history" id="history0">Previous History</a></li>
							@endif
						</ul>
					</div>
					<div id="current_history" class="col s12">
							@if(isset($data['current_member_years']))
					         <ul class="collapsible collapsible-accordion">
					            @foreach($data['current_member_years'] as $year)
					            @php
					            	$memberhistory = CommonHelper::getMonthendHistory($data['member_id'],$year->years);
					            	$slno=1;
					            @endphp
					            @if($year->years==date('Y'))
					            <li class="active">
					               <div class="collapsible-header"><i class="material-icons">perm_contact_calendar</i> {{$year->years}}</div>
					               <div class="collapsible-body">
					                  <table id="page-current-history{{$year->years}}" class="display ">
											<thead>
												<tr>
													<th>{{__('S.No')}}</th>
													<th>{{__('History Date')}}</th>
													<th>{{__('Subs.Paid')}}</th>
													<th>{{__('BF Paid')}}</th>
													<th>{{__('Ins.Paid')}}</th>
													<th>{{__('Month.Paid')}}</th>
													<th>{{__('LastPaymentDate')}}</th>
													<th>{{__('Advance')}}</th>
													<th>{{__('Tot.Mon.Paid')}}</th>
													<th>{{__('Tot.Mon.Due')}}</th>
													<th>{{__('Total')}}</th>
													<th>{{__('AccSubs')}}</th>
													<th>{{__('AccBF')}}</th>
													<th>{{__('AccIns')}}</th>
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
													<td>{{ $history->ENTRYMODE=='AD' ? 1 : '' }}</td>
													<td>{{ $history->TOTALMONTHSPAID }}</td>
													<td>{{ $history->TOTALMONTHSDUE }}</td>
													<td>{{ $history->TOTALMONTHSDUE+$history->TOTALMONTHSPAID }}</td>
													<td>{{ $history->ACCSUBSCRIPTION }}</td>
													<td>{{ $history->ACCBF }}</td>
													<td>{{ $history->ACCINSURANCE }}</td>
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
					               </div>
					            </li>
					            @else
					            <li>
					               <div class="collapsible-header"><i class="material-icons">perm_contact_calendar</i> {{$year->years}}</div>
					               <div class="collapsible-body">
					                  	 <table id="page-current-history{{$year->years}}" class="display ">
											<thead>
												<tr>
													<th>{{__('S.No')}}</th>
													<th>{{__('History Date')}}</th>
													<th>{{__('Subs.Paid')}}</th>
													<th>{{__('BF Paid')}}</th>
													<th>{{__('Ins.Paid')}}</th>
													<th>{{__('Month.Paid')}}</th>
													<th>{{__('LastPaymentDate')}}</th>
													<th>{{__('Advance')}}</th>
													<th>{{__('Tot.Mon.Paid')}}</th>
													<th>{{__('Tot.Mon.Due')}}</th>
													<th>{{__('Total')}}</th>
													<th>{{__('AccSubs')}}</th>
													<th>{{__('AccBF')}}</th>
													<th>{{__('AccIns')}}</th>
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
													<td>{{ $history->ENTRYMODE=='AD' ? 1 : '' }}</td>
													<td>{{ $history->TOTALMONTHSPAID }}</td>
													<td>{{ $history->TOTALMONTHSDUE }}</td>
													<td>{{ $history->TOTALMONTHSDUE+$history->TOTALMONTHSPAID }}</td>
													<td>{{ $history->ACCSUBSCRIPTION }}</td>
													<td>{{ $history->ACCBF }}</td>
													<td>{{ $history->ACCINSURANCE }}</td>
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
					               </div>
					            </li>
					            @endif
					            @endforeach
					         </ul>
					        @endif
					    
						<div class="card hide">
							@php
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
										<th>{{__('Advance')}}</th>
										<th>{{__('Tot.Mon.Paid')}}</th>
										<th>{{__('Tot.Mon.Due')}}</th>
										<th>{{__('Total')}}</th>
										<th>{{__('AccSubs')}}</th>
										<th>{{__('AccBF')}}</th>
										<th>{{__('AccIns')}}</th>
										<!-- <th>{{__('DueSubs')}}</th> -->
									</tr>
								</thead>
								<tbody>
									@foreach($data['current_member_history'] as $history)
									<tr style="color:{{$history->font_color}}">
										<td>{{$slno}}</td>
										<td>{{ date('M/ Y',strtotime($history->StatusMonth)) }} @if($history->arrear_status==1) <span style="background-color: #5d3fa0;color: #fff;padding: 2px;border-radius: 5%;">Arrear</span>@endif</td>
										<td>{{ $history->SUBSCRIPTION_AMOUNT }}</td>
										<td>{{ $history->BF_AMOUNT }}</td>
										<td>{{ $history->INSURANCE_AMOUNT }}</td>
										<td>{{ $history->TOTAL_MONTHS }}</td>
										<td>{{ date('M/ Y',strtotime($history->LASTPAYMENTDATE)) }}</td>
										<td>{{ $history->ENTRYMODE=='AD' ? 1 : '' }}</td>
										<td>{{ $history->TOTALMONTHSPAID }}</td>
										<td>{{ $history->TOTALMONTHSDUE }}</td>
										<td>{{ $history->TOTALMONTHSDUE+$history->TOTALMONTHSPAID }}</td>
										<td>{{ $history->ACCSUBSCRIPTION }}</td>
										<td>{{ $history->ACCBF }}</td>
										<td>{{ $history->ACCINSURANCE }}</td>
										<!-- <td style="background: #f2f2f2;">{{ $history->SUBSCRIPTIONDUE }}</td> -->
										
									</tr> 
									@php
										$slno++;
									@endphp
									@endforeach
									@if(count($data['current_member_history'])==0)
										<tr>
											<td colspan="13">NO DATA AVAILABLE</td>
										</tr> 
									@endif
								</tbody>
							</table>
							<input type="text" name="historyoffset" id="historyoffset" class="hide" value="{{$data['data_limit']}}"></input>
							<input type="text" name="totalhistory" id="totalhistory" class="hide" value="{{$slno}}"></input>
							<input type="text" name="resourcefree" id="resourcefree" class="hide" value="1"></input>
						</div>
					</div>
					@if( $data['old_member_id']!='')
					<div id="previous_history" class="col s12">
						<div class="card">
							@php
								$slno1=1;
							@endphp
							<table id="page-previous-history" class="display ">
								<thead>
									<tr>
										<th>{{__('S.No')}}</th>
										<th>{{__('History Date')}}</th>
										<th>{{__('Subs.Paid')}}</th>
										<th>{{__('BF Paid')}}</th>
										<th>{{__('Ins.Paid')}}</th>
										<th>{{__('Month.Paid')}}</th>
										<th>{{__('LastPaymentDate')}}</th>
										<th>{{__('Advance')}}</th>
										<th>{{__('Tot.Mon.Paid')}}</th>
										<th>{{__('Tot.Mon.Due')}}</th>
										<th>{{__('Total')}}</th>
										<th>{{__('AccSubs')}}</th>
										<th>{{__('AccBF')}}</th>
										<th>{{__('AccIns')}}</th>
									</tr>
								</thead>
								<tbody>
									@foreach($data['previous_member_history'] as $history)
									<tr style="color:{{$history->font_color}}">
										<td>{{$slno1}}</td>
										<td>{{ date('M/ Y',strtotime($history->StatusMonth)) }}</td>
										<td>{{ $history->SUBSCRIPTION_AMOUNT }}</td>
										<td>{{ $history->BF_AMOUNT }}</td>
										<td>{{ $history->INSURANCE_AMOUNT }}</td>
										<td>{{ $history->TOTAL_MONTHS }}</td>
										<td>{{ date('M/ Y',strtotime($history->LASTPAYMENTDATE)) }}</td>
										<td>{{ $history->ENTRYMODE=='AD' ? 1 : '' }}</td>
										<td>{{ $history->TOTALMONTHSPAID }}</td>
										<td>{{ $history->TOTALMONTHSDUE }}</td>
										<td>{{ $history->TOTALMONTHSDUE+$history->TOTALMONTHSPAID }}</td>
										<td>{{ $history->ACCSUBSCRIPTION }}</td>
										<td>{{ $history->ACCBF }}</td>
										<td>{{ $history->ACCINSURANCE }}</td>
										
									</tr> 
									@php
										$slno1++;
									@endphp
									@endforeach
									@if(count($data['previous_member_history'])==0)
										<tr>
											<td colspan="13">NO DATA AVAILABLE</td>
										</tr> 
									@endif
								</tbody>
							</table>
							<input type="text" name="previoushistoryoffset" id="previoushistoryoffset" class="hide" value="{{$data['data_limit']}}"></input>
							<input type="text" name="previoustotalhistory" id="previoustotalhistory" class="hide" value="{{$slno1}}"></input>
							
						</div>
					</div>
					@endif
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
$("#subscriptions_sidebars_id").addClass('active');
$("#subscomp_sidebar_li_id").addClass('active');
$("#subcomp_sidebar_a_id").addClass('active');

//Data table Ajax call
$(function() {
	// $(window).scroll(function() {
	// 	//alert($(document).height());
	// 	console.log('doc-height'+$(document).height());
	// 	console.log('top'+$(window).scrollTop());
	// 	console.log('window-height'+$(window).height());
	// 	if($(window).scrollTop() + $(window).height() > $(document).height() - 10 && $("#resourcefree").val()==1) {
	// 	//if($(window).scrollTop() + $(window).height() > $("#page-current-history").height()) {
	// 	//if($(window).scrollTop() == $(document).height() - $(window).height()) {
	// 		loader.showLoader();
	// 		$("#resourcefree").val(0);
	// 		var active_tab_id = $( "ul.tabs" ).find( "a.active" ).attr('id');
	// 		if(active_tab_id=='history1'){
	// 			var lastoffset = $("#historyoffset").val();
	// 			var limit = "{{$data['data_limit']}}";
	// 			var memberid = "{{$data['member_id']}}";
	// 			$("#historyoffset").val(parseInt(lastoffset)+parseInt(limit));
	// 			var reflect_table = 'page-current-history';
	// 			var reflect_serial_text = 'totalhistory';
	// 			var load_type = 1;
	// 			var totalhistory = parseInt($("#totalhistory").val());
	// 		}else{
	// 			var lastoffset = $("#previoushistoryoffset").val();
	// 			var limit = "{{$data['data_limit']}}";
	// 			$("#previoushistoryoffset").val(parseInt(lastoffset)+parseInt(limit));
	// 			var memberid = "{{$data['old_member_id']}}";
	// 			var reflect_table = 'page-previous-history';
	// 			var load_type = 0;
	// 			var totalhistory = parseInt($("#previoustotalhistory").val());
	// 			var reflect_serial_text = 'previoustotalhistory';
	// 		}
	// 		$.ajax({
	// 			type: "GET",
	// 			dataType: "json",
	// 			url : "{{ URL::to('/en/get-members-history') }}?offset="+lastoffset+"&member_id="+memberid+"&load_type="+load_type,
	// 			success:function(result){
	// 				if(result)
	// 				{
	// 					res = result.member_history;
						
	// 					$.each(res,function(key,entry){
	// 						var arrear_lbl = '';
	// 						if(entry.arrear_status==1) {
	// 							arrear_lbl = '<span style="background-color: #5d3fa0;color: #fff;padding: 2px;border-radius: 5%;">Arrear</span>';
	// 						}
	// 						var table_row = "<tr style='color:"+entry.font_color+";'><td>"+totalhistory+"</td>";
	// 							table_row += "<td>"+entry.StatusMonth+" "+arrear_lbl+"</td>";
	// 							table_row += "<td>"+entry.SUBSCRIPTION_AMOUNT+"</td>";
	// 							table_row += "<td>"+entry.BF_AMOUNT+"</td>";
	// 							table_row += "<td>"+entry.INSURANCE_AMOUNT+"</td>";
	// 							table_row += "<td>"+entry.TOTAL_MONTHS+"</td>";
	// 							table_row += "<td>"+entry.LASTPAYMENTDATE+"</td>";
	// 							table_row += "<td>"+entry.TOTALMONTHSPAID+"</td>";
	// 							table_row += "<td>"+entry.TOTALMONTHSDUE+"</td>";
	// 							table_row += "<td>"+entry.total+"</td>";
	// 							table_row += "<td>"+entry.ACCSUBSCRIPTION+"</td>";
	// 							table_row += "<td>"+entry.ACCBF+"</td>";
	// 							table_row += "<td>"+entry.ACCINSURANCE+"</td></tr>";
	// 							//table_row += "<td style='background: #f2f2f2;'>"+entry.SUBSCRIPTIONDUE+"</td></tr>";
	// 							$('#'+reflect_table+' tbody').append(table_row);
	// 						totalhistory+=1;
	// 					});
	// 					$("#"+reflect_serial_text).val(totalhistory);
	// 					loader.hideLoader();
	// 					$("#resourcefree").val(1);
	// 				}else{
						
	// 				}
	// 			}
	// 		});
	// 	}
	// 	// alert('ok');
	// });	   
});


</script>
@endsection