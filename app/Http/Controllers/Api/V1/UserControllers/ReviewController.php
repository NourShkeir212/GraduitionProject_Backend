<?php

namespace App\Http\Controllers\Api\v1\UserControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use App\Models\Worker;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{

    use HttpResponses;

    public function store(UploadReviewRequest $request)
    {
        $request->validated($request->all());

        $review = Review::create([
            'user_id' => Auth::user()->id,
            'worker_id' => $request->worker_id,
            'comment' => $request->comment,
            'rate' => $request->rate,
            'date' => $request->date,
            'task_id' => $request->task_id,
        ]);

        return $this->success(new ReviewResource($review), 'Review submitted successfully!', 201);

    }

    public function show($worker_id)
    {

        $worker = Worker::with('reviews')->find($worker_id);

        if (!$worker) {
            return response()->json(['message' => 'Worker not found'], 404);
        }

        return response()->json(
            [
                'rating_average' => $worker->rating_average,
                'data' => ReviewResource::collection($worker->reviews),
            ]
        );

    }

    public function destroy($review_id)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Use the reviews relationship to get the review with the specified id
        $review = $user->reviews()->find($review_id);

        // Check if the review exists
        if (!$review) {
            return $this->error('', 'Review not found', 404);
        }

        // Delete the review
        $review->delete();

        return $this->success('', 'Review deleted successfully');
    }

    public function update($review_id, Request $request)
    {
        $review = Auth::user()->reviews()->find($review_id);

        if (!$review) {
            return response()->json(['error' => 'Review not found.'], 404);
        }

        $request->validate([
            'rate' => 'required|numeric|min:0|max:5',
            'comment' => 'required|string',
            // Add any other fields that you want to validate
        ]);

        $review->update($request->all());

        return response()->json(['message' => 'Review updated successfully.']);
    }
}
