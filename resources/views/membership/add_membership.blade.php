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
										<h5 class="breadcrumbs-title mt-0 mb-0">New Membership</h5>
										<ol class="breadcrumbs mb-0">
											<li class="breadcrumb-item"><a href="index.html">Dashboard</a>
											</li>
											<li class="breadcrumb-item active"><a href="#">Member</a>
											</li>
											
										</ol>
									</div>
								</div>
							</div>
						</div>
						<div class="col s12">
							<div class="card">
								<div class="card-content">
									<h4 class="card-title">New Membership</h4>
									@include('includes.messages')
									<div class="row">
                                        <div class="col s12">
                                            <ul class="tabs">
                                            <li class="tab col m3"><a class="active"  href="#test1">Membership details</a></li>
                                            <li class="tab col m3"><a href="#test2">Nominee Details</a></li>
                                            <li class="tab col m3"><a href="#test4">Guardian Details</a></li>
                                            </ul>
                                        </div>
                                        <div id="view-validations">
                                    <form class="formValidate" id="formValidate" method="post" action="{{ url('country_save') }}">
                                        @csrf
                                      <div class="row">
                                        <div class="input-field col s12 m6">
                                          <select name="member_title" id="member_title">
                                                <option value="" disabled selected>Choose your option</option>
                                                @foreach($data['title_view'] as $key=>$value)
                                            <option value="{{$value->id}}">{{$value->person_title}}</option>
                                            @endforeach
                                                </select>
                                                <label>Member Title*</label>
                                          <div class="errorTxt1"></div>
                                        </div>
                                        <div class="input-field col s12 m6">
                                          <label for="country_name">Member Number *</label>
                                          <input id="country_name" name="country_name" type="text" data-error=".errorTxt1">
                                          <div class="errorTxt1"></div>
                                        </div>
                                        <div class="input-field col s12 m6">
                                          <label for="name">Member Name *</label>
                                          <input id="name" name="name" type="text" data-error=".errorTxt1">
                                          <div class="errorTxt1"></div>
                                        </div>
                                        <div class="input-field col s12 m6">
                                            <label>
                                            <input class="with-gap" name="group3" type="radio" checked />
                                            <span>Radio-1</span>
                                            </label>
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
                                        <div id="test2" class="col s12">Test 2</div>
                                        <div id="test4" class="col s12">Test 4</div>
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
<script src="{{ asset('public/assets/vendors/data-tables/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/data-tables/js/dataTables.select.min.js') }}" type="text/javascript"></script>
@endsection
@section('footerSecondSection')
<script>
$(document).ready(function(){
      $('tabs').tabs();
    });
</script>
@endsection