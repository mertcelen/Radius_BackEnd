<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        if(DB::table('users')->select('status')->where('id',request('userId'))->value('status') != 3) {
          //Dirty check for if its coming from session or not
            if(Auth::check()){
                return redirect('/');
            }else{
                if(DB::table('users')->select('status')->where('secret',request('secret'))->value('status') == 3){
                    return $next($request);
                }else{
                    return response()->json([
                        'error' => [
                            "message" => 'Not allowed',
                            "code" => 4
                        ]
                    ]);
                }

            }
        }
        return $next($request);
    }
}
