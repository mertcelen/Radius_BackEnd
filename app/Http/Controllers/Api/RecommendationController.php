<?php

namespace App\Http\Controllers\Api;

use App\Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RecommendationController extends Controller
{
    public static function main()
    {
        $images = \App\Image::where('userId', request('userId'))->where('enabled', true)->get()->toArray();
        $array = array();
        foreach ($images as $image) {
            array_push($array, $image);
        }
        return $images;
    }
}
