<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['user_id', 'post_id', 'title', 'body'];

    public function post(){
        return $this->belongsTo(Post::class);
    }

    public function author(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
