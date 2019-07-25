@section('headSecondSection')
<style>
.ct-series-a .ct-area, .ct-series-a .ct-slice-donut-solid, .ct-series-a .ct-slice-pie {
    stroke: #ff4bac !important;
	fill: #ff4bac !important;
}
.ct-series-b .ct-area, .ct-series-b .ct-slice-donut-solid, .ct-series-b .ct-slice-pie {
    stroke: #BBBBBB !important;
	fill: #BBBBBB !important;
}

</style>
@endsection
<!-- card stats start -->
<div id="card-stats">
   <div class="row">
      <div class="col s12 m6 l3">
         <div class="card animate fadeLeft">
            <div class="card-content cyan white-text">
               <p class="card-stats-title"><i class="material-icons">person_outline</i> {{__('No of Union Branch') }}</p>
               <h4 class="card-stats-number white-text">{{ $data['union_branch_count'] }}</h4>
               <!-- <p class="card-stats-compare">
                  <i class="mdi-creation"></i> 15%
                  <span class="cyan text text-lighten-5">from yesterday</span>
               </p> -->
            </div>
            <div class="card-action cyan darken-1">
               <div id="clients-bar" class="center-align"><a style="color:white" href="{{route('master.unionbranch', app()->getLocale())}}">{{__('Union Branch List') }}</a> </div>
            </div>
         </div>
      </div>
      
      <div class="col s12 m6 l3">
         <div class="card animate fadeRight">
            <div class="card-content orange lighten-1 white-text">
               <p class="card-stats-title"><i class="material-icons"></i>{{__('No of Companies') }}</p>
               <h4 class="card-stats-number white-text">{{ $data['total_company_count'] }}</h4>
            </div>
            <div class="card-action orange">
               <div id="profit-tristate" class="center-align"><a style="color:white" href="{{route('master.company', app()->getLocale())}}">{{__('Companies List') }} </a></div>
            </div>
         </div>
      </div>
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
               <p class="card-stats-title"><i class="material-icons"></i>{{__('No of Members') }}</p>
               <h4 class="card-stats-number white-text">{{ $data['total_member_count'] }}</h4>
              
            </div>
            <div class="card-action red">
               <div id="sales-compositebar" class="center-align"><a style="color:white" href="{{url(app()->getLocale().'/membership')}}">{{__('Members List') }}</a></div>
            </div>
         </div>
      </div>
   </div>
</div>
<!--card stats end-->
 <!-- Current balance & total transactions cards-->
<div class="row mt-4">
   <div class="col s12 m4 l4">
      <!-- Current Balance -->
		<div id="ct9-chart" class="ct-chart card hide">
		  <div class="card-content">
			 <h4 class="card-title">{{__('Total members') }}</h4>
			 <p class="caption">
				<a href="https://gionkunz.github.io/chartist-js/getting-started.html" target="_blank">Chartist-js</a> A very simple pie chart with label interpolation to show percentage instead of the actual data series value.
			 </p>
		  </div>
	   </div>
      <div class="card animate fadeLeft">
         <div class="card-content">
            <h4 class="card-title mb-0">{{__('Total Members') }}<i class="material-icons float-right hide">more_vert</i></h4>
			</br>
            <div class="current-balance-container">
				
               <div id="current-balance-donut-chart" class="current-balance-shadow"></div>
            </div>
            <h5 class="center-align">{{ $data['total_active_members_count'] }}</h5>
            <p class="medium-small center-align">{{__('Active Members') }}</p>
         </div>
      </div>
   </div>
   <div class="col s12 m8 l8 animate fadeRight">
      <!-- Total Transaction -->
      <div class="card">
         <div class="card-content">
            <h4 class="card-title mb-0">{{__('Total Transaction') }}<i class="material-icons float-right hide">more_vert</i></h4>
            <p class="medium-small">{{__('This month transaction') }}</p>
            <div class="total-transaction-container">
               <div id="total-transaction-line-chart" class="total-transaction-shadow"></div>
            </div>
         </div>
      </div>
   </div>
