<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	@include('layouts.head')
</head>
    <body class="vertical-layout vertical-menu-collapsible page-header-dark vertical-modern-menu 2-columns  " data-open="click" data-menu="vertical-modern-menu" data-col="2-columns">
		
		@include('layouts.header')
		@include('layouts.sidebar')
		@section('main-content')
			@show
		@include('layouts.footer')
    </body>
</html>
