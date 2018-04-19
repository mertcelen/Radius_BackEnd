<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Image;
class MongoController extends Controller
{
    public function main(){
        $image = new Image();
        $image->imageId = 1;
        $image->userId = 2;
        $image->color = "asd";
        $image->labels = "qwe";
        $image->type = 1;
        $image->save();
        return $image;
    }
}
