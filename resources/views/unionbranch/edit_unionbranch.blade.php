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
                                        <h5 class="breadcrumbs-title mt-0 mb-0">Edit Union Branch Details</h5>
                                        <ol class="breadcrumbs mb-0">
                                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a>
                                            </li>
                                            <li class="breadcrumb-item active"><a href="#">Union Branch</a>
                                            </li>
                                        </ol>
                                    </div>
                                    <div class="col s2 m6 l6 ">
                                        <a class="btn waves-effect waves-light breadcrumbs-btn right" href="{{url('unionbranch')}}">Union Branch List</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12">
                            <div class="card">
                                <div class="card-content">
                                    <h4 class="card-title">Edit Union Branch</h4>
                                    
                                   <div id="view-validations">
                                    <form class="formValidate" id="unionbranch_formValidate" enctype="multipart/form-data" method="post" action="{{url('unionbranch_update')}}">
                                    @foreach($data['union_branch'] as $key=>$values)
                                        @csrf
                                        <input type="hidden" name="id" value="{{$values->id}}">
                                      <div class="row">
                                        <div class="input-field col s12 m6">
                                          <label for="branch_name">Union Branch Name*</label>
                                          <input id="branch_name" name="branch_name" value="{{$values->union_branch}}" type="text" data-error=".errorTxt1">
                                          <div class="errorTxt1"></div>
                                        </div>
                                        <div class="input-field col s12 m6">
                                                <select name="country_id" id="country" class="error browser-default">
                                                <option value="">Select Country</option>
                                                    @foreach($data['country_view'] as $value)
                                                    <option value="{{$value->id}}" <?php if($value->id == $values->country_id) { echo "selected";} ?>>{{$value->country_name}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="input-field">        
													<div class="errorTxt10"></div>
												</div>
                                            </div>
                                            
                                            <div class="col s12 m6">
                                               <label>State Name*</label>
                                                <select class="error browser-default" id="state_id" name="state_id" aria-required="true" required>
                                                @foreach($data['state_view'] as $value)
                                                    <option value="{{$value->id}}" <?php if($value->id == $values->state_id) { echo "selected";} ?>>{{$value->state_name}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="input-field"> 
													<div class="errorTxt11"></div>
												</div>
                                            </div>
                                            
                                            <div class="col s12 m6">
                                                 <label>City Name*</label>
                                                <select name="city_id" id="city" class="error browser-default" aria-required="true" required>
                                                @foreach($data['city_view'] as $value)
                                                    <option value="{{$value->id}}" <?php if($value->id == $values->city_id) { echo "selected";} ?>>{{$value->city_name}}</option>
                                                    @endforeach
                                                        </select>
                                                <div class="input-field">        
													<div class="errorTxt12"></div>
												</div>
                                            </div>
                                            <div class="input-field col s12 m6">
                                            <label for="postal_code">Postal Code *</label>
                                                <input id="postal_code" name="postal_code" value="{{$values->postal_code}}" type="text" data-error=".errorTxt13">
                                                <div class="errorTxt13"></div>
                                            </div>
                                            <div class="input-field col s12 m6">
                                            <label for="address_one">Address Line 1*</label>
                                                <input id="address_one" name="address_one" value="{{$values->address_one}}" type="text" data-error=".errorTxt14">
                                                <div class="errorTxt14"></div>
                                            </div>
                                            <div class="input-field col s12 m6">
                                            <label for="address_two">Address Line 2</label>
                                                <input id="address_two" name="address_two" value="{{$values->address_two}}" type="text" data-error=".errorTxt15">
                                                <div class="errorTxt15"></div>
                                            </div>
                                            <div class="input-field col s12 m6">
                                            <label for="address_three">Address Line 3</label>
                                                <input id="address_three" name="address_three" value="{{$values->address_three}}" type="text" data-error=".errorTxt15">
                                                <div class="errorTxt15"></div>
                                            </div>
                                            <div class="input-field col s12 m6">
                                                <label for="mobile">Mobile Number *</label>
                                                <input id="mobile" name="mobile" type="text" value="{{$values->mobile}}" data-error=".errorTxt5">
                                                <div class="errorTxt5"></div>
                                            </div>
                                            <div class="input-field col s12 m6">
                                                <label for="phone">Phone *</label>
                                                <input id="phone" name="phone" type="text" value="{{$values->phone}}" data-error=".errorTxt5">
                                                <div class="errorTxt5"></div>
                                            </div>
                                            <div class="clearfix" style="clear:both"></div>
                                            <div class="input-field col s12 m6">
                                            <div class="file-field input-field">
                                                <div class="btn">
                                                    <span>File</span>
                                                    <input type="file" name="logo" id="logo"> 
                                                </div>
                                                <div class="file-path-wrapper">
                                                    <input class="file-path validate" type="text">
                                                </div>
                                                </div><img src="{{ asset('public/images/').'/'.$values->logo}}" height=100px width=100px>
                                            </div>
                                            <div class="input-field col s12 m6">
                                                <label for="email">Email *</label>
                                                <input id="email" name="email" type="text" value="{{$values->email}}" data-error=".errorTxt6">
                                                <div class="errorTxt6"></div>
                                            </div>
                                            <div class="clearfix" style="clear:both"></div>
                                        <div class="input-field col s12 m6">
                                        <p>
                                        <label>
                                            <input type="checkbox" name="is_head" id="is_head" value="1" {{ $values->is_head == '1' ? 'checked' : '' }} />
                                            <span>Head</span>
                                        </label>
                                        </p>
                                        <div class="clearfix" style="clear:both"></div>
                                        <div class="input-field col s12">
                                          <button class="btn waves-effect waves-light right submit" type="submit" name="action">Submit
                                            <i class="material-icons right">send</i>
                                          </button>
                                        </div>
                                      </div>
                                      @endforeach
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
	$("#relation_sidebar_li_id").addClass('active');
	$("#unionbranch_sidebar_a_id").addClass('active');
    $(document).ready(function(){
        //state
      $('#country').change(function(){
        var countryID = $(this).val();   
        
        if(countryID){
            $.ajax({
            type:"GET",
            dataType: "json",
            url:" {{ URL::to('/get-state-list') }}?country_id="+countryID,
            success:function(res){               
                if(res){
                   // console.log(res);
                    //console.log('hi test');
                    $("#state_id").empty();
                    $("#state_id").append($('<option></option>').attr('value', '').text("Select State"));
                    $.each(res,function(key,entry){
                        
                        $("#state_id").append($('<option></option>').attr('value', entry.id).text(entry.state_name));
                       // var select = $("#state");
                       // select.material_select('destroy');
                        //select.empty();
                        
                    });
                }else{
                    $("#state_id").empty();
                }
                console.log(res);
            }
            });
        }else{
            $("#state").empty();
            $("#city").empty();
        }      
    });
    //$("#country").trigger('change');
   // $("#state_id").trigger('change');
    $('#state_id').change(function(){
       var StateId = $(this).val();
      
       if(StateId!='' && StateId!='undefined')
       {
         $.ajax({
            type: "GET",
            dataType: "json",
            url : "{{ URL::to('/get-cities-list') }}?State_id="+StateId,
            success:function(res){
                console.log(res);
                if(res)
                {
                    $('#city').empty();
                    $("#city").append($('<option></option>').attr('value', '').text("Select City"));
                    $.each(res,function(key,entry){
                        $('#city').append($('<option></option>').attr('value',entry.id).text(entry.city_name));
                        
                    });
                }else{
                    $('#city').empty();
                }
               // console.log(res);
            }
         });
       }else{
           $('#city').empty();
       }
   });

    });
    $("#unionbranch_formValidate").validate({
        rules: {
            branch_name: {
                required: true,
            },
        },
        //For custom messages
        messages: {
            branch_name: {
                required: "Enter the Union Branch Name",
            },
        },
        errorElement: 'div',
        errorPlacement: function (error, element) {
        var placement = $(element).data('error');
        if (placement) {
            $(placement).append(error)
        } else {
            error.insertAfter(element);
        }
        }
    });
    $("#unionbranch_formValidate").validate({
        rules: {
            branch_name: {
                required: true,
            },
            phone: {
                required: true,
                digits: true,
            },
            email: {
                required: true,
                email: true,
            },
            country: {
                required: true,
            },
            state_id: {
                required: true,
            },
            city: {
                required: true,
            },
            postal_code: {
                required: true,
                digits: true,
            },
            address_one: {
                required:true,
            },
        },
        //For custom messages
        messages: {
            branch_name: {
                required: "Enter the Union Branch Name",
            },
            phone: {
                required: "Please Enter your Number",
                digits: "Enter Numbers only",
                
            },
            email: {
                required: "Please enter valid email",
                email : "Please Enter valid Email",
                },
            country: {
                required:"Please choose  your Country",
            },
            state_id: {
                required:"Please choose  your State",
            },
            city: {
                required:"Please choose  your city",
            },
            address_one: {
                required:"Please Enter your Address",
            },
        },
        errorElement: 'div',
        errorPlacement: function (error, element) {
        var placement = $(element).data('error');
        if (placement) {
            $(placement).append(error)
        } else {
            error.insertAfter(element);
        }
        }
    });
</script>
@endsection