<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Corretora extends Model
{
    protected $fillable = ['nome', 'uf', 'moeda_id'];


    public function moeda(){
        return $this->belongsTo(Moeda::class, 'moeda_id');
    }

    public function selectBoxList(){
		return $this->get()->pluck('nome', 'id');
    }
}
