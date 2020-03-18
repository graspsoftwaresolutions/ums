@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/vendors/data-tables/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css') }}">


@endsection
@section('headSecondSection')


<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/custom_respon.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/buttons.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/font-awesome.min.css') }}">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/export-button.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/datepicker.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/pages/data-tables.css') }}">
<style type="text/css">
    #main .section-data-tables .dataTables_wrapper table.dataTable tbody th, #main .section-data-tables .dataTables_wrapper table.dataTable tbody td:last-child {
        padding-top: 8px;
        padding-bottom: 8px;
        padding-left: 26px;
        padding-right: 16px;
        font-size: 12px;
        white-space: nowrap;
        text-transform: Uppercase;
        border: none !important;
        border-top: 1px solid lightgrey !important;
    }
   /* .btn-sm{
        padding: 0px 7px;
        font-size: 8px;
        line-height: 1.5;
        border-radius: 3px;
        color: #fff;
    }*/
   /* #page-length-option td:not(:last-child) {
        word-break: break-word !important;
        white-space: unset !important;
        vertical-align: top;
    }*/

</style>
@endsection
@section('main-content')
<div class="row">
	<div style="height:150px !important" class="content-wrapper-before gradient-45deg-indigo-purple"></div>
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
									<h5 class="breadcrumbs-title mt-0 mb-0">{{__('Due Members List') }}</h5>
									<ol class="breadcrumbs mb-0">
										<ol class="breadcrumbs mb-0">
											<li class="breadcrumb-item"><a
													href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard') }}</a>
											</li>
											<li class="breadcrumb-item active">{{__('Due Members') }}
											</li>
									</ol>
								</div>
								<div class="col s2 m6 l6 ">
									
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
	<div class="row">
		<div class="col s12">
			<div class="container">
				<div class="card">
					<div class="card-title">
						@php
							//dd($success);
						@endphp
						@if ($errors->any())
							<div class="card-alert card gradient-45deg-red-pink">
								<div class="card-content white-text">
								  <p>
									<i class="material-icons">check</i> {{ __('Error') }} : {{ implode('', $errors->all(':message')) }}</p>
								</div>
								<button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
								  <span aria-hidden="true">×</span>
								</button>
							 </div>
						@endif
						@if (isset($success))
							<div class=" card gradient-45deg-green-teal">
								<div class="card-content white-text">
								  <p>
									<i class="material-icons">check</i> {{__('SUCCESS') }}: {{ $success }}</p>
								</div>
								
							 </div>
						@endif
					</div>
					@php
						$ref_id = '';
						if($data['type']==1){
							$labelname='Union Branch';
							$typename = CommonHelper::getUnionBranchName($data['union_branchid']);
							$ref_id = $data['union_branchid'];
						}elseif($data['type']==2){
							$labelname='Bank';
							$typename = CommonHelper::getCompanyName($data['company_id']);
							$ref_id = $data['company_id'];
						}else{
							$labelname='Bank Branch';
							$typename = CommonHelper::getBranchName($data['branch_id']);
							$ref_id = $data['branch_id'];
						}
						//dd($data);
					@endphp
					<div class="card-content">
						<div class="row">
							<div class="col s12 m12">
								<div class="row">
									<h4 class="card-title">{{ $labelname }}: {{ $typename }}</h4>
									<h4 class="card-title">Date: {{ date('M Y',strtotime($data['date'])) }}</h4>

								</div>
							</div>
							
						</div>
					</div>
				</div>
			</div>
		</div>
		 <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <h4 class="card-title">{{__('Difference members List') }}</h4>
                    @include('includes.messages')
                    <div class="row">
                        <div class="col s12">
                            <table id="page-length-option" class="display" width="100%">
                                <thead>
                                    <tr>
										<th width="5%">{{__('S.No') }}</th>
                                        <th width="35%">{{__('Member Name') }}</th>
                                        <th width="15%">{{__('Member Number') }}</th>
                                        <th width="10%">{{__('Joining') }}</th>
                                        <th>{{ date('M Y',strtotime($data['date'])) }}</th>
										<th>{{ date('M Y',strtotime($data['date'].' -1 Month')) }}</th>
                                        <th class="hide"> {{__('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
									@php
										$slno = 1;
										//dd($data['type']); 
										$pre_company_members = CommonHelper::getLastMonthlyPaidMembersAll($ref_id,$data['date'],$data['type']);
										$current_company_members = CommonHelper::getcurrentMonthlyPaidMembersAll($ref_id,$data['date'],$data['type']);
									@endphp
									@foreach($pre_company_members as $company)
										<tr>
											<td>{{$slno}}</td>
											<td>{{ $company->name }}</td>
											<td>{{ $company->member_number }}</td>
											<td>{{ $company->doj }}</td>
											<td>0</td>
											<td>{{ number_format($company->SUBSCRIPTION_AMOUNT,2,".",",") }}</td>
											<td class="hide"></td>
										</tr>
										@php
											$slno++;
										@endphp
									@endforeach
									@foreach($current_company_members as $company)
										<tr>
											<td>{{$slno}}</td>
											<td>{{ $company->name }}</td>
											<td>{{ $company->member_number }}</td>
											<td>{{ $company->doj }}</td>
											<td>{{ number_format($company->SUBSCRIPTION_AMOUNT,2,".",",") }}</td>
											<td>0</td>
											<td></td>
										</tr>
										@php
											$slno++;
										@endphp
									@endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
	
</div>

@endsection
@section('footerSection')
<!--script src="{{ asset('public/assets/js/jquery.min.js') }}"></script-->
<script src="{{ asset('public/assets/vendors/data-tables/js/jquery.dataTables.min.js') }}" type="text/javascript">
</script>
<script src="{{ asset('public/assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js') }}"
    type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/data-tables/js/dataTables.select.min.js') }}" type="text/javascript">
</script>
<script src="{{ asset('public/assets/js/scripts/data-tables.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/dataTables.buttons.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/buttons.flash.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jszip.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/pdfmake.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/vfs_fonts.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/buttons.html5.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/buttons.print.min.js') }}" type="text/javascript"></script>

<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/assets/js/materialize.min.js') }}"></script>
<script src="{{ asset('public/assets/js/scripts/form-elements.js') }}" type="text/javascript"></script>

<script src="{{ asset('public/assets/js/datepicker.js') }}"></script>

@endsection
@section('footerSecondSection')


<script>
$(document).ready(function() {
	$('#page-length-option').DataTable({
			"order": [[ 0, "asc" ]],
			"lengthMenu": [
				[10, 25, 50, 100, 3000],
				[10, 25, 50, 100, 'All']
			],
			"responsive": true,
  				 dom: 'lBfrtip',
  				   buttons: [
					   {
						   extend: 'pdf',
			               text:      '<i class="fa fa-file-pdf-o"></i>',
						   footer: true,
						   exportOptions: {
								columns: [0,1,2,3,4,5]
			                },
			                titleAttr: 'pdf',
							title : 'Dues List'
					   },
					   {
			               extend: 'excel',
			               text:      '<i class="fa fa-file-excel-o"></i>',
						   footer: false,
						   exportOptions: {
								columns: [0,1,2,3,4,5]
							},
			                title : 'Dues List',
			                titleAttr: 'excel',
					   },
						{
			               extend: 'print',
			               text:      '<i class="fa fa-files-o"></i>',
						   footer: false,
						   exportOptions: {
								columns: [0,1,2,3,4,5]
							},
			                title : 'Dues List',
			                titleAttr: 'print',
					   }  
					],
		});
    // the "href" attribute of the modal trigger must specify the modal ID that wants to be triggered
  //    $('#page-length-option').DataTable({
  //    	"order": [[ 2, "asc" ]],
  //       "responsive": true,
  // //       dom: 'lBfrtip', 
  // //       buttons: [
		// //    {
		// // 	   extend: 'pdf',
  // //              text:      '<i class="fa fa-file-pdf-o"></i>',
		// // 	   footer: true,
		// // 	   exportOptions: {
		// // 			columns: [0]
  // //               },
  // //               titleAttr: 'pdf',
		// // 		title : 'Countries List'
		// //    },
		// //    {
  // //              extend: 'excel',
  // //              text:      '<i class="fa fa-file-excel-o"></i>',
		// // 	   footer: false,
		// // 	   exportOptions: {
		// // 			columns: [0]
		// // 		},
  // //               title : 'Countries List',
  // //               titleAttr: 'excel',
		// //    },
		// // 	{
  // //              extend: 'print',
  // //              text:      '<i class="fa fa-files-o"></i>',
		// // 	   footer: false,
		// // 	   exportOptions: {
		// // 			columns: [0]
		// // 		},
  // //               title : 'Countries List',
  // //               titleAttr: 'print',
		// //    }  
		// // ],
  //       "processing": true,
  //       "serverSide": true,
  //       "ajax": {
  //           "url": "{{ url(app()->getLocale().'/ajax_history_list') }}",
  //           "dataType": "json",
  //           "type": "POST",
  //           "data": {
  //               _token: "{{csrf_token()}}"
  //           },
  //           "error": function (jqXHR, textStatus, errorThrown) {
  //               if(jqXHR.status==419){
  //                   alert('Your session has expired, please login again');
  //                   window.location.href = base_url;
  //               }
  //           },
  //       },
  //       "columns": [{
  //               "data": "name"
  //           },
  //           {
  //               "data": "member_number"
  //           },
  //           {
  //               "data": "doj"
  //           },
  //           {
  //               "data": "options"
  //           }
  //       ]
  //   });
});


	
	
	
	
	$(document).on('submit','form#subscribe_formValidate',function(){
		// var type = $("#type").val();
		// if(type==1){
		// 	loader.showLoader();
		// }
		$("#submit-download").prop('disabled',true);
	});
	
	$("#data_cleaning_sidebars_id").addClass('active');
	$("#due_sidebar_li_id").addClass('active');
	$("#due_sidebar_a_id").addClass('active');
</script>
@endsection