<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Cviebrock\EloquentSluggable\Sluggable;

class Corretora extends Model
{
    use Sluggable;

    protected $fillable = ['nome', 'uf', 'moeda_id', 'usuario_id', 'slug'];


    public function sluggable()
    {
        return [
            'slug' => [
                'source'    => 'nome',
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

    public function moeda(){
        return $this->belongsTo(Moeda::class, 'moeda_id');
    }

    public function selectBoxList(){
        return $this->whereNull('usuario_id')->orWhere('usuario_id', '=', Auth::user()->id)
            ->orderBy('nome')->get()->pluck('id', 'nome');
    }
}
