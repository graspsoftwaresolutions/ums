@extends('layouts.admin')
@section('headSection')
@endsection
@section('headSecondSection')
@endsection
@section('main-content')
<div id="main">
    <div class="row">
        <div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
        <div class="col s12">
            <div class="container">
                <div class="section ">
                    <!-- BEGIN: Page Main-->
                    <div class="row">
                        <div class="breadcrumbs-dark pb-0 pt-4" id="breadcrumbs-wrapper">
                            <!-- Search for small screen-->
                            <div class="container">
                                <div class="row">
                                    <div class="col s10 m6 l6">
                                        @if(isset($data['union_branch'])){
                                        <h5 class="breadcrumbs-title mt-0 mb-0">{{__('Edit Union Branch Details') }}
                                        </h5>
                                        @else
                                        <h5 class="breadcrumbs-title mt-0 mb-0">{{__('Add Union Branch Details')}}</h5>
                                        @endif
                                        <ol class="breadcrumbs mb-0">
                                            <li class="breadcrumb-item"><a
                                                    href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard') }}</a>
                                            </li>
                                            <li class="breadcrumb-item active">{{__('Union Branch') }}
                                            </li>
                                        </ol>
                                    </div>
                                    <div class="col s2 m6 l6 ">
                                        <a class="btn waves-effect waves-light breadcrumbs-btn right"
                                            href="{{route('master.unionbranch', app()->getLocale())}}">{{__('Back') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12">
                            <div class="card">
                                <div class="card-content">
                                    @include('includes.messages')
                                    @if(isset($data['union_branch']))
                                    <h4 class="card-title">{{__('Edit Union Branch') }}</h4>
                                    @else
                                    <h4 class="card-title">{{__('Add Union Branch') }}</h4>
                                    @endif
                                    @php
                                    if(isset($data['union_branch'])){
                                    $values = $data['union_branch'][0];
                                    }
                                    @endphp
                                    <div id="view-validations">
                                        <form class="formValidate" id="unionbranch_formValidate"
                                            enctype="multipart/form-data" method="post"
                                            action="{{route('master.saveunionbranch', app()->getLocale())}}">
                                            @csrf
                                            <input type="hidden" name="auto_id" id="auto_id"
                                                value="@isset($values){{$values->branchid}}@endisset">
                                            <div class="row">
                                                <div class="input-field col s12 m6">
                                                    <label for="branch_name"
                                                        class="common-label">{{__('Union Branch Name') }}*</label>
                                                    <input id="branch_name" class="common-input" name="branch_name"
                                                        value="@isset($values){{ $values->union_branch }}@endisset"
                                                        type="text" data-error=".errorTxt1">
                                                    <div class="errorTxt1"></div>
                                                </div>
                                                <div class="col s12 m6">
                                                    <label class="common-label">{{__('Country Name') }}*</label>
                                                    <select name="country_id" id="country_id"
                                                        class="error browser-default common-select selectpicker"
                                                        data-error=".errorTxt101">
                                                        <option value="">{{__('Select Country') }}</option>
                                                        @php
                                                        $Defcountry = CommonHelper::DefaultCountry();
                                                        @endphp
                                                        @foreach($data['country_view'] as $value)
                                                        <option value="{{$value->id}}" @isset($values) @php if($value->
                                                            id == $values->country_id) { echo "selected";} @endphp @endisset @if($Defcountry==$value->id) selected @endif >
                                                    {{$value->country_name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="input-field">
                                                        <div class="errorTxt101"></div>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="col s12 m6">
                                                    <label class="common-label">{{__('State Name') }}*</label>
                                                    <select class="error browser-default common-select selectpicker"
                                                        id="state_id" name="state_id" aria-required="true"
                                                        data-error=".errorTxt102" required>
                                                        <option value="">{{__('Select City') }}</option>
                                                        @isset($data['state_view'])
                                                        @foreach($data['state_view'] as $value)
                                                        <option value="{{$value->id}}" @isset($values) @php if($value->
                                                            id == $values->state_id) { echo "selected";} @endphp
                                                            @endisset >{{$value->state_name}}</option>
                                                        @endforeach
                                                        @endisset
                                                    </select>
                                                    <div class="input-field">
                                                        <div class="errorTxt102"></div>
                                                    </div>
                                                </div>
                                                
                                                <div class="col s12 m6">
                                                    <label class="common-label">{{__('City Name') }}*</label>
                                                    <select name="city_id" id="city_id"
                                                        class="error browser-default common-select selectpicker"
                                                        aria-required="true" data-error=".errorTxt103" required>
                                                        @isset($data['city_view'])
                                                        @foreach($data['city_view'] as $value)
                                                        <option value="{{$value->id}}"
                                                            <?php if($value->id == $values->city_id) { echo "selected";} ?>>
                                                            {{$value->city_name}}</option>
                                                        @endforeach
                                                        @endisset
                                                    </select>
                                                    <div class="input-field">
                                                        <div class="errorTxt103"></div>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="input-field col s12 m6">
                                                    <label for="postal_code" class="common-label">{{__('Postal Code') }}
                                                        *</label>
                                                    <input id="postal_code" name="postal_code" class="common-input"
                                                        value="@isset($values){{$values->postal_code}}@endisset"
                                                        type="text" data-error=".errorTxt13">
                                                    <div class="errorTxt13"></div>
                                                </div>
                                                <div class="input-field col s12 m6">
                                                    <label for="address_one"
                                                        class="common-label">{{__('Address Line 1') }}*</label>
                                                    <input id="address_one" name="address_one" class="common-input"
                                                        value="@isset($values){{$values->address_one}}@endisset"
                                                        type="text" data-error=".errorTxt14">
                                                    <div class="errorTxt14"></div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="input-field col s12 m6">
                                                    <label for="address_two"
                                                        class="common-label">{{__('Address Line 2') }}</label>
                                                    <input id="address_two" name="address_two" class="common-input"
                                                        value="@isset($values){{$values->address_two}}@endisset"
                                                        type="text" data-error=".errorTxt15">
                                                    <div class="errorTxt15"></div>
                                                </div>
                                                
                                                <div class="input-field col s12 m6">
                                                    <label for="address_three"
                                                        class="common-label">{{__('Address Line 3') }}</label>
                                                    <input id="address_three" name="address_three" class="common-input"
                                                        value="@isset($values){{$values->address_three}}@endisset"
                                                        type="text" data-error=".errorTxt15">
                                                    <div class="errorTxt15"></div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="input-field col s12 m6">
                                                    <label for="phone" class="common-label">{{__('Phone') }} *</label>
                                                    <input id="phone" name="phone" type="text" class="common-input"
                                                        value="@isset($values){{$values->phone}}@endisset"
                                                        data-error=".errorTxt100">
                                                    <div class="errorTxt100"></div>
                                                </div>
                                                <div class="input-field col s12 m6">
                                                    <label for="mobile" class="common-label">{{__('Mobile Number') }}
                                                        *</label>
                                                    <input id="mobile" name="mobile" type="text" class="common-input"
                                                        value="@isset($values){{$values->mobile}}@endisset"
                                                        data-error=".errorTxt5">
                                                    <div class="errorTxt5"></div>
                                                </div>
                                                <div class="clearfix" style="clear:both"></div>
                                                <div class="input-field col s12 m6">
                                                    <div class="file-field input-field">
                                                        <div class="btn">
                                                            <span>{{__('File') }}</span>
                                                            <input type="file" name="logo" id="logo">
                                                        </div>
                                                        <div class="file-path-wrapper">
                                                            <input class="file-path validate" type="text">
                                                        </div>
                                                    </div>
                                                    @isset($values)
                                                    @php
                                                    if(!empty($values->logo))
                                                    { @endphp
                                                    <img src="{{ asset('public/images/').'/'.$values->logo}}"
                                                        height=100px>
                                                    @php
                                                    }else
                                                    {
                                                    @endphp
                                                    <img src="{{ asset('public/images/no-image.png')}}" height=100px>
                                                    @php } @endphp
                                                    @endisset
                                                </div>
                                                <div class="input-field col s12 m6">
                                                    <label for="email" class="common-label">{{__('Email') }} *</label>
                                                    <input id="email" name="email" class="common-input" type="text"
                                                        @isset($values) readonly @endisset
                                                        value="@isset($values){{$values->email}}@endisset"
                                                        data-error=".errorTxt6">
                                                    <div class="errorTxt6"></div>
                                                </div>
                                                <div class="clearfix" style="clear:both"></div>
                                                <div class="input-field col s12 m6">
                                                    <p>
                                                        <label>
                                                            <input type="checkbox" name="is_head"
                                                                class="common-checkbox" id="is_head" value="1"
                                                                @isset($values)
                                                                {{ $values->is_head == '1' ? 'checked' : '' }}
                                                                @endisset />
                                                            <span>{{__('Head') }}</span>
                                                        </label>
                                                    </p>
                                                    
                                                </div>
												<div class="clearfix" style="clear:both"></div>
												<div class="input-field col s12">
													<button id="form-save-btn" class="btn waves-effect waves-light right submit"
														type="submit" name="action">
														@if(isset($values))
														{{__('Update') }}
														@else{{__('Save') }}
														@endif
													</button>
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
$("#unionbranch_sidebar_li_id").addClass('active');
$("#unionbranch_sidebar_a_id").addClass('active');
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
                        $("#city_id").empty();
                        $("#state_id").empty();
                        $("#city_id").append($('<option></option>').attr('value', '').text(
                            "Select City"));
                        $("#state_id").append($('<option></option>').attr('value', '').text(
                            "Select State"));
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
                    // console.log(res);
                }
            });
        } else {
            $('#city_id').empty();
        }
    });
});
//$('#country_id').trigger('change');
    //$('#state_id').trigger('change');
