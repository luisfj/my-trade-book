<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Moeda extends Model
{
    use Sluggable;

    protected $fillable = [
        'nome', 'sigla', 'sifrao', 'slug'
    ];

    public function sluggable()
    {
        return [
            'slug' => [
                'source'    => 'sigla',
                'maxLength' => 30,
                'maxLengthKeepWords' => true,
                'method'             => null,
                'separator'          => '-',
                'unique'             => true,
                'uniqueSuffix'       => null,
                'includeTrashed'     => false,
                'reserved'           => null,
                'onUpdate'           => false,
            ]
        ];
    }

    public function getFullNameAttribute()
    {
        return $this->nome . ' (' . $this->sigla . ')';
    }

    public function selectBoxList(){
		return $this->get()->pluck('full_name', 'id');
    }
}
