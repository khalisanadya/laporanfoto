<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class DeviceIdentifier
{
    /**
     * Handle an incoming request.
     * Generate a unique device ID and store it in cookie for 1 year.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $deviceId = $request->cookie('device_id');

        if (!$deviceId) {
            $deviceId = Str::uuid()->toString();
        }

        // Share device_id to all views
        view()->share('deviceId', $deviceId);
        
        // Also store in request for controller access
        $request->merge(['device_id' => $deviceId]);

        $response = $next($request);

        // Always set/refresh cookie for 1 year (525600 minutes)
        $response->cookie('device_id', $deviceId, 525600, '/', null, false, false);

        return $response;
    }
}
