<!-- BEGIN: SideNav-->
    <aside class="sidenav-main nav-expanded nav-lock nav-collapsible sidenav-light sidenav-active-square">
      <div class="brand-sidebar">
        <h1 class="logo-wrapper"><a class="brand-logo darken-1" href="index.html"><img src="{{ asset('public/assets/images/logo/materialize-logo-color.png') }}" alt="Membership logo"><span class="logo-text hide-on-med-and-down">Membership</span></a><a class="navbar-toggler" href="#"><i class="material-icons">radio_button_checked</i></a></h1>
      </div>
      <ul class="sidenav sidenav-collapsible leftside-navigation collapsible sidenav-fixed menu-shadow" id="slide-out" data-menu="menu-navigation" data-collapsible="menu-accordion">
		<li class="navigation"> <center>{{ __('Welcome') }} {{ Auth::user()->name }} </center>
        </li>
		<li class="bold"><a id="dashboard_sidebar_a_id" class="waves-effect waves-cyan " href="{{ route('home',app()->getLocale()) }}"><i class="material-icons">settings_input_svideo</i><span class="menu-title" data-i18n="">{{ __('Dashboard') }}</span></a>
        </li>
		@role('union')
        <li id="masters_sidebars_id" class="bold "><a class="collapsible-header waves-effect waves-cyan" href="#"><i class="material-icons">dvr	</i><span class="menu-title" data-i18n="">{{ __('Masters') }}</span></a>
          <div class="collapsible-body">
            <ul class="collapsible collapsible-sub" data-collapsible="accordion">
              <li id="country_sidebar_li_id" class=""><a id="country_sidebar_a_id" class="collapsible-body " href="{{ route('master.country',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Country Details') }}</span></a></li>
              <li id="state_sidebar_li_id" class=""><a id="state_sidebar_a_id" class="collapsible-body" href="{{route('master.state',app()->getLocale())}}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('State Details') }}</span></a></li>
              <li id="city_sidebar_li_id" class=""><a id="city_sidebar_a_id" class="collapsible-body" href="{{route('master.city',app()->getLocale())}}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>City Details</span></a></li>
              <li id="company_sidebar_li_id" class=""><a id="company_sidebar_a_id" class="collapsible-body" href="{{url('company')}}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>Company Details</span></a></li>
              <li id="unionbranch_sidebar_li_id" class=""><a id="unionbranch_sidebar_a_id" class="collapsible-body" href="{{url('unionbranch')}}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>Union Branch Details</span></a></li>
              <li id="branch_sidebar_li_id" class=""><a id="branch_sidebar_a_id" class="collapsible-body" href="{{ route('master.branch',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Branch Details')}}</span></a></li> 
              <li id="status_sidebar_li_id" class=""><a id="status_sidebar_a_id" class="collapsible-body" href="{{ route('master.status',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Status Details')}}</span></a></li>
              <li id="designation_sidebar_li_id" class=""><a id="designation_sidebar_a_id" class="collapsible-body" href="{{ route('master.designation',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Designation Details')}}</span></a></li>
              <li id="race_sidebar_li_id" class=""><a id="race_sidebar_a_id" class="collapsible-body" href="{{ route('master.race',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Race Details')}}</span></a></li>
              <li id="fee_sidebar_li_id" class=""><a id="fee_sidebar_a_id" class="collapsible-body" href="{{ route('master.fee',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Fee Details')}}</span></a></li>
              <li id="reason_sidebar_li_id" class=""><a id="reason_sidebar_a_id" class="collapsible-body" href="{{ route('master.reason',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Reason Details') }}</span></a></li>
              <li id="title_sidebar_li_id" class=""><a id="title_sidebar_a_id" class="collapsible-body" href="{{ route('master.persontitle',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Person Title Details') }}</span></a></li>
              <li id="relation_sidebar_li_id" class=""><a id="relation_sidebar_a_id" class="collapsible-body" href="{{ route('master.relation',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Relation Details') }}</span></a></li>
            </ul>
          </div>
        </li>
        <li class="bold"><a id="membership_sidebar_a_id" class="waves-effect waves-cyan " href="{{url('membership')}}"><i class="material-icons">settings_input_svideo</i><span class="menu-title" data-i18n="">Member registration</span></a>
        </li>
        @endrole
        @role('branch')
        <li class="bold"><a id="membership_sidebar_a_id" class="waves-effect waves-cyan " href="{{url('membership')}}"><i class="material-icons">settings_input_svideo</i><span class="menu-title" data-i18n="">Member registration</span></a>
        @endrole
		
       
      </ul>
      <div class="navigation-background"></div><a class="sidenav-trigger btn-sidenav-toggle btn-floating btn-medium waves-effect waves-light hide-on-large-only" href="#" data-target="slide-out"><i class="material-icons">menu</i></a>
    </aside>
    <!-- END: SideNav-->