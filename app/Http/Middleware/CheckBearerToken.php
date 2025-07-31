<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckBearerToken
{
    // Define aqui seu token fixo
    private $token = 'token-teste';

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $authHeader = $request->header('Authorization');

        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return response()->json(['message' => 'Token não fornecido.'], 401);
        }

        $token = $matches[1];

        if ($token !== $this->token) {
            return response()->json(['message' => 'Token inválido.'], 401);
        }

        return $next($request);
    }
}
