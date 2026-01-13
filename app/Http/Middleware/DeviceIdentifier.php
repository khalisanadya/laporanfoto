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
        
        // Get BAP IDs from cookie
        $myBapsCookie = $request->cookie('my_baps');
        $myBapIds = $myBapsCookie ? explode(',', $myBapsCookie) : [];
        
        // Share to views and request
        view()->share('myReportIds', $myReportIds);
        view()->share('myBapIds', $myBapIds);
        $request->merge([
            'my_report_ids' => $myReportIds,
            'my_bap_ids' => $myBapIds,
        ]);

        return $next($request);
    }
}
