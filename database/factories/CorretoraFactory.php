<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Corretora;
use App\Models\Moeda;
use Faker\Generator as Faker;

$factory->define(Corretora::class, function (Faker $faker) {
    return [
        'nome'  => 'Corretora Teste',
        'moeda_id'  => function () {
                    $moeda = Moeda::first();
                    if(!$moeda)
                        $moeda = factory(Moeda::class)->create();
                    return $moeda->id;
                }
    ];
});
