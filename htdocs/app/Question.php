<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at', 'created_at', 'updated_at'];
	protected $casts = [
		'needs_specification' => 'boolean',
		'dimension'           => 'integer',
		'category'            => 'integer'
	];
    public function dimension()
	{
		return $this->belongsTo('App\Dimension');
	}
	public function category()
	{
		return $this->belongsTo('App\Category');
	}
	public function assistances()
	{
		return $this->belongsToMany('App\Assistance');
	}
	public function options()
	{
		return $this->hasMany('App\Option');
	}
	public function setNeedsSpecificationAttribute( $val )
	{
		$this->attributes['needs_specification'] = (bool) $val;
	}
	public function setFormulationAttribute( $val )
	{
		$this->attributes['formulation'] = filter_var( $val, FILTER_SANITIZE_STRING );
	}
	public function setSpecificationAttribute( $val )
	{
		$this->attributes['specification'] = filter_var( $val, FILTER_SANITIZE_STRING );
	}
}
