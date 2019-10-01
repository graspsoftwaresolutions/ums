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
		@foreach($data['member_view'] as $member)
		<div class="label">
			<p>NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</p>

			<p>NAME : {{ $member->name }}</p>
			<p>BANK : {{ $member->company_name }}</p>
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
						<p>BANK-ID : {{ $member->companycode }}</p>
						<p>I/C NO : {{ $member->ic }}</p>
						<p>DATE JOINED: {{ $member->doj }}</p>
						<p>MEMBERSHIP NO: {{ $member->member_number }}</p>
					</div>
				</div>
			</div>
		</div>
		@endforeach
	</div>
	<script type="text/javascript">
		window.print();
	</script>>
</body>
</html>