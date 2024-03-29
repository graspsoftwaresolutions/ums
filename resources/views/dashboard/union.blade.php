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
canvas#custom-line-chart-sample-three {
    height: 298px !important;
}
@media (max-width: 992px) {
	.dash-tab-clearfix {
	  clear:both;
	}
}
@media (max-width: 1024px) {
	.hide-on-med-and-down {
	  display:none;
	}
}
#container{
	font-family: 'Muli', sans-serif !important;
}
text{
	font-family: 'Muli', sans-serif !important;
}
.highcharts-legend{
	display:none;
}








#card-stats {
    padding-top: 1px !important;
}
.card {
    overflow: hidden;
    margin: 3px 0 3px 0 !important;
}
#main .content-wrapper-before {
    height: 0px;
}
footer.page-footer {
    padding-top: 1px;
    bottom: 0px;
    width: 100%;
}
.page-footer .footer-copyright {
    min-height:20px !important;
    padding: 7px 0;
}
.current-balance-container {
    /*border: 1px solid #CCC;*/
}
#resigned-members-donut-chart {
    height: 170px;
    webkit-filter: drop-shadow(0px 10px 4px rgba(133, 3, 168, .2));
    filter: drop-shadow(0px 10px 4px rgba(133, 3, 168, .2));
}
.card .card-title {
    font-size: 17px;
}
</style>
@endsection
<!-- card stats start -->
<div id="card-stats">
   <div class="row mt-1">
       <div style="">
      <div class="col s12 m6 l3">
        <a style="color:white" href="{{route('master.unionbranch', app()->getLocale())}}">
         <div class="card animate fadeLeft">
            <div class="card-content cyan white-text">
               <p class="card-stats-title"> {{__('No of Union Branch') }}</p>
               <h4 class="card-stats-number white-text">{{ $data['union_branch_count'] }}</h4>
               <!-- <p class="card-stats-compare">
                  <i class="mdi-creation"></i> 15%
                  <span class="cyan text text-lighten-5">from yesterday</span>
               </p> -->
            </div>
            <div class="card-action cyan darken-1">
               <div id="clients-bar" class="center-align">{{__('Union Branch List') }}</div>
            </div>
         </div>
         </a> 
      </div>
      
      <div class="col s12 m6 l3">
        <a style="color:white" href="{{route('master.company', app()->getLocale())}}">
         <div class="card animate fadeRight">
            <div class="card-content orange lighten-1 white-text">
               <p class="card-stats-title"><i class="material-icons"></i>{{__('No of Companies') }}</p>
               <h4 class="card-stats-number white-text">{{ $data['total_company_count'] }}</h4>
            </div>
            <div class="card-action orange">
               <div id="profit-tristate" class="center-align">{{__('Companies List') }} </div>
            </div>
         </div>
         </a>
      </div>
	  <div class="dash-tab-clearfix"/>
      <div class="col s12 m6 l3">
        <a style="color:white" href="{{route('master.branch', app()->getLocale())}}">
         <div class="card animate fadeRight">
            <div class="card-content green lighten-1 white-text">
               <p class="card-stats-title"><i class="material-icons"></i>{{__('No of Company Branches') }}</p>
               <h4 class="card-stats-number white-text">{{ $data['total_company_branch_count'] }}</h4>
            </div>
            <div class="card-action green">
               <div id="invoice-line" class="center-align"> {{__('Company Branches List') }}</div>
            </div>
         </div>
         </a>
      </div>
	  <div class="col s12 m6 l3">
        <a style="color:white" href="{{url(app()->getLocale().'/membership')}}">
         <div class="card animate fadeLeft">
            <div class="card-content red accent-2 white-text">
               <p class="card-stats-title"><i class="material-icons"></i>{{__('No of Members') }}</p>
               <h4 class="card-stats-number white-text">{{ $data['total_member_count'] }}</h4>
              
            </div>
            <div class="card-action red">
               <div id="sales-compositebar" class="center-align">{{__('Members List') }}</div>
            </div>
         </div>
         </a>
      </div>
      </div>
       <div class="dash-tab-clearfix"/>
      
    <div class="dash-tab-clearfix"/>
      <div class="col s12 m6 l3 hide">
        <a style="color:white" href="{{ route('resignation.add', app()->getLocale())  }}">
         <div class="card animate fadeRight">
            <div class="card-content green lighten-1 white-text">
               <p class="card-stats-title"><i class="material-icons"></i>{{__('New Resignation') }}</p>
              <!--  <h4 class="card-stats-number white-text"></h4> -->
            </div>
            <div class="card-action green hide">
               <div id="invoice-line" class="center-align"> {{__('Company Branches List') }}</div>
            </div>
         </div>
         </a>
      </div>
      <div class="dash-tab-clearfix"/>
   </div>
