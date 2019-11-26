<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    protected $fillable = ['title', 'body', 'tipo', 'data_fim_enquete', 'resultado_publico', 'exibir', 'multiescolha'];


    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function author(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function opcoesEnquete(){
        return $this->hasMany(OpcaoEnquete::class);
    }

    public function votos(){
        return $this->hasMany(VotoUser::class, 'post_id');
    }

    public function votosUsuario(){
        return $this->votos()->where('user_id', Auth::user()->id);
    }
}
