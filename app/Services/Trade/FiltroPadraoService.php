<?php

namespace App\Services\Trade;

use App\Models\FiltroPadrao;
use Illuminate\Support\Facades\Auth;
use Exception;

class FiltroPadraoService
{
    private $repository;

    public function __construct(FiltroPadrao $repository)
    {
        $this->repository = $repository;
    }

    public function getByTela($tela)
    {
        return $this->repository->where('tela', $tela)
                ->where('usuario_id', Auth::user()->id)->get();
    }

    public function getFiltrosDosDashboards()
    {
        return $this->repository->whereIn('tela',
            [
            'dashResultadoDiasDaSemana',
            'dashTradeATrade',
            'dashResultadoPorSemanaDoMes',
            'dashResultadoPorHoraDoDia'
            ])
                ->where('usuario_id', Auth::user()->id)->get();
    }

    public function adicionaOuAtualiza($tela, $campo, $filtro)
    {
        $filtroPadrao = $this->repository->where('tela', $tela)->where('campo', $campo)
                ->where('usuario_id', Auth::user()->id)->first();

        if($filtroPadrao){
            $filtroPadrao->filtro = $filtro ? implode(',', $filtro) : null;
            $filtroPadrao->update();
        } else {
            $filtroPadrao = new FiltroPadrao();
            $filtroPadrao->tela = $tela;
            $filtroPadrao->campo = $campo;
            $filtroPadrao->usuario_id = Auth::user()->id;
            $filtroPadrao->filtro = $filtro ? implode(',', $filtro) : null;

            $filtroPadrao->save();
        }
    }
}
