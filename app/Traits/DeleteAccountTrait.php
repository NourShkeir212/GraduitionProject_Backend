<?php

namespace App\Traits;

use App\Models\User;
use App\Models\UserProfileImage;
use Illuminate\Support\Facades\Auth;

trait DeleteAccountTrait
{
    use ProfileImageTrait;

    public function deleteAccount($id, $find, $profile_image,$typeOfId)
    {
        $userId = $id;

        // Revoke the Sanctum token
        $user = $find;

        $this->deleteProfileImage(
            $profile_image,
            $userId,
            $typeOfId
        );

        $user->tokens()->delete();
        // Delete the user account
        $user->delete();


        return $this->success(
            '',
            ' Account has been deleted Successfully',
        );
    }

//    public function deleteAccount($user, $profileImage, $typeofId)
//    {
//        // Revoke the Sanctum token
//        $user->tokens()->delete();
//
//        // Delete the profile image
//        $this->deleteProfileImage($profileImage,$user->id,$typeofId);
//
//        // Delete the user account
//        $user->delete();
//
//        return $this->success('',  ' Account has been deleted Successfully');
//    }

}
