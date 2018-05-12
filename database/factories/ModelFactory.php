<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name'=> $faker->lastName,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});


$factory->define(App\Profile::class, function (Faker\Generator $faker) {
    $name = $faker->unique()->firstName;
    return [
        'full_name' => $name,
        'services'=>  array("live_performance" => array("live performance lesson 1"=>$faker->numberBetween($min = 10, $max = 500)),
                            "music_lessons" => array("music lessons 1"=>$faker->numberBetween($min = 10, $max = 500))
                                        ),
        'geners'=>array($faker->randomElement(array(
                                'African'=>'African',
                                'Asian'=>'Asian',
                                'Blues'=>'Blues',
                                'Comedy'=>'Comedy',
                                'Country'=>'Country',
                                'Disco'=>'Disco',
                                'Easy listening'=>'Easy listening',
                                'Electronic'=>'Electronic',
                                'Folk'=>'Folk',
                                'Funk'=>'Funk',
                                'Hip hop'=>'Hip hop',
                                'Jazz'=>'Jazz',
                                'Latin'=>'Latin',
                                'Pop'=>'Pop',
                                'R&B'=>'R&B',
                                'Rock'=>'Rock',
                                'Soul'=>'Soul',
                                ))),
        'influnced_by'=>array('maikel jakson','haleem','lynda'),
        'role'=>array($faker->randomElement(array(
                                'Accordion'=>'Accordion',
                                'Acoustic Guitar'=>'Acoustic Guitar',
                                'Bagpipes'=>'Bagpipes',
                                'Accordion'=>'Accordion',
                                'Band'=>'Band',
                                'Bass Guitar'=>'Bass Guitar',
                                'Bongo Drums'=>'Bongo Drums',
                                'Cello'=>'Cello',
                                'Clarinet'=>'Clarinet',
                                'Cornet'=>'Cornet',
                                'DJ'=>'DJ',
                                'Double Bass'=>'Double Bass',
                                'Drums'=>'Drums',
                                'Electic Guitar'=>'Electic Guitar',
                                'Electric Upright Bass'=>'Electric Upright Bass',
                                'Electric Violin'=>'Electric Violin',
                                'Flute'=>'Flute',
                                'French Horn'=>'French Horn',
                                'Gong'=>'Gong',
                                'Guitar'=>'Guitar',
                                'Harmonica'=>'Harmonica',
                                'Harp'=>'Harp',
                                'Horn'=>'Horn',
                                'Keyboards'=>'Keyboards',
                                'Mandolin'=>'Mandolin',
                                'Marimba'=>'Marimba',
                                'Ocarina'=>'Ocarina',
                                'Organ'=>'Organ',
                                'Piano'=>'Piano',
                                'Piccolo'=>'Piccolo',
                                'Saxophone'=>'Saxophone',
                                'Singer'=>'Singer',
                                'Sitar'=>'Sitar',
                                'Synthesizer'=>'Synthesizer',
                                'Tabla'=>'Tabla',
                                'Triangle'=>'Triangle',
                                'Trombone'=>'Trombone',
                                'Trumpet'=>'Trumpet',
                                'Turntables'=>'Turntables',
                                'Ukulele'=>'Ukulele',
                                'Viola'=>'Viola',
                                'Violin'=>'Violin',
                                'Xylophone'=>'Xylophone',
                                ))),
        'tagline'=>'Tag line test',
        'location'=>$faker->city,
        'profile_url'=>$name,
        'profile_image'=>$faker->randomElement(array ('profiles/images/user-avatar-1.jpg','profiles/images/user-avatar-2.jpg','profiles/images/user-avatar-3.jpg','profiles/images/user-avatar-4.jpg')),
        'profile_video'=> $faker->randomElement(array ('https://www.youtube.com/watch?v=_TZLQJe9pFM','https://www.youtube.com/watch?v=zX27MJKp4Bw','https://www.youtube.com/watch?v=NdtqR2vSsXw','https://www.youtube.com/watch?v=ezUvi5yAWEY')),
        'gender'=>$faker->randomElement(array ('male','female')),
        'latitude'=>$faker->latitude($min = -90, $max = 90),
        'longitude'=>$faker->longitude($min = -180, $max = 180),
        'about'=> '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>'
    ];
});
