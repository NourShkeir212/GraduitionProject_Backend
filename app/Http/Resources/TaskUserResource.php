<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'tasks' => [
                'date' => $this->date,
                'day' => $this->day,
                'start_time' => date("g:i A", strtotime($this->start_time)),
                'end_time' => date("g:i A", strtotime($this->end_time)),
                'description' => $this->description,
                'status' => $this->status,
                'complete_task' => $this->complete_task,
                'reviewed' => $this->reviews()->where('user_id', auth()->id())->exists(),
            ],
            'worker' => [
                'id' => $this->worker->id,
                'name' => $this->worker->name,
                'phone' => $this->worker->phone,
                'profile_image' => $this->worker->workerProfileImage->profile_image ?? "images/default_user_image.jpg",
                'rating_average' => $this->worker->rating_average,
                'address' => $this->worker->address ?? "",
                'category' => $this->worker->category->category_name,
               // 'is_favorite' => $this->worker->favoritedByUsers->contains('id', Auth::id())
            ]
        ];
    }
}
