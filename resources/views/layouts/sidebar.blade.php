<!-- BEGIN: SideNav-->
    <aside class="sidenav-main nav-collapsible sidenav-light sidenav-active-square nav-collapsed">
      <div class="brand-sidebar">
        <h1 class="logo-wrapper"><a class="brand-logo darken-1" href="#"><img src="{{ asset('public/assets/images/logo/materialize-logo-color.png') }}" alt="Membership logo"><span class="logo-text hide-on-med-and-down">Membership</span></a><a class="navbar-toggler" href="#"><i class="material-icons">radio_button_checked</i></a></h1>
      </div>
      <ul class="sidenav sidenav-collapsible leftside-navigation collapsible sidenav-fixed menu-shadow" id="slide-out" data-menu="menu-navigation" data-collapsible="menu-accordion">
		<li class="navigation bold hide" style="font:size:8px;"> <center>{{ Auth::user()->name }} </center>
        </li>
		<li class="bold"><a id="dashboard_sidebar_a_id" class="waves-effect waves-cyan " href="{{ route('home',app()->getLocale()) }}"><i class="material-icons">settings_input_svideo</i><span class="menu-title" data-i18n="">{{ __('Dashboard') }}</span></a>
        </li>
        @if(CommonHelper::checkModuleAccess('master')==1)
        <li id="masters_sidebars_id" class="bold "><a class="collapsible-header waves-effect waves-cyan" href="#"><i class="material-icons">dvr	</i><span class="menu-title" data-i18n="">{{ __('Masters') }}</span></a>
          <div class="collapsible-body">
            <ul class="collapsible collapsible-sub" data-collapsible="accordion">
              <li id="country_sidebar_li_id" class=""><a id="country_sidebar_a_id" class="collapsible-body " href="{{ route('master.country',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Country Details') }}</span></a></li>
              <li id="state_sidebar_li_id" class=""><a id="state_sidebar_a_id" class="collapsible-body" href="{{route('master.state',app()->getLocale())}}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('State Details') }}</span></a></li>
              <li id="city_sidebar_li_id" class=""><a id="city_sidebar_a_id" class="collapsible-body" href="{{route('master.city',app()->getLocale())}}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('City Details') }}</span></a></li>
             
              <li id="status_sidebar_li_id" class=""><a id="status_sidebar_a_id" class="collapsible-body" href="{{ route('master.status',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Status Details')}}</span></a></li>
              
              <li id="company_sidebar_li_id" class=""><a id="company_sidebar_a_id" class="collapsible-body" href="{{route('master.company',app()->getLocale())}}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Company Details') }}</span></a></li>
              <li id="unionbranch_sidebar_li_id" class=""><a id="unionbranch_sidebar_a_id" class="collapsible-body" href="{{route('master.unionbranch',app()->getLocale())}}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Union Branch Details') }}</span></a></li>
               <li id="branch_sidebar_li_id" class=""><a id="branch_sidebar_a_id" class="collapsible-body" href="{{ route('master.branch',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Branch Details')}}</span></a></li> 
              <li id="designation_sidebar_li_id" class=""><a id="designation_sidebar_a_id" class="collapsible-body" href="{{ route('master.designation',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Designation Details')}}</span></a></li>
              <li id="race_sidebar_li_id" class=""><a id="race_sidebar_a_id" class="collapsible-body" href="{{ route('master.race',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Race Details')}}</span></a></li>
              <li id="fee_sidebar_li_id" class=""><a id="fee_sidebar_a_id" class="collapsible-body" href="{{ route('master.fee',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Fee Details')}}</span></a></li>
              <li id="reason_sidebar_li_id" class=""><a id="reason_sidebar_a_id" class="collapsible-body" href="{{ route('master.reason',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Reason Details') }}</span></a></li>
              <li id="title_sidebar_li_id" class=""><a id="title_sidebar_a_id" class="collapsible-body" href="{{ route('master.persontitle',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Person Title Details') }}</span></a></li>
              <li id="relation_sidebar_li_id" class=""><a id="relation_sidebar_a_id" class="collapsible-body" href="{{ route('master.relation',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Relation Details') }}</span></a></li>
              <li id="appform_sidebar_li_id" class="hide"><a id="appform_sidebar_a_id" class="collapsible-body" href="{{ route('master.appform',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('App Form') }}</span></a></li>
              <!-- <li id="roles_sidebar_li_id" class=""><a id="roles_sidebar_a_id" class="collapsible-body" href="{{ route('master.roles',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Roles') }}</span></a></li>
              <li id="users_sidebar_li_id" class=""><a id="users_sidebar_a_id" class="collapsible-body" href="{{ route('master.userslist',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Users') }}</span></a></li>
              <li id="formType_sidebar_li_id" class=""><a id="formType_sidebar_a_id" class="collapsible-body" href="{{ route('master.formtype',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Form Type')}}</span></a></li>-->
              </ul>
          </div>
        </li>
        
        @endif
        @if(!empty(Auth::user()))
          @php
            $user_id = Auth::user()->id;
            $get_roles = Auth::user()->roles;
            $user_role = $get_roles[0]->slug;
          @endphp
          <?php //print_r($user_role);die; ?>
          @if($user_role=='union' || $user_role=='union-branch' || $user_role=='company' || $user_role=='company-branch')
            <li class="bold"><a id="membership_sidebar_a_id" class="waves-effect waves-cyan " href="{{ route('master.membership',app()->getLocale()) }}"><i class="material-icons">settings_input_svideo</i><span class="menu-title" data-i18n="">{{ __('Member registration') }}</span></a>
          @endif
		     @if($user_role=='member')
            <li class="bold"><a id="membership_sidebar_a_id" class="waves-effect waves-cyan " href="{{ route('member.membership.profile',app()->getLocale()) }}"><i class="material-icons">account_box</i><span class="menu-title" data-i18n="">{{ __('Profile') }}</span></a>
            <li class="bold"><a id="history_sidebar_a_id" class="waves-effect waves-cyan " href="{{ url('maintenance') }}"><i class="material-icons">change_history</i><span class="menu-title" data-i18n="">{{ __('History') }}</span></a>
          @endif
        @endif

         <li id="subscriptions_sidebars_id" class="bold "><a class="collapsible-header waves-effect waves-cyan" href="#"><i class="material-icons">subscriptions</i><span class="menu-title" data-i18n="">{{ __('Subscription') }}</span></a>
          <div class="collapsible-body">
            <ul class="collapsible collapsible-sub" data-collapsible="accordion">
              @if($user_role!='member')
              <li id="subscription_sidebar_li_id" class=""><a id="subscription_sidebar_a_id" class="collapsible-body " href="{{ route('subscription.sub_fileupload',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Subscription Entry') }}</span></a></li>
              <li id="subscomp_sidebar_li_id" class=""><a id="subcomp_sidebar_a_id" class="collapsible-body " href="{{ route('subscription.sub_fileupload.sub_company',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Subscription Bank') }}</span></a></li>
              @endif
            </ul>
          </div>
        </li>
        <!--
		@php
			$form_type_list = CommonHelper::getFormTypes(1);
			$single_menu_list = CommonHelper::getSingleMenus();
		@endphp
		@foreach($form_type_list as $form)
		<li id="reports_sidebars_id" class="bold "><a class="collapsible-header waves-effect waves-cyan" href="#"><i class="material-icons">dvr	</i><span class="menu-title" data-i18n="">{{ __($form->formname) }}</span></a>
			<div class="collapsible-body">
				<ul class="collapsible collapsible-sub" data-collapsible="accordion">
					@php
						$forms_list = CommonHelper::getSubForms($form->id);
					@endphp
					@foreach($forms_list as $key => $subform)
						@if (Route::has($subform->route))
							<li id="status_sidebar_li_id" class=""><a id="status_sidebar_a_id" class="collapsible-body" href="{{ route($subform->route,app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __( $subform->formname )}}</span></a></li>
						@endif
					@endforeach
				</ul>
			</div>
		</li>
		@endforeach
		@foreach($single_menu_list as $form)
    @if (Route::has($form->route))
		<li class="bold"><a id="history_sidebar_a_id" class="waves-effect waves-cyan " href="{{ route($form->route,app()->getLocale()) }}"><i class="material-icons">change_history</i><span class="menu-title" data-i18n="">{{ __( $form->formname )}}</span></a></li>
    @endif
		@endforeach
   -->
      </ul>
      <div class="navigation-background"></div><a class="sidenav-trigger btn-sidenav-toggle btn-floating btn-medium waves-effect waves-light hide-on-large-only" href="#" data-target="slide-out"><i class="material-icons">menu</i></a>
    </aside>
    <!-- END: SideNav-->