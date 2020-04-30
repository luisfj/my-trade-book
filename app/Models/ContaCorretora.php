<?php

namespace App\Models;

use App\Helpers\ValoresHelper;
use App\Helpers\DatasHelper;
use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\Sluggable;

class ContaCorretora extends Model
{
    use Sluggable;

    protected $fillable = ['identificador','entradas','saidas','saldo','dtabertura','ativa','tipo','exibirnopainel','moeda_id','corretora_id', 'usuario_id',
            'operacoes_abertas', 'operacoes_fechadas', 'padrao', 'real_demo', 'slug'];

    public function sluggable()
    {
        return [
            'slug' => [
                'source'    => 'identificador',
                'maxLength' => 30,
                'maxLengthKeepWords' => true,
                'method'             => null,
                'separator'          => '-',
                'unique'             => true,
                'uniqueSuffix'       => null,
                'includeTrashed'     => false,
                'reserved'           => null,
                'onUpdate'           => false,
            ]
        ];
    }

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

    public function getDtaberturaFormatadoAttribute(){
        $dep = DatasHelper::formatarDataSemHoras($this->dtabertura);
        return $dep;
    }

    public function getEntradasFormatadoAttribute(){
        $dep = ValoresHelper::converterValorParaMoeda($this->entradas, $this->moeda);
        return $dep;
    }

    public function getSaidasFormatadoAttribute(){
        $dep = ValoresHelper::converterValorParaMoeda($this->saidas, $this->moeda);
        return $dep;
    }

    public function getSaldoFormatadoAttribute(){
        $dep = ValoresHelper::converterValorParaMoeda($this->saldo, $this->moeda);
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
