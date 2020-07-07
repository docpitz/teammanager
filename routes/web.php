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

if(App::environment('local'))
{
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
}

Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');



Auth::routes();

Route::get('home', 'HomeController@index')->name('home');
Route::get('lock', 'PageController@lock')->name('page.lock');

Route::group(['middleware' => 'auth'], function () {
	Route::resource('role', 'RoleController', ['except' => ['show', 'destroy']]);
	Route::resource('user', 'UserController', ['except' => ['show']]);

    Route::resource('group', 'GroupController', ['except' => ['show']]);
    Route::resource('event', 'EventController', ['except' => ['show']]);
    Route::get('event/{event}/copy', 'EventController@edit');

    Route::resource('eventBookingOverview', 'EventBookingOverviewController', ['only' => ['edit', 'update']]);

    Route::get('myEvent', 'MyEventController@edit')->name('myEvent');


     Route::get('showEvent/{event}', 'MyEventController@edit')->name('showEvent');
    Route::post('myEvent/{event}/ajaxCanceled', 'MyEventController@canceled')->name('myEvent.canceled');
    Route::post('myEvent/{event}/ajaxPromised', 'MyEventController@promised')->name('myEvent.promised');
    Route::post('myEvent/{event}/ajaxWaitlist', 'MyEventController@waitlist')->name('myEvent.waitlist');

	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
    Route::post('profile/avatar', ['as' => 'profile.avatar', 'uses' => 'ProfileController@avatar']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);

	Route::get('{page}', ['as' => 'page.index', 'uses' => 'PageController@index']);
});




