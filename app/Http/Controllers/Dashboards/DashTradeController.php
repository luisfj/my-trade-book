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
        $mesSelecionado = $request->all()['mesSelecionado'];

        $operacoes = $this->service->getByMesEAno($mesSelecionado);

        return response()->json(compact(['operacoes']));
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

            $anosOperados = $this->service->getAnosOperados();
            $ativosOperados = $this->service->getAtivosOperados();
            $corretorasOperadas = $this->contaCorretoraService->getAllByUser();//$this->service->getCorretorasOperadas();

            $operacoes = $this->service->getEvolucaoDeSaldoAnual($ativosSelecionado, $corretorasSelecionada, $anos);

            return response()->json(compact(['operacoes', 'anosOperados', 'ativosOperados', 'corretorasOperadas']));
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

            if (array_key_exists("ativosSelecionadosEvoMes", $filtros)) {
                $ativosSelecionado = $filtros['ativosSelecionadosEvoMes'];
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
        return response()->json(compact(['mesesOperados', 'ativosOperados', 'corretorasOperadas']));
    }

}
