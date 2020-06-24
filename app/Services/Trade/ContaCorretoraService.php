<?php

namespace App\Services\Trade;

use App\Helpers\ValoresHelper;
use App\Models\ContaCorretora;
use Illuminate\Support\Facades\Auth;

class ContaCorretoraService
{
    private $repository;
    private $moedaService;
    private $corretoraService;

    public function __construct(ContaCorretora $repository, MoedaService $moedaService, CorretoraService $corretoraService)
    {
        $this->repository       = $repository;
        $this->moedaService     = $moedaService;
        $this->corretoraService = $corretoraService;
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
        return $this->repository->with('corretora')->with('moeda')->where('usuario_id', Auth::user()->id)->get();
    }

    public function getById($id)
    {
        return $this->repository->with('corretora')->with('moeda')->where('usuario_id', Auth::user()->id)->findOrFail($id);
    }

    public function buscaContaPadraoDoUsuarioLogado()
    {
        $conta = $this->repository->where('usuario_id', Auth::user()->id)->where('padrao', true)->first();
        return $conta;
    }

    public function getByIdOrFirst($conta_id)
    {
        $conta = $this->repository->where('usuario_id', Auth::user()->id)->find($conta_id);
        if($conta)
            return $conta;
        return $this->getAllByUser()->first();
    }

    public function atualizaContaCorretoraPadrao($contaCorretora)
    {
        foreach ($this->getAllByUser() as $conta) {
            $conta->update(['padrao' => $contaCorretora && $conta->id == $contaCorretora->id]);
        }
    }

    public function getTotalDepositos($contaCorretora)
    {
        $conta = $this->repository->where('usuario_id', Auth::user()->id)->find($contaCorretora);
        return $conta->transacoes()->where('tipo', 'D')->sum('valor');
    }

    public function getTotalSaques($contaCorretora)
    {
        $conta = $this->repository->where('usuario_id', Auth::user()->id)->find($contaCorretora);
        return $conta->transacoes()->where('tipo', 'S')->sum('valor');
    }

    public function getByCodigoOrCreate($codigo, $corretora, $currency)
    {
        $conta = Auth::user()->contasCorretora()->with('moeda')->with('corretora')->where('identificador', $codigo)->first();
        if(!$conta){
            $moeda_obj     = $this->moedaService->getBySiglaOrCreate($currency);
            $corretora_obj = $this->corretoraService->getByNomeOrCreate($corretora, $moeda_obj);

            $conta = Auth::user()->contasCorretora()->with('moeda')->with('corretora')
                ->firstOrCreate(
                    ['identificador' => $codigo],
                    ['moeda_id' => $moeda_obj->id, 'corretora_id' => $corretora_obj->id]
                );
        }

        return $conta;
    }

    public function atualizarSaldoContaPorTransferencia($conta, $valorTransferencia)
    {
        $valorTransferencia = ValoresHelper::converterStringParaValor($valorTransferencia);

        $entradas    = ($conta->entradas ? $conta->entradas : 0) + ($valorTransferencia > 0 ? $valorTransferencia : 0);
        $saidas      = ($conta->saidas ? $conta->saidas : 0) + ($valorTransferencia < 0 ? ($valorTransferencia) : 0);
        $saldoadd    = ($conta->saldo ? $conta->saldo : 0) + ($valorTransferencia);

        $conta->update([
            'saldo'             => $saldoadd,
            'entradas'          => $entradas,
            'saidas'            => $saidas
            ]);

    }

    public function atualizarSaldoContaPorEstornoDeTransferencia(ContaCorretora $conta, $valorTransferencia)
    {
        $valorTransferencia = ValoresHelper::converterStringParaValor($valorTransferencia);

        $entradas    = ($conta->entradas ? $conta->entradas : 0) - ($valorTransferencia > 0 ? $valorTransferencia : 0);
        $saidas      = ($conta->saidas ? $conta->saidas : 0) - ($valorTransferencia < 0 ? ($valorTransferencia) : 0);
        $saldoadd    = ($conta->saldo ? $conta->saldo : 0) - ($valorTransferencia);

        $conta->update([
            'saldo'             => $saldoadd,
            'entradas'          => $entradas,
            'saidas'            => $saidas
            ]);

    }

    public function atualizarSaldoContaPorOperacoes($conta, $valorOperacoes, $operacoesAbertas, $operacoesFechadas)
    {
        $valorOperacoes     = ValoresHelper::converterStringParaValor($valorOperacoes);
        $operacoesAbertas   = ValoresHelper::converterStringParaInteiro($operacoesAbertas);
        $operacoesFechadas  = ValoresHelper::converterStringParaInteiro($operacoesFechadas);
        $saldoadd    = ($conta->saldo ? $conta->saldo : 0) + ($valorOperacoes);
        $op_abertas  = ($conta->operacoes_abertas ? $conta->operacoes_abertas : 0) + $operacoesAbertas;
        $op_fechadas = ($conta->operacoes_fechadas ? $conta->operacoes_fechadas : 0) + $operacoesFechadas;

        $conta->update([
            'saldo'             => $saldoadd,
            'operacoes_abertas' => $op_abertas,
            'operacoes_fechadas'=> $op_fechadas
            ]);
    }

    public function selectBoxList($notid = null){
        if($notid)
            return $this->repository
            ->where('usuario_id', Auth::user()->id)
            ->where('id', "<>", $notid)
            ->get()->pluck('pluck_name', 'id');
        else
		    return $this->repository->where('usuario_id', Auth::user()->id)->get()->pluck('pluck_name', 'id');
    }

    public function buscarPorCapitalAlocadoQuery($cap_ids){
        return $this->repository
            ->where('usuario_id', Auth::user()->id)
            ->whereIn('capitalAlocado_id', $cap_ids);
    }

    public function selectBoxListSemCapitalAlocado($moeda_id){
        return $this->repository
            ->where('usuario_id', Auth::user()->id)
            ->where('moeda_id', $moeda_id)
            ->whereNull('capitalAlocado_id')
            ->get()->pluck('pluck_name', 'id');
    }
}
