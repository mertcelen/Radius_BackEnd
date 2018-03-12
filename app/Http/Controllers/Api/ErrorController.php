<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ErrorController extends Controller
{
    public function main(){
        return [
            "codes" => [
                1 => "Missing parameter(s)",
                2 => "Wrong parameter(s)",
                3 => "Resource not found",
                4 => "False",
                5 => "True",
                6 => "Expired"
            ]
        ];
    }
}
