<?php

namespace App\Http\Middleware;

use Closure;

class SessionToToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->request->add(['userId' => \Auth::id()]);
        return $next($request);
    }
}
