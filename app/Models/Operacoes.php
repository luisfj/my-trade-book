<?php

namespace App\Models;

use App\Helpers\DatasHelper;
use App\Helpers\ValoresHelper;
use Illuminate\Database\Eloquent\Model;

class Operacoes extends Model
{
    protected $fillable = [
        'account', 'corretoranome', 'alavancagem', 'ticket', 'abertura', 'fechamento',
        'precoentrada', 'precosaida', 'tipo', 'lotes', 'comissao', 'impostos', 'swap',
        'resultadobruto', 'resultado', 'pips', 'importacao', 'moeda_id', 'instrumento_id',
        'conta_corretora_id', 'usuario_id', 'tempo_operacao_dias', 'tempo_operacao_horas',
        'mep', 'men', 'registro_importacao_id', 'estrategia_id'];

    public function instrumento(){
        return $this->belongsTo(Instrumento::class, 'instrumento_id');
    }

    public function estrategia(){
        return $this->belongsTo(Estrategia::class, 'estrategia_id');
    }

    public function moeda(){
        return $this->belongsTo(Moeda::class, 'moeda_id');
    }

    public function contaCorretora(){
        return $this->belongsTo(ContaCorretora::class, 'conta_corretora_id');
    }

    public function registroImportacao(){
        return $this->belongsTo(RegistroImportacao::class, 'registro_importacao_id');
    }

    public function usuario(){
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function setLotesAttribute($valor){
        $this->attributes['lotes'] = ValoresHelper::converterStringParaValor($valor);
    }

    public function setPrecoentradaAttribute($valor){
        $this->attributes['precoentrada'] = ValoresHelper::converterStringParaValor($valor);
    }

    public function setPrecosaidaAttribute($valor){
        $this->attributes['precosaida'] = ValoresHelper::converterStringParaValor($valor);
    }

    public function setComissaoAttribute($valor){
        $this->attributes['comissao'] = ValoresHelper::converterStringParaValor($valor);
    }

    public function setImpostosAttribute($valor){
        $this->attributes['impostos'] = ValoresHelper::converterStringParaValor($valor);
    }

    public function setSwapAttribute($valor){
        $this->attributes['swap'] = ValoresHelper::converterStringParaValor($valor);
    }

    public function setResultadobrutoAttribute($valor){
        $this->attributes['resultadobruto'] = ValoresHelper::converterStringParaValor($valor);
    }

    public function setResultadoAttribute($valor){
        $this->attributes['resultado'] = ValoresHelper::converterStringParaValor($valor);
    }

    public function getLotesFormatadoAttribute(){
        $dep = ValoresHelper::converterValorParaPadrao($this->lotes);
        return $dep;
    }

    public function getPrecoentradaFormatadoAttribute(){
        $dep = ValoresHelper::converterValorDesconsideraCasasDecimais($this->precoentrada);
        return $dep;
    }

    public function getPrecosaidaFormatadoAttribute(){
        $dep = ValoresHelper::converterValorDesconsideraCasasDecimais($this->precosaida);
        return $dep;
    }

    public function getComissaoFormatadoAttribute(){
        $dep = ValoresHelper::converterValorParaPadrao($this->comissao);
        return $dep;
    }

    public function getImpostosFormatadoAttribute(){
        $dep = ValoresHelper::converterValorParaPadrao($this->impostos);
        return $dep;
    }

    public function getSwapFormatadoAttribute(){
        $dep = ValoresHelper::converterValorParaPadrao($this->swap);
        return $dep;
    }

    public function getResultadobrutoFormatadoAttribute(){
        $dep = ValoresHelper::converterValorParaPadrao($this->resultadobruto);
        return $dep;
    }

    public function getResultadoFormatadoAttribute(){
        $dep = ValoresHelper::converterValorParaPadrao($this->resultado);
        return $dep;
    }

    public function getAberturaFormatadoAttribute(){
        $dep = DatasHelper::formatarDataSemHoras($this->abertura);
        return $dep;
    }

    public function getFechamentoFormatadoAttribute(){
        $dep = DatasHelper::formatarDataSemHoras($this->fechamento);
        return $dep;
    }

    public function getDuracaoTradeFormatadoAttribute(){
        $dep = DatasHelper::formatarTempoDeTrade($this->tempo_operacao_dias, $this->tempo_operacao_horas);
        return $dep;
    }
}
