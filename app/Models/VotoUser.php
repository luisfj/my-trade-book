<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VotoUser extends Model
{
    //user_id, opcao_id
    protected $fillable = ['user_id', 'opcao_id', 'post_id'];

    public function usuario(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function opcao(){
        return $this->belongsTo(OpcaoEnquete::class, 'opcao_id');
    }

    public function post(){
        return $this->belongsTo(Post::class, 'post_id');
    }

}
