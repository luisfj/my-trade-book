<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpcaoEnquete extends Model
{
    protected $fillable = [
        'nome','detalhamento'
    ];

    public function post(){
        return $this->belongsTo(Post::class);
    }
}
