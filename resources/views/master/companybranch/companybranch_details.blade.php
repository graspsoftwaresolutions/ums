@extends('layouts.admin')
@section('headSection')
@endsection
@section('headSecondSection')
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
                                        <h5 class="breadcrumbs-title mt-0 mb-0">
                                            @if(isset($data['branch_view']))
                                            {{__('Edit Bank Branch Details')}}
                                            @else
                                            {{__('Add Bank Branch Details')}}
                                            @endif
                                        </h5>
                                        <ol class="breadcrumbs mb-0">
                                            <li class="breadcrumb-item"><a
                                                    href="{{ route('home', app()->getLocale()) }}">{{__('Dashboard') }}</a>
                                            </li>
                                            <li class="breadcrumb-item active">{{__('Company Branch') }}
                                            </li>
                                        </ol>
                                    </div>
                                    <div class="col s2 m6 l6 ">
                                        <a class="btn waves-effect waves-light breadcrumbs-btn right"
                                            href="{{ route('master.branch', app()->getLocale()) }}">{{__('Company Branch List') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12">
                            <div class="card">
                                <div class="card-content">
                                    <h4 class="card-title">
                                        @if(isset($data['branch_view']))
                                        {{__('Edit Bank Branch')}}
                                        @else
                                        {{__('Add Bank Branch')}}
                                        @endif
                                    </h4>
                                    @php
                                    if(isset($data['branch_view'])){
                                        $row = $data['branch_view'][0];
                                        $addpage = 0;
                                    }else{
                                        $addpage = 1;
                                    }
                                    @endphp
                                    <div id="view-validations">
                                        <form class="formValidate" id="branchformValidate" method="post"
                                            action="{{route('master.savecompanybranch',app()->getLocale())}}">
                                            @csrf
                                            <input type="hidden" id="auto_id" name="auto_id"
                                                value="@isset($row){{$row->branchid}}@endisset">
                                            <div class="row" style="margin-bottom:0;">
                                                <div class="input-field col s12 m6">
                                                    <select class="error browser-default common-select selectpicker"
                                                        id="company_id" @if($addpage==1) onchange="return getBranchCode(this.value)" @endif name="company_id" data-error=".errorTxt1"
                                                        style="height: 4rem;">
                                                        <option value="" disabled="" selected="">
                                                            {{__('Select company') }}</option>
                                                        @foreach($data['company_view'] as $values)
                                                        <option value="{{$values->id}}" @isset($row) @php if($values->id
                                                            == $row->company_id) { echo "selected";} @endphp @endisset
                                                            >{{$values->company_name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="input-field">
                                                        <div class="errorTxt1"></div>
                                                    </div>
                                                </div>
                                                <div class="input-field col s12 m6" style="margin-bottom:0;">
                                                    <select class="error browser-default common-select selectpicker"
                                                        id="union_branch_id" name="union_branch_id"
                                                        data-error=".errorTxt2" style="height: 4rem;">
                                                        <option value="" disabled="" selected="">
                                                            {{__('Select Union Branch') }}</option>
                                                        @foreach($data['union_view'] as $value)
                                                        <option value="{{$value->id}}" @isset($row) @php if($value->id
                                                            == $row->union_branch_id) { echo "selected";} @endphp
                                                            @endisset >{{$value->union_branch}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="input-field">
                                                        <div class="errorTxt2"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="row">
                                                <div class="input-field col s12 m6">

                                                    <input name="branch_name" id="branch_name" class="common-input"
                                                        type="text" data-error=".errorTxt3"
                                                        value="@isset($row){{$row->branch_name}}@endisset">
                                                    <div class="errorTxt3"></div>
                                                    <label for="branch_name"
                                                        class="common-label">{{__('Branch Name') }}*</label>
                                                </div>
                                                <div class="input-field col s12 m6">
													@php
													$Defcountry = CommonHelper::DefaultCountry();
													@endphp
                                                    <select name="country_id" id="country_id" data-error=".errorTxt4"
                                                        class="error browser-default common-select selectpicker">
                                                        <option value="">{{__('Select country') }}</option>
                                                        @foreach($data['country_view'] as $value)
                                                        <option value="{{$value->id}}" @isset($row) @php if($value->id
                                                            == $row->country_id) { echo "selected";} @endphp @endisset @if($Defcountry==$value->id) selected @endif
                                                            >{{$value->country_name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="input-field">
                                                        <div class="errorTxt4"></div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col s12 m6">
                                                    <select name="state_id" id="state_id" data-error=".errorTxt5"
                                                        class="error browser-default common-select selectpicker"
                                                        style="height: 4rem;">
                                                        <option value="" selected>{{__('Select state') }}</option>
                                                        @foreach($data['state_view'] as $key=>$value)
                                                        <option value="{{$value->id}}" @isset($row) @php if($value->id
                                                            == $row->state_id) { echo "selected";} @endphp @endisset
                                                            >{{$value->state_name}}</option>
                                                        @endforeach
                                                    </select>

                                                    <div class="input-field">
                                                        <div class="errorTxt5"></div>
                                                    </div>
                                                </div>
                                                <div class="col s12 m6">
                                                    <select name="city_id" id="city_id" data-error=".errorTxt6"
                                                        class="error browser-default common-select selectpicker"
                                                        style="height: 4rem;">
                                                        <option value="">{{__('Select city') }}</option>
                                                        @foreach($data['city_view'] as $key=>$value)
                                                        <option value="{{$value->id}}" @isset($row) @php if($value->id
                                                            == $row->city_id) { echo "selected";} @endphp @endisset
                                                            >{{$value->city_name}}</option>
                                                        @endforeach
                                                    </select>

                                                    <div class="input-field">
                                                        <div class="errorTxt6"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input-field col s12 m6">
                                                    <label for="postal_code" class="common-label">{{__('Postal Code') }}
                                                        *</label>
                                                    <input id="postal_code" name="postal_code" class="common-input"
                                                        value="@isset($row){{$row->postal_code}}@endisset" type="text"
                                                        data-error=".errorTxt7">
                                                    <div class="errorTxt7"></div>
                                                </div>
                                                <div class="input-field col s12 m6">
                                                    <label for="address_one"
                                                        class="common-label">{{__('Address Line 1') }}*</label>
                                                    <input id="address_one" name="address_one" class="common-input"
                                                        type="text" value="@isset($row){{$row->address_one}}@endisset"
                                                        data-error=".errorTxt8">
                                                    <div class="errorTxt8"></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input-field col s12 m6">
                                                    <label for="address_two"
                                                        class="common-label">{{__('Address Line 2') }}</label>
                                                    <input id="address_two" name="address_two" class="common-input"
                                                        type="text" value="@isset($row){{$row->address_two}}@endisset"
                                                        data-error=".errorTxt9">
                                                    <div class="errorTxt9"></div>
                                                </div>
                                                <div class="input-field col s12 m6">
                                                    <label for="address_three"
                                                        class="common-label">{{__('Address Line 3') }}</label>
                                                    <input id="address_three" name="address_three" class="common-input"
                                                        type="text" value="@isset($row){{$row->address_three}}@endisset"
                                                        data-error=".errorTxt10">
                                                    <div class="errorTxt10"></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input-field col s12 m6">
                                                    <label for="phone" class="common-label">{{__('Phone Number') }}
                                                        *</label>
                                                    <input id="phone" name="phone" class="common-input"
                                                        value="@isset($row){{$row->phone}}@endisset" type="text"
                                                        data-error=".errorTxt11">
                                                    <div class="errorTxt11"></div>
                                                </div>
                                                <div class="input-field col s12 m6">
                                                    <label for="mobile" class="common-label">{{__('Mobile Number') }}
                                                        *</label>
                                                    <input id="mobile" name="mobile" class="common-input"
                                                        value="@isset($row){{$row->mobile}}@endisset" type="text"
                                                        data-error=".errorTxt12">
                                                    <div class="errorTxt12"></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input-field col s12 m6">
                                                    <label for="email" class="common-label">{{__('Email') }} *</label>
                                                    <input id="email" name="email" @isset($row) readonly @endisset
                                                        class="common-input" type="text"
                                                        value="@isset($row){{$row->email}}@endisset"
                                                        data-error=".errorTxt13">
                                                    <div class="errorTxt13"></div>
                                                </div>
                                                <div class="input-field col s12 m6">
                                                    <p>
                                                        <label>
                                                            <input type="checkbox" name="is_head"
                                                                class="common-checkbox" id="is_head" value="1"
                                                                @isset($row) {{ $row->is_head == '1' ? 'checked' : '' }}
                                                                @endisset />
                                                            <span>{{__('Head') }}</span>
															
                                                        </label>
														 @isset($row)
																@if($row->is_head == '1')
																	</br>
																	</br>
																	<span style="color: rgba(255, 255, 255, 0.901961);" class="gradient-45deg-indigo-light-blue padding-2 medium-small">	
																		If you uncheck Head, please make sure that change another branch as head
																	</span>
																@endif
															 @endisset
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input-field col s12 m6">
                                                    <label for="branch_shortcode" class="common-label">{{__('Short Code') }}
                                                        *</label>
                                                    <input id="branch_shortcode" readonly="" name="branch_shortcode" class="common-input allow_decimal"
                                                        value="@isset($row){{$row->branch_shortcode}}@endisset" placeholder="" type="text"
                                                        data-error=".errorTxt16">
                                                    <div class="errorTxt16"></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <button id="form-save-btn" class="btn waves-effect waves-light right submit"
                                                        type="submit" name="action">
                                                        @if(isset($row))
                                                        {{__('Update') }}
                                                        @else{{__('Save') }}
                                                        @endif
                                                        <!--i class="material-icons right">send</i-->
                                                    </button>
                                                </div>
                                            </div>

                                        </form>
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
<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
@endsection
@section('footerSecondSection')
<script src="{{ asset('public/assets/js/scripts/form-validation.js')}}" type="text/javascript"></script>
<script>
$("#masters_sidebars_id").addClass('active');
$("#branch_sidebar_li_id").addClass('active');
$("#branch_sidebar_a_id").addClass('active');
</script>
<script>
$(document).ready(function() {
    //state
    $('#country_id').change(function() {
        var countryID = $(this).val();

        if (countryID) {
            $.ajax({
                type: "GET",
                dataType: "json",
                url: " {{ URL::to('/get-state-list') }}?country_id=" + countryID,
                success: function(res) {
                    if (res) {
                        $("#state_id").empty();
                        $("#city_id").empty();
                        $("#state_id").append($('<option></option>').attr('value', '').text(
                            "Select State"));
                        $("#city_id").append($('<option></option>').attr('value', '').text(
                            "Select City"));
                        $.each(res, function(key, entry) {

                            $("#state_id").append($('<option></option>').attr(
                                'value', entry.id).text(entry.state_name));
                            // var select = $("#state");
                            // select.material_select('destroy');
                            //select.empty();

                        });
                    } else {
                        $("#state_id").empty();
                    }
                    //console.log(res);
                }
            });
        } else {
            $("#state_id").empty();
            $("#city_id").empty();
        }
    });
    //$("#country").trigger('change');
    // $("#state_id").trigger('change');
    $('#state_id').change(function() {
        var StateId = $(this).val();

        if (StateId != '' && StateId != 'undefined') {
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "{{ URL::to('/get-cities-list') }}?State_id=" + StateId,
                success: function(res) {
                    if (res) {
                        $('#city_id').empty();
                        $("#city_id").append($('<option></option>').attr('value', '').text(
                            "Select City"));
                        $.each(res, function(key, entry) {
                            $('#city_id').append($('<option></option>').attr(
                                'value', entry.id).text(entry.city_name));

                        });
                    } else {
                        $('#city_id').empty();
                    }
                }
            });
        } else {
            $('#city_id').empty();
        }
    });

});
</script>
<script>
$("#branchformValidate").validate({
    rules: {
        company_id: {
            required: true,
        },
        union_branch_id: {
            required: true,
        },
        branch_name: {
            required: true,
        },
        phone: {
            required: true,
			
        },
        mobile: {
            required: true,
			
        },
        email: {
            required: true,
            email:true,
            remote: {
                url: "{{ url(app()->getLocale().'/companybranch_emailexists')}}",
                data: {
                    db_autoid: function() {
                        return $("#auto_id").val();
                    },
                    _token: "{{csrf_token()}}",
                    email: $(this).data('email')
                },
                type: "post",
            },
        },
        postal_code: {
            required: true,
			number: true,
            minlength:5,
            maxlength:8,
        },
        address_one: {
            required: true,
        },
        state_id: {
            required: true,
        },
        country_id: "required",
        city_id: {
            required: true,
        },
        branch_shortcode: {
            required: true,
        },
    },
    //For custom messages
    messages: {

        company_id: {
            required: '{{__("Please enter your company") }}',

        },
        union_branch_id: {
            required: '{{__("Please enter your union branch name") }}',

        },
        branch_name: {
            required: '{{__("Please enter your branch name") }}',

        },
        phone: {
            required: '{{__("Please enter your phone number") }}',

        },
        mobile: {
            required: '{{__("Please enter your mobile number") }}',

        },
        email: {
            required: '{{__("Please enter valid email") }}',
			remote: '{{__("Email Already exists") }}',
        },
        country_id: {
            required: '{{__("Please choose  your Country") }}',
        },
        state_id: {
            required: '{{__("Please choose  your State") }}',
        },
        city_id: {
            required: '{{__("Please choose  your city") }}',
        },
        postal_code: {
            required: '{{__("Please enter postal code") }}',
        },
        address_one: {
            required: '{{__("Please enter your address") }}',
        },
        branch_shortcode: {
            required: '{{__("Please enter Branch Shortcode") }}',
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
$(document).on('submit','form#branchformValidate',function(){
    $("#form-save-btn").prop('disabled',true);
    loader.showLoader();
});
function getBranchCode(companyid){
   
    $.ajax({
        type: "GET",
        //dataType: "json",
        url: "{{ URL::to('/get-branch-count') }}?company_id=" + companyid,
        success: function(res) {
          
            if (res) {
                $('#branch_shortcode').val(res);
            } else {
                $('#branch_shortcode').val('0001');
            }
        }
    });
}
$(document).on('input', '.allow_decimal', function(){
   var self = $(this);
   self.val(self.val().replace(/[^0-9\.]/g, ''));
   if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) 
   {
     evt.preventDefault();
   }
 });
</script>
@endsection