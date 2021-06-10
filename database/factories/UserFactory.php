<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Card;
use App\Groups;
use App\Task;
use App\Users;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Users::class, function (Faker $faker) {
    return [
        'username' => $faker->username,
        'email' => $faker->unique()->safeEmail,
        'password' => Hash::make('a00000000'),
        'remember_token' => Str::random(10),
    ];
});

$factory->define(Card::class, function (Faker $faker) {
    return [
        'card_name' => $faker->title,
        'create_user' => $faker->username,
        'private' => $faker->boolean,
    ];
});

$factory->define(Groups::class, function (Faker $faker) {
    return [
        'users_id' => factory(Users::class),
        'card_id' => factory(Card::class),
    ];
});

$factory->define(Task::class, function (Faker $faker) {
    return [
        'title' => $faker->title,
        'status' => $faker->boolean,
        'create_user' => $faker->name,
        'update_user' => $faker->name,
        'description' => $faker->paragraph,
        'tag' => $faker->colorName,
        'image' => $faker->url,
        'card_id' => factory(Card::class),
    ];
});
