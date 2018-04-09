<?php

use Illuminate\Database\Seeder;
use App\Question;

class ScriptsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $question_ids =  Question::all(['id'])->pluck('id')->split( 4 )->toArray();
        DB::table('scripts')->insert([
            'id' => 1,
            'name' => 'Guión Predeterminado',
            'created_by' => 1,
            'questions_order' => json_encode( $question_ids )
        ]);
    }
}
