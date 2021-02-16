<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Membership">
    <meta name="keywords" content="Membership">
    <meta name="author" content="Membership">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Membership') }}</title>
    <link rel="apple-touch-icon" href="{{ asset('public/assetsapp-assets/images/favicon/apple-touch-icon-152x152.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('public/assets/images/favicon/favicon-32x32.png') }}">
    
    <!-- BEGIN: VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/vendors/vendors.min.css') }}">
    <!-- END: VENDOR CSS-->
    <!-- BEGIN: Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/themes/vertical-modern-menu-template/materialize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/themes/vertical-modern-menu-template/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/pages/login.css') }}">
    <!-- END: Page Level CSS-->
    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/custom/custom.css') }}">
    <style>
        select.browser-default {
            display: inline-block;
        }
        select {
            font-size: 17px;
            width: auto;
            height: 3rem;
            padding: 5px;
            border: none;
            border-bottom: 1px solid #bdbdbd;
            border-radius: 2px;
            background-color: transparent;
        }
    </style>
</head>
  <body class="vertical-layout vertical-menu-collapsible page-header-dark vertical-modern-menu 1-column login-bg  blank-page blank-page" data-open="click" data-menu="vertical-modern-menu" data-col="1-column">
    <div id="app">
        
        <main class="py-4">
            @yield('content')
        </main>
    </div>
	<script src="{{ asset('public/assets/js/vendors.min.js') }}" type="text/javascript"></script>
    <!-- BEGIN VENDOR JS-->
    <!-- BEGIN PAGE VENDOR JS-->
    <!-- END PAGE VENDOR JS-->
    <!-- BEGIN THEME  JS-->
    <script src="{{ asset('public/assets/js/plugins.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/assets/js/custom/custom-script.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/assets/js/scripts/ui-alerts.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
    <script>
        $("#LoginformValidate").validate({
            rules: {
                email: {
                    required: true,
                },
                password: {
                    required: true,
                },
            },
            //For custom messages
            messages: {
                email: {
                    required: '{{__("Please Enter Valid Email Address") }}',
                },
                password: {
                    required: '{{__("Please Enter Password") }}',
                }
            },
            errorElement: 'div',
            errorPlacement: function(error, element) {
                var placement = $(element).data('error');
                if (placement) {
                    $(placement).append(error)
                } else {
                    error.insertAfter(element);
                }
            }
        });
    </script>
</body>
</html>
