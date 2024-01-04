<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class SearchResource extends JsonResource
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

            'search_result' => [
                'id' => $this->id,
                'name' => $this->name,
                'profile_image' => $this->workerProfileImage->profile_image ?? "images/default_user_image.jpg",
                'category_name' => $this->category->category_name, // assuming 'category' is a relationship on the Worker model
                'bio' => $this->bio->bio ?? "", // assuming 'bio' is a relationship on the Worker model
                'rating_average' => $this->rating_average,
                'rating_count' => $this->rating_count,
                'is_favorite' => $this->favoritedByUsers->contains('id', Auth::id()),
            ]
        ];
    }
}
