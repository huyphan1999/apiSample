<?php
namespace App\Http\Middleware;

use Closure;

class DemoMiddleware2
{
    public function handle($request,Closure $next)
    {
        return $next($request);
    }
}