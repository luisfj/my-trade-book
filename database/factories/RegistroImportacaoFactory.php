<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\RegistroImportacao;
use App\Models\ContaCorretora;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(RegistroImportacao::class, function (Faker $faker) {
    return [
        'arquivo' => 'arquivo.pdf',
        'numero_operacoes' => 0,
        'numero_transferencias' => 0,
        'valor_operacoes' => 0,
        'valor_transferencias' => 0,
        'usuario_id'    => function () {
            $usuario = User::where([
                            'name' => 'testeMock',
                            'email' => 'teste_mock@mockteste.com.xy'
                        ])->first();
            if(!$usuario)
                $usuario = factory(User::class)->create();
            return $usuario->id;
        },
        'conta_corretora_id' => function () {
            $conta = ContaCorretora::first();
            if(!$conta)
                $conta = factory(ContaCorretora::class)->create();
            return $conta->id;
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
