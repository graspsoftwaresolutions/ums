<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/* Authentication */
Route::get('/', function () {
    return redirect(app()->getLocale());
});
Route::group(['prefix' => '{locale}', 'where' => ['locale' => '[a-zA-Z]{2}'], 'middleware' => 'setlocale'], function() {
	Route::get('/', 'Auth\LoginController@custom_login');
	Auth::routes();


	// Masters
	//users Form
	Route::post('ajax_users_list','MasterController@ajax_users_list')->name('master.ajaxuserslist');
	Route::get('users','MasterController@users_list')->name('master.userslist');
	Route::post('user_save','MasterController@userSave')->name('master.saveuser');
	Route::delete('users/{id}','MasterController@user_destroy')->name('master.destroy');
	Route::get('users_detail','CommonController@userDetail');
	Route::post('users_emailexists','AjaxController@checkemailExists');

	//Country Master Details 
	Route::post('ajax_countries_list','AjaxController@ajax_countries_list')->name('master.ajaxcountrieslist');
	Route::get('country','MasterController@countryList')->name('master.country');
	Route::post('country_save','MasterController@countrySave')->name('master.savecountry');
	Route::get('country-edit','MasterController@countryEdit')->name('master.editcountry');
	Route::delete('country_delete/{id}','MasterController@countrydestroy')->name('master.countrydestroy');
	Route::post('country_nameexists','AjaxController@checkCountryNameExists');
	Route::get('country_detail','CommonController@countryDetail');

	//Relation Details
	Route::post('ajax_relation_list','AjaxController@ajax_relation_list')->name('master.ajaxrelationlist');
	Route::get('relation','MasterController@relationList')->name('master.relation'); 
	Route::post('relation_save','MasterController@Relationsave')->name('master.saverelation'); 
	Route::post('relation_nameexists','AjaxController@checkRelationNameExists');
	Route::get('relation_detail','CommonController@relationDetail');
	Route::delete('relation_delete/{id}','MasterController@relationDestroy')->name('master.relationdestroy');

	Route::get('/home', 'HomeController@index')->name('home');
	Route::post('/member-register', 'MemberController@register')->name('member.register');
	

	//Race Details 
	Route::get('race','MasterController@raceList')->name('master.race'); 
	Route::post('ajax_race_list','AjaxController@ajax_race_list')->name('master.ajaxracelist');
	Route::post('race_nameexists','AjaxController@checkRaceNameExists');
	Route::post('race_save','MasterController@raceSave')->name('master.saverace');
	Route::get('race_detail','CommonController@raceDetail');
	Route::delete('race_delete/{id}','MasterController@raceDestroy')->name('master.racedestroy');

	//Reason Details 
	Route::post('ajax_reason_list','AjaxController@ajax_reason_list')->name('master.ajaxreasonlist');
	Route::get('reason','MasterController@reasonList')->name('master.reason'); 
	Route::post('reason_save','MasterController@reasonSave')->name('master.reasonSave');
	Route::get('reason_detail','CommonController@reasonDetail'); 
	Route::delete('reason_delete/{id}','MasterController@reasonDestroy')->name('master.reasondestroy');
	Route::post('reason_nameexists','AjaxController@checkReasonNameExists'); 

	//Person Title Details 
	Route::post('ajax_persontitle_list','AjaxController@ajax_persontitle_list')->name('master.ajaxpersontitlelist');
	Route::get('persontitle','MasterController@titleList')->name('master.persontitle');
	Route::get('persontitle_detail','CommonController@personTitleDetail');
	Route::post('persontitle_nameexists','AjaxController@checkTitleNameExists'); 
	Route::post('persontitle_save','MasterController@personTileSave')->name('master.savepersontitle');
	Route::delete('persontitle_delete/{id}','MasterController@personTiteDestroy')->name('master.persontitledestroy');

	//Designation Details  
	Route::get('designation','MasterController@designationList')->name('master.designation');
	Route::post('ajax_designation_list','AjaxController@ajax_designation_list')->name('master.ajaxdesignationlist');
	Route::post('designation_nameexists','AjaxController@checkDesignationNameExists'); 
	Route::post('designation_save','MasterController@designationSave')->name('master.saveDesignation'); 
	Route::get('designation_detail','CommonController@designationDetail');
	Route::delete('designation-delete/{id}','MasterController@designationDestroy')->name('master.designationdestroy');

	//Status Details
	Route::get('status','MasterController@statusList')->name('master.status');
	Route::post('ajax_status_list','AjaxController@ajax_status_list')->name('master.ajaxstatuslist'); 
	Route::post('status_nameexists','AjaxController@checkStatusNameExists'); 
	Route::post('saveStatus','MasterController@statusSave')->name('master.saveStatus'); 
	Route::get('status_details','CommonController@statusDetail'); 
	Route::delete('status-delete/{id}','MasterController@statusDestroy')->name('master.statusdestroy');
	
	//Form Type
	//Route::resource('formtype','FormTypeController');
	Route::post('formtype_nameexists','AjaxController@checkFormTypeNameExists');  
	Route::get('formtype','MasterController@formTypeList')->name('master.formtype'); 
	Route::post('ajax_formtype_list','AjaxController@ajax_formtype_list')->name('master.ajaxformtypelist'); 
	Route::post('formtype_moduleexists','AjaxController@checkFormTypeModuleExists');
	Route::post('saveFormType','MasterController@formTypeSave')->name('master.saveFormType');  
	Route::get('formtype_detail','CommonController@formTypeDetail'); 
	Route::delete('formtype-delete/{id}','MasterController@formTypeDestroy')->name('master.formTypedestroy');

	//Company 
	Route::get('/company','MasterController@companyList')->name('master.company');
	Route::post('ajax_company_list','AjaxController@ajax_company_list')->name('master.ajaxcompanylist'); 
	Route::post('company_nameexists','AjaxController@checkCompanyNameExists'); 
	Route::post('saveCompany','MasterController@companySave')->name('master.saveCompany'); 
	Route::get('company_detail','CommonController@companyDetail'); 
	Route::get('save_company_detail','CommonController@saveCompanyDetail');
	Route::delete('companydestroy/{id}','MasterController@companyDestroy')->name('master.companydestroy');
	
	//State Details 
	Route::get('state','MasterController@stateList')->name('master.state');
	Route::post('ajax_state_list','AjaxController@ajax_state_list');
	Route::post('state_save','MasterController@stateSave')->name('master.savestate');
	Route::delete('state-delete/{id}','MasterController@statedestroy')->name('master.statedestroy');
	Route::post('state_nameexists','AjaxController@checkStateNameExists');
	Route::get('state_detail','CommonController@stateDetail');
	

	//City Details 
	Route::get('city','MasterController@cityList')->name('master.city');
	Route::post('ajax_city_list','AjaxController@ajax_city_list');
	Route::post('city_save','MasterController@citySave')->name('master.savecity');
	Route::post('city_nameexists','AjaxController@checkCityNameExists');
	Route::get('city_detail','CommonController@cityDetail');
	Route::delete('city-delete/{id}','MasterController@citydestroy')->name('master.citydestroy');

	//Union Branch
	Route::get('unionbranch','MasterController@unionBranchList')->name('master.unionbranch');
	Route::get('save-unionbranch','MasterController@addUnionBranch')->name('master.addunionbranch');
	Route::post('unionbranch_save','MasterController@UnionBranchsave')->name('master.saveunionbranch');
	Route::get('unionbranch-edit/{parameter}','MasterController@EditUnionBranch')->name('master.editunionbranch');
	Route::get('unionbranch-delete/{id}','MasterController@deleteUnionBranch')->name('master.deleteunionbranch');
	Route::post('ajaxUnionBranchList','AjaxController@AjaxunionBranchList')->name('master.union_BranchList');
	Route::post('branch_emailexists','AjaxController@checkBranchemailExists');
	
	//Fee Details
	Route::get('fee','MasterController@fees_list')->name('master.fee');
	Route::post('ajax_fees_list','AjaxController@ajax_fees_list')->name('master.ajaxfeeslist');
	Route::post('fee_save','MasterController@saveFee')->name('master.savefee');
	Route::post('fee_nameexists','AjaxController@checkFeeNameExists');
	Route::get('fee_detail','CommonController@feeDetail');
	Route::delete('fee-delete/{id}','MasterController@feedestroy')->name('master.feedestroy');
    
	// Role Details
	Route::get('roles','MasterController@roles_list')->name('master.roles');
	Route::post('ajax_roles_list','AjaxController@ajax_roles_list')->name('master.ajax_roles_list');
	Route::post('role_save','MasterController@saveRole')->name('master.saverole');
	Route::post('roles_nameexists','AjaxController@checkRoleNameExists');
	Route::get('role_detail','CommonController@roleDetail');
	Route::delete('roles-delete/{id}','MasterController@roledestroy')->name('master.rolesdestroy');
	
	//Branch Details
	Route::get('branch','MasterController@CompanyBranchList')->name('master.branch');
	Route::get('add-branch','MasterController@addCompanyBranch')->name('master.addbranch');
	Route::post('branch_save','MasterController@CompanyBranchsave')->name('master.savecompanybranch');
	Route::get('branch-edit/{parameter}','MasterController@EditCompanyBranch')->name('master.editbranch');
	Route::get('branch-delete/{id}','MasterController@deleteCompanyBranch')->name('master.deletebranch');
	Route::post('ajax-company-branchlist','AjaxController@AjaxCompanyBranchList')->name('master.company_branch_list');
	Route::post('companybranch_emailexists','AjaxController@checkCompanyBranchemailExists');

	//App Form
	Route::get('appform','MasterController@appFormList')->name('master.appform');
	Route::post('ajax_appform_list','AjaxController@ajaxAppFormList')->name('master.ajax_appform_list');
	Route::get('save-appform','MasterController@addAppForm')->name('master.addappform');
	Route::post('appform_save','MasterController@AppFormsave')->name('master.saveappform');
	Route::get('appform-edit/{parameter}','MasterController@EditAppForm')->name('master.editappform');
	Route::get('appform-delete/{id}','MasterController@deleteAppForm')->name('master.deleteappform');
	Route::post('appformexists','AjaxController@checkAppformExists');
	
	//Route::resource('appform', 'AppFormController');
	//Roles Form
	//Route::resource('roles','RolesController');
	//users Form
	//Route::resource('users','UsersController');
	Route::post('ajax_users_list','AjaxController@ajax_users_list')->name('master.ajaxuserslist');
	Route::get('users','MasterController@users_list')->name('master.userslist');
	Route::post('user_save','MasterController@userSave')->name('master.saveuser');
	Route::delete('users/{id}','MasterController@user_destroy')->name('master.destroy');

	
	
	Route::get('/changePassword','HomeController@showChangePasswordForm')->name('changepassword');
	Route::post('/changePassword','HomeController@ChangePassword')->name('changePassword');
	//Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');

	//membership
	Route::get('membership','MembershipController@index')->name('master.membership');
	Route::get('membership_register','MembershipController@addMember')->name('master.addmembership');
	Route::get('membership-edit/{parameter}','MembershipController@edit')->name('master.editmembership');
	Route::get('membership-delete/{id}','MembershipController@delete')->name('master.deletemembership');
	Route::get('membership_list','MembershipController@new_members')->name('master.membershipnew');
	Route::get('edit-membership-profile','MemberController@editMemberProfile')->name('member.membership.profile');
	Route::post('membership_save','MemberController@Save')->name('member.savemembership');
	Route::post('member_emailexists','MemberController@checkMemberemailExists');

	Route::post('ajax_members_list/{parameter}','MembershipController@AjaxmembersList')->name('master.ajaxmemberslist');
	//subscription
	Route::get('subscription','SubscriptionController@index')->name('subscription.sub_fileupload');
	Route::get('subscription-submember/{parameter}','SubscriptionController@submember')->name('subscription.submember');
	Route::post('subscription-memberfilter','SubscriptionController@memberfilter')->name('subscription.memberfilter');
	Route::post('subscribe_download','SubscriptionController@subscribeDownload')->name('subscription.sub_filedownload');
	Route::get('sub_company','SubscriptionController@sub_company')->name('subscription.sub_fileupload.sub_company');
	Route::get('check-subscription-exists','SubscriptionController@getSubscriptionStatus')->name('subscription.getstatus');
	
	Route::post('ajax_submember_list','SubscriptionAjaxController@ajax_submember_list');
	Route::get('scan-subscription/{parameter}','SubscriptionController@viewScanSubscriptions')->name('subscription.viewscan');
	Route::get('process-scanning','SubscriptionController@scanSubscriptions')->name('subscription.scan');
	Route::get('sub-company-members/{parameter}','SubscriptionController@companyMembers')->name('subscription.members');
	Route::get('pending-members-details/{parameter}','SubscriptionController@pendingMembers')->name('subscription.pendingmembers');
	Route::get('subscription-download','SubscriptionController@downloadSubscription')->name('subscription.download');
	
	Route::post('ajax_pending_member_list','SubscriptionAjaxController@ajax_pending_member_list');
	Route::post('ajax_subcompany_list','SubscriptionAjaxController@ajax_sub_company_list');
	Route::get('get-datewise-status','SubscriptionAjaxController@getDatewiseMember')->name('subscription.datewisemember');

	
	Route::get('subscription.sub_payment','SubscriptionController@subPayment')->name('subscription.sub_payment');
	Route::get('subscription.sub_paymenthistory','SubscriptionController@subPaymentHistory')->name('subscription.sub_paymenthistory');
	Route::get('member_transfer','MembershipController@memberTransfer')->name('master.transfer');
	
	Route::post('ajax_arrear_list','SubscriptionAjaxController@ajax_arrear_list');
	
	//Arrear Entry 
	Route::get('subscription.arrearentry','SubscriptionController@arrearentryIndex')->name('subscription.arrearentry');
	Route::get('subscription.addarrearentry','SubscriptionController@arrearentryAdd')->name('subscription.addarrearentry');
	
	Route::get('editarreatentry/{parameter}','SubscriptionController@arrearentryEdit')->name('subscription.editarreatentry');
	
	Route::delete('arrearentrydelete/{id}','SubscriptionController@arrearentrydestroy')->name('subscription.arrearentrydelete');

	Route::post('subscription.saveArrear','SubscriptionController@arrearentrySave')->name('subscription.saveArrear');
	
	Route::get('transfer_history','MembershipController@memberTransferHistory')->name('transfer.history');
	Route::post('change-branch','MembershipController@ChangeMemberBranch')->name('master.changebranch');
	Route::post('ajax_transfer_list','MembershipController@ajax_transfer_list');
	Route::get('edit_member_transfer','MembershipController@editmemberTransfer')->name('master.edittransfer');
	Route::get('delete_transfer/{parameter}','MembershipController@deletememberTransfer')->name('transfer.delete');
	Route::post('updatemember_transfer','MembershipController@updatememberTransfer')->name('transfer.updatebranch');

	//IRC
	Route::get('irc_irc','IrcController@ircIndex')->name('irc.irc');
	Route::get('add_irc_account','IrcController@AddIrcAccount')->name('irc.add');
	Route::get('list_irc_account','IrcController@ListIrcAccount')->name('irc.list');
	Route::post('save-user-account','IrcController@SaveUserAccount')->name('irc.add-irc-account');
	Route::post('ajax_irc_users_list','IrcController@ajax_irc_users_list')->name('irc.ajaxuserslist');
	Route::get('irc_list','IrcController@listIrc')->name('irc.irc_list');
	Route::post('ajax_irc_list','IrcController@ajax_irc_list')->name('ajax.irc_list');
	Route::get('edit-irc/{parameter}','IrcController@editIrc')->name('edit.irc');
	Route::post('add-irc','IrcController@saveIrc')->name('irc.saveIrc'); 
	Route::post('update-irc','IrcController@saveIrc')->name('irc.updateIrc');
	
	Route::get('member-history/{parameter}','SubscriptionController@memberHistory')->name('member.history');
	Route::post('ajax_member_history','SubscriptionAjaxController@ajax_member_history');
	Route::get('get-relatives-info','MembershipController@getRelativename')->name('member.relatives');
	Route::get('/resign-pdf/{parameter}', 'MembershipController@resignPDF');
	Route::get('/generate-resign-pdf/{parameter}', 'MembershipController@genresignPDF')->name('resign.pdf');

	//Reports
	
	Route::get('newmember_report','ReportsController@newMemberReport')->name('reports.newmembers');
	Route::get('member_report/{parameter}','ReportsController@membersReport')->name('reports.members');
	Route::get('get-members-report','ReportsController@membersReportMore')->name('reports.moremembers');
	Route::get('get-new-members-report','ReportsController@membersNewReportMore')->name('reports.moremembers');

	
});
/* Master */
	Route::get('get-branch-list-register','CommonController@getConditionalBranchList');
	//Route::get('membership','MembershipController@index');
	//Route::get('membership_register','MembershipController@addMember');
	//Route::get('membership_list','MembershipController@new_members');
	//Route::get('membership-edit/{parameter}','MembershipController@edit');
	//Route::get('membership-delete/{id}','MembershipController@delete');
	
	Route::get('count','DashboardController@unionBranchCount');

