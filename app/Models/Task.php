<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory;
 //   use SoftDeletes;

    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'description',
        'complete_task',
        'user_id',
        'worker_id',
        'status',
        'deleted_by_worker',
        'deleted_by_user',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }


    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
