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
	Route::post('users_emailexists','CommonController@checkemailExists');

	//Country Master Details 
	Route::post('ajax_countries_list','MasterController@ajax_countries_list')->name('master.ajaxcountrieslist');
	Route::get('country','MasterController@countryList')->name('master.country');
	Route::post('country_save','MasterController@countrySave')->name('master.savecountry');
	Route::get('country-edit','MasterController@countryEdit')->name('master.editcountry');
	Route::delete('country_delete/{id}','MasterController@countrydestroy')->name('master.countrydestroy');
	Route::post('country_nameexists','CommonController@checkCountryNameExists');
	Route::get('country_detail','CommonController@countryDetail');

	//Relation Details
	Route::post('ajax_relation_list','MasterController@ajax_relation_list')->name('master.ajaxrelationlist');
	Route::get('relation','MasterController@relationList')->name('master.relation'); 
	Route::post('relation_save','MasterController@Relationsave')->name('master.saverelation'); 
	Route::post('relation_nameexists','CommonController@checkRelationNameExists');
	Route::get('relation_detail','CommonController@relationDetail');
	Route::delete('relation_delete/{id}','MasterController@relationDestroy')->name('master.relationdestroy');

	Route::get('/home', 'HomeController@index')->name('home');
	Route::post('/member-register', 'MemberController@register')->name('member.register');
	Route::get('get-branch-list-register','Auth\RegisterController@getBranchList');

	//Race Details 
	Route::get('race','MasterController@raceList')->name('master.race'); 
	Route::post('ajax_race_list','MasterController@ajax_race_list')->name('master.ajaxracelist');
	Route::post('race_nameexists','CommonController@checkRaceNameExists');
	Route::post('race_save','MasterController@raceSave')->name('master.saverace');
	Route::get('race_detail','CommonController@raceDetail');
	Route::delete('race_delete/{id}','MasterController@raceDestroy')->name('master.racedestroy');

	//Reason Details 
	Route::post('ajax_reason_list','MasterController@ajax_reason_list')->name('master.ajaxreasonlist');
	Route::get('reason','MasterController@reasonList')->name('master.reason'); 
	Route::post('reason_save','MasterController@reasonSave')->name('master.reasonSave');
	Route::get('reason_detail','CommonController@reasonDetail'); 
	Route::delete('reason_delete/{id}','MasterController@reasonDestroy')->name('master.reasondestroy');
	Route::post('reason_nameexists','CommonController@checkReasonNameExists'); 

	//Person Title Details 
	Route::post('ajax_persontitle_list','MasterController@ajax_persontitle_list')->name('master.ajaxpersontitlelist');
	Route::get('persontitle','MasterController@titleList')->name('master.persontitle');
	Route::get('persontitle_detail','CommonController@personTitleDetail');
	Route::post('persontitle_nameexists','CommonController@checkTitleNameExists'); 
	Route::post('persontitle_save','MasterController@personTileSave')->name('master.savepersontitle');
	Route::delete('persontitle_delete/{id}','MasterController@personTiteDestroy')->name('master.persontitledestroy');

	//Designation Details  
	Route::get('designation','MasterController@designationList')->name('master.designation');
	Route::post('ajax_designation_list','MasterController@ajax_designation_list')->name('master.ajaxdesignationlist');
	Route::post('designation_nameexists','CommonController@checkDesignationNameExists'); 
	Route::post('designation_save','MasterController@designationSave')->name('master.saveDesignation'); 
	Route::get('designation_detail','CommonController@designationDetail');
	Route::delete('designation-delete/{id}','MasterController@designationDestroy')->name('master.designationdestroy');

	//Status Details
	Route::get('status','MasterController@statusList')->name('master.status');
	Route::post('ajax_status_list','MasterController@ajax_status_list')->name('master.ajaxstatuslist'); 
	Route::post('status_nameexists','CommonController@checkStatusNameExists'); 
	Route::post('saveStatus','MasterController@statusSave')->name('master.saveStatus'); 
	Route::get('status_details','CommonController@statusDetail'); 
	Route::delete('status-delete/{id}','MasterController@statusDestroy')->name('master.statusdestroy');
	
	//Form Type
	//Route::resource('formtype','FormTypeController');
	Route::get('formtype','MasterController@formTypeList')->name('master.formtype'); 
	Route::post('ajax_formtype_list','MasterController@ajax_formtype_list')->name('master.ajaxformtypelist'); 
	Route::post('formtype_nameexists','CommonController@checkFormTypeNameExists');  
	Route::post('saveFormType','MasterController@formTypeSave')->name('master.saveFormType');  
	Route::get('formtype_detail','CommonController@formTypeDetail'); 
	Route::delete('formtype-delete/{id}','MasterController@formTypeDestroy')->name('master.formTypedestroy');

	//Company 
	Route::get('/company','MasterController@companyList')->name('master.company');
	Route::post('ajax_company_list','MasterController@ajax_company_list')->name('master.ajaxcompanylist'); 
	Route::post('company_nameexists','CommonController@checkCompanyNameExists'); 
	Route::post('saveCompany','MasterController@companySave')->name('master.saveCompany'); 
	Route::get('company_detail','CommonController@companyDetail'); 
	Route::get('save_company_detail','CommonController@saveCompanyDetail');
	Route::delete('companydestroy/{id}','MasterController@companyDestroy')->name('master.companydestroy');
	
	//State Details 
	Route::get('state','MasterController@stateList')->name('master.state');
	Route::post('ajax_state_list','MasterController@ajax_state_list');
	Route::post('state_save','MasterController@stateSave')->name('master.savestate');
	Route::delete('state-delete/{id}','MasterController@statedestroy')->name('master.statedestroy');
	Route::post('state_nameexists','CommonController@checkStateNameExists');
	Route::get('state_detail','CommonController@stateDetail');
	

	//City Details 
	Route::get('city','MasterController@cityList')->name('master.city');
	Route::post('ajax_city_list','MasterController@ajax_city_list');
	Route::post('city_save','MasterController@citySave')->name('master.savecity');
	Route::post('city_nameexists','CommonController@checkCityNameExists');
	Route::get('city_detail','CommonController@cityDetail');
	Route::delete('city-delete/{id}','MasterController@citydestroy')->name('master.citydestroy');

	//Union Branch
	Route::get('unionbranch','MasterController@unionBranchList')->name('master.unionbranch');
	Route::get('save-unionbranch','MasterController@addUnionBranch')->name('master.addunionbranch');
	Route::post('unionbranch_save','MasterController@UnionBranchsave')->name('master.saveunionbranch');
	Route::get('unionbranch-edit/{parameter}','MasterController@EditUnionBranch')->name('master.editunionbranch');
	Route::get('unionbranch-delete/{id}','MasterController@deleteUnionBranch')->name('master.deleteunionbranch');
	Route::post('ajaxUnionBranchList','MasterController@AjaxunionBranchList')->name('master.union_BranchList');
	Route::post('branch_emailexists','MasterController@checkBranchemailExists');
	
	//Fee Details
	Route::get('fee','MasterController@fees_list')->name('master.fee');
	Route::post('ajax_fees_list','MasterController@ajax_fees_list')->name('master.ajaxfeeslist');
	Route::post('fee_save','MasterController@saveFee')->name('master.savefee');
	Route::post('fee_nameexists','CommonController@checkFeeNameExists');
	Route::get('fee_detail','CommonController@feeDetail');
	Route::delete('fee-delete/{id}','MasterController@feedestroy')->name('master.feedestroy');
    
	// Role Details
	Route::get('roles','MasterController@roles_list')->name('master.roles');
	Route::post('ajax_roles_list','MasterController@ajax_roles_list')->name('master.ajax_roles_list');
	Route::post('role_save','MasterController@saveRole')->name('master.saverole');
	Route::post('roles_nameexists','CommonController@checkRoleNameExists');
	Route::get('role_detail','CommonController@roleDetail');
	//Route::delete('roles-delete/{id}','MasterController@roledestroy')->name('master.rolesdestroy');
	
	//Branch Details
	Route::get('branch','MasterController@CompanyBranchList')->name('master.branch');
	/* 
	Route::get('get-state-list','BranchController@getStateList');
	Route::get('get-cities-list','BranchController@getCitiesList');
	 */
	Route::get('add-branch','MasterController@addCompanyBranch')->name('master.addbranch');
	Route::post('branch_save','MasterController@CompanyBranchsave')->name('master.savecompanybranch');
	Route::get('branch-edit/{parameter}','CompanyBranchController@edit')->name('master.editbranch');
	Route::post('branch_update','CompanyBranchController@update')->name('master.updatebranch');
	Route::get('branch-delete/{id}','MasterController@deleteCompanyBranch')->name('master.deletebranch');
	Route::post('ajax-company-branchlist','MasterController@AjaxCompanyBranchList')->name('master.company_branch_list');

	//App Form
	Route::resource('appform', 'AppFormController');
	//Roles Form
	//Route::resource('roles','RolesController');
	//users Form
	//Route::resource('users','UsersController');
	Route::post('ajax_users_list','MasterController@ajax_users_list')->name('master.ajaxuserslist');
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

	

	

});
/* Master */
	Route::get('membership','MembershipController@index');
	Route::get('membership_register','MembershipController@addMember');
	Route::get('membership_list','MembershipController@new_members');
	Route::get('membership-edit/{parameter}','MembershipController@edit');
	Route::get('membership-delete/{id}','MembershipController@delete');
	
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
Route::post('membership_save','MemberController@Save');
Route::post('membership_update','MemberController@update');

//Person Tiltle Setup
Route::get('persontitle','PersontitleController@index');
Route::get('add-title','PersontitleController@addTitle');
Route::post('persontitle_save','PersontitleController@save');
Route::get('persontitle-edit/{parameter}','PersontitleController@edit');
Route::post('persontitle_update','PersontitleController@update');
Route::get('persontitle-delete/{id}','PersontitleController@delete');



Route::get('get-nominee-data','MembershipController@getNomineeData');
Route::post('update-nominee','MembershipController@updateNominee');
Route::get('delete-nominee-data','MembershipController@deleteNominee');
Route::get('delete-fee-data','MembershipController@deleteFee');
Route::get('edit-membership-profile','MemberController@editMemberProfile')->name('member.membership.profile');

Route::get('/maintenance', function () {
    return view('errors.maintenance');
});

