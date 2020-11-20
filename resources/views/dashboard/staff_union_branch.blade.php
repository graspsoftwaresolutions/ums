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
#card-stats .card-stats-title {
    font-size: 12px;
}

</style>
@endsection
@php
	$get_roles = Auth::user()->roles;
	
    $user_role = $get_roles[0]->slug;
    $user_id = Auth::user()->id;
    $rejected_count = CommonHelper::getStaffUnionRejectedCount($user_id);
@endphp
<!-- card stats start -->
<div id="card-stats">
   <div class="row">
   		 <div class="col s12 m6 l1">
   		 </div>
		<div class="col s12 m6 l2">
			<a style="color:white" >
			 <div class="card animate fadeRight">
				<div class="card-content green lighten-1 white-text">
				   <p class="card-stats-title"><i class="material-icons"></i>{{__('No of Company Branches') }}</p>
				   <h4 class="card-stats-number white-text">{{ $data['total_company_branch_count'] }}</h4>
				</div>
				<div class="card-action green">
				   <div id="invoice-line" class="center-align"> {{__('Bank Branches') }}</div>
				</div>
			 </div>
			</a>
		</div>
		<div class="col s12 m6 l2">
			<a style="color:white">
			 <div class="card animate fadeLeft">
				<div class="card-content red accent-2 white-text">
				   <p class="card-stats-title"><i class="material-icons"></i>{{ __('No of Members') }}</p>
				   <h4 class="card-stats-number white-text">{{ $data['total_member_count'] }}</h4>
				  
				</div>
				<div class="card-action red">
				   <div id="sales-compositebar" class="center-align">{{ __('Members List') }}</div>
				</div>
			 </div>
			</a>
		</div>
		<div class="dash-tab-clearfix"/>
	      <div class="col s12 m6 l2">
	      	<a style="color:white" href="{{ url(app()->getLocale().'/membership_list?type=1') }}"> 
	         <div class="card animate fadeRight">
	            <div class="card-content orange lighten-1 white-text">
	               <p class="card-stats-title"><i class="material-icons"></i>{{__('No of Rejected Members') }}</p>
	               <h4 class="card-stats-number white-text">{{ $rejected_count }}</h4>
	            </div>
	            <div class="card-action orange">
	               <div id="invoice-line" class="center-align">{{__('Rejected Members List') }}</div>
	            </div>
	         </div>
	        </a>
	      </div>
	     
	      <div class="col s12 m6 l2">
	      	<a style="color:white" href="{{ route('master.addmembership', app()->getLocale())  }}">
         <div class="card animate fadeRight">
            <div class="card-content pink lighten-1 white-text">
               <p class="card-stats-title" style="font-size:16px;margin-top: 20px;margin-bottom: 25px;"><i class="material-icons"></i>{{__('New Registration') }}</p>
               
            </div>
            <div class="card-action pink ">
               <div id="profit-tristate" class="center-align">&nbsp; </div>
            </div>
         </div>
         </a>
	        
	      </div>
	       <div class="col s12 m6 l2">
        <a style="color:white" href="{{ route('resignation.add', app()->getLocale())  }}">
	         <div class="card animate fadeRight" style="">
	            <div class="card-content blue lighten-1 white-text">
	               <p class="card-stats-title" style="font-size:16px;margin-top: 20px;margin-bottom: 25px;"><i class="material-icons"></i>{{__('New Resignation') }}</p>
	            </div>
	            <div class="card-action blue ">
	               <div id="invoice-line" class="center-align">&nbsp;</div>
	            </div>
	         </div>
	         </a>
     	 </div>
   </div>
</div>