<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class WorkerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request, $token = '')
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email ?? "",
            'phone' => $this->phone,
            'gender' => $this->gender,
            'address' => $this->address ?? "",
            'bio' => $this->bio->bio ?? "",
            'start_time' => $this->start_time != null ? date("g:i A", strtotime($this->start_time)) : "",
            'end_time' => $this->start_time != null ? date("g:i A", strtotime($this->end_time)) : "",
            'category' => $this->category->category_name,
            'availability' => $this->availability,
            'favorite_count' => $this->favorite_count,
            'rating_average' => $this->rating_average,
            'rating_count' => $this->rating_count,
            'profile_image' => $this->workerProfileImage->profile_image ?? "images/default_user_image.jpg",
            'is_favorite' => $this->favoritedByUsers->contains('id', Auth::id()),
           /* 'tasks' => $this->tasks->map(function ($task) {
                return [
                    'date'=>$task->date,
                    'start_time' => $task->start_time,
                    'end_time' => $task->end_time,
                ];
            })->all(),*/


        ];
    }
}
