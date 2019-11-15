<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PerfilInvestidor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerfilInvestidorController extends Controller
{

    private $perfis_tb;

    public function __construct(PerfilInvestidor $perfis_tb)
    {
        $this->perfis_tb = $perfis_tb;
    }

    public function index(){
        if(!Auth::user()->is_admin())
            throw new Exception("Sem autorização!");

        $perfis = $this->perfis_tb
            ->orderBy('nome', 'asc')
            ->orderBy('ativo', 'asc')->paginate(10);

        return view('modulos.admin.listaPerfis', compact('perfis'));
    }

    public function add(){
        return view('modulos.admin.adicionarPerfil');
    }

    public function edit($id){
        $perfil = $this->perfis_tb->find($id);

        if(!$perfil){
            session()->flash('error', [
                'messages' => 'Não existe um perfil com este id no sistema!',
            ]);

            return redirect()->route('perfil.index');
        }

        return view('modulos.admin.editarPerfil', compact('perfil'));
    }

    public function update(Request $request, $id){
        $perfil = $this->perfis_tb->find($id);

        if(!$perfil){
            session()->flash('error', [
                'messages' => 'Não existe um perfil com este id no sistema!',
            ]);

            return redirect()->route('perfil.index');
        }

        $dados = $request->all();
        array_splice($dados, 0, 2);

        $perfil->update($dados);

        session()->flash('success', [
            'messages' => 'Perfil atualizado com sucesso!',
        ]);

        return redirect()->route('perfil.index');
    }

    public function create(Request $request){

        $dados = $request->all();
        array_splice($dados, 0, 1);

        $this->perfis_tb->create($dados);

        session()->flash('success', [
            'messages' => 'Perfil criado com sucesso!',
        ]);

        return redirect()->route('perfil.index');
    }

    public function delete($id){
        $perfil = $this->perfis_tb->find($id);

        if(!$perfil){
            session()->flash('error', [
                'messages' => 'Não existe um perfil com este id no sistema!',
            ]);

            return redirect()->route('perfil.index');
        }
        try {
            $perfil->delete();
            session()->flash('success', [
                'messages' => 'Perfil removido com sucesso!',
            ]);
        } catch (\Throwable $th) {
            session()->flash('error', [
                'messages' => $th->getMessage(),
            ]);
        }
        return redirect()->route('perfil.index');
    }
}
