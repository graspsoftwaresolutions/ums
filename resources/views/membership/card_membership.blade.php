<html>
<head>
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/print-font.css')}}">
	
	<style type="text/css" media="all">
	
	
		@page {
		  size: 21cm 29.7cm;
		  font-size: 10px !important;
		   margin: 0mm;
		   xborder:1px solid blue !important;
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
		.label:first-child {
			
		}
		.label {
		  box-sizing: content-box;
		  display: inline-block;
		  width: 8.8cm;
		  height: 6.2cm;
		 border-left: 1px dotted black;
		
		 xborder-bottom: 1px dotted black;
		 xborder-right: 1px dotted black;

		  xmargin: 0.3cm 0cm 0cm 1cm;
		  xmargin-top:0.6mm;
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
		  border-top: 1px dotted black;
		  border-bottom: 1px dotted black;
		 border-right: 1px dotted black;
		
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
				xmargin-top:1mm;
				xborder:2px solid red;

				margin-bottom:13px;

				page-break-after: always;
			}
			
			.alt { 
				xbackground-color: red !important;
				border-top:25px solid white !important; 
				}
			.label{
				margin-top: -2.75 !important;
				margin-right: -2px !important;
			}	
				
		}
		.label{
				margin-top: -2px !important;
				margin-right: -2px !important;
			}	
		#labelstart{
			margin-top:34px;
			margin-left:100px;
			position:absolute;
			font-size:12px;
			font-family: "Courier New";
			font-weight: bold;
			text-transform:uppercase;
			letter-spacing:0mm;
			width:220px;
			xdisplay: block;
			xoverflow: hidden;
			overflow-wrap: break-word;
			word-wrap: break-word;
			hyphens: auto;
			xwhite-space: nowrap;
			word-spacing: -5px;
			xborder:1px solid red;
		}
		#labelbank{
			margin-top:73px;
			margin-left:160px;
			position:absolute;
			font-size:12px;
			font-family: "Courier New";
			font-weight: bold;
			text-transform:uppercase;
			letter-spacing:0mm;
			width:160px;
			xdisplay: block;
			xxoverflow: hidden;
			xwhite-space: nowrap;
			overflow-wrap: break-word;
			word-wrap: break-word;
			hyphens: auto;
			word-spacing: -5px;
			xborder:1px solid red;
		}
		#labeladdr{
			margin-top:110px;
			margin-left:158px;
			position:absolute;
			font-size:12px;
			font-family: "Courier New";
			font-weight: bold;
			text-transform:uppercase;
			letter-spacing:0mm;
			width:160px;
			display: block;
			overflow: hidden;
			white-space: nowrap;
			word-spacing: -5px;
		}
		
		#labelbankcode{
			margin-top:158px;
			margin-left:217px;
			position:absolute;
			font-size:12px;
			font-family: "Courier New";
			font-weight: bold;
			text-transform:uppercase;
			letter-spacing:0mm;
			width:110px;
			display: block;
			overflow: hidden;
			white-space: nowrap;
			word-spacing: -5px;
		}
		#labelicno{
			margin-top:175px;
			margin-left:215px;
			position:absolute;
			font-size:12px;
			font-family: "Courier New";
			font-weight: bold;
			text-transform:uppercase;
			letter-spacing:0mm;
			width:110px;
			display: block;
			overflow: hidden;
			white-space: nowrap;
			xborder:1px solid red;
			word-spacing: -5px;
		}
		#labeldoj{
			margin-top:191px;
			margin-left:215px;
			position:absolute;
			font-size:12px;
			font-family: "Courier New";
			font-weight: bold;
			text-transform:uppercase;
			letter-spacing:0mm;
			width:110px;
			display: block;
			overflow: hidden;
			white-space: nowrap;
			xborder:1px solid red;
			word-spacing: -5px;
		}
		#labelmemno{
			margin-top:208px;
			margin-left:215px;
			position:absolute;
			font-size:12px;
			font-family: "Courier New";
			font-weight: bold;
			text-transform:uppercase;
			letter-spacing:0mm;
			width:110px;
			display: block;
			overflow: hidden;
			white-space: nowrap;
			xborder:1px solid red;
			word-spacing: normal;
			word-spacing: -5px;
		}
		body{
margin-top:0px !important;
padding-top:0px !important;
			xborder-top: 1px dotted red;
			xborder-left: 1px dotted black;
			
		}
		.alt { 
				xbackground-color: red;
				xborder-bottom:1px solid green; 
				}
				.subpage {
        padding: 1cm;
        border: 5px red solid;
        height: 257mm;
        outline: 2cm #FFEAEA solid;
    }
	</style>
	<script>
	
	</script>
</head>

<body>
	
	<div id="page-content">
		@php
			$slno = 0;
		@endphp
		
		@foreach($data['member_view'] as $member)
		
		<div class="label @php if($slno % 8 == 0){ echo 'alt'; } @endphp">
			<div class="label-inner">
			<span id="labelstart">{{ $member->name }}  </span>
			<span id="labelbank">{{ $member->company_name }}</span>
			<span id="labeladdr">
			{{ $member->address_one }}, </br>
			{{ $member->address_two }}	</br>		
			@php
			echo $member->city_name." ". $member->postal_code;
			@endphp
			</span>
			<span id="labelbankcode">{{ $member->companycode }}</span>
			<span id="labelicno">{{ $member->ic }}</span>
			<span id="labeldoj">{{ date('d/M/Y',strtotime($member->doj)) }}</span>
			<span id="labelmemno">{{ $member->member_number }}</span>
			<center><img src="{{ asset('public/assets/images/logo/idfront.png') }}"></center>
		<!--	<center><img src="{{ asset('public/assets/images/logo/frontcardfinal.png') }}" alt="Membership logo"></center>
		--><!--	<center><img src="{{ asset('public/assets/images/logo/fronttext.png') }}" alt="Membership logo"></center>
			-->
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