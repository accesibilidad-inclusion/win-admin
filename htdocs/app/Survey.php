<?php

namespace App;

use App\Events\SurveyCreating;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
	protected $hidden = [

	];
	protected $appends = [
		'questionnaire',
		'aids',
		'specifications'
	];
	protected $casts = [
		'id'           => 'integer',
		'subject_id'   => 'integer',
		'event_id'     => 'integer',
		'script_id'    => 'integer',
		'hash'         => 'string',
		'is_completed' => 'boolean',
	];
	protected $dates = [
		'last_answer_at',
		'deleted_at',
		'created_at',
		'updated_at'
	];
	protected $fillable = [
		'subject_id',
		'script_id'
	];

	protected $dispatchesEvents = [
		'creating' => SurveyCreating::class
	];

	public function getResultsAttribute()
	{
		// cargar las preguntas y respuestas que corresponden a esta aplicación
		$answers = Answer::where([
			'survey_id' => $this->id
		])->get()->load(['question', 'option']);
		$dimension_ids             = $answers->pluck('question.dimension_id')->unique()->filter()->values();

		$indicators = DB::table('dimensions')->whereIn('id', $dimension_ids)->get();
		$indicators_by_dimension = $indicators->groupBy('parent_id');
		$buckets = [];
		foreach ( $indicators_by_dimension as $key => $values ) {
			$bucket = $values->pluck('id')->toArray();
			$bucket[] = $key;
			$buckets[ $key ] = $bucket;
		}

		$dimension_values = [];
		// agrupar respuestas por dimensión
		foreach ( $answers as $answer ) {
			$dimension_or_indicator_id = $answer->question->dimension_id;
			foreach ( $buckets as $dimension_id => $bucket_dimensions ) {
				if ( in_array( $dimension_or_indicator_id, $bucket_dimensions ) ) {
					$dimension_values[ $dimension_id ][] = $answer->option->value;
				}
			}
		}

		$dimensions_raw = DB::table('dimensions')->where('parent_id', 0)->get();
		$dimensions = [];
		foreach ( $dimensions_raw as $dimension ) {
			unset( $dimension->parent_id, $dimension->created_at, $dimension->updated_at );
			$dimension->values = $dimension_values[ $dimension->id ];
			$dimension->score = array_sum( $dimension->values );
			$dimension->max = count( $dimension->values ) * 6;
			$dimension->min = count( $dimension->values ) * 1;
			$dimension->answered = count( $dimension->values );

			// indicar nivel según puntje máximo
			switch ( $dimension->max ) {
				case 96:
					if ( $dimension->score > 69 ) {
						$dimension->level = 'high';
					} elseif ( $dimension->score > 42 ) {
						$dimension->level = 'medium';
					} else {
						$dimension->level = 'low';
					}
					break;
				case 72:
					if ( $dimension->score > 52 ) {
						$dimension->level = 'high';
					} elseif ( $dimension->score > 31 ) {
						$dimension->level = 'medium';
					} else {
						$dimension->level = 'low';
					}
					break;
				case 24:
					if ( $dimension->score > 17 ) {
						$dimension->level = 'high';
					} elseif ( $dimension->score > 9 ) {
						$dimension->level = 'medium';
					} else {
						$dimension->level = 'low';
					}
					break;
			}

			$dimensions[] = $dimension;
		}

		return (object) [
			'dimensions' => $dimensions,
			'answers'    => $answers,
		];
	}

	public function subject() {
		return $this->belongsTo('App\Subject');
	}
	public function script() {
		return $this->belongsTo('App\Script');
	}
	public function getQuestionnaireAttribute() {
		$script = Script::findOrFail( $this->script_id );
		return $script->stages;
	}
	public function getAidsAttribute() {
		return Aid::all();
	}
	public function getSpecificationsAttribute() {
		return [
			[
				'id'    => 'home',
				'label' => 'En el hogar'
			],
			[
				'id'    => 'outside',
				'label' => 'Fuera del hogar'
			]
		];
	}
}
