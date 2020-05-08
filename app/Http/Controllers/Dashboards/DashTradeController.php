<?php

namespace App\Http\Controllers\Dashboards;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Moeda;
use App\Services\Trade\ContaCorretoraService;
use App\Services\Trade\InstrumentoService;
use App\Services\Trade\OperacoesService;


class DashTradeController extends Controller
{
    private $service;
    private $moeda_tb;
    private $contaCorretoraService;
    private $instrumentoService;

    public function __construct(OperacoesService $service, ContaCorretoraService $contaCorretoraService, InstrumentoService $instrumentoService, Moeda $moeda_tb)
    {
        $this->service   = $service;
        $this->moeda_tb  = $moeda_tb;
        $this->contaCorretoraService =  $contaCorretoraService;
        $this->instrumentoService    =  $instrumentoService;
    }

    public function buscarDashTradeATrade(Request $request){
        $filtros = $request->all();
        try {
            $operacoes = [];

            $mesSelecionado = null;
            $corretorasSelecionada = null;
            $ativosSelecionado = null;

            if (array_key_exists("mesSelecionado", $filtros)) {
                $mesSelecionado = $filtros['mesSelecionado'];
            } else {
                return response()->json(compact(['operacoes']));
            }

            if (array_key_exists("ativoSelecionado", $filtros)) {
                $ativosSelecionado = $filtros['ativoSelecionado'];
            }
            if (array_key_exists("corretoraSelecionada", $filtros)) {
                $corretorasSelecionada = $filtros['corretoraSelecionada'];
            }

            $operacoes = $this->service->getByMesEAno($mesSelecionado, $ativosSelecionado, $corretorasSelecionada);

            return response()->json(compact(['operacoes']));
        } catch(\Throwable $th) {
            $error = $th->getMessage();
            return response()->json(compact(['th', 'error', 'filtros']));
        }
    }

    public function buscarDashResultadoDiasDaSemana(Request $request)
    {
        $filtros = $request->all();
        try {
            $ativosSelecionado = null;
            $corretorasSelecionada = null;
            $dataInicial = null;
            $dataFinal = null;

            if (array_key_exists("ativoSelecionadoDDS", $filtros)) {
                $ativosSelecionado = $filtros['ativoSelecionadoDDS'];
            }
            if (array_key_exists("corretoraSelecionadaDDS", $filtros)) {
                $corretorasSelecionada = $filtros['corretoraSelecionadaDDS'];
            }
            if (array_key_exists("dataInicial", $filtros)) {
                $dataInicial = $filtros['dataInicial'];
            }
            if (array_key_exists("dataFinal", $filtros)) {
                $dataFinal = $filtros['dataFinal'];
            }

            $resultado = $this->service->getResultadoDiasDaSemana($ativosSelecionado, $corretorasSelecionada, $dataInicial, $dataFinal);

            return response()->json(compact(['resultado', 'filtros']));
        } catch(\Throwable $th) {
            $error = $th->getMessage();
            return response()->json(compact(['th', 'error', 'filtros']));
        }
    }

    public function buscarDashResultadoPorSemanaDoMes(Request $request)
    {
        $filtros = $request->all();
        try {
            $ativosSelecionado = null;
            $corretorasSelecionada = null;
            $dataInicial = null;
            $dataFinal = null;

            if (array_key_exists("ativoSelecionadoSDM", $filtros)) {
                $ativosSelecionado = $filtros['ativoSelecionadoSDM'];
            }
            if (array_key_exists("corretoraSelecionadaSDM", $filtros)) {
                $corretorasSelecionada = $filtros['corretoraSelecionadaSDM'];
            }
            if (array_key_exists("dataInicial", $filtros)) {
                $dataInicial = $filtros['dataInicial'];
            }
            if (array_key_exists("dataFinal", $filtros)) {
                $dataFinal = $filtros['dataFinal'];
            }

            $resultado = $this->service->getResultadoPorSemanaDoMes($ativosSelecionado, $corretorasSelecionada, $dataInicial, $dataFinal);

            return response()->json(compact(['resultado', 'filtros']));
        } catch(\Throwable $th) {
            $error = $th->getMessage();
            return response()->json(compact(['th', 'error', 'filtros']));
        }
    }

