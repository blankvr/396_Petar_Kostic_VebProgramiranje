<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    public function user_following(){
        return $this->belongsTo(User::class, 'users_id');
    }
    public function user_getting_followed(){
        return $this->belongsTo(User::class, 'follows');
        
    }
}
