<?php

use Illuminate\Database\Seeder;

class SubjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = ( new \DateTime )->format('Y-m-d H:i:s');
        DB::table('subjects')->insert([
            'given_name'         => 'Sherlock',
            'family_name'        => 'Holmes',
            'sex'                => 'male',
            'consent_at'         => $now,
            'works'              => true,
            'studies'            => false,
            'studies_at'         => '',
            'last_connection_at' => $now,
            'created_at'         => $now,
            'updated_at'         => $now,
            'birthday'           => ( new DateTime() )->sub( new DateInterval('P16Y') )
        ]);
        DB::table('impairment_subject')->insert([
            'impairment_id' => 5,
            'subject_id'    => 1
        ]);
    }
}
