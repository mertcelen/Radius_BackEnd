<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ErrorController extends Controller
{
    public function main(){
        return response()->json([
            "codes" => [
                1 => "Missing token",
                2 => "Wrong token",
                3 => "Resource not found",
                4 => "False returned",
                5 => "True returned"
            ]
        ]);
    }
}
