<?php

namespace App\Services\Trade;

use Illuminate\Support\Facades\Auth;

use App\Models\Estrategia;
use Exception;

class EstrategiaService
{
    private $repository;

    public function __construct(Estrategia $repository)
    {
        $this->repository = $repository;
    }

    public function create($dados)
    {
        $this->repository->create($dados);
    }

    public function update($dados, $id)
    {
        $estrategia = $this->getById($id);
        $estrategia->update($dados);
        return $estrategia;
    }

    public function delete($id)
    {
        $estrategia = $this->getById($id);
        $estrategia->delete();
    }

    public function getById($id)
    {
        $estrategia = $this->repository->where('usuario_id', Auth::user()->id)->findOrFail($id);
        return $estrategia;
    }

    public function selectBoxList(){
        return $this->repository->where('usuario_id', Auth::user()->id)->get()->pluck('nome', 'id');
    }

    public function selectBoxAtivosList(){
        return $this->repository
            ->where('ativa', true)
            ->where('usuario_id', Auth::user()->id)->get()->pluck('nome', 'id');
    }
}
