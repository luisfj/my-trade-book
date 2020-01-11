<?php

namespace App\Http\Controllers\BalancoMensal;

use App\Http\Controllers\Controller;
use App\Models\ContaFechamento;
use App\Services\BalancoMensal\ContaFechamentoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContaFechamentoController extends Controller
{

    private $service;

    public function __construct(ContaFechamentoService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $contas = $this->service->getAllByUser();
        return view('modulos/balancoMensal/listaContaFechamento', compact('contas'));
    }

    public function add()
    {
        return view('modulos/balancoMensal/adicionarContaFechamento');
    }

    public function edit($id){
        $conta = $this->service->getById($id);

        return view('modulos/balancoMensal/editarContaFechamento', compact('conta'));
    }

    public function update(Request $request, $id){
        try{
            $dados = $request->all();
            array_splice($dados, 0, 2);

            $this->service->update($dados, $id);

            session()->flash('success', [
                'messages' => 'Conta fechamento atualizada com sucesso!',
            ]);

            return redirect()->route('conta.fechamento.index');
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
                'messages' => 'Conta fechamento criada com sucesso!',
            ]);

            return redirect()->route('conta.fechamento.index');
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
                'messages' => 'Conta fechamento removida com sucesso!',
            ]);
        } catch (\Throwable $th) {
            session()->flash('error', [
                'messages' => $th->getMessage(),
            ]);
        }
        return redirect()->route('conta.fechamento.index');
    }
}
