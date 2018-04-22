<?php

use App\Aid;
use App\Script;
use App\Survey;
use App\Question;
use App\Impairment;
use Illuminate\Database\Seeder;

class TestingSubjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $impairments      = Impairment::all()->pluck('id');
        $script           = Script::find( 1 );
        $specs            = collect(['home', 'outside', 'both']);
        $aids             = Aid::all();
        $script_questions = $script->questions_order;
        $subjects = factory( App\Subject::class, 499 )->create()->each( function( $subject ) use ( $impairments, $script, $script_questions, $specs, $aids ) {
            $subject_impairments = $impairments->random( mt_rand( 0, count( $impairments ) ) );
            if ( ! empty( $subject_impairments ) ) {
                $subject->impairments()->attach( $subject_impairments );
            }
            // crear survey
            $survey = new Survey;
            $survey->subject_id = $subject->id;
            $survey->script_id = 1;
            $survey->save();
            // crear respuestas
            foreach ( $script_questions as $question ) {
                if ( ! $question instanceof Question ) {
                    continue;
                }
                // @agregar aleatoriamente especificación en las que lo solicitan
                if ( $question->needs_specification ) {
                    $specification = $specs->random();
                } else {
                    $specification = null;
                }
                $option    = $question->options->random();
                $option_id = $option->id;
                $answer_id = DB::table('answers')->insertGetId([
                    'question_id'   => $question->id,
                    'subject_id'    => $subject->id,
                    'survey_id'     => $survey->id,
                    'option_id'     => $option->id,
                    'response_time' =>  mt_rand(5, 60),
                    'specification' => $specification
                ]);
                // @todo: cuando la opción es "sí con ayuda", añadir ayudas aleatoriamente
                if ( $option->value == 4 ) {
                    $selected_aids = $aids->random( mt_rand(1, count( $aids )) )->pluck('id');
                    if ( $selected_aids ) {
                        foreach ( $selected_aids as $aid_id ) {
                            DB::table('aid_answer')->insert([
                                'aid_id'    => $aid_id,
                                'answer_id' => $answer_id
                            ]);
                        }
                    }
                }
            }
        } );
    }
}
