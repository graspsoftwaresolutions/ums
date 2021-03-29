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
									<h5 class="breadcrumbs-title mt-0 mb-0">{{__('State Members List') }}</h5>
									<ol class="breadcrumbs mb-0">
										<ol class="breadcrumbs mb-0">
											<li class="breadcrumb-item"><a
													href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard') }}</a>
											</li>
											<li class="breadcrumb-item active">{{__('Members') }}
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
		 @php 
         	$auth_user = Auth::user(); $companylist = []; $companyid = ''; if(!empty($auth_user)){ $userid = Auth::user()->id; $get_roles = Auth::user()->roles; $user_role = $get_roles[0]->slug; if($user_role =='union'){ $companylist = CommonHelper::getCompanyListAll(); } else if($user_role =='union-branch'){ $unionbranchid = CommonHelper::getUnionBranchID($userid); $companylist = CommonHelper::getUnionCompanyList($unionbranchid); } else if($user_role =='company'){ $companyid = CommonHelper::getCompanyID($userid); $companylist = CommonHelper::getCompanyList($companyid); } else if($user_role =='company-branch'){ $companyid = CommonHelper::getCompanyID($userid); $companylist = CommonHelper::getCompanyList($companyid); } $company_count = count($companylist); }
        @endphp 
		<form class="formValidate" id="subscribe_formValidate" method="post" action="{{ url(app()->getLocale().'/clean-state') }}" enctype="multipart/form-data">
		@csrf
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
						<div class="row">
							<div class="col s12 m12">
								<div class="row">
									
										<div class="row">
											
											<div class="col s12 m6 l2">
												<label>{{__('From State') }}</label>
												<select name="from_state_id" id="from_state_id" class="error browser-default selectpicker" data-error=".errorTxt23" >
													<option value="">{{__('Select State') }}</option>
													<option value="empty">{{__('Empty State') }}</option>
													 @foreach($data['state_view'] as $value)
	                                                <option value="{{$value->id}}" >
	                                                    {{$value->state_name}}</option>
	                                                @endforeach
													
												</select>
												<div class="input-field">
													<div class="errorTxt23"></div>
												</div>
											</div>
											<div class="col s12 m6 l3">
												<label>{{__('From City') }}</label>

												<select name="from_city_id" id="from_city_id" class="error browser-default selectpicker" data-error=".errorTxt24" >
													<option value="">{{__('Select City') }}</option>
													<option value="empty">{{__('Empty City') }}</option>
												</select>
												<div class="input-field">
													<div class="errorTxt24"></div>
												</div>
											</div>

											<div class="col m3 s12">
                                                <label for="sub_company">{{__('Company') }}*</label>
                                                <select name="sub_company" id="sub_company" class="error browser-default selectpicker" data-error=".errorTxt6">
                                                    <option value="" selected>{{__('Choose Company') }}</option>
                                                    @foreach($companylist as $value)
                                                    <option data-companyname="{{$value->company_name}}" value="{{$value->id}}">{{$value->company_name}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="errorTxt6"></div>
                                            </div>
                                            <div class="col s12 m6 l3 ">
                                                <label>{{__('Company Branch Name') }}</label>
                                                <select name="branch_id" id="branch_id" class="error browser-default selectpicker" data-error=".errorTxt23" >
                                                    <option value="">{{__('Select Branch') }}</option>
                                                    
                                                </select>
                                                <div class="input-field">
                                                    <div class="errorTxt23"></div>
                                                </div>
                                            </div>

											
											<div class="col m1 s12 " style="padding-top:5px;">
												</br>
												<button id="get_list" class="mb-6 btn waves-effect waves-light purple lightrn-1 form-download-btn" type="button">{{__('Get list') }}</button>
												
											</div>
											
										</div>
										
									
									
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
                   
                    	<div class="row">
                    		<div class="col s12 m6 l2">
                    			<br>
                    			 <h4 class="card-title">{{__('Members List') }}</h4>
                    		</div>
                    		<div class="col s12 m6 l2">
								<label>{{__('To State') }}</label>
								<select name="to_state_id" id="to_state_id" class="error browser-default selectpicker" data-error=".errorTxt25" >
									<option value="">{{__('Select State') }}</option>
									 @foreach($data['state_view'] as $value)
	                                <option value="{{$value->id}}" >
	                                    {{$value->state_name}}</option>
	                                @endforeach
									
								</select>
								<div class="input-field">
									<div class="errorTxt25"></div>
								</div>
							</div>
							<div class="col s12 m6 l3">
								<label>{{__('To City') }}</label>
								<select name="to_city_id" id="to_city_id" class="error browser-default selectpicker" data-error=".errorTxt27" >
									<option value="">{{__('Select City') }}</option>
									
								</select>
								<div class="input-field">
									<div class="errorTxt27"></div>
								</div>
							</div>
							<div class="col m2 s12 " style="padding-top:5px;">
								</br>
								<button id="submit-upload" class="mb-6 btn waves-effect waves-light form-download-btn" type="submit">{{__('Update') }}</button>
								
							</div>
                    	</div>
                    	
                    
                    @include('includes.messages')
                    <div class="row">
                        <div class="col s12">
                            <table id="page-length-option" class="display" width="100%">
                                <thead>
                                    <tr>
                                        <th width="10%"><p style="margin-left: 10px; "><label><input class="checkall" id="checkAll" type="checkbox" /> <span>Check All</span> </label> </p></th>
                                        <th width="5%">Sno</th>
                                        <th width="25%">{{__('Member Name') }}</th>
                                        <th width="10%">{{__('Member Number') }}</th>
                                        <th width="20%">{{__('Bank') }}</th>
                                        <th width="20%">{{__('Branch') }}</th>
                                       
                                    </tr>
                                </thead>
                                <tbody id="memberslist">
                                	
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
	</div>
	
</div>

@endsection
@section('footerSection')
<!--script src="{{ asset('public/assets/js/jquery.min.js') }}"></script-->

<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/assets/js/materialize.min.js') }}"></script>
<script src="{{ asset('public/assets/js/scripts/form-elements.js') }}" type="text/javascript"></script>

<script src="{{ asset('public/assets/js/datepicker.js') }}"></script>

@endsection
@section('footerSecondSection')


<script>
$(document).ready(function() {
	
});
$('.datepicker,.datepicker-custom').datepicker({
    format: 'dd-mm-yyyy',
    autoHide: true,
});

	
	$("#subscribe_formValidate").validate({
       rules: {
			// from_state_id: {
			// 	required: true,
			// },
			// from_city_id: {
			// 	required: true,
			// },
			to_state_id: {
				required: true,
			},
			to_city_id: {
				required: true,
			},
		},
		  //For custom messages
		  messages: {
				
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

	$('#from_state_id').change(function(){
	   var StateId = $(this).val();
	  
	   if(StateId!='' && StateId!='undefined')
	   {
	   	$("#memberslist").empty();
	   	// if(StateId=='empty'){
	   	// 	var url = "{{ url(app()->getLocale().'/get_statemembers') }}" + '?from_city_id=&from_state_id='+StateId;
     //        $.ajax({
     //            url: url,
     //            type: "GET",
     //            success: function(result) {
     //                loader.hideLoader();
     //                console.log(result);
     //                if (result.status == 1 ) {
                     
     //                    $("#memberslist").empty();

     //                    $.each(result.members, function(key, entry) {

                             
     //                         var hiddensec = '';
     //                         $("#memberslist").append('<tr style=""> <td width="15%"><p style="margin-left: 10px; "><label><input name="memberids[]" class="checkboxes" value="'+entry.memberid+'" type="checkbox"> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> </label> </p><div id="salarysection_'+entry.memberid+'">'+hiddensec+'</div></td><td>'+entry.name+'</td><td>'+entry.member_number+'</td></tr>');

     //                        var baselink = base_url + '/{{ app()->getLocale() }}/';
     //                        //$("#monthly_company_sub_status_" + key).attr('data-href', baselink + "subscription-status?member_status=" + key + "&date=" + result.month_year_number + "&company_id=" + result.company_auto_id);
     //                       // $("#company_member_status_count_" + key).html(entry);
     //                    });
                  
                       
     //                } else {
     //                    //$(".subscription-bankname").text('');
     //                    $(".clear-approval").html(0);
     //                    $(".monthly-company-approval-status").attr('data-href', '');
     //                    $(".monthly-company-sub-status").attr('data-href', '');

     //                    //$("#bankname-listing").addClass('hide');
     //                }
     //            }
     //        });
	   	// }
	   		$("#memberslist").empty();
	   		$.ajax({
				type: "GET",
				dataType: "json",
				url : "{{ URL::to('/get-cities-list') }}?State_id="+StateId,
				success:function(res){
					if(res)
					{
						$('#from_city_id').empty();
						$("#from_city_id").append($('<option></option>').attr('value', '').text("Select City"));
						$("#from_city_id").append($('<option></option>').attr('value', 'empty').text("Empty City"));
						$.each(res,function(key,entry){
							$('#from_city_id').append($('<option></option>').attr('value',entry.id).text(entry.city_name));
							
						});
					}else{
						$('#from_city_id').empty();
					}
				}
			 });
	   	
		 
	   }else{
		   $('#from_city_id').empty();
		   $("#from_city_id").append($('<option></option>').attr('value', '').text("Select City"));
	   }
	});

	$('#to_state_id').change(function(){
	   var StateId = $(this).val();
	  
	   if(StateId!='' && StateId!='undefined')
	   {
		 $.ajax({
			type: "GET",
			dataType: "json",
			url : "{{ URL::to('/get-cities-list') }}?State_id="+StateId,
			success:function(res){
				if(res)
				{
					$('#to_city_id').empty();
					$("#to_city_id").append($('<option></option>').attr('value', '').text("Select City"));
					$.each(res,function(key,entry){
						$('#to_city_id').append($('<option></option>').attr('value',entry.id).text(entry.city_name));
						
					});
				}else{
					$('#to_city_id').empty();
				}
			}
		 });
	   }else{
		   $('#to_city_id').empty();
		   $("#to_city_id").append($('<option></option>').attr('value', '').text("Select City"));
	   }
	});

	$('#get_list').click(function(){
		var from_city_id = $('#from_city_id').val();
		var from_state_id = $('#from_state_id').val();
		$("#memberslist").empty();
		if(from_city_id!='' || from_state_id=='empty'){
            company_id = $('#sub_company').val();
            branch_id = $('#branch_id').val();
			var url = "{{ url(app()->getLocale().'/get_statemembers') }}" + '?from_city_id=' + from_city_id+'&from_state_id=' + from_state_id+'&company_id=' + company_id+'&branch_id=' + branch_id;
            $.ajax({
                url: url,
                type: "GET",
                success: function(result) {
                    loader.hideLoader();
                    console.log(result);
                    if (result.status == 1 ) {
                     
                        $("#memberslist").empty();
                         var slno = 1;
                        $.each(result.members, function(key, entry) {

                             
                             var hiddensec = '';
                             $("#memberslist").append('<tr style=""> <td width="15%"><p style="margin-left: 10px; "><label><input name="memberids[]" class="checkboxes" value="'+entry.memberid+'" type="checkbox"> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> </label> </p><div id="salarysection_'+entry.memberid+'">'+hiddensec+'</div></td><td>'+slno+'</td><td>'+entry.name+'</td><td>'+entry.member_number+'</td><td>'+entry.company_name+'</td><td>'+entry.branch_name+'</td></tr>');
                             slno++;
                            var baselink = base_url + '/{{ app()->getLocale() }}/';
                        });
                  
                       
                    } else {
                        //$(".subscription-bankname").text('');
                        $(".clear-approval").html(0);
                        $(".monthly-company-approval-status").attr('data-href', '');
                        $(".monthly-company-sub-status").attr('data-href', '');

                        //$("#bankname-listing").addClass('hide');
                    }
                }
            });
		}else{
			$("#memberslist").empty();
		}
		 
	});

	$("#checkAll").click(function(){
        $('.checkboxes').not(this).prop('checked', this.checked);
    });

    $(document).on('click', '.checkboxes', function() {
        $('#checkAll').prop('checked', false);
    });

     $('#sub_company').change(function(){
       var CompanyID = $(this).val();
       var ajaxunionbranchid = '';
       var ajaxbranchid = '';
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
	
	$("#data_cleaning_sidebars_id").addClass('active');
	$("#stateclear_list_sidebar_li_id").addClass('active');
	$("#stateclear_list_sidebar_a_id").addClass('active');
</script>
@endsection