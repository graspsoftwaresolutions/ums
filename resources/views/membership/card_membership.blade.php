<html>
<head>
	<style type="text/css" media="all">
		@page {
			size: A4 portrait; /* can use also 'landscape' for orientation */
			//margin: 1.0in;
			border: thin solid black;
			//padding: 1em;
			
			@bottom-center {
				content: element(footer);
			}
			
			@top-center {
				content: element(header);
			}
		}
			
		#page-header {
			display: block;
			position: running(header);
		}
		
		#page-footer {
			display: block;
			position: running(footer);
		}
		
		.page-number:before {
			content: counter(page); 
		}
		
		.page-count:before {
			content: counter(pages);  
		}
		.page-content-2{
			float: left;
			width:8.9cm;
			margin-left: 1cm;
			font-size: 10px;
			height: 6.8cm;
		}
		* {
		  box-sizing: border-box;
		}
		.card{
			width: 8.9cm;
			height: 6.0cm; /* Should be removed. Only for demonstration */
		}

		/* Create two equal columns that floats next to each other */
		.column-left {
		  float: left;
		  width: 3.4cm;
		  padding: 10px;
		  height: 5.5cm;
		  border: 1px solid;
		}
		.column-right {
		  float: left;
		  padding: 10px;
		  height: 5.5cm;
		  
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
	</style>
</head>

<body>
	
	<div id="page-content">
		<div class="page-content-2">
			<p>NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</p>
			
			<p>NAME : </p>
			<p>BANK : </p>
			<div class="row">
				<div class="card" >
		  			<div class="column-left">
						
					</div>
		  			<div class="column-right" >
						ADD:
						<br/>
						<br/>
						<p>BANK-ID : </p>
						<p>I/C NO : </p>
						<p>DATE JOINED: </p>
						<p>MEMBERSHIP NO: </p>
					</div>
				</div>
			</div>
		</div>
		<div class="page-content-2">
			<p>NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</p>
			
			<p>Name : </p>
			<p>Bank : </p>
			<div class="row">
				<div class="card" >
		  			<div class="column-left">
						
					</div>
		  			<div class="column-right" >
						ADD:
						<br/>
						<br/>
						<p>BANK-ID : </p>
						<p>I/C NO : </p>
						<p>Date Joined: </p>
						<p>Membership No: </p>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="page-content-2">
			<p>NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</p>
			
			<p>Name : </p>
			<p>Bank : </p>
			<div class="row">
				<div class="card" >
		  			<div class="column-left">
						
					</div>
		  			<div class="column-right" >
						ADD:
						<br/>
						<br/>
						<p>BANK-ID : </p>
						<p>I/C NO : </p>
						<p>Date Joined: </p>
						<p>Membership No: </p>
					</div>
				</div>
				
			</div>
		</div>
		<div class="page-content-2">
			<p>NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</p>
			
			<p>Name : </p>
			<p>Bank : </p>
			<div class="row">
				<div class="card" >
		  			<div class="column-left">
						
					</div>
		  			<div class="column-right" >
						ADD:
						<br/>
						<br/>
						<p>BANK-ID : </p>
						<p>I/C NO : </p>
						<p>Date Joined: </p>
						<p>Membership No: </p>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="page-content-2">
			<p>NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</p>
			
			<p>Name : </p>
			<p>Bank : </p>
			<div class="row">
				<div class="card" >
		  			<div class="column-left">
						
					</div>
		  			<div class="column-right" >
						ADD:
						<br/>
						<br/>
						<p>BANK-ID : </p>
						<p>I/C NO : </p>
						<p>Date Joined: </p>
						<p>Membership No: </p>
					</div>
				</div>
				
			</div>
		</div>
		<div class="page-content-2">
			<p>NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA</p>
			
			<p>Name : </p>
			<p>Bank : </p>
			<div class="row">
				<div class="card" >
		  			<div class="column-left">
						
					</div>
		  			<div class="column-right" >
						ADD:
						<br/>
						<br/>
						<p>BANK-ID : </p>
						<p>I/C NO : </p>
						<p>Date Joined: </p>
						<p>Membership No: </p>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>