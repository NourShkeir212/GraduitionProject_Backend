<?php

namespace App\Traits;

use App\Http\Requests\ImageRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;
use League\CommonMark\Extension\CommonMark\Node\Inline\Image;


trait ProfileImageTrait
{
    use HttpResponses;

    public function uploadProfileImage(ImageRequest $request, $folderName)
    {
        //store image in DB FOLDERS
        $path = $request->file('profile_image')->store(
            $folderName,
            'images',
        );
        return $path;
    }






    public function deleteProfileImage($image, $id, $typeOfId)
    {
        $type_id = $id;

        if ($image && $image->$typeOfId === $type_id) {
            // Delete the image file
            $imagePath = $image->profile_image;
            $imageFullPath = public_path($imagePath);
            if (file_exists($imageFullPath)) {
                File::delete($imageFullPath);
            }

            // Delete the profile image record
            $image->delete();

            return $this->success(
                null,
                'Profile image deleted successfully',
                200
            );
        } else {
            return $this->error(
                ['profile_image' => 'Profile image not found or does not belong to this user'],
                'Profile image not found or does not belong to this user',
                204
            );
        }
    }


    public function updateProfileImage(ImageRequest $request, $exisingImage, $folderName, $id, $userType)
    {
        try {
            // Check if the user has a profile image
            if (!$exisingImage) {
                return $this->error(
                    ['profile_image' => 'User does not have a profile image'],
                    'User does not have a profile image',
                    404
                );
            }

            $userId = $id;

            //validate the new image
            $request->validated($request->profile_image);

            // Delete the old image if it exists
            $oldImagePath = $exisingImage->profile_image;
            $oldImageFullPath = public_path($oldImagePath);
            if (file_exists($oldImageFullPath)) {
                File::delete($oldImageFullPath);
            }

            // Upload the new image
            $path = $this->uploadProfileImage($request, $folderName);

            // Update the profile image record
            $exisingImage->update([
                'profile_image' => "images/" . $path,
                $userType => $userId
            ]);

            return $this->success(
                $exisingImage,
                'Profile image updated successfully',
                200
            );
        } catch (ValidationException $e) {
            return $this->error('', $e, 400);
        }
    }

}
