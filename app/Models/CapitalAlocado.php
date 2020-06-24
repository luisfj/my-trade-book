<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\ValoresHelper;

class CapitalAlocado extends Model
{
    protected $fillable = [
        'nome', 'saldo', 'moeda_id', 'usuario_id'
    ];

    public function contasComposicao(){
        return $this->hasMany(ContaCorretora::class, 'capitalAlocado_id');
    }

    public function transferencias(){
        return $this->hasMany(DepositoEmConta::class, 'capitalAlocado_id');
    }

    public function moeda(){
        return $this->belongsTo(Moeda::class, 'moeda_id');
    }


    public function getSaldoContasComposicaoCalculadoAttribute(){
        return $this->contasComposicao()->sum('saldo');
    }

    public function getSaldoContasComposicaoCalculadoFormatadoAttribute(){
        $ret = ValoresHelper::converterValorParaPadrao($this->saldoContasComposicaoCalculado);
        return $ret;
    }

    public function getSaldoTransferenciasCalculadoAttribute(){
        return $this->transferencias()
            ->selectRaw('sum(IF(conta_id IS NULL, valor, (valor * -1))) as totalCapAl')->first()->totalCapAl;
    }

    public function getSaldoTransferenciasCalculadoFormatadoAttribute(){
        $ret = ValoresHelper::converterValorParaPadrao($this->saldoTransferenciasCalculado);
        return $ret;
    }

    public function getSaldoTotalCalculadoAttribute(){
        return $this->saldoContasComposicaoCalculado + $this->saldoTransferenciasCalculado;
    }

    public function getSaldoTotalCalculadoFormatadoAttribute(){
        $ret = ValoresHelper::converterValorParaPadrao($this->saldoTotalCalculado);
        return $ret;
    }
}
