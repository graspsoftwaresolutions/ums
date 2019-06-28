@extends('layouts.layout')
@section('content')
@if(session('message'))
<div class="alert alert-success" id="id">
{{session('message')}}
</div>
@endif
@if(session('errors'))

@endif
<div class="row">
        	<div class="customer-header">
        	<div class="col-md-8">
        	<h5><strong> View Reason Details</strong></h5>
        	</div>
	        <div class="col-md-4">
	        	<a class="cust" href="{{url('reason')}}">Back</a>
	        </div>
	    	</div>
        	<div class="widget">
        	<div class="activity-sec">
                    <div class="row">
                        <form method="post" action="{{url('reason_save')}}">
                        @foreach($data['reason_view'] as $value)
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                 <label for="Name" class="control-label col-md-4">Reason Name</label>
                                 <div class="col-md-7"> 
                                    <input type="text" disabled placeholder="Enter Reason Name" name="reason_name" value="{{$value->reason_name}}" id="reason_name" class="form-control">
                                    @if($errors->has('reason_name'))
                                    <span class="text-danger">{{$errors->first('reason_name')}}</span>
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