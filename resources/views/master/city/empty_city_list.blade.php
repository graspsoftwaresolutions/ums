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
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/export-button.css') }}">
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
                                        <h5 class="breadcrumbs-title mt-0 mb-0">{{__('City List') }}</h5>
                                        <ol class="breadcrumbs mb-0">
                                            <ol class="breadcrumbs mb-0">
                                                <li class="breadcrumb-item"><a
                                                        href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard') }}</a>
                                                </li>
                                                <li class="breadcrumb-item active">{{__('City') }}
                                                </li>
                                            </ol>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                        <div class="col s12">
                            <div class="card">
                                <div class="card-content">
                                    <!--<h4 class="card-title">{{__('City List') }}[ 0 Members ]</h4>-->
                                    @include('includes.messages')
                                    <div class="row">
                                        <div class="col s12">
                                            <table id="page-length-option" class="display">
                                                <thead>
                                                    <tr>
                                                        <!-- <th>{{__('Country Name') }}</th> -->
                                                        <th>{{__('State Name') }}</th>
                                                        <th>{{__('City Name') }}</th>
                                                        <th style="text-align:center !important;"> {{__('Action') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $get_roles = Auth::user()->roles;
                                                        $user_role = $get_roles[0]->slug;
                                                        $user_id = Auth::user()->id;
                                                    @endphp
                                                    @foreach($data['city_view'] as $city)
                                                    @php
                                                        $statecity = CommonHelper::getStateCity($city->state_id);
                                                        $memberscount = CommonHelper::getcityusedcount($city->id);
                                                    @endphp
                                                    @if($memberscount==0)
                                                    <tr>
                                                        <td>{{$statecity}}</td>
                                                        <td>{{$city->city_name}}</td>
                                                        <td>
                                                            <a><form style="display:inline-block;" action="{{ url(app()->getLocale()) }}/city-deleteone/{{$city->id}}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="" style="background:none;border:none;" onclick="return ConfirmDeletion()"><i class="material-icons" style="color:red;">delete</i></button> </form>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    @endforeach
                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="modal_add_edit" class="modal modal-fixed-header">
                        <div class="modal-header" id="modal-header">
                            
                                <h4>{{__('City Details') }}</h4>
                                </div>
                                <div class="modal-content">
                                
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
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
<script>
$("#masters_sidebars_id").addClass('active');
$("#city_0sidebar_li_id").addClass('active');
$("#city_0sidebar_a_id").addClass('active');

$(function() {
    $('select[name=country_id]').change(function() {

        var url = "{{ url('get-state-list') }}" + '?country_id=' + $(this).val();

        $.get(url, function(data) {
            var select = $('form select[name= state_id]');

            select.empty();

            $.each(data, function(key, value) {
                select.append('<option value=' + value.id + '>' + value.state_name +
                    '</option>');
            });
        });
    });
});
$(function() {
    $('#page-length-option').DataTable({
    
    "columnDefs": [{
      "visible": false,
      "targets": 0
    }],
    "order": [
      [0, 'asc']
    ],
    "lengthMenu": [
        [3000, 10, 25, 50, 100 ],
        ['All',10, 25, 50, 100 ]
    ],
	dom: 'lBfrtip', 
        buttons: [
		   {
			   extend: 'pdf',
			   footer: true,
			   exportOptions: {
					columns: [0,1]
				},
                title : 'Cities List', 
                text: '<i class="fa fa-file-pdf-o"></i>',
                titleAttr: 'pdf'
		   },
		   {
			   extend: 'excel',
			   footer: false,
			   exportOptions: {
					columns: [0,1]
				},
				title : 'Cities List',
                text:    '<i class="fa fa-file-excel-o"></i>',
                titleAttr: 'excel'
		   },
			{
			   extend: 'print',
			   footer: false,
			   exportOptions: {
					columns: [0,1]
				},
				title : 'Cities List',
                text:   '<i class="fa fa-files-o"></i>',
                titleAttr: 'print'
		   }  
		],
    "drawCallback": function (settings) {
      var api = this.api();
      var rows = api.rows({
        page: 'current'
      }).nodes();
      var last = null;

      api.column(0, {
        page: 'current'
      }).data().each(function (group, i) {
        if (last !== group) {
          $(rows).eq(i).before(
            '<tr class="group"><td colspan="2">' + group + '</td></tr>'
          );

          last = group;
        }
      });
    },
	"processing": true,
    "serverSide": false,
       
  });

});



function ConfirmDeletion() {
    if (confirm("{{ __('Are you sure you want to delete?') }}")) {
        return true;
    } else {
        return false;
    }
}

// Form Validation
$("#cityformValidate").validate({
    rules: {
        country_id: {
            required: true,
        },
        state_id: {
            required: true,
        },
        city_name: {
            required: true,
            remote: {
                url: "{{ url(app()->getLocale().'/city_nameexists')}}",
                data: {
                    city_id: function() {
                        return $("#updateid").val();
                    },
                    country_id: function() {
                        return $("#country_id").val();
                    },
                    state_id: function() {
                        return $("#state_id").val();
                    },
                    _token: "{{csrf_token()}}",
                    city_name: $(this).data('city_name')
                },
                type: "post",
            },
        },
    },
    //For custom messages
    messages: {
        country_id: {
            required: '{{__("Please Choose Country Name") }}',
        },
        state_id: {
            required: '{{__("Please Choose State Name") }}',
        },
        city_name: {
            required: '{{__("Please enter City Name") }}',
            remote: '{{__("City Name Already exists") }}',
        }
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

//Model
$(document).ready(function() {
    // the "href" attribute of the modal trigger must specify the modal ID that wants to be triggered
    $('.modal').modal();
});


$(document).on('submit','form#cityformValidate',function(){
    $("#modal-save-btn").prop('disabled',true);
    $("#modal-update-btn").prop('disabled',true);
    //loader.showLoader();
});
</script>
@endsection