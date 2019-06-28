<!-- BEGIN: SideNav-->
    <aside class="sidenav-main nav-expanded nav-lock nav-collapsible sidenav-light sidenav-active-square">
      <div class="brand-sidebar">
        <h1 class="logo-wrapper"><a class="brand-logo darken-1" href="index.html"><img src="{{ asset('public/assets/images/logo/materialize-logo-color.png') }}" alt="Membership logo"><span class="logo-text hide-on-med-and-down">Membership</span></a><a class="navbar-toggler" href="#"><i class="material-icons">radio_button_checked</i></a></h1>
      </div>
      <ul class="sidenav sidenav-collapsible leftside-navigation collapsible sidenav-fixed menu-shadow" id="slide-out" data-menu="menu-navigation" data-collapsible="menu-accordion">
        <li class="bold text-center">
			 <center>{{ Auth::user()->name }}  <?php //print_r(Auth::user()->roles[0]->name); ?></center>
        </li>
		<li class="bold"><a class="waves-effect waves-cyan " href=""><i class="material-icons">settings_input_svideo</i><span class="menu-title" data-i18n="">Dashboard</span></a>
        </li>
		  @role('union')
        <li class="bold active"><a class="collapsible-header waves-effect waves-cyan active" href="#"><i class="material-icons">dvr</i><span class="menu-title" data-i18n="">Masters</span></a>
          <div class="collapsible-body">
            <ul class="collapsible collapsible-sub" data-collapsible="accordion">
              <li class="active"><a class="collapsible-body" href="{{url('country')}}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>Country Details</span></a></li>
            </ul>
          </div>
        </li>
        @endrole
        <li class="bold"><a class="waves-effect waves-cyan " href="{{url('membership')}}"><i class="material-icons">settings_input_svideo</i><span class="menu-title" data-i18n="">Membership Register</span></a>
       
      </ul>
      <div class="navigation-background"></div><a class="sidenav-trigger btn-sidenav-toggle btn-floating btn-medium waves-effect waves-light hide-on-large-only" href="#" data-target="slide-out"><i class="material-icons">menu</i></a>
    </aside>
    <!-- END: SideNav-->