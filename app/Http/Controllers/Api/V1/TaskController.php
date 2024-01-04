<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Http\Resources\TaskUserResource;
use App\Models\Task;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    use HttpResponses;

    public function store(StoreTaskRequest $request)
    {
        $validatedData = $request->validated();

        $validatedData['day'] = date('l', strtotime($request->date));


        $task = Task::create(
            $validatedData + [
                'user_id' => Auth::user()->id,
            ]);

        return $this->success(new TaskResource($task), 'Success');
    }

    public function destroy($id)
    {
        $task = Auth::user()->tasks()->find($id);

        if (!$task) {
            return response()->json(['error' => 'Task not found.'], 404);
        }

        if ($task->status !== 'pending') {
            return response()->json(['error' => 'You can only delete tasks that are still pending.'], 403);
        }

        $task->delete();

        return response()->json(['message' => 'Task deleted successfully.']);

    }

    public function acceptTask($id)
    {
        $task = Task::find($id);

        // Check if the task exists
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        if (auth()->user()->id != $task->worker_id) {
            return response()->json(['message' => 'You are not authorized to accept this task'], 403);
        }

        if ($task->status != 'pending') {
            return response()->json(['message' => 'Task cannot be accepted because it is not pending'], 400);
        }

        $task->update(['status' => 'accepted']);

        return response()->json(['message' => 'Task accepted']);
    }

    public function declineTask(Task $task)
    {

        if (auth()->user()->id != $task->worker_id) {
            return response()->json(['message' => 'You are not authorized to accept this task'], 403);
        }

        if ($task->status != 'pending') {
            return response()->json(['message' => 'Task cannot be declined because it is not pending'], 400);
        }

        $task->update(['status' => 'declined']);


        return response()->json(['message' => 'Task declined']);
    }

    //get tasks for worker app
    public function getTasks()
    {
        $tasks = Auth::user()->tasks;

        return $this->success(TaskResource::collection($tasks), 'Success');
    }

    // get tasks for users app
    public function getUserTasks()
    {
        $tasks = Auth::user()->tasks;

        return $this->success(TaskUserResource::collection($tasks), 'Success');
    }

    public function deleteCompletedTasks() {
        // Get the authenticated user
        $user = Auth::user();

        // Delete all the completed tasks for the authenticated user
        $deletedRows = $user->tasks()->where('complete_task', 'complete')->delete();

        // Check if any rows were deleted
        if ($deletedRows > 0) {
            // If rows were deleted, return a success response
            return response()->json(['message' => 'Tasks deleted successfully'], 200);
        } else {
            // If no rows were deleted, return an error response
            return response()->json(['message' => 'No tasks to delete'], 404);
        }
    }

}
