<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
	protected $fillable = ['label', 'type', 'order'];
	protected $hidden = ['created_at', 'updated_at'];
    public function question()
	{
		return $this->belongsTo('App\Question');
	}
}
