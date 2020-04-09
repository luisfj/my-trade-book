<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Corretora extends Model
{
    protected $fillable = ['nome', 'uf', 'moeda_id', 'usuario_id'];


    public function moeda(){
        return $this->belongsTo(Moeda::class, 'moeda_id');
    }

    public function selectBoxList(){
        return $this->whereNull('usuario_id')->orWhere('usuario_id', '=', Auth::user()->id)
            ->orderBy('nome')->get()->pluck('id', 'nome');
    }
}
