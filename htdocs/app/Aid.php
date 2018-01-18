<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Aid extends Model
{
    public function answers()
	{
		return $this->belongsToMany('App\Answer');
	}
}
