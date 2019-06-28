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
        	<h5><strong> Add Branch Details</strong></h5>
        	</div>
	        <div class="col-md-4">
	        	<a class="cust" href="{{url('branch')}}">Back</a>
	        </div>
	    	</div>
        	<div class="widget">
        	<div class="activity-sec">
                 
                    <div class="row">
                        <form method="post" action="{{url('branch_update')}}">
                        @foreach($data['branch_view'] as $key=>$values)
                        @csrf
                        <input type="hidden" name="id" value="{{$values->id}}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                 <label for="Name" class="control-label col-md-4">Company Name <span style="color:red">*<span></label>
                                 <div class="col-md-7">
                                    <select class="form-control" name="company_id">
                                    @foreach($data['company_view'] as $key=>$value)
                                        <option  value="{{$value->id}}" <?php if($value->id == $values->company_id) { echo "selected";} ?>>{{$value->company_name}}</option>
                                     @endforeach
                                    </select>
                                 </div>
                                 </div>
                                 <div class="form-group">
                                 <label for="Name" class="control-label col-md-4">Branch Name <span style="color:red">*<span></label>
                                 <div class="col-md-7"> 
                                    <input type="text" value="{{$values->branch_name}}"  placeholder="Enter Branch Name" name="branch_name" id="branch_name" class="form-control">
                                    @if($errors->has('branch_name'))
                                    <span class="text-danger">{{$errors->first('branch_name')}}</span>
                                    @endif
                                 </div>
                                 </div>
                            </div>
                        </div>
                        @endforeach
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
@stop