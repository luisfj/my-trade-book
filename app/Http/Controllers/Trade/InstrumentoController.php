<?php

namespace App\Http\Controllers\Trade;

use App\Http\Controllers\Controller;
use App\Services\Trade\InstrumentoService;
use Illuminate\Http\Request;

class InstrumentoController extends Controller
{
    private $service;

    public function __construct(InstrumentoService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $instrumentos = $this->service->getAll();

        return view('modulos.trade.listaInstrumentos', compact('instrumentos'));
    }

    public function add(){
        return view('modulos.trade.adicionarInstrumento');
    }

    public function edit($id){

        $instrumento = $this->service->getById($id);

        if(!$instrumento){
            session()->flash('error', [
                'messages' => 'Não existe um instrumento com este id no sistema!',
            ]);

            return redirect()->route('instrumento.index');
        }
        return view('modulos.trade.editarInstrumento', compact('instrumento'));
    }

    public function update(Request $request, $id){
        try{
            $dados = $request->all();
            array_splice($dados, 0, 2);

            $this->service->update($dados, $id);

            session()->flash('success', [
                'messages' => 'Instrumento atualizado com sucesso!',
            ]);

            return redirect()->route('instrumento.index');
        } catch (\Throwable $th) {
            session()->flash('error', [
                'messages' => $th->getMessage(),
            ]);
            return redirect()->route('instrumento.update', $id);
        }
    }

    public function create(Request $request){
        try{
            $dados = $request->all();
            array_splice($dados, 0, 1);

            $this->service->create($dados);

            session()->flash('success', [
                'messages' => 'Instrumento criado com sucesso!',
            ]);

            return redirect()->route('instrumento.index');
        } catch (\Throwable $th) {
            session()->flash('error', [
                'messages' => $th->getMessage(),
            ]);
            return redirect()->route('instrumento.add');
        }
    }

    public function delete($id){

        try {
            $this->service->delete($id);
            session()->flash('success', [
                'messages' => 'Instrumento removido com sucesso!',
            ]);
        } catch (\Throwable $th) {
            session()->flash('error', [
                'messages' => $th->getMessage(),
            ]);
        }
        return redirect()->route('instrumento.index');
    }
}
