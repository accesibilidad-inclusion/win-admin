<?php

use App\Event;
use App\Answer;
use App\Option;
use App\Script;
use App\Survey;
use App\Subject;
use App\Question;
use App\Assistance;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Requests\StoreSubject;

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
	Route::get('/surveys/{id}', function( Request $request, int $id ){
		$survey = Survey::where([
			'id' => $id,
			'hash' => $request->get('hash')
		])->firstOrFail();
		return response( $survey->toJson() );
	});
	Route::post('/surveys', function( Request $request ){
		$validate = $request->validate([
			'subject_id' => 'required|integer|exists:subjects,id',
			'script_id'  => 'nullable|integer|exists:scripts,id'
		]);
		$survey = Survey::create([
			'subject_id' => $request->get('subject_id'),
			'script_id'  => $request->get('script_id') ?? 1
		]);
		return response( $survey->toJson() )->setStatusCode( 201 );
	});
	Route::get('/events/{hash}', function( Request $request ){
		$event = Event::where([
			'hash'   => $request->hash,
			'status' => 'active'
		])->firstOrFail();
		return response( $event->toJson() );
	});
	Route::post('/answers', function( Request $request ){
		$validate = $request->validate([
			'question_id'     => 'required|integer|exists:questions,id',
			'subject_id'      => 'required|integer|exists:subjects,id',
			'survey_id'       => 'required|integer|exists:surveys,id',
			'hash'            => 'required|exists:surveys,hash',
			'option_id'       => 'required|integer|exists:options,id',
			'response_time'   => 'required|integer',
			'specification.*' => 'string|in:home,outside',
			'aids.*'          => 'integer|exists:aids,id'
		]);

		// verificar cumplimiento de reglas de negocio:

		// - el hash debe coincidir con el survey_id → si no, 403
		$survey = Survey::where([
			'id' => $request->get('survey_id')
		], ['id', 'hash'])->first();
		if ( ! $survey || $request->get('hash') != $survey->hash ) {
			return response( json_encode('Código de acceso incorrecto') )->setStatusCode( 403 );
		}

		// - el option_id debe estar asociado al question_id → si no, 422
		$option_question_match = Option::where([
			'id'          => $request->get('option_id'),
			'question_id' => $request->get('question_id')
		])->first();
		if ( ! $option_question_match ) {
			return response( json_encode('La respuesta no corresponde a una de las opciones de la pregunta') )->setStatusCode( 422 );
		}

		// - el question_id debe ser parte del survey → si no, 422
		$survey->load('script');
		$question_ids = collect( $survey->script->getAttributeValue('questions_order') )->pluck('id');
		if ( ! $question_ids->has( $request->get('question_id') ) ) {
			return response( json_encode('La pregunta no es parte del guión especificado') )->setStatusCode( 422 );
		}

		// - sólo debe tener specificacion en caso de que la pregunta lo requiera → si no, 422
		$question = Question::find( $request->get('question_id') );
		if ( ! $question->needs_specification && $request->get('specification') ) {
			return response( json_encode('La pregunta señalada no debe indicar especificación') )->setStatusCode( 422 );
		}

		$answer_data = [];
		foreach ( [
			'question_id',
			'subject_id',
			'survey_id',
			'option_id',
			'response_time',
			'specification'
		] as $key ) {
			$answer_data[ $key ] = $request->get( $key );
		}

		$answer_data = array_filter( $answer_data );

		$answer = Answer::where([
			'subject_id'  => $answer_data['subject_id'],
			'survey_id'   => $answer_data['survey_id'],
			'question_id' => $answer_data['question_id']
		])->get()->first();

		if ( $answer ) {
			$answer->update( $answer_data );
		} else {
			$answer = Answer::create( $answer_data );
		}

		$answer->save();

		$answer->aids()->sync( $request->get('aids') );

		// cargar "aids" para mostrarlas en la respuesta
		$answer->load('aids');

		return response( $answer->toJson( ) )->setStatusCode( $answer->wasRecentlyCreated ? 201 : 200 );
	});
	Route::post('/subjects', function( Request $request ){
		$validate = $request->validate([
            'sex' => [
                'required',
                Rule::in( array_keys( Subject::getSexes() ) )
            ],
            'given_name'         => 'required|string|max:191',
            'family_name'        => 'required|string|max:191',
            'works'              => 'bool',
			'studies'            => 'bool',
			'birthday'           => 'date_format:Y-m-d',
			'works_at'           => 'nullable|string|max:191',
			'studies_at'         => 'nullable|string|max:191',
            'personal_id'        => 'nullable|string|max:32',
            'consent_at'         => 'date_format:Y-m-d H:i:s',
            'last_connection_at' => 'date_format:Y-m-d H:i:s',
		]);
		$subject = new Subject;
		foreach ( $request->all() as $key => $val ) {
			switch ( $key ) {
				case 'personal_id':
				case 'given_name':
				case 'family_name':
				case 'studies_at':
				case 'works_at':
					$subject->{$key} = filter_var( trim( $val ), FILTER_SANITIZE_STRING );
					break;
				case 'sex':
				case 'works':
				case 'studies':
				case 'birthday':
					$subject->{$key} = $val;
					break;

			}
		}
		$subject->save();
		return response( $subject->toJson() )
			->setStatusCode( 201 );
	});
});