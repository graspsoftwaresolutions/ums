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

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
//Route::get('/register', 'MemberController@index')->name('member.register');
Route::post('/member-register', 'MemberController@register')->name('member.register');


Route::group(['middleware' => 'role:union,create-user'], function() {

		Route::get('/admin', function() {
		return 'Welcome Admin';
	});
});

Route::get('/test', function (\Illuminate\Http\Request $request) {

	$user = $request->user();
//	dd($user);

	//dd($user->hasRole('developer'));
//		dd($user->givePermissionsTo('create-tasks'));
	dd($user->can('create-user'));
});

