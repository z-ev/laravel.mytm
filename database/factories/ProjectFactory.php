<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Project;
use Faker\Generator as Faker;
use App\Models\User;

$factory->define(Project::class, function (Faker $faker) {
    return [
        'user_id' => User::all()->random()->id,
        'status' => '1',
        'title' => $faker->sentence,
        'body' => $faker->paragraph,
        'deadline' => now()->format('Y-m-d H:i:s'),
    ];
});
