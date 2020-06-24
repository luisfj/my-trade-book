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
                $conta_padrao = $this->contaService->buscaContaPadraoDoUsuarioLogado();
                $conta_id = $conta_padrao ? $conta_padrao->id : $conta_id;
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

            return response()->json(compact(['transacao']));
        } catch (\Throwable $th) {
            $erro = null;
            $errors = $th;
            if(isset($th->errorInfo) && is_array($th->errorInfo) && count($th->errorInfo) >= 3)
                $erro = $th->errorInfo[2];
            return response()->json(['error' => ($erro ? $erro : $errors)]);
        }
    }

    public function atualizar(Request $request, int $transacao_id){
        try {
            $compoemCapAlocExt = $request->capExt;
            $dados = $request->except(['_token', '_method', 'conta_id', 'capExt']);

            DB::beginTransaction();
            $this->service->atualizaTransacao($dados, $transacao_id, $compoemCapAlocExt);
            DB::commit();

            session()->flash('success', [
                'messages' => 'Transação atualizada com sucesso!',
            ]);
            return response()->json(['success' => 'Adicionado']);
        } catch (\Throwable $th) {
            DB::rollback();
            $erro = null;
            $errors = $th;
            if(isset($th->errorInfo) && is_array($th->errorInfo) && count($th->errorInfo) >= 3)
                $erro = $th->errorInfo[2];
            return response()->json(['error' => ($erro ? $erro : $errors)]);
        }
    }

    public function salvar(Request $request){
        try {
            $compoemCapAlocExt = $request->capExt;
            $dados = $request->except(['_token', '_method', 'capExt']);
            DB::beginTransaction();
            $transacao = $this->service->adicionarTransferencia($dados, $compoemCapAlocExt);
            DB::commit();
            session()->flash('success', [
                'messages' => 'Transação adicionada com sucesso!',
            ]);

            return response()->json(['success' => 'Adicionado']);
        } catch (\Throwable $th) {
            DB::rollback();
            $erro = null;
            $errors = $th;
            if(isset($th->errorInfo) && is_array($th->errorInfo) && count($th->errorInfo) >= 3)
                $erro = $th->errorInfo[2];
            return response()->json(['error' => ($erro ? $erro : $errors)]);
        }
    }
}
