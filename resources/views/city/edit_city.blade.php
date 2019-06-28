@extends('layouts.layout')
@section('content')
@if(session('message'))
<div class="alert alert-success" id="id">
{{session('message')}}
</div>
@endif
@if(session('errors'))
<div class="alert alert-danger" id="id">
{{session('errors')}}
</div>
@endif
<script src="http://www.codermen.com/js/jquery.js"></script>
<div class="row">
        	<div class="customer-header">
        	<div class="col-md-8">
        	<h5><strong> View City Details</strong></h5>
        	</div>
	        <div class="col-md-4">
	        	<a class="cust" href="{{url('city')}}">Back</a>
	        </div>
	    	</div>
        	<div class="widget">
        	<div class="activity-sec">
                 
                    <div class="row">
                        <form method="post" action="{{url('city_update')}}">
                       
                        @foreach($data['city_view'] as $value)
                        @csrf
                        <input type="hidden" name="id" value="{{$value->id}}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                 <label for="Name" class="control-label col-md-4">Country Name <span style="color:red">*<span></label>
                                 <div class="col-md-7">
                                 <select name="country_id" id="country" class="form-control">
                                 @foreach($data['country_view'] as $values)
                                        <option value="{{$values->id}}"<?php if($values->id == $value->country_id) { echo "selected";}?>>{{$values->country_name}}</option>
                                        @endforeach
                                        </select>
                                 </div>
                                 </div>
                                 <div class="form-group">
                                 <label for="Name" class="control-label col-md-4">State Name <span style="color:red">*<span></label>
                                 <div class="col-md-7"> 

                                 <select name="state_id" id="state" class="form-control">
                                 @foreach($data['state_view'] as $values)
                                        <option value="{{$values->id}}"<?php if($values->id == $value->state_id) { echo "selected";}?>>{{$values->state_name}}</option>
                                        @endforeach
                                        </select>
                                 </div>
                                 </div>
                            </div>
                        </div><br>        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                 <label for="Name" class="control-label col-md-4">City Name <span style="color:red">*<span></label>
                                 <div class="col-md-7"> 
                                   <input type="textbox" class="form-control" name="city_name" value="{{$value->city_name}}">
                                 </div>
                                 </div>
                            </div>
                        </div>
                        @endforeach
                        <br> 
                        <br>
                        <div class="row">
                            <div class="wrapper">            
                                    <input type="submit" name="submit" value="Update" class="btn btn-success">
                                    <input type="submit" name="Cancel" value="Cancel" class="btn btn-danger">
                                                                                 </div>
                        </div>
                    </form>
                    </div>
            </div>
        </div>
        </div>
        <script type="text/javascript">
$(document).ready(function(){
    $('#country').change(function(){
    var countryID = $(this).val();    
    if(countryID){
        $.ajax({
           type:"GET",
           url:" {{ URL::to('/get-state-list') }}?country_id="+countryID,
           success:function(res){               
            if(res){
                $("#state").empty();
                console.log(res);
                $.each(res,function(key,entry){
                    $("#state").append($('<option></option>').attr('value', entry.id).text(entry.state_name));
                });
            }else{
               $("#state").empty();
            }
            //console.log(res);
           }
        });
    }else{
        $("#state").empty();
        $("#city").empty();
    }      
   });
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
   $("#country").trigger('change');
});
</script>
@stop