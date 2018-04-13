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
		'question_id'
	];
	protected $casts = [
		'question_id'   => 'integer',
		'option_id'     => 'integer',
		'response_time' => 'integer',
		'subject_id'    => 'integer',
		'survey_id'     => 'integer'
	];
    public function aids()
	{
		return $this->belongsToMany('App\Aid');
	}
}
