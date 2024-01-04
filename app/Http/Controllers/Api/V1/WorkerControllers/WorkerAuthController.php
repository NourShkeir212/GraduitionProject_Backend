<?php

namespace App\Http\Controllers\Api\V1\WorkerControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreWorkerRequest;
use App\Http\Resources\WorkerResource;
use App\Models\Category;
use App\Models\Worker;
use App\Traits\HttpResponses;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class WorkerAuthController extends Controller
{
    use HttpResponses;



    public function register(StoreWorkerRequest $request)
    {
        $request->validated($request->all());

        // Find the category by its name
        $category = Category::firstWhere('category_name', $request->category);

        // If the category does not exist, return an error response
        if (!$category) {
            return $this->error('', 'The provided category does not exist.', 404);
        }

        $worker = Worker::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'phone' => $request->phone,
            'category_id' => $category->id, // Associate the worker with the category
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return $this->success(
            [
                'worker' => $worker,
            ],
            'Create Worker Successfully',
            201
        );
    }




    public function login(LoginRequest $request)
    {
        $request->validated($request->all());

        $worker = Worker::where('email', $request->email)->first();

        if (!$worker || !Hash::check($request->password, $worker->password)) {
            return $this->error('', 'Invalid credentials.', 401);
        }

        return response()->json(
            [
                'status' => 'success',
                'user' => new WorkerResource($worker),
                'token' => $worker->createToken('Api Token of ' . $worker->name)->plainTextToken,
                'code' => 201
            ]
        );
    }


    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
        return $this->success('', 'Successfully Logout');
    }
}
