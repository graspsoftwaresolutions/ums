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
    #page-length-option td:not(:last-child) {
        word-break: break-word !important;
        white-space: unset !important;
        vertical-align: top;
    }
    .btn, .btn-large, .btn-small {
	    margin: 2px !important;
	}

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
								<div class="col s10 m6 l6" style="color: #fff !important;">
									<h5 class="breadcrumbs-title mt-0 mb-0" style="color: #fff !important;">{{__('Advance Due List') }}[Dec/2019]</h5>
									<ol class="breadcrumbs mb-0">
										<ol class="breadcrumbs mb-0" style="color: #fff !important;">
											<li class="breadcrumb-item" style="color: #fff !important;"><a
													href="{{ route('home', app()->getLocale())  }}"  style="color: #fff !important;" >{{__('Dashboard') }}</a>
											</li>
											<li class="breadcrumb-item active">{{__('Advance Due List') }}
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
	@php
		
		$userid = Auth::user()->id;
		$get_roles = Auth::user()->roles;
		$user_role = $get_roles[0]->slug;
		$companylist = [];
		$branchlist = [];
		$companyid = '';
		$branchid = '';
		$unionbranchid='';

	@endphp
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
					<div class="card-content">
						
					</div>
				</div>
			</div>
		</div>
		 <div class="col s12">
            <div class="card">
                <div class="card-content">

                    <h4 class="card-title">{{__('Advance Due List') }}[Dec/2019]
                	</h4>
                    @include('includes.messages')
                    <div class="row">
                        <div class="col s12">
                            <table id="page-length-option" class="display" width="100%">
                                <thead>
                                    <tr>
                                    	<th width="3%">{{__('S.No') }}</th>
                                    	
                                        <th width="15%">{{__('Member Name') }}</th>
                                        <th width="10%">{{__('M/No') }}</th>
                                        <th width="20%">{{__('Bank') }}</th>
                                    	<th width="20%">{{__('Bank Branch') }}</th>
                                        <th width="10%">{{__('DOJ') }}</th>
                                        <th width="5%">{{__('Status') }}</th>
                                        <th width="5%">{{__('Dues') }}</th>

                                        <th> {{__('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	@php
										$slno = 1;
										$statuslist = $data['status'];

									@endphp
                                	@foreach($data['members_list'] as $membersdata)
                                		@php
	                                		$members = CommonHelper::getAllMemberDetails($membersdata->MEMBER_CODE);
                                		@endphp
                                		
                                		<tr>
                                			<td>{{ $slno }}</td>
                                			
                                			<td>{{ $members->name }}</td>
                                			<td>{{ $members->member_number }}</td>
                                			<td>{{ $members->company_name }}</td>
                                			<td>{{ $members->branch_name }}</td>
                                			<td>{{ date('d/M/Y',strtotime($members->doj)) }}</td>
                                			<td>{{ $statuslist[$members->status_id-1]->status_name }}</td>
                                			<td>{{ $membersdata->TOTALMONTHSDUE }}</td>
                                			<td>
                                				<a class='waves-effect waves-light btn btn-sm' href='{{ route("monthend.viewlists", [app()->getLocale(),Crypt::encrypt($members->id)]) }}'>Update</a>
                                				<a style='' title='History'  class='waves-effect waves-light blue btn btn-sm' href='{{ route("member.history", [app()->getLocale(),Crypt::encrypt($members->id)]) }}'>View</a>
                                			</td>
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
@php	
	$ajaxcompanyid = '';
	$ajaxbranchid = '';
	$ajaxunionbranchid = '';
	if(!empty(Auth::user())){
		$userid = Auth::user()->id;
		
		if($user_role =='union'){

		}else if($user_role =='union-branch'){
			$ajaxunionbranchid = CommonHelper::getUnionBranchID($userid);
		}else if($user_role =='company'){
			$ajaxcompanyid = CommonHelper::getCompanyID($userid);
		}else if($user_role =='company-branch'){
			$ajaxbranchid = CommonHelper::getCompanyBranchID($userid);
		}else{

		}
	}
@endphp
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
	//$('#page-length-option').DataTable({"order": [[ 1, "asc" ]]});
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
								columns: [0,1,2,3,4,5,6]
			                },
			                titleAttr: 'pdf',
							title : 'Unpaid List'
					   },
					   {
			               extend: 'excel',
			               text:      '<i class="fa fa-file-excel-o"></i>',
						   footer: false,
						   exportOptions: {
								columns: [0,1,2,3,4,5,6]
							},
			                title : 'Unpaid List',
			                titleAttr: 'excel',
					   },
						{
			               extend: 'print',
			               text:      '<i class="fa fa-files-o"></i>',
						   footer: false,
						   exportOptions: {
								columns: [0,1,2,3,4,5,6]
							},
			                title : 'Unpaid List',
			                titleAttr: 'print',
					   }  
					],
		});
	$('#company_id').change(function(){
		   var CompanyID = $(this).val();
		   var ajaxunionbranchid = '{{ $ajaxunionbranchid }}';
		   var ajaxbranchid = '{{ $ajaxbranchid }}';
		   var additional_cond;
		   if(CompanyID!='' && CompanyID!='undefined')
		   {
			 additional_cond = '&unionbranch_id='+ajaxunionbranchid+'&branch_id='+ajaxbranchid;
			 $.ajax({
				type: "GET",
				dataType: "json",
				url : "{{ URL::to('/get-branch-list-register') }}?company_id="+CompanyID+additional_cond,
				success:function(res){
					if(res)
					{
						$('#branch_id').empty();
						$("#branch_id").append($('<option></option>').attr('value', '').text("Select"));
						$.each(res,function(key,entry){
							$('#branch_id').append($('<option></option>').attr('value',entry.id).text(entry.branch_name)); 
						});
					}else{
						$('#branch_id').empty();
					}
				}
			 });
		   }else{
			   $('#branch_id').empty();
			   $("#branch_id").append($('<option></option>').attr('value', '').text("Select"));
		   }
		  
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
$('.datepicker,.datepicker-custom').datepicker({
    format: 'dd-mm-yyyy',
    autoHide: true,
});

	
	$("#subscribe_formValidate").validate({
       rules: {
			from_date: {
				required: true,
			},
			to_date: {
				required: true,
			},
		},
		  //For custom messages
		  messages: {
				from_date:{
					required: "Enter From Date"
				},
				to_date:{
					required: "Enter To Date"
				},
		  },
		  errorElement : 'div',
		  errorPlacement: function(error, element) {
				var placement = $(element).data('error');
				if (placement) {
				  $(placement).append(error)
				} else {
			  error.insertAfter(element);
			  }
			}
    });
	
	
	$(document).on('submit','form#subscribe_formValidate',function(){
		// var type = $("#type").val();
		// if(type==1){
		// 	loader.showLoader();
		// }
		$("#submit-download").prop('disabled',true);
	});
	
	$("#data_cleaning_sidebars_id").addClass('active');
	$("#update_history_sidebar_li_id").addClass('active');
	$("#update_history_sidebar_a_id").addClass('active');
</script>
@endsection