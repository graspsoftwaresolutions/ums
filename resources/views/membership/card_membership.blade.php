<html>
<head>
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/print-font.css')}}">
	
<style type="text/css" media="all">
body{
	margin: 0mm;		  
	padding:0mm;
}
	@page {
		  size: 8.8cm 6.5cm;		
		  font-size: 10px !important;
		  margin: 0mm;		   
		}
	
		.card{
			width: 8.8cm;
		    height: 6.5cm;
			xborder:1px solid red;
			margin: 0mm;		  
			padding:0mm;
			top:0;
			bottom:0;
		}
		#labelstart{
			margin-top:34px;
			margin-left:115px;
			position:absolute;
			font-size:9px;
			font-family: "Courier New";
			font-weight: bold;
			text-transform:uppercase;
			letter-spacing:0mm;
			width:220px;			
			overflow-wrap: break-word;
			word-wrap: break-word;
			hyphens: auto;
			word-spacing: -5px;
		}
	
	@media print {
		.card{
			margin: 0mm;		  
			padding:0mm;
			top:0;
			bottom:0;
			width: 8.8cm;
		  height: 6.5cm;
			xborder:1px solid red;
		}
    

    @page {
		size: 8.8cm 6.5cm;		
    }
	body{
	margin: 0mm;		  
	padding:0mm;
}
#labelstart{
			margin-top:34px;
			margin-left:115px;
			position:absolute;
			font-size:9px;
			font-family: "Courier New";
			font-weight: bold;
			text-transform:uppercase;
			letter-spacing:0mm;
			width:220px;			
			overflow-wrap: break-word;
			word-wrap: break-word;
			hyphens: auto;
			word-spacing: -5px;
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
	<div class="card">
	<span id="labelstart">{{ $member->name }}  </span>
	</div>
	@endforeach
	
</div>
<script type="text/javascript">
	window.print();
</script>
</body>
</html>