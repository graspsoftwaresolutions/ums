@section('headSecondSection')
<style>

@media (max-width: 992px) {
	.dash-tab-clearfix {
	  clear:both;
	}
}
#main.main-full {
		height: 10px;
		overflow: auto;
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

</style>
@endsection
<!-- card stats start -->
<div id="card-stats">
   <div class="row">
		<div class="col s12 m6 l3">
			<a style="color:white" href="{{url(app()->getLocale().'/irc_list?status=0')}}">
				<div class="card animate fadeLeft">
					<div class="card-content orange white-text">
					   <p class="card-stats-title"><i class="material-icons"></i> {{__('No of Pending IRC') }}</p>
					   <h4 class="card-stats-number white-text">{{ $data['total_ircpending_count'] }}</h4>
					</div>
					<div class="card-action orange darken-1">
					   <div id="clients-bar" class="center-align">{{__('Pending IRC List') }}</div>
					</div>
				</div>
			</a> 
		</div>

		<div class="col s12 m6 l3">
			<div class="card animate fadeLeft">
				<div class="card-content cyan white-text">
				   <p class="card-stats-title"><i class="material-icons"></i> {{__('Pending Secretary') }}</p>
				   <h4 class="card-stats-number white-text">{{ $data['total_ircapproval_count'] }}</h4>
				</div>
				<div class="card-action cyan darken-1">
				   <div id="clients-bar" class="center-align"> &nbsp; </div>
				</div>
			</div>
		</div>

		<div class="col s12 m6 l3">
			<div class="card animate fadeRight">
				<div class="card-content green lighten-1 white-text">
				   <p class="card-stats-title"><i class="material-icons"></i>{{__('No of Confirmed IRC') }}</p>
				   <h4 class="card-stats-number white-text">{{ $data['total_ircconfirm_count'] }}</h4>
				</div>
				<div class="card-action green">
				   <div id="profit-tristate" class="center-align"> &nbsp;</div>
				</div>
			</div>
		</div>
		<div class="col s12 m6 l3">
        <a style="color:white" href="{{url(app()->getLocale().'/irc_waiters')}}">
         <div class="card animate fadeLeft">
            <div class="card-content red accent-2 white-text">
               <p class="card-stats-title"><i class="material-icons"></i>{{__('Waiting for IRC Branch Committee') }}</p>
               <h4 class="card-stats-number white-text">{{ $data['total_ircwaited_count'] }}</h4>
              
            </div>
            <div class="card-action red">
               <div id="sales-compositebar" class="center-align">{{__('Members List') }}</div>
            </div>
         </div>
         </a>
      </div>
		<div class="dash-tab-clearfix"/>
		
   </div>
</div>