<?php

namespace App\Models;

use App\Helpers\ValoresHelper;
use Illuminate\Database\Eloquent\Model;

class ContaCorretora extends Model
{
    protected $fillable = ['identificador','entradas','saidas','saldo','dtabertura','ativa','tipo','exibirnopainel','moeda_id','corretora_id', 'usuario_id', 'operacoes_abertas', 'operacoes_fechadas'];

    public function moeda(){
        return $this->belongsTo(Moeda::class, 'moeda_id');
    }

    public function corretora(){
        return $this->belongsTo(Corretora::class, 'corretora_id');
    }

    public function usuario(){
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function transacoes(){
        return $this->hasMany(DepositoEmConta::class, 'conta_id');
    }

    public function getPluckNameAttribute()
    {
        return $this->identificador . ($this->corretora ? ' (' . $this->corretora->nome . ')' : '');
    }

    public function getEntradasFormatadoAttribute(){
        $dep = ValoresHelper::converterValorParaPadrao($this->entradas);
        return $dep;
    }

    public function getSaidasFormatadoAttribute(){
        $dep = ValoresHelper::converterValorParaPadrao($this->saidas);
        return $dep;
    }

    public function getSaldoFormatadoAttribute(){
        $dep = ValoresHelper::converterValorParaPadrao($this->saldo);
        return $dep;
    }

    public function setEntradasAttribute($valor){
        $this->attributes['entradas'] = ValoresHelper::converterStringParaValor($valor);
    }

    public function setSaidasAttribute($valor){
        $this->attributes['saidas'] = ValoresHelper::converterStringParaValor($valor);
    }

    public function setSaldoAttribute($valor){
        $this->attributes['saldo'] = ValoresHelper::converterStringParaValor($valor);
    }
}
