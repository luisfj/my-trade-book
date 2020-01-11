<?php

namespace App\Services\Trade;

use App\Helpers\ValoresHelper;
use App\Models\DepositoEmConta;
use Exception;
use Illuminate\Support\Facades\Auth;

class DepositoEmContaService
{
    private $repository;
    private $contaService;

    public function __construct(DepositoEmConta $repository, ContaCorretoraService $contaService)
    {
        $this->repository = $repository;
        $this->contaService = $contaService;
    }

    public function create($dados)
    {
        $this->repository->create($dados);
    }

    public function update($dados, $id)
    {
        $transacao = $this->getById($id);
        $transacao->update($dados);
        return $transacao;
    }

    public function delete($id)
    {
        $transacao = $this->getById($id);
        $transacao->delete();
    }

    public function getAllByContaId($idConta)
    {
        return $this->repository->with('conta')->with('contraparte')->where('conta_id', $idConta)->orderBy('data', 'desc')->get();
    }

    public function getAllByContaIdAndBetweenData($idConta, $dt_ini, $dt_fim)
    {
        $transacoes = $this->getAllByContaId($idConta);
        if(!$dt_ini || !$dt_fim)
            return $transacoes;
        else
            return $transacoes->whereBetween('data', [$dt_ini, $dt_fim])->all();
    }

    public function getById($id)
    {
        $dep =  $this->repository->with('conta')->with('contraparte')->findOrFail($id);
        if($dep == null || $dep->conta->usuario_id != Auth::user()->id)
            throw new Exception('Transferêcia inválida!');
        return $dep;
    }

    public function getByContaTicket($conta_id, $ticket)
    {
        $dep =  $this->repository->with('conta')->with('contraparte')
            ->where('ticket', $ticket)
            ->where('conta_id', $conta_id)->first();
        if($dep != null && $dep->conta->usuario_id != Auth::user()->id)
            throw new Exception('Transferêcia inválida!');
        return $dep;
    }

    public function adicionarSeNaoExistir($ticket, $data, $codigo, $valor, $conta_obj)
    {
        $deposito = $conta_obj->transacoes()->where('ticket', $ticket)->find();
        if(!$deposito){
            $conta_obj->transacoes()->findOrCreate(
                ['ticket' => $ticket],
                ['data' => $data, 'codigo_transacao' => $codigo, 'valor' => $valor, 'conta_id' => $conta_obj->id]
            );
            $this->contaService->atualizarSaldoContaPorTransferencia($conta_obj, $valor);
            return true;
        }
        return false;
    }

    public function removerTransacao(int $transacao_id){
        $transacao = $this->getById($transacao_id);
        $valor =  ValoresHelper::converterStringParaValor($transacao->valor);
        $this->contaService->atualizarSaldoContaPorEstornoDeTransferencia($transacao->conta, $valor );
        $transacao->delete();
    }

    public function atualizaTransacao($dados, $id)
    {
        $transacao = $this->getById($id);
        $ticket_original = $transacao->ticket;
        $contraparte = $transacao->contraparte;
        $diferencaValor = ValoresHelper::converterStringParaValor($transacao->valor);
        $transacao->update($dados);
        $transacao = $this->getById($id);
        $valorFinal = ValoresHelper::converterStringParaValor($transacao->valor);
        $diferencaValor = $valorFinal - $diferencaValor;

        if(($contraparte && !$transacao->contraparte)
        || ($contraparte && $contraparte != $transacao->contraparte)){//se tinha e nao tem mais, removo a transação de contraparte
            $dep_contraparte = $this->getByContaTicket($contraparte->id, $ticket_original);
            if($dep_contraparte)
                $this->removerTransacao($dep_contraparte->id);
        }


        if($transacao->contraparte){//tinha ou tem uma contraparte, crio a transação ou atualizo ela
            $trans_contraparte = $this->getByContaTicket($transacao->contraparte->id, $ticket_original);
            $valor_contraparte = $diferencaValor;
            if(!$trans_contraparte){
                $trans_contraparte = new DepositoEmConta();
                $valor_contraparte = $valorFinal;
            }

            $trans_contraparte = $this->repository->updateOrCreate(['id' => $trans_contraparte->id],
                        [
                            'tipo'             => ($transacao->tipo == 'D' ? 'S' : $transacao->tipo == 'S' ? 'D' : 'T'),
                            'ticket'           => $transacao->ticket,
                            'data'             => $transacao->data,
                            'codigo_transacao' => $transacao->codigo_transacao,
                            'valor'            => ($valorFinal * -1),
                            'conta_id'         => $transacao->contraparte->id,
                            'contraparte_id'   => $transacao->conta_id,
                        ]);
            if($valor_contraparte > 0 || $valor_contraparte < 0)
                $this->contaService->atualizarSaldoContaPorTransferencia($trans_contraparte->conta, ($valor_contraparte * -1));

        }

        if($diferencaValor > 0)
            $this->contaService->atualizarSaldoContaPorTransferencia($transacao->conta, $diferencaValor);
        if($diferencaValor < 0)
            $this->contaService->atualizarSaldoContaPorEstornoDeTransferencia($transacao->conta, $diferencaValor);

        return $transacao;
    }
}
