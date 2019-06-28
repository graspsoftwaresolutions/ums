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
        	<h5><strong>Reason Details</strong></h5>
        	</div>
	        <div class="col-md-4">
	        	<a class="cust" href="{{url('add-reason')}}">Add New Reason</a>
	        </div>
	    	</div>
        	<div class="widget">
        	
        	<div class="activity-sec">
                    <table id="home-table2" class="table">
                    <thead>
                        <tr>
                           	<td>Reason Name</td>
                            <td>Status</td>
                            <td> Action</td>
                         </tr>
                    </thead>
                    <tbody>
                    @foreach($data['reason_view'] as $key=>$value)
                        <tr>
                        <?php
                        $parameter =[
                            'id' =>$value->id,
                        ];
                         $parameter = Crypt::encrypt($parameter);      
                         ?>
                            <td>{{$value->reason_name}}</td>
                            <td>Active</td>
                            <td><a href="{{url('reason-view/').'/'.$parameter}}">View </a>|<a href="{{url('reason-edit/').'/'.$parameter}}">Edit</a>|<a href="{{url('reason-delete/').'/'.$value->id}}">Delete</a></td>
                      </tr>
                      @endforeach
                    </tbody>   
                </table>
           
        </div>
        </div>
@stop