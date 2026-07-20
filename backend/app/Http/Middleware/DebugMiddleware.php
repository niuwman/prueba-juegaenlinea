<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DebugMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Log de la petición
        Log::channel('daily')->info('=== REQUEST RECIBIDA ===', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'headers' => $request->headers->all(),
            'ip' => $request->ip(),
            'content' => $request->getContent(),
        ]);

        $response = $next($request);

        // Log de la respuesta
        Log::channel('daily')->info('=== RESPONSE ENVIADA ===', [
            'status' => $response->getStatusCode(),
            'headers' => $response->headers->all(),
            'content' => $response->getContent(),
        ]);

        return $response;
    }
}