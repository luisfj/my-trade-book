<?php

namespace App\Http\Controllers\Configuracoes;

use App\Http\Controllers\Controller;
use App\Models\PerfilInvestidor;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    private $profile_tb;
    private $perfil_investidor_tb;

    public function __construct(Profile $profile_tb, PerfilInvestidor $perfil_investidor_tb)
    {
        $this->profile_tb = $profile_tb;
        $this->perfil_investidor_tb = $perfil_investidor_tb;
    }

    public function index(){
        $profile = Auth::User()->profile;

        if(!$profile){
            $profile = $this->profile_tb->create([
                'user_id' => Auth::User()->id
            ]);
            Auth::User()->profile_id = $profile->id;
            Auth::User()->save();
        }

        $select_list_perfil = $this->perfil_investidor_tb->selectBoxList();

        return view('modulos.configuracoes.profile', compact('profile', 'select_list_perfil'));
    }

    public function update(Request $request){
        $dados = $request->all();
        array_splice($dados, 0, 2);

        Auth::User()->profile()->update($dados);

        session()->flash('success', [
            'messages' => 'Profile atualizado com sucesso!',
        ]);

        return redirect()->route('profile.index');
    }
}
