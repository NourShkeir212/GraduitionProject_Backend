<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BioResource extends JsonResource
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
            'bio' => $this->bio,
            'worker_id' => $this->worker_id
        ];
    }
}
