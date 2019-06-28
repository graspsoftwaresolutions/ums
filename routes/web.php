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
Route::get('get-state-list','MembershipController@getStateList');
Route::get('get-cities-list','MembershipController@getCitiesList');

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