<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Script extends Model
{
	protected $casts = [
		'questions_order' => 'array'
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
		$questions = Question::findMany( $all_questions );
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
}
