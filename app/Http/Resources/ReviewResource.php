<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'reviews' => [
                'comment' => $this->comment,
                'rate' => $this->rate,
                'date' => $this->date,
                'user' => [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'profile_image' => optional($this->user->userProfileImage)->profile_image ?? "images/default_user_image.jpg"
                ]
            ],
        ];
    }
}
