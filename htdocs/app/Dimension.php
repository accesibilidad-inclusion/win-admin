<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dimension extends Model
{
    public function questions()
	{
		return $this->hasMany('App\Question');
	}
	public function getParentDimension()
	{
		if ( $this->parent_id === 0 ) {
			return $this;
		} else {
			return Dimension::find( $this->parent_id );
		}
	}
}
