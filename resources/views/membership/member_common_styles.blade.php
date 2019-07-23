<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/flag-icon/css/flag-icon.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/materialize-stepper/materialize-stepper.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/themes/vertical-modern-menu-template/materialize.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/themes/vertical-modern-menu-template/style.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/pages/form-wizard.css') }}">
<style type="text/css">
	.autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; cursor:pointer; }
	.autocomplete-suggestion { padding: 8px 5px; white-space: nowrap; overflow: hidden; }
	.autocomplete-selected { background: #F0F0F0; }
	.autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
	.autocomplete-group { padding: 8px 5px; }
	.autocomplete-group strong { display: block; border-bottom: 1px solid #000; }
	@media only screen and (min-width: 993px){
		ul.stepper.horizontal {
			position: relative;
			display: flex;
			justify-content: space-between;
			height:auto !important;
			overflow: hidden;
		}
	}
	@media only screen and (min-width: 993px){
		ul.stepper.horizontal .step .step-content {
			position: absolute;
			height: 600px;
			top: 84px;
			display: block;
			left: -100%;
			width: 100%;
			overflow-y: auto;
			overflow-x: hidden;
			margin: 0;
			padding: 0 !important;
			transition: left .4s cubic-bezier(.4,0,.2,1);
		}
	}
	
	@media only screen and (min-width: 993px)
		ul.stepper.horizontal .step .step-title::before {
			position: absolute;
			counter-increment: section;
			content: counter(section);
			height: 26px;
			width: 26px;
			color: #fff;
			background-color: #b2b2b2;
			border-radius: 50%;
			text-align: center;
			line-height: 26px;
			font-weight: 400;
			transition: background-color .4s cubic-bezier(.4,0,.2,1);
			transition-property: background-color;
			transition-duration: 0.4s;
			transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
			transition-delay: 0s;
			font-size: 14px;
			left: 1px;
			top: 28.5px;
			left: 19px;
		}
		
		.loading-overlay {
			display: none;
			background: rgba( 26, 26, 26, 0.7 );
			position: fixed;
			width: 100%;
			height: 100%;
			z-index: 9999;
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
			
</style>