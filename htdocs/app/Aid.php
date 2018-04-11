<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Aid extends Model
{
	protected $visible = [
		'id',
		'label'
	];
    public function answers()
	{
		return $this->belongsToMany('App\Answer');
	}
}
