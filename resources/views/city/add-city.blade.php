@extends('layouts.layout')
@section('content')
@if(session('message'))
<div class="alert alert-success" id="id">
{{session('message')}}
</div>
@endif
@if(session('errors'))

@endif
<script src="http://www.codermen.com/js/jquery.js"></script>
<div class="row">
        	<div class="customer-header">
        	<div class="col-md-8">
        	<h5><strong> Add City Details</strong></h5>
        	</div>
	        <div class="col-md-4">
	        	<a class="cust" href="{{url('city')}}">Back</a>
	        </div>
	    	</div>
        	<div class="widget">
        	<div class="activity-sec">
                 
                    <div class="row">
                        <form method="post" action="{{url('city_save')}}">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                 <label for="Name" class="control-label col-md-4">Country Name <span style="color:red">*<span></label>
                                 <div class="col-md-7"> 
                                    <select class="form-control" name="country_id" id="country">
                                    @foreach($data['country_view'] as $value)
                                        <option value="{{$value->id}}">{{$value->country_name}}</option>
                                    @endforeach
                                    </select>
                                 </div>
                                 </div>
                                 <div class="form-group">
                                 <label for="Name" class="control-label col-md-4">State Name <span style="color:red">*<span></label>
                                 <div class="col-md-7"> 
                                 <select name="state_id" id="state" class="form-control">
                                        
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
                                    <input type="text" name="city_name" id="city" class="form-control">
                                 </div>
                                 </div>
                        </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="wrapper">            
                                    <input type="submit" name="submit" value="Save" class="btn btn-success">
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
           url:"{{url('get-state-order-list')}}?country_id="+countryID,
           success:function(res){               
            if(res){
                $("#state").empty();
                $.each(res,function(key,entry){
                    $("#state").append($('<option></option>').attr('value', entry.id).text(entry.state_name));
                });
            }else{
               $("#state").empty();
            }
           }
        });
    }else{
        $("#state").empty();
    }      
   });
});
</script>
@stop