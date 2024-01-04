<?php

namespace App\Http\Controllers\Api\V1\UserControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImageRequest;
use App\Models\UserProfileImage;
use App\Traits\HttpResponses;
use App\Traits\ProfileImageTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserProfileImageController extends Controller
{
    use HttpResponses;
    use ProfileImageTrait;


    public function store(ImageRequest $request)
    {
        try {

            $user_id = Auth::user()->id;
            $existingImage = Auth::user()->userProfileImage;


            if ($existingImage) {
                return $this->error('', 'You already have an Profile Image', 412);
            }
            $path = $this->uploadProfileImage($request, 'usersProfileImage');
            $uploadImage = UserProfileImage::create([
                'profile_image' => "images/" . $path,
                'user_id' => $user_id
            ]);

            return $this->success(
                $uploadImage,
                'Success Upload Image',
                201
            );
        } catch (ValidationException $e) {
            return $this->error(
                $e->errors(),
                'Validation Error',
                422
            );
        }

    }

    public function update(ImageRequest $request)
    {

        $exisingImage = Auth::user()->userProfileImage;

        return $this->updateProfileImage(
            $request, //the request from the user means the image
            $exisingImage,   //get the user image
            'usersProfileImage', // save the image in this folder in DB  App/public/images/usersProfileImage/
            $exisingImage->user_id, //send the user_id for save it in DB
            'user_id' // the type could be user_id or user_id in DB
        );
    }

    public function destroy()
    {
        $image = Auth::user()->userProfileImage;

        return $this->deleteProfileImage(
            $image, ////get the worker image
            $image->user_id, //send the user_id,
            'user_id'
        );
    }
}
