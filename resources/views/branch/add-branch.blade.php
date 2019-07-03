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
                <div class="section section-data-tables">
                    <!-- BEGIN: Page Main-->
                    <div class="row">
                        <div class="breadcrumbs-dark pb-0 pt-4" id="breadcrumbs-wrapper">
                            <!-- Search for small screen-->
                            <div class="container">
                                <div class="row">
                                    <div class="col s10 m6 l6">
                                        <h5 class="breadcrumbs-title mt-0 mb-0">Branch</h5>
                                        <ol class="breadcrumbs mb-0">
                                            <li class="breadcrumb-item"><a href="#">Dashboard</a>
                                            </li>
                                            <li class="breadcrumb-item active">Branch
                                            </li>
                                            
                                        </ol>
                                    </div>
                                    <div class="col s2 m6 l6 ">
                                        <a class="btn waves-effect waves-light breadcrumbs-btn right" href="{{url('branch')}}">Branch List</a>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12">
                            <div class="card">
                                <div class="card-content">
                                    <h4 class="card-title">Add Branch</h4>
                                    
                                   <div id="view-validations">
                                    <form class="formValidate" id="branchformValidate" method="post" action="{{ url('branch_save') }}">
                                        @csrf
                                      <div class="row">
                                        <div class="input-field col s12 m12">
                                            <select class="error browser-default" id="company_id" name="company_id"  data-error=".errorTxt1">
                                                <option value="" disabled="" selected="">Select company</option>
                                                @foreach($data['company_view'] as $value)
                                                    <option value="{{$value->id}}">{{$value->company_name}}</option>
                                                @endforeach
                                            </select>
                                            <div class="input-field">
                                                <div class="errorTxt1"></div>
                                            </div>
                                        </div>
                                        <div class="input-field col s12 m12">
                                            <select class="error browser-default" id="union_branch_id" name="union_branch_id"  data-error=".errorTxt2">
                                                <option value="" disabled="" selected="">Select Union Branch</option>
                                                @foreach($data['union_view'] as $value)
                                                    <option value="{{$value->id}}">{{$value->union_branch}}</option>
                                                @endforeach
                                            </select>
                                            <div class="input-field">
                                                <div class="errorTxt2"></div>
                                            </div>
                                        </div>
                                        <div class="input-field col s12 m12">
                                            <input id="branch_name" name="branch_name" type="text" data-error=".errorTxt3">
                                            <div class="errorTxt3" ></div>
                                                <label for="branch_name">Branch Name*</label>
                                        </div>
                                        <div class="input-field col s12 m6">
                                            <input id="address_one" name="address_one" type="text" data-error=".errorTxt4">
                                            <div class="errorTxt4"></div>
                                            <label for="address_one">Address Line 1*</label>
                                        </div>
                                        <div class="input-field col s12 m6">
                                            <input id="address_two" name="address_two" type="text" data-error=".errorTxt5">
                                            <div class="errorTxt5"></div>
                                            <label for="address_two">Address Line 2*</label>
                                        </div>
                                        <div class="col s12 m6">
                                            <select class="error browser-default" name="country_id" id="country" data-error=".errorTxt6">
                                            <option value="">Select Country</option>
                                                @foreach($data['country_view'] as $value)
                                                <option value="{{$value->id}}">{{$value->country_name}}</option>
                                                @endforeach
                                            </select>
                                            <div class="input-field">        
                                                <div class="errorTxt6"></div>
                                            </div>
                                        </div>
                                        <div class="col s12 m6">
                                            <select class="error browser-default" data-error=".errorTxt7" id="state_id" name="state_id" aria-required="true" required style="    margin: 1px 0;">
                                                <option value="" selected>State Name</option>
                                            </select>
                                            <div class="input-field"> 
                                                <div class="errorTxt7"></div>
                                            </div>
                                        </div>
                                        <div class="col s12 m6">
                                            <select name="city_id" id="city" class="error browser-default" aria-required="true" required data-error=".errorTxt8" style="    margin: 16px 0;">
                                                <option value="">Select City</option>
                                            </select>
                                            <div class="input-field">        
                                                <div class="errorTxt8"></div>
                                            </div>
                                        </div>
                                        <div class="input-field col s12 m6">
                                             <label for="postal_code">Postal Code *</label>
                                            <input id="postal_code" name="postal_code" type="text" data-error=".errorTxt9">
                                            <div class="errorTxt9"></div>
                                        </div>
                                        <div class="clearfix" ></div>
                                        <div class="input-field col s12 m6">
                                          <label for="phone">Mobile Number *</label>
                                          <input id="phone" name="phone" type="text" data-error=".errorTxt10">
                                          <div class="errorTxt10"></div>
                                        </div>
                                        <div class="input-field col s12 m6">
                                          <label for="email">Email *</label>
                                          <input id="email" name="email" type="text" data-error=".errorTxt11">
                                          <div class="errorTxt11"></div>
                                        </div>
                                        <div class="input-field col s12">
                                          <button class="btn waves-effect waves-light right submit" type="submit" name="action">Submit
                                            <i class="material-icons right">send</i>
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

</script>
<script>
    $("#branchformValidate").validate({
        rules: {
            company_id:{
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
            email: {
                required: true,
            },
            postal_code: {
                required: true,
            },
            address_one: {
                required:true,
            },
			address_two: {
                required:true,
            },
            state_id: {
                required: true,
            },
            country_id: "required",
            city_id : {
            required: true,
            },
        },
        //For custom messages
        messages: {
            
            company_id: {
                required: "Please Enter your Company",
                
            },
            union_branch_id: {
                required: "Please Enter your Union Branch Name",
                
            },
            branch_name: {
                required: "Please Enter your Branch Name",
                
            },
            phone: {
                required: "Please Enter your Number",
                
            },
            email: {
                required: "Please enter valid email",
                },
            country_id: {
                required:"Please choose  your Country",
            },
            state_id: {
                required:"Please choose  your State",
            },
            city_id: {
                required:"Please choose  your city",
            },
            address_one: {
                required:"Please Enter your Address",
            }
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