<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instrumento extends Model
{
    protected $fillable = [
        'nome', 'sigla'
    ];

    public function getShowNameAttribute()
    {
        return $this->nome . ' (' . $this->sigla . ')';
    }

    public function selectBoxList(){
		return $this->get()->pluck('show_name', 'id');
    }

    public function getPluckNameAttribute()
    {
        return $this->nome . ' (' . $this->sigla . ')';
    }
}
