<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TopRatedWorkersResource extends JsonResource
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
            'name' => $this->name,
            'category' => $this->category->category_name,
            'rating_average' => $this->rating_average,
            'rating_count' => $this->rating_count,
            'profile_image' => $this->workerProfileImage->profile_image ?? "images/default_user_image.jpg",
        ];
    }
}
