@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/vendors/data-tables/css/jquery.dataTables.min.css') }}">
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
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/export-button.css') }}">
<style>
	/*#main.main-full {
		height: 750px;
		overflow: auto;
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
	}*/
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
                                        <h5 class="breadcrumbs-title mt-0 mb-0">{{__('Designation List') }}</h5>
                                        <ol class="breadcrumbs mb-0">
                                            <li class="breadcrumb-item"><a
                                                    href="{{ route('home', app()->getLocale()) }}">{{__('Dashboard') }}</a>
                                            </li>
                                            <li class="breadcrumb-item active">{{__('Designation') }}
                                            </li>
                                        </ol>
                                    </div>
                                    <div class="col s2 m6 l6 ">
                                        <a class="btn waves-effect waves-light breadcrumbs-btn right modal-trigger"
                                            onClick='showaddForm();'
                                            href="#modal_add_edit">{{__('Add') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12">
                            <div class="card">
                                <div class="card-content">
                                   <!-- <h4 class="card-title">{{__('Designation List') }}</h4>-->
                                    @include('includes.messages')
                                    <div class="row">
                                        <div class="col s12">
                                            <table id="page-length-option" class="display" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>{{__('Designation Name') }}</th>

                                                        <th  style="text-align:center;">  {{__('Action') }}</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="modal_add_edit" class="modal">
                        <div class="modal-header" id="modal-header">
                                <h4>{{__('Designation Details') }}</h4>
                                </div>
                                <div class="modal-content">
                                <form class="formValidate" id="designation_formValidate" method="post"
                                    action="{{ route('master.saveDesignation',app()->getLocale()) }}">
                                    @csrf
                                    <input type="hidden" name="id" id="updateid">
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <label for="designation_name"
                                                class="common-label force-active">{{__('Designation Name') }}*</label>
                                            <input id="designation_name" name="designation_name" autofocus class="common-input"
                                                type="text" data-error=".errorTxt1">
                                            <div class="errorTxt1"></div>
                                        </div>
                                        <div class="clearfix" style="clear:both"></div>
                                        </div> </div> 
                                        <div class="modal-footer">
                                            
                                            <button id="modal-update-btn" class="btn waves-effect waves-light submit edit_hide_btn "
                                                type="submit" name="action">{{__('Update')}}
                                            </button>
                                            <button id="modal-save-btn" class="btn waves-effect waves-light submit add_hide"
                                                style="display:none;" type="submit" name="action">{{__('Save')}}
                                            </button>
                                            <a href="#!"
                                                class="modal-action modal-close btn waves-effect waves-light cyan">{{__('Close')}}  </a>
                                        </div>
                                    </div>
                                </form>
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
<script src="{{ asset('public/assets/vendors/data-tables/js/jquery.dataTables.min.js') }}" type="text/javascript">
</script>
<script src="{{ asset('public/assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js') }}"
    type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/data-tables/js/dataTables.select.min.js') }}" type="text/javascript">
</script>
@endsection
@section('footerSecondSection')
<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/assets/js/scripts/form-validation.js')}}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/scripts/data-tables.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/dataTables.buttons.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/buttons.flash.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jszip.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/pdfmake.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/vfs_fonts.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/buttons.html5.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/buttons.print.min.js') }}" type="text/javascript"></script>
<script>
$("#masters_sidebars_id").addClass('active');
$("#designation_sidebar_li_id").addClass('active');
$("#designation_sidebar_a_id").addClass('active');
$("#designation_formValidate").validate({
    rules: {
        designation_name: {
            required: true,
            remote: {
                url: "{{ url(app()->getLocale().'/designation_nameexists')}}",
                data: {
                    designation_id: function() {
                        return $("#updateid").val();
                    },
                    _token: "{{csrf_token()}}",
                    designation_name: $(this).data('designation_name')
                },
                type: "post",
            },
        },
    },
    //For custom messages
    messages: {
        designation_name: {
            required: '{{__("Enter a Designation Name") }}',
            remote: '{{__("Designation Already exists") }}',
        },
    },
    errorElement: 'div',
    errorPlacement: function(error, element) {
        var placement = $(element).data('error');
        if (placement) {
            $(placement).append(error)
        } else {
            error.insertAfter(element);
        }
    }
});
//Data table Ajax call
$(function() {
    $('#page-length-option').DataTable({
        "responsive": true,
        dom: 'lBfrtip', 
			buttons: [
			   {
				   extend: 'pdf',
				   footer: true,
				   exportOptions: {
						columns: [0]
					},
                    title : 'Designation List',
                    titleAttr: 'pdf',
            		text:'<i class="fa fa-file-pdf-o"></i>'
			   },
			   {
				   extend: 'excel',
				   footer: false,
				   exportOptions: {
						columns: [0]
					},
                    title : 'Designation List',
                    text:      '<i class="fa fa-file-excel-o"></i>',
					titleAttr: 'excel'
			   },
				{
				   extend: 'print',
				   footer: false,
				   exportOptions: {
						columns: [0]
					},
                    title : 'Designation List',
                    text:   '<i class="fa fa-files-o"></i>',
           			titleAttr: 'print'
			   }  
			],
        /* "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ], */
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "{{ url(app()->getLocale().'/ajax_designation_list') }}",
            "dataType": "json",
            "type": "POST",
            "data": {
                _token: "{{csrf_token()}}"
            },
            "error": function (jqXHR, textStatus, errorThrown) {
                if(jqXHR.status==419){
                    alert('Your session has expired, please login again');
                    window.location.href = base_url;
                }
            },
        },
        "columns": [{
                "data": "designation_name"
            },
            {
                "data": "options"
            }
        ]
    });
});

function ConfirmDeletion() {
    if (confirm("{{ __('Are you sure you want to delete?') }}")) {
        return true;
    } else {
        return false;
    }
}
//Model
$(document).ready(function() {
    // the "href" attribute of the modal trigger must specify the modal ID that wants to be triggered
    $('.modal').modal();
});

function showaddForm() {
    $('.edit_hide').show();
    $('.add_hide').show();
    $('.edit_hide_btn').hide();
    $('#designation_name').val("");
    $('#updateid').val("");
    $('.modal').modal();
    $('.common-label').removeClass('force-active');
}

function showeditForm(designationid) {
    $('.edit_hide').hide();
    $('.add_hide').hide();
    $('#designation_name').val("");
    $('.edit_hide_btn').show();
    $('.modal').modal();
    loader.showLoader();
    var url = "{{ url(app()->getLocale().'/designation_detail') }}" + '?id=' + designationid;
    $.ajax({
        url: url,
        type: "GET",
        success: function(result) {
            $('#updateid').val(result.id);
            $('#updateid').attr('data-autoid', result.id);
            $('#designation_name').val(result.designation_name);
            loader.hideLoader();
            $('.common-label').addClass('force-active');
            $("#modal_add_edit").modal('open');
        }
    });
}
$(document).on('submit','form#designation_formValidate',function(){
    $("#modal-save-btn").prop('disabled',true);
    $("#modal-update-btn").prop('disabled',true);
});
</script>
@endsection