</div>
<!--/ Current balance & total transactions cards-->

<!-- User statistics & appointment cards-->
<div class="row hide">
   <div class="col s12 l5">
      <!-- User Statistics -->
      <div class="card user-statistics-card animate fadeLeft">
         <div class="card-content">
            <h4 class="card-title mb-0">User Statistics <i class="material-icons float-right">more_vert</i></h4>
            <div class="row">
               <div class="col s12 m6">
                  <ul class="collection border-none mb-0">
                     <li class="collection-item avatar">
                        <i class="material-icons circle pink accent-2">trending_up</i>
                        <p class="medium-small">This year</p>
                        <h5 class="mt-0 mb-0">60%</h5>
                     </li>
                  </ul>
               </div>
               <div class="col s12 m6">
                  <ul class="collection border-none mb-0">
                     <li class="collection-item avatar">
                        <i class="material-icons circle purple accent-4">trending_down</i>
                        <p class="medium-small">Last year</p>
                        <h5 class="mt-0 mb-0">40%</h5>
                     </li>
                  </ul>
               </div>
            </div>
            <div class="user-statistics-container">
               <div id="user-statistics-bar-chart" class="user-statistics-shadow"></div>
            </div>
         </div>
      </div>
   </div>
   <div class="col s12 l4">
      <!-- Recent Buyers -->
      <div class="card recent-buyers-card animate fadeUp">
         <div class="card-content">
            <h4 class="card-title mb-0">Recent Members <i class="material-icons float-right">more_vert</i></h4>
            <p class="medium-small pt-2">Today</p>
            <ul class="collection mb-0">
               <li class="collection-item avatar">
                  <img src="{{ asset('public/assets/images/avatar/avatar-7.png') }}" alt="" class="circle">
                  <p class="font-weight-600">John Doe</p>
                  <p class="medium-small">18, January 2019</p>
                  <a href="#!" class="secondary-content"><i class="material-icons">star_border</i></a>
               </li>
               <li class="collection-item avatar">
                  <img src="{{ asset('public/assets/images/avatar/avatar-7.png') }}" alt="" class="circle">
                  <p class="font-weight-600">Adam Garza</p>
                  <p class="medium-small">20, January 2019</p>
                  <a href="#!" class="secondary-content"><i class="material-icons">star_border</i></a>
               </li>
               <li class="collection-item avatar">
                  <img src="{{ asset('public/assets/images/avatar/avatar-7.png') }}" alt="" class="circle">
                  <p class="font-weight-600">Jennifer Rice</p>
                  <p class="medium-small">25, January 2019</p>
                  <a href="#!" class="secondary-content"><i class="material-icons">star_border</i></a>
               </li>
            </ul>
         </div>
      </div>
   </div>
   <div class="col s12 l3">
      <div class="card animate fadeRight">
         <div class="card-content">
            <h4 class="card-title mb-0">Conversion Ratio</h4>
            <div class="conversion-ration-container mt-8">
               <div id="conversion-ration-bar-chart" class="conversion-ration-shadow"></div>
            </div>
            <p class="medium-small center-align">This month conversion ratio</p>
            <h5 class="center-align mb-0 mt-0">62%</h5>
         </div>
      </div>
   </div>
</div>
<!--/ Current balance & appointment cards-->

