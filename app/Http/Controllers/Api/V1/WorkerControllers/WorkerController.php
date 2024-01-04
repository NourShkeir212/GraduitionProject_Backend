<?php

namespace App\Http\Controllers\Api\V1\WorkerControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateWorkerRequest;
use App\Http\Resources\ReviewResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\WorkerResource;
use App\Models\User;
use App\Models\Worker;
use App\Traits\DeleteAccountTrait;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class WorkerController extends Controller
{
    use  HttpResponses;
    use DeleteAccountTrait;

    public function getProfile()
    {


        $worker = Auth::user();
        $AuthWorker = Worker::findOrFail($worker->id);
        $workerProfile = Worker::with('workerProfileImage')->find($AuthWorker->id);

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Worker profile',
                'data' => new WorkerResource($workerProfile),
            ]
        );

    }

    public function getWorkerProfileForUser($id)
    {
        $AuthWorker = Worker::findOrFail($id);
        $workerProfile = Worker::with('workerProfileImage')->find($AuthWorker->id);

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Worker profile',
                'data' => new WorkerResource($workerProfile),
            ]
        );
    }

    public function updateProfile(UpdateWorkerRequest $request)
    {
        $request->validated();
        $workerId = Auth::user()->id;
        $userData = $request->except('gender');

        $worker = Worker::findOrFail($workerId);
        $worker->update($userData);

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'data' => new WorkerResource($worker)
        ]);
    }

    public function deleteWorkerAccount()
    {
        $id = Auth::user()->id;

        $this->deleteAccount(
            $id,
            Worker::findOrFail($id),
            Auth::user()->workerProfileImage,
            'worker_id',
        );
        return $this->success('', 'Worker Account has been deleted Successfully');

    }

    public function getReviews()
    {
        $reviews = Auth::user()->reviews;
        return $this->success(ReviewResource::collection($reviews), 'Success');
    }

    public function getUserProfile($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return new UserResource($user);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        // Get the currently authenticated user
        $worker = Worker::find(auth()->user()->id);

        // Check if the old password matches the current password
        if (Hash::check($request->old_password, $worker->password)) {
            // Update the password
            $worker->password = Hash::make($request->new_password);
            $worker->save();

            return response()->json(['message' => 'Password updated successfully']);
        } else {
            return response()->json(['message' => 'Make sure of your old password'], 400);
        }
    }

}
