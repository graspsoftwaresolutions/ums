@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">

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
										<h5 class="breadcrumbs-title mt-0 mb-0">New Membership</h5>
										<ol class="breadcrumbs mb-0">
											<li class="breadcrumb-item"><a href="index.html">Dashboard</a>
											</li>
											<li class="breadcrumb-item active"><a href="#">Member</a>
											</li>
											
										</ol>
									</div>
								</div>
							</div>
						</div>
						<div class="col s12">
							<div class="card">
								<div class="card-content">
									<h4 class="card-title">New Membership</h4>
									@include('includes.messages')
									<div class="row">
                                        <div class="col s12">
                                            <ul class="tabs">
                                            <li class="tab col m3"><a class="active"  href="#test1">Membership details</a></li>
                                            </ul>
                                        </div>
                                        <div id="view-validations">
                                    <form class="formValidate" id="formValidate" method="post" action="{{ url('membership_save') }}">
                                        @csrf
                                      <div class="row">
                                        <div class="input-field col s12 m6">
                                          <select name="member_title" id="member_title">
                                                <option value="" disabled selected>Choose your option</option>
                                                @foreach($data['title_view'] as $key=>$value)
                                            <option value="{{$value->id}}">{{$value->person_title}}</option>
                                            @endforeach
                                                </select>
                                                <label>Member Title*</label>
                                          <div class="errorTxt1"></div>
                                        </div>
                                        <div class="input-field col s12 m6">
                                          <label for="member_number">Member Number *</label>
                                          <input id="member_number" name="member_number" type="text" data-error=".errorTxt1">
                                          <div class="errorTxt1"></div>
                                        </div>
                                        <div class="input-field col s12 m6">
                                          <label for="name">Member Name *</label>
                                          <input id="name" name="name" type="text" data-error=".errorTxt1">
                                          <div class="errorTxt1"></div>
                                        </div>
                                        
                                        <div class="input-field col s12 m6">
                                        <div class="col s12 row">
                                                <div class="col s12 m4">
                                                    <p>Gender</p>
                                                </div>
                                                <div class="col s12 m4">
                                                    <label>
                                                        <input class="validate" required="" aria-required="true" id="gender" name="gender" type="radio" value="female">
                                                        <span>Female</span>
                                                    </label>  
                                                </div>
                                                <div class="col s12 m4">
                                                    <p>
                                                        <label>
                                                        <input class="validate" required="" aria-required="true" id="gender" name="gender" type="radio" checked="" value="male">
                                                        <span>Male</span>
                                                        </label>
                                                    </p>
                                                </div>
                                                <div class="input-field">
                                                </div>
                                                </div>
                                        </div> 
                                        <div class="clearfix" style="clear:both"></div>
                                        <div class="input-field col s12 m6">
                                          <label for="phone">Mobile Number *</label>
                                          <input id="phone" name="phone" type="text" data-error=".errorTxt1">
                                          <div class="errorTxt1"></div>
                                        </div>
                                        <div class="input-field col s12 m6">
                                          <label for="email">Email *</label>
                                          <input id="email" name="email" type="text" data-error=".errorTxt1">
                                          <div class="errorTxt1"></div>
                                        </div>
                                        <div class="input-field col s12 m6">
                                          
                                          <input type="text" class="datepicker" id="doe" name="doe">
                                            <label for="doe">Date of Emp</label>
                                          <div class="errorTxt1"></div>
                                        </div>
                                            <div class="col s12 m6">
                                                 <div class="input-field col s12 m6">
                                                    <p>
                                                    <label>
                                                        <input type="checkbox" id="rejoined"/>
                                                        <span>Rejoined</span>
                                                        
                                                    </label>
                                                    </p>
                                                 </div>
                                                 <div class="input-field col s12 m6" id="member_old_div">
                                                 <span> 
                                                 <select name="old_mumber_number" id="old_mumber_number">
                                                 </select>
                                                        
                                                 </span>
                                                 </div>
                                            </div>
                                            <div class="clearfix" style="clear:both"></div>

                                            <div class="input-field col s12 m6">
                                                <select name="designation" id="designation">
                                                    @foreach($data['designation_view'] as $key=>$value)
                                                        <option value="{{$value->id}}">{{$value->designation_name}}</option>
                                                    @endforeach
                                                        </select>
                                                        <label>Designation*</label>
                                                <div class="errorTxt1"></div>
                                            </div>
                                            <div class="input-field col s12 m6">
                                                <select name="race" id="race">
                                                @foreach($data['race_view'] as $key=>$value)
                                                    <option value="{{$value->id}}">{{$value->race_name}}</option>
                                                    @endforeach
                                                        </select>
                                                        <label>Race*</label>
                                                <div class="errorTxt1"></div>
                                            </div>
                                            <div class="input-field col s12 m6">
                                                <select name="country_id" id="country">
                                                    @foreach($data['country_view'] as $value)
                                                    <option value="{{$value->id}}">{{$value->country_name}}</option>
                                                    @endforeach
                                                        </select>
                                                        <label>Country Name*</label>
                                                <div class="errorTxt1"></div>
                                            </div>
                                            <div class="input-field col s12 m6">
                                            <select class="error validate" id="state_id" name="state_id" aria-required="true" required>
                                                <option value="" selected>State Name</option>
                                            </select>
                                                        <label>State Name*</label>
                                                <div class="errorTxt1"></div>
                                            </div>
                                            <div class="clearfix" style="clear:both"></div>
                                            <div class="input-field col s12 m6">
                                                <select name="city_id" id="city">
                                                        </select>
                                                        <label>City Name*</label>
                                                <div class="errorTxt1"></div>
                                            </div>
                                            <div class="input-field col s12 m6">
                                            <label for="postal_code">Postal Code *</label>
                                                <input id="postal_code" name="postal_code" type="text" data-error=".errorTxt1">
                                                <div class="errorTxt1"></div>
                                            </div>
                                            <div class="input-field col s12 m6">
                                            <label for="address_one">Address Line 1*</label>
                                                <input id="address_one" name="address_one" type="text" data-error=".errorTxt1">
                                                <div class="errorTxt1"></div>
                                            </div>
                                            <div class="input-field col s12 m6">
                                            <label for="address_two">Address Line 2*</label>
                                                <input id="address_two" name="address_two" type="text" data-error=".errorTxt1">
                                                <div class="errorTxt1"></div>
                                            </div>
                                            <div class="input-field col s12 m6">
                                            <label for="address_three">Address Line 3*</label>
                                                <input id="address_three" name="address_three" type="text" data-error=".errorTxt1">
                                                <div class="errorTxt1"></div>
                                            </div>
                                            <div class="input-field col s12 m6">
                                                <input type="text" class="datepicker" id="dob" name="dob">
                                                    <label for="dob">Date of Birth</label>
                                                <div class="errorTxt1"></div>
                                            </div>
                                            <div class="input-field col s12 m6">
                                                <input type="text" class="datepicker" id="doj" name="doj">
                                                    <label for="doj">Date of Joining</label>
                                                <div class="errorTxt1"></div>
                                            </div>
                                            
                                            <div class="input-field col s12 m6">
                                            <label for="salary">Salary</label>
                                                <input id="salary" name="salary" type="text" data-error=".errorTxt1">
                                                <div class="errorTxt1"></div>
                                            </div>
                                            <div class="input-field col s12 m6">
                                            <label for="salary">Old IC Number</label>
                                                <input id="old_ic" name="old_ic" type="text" data-error=".errorTxt1">
                                                <div class="errorTxt1"></div>
                                            </div>
                                            <div class="input-field col s12 m6">
                                            <label for="new_ic">New IC Number</label>
                                                <input id="new_ic" name="new_ic" type="text" data-error=".errorTxt1">
                                                <div class="errorTxt1"></div>
                                            </div>
                                            <div class="input-field col s12 m6">
                                                <select name="company_id" id="company">
                                                    @foreach($data['company_view'] as $value)
                                                    <option value="{{$value->id}}">{{$value->company_name}}</option>
                                                    @endforeach
                                                        </select>
                                                        <label>Company Name*</label>
                                                <div class="errorTxt1"></div>
                                            </div>
                                            <div class="input-field col s12 m6">
                                                <select name="branch_id" id="branch">
                                                        </select>
                                                        <label>Branch Name*</label>
                                                <div class="errorTxt1"></div>
                                            </div>
                                            <div class="input-field col s12 m6">
                                                <select name="status_id" id="status_id">
                                                @foreach($data['status_view'] as $key=>$value)
                                                       <option value="{{$value->id}}">{{$value->status_name}}</option>
                                                @endforeach
                                                </select>
                                                        <label>Status*</label>
                                                <div class="errorTxt1"></div>
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
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('footerSection')
<!--script src = "{{ asset('public/assets/js/materialize.min.js') }}" type="text/javascript"></script-->
<script src="{{ asset('public/assets/vendors/noUiSlider/nouislider.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/scripts/form-elements.js') }}" type="text/javascript"></script>
@endsection
@section('footerSecondSection')
<script>

