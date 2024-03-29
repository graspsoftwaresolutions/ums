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
	Route::get('cityclear','MasterController@EmptycityList')->name('master.citytemp');
	Route::get('stateclear','MasterController@EmptystateList')->name('master.statetemp');
	Route::delete('state-deleteone/{id}','MasterController@statedestroyEmpty')->name('master.statedestroy');

	Route::post('ajax_city_list','AjaxController@ajax_city_list');
	Route::post('city_save','MasterController@citySave')->name('master.savecity');
	Route::post('city_nameexists','AjaxController@checkCityNameExists');
	Route::get('city_detail','CommonController@cityDetail');
	Route::delete('city-delete/{id}','MasterController@citydestroy')->name('master.citydestroy');
	Route::delete('city-deleteone/{id}','MasterController@citydestroyone')->name('master.citydestroyone');

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
	Route::post('member_newicexists','MemberController@checkMemberNewicExists');

	Route::get('membership-verify/{parameter}','MembershipController@VerifyMember')->name('approve.editmembership');

	Route::get('verifymembership','MembershipController@VerifyList')->name('verify.membership');
	Route::get('dojmembership','MembershipController@DojList')->name('verify.doj');

	Route::post('ajax_members_list/{parameter}','MembershipController@AjaxmembersList')->name('master.ajaxmemberslist');
	Route::post('ajax_verifymembers_list/{parameter}','MembershipController@AjaxVerifymembersList')->name('verify.ajax_verifymembers_list');
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
	Route::get('salary-scanning','SubscriptionAjaxController@scanSalary')->name('salary.scan');
	Route::get('sub-company-members/{parameter}','SubscriptionController@companyMembers')->name('subscription.members');
	Route::get('pending-members-details/{parameter}','SubscriptionController@pendingMembers')->name('subscription.pendingmembers');
	Route::get('subscription-download','SubscriptionController@downloadSubscription')->name('subscription.download');
	
	Route::post('ajax_pending_member_list','SubscriptionAjaxController@ajax_pending_member_list');
	Route::post('ajax_subcompany_list','SubscriptionAjaxController@ajax_sub_company_list');
	Route::get('get-datewise-status','SubscriptionAjaxController@getDatewiseMember')->name('subscription.datewisemember');
	
	Route::get('subscription.sub_payment','SubscriptionController@subPayment')->name('subscription.sub_payment');
	Route::get('subscription_paymenthistory','SubscriptionController@subPaymentHistory')->name('subscription.sub_paymenthistory');
	Route::get('subs_member_statement','SubscriptionController@subMemberStatement')->name('member.statement');
	Route::get('member_transfer','MembershipController@memberTransfer')->name('master.transfer');
	
	Route::post('ajax_arrear_list','SubscriptionAjaxController@ajax_arrear_list');
	
	//Arrear Entry 
	Route::get('sub-arrearentry','SubscriptionController@arrearentryIndex')->name('subscription.arrearentry');
	Route::get('sub-addarrearentry','SubscriptionController@arrearentryAdd')->name('subscription.addarrearentry');
	
	Route::get('editarreatentry/{parameter}','SubscriptionController@arrearentryEdit')->name('subscription.editarreatentry');
	
	Route::delete('arrearentrydelete/{id}','SubscriptionController@arrearentrydestroy')->name('subscription.arrearentrydelete');

	Route::post('subscription-saveArrear','SubscriptionController@arrearentrySave')->name('subscription.saveArrear');
	
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
	Route::post('ajax_irc_waiters_list','IrcController@ajax_irc_waiters_list')->name('irc.ajaxwaiterslist');
	Route::get('irc_list','IrcController@listIrc')->name('irc.irc_list');
	Route::post('ajax_irc_list','IrcController@ajax_irc_list')->name('ajax.irc_list');
	Route::get('edit-irc/{parameter}','IrcController@editIrc')->name('edit.irc');
	Route::post('add-irc','IrcController@saveIrc')->name('irc.saveIrc'); 
	Route::post('update-irc','IrcController@saveIrc')->name('irc.updateIrc');
	
	Route::get('member-history/{parameter}','SubscriptionController@memberHistory')->name('member.history');
	Route::post('ajax_member_history','SubscriptionAjaxController@ajax_member_history');
	Route::get('get-relatives-info','MembershipController@getRelativename')->name('member.relatives');
	Route::get('/resign-pdf/{parameter}', 'MembershipController@resignPDF')->name('resign.status');
	Route::get('/generate-resign-pdf/{parameter}', 'MembershipController@genresignPDF')->name('resign.pdf');
	Route::get('subscription-status','SubscriptionController@statusCountView')->name('subscription.status');
	Route::get('subscription_member_info','SubscriptionAjaxController@SubscriptionMemberDetails')->name('subscription.match');
	Route::post('ajax_save_approval','SubscriptionController@saveApproval')->name('approval.save');

	//Reports
	
	//New Member Report
	Route::get('newmember_report','ReportsController@newMemberReport')->name('reports.newmembers');
	Route::get('newmembers_report','ReportsController@newMembersReport')->name('reports.membersnew');
	Route::get('get-new-members-report','ReportsController@membersNewReportMore')->name('reports.newmoremembers');
	Route::get('get-new-moremembers-report','ReportsController@membersNewReportloadMore');

	//Takaful Report
	Route::get('takaful_report','ReportsController@takafulnewReport')->name('reports.takaful');
	Route::get('newtakaful_report','ReportsController@newTakaulReport')->name('reports.takafulnew');
	Route::get('get-takaful-more-report','ReportsController@takafulReportMore')->name('reports.takfulmore');
	Route::get('get-takaful-moremembers-report','ReportsController@takafulReportloadMore');

	//Variation By bank 
	Route::get('variation_report','ReportsController@VariationReport')->name('reports.variation');
	Route::get('newvartation_report','ReportsController@newVariationReport')->name('reports.variationnew');
	Route::get('get-variation-report','ReportsController@VariationFiltereport')->name('reports.variationfilter');
	Route::get('get-newvariation_report-more-report','ReportsController@variationBankReportloadMore');

	//subscription Report
	Route::get('subscription_report','ReportsController@SubscriptionReport')->name('reports.subscription');
	Route::get('newsubscription_report','ReportsController@newSubscriptionReport')->name('reports.subscriptionnew');
	Route::get('get-subscription-report','ReportsController@SubscriptionFiltereport')->name('reports.subscriptionfilter');
	Route::get('get-subscription-more-report','ReportsController@subscriptionReportloadMore');
	
	//Finacial Half Share Report
	Route::get('halfshare_report','ReportsController@halfshareReport')->name('reports.halfshare'); 
	Route::get('newhalfshare_report','ReportsController@newhalfshareReport')->name('reports.halfsharenew');
	Route::get('get-new-morehalfshare-report','ReportsController@halfshareFiltereportLoadmore')->name('reports.halfsharefilter');
	Route::get('get-new-halfshare-report','ReportsController@halfshareFiltereport')->name('reports.halfsharefilters');

	Route::get('halfshare_unionreport','ReportsController@halfshareUnionReport')->name('union.halfshare'); 
	Route::get('newhalfshare_unionreport','ReportsController@newhalfshareUnionReport')->name('union.halfsharenew');

	Route::get('get-new-halfsharefilter-report','ReportsController@newHalfshareReportfilter')->name('reports.newhalfsharefilter');
	Route::post('filter_halfshare_report','ReportsController@filterHalfShareReport')->name('reports.filterhalfshare');

	//Statstics iframe report 
	Route::get('statistics_report','ReportsController@activeStatisticsReport')->name('reports.statistics');
	Route::get('newstatistic_report','ReportsController@newStatisticReport')->name('reports.statsticsnew');
	Route::get('get-statstics-more-report','ReportsController@statisticsReportMore')->name('reports.statsticmore');
	Route::get('get-statstic-more-report','ReportsController@statisticReportloadMore');
	Route::get('get-statstics-union-report','ReportsController@statisticUnionReportFilter');

	Route::get('yealy_statistics_report','ReportsController@YearlyStatisticsReport')->name('yearly.statistics');
	Route::get('get-yearly-statistics-report','ReportsController@YearlyStatisticsFilter')->name('statistics.yearly');

	Route::get('membership_statistics_report','ReportsController@MembershipStatisticsReport')->name('members.statistics');
	Route::get('get-membership-statistics-report','ReportsController@MembershipStatisticsFilter')->name('statistics.membership');

	Route::get('monthly_statistics_report','ReportsController@MonthlyStatisticsReport')->name('monthly.statistics');
	Route::get('get-monthly-statstics-report','ReportsController@MonthlyStatisticsFilter')->name('statistics.membership');

	//Members Iframe Report 
	//reports.membersnewactive
	Route::get('member_report/{parameter}','ReportsController@membersReport')->name('reports.members');
	Route::get('membersnewactive/{parameter}','ReportsController@membersNewActiveReport')->name('reports.membersnewactive');

	Route::get('get-pgm-members-report','ReportsController@membersPgmReport')->name('reports.pgmmembers');

	Route::get('get-monthly-summary-report','ReportsController@monthlySummaryReport')->name('summary.monthly');
	Route::get('get-yearly-summary-report','ReportsController@yearlySummaryReport')->name('summary.yearly');
	
	Route::get('get-membersstatus-more-report','ReportsController@membersReportMore')->name('reports.moremembers');
	Route::get('get-new-moremembers-report','ReportsController@membersReportLoadMore')->name('reports.moremembers');
	
	Route::get('get-subscription-more','SubscriptionAjaxController@getMoreSubscription')->name('subscription.more');

	Route::get('get-members-history','SubscriptionAjaxController@membershistoryMore')->name('subscription.history');
	
	Route::get('resignmember_report','ReportsController@resignMemberReport')->name('reports.resignmembers');
	Route::get('pgmmember_report','ReportsController@PgmMemberReport')->name('reports.pgm');
	Route::get('pgmallmember_report','ReportsController@PgmAllMemberReport')->name('reports.allpgm');
	Route::get('summary_report','ReportsController@SummaryReport')->name('reports.summary');

	Route::get('yearly_member_report','ReportsController@YearlyMemberReport')->name('reports.yearly');

	Route::get('reports.resignmembernew','ReportsController@resignNewMemberReport')->name('reports.resignmembernew');
	Route::get('get-new-resignedmembers-report','ReportsController@membersResignReportMore')->name('get-new-resignedmembers-report');
	
	Route::get('get-resign-members-report','ReportsController@membersResignReportLoadMore')->name('get-new-resignedmembers-loadmoreport');
	
	Route::get('subscription_variation','SubscriptionController@variation')->name('subscription.month');
	Route::get('subscription-variation','SubscriptionController@variationAll')->name('subscription.all');
	Route::post('subscription_variation','SubscriptionController@variationFilter')->name('subscription.filter');
	Route::get('subscription-variation-members','SubscriptionController@variationMembersAll')->name('subscription.membersall');
	
	Route::get('newtakaful_premium_report','ReportsController@PremiumTakaulReport')->name('takafulnew.premium');
	Route::get('newtakaful_summary_report','ReportsController@SummaryTakaulReport')->name('takafulnew.summary');
	Route::get('get-takaful-premium-report','ReportsController@PremiumTakaulmore')->name('premium.more');
	Route::get('get-takaful-summary-report','ReportsController@SummaryTakaulmore')->name('summary.more');
	Route::get('statistic_union_report','ReportsController@UnionStatisticReport')->name('statsticsnew.union');
	Route::get('process-memberstatus','SubscriptionAjaxController@UpdateMemberStatus');
	Route::get('subscription_delete','SubscriptionController@DeleteSubscription');
	Route::get('subscription_delete_bank','SubscriptionController@DeleteSubscriptionBank');
	Route::post('update_subscription','SubscriptionController@saveSubscription')->name('subscription.update');
	Route::get('get-new-members-print','MembershipController@MembersNewPrint')->name('reports.newmembersprint');
	Route::get('get-new-members-back-print','MembershipController@MembersNewBackPrint')->name('reports.membersbackprint');

	Route::get('due_report','ReportsController@DueReport')->name('reports.due');
	Route::get('iframe_due_report','ReportsController@IframeDueReport')->name('reports.due_iframe');
	Route::get('get-due-report','ReportsController@IframeDueFiltereport')->name('reports.duefilter');

	Route::get('advice_report','ReportsController@AdviceReport')->name('reports.advice');
	Route::get('union_new_members','ReportsController@newMembersUnionReport')->name('union.newmembers');
	Route::get('iframe_advice_new_report','ReportsController@IframeAdviceReport')->name('advice.newmember');
	Route::get('iframe_advice_resign_report','ReportsController@IframeAdviceResignReport')->name('advice.resignmember');
	Route::get('get-new-unionmembers-report','ReportsController@newMembersUnionFilterReport')->name('reports.unionmembers');
	Route::get('get-filter-resignedadvice-report','ReportsController@ResignMembersFilterReport')->name('advice.resign');
	Route::get('get-filter-newadvice-report','ReportsController@AdvanceNewMembersFilterReport')->name('advice.new');

	Route::get('branch_status_report','ReportsController@BranchStatusReport')->name('reports.status');
	Route::get('iframe_branch_status','ReportsController@IframeBranchStatusReport')->name('reports.branchstatus');
	Route::get('get-branchstatus-filter-report','ReportsController@IframeBranchStatusFilterReport')->name('more.branchstatus');
	Route::get('union-membersnewactive/{parameter}','ReportsController@StatusMembersUnionReport')->name('union.membersnewactive');
	Route::get('get-membersstatus-union-report','ReportsController@membersReportUnionMore')->name('union.moremembers');
	Route::get('save-subscription-approve','SubscriptionAjaxController@ApproveSubscriptionAll')->name('all.subscriptionsave');
	Route::get('membership-view/{parameter}','MembershipController@viewMember')->name('master.viewmembership');
	Route::get('monthend-update','MonthEndController@index')->name('monthend');
	Route::get('get-monthend-record','MonthEndController@getMonthendInfo')->name('monthend.info');
	
	Route::get('monthend_save','MonthEndController@SaveMonthEnd')->name('monthend.save');
	Route::get('insertmonth','MonthEndController@insertMonthendView')->name('subscription.monthend');
	Route::post('insertmonth-record','MonthEndController@insertMonthend')->name('monthend.insert');

	Route::get('get-union-resignedmembers-report','ReportsController@unionResignReportMore')->name('get-union-resignedmembers-report');
	Route::get('union-resignmembernew','ReportsController@resignUnionMemberReport')->name('union.resignmembernew');

	Route::get('export-pdf-advice-new','ReportsController@exportPdfAdvancenew')->name('advance.pdf');
	Route::get('export-pdf-members-new','ReportsController@exportPdfMembersnew')->name('members.pdf');
	Route::get('export-pdf-members-unionnew','ReportsController@exportPdfMembersUnionnew')->name('members.pdf');

	Route::get('export-pdf-members','ReportsController@exportPdfMembers')->name('members.pdf');
	Route::get('export-pdf-resignmembers','ReportsController@exportPdfResignMembers')->name('resignmember.pdf');
	Route::get('export-pdf-resignmembers-union','ReportsController@exportPdfUnionResignMembers')->name('resignmember.unionpdf');

	Route::get('export-pdf-members-union','ReportsController@exportPdfMembersUnion')->name('membersunion.pdf');
	Route::get('export-pdf-advice-resign','ReportsController@exportPdfAdviceResign')->name('advanceresign.pdf');
	Route::get('export-pdf-branch-status','ReportsController@exportPdfBranchStatus')->name('branchstatus.pdf');
	Route::get('export-pdf-takaful','ReportsController@exportPdfTakaful')->name('takaful.pdf');
	Route::get('export-pdf-takaful-premium','ReportsController@exportPdfTakafulPremium')->name('takaful.pdf');
	Route::get('export-pdf-takaful-summary','ReportsController@exportPdfTakafulSummary')->name('takafulsum.pdf');
	Route::get('export-pdf-statistics','ReportsController@exportPdfStatistics')->name('statistics.pdf');
	Route::get('export-pdf-statistics-union','ReportsController@exportPdfStatisticsUnion')->name('statisticsunion.pdf');
	Route::get('export-pdf-half-share','ReportsController@exportPdfHalfShare')->name('halfshare.pdf');
	Route::get('export-pdf-variation-bank','ReportsController@exportPdfVariationBank')->name('variationbank.pdf');
	Route::get('export-pdf-subscription-bank','ReportsController@exportPdfSubscriptionBank')->name('variationbank.pdf');
	Route::get('export-pdf-due','ReportsController@exportPdfDue')->name('due.pdf');

	Route::get('export-pdf-monthly-summary','ReportsController@exportPdfMonthlySummary')->name('monthsummary.pdf');
	Route::get('export-pdf-yearly-summary','ReportsController@exportPdfYearlySummary')->name('yearsummary.pdf');
	Route::get('export-pdf-yearly-statistics','ReportsController@exportPdfYearlyStatistics')->name('yearstatistics.pdf');

	Route::get('sub-arrearupdate/{parameter}','SubscriptionController@UpdateArrear')->name('subs.arrearview');
	Route::post('update_subscription_rows','SubscriptionController@saveArrearRows')->name('subscription.udatearrearrows');
	Route::get('editarrearrecords/{parameter}','SubscriptionController@arrearRecordEdit')->name('subscription.editarrearrecords');

	Route::get('history-list','MonthEndController@ListMonthend')->name('history.list');
	Route::get('history-update','MonthEndController@ListMonthend')->name('history.update');

	Route::get('before-dojlist','MonthEndController@ListBeforeDojMonthend')->name('history.beforedoj');
	Route::get('beforedoj-history/{parameter}','MonthEndController@ViewBeforeDojHistory')->name('beforedoj.viewlists');
	Route::post('before-dojlist','MonthEndController@ListBeforeDojMonthendFilter')->name('monthend.beforedoj');

	Route::delete('delete_beforedoj_history','MonthEndController@DeleteBeforeDOJ')->name('beforedoj.delete');

	Route::post('ajax_history_list','MonthEndController@ajax_history_list');

	Route::get('monthend-history/{parameter}','MonthEndController@ViewMemberHistory')->name('monthend.viewlists');
	Route::post('update_unpaid_rows/{parameter}','SubscriptionController@saveMonthendRows')->name('subscription.updatehistoryrows');
	Route::post('history-list','MonthEndController@ListMonthendFilter')->name('monthend.list');

	Route::get('statement-report','ReportsController@StatementReport')->name('reports.statement');
	Route::get('member_statement','ReportsController@MemberStatementReport')->name('statement.member');

	Route::get('refresh-csrf', function(){
	    return csrf_token();
	});

	Route::get('clean-membership','MonthEndController@ListMembers')->name('cleaning.membership');
	Route::post('clean-membership','MonthEndController@ListMemberFilter')->name('membership.cleaning');
	Route::post('ajax_member_list','MonthEndController@ajax_member_list')->name('ajax.listmembership');
	Route::get('monthend-historyall/{parameter}','MonthEndController@ViewAllMemberHistory')->name('monthend.viewlistsall');
	Route::get('due-list','MonthEndController@ListMonthendDue')->name('due.list');
	Route::post('due-list','MonthEndController@ListMonthendDueFilter')->name('monthend.duelist');

	Route::get('sub-addbulkarrearentry','SubscriptionController@arrearBulkEntryAdd')->name('subs.addbulkarrearentry');
	Route::post('subscription-saveBulkArrear','SubscriptionController@arrearBulkEntrySave')->name('subscription.saveBulkArrear');

	Route::get('sub-bulkarrearupdate/{parameter}','SubscriptionController@ViewBulkArrear')->name('subs.arrearbulkview');

	Route::post('update_subs_bulk_rows','SubscriptionController@saveArrearBulkRows')->name('subscription.upbulkarrearrows');
	Route::post('save_subs_bulk_rows','SubscriptionController@UpdateArrearBulkRows')->name('save.upbulkarrearrows');

	Route::get('editbulkarrearrecords/{parameter}','SubscriptionController@arrearRecordBulkEdit')->name('subscription.editbulkarrearrecords');
	Route::get('editadvance/{parameter}','SubscriptionController@advanceEdit')->name('subscription.editadvance');

	Route::get('export-excel-takaful','ReportsController@exportExcelTakaful')->name('takaful.excel');
	Route::get('export-excel-members','ReportsController@exportExcelMembers')->name('members.excel');
	Route::get('export-excel-unionmembers','ReportsController@exportExcelUnionMembers')->name('unionmembers.excel');

	Route::get('export-excel-resignmembers','ReportsController@exportExcelResignMembers')->name('resignmembers.excel');
	Route::get('export-excel-resignmembers-union','ReportsController@exportExcelUnionResignMembers')->name('resignmembersunion.excel');

	Route::get('export-excel-pgmmembers','ReportsController@exportExcelPGMMembers')->name('pgmmembers.excel');
	Route::get('export-pdf-tgmmembers','ReportsController@exportPdfTGMMembers')->name('tgmmembers.pdf');

	Route::get('export-excel-membership-statistics','ReportsController@exportExcelStatisticsMembers')->name('statistics.excel');

	Route::get('export-pdf-membership-statistics','ReportsController@exportPdfStatisticsMembers')->name('memberstatistics.pdf');

	Route::get('export-excel-half-share','ReportsController@exportExcelHalfshare')->name('halfshare.excel');

	Route::get('variancemembers-list','SubscriptionController@VarianceMembersList')->name('variation.list');

	Route::get('new-member-history/{parameter}','MonthEndController@memberallHistory')->name('monthend.addhistory');
	Route::post('add_new_rows/{parameter}','MonthEndController@saveMonthendRows')->name('monthend.updatehistoryrows');

	Route::get('transfers_report','ReportsController@MemberTransferReport')->name('reports.transfers');
	Route::get('iframe_transfers_report','ReportsController@TransferReport')->name('iframe.transfers');
	Route::get('get-transfers-filter-report','ReportsController@TransferFilterReport')->name('more.transfers');

	Route::get('export-pdf-member-transfers','ReportsController@exportPdfTransfers')->name('transfers.pdf');
	Route::get('invalidsubs','SubscriptionController@InvalidSubscription')->name('subscription.invalidsubs');
	Route::post('invalidsubs','SubscriptionController@ViewStruckoff')->name('subscription.view_struckoff');
	Route::get('followup-list','MonthEndController@ListMonthendFollowUp')->name('followup.list');
	Route::post('followup-list','MonthEndController@ListMonthendFollowUpFilter')->name('monthendfollowup.list');

	Route::get('sub-company-summary/{parameter}','SubscriptionController@companyMembersSummary')->name('subscription.summary');
	Route::get('summary-status','SubscriptionController@SummarystatusCountView')->name('summary.status');
	Route::post('ajax_save_summary','SubscriptionController@saveMismatched')->name('mismatched.save');

	Route::get('subscription_variance','SubscriptionController@varianceList')->name('subscription.variance');
	Route::post('subscription_variance','SubscriptionController@varianceFilter')->name('variance.filter');

	Route::get('list_sub_company','SubscriptionController@sub_company_list')->name('company.sub_company');
	Route::post('ajax_subscriptioncompany_list','SubscriptionAjaxController@ajax_subscription_company_list');
	Route::get('unmatched_member_info','SubscriptionAjaxController@unmatchedMemberDetails')->name('unmatched.members');
	Route::get('unpaid_member_info','SubscriptionAjaxController@unpaidMemberDetails')->name('unpaid.members');
	Route::get('varation_member_info','SubscriptionAjaxController@VariationMemberDetails')->name('variation.members');
	Route::post('ajax_save_variation','SubscriptionController@saveVariance')->name('variance.save');

	Route::get('irc_users_detail','IrcController@userDetail');

	Route::get('salary_upload','MembershipController@SalaryUpload')->name('salary.upload');
	Route::get('salary_list','MembershipController@Salarylists')->name('salary.list');
	Route::get('get_bankmembers','MembershipController@getBankMembersList')->name('bank.members');
	Route::post('upload_salary','MembershipController@saveSalary')->name('salary.save');

	Route::get('subssalary_upload','SubscriptionController@SubsSalaryUpload')->name('upload.salary');
	Route::get('latestsalary_upload','SubscriptionController@LatestSubsSalaryUpload')->name('latestupload.salary');

	Route::get('latestsalary_process','SubscriptionAjaxController@LatestSubsSalaryProcess')->name('latestprocess.salary');
	Route::get('salary-download','SubscriptionController@downloadSalaryFile')->name('salary.download');

	Route::get('get_memberssalaries','MembershipController@getBankMembersSalaries')->name('bank.salaries');
	Route::get('get-salary-list-inc','MembershipController@getMembersIncrements')->name('salary.increments');

	Route::get('subscription_discrepancy','SubscriptionController@Discripancy')->name('subscription.discrepancy');
	Route::post('subscription_discrepancy','SubscriptionController@DiscripancyFilter')->name('discrepancy.filter');
	Route::post('update_discrepancy','SubscriptionController@DiscripancyUpdate')->name('discrepancy.update');

	Route::get('discrepancy-print','SubscriptionController@DiscrepancyPrint')->name('discrepancy.print');
	Route::get('discrepancy-excel','SubscriptionController@DiscrepancyExcel')->name('discrepancy.excel');

	Route::get('clean-state','MembershipController@ListStateMembers')->name('cleaning.state');
	Route::post('clean-state','MembershipController@UpdateStateCity')->name('state.cleaning');
	Route::get('get_statemembers','MembershipController@getStateMembersList')->name('state.members');

	Route::get('clean-statebulk','MembershipController@ListStateCities')->name('cleaning.statebulk');
	Route::get('get_statecities','MembershipController@getStateCitiesList')->name('state.cities');
	Route::post('clean-statebulk','MembershipController@UpdateStateCityBulk')->name('statebulk.cleaning');

	Route::get('clean-membershiplist','MembershipController@ListMembers')->name('clean.membership');
	Route::post('ajax_cleanmember_list','MonthEndController@ajax_cleanmember_list')->name('ajax.listcleanmembership');

	Route::get('member-levy/{parameter}','MembershipController@memberlevy')->name('member.viewlevy');
	Route::post('levyupdate','MembershipController@UpdateLevy')->name('member.levyupdate');
	//Route::post('clean-membership','MonthEndController@ListMemberFilter')->name('membership.clean');

	Route::get('designation_report','ReportsController@DesignationReport')->name('reports.designation');
	Route::get('empty_report','ReportsController@EmptyReport')->name('reports.empty');
	Route::get('get-designation-filter-report','ReportsController@IframeDesignationReport')->name('reports.memberdesignation');

	Route::get('export-pdf-designation','ReportsController@exportPdfDesignation')->name('designation.pdf');

	Route::get('clean-salary','MembershipController@ListSalaryMembers')->name('cleaning.salary');
	Route::post('clean-salary','MembershipController@UpdateMembersSalary')->name('salary.cleaning');

	Route::get('get_salarymembers','MembershipController@getSalaryMembersList')->name('salary.members');

	Route::post('update_salary','MembershipController@updateSalary')->name('salary.update');
	Route::post('subssalary_update','SubscriptionController@SubsSalaryUpdate')->name('subssalary.update');
	Route::post('latestsalary_update','SubscriptionAjaxController@LatestSubsSalaryUpdate')->name('latestsubssalary.update');
	//Route::get('export-excel-designation','ReportsController@exportPdfDesignation')->name('designation.pdf');

	Route::get('advance-report','ReportsController@AdvanceReport')->name('reports.advance');
	Route::get('iframe_advance_report','ReportsController@IframeAdvanceReport')->name('reports.iframeadvance');
	Route::get('get-advance-filter-report','ReportsController@IframeAdvanceFilterReport')->name('more.advance');

	Route::get('subs-advance','SubscriptionController@AddAdvance')->name('subscription.advance');
	Route::get('get_months_number','SubscriptionController@GetMonthsDifference')->name('months.difference');

	Route::post('subscription-saveAdvance','SubscriptionController@AdvanceEntrySave')->name('subscription.saveAdvance');
	Route::post('subscription-updateAdvance','SubscriptionController@AdvanceUpdate')->name('subscription.updateAdvance');

	Route::get('sub-advanceentry','SubscriptionController@advanceentryList')->name('subscription.advanceentry');
	Route::post('ajax_advance_list','SubscriptionAjaxController@ajax_advance_list');

	Route::get('advance_payment_info','SubscriptionAjaxController@AdvanceDetails')->name('advance.info');
	Route::post('ajax_pending_members_list/{parameter}','MembershipController@AjaxPendingmembersList')->name('pending.ajaxmemberslist');

	Route::get('membership-union-edit/{parameter}','MembershipController@editMember')->name('union.editmembership');
	Route::post('u_membership_save','MemberController@MemberSave')->name('member.saveumembership');
	Route::post('m_membership_save','MemberController@MemberUpdate')->name('member.updatemembership');
	Route::post('v_membership_save','MemberController@VerifyMemberUpdate')->name('verify.updatemembership');

	Route::get('delete_member_file','MembershipController@DeleteFile')->name('file.delete');
	Route::post('ajax_save_advance','SubscriptionController@saveAdvanceApprove')->name('advance_approve.save');
	Route::post('ajax_save_doj','MembershipController@saveDojApprove')->name('doj_approve.save');

	Route::get('subscription-variation-pdf','SubscriptionController@variationAllPDF')->name('subscription.pdf');

	Route::get('subscription_add','SubscriptionController@SubsAdditional')->name('subscription.sub_additional');

	Route::post('subscribe_entry','SubscriptionController@subscribeEntry')->name('subscription.entry');

	Route::get('add_resignation','MembershipController@AddResignation')->name('resignation.add');

	Route::post('resignation_save','MembershipController@SendIrc')->name('member.savemembership');

	Route::get('irc_waiters','IrcController@IrcWaiters')->name('irc.irc_waiters');

	Route::get('get-dojchanged-list','MembershipController@Dojchanged')->name('member.dojchanged');

	Route::get('staff','MasterController@staffAccountList')->name('staff.account'); 
	Route::post('staff_save','MasterController@Staffsave')->name('master.savestaff'); 
	Route::get('resignation_list','MembershipController@resignationMembers')->name('pending.resignation');
	Route::post('ajax_resignation_list','MembershipController@AjaxResignationList')->name('ajaxpending.resignation');

	Route::get('tdf_upload','MembershipController@TdfUpload')->name('upload.tdf');
	Route::post('tdf_update','MembershipController@LatestTdfUpdate')->name('tdf.update');
	Route::get('latesttdf_process','MembershipController@LatestTDFProcess')->name('latestprocess.tdf');

	Route::get('tdf-scanning','MembershipController@scanTDF')->name('tdf.scan');

	Route::get('tdf-report','ReportsController@TDFReport')->name('reports.tdf');

	Route::get('newmemberunion_report','ReportsController@newMemberUnionReport')->name('reports.unionnewmembers');
	
	/* Eco Park */
	Route::get('ecopark/fileupload','EcoParkController@FileUpload')->name('ecopark.fileupload');
	Route::post('ecopark_update','EcoParkController@EcoParkUpdate')->name('ecopark.update');
	Route::get('latestecopark_process','EcoParkController@LatestEcoParkProcess')->name('latestprocess.ecopark');
	Route::get('ecopark-scanning','EcoParkController@scanEcoPark')->name('ecopark.scan');
	Route::get('ecopark/list','EcoParkController@EcoParkList')->name('ecopark.list');
	Route::get('ecopark/summary','EcoParkController@EcoParkSummary')->name('ecopark.summary');
	Route::get('ecopark/members','EcoParkController@EcoParkMembers')->name('ecopark.members');
	Route::post('ecopark/summary','EcoParkController@EcoParkSummary')->name('ecopark.filter');

	Route::get('ecopark-status','EcoParkController@statusCountView')->name('ecopark.status');
	Route::post('ecopark-status','EcoParkController@statusCountView');
	Route::post('ajax_ecoparkmember_list','EcoParkController@ajax_ecoparkmember_list');

	Route::get('privilegecard/list','EcoParkController@PrivilegeCardUsersList')->name('privilegecard.users');
	Route::post('ajax_privilege_card_users_list','EcoParkController@ajax_privilege_card_users_list');

	Route::get('ecopark/report','EcoParkController@EcoParkReport')->name('ecopark.report');
	Route::get('iframe_eco_park','EcoParkController@IframeEcoParkReport')->name('reports.ecopark');

	Route::get('privilegecard_status','MasterController@PCstatusList')->name('privilegecard.status');
	Route::post('pcstatus_nameexists','AjaxController@checkPCStatusNameExists'); 
	Route::post('PCsaveStatus','MasterController@PCStatusSave')->name('pc.saveStatus'); 
	Route::delete('pcstatus-delete/{id}','MasterController@PCstatusDestroy')->name('pc.statusdestroy');
	Route::get('pcstatus_details','CommonController@PCstatusDetail'); 
	Route::get('privilegecard-edit/{parameter}','EcoParkController@EditPrivilegeCard')->name('privilegecard.edit');

	Route::post('pc_user_save','EcoParkController@PCUserSave')->name('pc.updateuser');

	Route::get('get-privilegecard-print','EcoParkController@PrivilegeCardPrint')->name('reports.privilegecardprint');

	Route::get('due-negativelist','MonthEndController@ListNegativeDue')->name('due.negativelist');

	Route::get('newmembers_resignreport','ReportsController@newMembersResignReport')->name('resign.membersnew');

	Route::get('tdf/list','MembershipController@TdfList')->name('tdf.list');

	Route::get('tdf/summary','MembershipController@TDFSummary')->name('tdf.summary');
	Route::get('tdf/members','MembershipController@TDFMembers')->name('tdf.members');
	Route::post('tdf/summary','MembershipController@TDFSummary')->name('tdf.filter');

	Route::post('ajax_tdfmember_list','MembershipController@ajax_tdfmember_list');

	Route::get('tdf-status','MembershipController@TdfStatusMembers')->name('tdf.status');
	Route::get('tdf_member_info','MembershipController@TDFMemberDetails')->name('tdf.memberinfo');

	Route::post('ajax_save_tdf_member','MembershipController@saveTDFMemberApproval')->name('approval.tdfsave');
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
Route::get('get-company-list','CommonController@getCompanyList');
Route::get('get-companybranches-list','CommonController@getCompanyBranchesList');
Route::get('get-unionbankbranch-list','CommonController@getUnionBranchesList');
Route::get('get-cities-list','CommonController@getCitiesList');
Route::get('get-branch-list','CommonController@getBranchList');
Route::get('get-age','CommonController@getAge');
Route::get('get-serviceyear','MembershipController@ServiceYear');
Route::get('get-fee-options','CommonController@getFeesList');
Route::get('get-bf-amount','MembershipController@getBFAmount');

