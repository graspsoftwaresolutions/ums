@section('headSecondSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
<link href="{{ asset('public/assets/css/jquery-ui-month.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/css/MonthPicker.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/css/sweetalert.css') }}" rel="stylesheet" type="text/css" />
<style>
@media (min-height: 100px) and (max-height: 657px) {
	#main.main-full {
		height: 657px;
		//overflow: auto;
	}
	
	.footer {
	   position: fixed;
	   margin-top:50px;
	   left: 0;
	   bottom: 0;
	   width: 100%;
	   height:auto;
	   background-color: red;
	   color: white;
	   text-align: center;
	   z-index:999;
	} 
	.sidenav-main{
		z-index:9999;
	}
}

</style>
@endsection
@php 
   $logo = CommonHelper::getLogo();
   $userid = Auth::user()->id;
   $companyid = CommonHelper::getCompanyID($userid);
   $companyname = CommonHelper::getCompanyName($companyid);
@endphp
<div class="row">
	<div class="col s12 m4 l4">
    	&nbsp;
    </div>
    <div class="col s12 m4 l4">
    	<br>
    	<br>
        <h4 class="center">{{ $companyname }}</h4>
      <div class="card gradient-45deg-indigo-purple">
        <div class="card-content white-text">
          	<div class="col s12 m4 l4" style="text-align: center;">
          		<img src="{{ asset('public/assets/images/logo/'.$logo) }}" style="width: 40px;margin: 0 0 0 30px;" alt="Membership logo"/>
         	</div>
            <div class="col s12 m8 l8">
            	<span class="card-title" style=" text-align: center;">Subscription Upload</span>
         	</div>
         	 <div class="clearfix"> </div>
          	<br>
          	<form class="formValidate" id="subscribe_formValidate" method="post" action="{{ url(app()->getLocale().'/subscribe_download') }}" enctype="multipart/form-data">
	             @csrf
	         	<div class="row">
	         		<div class="col s12 m11">
	         			 <div class="input-field">
	                        <input placeholder="Date" name="entry_date" id="entry_date" type="text" class="validate datepicker-custom" style="border: 1px solid #9e9e9e;border-radius: 6px;padding: 7px 7px 7px 16px;background: none !important;color: #fff;" value="{{ date('M/Y') }}">
	                        <label for="first_name" class="active " style=" margin-top: 4px;margin-left: 10px; background: #523ea0; color:#fff !important;padding: 0 10px;width: auto; " >{{__('Subscription Month') }}</label>
	                        <input type="text" name="sub_company" id="sub_company" class=" hide" value="{{ $companyid }}">
	                         <select id="type" name="type" class="error browser-default hide common-select add-select" onChange="return FileUploadEnable(this.value)">
                                            <option value="0">{{__('Download Empty File') }}</option>
                                            <option value="1" selected>{{__('Upload File') }}</option>
                                        </select>
	                    </div>
	         		</div>
	         		<div class="col s12 m12">
						<br>
                        <div id="file-upload-div" class="input-field  file-field " style="border: 1px solid #9e9e9e;margin: 0;padding: 0;border-radius: 6px; color: #fff;">
                            <div class="btn " style="float: right;height: 3rem;line-height: 3rem; background: none;color: #fff;margin: 0;">
                                <span>File Upload</span>
                                <input type="file" name="file"  class="form-control btn" accept=".xls,.xlsx">
                            </div>
                            <div class="file-path-wrapper ">
                                <input class="file-path validate" type="text" style="border:none;box-shadow: none;height: 2rem;margin-top: 10px;color: #fff;">
                            </div>
                        </div>
	         		</div>
	         		<div class="row">
                        <div class="input-field col s12">
                        	<br>
                        	<br>
                          	<button id="submit-download" style="margin-right: 10px;background: #757474;" class="mb-3 btn waves-effect waves-light form-download-btn left" type="button">{{__('Download') }}</button>
                            <button id="submit-upload" style="margin-right: 10px;" class="mb-3 btn waves-effect waves-light green lightrn-1 form-download-btn right" type="button">{{__('Submit') }}</button>
                        </div>
                    </div>
	         	</div>
         	</form>
        </div>
       
      </div>
      <br> <br> <br> 
    </div>
   
  </div>
<!-- card stats start -->
<!-- <div id="card-stats ">
   <div class="row">
		<div class="col s12 m6 l3">
		 <div class="card animate fadeRight">
			<div class="card-content green lighten-1 white-text">
			   <p class="card-stats-title"><i class="material-icons"></i>{{__('No of Company Branches') }}</p>
			   <h4 class="card-stats-number white-text">{{ $data['total_company_branch_count'] }}</h4>
			</div>
			<div class="card-action green">
			  <div id="invoice-line" class="center-align"><a style="color:white"href="{{route('master.branch', app()->getLocale())}}"> {{__('Company Branches List') }}</a></div> 
			  &nbsp;
			</div>
		 </div>
		</div>
      <div class="col s12 m6 l3">
         <div class="card animate fadeLeft">
            <div class="card-content red accent-2 white-text">
               <p class="card-stats-title"><i class="material-icons"></i>No of Members</p>
               <h4 class="card-stats-number white-text">{{ $data['total_member_count'] }}</h4>
              
            </div>
            <div class="card-action red">
              <div id="sales-compositebar" class="center-align"><a style="color:white" href="{{url(app()->getLocale().'/membership')}}">Members List</a></div> 
                &nbsp;
            </div>
         </div>
      </div>
      
   </div>
