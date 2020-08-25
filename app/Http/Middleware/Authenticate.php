<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Api\v1\UserController;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            $data = [
                'errors' => [
                    'code' => 403,
                    'title' => 'User not auth',
                    'detail' => 'Route only for auth users',
                ]
            ];

            return response()->json($data, 403);
        }

    }
}
