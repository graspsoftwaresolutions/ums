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
		xborder:1px solid blue;
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
		xborder:2px solid red; 
    }
    .subpage {
        xpadding: 1cm;
		margin: -8mm;
		border: 1px white solid;
		width: 88mm;
        height: 61mm;   
        xborder:2px solid red; 
    }
    
    @page {
		width: 88mm;
        height: 62mm;      
        margin: 0;
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
			width:250px;
			float:left;
			display: block;
			xoverflow: hidden;
			xwhite-space: nowrap;
			
			word-spacing: -5px;
			xborder:1px solid red;
		}
		#labeladdr{
			margin-top:18px;
			margin-left:127px;
			position:absolute;
			font-size:12px;
			font-family: "Courier New";
			font-weight: bold;
			text-transform:uppercase;
			letter-spacing:0mm;
			width:200px;
			white-space: nowrap;
			display: block;
			overflow-wrap: break-word;
			word-wrap: break-word;
			hyphens: auto;
			word-spacing: -1mm;
			
			xdisplay: block;
			xoverflow: hidden;
			white-space: nowrap;
			xword-spacing: -5px;
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
			overflow-wrap: break-word;
			word-wrap: break-word;
			hyphens: auto;
			word-spacing: -1mm;
			
			xdisplay: block;
			xoverflow: hidden;
			xwhite-space: nowrap;
			xoverflow-wrap: break-word;
			xword-wrap: break-word;
			xhyphens: auto;
			xword-spacing: -5px;
        }
		.alt{
			border-top:2px solid white !important;
		}
		.subpage {
        padding-top: 0.6cm !important;	 
		xborder:2px solid red; 
    	}
    @media print {
        html, body {
            width: 88mm;
            height: 62mm;       
			xborder:1px solid blue;
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
			xborder:2px solid red; 
        }
		.subpage {
        padding-top: 0.6cm !important;	 
		xborder:2px solid red; 
    	}
		.un_kel{			
			margin-top:0.03mm !important;
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
		.un_kelname{
			margin-top:50px !important;
			margin-left:115px !important;
			font-size:15px !important;
			font-family: "Courier New" !important;
			font-weight: bold !important;	
		}
		.un_kelbank{
			margin-left:115px !important;
			font-size:15px !important;
			font-family: "Courier New" !important;
			font-weight: bold !important;	
		}
		.un_keladdr{
			margin-left:205px !important;
			margin-top:30px !important;
			font-size:15px !important;
			font-family: "Courier New" !important;
			font-weight: bold !important;	
		}
		.un_kelbot{
			font-size:15px !important;
			font-family: "Courier New" !important;
			font-weight: bold !important;	
			margin-left:300px !important;
			margin-top:100px !important;
		}
		.un_kelbot1{
			font-size:15px !important;
			font-family: "Courier New" !important;
			font-weight: bold !important;	
			margin-left:300px !important;
			margin-top:125px !important;
		}
		.un_kelbot2{
			font-size:15px !important;
			font-family: "Courier New" !important;
			font-weight: bold !important;	
			margin-left:300px !important;
			margin-top:150px !important;
		}
		.un_kelbot3{
			font-size:15px !important;
			font-family: "Courier New" !important;
			font-weight: bold !important;	
			margin-left:300px !important;
			margin-top:182px !important;
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
			width:250px;
			float:left;
			display: block;
			xoverflow: hidden;
			xwhite-space: nowrap;
			
			word-spacing: -5px;
			xborder:1px solid red;
		}
		#labeladdr{
			margin-top:18px;
			margin-left:130px;
			position:absolute;
			font-size:12px;
			font-family: "Courier New";
			font-weight: bold;
			text-transform:uppercase;
			letter-spacing:0mm;
			width:200px;
			white-space: nowrap;
			display: block;
			overflow-wrap: break-word;
			word-wrap: break-word;
			hyphens: auto;
			word-spacing: -1mm;
			
			xdisplay: block;
			xoverflow: hidden;
			white-space: nowrap;
			xword-spacing: -5px;
		}
		
		#labelbankcode{
			margin-top:71px;
			margin-left:200px;
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
			margin-top:87px;
			margin-left:200px;
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
			margin-top:104px;
			margin-left:200px;
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
			margin-top:121px;
			margin-left:200px;
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
			overflow-wrap: break-word;
			word-wrap: break-word;
			hyphens: auto;
			word-spacing: -1mm;
			
			xdisplay: block;
			xoverflow: hidden;
			xwhite-space: nowrap;
			xoverflow-wrap: break-word;
			xword-wrap: break-word;
			xhyphens: auto;
			xword-spacing: -5px;
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
		   <div class="subpage @php if($member->unbid=='5'){ echo 'un_kel'; } @endphp" style='margin-top:5px 1important;'>
			<span class="@php if($i>0){ echo 'alt'; } @endphp  @php if($member->unbid=='5'){ echo 'un_kelname'; } @endphp" id="labelname">
		   @php
		   	$str = $member->name;
			echo wordwrap($str,40,"<p style='display:none !important;'>\n");
		    @endphp
		   <label style="display:none;"> </p></label>
		    </span>
            <span class="@php if($member->unbid=='5'){ echo 'un_kelbank'; } @endphp" id="labelbank">
			@php
		   	$strbank = $member->company_name;
			echo wordwrap($strbank,40,"<p style='display:none !important;'>\n");
		   	@endphp
		  <label style="display:none;"> </p></label></span>
			
			<span  class="@php if($member->unbid=='5'){ echo 'un_keladdr'; } @endphp" id="labeladdr">			
			@php
		   	$stradd1 =  $member->address_one;
			echo wordwrap($stradd1,40,"<p style='display:none !important;'>\n");
		   	@endphp
		  <label style="display:none;"> </p></label>
			</br>
			@php
		   	$stradd2 =  $member->address_two;
			echo wordwrap($stradd2,40,"<p style='display:none !important;'>\n");
		   	@endphp
		  <label style="display:none;"> </p></label>
			</br>		
			@php
			echo $member->city_name." ". $member->postal_code;
			@endphp
			
			</span>
			</br>
			<span class="@php if($member->unbid=='5'){ echo 'un_kelbot'; } @endphp" id="labelbankcode">{{ $member->companycode }}</span>
			<span class="@php if($member->unbid=='5'){ echo 'un_kelbot1'; } @endphp" id="labelicno">{{ $member->ic }}</span>
			<span class="@php if($member->unbid=='5'){ echo 'un_kelbot2'; } @endphp" id="labeldoj">{{ date('d/M/Y',strtotime($member->doj)) }}</span>
			<span class="@php if($member->unbid=='5'){ echo 'un_kelbot3'; } @endphp" id="labelmemno">{{ $member->member_number }}</span><br>
			</div>    
		</div>
		@php
			
		$i++;
		@endphp
		@endforeach
	</div>
</body>
</html>