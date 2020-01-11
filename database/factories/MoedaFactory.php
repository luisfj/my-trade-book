<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Moeda;
use Faker\Generator as Faker;

$factory->define(Moeda::class, function (Faker $faker) {
    return [
        'nome'  => 'DOLAR',
        'sigla' => 'USD'
    ];
});
