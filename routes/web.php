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
Route::get('pricing', 'PageController@pricing')->name('page.pricing');
Route::get('lock', 'PageController@lock')->name('page.lock');

Route::group(['middleware' => 'auth'], function () {
	Route::resource('category', 'CategoryController', ['except' => ['show']]);
	Route::resource('tag', 'TagController', ['except' => ['show']]);
	Route::resource('item', 'ItemController', ['except' => ['show']]);
	Route::resource('role', 'RoleController', ['except' => ['show', 'destroy']]);
	Route::resource('user', 'UserController', ['except' => ['show']]);

    Route::resource('group', 'GroupController', ['except' => ['show']]);
    Route::resource('event', 'EventController', ['except' => ['show']]);

    Route::get('myEvent', 'MyEventController@edit')->name('myEvent');
    Route::post('myEvent/{event}/ajaxCancel', 'MyEventController@delete')->name('myEvent.delete');
    Route::post('myEvent/{event}/ajaxPromise', 'MyEventController@save')->name('myEvent.save');

    //Route::resource('myevent', 'MyEventController', ['except' => ['show']]);

    //Route::get('setting', ['as' => 'setting.edit', 'uses' => 'SettingCotroller@edit']);
    //Route::put('setting', ['as' => 'setting.update', 'uses' => 'SettingCotroller@update']);

	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);
	
	Route::get('{page}', ['as' => 'page.index', 'uses' => 'PageController@index']);


});




