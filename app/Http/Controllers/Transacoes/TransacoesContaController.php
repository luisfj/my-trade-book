<?php

namespace App\Http\Controllers\Transacoes;

use App\Http\Controllers\Controller;
use App\Models\DepositoEmConta;
use App\Services\Trade\ContaCorretoraService;
use App\Services\Trade\DepositoEmContaService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Integer;

class TransacoesContaController extends Controller
{
    private $service;
    private $contaService;

    public function __construct(DepositoEmContaService $service, ContaCorretoraService $contaService)
    {
        $this->service      = $service;
        $this->contaService = $contaService;
    }

    public function index(Request $request)
    {
        //dd(session()->get('filter'));
        $conta_id = $request->conta_id;
        $data_inicial = $request->data_inicial;
        $data_final = $request->data_final;
        $por_pagina = $request->por_pagina ?? 10;

        if($request->method() == 'GET'){
            $conta_id = session()->get('conta_id');
            $data_inicial = session()->get('data_inicial');
            $data_final = session()->get('data_final');
            if(!$conta_id){
                $data_inicial = date('Y-m-01');
                $data_final = date('Y-m-t');
            }
        }

        $conta_lista = $this->contaService->selectBoxList();

        $transacoes = $this->service->getAllByContaIdAndBetweenData($conta_id, $data_inicial, $data_final);//->paginate($por_pagina);

        return view('modulos.transacoesConta.listaTransacoesConta',
            compact(['conta_lista', 'transacoes', 'conta_id', 'data_inicial' , 'data_final', 'por_pagina']));
    }

    public function remover(int $transacao, Request $request)
    {
        $conta_id = $request->conta_id;
        $data_inicial = $request->data_inicial;
        $data_final = $request->data_final;
        try {
            DB::beginTransaction();
            $this->service->removerTransacao($transacao);
            DB::commit();
            return redirect()->back()->with('success', [
                'messages' => 'Transação removida com sucesso!',
            ])->with(compact(['conta_id', 'data_inicial', 'data_final']));
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', [
                'messages' => $th->getMessage(),
            ])->with(compact(['conta_id', 'data_inicial', 'data_final']));
        }
    }

    public function editar(int $transacao_id){
        try {
            $transacao = $this->service->getById($transacao_id);
            $conta_lista = $this->contaService->selectBoxList();

            $contraparte_lista = $this->contaService->selectBoxList($transacao->conta_id);

            return view('modulos.transacoesConta.editarTransacao', compact(['conta_lista', 'contraparte_lista', 'transacao']));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', [
                'messages' => $th->getMessage(),
            ])->withInput();
        }
    }

    public function atualizar(Request $request, int $transacao_id){
        try {
            $dados = $request->except(['_token', '_method']);
            DB::beginTransaction();
            $transacao = $this->service->atualizaTransacao($dados, $transacao_id);

            $conta_id = $transacao->conta->id;
            DB::commit();
            return redirect()->route('transacoes.index')->with('success', [
                'messages' => 'Instrumento atualizado com sucesso!',
            ])->with(compact(['conta_id']));
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
            return redirect()->back()->with('error', [
                'messages' => $th->getMessage(),
            ])->withInput();
        }
    }
}
