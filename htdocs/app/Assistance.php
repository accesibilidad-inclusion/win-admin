<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assistance extends Model
{
	protected $hidden = ['created_at', 'updated_at'];
    public function questions()
	{
		return $this->belongsToMany('App\Question');
	}
}
