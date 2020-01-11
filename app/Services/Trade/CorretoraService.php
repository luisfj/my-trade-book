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
}
