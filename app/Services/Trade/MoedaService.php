<?php

namespace App\Services\Trade;

use App\Models\Moeda;
use Exception;

class MoedaService
{
    private $repository;

    public function __construct(Moeda $repository)
    {
        $this->repository = $repository;
    }

    public function getBySiglaOrCreate($currency)
    {
        return $this->repository->firstOrCreate(
            ['sigla' => $currency],
            ['nome' => $currency]
        );
    }

    public function selectBoxList(){
        return $this->repository->selectBoxList();
    }
}
