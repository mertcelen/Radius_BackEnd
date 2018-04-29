<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->status != 3) {
            //Dirty check for if its coming from session or not
            if (Auth::check()) {
                return redirect('/');
            } else {
                return response()->json([
                    'error' => [
                        "message" => 'Not allowed',
                        "code" => 4
                    ]
                ]);
            }

        }
        return $next($request);
    }
}
