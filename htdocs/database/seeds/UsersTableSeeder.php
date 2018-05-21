<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Felipe Lavín',
            'email' => 'felipe@bloom-ux.com',
            'password' => Hash::make( env('ADMIN_USER_PASS') )
        ]);
        DB::table('users')->insert([
            'name' => 'Basilio Cáceres',
            'email' => 'basilio@bloom-ux.com',
            'password' => Hash::make( str_random() )
        ]);
        DB::table('users')->insert([
            'name' => 'Herbert Spencer',
            'email' => 'hspencer@ead.cl',
            'password' => Hash::make( str_random() )
        ]);
        DB::table('users')->insert([
            'name' => 'Vanessa Vega',
            'email' => 'vanessa.vega@pucv.cl',
            'password' => Hash::make( str_random() )
        ])
    }
}
