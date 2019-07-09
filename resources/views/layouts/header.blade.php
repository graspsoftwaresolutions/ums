<!-- BEGIN: Header-->
    <header class="page-topbar" id="header">
      <div class="navbar navbar-fixed"> 
        <nav class="navbar-main navbar-color nav-collapsible sideNav-lock navbar-dark gradient-45deg-indigo-purple no-shadow">
          <div class="nav-wrapper">
            <div class="header-search-wrapper hide-on-med-and-down"><i class="material-icons">search</i>
              <input class="header-search-input z-depth-2" type="text" name="Search" placeholder="">
            </div>
            <ul class="navbar-list right">
              <li class=""><a class="waves-effect waves-block waves-light translation-button" href="javascript:void(0);" data-target="translation-dropdown"><img src="{{ asset('public/assets/images/en_icon.png') }}" alt="English"></a></li>
              <!-- <li class="hide-on-med-and-down"><a class="waves-effect waves-block waves-light toggle-fullscreen" href="javascript:void(0);"><i class="material-icons">settings_overscan</i></a></li> -->
              <li class="hide-on-large-only"><a class="waves-effect waves-block waves-light search-button" href="javascript:void(0);"><i class="material-icons">search</i></a></li>
              <!-- <li><a class="waves-effect waves-block waves-light notification-button" href="javascript:void(0);" data-target="notifications-dropdown"><i class="material-icons">notifications_none<small class="notification-badge">5</small></i></a></li> -->
              <li><a class="waves-effect waves-block waves-light profile-button" href="javascript:void(0);" data-target="profile-dropdown"><span class="avatar-status avatar-online"><img src="{{ asset('public/assets/images/avatar/avatar-7.png') }}" alt="avatar"><i></i></span></a></li>
              <li><a class="waves-effect waves-block waves-light sidenav-trigger" href="#" data-target="slide-out-right"><i class="material-icons">format_indent_increase</i></a></li>
            </ul>
            <!-- translation-button-->
            <ul class="dropdown-content" id="translation-dropdown">
			    {{-- strlen(Request::segment(1))==2 && strlen(Request::segment(2)) ? route(\Illuminate\Support\Facades\Route::currentRouteName(), 'en') : route('home', 'en') --}}
				
              <li><a class="grey-text text-darken-1" href="{{ route('home','en') }}"><img src="{{ asset('public/assets/images/en_icon.png') }}" alt="English"> English</a></li>
              <li><a class="grey-text text-darken-1" href="{{ route('home','my') }}"><img src="{{ asset('public/assets/images/my_icon.png') }}" alt="Malay"> Malay</a></li>
            </ul>
            <!-- notifications-dropdown-->
            <!-- <ul class="dropdown-content" id="notifications-dropdown">
              <li>
                <h6>NOTIFICATIONS<span class="new badge">5</span></h6>
              </li>
              <li class="divider"></li>
              <li><a class="grey-text text-darken-2" href="#!"><span class="material-icons icon-bg-circle cyan small">add_shopping_cart</span> A new order has been placed!</a>
                <time class="media-meta" datetime="2015-06-12T20:50:48+08:00">2 hours ago</time>
              </li>
              <li><a class="grey-text text-darken-2" href="#!"><span class="material-icons icon-bg-circle red small">stars</span> Completed the task</a>
                <time class="media-meta" datetime="2015-06-12T20:50:48+08:00">3 days ago</time>
              </li>
              <li><a class="grey-text text-darken-2" href="#!"><span class="material-icons icon-bg-circle teal small">settings</span> Settings updated</a>
                <time class="media-meta" datetime="2015-06-12T20:50:48+08:00">4 days ago</time>
              </li>
              <li><a class="grey-text text-darken-2" href="#!"><span class="material-icons icon-bg-circle deep-orange small">today</span> Director meeting started</a>
                <time class="media-meta" datetime="2015-06-12T20:50:48+08:00">6 days ago</time>
              </li>
              <li><a class="grey-text text-darken-2" href="#!"><span class="material-icons icon-bg-circle amber small">trending_up</span> Generate monthly report</a>
                <time class="media-meta" datetime="2015-06-12T20:50:48+08:00">1 week ago</time>
              </li>
            </ul> -->
            <!-- profile-dropdown-->
            @php
            $auth_user = Auth::user();
            $check_member = $auth_user->hasRole('member');
            $user_id = Auth::user()->id;
            $get_roles = Auth::user()->roles;
            $user_role = $get_roles[0]->slug;
            @endphp
            <ul class="dropdown-content" id="profile-dropdown">
              <li class="center-align"><a class="grey-text text-darken-1 center-align" ><i class="material-icons"></i> {{ ucfirst($user_role) }}</a></li>
              <li><a class="grey-text text-darken-1" href="#"><i class="material-icons">person_outline</i> {{__('Profile') }}</a></li>
			 
			  @if($check_member ==1)
              <li><a class="grey-text text-darken-1" href="{{ route('member.membership.profile') }}"><i class="material-icons">person_outline</i> {{__('Membership details') }}</a></li>
			  @endif
              <!-- <li><a class="grey-text text-darken-1" href="app-chat.html"><i class="material-icons">chat_bubble_outline</i> Chat</a></li>
              <li><a class="grey-text text-darken-1" href="page-faq.html"><i class="material-icons">help_outline</i> Help</a></li>
              <li class="divider"></li>
              <li><a class="grey-text text-darken-1" href="user-lock-screen.html"><i class="material-icons">lock_outline</i> Lock</a></li> -->
              <li><a class="grey-text text-darken-1" href="{{ route('logout', app()->getLocale()) }}"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="material-icons">keyboard_tab</i> {{__('Logout')}}</a>
			   <form id="logout-form" action="{{ route('logout', app()->getLocale()) }}" method="POST" style="display: none;">
					@csrf
				</form>
			  </li>
            </ul>
          </div>
          <nav class="display-none search-sm">
            <div class="nav-wrapper">
              <form>
                <div class="input-field">
                  <input class="search-box-sm" type="search" required="">
                  <label class="label-icon" for="search"><i class="material-icons search-sm-icon">search</i></label><i class="material-icons search-sm-close">close</i>
                </div>
              </form>
            </div>
          </nav>
        </nav>
      </div>
    </header>
    <!-- END: Header-->
    <a class="toast-basic"></a>