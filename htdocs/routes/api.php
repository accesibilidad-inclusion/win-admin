<?php

use App\Script;
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
    Route::get('/scripts/{id}', function( Request $request, Response $response, $id ){

        // $script = Script::find( $id );
		// return $script->toJson();
		$response = [
			'onboarding' => [
				[
					'slug' => 'bienvenida',
					'audio' => [
						'url' => 'https://admin.apoyos.win/audio/33428349283.mp3'
					]
				]
			],
			'questionnaire' => [
				[
					'name' => 'Etapa 1',
					'description' => '',
					'questions' => App\Question::with(['options'])->take( 11 )->get()
				],
				[
					'name' => 'Etapa 2',
					'description' => '',
					'questions' => App\Question::with(['options'])->take( 11 )->offset( 11 )->get()
				]
			]
		];
		return response( json_encode( $response ) )
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
});