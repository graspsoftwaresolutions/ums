@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
<link href="{{ asset('public/assets/css/jquery-ui-month.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/css/MonthPicker.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('headSecondSection')
@php 
  $edit_data = $data; 
  //dd($edit_data->arrear_date);
  $duerecords = CommonHelper::getDueMonthendsByDate($edit_data->memberid,$edit_data->no_of_months,$edit_data->arrear_date);
@endphp
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
  @if($edit_data->no_of_months<=5)
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
  @endif
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
			<h5 class="padding-left-10">Arrear Entry Details <a class="btn waves-effect waves-light right" href="{{ route('subscription.arrearentry',app()->getLocale())  }}">{{__('Back') }}</a></h5>
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
                          {{__('Arrear Date') }}
                        </th>
                        <th>
                          {{__('Arrear Amount') }}
                        </th>
                        <th>
                          {{__('No of months') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                     <tr>
                        <td>
                            {{$edit_data->member_number}}
                        </td>
                         <td>
                            {{$edit_data->membername}}
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
                         {{$edit_data->arrear_date}}
                        </td>
                        <td>
                          {{$edit_data->arrear_amount}}
                        </td>
                        <td>
                          {{$edit_data->no_of_months}}
                        </td>
                    </tr>
                </tbody>
            </table>
             
				    
										
             </div>
          
		</div>
		</div>
    <div class="container">
        <form class="formValidate" id="addarrear_formValidate" method="post" action="{{ route('subscription.udatearrearrows',app()->getLocale()) }}">
         @csrf
         <input type="hidden" name="arrear_id" id="arrear_id" value="{{$edit_data->arrearid}}">
         <input type="hidden" name="member_id" id="member_id" value="{{$edit_data->memberid}}">
         <input type="hidden" name="arrear_date" id="arrear_date" value="{{$edit_data->arrear_date}}">
        <div class="card">
            <table class="bordered">
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
                      $total_subs=0;
                      $total_bf=0;
                      $total_ins=0;
                      $total_pay=0;
                    @endphp
                    @foreach($duerecords as $rows)
                     <tr>
                        <td>
                            <input type="text" name="entry_date[]" id="entry_date_{{ $slno }}" value="{{ date('d-m-Y',strtotime($rows->StatusMonth)) }}" class="datepicker-custom entry_date" readonly="true" />
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
                    @php
                      $due_count = count($duerecords);
                      //dd($due_count);
                    @endphp
                     @for($rowi=0;$rowi<($edit_data->no_of_months-$due_count); $rowi++)
                     <tr>
                        <td>
                            <input type="text" name="entry_date[]" id="entry_date_{{ $slno }}" value="" class="datepicker-custom entry_date" readonly="true" />
                        </td>
                        <td>
                            <input type="text" name="subscription_amount[]" id="subscription_amount_{{ $slno }}" value="" class="subscription_amount allow_decimal" />
                        </td>
                        <td>
                          <input type="text" name="bf_amount[]" id="bf_amount_{{ $slno }}" value="" class="bf_amount allow_decimal" />
                        </td>
                         <td>
                          <input type="text" name="insurance_amount[]" id="insurance_amount_{{ $slno }}" value="" class="insurance_amount allow_decimal" />
                        </td>
                    </tr>
                    @php
                      $slno++;
                    @endphp
                    @endfor
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
              <div class="col m6">
               
               </div>
               <div class="col m6">
                <p>
                  <input type="submit" class="btn center" id="save" name="save" value="Submit" >
                </P>
               </div>
            </div>
        </div>
        </form>
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
<script src="{{ asset('public/assets/js/jquery-ui-month.min.js')}}"></script>
<script src="{{ asset('public/js/MonthPicker.min.js')}}"></script>
@endsection
@section('footerSecondSection')
<script>
$("#subscriptions_sidebars_id").addClass('active');
$("#subsarrear_sidebar_li_id").addClass('active');
$("#subarrear_sidebar_a_id").addClass('active');

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
$('.datepicker,.datepicker-custom').MonthPicker({ 
    Button: false, 
    MonthFormat: '01-mm-yy',
    OnAfterChooseMonth: function() { 
      //getDataStatus();
    } 
   });
$(".subscription_amount").keyup(function(){
  var total_subs = 0;
  $(".subscription_amount").each(function() {
      var subs_value = $(this).val()=='' ? 0 : $(this).val();
      total_subs += parseFloat(subs_value);
  });
  total_subs = total_subs.toFixed(2);
  $("#total_subscription_amount").val(total_subs);
  CalculateTotal();
});
$(".bf_amount").keyup(function(){
  var total_bf = 0;
  $(".bf_amount").each(function() {
      var bf_value = $(this).val()=='' ? 0 : $(this).val();
      total_bf += parseFloat(bf_value);
  });
  total_bf = total_bf.toFixed(2);
  $("#total_bf_amount").val(total_bf);
  CalculateTotal();
});
$(".insurance_amount").keyup(function(){
  var total_ins = 0;
  $(".insurance_amount").each(function() {
      var ins_value = $(this).val()=='' ? 0 : $(this).val();
      total_ins += parseFloat(ins_value);
  });
  total_ins = total_ins.toFixed(2);
  $("#total_insurance_amount").val(total_ins);
  CalculateTotal();
});

$(".allow_decimal").on("input", function(evt) {
   var self = $(this);
   self.val(self.val().replace(/[^0-9\.]/g, ''));
   if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) 
   {
     evt.preventDefault();
   }
 });

function CalculateTotal(){
  var total_pay = 0;
  var arrearamt = parseFloat('{{$edit_data->arrear_amount}}');
 
  total_pay += parseFloat($('#total_subscription_amount').val());
  total_pay += parseFloat($('#total_bf_amount').val());
  total_pay += parseFloat($('#total_insurance_amount').val());
  if(total_pay>arrearamt){
    alert("Total Amount is higher than Entered arear amount, Please enter correct amount");
  }
  $('#total-payable').text(total_pay);
  $('#total-payamt').val(total_pay);
}
$("#addarrear_formValidate").on("submit", function(evt) {
  var arrearamt = parseFloat('{{$edit_data->arrear_amount}}');
  var total_payamt = parseFloat($('#total-payamt').val());
  if(total_payamt!=arrearamt){
    alert("Total Amount is not same, Please enter correct amount");
    evt.preventDefault();
  }else{
    if (confirm('Are you sure you want to update?')) {
      return true;
    }else{
      return false;
    }
  }
    
});
</script>
@endsection