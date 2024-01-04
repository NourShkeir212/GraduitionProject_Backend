<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'worker_id',
        'user_id',
    ];

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    protected static function booted()
    {
        static::created(function ($favorite) {
            $favorite->worker->increment('favorite_count');
        });

        static::deleted(function ($favorite) {
            $favorite->worker->decrement('favorite_count');
        });
    }
}
