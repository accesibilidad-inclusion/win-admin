<?php

use Illuminate\Database\Seeder;

class ImpairmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('impairments')->insert([
            [
                'label' => 'Intelectual'
            ],
            [
                'label' => 'Física'
            ],
            [
                'label' => 'Auditiva'
            ],
            [
                'label' => 'Visual'
            ],
            [
                'label' => 'Otra'
            ]
        ]);
    }
}
