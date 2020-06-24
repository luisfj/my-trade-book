<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estrategia extends Model
{
    protected $fillable = [
        'nome', 'descricao', 'ativa', 'usuario_id'
    ];
}
