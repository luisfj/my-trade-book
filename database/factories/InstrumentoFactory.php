<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Instrumento;
use Faker\Generator as Faker;

$factory->define(Instrumento::class, function (Faker $faker) {
    return [
        'nome' => 'Ouro',
        'sigla'=> 'xauusd'
    ];
});
