<?php

use Illuminate\Database\Seeder;

class ScriptsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('scripts')->insert([
            'name' => 'Guión Predeterminado',
            'created_by' => 1,
            'questions_order' => json_encode( [] )
        ]);
    }
}
