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

        return $this->success(
            [
                'user' => $user,
                'token' => $user->createToken('Api Token of ' . $user->name)->plainTextToken
            ]);
    }


    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
        return $this->success('', 'Successfully Logout');
    }
}
