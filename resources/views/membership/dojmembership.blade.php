@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/data-tables/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css') }}">
@endsection
@section('headSecondSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/pages/data-tables.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/custom_respon.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/buttons.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/font-awesome.min.css') }}">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/export-button.css') }}">
<style>
@media (min-width: 1025px) {
	ul.dtr-details li {
	  display:inline;
	  margin-right: 13px;
	}
	ul.dtr-details {
	   width: 1180px; overflow: auto
	}
	span.dtr-data{
		padding-right:5px;
		border-right: 1px solid #636363;
	}
	span.dtr-title{
		color: #6a6a6a;
	}
}
span.dtr-title{
	color: #6a6a6a;
}
span.dtr-title::after {
  content: ":";
}

#main .section-data-tables .dataTables_wrapper table.dataTable tbody th, #main .section-data-tables .dataTables_wrapper table.dataTable tbody td:first-child {
    padding-top: 8px;
    padding-bottom: 8px;
    padding-left: 26px;
    padding-right: 16px;
    font-size: 12px;
    white-space: nowrap;
    text-transform: Uppercase;
    border: none !important;
}
.btn-sm{
	padding: 1px 3px;
    font-size: 8px;
    line-height: 1.5;
    border-radius: 3px;
	color: #fff;
}
#page-length-option td:not(:first-child) {
	word-break: break-word !important;
	white-space: unset !important;
	vertical-align: top;
}

</style>
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
								<h5 class="breadcrumbs-title mt-0 mb-0">{{__('Membership List') }}</h5>
								<ol class="breadcrumbs mb-0">
								<li class="breadcrumb-item"><a href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard') }}</a>
									<li class="breadcrumb-item active"><a href="#">{{__('Member') }}</a>
									</li>
									
								</ol>
							</div>
							<div class="col s2 m6 l6 ">
								
							</div>
						</div>
						
					</div>
				</div> 
				<div class="col s12">
					<div class="card">
						<div class="card-content">
							<div class="row">
								<div class="col s12 m4 l3">
									<h4 class="card-title">
										
										Doj Membership List &nbsp;</h4>
									</div>
								<div >
									
								</div> 
								
							</div>	
							
							</div>
							@include('includes.messages')
							<div class="row">
								<div class="col s12">
									<table id="page-length-option" class="display" width="100%">
										<thead>
											<tr>
												<th style="text-align:center;white-space: nowrap !important;">{{__('Action') }}</th>
												<th width="15%">{{__('Mem/ID') }}</th>
												<th width="15%">{{__('Mem/Name') }}</th>
												<th width="10%">{{__('Bank Code') }}</th>
												<th width="10%">{{__('Branch Name') }}</th>
												<th width="15%">{{__('From Doj') }}</th>
												<th width="15%">{{__('To Doj') }}</th>
												<th width="10%">{{__('NRIC Old') }}</th>
												<th width="10%">{{__('NRIC New') }}</th>
												
												<!-- <th>{{__('Union Branch Name') }}</th> -->
												<th width="10%">{{__('Status') }}</th>
												
											</tr>
										</thead>
										<tbody>
											@foreach($data['membership_view'] as $member)
											<tr>
												<td>
													<a style="" id="" onclick="showeditForm({{$member->id}});" title="Approve" class="btn-sm waves-effect waves-light cyan modal-trigger" href="#"><i class="material-icons">edit</i></a>
												</td>
												<td>{{ $member->member_number }}</td>
												<td>{{ $member->name }}</td>
												<td>{{ $member->short_code }}</td>
												<td>{{ $member->branch_name }}</td>
												<td>{{ $member->doj }}</td>
												<td>{{ $member->temp_doj }}</td>
												<td>{{ $member->old_ic }}</td>
												<td>{{ $member->new_ic }}</td>
												<td>{{ $member->status_name }}</td>
											</tr>
											@endforeach
										</tbody>
										
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
<div id="modal-approval" class="modal">
    <form class="formValidate" id="approvalformValidate" method="post" action="{{ route('doj_approve.save',app()->getLocale()) }}">
        @csrf
    <input type="text" class="hide" name="member_autoid" id="member_autoid"/>
    <div class="modal-content">
      <h4>Member details</h4>
      <div class="row">
        <div class="col s12 m6">
           <p>
            Member Name: <span id="view_member_name" class="bold"></span>
            </br>
           
           </p>
        </div>
        <div class="col s12 m6">
           <p>
             M/O: <span id="view_mno" class="bold"></span>
           </p>
        </div>
      </div>
      
       </hr>
      
        <div class="row">
            <div class="col m3">
                <label for="fromdoj">{{__('From Doj') }}*</label>
                <input type="text" class="" name="fromdoj" id="fromdoj" required="" readonly="" value="" />
            </div>
            <div class="col m3">
                <label for="todoj">{{__('To Doj') }}*</label>
                <input type="text" class="" name="todoj" id="todoj" required="" readonly="" value="" />
            </div>
             <div class="col m3">
                <label for="acceptstatus">{{__('Status') }}*</label>
                <select name="acceptstatus" id="acceptstatus" required="" class="error browser-default selectpicker">
                    <option value="">Select</option>
                    <option value="1">Accepted</option>
                    <option value="2">Rejected</option>
                </select>
            </div>
           
        </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="modal-action modal-close btn waves-effect red accent-2 left">Close</button>
      <button type="submit" class="btn waves-light submitApproval" >Submit</button>
    </div>
     </form>
