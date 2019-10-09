<html>
<head>

	<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/print-font.css')}}">
	<style type="text/css" media="all">
		@page {
		  size: 21cm 29.7cm;
		  font-size: 10px !important;
		  margin: 0mm;
		}
		p{
			padding:2px 2px;
			margin:0;		
		}	
		
		* {
		  box-sizing: border-box;
		}
		
		/* Create two equal columns that floats next to each other */
		.column-left {
		  float: left;
		  width: 2.8cm;
		  padding: 5px;
		  height: 3.5cm;
		  border: 1px solid;
		}
		.column-right {
		  float: left;
		  width: 5.2cm;
		  padding: 5px;
		  
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
		 // border-collapse: collapse;
		  
		  border-bottom: 1px dotted black;
		  border-right: 1px dotted black;
		  //outline-style: dotted;
		  //padding-top: 0.7cm;
		  //padding-left: 1cm;

		  margin-left: 0.3cm;
		  padding-left: 0.3cm;
		  padding-right: 0.3cm;

		  //padding-left: 0.4cm;
		  //padding-right: 0.4cm;
		  padding-top: 0.3cm;
		  padding-bottom: 0cm;
		    
   			
		}
		.label-inner {
		  padding: 5px;
		 
		
		}
		#page-content{
			font-size: 10px !important;
		}
		@media print{
			#page-content{
				font-size: 10px !important;
			}
			
		}
		table td,th{
			font-size: 10px !important;
			font-family: "Courier New";
			font-weight: bold;
			word-spacing: -2px;
		}
		html{
			font-family: "Courier New";
			font-weight: bold;
			word-spacing: -2px;
		}
		.font-one{
			font-family: 'SwitzerlandCondBlack';
			word-spacing: 0px;
			font-weight: normal;
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
			<div class="label-inner" >
			<p style="padding:5px 0px;font-family: SwitzerlandCondBlack;" class="font-one">NATIONAL UNION OF BANK EMPLOYEES PENINSULAR MALAYSIA</p>

			<p style="margin-left: 38px;"> <span class="font-one"> NAME </span>: {{ $member->name }}</p>
			<p style="margin-left: 38px;padding-bottom:8px" ><span class="font-one">BANK </span> : {{ $member->company_name }}</p>
			<div class="">
					@php
						$total_addr = $member->address_one;
						$total_addr .= $member->address_two;
						$total_addr .= $member->city_name;
						$total_addr .= $member->postal_code;
					@endphp
					<div class="column-left">
						
					</div>
					<div class="column-right" >
						<table>
							<tr>
								<td width="15%" style="vertical-align: super;">
									<span class="font-one"> ADD: </span>
								</td>
								<td width="85%">
									{{ $member->address_one }}
									@if($member->address_two!='')
										{{ $member->address_two }}
									@endif
									
									@php
										if($member->city_name!=''){
											echo $member->city_name.'-';
										}
										
									@endphp
									{{ $member->postal_code }}
									@if(strlen($total_addr)<60)
									<br/>
									<br/>
									@endif
									@if(strlen($total_addr)<=38)
									<br/>
									@endif
								</td>
							</tr>
						</table>
						
						
						<table>
							<tr>
								<td>
									<span class="font-one"> BANK-ID </span>
								</td>
								<td>
									: {{ $member->companycode }}
								</td>
							</tr>
							<tr>
								<td>
									<span class="font-one"> I/C NO </span>
								</td>
								<td>
									: {{ $member->ic }}
								</td>
							</tr>
							<tr>
								<td>
									<span class="font-one"> DATE JOINED </span>
								</td>
								<td>
									: {{ date('d/M/Y',strtotime($member->doj)) }}
								</td>
							</tr>
							<tr>
								<td>
									<span class="font-one">MEMBERSHIP NO </span>
								</td>
								<td>
									: {{ $member->member_number }}
								</td>
							</tr>
						</table>
						
					</div>
				
			</div>
			</div>
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