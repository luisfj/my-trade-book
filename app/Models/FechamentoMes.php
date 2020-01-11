<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FechamentoMes extends Model
{
    protected $fillable = [
        'mes_ano','receitas','despesas','resultado_mes','usuario_id','conta_fechamento_id',
    ];

    public function conta_fechamento(){
        return $this->belongsTo(ContaFechamento::class, 'conta_fechamento_id');
    }
}
