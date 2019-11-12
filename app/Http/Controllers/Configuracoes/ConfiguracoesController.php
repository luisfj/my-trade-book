<?php

namespace App\Http\Controllers\Configuracoes;

use App\Http\Controllers\Controller;
use App\Http\Requests\Configuracoes\ConfiguracoesUpdateRequest;
use App\Models\Configuracoes;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConfiguracoesController extends Controller
{
    private $configuracoes;

    public function __construct(Configuracoes $configuracoes)
    {
        $this->configuracoes = $configuracoes;
    }

    public function index(){
        $config = $this->configuracoes->where('user_id', '=', Auth::User()->id)->firstOrCreate(['user_id'=> Auth::User()->id]);

        return view('modulos.configuracoes.index', compact('config'));
    }

    public function update(ConfiguracoesUpdateRequest $request){
        //dd($request->all());
        $conf = $this->configuracoes->where('user_id', '=', Auth::User()->id);
        $conf->update(['descricao_verificar_mensagem' => $request->descricao_verificar_mensagem]);

        session()->flash('success', [
            'messages' => 'Configuração atualizada com sucesso!',
        ]);

        return redirect()->route('configuracoes.index');
    }

}
