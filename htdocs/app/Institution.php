<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institution extends Model
{
    use SoftDeletes;
    protected $casts = [
        'lat'      => 'double',
        'lng'      => 'double',
        'location' => 'object'
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    public function setNameAttribute( $val )
    {
        $this->attributes['name'] = filter_var( $val, FILTER_SANITIZE_STRING );
    }
    public function addressComponents()
    {
        return $this->hasMany('App\AddressComponent');
    }
}
