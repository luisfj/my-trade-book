<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ContaCorretora;
use App\Models\Corretora;
use App\Models\Moeda;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(ContaCorretora::class, function (Faker $faker) {
    return [
        'identificador' => 'ID001',
        'usuario_id'    => function () {
            $usuario = User::where([
                            'name' => 'testeMock',
                            'email' => 'teste_mock@mockteste.com.xy'
                        ])->first();
            if(!$usuario)
                $usuario = factory(User::class)->create();
            return $usuario->id;
        },
        'corretora_id' => function() {
            $corretora = Corretora::first();
            if(!$corretora)
                $corretora = factory(Corretora::class)->create();
            return $corretora->id;
        },
        'moeda_id' => function() {
            $moeda = Moeda::first();
            if(!$moeda)
                $moeda = factory(Moeda::class)->create();
            return $moeda->id;
        }
    ];
});
/*



    $factory->define(App\Post::class, function ($faker) {
        return [
            'title' => $faker->title,
            'content' => $faker->paragraph,
            'user_id' => function () {
                return factory(App\User::class)->create()->id;
            },
            'user_type' => function (array $post) {
                return App\User::find($post['user_id'])->type;
            }
        ];
    });
*/