</div>
<div class="row">
  
</div>
<!--card stats end-->
 <!-- Current balance & total transactions cards-->
<div class="row mt-1" style="background:#f9f9f9; padding:10px 10px 0;">
   <div class="col s12 m6 l3">
      <!-- Current Balance -->
		<div id="ct9-chart" class="ct-chart card hide">
		  <div class="card-content">
			 <h4 class="card-title">{{__('Total members') }}</h4>
			 <p class="caption">
				<a href="" target="_blank">Chartist-js</a> A very simple pie chart with label interpolation to show percentage instead of the actual data series value.
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
            <h5 class="center-align">{{ $data['total_approved_members_count'] }}</h5>
            <p class="medium-small center-align">{{__('Approved Members') }}</p>
         </div>
      </div>
   </div>
   <div class="col s12 m6 l3">
      <!-- Current Balance -->
		
      <div class="card animate fadeLeft">
         <div class="card-content">
            <h4 class="card-title mb-0">{{__('Resignation Members') }}<i class="material-icons float-right hide">more_vert</i></h4>
			   
			     </br>
            <div class="current-balance-container">
				
               <div id="resigned-members-donut-chart" class="current-balance-shadow"></div>
            </div>
            <h5 class="center-align">{{ $data['totla_resigned_member_count'] }}</h5>
            <p class="medium-small center-align">{{__('Resigned Members') }}</p>
         </div>
      </div>
   </div>
   <div class="col s12 m12 l6 animate fadeRight" style="padding:0px 15px;margin:0;">
		<div id="container" style="min-width: 100%; height: 340px; margin: 0px 0 auto"></div>
   </div>
   @php
   $data_status = CommonHelper::getStatus();
   @endphp
</div>

@section('footerSecondSection')
<script src="{{ asset('public/assets/js/highchartjs/highcharts.js') }}" type="text/javascript"></script>


<script>
	var data = {
	  series: [9, 3]
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
				series: [{ meta: "Approved members", value: {{ $data['total_approved_members_count'] }} }, { meta: "Pending", value: {{ $data['total_pending_members_count'] }} }]
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
								content: '<a href="'+base_url+'/en/membership_list"><p class="small">{{__("Pending") }}</p><h5 class="mt-0 mb-0 center-align">'+{{$data['total_pending_members_count']}}+'</h5></a>'
							}
						]
					})
				]
			}
		)
   })(window, document, jQuery);

   (function (window, document, $) {
    // Donut chart
    // -----------
    var CurrentBalanceDonutChart = new Chartist.Pie(
      "#resigned-members-donut-chart",
      {
        labels: [1, 2],
        series: [{ meta: "Resigned members", value: {{ $data['totla_resigned_member_count'] }} }, { meta: "Pending", value: {{ $data['total_resignpending_members_count'] }} }]
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
                content: '<a href="'+base_url+'/en/resignation_list"><p class="small">{{__("Pending") }}</p><h5 class="mt-0 mb-0 center-align">'+{{$data['total_resignpending_members_count']}}+'</h5></a>'
              }
            ]
          })
        ]
      }
    )
   })(window, document, jQuery);
   //Sampel Line Chart Three
    // Options
	//High Chart starts 
	Highcharts.chart('container', {
    chart: {
        type: 'areaspline'
    },
    title: {
        text: 'Statuswise members count'
    },
    legend: {
        layout: 'vertical',
        align: 'left',
        verticalAlign: 'top',
        x: 150,
        y: 100,
        floating: true,
        borderWidth: 1,
        backgroundColor:
            Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF'
    },
    xAxis: {
        categories: ['ACTIVE','DEFAULTER','STRUCKOFF','RESIGNED'],
        plotBands: [{ // visualize the weekend
            from: 4.5,
            to: 6.5,
            color: 'rgba(68, 170, 213, .2)'
        }]
    },
    yAxis: {
        title: {
            text: 'Count'
        }
    },
    tooltip: {
        shared: true,
        valueSuffix: ''
    },
    credits: {
        enabled: false
    },
    plotOptions: {   
		 series: {
			dataLabels: {
				enabled: true
			},
			fillColor: {
                linearGradient: [0, 0, 0, 300],
                stops: [
                    [0, '#7b1fa2'],
                    [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                ]
            }
		},
    },
    series: [{
		name : "Status Count",
		data: [ @php echo  $data['totla_active_member_count'] @endphp, @php echo $data['totla_defaulter_member_count'] @endphp, @php echo $data['totla_struckoff_member_count'] @endphp,@php echo $data['totla_resigned_member_count'] @endphp]
    }]
}); 
 //High Chart Ends  
</script>
@endsection