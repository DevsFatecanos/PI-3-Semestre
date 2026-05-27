<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompressResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (!function_exists('gzencode') || !$request->header('Accept-Encoding') || !str_contains($request->header('Accept-Encoding'), 'gzip')) {
            return $response;
        }

        if (strlen($response->getContent()) < 860) {
            return $response;
        }

        $response->setContent(gzencode($response->getContent(), 9));
        $response->headers->set('Content-Encoding', 'gzip');
        $response->headers->set('Content-Length', strlen($response->getContent()));

        return $response;
    }
}
