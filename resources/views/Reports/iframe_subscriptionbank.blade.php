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
			@php $logo = CommonHelper::getLogo(); @endphp
				<td width="20%"></td>
				<td width="10%"><img src="{{ asset('public/assets/images/logo/'.$logo) }}" alt="Membership logo" height="50"></td>
				<td width="50%" style="text-align:center;">NATIONAL UNION BANK OF EMPLOYEES, MALAYSIA
					<br/> 
					<h6 style="text-align:center;">Subscription Bank Report</h6>
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
			<tr class="page-table-header-space">
            <th width="20%">{{__('Bank Name')}}</th>
						<th width="15%">{{__('# Member')}}</th>
						<th width='10%'>{{__('Total Amount')}}</th>
						<th width='15%'>{{__('Active')}}</th>
						<th width='15%'>{{__('Defaulter')}}</th>
						<th width='15%'>{{__('StruckOff')}}</th>
						<th width='15%'>{{__('Resigned')}}</th>
						<th width='15%'>{{__('SundryCr')}}</th>
			</tr>
		</thead>
		@php 
			$get_roles = Auth::user()->roles;
			$user_role = $get_roles[0]->slug;
			$user_id = Auth::user()->id;
			$dateformat = date('Y-m-01');
		@endphp 
		<tbody class="tbody-area" width="100%">
            @foreach($data['company_view'] as $company)
						@php
							$active_amt = CommonHelper::statusMembersCompanyAmount(1, $user_role, $user_id,$company->id, $dateformat);
							$default_amt = CommonHelper::statusMembersCompanyAmount(2, $user_role, $user_id,$company->id, $dateformat);
							$struckoff_amt = CommonHelper::statusMembersCompanyAmount(3, $user_role, $user_id,$company->id, $dateformat);
							$resign_amt = CommonHelper::statusMembersCompanyAmount(4, $user_role, $user_id,$company->id, $dateformat);
							$sundry_amt = CommonHelper::statusSubsCompanyMatchAmount(2, $user_role, $user_id,$company->id, $dateformat);
							
							$total_members = CommonHelper::statusSubsMembersCompanyTotalCount($user_role, $user_id,$company->id,$dateformat);
							$member_sub_link = URL::to(app()->getLocale().'/sub-company-members/'.Crypt::encrypt($company->id));
						@endphp
						<tr class="monthly-sub-status" data-href="{{ $member_sub_link }}">
							<td width="20%">{{ $company->company_name }}</td>
							<td width='15%'>{{ $total_members }}</td>
							<td width='10%'>{{ number_format(($active_amt+$default_amt+$struckoff_amt+$resign_amt+$sundry_amt), 2, '.', ',') }}</td>
							<td width='15%'>{{ number_format($active_amt,2, '.', ',') }}</td>
							<td width='15%'>{{ number_format($default_amt,2, '.', ',') }}</td>
							<td width='15%'>{{ number_format($struckoff_amt,2, '.', ',') }}</td>
							<td width='15%'>{{ number_format($resign_amt,2, '.', ',') }}</td>
							<td width='15%'>{{ number_format($sundry_amt,2, '.', ',') }}</td>
						</tr> 
					@endforeach
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
	 $(window).scroll(function() {   
	   var lastoffset = $("#memberoffset").val();
	   var limit = "{{$data['data_limit']}}";
	   if($(window).scrollTop() + $(window).height() == $(document).height()) {
		    //loader.showLoader();
			var month_year = "{{$data['month_year']}}";
			var company_id = "{{$data['company_id']}}";
			var searchfilters = '&month_year='+month_year+'&company_id='+company_id;
		    $("#memberoffset").val(parseInt(lastoffset)+parseInt(limit));
			$.ajax({
				type: "GET",
				dataType: "json",
				url : "{{ url(app()->getLocale().'/get-subscription-more-report') }}?offset="+lastoffset+searchfilters,
				success:function(res){
					if(result)
					{
						res = result.company_view;
						$.each(res,function(key,entry){
							var new_member_sub_link =base_url+"/{{app()->getLocale()}}/sub-company-members/"+entry.enc_id;
							var table_row = "<tr class='monthly-sub-status' data-href='"+new_member_sub_link+"'><td width='30%'>"+entry.company_name+"</td>";
								table_row += "<td width='20%'>"+entry.total_members+"</td>";
								table_row += "<td width='10%'>"+entry.total_amount+"</td>";
								table_row += "<td width='15%'>"+entry.active_amt+"</td>";
								table_row += "<td width='15%'>"+entry.default_amt+"</td>";
								table_row += "<td width='15%'>"+entry.struckoff_amt+"</td>";
								table_row += "<td width='15%'>"+entry.resign_amt+"</td>";
								table_row += "<td width='15%'>"+entry.sundry_amt+"</td></tr>";
								$('#scroll-vert-hor tbody').append(table_row);
						});
						if(!res){
								var table_row = "<tr><td colspan='6'>No data found</td></tr>";
								$('#scroll-vert-hor tbody').append(table_row);
						}
						loader.hideLoader();
					}else{
						
					}
				}
			});	
	   }
	});
</script>

</html>