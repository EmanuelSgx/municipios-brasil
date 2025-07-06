<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $e)
    {
        if ($request->expectsJson()) {
            $status = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
            return response()->json([
                'type' => 'about:blank',
                'title' => $e->getMessage() ?: 'Erro interno',
                'status' => $status,
                'detail' => method_exists($e, 'getMessage') ? $e->getMessage() : null,
            ], $status);
        }
        return parent::render($request, $e);
    }
}
