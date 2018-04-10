<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,

            // todo lo necesario para generar las preguntas
			DimensionsTableSeeder::class,
			CategoriesTableSeeder::class,
			AssistancesTableSeeder::class,
            AidsTableSeeder::class,
            QuestionsTableSeeder::class,

            // sujetos de prueba
            ImpairmentsTableSeeder::class,
            SubjectsTableSeeder::class,

            // Guión predeterminado
            ScriptsTableSeeder::class,

            // Respuestas de prueba
            SurveysTableSeeder::class,
            AnswersTableSeeder::class
		]);
    }
}
