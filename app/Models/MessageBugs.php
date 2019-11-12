<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageBugs extends Model
{
    //descricao, data_resolucao, bug,autor
    protected $fillable = ['descricao', 'data_resolucao', 'bug_id', 'autor_id'];

    public function autor(){
        return $this->belongsTo(User::class, 'autor_id');
    }

    public function bug(){
        return $this->belongsTo(Bugs::class, 'bug_id');
    }
}
