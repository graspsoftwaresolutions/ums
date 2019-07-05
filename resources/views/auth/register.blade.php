@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col s12">
		<div class="container m8">
			<div class="col s12 m8 l12">
				<div id="Form-advance" class="card card card-default scrollspy">
					<div class="card-content">
						<h4 class="card-title">Register</h4>
						<form class="col s12" method="POST" action="{{ route('member.register', app()->getLocale()) }}"> 
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
