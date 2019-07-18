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

	//Person Title Details 
	Route::post('ajax_persontitle_list','MasterController@ajax_persontitle_list')->name('master.ajaxpersontitlelist');
	Route::get('persontitle','MasterController@titleList')->name('master.persontitle');
	Route::get('persontitle_detail','CommonController@personTitleDetail');
	Route::post('persontitle_nameexists','CommonController@checkTitleNameExists'); 
	Route::post('persontitle_save','MasterController@personTileSave')->name('master.savepersontitle');
	Route::delete('reason_delete/{id}','MasterController@personTiteDestroy')->name('master.persontitledestroy');

	//Designation Details  
	Route::get('designation','MasterController@designationList')->name('master.designation');
	Route::post('ajax_designation_list','MasterController@ajax_designation_list')->name('master.ajaxdesignationlist');
	Route::post('designation_nameexists','CommonController@checkDesignationNameExists'); 
	Route::post('designation_save','MasterController@designationSave')->name('master.saveDesignation'); 
	Route::get('designation_detail','CommonController@designationDetail');
	Route::delete('designation-delete/{id}','MasterController@designationDestroy')->name('master.designationdestroy');
	
	//State Details 
	Route::get('state','MasterController@stateList')->name('master.state');
	Route::get('add-state','StateController@addState')->name('master.addstate');
	Route::post('state_save','StateController@save')->name('master.savestate');
	Route::get('state-edit/{parameter}','StateController@edit')->name('master.editstate');
	Route::post('state_edit','StateController@update')->name('master.updatestate');
	Route::get('state-delete/{id}','StateController@delete')->name('master.deletestate');

	//City Details 
	Route::get('city','CityController@index')->name('master.city');
	Route::get('add-city','CityController@addCity')->name('master.addCity');
	//Route::get('get-state-order-list/{country}','CityController@getStates')->name('master.getStates');
	Route::post('city_save','CityController@save')->name('master.citysave');
	Route::get('city-edit/{parameter}','CityController@edit')->name('master.editcity');
	Route::post('city_update','CityController@update')->name('master.updatecity');
	Route::get('city-delete/{id}','CityController@delete')->name('master.deletecity');

	//Company
	Route::get('/company','CompanyController@index')->name('master.company');
	Route::get('/add-company','CompanyController@addCompany')->name('master.addcompany');
	Route::post('/company_save','CompanyController@save')->name('master.companysave');
	Route::get('company-edit/{parameter}','CompanyController@edit')->name('master.companyedit');
	Route::post('company_edit','CompanyController@update')->name('master.companyupdate');
	Route::get('company-delete/{id}','CompanyController@delete')->name('master.deletecompany');

	//Union Branch
	Route::get('unionbranch','UnionBranchController@index')->name('master.unionbranch');
	Route::get('add-unionbranch','UnionBranchController@addUnionBranch')->name('master.addunionbranch');
	Route::post('unionbranch_save','UnionBranchController@save')->name('master.saveunionbranch');
	Route::get('unionbranch-edit/{parameter}','UnionBranchController@edit')->name('master.editunionbranch');
	Route::post('unionbranch_update','UnionBranchController@update')->name('master.updateunionbranch');
	Route::get('unionbranch-delete/{id}','UnionBranchController@delete')->name('master.deleteunionbranch');
	
	//Fee Details
	Route::get('fee','FeeController@index')->name('master.fee');
	Route::get('add-fee','FeeController@addFee')->name('master.addfee');
	Route::post('fee_save','FeeController@save')->name('master.savefee');
	//Route::get('fee-view/{parameter}','FeeController@view')->name('master.fee');
	Route::get('fee-edit/{parameter}','FeeController@edit')->name('master.editfee');
	Route::post('fee_update','FeeController@update')->name('master.updatefee');
	Route::get('fee-delete/{id}','FeeController@delete')->name('master.deletefee');
		
	//Status Details
	Route::get('status','StatusController@index')->name('master.status');
	Route::get('add-status','StatusController@addStatus')->name('master.addstatus');
	Route::post('status_save','StatusController@save')->name('master.savestatus');
	Route::get('status-edit/{parameter}','StatusController@edit')->name('master.editstatus');
	Route::post('status_update','StatusController@update')->name('master.updatestatus');
	Route::get('status-delete/{id}','StatusController@delete')->name('master.deletestatus');
	
	//Branch Details
	Route::get('branch','CompanyBranchController@index')->name('master.branch');
	/* 
	Route::get('get-state-list','BranchController@getStateList');
	Route::get('get-cities-list','BranchController@getCitiesList');
	 */
	Route::get('add-branch','CompanyBranchController@addBranch')->name('master.addbranch');
	Route::post('branch_save','CompanyBranchController@save')->name('master.savebranch');
	Route::get('branch-edit/{parameter}','CompanyBranchController@edit')->name('master.editbranch');
	Route::post('branch_update','CompanyBranchController@update')->name('master.updatebranch');
	Route::get('branch-delete/{id}','CompanyBranchController@delete')->name('master.deletebranch');

	//App Form
	Route::resource('appform', 'AppFormController');
	//Roles Form
	Route::resource('roles','RolesController');
	//users Form
	//Route::resource('users','UsersController');
	Route::post('ajax_users_list','MasterController@ajax_users_list')->name('master.ajaxuserslist');
	Route::get('users','MasterController@users_list')->name('master.userslist');
	Route::post('user_save','MasterController@userSave')->name('master.saveuser');
	Route::delete('users/{id}','MasterController@user_destroy')->name('master.destroy');

	Route::post('unionBranchList','MasterController@unionBranchList')->name('master.union_BranchList');
	
	//Form Type
	Route::resource('formtype','FormTypeController');
	
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

