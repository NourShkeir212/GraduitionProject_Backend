<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerProfileImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_image',
        'worker_id',
    ];

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }
}
