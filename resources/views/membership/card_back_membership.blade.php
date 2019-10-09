<html>
<head>
	<style type="text/css" media="all">
		@page {
		  size: 21cm 29.7cm;
		  font-size: 10px !important;
		   margin: 0mm;
		}
		p{
			padding:5px 2px;
			margin:0;		
		}	
		
		* {
		  box-sizing: border-box;
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
		  width: 8.9cm;
		  height: 6.3cm;
		 
		  border-bottom: 1px dotted black;
		  border-right: 1px dotted black;
		  
		  //padding: 5px;
		 
		  margin-left: 0.3cm;
		  padding-left: 0.3cm;
		  padding-right: 0.3cm;

		  padding-top: 0.3cm;
		  padding-bottom: 0cm;
		}
		#page-content{
			font-size: 10px !important;
		}
		@media print{
			#page-content{
				font-size: 10px !important;
			}
		}
		
	</style>
</head>

<body>
	
	<div id="page-content">
		@php
			$slno = 0;
		@endphp
		@foreach($data['member_view'] as $member)
		<div class="label" style="@if($slno%2==0)@endif border-top: 1px dotted black; border-collapse: collapse; border-left: 1px dotted black;">
				
			@php $logo = 'nube_log_cyan.png'; @endphp
			<center style="padding:10px 0px 0px;"><img src="{{ asset('public/assets/images/logo/'.$logo) }}" alt="Membership logo" height="100"></center>
			
			<p style="text-align:center;font-size:15px;padding-bottom:40px;padding-top:10px;">This Membership Card must be returned to </br> the Union on cessation of membership</p>
			<hr style="width:200px;float:right;">
			<p style="text-align:center;font-size:15px;float:right;">Hon. General Secretary &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
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