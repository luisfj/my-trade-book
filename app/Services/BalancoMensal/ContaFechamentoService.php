<?php

namespace App\Services\BalancoMensal;

use App\Models\ContaFechamento;
use Illuminate\Support\Facades\Auth;

class ContaFechamentoService
{
    private $repository;

    public function __construct(ContaFechamento $repository)
    {
        $this->repository       = $repository;
    }

    public function create($dados)
    {
        $dados['usuario_id'] = Auth::user()->id;

        $this->repository->create($dados);
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

    public function getAllByUser()
    {
        return $this->repository->where('usuario_id', Auth::user()->id)->get();
    }

    public function getById($id)
    {
        return $this->repository->where('usuario_id', Auth::user()->id)->findOrFail($id);
    }

    public function getByIdOrFirst($conta_id)
    {
        $conta = $this->repository->where('usuario_id', Auth::user()->id)->find($conta_id);
        if($conta)
            return $conta;
        return $this->getAllByUser()->first();
    }

    public function selectBoxList($notid = null){
        if($notid)
            return $this->repository
            ->where('usuario_id', Auth::user()->id)
            ->where('id', "<>", $notid)
            ->get()->pluck('nome', 'id');
        else
		    return $this->repository->where('usuario_id', Auth::user()->id)->get()->pluck('nome', 'id');
    }
}
