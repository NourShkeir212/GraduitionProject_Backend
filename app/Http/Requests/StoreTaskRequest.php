<?php
namespace App\Http\Requests;

use App\Rules\WorkerAvailability;
use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'worker_id' => ['required', 'exists:workers,id', new WorkerAvailability(request('date'), request('start_time'), request('end_time'))],
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'description' => 'required|string',
            'complete_task' => 'required|in:complete,not_complete',
        ];
    }
}


