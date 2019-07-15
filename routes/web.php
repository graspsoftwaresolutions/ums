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
	//Country Master Details
	Route::get('country','MasterController@countryList')->name('master.country');
	Route::post('country_save','MasterController@countrySave')->name('master.savecountry');
	Route::get('country-edit','MasterController@countryEdit')->name('master.editcountry');

	Route::get('/home', 'HomeController@index')->name('home');
	Route::post('/member-register', 'MemberController@register')->name('member.register');
	Route::get('get-branch-list-register','Auth\RegisterController@getBranchList');

	//Country Details
	
	//Route::get('country-edit/{parameter}','CountryController@edit')->name('master.editcountry');
	//Route::post('country_edit','CountryController@update')->name('master.updatecountry');
	Route::get('country-delete/{parameter}','CountryController@delete')->name('master.deletecountry');

	//State Details 
	Route::get('state','StateController@index')->name('master.state');
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
	
	//Person Details
	Route::get('persontitle','PersontitleController@index')->name('master.persontitle');
	Route::get('add-title','PersontitleController@addTitle')->name('master.addpersontitle');
	Route::post('persontitle_save','PersontitleController@save')->name('master.savepersontitle');
	Route::get('persontitle-edit/{parameter}','PersontitleController@edit')->name('master.editpersontitle');
	Route::post('persontitle_update','PersontitleController@update')->name('master.updatepersontitle');
	Route::get('persontitle-delete/{id}','PersontitleController@delete')->name('master.deletepersontitle');

	//Relation Details
	Route::get('relation','RelationController@index')->name('master.relation');
	Route::get('add-relation','RelationController@addRelation')->name('master.addrelation');
	Route::post('relation_save','RelationController@save')->name('master.saverelation');
	Route::get('relation-edit/{parameter}','RelationController@edit')->name('master.editrelation');
	Route::post('relation_update','RelationController@update')->name('master.updaterelation');
	Route::get('relation-delete/{id}','RelationController@delete')->name('master.deleterelation');
	
	//Reason Details
	Route::get('reason','ReasonController@index')->name('master.reason');
	Route::get('add-reason','ReasonController@addReason')->name('master.addreason');
	Route::post('reason_save','ReasonController@save')->name('master.savereason');
	//Route::get('reason-view/{parameter}','ReasonController@view')->name('master.reason');
	Route::get('reason-edit/{parameter}','ReasonController@edit')->name('master.editreason');
	Route::post('reason_update','ReasonController@update')->name('master.updatereason');
	Route::get('reason-delete/{id}','ReasonController@delete')->name('master.deletereason');
	
	//Fee Details
	Route::get('fee','FeeController@index')->name('master.fee');
	Route::get('add-fee','FeeController@addFee')->name('master.addfee');
	Route::post('fee_save','FeeController@save')->name('master.savefee');
	//Route::get('fee-view/{parameter}','FeeController@view')->name('master.fee');
	Route::get('fee-edit/{parameter}','FeeController@edit')->name('master.editfee');
	Route::post('fee_update','FeeController@update')->name('master.updatefee');
	Route::get('fee-delete/{id}','FeeController@delete')->name('master.deletefee');
	
	//Race Details
	Route::get('race','RaceController@index')->name('master.race');
	Route::get('add-race','RaceController@addRace')->name('master.addrace');
	Route::post('race_save','RaceController@save')->name('master.saverace');
	//Route::get('race-view/{parameter}','RaceController@view')->name('master.fee');
	Route::get('race-edit/{parameter}','RaceController@edit')->name('master.editrace');
	Route::post('race_update','RaceController@update')->name('master.updaterace');
	Route::get('race-delete/{id}','RaceController@delete')->name('master.deleterace');
	
	//Designation Details
	Route::get('designation','DesignationController@index')->name('master.designation');
	Route::get('add-designation','DesignationController@addDesignation')->name('master.adddesignation');
	Route::post('designation_save','DesignationController@save')->name('master.savedesignation');
	Route::get('designation-edit/{parameter}','DesignationController@edit')->name('master.editdesignation');
	Route::post('update_designation','DesignationController@update')->name('master.updatedesignation');
	Route::get('designation-delete/{id}','DesignationController@delete')->name('master.deletedesignation');
	
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
	Route::resource('users','UsersController');
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

	Route::post('users_list','UsersController@userList')->name('master.userList');

	Route::get('users_detail','CommonController@userDetail');

	
	//Route::get('membership_list','MembershipController@new_members')->name('reports.new-members');
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


//Union Branch
Route::get('unionbranch','UnionBranchController@index');
Route::get('add-unionbranch','UnionBranchController@addUnionBranch');
Route::post('unionbranch_save','UnionBranchController@save');
Route::get('unionbranch-edit/{parameter}','UnionBranchController@edit');
Route::post('unionbranch_update','UnionBranchController@update');
Route::get('unionbranch-delete/{id}','UnionBranchController@delete');


Route::get('get-nominee-data','MembershipController@getNomineeData');
Route::post('update-nominee','MembershipController@updateNominee');
Route::get('delete-nominee-data','MembershipController@deleteNominee');

Route::get('delete-fee-data','MembershipController@deleteFee');
Route::get('edit-membership-profile','MemberController@editMemberProfile')->name('member.membership.profile');




Route::get('/maintenance', function () {
    return view('errors.maintenance');
});

