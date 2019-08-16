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
<style type="text/css">
	.autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; cursor:pointer; }
	.autocomplete-suggestion { padding: 8px 5px; white-space: nowrap; overflow: hidden; }
	.autocomplete-selected { background: #F0F0F0; }
	.autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
	.autocomplete-group { padding: 8px 5px; }
	.autocomplete-group strong { display: block; border-bottom: 1px solid #000; }
	.padding-left-10{
		padding-left:10px;
	}
	.padding-left-20{
		padding-left:20px;
	}
	.padding-left-40{
		padding-left:40px;
	}
	#irc_confirmation_area {
		pointer-events: none;
	}
</style>
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
		<form class="formValidate" id="irc_formValidate" method="post"
		action="{{ route('irc.updateIrc',app()->getLocale()) }}">
		@csrf
        @php $dataresigneddata = $data['resignedmember']; 
           // dd($data);
         @endphp
		<h6> Resignation Member <a class="btn waves-effect waves-light breadcrumbs-btn right" href="{{ route('irc.irc_list',app()->getLocale())  }}">{{__('IRC Confirmation List') }}</a></h6>
			<div class="row"> 
            <input type="hidden" name="id" id="ircid" value="{{$dataresigneddata->ircid}}">
				 <div class="input-field col s4">
					<label for="member_number"
						class="common-label force-active">{{__('Membership Number') }}*</label>
					<input id="member_number" name="resignedmembernoE" readonly value="{{$dataresigneddata->member_number}}"  class="common-input autocompleteoff"
						type="text" data-error=".errorTxt1">
					<input type="hidden" name="resignedmemberno" id="memberid" value="{{$dataresigneddata->resignedmemberno}}">
					<div class="errorTxt1"></div>
				</div>
				<div class="input-field col s4">
					<label for="member_type"
						class="common-label force-active">{{__('Member Type') }}*</label>
					<input id="member_type" name="member_type"  value="{{$dataresigneddata->designation_name}}" readonly class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="input-field col s4">
					<label for="member_title"
						class="common-label force-active">{{__('Member Title') }}*</label>
					<input id="member_title" name="member_title" value="{{$dataresigneddata->person_title}}"  readonly class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				 <div class="clearfix" style="clear:both"></div>
				 <div class="input-field col s4">
					<label for="member_name"
						class="common-label force-active">{{__('Member Name') }}*</label>
					<input id="member_name" name="resignedmembername" value="{{$dataresigneddata->resignedmembername}}" readonly class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="input-field col s4">
					<label for="bank_name"
						class="common-label force-active">{{__('Bank') }}*</label>
					<input id="bank_name" name="resignedmemberbankname" value="{{$dataresigneddata->resignedmemberbankname}}" readonly class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="input-field col s4">
					<label for="branch_name"
						class="common-label force-active">{{__('Bank Branch') }}*</label>
					<input id="branch_name" name="resignedmemberbranchname" value="{{$dataresigneddata->resignedmemberbranchname}}" readonly class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				 <div class="clearfix" style="clear:both"></div>
				 <div class="input-field col s4">
					<div class="row">
						<div class="input-field col s12 m8">
							<label for="dob" class="force-active">{{__('Date of Birth') }} *</label>
							<input id="dob" readonly name="dob" data-reflectage="dob" value="{{$dataresigneddata->dob}}"  class="datepicker"  type="text"> 
						</div>
						<div class="input-field col s12 m4">
							<label for="member_age" class="force-active">{{__('Age') }}</label>
							<input type="text" value="{{$dataresigneddata->age}}" readonly id="member_age" >
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
							<input class="validate" required="" readonly aria-required="true" id="femalegender" name="gender" type="radio" value="Female" {{ $dataresigneddata->gender == 'Female' ? 'checked' : '' }} >
							<span>{{__('Female') }}</span>
							</label> 
						</p>						
					</div>
					<div class="col s12 m4">
						<p>
							<label>
							<input class="validate" readonly required="" aria-required="true" id="malegender" name="gender" type="radio"  value="Male" {{ $dataresigneddata->gender == 'Male' ? 'checked' : '' }}>
							<span>{{__('Male') }}</span>
							</label>
						</p>
					</div>
				<div class="errorTxt1"></div>
				</div>
				<div class="input-field col s4">
					<label for="doj"
						class="common-label force-active">{{__('DOJ') }}*</label>
					<input id="doj" value="{{$dataresigneddata->doj}}" name="doj" readonly class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="clearfix" style="clear:both"></div>
				<div class="input-field col s4">
					<label for="race_name"
						class="common-label force-active">{{__('Race') }}*</label>
					<input id="race_name" readonly name="race_name" value="{{$dataresigneddata->race_name}}"  class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="input-field col s4">
					<label for="nric_n"
						class="common-label force-active">{{__('NRIC-N') }}*</label>
					<input id="nric_n" readonly name="resignedmembericno"  value="{{$dataresigneddata->resignedmembericno}}" class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="input-field col s4">
					<label for="remarks"
						class="common-label force-active">{{__('Remarks') }}*</label>
					<input id="remarks" name="remarks" readonly value="{{$dataresigneddata->remarks}}" class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
			</div>
		</div>
	</div>
	<div class=" col s12 ">
	  <div class="container">
	 
		 <div class="card {{$irc_information}}">
		 <h6>IRC CONFORMATION OF BENEVOLENT FUND APPLICATION</h6>
			  <div class="row">
				<div class="input-field col s6">
					<label for="irc_member_no"
						class="common-label force-active">{{__('Membership No') }}*</label>
                        @php $id = $dataresigneddata->ircmembershipno;
                           $value = CommonHelper::getmembercode_byid($id);  
                         @endphp
					<input id="irc_member_no"  value="{{$value}}" readonly name="ircmember" class="common-input"
						type="text" data-error=".errorTxt1">
                       
						<input type="hidden" name="ircmembershipno" value="{{$id}}"  id="irc_member_code">
					<div class="errorTxt1"></div>
				</div>
				<div class="input-field col s6">
					<label for="irc_name"
						class="common-label force-active">{{__('IRC Name in Full') }}*</label>
					<input id="irc_name"  readonly name="ircname" value="{{$dataresigneddata->ircname}}"  class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="clearfix" style="clear:both"></div>
				<div class="input-field col s6">
					<div class="col s12 m3">
					 <p>{{__('IRC Position') }}*</p>
					</div>
					<div class="col s12 m3">
						<p>
							<label>
							<input class="validate" required="true" readonly  aria-required="true" id="ircposition" name="ircposition" type="radio" value="Chairman" {{ $dataresigneddata->ircposition == 'Chairman' ? 'checked' : '' }}>
							<span>{{__('Chairman') }}</span>
							</label> 
						</p>						
					</div>
					<div class="col s12 m3">
						<p>
							<label>
							<input class="validate"  readonly required="" aria-required="true" id="ircposition" name="ircposition" type="radio" value="Secretary" {{ $dataresigneddata->ircposition == 'Secretary' ? 'checked' : '' }}>
							<span>{{__('Secretary') }}</span>
							</label>
						</p>
					</div>
					<div class="col s12 m3">
						<p>
							<label>
							<input class="validate" readonly required="" aria-required="true" id="ircposition" name="ircposition" type="radio"  value="Commitee-Member" {{ $dataresigneddata->ircposition == 'Commitee-Member' ? 'checked' : '' }}>
							<span>{{__('Commitee Member') }}</span>
							</label>
						</p>
					</div>
				</div>
				<div class="input-field col s6">
					<label for="irc_bank"
						class="common-label force-active">{{__('Bank') }}*</label>
					<input id="irc_bank" readonly value="{{$dataresigneddata->ircbank}}"  name="ircbank" class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="clearfix" style="clear:both"></div>
				<div class="input-field col s6">
					<label for="bank_address"
						class="common-label force-active">{{__('Bank Address') }}</label>
					<input id="bank_address" readonly  name="ircbankaddress" value="{{ isset($dataresigneddata->ircbankaddress) ? $dataresigneddata->ircbankaddress : '' }}" class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="input-field col s6">
					<label for="irctelephoneno"
						class="common-label force-active">{{__('Office Number') }}</label>
					<input id="irctelephoneno" readonly name="irctelephoneno"  value="{{ isset($dataresigneddata->irctelephoneno) ? $dataresigneddata->irctelephoneno : '' }}" class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="clearfix" style="clear:both"></div>
				<div class="input-field col s6">
					<label for="ircmobileno"
						class="common-label force-active">{{__('Mobile') }}</label>
					<input id="ircmobileno" readonly name="ircmobileno" value="{{ isset($dataresigneddata->ircmobileno) ? $dataresigneddata->ircmobileno : '' }}" class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="input-field col s6">
					<label for="ircfaxno"
						class="common-label force-active">{{__('Fax') }}</label>
					<input id="ircfaxno" readonly name="ircfaxno" value="{{ isset($dataresigneddata->ircfaxno) ? $dataresigneddata->ircfaxno : '' }}" class="common-input"
						type="text" data-error=".errorTxt1">
					<div class="errorTxt1"></div>
				</div>
				<div class="input-field col s12">
					<h6>Dear Sir,<br><br>
					I,The above named IRC hereby Confirmed that the following : [Tick all the boxes as confirmation]
					</h6>
				</div>
					<div class="col s12 m12">
						<div class="row padding-left-20">
							<div class="col s12 m4 ">
								<p>
									<label>
									<input type="hidden" name="nameofperson" value="0">
									<input type="checkbox"  class="common-checkbox" name="nameofperson" id="nameofperson"  value="1"  @isset($dataresigneddata) {{ $dataresigneddata->nameofperson == '1' ? 'checked' : '' }} @endisset >
                                                                
									<span>Name of the Person appliying for BF is</span>
									</label> 
								</p>	
							</div>
							<div class="col s12 m3 ">								
									<input type="text" id="person_name" readonly value="{{$dataresigneddata->ircname}}">
							</div>
						</div>						
					</div>
					<div class="col s12 m12">
						<div class="row padding-left-20">
							<div class="col s12 m3 ">
								<p>
									<label>
									<input type="hidden" name="waspromoted" value="0">
									<input type="checkbox"  name="waspromoted" id="waspromoted" class="common-checkbox"  value="1" {{ $dataresigneddata->waspromoted == '1' ? 'checked' : '' }}/>
									<span>She/He was</span>
									</label> 
								</p>	
							</div>
							<div class="col s12 m3">
									<select name="resignedreason"  id="reason" class="error browser-default selectpicker">
									<option value="">Select reason</option>
										@foreach($data['reason_view'] as $values)
                                        <option value="{{$values->id}}" @php if($values->id == $dataresigneddata->reasonid) { echo "selected";} @endphp>{{$values->reason_name}}</option>
										@endforeach
									</select>
							</div>
							<div class="col s12 m3">
							
								<input type="text" id="type" readonly name="type" placeholder="" value="{{$dataresigneddata->designation_name}}">	
								
							</div>
							<div class="col s12 m3">
								<input type="text" 	name="gradewef" id="gradewef" value="{{$dataresigneddata->gradewef}}" placeholder="grade w.e.f" name="text" class="datepicker"/>
							</div>
						</div>						
					</div>
					<div class="col s12 m12">
						<p class=" padding-left-20">
							<label>
							<input type="hidden" name="beforepromotion" value="0">
							<input type="checkbox" name="beforepromotion"  id="beforepromotion" class="common-checkbox"  value="1" {{ $dataresigneddata->beforepromotion == '1' ? 'checked' : '' }}/>
							<span>I hearby confirm that She/He got She/He is no longer doing any clerical job function. </span>
							</label> 
						</p>		
					</div>
					<div class="col s12 m12 padding-left-20">
						<p class=" padding-left-20">
							<label>
							<input type="hidden" name="attached" value="0">
							<input type="checkbox" name="attached" id="attached" class="common-checkbox"   value="1"  {{ $dataresigneddata->attached == '1' ? 'checked' : '' }} />
							<span>Attached Job function/Description (compulsory). </span>
							</label> 
						</p>		
					</div>
					<div class="col s12 m12">
						<p class=" padding-left-20">
							<label>
							<input type="hidden" name="herebyconfirm" value="0">
							<input type="checkbox" name="herebyconfirm" id="herebyconfirm" class="common-checkbox" value="1" {{ $dataresigneddata->herebyconfirm == '1' ? 'checked' : '' }}/>
							<span>I hereby confirm that he/she got promoted he/she no longer doing any clerical job function. </span>
							</label> 
						</p>		
					</div>
					<div class="col s12 m12">
						<div class="row padding-left-20">
							<div class="col s12 m4 ">
								<p>
									<label>
									<input type="hidden" name="filledby" value="0">
									<input type="checkbox" name="filledby" id="filledby"  class="common-checkbox"  value="1"  {{ $dataresigneddata->filledby == '1' ? 'checked' : '' }}/>
									<span>The messenger clerical position has been filled by</span>
									</label> 
								</p>	
							</div>
							<div class="col s12 m3 ">				
									<input type="text" name="nameforfilledby" id="nameforfilledby" value="{{$dataresigneddata->nameforfilledby}}">
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
						<p class="padding-left-20">
							<label>
							<input type="hidden" name="branchcommitteeverification1" value="0">
							<input type="checkbox" name="branchcommitteeverification1" class="common-checkbox"  value="1"  {{ $dataresigneddata->branchcommitteeverification1 == '1' ? 'checked' : '' }}/>
							<span>I have verified the above and confirm that the declaration by the IRC is correct.The messenger/clerical And; </span>
							</label> 
						</p>	
						</div>
					</div>
					<div class="col s12 m12">
					<div class="row">
						<p class="padding-left-20">
							<label>
							<input type="hidden" name="branchcommitteeverification2" value="0">
							<input type="checkbox" id="" name="branchcommitteeverification2" class="common-checkbox"  value="1" {{ $dataresigneddata->branchcommitteeverification2 == '1' ? 'checked' : '' }}/>
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
									<input type="text" name="branchcommitteeName" placeholder="Name" value="{{$dataresigneddata->branchcommitteeName}}">
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
									<input type="text" name="branchcommitteeZone" value="{{$dataresigneddata->branchcommitteeZone}}" Placeholder="Zone">
							</div>
							<div class="col s12 m3 ">
									<input type="text" class="datepicker" Placeholder="Date" value="@isset($dataresigneddata->branchcommitteedate){{$dataresigneddata->branchcommitteedate}}@endisset" name="branchcommitteedate">
							</div>
						</div>	
					</div>
				</div>
			  </div>
			  <div class="col s12 m12">
						<div class="row">
							
							<div class="input-field col s12 m4">
							  <i class="material-icons prefix">date_range</i>
							  <input id="submitted_at" name="submitted_at" data-reflectage="dob" value="{{$dataresigneddata->submitted_at}}" class="datepicker"  type="text">
							  <label for="icon_prefix">File Submitted</label>
							</div> 
						<div class="input-field col s12 m2">
							<p>
							<input type="submit" class="btn" id="save" name="save" value="Submit" >
							</P>
						</div>	
						<div class="input-field col s12 m2 hide">
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

$("#irc_sidebar_a_id").addClass('active');

//Model
$(document).ready(function() {
// the "href" attribute of the modal trigger must specify the modal ID that wants to be triggered
$('.modal').modal();
});
/* $("#member_number").devbridgeAutocomplete({
	//lookup: countries,
	serviceUrl: "{{ URL::to('/get-member-list') }}?searchkey="+ $("#member_number").val(),
	type:'GET',
	//callback just to show it's working
	onSelect: function (suggestion) {
			$("#member_number").val(suggestion.member_number);
			$.ajax({
				url: "{{ URL::to('/get-member-list-values') }}?member_id="+ $("#member_number").val(),
                type: "GET",
				dataType: "json",
				success: function(res) {
					
					$('#member_type').val(res.membertype);
					$('#type').val(res.membertype);
					$('#member_title').val(res.persontitle);
					$('#member_name').val(res.membername);
					$('#person_name').val(res.membername);
					$('#bank_name').val(res.company_name);
					$('#memberid').val(res.memberid);
					$('#branch_name').val(res.branch_name);
					$('#dob').val(res.dob);
					$('#member_age').val(res.age);
					if(res.gender == 'Male')
					{
						$("#malegender").attr("checked",true);
					}
					else if(res.gender == 'Female')
					{
						$("#femalegender").attr("checked",true);
					}
					$('#doj').val(res.doj);
					$('#race_name').val(res.race_name);
					$('#nric_n').val(res.nric);
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
}); */
$(document.body).on('click', '.autocomplete-no-suggestion' ,function(){
	$("#member_number").val('');
});
$('.datepicker').datepicker({
	format: 'dd/mmm/yyyy'
});
//IRC Member Details 
/* $("#irc_member_no").devbridgeAutocomplete({
	//lookup: countries,
	serviceUrl: "{{ URL::to('/get-ircmember-list') }}?searchkey="+ $("#irc_member_no").val(),
	type:'GET',
	//callback just to show it's working
	onSelect: function (suggestion) {
			$("#irc_member_no").val(suggestion.member_number);	
			$.ajax({
				url: "{{ URL::to('/get-ircmember-list-values') }}?member_id="+ $("#irc_member_no").val(),
                type: "GET",
				dataType: "json",
				success: function(res) {
					$('#irc_name').val(res.membername);
					$('#irc_bank').val(res.bankname);
					$('#irc_member_code').val(res.mid);
				}
        
			});

	},
	showNoSuggestionNotice: true,
	noSuggestionNotice: 'Sorry, no matching results',
	onSearchComplete: function (query, suggestions) {
		if(!suggestions.length){
			//$("#irc_member_no").val('');
		}
	}
}); */
$(document.body).on('click', '.autocomplete-no-suggestion' ,function(){
	$("#irc_member_no").val('');
});

</script>
@endsection