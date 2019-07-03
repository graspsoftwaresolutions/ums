@extends('layouts.admin')
@section('headSection')
@endsection
@section('headSecondSection')
@endsection
@section('main-content')
<div id="main">
    <div class="row">
        <div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
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
                                        <h5 class="breadcrumbs-title mt-0 mb-0"> Edit State Details</h5>
                                        <ol class="breadcrumbs mb-0">
                                            <li class="breadcrumb-item"><a href="#">Dashboard</a>
                                            </li>
                                            <li class="breadcrumb-item active">State
                                            </li>
                                            
                                        </ol>
                                    </div>
                                    <div class="col s2 m6 l6 ">
                                        <a class="btn  waves-effect waves-light breadcrumbs-btn right" href="{{url('state')}}">State List</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12">
                            <div class="card">
                                <div class="card-content">
                                    <h4 class="card-title">Edit State</h4>
                                    
                                   <div id="view-validations">
                                    <form class="formValidate" id="formValidate" method="post" action="{{url('state_edit')}}">
                                       <?php $row = $data['state_view'][0]; ?>
										@csrf
										<input type="hidden" name="id" value="{{$row->id}}">
                                      <div class="row">
                                        <div class="input-field col s12 m6">
                                            <i class="material-icons prefix">map</i>
                                            <select class="error validate" id="country_id" name="country_id"  data-error=".errorTxt6">
                                                <option value="" disabled="" selected="">Select country</option>
                                                @foreach($data['country_view'] as $values)
                                                    <option value="{{$values->id}}" <?php if($values->id == $row->country_id) { echo "selected";} ?>>{{$values->country_name}}</option>
                                                @endforeach
                                            </select>
                                            <div class="input-field">
                                                <div class="errorTxt6"></div>
                                            </div>
                                        </div>
                                        <div class="input-field col s12 m6">
                                          <label for="state_name">State Name*</label>
                                          <input name="state_name" id="state_name" type="text" value="{{$row->state_name}}" data-error=".errorTxt1">
                                          <div class="errorTxt1" style="margin: 0 45px;"></div>
                                        </div>
                                        <div class="input-field col s12">
                                          <button class="btn waves-effect waves-light right submit" type="submit" name="action">Update
                                            <i class="material-icons right">send</i>
                                          </button>
                                        </div>
                                      </div>
									 
                                    </form>
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
    </div>
</div>
@endsection
@section('footerSection')
<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
@endsection
@section('footerSecondSection')
<script src="{{ asset('public/assets/js/scripts/form-validation.js')}}" type="text/javascript"></script>
<script>
	$("#masters_sidebars_id").addClass('active');
	$("#state_sidebar_li_id").addClass('active');
	$("#state_sidebar_a_id").addClass('active');
</script>
@endsection