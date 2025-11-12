<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && !$request->user()->isEmailVerified()) {
            return response()->json([
                'success' => false,
                'message' => 'Email Anda belum diverifikasi.',
                'error_code' => 'EMAIL_NOT_VERIFIED',
            ], 403);
        }

        return $next($request);
    }
}
