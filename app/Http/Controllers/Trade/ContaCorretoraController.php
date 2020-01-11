<?php

namespace App\Http\Controllers\Trade;

use App\Http\Controllers\Controller;
use App\Models\Corretora;
use App\Models\Moeda;
use App\Services\Trade\ContaCorretoraService;
use Illuminate\Http\Request;

class ContaCorretoraController extends Controller
{
    private $service;
    private $moeda_tb;
    private $corretora_tb;

    public function __construct(ContaCorretoraService $service, Moeda $moeda_tb, Corretora $corretora_tb)
    {
        $this->service      = $service;
        $this->moeda_tb     = $moeda_tb;
        $this->corretora_tb = $corretora_tb;
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
        return view('modulos.trade.editarContaCorretora', compact(['conta', 'corretoras_list', 'moedas_list']));
    }

    public function update(Request $request, $id){
        try{
            $dados = $request->all();
            array_splice($dados, 0, 2);

            $this->service->update($dados, $id);

            session()->flash('success', [
                'messages' => 'Conta corretora atualizada com sucesso!',
            ]);

            return redirect()->route('conta.corretora.index');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', [
                'messages' => $th->getMessage(),
            ])->withInput();
        }
    }

    public function create(Request $request){
        try{
            $dados = $request->all();
            array_splice($dados, 0, 1);

            $this->service->create($dados);

            session()->flash('success', [
                'messages' => 'Conta corretora criada com sucesso!',
            ]);

            return redirect()->route('conta.corretora.index');
        } catch (\Throwable $th) {
            
            return redirect()->back()->with('error', [
                'messages' => $th->getMessage(),
            ])->withInput();
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
}
