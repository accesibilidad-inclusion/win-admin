<?php

namespace App;

use App\Events\SurveyCreating;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $hidden = [

    ];
    protected $appends = [
        'questionnaire',
        'aids',
        'specifications'
    ];
    protected $casts = [
        'id'           => 'integer',
        'subject_id'   => 'integer',
        'event_id'     => 'integer',
        'script_id'    => 'integer',
        'hash'         => 'string',
        'is_completed' => 'boolean',
    ];
    protected $dates = [
        'last_answer_at',
        'deleted_at',
        'created_at',
        'updated_at'
    ];
    protected $fillable = [
        'subject_id',
        'script_id'
    ];

   protected $dispatchesEvents = [
       'creating' => SurveyCreating::class
   ];

    public function subject() {
        return $this->belongsTo('App\Subject');
    }
    public function script() {
        return $this->belongsTo('App\Script');
    }
    public function getQuestionnaireAttribute() {
        $script = Script::findOrFail( $this->script_id );
        return $script->stages;
    }
    public function getAidsAttribute() {
        return Aid::all();
    }
    public function getSpecificationsAttribute() {
        return [
			[
                'id'    => 'home',
                'label' => 'En el hogar'
            ],
            [
                'id'    => 'outside',
                'label' => 'Fuera del hogar'
            ]
		];
    }
}
