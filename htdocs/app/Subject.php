<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'last_connection_at'
    ];
    protected $casts = [
        'works' => 'boolean',
        'studies' => 'boolean',
    ];
    public static function getSexes()
    {
        return [
            'female' => 'Mujer',
            'male'   => 'Hombre',
            'other'  => 'Otro'
        ];
    }
    public function setPersonalIdAttribute( $id )
    {
        $this->attributes['personal_id'] = mb_strtoupper( filter_var( $id, FILTER_SANITIZE_STRING) );
    }
    public function setGivenNameAttribute( $val )
    {
        $this->attributes['given_name'] = filter_var( $val, FILTER_SANITIZE_STRING );
    }
    public function setFamilyNameAttribute( $val )
    {
        $this->attributes['family_name'] = filter_var( $val, FILTER_SANITIZE_STRING );
    }
    public function impairments()
    {
        return $this->belongsToMany('App\Impairment');
    }
    public function surveys()
    {
        return $this->hasMany('App\Survey');
    }
}
