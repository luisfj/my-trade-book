<?php

namespace App\Models;

use App\Helpers\ValoresHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DepositoEmConta extends Model
{
    protected $fillable = [
        'tipo', 'ticket', 'data', 'codigo_transacao', 'valor', 'conta_id', 'contraparte_id'
    ];

    public function conta(){
        return $this->belongsTo(ContaCorretora::class, 'conta_id');
    }

    public function contraparte(){
        return $this->belongsTo(ContaCorretora::class, 'contraparte_id');
    }

    public function getValorFormatadoAttribute(){
        $dep = ValoresHelper::converterValorParaPadrao($this->valor);
        return $dep;
    }

    public function setValorAttribute($valor){
        $this->attributes['valor'] = ValoresHelper::converterStringParaValor($valor);
    }
}
