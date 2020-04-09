<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\DatasHelper;
use App\Helpers\ValoresHelper;

class RegistroImportacao extends Model
{
    protected $fillable = [
        'arquivo', 'data_primeiro_registro', 'data_ultimo_registro', 'numero_operacoes',
        'numero_transferencias', 'valor_operacoes',
        'valor_transferencias', 'conta_corretora_id', 'usuario_id'];

        public function operacoes(){
            return $this->hasMany(Operacoes::class);
        }

        public function transferencias(){
            return $this->hasMany(DepositoEmConta::class);
        }

        public function contaCorretora(){
            return $this->belongsTo(ContaCorretora::class, 'conta_corretora_id');
        }

        public function usuario(){
            return $this->belongsTo(User::class, 'usuario_id');
        }

        public function getValorOperacoesFormatadoAttribute(){
            $dep = ValoresHelper::converterValorParaPadrao($this->valor_operacoes);
            return $dep;
        }

        public function getValorTransferenciasFormatadoAttribute(){
            $dep = ValoresHelper::converterValorParaPadrao($this->valor_transferencias);
            return $dep;
        }

        public function getDataImportacaoFormatadoAttribute(){
            $dep = DatasHelper::formatarDataComHoras($this->created_at);
            return $dep;
        }

        public function getDataPrimeiroRegistroFormatadoAttribute(){
            $dep = DatasHelper::formatarDataComHoras($this->data_primeiro_registro);
            return $dep;
        }

        public function getDataUltimoRegistroFormatadoAttribute(){
            $dep = DatasHelper::formatarDataComHoras($this->data_ultimo_registro);
            return $dep;
        }

}
