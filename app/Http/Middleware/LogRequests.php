<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogRequests
{
    public function handle($request, Closure $next)
    {
        Log::info('API Request', [
            'method' => $request->method(),
            'url' => $request->url(),
            'params' => $request->all(),
        ]);

        return $next($request);
    }
}
