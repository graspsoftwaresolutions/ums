<html>
<head>
	<style type="text/css" media="all">
	body{
		margin-left:24mm;
		xborder:1px solid red;
	}
		@page {
		  size: 21cm 29.7cm;
		  font-size: 10px !important;
		   margin: 0mm;
		}
		
		p{
			padding:5px 2px;
			margin:0;		
		}	
		
		
		
		/* Create two equal columns that floats next to each other */
		.column-left {
		  float: left;
		  width: 3.4cm;
		  padding: 10px;
		  height: 4.2cm;
		  border: 1px solid;
		}
		.column-right {
		  float: left;
		  padding: 10px;
		  
		}

		/* Clear floats after the columns */
		.row:after {
		  content: "";
		  display: table;
		  clear: both;
		}
		.clearfix{
			 clear: both;
		}
		.label {
		  box-sizing: content-box;
		  display: inline-block;
		  width: 8.8cm;
		  height: 6.2cm;
		 
		  xborder-bottom: 1px dotted black;
		 xborder-right: 1px dotted black;

		  xmargin: 0.3cm 0cm 0cm 1cm;
		  
		  /* padding: 5px; */
		 
		  xmargin-right: 0.3cm;
		  xpadding-left: 0.3cm;
		  xpadding-right: 0.3cm;

		  xpadding-top: 0.3cm;
		  padding-bottom: 0cm; 
		}
		.label-inner {
			width:100% !important;
			height:100% !important;
		  xpadding: 5px;	 
		  xborder-bottom: 1px dotted black;
		 xborder-right: 1px dotted black;
		
		}
		#page-content{
			size: 21cm 29.7cm;
		 	font-size: 10px !important;
			 margin-top:1mm;
			  
			font-size: 10px !important;
		}
		.label-inner img{
			width:100%;
			height:100%;
			xborder:1px solid red;
		}
		@media print{
			
			#page-content{
				font-size: 10px !important;
				margin-left:10px;
				margin-top:1mm;
			}
		}
		
	</style>
</head>

<body>
	
	<div id="page-content" xstyle="border:1px solid green;">
		@php
			$slno = 0;
		@endphp
		@foreach($data['member_view'] as $member)
		<div class="label" >
			<div class="label-inner" >
			<center><img src="{{ asset('public/assets/images/logo/cardbck.png') }}" alt="Membership logo"></center>
			
			<!--
			@php $logo = 'nube_log_cyan.png'; @endphp
			<center style="padding:5px 0px;"><img src="{{ asset('public/assets/images/logo/'.$logo) }}" alt="Membership logo" height="100"></center>
			
			<p style="text-align:center;font-size:15px;padding-bottom:30px;padding-top:10px;">This Membership Card must be returned to </br> the Union on cessation of membership</p>
			<hr style="width:200px;float:right;">
			<p style="text-align:center;font-size:15px;float:right;">Hon. General Secretary &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
			--></div>
		</div>
		@php
			$slno++;
		@endphp
		@endforeach


	</div>
	<script type="text/javascript">
		window.print();
	</script>
</body>
</html>