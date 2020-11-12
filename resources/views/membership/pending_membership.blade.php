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
								<a class="btn waves-effect waves-light breadcrumbs-btn right" href="{{ route('master.addmembership', app()->getLocale())  }}">{{__('New Registration') }}</a>
								
							</div>
						</div>
						@php 
							//dd($data['type']);
							$auth_user = Auth::user();
							$member_number_readonly = 'readonly';
							$member_number_hide = 'hide';
							$companylist = $data['company_view'];
							$branchlist = [];
							$companyid = '';
							$branchid = '';
							if(!empty($auth_user)){
								$userid = Auth::user()->id;
								$get_roles = Auth::user()->roles;
								$user_role = $get_roles[0]->slug;
								
								$companylist = [];
								
								if($user_role =='union' || $user_role =='data-entry'){
									$member_number_readonly = '';
									$member_number_hide = '';
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
									//print_r($branchlist);die;
								}
								else if($user_role =='company-branch'){
									$branchid = CommonHelper::getCompanyBranchID($userid);
									$companyid = CommonHelper::getCompanyID($userid);
									$companylist = CommonHelper::getCompanyList($companyid);
									$branchlist = CommonHelper::getCompanyBranchList($companyid,$branchid);
								}  
							}
							
						@endphp
					</div>
				</div> 
				<div class="col s12">
					<div class="card">
						<div class="card-content">
							<div class="row">
								<div class="col s12 m4 l3">
								<h4 class="card-title">
									@if($data['member_type'] ==1)
										Approved
									@else
										Pending
									@endif
									 Membership List &nbsp; </h4>
								</div>
								<div >
									<input type="button" id="advancedsearchs" name="advancedsearch" style="margin-bottom: 10px" class="btn col s12 m4 l3" value="Advanced search">
								</div> 
								<div>
									@if($data['member_type'] ==1) 
									<a class="col s12 m4 l3 btn waves-effect breadcrumbs-btn waves-light amber right" style="margin-bottom: 10px" href="{{ route('master.membershipnew', app()->getLocale() )}}">{{__('Pending members list') }}</a>
								@else
									<a class="col s12 m4 l3 btn waves-effect breadcrumbs-btn waves-light green darken-1 right " style="margin-bottom: 10px" href="{{ route('master.membership', app()->getLocale()) }}">{{__('Approved members list') }}</a>
								@endif
								</div>
							</div>	
							<div class="row">
							<div class="card advancedsearch" style="dispaly:none;">
								<div class="col s12">
									<form method="post" id="advancedsearch">
									@csrf  
									<div class="row">   
										<div class="col s12 m6 l3 @if($user_role !='union') hide @endif">
											<label>{{__('Union Branch Name') }}</label>
											<select name="unionbranch_id" id="unionbranch_id" class="error browser-default selectpicker" data-error=".errorTxt22" >
												<option value="">{{__('Select Union') }}</option>
												@foreach($data['unionbranch_view'] as $value)
                                                <option value="{{$value->id}}">
                                                    {{$value->union_branch}}</option>
                                                @endforeach
											</select>
											<div class="input-field">
												<div class="errorTxt22"></div>
											</div>
										</div>
										<div class="col s12 m6 l3 @if($user_role =='company-branch') hide @endif">
											<label>{{__('Company Name') }}</label>
											<input type="hidden" name="companyid" id="companyid">
										
												<select name="company_id" id="company_id" class="error browser-default selectpicker" data-error=".errorTxt22">
												<option value=""> Select Company</option>
												@foreach($companylist as $key=>$value)
                                                        <option value="{{$value->id}}"
                                                            >{{$value->company_name}}</option>
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
											</select>
											<div class="input-field">
												<div class="errorTxt23"></div>
											</div>
										</div>
										<div class="col s12 m6 l3">
											<label>{{__('Gender') }}</label>
											<select name="gender" id="gender" class="error browser-default selectpicker" data-error=".errorTxt23" >
												<option value="">{{__('Select Gender') }}</option>
												<option value="Male">Male</option>
												<option value="Female"> Female</option>
											</select>
											<div class="input-field">
												<div class="errorTxt23"></div>
											</div>
										</div>
										<div class="col s12 m6 l3">
											<label>{{__('Race') }}</label>
											<select name="race_id" id="race_id" class="error browser-default selectpicker" data-error=".errorTxt23" >
												<option value="">{{__('Select Race') }}</option>
												 @foreach($data['race_view'] as $value)
                                                <option value="{{$value->id}}" >
                                                    {{$value->race_name}}</option>
                                                @endforeach
												
											</select>
											<div class="input-field">
												<div class="errorTxt23"></div>
											</div>
										</div>
										<div class="col s12 m6 l3">
											<label>{{__('Status') }}</label>
											<select name="status_id" id="status_id" class="error browser-default selectpicker" data-error=".errorTxt23" >
												<option value="">{{__('Select Status') }}</option>
												 @foreach($data['status_view'] as $value)
                                                <option value="{{$value->id}}" >
                                                    {{$value->status_name}}</option>
                                                @endforeach
												
											</select>
											<div class="input-field">
												<div class="errorTxt23"></div>
											</div>
										</div>
										<div class="col s12 m6 l3">
											<label>{{__('Country') }}</label>
											<select name="country_id" id="country_id" class="error browser-default selectpicker" data-error=".errorTxt23" >
												<option value="">{{__('Select Country') }}</option>
												
												@foreach($data['country_view'] as $value)
                                                <option value="{{$value->id}}" >
                                                    {{$value->country_name}}</option>
                                                @endforeach
											</select>
											<div class="input-field">
												<div class="errorTxt23"></div>
											</div>
										</div>
										<div class="col s12 m6 l3">
											<label>{{__('State') }}</label>
											<select name="state_id" id="state_id" class="error browser-default selectpicker" data-error=".errorTxt23" >
												<option value="">{{__('Select State') }}</option>
												
											</select>
											<div class="input-field">
												<div class="errorTxt23"></div>
											</div>
										</div>
										<div class="col s12 m6 l3">
											<label>{{__('City') }}</label>
											<select name="city_id" id="city_id" class="error browser-default selectpicker" data-error=".errorTxt23" >
												<option value="">{{__('Select City') }}</option>
												
												
											</select>
											<div class="input-field">
												<div class="errorTxt23"></div>
											</div>
										</div>
										<div class="col s12 m6 l3">
											<label>{{__('Pending Type') }}</label>
											<select name="pending_type" id="pending_type" class="error browser-default selectpicker" data-error=".errorTxt29" >
												<option value="">{{__('Select Type') }}</option>
												<option>{{__('Pending') }}</option>
												<option @if($data['type']==1) selected @endif>{{__('Rejected') }}</option>
												
												
											</select>
											<div class="input-field">
												<div class="errorTxt23"></div>
											</div>
										</div>
										</div>
										<div class="row">
											<div class="input-field col s6 right">
												<input type="submit" id="clear"  class="btn" name="clear" value="{{__('Clear')}}">
											</div>
											<div class="input-field col s6 right-align">
												<input type="submit"  class="btn" name="search" value="{{__('Search')}}">
											</div>
										</div>
									</div>
									</form> 
								</div>
								</div>
							</div>
							@include('includes.messages')
							<div class="row">
								<div class="col s12">
									<table id="page-length-option" class="display" width="100%">
										<thead>
											<tr>
												<th width="20%" style="text-align:center;white-space: nowrap !important;">{{__('Action') }}</th>
												<!-- <th width="5%">{{__('Mem/ID') }}</th> -->
												<th width="5%">{{__('Mem/Name') }}</th>
												<th width="5%">{{__('Type')}} </th>
												<th width="5%">{{__('M/F')}}</th>
												<th width="5%">{{__('Bank Code') }}</th>
												<th width="7%">{{__('Branch Name') }}</th>
												<th width="5%">{{__('Levy') }}</th>
												<th width="5%">{{__('Levy Amount') }}</th>
												<th width="10%">{{__('TDF') }}</th>
												<th width="5%">{{__('TDF Amount') }}</th>
												<th width="5%">{{__('DOJ')}}</th>
												<th width="5%">{{__('City') }}</th>
												<th width="5%">{{__('State') }}</th>
												<th width="5%">{{__('NRIC Old') }}</th>
												<th width="5%">{{__('NRIC New') }}</th>
												<th width="5%">{{__('Mobile') }}</th>
												<th width="5%">{{__('Race Short Code') }}</th>
												<!-- <th>{{__('Union Branch Name') }}</th> -->
												<th width="3%">{{__('Status') }}</th>
												
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

