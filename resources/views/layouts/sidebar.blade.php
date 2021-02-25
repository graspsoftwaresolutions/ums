<!-- BEGIN: SideNav-->
@php 
  $logo = CommonHelper::getLogo();
  $user_role = '';
@endphp
@if(!empty(Auth::user()))
@php
  $user_id = Auth::user()->id;
  $get_roles = Auth::user()->roles;
  $user_role = $get_roles[0]->slug;

@endphp
@endif
    <aside class="sidenav-main nav-collapsible sidenav-light sidenav-active-square nav-collapsed">
      <div class="brand-sidebar">
        <h1 class="logo-wrapper"><a class="brand-logo darken-1" href="#"><img src="{{ asset('public/assets/images/logo/'.$logo) }}" alt="Membership logo"><span class="logo-text hide-on-med-and-down">Membership</span></a><a class="navbar-toggler" href="#"><i class="material-icons hide">radio_button_checked</i></a></h1>
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
              @if($user_role=='union')
              <li id="country_sidebar_li_id" class=""><a id="country_sidebar_a_id" class="collapsible-body " href="{{ route('master.country',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Country Details') }}</span></a></li>
              <li id="state_sidebar_li_id" class=""><a id="state_sidebar_a_id" class="collapsible-body" href="{{route('master.state',app()->getLocale())}}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('State Details') }}</span></a></li>

              <li id="city_sidebar_li_id" class=""><a id="city_sidebar_a_id" class="collapsible-body" href="{{route('master.city',app()->getLocale())}}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('City Details') }}</span></a></li>

              <li id="city_0sidebar_li_id" class=""><a id="city_0sidebar_a_id" class="collapsible-body" href="{{route('master.citytemp',app()->getLocale())}}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('City Details[0 Members]') }}</span></a></li>
             
              <li id="status_sidebar_li_id" class=""><a id="status_sidebar_a_id" class="collapsible-body" href="{{ route('master.status',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Status Details')}}</span></a></li>
              @endif
              <li id="company_sidebar_li_id" class=""><a id="company_sidebar_a_id" class="collapsible-body" href="{{route('master.company',app()->getLocale())}}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Company Details') }}</span></a></li>
              @if($user_role=='union')
              <li id="unionbranch_sidebar_li_id" class=""><a id="unionbranch_sidebar_a_id" class="collapsible-body" href="{{route('master.unionbranch',app()->getLocale())}}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Union Branch Details') }}</span></a></li>
               @endif
               <li id="branch_sidebar_li_id" class=""><a id="branch_sidebar_a_id" class="collapsible-body" href="{{ route('master.branch',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Branch Details')}}</span></a></li> 
              @if($user_role=='union')
              <li id="designation_sidebar_li_id" class=""><a id="designation_sidebar_a_id" class="collapsible-body" href="{{ route('master.designation',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Designation Details')}}</span></a></li>
              <li id="race_sidebar_li_id" class=""><a id="race_sidebar_a_id" class="collapsible-body" href="{{ route('master.race',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Race Details')}}</span></a></li>
              <li id="fee_sidebar_li_id" class=""><a id="fee_sidebar_a_id" class="collapsible-body" href="{{ route('master.fee',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Fee Details')}}</span></a></li>
              <li id="reason_sidebar_li_id" class=""><a id="reason_sidebar_a_id" class="collapsible-body" href="{{ route('master.reason',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Reason Details') }}</span></a></li>
              <li id="title_sidebar_li_id" class=""><a id="title_sidebar_a_id" class="collapsible-body" href="{{ route('master.persontitle',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Person Title Details') }}</span></a></li>
              <li id="relation_sidebar_li_id" class=""><a id="relation_sidebar_a_id" class="collapsible-body" href="{{ route('master.relation',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Relation Details') }}</span></a></li>
              <li id="relation_sidebar_li_id" class=""><a id="relation_sidebar_a_id" class="collapsible-body" href="{{ route('staff.account',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Staff Union Account') }}</span></a></li>
              <li id="appform_sidebar_li_id" class="hide"><a id="appform_sidebar_a_id" class="collapsible-body" href="{{ route('master.appform',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('App Form') }}</span></a></li>
              <li id="roles_sidebar_li_id" class="hide"><a id="roles_sidebar_a_id" class="collapsible-body" href="{{ route('master.roles',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Roles') }}</span></a></li>
              @endif
             <!--  <li id="users_sidebar_li_id" class=""><a id="users_sidebar_a_id" class="collapsible-body" href="{{ route('master.userslist',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Users') }}</span></a></li>
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
          @if($user_role=='staff-union-branch')
           <li class="bold"><a id="membership_sidebar_a_id" class="waves-effect waves-cyan " href="{{ route('master.membershipnew',app()->getLocale()) }}"><i class="material-icons">account_circle</i><span class="menu-title" data-i18n="">{{ __('Member Query') }}</span></a>
            </li>
          @endif
           @if($user_role=='irc-branch-committee')
           <li class="bold"><a id="membership_sidebar_a_id" class="waves-effect waves-cyan " href="{{ route('master.membership',app()->getLocale()) }}"><i class="material-icons">account_circle</i><span class="menu-title" data-i18n="">{{ __('Member Query') }}</span></a>
            </li>
          @endif
          @if($user_role=='union' || $user_role=='union-branch')
            <li class="bold"><a id="membership_sidebar_a_id" class="waves-effect waves-cyan " href="{{ route('master.membership',app()->getLocale()) }}"><i class="material-icons">account_circle</i><span class="menu-title" data-i18n="">{{ __('Member Query') }}</span></a>
            </li>
            @if($user_role=='union')
            <li class="bold"><a id="vmembership_sidebar_a_id" class="waves-effect waves-cyan " href="{{ route('verify.membership',app()->getLocale()) }}"><i class="material-icons">face</i><span class="menu-title" data-i18n="">{{ __('Verify Member Details') }}</span></a>
            </li>
            <li class="bold"><a id="dojmembership_sidebar_a_id" class="waves-effect waves-cyan " href="{{ route('verify.doj',app()->getLocale()) }}"><i class="material-icons">date_range</i><span class="menu-title" data-i18n="">{{ __('Verify DOJ') }}</span></a>
            </li>
            <li class="bold"><a id="tdfmembership_sidebar_a_id" class="waves-effect waves-cyan " href="{{ route('upload.tdf',app()->getLocale()) }}"><i class="material-icons">file_upload</i><span class="menu-title" data-i18n="">{{ __('TDF Upload') }}</span></a>
            </li>
             <li class="bold"><a id="sal_updatelist_sidebar_a_id" class="waves-effect waves-cyan " href="{{ route('salary.list',app()->getLocale()) }}"><i class="material-icons">system_update_alt</i><span class="menu-title" data-i18n="">{{ __('Salary Updation List') }}</span></a>
            </li>
            @endif

             
            
          @endif
           @if($user_role=='data-entry')
            <li class="bold"><a id="membership_sidebar_a_id" class="waves-effect waves-cyan " href="{{ route('master.membership',app()->getLocale()) }}"><i class="material-icons">account_circle</i><span class="menu-title" data-i18n="">{{ __('Member Query') }}</span></a></li>
          @endif
		     @if($user_role=='member')
            <li class="bold"><a id="membership_sidebar_a_id" class="waves-effect waves-cyan " href="{{ route('member.membership.profile',app()->getLocale()) }}"><i class="material-icons">account_box</i><span class="menu-title" data-i18n="">{{ __('Profile') }}</span></a></li>
            <li class="bold"><a id="history_sidebar_a_id" class="waves-effect waves-cyan hide" href="{{ url('maintenance') }}"><i class="material-icons">change_history</i><span class="menu-title" data-i18n="">{{ __('History') }}</span></a></li>
          @endif
        @endif
        @if($user_role=='union' || $user_role=='company' || $user_role=='company-branch' || $user_role == 'member')
         <li id="subscriptions_sidebars_id" class="bold "><a class="collapsible-header waves-effect waves-cyan" href="#"><i class="material-icons">subscriptions</i><span class="menu-title" data-i18n="">{{ __('Subscription') }}</span></a>
          <div class="collapsible-body">
            <ul class="collapsible collapsible-sub" data-collapsible="accordion">
              @if($user_role=='union' || $user_role=='union-branch' || $user_role=='company' || $user_role=='company-branch')
              <li id="subscription_sidebar_li_id" class=""><a id="subscription_sidebar_a_id" class="collapsible-body " href="{{ route('subscription.sub_fileupload',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Subscription Entry') }}</span></a></li>

              @if($user_role=='union')
              <li id="subadd_sidebar_li_id" class=""><a id="subadd_sidebar_a_id" class="collapsible-body " href="{{ route('subscription.sub_additional',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Subscription Additional') }}</span></a></li>
              @endif

              @if($user_role=='union')
              <li id="subscomp_sidebar_li_id" class=""><a id="subcomp_sidebar_a_id" class="collapsible-body " href="{{ route('subscription.sub_fileupload.sub_company',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Subscription Bank[Union]') }}</span></a></li>
              @endif
              <li id="subscompbank_sidebar_li_id" class=""><a id="subscompbank_sidebar_a_id" class="collapsible-body " href="{{ route('company.sub_company',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Subscription Bank[Bank]') }}</span></a></li>
               
              @if($user_role=='company')
                 <li id="subvariance_sidebar_li_id" class="hide"><a id="subvariance_sidebar_sidebar_a_id" class="collapsible-body " href="{{ route('subscription.variance',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Variation') }}</span></a></li>
                <!--li id="invalidsubs_sidebar_li_id" class=""><a id="invalidsubs_sidebar_a_id" class="collapsible-body " href="{{ route('subscription.invalidsubs',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Struckoff/Resigned') }}</span></a></li>
                  <li id="subvariance_sidebar_li_id" class=""><a id="subvariance_sidebar_sidebar_a_id" class="collapsible-body " href="{{ route('subscription.variance',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Variance list') }}</span></a></li-->
              @endif
               @if($user_role=='union')
               <li id="followup_sidebar_li_id" class=""><a id="followup_sidebar_a_id" class="collapsible-body " href="{{ route('followup.list',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Followup Members') }}</span></a></li>
               @endif
          @if($user_role=='union' || $user_role=='union-branch')
			     <li id="subvariation_sidebar_li_id" class=""><a id="subvariation_sidebar_sidebar_a_id" class="collapsible-body " href="{{ route('subscription.month',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Subscription Variation') }}</span></a></li>
           <li id="subvariationdis_sidebar_li_id" class=""><a id="subvariationdis_sidebar_sidebar_a_id" class="collapsible-body " href="{{ route('subscription.discrepancy',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Subscription Discrepancy') }}</span></a></li>
           <li id="subupsalary_sidebar_li_id" class="hide"><a id="subupsalary_sidebar_a_id" class="collapsible-body " href="{{ route('upload.salary',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Upload Salary') }}</span></a></li>
           <li id="latestsalary_sidebar_li_id" class=""><a id="latestsalary_sidebar_a_id" class="collapsible-body " href="{{ route('latestupload.salary',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Latest Upload Salary') }}</span></a></li>
			     <li id="subsarrear_sidebar_li_id" class=""><a id="subarrear_sidebar_a_id" class="collapsible-body " href="{{ route('subscription.arrearentry',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Arrear Entry') }}</span></a></li>
           <li id="subsadvance_sidebar_li_id" class=""><a id="subsadvance_sidebar_a_id" class="collapsible-body " href="{{ route('subscription.advanceentry',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Advance Payment') }}</span></a></li>
          @endif
        
              @endif
              @if($user_role == 'member')
              <!--li id="subscriptionpayment_sidebar_li_id" class=""><a id="subscriptionpayment_sidebar_a_id" class="collapsible-body " href="{{ route('subscription.sub_payment',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Subscription Payment') }}</span></a></li-->
              <li id="subscriptionpaymenthistory_sidebar_li_id" class="hide"><a id="subscriptionpaymenthistory_sidebar_a_id" class="collapsible-body " href="{{ route('subscription.sub_paymenthistory',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Subscription History') }}</span></a></li>
              <li id="member_statement_sidebar_li_id" class=""><a id="member_statement_sidebar_a_id" class="collapsible-body" href="{{ route('member.statement',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Member Statement') }}</span></a></li>
              @endif
            </ul>
          </div>
        </li>
        @endif
		
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
		<li id="ecopark_sidebars_id" class="bold "><a class="collapsible-header waves-effect waves-cyan" href="#"><i class="material-icons">photo_filter</i><span class="menu-title" data-i18n="">Eco Park</span></a>
			<div class="collapsible-body">
				<ul class="collapsible collapsible-sub" data-collapsible="accordion">
					<li id="ecopark_sidebar_li_id" class=""><a id="ecopark_sidebar_a_id" class="collapsible-body " href="{{ route('ecopark.fileupload',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>Upload File</span></a>
					</li>
          <li id="ecoparklist_sidebar_li_id" class=""><a id="ecoparklist_sidebar_a_id" class="collapsible-body " href="{{ route('ecopark.list',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>Eco Park List</span></a>
          </li>
          <li id="ecoparkreport_sidebar_li_id" class=""><a id="ecoparkreport_sidebar_a_id" class="collapsible-body " href="{{ route('ecopark.report',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>Privilege card Report</span></a>
          </li>
				</ul>
			</div>
		</li>
      @if($user_role=='union')
        <li class="bold"><a id="member_transfer_sidebar_a_id" class="waves-effect waves-cyan " href="{{ route('transfer.history',app()->getLocale()) }}"><i class="material-icons">transfer_within_a_station</i><span class="menu-title" data-i18n="">{{ __('Member Transfer History') }}</span></a></li>
      @endif
      @if($user_role=='union')
        @if (env('IRC')!='' || env('IRC')!=0)
        <li class="bold"><a id="irc_account_sidebar_a_id" class="waves-effect waves-cyan " href="{{ route('irc.list',app()->getLocale()) }}"><i class="material-icons">face</i><span class="menu-title" data-i18n="">{{ __('IRC Account') }}</span></a></li>
        @endif
      @endif
      @if($user_role=='irc-confirmation')
       <li class="bold"><a id="irc_sidebar_a_id" class="waves-effect waves-cyan " href="{{ route('irc.irc_waiters',app()->getLocale()) }}"><i class="material-icons">confirmation_number</i><span class="menu-title" data-i18n="">{{ __('Waited IRC List') }}</span></a></li>
      @endif
      @if($user_role=='irc-confirmation' || $user_role=='irc-branch-committee' || $user_role=='irc-confirmation-officials' || $user_role=='irc-branch-committee-officials')
      @if($user_role=='irc-confirmation' || $user_role=='irc-confirmation-officials')
		  <li class="bold"><a id="irc_sidebar_a_id" class="waves-effect waves-cyan " href="{{ route('irc.irc',app()->getLocale()) }}"><i class="material-icons">confirmation_number</i><span class="menu-title" data-i18n="">{{ __('IRC') }}</span></a></li>
      @else
       <li class="bold"><a id="irc_sidebar_a_id" class="waves-effect waves-cyan " href="{{ route('irc.irc_list',app()->getLocale()) }}"><i class="material-icons">confirmation_number</i><span class="menu-title" data-i18n="">{{ __('IRC List') }}</span></a></li>
      @endif
		  <li class="bold"><a id="pendirc_sidebar_a_id" class="waves-effect waves-cyan " href="{{ route('irc.irc_list',app()->getLocale()) }}?status=0"><i class="material-icons">confirmation_number</i><span class="menu-title" data-i18n="">{{ __('IRC Pending List') }}</span></a></li>
         <li class="bold"><a id="conirc_sidebar_a_id" class="waves-effect waves-cyan " href="{{ route('irc.irc_list',app()->getLocale()) }}?status=1"><i class="material-icons">confirmation_number</i><span class="menu-title" data-i18n="">{{ __('IRC Confirmed List') }}</span></a></li>
		  @endif
		@if($user_role=='union')
       <li id="data_cleaning_sidebars_id" class="bold"><a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)"><i class="material-icons">clear</i><span class="menu-title" data-i18n="Dashboard">Data Cleaning</span></a>
          <div class="collapsible-body">
            <ul class="collapsible collapsible-sub" data-collapsible="accordion">
               <li id="update_history_sidebar_li_id" class=""><a id="update_history_sidebar_a_id" class="collapsible-body " href="{{ route('history.list',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Update Unpaid History') }}</span></a></li>

               <li id="beforedoj_history_sidebar_li_id" class="hide"><a id="beforedoj_history_sidebar_a_id" class="collapsible-body " href="{{ route('history.beforedoj',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Before DOJ History') }}</span></a></li>

                <li id="due_sidebar_li_id" class=""><a id="due_sidebar_a_id" class="collapsible-body " href="{{ route('due.list',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Due Members') }}</span></a></li>
               
              <li id="members_list_sidebar_li_id" class=""><a id="members_list_sidebar_a_id" class="collapsible-body " href="{{ route('cleaning.membership',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Members List') }}[history]</span></a></li>

              <li id="members_cleanlist_sidebar_li_id" class=""><a id="members_cleanlist_sidebar_a_id" class="collapsible-body " href="{{ route('clean.membership',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Members List') }}[Profile]</span></a></li>

              <li id="stateclear_list_sidebar_li_id" class=""><a id="stateclear_list_sidebar_a_id" class="collapsible-body " href="{{ route('cleaning.state',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Clear State') }}</span></a></li>
              
              <li id="salaryclear_list_sidebar_li_id" class=""><a id="salaryclear_list_sidebar_a_id" class="collapsible-body " href="{{ route('cleaning.salary',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Clear Salary') }}</span></a></li>
              
            </ul>
          </div>
        </li>
		@endif
		   @if($user_role=='union')
	  <li id="reports_sidebars_id" class="bold "><a class="collapsible-header waves-effect waves-cyan" href="#"><i class="material-icons">receipt	</i><span class="menu-title" data-i18n="">{{ __('Reports') }}</span></a>
          <div class="collapsible-body">
            @php
              $status_list = CommonHelper::getStatusList();
            @endphp
            <ul class="collapsible collapsible-sub" data-collapsible="accordion">
              <li id="member_status0_sidebar_li_id" class=""><a id="member_status0_sidebar_a_id" class="collapsible-body" href="{{ route('reports.newmembers',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('New Members') }}</span></a></li>
              <li id="newmember_sidebar_li_id" class="hide"><a id="newmember_sidebar_a_id" class="collapsible-body" href="{{ route('reports.unionnewmembers',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('New Members[Union]') }}</span></a></li>
              <li id="members_report_sidebar_li_id" class="hide"><a id="members_report_sidebar_a_id" class="collapsible-body" href="{{ route('reports.newmembers',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Members Report') }}</span></a></li>
              @foreach ($status_list as $status)
              @if($status->id!=4)
              <li id="member_status{{strtolower($status->id)}}_sidebar_li_id" class=""><a id="member_status{{strtolower($status->id)}}_sidebar_a_id" class="collapsible-body" href="{{ route('reports.members',[app()->getLocale(),$status->id]) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ Ucfirst(strtolower($status->status_name)) }}{{ __(' Members') }}</span></a></li>
              @endif
              @endforeach
              <li id="member_status4_sidebar_li_id" class=""><a id="member_status4_sidebar_a_id" class="collapsible-body" href="{{ route('reports.resignmembers',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>Resigned Members</span></a></li>
              <li id="member_pgm_sidebar_li_id" class=""><a id="member_pgm_sidebar_a_id" class="collapsible-body" href="{{ route('reports.pgm',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>TGM Members</span></a></li>
             <li id="member_allpgm_sidebar_li_id" class="hide"><a id="member_allpgm_sidebar_a_id" class="collapsible-body" href="{{ route('reports.allpgm',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>PGM Status Members</span></a></li>
              @if($user_role=='union')
              <li id="summary_sidebar_li_id" class=""><a id="summary_sidebar_a_id" class="collapsible-body" href="{{ route('reports.summary',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>Monthly Summary</span></a></li>
              <li id="yearly_sidebar_li_id" class=""><a id="yearly_sidebar_a_id" class="collapsible-body" href="{{ route('reports.yearly',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>Yearly Based</span></a></li>
              @endif
               @if($user_role=='union' || $user_role=='union-branch')
              <li id="member_transfers_sidebar_li_id" class=""><a id="member_transfers_sidebar_a_id" class="collapsible-body" href="{{ route('reports.transfers',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>Member Transfers</span></a></li>
              <li id="branch_advice_sidebar_li_id" class=""><a id="branch_advice_sidebar_a_id" class="collapsible-body" href="{{ route('reports.advice',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>Branch Advice</span></a></li>
              <li id="branch_status_sidebar_li_id" class=""><a id="branch_status_sidebar_a_id" class="collapsible-body" href="{{ route('reports.status',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>Branch Status</span></a></li>

              <li id="designation_report_sidebar_li_id" class=""><a id="designation_report_sidebar_a_id" class="collapsible-body" href="{{ route('reports.designation',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>Designation Report</span></a></li>
              
              <li id="takaful_report_sidebar_li_id" class=""><a id="takaful_report_sidebar_a_id" class="collapsible-body" href="{{ route('reports.takaful',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>Takaful Report</span></a></li>
			       <li id="member_statistic_sidebar_li_id" class=""><a id="member_statistic_sidebar_a_id" class="collapsible-body" href="{{ route('reports.statistics',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Statistics Report') }}</span></a></li>

              <li id="member_statistic_sidebar_li_id" class=""><a id="member_statistic_sidebar_a_id" class="collapsible-body" href="{{ route('yearly.statistics',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Yearly Statistics Report') }}</span></a></li>

              <li id="membership_statistic_sidebar_li_id" class=""><a id="membership_statistic_sidebar_a_id" class="collapsible-body" href="{{ route('members.statistics',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('
              Membership Statistics') }}</span></a></li>

              <li id="monthly_statistic_sidebar_li_id" class=""><a id="monthly_statistic_sidebar_a_id" class="collapsible-body" href="{{ route('monthly.statistics',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('
              Monthly Statistics') }}</span></a></li>

              <li id="halfshare_sidebar_li_id" class=""><a id="halfshare_sidebar_a_id" class="collapsible-body" href="{{ route('union.halfshare',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Half Share') }}</span></a></li>
              <li id="member_halfshare_sidebar_li_id" class=""><a id="member_halfshare_sidebar_a_id" class="collapsible-body" href="{{ route('reports.halfshare',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Financial Half Share') }}</span></a></li>
              <li id="variation_bank_sidebar_li_id" class=""><a id="variation_bank_sidebar_a_id" class="collapsible-body" href="{{ route('reports.variation',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Variation By Bank') }}</span></a></li>
              <li id="subscription_bank_sidebar_li_id" class=""><a id="subscription_bank_sidebar_a_id" class="collapsible-body" href="{{ route('reports.subscription',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Subscription By Bank') }}</span></a></li>
               <li id="member_due_sidebar_li_id" class=""><a id="member_due_sidebar_a_id" class="collapsible-body" href="{{ route('reports.due',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Due Report') }}</span></a></li>
                <li id="member_statement_sidebar_li_id" class=""><a id="member_statement_sidebar_a_id" class="collapsible-body" href="{{ route('reports.statement',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Member Statement') }}</span></a></li>
                @endif
                @if($user_role=='union')
                 <li id="advance_payment_sidebar_li_id" class=""><a id="advance_payment_sidebar_a_id" class="collapsible-body" href="{{ route('reports.advance',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('Advance Payment') }}</span></a></li>
                 <li id="tdf_sidebar_li_id" class=""><a id="tdf_sidebar_a_id" class="collapsible-body" href="{{ route('reports.tdf',app()->getLocale()) }}" data-i18n=""><i class="material-icons">radio_button_unchecked</i><span>{{ __('TDF Report') }}</span></a></li>
                @endif
            </ul>
          </div>
        </li>
		@endif
      </ul>
      <div class="navigation-background"></div><a class="sidenav-trigger btn-sidenav-toggle btn-floating btn-medium waves-effect waves-light hide-on-large-only" href="#" data-target="slide-out"><i class="material-icons">menu</i></a>
    </aside>
    <!-- END: SideNav-->