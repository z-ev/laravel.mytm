<?php

namespace App\Exceptions;

use Exception;

class ElasticNoWork extends Exception
{
    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request)
    {
        return response()->json([

            'errors' => [
            'code' => 500,
            'title' => 'Elastic не работает',
            'detail' => 'Подробное сообщение',
            ]

        ], 500);
    }
}
