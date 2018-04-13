<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SurveysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('surveys')->insert([
            'subject_id'     => 1,
            'event_id'       => null,
            'script_id'      => 1,
            'hash'           => Hash::make( str_random( 64 ) ),
            'is_completed'   => 0,
            'last_answer_at' => ( new DateTime() )->format('Y-m-d H:i:s')
        ]);
    }
}