<?php

use Illuminate\Database\Seeder;

class AssistancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('assistances')->insert([
			['label' => 'Desarrollo humano'],
			['label' => 'Enseñanza y educación'],
			['label' => 'Vida en el hogar'],
			['label' => 'Vida en comunidad'],
			['label' => 'Empleo'],
			['label' => 'Salud y seguridad'],
			['label' => 'Actividades conductuales'],
			['label' => 'Actividades sociales'],
			['label' => 'Protección y defensa']
		]);
    }
}
