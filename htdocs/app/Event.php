<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;
    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at',
        'starts_at',
        'ends_at'
    ];
    public function setLabelAttribute( $val )
    {
        $this->attributes['label'] = filter_var( $val, FILTER_SANITIZE_STRING );
    }
    public function institution()
    {
        return $this->belongsTo('App\Institution');
    }
}
