<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPassword;
use Cviebrock\EloquentSluggable\Sluggable;

class User extends Authenticatable
{
    use Notifiable;
    use Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'profile_id', 'slug'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'role', 'remember_token', 'token_facebook',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sluggable()
    {
        return [
            'slug' => [
                'source'    => 'name',
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

    public function profile(){
        return $this->belongsTo(Profile::class);
    }

    public function posts(){
        return $this->hasMany(Post::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function bugs(){
        return $this->hasMany(Bugs::class,'autor_id');
    }

    public function contasCorretora(){
        return $this->hasMany(ContaCorretora::class, 'usuario_id');
    }

    public function operacoes(){
        return $this->hasMany(Operacoes::class, 'usuario_id');
    }

    public function votos_computados(){
        return $this->hasMany(VotoUser::class,'user_id');
    }

    public function is_super_admin(){
        return $this->role == 'super_admin';
    }

    public function is_admin(){
        return $this->role == 'super_admin' || $this->role == 'admin';
    }

    public function sendPasswordResetNotification($token)
{
    // Não esquece: use App\Notifications\ResetPassword;
    $this->notify(new ResetPassword($token));
}
}
