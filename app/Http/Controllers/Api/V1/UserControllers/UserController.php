<?php

namespace App\Http\Controllers\Api\V1\UserControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\WorkerResource;
use App\Models\User;
use App\Models\UserProfileImage;
use App\Models\Worker;
use App\Traits\DeleteAccountTrait;
use App\Traits\HttpResponses;
use App\Traits\ProfileImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    use HttpResponses;
    use ProfileImageTrait;
    use DeleteAccountTrait;

    public function getProfile(): UserResource
    {

        $user = Auth::user();
        $AuthUser = User::findOrFail($user->id);
        $userProfile = User::with('userProfileImage')->find($AuthUser->id);
        return new  UserResource($userProfile);
    }

    public function updateProfile(UpdateUserRequest $request, User $user): UserResource
    {
        $request->validated($request->all());
        $userId = Auth::user()->id;
        $userData = $request->except('gender');
        $user = User::findOrFail($userId);
        $user->update($userData);
        $user->save();
        return new UserResource($user);
    }

    public function deleteUserAccount(Request $request)
    {
        $request->validate([
            'password' => 'required|string'
        ]);

        $id = Auth::user()->id;
        $user = User::find(auth()->user()->id);

        if (Hash::check($request->password, $user->password)) {
            $this->deleteAccount(
                $id,
                User::findOrFail($id),
                Auth::user()->userProfileImage,
                'user_id',
            );
        } else {
            return response()->json(['message' => 'Make sure of your password'], 400);
        }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        // Get the currently authenticated user
        $user = User::find(auth()->user()->id);

        // Check if the old password matches the current password
        if (Hash::check($request->old_password, $user->password)) {
            // Update the password
            $user->password = Hash::make($request->new_password);
            $user->save();

            return response()->json(['message' => 'Password updated successfully']);
        } else {
            return response()->json(['message' => 'Make sure of your old password'], 400);
        }
    }

}




