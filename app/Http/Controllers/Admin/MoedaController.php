<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Moeda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MoedaController extends Controller
{
    private $moeda_tb;

    public function __construct(Moeda $moeda_tb)
    {
        $this->moeda_tb = $moeda_tb;
    }

    public function index(){
        if(!Auth::user()->is_admin())
            throw new \Exception("Sem autorização!");

        $moedas = $this->moeda_tb
            ->orderBy('nome', 'asc')->paginate(10);

        return view('modulos.admin.listaMoedas', compact('moedas'));
    }

    public function edit($id){
        if(!Auth::user()->is_admin())
            throw new \Exception("Sem autorização!");

        $moeda = $this->moeda_tb->find($id);

        if(!$moeda){
            session()->flash('error', [
                'messages' => 'Não existe uma moeda com este id no sistema!',
            ]);

            return redirect()->back();
        }

        return response()->json(compact('moeda'));
    }

    public function update(Request $request, $id){
        if(!Auth::user()->is_admin())
            throw new Exception("Sem autorização!");
        try{
            $moeda = $this->moeda_tb->find($id);

            if(!$moeda){
                session()->flash('error', [
                    'messages' => 'Não existe uma moeda com este id no sistema!',
                ]);

                return redirect()->back();
            }

            $dados = $request->all();
            //array_splice($dados, 0, 2);

            $moeda->update($dados);

            session()->flash('success', [
                'messages' => 'Moeda atualizada com sucesso!',
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
            throw new \Exception("Sem autorização!");
        try{
            $dados = $request->all();
            //array_splice($dados, 0, 1);

            $this->moeda_tb->create($dados);

            session()->flash('success', [
                'messages' => 'Moeda criada com sucesso!',
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
            throw new \Exception("Sem autorização!");

        try{
            $moeda = $this->moeda_tb->find($id);

            if(!$moeda){
                session()->flash('error', [
                    'messages' => 'Não existe uma moeda com este id no sistema!',
                ]);

                return redirect()->route('moeda.index');
            }
            try {
                $moeda->delete();
                session()->flash('success', [
                    'messages' => 'Moeda removida com sucesso!',
                ]);
            } catch (\Throwable $th) {
                session()->flash('error', [
                    'messages' => $th->getMessage(),
                ]);
            }
        } catch (\Throwable $th) {
            session()->flash('error', [
                'messages' => $th->getMessage(),
            ]);
        }
        return redirect()->route('moeda.index');
    }

    public function buscarSelectBoxList(){
        $moedas_list = $this->moeda_tb->selectBoxList();

        return response()->json(compact(['moedas_list']));
    }
}
