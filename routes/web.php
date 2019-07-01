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
Route::get('/', 'Auth\LoginController@custom_login');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/member-register', 'MemberController@register')->name('member.register');


/* Master */

//Country Details
Route::get('country','CountryController@index');
Route::get('add-country','CountryController@addCountry');
Route::post('country_save','CountryController@save');
Route::get('country-view/{parameter}','CountryController@view');
Route::get('country-edit/{parameter}','CountryController@edit');
Route::post('country_edit','CountryController@update');
Route::get('country-delete/{id}','CountryController@delete');

//Membership
Route::get('get-state-list','MembershipController@getStateList');
Route::get('get-cities-list','MembershipController@getCitiesList');
Route::get('get-branch-list','MembershipController@getBranchList');
Route::get('get-oldmember-list','MembershipController@getoldMemberList');
Route::get('add-nominee','MembershipController@addNominee');

Route::get('membership','MembershipController@index');
Route::get('membership_register','MembershipController@addMember');
Route::post('membership_save','MembershipController@Save');
Route::get('membership-view/{parameter}','MembershipController@view');
Route::get('membership-edit/{parameter}','MembershipController@edit');
Route::post('membership_update','MembershipController@update');
Route::get('membership-delete/{id}','MembershipController@delete');
//State Details 
Route::get('state','StateController@index');
Route::get('add-state','StateController@addState');
Route::post('state_save','StateController@save');
Route::get('state-edit/{parameter}','StateController@edit');
Route::post('state_edit','StateController@update');
Route::get('state-delete/{id}','StateController@delete');
//Status Master
Route::get('status','StatusController@index');
Route::get('add-status','StatusController@addStatus');
Route::post('status_save','StatusController@save');
Route::get('status-edit/{parameter}','StatusController@edit');
Route::post('status_update','StatusController@update');
Route::get('status-delete/{id}','StatusController@delete');

//City Details 
Route::get('city','CityController@index');
Route::get('add-city','CityController@addCity');
//Route::get('get-state-order-list','CityController@getStateorderList');
Route::get('get-state-order-list/{country}','CityController@getStates');
Route::post('city_save','CityController@save');
Route::get('city-edit/{parameter}','CityController@edit');
Route::post('city_update','CityController@update');
Route::get('city-delete/{id}','CityController@delete');

//Designation
Route::get('designation','DesignationController@index');
Route::get('add-designation','DesignationController@addDesignation');
Route::post('designation_save','DesignationController@save');
Route::get('designation-edit/{parameter}','DesignationController@edit');
Route::post('update_designation','DesignationController@update');
Route::get('designation-delete/{id}','DesignationController@delete');

//Race 
Route::get('race','RaceController@index');
Route::get('add-race','RaceController@addRace');
Route::post('race_save','RaceController@save');
Route::get('race-view/{parameter}','RaceController@view');
Route::get('race-edit/{parameter}','RaceController@edit');
Route::post('race_update','RaceController@update');
Route::get('race-delete/{id}','RaceController@delete');

//Fee
Route::get('fee','FeeController@index');
Route::get('add-fee','FeeController@addFee');
Route::post('fee_save','FeeController@save');
Route::get('fee-view/{parameter}','FeeController@view');
Route::get('fee-edit/{parameter}','FeeController@edit');
Route::post('fee_update','FeeController@update');
Route::get('fee-delete/{id}','FeeController@delete');

//Reason
Route::get('reason','ReasonController@index');
Route::get('add-reason','ReasonController@addReason');
Route::post('reason_save','ReasonController@save');
Route::get('reason-view/{parameter}','ReasonController@view');
Route::get('reason-edit/{parameter}','ReasonController@edit');
Route::post('reason_update','ReasonController@update');
Route::get('reason-delete/{id}','ReasonController@delete');

//branch
Route::get('branch','BranchController@index');
Route::get('add-branch','BranchController@addBranch');
Route::post('branch_save','BranchController@save');
Route::get('branch-edit/{parameter}','BranchController@edit');
Route::post('branch_update','BranchController@update');
Route::get('branch-delete/{id}','BranchController@delete');

//Union Branch
Route::get('unionbranch','UnionBranchController@index');
Route::get('add-unionbranch','UnionBranchController@addUnionBranch');
Route::post('unionbranch_save','UnionBranchController@save');
Route::get('unionbranch-view/{parameter}','UnionBranchController@view');
Route::get('unionbranch-edit/{parameter}','UnionBranchController@edit');
Route::post('unionbranch_update','UnionBranchController@update');
Route::get('unionbranch-delete/{id}','UnionBranchController@delete');

//Company
Route::get('/company','CompanyController@index');
Route::get('/add-company','CompanyController@addCompany');
Route::post('/company_save','CompanyController@save');
Route::get('company-edit/{parameter}','CompanyController@edit');
Route::post('company_edit','CompanyController@update');
Route::get('company-delete/{id}','CompanyController@delete');

//Person Tiltle Setup
Route::get('persontitle','PersontitleController@index');
Route::get('add-title','PersontitleController@addTitle');
Route::post('persontitle_save','PersontitleController@save');
Route::get('persontitle-edit/{parameter}','PersontitleController@edit');
Route::post('persontitle_update','PersontitleController@update');
Route::get('persontitle-delete/{id}','PersontitleController@delete');

//Member Relation Setup
Route::get('relation','RelationController@index');
Route::get('add-relation','RelationController@addRelation');
Route::post('relation_save','RelationController@save');
Route::get('relation-edit/{parameter}','RelationController@edit');
Route::post('relation_update','RelationController@update');
Route::get('relation-delete/{id}','RelationController@delete');

//Union Branch
Route::get('unionbranch','UnionBranchController@index');
Route::get('add-unionbranch','UnionBranchController@addUnionBranch');
Route::post('unionbranch_save','UnionBranchController@save');
Route::get('unionbranch-edit/{parameter}','UnionBranchController@edit');
Route::post('unionbranch_update','UnionBranchController@update');
Route::get('unionbranch-delete/{id}','UnionBranchController@delete');