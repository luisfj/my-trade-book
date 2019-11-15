<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class PerfilInvestidor extends Model
{
    protected $fillable = [
        'nome', 'descricao', 'ativo'
    ];

    public function selectBoxList(){
		return $this->pluck('nome', 'id')->all();
    }

    public function isAtivo(){
        if($this->ativo == 1)
            return 'Ativo';
        return "Inativo";
    }
}
