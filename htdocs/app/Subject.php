<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use SoftDeletes;
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'last_connection_at'
    ];
    protected $casts = [
        'works'    => 'boolean',
        'studies'  => 'boolean',
        'birthday' => 'date'
    ];
    protected $appends = [
        'age'
    ];
    public static function getSexes()
    {
        return [
            'female' => 'Mujer',
            'male'   => 'Hombre',
            'other'  => 'Otro'
        ];
    }
    public function getAgeAttribute()
    {
        if ( ! $this->birthday ) {
            return '';
        }
        $today = new \DateTime();
        $difference = $this->birthday->diff( $today, true );
        return $difference->y;
    }
    public function getRelativeAge( \DateTimeInterface $date )
    {
        if ( ! $this->birthday ) {
            return '';
        }
        return $this->birthday->diff( $date, true );
    }
    public function setPersonalIdAttribute( $id )
    {
        $this->attributes['personal_id'] = mb_strtoupper( filter_var( $id, FILTER_SANITIZE_STRING) );
    }
    public function setGivenNameAttribute( $val )
    {
        $this->attributes['given_name'] = filter_var( $val, FILTER_SANITIZE_STRING );
    }
    public function setSexAttribute( $val )
    {
        $values = static::getSexes();
        if ( \array_key_exists( $val, static::getSexes() ) ) {
            $this->attributes['sex'] = $val;
        } else {
            throw new \InvalidArgumentException("The value {$val} it's not valid");
        }
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
