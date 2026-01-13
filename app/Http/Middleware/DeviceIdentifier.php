<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DeviceIdentifier
{
    /**
     * Handle an incoming request.
     * Read my_reports cookie containing report IDs created from this browser.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get report IDs from cookie (stored as comma-separated string)
        $myReportsCookie = $request->cookie('my_reports');
        $myReportIds = $myReportsCookie ? explode(',', $myReportsCookie) : [];
        
        // Share to views and request
        view()->share('myReportIds', $myReportIds);
        $request->merge(['my_report_ids' => $myReportIds]);

        return $next($request);
    }
}
