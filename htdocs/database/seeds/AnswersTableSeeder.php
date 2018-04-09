<?php

use App\Script;
use App\Question;
use Illuminate\Database\Seeder;

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
        foreach ( $script->questions_order as $question ) {
            if ( ! $question instanceof Question ) {
                continue;
            }
            // @todo: agregar aleatoriamente especificación en las que lo solicitan
            // @todo: cuando la opción es "sí con ayuda", añadir ayudas aleatoriamente
            DB::table('answers')->insert([
                'subject_id' => 1,
                'survey_id' => 1,
                'option_id' => $question->options->random()->id,
                'response_time' => mt_rand(5, 60)
            ]);
        }
    }
}
