<?php

namespace App\Exceptions;

use Exception;

/**
 * Class UserNotSignIn
 * @package App\Exceptions
 */
class UserNotSignIn extends Exception
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
                'title' => 'SignIn error',
                'detail' => 'User name or password is wrong',
                'meta' => json_decode($this->getMessage()),
            ]
        ], 422);
    }
}
