<?php

namespace App\Services\Trade;

use App\Models\Instrumento;
use Exception;

class InstrumentoService
{
    private $repository;

    public function __construct(Instrumento $repository)
    {
        $this->repository = $repository;
    }

    public function create($dados)
    {
        $instrument = $this->getBySigla($dados['sigla']);
        if($instrument)
            return $instrument;
        $instrument = $this->repository->create($dados);
        return $instrument;
    }

    public function update($dados, $id)
    {
        $conta = $this->getById($id);
        $conta->update($dados);
    }

    public function delete($id)
    {
        $conta = $this->getById($id);
        $conta->delete();
    }

    public function selectBoxList(){
		return $this->repository->get()->pluck('pluck_name', 'id');
    }

    public function getAll()
    {
        return $this->repository->all();
    }

    public function getByNome($nome)
    {
        return $this->repository->where('nome', $nome)->first();
    }

    public function getBySigla($sigla)
    {
        return $this->repository->where('sigla', $sigla)->first();
    }

    public function getById($id)
    {
        return $this->repository->findOrFail($id);
    }

    public function getBySiglaOrCreate($instrumento)
    {
        return $this->repository->firstOrCreate(
            ['sigla' => strtoupper($instrumento)],
            ['nome' => strtoupper($instrumento)]
        );
    }

}
