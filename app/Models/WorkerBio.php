<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerBio extends Model
{
    use HasFactory;

    protected $fillable = [
        'bio',
        'worker_id'
    ];

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    protected $table = 'workers_bio';
}
