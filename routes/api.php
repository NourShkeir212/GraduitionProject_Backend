<?php

use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\TaskController;
use App\Http\Controllers\Api\V1\UserControllers\FavoriteController;
use App\Http\Controllers\Api\V1\UserControllers\ReviewController;
use App\Http\Controllers\Api\V1\UserControllers\SearchController;
use App\Http\Controllers\Api\V1\UserControllers\UserAuthController;
use App\Http\Controllers\Api\V1\UserControllers\UserController;
use App\Http\Controllers\Api\V1\UserControllers\UserProfileImageController;
use App\Http\Controllers\Api\V1\WorkerControllers\BioController;
use App\Http\Controllers\Api\V1\WorkerControllers\WorkerAuthController;
use App\Http\Controllers\Api\V1\WorkerControllers\WorkerController;
use App\Http\Controllers\Api\v1\WorkerControllers\WorkerProfileImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



//----------------------- User Auth -------------------------
Route::prefix('auth/user')->group(function () {
    Route::post('/register', [UserAuthController::class, 'register']);
    Route::post('/login', [UserAuthController::class, 'login']);

    Route::group(['middleware' => ['auth:sanctum:user']], function () {
        Route::get('/logout_current_session', [UserAuthController::class, 'logoutCurrentDevice']);
        Route::get('/logout_all_sessions', [UserAuthController::class, 'logoutAllDevices']);
    });
});

//----------------------- Worker Auth -------------------------
Route::prefix('auth/worker')->group(function () {
    Route::post('/register', [WorkerAuthController::class, 'register']);
    Route::post('/login', [WorkerAuthController::class, 'login']);

    Route::group(['middleware' => ['auth:sanctum:worker']], function () {
        Route::get('/logout', [WorkerAuthController::class, 'logout']);
        Route::get('/logout_current_session', [WorkerAuthController::class, 'logoutCurrentDevice']);
        Route::get('/logout_all_sessions', [WorkerAuthController::class, 'logoutAllDevices']);
    });
});

//-----------------User Protected Routes--------------------
Route::prefix('user')->group(function () {
    Route::group(['middleware' => ['auth:sanctum:user']], function () {

        //Profile
        Route::get('/get_profile', [UserController::class, 'getProfile']);
        Route::patch('/update_profile', [UserController::class, 'updateProfile']);
        Route::delete('/delete_account', [UserController::class, 'deleteUserAccount']);
        Route::patch('/change_password', [UserController::class, 'changePassword']);

        //profile_image
        Route::post('/update_profile_image', [UserProfileImageController::class, 'update']);
        Route::post('/upload_profile_image', [UserProfileImageController::class, 'store']);
        Route::delete('/delete_profile_image', [UserProfileImageController::class, 'destroy']);


        //favorites
        Route::post('/add_to_favorites', [FavoriteController::class, 'store']);
        Route::get('/get_favorites', [FavoriteController::class, 'index']);
        Route::delete('/delete_from_favorite/{id}', [FavoriteController::class, 'destroy']);
        Route::delete('/delete_all_favorites', [FavoriteController::class, 'destroyAll']);

        //reviews
        Route::get('/worker/reviews/{id}', [ReviewController::class, 'show']);
        Route::post('/upload_review', [ReviewController::class, 'store']);
        Route::delete('/delete_review/{id}', [ReviewController::class, 'destroy']);
        Route::patch('/update_review/{id}', [ReviewController::class, 'update']);

        //tasks
        Route::post('/create_task', [TaskController::class, 'store']);
        Route::get('/get_tasks', [TaskController::class, 'getUserTasks']);
        Route::delete('/delete_task/{id}', [TaskController::class, 'destroy']);
        Route::delete('/delete_completed_tasks', [TaskController::class, 'deleteCompletedTasks']);

        //category
        Route::get('/get_categories', [CategoryController::class, 'getCategory']);
        Route::get('/categories/{id}/workers', [CategoryController::class, 'getWorkersFromCategory']);
        Route::get('/popular-categories', [CategoryController::class, 'getPopularCategories']);

        //search
        Route::post('/workers/search', [Searchcontroller::class, 'search']);

        //get top-rated

        Route::get('/top-rated-workers', [WorkerController::class, 'getTopRatedWorkers']);
        //worker profile
        Route::get('/worker/get_profile/{id}', [WorkerController::class, 'getWorkerProfileForUser']);

    });
});

//----------------Worker Protected Routes------------------
Route::prefix('worker')->group(function () {
    Route::group(['middleware' => ['auth:sanctum:worker']], function () {

        //Profile
        Route::get('/get_profile', [WorkerController::class, 'getProfile']);
        Route::delete('/delete_account', [WorkerController::class, 'deleteWorkerAccount']);
        Route::patch('/update_profile', [WorkerController::class, 'updateProfile']);
        Route::patch('/change_password', [WorkerController::class, 'changePassword']);


        //profile_image
        Route::post('/update_profile_image', [WorkerProfileImageController::class, 'update']);
        Route::post('/upload_profile_image', [WorkerProfileImageController::class, 'store']);
        Route::delete('/delete_profile_image', [WorkerProfileImageController::class, 'destroy']);

        //bio
        Route::resource('bio', BioController::class)->only([
            'store',
            'update',
            'destroy'
        ]);

        //get Review
        Route::get('/reviews', [WorkerController::class, 'getReviews']);

        //get user_profile
        Route::get('/get_user_profile/{id}', [WorkerController::class, 'getUserProfile']);

        //Tasks
        Route::get('/tasks', [TaskController::class, 'getTasks']);
        Route::patch('/tasks/{task}/accept', [TaskController::class, 'acceptTask']);
        Route::patch('/tasks/{task}/decline', [TaskController::class, 'declineTask']);

    });
});




