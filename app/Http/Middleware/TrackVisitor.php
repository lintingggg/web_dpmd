<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\VisitorLog;

class TrackVisitor
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only track GET requests on public pages
        if ($request->isMethod('GET') 
            && !$request->is('admin*') 
            && !$request->is('dashboard*') 
            && !$request->is('api*')
            && !$request->is('up')
        ) {
            $ip = $request->ip();
            $today = now()->toDateString();

            try {
                // Record unique visitor per day
                VisitorLog::firstOrCreate([
                    'ip_address' => $ip,
                    'visit_date' => $today,
                ]);
            } catch (\Exception $e) {
                logger()->error('Gagal mencatat data pengunjung: ' . $e->getMessage());
            }
        }

        return $next($request);
    }
}
