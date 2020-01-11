<?php

namespace App\Http\Controllers\BalancoMensal;

use App\Http\Controllers\Controller;
use App\Models\FechamentoMes;
use App\Services\BalancoMensal\ContaFechamentoService;
use App\Services\BalancoMensal\FechamentoMesService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FechamentoMesController extends Controller
{
    private $service;
    private $contaFechamentoService;

    public function __construct(FechamentoMesService $service, ContaFechamentoService $contaFechamentoService)
    {
        $this->service = $service;
        $this->contaFechamentoService = $contaFechamentoService;
    }

    public function add()
    {
        return view('modulos.balancoMensal.selecionarFechamentoMes');
    }

    public function show()
    {
        $contas = $this->contaFechamentoService->getAllByUser();
        return view('modulos.balancoMensal.fechamentoMes', compact('contas'));
    }

    public function select(Request $request)
    {
        $mes_ano = $request->mes_ano;
        $fechamentos_mes =  $this->service->getFechamentosDoMes($mes_ano);
        $saldo_anterior =  $this->service->getSaldoAnterior($mes_ano);
        $contas_fechamento = $this->contaFechamentoService->selectBoxList();
        return view('modulos.balancoMensal.adicionarFechamentoMes', compact(['contas_fechamento', 'mes_ano', 'fechamentos_mes', 'saldo_anterior']));
    }

    public function create(Request $request)
    {
        $mes_ano = $request->mes_ano;

        $fechamentosid  = $request->fechamentosid;
        $contasid       = $request->contasid;
        $receitas       = $request->receitas;
        $despesas       = $request->despesas;
        $resultados     = $request->resultados;

        try {
            DB::beginTransaction();

            //remove o que foi excluido
            $this->service->deleteDoMesIdDiferenteDe($mes_ano, $fechamentosid);

            foreach($contasid as $key => $n ) {
                $fechamento = $this->service->getByIdOrNull($fechamentosid[$key]);
                if($fechamento){
                    $fechamento->update([
                        'conta_fechamento_id' => $n,
                        'receitas'            => $receitas[$key],
                        'despesas'            => $despesas[$key],
                        'resultado_mes'       => $resultados[$key],
                    ]);
                } else {
                    $this->service->create([
                        'mes_ano' => $mes_ano,
                        'receitas' => $receitas[$key],
                        'despesas' => $despesas[$key],
                        'resultado_mes' => $resultados[$key],
                        'conta_fechamento_id' => $n,
                    ]);
                }
            }

            DB::commit();

            session()->flash('success', [
                'messages' => 'Fechamento adicionado com sucesso!',
            ]);

        } catch (\Throwable $th) {
            DB::rollback();
            session()->flash('error', [
                'messages' => $th->getMessage(),
            ]);
            return redirect()->back()->withInput();
        }

        return redirect()->route('conta.fechamento.index');
    }

    public function graficoFiltrado(Request $request)
    {
        $filtros = [
            'tipoSelecionado'    => $request->tipoSelecionado,
            'periodoSelecionado' => $request->periodoSelecionado,
            'dataInicial'        => $request->dataInicial,
            'dataFinal'          => $request->dataFinal
        ];
        $relatorio = $this->service->getDadosGraficoFiltrado($filtros);

        return response()->json(compact('relatorio'));
    }

    public function graficoEvolucaoSaldo(Request $request)
    {
        $filtros = [
            'tipoSelecionado'    => $request->tipoSelecionado,
            'periodoSelecionado' => $request->periodoSelecionado,
            'dataInicial'        => $request->dataInicial,
            'dataFinal'          => $request->dataFinal
        ];
        $relatorio = $this->service->getDadosGraficoEvolucaoSaldo($filtros);

        return response()->json(compact('relatorio'));
    }

    public function gridFiltrado(Request $request)
    {
        $filtros = [
            'contaSelecionado'   => $request->contaSelecionado,
            'periodoSelecionado' => $request->periodoSelecionado,
            'dataInicial'        => $request->dataInicial,
            'dataFinal'          => $request->dataFinal
        ];
        $relatorio = $this->service->getDadosGridFechamentoFiltrado($filtros);

        return response()->json(compact('relatorio'));
    }
}
