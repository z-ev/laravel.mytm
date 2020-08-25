<?php

namespace App\Exceptions;

use Exception;

class ElasticNoWork extends Exception
{
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
