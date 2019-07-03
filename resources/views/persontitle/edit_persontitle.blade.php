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
                                        <h5 class="breadcrumbs-title mt-0 mb-0">Person Title Edit</h5>
                                        <ol class="breadcrumbs mb-0">
                                            <li class="breadcrumb-item"><a href="#">Dashboard</a>
                                            </li>
                                            <li class="breadcrumb-item active"><a href="#">Person Title</a>
                                            </li>
                                            
                                        </ol>
                                    </div>
                                    <div class="col s2 m6 l6 ">
                                        
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12">
                            <div class="card">
                                <div class="card-content">
                                    <h4 class="card-title">Edit Person Title</h4>
                                    
                                   <div id="view-validations">
                                    <form class="formValidate" id="title_formValidate" method="post" action="{{ url('persontitle_update') }}">
                                    
                                    @foreach($data['title_edit'] as $key=>$values)
                                    <input type="hidden" name="id" value="{{$values->id}}">
                                        @csrf
                                      <div class="row">
                                        <div class="input-field col s12 m6">
                                          <label for="person_title">Title Name*</label>
                                          <input id="person_title" name="person_title"  value="{{$values->person_title}}" type="text" data-error=".errorTxt1">
                                          <div class="errorTxt1"></div>
                                        </div>
                                        <div class="input-field col s12">
                                          <button class="btn waves-effect waves-light right submit" type="submit" name="action">Update
                                            <i class="material-icons right"></i>
                                          </button>
                                        </div>
                                      </div>
                                     @endforeach
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
	$("#title_sidebar_li_id").addClass('active');
    $("#title_sidebar_a_id").addClass('active');
    $("#title_formValidate").validate({
        rules: {
            person_title: {
                required: true,
            },
        },
        //For custom messages
        messages: {
            person_title: {
                required: "Enter a Person Title Name",
            },
        },
        errorElement: 'div',
        errorPlacement: function (error, element) {
        var placement = $(element).data('error');
        if (placement) {
            $(placement).append(error)
        } else {
            error.insertAfter(element);
        }
        }
    });
    
</script>
@endsection