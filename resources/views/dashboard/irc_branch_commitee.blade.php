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
<!-- card stats start -->
<div id="card-stats">
   <div class="row">
      <div class="col s12 m6 l3">
         <div class="card animate fadeLeft">
            <div class="card-content red accent-2 white-text">
               <p class="card-stats-title"><i class="material-icons"></i>No of IRC</p>
               <h4 class="card-stats-number white-text">{{ $data['total_irc_count'] }}</h4>
              
            </div>
            <div class="card-action red">
               <div id="sales-compositebar" class="center-align"><a style="color:white" href="{{url(app()->getLocale().'/irc_list')}}">IRC List</a></div>
            </div>
         </div>
      </div>
      
   </div>
</div>