</div>
@endsection
@section('footerSection')
<script src="{{ asset('public/assets/vendors/data-tables/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/dataTables.buttons.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/buttons.flash.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jszip.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/pdfmake.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/vfs_fonts.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/buttons.html5.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/buttons.print.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/data-tables/js/dataTables.select.min.js') }}" type="text/javascript"></script>

@endsection
@section('footerSecondSection')
<script src="{{ asset('public/assets/js/scripts/data-tables.js') }}" type="text/javascript"></script>
<script>
$('#advancedsearch').hide();
 $('#advancedsearchs').click(function(){
	$('#advancedsearch').toggle();
	});
$('#clear').click(function(){
	$(".selectpicker").val('').trigger("change"); 
});	

$("#other_sidebars_id").addClass('active');
$("#dojmembership_sidebar_li_id").addClass('active');
$("#dojmembership_sidebar_a_id").addClass('active');


$(function () {
	$.ajaxSetup({
	  headers: {
	    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	  }
	});
	$('#page-length-option').DataTable({});

/* $('#page-length-option tbody').on('click', 'ul.dtr-details', function () {
        var tr = $(this).closest('tr');
        var row = table.row( tr );
	console.log(row);
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
            tr.removeClass('highlightExpanded');
        }
        else {
            // Open this row
            row.child( format(row.data()) ).show();
            tr.addClass('shown');
            tr.addClass('highlightExpanded');
        }
    } ); */
$(document).on('submit','form#advancedsearch',function(event){
	event.preventDefault();
	dataTable.draw();
	loader.showLoader();
});
});

