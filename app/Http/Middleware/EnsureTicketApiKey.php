<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTicketApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $expectedApiKey = config('services.ticketing.api_key');
        $providedApiKey = $request->header('X-API-Key');

        if (
            ! is_string($expectedApiKey)
            || $expectedApiKey === ''
            || ! is_string($providedApiKey)
            || ! hash_equals($expectedApiKey, $providedApiKey)
        ) {
            return response()->json(
                ['message' => 'Forbidden.'],
                Response::HTTP_FORBIDDEN,
            );
        }

        return $next($request);
    }
}
