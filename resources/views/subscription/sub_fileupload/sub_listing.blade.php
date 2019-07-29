@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/vendors/data-tables/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css') }}">
@endsection
@section('headSecondSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/pages/data-tables.css') }}">
@endsection
@section('main-content')
<div id="main">
    <div class="row">
        <div style="height:150px !important" class="content-wrapper-before gradient-45deg-indigo-purple"></div>
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
                                        <h5 class="breadcrumbs-title mt-0 mb-0">{{__('Subscription List') }}</h5>
                                        <ol class="breadcrumbs mb-0">
                                            <ol class="breadcrumbs mb-0">
                                                <li class="breadcrumb-item"><a
                                                        href="{{ route('home', app()->getLocale())  }}">{{__('Dashboard') }}</a>
                                                </li>
                                                <li class="breadcrumb-item active">{{__('Subscription') }}
                                                </li>
                                        </ol>
                                    </div>                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: Page Main-->
                    @include('layouts.right-sidebar')
                </div>   
            </div>
        </div>
<!--sfsdgfdg-->
<div class="clearfix"></div>

    <div class="col s12">
      <div id="validations" class="card card-tabs">
        <div class="card-content">
          <div class="card-title">
            <div class="row">
              <div class="col s12 m6 l10">
              <div class="row">
                <form class="col s12">
                    <div class="row">
                        <div class="input-field col s4">
                        <!--Month and year-->
                        
                        </div>
                        <div class="input-field col s4">
                        <select name="sub_company" class="error browser-default selectpicker">
						<option value="" disabled selected>Choose Company</option>
                        			
						</select>
                        
                        </div>
                        <div class="input-field  file-field col s4">
                        
                        <div class="btn">
                            <span>File</span>
                            <input type="file">
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text">
                        </div>
                        
                    </div>               
                </form>
            </div>
            <!-- <div class="row">
              <div class="col s12 m6 l10">
              <div class="row">
                <form class="col s12">
                    <div class="row">
                        <div class="input-field col s4">
                        Month and year
                        
                        </div>
                        <div class="input-field col s4">
                       
                        			
						</select>
                        
                        </div>
                        <div class="input-field  file-field col s4">
                        
                        <div class="btn">
                            <span>File</span>
                            <input type="file">
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text">
                        </div>
                        
                    </div>               
                </form>
            </div> -->
        
              </div>
              
            </div>
          </div>
          
         
        </div>
      </div>
    </div>
  </div>
</div>

<!--dgfdgfdg-->


    </div>
</div>
@endsection
@section('footerSection')
<script src="{{ asset('public/assets/vendors/data-tables/js/jquery.dataTables.min.js') }}" type="text/javascript">
</script>
<script src="{{ asset('public/assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js') }}"
    type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/data-tables/js/dataTables.select.min.js') }}" type="text/javascript">
</script>
@endsection
@section('footerSecondSection')
<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/assets/js/scripts/form-validation.js')}}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/scripts/data-tables.js') }}" type="text/javascript"></script>
<script>
</script>
@endsection