<?php

namespace App\Exceptions;

use Exception;

class UserNotSignUp extends Exception
{
    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request)
    {
        return response()->json([

            'errors' => [
                'code' => 422,
                'title' => 'Registration error',
                'detail' => 'You request is malformed or missing fields.',
                'meta' => json_decode($this->getMessage()),
            ]

        ], 422);
    }
}
