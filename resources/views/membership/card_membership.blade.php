<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
  <title>Membership Card</title>
  <style type="text/css">
    body {
        width: 88mm;
        height: 62mm;  
        margin: 0;
        padding: 0;
        xbackground-color: #FAFAFA;
        font: 12pt "Tahoma";
    }
    * {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }
    .page {
        width: 88mm;
        height: 62mm;  
        padding: 10mm;
        xmargin: 10mm auto;
        border: 1px white solid;
        border-radius: 5px;
        xbackground: red;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }
    .subpage {
        xpadding: 1cm;
		margin: -8mm;
        border: 1px white solid;
		width: 88mm;
        height: 61mm;   
        
    }
    
    @page {
		width: 88mm;
        height: 62mm;      
        margin: 0;
    }
	#labelstart{
			margin-top:29px;
			margin-left:140px;
			position:absolute;
			font-size:12px;
			font-family: "Courier New";
			font-weight: bold;
			text-transform:uppercase;
			letter-spacing:0mm;
			width:190px;
			xdisplay: block;
			xoverflow: hidden;
			xoverflow-wrap: break-word;
			xword-wrap: break-word;
			xhyphens: auto;
			xwhite-space: nowrap;
			word-spacing: -5px;
			border:1px solid white;
		}
        #labelbank{
			margin-top:67px;
			xmargin-left:160px;
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
            margin-left: 95px;
		}
		#labeladdr{
			margin-top:65px;
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
			margin-top:138px;
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
			margin-top:155px;
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
			margin-top:171px;
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
			margin-top:190px;
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
        #labelname{
            margin-top:23px;
			margin-left: 90px;
			font-size:12px;
			font-family: "Courier New";
			font-weight: bold;
			text-transform:uppercase;
			letter-spacing:0mm;
			
            white-space: nowrap;
            width:170px;
			display: block;
			overflow: hidden;
			white-space: nowrap;
        }
    @media print {
        html, body {
            width: 88mm;
            height: 62mm;        
        }
        .page {
            margin: 0;
            border: initial;
            border-radius: initial;
            width: 88mm;
            height: 62mm;  
            box-shadow: initial;
            background: initial;
            page-break-after: always;
        }
		#labelstart{
			margin-top:29px;
			margin-left:190px;
			position:absolute;
			font-size:12px;
			font-family: "Courier New";
			font-weight: bold;
			text-transform:uppercase;
			letter-spacing:0mm;
			width:190px;
			display: block;
			overflow: hidden;
			xoverflow-wrap: break-word;
			xword-wrap: break-word;
			xhyphens: auto;
			xwhite-space: nowrap;
			word-spacing: -5px;
			xborder:1px solid red;
		}
        #labelbank{
			margin-top:3px;
			margin-left: 75px;
			position:absolute;
			font-size:12px;
			font-family: "Courier New";
			font-weight: bold;
			text-transform:uppercase;
			letter-spacing:0mm;
			width:190px;
			display: block;
			overflow: hidden;
			white-space: nowrap;
			
			word-spacing: -5px;
			xborder:1px solid red;
		}
		#labeladdr{
			margin-top:18px;
			margin-left:135px;
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
			margin-top:68px;
			margin-left:197px;
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
			margin-top:85px;
			margin-left:195px;
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
			margin-top:102px;
			margin-left:195px;
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
			margin-top:119px;
			margin-left:195px;
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
        #labelname{
            margin-top:25px;
			margin-left: 75px;
			font-size:12px;
			font-family: "Courier New";
			font-weight: bold;
			text-transform:uppercase;
			letter-spacing:0mm;
            width:190px;
			display: block;
			overflow: hidden;
			white-space: nowrap;
        }
		.alt{
			border-top:2px solid white !important;
		}
    }
  </style>
  <script>
	window.print();
  </script>
</head>
<body>
	<div class="book">
	@php
	$i=0;
	@endphp
		@foreach($data['member_view'] as $member)
		<div class="page">
			<div class="subpage">
			
           <span id="labelname" class="@php if($i>0){ echo 'alt'; } @endphp">{{ $member->name }}  </span>
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
			<span id="labelmemno">{{ $member->member_number }}</span><br>
			</div>    
		</div>
		@php
			
		$i++;
		@endphp
		@endforeach
	</div>
</body>
</html>