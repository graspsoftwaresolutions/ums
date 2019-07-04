@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col s12">
		<div class="container m8">
			<div class="col s12 m8 l12">
				<div id="Form-advance" class="card card card-default scrollspy">
					<div class="card-content">
						<h4 class="card-title">Register</h4>
						<form class="col s12" method="POST" action="{{ route('member.register') }}"> 
						  @csrf
							<div class="row">
								<div class="input-field col m6 s12">
									<input id="member_name" name="member_name" type="text">
									<label for="member_name">Member Name</label>
									@error('member_name')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
								<div class="input-field col m6 s12">
									<input id="phone" name="phone" type="text">
									<label for="phone">Mobile Number</label>
									@error('mobile')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
							<div class="row">
								<div class="input-field col m6 s12">
									<input id="email" name="email" type="email">
									<label for="email" class="">Email</label>
									@error('email')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
								<div class=" col s12 m6 union-data">
                                            <label>Company Name*</label>
                                                <select name="company_id" id="company" class="error browser-default">
                                                <option value="">Select Company</option>
                                                    @foreach($data['company_view'] as $value)
                                                    <option value="{{$value->id}}">{{$value->company_name}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="input-field">      
                                                    <div class="errorTxt22"></div>
                                                </div>
                                            </div>
											<div class="clearfix" style="clear:both"></div>
											<div class="col s12 m6 union-data">
                                             <label>Branch Name*</label>
                                                <select name="branch_id" id="branch" class="error browser-default">
                                                    <option value="">Select Branch</option>
                                                    <?php 
                                                        //  if(!$check_union){
                                                        //      echo '<option selected >'.$branch_id.'</option>';
                                                        //  }
                                                    ?>
                                                </select>
                                                <div class="input-field">      
                                                    <div class="errorTxt23"></div>
                                                </div>       
                                            </div>
							<div class="row">
								<div class="input-field col s12">
									<button class="btn cyan waves-effect waves-light right" type="submit" name="action">Submit
										<i class="material-icons right">send</i>
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<!--div id="login-page" class="row hide">
				<div class="col s12 m12 l4 z-depth-4 card-panel border-radius-6 login-card bg-opacity-8">
					<form method="POST" action="{{ route('member.register') }}">
                        @csrf
						<div class="row">
							<div class="input-field col s12">
								<h5 class="ml-4">{{ __('Register') }}</h5>
							</div>
						</div>
						<div class="row margin">
							<div class="input-field col s12">
								<i class="material-icons prefix pt-2">person_outline</i>
								<input id="name" name="name" type="text" class="@error('name') is-invalid @enderror" value="{{ old('name') }}" required >
								<label for="name" class="center-align">{{ __('name') }}</label>
								@error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
							</div>
						</div>
						
						<div class="row margin">
							<div class="input-field col s12">
								<i class="material-icons prefix pt-2">person_outline</i>
								<input id="email" name="email" type="email" class="@error('email') is-invalid @enderror" value="{{ old('email') }}" required >
								<label for="email" class="center-align">{{ __('E-Mail Address') }}</label>
								@error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
							</div>
						</div>
						
						<div class="row">
							<div class="col s12 m12 l12 ml-2 mt-1">
								<p>
									<label>
									<input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} />
									<span> {{ __('Remember Me') }}</span>
									</label>
								</p>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s12">
								<button type="submit" class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12">
                                    {{ __('Login') }}
                                </button>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s6 m6 l6">
								<p class="margin medium-small">
									 @if (Route::has('register'))
										 <a href="{{ route('register') }}">Register Now!</a>
									@endif
								</p>
							</div>
							<div class="input-field col s6 m6 l6">
								<p class="margin right-align medium-small">
									 @if (Route::has('password.request'))
										<a href="{{ route('password.request') }}">
											{{ __('Forgot Your Password?') }}
										</a>
									@endif
								</p>
							</div>
						</div>
					</form>
				</div>
			</div-->
		</div>
	</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
	$('#company').change(function(){
       var CompanyID = $(this).val();
      
       if(CompanyID!='' && CompanyID!='undefined')
       {
         $.ajax({
            type: "GET",
            dataType: "json",
            url : "{{ URL::to('/get-branch-list-register') }}?company_id="+CompanyID,
            success:function(res){
                //console.log(res);
                if(res)
                {
                    $('#branch').empty();
                    
                    $.each(res,function(key,entry){
                        $('#branch').append($('<option></option>').attr('value',entry.id).text(entry.branch_name)); 
                    });
                }else{
                    $('#branch').empty();
                }
                console.log(res);
            }
         });
       }else{
           $('#city').empty();
       }
   });
});
</script>

@endsection
