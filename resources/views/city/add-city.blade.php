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
                                        <h5 class="breadcrumbs-title mt-0 mb-0">Add City Details</h5>
                                        <ol class="breadcrumbs mb-0">
                                            <li class="breadcrumb-item"><a href="#">Dashboard</a>
                                            </li>
                                            <li class="breadcrumb-item active">City
                                            </li>
                                            
                                        </ol>
                                    </div>
                                    <div class="col s2 m6 l6 ">
                                        <a class="btn dropdown-settings waves-effect waves-light breadcrumbs-btn right" href="{{url('city')}}">City List</a>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col s12">
                            <div class="card">
                                <div class="card-content">
                                    <h4 class="card-title">Add State</h4>
                                    
                                   <div id="view-validations">
                                    <form class="formValidate" id="formValidate" method="post" action="{{ url('city_save') }}">
                                        @csrf
                                    
                                        <div class="row">
                                            <div class="input-field col s12 m12">
                                                <i class="material-icons prefix">map</i>
                                                <select class="error validate" id="country_id" name="country_id"  data-error=".errorTxt6">
                                                    <option value="" disabled="" selected="">Select country</option>
                                                    @foreach($country_view as $value)
                                                        <option value="{{$value->id}}">{{$value->country_name}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="input-field">
                                                    <div class="errorTxt6"></div>
                                                </div>
                                            </div>
                                            <div class="input-field col s12 m12">
                                            <i class="material-icons prefix">room</i>
                                            <select class="error validate" id="state" name="state_id"  data-error=".errorTxt7">
                                                 @foreach ($state_view as $state)
                                                    <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                                                @endforeach
                                            </select>
                                             <div class="errorTxt7" style="margin: 0 45px;"></div>
                                            </div>
                                            <div class="input-field col s12 m12">
                                                 <i class="material-icons prefix">room</i>
                                               
                                                <input id="city_name" name="city_name" type="text" data-error=".errorTxt1">
                                                <div class="errorTxt1" style="margin: 0 45px;"></div>
                                                 <label for="city_name">City Name*</label>
                                            </div>
                                        </div>

                                        <div class="row">
                                        <div class="input-field col s12">
                                          <button class="btn waves-effect waves-light right submit" type="submit" name="action">Submit
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
	$("#city_sidebar_li_id").addClass('active');
	$("#city_sidebar_a_id").addClass('active');
</script>
<script>
    $(function() {
        $('select[name=country_id]').change(function() {

            var url = "{{ url('get-state-order-list') }}" + '/' + $(this).val();

            $.get(url, function(data) {
                var select = $('form select[name= state_id]');

                select.empty();

                $.each(data,function(key, value) {
                    select.append('<option value=' + value.id + '>' + value.state_name + '</option>');
                });
            });
        });
    });
</script>

@endsection