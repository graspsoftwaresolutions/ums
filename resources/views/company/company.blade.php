@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/data-tables/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css') }}">
@endsection
@section('headSecondSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/pages/data-tables.css') }}">
@endsection
@section('main-content')
    <div id="main">
      <div class="row">
        <div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
        <div class="breadcrumbs-dark pb-0 pt-4" id="breadcrumbs-wrapper">
          <!-- Search for small screen-->
          <div class="container">
            <div class="row">
              <div class="col s10 m6 l6">
                <h5 class="breadcrumbs-title mt-0 mb-0">Dashboard</h5>
                <ol class="breadcrumbs mb-0">
                  <li class="breadcrumb-item"><a href="">Company</a>
                  </li>
                  <li class="breadcrumb-item">List
                  </li>
                  </li>
                </ol>
              </div>
              <div class="col s2 m6 l6">
			 <a class="btn waves-effect waves-light breadcrumbs-btn right" href="{{url('add-company')}}">Add New Company</a>
              </div>
            </div>
          </div>
        </div>
        <div class="col s12">
          <div class="container">
            <div class="section section-data-tables">
  <!-- Page Length Options -->
  <div class="row">
    <div class="col s12">
      <div class="card">
        <div class="card-content">
          <h4 class="card-title">Company List</h4>
          @include('includes.messages')
          <div class="row">
            <div class="col s12">
              <table id="page-length-option" class="display">
                <thead>
                    <tr>
                    <td>Company Name</td>
                            <td>Short Name</td>
                            <td> Head of company</td>
                        <th style="text-align:center"> Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['company'] as $key=>$value)
                    <tr>
                    <?php
                    $parameter =[
                        'id' =>$value->id,
                    ];
                    $parameter = Crypt::encrypt($parameter);  
                    ?>
                        <td>{{$value->company_name}}</td>
                        <td> {{$value->short_code}}</td>
                        <td> {{$value->head_of_company}}</td>
                        <td> <a class="btn-small waves-effect waves-light cyan" href="{{url('company-edit/').'/'.$parameter}}">Edit</a> <a class="btn-small waves-effect waves-light amber darken-4" href="{{url('company-delete/').'/'.$value->id}}" onclick="if (confirm('Are you sure you want to delete?')) return true; else return false;">Delete</a></td>
                        <td></td> 
                        
                </tr>
                @endforeach
                </tbody>
                
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

 <!-- Multi Select -->


</div><!-- START RIGHT SIDEBAR NAV -->
@include('layouts.right-sidebar')
<!-- END RIGHT SIDEBAR NAV -->
           
          </div>
        </div>
      </div>
    </div>
    <!-- END: Page Main-->

    <!-- Theme Customizer -->

@endsection
@section('footerSection')
<script src="{{ asset('public/assets/vendors/data-tables/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/data-tables/js/dataTables.select.min.js') }}" type="text/javascript"></script>
@endsection
@section('footerSecondSection')
<script src="{{ asset('public/assets/js/scripts/data-tables.js') }}" type="text/javascript"></script>
<script>
	$("#masters_sidebars_id").addClass('active');
	$("#company_sidebar_li_id").addClass('active');
	$("#company_sidebar_a_id").addClass('active');
</script>
@endsection