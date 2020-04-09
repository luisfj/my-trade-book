<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Corretora;
use App\Models\Moeda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CorretoraController extends Controller
{
    private $moeda_tb;
    private $corretora_tb;

    public function __construct(Corretora $corretora_tb, Moeda $moeda_tb)
    {
        $this->moeda_tb = $moeda_tb;
        $this->corretora_tb = $corretora_tb;
    }

    public function index(){
        if(!Auth::user()->is_admin())
            throw new Exception("Sem autorização!");

        $corretoras = $this->corretora_tb->with('moeda')
            ->orderBy('nome', 'asc')->paginate(10);

        return view('modulos.admin.listaCorretoras', compact('corretoras'));
    }

    public function edit($id){
        if(!Auth::user()->is_admin())
            throw new Exception("Sem autorização!");

        $corretora = $this->corretora_tb->find($id);

        if(!$corretora){
            session()->flash('error', [
                'messages' => 'Não existe uma corretora com este id no sistema!',
            ]);

            return redirect()->route('corretora.index');
        }
        $moedas_list = $this->moeda_tb->selectBoxList();

        return response()->json(compact(['corretora', 'moedas_list']));
    }

    public function update(Request $request, $id){
        if(!Auth::user()->is_admin())
            throw new Exception("Sem autorização!");
        try{
            $corretora = $this->corretora_tb->find($id);

            if(!$corretora){
                session()->flash('error', [
                    'messages' => 'Não existe uma corretora com este id no sistema!',
                ]);

                return redirect()->back();
            }

            $dados = $request->all();
            //array_splice($dados, 0, 2);

            $corretora->update($dados);

            session()->flash('success', [
                'messages' => 'Corretora atualizada com sucesso!',
            ]);

            return redirect()->back();
        } catch (\Throwable $th) {
            session()->flash('error', [
                'messages' => $th->getMessage(),
            ]);
            return redirect()->back();
        }
    }

    public function create(Request $request){
        if(!Auth::user()->is_admin())
            throw new Exception("Sem autorização!");
        try{
            $dados = $request->all();
            //array_splice($dados, 0, 1);

            $this->corretora_tb->create($dados);

            session()->flash('success', [
                'messages' => 'Corretora criada com sucesso!',
            ]);

            return redirect()->back();
        } catch (\Throwable $th) {
            session()->flash('error', [
                'messages' => $th->getMessage(),
            ]);
            return redirect()->back();
        }
    }

    public function delete($id){
        if(!Auth::user()->is_admin())
            throw new Exception("Sem autorização!");

        $corretora = $this->corretora_tb->find($id);

        if(!$corretora){
            session()->flash('error', [
                'messages' => 'Não existe uma corretora com este id no sistema!',
            ]);

            return redirect()->route('corretora.index');
        }
        try {
            $corretora->delete();
            session()->flash('success', [
                'messages' => 'Corretora removida com sucesso!',
            ]);
        } catch (\Throwable $th) {
            session()->flash('error', [
                'messages' => $th->getMessage(),
            ]);
        }
        return redirect()->route('corretora.index');
    }

    public function buscarSelectBoxList(){
        $corretoras_list = $this->corretora_tb->selectBoxList();

        return response()->json(compact(['corretoras_list']));
    }
}
