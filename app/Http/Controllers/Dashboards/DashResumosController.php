<?php

namespace App\Http\Controllers\Dashboards;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Moeda;
use App\Services\Trade\ContaCorretoraService;
use App\Services\Trade\InstrumentoService;
use App\Services\Trade\OperacoesService;

class DashResumosController extends Controller
{
    private $operacoesService;
    private $moeda_tb;
    private $contaCorretoraService;
    private $instrumentoService;

    public function __construct(OperacoesService $operacoesService, ContaCorretoraService $contaCorretoraService, InstrumentoService $instrumentoService, Moeda $moeda_tb)
    {
        $this->operacoesService   = $operacoesService;
        $this->moeda_tb  = $moeda_tb;
        $this->contaCorretoraService =  $contaCorretoraService;
        $this->instrumentoService    =  $instrumentoService;
    }

    public function buscarHistoricoContaCorretora(Request $request)
    {
        $filtros = $request->all();
        try {
            $contaCorretorasSelecionada = null;

            if (array_key_exists("resCorrSelecionada", $filtros)) {
                $contaCorretorasSelecionada = $filtros['resCorrSelecionada'];
            }

            $dataPrimeiroTrade = null;
            $maiorSaldoDiario  = null;
            $resultadoTotal    = null;
            $totalDepositos    = null;
            $totalSaques       = null;

            if($contaCorretorasSelecionada != null) {
                $dataPrimeiroTrade  = $this->operacoesService->getDataPrimeiraOperacao($contaCorretorasSelecionada);
                $maiorSaldoDiario   = $this->operacoesService->getMaiorSaldoDiario($contaCorretorasSelecionada);
                $resultadoTotal     = $this->operacoesService->getResultadoDaConta($contaCorretorasSelecionada);
                $totalDepositos     = $this->contaCorretoraService->getTotalDepositos($contaCorretorasSelecionada);
                $totalSaques        = $this->contaCorretoraService->getTotalSaques($contaCorretorasSelecionada);
            }

            return response()->json(compact(['dataPrimeiroTrade', 'maiorSaldoDiario', 'resultadoTotal', 'totalDepositos', 'totalSaques']));
        } catch(\Throwable $th) {
            $error = $th->getMessage();
            return response()->json(compact(['th', 'error', 'filtros']));
        }
    }

    public function buscarEstatisticasAvancadasContaCorretora(Request $request)
    {
        $filtros = $request->all();
        try {
            $contaCorretorasSelecionada = null;
            if (array_key_exists("contaCorretoraId", $filtros)) {
                $contaCorretorasSelecionada = $filtros['contaCorretoraId'];
            }

            $estatisticasConta = null;
            $melhorOperacaoValor  = null;
            $melhorOperacaoPontos  = null;
            $piorOperacaoPontos = null;
            $piorOperacaoValor = null;
            $totaisPorAtivo = null;

            if($contaCorretorasSelecionada != null) {
                $estatisticasConta      = $this->operacoesService->getDadosAvancadosConta($contaCorretorasSelecionada);
                $piorOperacaoValor      = $this->operacoesService->getPiorOperacaoValor($contaCorretorasSelecionada);
                $piorOperacaoPontos     = $this->operacoesService->getPiorOperacaoPontos($contaCorretorasSelecionada);
                $melhorOperacaoValor    = $this->operacoesService->getMelhorOperacaoValor($contaCorretorasSelecionada);
                $melhorOperacaoPontos   = $this->operacoesService->getMelhorOperacaoPontos($contaCorretorasSelecionada);
                $totaisPorAtivo         = $this->operacoesService->getDadosAvancadosContaPorAtivo($contaCorretorasSelecionada);
            }

            return response()->json(compact(['estatisticasConta', 'piorOperacaoValor', 'piorOperacaoPontos', 'melhorOperacaoValor', 'melhorOperacaoPontos', 'totaisPorAtivo']));
        } catch(\Throwable $th) {
            $error = $th->getMessage();
            return response()->json(compact(['th', 'error', 'filtros']));
        }
    }
}
