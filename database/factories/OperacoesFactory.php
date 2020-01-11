<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ContaCorretora;
use App\Models\Instrumento;
use App\Models\Moeda;
use App\Models\Operacoes;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Operacoes::class, function (Faker $faker) {
    return [
        'account' => $faker->unique()->word,
        'corretoranome' => $faker->unique()->name,
        'abertura' => $faker->date,
        'fechamento' => $faker->date,
        'tipo' => 'buy',
        'lotes' => 0.01,
        'resultadobruto' => 0.00,
        'resultado' => 0.00,
        'moeda_id' => function () {
            $moeda = Moeda::first();
            if(!$moeda)
                $moeda = factory(Moeda::class)->create();
            return $moeda->id;
        },
        'instrumento_id' => function() {
            $instrumento = Instrumento::first();
            if(!$instrumento){
                $instrumento = factory(Instrumento::class)->create();
            }
            return $instrumento->id;
        },
        'conta_corretora_id' => function () {
            $conta = ContaCorretora::first();
            if(!$conta)
                $conta = factory(ContaCorretora::class)->create();
            return $conta->id;
        },
        'usuario_id' => function () {
            $usuario = User::where([
                            'name' => 'testeMock',
                            'email' => 'teste_mock@mockteste.com.xy'
                        ])->first();
            if(!$usuario)
                $usuario = factory(User::class)->create();
            return $usuario->id;
        },
    ];
});
