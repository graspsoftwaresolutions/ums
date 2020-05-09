@section('headSecondSection')
<style>
@media (min-height: 100px) and (max-height: 657px) {
	#main.main-full {
		height: 657px;
		//overflow: auto;
	}
	
	.footer {
	   position: fixed;
	   margin-top:50px;
	   left: 0;
	   bottom: 0;
	   width: 100%;
	   height:auto;
	   background-color: red;
	   color: white;
	   text-align: center;
	   z-index:999;
	} 
	.sidenav-main{
		z-index:9999;
	}
}

</style>
@endsection
@php
	$get_roles = Auth::user()->roles;
	
    $user_role = $get_roles[0]->slug;
    $user_id = Auth::user()->id;
    $rejected_count = CommonHelper::getUnionRejectedCount($user_id);
@endphp
<!-- card stats start -->
<div id="card-stats">
   <div class="row">
		<div class="col s12 m6 l3">
		 <div class="card animate fadeRight">
			<div class="card-content green lighten-1 white-text">
			   <p class="card-stats-title"><i class="material-icons"></i>{{__('No of Company Branches') }}</p>
			   <h4 class="card-stats-number white-text">{{ $data['total_company_branch_count'] }}</h4>
			</div>
			<div class="card-action green">
			   <div id="invoice-line" class="center-align"><a style="color:white" href="{{route('master.branch', app()->getLocale())}}"> {{__('Company Branches List') }}</a></div>
			</div>
		 </div>
		</div>
		<div class="col s12 m6 l3">
		 <div class="card animate fadeLeft">
			<div class="card-content red accent-2 white-text">
			   <p class="card-stats-title"><i class="material-icons"></i>{{ __('No of Members') }}</p>
			   <h4 class="card-stats-number white-text">{{ $data['total_member_count'] }}</h4>
			  
			</div>
			<div class="card-action red">
			   <div id="sales-compositebar" class="center-align"><a style="color:white" href="{{url(app()->getLocale().'/membership')}}">{{ __('Members List') }}</a></div>
			</div>
		 </div>
		</div>
		<div class="dash-tab-clearfix"/>
	      <div class="col s12 m6 l3">
	         <div class="card animate fadeRight">
	            <div class="card-content orange lighten-1 white-text">
	               <p class="card-stats-title"><i class="material-icons"></i>{{__('No of Rejected Members') }}</p>
	               <h4 class="card-stats-number white-text">{{ $rejected_count }}</h4>
	            </div>
	            <div class="card-action orange">
	               <div id="invoice-line" class="center-align"><a style="color:white" href="{{ url(app()->getLocale().'/membership_list?type=1') }}"> {{__('Rejected Members List') }}</a></div>
	            </div>
	         </div>
	      </div>
   </div>
</div>