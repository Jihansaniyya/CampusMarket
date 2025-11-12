<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBuyerStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->isBuyer()) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pembeli yang dapat mengakses fitur ini.',
                'error_code' => 'NOT_A_BUYER',
            ], 403);
        }

        return $next($request);
    }
}