//union branch related bank 
$('#unionbranch_id').change(function(){
	var unionbranchID = $(this).val();   
	
	if(unionbranchID){
		$.ajax({
		type:"GET",
		dataType: "json",
		url:" {{ URL::to('/get-company-list') }}?unionbranch_id="+unionbranchID,
		success:function(res){              
			if(res){
				$("#company_id").empty();
				$("#company_id").append($('<option></option>').attr('value', '').text("Select"));
				$.each(res,function(key,entry){
					//console.log(res);
					
					$("#company_id").append($('<option></option>').attr('value', entry.id).text(entry.company_name));
					$('#companyid').val(entry.id);
				   // var select = $("#state");
				   // select.material_select('destroy');
					//select.empty();
					
				});
			   // $('#state').material_select();
			}else{
			  $("#company_id").empty();
			  $("#branch_id").empty();
			}
		}
		});
	}else{
		$("#branch_id").empty();
		$("#company_id").empty();
	}      
});
//union branch for bank  branch
$('#unionbranch_id').change(function(){
	var unionbranchID = $(this).val();   
	
	if(unionbranchID){
		$.ajax({
		type:"GET",
		dataType: "json",
		url:" {{ URL::to('/get-unionbankbranch-list') }}?unionbranch_id="+unionbranchID,
		success:function(res){              
			if(res){
				$("#branch_id").empty();
				$("#branch_id").append($('<option></option>').attr('value', '').text("Select"));
				$.each(res,function(key,entry){
					console.log(res);
					
					$("#branch_id").append($('<option></option>').attr('value', entry.id).text(entry.branch_name));
					//$('#branch_id').val(entry.id);
				   // var select = $("#state");
				   // select.material_select('destroy');
					//select.empty();
					
				});
			   // $('#state').material_select();
			}else{
			  //$("#company_id").empty();
			  $("#branch_id").empty();
			}
		}
		});
	}else{
		$("#branch_id").empty();
		$("#company_id").empty();
	}      
});


$('#company_id').change(function() {
        var companyID = $(this).val();

        if (companyID != '' && companyID != 'undefined') {
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "{{ URL::to('/get-companybranches-list') }}?company_id=" + companyID,
                success: function(res) {
                    if (res) {
                        $('#branch_id').empty();
                        $("#branch_id").append($('<option></option>').attr('value', '').text(
                            "Select"));
                        $.each(res, function(key, entry) {
                            $('#branch_id').append($('<option></option>').attr(
                                'value', entry.branchid).text(entry.branch_name));

                        });
                    } else {
                        $('#branch_id').empty();
                    }
                }
            });
        } else {
            $('#branch_id').empty();
        }
    });

$('#country_id').change(function(){
	var countryID = $(this).val();   
	
	if(countryID){
		$.ajax({
		type:"GET",
		dataType: "json",
		url:" {{ URL::to('/get-state-list') }}?country_id="+countryID,
		success:function(res){               
			if(res){
				$("#state_id").empty();
				$("#state_id").append($('<option></option>').attr('value', '').text("Select"));
				$.each(res,function(key,entry){
					$("#state_id").append($('<option></option>').attr('value', entry.id).text(entry.state_name));
				   // var select = $("#state");
				   // select.material_select('destroy');
					//select.empty();
					
				});
			   // $('#state').material_select();
			}else{
			  $("#state_id").empty();
			}
		}
		});
	}else{
		$("#state_id").empty();
		$("#city_id").empty();
	}      
});
$('#state_id').change(function(){
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
				$('#city_id').empty();
				$("#city_id").append($('<option></option>').attr('value', '').text("Select City"));
				$.each(res,function(key,entry){
					$('#city_id').append($('<option></option>').attr('value',entry.id).text(entry.city_name));
					
				});
			}else{
				$('#city_id').empty();
			}
		}
	 });
   }else{
	   $('#city_id').empty();
   }
});

function handleAjaxErrorLoc(){
	console.log('error occurs');
	alert('error occurs');
}

function showeditForm(memberid){
	$('.modal').modal();
	$.ajax({
		type: "GET",
		dataType: "json",
		url : "{{ URL::to('/en/get-dojchanged-list') }}?memberid="+memberid,
		success:function(res){
			if(res)
			{
				$("#view_member_name").text(res.name);
				$("#view_mno").text(res.member_number);
				$("#fromdoj").val(res.doj);
				$("#todoj").val(res.temp_doj);
				$("#member_autoid").val(res.id);
			}else{
				
			}
			$("#modal-approval").modal('open');
       		loader.hideLoader();
		}
	 });
}

</script>
@endsection