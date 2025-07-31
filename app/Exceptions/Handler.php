<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Helpers\ErrorHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{

    public function render($request, Throwable $exception)
    {
        Log::debug("CHEGOU NO HANDLER!!");
        Log::error('AAA Exceção capturada:', [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
        ]);


        if ($exception instanceof ValidationException) {
            Log::debug('ValidationException capturada no Handler.php', [
                'errors' => $exception->errors(),
            ]);

            return response()->json([
                'message' => 'Erro de validação.',
                'errors' => $exception->errors(),
            ], 422, [], JSON_UNESCAPED_UNICODE);
        }

        // Seu ErrorHandler customizado, se existir
        $custom = (new \App\Helpers\ErrorHandler())->render($request, $exception);
        if ($custom !== null) {
            return $custom;
        }

        return parent::render($request, $exception);
    }
}
