<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    public function aids()
	{
		return $this->belongsToMany('App\Aid');
	}
}
