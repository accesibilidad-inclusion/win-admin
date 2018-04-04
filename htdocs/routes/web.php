<?php

use Illuminate\Http\Request;

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::resources([
	'questions'    => 'QuestionController',
	'users'        => 'UserController',
	'events'       => 'EventController',
	'institutions' => 'InstitutionController',
	'subjects'     => 'SubjectController',
	'scripts'      => 'ScriptController'
]);

// Route::get('/users', 'UsersController@index')->name('users.index');
// Route::get('/users/create', 'UsersController@create')->name('users.create');
// Route::get('/users/{user}/edit', 'UsersController@edit')->name('users.edit');
// Route::delete('/users/{user}', 'UsersController@destroy')->name('users.destroy');