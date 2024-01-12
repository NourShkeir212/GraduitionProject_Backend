<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
                'start_time' => date("g:i A", strtotime($this->start_time)),
                'end_time' => date("g:i A", strtotime($this->end_time)),
                'description' => $this->description,
                'status' => $this->status,
                'complete_task' => $this->complete_task,
                'reviewed' => $this->reviews()->where('user_id', auth()->id())->exists(),
            ],
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'phone' => $this->user->phone,
                'profile_image' => $this->user->userProfileImage->profile_image ?? "images/default_user_image.jpg",
                'address' => $this->user->address
            ]
        ];
    }
}

