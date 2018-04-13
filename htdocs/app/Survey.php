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
        'onboarding',
        'questionnaire',
        'aids',
        'specifications'
    ];
    protected $casts = [
        'is_completed' => 'boolean',
        'subject_id' => 'integer'
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
    public function getOnboardingAttribute() : array
    {
        return [];
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
