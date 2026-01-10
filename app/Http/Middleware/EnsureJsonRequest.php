<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

class EnsureJsonRequest
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isJson()) {
            return $next($request);
        }

        throw new UnsupportedMediaTypeHttpException('JSON is expected.');
    }
}
