@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/data-tables/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css') }}">
@endsection
@section('headSecondSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/pages/data-tables.css') }}">
@endsection
@section('main-content')
<div id="">
	<div class="row">
		<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
		<div class="col s12">
			<div class="container">
				<div class="section section-data-tables">
					<!-- BEGIN: Page Main-->
					<div class="row">
						<div class="breadcrumbs-dark" id="breadcrumbs-wrapper">
							<!-- Search for small screen-->
							<div class="container">
								<div class="row">
									<div class="col s10 m6 l6">
										<h5 class="breadcrumbs-title mt-0 mb-0">{{__('IRC Confirmation List') }}</h5>
										<ol class="breadcrumbs mb-0">
										<li class="breadcrumb-item"><a href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard') }}</a>
											<li class="breadcrumb-item active"><a href="#">{{__('IRC Confirmation') }}</a>
											</li>
											
										</ol>
									</div>
									 @php
										$user_id = Auth::user()->id;
										$get_roles = Auth::user()->roles;
										$user_role = $get_roles[0]->slug;
									@endphp
									<div class="col s2 m6 l6 ">
										@if($user_role=='irc-confirmation' )
											<a class="btn waves-effect waves-light breadcrumbs-btn right" href="{{ route('irc.irc', 	app()->getLocale())  }}">{{__('New Confirmation') }}</a>
										@endif
										
									</div>
								</div>
							</div>
						</div>
						<div class="col s12">
							<div class="card">
								<div class="card-content">
									<h4 class="card-title">
										
									</h4>
									@include('includes.messages')
									<div class="row">
										<div class="col s12">
											<table id="page-length-option" class="display">
												<thead>
													<tr>
														<td colspan="4">&nbsp;</td>
														<td colspan="2"><input type="text" id="common-search-input" class="common-search-input " value="" ></td>
														<td>Status</td>
														<td colspan="2" valign="middle">
															<select class="browser-default">
																<option value="">All</option>
																<option value="1">Confirm</option>
																<option value="0">Pending</option>
															</select>
														</td>
														
													</tr>
													<tr>
														<th>{{__('Status') }}</th>
														<th>{{__('memberId')}} </th>
														<th>{{__('Full Name')}}</th>
														<th>{{__('ICNO')}}</th>
														<th>{{__('Bank Name')}}</th>
														<th>{{__('Branch')}}</th>
														<th>{{__('Received')}}</th>
														<th>{{__('Submitted')}}</th>
														<th  style="text-align:center;">{{__('Action') }}</th>
													</tr>
													 
												</thead>
												
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- END: Page Main-->
					@include('layouts.right-sidebar')
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('footerSection')
<script src="{{ asset('public/assets/vendors/data-tables/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/data-tables/js/dataTables.select.min.js') }}" type="text/javascript"></script>
@endsection
@section('footerSecondSection')
<script src="{{ asset('public/assets/js/scripts/data-tables.js') }}" type="text/javascript"></script>
<script>
 $("#member_transfer_sidebar_a_id").addClass('active');
 $(function () {
	  $(".datepicker-cutom").datepicker({
		 autoclose: true,
		 format: "mmm/yyyy"
	  });
    var dataTable =  $('#page-length-option').DataTable({
			"responsive": true,
			"searching": false,
			"lengthMenu": [
				[10, 25, 50, -1],
				[10, 25, 50, "All"]
			],
			"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "{{ url(app()->getLocale().'/ajax_transfer_list/') }}",
				"dataType": "json",
				"type": "POST",
				'data': function(data){
				  var datefilter = $('.transferdate-search-input').val();
				  //console.log(datefilter);
				  // Append to data
				  //data.search['value'] = datefilter;
				  data.datefilter = datefilter;
				  data._token = "{{csrf_token()}}";
			   }
				//"data": {_token: "{{csrf_token()}}", "datefilter" : $('#transferdate-search-input').val()},
			},
			"search-transfer" : 12,
			"columns": [
				{"data" : "member_name"},
				{"data":  "frombank"},
				{"data" : "tobank"},
				{"data" : "transfer_date"},
				{"data" : "transfer_date"},
				{"data" : "transfer_date"},
				{"data" : "transfer_date"},
				{"data" : "transfer_date"},
				{"data":  "options"}
			]
		});
		$("#employee-grid_filter").css("display","none");  // hiding global search box
 
		$('.transferdate-search-input').on( 'keyup click change', function () {
			var i =$(this).attr('id');  // getting column index
			var v =$(this).val();  // getting search input value
			dataTable.draw();
			//dataTable.ajax.reload();
			/* var data = dataTable.data();
			console.log(data);
			dataTable.ajax.reload();
			dataTable.search( v ).draw();
			dataTable.column(3).search(v).draw() */
			//data.push.datefilter( v ).draw();
		} );
	 
		 /* $( ".datepicker" ).datepicker({
			dateFormat: "yy-mm-dd",
			showOn: "button",
			showAnim: 'slideDown',
			showButtonPanel: true ,
			autoSize: true,
			buttonImage: "//jqueryui.com/resources/demos/datepicker/images/calendar.gif",
			buttonImageOnly: true,
			buttonText: "Select date",
			closeText: "Clear"
		}); */
		/* $(document).on("click", ".ui-datepicker-close", function(){
			$('.datepicker').val("");
			dataTable.search( '' ).draw();
		}); */
});
 </script>
@endsection