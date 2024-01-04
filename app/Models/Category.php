<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_name',
        'description',
        'worker_id',
        'image',
    ];


    public function workers()
    {
        return $this->hasMany(Worker::class);
    }
}
