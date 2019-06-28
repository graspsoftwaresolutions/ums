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
<div class="row">
        	<div class="customer-header">
        	<div class="col-md-8">
        	<h5><strong> Edit State Details</strong></h5>
        	</div>
	        <div class="col-md-4">
	        	<a class="cust" href="{{url('state')}}">Back</a>
	        </div>
	    	</div>
        	<div class="widget">
        	<div class="activity-sec">
                 
                    <div class="row">
                        <form method="post" action="{{url('state_edit')}}">
                        @foreach($data['state_view'] as $value)
                        @csrf
                        <input type="hidden" name="id" value="{{$value->id}}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                 <label for="Name" class="control-label col-md-4">Country Name <span style="color:red">*<span></label>
                                 <div class="col-md-7"> 
                                    <select class="form-control" name="country_id" > 
                                    @foreach($data['country_view'] as $values)
                                        <option  value="{{$values->id}}" <?php if($values->id == $value->country_id) { echo "selected";} ?> >{{$values->country_name}}</option>
                                    @endforeach
                                    </select>
                                 </div>
                                 </div>
                                 <div class="form-group">
                                 <label for="Name" class="control-label col-md-4">State Name <span style="color:red">*<span></label>
                                 <div class="col-md-7"> 
                                    <input type="text"  placeholder="Enter State Name" value="{{$value->state_name}}" name="state_name" id="state_name" class="form-control">
                                 </div>
                                 </div>
                            </div>
                        </div>
                        @endforeach
                        <br> 
                        <div class="row">
                            <div class="wrapper">            
                                    <input type="submit" name="submit" value="update" class="btn btn-success">
                                    <input type="submit" name="Cancel" value="Cancel" class="btn btn-danger">
                            </div>
                        </div> 
                    </form>
                    </div>
            </div>
        </div>
        </div>
@stop