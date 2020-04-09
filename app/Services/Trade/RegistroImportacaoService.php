<?php

namespace App\Services\Trade;

use App\Models\RegistroImportacao;
use App\Services\Trade\ContaCorretoraService;
use Exception;
use Illuminate\Support\Facades\Auth;

class RegistroImportacaoService
{
    private $repository;
    private $contaService;
    private $depositoEmContaService;

    public function __construct(RegistroImportacao $repository, ContaCorretoraService $contaService, DepositoEmContaService $depositoEmContaService)
    {
        $this->repository = $repository;
        $this->contaService = $contaService;
        $this->depositoEmContaService = $depositoEmContaService;
    }

    public function create($arquivo, $data_primeiro_registro,
            $data_ultimo_registro, $numero_operacoes, $numero_transferencias,
            $valor_operacoes, $valor_transferencias, $conta_corretora_id)
    {
        $reg = $this->repository->create([
            'usuario_id' => Auth::user()->id,
            'arquivo' => $arquivo,
            'data_primeiro_registro' => $data_primeiro_registro,
            'data_ultimo_registro' => $data_ultimo_registro,
            'numero_operacoes' => $numero_operacoes,
            'numero_transferencias' => $numero_transferencias,
            'valor_operacoes' => $valor_operacoes,
            'valor_transferencias' => $valor_transferencias,
            'conta_corretora_id' => $conta_corretora_id
        ]);
        return $reg;
    }

    public function update($dados, $id)
    {
        $importacao = $this->getById($id);
        $importacao->update($dados);
    }

    public function delete($id)
    {
        $importacao = $this->repository
                ->with('transferencias')
                ->with('contaCorretora')
            ->where('usuario_id', Auth::user()->id)->findOrFail($id);

        $conta = $this->contaService->getById($importacao->conta_corretora_id);

        if($importacao->transferencias){
            foreach ($importacao->transferencias as $key => $tranferencia) {
                $this->depositoEmContaService->removerTransacao($tranferencia->id);
            }
        }

        $conta = $this->contaService->getById($importacao->contaCorretora->id);
        $this->contaService->atualizarSaldoContaPorOperacoes($conta, ($importacao->valor_operacoes * -1), 0, $importacao->numero_operacoes);

        $importacao->delete();
    }

    public function getAll()
    {
        return $this->repository->where('usuario_id', Auth::user()->id)->all();
    }

    public function getEntreDataDeRegistro($conta_id, $inicio, $fim)
    {
        return $this->repository->with('contaCorretora')
            ->where('usuario_id', Auth::user()->id)
            ->where('conta_corretora_id', $conta_id)
            ->whereBetween('created_at', array($inicio, $fim))
            ->get();
    }

    public function getById($id)
    {
        return $this->repository->where('usuario_id', Auth::user()->id)->findOrFail($id);
    }

}
