<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Script extends Model
{
	protected $casts = [
		'questions_order' => 'array'
	];
	protected $visible = [
		'id',
		'name',
		'questions_order',
		'updated_at'
	];
	public function getQuestionsOrderAttribute( $ordered )
	{
		$all_questions = [];
		$ordered = json_decode( $ordered );
		foreach ( $ordered as $stage ) {
			foreach ( $stage as $question ) {
				$all_questions[] = $question;
			}
		}
		$questions = Question::findMany( $all_questions )->load('options');
		$stage_id = 2;
		$ordered_questions = [];
		$i = 0;
		foreach ( $ordered as $stage ) {
			foreach ( $stage as $question_id ) {
				$ordered_questions[] = $questions->find( $question_id );
				++$i;
			}
			if ( $i < count( $all_questions ) ) {
				$ordered_questions[] = (object) [
					'id'              => 'stage-'. $stage_id,
					'formulation'     => 'Nueva etapa',
					'container_class' => 'bg-secondary text-white'
				];
			}
			++$stage_id;
		}
		return $ordered_questions;
	}
	public function getStagesAttribute( )
	{
		$questions_order = json_decode( $this->attributes['questions_order'] );
		$question_ids = [];
		foreach ( $questions_order as $key => $val ) {
			$questions_order[ $key ] = array_values( (array) $val );
			$question_ids = array_merge( $question_ids, $questions_order[ $key ] );
		}
		$questions = Question::findMany( $question_ids )->load('options');
		$stages = [];
		$i = 0;
		foreach ( $questions_order as $stage ) {
			$stages[] = [
				'name'        => 'Etapa '. ($i+1),
				'id'          => ( $i + 1 ),
				'description' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Hic, iure?',
				'questions'   => $questions->filter( function( $item ) use ( $stage ){
					return in_array( $item->id, $stage );
				})->values()
			];
		}
		return $stages;
	}
}
