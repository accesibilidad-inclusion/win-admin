<?php

use Faker\Generator as Faker;

$factory->define(App\Subject::class, function (Faker $faker) {
    $sex = ['other', 'male', 'female'];
    $random_sex = (int) $faker->biasedNumberBetween( 0, 2 );

    $studies = collect([1, 0])->random();
    $works   = collect([1, 0])->random();

    return [
        'given_name' => $faker->firstName( $sex ),
        'family_name' => $faker->lastName( $sex ),
        'sex' => $sex[ $random_sex ],
        'consent_at' => ( new DateTime )->format('Y-m-d H:i:s'),
        'works' => collect([1, 0])->random(),
        'works_at' => $works ? $faker->company : '',
        'personal_id' => $faker->ean13,
        'studies' => $studies,
        'studies_at' => $studies ? $faker->company : '',
        'birthday' => $faker->date(),
        'last_connection_at' => ( new DateTime )->format('Y-m-d H:i:s')
    ];
});
