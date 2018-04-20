<?php

use App\Script;
use App\Question;
use Illuminate\Database\Seeder;
use App\Assistance;
use App\Aid;

class AnswersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * subject_id
         * survey_id
         * option_id
         * specification
         * response_time
         * ---
         * aids_answer
         */
        $script = Script::find( 1 );
        $specs  = collect(['home', 'outside', 'both']);
        $aids   = Aid::all();
        foreach ( $script->questions_order as $question ) {
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
                'subject_id'    => 1,
                'survey_id'     => 1,
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
                // DB::table('aid_answer')->inse
            }
        }
    }
}
