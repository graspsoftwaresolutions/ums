<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="Membership">
<meta name="keywords" content="Membership">
<meta name="author" content="Membership">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name', 'Membership') }}</title>
<link rel="apple-touch-icon" href="{{ asset('public/assets/images/favicon/apple-touch-icon-152x152.png') }}">
<link rel="shortcut icon" type="image/x-icon" href="{{ asset('public/assets/images/favicon/favicon-32x32.png') }}">
<link href="{{ asset('public/assets/css/google-font.css') }}" rel="stylesheet">
<!-- BEGIN: VENDOR CSS-->
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/vendors.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/animate-css/animate.css') }}">
@section('headSection')
    @show
<!-- END: VENDOR CSS-->
<!-- BEGIN: Page Level CSS-->
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/themes/vertical-modern-menu-template/materialize.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/themes/vertical-modern-menu-template/style.css') }}">
<!-- END: Page Level CSS-->
<!-- BEGIN: Custom CSS-->
@section('headSecondSection')
    @show

<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/custom/custom.css') }}">
<style>
	.loading-overlay {
		display: none;
		background: rgba( 26, 26, 26, 0.7 );
		position: fixed;
		width: 100%;
		height: 100%;
		z-index: 99999;
		top: 0;
		left: 0;
	}

	.loading-overlay-image-container {
		display: none;
		position: fixed;
		z-index: 7;
		top: 50%;
		left: 50%;
		transform: translate( -50%, -50% );
	}

	.loading-overlay-img {
		border-radius: 5px;
	}
	.select2-container{
		width:100% !important; 
	}
	#main.main-full {
		height: 750px;
		overflow: auto;
	}
	
	.footer {
	   position: fixed;
	   margin-top:50px;
	   left: 0;
	   bottom: 0;
	   width: 100%;
	   height:auto;
	   background-color: red;
	   color: white;
	   text-align: center;
	   z-index:999;
	} 
	.sidenav-main{
		z-index:9999;
	}
	  
</style>
