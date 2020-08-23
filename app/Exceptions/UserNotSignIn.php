<?php

namespace App\Exceptions;

use Exception;

class UserNotSignIn extends Exception
{
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