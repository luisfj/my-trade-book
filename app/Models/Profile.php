<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'perfil_investidor_id','sexo','nome_completo','inicio_mercado','nascimento','cpf',
        'facebook','instagram','site','telefone','cidade','estado','pais','cep','sobre_mim'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
