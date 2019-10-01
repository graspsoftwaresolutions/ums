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
		  display: inline-block;
		  width: 8.9cm;
		  height: 6.4cm;
		  //border: 1px solid black;
		  padding: 5px;
		  margin-left: 1cm;
		  margin-top: 0.7cm;
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
		@foreach($data['member_view'] as $member)
		<div class="label">
				
			@php $logo = CommonHelper::getLogo(); @endphp
			<center style="padding-top:40px;"><img src="{{ asset('public/assets/images/logo/'.$logo) }}" alt="Membership logo" height="60"></center>
			
			<p style="text-align:center;font-size:15px;padding-bottom:40px;">This Membership Card must be returned to </br> the Union on cessation of membership</p>
			<hr style="width:200px;float:right;">
			<p style="text-align:center;font-size:15px;float:right;">Hon. General Secretary</p>
		</div>
		@endforeach


	</div>
</body>
</html>