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
        	<h5><strong> Add Race Details</strong></h5>
        	</div>
	        <div class="col-md-4">
	        	<a class="cust" href="{{url('race')}}">Back</a>
	        </div>
	    	</div>
        	<div class="widget">
        	<div class="activity-sec">
                    <div class="row">
                        <form method="post" action="{{url('race_save')}}">
                        @foreach($data['race_view'] as $key=>$value)
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                 <label for="Name" class="control-label col-md-4">Race Name</label>
                                 <div class="col-md-7"> 
                                    <input type="text" disabled="true" value="{{$value->race_name}}" placeholder="Enter Race Name" name="race_name" id="race_name" class="form-control">
                                    @if($errors->has('race_name'))
                                    <span class="text-danger">{{$errors->first('race_name')}}</span>
                                    @endif
                                 </div>
                                 </div>
                            </div>
                        </div>
                        @endforeach
                        <br>
                       
                    </form>
                    </div>
                    
            </div>
        </div>
        </div>
@stop