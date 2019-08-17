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
@media (max-width: 992px) {
	.dash-tab-clearfix {
	  clear:both;
	}
}

</style>
@endsection
<!-- card stats start -->
<div id="card-stats">
   <div class="row">
		<div class="col s12 m6 l3">
			<div class="card animate fadeLeft">
				<div class="card-content cyan white-text">
				   <p class="card-stats-title"><i class="material-icons"></i> {{__('No of Pending IRC') }}</p>
				   <h4 class="card-stats-number white-text">{{ $data['total_ircpending_count'] }}</h4>
				</div>
				<div class="card-action cyan darken-1">
				   <div id="clients-bar" class="center-align"><a style="color:white" href="{{url(app()->getLocale().'/irc_list?status=0')}}">{{__('Pending IRC List') }}</a> </div>
				</div>
			</div>
		</div>

		<div class="col s12 m6 l3">
			<div class="card animate fadeRight">
				<div class="card-content orange lighten-1 white-text">
				   <p class="card-stats-title"><i class="material-icons"></i>{{__('No of Confirm IRC') }}</p>
				   <h4 class="card-stats-number white-text">{{ $data['total_ircconfirm_count'] }}</h4>
				</div>
				<div class="card-action orange">
				   <div id="profit-tristate" class="center-align"><a style="color:white" href="{{url(app()->getLocale().'/irc_list?status=1')}}">{{__('Confirm IRC List') }}  </a></div>
				</div>
			</div>
		</div>
		<div class="dash-tab-clearfix"/>
		
   </div>
</div>