Route::get('check-member-date','MembershipController@CheckMemberDate');

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
Route::get('get-subscription-status-list','SubscriptionAjaxController@getAutomemberslist');
Route::get('get-ircauto-member-list','IrcController@getAutomemberslist');

Route::get('get-activemember-list','IrcController@getMembersList');
Route::get('/maintenance', function () {
    return view('errors.maintenance');
});

Route::get('/test-card', 'CustomerController@TestWizard');
Route::get('/test-backcard', 'CustomerController@Testbackcard');

Route::get('irc','IRCController@index');

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
});

Route::get('addpayments','MemberController@AddPaymentEntry');

Route::get('get-ircbranch-member-list','IrcController@getUnionAutomemberslist');

Route::get('register-one','MemberController@NewRegisterDesign');

Route::get('takaful-excel-export','ReportsController@exportTakafulExcel')->name('takaful.excelexport');

Route::get('get-member-irclist-values','IrcController@getMembersListValues');

Route::get('update_password','MemberController@UpdateMemberPassword');

Route::get('get-all-member-list','MembershipController@getAllmemberslist');
Route::get('get-branch-count','MasterController@getTotalBranchCount');

Route::get('/test-irc', 'IrcController@TestIRC');

// Display All User In Table
Route::get('user-list', 'HomeController@userList')->name('user-list');

// Export User Table Data In Excel With Set Header
Route::get('user-export/{parameter}', 'HomeController@exportUserData')->name('user-export');
Route::get('change-memberstatus','HomeController@UpdateMemberStatus');

