<?php

namespace App\Http\Controllers\Transacoes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Trade\CapitalAlocadoService;
use App\Services\Trade\ContaCorretoraService;
use App\Services\Trade\MoedaService;

class CapitalAlocadoController extends Controller
{
    private $service;
    private $contaCorretoraService;
    private $moedaService;

    public function __construct(CapitalAlocadoService $service, ContaCorretoraService $contaCorretoraService, MoedaService $moedaService)
    {
        $this->service      = $service;
        $this->contaCorretoraService = $contaCorretoraService;
        $this->moedaService = $moedaService;
    }

    public function index(Request $request)
    {
        $capitalAlocado_id = null;

        if($request->method() == 'POST'){
            $dados = $request->all();
            $operacao = null;

            if (array_key_exists("capitalAlocado_id", $dados))
                $capitalAlocado_id = $dados['capitalAlocado_id'];

            if (array_key_exists("operacao", $dados)) {
                $operacao = $dados['operacao'];
                try {
                    if($operacao == 'CADASTRO'){
                        if($capitalAlocado_id){
                            $this->service->update([
                                'nome' => $dados['nome'],
                                'moeda_id' => $dados['moeda_id']
                            ], $capitalAlocado_id);
                            session()->flash('success', [
                                'messages' => 'Capital alocado atualizado com sucesso!',
                            ]);
                        } else {
                            $this->service->create([
                                'nome' => $dados['nome'],
                                'moeda_id' => $dados['moeda_id']
                            ]);
                            session()->flash('success', [
                                'messages' => 'Capital alocado adicionado com sucesso!',
                            ]);
                        }
                    } else
                    if($operacao == 'REMOCAO'){
                        $this->service->delete($capitalAlocado_id);
                        session()->flash('success', [
                            'messages' => 'Capital alocado removido com sucesso!',
                        ]);
                        $capitalAlocado_id = null;
                    }
                } catch (\Throwable $th) {
                    session()->flash('error', [
                        'messages' => $th->getMessage()
                    ]);
                }
            }
        } else {
            $capitalAlocado_id = session()->get('capitalAlocado_id');
        }

        $contasCapitalAlocado = $this->service->getByUser();
        $capitalAlocadoSelecionado = ($capitalAlocado_id ? $contasCapitalAlocado->find($capitalAlocado_id) : $capitalAlocado_id);
        $contasComposicao = $capitalAlocadoSelecionado ? $capitalAlocadoSelecionado->contasComposicao : [];
        $transferencias = $capitalAlocadoSelecionado ? $capitalAlocadoSelecionado->transferencias->sortByDesc('data') : [];

        $conta_lista = $this->contaCorretoraService->selectBoxListSemCapitalAlocado(($capitalAlocadoSelecionado ? $capitalAlocadoSelecionado->moeda_id : null));
        $moedas_lista = $this->moedaService->selectBoxList();
        return view('modulos.capitalAlocado.listaCapitalAlocado',
            compact(['contasCapitalAlocado', 'capitalAlocadoSelecionado', 'contasComposicao', 'transferencias', 'conta_lista', 'moedas_lista']));
    }

    public function remover(int $transacao, Request $request)
    {
        try {
            $dados = $request->except(['_token', '_method']);

            $this->service->removerDepositoOuSaque($transacao);
            return redirect()->back()->with('success', [
                'messages' => 'Transação removida com sucesso!',
            ])->with('capitalAlocado_id', $dados['capitalAlocado_id']);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', [
                'messages' => $th->getMessage(),
            ])->with('capitalAlocado_id', $dados['capitalAlocado_id']);
        }
    }

    public function editar(int $transacao_id){
        try {
            $transacao = $this->service->getByDepositoOuSaquePeloId($transacao_id);

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
            $dados = $request->except(['_token', '_method', 'capitalAlocado_id']);

            $this->service->atualizaDepositoOuSaque($dados, $transacao_id);

            session()->flash('success', [
                'messages' => 'Transação atualizada com sucesso!',
            ]);
            return response()->json(['success' => 'Adicionado']);
        } catch (\Throwable $th) {
            $erro = null;
            $errors = $th;
            if(isset($th->errorInfo) && is_array($th->errorInfo) && count($th->errorInfo) >= 3)
                $erro = $th->errorInfo[2];
            return response()->json(['error' => ($erro ? $erro : $errors)]);
        }
    }

    public function salvar(Request $request){
        try {
            $dados = $request->except(['_token', '_method']);

            $this->service->depositarOuSacar($dados);

            session()->flash('success', [
                'messages' => 'Transação adicionada com sucesso!',
            ]);
            session()->flash('capitalAlocado_id', $dados['capitalAlocado_id']);

            return response()->json(['success' => 'Adicionado']);
        } catch (\Throwable $th) {
            $erro = null;
            $errors = $th;
            if(isset($th->errorInfo) && is_array($th->errorInfo) && count($th->errorInfo) >= 3)
                $erro = $th->errorInfo[2];
            return response()->json(['error' => $th/*($erro ? $erro : $errors)*/]);
        }
    }

    public function addConta(Request $request)
    {
        $dados = $request->except(['_token', '_method']);

        try {
            $this->service->addConta($dados);
            return redirect()->back()->with('success', [
                'messages' => 'Conta adicionada ao capital alocado com sucesso!',
            ])->with('capitalAlocado_id', $dados['capitalAlocado_id']);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', [
                'messages' => $th->getMessage(),
            ])->with('capitalAlocado_id', $dados['capitalAlocado_id']);
        }
    }

    public function removeConta(Request $request)
    {
        $dados = $request->except(['_token', '_method']);

        try {
            $this->service->removeConta($dados);
            return redirect()->back()->with('success', [
                'messages' => 'Conta removida do capital alocado com sucesso!',
            ])->with('capitalAlocado_id', $dados['capitalAlocado_id']);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', [
                'messages' => $th->getMessage(),
            ])->with('capitalAlocado_id', $dados['capitalAlocado_id']);
        }
    }
}
