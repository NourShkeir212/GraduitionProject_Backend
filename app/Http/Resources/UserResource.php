<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;


class UserResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            'status' => 'success',
            'message' => 'User Profile',
            'data' => [
                'id' => $this->id,
                'name' => $this->name ?? "",
                'email' => $this->email,
                'phone' => $this->phone ?? "",
                'gender' => $this->gender,
                'address' => $this->address ?? "",
                'profile_image' => $this->userProfileImage->profile_image ?? "images/default_user_image.jpg",
                'created_at' => $this->created_at->toDateTimeString(),
            ]
        ];
    }
}
