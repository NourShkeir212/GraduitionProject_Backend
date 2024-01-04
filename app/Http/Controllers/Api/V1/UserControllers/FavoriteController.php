<?php

namespace App\Http\Controllers\Api\v1\UserControllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\FavoriteResource;
use App\Models\Favorite;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//class FavoriteController extends Controller
//{
//    public function store(Request $request)
//    {
//        $user_id = Auth::user()->id; // get the logged in user id
//        $worker_id = $request->worker_id; // get the worker id from the request
//
//        $workerCheck = Worker::find($worker_id);
//
//        if($workerCheck) {
//
//            // check if the user has already favorited the worker
//            $favorite = Favorite::where('user_id', $user_id)->where('worker_id', $worker_id)->first();
//
//            if ($favorite) {
//                // if the worker is already favorited, you can choose to return a message
//                return response()->json(['message' => 'This worker is already in your favorites.']);
//            } else {
//                // if not, create a new favorite
//                Favorite::create([
//                    'user_id' => $user_id,
//                    'worker_id' => $worker_id,
//                ]);
//
//                return response()->json(['message' => 'Worker added to favorites successfully.']);
//            }
//        }
//        return response()->json(['message' => 'Worker not found'], 404);
//    }
//
//    public function index()
//    {
//        $favorites = Auth::user()->favorites;
//        // return the data to your view
//        return FavoriteResource::collection($favorites);
//    }
//
//    public function destroy($id)
//    {
//        $favorite = Favorite::find($id);
//
//        if ($favorite) {
//            $favorite->delete();
//            return response()->json(['message' => 'Worker removed from favorites successfully'], 200);
//        }
//
//        return response()->json(['message' => 'Worker not found'], 404);
//    }
//}

use App\Http\Requests\FavoriteStoreRequest;

class FavoriteController extends Controller
{
    public function store(FavoriteStoreRequest $request)
    {
        $user = Auth::user();

        // check if the user has already favorite this worker
        $favorite = $user->favoriteWorkers()->where('worker_id', $request->worker_id)->first();

        if ($favorite) {
            // if the worker is already in favorite, you can choose to return a message
            return response()->json(['message' => 'This worker is already in your favorites.']);
        } else {
            // if not, create a new favorite
            $user->favoriteWorkers()->attach($request->worker_id);

            $worker = Worker::find($request->worker_id);
            $worker->increment('favorite_count');


            return response()->json(['message' => 'Worker added to favorites successfully.']);
        }
    }


    public function index()
    {
        $favoriteWorkers = Auth::user()->favoriteWorkers;
        // return the data to your view
        return FavoriteResource::collection($favoriteWorkers);
    }

    public function destroyAll()
    {
        Auth::user()->favoriteWorkers()->detach();
        return response()->json(['message' => 'Workers removed from favorites successfully']);
    }

    public function destroy($id)
    {
        $worker = Auth::user()->favoriteWorkers()->find($id);

        if (!$worker) {
            return response()->json(['message' => 'Worker not found in your favorites'], 404);
        }

        // decrement the favorite_count column of the Worker
        $workerModel = Worker::find($id);
        $workerModel->decrement('favorite_count');

        Auth::user()->favoriteWorkers()->detach($id);


        return response()->json(['message' => 'Worker removed from favorites successfully'], 200);
    }


}