$(document).ready(function(){
      $('tabs').tabs();
     
      $('#member_old_div').hide();
      $('#rejoined').click(function(){
        $('#member_old_div').toggle();
       
        var  oldMemberID = $('#old_mumber_number').val();
        
        if(oldMemberID!= '' && oldMemberID != 'undefined'){
            $.ajax({
                type:"GET",
                dataType: "json",
                url:" {{ URL::to('/get-oldmember-list') }}",
                success:function(res){
                    if(res){
                    $("#old_mumber_number").parent().find(".select-dropdown").remove();
                    $("#old_mumber_number").parent().find("svg.caret").remove();
                    $("#old_mumber_number").empty();
                    $('#old_mumber_number').append($('<option></option>').attr('value','0').text('Select'));
                    //console.log('hi test');
                    $.each(res,function(key,entry){
                        $("#old_mumber_number").append($('<option></option>').attr('value', entry.member_number).text(entry.member_number));
                    });
                    $('#old_mumber_number').material_select();
                }else{
                $("#old_mumber_number").empty();
                }
                console.log(res);
                }
            });
        }

    });
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
                    $("#state").parent().find(".select-dropdown").remove();
                    $("#state").parent().find("svg.caret").remove();
                    $("#state").empty();
                    $('#state').append($('<option></option>').attr('value','0').text('Select'));
                    //console.log('hi test');
                    $.each(res,function(key,entry){
                      
                        $("#state").append($('<option></option>').attr('value', entry.id).text(entry.state_name));
                       // var select = $("#state");
                       // select.material_select('destroy');
                        //select.empty();
                        
                    });
                    $('#state').material_select();
                }else{
                $("#state").empty();
                }
                console.log(res);
            }
            });
        }else{
            $("#state").empty();
            $("#city").empty();
        }      
    });
    $("#country").trigger('change');
    $('#state').change(function(){
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
   $('#company').change(function(){
       var CompanyID = $(this).val();
      
       if(CompanyID!='' && CompanyID!='undefined')
       {
         $.ajax({
            type: "GET",
            dataType: "json",
            url : "{{ URL::to('/get-branch-list') }}?company_id="+CompanyID,
            success:function(res){
                //console.log(res);
                if(res)
                {
                    $('#branch').empty();
                    
                    $.each(res,function(key,entry){
                        $('#branch').append($('<option></option>').attr('value',entry.id).text(entry.branch_name)); 
                    });
                }else{
                    $('#branch').empty();
                }
                console.log(res);
            }
         });
       }else{
           $('#city').empty();
       }
   });
    });
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd'
    });
</script>
@endsection