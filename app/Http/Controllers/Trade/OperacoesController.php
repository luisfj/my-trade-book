<?php

namespace App\Http\Controllers\Trade;

use App\Http\Controllers\Controller;
use App\Models\Moeda;
use App\Services\Trade\ContaCorretoraService;
use App\Services\Trade\InstrumentoService;
use App\Services\Trade\OperacoesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OperacoesController extends Controller
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

    public function index()
    {
        $operacoes = $this->service->getAllByUser();

        return view('modulos.trade.listaOperacoes', compact('operacoes'));
    }

    public function add(){
        $moedas_list = $this->moeda_tb->selectBoxList();
        $contacorretora_list = $this->contaCorretoraService->selectBoxList();
        $instrumentos_list = $this->instrumentoService->selectBoxList();
        return view('modulos.trade.adicionarOperacao', compact(['moedas_list', 'contacorretora_list', 'instrumentos_list']));
    }

    public function edit($id){

        $operacao = $this->service->getById($id);

        if(!$operacao){
            session()->flash('error', [
                'messages' => 'Não existe uma operação com este id no sistema!',
            ]);

            return redirect()->route('operacao.index');
        }
        $moedas_list = $this->moeda_tb->selectBoxList();
        $contacorretora_list = $this->contaCorretoraService->selectBoxList();
        $instrumentos_list = $this->instrumentoService->selectBoxList();
        return view('modulos.trade.editarOperacao', compact(['operacao', 'moedas_list', 'contacorretora_list', 'instrumentos_list']));
    }

    public function update(Request $request, $id){
        try{
            $dados = $request->all();
            array_splice($dados, 0, 2);

            $this->service->update($dados, $id);

            session()->flash('success', [
                'messages' => 'Operação atualizada com sucesso!',
            ]);

            return redirect()->route('operacao.index');
        } catch (\Throwable $th) {
            session()->flash('error', [
                'messages' => $th->getMessage(),
            ]);
            return redirect()->route('operacao.update', $id);
        }
    }

    public function create(Request $request){
        try{
            $dados = $request->all();
            array_splice($dados, 0, 1);

            $this->service->create($dados);

            session()->flash('success', [
                'messages' => 'Operação adicionada com sucesso!',
            ]);

            return redirect()->route('operacao.index');
        } catch (\Throwable $th) {
            session()->flash('error', [
                'messages' => $th->getMessage(),
            ]);
            return redirect()->route('operacao.add');
        }
    }

    public function delete($id){

        try {
            $this->service->delete($id);
            session()->flash('success', [
                'messages' => 'Operação removida com sucesso!',
            ]);
        } catch (\Throwable $th) {
            session()->flash('error', [
                'messages' => $th->getMessage(),
            ]);
        }
        return redirect()->route('operacao.index');
    }

    public function importarOperacoesIndex()
    {
        return view('modulos.trade.importarOperacoes');
    }

    public function importarOperacoes(Request $request)
    {
        try {
            DB::beginTransaction();
            $success = $this->service->importarOperacoes(
                    $request->corretora,
                    $request->cabecalho,
                    $request->transferencias,
                    $request->openTrades,
                    $request->closedTrades
                );

            DB::commit();
            return response()->json(compact(['success']));
        } catch (\Throwable $th) {
            DB::rollback();
            $error = $th->getMessage();
            return response()->json(compact(['error']));
        }
    }
}
