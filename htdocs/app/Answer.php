<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
	protected $fillable = [
		'option_id',
		'response_time',
		'subject_id',
		'survey_id',
		'question_id',
		'specification'
	];
	protected $casts = [
		'question_id'   => 'integer',
		'option_id'     => 'integer',
		'response_time' => 'integer',
		'subject_id'    => 'integer',
		'survey_id'     => 'integer',
	];
	public static function getAllowedSpecifcations() {
		return [
			'home',
			'outside'
		];
	}
	public function setSpecificationAttribute( $values )
	{
		$sanitized = [];
		foreach ( (array) $values as $val ) {
			if ( in_array( $val, static::getAllowedSpecifcations() ) ) {
				$sanitized[] = $val;
			}
		}
		if ( $sanitized == static::getAllowedSpecifcations() ) {
			$this->attributes['specification'] = 'both';
			return;
		}
		$this->attributes['specification'] = current( $sanitized );
	}
	public function getSpecificationAttribute( $val )
	{
		if ( $val == 'both' ) {
			return static::getAllowedSpecifcations();
		} else {
			return (array) $val;
		}
	}
    public function aids()
	{
		return $this->belongsToMany('App\Aid');
	}
}
