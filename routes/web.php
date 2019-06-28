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
    return view('auth.login');
});

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