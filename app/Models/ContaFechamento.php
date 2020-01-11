<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContaFechamento extends Model
{
    protected $fillable = ['nome', 'tipo', 'saldo_inicial', 'abertura', 'usuario_id'];

    public function fechamentoMes(){
        return $this->hasMany(FechamentoMes::class, 'conta_fechamento_id');
    }

    public function getSaldoAtualAttribute(){
        return $this->fechamentoMes()->sum('resultado_mes');
    }
}
