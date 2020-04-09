<?php

namespace App\Http\Controllers\Trade;

use App\Http\Controllers\Controller;
use App\Services\Trade\RegistroImportacaoService;
use App\Services\Trade\ContaCorretoraService;
use App\Models\RegistroImportacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistroImportacaoController extends Controller
{
    private $service;
    private $contaCorretoraService;

    public function __construct(RegistroImportacaoService $service, ContaCorretoraService $contaCorretoraService)
    {
        $this->service      = $service;
        $this->contaCorretoraService        = $contaCorretoraService;
    }

    public function index(Request $request)
    {
        //dd(session()->get('filter'));
        $conta_id = $request->conta_id;
        $data_inicial = $request->data_inicial;
        $data_final = $request->data_final;
        $por_pagina = $request->por_pagina ?? 20;

        if($request->method() == 'GET'){
            $conta_id = session()->get('conta_id');
            $data_inicial = session()->get('data_inicial');
            $data_final = session()->get('data_final');
            if(!$conta_id){
                $data_inicial = date('Y-m-01');
                $data_final = date('Y-m-t');
                $conta_padrao = $this->contaCorretoraService->buscaContaPadraoDoUsuarioLogado();
                $conta_id = $conta_padrao ? $conta_padrao->id : $conta_id;
            }
        }

        $conta_lista = $this->contaCorretoraService->selectBoxList();

        $importacoes = $this->service->getEntreDataDeRegistro($conta_id, $data_inicial, $data_final);

        return view('modulos.trade.listaImportacaoDeOperacoes', compact('importacoes','conta_lista', 'conta_id', 'data_inicial' , 'data_final', 'por_pagina'));
    }

    public function delete($id){
        try {
            $this->service->delete($id);

            session()->flash('success', [
                'messages' => 'Importação removida com sucesso!',
            ]);
        } catch (\Throwable $th) {
            session()->flash('error', [
                'messages' => $th->getMessage(),
            ]);
        }
        return redirect()->route('registros.importacoes.index');
    }
}
