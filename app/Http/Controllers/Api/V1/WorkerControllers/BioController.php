<?php

namespace App\Http\Controllers\Api\V1\WorkerControllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\BioResource;
use App\Models\WorkerBio;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class BioController extends Controller
{
    use HttpResponses;

    public function store(Request $request)
    {
        $request->validate([
            'bio' => 'required|string|max:255'
        ]);

        try {

            $existingBio = Auth::user()->bio;

            if ($existingBio) {
                return $this->error('', 'You already have an Bio', 412);
            }

            $bio = WorkerBio::create([
                'bio' => $request->bio,
                'worker_id' => Auth::user()->id
            ]);
            return $this->success(new BioResource($bio), 'Success Create Bio');

        } catch (ValidationException $e) {

            return $this->error('', $e, $e->getCode());

        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'bio' => 'required|string|max:255'
        ]);

        $existingBio = Auth::user()->bio;
        if ($existingBio) {
            $updatedBio = $request->all();
            $existingBio->update($updatedBio);
            return $this->success($updatedBio, 'Successfully Update Bio');
        }
        return $this->error('', 'you dont have a bio to update it', 404);
    }

    public function destroy()
    {
        $bio = Auth::user()->bio;
        if ($bio) {
            $bio->delete();
            return $this->success('', 'Successfully Delete Bio');
        }
        return $this->error('', 'you dont have a bio to delete it', 204);

    }
}
