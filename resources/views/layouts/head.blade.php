<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="Membership">
<meta name="keywords" content="Membership">
<meta name="author" content="Membership">
<meta name="csrf-token" content="{{ csrf_token() }}">
@php $logo = CommonHelper::getLogo(); @endphp
<title>{{ config('app.name', 'Membership') }}</title>
<link rel="apple-touch-icon" href="{{ asset('public/assets/images/logo/'.$logo) }}">
<link rel="shortcut icon" type="image/x-icon" href="{{ asset('public/assets/images/logo/'.$logo) }}">
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
	
	.dtr-data a{
		float : none !important;
	}
	.nav-collapsed.sidenav-main {
		left: -5px;
		width: 64px;
	}
	span.custom-badge{
		font-size: .8rem;
		line-height: 20px;
		min-width: 1rem;
		height: 20px;
		background-color: #03a9f4 !important;
		border-radius: 5px;
		font-size: 1rem;
		line-height: 22px;
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
		min-width: 3rem;
		height: 22px;
		margin-left: 14px;
		padding: 0 6px;
		text-align: center;
		color: #fff;
	}
	.padding-left-10{
		padding-left:10px;
	}
	.padding-left-20{
		padding-left:20px;
	}
	.padding-left-24{
		padding-left:24px;
	}
	.padding-left-40{
		padding-left:40px;
	}
	.btn-sm-all{
		padding: 0 10px;
		height: 30px;
	}
	.tabs .tab a:hover, .tabs .tab a.active {
		color: #3f51b5;
		background-color: #eee;
	}
	.swal-button--cancel {
		color: #fff;
		background-color: #ff5a92 !important;
	}
	.input-field > label {
	    color: #000 !important;
	}
	label {
	    color: #000 !important;
	}
	html {
	    color: #000 !important;
	}
	#main .section-data-tables .dataTables_wrapper table.dataTable tbody
	{
	    overflow: auto; 

	    max-width: 100%;
	    height: auto;
	}
	.sidenav-main{
		z-index: 99999;
	}
	  
</style>