<div class="row hide">
   <div class="col s12 m6 l4">
      <div class="card padding-4 animate fadeLeft">
         <div class="col s5 m5">
            <h5 class="mb-0">1885</h5>
            <p class="no-margin">New</p>
            <p class="mb-0 pt-8">1,12,900</p>
         </div>
         <div class="col s7 m7 right-align">
            <i class="material-icons background-round mt-5 mb-5 gradient-45deg-purple-amber gradient-shadow white-text">perm_identity</i>
            <p class="mb-0">Total Clients</p>
         </div>
      </div>
      <div id="chartjs" class="card pt-0 pb-0 animate fadeLeft">
         <div class="padding-2 ml-2">
            <span class="new badge gradient-45deg-indigo-purple gradient-shadow mt-2 mr-2">+ $900</span>
            <p class="mt-2 mb-0 font-weight-600">Today's revenue</p>
            <p class="no-margin grey-text lighten-3">$40,512 avg</p>
            <h5>$ 22,300</h5>
         </div>
         <div class="row">
            <div class="sample-chart-wrapper card-gradient-chart">
               <canvas id="custom-line-chart-sample-three" class="center"></canvas>
            </div>
         </div>
      </div>
   </div>
   <div class="col s12 m6 l8">
      <div class="card subscriber-list-card animate fadeRight">
         <div class="card-content pb-1">
            <h4 class="card-title mb-0">Subscriber List <i class="material-icons float-right">more_vert</i></h4>
         </div>
         <table class="subscription-table responsive-table highlight">
            <thead>
               <tr>
                  <th>Name</th>
                  <th>Company</th>
                  <th>Start Date</th>
                  <th>Status</th>
                  <th>Amount</th>
                  <th>Action</th>
               </tr>
            </thead>
            <tbody>
               <tr>
                  <td>Michael Austin</td>
                  <td>ABC Fintech LTD.</td>
                  <td>Jan 1,2019</td>
                  <td><span class="badge pink lighten-5 pink-text text-accent-2">Close</span></td>
                  <td>$ 1000.00</td>
                  <td class="center-align"><a href="#"><i class="material-icons pink-text">clear</i></a></td>
               </tr>
               <tr>
                  <td>Aldin Rakić</td>
                  <td>ACME Pvt LTD.</td>
                  <td>Jan 10,2019</td>
                  <td><span class="badge green lighten-5 green-text text-accent-4">Open</span></td>
                  <td>$ 3000.00</td>
                  <td class="center-align"><a href="#"><i class="material-icons pink-text">clear</i></a></td>
               </tr>
               <tr>
                  <td>İris Yılmaz</td>
                  <td>Collboy Tech LTD.</td>
                  <td>Jan 12,2019</td>
                  <td><span class="badge green lighten-5 green-text text-accent-4">Open</span></td>
                  <td>$ 2000.00</td>
                  <td class="center-align"><a href="#"><i class="material-icons pink-text">clear</i></a></td>
               </tr>
               <tr>
                  <td>Lidia Livescu</td>
                  <td>My Fintech LTD.</td>
                  <td>Jan 14,2019</td>
                  <td><span class="badge pink lighten-5 pink-text text-accent-2">Close</span></td>
                  <td>$ 1100.00</td>
                  <td class="center-align"><a href="#"><i class="material-icons pink-text">clear</i></a></td>
               </tr>
            </tbody>
         </table>
      </div>
   </div>
</div>
@section('footerSecondSection')
<script>
	var data = {
	  series: [97, 3]
	};

	var sum = function (a, b) { return a + b };
	new Chartist.Pie('#ct9-chart', data, {
	  labelInterpolationFnc: function (value) {
		return Math.round(value);
	  }
	});
	(function (window, document, $) {
		// Donut chart
		// -----------
		var CurrentBalanceDonutChart = new Chartist.Pie(
			"#current-balance-donut-chart",
			{
				labels: [1, 2],
				series: [{ meta: "Active members", value: {{ $data['total_active_members_count'] }} }, { meta: "Defaulter", value: {{ $data['total_new_members_count'] }} }]
			},
			{
				donut: true,
				donutWidth: 8,
				showLabel: false,
				plugins: [
					Chartist.plugins.tooltip({ class: "current-balance-tooltip", appendToBody: true }),
					Chartist.plugins.fillDonut({
						items: [
							{
								content: '<p class="small">{{__("Defaulter") }}</p><h5 class="mt-0 mb-0 center-align">'+{{$data['total_new_members_count']}}+'</h5>'
							}
						]
					})
				]
			}
		)
	})(window, document, jQuery);
</script>
@endsection