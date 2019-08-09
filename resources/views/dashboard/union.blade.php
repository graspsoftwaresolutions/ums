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
            <h5 class="center-align">{{ $data['total_approved_members_count'] }}</h5>
            <p class="medium-small center-align">{{__('Approved Members') }}</p>
         </div>
      </div>
   </div>
   <div class="col s12 m8 l8 animate fadeRight">

      <div id="chartjs" class="card pt-0 pb-0 animate fadeLeft">
         <div class=" ml-2">
				<span class=" badge  gradient-shadow mt-2 mr-2" style="color:orange">{{$data['totla_resigned_member_count']}} Resigned</span>
				<span class=" badge gradient-shadow mt-2 mr-2" style="color:#e75480">{{$data['totla_struckoff_member_count']}} Strukoff</span> 
				<span class="badge gradient-shadow mt-2 mr-2" style="color:blue">{{$data['totla_defaulter_member_count']}} Deaulter</span> 
             <span class="badge gradient-shadow mt-2 mr-2" style="color:purple;">{{$data['totla_active_member_count']}} Active</span>
			 
			 
			
			
          <p class="mt-2 mb-0 font-weight-600">Total Members</p>
            <p class="no-margin grey-text lighten-3">{{ $data['total_member_count']}}</p> 
         </div>
         <div class="row">
            <div class="sample-chart-wrapper card-gradient-chart">
               <canvas id="custom-line-chart-sample-three" class="center"></canvas>
            </div>
         </div>
      </div> 
   </div>
   @php
   $data_status = CommonHelper::getStatus();
   @endphp
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
								content: '<p class="small">{{__("Pending") }}</p><h5 class="mt-0 mb-0 center-align">'+{{$data['total_pending_members_count']}}+'</h5>'
							}
						]
					})
				]
			}
		)
   })(window, document, jQuery);
   //Sampel Line Chart Three
    // Options
    var SLOption = {
        responsive: true,
        maintainAspectRatio: true,
        datasetStrokeWidth: 3,
        pointDotStrokeWidth: 4,
        tooltipFillColor: "rgba(0,0,0,0.6)",
        legend: {
            display: false,
            position: "bottom"
        },
        hover: {
            mode: "label"
        },
        scales: {
            xAxes: [
                {
                    display: false
                }
            ],
            yAxes: [
                {
                    display: false
                }
            ]
        },
        title: {
            display: false,
            fontColor: "#FFF",
            fullWidth: false,
            fontSize: 40,
            text: "82%"
        }
    };
 
    // var SLlabels = @php echo json_encode($data_status) @endphp;
   var SLlabels = ['x','ACTIVE','DEFAULTER','STRUCKOFF','RESIGNED','y'];
    var LineSL3ctx = document.getElementById("custom-line-chart-sample-three").getContext("2d");
    var gradientStroke = LineSL3ctx.createLinearGradient(500, 0, 0, 200);
    gradientStroke.addColorStop(0, "#8133ff");
    gradientStroke.addColorStop(1, "#ff4bac");

    var gradientFill = LineSL3ctx.createLinearGradient(500, 0, 0, 200);
    gradientFill.addColorStop(0, "#8133ff");
    gradientFill.addColorStop(1, "#ff4bac");

    var SL3Chart = new Chart(LineSL3ctx, {
        type: "line",
        data: {
            labels: SLlabels,
            datasets: [
                {
                    label: "Members Count",
                   
                    borderColor: gradientStroke,
                    pointColor: "#fff",
                    pointBorderColor: gradientStroke,
                    pointBackgroundColor: "#fff",
                    pointHoverBackgroundColor: gradientStroke,
                    pointHoverBorderColor: gradientStroke,
                    pointRadius: 4,
                    pointBorderWidth: 1,
                    pointHoverRadius: 4,
                    pointHoverBorderWidth: 1,
                    fill: true,
                    backgroundColor: gradientFill,
                    borderWidth: 1,
                    data: [0, @php echo  $data['totla_active_member_count'] @endphp, @php echo $data['totla_defaulter_member_count'] @endphp, @php echo $data['totla_struckoff_member_count'] @endphp,@php echo $data['totla_resigned_member_count'] @endphp,0]
                }
            ],
            
        },
        options: SLOption
    });
</script>
@endsection