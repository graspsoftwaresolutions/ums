
@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/materialize-stepper/materialize-stepper.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/themes/vertical-modern-menu-template/materialize.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/themes/vertical-modern-menu-template/style.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/vendors/data-tables/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css') }}">
	<style>
	.exportExcel{
  padding: 5px;
  border: 1px solid grey;
  margin: 5px;
  cursor: pointer;
}
</style>
@endsection
@section('headSecondSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/pages/data-tables.css') }}">
<style>
.filter{
    padding-top: 9px;
    background-color: #dad1d1c7;
}
</style>
@endsection
@section('main-content')
@php 

@endphp
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
                                        <h5 class="breadcrumbs-title mt-0 mb-0">{{__('New Memebers List')}}</h5>
                                        <ol class="breadcrumbs mb-0">
                                            <li class="breadcrumb-item"><a
                                                    href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard')}}</a>
                                            </li>
                                            <li class="breadcrumb-item active">{{__('New Members')}}
                                            </li>
                                        </ol>
                                    </div>
                                  
                                </div>
                            </div>
                           
                        </div>
                        <div class="row">
                        <div class="col s12">
                        <div class="card">
                            
                            <div class="card-content">
                            <h4 class="card-title">{{__('New Memebers ')}}  </h4> 
							<table width="100%" style="font-weight:bold">
								<tr>
									<td width="25%">{{__('Current Month ')}}</td>
									<td width="25%">: @php echo date('M/Y') @endphp</td>
									
									<td width="25%"></td>
									<td width="25%"></td>
								</tr>
							</table>
                            <h6 class="">   </h6>
                            <h6 class="">  </h6>
                                                                   
							<h6 class="">  </h6>
                          
                            <h6 class="">   </h6>
                            <div class="card filter">
								<form method="post" id="filtersubmit" action="{{route('subscription.memberfilter',app()->getLocale())}}">
									@csrf  
									<input type="hidden" name="id" value="{{ isset($row->MemberCode) ? $row->MemberCode : '' }}">
									<input type="hidden" name="memberid" value="{{ isset($row->memberid) ? $row->memberid : ''}}">
									<div class="row">                          
										<div class="input-field col s4">
											<i class="material-icons prefix">date_range</i>
											<input id="from_date" type="text" required class="validate datepicker" name="from_date">
											<label for="from_date">{{__('From Month and Year')}}</label>
										</div>
										<div class="input-field col s4">
											<i class="material-icons prefix">date_range</i>
											<input id="to_date" type="text" required class="validate datepicker" name="to_date">
											<label for="to_date">{{__('To Month and Year')}}</label>
										</div>
										<div class="input-field col s4">
										<input type="submit"  class="btn" name="search" value="{{__('Search')}}">
										</div>
									</div>
								</form>  
                            </div>
                            @include('includes.messages')
                            <div class="row">
                                <div class="col s12">
                                    <!-- Horizontal Stepper -->
									<div class="card">
                                    <div class="col sm12 m12">   
                                        <table id="page-length-option" class="display" cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                            <th>{{__('Member Number')}}</th>
                                            <th>{{__('Member Name')}}</th>
                                            <th>{{__('NRIC')}}</th>
                                            <th>{{__('Gender')}}</th>
                                            <th>{{__('Bank')}}</th>
                                            <th>{{__('Branch')}}</th>
                                            <th>{{__('Type')}}</th>
											<th>{{__('DOJ')}}</th>
											<th>{{__('Levy')}}</th>
											<th>{{__('Type')}}</th>
                                            </tr> 
                                                    <td><div calss="row"></td>
                                               
                                            </thead>
                                        </table>
                                        </div>          
									</div>
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
<!--<script src="{{ asset('public/assets/js/jquery.min.js') }}"></script> -->
<script src="{{ asset('public/assets/vendors/data-tables/js/jquery.dataTables.min.js') }}" type="text/javascript">
</script>
<script src="{{ asset('public/assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js') }}"
    type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/data-tables/js/dataTables.select.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/assets/js/materialize.min.js') }}"></script>
<script src="{{ asset('public/assets/js/scripts/form-elements.js') }}" type="text/javascript"></script>
@endsection
@section('footerSecondSection')
<script>
$("#subscriptions_sidebars_id").addClass('active');
$("#subscomp_sidebar_li_id").addClass('active');
$("#subcomp_sidebar_a_id").addClass('active');

	$(document).ready(function(){
		//loader.showLoader();
	
	});
	
    $("#filtersubmit").validate({
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
  $(document).ready(function() {
  var table = $('#page-length-option').DataTable({
    dom: 'Bfrtip',
    buttons: [
    {
      extend: 'excel',
      text: 'Export excel',
      className: 'exportExcel',
      filename: 'Export excel',
      exportOptions: {
        modifier: {
          page: 'all'
        }
      }
    }, 
    {
      extend: 'copy',
      text: '<u>C</u>opie presse papier',
      className: 'exportExcel',
      key: {
        key: 'c',
        altKey: true
      }
    }, 
    {
      text: 'Alert Js',
      className: 'exportExcel',
      action: function(e, dt, node, config) {
        alert('Activated!');
        // console.log(table);

        // new $.fn.dataTable.Buttons(table, {
        //   buttons: [{
        //     text: 'gfdsgfsd',
        //     action: function(e, dt, node, config) {
        //       alert('ok!');
        //     }
        //   }]
        // });
      }
    }]
  });

});


</script>
@endsection