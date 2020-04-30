<?php

namespace App\Helpers;

use App\Models\ContaCorretora;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ValoresHelper
{
    static function converterStringParaValor($valor){
        if(!$valor || !Str::contains($valor, ','))
            return $valor;

        return str_replace (',', '.', str_replace ('.', '', $valor));
    }

    static function converterStringParaInteiro($valor){
        if(!$valor || (!Str::contains($valor, ',') && !Str::contains($valor, '.')))
            return $valor;
        $valor = ValoresHelper::converterStringParaValor($valor);
        if(Str::contains($valor, ','))
            $valor = explode(',', $valor);
        if(Str::contains($valor, '.'))
            $valor = explode('.', $valor);
        return $valor[0];
    }

    static function converterValorParaPadrao($valor){
        return number_format( $valor , 2 , ',' , '.' );
    }

    static function converterValorDesconsideraCasasDecimais($valor){
        if(!$valor)
            return '';
        return str_replace('.', ',', $valor);
    }

    static function converterValorParaMoeda($valor, $moeda){
        $formato = ($moeda && $moeda->sigla ? $moeda->sigla : 'BRL');
        $fmt = new \NumberFormatter( 'pt_BR', \NumberFormatter::CURRENCY );
        return $fmt->formatCurrency($valor, $formato);
    }
}
