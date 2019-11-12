<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracoes extends Model
{

    protected $fillable = ['descricao_verificar_mensagem', 'user_id'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
