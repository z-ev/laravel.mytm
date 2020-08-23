<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Task;
use Faker\Generator as Faker;
use App\Models\User;
use App\Models\Project;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'user_id' => User::all()->random()->id,
        'project_id' => Project::all()->random()->id,
        'status' => '1',
        'title' => $faker->sentence,
        'body' => $faker->paragraph,
        'deadline' => now(),
    ];
});
