<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAlbum extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'name',
        'artist',
        'mbid',
        'image',
    ];

    
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    
}
