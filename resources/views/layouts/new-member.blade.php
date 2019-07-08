<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	@include('layouts.head')
	<style>
		#main {
			padding-left: 40px !important;
			padding-right: 40px !important;
		}
	</style>
</head>
    <body class="vertical-layout vertical-menu-collapsible page-header-dark vertical-modern-menu 2-columns  " data-open="click" data-menu="vertical-modern-menu" data-col="2-columns">
		<div id="app">
			<main class="py-4">
				@yield('content')
				
				@section('main-content')
					@show
			</main>
		</div>		
		
		@include('layouts.footer')
    </body>
</html>