//common routes
Route::get('get-state-list','CommonController@getStateList');
Route::get('get-cities-list','CommonController@getCitiesList');
Route::get('get-branch-list','CommonController@getBranchList');
Route::get('get-age','CommonController@getAge');
Route::get('get-fee-options','CommonController@getFeesList');

// commom member functions
Route::post('add-nominee','MemberController@addNominee');
Route::get('get-oldmember-list','MemberController@getoldMemberList');

Route::get('get-member-list','MemberController@getMembersList');
Route::get('get-member-list-values','MemberController@getMembersListValues');
Route::get('get-ircmember-list','IrcController@getIrcMembersList'); 
Route::get('get-ircmember-list-values','IrcController@getIrcMembersListValues'); 

Route::get('get-nricmember-list','SubscriptionController@getNricMemberlist'); 
Route::get('get-nricmember-list-values','SubscriptionController@getMembersListValues');

Route::post('membership_update','MemberController@update');

Route::get('get-nominee-data','MembershipController@getNomineeData');
Route::post('update-nominee','MembershipController@updateNominee');
Route::get('delete-nominee-data','MembershipController@deleteNominee');
Route::get('delete-fee-data','MembershipController@deleteFee');
Route::get('get-auto-member-list','MembershipController@getAutomemberslist');
Route::get('get-branch-details','MembershipController@getBranchDetails');
Route::get('get-company-member-list','ReportsController@getAutomemberslist');

Route::get('/maintenance', function () {
    return view('errors.maintenance');
});

Route::get('/customer/print-pdf', 'CustomerController@printPDF');

Route::get('irc','IRCController@index');
