<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bugs extends Model
{
    //id, pagina, descricao, data_resolucao, autor
    protected $fillable = ['pagina', 'tipo', 'descricao', 'data_resolucao', 'data_verificacao', 'autor_id'];

    public function autor(){
        return $this->belongsTo(User::class, 'autor_id');
    }

    public function messages(){
        return $this->hasMany(MessageBugs::class, 'bug_id');
    }

    public function listaTipos(){
        return ['Bug', 'Melhoria'];
    }
}
