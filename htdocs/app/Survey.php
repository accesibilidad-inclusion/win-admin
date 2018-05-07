<?php

namespace App;

use App\Events\SurveyCreating;
use MathPHP\Statistics\Average;
use Illuminate\Support\Facades\DB;
use MathPHP\Statistics\Descriptive;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Survey extends Model
{
	use SoftDeletes;
	protected $hidden = [];
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
		])->get()->load(['question', 'option', 'aids']);
		$dimension_ids = $answers->pluck('question.dimension_id')->unique()->filter()->values();

		$indicators = DB::table('dimensions')->whereIn('id', $dimension_ids)->get();
		$indicators_by_dimension = $indicators->groupBy('parent_id');
		$buckets = [];
		foreach ( $indicators_by_dimension as $key => $values ) {
			$bucket = $values->pluck('id')->toArray();
			$bucket[] = $key;
			$buckets[ $key ] = $bucket;
		}

		$dimension_values = [];
		$dimension_answers = [];
		// agrupar respuestas por dimensión
		foreach ( $answers as $answer ) {
			$dimension_or_indicator_id = $answer->question->dimension_id;
			foreach ( $buckets as $dimension_id => $bucket_dimensions ) {
				if ( in_array( $dimension_or_indicator_id, $bucket_dimensions ) ) {
					$dimension_values[ $dimension_id ][] = $answer->option->value;
					$dimension_answers[ $dimension_id ][] = $answer;
				}
			}
		}

		$dimensions_raw = DB::table('dimensions')->where('parent_id', 0)->get();
		$dimensions = [];
		$aggregated = (object) [
			'score'    => 0,
			'max'      => 0,
			'min'      => 0,
			'answered' => 0,
			'level'    => ''
		];
		foreach ( $dimensions_raw as $dimension ) {
			unset( $dimension->parent_id, $dimension->created_at, $dimension->updated_at );
			$dimension->values     = $dimension_values[ $dimension->id ] ?? [];
			$dimension->answers    = $dimension_answers[ $dimension->id ] ?? [];
			$dimension->indicators = $buckets[ $dimension->id ] ?? [];
			$dimension->score      = array_sum( $dimension->values );
			$dimension->answered   = count( $dimension->values );
			$dimension->max        = $dimension->answered * 6;
			$dimension->min        = $dimension->answered * 1;

			// añadir a resultados totales
			$aggregated->answered += $dimension->answered;
			$aggregated->score    += $dimension->score;
			$aggregated->max      += $dimension->max;
			$aggregated->min      += $dimension->min;

			// indicar nivel según puntaje máximo
			$dimension->level = '';
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

			$dimension->stats = $this->buildStats( $dimension );

			$dimensions[] = $dimension;
		}

		// resultados totales
		if ( $aggregated->score > 190 ) {
			$aggregated->level = 'high';
		} elseif ( $aggregated->score > 116 ) {
			$aggregated->level = 'medium';
		} else {
			$aggregated->level = 'low';
		}

		$aggregated->values = collect( $dimension_values )->flatten()->toArray();
		$aggregated->stats = $this->buildStats( $aggregated );
		unset( $aggregated->values );

		return (object) [
			'dimensions' => $dimensions,
			'answers'    => $answers,
			'aggregated' => $aggregated
		];
	}

	private function buildStats( $data )
	{
		// promedio
		$mean = Average::mean( $data->values );
		// media
		$median = Average::median( $data->values );
		// moda
		$mode = Average::mode( $data->values );
		// varianza
		$variance = Descriptive::sampleVariance( $data->values );
		// desviación estándar
		$standard_deviation = Descriptive::standardDeviation( $data->values );
		return (object) compact('mean', 'median', 'mode', 'variance', 'standard_deviation');
	}

	public function getLevelLabel( string $level ) : string
	{
		switch ( $level ) {
			case 'low':
				return 'Bajo';
				break;
			case 'medium':
				return 'Medio';
				break;
			case 'high':
				return 'Alto';
				break;
		}
	}

	public function subject() {
		return $this->belongsTo('App\Subject');
	}
	public function script() {
		return $this->belongsTo('App\Script');
	}
	public function answers()
	{
		return $this->belongsToMany('App\Answer');
	}
	public function getRelativeAge()
	{
		return $this->subject->getRelativeAge( $this->created_at );
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
