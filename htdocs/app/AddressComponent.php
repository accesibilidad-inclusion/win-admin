<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AddressComponent extends Model
{
    public $timestamps = false;
    protected $guarded = [];
    public function institution()
    {
        return $this->belongsTo('App\Institution');
    }
}
