<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'rate',
        'comment',
        'user_id',
        'worker_id',
        'date',
        'task_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    protected static function booted()
    {
        static::created(function ($review) {
            $review->worker->increment('rating_count');
            $average = round($review->worker->reviews->avg('rate') * 2) / 2;
            $review->worker->update(['rating_average' => $average]);
        });

        static::updated(function ($review) {
            $average = round($review->worker->reviews->avg('rate') * 2) / 2;
            $review->worker->update(['rating_average' => $average]);
        });

        static::deleted(function ($review) {
            $review->worker->decrement('rating_count');
            $average = $review->worker->reviews->count() > 0 ? round($review->worker->reviews->avg('rate') * 2) / 2 : 0;
            $review->worker->update(['rating_average' => $average]);
        });
    }
}
