<?php

namespace App\Http\Controllers\Dashboards;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Trade\OperacoesService;
use App\Services\Trade\CapitalAlocadoService;

class EvolucaoPercentualCapitalController extends Controller
{
    private $operacoesService;
    private $capitalAlocadoService;

    public function __construct(OperacoesService $operacoesService, CapitalAlocadoService $capitalAlocadoService)
    {
        $this->operacoesService   = $operacoesService;
        $this->capitalAlocadoService = $capitalAlocadoService;
    }

    public function buscarEvolucaoMensalDoCapital(Request $request)
    {
        try {
            $resumoCapitais = $this->capitalAlocadoService->getSaldoAtualDeCapitalAlocado();
            $dados = $this->capitalAlocadoService->getEvolucaoDeSaldoAnualPorContaCorretoraDeCapitalAlocadoQuery()->get();
            return response()->json(compact(['dados', 'resumoCapitais']));
        } catch(\Throwable $th) {
            $error = $th->getMessage();
            return response()->json(compact(['th', 'error', 'filtros']));
        }
    }

    public function index()
    {
        return view('evolucaoPercentualCapital');
    }

    public function teste()
    {
        $resumoCapitais = $this->capitalAlocadoService->getSaldoAtualDeCapitalAlocado();

        $dados = $this->capitalAlocadoService->getEvolucaoDeSaldoAnualPorContaCorretoraDeCapitalAlocadoQuery()->get();
dd($dados);
        //return view('evolucaoPercentualCapital');
    }
}
