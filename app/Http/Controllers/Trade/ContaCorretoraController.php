<?php

namespace App\Http\Controllers\Trade;

use App\Http\Controllers\Controller;
use App\Models\Corretora;
use App\Models\Moeda;
use App\Services\Trade\ContaCorretoraService;
use App\Services\Trade\CorretoraService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContaCorretoraController extends Controller
{
    private $service;
    private $moeda_tb;
    private $corretora_tb;
    private $corretoraService;

    public function __construct(ContaCorretoraService $service, Moeda $moeda_tb, Corretora $corretora_tb, CorretoraService $corretoraService)
    {
        $this->service      = $service;
        $this->moeda_tb     = $moeda_tb;
        $this->corretora_tb = $corretora_tb;
        $this->corretoraService = $corretoraService;
    }

    public function index()
    {
        $contas = $this->service->getAllByUser();

        return view('modulos.trade.listaContaCorretora', compact('contas'));
    }

    public function add(){
        $moedas_list = $this->moeda_tb->selectBoxList();
        $corretoras_list = $this->corretora_tb->selectBoxList();
        return view('modulos.trade.adicionarContaCorretora', compact(['corretoras_list', 'moedas_list']));
    }

    public function edit($id){

        $conta = $this->service->getById($id);

        if(!$conta){
            session()->flash('error', [
                'messages' => 'Não existe uma conta corretora com este id no sistema!',
            ]);

            return redirect()->route('conta.corretora.index');
        }
        $moedas_list = $this->moeda_tb->selectBoxList();
        $corretoras_list = $this->corretora_tb->selectBoxList();
        return response()->json(compact(['conta', 'corretoras_list', 'moedas_list']));
    }

    public function update(Request $request, $id){
        try{
            $dados = $request->except('_token');//all();

            if($dados['corretora_id'] == -1){
                $corre = $this->corretoraService->getByNomeAndMoedaIdOrCreate($dados['corretora_nm'], $dados['moeda_id'], Auth::user()->id);
                $dados['corretora_id'] = $corre->id;
            }

            $this->service->update($dados, $id);

            session()->flash('success', [
                'messages' => 'Conta corretora atualizada com sucesso!',
            ]);

            return response()->json(['success' => 'Adicionado']);
        } catch (\Throwable $th) {
            $erro = null;
            $errors = $th->getMessage();
            if(isset($th->errorInfo) && is_array($th->errorInfo) && count($th->errorInfo) >= 3)
                $erro = $th->errorInfo[2];
            return response()->json(['error' => ($erro ? $erro : $errors)]);
        }
    }

    public function create(Request $request){
        try{
            $dados = $request->all();
            array_splice($dados, 0, 1);
            if($dados['padrao'])
                $this->service->atualizaContaCorretoraPadrao(null);

            if($dados['corretora_id'] == -1){
                $corre = $this->corretoraService->getByNomeAndMoedaIdOrCreate($dados['corretora_nm'], $dados['moeda_id'], Auth::user()->id);
                $dados['corretora_id'] = $corre->id;
            }

            $this->service->create($dados);

            session()->flash('success', [
                'messages' => 'Conta corretora criada com sucesso!',
            ]);

            return response()->json(['success' => 'Adicionado']);
        } catch (\Throwable $th) {
            $erro = null;
            $errors = $th->getMessage();
            if(isset($th->errorInfo) && is_array($th->errorInfo) && count($th->errorInfo) >= 3)
                $erro = $th->errorInfo[2];
            return response()->json(['error' => ($erro ? $erro : $errors)]);
        }
    }

    public function atualizaCorretoraPadrao(Request $request, $id){
        try{
            $contaCorretora =  $this->service->getById($id);
            $this->service->atualizaContaCorretoraPadrao($contaCorretora);

            session()->flash('success', [
                'messages' => 'Conta corretora padrão alterada com sucesso!',
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

    public function delete($id){

        try {
            $this->service->delete($id);
            session()->flash('success', [
                'messages' => 'Conta corretora removida com sucesso!',
            ]);
        } catch (\Throwable $th) {
            session()->flash('error', [
                'messages' => $th->getMessage(),
            ]);
        }
        return redirect()->route('conta.corretora.index');
    }

    public function buscarContasEmCorretoraDoUsuario()
    {
        $contasEmCorretoras = $this->service->getAllByUser();

        return response()->json(compact(['contasEmCorretoras']));
    }

    public function buscarContaCorretoraPorId($id){

        $conta = $this->service->getById($id);

        if(!$conta){
            $error = 'Não existe uma conta corretora com este id no sistema!';

            response()->json(compact(['error']));
        }

        return response()->json(compact(['conta']));
    }
}