$("#membership_sidebar_a_id").addClass('active');


$(function () {
	$.ajaxSetup({
	  headers: {
	    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	  }
	});
 var dataTable = $('#page-length-option').DataTable({
	"responsive": true,
	"order": [[ 0, "desc" ]],
	//"searching": false,
	"lengthMenu": [
		[10, 25, 50, 100, 3000],
		[10, 25, 50, 100, 'All']
	],
	dom: 'lBfrtip', 
        buttons: [
		   {
			   extend: 'pdf',
			   footer: true,
			   exportOptions: {
					columns: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17]
				},
                title : 'Members List', 
                text: '<i class="fa fa-file-pdf-o"></i>',
                titleAttr: 'pdf'
		   },
		   {
			   extend: 'excel',
			   footer: false,
			   exportOptions: {
					columns: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17]
				},
				title : 'Members List',
                text:    '<i class="fa fa-file-excel-o"></i>',
                titleAttr: 'excel'
		   },
			{
			   extend: 'print',
			   footer: false,
			   exportOptions: {
					columns: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17]
				},
				title : 'Members List',
                text:   '<i class="fa fa-files-o"></i>',
                titleAttr: 'print'
		   }  
		],
	"processing": true,
	"serverSide": true,
	"ajax": {
		"url": "{{ url(app()->getLocale().'/ajax_pending_members_list/'.$data['member_type']) }}?status={{$data['member_status']}}",
		"dataType": "json",
		"type": "POST",
		'data': function(data){
		  var unionbranch_id = $('#unionbranch_id').val();
		  var company_id      = $('#company_id').val();
		  var branch_id = $('#branch_id').val();
		  var gender = $('#gender').val();
		  var race_id = $('#race_id').val();
		  var status_id = $('#status_id').val();
		  var country_id = $('#country_id').val();
		  var state_id = $('#state_id').val();
		  var city_id = $('#city_id').val();
		  var pending_type = $('#pending_type').val();
		  
		  //console.log(datefilter);
		 
		  data.unionbranch_id = unionbranch_id;
		  data.company_id = company_id;
		  data.branch_id = branch_id;
		  data.gender = gender;
		  data.race_id = race_id;
		  data.status_id = status_id;
		  data.country_id = country_id;
		  data.state_id = state_id;
		  data.city_id = city_id;
		  data.pending_type = pending_type;
		  data._token = "{{csrf_token()}}";
	   },
	   "error": function (jqXHR, textStatus, errorThrown) {
            if(jqXHR.status==419){
            	alert('Your session has expired, please login again');
            	window.location.href = base_url;
            }
       },
	},
	"columns": [
		{"data": "options"},
		// {"data" : "member_number"},
		{"data": "name"},
		{"data" : "designation_id"},
		{"data" : "gender"},
		{"data" : "short_code"},
		{"data": "branch_name"},
		{"data": "levy"},
		{"data": "levy_amount"},	
		{"data": "tdf"},
		{"data": "tdf_amount"},
		{"data": "doj"},
		{"data": "city_id"},
		{"data": "state_id"},
		{"data": "old_ic"},
		{"data": "new_ic"},
		{"data": "mobile"},
		{"data": "race_id"},
		{"data": "status"}
		
	],
	"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
		
		$('td', nRow).css('background', aData.bg_color );
		$('td', nRow).css('color', aData.text_color );
	},
	"columnDefs": [
            {
                "render": function ( data, type, row ) {
                	return data ;
                    
                },
                "targets": [0, 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17]
            },
            { "visible": true,  "targets": '_all' }
        ],
	"drawCallback": function(settings) {
		loader.hideLoader();
	},
	/* responsive: {
		details: {
			renderer: function ( api, rowIdx, columns ) {
				
				var data = $.map( columns, function ( col, i ) {
					  var tr = $(this).closest('tr');
					  console.log(tr);
					/* return col.hidden ?
						'<ul><li data-dtr-index="'+col.columnIndex+'" data-dt-row="'+col.rowIndex+'" data-dt-column="'+col.columnIndex+'"><span class="dtr-title">'+col.title+'</span> <span class="dtr-data">'+col.data+'</span></li>' : ''; 
					return col.hidden ?
						'<tr data-dt-row="'+col.rowIndex+'" data-dt-column="'+col.columnIndex+'">'+
							'<td class="test">'+col.title+':'+'</td> '+
							'<td>'+col.data+'</td>'+
						'</tr>' :
						'';
				} ).join('');
				data += "</ul>";

				return data ?
					$('<table/>').append( data ) :
					false;
			}
		}
	} */
});
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


</script>
@endsection