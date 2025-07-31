<?php

namespace App\Helpers;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Throwable;
use Illuminate\Support\Facades\Log;

class ErrorHandler
{
    public function render($request, Throwable $exception)
    {

        if ($exception instanceof QueryException && Str::contains($exception->getMessage(), 'invalid input syntax for type uuid')) {
            return response()->json([
                'message' => 'ID informado é inválido.'
            ], 400, [], JSON_UNESCAPED_UNICODE);
        }

        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'message' => 'Registro não encontrado.'
            ], 404, [], JSON_UNESCAPED_UNICODE);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'message' => 'Rota não encontrada.'
            ], 404, [], JSON_UNESCAPED_UNICODE);
        }

        if ($exception instanceof ValidationException) {
            return response()->json([
                'message' => 'Erro de validação.',
                'errors' => $exception->errors(),
            ], 422, [], JSON_UNESCAPED_UNICODE);
        }

        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'message' => 'Não autenticado.'
            ], 401, [], JSON_UNESCAPED_UNICODE);
        }

        return null;
    }
}
