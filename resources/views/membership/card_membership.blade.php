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
		  width: 2.5cm;
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
		table td,th{
			font-size: 10px !important;
		}
		
		
	</style>
</head>

<body>

	<div id="page-content">
		@php
			$slno = 0;
		@endphp
		@foreach($data['member_view'] as $member)
		<div class="label" @if($slno%2==0)style="border-right: 1px dotted black;"@endif >
			<p>NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</p>

			<p style="margin-left: 38px;">NAME : {{ $member->name }}</p>
			<p style="margin-left: 38px;">BANK : {{ $member->company_name }}</p>
			<div class="">
				<div class="" >
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
											<br/>
									@php
										if($member->city_name!=''){
											echo $member->city_name.'-';
										}
									@endphp
									{{ $member->postal_code }} 
								</td>
							</tr>
						</table>
						
						<br/>
						<br/>
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
	</script>>
</body>
</html>