<html>
<head>
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
		  padding: 10px;
		  height: 3.5cm;
		  border: 1px solid;
		}
		.column-right {
		  float: left;
		  width: 5.2cm;
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

		  height: 6.4cm;
		 // border-collapse: collapse;
		  
		  border-bottom: 1px dotted black;
		  border-right: 1px dotted black;
		  //outline-style: dotted;
		  //padding-top: 0.7cm;
		  //padding-left: 1cm;

		  margin-left: 0.3cm;
		  padding-left: 0.4cm;
		  padding-right: 0.4cm;

		  //padding-left: 0.5cm;
		  //padding-right: 0.5cm;
		  padding-top: 0.9cm;
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
		}
		html{
			font-family: "Courier New";
			font-weight: bold;
		}
		
		
	</style>
</head>

<body>

	<div id="page-content">
		@php
			$slno = 0;
		@endphp
		@foreach($data['member_view'] as $member)
		<div class="label" style="@if($slno%2==0)@endif @if($slno<2)border-top: 1px dotted black;@endif border-left: 1px dotted black;">
			<div class="label-inner" >
			<p style="font-family: arial;padding:5px 0px;">NATIONAL UNION OF BANK EMPLOYEES PENINSULAR MALAYSIA</p>

			<p style="margin-left: 38px;">NAME : {{ $member->name }}</p>
			<p style="margin-left: 38px;">BANK : {{ $member->company_name }}</p>
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
									ADD:
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
										echo strlen($total_addr);
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
									BANK-ID
								</td>
								<td>
									: {{ $member->companycode }}
								</td>
							</tr>
							<tr>
								<td>
									I/C NO
								</td>
								<td>
									: {{ $member->ic }}
								</td>
							</tr>
							<tr>
								<td>
									DATE JOINED
								</td>
								<td>
									: {{ date('d/M/Y',strtotime($member->doj)) }}
								</td>
							</tr>
							<tr>
								<td>
									MEMBERSHIP NO
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