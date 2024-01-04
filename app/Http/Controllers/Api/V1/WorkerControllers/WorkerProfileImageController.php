<?php

namespace App\Http\Controllers\Api\v1\WorkerControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImageRequest;
use App\Models\WorkerProfileImage;
use App\Traits\HttpResponses;
use App\Traits\ProfileImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class WorkerProfileImageController extends Controller
{
    use ProfileImageTrait;
    use HttpResponses;

    public function store(ImageRequest $request)
    {


        try {

            $worker_id = Auth::user()->id;
            $existingImage = Auth::user()->workerProfileImage;


            if ($existingImage) {
                return $this->error('', 'You already have an Profile Image', 412);
            }
            $path = $this->uploadProfileImage($request, 'workersProfileImage');
            $uploadImage = WorkerProfileImage::create([
                'profile_image' => "images/" . $path,
                'worker_id' => $worker_id
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

        $exisingImage = Auth::user()->workerProfileImage;

        return $this->updateProfileImage(
            $request, //the request from the user means the image
            $exisingImage,   //get the user image
            'workersProfileImage', // save the image in this folder in DB  App/public/images/usersProfileImage/
            $exisingImage->worker_id, //send the user_id for save it in DB
            'worker_id' // the type could be worker_id or user_id in DB
        );
    }

    public function destroy()
    {
        $image = Auth::user()->workerProfileImage;

        return $this->deleteProfileImage(
            $image, ////get the worker image
            $image->worker_id, //send the worker_id,
            'worker_id'
        );
    }
}
