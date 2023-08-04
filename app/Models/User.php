<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $timestamps = false;
    protected $fillable = [
        'name',
        'surname',
        'gender',
        'dob',
        'email',
        'username',
        'password',
        'retype'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function followers(){
        return $this->hasMany(Follow::class, 'follows');
    }

    public function following(){
        return $this->hasMany(Follow::class, 'users_id');
    }

    public function posts(){
        return $this->hasMany(Post::class, 'users_id');
    }

    public function feedPosts(){
        return $this->hasManyThrough(Post::class, Follow::class, 'users_id', 'users_id', 'id', 'follows');
    }
}
