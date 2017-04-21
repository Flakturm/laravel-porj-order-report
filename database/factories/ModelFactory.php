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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(turnip\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(turnip\Clients::class, function (Faker\Generator $faker) {
    return [
        'route' => $faker->randomElement($array = array ('A','B','C')),
        'route_number' => $faker->unique()->numberBetween($min = 1, $max = 15000),
        'name' => $faker->name,
        'is_small' => $faker->randomElement($array = array (0, 1)),
        'invoiced_daily' => $faker->randomElement($array = array (0, 1))
    ];
});

$factory->define(turnip\Orders::class, function (Faker\Generator $faker) {
    return [
        'client_id' => function () {
            return factory(turnip\Clients::class)->create()->id;
        },
        'total' =>  $faker->numberBetween($min = 100, $max = 900),
        'ordered_date' => $faker->dateTimeBetween($startDate = "-16 days", $endDate = "17 days")->format('Y-m-d')
    ];
});

$factory->define(turnip\OrderProducts::class, function (Faker\Generator $faker) {
    $product = turnip\Products::inRandomOrder()->first();
    $quantity = $faker->numberBetween($min = 1, $max = 200);
    return [
        'order_id' => turnip\Orders::inRandomOrder()->first()->id,
        'product_id' => $product->id,
        'quantity' =>  $quantity,
        'price' => $product->price,
        'total' => $quantity * $product->price
    ];
});