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
			QuestionsTableSeeder::class,
			DimensionsTableSeeder::class,
			CategoriesTableSeeder::class,
			AssistancesTableSeeder::class,
			AidsTableSeeder::class
		]);
    }
}
