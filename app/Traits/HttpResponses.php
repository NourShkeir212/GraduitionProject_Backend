<?php

namespace App\Traits;


use Illuminate\Http\Request;

trait HttpResponses
{

    protected function success($data, $message = null, $code = 200)
    {
        return response()->json(
            [
                'message' => $message,
                'data' => $data,
            ],
            $code);
    }

    protected function error($data, $message = null, $code)
    {
        return response()->json(
            [
                'error' => $message,
            ],
            $code);
    }
}
