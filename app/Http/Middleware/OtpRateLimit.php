<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class OtpRateLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $identifier = $request->input('identifier');
        $type = $request->input('type');
        
        if (!$identifier || !$type) {
            return $next($request);
        }

        $key = "otp_rate_limit:{$type}:{$identifier}";
        $attempts = Cache::get($key, 0);
        
        // Allow max 3 OTP requests per 15 minutes
        if ($attempts >= 3) {
            return response()->json([
                'error' => 'Too many OTP requests. Please wait 15 minutes before trying again.'
            ], 429);
        }
        
        // Increment attempts
        Cache::put($key, $attempts + 1, now()->addMinutes(15));
        
        return $next($request);
    }
}
