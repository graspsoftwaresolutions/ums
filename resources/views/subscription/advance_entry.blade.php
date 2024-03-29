@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
<link href="{{ asset('public/assets/css/jquery-ui-month.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/css/MonthPicker.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('headSecondSection')
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
  input[readonly] {
      background-color: #f4f8fb !important;
      opacity: 1;
  }
</style>
<style>
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
</style>
@endsection
@section('main-content')
@php 

@endphp
<div class="row">
<div class="content-wrapper-before"></div>
<form class="formValidate" id="addadvance_formValidate" method="post"
  action="{{ route('subscription.saveAdvance',app()->getLocale()) }}">
  @csrf
  <div class="container">
    <h5 class="padding-left-10"> Advance Entry Details <a class="btn waves-effect waves-light right" href="{{ route('subscription.advanceentry',app()->getLocale())  }}">{{__('Back') }}</a></h5>
    <div class="row">
      <div class="input-field col s12 m6">
        <label for="nric" class="common-label force-active">{{__('NRIC or Member Name or Member Number') }}*</label>
        <input id="nric" class="common-input"
          name="nric" type="text" data-error=".errorTxt1">
        <div class="errorTxt1"></div>
      </div>
      <div class="input-field col s12 m6">
        <label for="company_name"
          class="common-label force-active">{{__('Member Number') }}*</label>
        <input id="member_number" readonly class="common-input"
          name="member_number" type="text" data-error=".errorTxt2">
        <input type="hidden" name="membercode" id="memberid">
        <div class="errorTxt2"></div>
      </div>
      <div class="clearfix" style="clear:both"></div>
      <div class="col s12 m6">
        <label
          class="common-label  force-active">{{__('Member Name') }}</label>
        <input id="member_name" class="common-input"
          name="member_name" readonly type="text" data-error=".errorTxt3">
        <div class="errorTxt3"></div>
      </div>
      <div class="col s12 m6">
        <label
          class="common-label  force-active">{{__('Company Name') }}</label>
        <input id="company_name" class="common-input"
          name="company_name" readonly type="text" data-error=".errorTxt3">
        <input type="hidden" name="company_id" id="companyid">
        <div class="errorTxt3"></div>
      </div>
      <div class="col s12 m6">
        <label
          class="common-label  force-active">{{__('Company Branch') }}</label>
        <input id="company_branch" class="common-input"
          name="company_branch" readonly type="text" data-error=".errorTxt3">
        <input type="hidden" name="branch_id" id="companybranchid">
        <div class="errorTxt3"
          ></div>
      </div>
      <div class="col s12 m6">
        <label
          class="common-label  force-active">{{__('Status') }}</label>
        <input id="status" class="common-input"
          name="status" type="text" readonly data-error=".errorTxt3">
        <div class="errorTxt3"></div>
        <input type="hidden" name="status_id" id="statusid">
      </div>
      <div class="col s12 m6 hide">
        <label
          class="common-label  force-active">{{__('Advance Date') }}*</label>
        <input id="advance_date"  type="text" class="datepicker" value="{{ date('Y-m-d') }}" name="advance_date">
        <div class="errorTxt5"></div>
      </div>
      <div class="col s12 m6">
        <label
          class="common-label  force-active">{{__('Advance Date') }}*</label>
        <input id="from_date"  type="text" required class="datepicker-custom" name="from_date">
        <div class="errorTxt5"></div>
      </div>
      <div class="col s12 m6">
        <label
          class="common-label  force-active">{{__('No of Months') }}</label>
        <input id="no_of_months" class="common-input"
          name="no_of_months" required type="number" min="1" data-error=".errorTxt7">
        <div class="errorTxt7"></div>
        <div class="clearfix" style="clear:both"></div>
      </div>
     <!--  <div class="col s12 m6">
        <label
          class="common-label  force-active">{{__('To Date') }}*</label>
        <input id="to_date"  type="text" readonly="" class="datepicker-custom" name="to_date">
        <div class="errorTxt5"></div>
      </div> -->
      
      <div class="col s12 m6">
        <div class="row">
          
          <div class="col s12 m12">
            <label
              class="common-label  force-active">{{__('Advance Amount') }}*</label>
            <input id="advance_amount" class="common-input"
              name="advance_amount" type="text" data-error=".errorTxt6">
            <div class="errorTxt6"></div>
          </div>
        </div>
        <div class="clearfix" style="clear:both"></div>
      </div>
      
      <div class="col s12 m12">
        <div class="row">
          <div class="input-field col s12 m2">
            <p>
              <input type="submit" class="btn" id="save" name="save" value="Submit" >
            </P>
          </div>
          <div class="input-field col s12 m2">
            <p>
              <input type="button" class="btn" id="clear" name="clear" onClick="refreshPage()" value="Clear" >
            </P>
          </div>
        </div>
      </div>
    </div>
