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
    .btn, .btn-large, .btn-small {
	    margin: 2px !important;
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
	<!--<div style="height:150px !important" class="content-wrapper-before gradient-45deg-indigo-purple"></div>-->
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
					
					$userid = Auth::user()->id;
					$get_roles = Auth::user()->roles;
					$user_role = $get_roles[0]->slug;
					$companylist = [];
					$branchlist = [];
					$companyid = '';
					$branchid = '';
					$unionbranchid='';
					if($user_role =='union'){
						$companylist = $data['company_view'];
					}
					else if($user_role =='union-branch'){
						$unionbranchid = CommonHelper::getUnionBranchID($userid);
						$companylist = CommonHelper::getUnionCompanyList($unionbranchid);
					} 
					else if($user_role =='company'){
						$branchid = CommonHelper::getCompanyBranchID($userid);
						$companyid = CommonHelper::getCompanyID($userid);
						$companylist = CommonHelper::getCompanyList($companyid);
						$branchlist = CommonHelper::getCompanyBranchList($companyid);
					}
					else if($user_role =='company-branch'){
						$branchid = CommonHelper::getCompanyBranchID($userid);
						$companyid = CommonHelper::getCompanyID($userid);
						$companylist = CommonHelper::getCompanyList($companyid);
						$branchlist = CommonHelper::getCompanyBranchList($companyid,$branchid);
					} 
					
				@endphp
					<div class="card-content">
						<div class="row">
							<div class="col s12 m12">
								<div class="row">
									<form class="formValidate" id="subscribe_formValidate" method="post" action="{{ url(app()->getLocale().'/due-list') }}" enctype="multipart/form-data">
										@csrf
										<div class="row">

											<div class="col s12 m6 l3 @if($user_role =='company-branch' || $user_role =='company') hide @endif">
												<label>{{__('Company Name') }}</label>
												<select name="company_id" id="company_id" class="error browser-default selectpicker" data-error=".errorTxt22" >
													<option value="">{{__('Select Company') }}</option>
													@foreach($companylist as $value)
													<option value="{{$value->id}}">{{$value->company_name}}</option>
													@endforeach
												</select>
												<div class="input-field">
													<div class="errorTxt22"></div>
												</div>
											</div>
											
											
											<div class="col s12 m6 l3 @if($user_role =='company-branch') hide @endif">
												<label>{{__('Company Branch Name') }}</label>
												<select name="branch_id" id="branch_id" class="error browser-default selectpicker" data-error=".errorTxt23" >
													<option value="">{{__('Select Branch') }}</option>
													@foreach($branchlist as $branch)
													<option value="{{$branch->id}}">{{$branch->branch_name}}</option>
													@endforeach
												</select>
												<div class="input-field">
													<div class="errorTxt23"></div>
												</div>
											</div>
						
											

											<div class="col s12 m6 l2">
												<label>{{__('Status') }}</label>
												<select name="status_id" id="status_id" class="error browser-default selectpicker" data-error=".errorTxt23" >
													<option value="">{{__('Select Status') }}</option>
													 @foreach($data['status_view'] as $value)
	                                                <option @if($data['status_id']==$value->id) selected @endif value="{{$value->id}}" >
	                                                    {{$value->status_name}}</option>
	                                                @endforeach
													
												</select>
												<div class="input-field">
													<div class="errorTxt23"></div>
												</div>
											</div>

											<div class="col s12 m6 l2">
												<label>{{__('Due Months') }}</label>
												<select name="due_months" id="due_months" class="error browser-default selectpicker" data-error=".errorTxt24" >
													<option value="">{{__('Select Month') }}</option>
													@for($i=1;$i<=15;$i++)
													<option @if($data['due_months']==$i) selected @endif value="{{$i}}">{{$i}} @if($i==1) month @else months @endif </option>
													@endfor
													<option @if($data['due_months']==16) selected @endif value="16">More than 15 months</option>
													
												</select>
												<div class="input-field">
													<div class="errorTxt24"></div>
												</div>
											</div>
											
											<div class="col m2 s12 " style="padding-top:5px;">
												</br>
												<button id="submit-upload" class="mb-6 btn waves-effect waves-light purple lightrn-1 form-download-btn" type="submit">{{__('Submit') }}</button>
												
											</div>
											
										</div>
										<div class="row hide">
											<div class="col s7">
												
											</div>
											<div class="col s4 ">
												
												<button id="submit-download" class="waves-effect waves-light cyan btn btn-primary form-download-btn hide" type="button">{{__('Download Sample') }}</button>
												
											</div>
										</div>
									</form>
									
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
                    <h4 class="card-title">{{__('Due List') }}@if($data['company_id'])[ Bank: {{ CommonHelper::getCompanyName($data['company_id']) }} ]@endif @if($data['branch_id'])[Bank branch: {{ CommonHelper::getBranchName($data['branch_id']) }}]@endif</h4>
                    @include('includes.messages')
                    <div class="row">
                        <div class="col s12">
                            <table id="page-length-option" class="display" width="100%">
                                <thead>
                                    <tr>
										<th width="5%">{{__('S.No') }}</th>
                                        <th width="10%">{{__('Member Name') }}</th>
                                        <th width="15%">{{__('Member Number') }}</th>
                                        <th width="15%">{{__('Bank') }}</th>
                                    	<th width="15%">{{__('Bank Branch') }}</th>
                                        <th width="10%">{{__('DOJ') }}</th>
                                        <th width="10%">{{__('Status') }}</th>
                                        <th width="10%">{{__('Dues') }}</th>

                                        <th> {{__('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
									@php
										$slno = 1;
									@endphp
                                	@foreach($data['members_list'] as $members)
                                		@php
                                			$due_count = CommonHelper::getMonthendDueCount($members->id);
                                			$due_def = $data['due_months'] =='' ? 0 : $data['due_months'];
                                			$branch_data = CommonHelper::getBranchCompany($members->branch_id);
                                		@endphp
                                		@if(($data['due_months'] =='' && $due_count>0) || ($data['due_months']<16 && $data['due_months']>0 && $due_count==$due_def) || ($data['due_months']==16 && $due_count>15) )
                                		<tr>
                                			<td>{{ $slno }}</td>
                                			<td>{{ $members->name }}</td>
                                			<td>{{ $members->member_number }}</td>
                                			<td>{{ $branch_data->company_name }}</td>
                                			<td>{{ $branch_data->branch_name }}</td>
                                			<td>{{ date('d/M/Y',strtotime($members->doj)) }}</td>
                                			<td>{{ CommonHelper::get_member_status_name($members->status_id) }}</td>
                                			<td>{{ $due_count }}</td>
                                			<td>
                                				<a class='waves-effect waves-light btn btn-sm' href='{{ route("monthend.viewlistsall", [app()->getLocale(),Crypt::encrypt($members->id)]) }}'>Update</a>
                                				<a style='' title='History'  class='waves-effect waves-light blue btn btn-sm' href='{{ route("member.history", [app()->getLocale(),Crypt::encrypt($members->id)]) }}'>View</a>
                                			</td>
                                		</tr>
										@php
											$slno++;
										@endphp
                                		@endif
										
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
			company_id: {
				required: true,
			},
		},
		  //For custom messages
		  messages: {
				company_id:{
					required: "Please pick bank"
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
	$("#due_sidebar_li_id").addClass('active');
	$("#due_sidebar_a_id").addClass('active');
</script>
@endsection