</div> -->
 @section('footerSection')
    <script src="{{ asset('public/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>

    <script src="{{ asset('public/assets/vendors/noUiSlider/nouislider.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/assets/js/materialize.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/scripts/form-elements.js') }}" type="text/javascript"></script>

    @endsection 
    @section('footerSecondSection')
    <script src="{{ asset('public/assets/js/scripts/form-validation.js')}}" type="text/javascript"></script>

    <script src="{{ asset('public/assets/js/jquery-ui-month.min.js')}}"></script>
    <script src="{{ asset('public/js/MonthPicker.min.js')}}"></script>
    <script src="{{ asset('public/js/sweetalert.min.js')}}"></script>

    <script>
        $(document).ready(function() {
            // the "href" attribute of the modal trigger must specify the modal ID that wants to be triggered
            $('.modal').modal();
        });

        $(document).ready(function() {
            $('.datepicker-custom').MonthPicker({
                Button: false,
                changeYear: true,
                MonthFormat: 'M/yy',
                OnAfterChooseMonth: function() {
                    getDataStatus();
                }
            });
            $('.ui-button').removeClass("ui-state-disabled");
            //$('.datepicker-custom').MonthPicker({ Button: false,dateFormat: 'M/yy' });

        });
        function getDataStatus() {
            var entry_date = $("#entry_date").val();
            var sub_company = $("#sub_company").val();
            $(".datamonth").text('[' + entry_date + ']');
            $("#entry_date_one").val(entry_date);
            if (entry_date != "" && sub_company != "") {
                var selected = $("#sub_company").find('option:selected');
                var company_name = selected.data('companyname');
                $("#bankname-listing").removeClass('hide');
                $(".subscription-bankname").text(company_name);
                //alert(company_name);
                loader.showLoader();
                $("#type option[value='2']").remove();
                var url = "{{ url(app()->getLocale().'/check-subscription-exists') }}" + '?entry_date=' + entry_date + "&sub_company=" + sub_company;
                $.ajax({
                    url: url,
                    type: "GET",
                    success: function(result) {
                        loader.hideLoader();
                        if (result.status == 1 || result.status == 2) {
                            if (result.status == 1) {
                                swal({
                                    title: "Data Already Exists!",
                                    text: "Are you sure you want to download existance data?",
                                    icon: 'success',
                                    dangerMode: true,
                                    buttons: {
                                        cancel: 'No, Please!',
                                        delete: 'Yes, Download It'
                                    }
                                }).then(function(willDelete) {
                                    if (willDelete) {
                                        DownloadExistance(1);
                                    } else {
                                        DownloadExistance(0);
                                    }
                                });
                            } else {
                                //alert('test');
                                $("#sub_company").val('').trigger('change');
                                swal({
                                    title: 'Subscription for this bank already uploaded by bank',
                                    icon: 'error'
                                });
                            }

                            $.each(result.status_data.count, function(key, entry) {
                                var baselink = base_url + '/{{ app()->getLocale() }}/';
                                $("#monthly_company_sub_status_" + key).attr('data-href', baselink + "subscription-status?member_status=" + key + "&date=" + result.month_year_number + "&company_id=" + result.company_auto_id);
                                $("#company_member_status_count_" + key).html(entry);
                            });
                            $.each(result.status_data.amount, function(key, entry) {
                                $("#company_member_status_amount_" + key).html(entry);
                            });
                            $("#memberstatustable").css('opacity', 1);
                            $.each(result.approval_data.count, function(key, entry) {
                                var baselink = base_url + '/{{ app()->getLocale() }}/';
                                $("#monthly_company_approval_status_" + key).attr('data-href', baselink + "subscription-status?approval_status=" + key + "&date=" + result.month_year_number + "&company_id=" + result.company_auto_id);
                                $("#company_approval_status_count_" + key).html(entry);
                            });
                            $.each(result.approval_data.approved, function(key, entry) {
                                $("#company_approval_approved_count_" + key).html(entry);
                            });
                            $.each(result.approval_data.pending, function(key, entry) {
                                $("#company_approval_pending_count_" + key).html(entry);
                            });
                            var baselink = base_url + '/{{ app()->getLocale() }}/';
                            $("#monthly_company_sub_status_0").attr('data-href', baselink + "subscription-status?member_status=0&date=" + result.month_year_number + "&company_id=" + result.company_auto_id);
                            $("#monthly_company_sub_status_all").attr('data-href', baselink + "subscription-status?member_status=all&date=" + result.month_year_number + "&company_id=" + result.company_auto_id);
                            $("#monthly_company_approval_status_all").attr('data-href', baselink + "subscription-status?approval_status=all&date=" + result.month_year_number + "&company_id=" + result.company_auto_id);
                            $("#company_member_status_count_sundry").html(result.sundry_count);
                            $("#company_member_status_amount_sundry").html(result.sundry_amount);
                            $("#company_member_status_count_total").html(result.total_members_count);
                            $("#company_member_status_amount_total").html(result.total_members_amount);
                            $("#company_approval_status_count_total").html(result.total_match_members_count);
                            $("#company_approval_approved_count_total").html(result.total_match_approval_members_count);
                            $("#company_approval_pending_count_total").html(result.total_match_pending_members_count);
                            $("#approvalstatustable").css('opacity', 1);
                            $("#type").append('<option value="2">Download Existance data</option>');
                        } else {
                            //$(".subscription-bankname").text('');
                            $(".clear-approval").html(0);
                            $(".monthly-company-approval-status").attr('data-href', '');
                            $(".monthly-company-sub-status").attr('data-href', '');

                            //$("#bankname-listing").addClass('hide');
                        }
                    }
                });
            } else {
                //$(".subscription-bankname").text('');
                $(".clear-approval").html(0);
                $(".monthly-company-approval-status").attr('data-href', '');
                $(".monthly-company-sub-status").attr('data-href', '');
                //$("#bankname-listing").addClass('hide');
            }
            if (entry_date != "") {
                $("#memberstatustable").css('opacity', 0.5);
                $("#approvalstatustable").css('opacity', 0.5);
                var url = "{{ url(app()->getLocale().'/get-datewise-status') }}" + '?entry_date=' + entry_date;
                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: "json",
                    success: function(result) {
                        //console.log(result);
                        if (result.status == 1) {
                            $.each(result.status_data.count, function(key, entry) {
                                var baselink = base_url + '/{{ app()->getLocale() }}/';
                                var member_link = "<a target='_blank' href='" + baselink + "subscription-status?member_status=" + key + "&date=" + result.month_year_number + "'>";
                                $("#member_status_count_" + key).html(entry);
                                $("#monthly_member_status_" + key).attr('data-href', baselink + "subscription-status?member_status=" + key + "&date=" + result.month_year_number);
                                //$("#monthly_member_status_"+key).html(entry);
                            });
                            var baselink = base_url + '/{{ app()->getLocale() }}/';
                            $("#monthly_member_status_0").attr('data-href', baselink + "subscription-status?member_status=0&date=" + result.month_year_number);
                            $("#monthly_member_status_all").attr('data-href', baselink + "subscription-status?member_status=all&date=" + result.month_year_number);
                            $.each(result.status_data.amount, function(key, entry) {
                                $("#member_status_amount_" + key).html(entry);
                            });
                            $("#memberstatustable").css('opacity', 1);
                            $.each(result.approval_data.count, function(key, entry) {
                                var baselink = base_url + '/{{ app()->getLocale() }}/';
                                var member_link = "<a target='_blank' href='" + baselink + "subscription-status?approval_status=" + key + "&date=" + result.month_year_number + "'>"
                                $("#approval_status_count_" + key).html(entry);
                                $("#monthly_approval_status_" + key).attr('data-href', baselink + "subscription-status?approval_status=" + key + "&date=" + result.month_year_number);
                            });
                            $.each(result.approval_data.approved, function(key, entry) {
                                //console.log("#approval_approved_count_"+key);
                                //console.log("#approval_approved_count_"+entry);
                                $("#approval_approved_count_" + key).html(entry);
                            });
                            $("#monthly_approval_status_all").attr('data-href', baselink + "subscription-status?approval_status=all&date=" + result.month_year_number);
                            $.each(result.approval_data.pending, function(key, entry) {
                                $("#approval_pending_count_" + key).html(entry);
                            });
                            $("#approvalstatustable").css('opacity', 1);
                            $("#member_status_count_sundry").html(result.sundry_count);
                            $("#member_status_amount_sundry").html(result.sundry_amount);
                            $("#member_status_count_total").html(result.total_members_count);
                            $("#member_status_amount_total").html(result.total_members_amount);
                            $("#approval_status_count_total").html(result.total_match_members_count);
                            $("#approval_approved_count_total").html(result.total_match_approval_members_count);
                            $("#approval_pending_count_total").html(result.total_match_pending_members_count);
                            //$("#member_status_count_1").html(5555);
                        } else {

                        }
                    }
                });
            }
        }
         function DownloadExistance(existance) {
            if (existance == 1) {
                $("#type").val(2);
                $('#subscribe_formValidate').trigger('submit');
            } else {
                //$("#type option[value='2']").remove();
            }
            $("#modal_subscription").modal('close');
        }
        $(document).on('submit', 'form#subscribe_formValidate', function() {
            var type = $("#type").val();
            if (type == 1) {
                loader.showLoader();
            }
            //$("#submit-download").prop('disabled',true);
        });
        $(document).on('click', '#submit-download', function() {
            $("#type").val(0);
            $('#subscribe_formValidate').trigger('submit');
        });
        $(document).on('click', '#submit-upload', function() {
            $("#type").val(1);
            $('#subscribe_formValidate').trigger('submit');

        });
    </script>
   
 @endsection