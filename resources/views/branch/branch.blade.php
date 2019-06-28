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
        	<h5><strong>Branch Details</strong></h5>
        	</div>
	        <div class="col-md-4">
	        	<a class="cust" href="{{url('add-branch')}}">Add New branch</a>
	        </div>
	    	</div>
        	<div class="widget">
        	<div class="activity-sec">
                    <table id="home-table2" class="table datatable">
                    <thead>
                        <tr>
                           	<td>Company Name</td>
                            <td>Branch Name</td>
                            <td> Action</td>
                         </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $value)
                        <tr>
                        <?php
                        $parameter = ['id'=>$value->id];
                        $parameter = Crypt::encrypt($parameter);  
                        ?>
                            <td>{{$value->company_name}}</td>
                            <td>{{$value->branch_name}}</td>
                            <td><a href="{{url('branch-view/').'/'.$parameter}}">View </a>|<a href="{{url('branch-edit/').'/'.$parameter}}">Edit</a>|<a href="{{url('branch-delete/').'/'.$value->id}}">Delete</a></td>
                         </tr>
                         @endforeach
                    </tbody>   
                </table>
            </div>
        </div>
        </div>
@stop