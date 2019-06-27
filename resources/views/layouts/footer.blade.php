<!-- BEGIN: Footer-->

    <footer class="page-footer footer footer-static footer-dark gradient-45deg-indigo-purple gradient-shadow navbar-border navbar-shadow">
      <div class="footer-copyright">
        <div class="container"><span>&copy; 2019          <a href="#" target="_blank">Membership</a> All rights reserved.</span><span class="right hide-on-small-only"></span></div>
      </div>
    </footer>

    <!-- END: Footer-->
    <!-- BEGIN VENDOR JS-->
    <script src="{{ asset('assets/js/vendors.min.js') }}" type="text/javascript"></script>
	<script>
		var base_url = '{{ URL::to("/") }}';
	</script>
    <!-- BEGIN VENDOR JS-->
    <!-- BEGIN PAGE VENDOR JS-->
   
	@section('footerSection')
    @show
    <!-- END PAGE VENDOR JS-->
    <!-- BEGIN THEME  JS-->
    <script src="{{ asset('assets/js/plugins.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/custom/custom-script.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/scripts/customizer.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/scripts/ui-alerts.js') }}" type="text/javascript"></script>
    <!-- END THEME  JS-->
    <!-- BEGIN PAGE LEVEL JS-->
    <!--script src="{{ asset('assets/js/scripts/intro.js') }}" type="text/javascript"></script-->
    <!-- END PAGE LEVEL JS-->
	
	@section('footerSecondSection')
    @show