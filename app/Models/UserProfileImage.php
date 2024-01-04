<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfileImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_image',
        'alt_image',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

