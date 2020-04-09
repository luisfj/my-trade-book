<?php

namespace App\Http\Controllers\Trade;

use App\Http\Controllers\Controller;
use App\Models\Moeda;
use App\Services\Trade\ContaCorretoraService;
use App\Services\Trade\InstrumentoService;
use App\Services\Trade\OperacoesService;
use App\Services\Trade\RegistroImportacaoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use App\Models\Operacoes;
use Illuminate\Support\Facades\Auth;

class OperacoesController extends Controller
{
    private $repository;
    private $service;
    private $moeda_tb;
    private $contaCorretoraService;
    private $instrumentoService;
    private $registroImportacaoService;

    public function __construct(Operacoes $repository, OperacoesService $service, ContaCorretoraService $contaCorretoraService,
        InstrumentoService $instrumentoService, Moeda $moeda_tb, RegistroImportacaoService $registroImportacaoService)
    {
        $this->repository = $repository;
        $this->service   = $service;
        $this->moeda_tb  = $moeda_tb;
        $this->contaCorretoraService =  $contaCorretoraService;
        $this->instrumentoService    =  $instrumentoService;
        $this->registroImportacaoService = $registroImportacaoService;
    }

    public function index(Request $request)
    {
        //dd(session()->get('filter'));
        $conta_id = $request->conta_id;
        $data_inicial = $request->data_inicial;
        $data_final = $request->data_final;
        $por_pagina = $request->por_pagina ?? 20;

        if($request->method() == 'GET'){
            $conta_id = session()->get('conta_id');
            $data_inicial = session()->get('data_inicial');
            $data_final = session()->get('data_final');
            if(!$conta_id){
                $data_inicial = date('Y-m-01');
                $data_final = date('Y-m-t');
                $conta_padrao = $this->contaCorretoraService->buscaContaPadraoDoUsuarioLogado();
                $conta_id = $conta_padrao ? $conta_padrao->id : $conta_id;
            }
        }

        $conta_lista = $this->contaCorretoraService->selectBoxList();

        //$operacoes = $this->service->getAllByUser();
        $operacoes = $this->repository->with('instrumento')->with('moeda')->with('contaCorretora')
            ->where('usuario_id', Auth::user()->id)
            ->where('conta_corretora_id', $conta_id)
            ->whereBetween('fechamento', array($data_inicial, $data_final))
            ->orderByDesc('fechamento')->get();//paginate($por_pagina);
        return view('modulos.trade.listaOperacoes', compact('operacoes','conta_lista', 'conta_id', 'data_inicial' , 'data_final', 'por_pagina'));
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
        $dados = json_decode($request->dados);
        //$error = $dados;
        //return response()->json(compact(['error']));

        try {
            DB::beginTransaction();
            $regImport = $this->registroImportacaoService->create($dados->arquivo, $dados->primeiraData,
                $dados->ultimaData, $dados->numeroOperacoes, $dados->numeroTransferencias,
                $dados->valorOperacoes, $dados->valorTransferencias, $dados->conta_id);

            $success = $this->service->importarOperacoes(
                    $dados->conta_id,
                    $dados->transferencias,
                    $dados->openTrades,
                    $dados->closedTrades,
                    $regImport
                );

            DB::commit();
            return response()->json(compact(['success']));
        } catch (\Throwable $th) {
            DB::rollback();
            $error = $th->getMessage();
            if(App::environment('local'))
                $error = $th->getLine().'-'.$th->getFile().'  /'.$th->getTraceAsString();

            return response()->json(compact(['error']));
        }
    }

    public function validarOperacoesImportar(Request $request)
    {
        $dados = json_decode($request->dados);
//        return response()->json(compact(['dados']));

        try {
            $tradesAbertos  = $this->service->validarTradesAbertos( $dados->openTrades, $dados->conta_id);
            $tradesFechados = $this->service->validarTradesFechados($dados->closedTrades, $dados->conta_id);
            $transferencias = $this->service->validarTransferencias($dados->transferencias, $dados->conta_id);

            return response()->json(compact(['tradesAbertos', 'tradesFechados', 'transferencias']));
        } catch (\Throwable $th) {
            $error = $th->getMessage();
            if(App::environment('local'))
                $error = $th->getLine().'-'.$th->getFile().'  /'.$th->getTraceAsString();

            return response()->json(compact(['error']));
        }
    }
}
