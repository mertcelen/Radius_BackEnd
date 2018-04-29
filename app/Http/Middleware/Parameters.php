<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class Parameters
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,...$parameters)
    {
        //Checking all parameters if they exist at request.
        foreach ($parameters as $parameter) {
            if(!$request->has($parameter)){
                return response()->json([
                    "error" => [
                        "message" => "Missing parameter(s)",
                        "code" => 1
                    ],
                    "missing" => $parameter
                ]);
            }
        }
        if($request->has('secret')){
            //Checking if token is real.
            $userId = User::where('secret',request('secret'))->first()->_id;
            if( $userId == null){
                return response()->json([
                    "error" => [
                        "message" => "Wrong parameter(s)",
                        "code" => 2
                    ]
                ]);
            }
            //Now that we have confirmed token and received id, we can now add to the request so that controllers can access without requiring more database calls.
            $request->request->add(['userId' => $userId]);
        }
        return $next($request);
    }
}
