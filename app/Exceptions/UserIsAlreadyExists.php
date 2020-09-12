<?php

namespace App\Exceptions;

use Exception;

/**
 * Class UserIsAlreadyExists
 * @package App\Exceptions
 */
class UserIsAlreadyExists extends Exception
{
    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request)
    {
        return response()->json([
            'data' => [
            'errors' => [
                'code' => 422,
                'title' => 'The user can\'t be created',
                'detail' => "The user with this email is already exists",
                ]
            ]
        ], 422);
    }
}
