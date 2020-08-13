<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Message;
use Faker\Generator as Faker;

$factory->define(Message::class, function (Faker $faker) {
    $usersCount = User::count();
    do {
        $from = rand(1, $usersCount);
        $to = rand(1, $usersCount);
    } while ($from === $to);

    return [
        Message::FROM => $from,
        Message::TO => $to,
        Message::TEXT => $faker->sentence,
    ];
});

$factory->state(Message::class, 'read', function (Faker $faker) {
    $usersCount = User::count();
    do {
        $from = rand(1, $usersCount);
        $to = rand(1, $usersCount);
    } while ($from === $to);

    return [
        Message::FROM => $from,
        Message::TO => $to,
        Message::READ => \Carbon\Carbon::now(),
        Message::TEXT => $faker->sentence,
    ];
});