$("#unionbranch_formValidate").validate({
    rules: {
        branch_name: {
            required: true,
        },
        phone: {
            required: true,
            number: true,
            minlength:10,
            maxlength:15,
        },
        mobile: {
            required: true,
            number: true,
            minlength:10,
            maxlength:13,
        },
        email: {
            required: true,
            email:true,
            remote: {
                url: "{{ url(app()->getLocale().'/branch_emailexists')}}",
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
        country_id: {
            required: true,
        },
        state_id: {
            required: true,
        },
        city_id: {
            required: true,
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
    },
    //For custom messages
    messages: {
        branch_name: {
            required: '{{__("Enter the Union Branch Name") }}',
        },
        phone: {
            required: '{{__("Please Enter your Phone Number") }}',
            digits: '{{__("Enter Numbers only") }}',

        },
        mobile: {
            required: '{{__("Please Enter your Mobile Number") }}',
            digits: '{{__("Enter Numbers only") }}',

        },
        email: {
            remote: '{{__("Email Already exists") }}',
            required: '{{__("Please enter valid email") }}',
        },
        country_id: {
            required: '{{__("Please choose Country") }}',
        },
        state_id: {
            required: '{{__("Please choose state") }}',
        },
        city_id: {
            required: '{{__("Please choose  city") }}',
        },
        postal_code: {
            required: '{{__("Please enter postal code") }}',
        },
        address_one: {
            required: '{{__("Please Enter your Address") }}',
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
$(document).on('submit','form#unionbranch_formValidate',function(){
    $("#form-save-btn").prop('disabled',true);
    loader.showLoader();
});

</script>
@endsection