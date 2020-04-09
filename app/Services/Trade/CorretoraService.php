<?php

namespace App\Services\Trade;

use App\Models\Corretora;
use App\Models\Moeda;
use Exception;

class CorretoraService
{
    private $repository;

    public function __construct(Corretora $repository)
    {
        $this->repository = $repository;
    }

    public function getByNomeOrCreate($corretora, $moeda)
    {
        return $this->repository->firstOrCreate(
            ['nome' => $corretora],
            ['moeda_id' => $moeda->id]
        );
    }

    public function getByNomeAndMoedaIdOrCreate($corretora_nm, $moeda, $user_id)
    {//se criar tem que ser com o id do usuario
        $corretora = $this->repository->where('nome', '=', $corretora_nm)->where('moeda_id', $moeda)->first();
        if(!$corretora){
            $corret = new Corretora();

            $corret->nome = $corretora_nm;
            $corret->moeda_id = $moeda;
            $corret->usuario_id = $user_id;
            $corret->save();
            return $corret;
        }
        if($corretora->usuario_id && $corretora->usuario_id <> $user_id){
            $corretora->usuario_id = null;
            $corretora->update();
        }
        return $corretora;
    }
}
