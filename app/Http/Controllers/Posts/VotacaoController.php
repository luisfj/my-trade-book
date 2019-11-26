<?php

namespace App\Http\Controllers\Posts;

use App\Http\Controllers\Controller;
use App\Models\OpcaoEnquete;
use App\Models\VotoUser;
use Exception;
use Illuminate\Http\Request;

class VotacaoController extends Controller
{
    private $votos_tb;
    private $opcoes_tb;

    public function __construct(VotoUser $votos_tb, OpcaoEnquete $opcoes_tb)
    {
        $this->votos_tb = $votos_tb;
        $this->opcoes_tb = $opcoes_tb;
    }

    public function votarEnquete(Request $request)
    {
        $post_id = $request->idpost;
        $notificacao_id = $request->idnotificacao;
        $opcao_id = $request->idopcoes;

        try {
            $notification = $request->user()
                        ->notifications()
                        ->where('id', $notificacao_id)
                        ->first();
            if(!$notification){
                throw new Exception('Deve informar uma notificação existente!');
            }

            $request->user()->votos_computados()->where('post_id', $post_id)->delete();

            foreach($opcao_id as $opc_id){
                $request->user()->votos_computados()->create([
                    'opcao_id' => $opc_id,
                    'post_id'  => $post_id
                ]);
            }

            $notification->markAsRead();
            $success = 'Voto computado com sucesso!';

            return response()->json(compact('success'));
        } catch (\Throwable $th) {
            $error = $th->getMessage();
            return response()->json(compact('error'));
        }
    }
}
