<?php

namespace App\Http\Resources;

use App\Models\Worker;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class FavoriteResource extends JsonResource
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
            'bio' => $this->bio->bio ?? "",
            'rating_average' => $this->rating_average,
            'rating_count' => $this->rating_count,
            'availability' => $this->availability,
            'category' => $this->category->category_name,
            'profile_image' => $this->workerProfileImage->profile_image ?? "images/default_user_image.jpg",
            'is_favorite' => $this->favoritedByUsers->contains('id', Auth::id()),
        ];
    }
}
