<?php

namespace App\Helpers;

use App\Models\ContaCorretora;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DatasHelper
{
    static function converterMesAnoParaData($mes_ano){
        if(!$mes_ano || !Str::contains($mes_ano, '-'))
            return $mes_ano;
        $retorno = explode('-', $mes_ano);
        if(count($retorno) > 2)
            return $mes_ano;
        return $mes_ano . '-1';
    }

    static function converterDataParaMesTracoAno_Numero($mes_ano){
        if(!$mes_ano) return $mes_ano;

        $year = date('y', strtotime($mes_ano));
        $month = date('m', strtotime($mes_ano));
        return $month . ' - ' . $year;
    }

    static function converterDataParaMesTracoAno_String($mes_ano){
        if(!$mes_ano) return $mes_ano;

        $year = date('y', strtotime($mes_ano));
        $month = date('m', strtotime($mes_ano));
        $month = DatasHelper::converterMesNumeroParaMesStringAbreviado($month);
        return $month . ' - ' . $year;
    }

    static function converterDataParaMes_Numero($mes_ano){
        if(!$mes_ano) return $mes_ano;

        $month = date('m', strtotime($mes_ano));
        return $month;
    }

    static function converterDataParaMes_String($mes_ano){
        if(!$mes_ano) return $mes_ano;

        $month = date('m', strtotime($mes_ano));
        $month = DatasHelper::converterMesNumeroParaMesStringAbreviado($month);
        return $month;
    }

    static function converterMesNumeroParaMesStringAbreviado($mes){
        if(!$mes) return $mes;

        switch ($mes) {
            case 1:
                return 'Jan';
            case 2:
                return 'Fev';
            case 3:
                return 'Mar';
            case 4:
                return 'Abr';
            case 5:
                return 'Mai';
            case 6:
                return 'Jun';
            case 7:
                return 'Jul';
            case 8:
                return 'Ago';
            case 9:
                return 'Set';
            case 10:
                return 'Out';
            case 11:
                return 'Nov';
            case 12:
                return 'Dez';

            default:
                return '';
        }
    }

    static function converterMesNumeroParaMesStringCompleto($mes){
        if(!$mes) return $mes;

        switch ($mes) {
            case 1:
                return 'Janeiro';
            case 2:
                return 'Fevereiro';
            case 3:
                return 'Março';
            case 4:
                return 'Abril';
            case 5:
                return 'Maio';
            case 6:
                return 'Junho';
            case 7:
                return 'Julho';
            case 8:
                return 'Agosto';
            case 9:
                return 'Setembro';
            case 10:
                return 'Outubro';
            case 11:
                return 'Novembro';
            case 12:
                return 'Dezembro';

            default:
                return '';
        }
    }

}
