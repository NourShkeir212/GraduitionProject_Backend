<?php

namespace App\Http\Controllers\Api\v1\userControllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\SearchResource;
use App\Models\Worker;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        
        $workers = Worker::query();

        if ($request->has('name')) {
            $workers->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->has('category')) {
            $workers->category($request->category);
        }

        if ($request->has('popularity')) {
            $workers->popularity($request->popularity);
        }

        if ($request->has('rating')) {
            $workers->rating($request->rating);
        }

        if ($request->has('gender')) {
            $workers->gender($request->gender);
        }

        if ($request->has('availability')) {
            $workers->availability($request->availability);
        }

        if ($request->has('start_time') && $request->has('end_time')) {
            $workers->workingHours($request->start_time, $request->end_time);
        }

        return SearchResource::collection($workers->get());
    }
}
