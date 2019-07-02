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
                <div class="section ">
                    <!-- BEGIN: Page Main-->
                    <div class="row">
                        <div class="breadcrumbs-dark pb-0 pt-4" id="breadcrumbs-wrapper">
                            <!-- Search for small screen-->
                            <div class="container">
                                <div class="row">
                                    <div class="col s10 m6 l6">
                                        <h5 class="breadcrumbs-title mt-0 mb-0">Edit Fee Details</h5>
                                        <ol class="breadcrumbs mb-0">
                                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a>
                                            </li>
                                            <li class="breadcrumb-item active"><a href="#">Fee</a>
                                            </li>
                                            
                                        </ol>
                                    </div>
                                    <div class="col s2 m6 l6 ">
                                        <a class="btn dropdown-settings waves-effect waves-light breadcrumbs-btn right" href="{{url('fee')}}">Fee List</a>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12">
                            <div class="card">
                                <div class="card-content">
                                    <h4 class="card-title">Edit Fee</h4>
                                    <?php $row = $data['fee_view'][0]; ?>
                                    
                                   <div id="view-validations">
                                    <form class="formValidate" id="add_formValidate" method="post" action="{{url('fee_update')}}">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$row->id}}">
                                      <div class="row">
                                            <div class="input-field col s12 m6">
                                            <label for="fee_name">Fee Name*</label>
                                            <input id="fee_name" name="fee_name" type="text" value="{{$row->fee_name}}" data-error=".errorTxt1">
                                            <div class="errorTxt1"></div>
                                            </div>
                                            <div class="input-field col s12 m6">
                                            <label for="fee_amount">Fee Amount*</label>
                                            <input id="fee_amount" name="fee_amount" type="text" value="{{$row->fee_amount}}" data-error=".errorTxt2">
                                            <div class="errorTxt2"></div>
                                            </div>
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
	$("#fee_sidebar_li_id").addClass('active');
	$("#fee_sidebar_a_id").addClass('active');
    $("#add_formValidate").validate({
        rules: {
            fee_name: {
                required: true,
            },
            fee_amount: {
                required: true,
                digits: true,
                
            },
        },
        //For custom messages
        messages: {
            fee_name: {
                required: "Enter a Fee Name",
            },
            fee_amount: {
                required: "Enter a Fee Amount",
                digits: "Enter Numbers only"
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