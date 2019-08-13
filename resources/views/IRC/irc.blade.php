@extends('layouts.admin')
@section('headSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
<link rel="stylesheet" type="text/css"
href="{{ asset('public/assets/vendors/data-tables/css/jquery.dataTables.min.css') }}"> 
<link rel="stylesheet" type="text/css"
href="{{ asset('public/assets/vendors/data-tables/css/select.dataTables.min.css') }}"> 
<link rel="stylesheet" type="text/css"
href="{{ asset('public/assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css') }}">
@endsection
@section('headSecondSection')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/pages/data-tables.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/custom_respon.css') }}">
@endsection
@section('main-content')
<div id="">
<div class="row">
<div class="content-wrapper-before"></div>
	@php 
	if(!empty(Auth::user())){
		
		$userid = Auth::user()->id;
		$get_roles = Auth::user()->roles;
		$user_role = $get_roles[0]->slug;
			
			if($user_role =='irc-confirmation'){
				$irc_information = 'show';
				$irc_branch_commitee = 'hide';
			}
			else if($user_role =='irc-branch-committee'){
				$irc_information = 'hide';
				$irc_branch_commitee = 'show';
			} 
		}
	
	@endphp
		<div class="container">
		<div class="card">
		<h6> Resignation Member </h6>
			<div class="row">
				 <div class="input-field col s4">
					<label for="member_number"
						class="common-label force-active">{{__('Membership Number') }}*</label>
					<input id="member_number" name="member_number"  class="common-input autocomplete"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="input-field col s4">
					<label for="member_type"
						class="common-label force-active">{{__('Member Type') }}*</label>
					<input id="member_type" name="member_type" readonly class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="input-field col s4">
					<label for="member_title"
						class="common-label force-active">{{__('Member Title') }}*</label>
					<input id="member_title" name="member_title" readonly class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				 <div class="clearfix" style="clear:both"></div>
				 <div class="input-field col s4">
					<label for="member_name"
						class="common-label force-active">{{__('Member Name') }}*</label>
					<input id="member_name" name="member_name" readonly class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="input-field col s4">
					<label for="bank_name"
						class="common-label force-active">{{__('Bank') }}*</label>
					<input id="bank_name" name="bank_name" readonly class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="input-field col s4">
					<label for="branch_name"
						class="common-label force-active">{{__('Bank Branch') }}*</label>
					<input id="branch_name" name="branch_name" readonly class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				 <div class="clearfix" style="clear:both"></div>
				 <div class="input-field col s4">
					<div class="row">
						<div class="input-field col s12 m8">
							<label for="dob" class="force-active">{{__('Date of Birth') }} *</label>
							<input id="dob" readonly name="dob" data-reflectage="dob" class="datepicker"  type="text"> 
						</div>
						<div class="input-field col s12 m4">
							<label for="member_age" class="force-active">{{__('Age') }}</label>
							<input type="text" readonly id="member_age" >
						</div>
					</div>
					<div class="errorTxt1"></div>
				</div>
				<div class="input-field col s4">
					<div class="col s12 m4">
					 <p>{{__('Gender') }}</p>
					</div>
					<div class="col s12 m4">
						<p>
							<label>
							<input class="validate" required="" readonly aria-required="true" id="gender" name="gender" type="radio" value="Female">
							<span>{{__('Female') }}</span>
							</label> 
						</p>						
					</div>
					<div class="col s12 m4">
						<p>
							<label>
							<input class="validate" readonly required="" aria-required="true" id="gender" name="gender" type="radio" checked="" value="Male">
							<span>{{__('Male') }}</span>
							</label>
						</p>
					</div>
				<div class="errorTxt1"></div>
				</div>
				<div class="input-field col s4">
					<label for="doj"
						class="common-label force-active">{{__('DOJ') }}*</label>
					<input id="doj" name="doj" readonly class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="clearfix" style="clear:both"></div>
				<div class="input-field col s4">
					<label for="race_name"
						class="common-label force-active">{{__('Race') }}*</label>
					<input id="race_name" readonly name="race_name" class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="input-field col s4">
					<label for="nric_n"
						class="common-label force-active">{{__('NRIC-N') }}*</label>
					<input id="nric_n" readonly name="nric_n" class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="input-field col s4">
					<label for="remarks"
						class="common-label force-active">{{__('Remarks') }}*</label>
					<input id="remarks" readonly name="remarks" class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				
			</div>
		</div>
	</div>
	<div class=" col s12 ">
	  <div class="container">
	  <form> 
		 <div class="card {{$irc_information}}">
		 <h6>IRC CONFORMATION OF BENEVOLENT FUND APPLICATION</h6>
			  <div class="row">
				<div class="input-field col s6">
					<label for="remarks"
						class="common-label force-active">{{__('Membership No') }}*</label>
					<input id="remarks"  name="remarks" class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="input-field col s6">
					<label for="remarks"
						class="common-label force-active">{{__('IRC Name in Full') }}*</label>
					<input id="remarks"  name="remarks" class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="clearfix" style="clear:both"></div>
				<div class="input-field col s6">
					<div class="col s12 m3">
					 <p>{{__('IRC Position') }}</p>
					</div>
					<div class="col s12 m3">
						<p>
							<label>
							<input class="validate" required="" readonly aria-required="true" id="gender" name="gender" type="radio" value="Female">
							<span>{{__('Chairman') }}</span>
							</label> 
						</p>						
					</div>
					<div class="col s12 m3">
						<p>
							<label>
							<input class="validate" readonly required="" aria-required="true" id="gender" name="gender" type="radio" checked="" value="Male">
							<span>{{__('Secretary') }}</span>
							</label>
						</p>
					</div>
					<div class="col s12 m3">
						<p>
							<label>
							<input class="validate" readonly required="" aria-required="true" id="gender" name="gender" type="radio" checked="" value="Male">
							<span>{{__('Commitee Member') }}</span>
							</label>
						</p>
					</div>
				</div>
				<div class="input-field col s6">
					<label for="bank"
						class="common-label force-active">{{__('Bank') }}*</label>
					<input id="bank"  name="bank" class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="clearfix" style="clear:both"></div>
				<div class="input-field col s6">
					<label for="bank_address"
						class="common-label force-active">{{__('Bank Address') }}*</label>
					<input id="bank_address"  name="bank_address" class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="input-field col s6">
					<label for="office_telephone_number"
						class="common-label force-active">{{__('Office Number') }}*</label>
					<input id="office_telephone_number"  name="office_telephone_number" class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="clearfix" style="clear:both"></div>
				<div class="input-field col s6">
					<label for="mobile"
						class="common-label force-active">{{__('Mobile') }}*</label>
					<input id="mobile"  name="mobile" class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="input-field col s6">
					<label for="fax"
						class="common-label force-active">{{__('Fax') }}*</label>
					<input id="fax"  name="fax" class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="input-field col s12">
					<h6>Dear Sir,<br><br>
					I,The above named IRC hereby Confirmed that the following : [Tick all the boxes as confirmation]
					</h6>
				</div>
					<div class="col s12 m12">
						<div class="row">
							<div class="col s12 m4 ">
								<p>
									<label>
									<input type="checkbox" class="filled-in" checked="checked" />
									<span>Name of the Person appliying for BF is</span>
									</label> 
								</p>	
							</div>
							<div class="col s12 m3 ">
								
									<input type="text" name="person_name">
								
							</div>
						</div>						
					</div>
					<div class="col s12 m12">
						<div class="row">
							<div class="col s12 m3 ">
								<p>
									<label>
									<input type="checkbox" class="filled-in" checked="checked" />
									<span>She/He was</span>
									</label> 
								</p>	
							</div>
							<div class="col s12 m3">
									<label >{{__('Reason') }}*</label>
									<select name="reason" id="reason">
									<option value="">Choose</option>
									<option value="">xx</option>
									</select>
							</div>
							<div class="col s12 m3">
							<p>
								<input type="text"  name="text" />	
								</p>
							</div>
							<div class="col s12 m3">
								<label >{{__('garde w.e.f') }}*</label>
								<input type="text"  name="text" class="datepicker"/>
							</div>
						</div>						
					</div>
					<div class="col s12 m12">
						<p>
							<label>
							<input type="checkbox" class="filled-in" checked="checked" />
							<span>I hearby confirm that She/He got She/He is no longer doing any clerical job function. </span>
							</label> 
						</p>		
					</div>
					<div class="col s12 m12">
						<p>
							<label>
							<input type="checkbox" class="filled-in" checked="checked" />
							<span>Attached Job function/Description (compulsory). </span>
							</label> 
						</p>		
					</div>
					<div class="col s12 m12">
						<p>
							<label>
							<input type="checkbox" class="filled-in" checked="checked" />
							<span>I hereby confirm that he/she got promoted he/she no longer doing any clerical job function. </span>
							</label> 
						</p>		
					</div>
					<div class="col s12 m12">
						<div class="row">
							<div class="col s12 m4 ">
								<p>
									<label>
									<input type="checkbox" class="filled-in" checked="checked" />
									<span>The messenger clerical position has been filled by</span>
									</label> 
								</p>	
							</div>
							<div class="col s12 m3 ">
								
									<input type="text" name="person_name">
								
							</div>
						</div>						
					</div>
					</div>
			  </div>
			  <div class="card {{$irc_branch_commitee}}">
			  <h6>BRANCH COMMITEE VERIFICATION</h6>
				<div class="row">
					<div class="col s12 m12">
					<div class="row">
						<p>
							<label>
							<input type="checkbox" class="filled-in" checked="checked" />
							<span>I have verified the above and confirm that the declaration by the IRC is correct.The messenger/clerical And; </span>
							</label> 
						</p>	
						</div>
					</div>
					<div class="col s12 m12">
					<div class="row">
						<p>
							<label>
							<input type="checkbox" class="filled-in" checked="checked" />
							<span>I have promoted member is no longer doing Messenger/Clerical job functions. </span>
							</label> 
						</p>
					</div>						
					</div>
					<div class="col s12 m12">
						<div class="row">
							<div class="col s12 m4 ">
								<p>
									<label>
									
									<span>Branch Commitee [Name in full]</span>
									</label> 
								</p>	
							</div>
							<div class="col s12 m3 ">
									<input type="text" name="person_name">
							</div>
						</div>	
					</div>
					<div class="col s12 m12">
						<div class="row">
							<div class="col s12 m4 ">
								<p>
									<label>
									<span>Zone</span>
									</label> 
								</p>	
							</div>
							<div class="col s12 m3 ">
									<input type="text" name="person_name">
							</div>
							<div class="col s12 m3 ">
							<!--<label>Date</label> -->
									<input type="text" class="datepicker" palceholder="Date" name="date">
							</div>
						</div>	
					</div>
				</div>
			  </div>
			  <div class="col s12 m12">
						<div class="row">
							<div class="input-field col s12 m4">
							<label for="file_submited" class="force-active">{{__('File Submitted') }} *</label>
							<input id="file_submited" name="dob" data-reflectage="dob" class="datepicker"  type="text"> 
						</div>
						<div class="input-field col s12 m2">
							<p>
							<input type="button" class="btn" id="save" name="save" value="Submit" >
							</P>
						</div>	
						<div class="input-field col s12 m2">
							<p>
							<input type="button" class="btn" id="search" name="search" value="Search" >
							</P>
						</div><div class="input-field col s12 m2">
							<p>
							<input type="button" class="btn" id="cancel" name="cancel" value="Cancel" >
							</P>
						</div>
						</div>
					</div>
				</form>
		 </div>
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
<script src="{{ asset('public/assets/vendors/data-tables/js/jquery.dataTables.min.js') }}"
type="text/javascript"></script>
<script
src="{{ asset('public/assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js') }}"
type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/data-tables/js/dataTables.select.min.js') }}"
type="text/javascript"></script>
<script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/assets/js/scripts/form-validation.js')}}" type="text/javascript"></script>
@endsection
@section('footerSecondSection')
<script src="{{ asset('public/assets/js/scripts/data-tables.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/jquery.autocomplete.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/mstepper.min.js') }}"></script>
<script>
$("#masters_sidebars_id").addClass('active');
$("#irc_sidebar_a_id").addClass('active');
$("#irc_sidebar_a_id").addClass('active');

//Model
$(document).ready(function() {
// the "href" attribute of the modal trigger must specify the modal ID that wants to be triggered
$('.modal').modal();
});
$("#member_number").devbridgeAutocomplete({
	//lookup: countries,
	serviceUrl: "{{ URL::to('/get-member-list') }}?searchkey="+ $("#member_number").val(),
	type:'GET',
	//callback just to show it's working
	onSelect: function (suggestion) {
			$("#member_number").val(suggestion.number);
			$.ajax({
				url: "{{ URL::to('/get-member-list-values') }}?member_id="+ $("#member_number").val(),
                type: "GET",
				success: function(result) {
				 $('#member_number').val(result.id);
				}
        
			});
			
	},
	showNoSuggestionNotice: true,
	noSuggestionNotice: 'Sorry, no matching results',
	onSearchComplete: function (query, suggestions) {
		if(!suggestions.length){
			//$("#member_number").val('');
		}
	}
});
$(document.body).on('click', '.autocomplete-no-suggestion' ,function(){
	$("#member_number").val('');
});
</script>
@endsection