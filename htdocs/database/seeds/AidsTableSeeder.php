<?php

use Illuminate\Database\Seeder;

class AidsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('aids')->insert([
			[
				'label' => 'Amigos'
			],
			[
				'label' => 'Familia'
 			],
			[
				'label' => 'Profesional'
			],
			[
				'label' => 'Tecnología'
			],
			[
				'label' => 'Otro'
			]
		]);
    }
}
