@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/themes/vertical-modern-menu-template/materialize.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/themes/vertical-modern-menu-template/style.css') }}">

@endsection
@section('headSecondSection')
@php 
  $edit_data = $data['member_view']; 
 
@endphp
<link href="{{ asset('public/assets/css/jquery-ui-month.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/css/MonthPicker.min.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/custom_respon.css') }}">
<style type="text/css">
	.autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; cursor:pointer; }
	.autocomplete-suggestion { padding: 8px 5px; white-space: nowrap; overflow: hidden; }
	.autocomplete-selected { background: #F0F0F0; }
	.autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
	.autocomplete-group { padding: 8px 5px; }
	.autocomplete-group strong { display: block; border-bottom: 1px solid #000; }
	.padding-left-10{
		padding-left:10px;
	}
	.padding-left-20{
		padding-left:20px;
	}
	.padding-left-24{
		padding-left:24px;
	}
	.padding-left-40{
		padding-left:40px;
	}
	#irc_confirmation_area {
		pointer-events: none;
	}
	.branch 
	{
    	pointer-events: none;
		background-color: #f4f8fb !important;
	}
</style>
<style>
	td, th {
      display: table-cell;
      padding: 10px 5px !important;
      text-align: left;
      vertical-align: middle;
      border-radius: 2px;
  }
 
/*  @if(1<=5)
  #main.main-full {
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
  }
  @endif*/
</style>
@endsection
@section('main-content')
@php
    
@endphp
<div class="row">
<div class="content-wrapper-before"></div>
	
		<div class="container">
      <div class="card">
          <div class="card-title">
			<h5 class="padding-left-10">Member Details 
        
      </h5>
      </div>
			<div class="row">
           
            <table class="bordered">
                <thead>
                    <tr>
                        <th>
                            {{__('Member Number') }}
                        </th>
                         <th>
                            {{__('Member Name') }}
                        </th>
                        <th>
                            {{__('Company Name') }}
                        </th>
                        <th>
                           {{__('Company Branch') }}
                        </th>
                        <th>
                            {{__('Status') }}
                        </th>
                        <th>
                          {{__('DOJ') }}
                        </th>
                        
                        
                    </tr>
                </thead>
                <tbody>
                     <tr>
                        <td>
                            {{$edit_data->member_number}}
                        </td>
                         <td>
                            {{$edit_data->name}}
                        </td>
                        <td>
                           {{$edit_data->company_name}}
                        </td>
                        <td>
                           {{$edit_data->branch_name}}
                        </td>
                        <td>
                           {{$edit_data->status_name}}
                        </td>
                        <td>
                         {{ date('d-m-Y',strtotime($edit_data->doj)) }}
                        </td>
                       
                    </tr>
                </tbody>
            </table>
             
				    
										
             </div>
          
		</div>
		</div>
    <form class="formValidate" id="addarrear_formValidate" method="post" action="{{ route('member.levyupdate',app()->getLocale()) }}">
         @csrf
    <div class="container">
      <div class="card">
      <div class="card-content">
         <div class="row">
            <div class="col s12 m12">
              @php
               
              @endphp
              
               <div class=" ">
                    <h5>Profile Update</h5>
                    <input id="memberid" name="memberid" type="text" class="hide" value="{{$edit_data->mid}}">
                    <br>
                     <div class="row">
                         <div class="col s12 l6">
                              <label>{{__('Levy') }}</label>
                              <select name="levy" id="levy" onChange="return HideLevy(this.value)" class="error browser-default selectpicker">
                                  <option value="">{{__('Select levy') }}</option>
                                  <option value="Not Applicable" {{ $edit_data->levy == 'Not Applicable' ? 'selected' : '' }}> N/A</option>
                                  <option value="Yes" {{ $edit_data->levy == 'Yes' ? 'selected' : '' }}>Yes</option>
                                  <option value="NO" {{ $edit_data->levy == 'NO' ? 'selected' : '' }}>No</option>
                              </select>
                          </div>
                          
                          <div id="levydiv" class="col s12 l6 @if($edit_data->levy == 'NO') hide @endif ">
                            <label for="levy_amount" class="force-active">{{__('Levy Amount') }} </label>
                              <input id="levy_amount" name="levy_amount" type="text" value="{{$edit_data->levy_amount}}">
                              
                          </div>
                          <div class="clearfix"></div>
                          <br>
                           <div class="clearfix"></div>
                          <div class="col s12 l6">
                              <label>{{__('TDF') }}</label>
                              <select name="tdf" id="tdf" onChange="return HideTDF(this.value)" class="error browser-default selectpicker">
                                  <option value="0">Select TDF</option>
                                  <option value="Not Applicable" {{ $edit_data->tdf == 'Not Applicable' ? 'selected' : '' }}> N/A</option>
                                  <option value="Yes" {{ $edit_data->tdf == 'Yes' ? 'selected' : '' }}>Yes</option>
                                  <option value="NO" {{ $edit_data->tdf == 'NO' ? 'selected' : '' }}>No</option>
                              </select>
                          </div>
                         
                          <div id="tdfdiv" class="col s12 l6 @if($edit_data->tdf == 'NO') hide @endif">
                             <label for="tdf_amount" class="force-active">{{__('TDF Amount') }} </label>
                              <input id="tdf_amount" name="tdf_amount" type="text" value="{{$edit_data->tdf_amount}}">
                             
                          </div>
                           <div class="input-field col s12 m6 ">
                                <label for="lastupdate" class="force-active">Last Updated Date</label>
                                <input id="lastupdate" name="lastupdate" readonly="" value="{{ $edit_data->levy_update_date!='' ? date('d-m-Y  h:i:s',strtotime($edit_data->levy_update_date)) : '' }}" type="text" data-error=".errorTxt11">
                                <div class="errorTxt11"></div>
                            </div>

                          <div class="col m12 s12 " style="padding-top:5px;">
                              </br>
                              <button id="submit-upload" class="mb-6 btn waves-effect waves-light purple lightrn-1 form-download-btn right" type="submit">{{__('Submit') }}</button>

                          </div>

                        
                        
                     </div>
                     
               </div>
            </div>
         </div>
      </div>
    </div>
    </div>
   
     </form>
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
<script src="{{ asset('public/assets/js/jquery-ui-month.min.js')}}"></script>
<script src="{{ asset('public/js/MonthPicker.min.js')}}"></script>
@endsection
@section('footerSecondSection')
<script>
$("#data_cleaning_sidebars_id").addClass('active');
$("#members_cleanlist_sidebar_li_id").addClass('active');
$("#members_cleanlist_sidebar_a_id").addClass('active');

	$('#addarrear_formValidate').validate({
rules: {
    nric: {
      required: true,
  },
  member_number: {
      required: true,
  },
  arrear_date: {
    required: true,
  },
  arrear_amount: {
    required: true,
      number: true,
  },
},
//For custom messages
messages: {
    nric: {
      required: '{{__("Please Enter NRIC") }}',
  },
  member_number: {
      required: '{{__("Please Enter Member Number") }}',
  },
  arrear_date: {
    required: '{{__("Please Enter Arrear Date") }}',
  },
  arrear_amount: {
    required: '{{__("Please Enter Arrear Amount") }}',
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
// $('.datepicker').datepicker({
//     format: 'dd/mm/yyyy',
//     autoHide: true,
// });

$(document).on('input', '.allow_decimal', function(){
   var self = $(this);
   self.val(self.val().replace(/[^0-9\.]/g, ''));
   if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) 
   {
     evt.preventDefault();
   }
 });
 function HideLevy(levytitle){
      if(levytitle=='NO'){
          $("#levydiv").addClass('hide');
      }else{
          $("#levydiv").removeClass('hide');
      }
  }
  function HideTDF(tdftitle){
      if(tdftitle=='NO'){
          $("#tdfdiv").addClass('hide');
      }else{
          $("#tdfdiv").removeClass('hide');
      }
  }


// $("#addarrear_formValidate").on("submit", function(evt) {
//   var arrearamt = parseFloat('0');
//   var total_payamt = parseFloat($('#total-payamt').val());
//    var entered_name = $('#entered_name').val();
//   if(entered_name==''){
//     alert("Please enter your name");
//     evt.preventDefault();
//   }else{
//     if (confirm('Are you sure you want to update?')) {
//       return true;
//     }else{
//       return false;
//     }
//   }
    
// });


</script>
@endsection