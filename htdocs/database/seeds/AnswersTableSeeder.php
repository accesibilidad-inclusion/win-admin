<?php

use App\Script;
use App\Question;
use Illuminate\Database\Seeder;
use App\Assistance;

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
        $script      = Script::find( 1 );
        $specs       = collect(['home', 'outside', 'always']);
        $assistances = Assistance::all();
        foreach ( $script->questions_order as $question ) {
            if ( ! $question instanceof Question ) {
                continue;
            }
            if ( $question->needs_specification ) {
                $specification = $specs->random();
            } else {
                $specification = null;
            }
            $option    = $question->options->random();
            $option_id = $option->id;
            // @todo: agregar aleatoriamente especificación en las que lo solicitan
            // @todo: cuando la opción es "sí con ayuda", añadir ayudas aleatoriamente
            DB::table('answers')->insert([
                'question_id'   => $question->id,
                'subject_id'    => 1,
                'survey_id'     => 1,
                'option_id'     => $option->id,
                'response_time' =>  mt_rand(5, 60)
            ]);
            if ( $option->type == 'yes' && $option->order == 3 ) {

            }
        }
    }
}