    public function buscarDashResultadoPorHoraDoDia(Request $request)
    {
        $filtros = $request->all();
        try {
            $ativosSelecionado = null;
            $corretorasSelecionada = null;
            $dataInicial = null;
            $dataFinal = null;

            if (array_key_exists("ativoSelecionadoHDD", $filtros)) {
                $ativosSelecionado = $filtros['ativoSelecionadoHDD'];
            }
            if (array_key_exists("corretoraSelecionadaHDD", $filtros)) {
                $corretorasSelecionada = $filtros['corretoraSelecionadaHDD'];
            }
            if (array_key_exists("dataInicial", $filtros)) {
                $dataInicial = $filtros['dataInicial'];
            }
            if (array_key_exists("dataFinal", $filtros)) {
                $dataFinal = $filtros['dataFinal'];
            }

            $resultado = $this->service->getResultadoHoraDoDia($ativosSelecionado, $corretorasSelecionada, $dataInicial, $dataFinal);

            return response()->json(compact(['resultado', 'filtros']));
        } catch(\Throwable $th) {
            $error = $th->getMessage();
            return response()->json(compact(['th', 'error', 'filtros']));
        }
    }

    public function buscarDashEvolucaoAnualDoSaldo(Request $request)
    {
        $filtros = $request->all();
        try {
            $ativosSelecionado = null;
            $corretorasSelecionada = null;
            $anos = null;

            if (array_key_exists("evoAtivoSelecionado", $filtros)) {
                $ativosSelecionado = $filtros['evoAtivoSelecionado'];
            }
            if (array_key_exists("evoCorretoraSelecionada", $filtros)) {
                $corretorasSelecionada = $filtros['evoCorretoraSelecionada'];
            }
            if (array_key_exists("evoAnoSelecionado", $filtros)) {
                $anos = $filtros['evoAnoSelecionado'];
            }

            $operacoes = $this->service->getEvolucaoDeSaldoAnual($ativosSelecionado, $corretorasSelecionada, $anos);

            return response()->json(compact(['operacoes']));
        } catch(\Throwable $th) {
            $error = $th->getMessage();
            return response()->json(compact(['th', 'error', 'filtros']));
        }
    }

    public function buscarDashEvolucaoMensalDoSaldo(Request $request)
    {
        $filtros = $request->all();
        try {
            $ativosSelecionado = null;
            $corretorasSelecionada = null;
            $mesSelecionado = null;

            if (array_key_exists("ativoSelecionadoEvoMes", $filtros)) {
                $ativosSelecionado = $filtros['ativoSelecionadoEvoMes'];
            }
            if (array_key_exists("corretorasSelecionadasEvoMes", $filtros)) {
                $corretorasSelecionada = $filtros['corretorasSelecionadasEvoMes'];
            }
            if (array_key_exists("mesSelecionadoEvoMes", $filtros)) {
                $mesSelecionado = $filtros['mesSelecionadoEvoMes'];
            }

            $operacoes = [];
            if($mesSelecionado != null) {
                $operacoes = $this->service->getEvolucaoDeSaldoMensal($ativosSelecionado, $corretorasSelecionada, $mesSelecionado);
            }

            return response()->json(compact(['operacoes']));
        } catch(\Throwable $th) {
            $error = $th->getMessage();
            return response()->json(compact(['th', 'error', 'filtros']));
        }
    }

    public function buscarMesesOperados()
    {
        $mesesOperados = $this->service->getMesesOperados();
        $ativosOperados = $this->service->getAtivosOperados();
        $corretorasOperadas = $this->contaCorretoraService->getAllByUser();//$this->service->getCorretorasOperadas();
        $anosOperados = $this->service->getAnosOperados();

        return response()->json(compact(['mesesOperados', 'ativosOperados', 'corretorasOperadas', 'anosOperados']));
    }

}
