<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class Token
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
        //Checking if secret exist in api call.
        if(!$request->has('secret')){
            return response()->json([
               "error" => [
                   "message" => "Missing token",
                   "code" => 1
               ]
            ]);
        }
        //Checking if token is real.
        $dummy = DB::table('users')->where('secret',$request->get('secret'))->first();
        if($dummy == null){
            return response()->json([
                "error" => [
                    "message" => "Wrong token",
                    "code" => 2
                ]
            ]);
        }
        return $next($request);
    }
}
