@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/themes/vertical-modern-menu-template/materialize.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/themes/vertical-modern-menu-template/style.css') }}">

@endsection
@section('headSecondSection')
@php 
  $edit_data = $data; 
  //dd($edit_data->arrear_date);
  $monthsrecords = CommonHelper::getMonthendsByJoinDate($edit_data->memberid,100,date('Y-m-01',strtotime($edit_data->doj)));
  $dojrecord = CommonHelper::getMonthendsOnJoinDate($edit_data->memberid,100,date('Y-m-01',strtotime($edit_data->doj)));
 // dd($dojrecord);
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
  input:not([type]), input[type=text]:not(.browser-default), input[type=password]:not(.browser-default), input[type=email]:not(.browser-default), input[type=url]:not(.browser-default), input[type=time]:not(.browser-default), input[type=date]:not(.browser-default), input[type=datetime]:not(.browser-default), input[type=datetime-local]:not(.browser-default), input[type=tel]:not(.browser-default), input[type=number]:not(.browser-default), input[type=search]:not(.browser-default), textarea.materialize-textarea {
    height: 2rem !important;
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
    $total_subs = !empty($dojrecord) ? $dojrecord->TOTALSUBCRP_AMOUNT : 0;
    $total_bf = !empty($dojrecord) ? $dojrecord->TOTALBF_AMOUNT : 0;
    $total_ins = !empty($dojrecord) ? $dojrecord->TOTALINSURANCE_AMOUNT : 0;
    $total_pay=0;
  @endphp
<div class="row">
<div class="content-wrapper-before"></div>
	
		<div class="container">
      <div class="card">
          <div class="card-title">
			<h5 class="padding-left-10">Monthend History Details 
        <a style="margin-left: 10px;" onclick="AddNewHistory()" class="btn waves-effect waves-light blue right ">{{__('Add Additional History') }}</a> &nbsp; &nbsp; 
        <a class="btn waves-effect waves-light right" href="{{ route('subscription.arrearentry',app()->getLocale())  }}">{{__('Back') }}</a>&nbsp; 
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
                         <th>
                          {{__('Salary') }}
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
                         <th>
                          {{$edit_data->salary}}
                        </th>
                       
                    </tr>
                </tbody>
            </table>
             
				    
										
             </div>
          
		</div>
		</div>
    <form class="formValidate" id="addarrear_formValidate" method="post" action="{{ route('monthend.updatehistoryrows',[app()->getLocale(),2]) }}">
         @csrf
    <div class="container">
      <div class="card">
      <div class="card-content">
         <div class="row">
            <div class="col s12 m12">
               @php
                $hide_doj_row = '';
                if(count($monthsrecords)>0){
                  $below_first = $monthsrecords[0];
                  if(($below_first->TOTALMONTHSDUE>1 || $below_first->TOTALMONTHSPAID>1) && empty($dojrecord)){
                    $hide_doj_row = 'hide';
                  }
                }
              @endphp
               <div class="row {{ $hide_doj_row }}">
                  
                     <div class="row">
                        <div class="col s12 m6 l3">
                           <label for="doj_date">{{__('First Month')}}</label>
                           <input id="doj_date" type="text" class="validate " readonly="" value="{{ date('01-m-Y',strtotime($edit_data->doj)) }}" name="doj_date">
                        </div>
                        <div class="col s12 m6 l3">
                           <label for="doj_subs">{{__('Subscription Amount')}}</label>
                           <input id="doj_subs" type="text" class="validate subscription_amount allow_decimal" value="{{ !empty($dojrecord) ? $dojrecord->TOTALSUBCRP_AMOUNT : 0 }}" name="doj_subs">
                        </div>
                         <div class="col s12 m6 l3">
                           <label for="doj_bf">{{__('BF Amount')}}</label>
                           <input id="doj_bf" type="text" class="bf_amount allow_decimal " value="{{ !empty($dojrecord) ? $dojrecord->TOTALBF_AMOUNT : 0 }}" name="doj_bf">
                        </div>
                        <div class="col s12 m6 l3">
                           <label for="doj_ins">{{__('Insurance Amount')}}</label>
                           <input id="doj_ins" type="text" class="insurance_amount allow_decimal" value="{{ !empty($dojrecord) ? $dojrecord->TOTALINSURANCE_AMOUNT : 0 }}" name="doj_ins">
                        </div>
                         <div class="col s12 m6 l2 hide">
                           <label for="entrance_fee">{{__('Entrance Fee')}}</label>
                           <input id="entrance_fee" type="text" class="allow_decimal" value="" name="entrance_fee">
                        </div>
                        <div class="col s12 m6 l2 hide">
                           <label for="hq_fee">{{__('Building Fund(HQ)')}}</label>
                           <input id="hq_fee" type="text" class="allow_decimal" value="" name="hq_fee">
                        </div>
                        
                     </div>
                     <div class="row hide">
                        <div class="col s7">
                        </div>
                        <div class="col s4 ">
                           
                        </div>
                     </div>
                 
               </div>
            </div>
         </div>
      </div>
    </div>
    </div>
    <div class="container">
        
         <input type="hidden" name="member_id" id="member_id" value="{{$edit_data->memberid}}">
        <div class="card">
            <table id="member_history" class="bordered">
                <thead style="background: -webkit-linear-gradient(45deg, #5e68a7, #9458ad);color:#fff;">
                    <tr>
                        <th>
                            {{__('Month') }}
                        </th>
                         <th>
                            {{__('Subscription Amount') }}
                        </th>
                        <th>
                            {{__('BF Amount') }}
                        </th>
                        <th>
                           {{__('Insurance Amount') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php
                      $slno=0;
                      /* $total_subs=0;
                      $total_bf=0;
                      $total_ins=0;
                      $total_pay=0; */
                    @endphp
                    @foreach($monthsrecords as $rows)
                    @php
                     //dd($rows);
                    @endphp
                     <tr style="pointer-events: none;background-color: #f4f8fb !important;" >
                        <td>
                            <input type="text" name="month_auto_id[]" id="month_auto_id_{{ $slno }}" class="hide" value="{{ $rows->autoid }}"/>
                            <input type="text" name="entry_date[]" id="entry_date_{{ $slno }}" value="{{ date('d-m-Y',strtotime($rows->StatusMonth)) }}" class=" entry_date" readonly="true" />
                            <input id="total_months" type="text" class="validate hide" readonly="" value="{{ $rows->TOTAL_MONTHS }}" name="total_months[]"/>
                        </td>
                        <td>
                            <input type="text" name="subscription_amount[]" id="subscription_amount_{{ $slno }}" value="{{ $rows->TOTALSUBCRP_AMOUNT }}" class="subscription_amount allow_decimal" />
                        </td>
                        <td>
                          <input type="text" name="bf_amount[]" id="bf_amount_{{ $slno }}" value="{{ $rows->TOTALBF_AMOUNT }}" class="bf_amount allow_decimal" />
                        </td>
                         <td>
                          <input type="text" name="insurance_amount[]" id="insurance_amount_{{ $slno }}" value="{{ $rows->TOTALINSURANCE_AMOUNT }}" class="insurance_amount allow_decimal" />
                        </td>
                    </tr>
                    @php
                      $slno++;
                      $total_subs += $rows->TOTALSUBCRP_AMOUNT;
                      $total_bf += $rows->TOTALBF_AMOUNT;
                      $total_ins += $rows->TOTALINSURANCE_AMOUNT;
                    @endphp
                    @endforeach
                    
                </tbody>
                  @php
                      $total_pay = $total_subs+$total_bf+$total_ins;
                  @endphp
                <tfoot style="background: #dbdbf7;font-weight:bold;">
                   <tr>
                        <th>
                            Total (<span id="total-payable">{{ number_format($total_pay,2,'.','') }}</span>)
                            <input type="text" style="background: none !important;" readonly="true" name="total-payamt" id="total-payamt" value="{{ number_format($total_pay,2,'.','') }}" class="hide" />
                        </th>
                        <th>
                            <input type="text" style="background: none !important;" readonly="true" name="total_subscription_amount" id="total_subscription_amount" value="{{ number_format($total_subs,2,'.','') }}" class="" />
                        </th>
                        <th>
                          <input type="text" style="background: none !important;" readonly="true" name="total_bf_amount" id="total_bf_amount" value="{{ number_format($total_bf,2,'.','') }}" class="" />
                        </th>
                         <th>
                          <input type="text" style="background: none !important;" readonly="true" name="total_insurance_amount" id="total_insurance_amount" value="{{ number_format($total_ins,2,'.','') }}" class="" />
                        </th>
                    </tr>
                </tfoot>
            </table>
            <div class="row">
              <div class="col m4">
                  <input type="text" name="totalno" id="totalno" class="hide" value="{{ $slno }}">
                  <label for="entrance_fee" style="margin-top: 10px;font-weight: bold;font-size: 16px;" class="right">{{__('Enter Your Name')}}</label>
               </div>
                <div class="col m3">
                
                  <input type="text" name="entered_name" id="entered_name" value="" class="" />
                
               </div>
               <div class="col m3">
                
                  <input type="submit" class="btn center" id="save" name="save" value="Submit" >
               
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
$("#members_list_sidebar_li_id").addClass('active');
$("#members_list_sidebar_a_id").addClass('active');

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
$(document).on('keyup', '.subscription_amount', function(){
  var total_subs = 0;
  $(".subscription_amount").each(function() {
      var subs_value = $(this).val()=='' ? 0 : $(this).val();
      total_subs += parseFloat(subs_value);
  });
  total_subs = total_subs.toFixed(2);
  $("#total_subscription_amount").val(total_subs);
  CalculateTotal();
});
$(document).on('keyup', '.bf_amount', function(){
  var total_bf = 0;
  $(".bf_amount").each(function() {
      var bf_value = $(this).val()=='' ? 0 : $(this).val();
      total_bf += parseFloat(bf_value);
  });
  total_bf = total_bf.toFixed(2);
  $("#total_bf_amount").val(total_bf);
  CalculateTotal();
});
$(document).on('keyup', '.insurance_amount', function(){
  var total_ins = 0;
  $(".insurance_amount").each(function() {
      var ins_value = $(this).val()=='' ? 0 : $(this).val();
      total_ins += parseFloat(ins_value);
  });
  total_ins = total_ins.toFixed(2);
  $("#total_insurance_amount").val(total_ins);
  CalculateTotal();
});

$(document).on('input', '.allow_decimal', function(){
   var self = $(this);
   self.val(self.val().replace(/[^0-9\.]/g, ''));
   if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) 
   {
     evt.preventDefault();
   }
 });

function CalculateTotal(){
  var total_pay = 0;
  var arrearamt = parseFloat('0');
 
  total_pay += parseFloat($('#total_subscription_amount').val());
  total_pay += parseFloat($('#total_bf_amount').val());
  total_pay += parseFloat($('#total_insurance_amount').val());
  if(total_pay>arrearamt){
    //alert("Total Amount is higher than Entered arear amount, Please enter correct amount");
  }
  $('#total-payable').text(total_pay);
  $('#total-payamt').val(total_pay);
}
$("#addarrear_formValidate").on("submit", function(evt) {
  var arrearamt = parseFloat('0');
  var total_payamt = parseFloat($('#total-payamt').val());
   var entered_name = $('#entered_name').val();
  if(entered_name==''){
    alert("Please enter your name");
    evt.preventDefault();
  }else{
    if (confirm('Are you sure you want to update?')) {
      return true;
    }else{
      return false;
    }
  }
    
});
function AddNewHistory(){
  var totalno = parseInt($("#totalno").val());
  var history = '<tr ><td> <input type="text" name="month_auto_id[]" id="month_auto_id_'+totalno+'" class="hide" value=""/><input type="text" name="entry_date[]" id="entry_date_'+totalno+'" value="" readonly class="datepicker-custom entry_date valid" aria-invalid="false"><input id="total_months" type="text" class="validate hide" readonly="" value="0" name="total_months[]"/></td>';
  history += '<td><input type="text" readonly name="subscription_amount[]" id="subscription_amount_'+totalno+'" value="0" class="subscription_amount allow_decimal"><input type="text" name="entrymode[]" id="entrymode_'+totalno+'" readonly="" value="S" class="entrymode hide" /></td>';
  history += '<td><input type="text" readonly name="bf_amount[]" id="bf_amount_6" value="0" class="bf_amount allow_decimal valid" aria-invalid="false"></td>';
  history += '<td><input type="text" readonly name="insurance_amount[]" id="insurance_amount_'+totalno+'" value="0" class="insurance_amount allow_decimal"></td></tr>'
  $("#member_history").append(history);
  var newtotalno = totalno+1;
  $("#totalno").val(newtotalno);
  $('.datepicker-custom').MonthPicker({ 
    Button: false, 
    MonthFormat: '01-mm-yy',
    OnAfterChooseMonth: function() { 
      //getDataStatus();
    } 
   });
}
$('.datepicker-custom').MonthPicker({ 
    Button: false, 
    MonthFormat: '01-mm-yy',
    OnAfterChooseMonth: function() { 
      //getDataStatus();
    } 
   });
</script>
@endsection