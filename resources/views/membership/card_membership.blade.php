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
        padding-top: 0.1cm !important;	 
		xborder:2px solid red; 
    	}
		.un_klb{
			margin-top:-5px !important;
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
        padding-top: 0.15cm !important;	 
		xborder:2px solid red; 
    	}
		.un_kel{			
			margin-top:-4px !important;
			xborder:1px solid red;
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
			margin-left:95px !important;
			font-size:14px !important;
			font-family: "Courier New" !important;
			font-weight: bold !important;	
			letter-spacing:0mm !important;
            width:250px !important;
			xborder:5px solid red;
			white-space: nowrap !important; 
			overflow-wrap: break-word !important;
			word-wrap: break-word !important;
			hyphens: auto !important;
			word-spacing: -1mm !important;
			
		}
		.un_kelbank{
			margin-left:95px !important;
			font-size:14px !important;
			width:300px !important;
			xborder:1px solid red;
			overflow-wrap: break-word !important;
			word-wrap: break-word !important;
			hyphens: auto !important;
			font-family: "Courier New" !important;
			font-weight: bold !important;	
		}
		.un_keladdr{
			margin-left:160px !important;
			margin-top:25px !important;
			font-size:14px !important;
			font-family: "Courier New" !important;
			font-weight: bold !important;	
			white-space: nowrap !important; 
			overflow-wrap: break-word !important;
			word-wrap: break-word !important;
			hyphens: auto !important;
			word-spacing: -1mm !important;
			xborder:2px solid red;
		}
		.un_kelbot{
			font-size:16px !important;
			font-family: "Courier New" !important;
			font-weight: bold !important;	
			margin-left:270px !important;
			margin-top:94px !important;
		}
		.un_kelbot01{
			font-size:14px !important;
			font-family: "Courier New" !important;
			font-weight: bold !important;	
			margin-left:240px !important;
			margin-top:79px !important;
		}
		.un_kelbot1{
			font-size:17px !important;
			font-family: "Courier New" !important;
			font-weight: bold !important;	
			margin-left:270px !important;
			margin-top:116px !important;
		}
		
		.un_kelbot2{
			font-size:17px !important;
			font-family: "Courier New" !important;
			font-weight: bold !important;	
			margin-left:280px !important;
			margin-top:137px !important;
		}
		.un_kelbot11{
			width:140px !important;
			xborder:1px solid red !important;
			font-size:14px !important;
			font-family: "Courier New" !important;
			font-weight: bold !important;	
			margin-left:240px !important;
			margin-top:100px !important;
		}
		.un_kelbot21{
			font-size:14px !important;
			font-family: "Courier New" !important;
			font-weight: bold !important;	
			margin-left:240px !important;
			margin-top:119px !important;
		}
		.un_kelbot3{
			font-size:17px !important;
			font-family: "Courier New" !important;
			font-weight: bold !important;	
			margin-left:270px !important;
			margin-top:161px !important;
		}
		.un_kelbot31{
			font-size:14px !important;
			font-family: "Courier New" !important;
			font-weight: bold !important;	
			margin-left:240px !important;
			margin-top:139px !important;
		}
		.addrtwo{
			margin-left:-25px !important;
		}
		.addrthree{
			margin-left:-25px !important;
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
            width:250px !important;
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
		.un_klb{
			margin-top:66px !important;
		}
		.un_klb1{
			margin-top:84px !important;
		}
		.un_klb2{
			margin-top:101px !important;
		}
		.un_klb3{
			margin-top:119px !important;
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
	//var_dump(count($data['member_view']));
	$kelct = count($data['member_view']);
	@endphp
		@foreach($data['member_view'] as $member)
		<div  class="page">
			@php 
			if($member->unbid=='6'){ 
			@endphp
				<p style="xborder:1px solid red; margin-top:-12px !important;"></p>
			@php
			} 
			if(($member->unbid!='6') && ($member->unbid!='5')){ 
			@endphp
				<p style="xborder:1px solid red; margin-top:9px !important;"></p>
			@php
			} 
			@endphp
		   <div class="subpage @php if($member->unbid=='5'){ echo 'un_kel'; } @endphp" style="margin-top:5px 1important;">
			<span class="@php if($i>0){ echo 'alt'; } @endphp  @php if($member->unbid=='5'){ echo 'un_kelname'; } @endphp" id="labelname">
		   @php
		   if($member->unbid=='5'){
		   if($i>0){ 
			  // echo 'alt';
			}
		   }
		   	$str = $member->name;
			echo wordwrap($str,35,"<p style='display:none !important;'>\n");
		    @endphp
		   <label style="display:none;"> </p></label>
		    </span>
            <span style="xmargin-top:-1px !important" class="@php if($member->unbid=='5'){ echo 'un_kelbank'; } @endphp" id="labelbank">
			@php
		   	$strbank = $member->company_name;
			echo wordwrap($strbank,40,"<p style='display:none !important;'>\n");
		   	@endphp
		  <label style="display:none;"> </p></label></span>
			
			<span style="margin-top:21px !important" class="@php if($member->unbid=='5'){ echo 'un_keladdr'; } @endphp" id="labeladdr">			
			@php
		   	$stradd1 =  $member->address_one;
			echo wordwrap($stradd1,30,"<p style='display:none !important;'>\n");
		   	@endphp
		  <label style="display:none;"> </p></label>
		  <span class="addrtwo">
			</br>
			@php
		   	$stradd2 =  $member->address_two;
			echo wordwrap($stradd2,40,"<p style='display:none !important;'>\n");
		   	@endphp
			</span>
		  <label style="display:none;"> </p></label>
		  <span class="addrthree">
			</br>		
			@php
			echo $member->city_name." ". $member->postal_code;
			@endphp
			</span>
			</span>
			</br>
			@php
			if(($member->unbid!='6') && ($member->unbid!='5')){ 
			@endphp
			<p style="margin-top:-20px;"></p>
			@php
			}
			@endphp
			<span class="@php if($member->unbid=='5'){ if($kelct==1) {  echo 'un_kelbot01'; } else{ echo 'un_kelbot';} } if($member->unbid=='6'){ echo 'un_klb'; } @endphp" id="labelbankcode">{{ $member->companycode }}</span>
			<span class="@php if($member->unbid=='5'){ if($kelct==1) { echo 'un_kelbot11'; } else{ echo 'un_kelbot1'; }  } if($member->unbid=='6'){ echo 'un_klb1'; } @endphp" id="labelicno">{{ $member->ic }}</span>
			<span class="@php if($member->unbid=='5'){ if($kelct==1) { echo 'un_kelbot21'; } else{ echo 'un_kelbot2'; } } if($member->unbid=='6'){ echo 'un_klb2'; } @endphp" id="labeldoj">{{ date('d/M/Y',strtotime($member->doj)) }}</span>
			<span class="@php if($member->unbid=='5'){ if($kelct==1) { echo 'un_kelbot31'; } else{ echo 'un_kelbot3'; } } if($member->unbid=='6'){ echo 'un_klb3'; } @endphp" id="labelmemno">{{ $member->member_number }}</span><br>
			</div>    
		</div>
		@php
			
		$i++;
		@endphp
		@endforeach
	</div>
</body>
</html>