<?php

namespace App\Http\Controllers\Api\V1\UserControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

class UserAuthController extends Controller
{
    use HttpResponses;



    public function register(StoreUserRequest $request)
    {
        $request->validated($request->all());

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'phone' => $request->phone,
        ]);
        return $this->success(
            [
                'user' => $user,
            ],
            'Create User Successfully',
            201
        );
    }


    public function login(LoginRequest $request)
    {
        $request->validated($request->all());

        if (!Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])) {
            return $this->error('', 'Email or Password not correct', 401);
        }
        $user = User::where('email', $request->email)->first();

        $user->tokens()->delete();

        return $this->success(
            [
                'user' => $user,
                'token' => $user->createToken('Api Token of ' . $user->name)->plainTextToken
            ]);
    }


    public function logoutCurrentDevice()
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Delete the token for the current session
        $user->currentAccessToken()->delete();

        return $this->success('', 'Successfully logged out from this device');
    }

    public function logoutAllDevices()
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Delete all tokens for the user
        $user->tokens()->delete();

        return $this->success('', 'Successfully logged out from all devices');
    }

}
