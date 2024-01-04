<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use App\Models\Worker;
use App\Models\Task;
use Throwable;

class WorkerAvailability implements Rule
{
    public $date;
    public $start_time;
    public $end_time;

    public function __construct($date, $start_time, $end_time)
    {
        $this->date = $date;
        $this->start_time = $start_time;
        $this->end_time = $end_time;
    }

// Main function that checks if a worker is available for a task at a given time
    public function passes($attribute, $value)
    {
        $worker = Worker::find($value);
        if (!$worker) {
            throw new \Exception('Worker not found.', 404);
        }

        if (!$this->isWorkerAvailable($worker)) {
            throw new \Exception('Worker is unavailable.', 400);
        }

        if (!$this->isWithinWorkingHours($worker)) {
            throw new \Exception('The requested time is not within the worker\'s working hours.', 400);
        }

        if ($this->isOverlappingWithExistingTask($worker)) {
            throw new \Exception('The requested time overlaps with an existing task.', 400);
        }

        if ($this->isWorkerFullyBooked($worker)) {
            throw new \Exception('The worker\'s entire working time is covered by tasks.', 400);
        }

        return response()->json(['success' => 'The time slot is available.'], 200);
    }


// The helper functions remain the same


// Helper function to check if the worker is available
    private function isWorkerAvailable($worker)
    {
        return $worker->availability != 'unavailable';
    }

// Helper function to check if the requested time is within the worker's working hours
    private function isWithinWorkingHours($worker)
    {
        $requestedStartTime = Carbon::parse($this->start_time);
        $requestedEndTime = Carbon::parse($this->end_time);

        $workerStartTime = Carbon::parse($worker->start_time);
        $workerEndTime = Carbon::parse($worker->end_time);

        return $requestedStartTime->between($workerStartTime, $workerEndTime) &&
            $requestedEndTime->between($workerStartTime, $workerEndTime);
    }

// Helper function to check if the requested time overlaps with an existing task
    private function isOverlappingWithExistingTask($worker)
    {
        $overlappingTask = Task::where('worker_id', $worker->id)
            ->where('date', $this->date)
            ->where(function ($query) {
                $query->where('start_time', '<', $this->end_time)
                    ->where('end_time', '>', $this->start_time);
            })
            ->first();

        return $overlappingTask != null;
    }

// Helper function to check if the worker is fully booked
    private function isWorkerFullyBooked($worker)
    {
        $workerStartTime = Carbon::parse($worker->start_time);
        $workerEndTime = Carbon::parse($worker->end_time);
        $totalWorkingTime = $workerEndTime->diffInMinutes($workerStartTime);

        $totalTaskTime = Task::where('worker_id', $worker->id)
            ->where('date', $this->date)
            ->get()
            ->sum(function ($task) {
                $taskStartTime = Carbon::parse($task->start_time);
                $taskEndTime = Carbon::parse($task->end_time);
                return $taskEndTime->diffInMinutes($taskStartTime);
            });

        return $totalTaskTime >= $totalWorkingTime;
    }


    public function message()
    {
        return 'The selected worker is either unavailable or already assigned to another task at the same time.';
    }
}
