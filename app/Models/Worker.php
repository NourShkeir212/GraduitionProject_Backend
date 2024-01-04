<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Worker extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'phone',
        'gender',
        'address',
        'start_time',
        'end_time',
        'rating_average', // Added rating_average field
        'rating_count',
        'availability',
        'favorite_count',// Added rating_count field
        'category_id'
    ];

    public function workerProfileImage()
    {
        return $this->hasOne(WorkerProfileImage::class);
    }

    public function bio()
    {
        return $this->hasOne(WorkerBio::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    protected $hidden = [
        'password',
    ];


    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopePopularity($query, $popularity)
    {
        return $query->where('favorite_count', '>=', $popularity);
    }

    public function scopeRating($query, $rating)
    {
        if (isset($rating['min']) && isset($rating['max'])) {
            return $query->whereBetween('rating_average', [$rating['min'], $rating['max']]);
        } elseif (isset($rating['min'])) {
            return $query->where('rating_average', '>=', $rating['min']);
        } elseif (isset($rating['max'])) {
            return $query->where('rating_average', '<=', $rating['max']);
        }
    }


    public function scopeGender($query, $gender)
    {
        return $query->where('gender', $gender);
    }

    public function scopeAvailability($query, $availability)
    {
        return $query->where('availability', $availability);
    }

    public function scopeWorkingHours($query, $start_time, $end_time)
    {
        return $query->whereBetween('start_time', [$start_time, $end_time])
            ->whereBetween('end_time', [$start_time, $end_time]);
    }
}
