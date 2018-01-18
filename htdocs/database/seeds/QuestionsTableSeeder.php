<?php

use Illuminate\Database\Seeder;

class QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('questions')->insert([
			'formulation' => 'Soy como quiero',
			'needs_specification' => false,
			'specification' => '',
			'order' => 1,
			'status' => 'visible'
		]);
		DB::table('questions')->insert([
			'formulation' => 'Controlo algunas cosas de mi vida',
			'needs_specification' => true,
			'specification' => '¿Dónde o cuándo controlas cosas?',
			'order' => 2,
			'status' => 'visible'
		]);
    }
}
