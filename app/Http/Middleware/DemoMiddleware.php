<?php
namespace App\Http\Middleware;

use Closure;

class DemoMiddleware
{
    public function handle($request,Closure $next)
    {
        if($request->input('age')>20)
        {
            return $next($request);
        }
        return phpinfo();
    }
}