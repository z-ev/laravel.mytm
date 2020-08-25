<?php

namespace App\Exceptions;

use Exception;

class Filter extends Exception
{
    public function render($request)
    {
        return response()->json([
                                    'errors' => [
                                        'code' => 500,
                                        'title' => 'Bad filter',
                                        'detail' => 'Bad filter params',
                                        'meta' => json_decode($this->getMessage()),
                                    ]
                                ], 500);
    }
}