</form>
</div>
@endsection
@section('footerSection')

<script src="{{ asset('public/assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/assets/js/materialize.min.js') }}"></script>
<script src="{{ asset('public/assets/js/scripts/form-elements.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jquery.autocomplete.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/scripts/form-validation.js')}}" type="text/javascript"></script>

<script src="{{ asset('public/assets/js/jquery-ui-month.min.js')}}"></script>
<script src="{{ asset('public/js/MonthPicker.min.js')}}"></script>
<script src="{{ asset('public/js/sweetalert.min.js')}}"></script>
@endsection
@section('footerSecondSection')
<script>
function refreshPage(){
    window.location.reload();
}
$("#subscriptions_sidebars_id").addClass('active');
$("#subsarrear_sidebar_li_id").addClass('active');
$("#subarrear_sidebar_a_id").addClass('active');

	$('#addadvance_formValidate').validate({
rules: {
    nric: {
      required: true,
  },
  member_number: {
      required: true,
  },
  from_date: {
    required: true,
  },
  to_date: {
    required: true,
  },
  advance_amount: {
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
  from_date: {
    required: '{{__("Please Enter Date") }}',
  },
  to_date: {
    required: '{{__("Please Enter Date") }}',
  },
  advance_amount: {
    required: '{{__("Please Enter Arrear Amount") }}',
    digits: '{{__("please Enter numbers only")}}'
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

$("#nric").devbridgeAutocomplete({
	//lookup: countries,
	serviceUrl: "{{ URL::to('/get-nricmember-list') }}?searchkey="+ $("#nric").val(),
	type:'GET',
	//callback just to show it's working
	onSelect: function (suggestion) {
			$("#nric").val(suggestion.nric);
      $('#member_number').val(suggestion.member_number);
			$.ajax({
				url: "{{ URL::to('/get-nricmember-list-values') }}?member_id="+ $("#member_number").val(),
                type: "GET",
				dataType: "json",
				success: function(res) {
					
					     //$('#nric').val(res.nric);
                  //  $('#member_number').val(res.member_number);
              $('#memberid').val(res.memberid);
              $('#member_name').val(res.membername);
    					$('#company_branch').val(res.branch_name);
    					$('#company_name').val(res.company_name); 
              $('#status').val(res.status_name);  
              $('#statusid').val(res.statusid);
              $('#companybranchid').val(res.companybranchid);
              $('#companyid').val(res.companyid);
              $('#total_due_amount').val(res.totaldues);
              $('#no_of_due_months').val(res.duemonths);
				}
        
			});
			
	},
	showNoSuggestionNotice: true,
	noSuggestionNotice: 'Sorry, no matching results',
	onSearchComplete: function (query, suggestions) {
		if(!suggestions.length){
			//$("#member_number").val('');
		}
	}
});
$(document.body).on('click', '.autocomplete-no-suggestion' ,function(){
	$("#member_number").val('');
});
 $(document).ready(function() {
    $('.datepicker-custom').MonthPicker({
        Button: false,
        changeYear: true,
        MonthFormat: 'M/yy',
        OnAfterChooseMonth: function() {
           // getToMonths();
        }
    });
    $('.ui-button').removeClass("ui-state-disabled");
    //$('.datepicker-custom').MonthPicker({ Button: false,dateFormat: 'M/yy' });

});
 // function getToMonths(){
 //    var from_date = $("#from_date").val();
 //    var no_of_months = $("#no_of_months").val();
 //    if (from_date != "" && no_of_months != "") {
 //      var url = "{{ url(app()->getLocale().'/get_tomonths_number') }}" + '?from_date=' + from_date + '&no_of_months='+no_of_months;
 //      $.ajax({
 //        url: url,
 //        type: "GET",
 //        dataType: "json",
 //        success: function(result) {
 //          $("#no_of_months").val(result);
 //        }
 //      });
 //    }else{
 //       $("#no_of_months").val(0);
 //    }
 // }
// $("#addadvance_formValidate").on("submit", function(evt) {
//    var arrear_date = $("#arrear_date").val();
//    var datestatus = ValidateDate(arrear_date);
//    if(datestatus){
//       return true;
//    }else{
//       alert("Please choose correct date");
//       return false;
//    }
// });
// function ValidateDate(dtValue){
//     var dtRegex = new RegExp(/\b\d{1,2}[\/-]\d{1,2}[\/-]\d{4}\b/);
//     return dtRegex.test(dtValue);
// }
</script>
@endsection