<?php

use App\Script;
use App\Survey;
use App\Subject;
use App\Assistance;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function() {
	Route::get('/questions', function( Request $request ){
		$search = $request->get('search');
		if ( ! empty( $search ) ) {
			$questions = App\Question::where('formulation', 'LIKE', "%$search%")
				->limit(5)
				->get();
		} else {
			$questions = App\Question::with(['category', 'dimension', 'options', 'assistances'])->get();
		}
		return $questions->toJson();
	});
	Route::get('/questions/{id}', function($id){
		$question = App\Question::with(['category', 'dimension', 'options', 'assistances'])->find($id);
		return $question->toJson();
	});
	Route::get('/questions/search', function(){
		$questions = App\Question::with(['category'])->get(5);
		return $questions->toJson();
    });
    Route::get('/scripts/{id}', function( Request $request, Response $response, int $id ){

		$script = Script::findOrFail( $id );

        // $script = Script::find( $id );
		// return $script->toJson();
		// $response = [
		// 	'onboarding' => [
		// 		[
		// 			'slug' => 'bienvenida',
		// 			'audio' => [
		// 				'url' => 'https://admin.apoyos.win/audio/33428349283.mp3'
		// 			]
		// 		]
		// 	],
		// 	'questionnaire' => [
		// 		[
		// 			'name' => 'Etapa 1',
		// 			'description' => '',
		// 			'questions' => App\Question::with(['options'])->take( 11 )->get()
		// 		],
		// 		[
		// 			'name' => 'Etapa 2',
		// 			'description' => '',
		// 			'questions' => App\Question::with(['options'])->take( 11 )->offset( 11 )->get()
		// 		]
		// 	]
		// ];
		return response( json_encode( $script->stages ) )
			->header( 'Content-Type', 'application/json' );
	});
	Route::get('/assistances', function(){
		$assistances = Assistance::all();
		return $assistances->toJson();
	});
	Route::get('/specifications', function(){
		$specs = [
			[
				'id' => 1,
				'label' => 'Familia'
			],
			[
				'id' => 2,
				'label' => 'Amigos'
			],
			[
				'id' => 3,
				'label' => 'Profesional'
			],
			[
				'id' => 4,
				'label' => 'Tecnología'
			],
			[
				'id' => 5,
				'label' => 'Otro'
			]
		];
		return response( json_encode( $specs ) )
			->header('Content-Type', 'application/json');
	});
	Route::get('/surveys/{id}', function( Request $request, int $id ){
		$survey = Survey::where([
			'id' => $id,
			'hash' => $request->get('hash')
		])->firstOrFail();
		return $survey->toJson();
	});
	Route::post('/surveys', function( Request $request ){
		$survey = Survey::create([
			'subject_id' => $request->get('subject_id'),
			'script_id'  => $request->get('script_id') ?? 1
		]);
		return $survey->toJson();
	});
	Route::post('/answer', function( Request $request ){
		// question_id
		// subject_id
		// survey_id
		// hash
		// option_id
		// response_time
		// specification: [ home, outside ]
		// aids: [ id, id, id ]
	});
});