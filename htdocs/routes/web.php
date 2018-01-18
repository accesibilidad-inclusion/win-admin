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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::resources([
	'questions' => 'QuestionsController'
]);

Route::prefix('api/v1')->group(function() {
	Route::get('/questions', function(){
		$questions = App\Question::with(['category', 'dimension', 'options', 'assistances'])->get();
		return $questions->toJson();
	});
	Route::get('/questions/{id}', function($id){
		$question = App\Question::with(['category', 'dimension', 'options', 'assistances'])->find($id);
		return $question->toJson();
	});
});