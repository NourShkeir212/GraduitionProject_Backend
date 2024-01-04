<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'id' => (string)$this->id,
            'category' => [
                'name' => $this->category_name,
              //  'description' => $this->description,
                'image' => $this->image,
                'worker_count' => $this->workers_count, // Add this line
            ]
        ];
    }
}
