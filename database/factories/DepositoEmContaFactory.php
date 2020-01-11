<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ContaCorretora;
use App\Models\DepositoEmConta;
use Faker\Generator as Faker;

$factory->define(DepositoEmConta::class, function (Faker $faker) {
    return [
        'data' => $faker->dateTime(),
        'valor' => 0.00,
        'conta_id' => function () {
            $conta = ContaCorretora::first();
            if(!$conta)
                $conta = factory(ContaCorretora::class)->create();
            return $conta->id;
        }
    ];
});
