<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Moeda extends Model
{
    protected $fillable = [
        'nome', 'sigla'
    ];

    public function getFullNameAttribute()
    {
        return $this->nome . ' (' . $this->sigla . ')';
    }

    public function selectBoxList(){
		return $this->get()->pluck('full_name', 'id');
    }
}
